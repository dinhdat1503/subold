<?php

namespace App\Http\Controllers\Admin\Services;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceSocial;
use App\Models\SocialService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function services()
    {
        $social = SocialService::get();
        return view('admin.service.services-manage', compact("social"));
    }
    public function servicesData(Request $request)
    {
        $validated = $request->validate(
            [
                'start' => 'required|numeric|min:0',
                'length' => 'required|numeric|min:1|max:10000',
                'search.value' => 'nullable|string',
                'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
                'order.0.dir' => 'nullable|string|in:asc,desc',
            ]
        );
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $columns = [
            'id',
            'image',
            'name',
            'social_name',
            'slug',
            'status',
            'id',
        ];
        $selectColumns = collect($columns)->filter(function ($column) {
            return $column !== 'social_name';
        })->merge(['social_id'])->unique()->values()->all();
        $query = Service::with('social:id,name')->select($selectColumns);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('slug', 'like', "%$search%");
            });
        }

        $total = $query->count();
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'social_name') {
                $query->join('social_services', 'services.social_id', '=', 'social_services.id')
                    ->orderBy('social_services.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'services.' . $col;
                    }, $selectColumns));
            } else {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data = $data->map(function ($service) {
            $service->social_name = $service->social->name ?? 'N/A';
            unset($service->social);
            unset($service->social_id);
            return $service;
        });
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function servicesStore(Request $request)
    {
        $request->validate([
            'social_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'note' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = BackendHelper::uploadPublicFile(
            $request->file('image'),
            'assets/images/client/services',
            null,
            'service' // prefix
        );

        $validated['image'] = $imagePath;
        $validated['status'] = isset($validated['status']);

        $services = Service::create($validated);

        UserHelper::logWrite('AdminService', 'Thêm Services - ' . $services->name);
        return redirect()->back()->with('success', 'Thêm Dịch Vụ thành công!');
    }
    public function servicesEdit($id, Request $request)
    {
        $service = Service::findOrFail($id);
        $social = SocialService::get();
        return view("admin.service.services-edit", compact("social", "service"));
    }
    public function servicesUpdate($id, Request $request)
    {
        $validated = $request->validate([
            'social_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug, ' . $id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'note' => 'required|string',
            'status' => 'nullable|boolean',
        ]);
        $service = Service::findOrFail($id);
        if (isset($validated['image'])) {
            $imagePath = BackendHelper::uploadPublicFile(
                $validated['image'],
                'assets/images/client/services',
                $service->image,
                'service' // prefix
            );
            $validated['image'] = $imagePath;
        }
        $validated['status'] = isset($validated['status']);
        $service->update($validated);
        UserHelper::logWrite("AdminService", "Cập nhật Services ID {$id} - {$service->name}");
        return back()->with('success', 'Cập nhật Dịch Vụ thành công!');

    }
    public function servicesDestroy($id)
    {
        $service = Service::findOrFail($id);
        UserHelper::logWrite("AdminService", "Xoá Services $id - " . $service->name);
        $service->delete();
        return BackendHelper::resApi("success", "Xoá Services Thành Công");
    }
}
