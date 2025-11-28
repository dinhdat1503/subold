<?php

namespace App\Http\Controllers\Client;

use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\SocialService;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function price()
    {
        $socials = SocialService::where('status', true)
            ->with([
                'services' => function ($query) {
                    $query->where('status', true);
                    $query->with(['servers']);
                }
            ])
            ->get();
        return view("client.service.price", compact("socials"));
    }
    public function orders()
    {
        $socials = SocialService::with('services')->orderBy('slug')->get();
        return view("client.service.orders", compact('socials'));
    }
    public function ordersData($type, Request $request)
    {
        $request->merge(['type' => $type]);
        $validated = $request->validate([
            'type' => ['required', 'numeric'],
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string', // Giới hạn độ dài search
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'], // Giới hạn số cột
            'order.0.dir' => 'nullable|string|in:asc,desc', // Chỉ asc hoặc desc
            'filterDate' => 'nullable|string|max:100', // "2023-01-01 to 2023-01-31"
            'filterStatus' => 'nullable|string', // Hoặc 'integer'
            'filterService' => 'nullable|numeric', // Hoặc 'integer'
        ]);
        $type = $validated['type'];
        if ($type != 0) {
            $columns = ['id', 'id', 'id', 'status', 'order_link', 'payment', 'count_start', 'order_info', 'note', 'created_at'];
        } else {
            $columns = ['id', 'id', 'id', 'status', 'social_name', 'order_link', 'payment', 'count_start', 'order_info', 'note', 'created_at'];
        }
        $selectColumns = collect($columns)
            ->filter(fn($col) => $col !== 'social_name')
            ->merge(['server_id', 'quantity', 'count_buff'])
            ->unique()->values()->all();
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $query = Order::with([
            'server:id,service_id,server,price',
            'server.service:id,social_id,name',
            'server.service.social:id,name'
        ])->select($selectColumns)->where('user_id', Auth::id());
        // filter theo service_id
        if ($type != 0) {
            $query->whereHas('server', function ($q) use ($type) {
                $q->where('service_id', $type);
            });
        }
        // search theo id, service_name, order_link
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('order_link', 'like', "%$search%");
            });
        }
        // Nếu có lọc theo khoảng ngày
        if (!empty($validated['filterDate'])) {
            $range = preg_split('/\s(to|đến)\s/', $validated['filterDate']);
            if (count($range) === 2) {
                $startDate = trim($range[0]);
                $endDate = trim($range[1]);
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }
        if (!empty($validated['filterStatus'])) {
            $query->where('status', $validated['filterStatus']);
        }
        if (!empty($validated['filterService'])) {
            $filterService = $validated['filterService'];
            $query->whereHas('server', function ($q) use ($filterService) {
                $q->where('service_id', $filterService);
            });
        }

        $total = $query->count();

        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'social_name') {
                $query
                    ->join('server_services', 'orders.server_id', '=', 'server_services.id')
                    ->join('services', 'server_services.service_id', '=', 'services.id')
                    ->join('social_services', 'services.social_id', '=', 'social_services.id')
                    ->orderBy('social_services.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'orders.' . $col;
                    }, $selectColumns));
            } elseif ($columnName) {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data = $data->map(function ($order) use ($type) {
            $server_service = $order->server;
            $order->serverService = [
                "index" => $server_service->server ?? 0,
                "price" => $server_service->price ?? 0,
            ];
            if ($type == 0) {
                $order->social_name = $server_service->service->social->name ?? 'N/A';
                $order->service_name = $server_service->service->name ?? 'N/A';
            }
            unset($order->server);
            return $order;
        });
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function view($socialSlug, $serviceSlug, Request $request)
    {
        $social = SocialService::select("name")->where('slug', $socialSlug)
            ->where('status', true)
            ->firstOrFail();

        $service = Service::select("id", "name", "note")->where('slug', $serviceSlug)
            ->where('status', true)
            ->firstOrFail();

        $server = ServerService::select(["id", "server", "title", "price", "action_order"])->where("service_id", $service->id)->where("status", true)->whereNotNull('server')->get();
        $reactionsVar = ['like', 'love', 'haha', 'wow', 'sad', 'angry', 'care'];

        return view('client.service.view', compact("social", "service", "server", "reactionsVar"));
    }
    public function orderProcess(Request $request)
    {
        // Lấy danh sách link từ request
        $links = $request->input('links');

        // Nếu không có hoặc không phải mảng → báo lỗi
        if (empty($links)) {
            return BackendHelper::resApi("error", "Không có link");

        }

        return BackendHelper::resApi("success", "Hành động không xác định", [
            'data' => [
                'success' => [
                    [
                        'id' => 1001,
                        'link' => 'https://facebook.com/user_A'
                    ],
                    [
                        'id' => 1002,
                        'link' => 'https://tiktok.com/@clip_B'
                    ]
                ],
                'error' => [
                    [
                        'id' => 1003,
                        'link' => 'https://instagram.com/photo_C',
                        'reason' => 'Đơn hàng đã đạt trạng thái Hoàn thành.'
                    ],
                    [
                        'id' => 1004,
                        'link' => 'https://youtube.com/watch?v=D',
                        'reason' => 'Link không hợp lệ hoặc đã bị khóa.'
                    ]
                ]
            ]
        ]);

    }
    public function orderUpdate($type, Request $request)
    {
        switch ($type) {
            case 'refund':
                return BackendHelper::resApi("success", "Hành động không xác định", [
                    'data' => [
                        'success' => [
                            [
                                'id' => 1001,
                                'link' => 'https://facebook.com/user_A'
                            ],
                            [
                                'id' => 1002,
                                'link' => 'https://tiktok.com/@clip_B'
                            ]
                        ],
                        'error' => [
                            [
                                'id' => 1003,
                                'link' => 'https://instagram.com/photo_C',
                                'reason' => 'Đơn hàng đã đạt trạng thái Hoàn thành.'
                            ],
                            [
                                'id' => 1004,
                                'link' => 'https://youtube.com/watch?v=D',
                                'reason' => 'Link không hợp lệ hoặc đã bị khóa.'
                            ]
                        ]
                    ]
                ]);
            case 'warranty':
                return BackendHelper::resApi("success", "Hành động không xác định", [
                    'data' => [
                        'success' => [
                            [
                                'id' => 1001,
                                'link' => 'https://facebook.com/user_A'
                            ],
                            [
                                'id' => 1002,
                                'link' => 'https://tiktok.com/@clip_B'
                            ]
                        ],
                        'error' => [
                            [
                                'id' => 1003,
                                'link' => 'https://instagram.com/photo_C',
                                'reason' => 'Đơn hàng đã đạt trạng thái Hoàn thành.'
                            ],
                            [
                                'id' => 1004,
                                'link' => 'https://youtube.com/watch?v=D',
                                'reason' => 'Link không hợp lệ hoặc đã bị khóa.'
                            ]
                        ]
                    ]
                ]);
            default:
                return BackendHelper::resApi("error", "Hành động không xác định");
        }
    }
    public function orderInfo($id, Request $request)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        $key = "user-attempt-error-" . Auth::id();
        if (!$order) {
            Cache::increment($key);
            return BackendHelper::resApi("error", "Không tìm thấy đơn hàng");
        }
        $data = [
            'id' => $order->id,
            'order_link' => $order->order_link,
            'quantity' => $order->quantity,
            'start' => $order->count_start,
            'buff' => $order->count_buff,
            'payment' => $order->payment,
            'logs' => $order->logs ?? [], // đã cast sang array
        ];
        Cache::forget($key);
        return BackendHelper::resApi("success", "Lấy thông tin đơn hàng thành công", $data);
    }
    public function serverInfo($id, Request $request)
    {
        $server = ServerService::find($id);
        $key = "user-attempt-error-" . Auth::user()->id;
        if (!$server) {
            Cache::increment($key);
            return BackendHelper::resApi("error", "Không tìm thấy dịch vụ");
        }
        $orders = Order::where("server_id", $id)
            ->whereBetween("created_at", [Carbon::now()->subDays(3), Carbon::now()])
            ->get();

        if ($orders->isEmpty()) {
            $averageStartDuration = "Không có dữ liệu";
            $averageEndDuration = "Không có dữ liệu";
        } else {
            $averageStartSeconds = $orders->avg(function ($order) {
                $created = Carbon::parse($order->created_at);
                $started = Carbon::parse($order->time_start);
                return $created->diffInSeconds($started);
            });
            $averageStartDuration = CarbonInterval::seconds($averageStartSeconds)->cascade()->forHumans();
            $averageEndSeconds = $orders->avg(function ($order) {
                $created = Carbon::parse($order->created_at);
                $ended = Carbon::parse($order->time_end);
                return $created->diffInSeconds($ended);
            });
            $averageEndDuration = CarbonInterval::seconds($averageEndSeconds)->cascade()->forHumans();
        }

        $checkPrices = function ($action) {
            $serverData = json_decode($action['server'] ?? null, true);
            if (!empty($serverData)) {
                foreach ($serverData as $index => $serverID) {
                    if ($serverID != 0) {
                        $serverPrice = ServerService::where('id', $serverID)->value('price');
                        $serverData[$index] = priceServer($serverPrice, Auth::user()->level, null);
                    } else {
                        $serverData[$index] = 0;
                    }
                }
                $action['server'] = $serverData;
            }
            return $action;
        };
        Cache::forget($key);
        return BackendHelper::resApi("success", "Lấy thông tin thành công", [
            "id" => $server->id,
            "description" => $server->description,
            "min" => $server->min,
            "max" => $server->max,
            "reaction" => $checkPrices($server->action_reaction),
            "comment" => $server->action_comment,
            "time" => $checkPrices($server->action_time),
            "amount" => $checkPrices($server->action_amount),
            "server_action" => $server->action_order,
            "order_recent_count" => $orders->count(),
            "order_recent_start" => $averageStartDuration,
            "order_recent_end" => $averageEndDuration,
        ]);
    }
}
