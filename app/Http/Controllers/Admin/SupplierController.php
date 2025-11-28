<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function supplier()
    {
        return view("admin.suppliers.manage");
    }
    public function data(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'order.0.dir' => 'nullable|string|in:asc,desc',
        ]);

        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $columns = [
            'id',
            'id',
            'name',
            'base_url',
            'username',
            'money',
            'api',
            'status',
            'last_synced_at',
            'created_at',
        ];
        $selectColumns = collect($columns)->values()->unique()->all();
        $query = Supplier::select($selectColumns);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $total = $query->count();


        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $query->orderBy($columnName, $order['dir'] ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $selectColumns = array_filter($columns, fn($col) => !is_null($col));

        $data = $query->skip($validated['start'])->take($validated['length'])->get();

        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:25|unique:suppliers,name',
            'base_url' => 'required|url|max:255',
            'api_key' => 'required|string|max:500',
            'proxy' => 'nullable|string|max:255',
            'price_percent' => 'required|numeric|min:0|max:1000',
            'price_unit_value' => 'required|numeric|min:1',
            'api' => 'required|in:SMM,2MXH,TRUMVIP',
            'currency' => 'required|string',
            'exchange_rate' => 'required|numeric|min:0.0001|max:1000000',
            'status' => 'nullable|boolean',
        ]);
        $validated['status'] = isset($validated['status']);
        $supplier = Supplier::create($validated);
        UserHelper::logWrite("AdminSupplier", "Thêm Supplier - " . $supplier->name);
        return redirect()->back()->with('success', "Đã thêm Supplier '{$supplier->name}' thành công!");
    }
    public function update($id, Request $request)
    {
        $supplier = Supplier::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:25|unique:suppliers,name,' . $supplier->id,
            'base_url' => 'required|url|max:255',
            'api_key' => 'required|string|max:500',
            'proxy' => 'nullable|string|max:255',
            'price_percent' => 'required|numeric|min:0|max:1000',
            'price_unit_value' => 'required|numeric|min:1',
            'api' => 'required|in:SMM,2MXH,TRUMVIP',
            'username' => 'required|string|max:255',
            'currency' => 'required|string',
            'exchange_rate' => 'required|numeric|min:0.0001|max:1000000',
            'status' => 'nullable|boolean',
        ]);
        $validated['status'] = isset($validated['status']);
        $supplier->update($validated);
        UserHelper::logWrite("AdminSupplier", "Cập Nhật Supplier - " . $supplier->name);
        return redirect()
            ->back()
            ->with('success', 'Cập nhật Supplier thành công!');
    }
    public function edit($id, Request $request)
    {
        $supplier = Supplier::findOrFail($id);
        return view("admin.suppliers.edit", compact("supplier"));
    }
    public function destroy($id, Request $request)
    {
        $supplier = Supplier::findOrFail($id);
        UserHelper::logWrite("AdminSupplier", "Xoá Supplier - " . $supplier->name);
        $supplier->delete();
        return BackendHelper::resApi("success", "Xoá thành công");
    }
}
