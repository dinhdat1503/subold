<?php

namespace App\Http\Controllers\Admin\Services;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\ServerService;
use App\Models\ServerSupplier;
use App\Models\Service;
use App\Models\SocialService;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function server()
    {
        $socials = SocialService::with([
            'services' => function ($query) {
                $query->select('id', 'social_id', 'name')->orderBy('name', 'asc');
            }
        ])->get();
        $suppliers = Supplier::orderBy('name')->with(['servers:id,supplier_id', 'servers.serverSupplier:id,server_id,service'])->get();
        $suppliers->each(function ($supplier) {
            $allServerSuppliers = $supplier->servers->pluck('serverSupplier')->flatten();
            $services = $allServerSuppliers->pluck('service');
            $supplier->unique_sorted_services = $services->unique()->sort()->values();
        });
        return view('admin.service.server-manage', compact('socials', "suppliers"));
    }
    public function serverData(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'order.0.dir' => 'nullable|string|in:asc,desc',
            'filterService' => 'nullable|integer',
            'filterServiceApi' => 'nullable|string',
            'filterStatus' => 'nullable|integer',
        ]);
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $columns = [
            'id',
            'id',
            'title',
            'social_name',
            'server',
            'price',
            'min',
            'status',
            'supplier_name',
            'created_at',
        ];
        $selectColumns = collect($columns)->filter(function ($column) {
            return $column !== 'social_name' && $column !== 'supplier_name';
        })->merge(['service_id', 'supplier_id', 'max'])->values()->unique()->all();

        $query = ServerService::with(['supplier:id,name', 'serverSupplier:id,server_id,service', 'service:id,name,social_id', 'service.social:id,name,image'])->select($selectColumns);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%");
            });
        }
        if (isset($validated['filterService'])) {
            if ($validated['filterService'] == -1) {
                $query->where('service_id', '!=', null);
            } else if ($validated['filterService'] == 0) {
                $query->where('service_id', null);
            } else {
                $query->where('service_id', $validated['filterService']);
            }
        }
        if (!empty($validated['filterServiceApi'])) {
            [$providerName, $serviceName] = explode('||---||', $validated['filterServiceApi']);
            $query->whereHas('serverSupplier', function ($q) use ($serviceName) {
                $q->where('service', $serviceName);
            });
            $query->whereHas('supplier', function ($q) use ($providerName) {
                $q->where('name', $providerName);
            });
        }
        if (isset($validated['filterStatus'])) {
            $query->where('status', $validated['filterStatus']);
        }
        $total = $query->count();

        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'supplier_name') {
                $query
                    ->join('suppliers', 'server_services.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'server_services.' . $col;
                    }, $selectColumns));
            } else if ($columnName === 'social_name') {
                $query
                    ->join('services', 'server_services.service_id', '=', 'services.id')
                    ->join('social_services', 'services.social_id', '=', 'social_services.id')
                    ->orderBy('social_services.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'server_services.' . $col;
                    }, $selectColumns));
            } else {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data->each(function ($item) {
            $item->social_name = $item->service->social->name ?? '';
            $item->social_image = $item->service->social->image ?? '';
            $item->service_name = $item->service->name ?? '';
            $item->supplier_name = $item->supplier->name ?? '';
            $item->supplier_service = $item->serverSupplier->service ?? '';
        });
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function serverEdit($id, Request $request)
    {
        $server = ServerService::with('serverSupplier')->findOrFail($id);
        $socials = SocialService::select(['id', 'name'])->with('services:id,social_id,name')->get();
        $suppliers = Supplier::orderBy('name')->select(['id', 'name'])->get();
        $flags = [
            ['value' => 'gb', 'name' => 'United Kingdom'],
            ['value' => 'de', 'name' => 'Đức'],
            ['value' => 'fr', 'name' => 'Pháp'],
            ['value' => 'it', 'name' => 'Ý'],
            ['value' => 'es', 'name' => 'Tây Ban Nha'],
            ['value' => 'nl', 'name' => 'Hà Lan'],
            ['value' => 'ch', 'name' => 'Thuỵ Sĩ'],
            ['value' => 'se', 'name' => 'Thuỵ Điển'],
            // === Toàn bộ Châu Á ===
            ['value' => 'vn', 'name' => 'Việt Nam'],
            ['value' => 'cn', 'name' => 'Trung Quốc'],
            ['value' => 'jp', 'name' => 'Nhật Bản'],
            ['value' => 'kr', 'name' => 'Hàn Quốc'],
            ['value' => 'in', 'name' => 'Ấn Độ'],
            ['value' => 'id', 'name' => 'Indonesia'],
            ['value' => 'th', 'name' => 'Thái Lan'],
            ['value' => 'my', 'name' => 'Malaysia'],
            ['value' => 'sg', 'name' => 'Singapore'],
            ['value' => 'ph', 'name' => 'Philippines'],
            ['value' => 'hk', 'name' => 'Hồng Kông'],
            ['value' => 'tw', 'name' => 'Đài Loan'],
            ['value' => 'mo', 'name' => 'Ma Cao'],
            ['value' => 'af', 'name' => 'Afghanistan'],
            ['value' => 'am', 'name' => 'Armenia'],
            ['value' => 'az', 'name' => 'Azerbaijan'],
            ['value' => 'bh', 'name' => 'Bahrain'],
            ['value' => 'bd', 'name' => 'Bangladesh'],
            ['value' => 'bt', 'name' => 'Bhutan'],
            ['value' => 'bn', 'name' => 'Brunei'],
            ['value' => 'kh', 'name' => 'Campuchia'],
            ['value' => 'cy', 'name' => 'Síp'],
            ['value' => 'ge', 'name' => 'Gruzia'],
            ['value' => 'ir', 'name' => 'Iran'],
            ['value' => 'iq', 'name' => 'Iraq'],
            ['value' => 'il', 'name' => 'Israel'],
            ['value' => 'jo', 'name' => 'Jordan'],
            ['value' => 'kz', 'name' => 'Kazakhstan'],
            ['value' => 'kp', 'name' => 'Triều Tiên'],
            ['value' => 'kw', 'name' => 'Kuwait'],
            ['value' => 'kg', 'name' => 'Kyrgyzstan'],
            ['value' => 'la', 'name' => 'Lào'],
            ['value' => 'lb', 'name' => 'Liban'],
            ['value' => 'mv', 'name' => 'Maldives'],
            ['value' => 'mn', 'name' => 'Mông Cổ'],
            ['value' => 'mm', 'name' => 'Myanmar'],
            ['value' => 'np', 'name' => 'Nepal'],
            ['value' => 'om', 'name' => 'Oman'],
            ['value' => 'pk', 'name' => 'Pakistan'],
            ['value' => 'ps', 'name' => 'Palestine'],
            ['value' => 'qa', 'name' => 'Qatar'],
            ['value' => 'sa', 'name' => 'Ả Rập Xê Út'],
            ['value' => 'lk', 'name' => 'Sri Lanka'],
            ['value' => 'sy', 'name' => 'Syria'],
            ['value' => 'tj', 'name' => 'Tajikistan'],
            ['value' => 'th', 'name' => 'Thái Lan'],
            ['value' => 'tl', 'name' => 'Timor-Leste'],
            ['value' => 'tr', 'name' => 'Thổ Nhĩ Kỳ'],
            ['value' => 'tm', 'name' => 'Turkmenistan'],
            ['value' => 'ae', 'name' => 'UAE'],
            ['value' => 'uz', 'name' => 'Uzbekistan'],
            ['value' => 'ye', 'name' => 'Yemen'],
        ];
        $reaction = [
            'like' => '👍 Like',
            'love' => '❤️ Love',
            'haha' => '😂 Haha',
            'wow' => '😮 Wow',
            'sad' => '😢 Sad',
            'angry' => '😡 Angry',
            'care' => '🤗 Care',
        ];
        return view("admin.service.server-edit", compact("server", "socials", "flags", "reaction", "suppliers"));
    }
    public function serverEditFetch(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'value' => 'required|string',
        ]);
        $val = $validated['value'];
        $status = "success";
        $mes = "Lấy Dữ Liệu Thành Công";
        switch ($validated['type']) {
            case 'SupplierName':
                $data = ServerSupplier::whereHas('server.supplier', function ($query) use ($val) {
                    $query->where('id', $val);
                })->select("service")
                    ->distinct()
                    ->orderBy('service')
                    ->get();
                break;
            case 'SupplierService':
                $data = ServerSupplier::select(['server_id'])->where('service', $val)->with("server:id,title")->get();
                break;
            case 'SupplierServer':
                $data = ServerService::select(['id', 'price', 'min', 'max', 'description', 'status'])->where('id', $val)->first();
                break;
            case 'SupplierFull':
                $data = ServerService::select(['id', 'supplier_id'])->with("serverSupplier:id,server_id,service")->where('id', $val)->first();
                break;
            default:
                break;
        }
        if (empty($data)) {
            $status = "error";
            $mes = "Không có dữ liệu !";
        }
        return BackendHelper::resApi($status, $mes, ['data' => $data ?? null]);
    }
    public function serverUpdate($id, Request $request)
    {
        $server = ServerService::with('serverSupplier')->findOrFail($id);
        $validated = $request->validate([
            'service_id' => 'nullable|integer',
            'server' => [
                'nullable',
                'integer',
                'max:100',
                \Illuminate\Validation\Rule::unique('server_services', 'server')
                    ->where('service_id', $request->service_id)->ignore($id),
            ],
            'flag' => 'nullable|string|max:5',
            'price' => 'required|decimal:0,2|min:0',
            'min' => 'required|integer|min:0',
            'max' => 'required|integer|min:0',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'action_reaction' => 'required|array|max:255',
            'action_time' => 'required|array|max:255',
            'action_comment' => 'nullable|array|max:255',
            'action_amount' => 'required|array|max:255',
            'action_order' => 'nullable|array|max:255',
        ]);
        $validated['status'] = isset($validated['status']);

        $validated['action_reaction']['status'] = isset($validated['action_reaction']['status']);

        $validated['action_time']['status'] = isset($validated['action_time']['status']);

        $validated['action_comment']['status'] = isset($validated['action_comment']['status']);

        $validated['action_amount']['status'] = isset($validated['action_amount']['status']);

        $validated['action_order']['multi_link'] = isset($validated['action_order']) && isset($validated['action_order']['multi_link']);
        $validated['action_order']['refund'] = isset($validated['action_order']) && isset($validated['action_order']['refund']);
        $validated['action_order']['warranty'] = isset($validated['action_order']) && isset($validated['action_order']['warranty']);

        $server->update($validated);

        UserHelper::logWrite("AdminService", "Cập nhật Server #{$server->id} - {$server->title}");
        return redirect()->back()->with('success', 'Cập nhật máy chủ thành công!');
    }
    public function serverDestroy($id)
    {
        $server = ServerService::findOrFail($id);
        UserHelper::logWrite("AdminService", "Xoá Server $id - " . $server->title);
        $server->delete();
        return BackendHelper::resApi("success", "Xoá Server Thành Công");
    }
}
