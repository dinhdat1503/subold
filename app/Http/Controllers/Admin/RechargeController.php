<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\RechargeMethod;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // thêm dòng này để thao tác file

class RechargeController extends Controller
{
    public function recharge()
    {
        $promotionLevels = json_decode(siteSetting('promotion_levels'), true);
        $bank = RechargeMethod::where('method_type', 'bank')->first();
        $crypto = RechargeMethod::where('method_type', 'crypto')->first();
        return view("admin.recharge.manage", compact("bank", "crypto", "promotionLevels"));
    }
    public function logs()
    {
        return view("admin.history.recharge");
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:25',
            'bank_account_name' => 'required|string|max:100',
            'bank_account_index' => 'required|string|max:100',
            'bank_api_key' => 'required|string|max:500',
            'bank_recharge_min' => 'nullable|numeric|min:5000',
            'bank_code' => 'required|string|max:100',
            'bank_note' => 'required|string',
            'bank_status' => 'nullable|boolean',
            'crypto_name' => 'required|string|max:25',
            'crypto_network' => 'required|string|max:25',
            'crypto_account_index' => 'required|string|max:100',
            'crypto_exchange_rate' => 'nullable|numeric|min:1',
            'crypto_recharge_min' => 'nullable|numeric|min:1',
            'crypto_wallet_qr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'crypto_note' => 'required|string',
            'crypto_status' => 'nullable|boolean',
            'promotion_show' => 'nullable|boolean',
            'promotion_money.*' => 'required|numeric',
            'promotion_value.*' => 'required|numeric',
        ]);


        $bank = RechargeMethod::firstOrNew(['method_type' => 'bank']);
        $bank->update([
            'name' => $validated['bank_name'],
            'recharge_min' => $validated['bank_recharge_min'] ?? 10000,
            'account_name' => $validated['bank_account_name'],
            'account_index' => $validated['bank_account_index'],
            'api_key' => $validated['bank_api_key'],
            'note' => $validated['bank_note'],
            'status' => $validated['bank_status'] ?? false,
        ]);
        $crypto = RechargeMethod::firstOrNew(['method_type' => 'crypto']);
        $crypto->update([
            'name' => $validated['crypto_name'],
            'exchange_rate' => $validated['crypto_exchange_rate'] ?? 1,
            'recharge_min' => $validated['crypto_recharge_min'] ?? 1,
            'account_index' => $validated['crypto_account_index'],
            'network' => $validated['crypto_network'],
            'note' => $validated['crypto_note'],
            'status' => $validated['crypto_status'] ?? false,
        ]);
        if (isset($validated['crypto_wallet_qr'])) {
            $filePath = BackendHelper::uploadPublicFile($validated['crypto_wallet_qr'], "assets/images/crypto", $crypto->wallet_qr, "crypto_logo");
            $crypto->wallet_qr = $filePath;
        }
        $crypto->save();
        SiteSetting::setValue('bank_code', $validated['bank_code']);
        SiteSetting::setValue('promotion_show', isset($validated['promotion_show']));
        $promotionLevels = [];

        if ($validated['promotion_money'] && is_array($validated['promotion_money'])) {
            foreach ($validated['promotion_money'] as $key => $money) {
                $promotionLevels[] = [
                    'money' => (int) $money,
                    'promotion' => (int) ($validated['promotion_value'][$key] ?? 0),
                ];
            }
        }
        SiteSetting::setValue('promotion_levels', json_encode($promotionLevels));
        UserHelper::logWrite("AdminRecharge", "Cập Nhật Cài Đặt Nạp Tiền");
        return redirect()->back()->with('success', 'Cập nhật cấu hình nạp tiền và khuyến mãi thành công!');
    }
    public function logsData(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'order.0.dir' => 'nullable|string|in:asc,desc',
            'filterDate' => 'nullable|string|max:100',
            'filterTransID' => 'nullable|string',
            'filterStatus' => 'nullable|numeric',
        ]);

        $search = $validated['search']['value'] ?? '';
        $order = $request->order[0] ?? null;
        $columns = [
            'id',
            'created_at',
            'status',
            'username',
            'recharge_name',
            'trans_id',
            'amount',
            'promotion',
            'amount_received',
            'note',
        ];
        $selectColumns = collect($columns)->filter(function ($column) {
            return $column !== 'username' && $column !== 'recharge_name';
        })->merge(['user_id', 'recharge_id'])->values()->all();
        $query = \App\Models\RechargeLog::select($selectColumns)->with('user:id,username')->with('recharge:id,name,method_type');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('username', "like", "%$search%");
                })->orWhere('note', 'like', "%$search%");
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
        if (isset($validated['filterStatus'])) {
            $query->where('recharge_logs.status', $validated['filterStatus']);
        }
        if (!empty($validated['filterTransID'])) {
            $id = $validated['filterTransID'];
            $query->where('trans_id', $id);
        }
        $total = $query->count();
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'username') {
                $query
                    ->join('users', 'recharge_logs.user_id', '=', 'users.id')
                    ->orderBy('users.username', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'recharge_logs.' . $col;
                    }, $selectColumns));
            } else if ($columnName === 'recharge_name') {
                $query
                    ->join('recharge_methods', 'recharge_logs.recharge_id', '=', 'recharge_methods.id')
                    ->orderBy('recharge_methods.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'recharge_logs.' . $col;
                    }, $selectColumns));
            } {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data = $data->map(function ($log) {
            $log->username = $log->user->username ?? 'N/A';
            unset($log->recharge);
            unset($log->recharge_id);
            unset($log->user_id);
            unset($log->user);
            return $log;
        });

        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
}
