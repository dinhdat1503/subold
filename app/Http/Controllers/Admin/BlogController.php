<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blog()
    {
        return view("admin.blog.manage");
    }
    public function blogData(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'order.0.dir' => 'nullable|string|in:asc,desc',
        ]);
        $columns = [
            'id',
            'slug',
            'utm_source',
            'content',
            'content',
            'created_at',
        ];
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $selectColumns = collect($columns)->values()->all();
        $query = Blog::select($selectColumns);
        if (!empty($search)) {
            $query->where('slug', 'like', "%$search%")
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
    public function blogStore(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'utm_source' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Blog::create($validated);
        UserHelper::logWrite("AdminBlog", "Thêm Blog - $validated[slug]");
        return back()->with('success', 'Tạo blog mới thành công!');
    }
    public function blogUpdate($id, Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
            'utm_source' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $blog = Blog::findOrFail($id);
        $blog->update($validated);
        UserHelper::logWrite("AdminBlog", "Cập Nhật Blog - " . $validated["slug"]);
        return BackendHelper::resApi("success", 'Cập nhật blog thành công!');
    }
    public function blogDelete($id)
    {
        $blog = Blog::findOrFail($id);
        UserHelper::logWrite("AdminBlog", "Xoá Blog - $blog->slug");
        $blog->delete();
        return BackendHelper::resApi("success", 'Đã xóa blog!');

    }
}
