<?php

namespace App\Http\Controllers\Admin\Services;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\SocialService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders()
    {
        $socials = SocialService::with('services')->orderBy('slug')->get();
        return view("admin.history.orders", compact('socials'));
    }
    public function ordersData(Request $request)
    {
        $validated = $request->validate(
            [
                'start' => 'required|numeric|min:0',
                'length' => 'required|numeric|min:1|max:10000',
                'search.value' => 'nullable|string',
                'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
                'order.0.dir' => 'nullable|string|in:asc,desc',
                'filterDate' => 'nullable|string|max:100',
                'filterStatus' => 'nullable|string|max:25',
                'filterService' => 'nullable|numeric',
                'filterID' => 'nullable|numeric',
                'filterApiID' => 'nullable|string|max:50',
            ]
        );
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $columns = [
            'id',
            'id',
            'supplier_name',
            'username',
            'status',
            'social_name',
            'order_link',
            'payment',
            'count_start',
            'order_info',
            'note',
            'created_at',
            'time_start',
        ];
        $selectColumns = collect($columns)->filter(function ($column) {
            return $column !== 'supplier_name' && $column !== 'username' && $column !== 'social_name';
        })->merge(['server_id', 'user_id', 'supplier_id', 'supplier_order_id', 'time_end', 'count_buff', 'quantity', 'payment_real'])->unique()->values()->all();
        $query = Order::with([
            'server:id,service_id,server,price',
            'server.service:id,social_id,name',
            'server.service.social:id,name',
            'supplier:id,name',
            'user:id,username',
        ])->select($selectColumns);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('username', "like", "%$search%");
                })->orWhere('order_link', 'like', "%$search%");
            });
        }
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
            $query->whereHas('server.service', function ($q) use ($validated) {
                $q->where('id', $validated['filterService']);
            });
        }
        if (!empty($validated['filterID'])) {
            $query->where('id', $validated['filterID']);
        }
        if (!empty($validated['filterApiID'])) {
            $query->where('supplier_order_id', $validated['filterApiID']);
        }
        $total = $query->count();
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'supplier_name') {
                $query
                    ->join('suppliers', 'orders.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'orders.' . $col;
                    }, $selectColumns));
            } else if ($columnName === 'username') {
                $query
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->orderBy('users.username', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'orders.' . $col;
                    }, $selectColumns));
            } else if ($columnName === 'social_name') {
                $query
                    ->join('server_services', 'orders.server_id', '=', 'server_services.id')
                    ->join('services', 'server_services.service_id', '=', 'services.id')
                    ->join('social_services', 'services.social_id', '=', 'social_services.id')
                    ->orderBy('social_services.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'orders.' . $col;
                    }, $selectColumns));
            } else {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data->transform(function ($item) {
            $item->supplier_name = $item->supplier->name ?? 'N/A';
            $item->username = $item->user->username ?? 'N/A';

            $item->serverService = [
                "index" => $item->server->server ?? 0,
                "price" => $item->server->price ?? 0,
            ];
            $item->social_name = $item->server->service->social->name ?? 'N/A';
            $item->service_name = $item->server->service->name ?? 'N/A';
            unset($item->supplier);
            unset($item->user);
            unset($item->server);
            unset($item->server_id);
            unset($item->user_id);
            unset($item->supplier_id);
            return $item;
        });
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function orderInfo($id)
    {
        $order = Order::findOrFail($id);
        $data = [
            'id' => $order->id,
            'order_link' => $order->order_link,
            'quantity' => $order->quantity,
            'start' => $order->count_start,
            'buff' => $order->count_buff,
            'payment' => $order->payment,
            'logs' => $order->logs ?? [],
        ];
        return BackendHelper::resApi("success", "Lấy thông tin đơn hàng thành công", $data);

    }
    public function orderUpdate($type, $id, Request $request)
    {
        $order = Order::findOrFail($id);
        $user = \App\Models\User::where('id', $order->user_id)->firstOrFail();
        switch ($type) {
            case 'refund': {
                $validated = $request->validate([
                    'refund_amount' => 'nullable|numeric|min:0',
                ]);

                if (in_array($order->status, ['Completed', 'Refunded', 'Cancelled'])) {
                    return BackendHelper::resApi("error", "Đơn hàng đã hoàn tất hoặc đã huỷ, không thể huỷ thêm");
                }

                $refundAmount = round(floatval($validated['refund_amount'] ?? 0), 2);
                if ($refundAmount <= 0 || $refundAmount >= $order->payment) {
                    $refundAmount = $order->payment;
                }

                \DB::transaction(function () use ($order, $user, $refundAmount) {
                    $refundPromotion = 0;
                    $marginProfit = round($order->payment_real / $order->profit, 8);

                    $paymentPromotion = round($order->payment - $order->payment_real, 2);
                    $finalPayment = round($order->payment - $refundAmount, 2);
                    if ($finalPayment <= $paymentPromotion) {
                        $refundPromotion = round($paymentPromotion - $finalPayment, 2);
                        $refundPromotion = round($refundPromotion / 2, 2);
                        $order->payment = max(0, $finalPayment);
                        $order->payment_real = 0;
                        $order->profit = -$order->payment;
                    } else {
                        $order->payment = max(0, $finalPayment);
                        $order->payment_real = max(0, round($order->payment_real - $refundAmount, 2));
                        $order->profit -= round($refundAmount / $marginProfit, 2);
                    }
                    $order->status = "Refunded";
                    $logs = $order->logs ?? [];
                    $logs[] = [
                        'status' => "primary",
                        'title' => "Trạng thái: Hoàn tiền",
                        'time' => now()->format('H:i d/m/Y'),
                    ];
                    $order->logs = $logs;
                    $order->save();
                    $user->increment('balance', $refundAmount);
                    $user->increment('promotion_recharge', $refundPromotion);

                    UserHelper::logWrite("AdminService", "Hoàn Tiền Đơn Hàng - {$order->id}");
                    UserHelper::logWrite("Balance", "Admin Hoàn Tiền Đơn Hàng", $refundAmount, $user);
                });
                return BackendHelper::resApi("success", "Hoàn Tiền Đơn Hàng Thành Công !");

            }
            case 'status': {
                $newStatus = $request->status ?? null;
                $allowed = ['Pending', 'Active', 'Completed', 'Error', 'Refunded', 'Cancelled', 'Warranty', 'WaitingForRefund'];

                if (!$newStatus || !in_array($newStatus, $allowed)) {
                    return BackendHelper::resApi("error", "Trạng thái không hợp lệ");
                }

                $statusMap = [
                    'WaitingForRefund' => ['label' => 'Đang huỷ', 'color' => 'warning'],
                    'Pending' => ['label' => 'Chờ xử lý', 'color' => 'warning'],
                    'Active' => ['label' => 'Đang hoạt động', 'color' => 'info'],
                    'Error' => ['label' => 'Lỗi đơn', 'color' => 'danger'],
                    'Warranty' => ['label' => 'Bảo hành', 'color' => 'secondary'],
                    'Completed' => ['label' => 'Hoàn thành', 'color' => 'success'],
                    'Refunded' => ['label' => 'Hoàn tiền', 'color' => 'primary'],
                    'Cancelled' => ['label' => 'Đã hủy', 'color' => 'danger'],
                ];

                $color = $statusMap[$newStatus]['color'] ?? 'warning';
                $label = $statusMap[$newStatus]['label'] ?? 'Không xác định';

                $logs = $order->logs ?? [];
                $logs[] = [
                    'status' => $color,
                    'title' => "Trạng thái: {$label}",
                    'time' => now()->format('H:i d/m/Y'),
                ];

                $order->logs = $logs;
                $order->status = $newStatus;
                $order->save();

                UserHelper::logWrite("AdminService", "Thay Đổi Trạng Thái Đơn Hàng - {$order->id} Thành - {$label}");

                return BackendHelper::resApi("success", "Cập nhật trạng thái đơn hàng thành công");
            }

            default:
                return BackendHelper::resApi("error", "Hành động không xác định");
        }
    }
}