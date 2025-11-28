<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;

class IpController extends Controller
{
    public function ipBlock()
    {
        return view("admin.website.ip-block");
    }

    public function ipBlockStore(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip||max:45|unique:blocked_ips,ip_address',
            'reason' => 'nullable|string|max:255',
        ]);
        $ip = BlockedIp::create($validated);
        \Cache::forget('blocked-ips-list');
        UserHelper::logWrite("AdminIPBlock", "Chặn IP mới - " . $ip->ip_address);
        return back()->with('success', 'Chặn IP Thành Công!');
    }

    public function ipBlockDestroy($id, Request $request)
    {
        $ip = BlockedIp::findOrFail($id);
        UserHelper::logWrite("AdminIPBlock", "Bỏ Chặn IP - " . $ip->ip_address);
        $ip->delete();
        \Cache::forget('blocked-ips-list');
        return BackendHelper::resApi('success', 'Bỏ chặn IP thành công');
    }
    public function ipBlockData(Request $request)
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
            'ip_address',
            'reason',
            'attempts',
            'banned',
            'created_at',
        ];
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $selectColumns = collect($columns)->values()->all();
        $query = BlockedIp::select($selectColumns);
        if (!empty($search)) {
            $query->where('ip_address', 'like', "%$search%")
                ->orWhere("reason", "like", "%$search%");
        }

        if (!empty($validated['filterDate'])) {
            $range = preg_split('/\s(to|đến)\s/', $validated['filterDate']);
            if (count($range) === 2) {
                $query->whereBetween('created_at', [trim($range[0]), trim($range[1])]);
            }
        }
        if (!empty($validated['filterBanned'])) {
            $query->where('banned', $validated['filterBanned']);
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

}
