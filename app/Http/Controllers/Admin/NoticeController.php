<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function notification()
    {
        return view('admin.notice.notification');
    }
    public function notificationUpdate(Request $request)
    {
        $validated = $request->validate([
            'notice_modal' => 'nullable|string',
            'notice_main' => 'nullable|string',
        ]);
        foreach ($validated as $key => $value) {
            \App\Models\SiteSetting::setValue($key, $value ?? '');
        }
        UserHelper::logWrite("AdminNotification", "Cập Nhật Thông Báo Mới");
        return redirect()->back()->with('success', 'Cập nhật thông báo thành công');
    }
    public function activity()
    {
        return view('admin.notice.activity');

    }
    public function activityData(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'order.0.dir' => 'nullable|string|in:asc,desc',
            'filterDate' => 'nullable|string|max:100',
            'filterBanned' => 'nullable|boolean',
        ]);
        $columns = [
            'id',
            'username',
            'content',
            'created_at',
        ];
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $selectColumns = collect($columns)->values()->all();
        $query = Activity::select($selectColumns);
        if (!empty($search)) {
            $query->where('username', 'like', "%$search%")
                ->orWhere("content", "like", "%$search%");
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
    public function activityStore(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100',
            'content' => 'required|string',
        ]);
        Activity::create($validated);
        UserHelper::logWrite("AdminActivity", "Thêm Hoạt Động Mới");
        return redirect()->back()->with('success', 'Thêm hoạt động mới thành công!');
    }
    public function activityDestroy($id)
    {
        $activity = Activity::findOrFail($id);
        UserHelper::logWrite("AdminActivity", "Xoá Hoạt Động $id");
        $activity->delete();
        return BackendHelper::resApi("success", 'Xoá Hoạt động thành công');

    }
}
