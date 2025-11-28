<?php

namespace App\Http\Controllers\Admin\Services;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\ServiceSocial;
use App\Models\SocialService;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function social()
    {
        return view('admin.service.social-manage');
    }
    public function socialData(Request $request)
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
        $columns = [
            'id',
            'name',
            'slug',
            'image',
            'status',
            'id',
        ];

        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $selectColumns = collect($columns)->values()->unique()->all();
        $query = SocialService::select($selectColumns);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('slug', 'like', "%$search%");
            });
        }
        $total = $query->count();
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $query->orderBy($columnName, $order['dir'] ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function socialStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:social_services,slug',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = BackendHelper::uploadPublicFile(
            $validated['image'],
            'assets/images/client/services',
            null,
            'social' // prefix
        );

        $validated['image'] = $imagePath;
        $validated['status'] = isset($validated['status']);
        $social = SocialService::create($validated);

        UserHelper::logWrite('AdminService', 'Thêm Social - ' . $social->name);
        return redirect()->back()->with('success', 'Thêm dịch vụ MXH thành công!');
    }
    public function socialUpdate($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:social_services,slug,' . $id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);
        $social = SocialService::findOrFail($id);
        if (isset($validated['image'])) {
            $imagePath = BackendHelper::uploadPublicFile(
                $validated['image'],
                'assets/images/client/services',
                null,
                'social' // prefix
            );
            $validated['image'] = $imagePath;
        }
        $validated['status'] = $validated['status'] ?? false;
        $social->update($validated);
        UserHelper::logWrite("AdminService", "Cập nhật Social ID {$id} - {$social->name}");
        return back()->with('success', 'Cập nhật dịch vụ MXH thành công!');

    }
    public function socialEdit($id, Request $request)
    {
        $social = SocialService::findOrFail($id);
        return view("admin.service.social-edit", compact("social"));
    }
    public function socialDestroy($id)
    {
        $social = SocialService::findOrFail($id);
        UserHelper::logWrite("AdminService", "Xoá Social $id - " . $social->name);
        $social->delete();
        return BackendHelper::resApi("success", "Xoá Social Thành Công");
    }
}
