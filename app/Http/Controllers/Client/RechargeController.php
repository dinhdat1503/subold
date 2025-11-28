<?php

namespace App\Http\Controllers\Client;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\LogRecharge;
use App\Models\RechargeLog;
use App\Models\RechargeMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RechargeController extends Controller
{
    public function bank()
    {
        $promotionLevels = json_decode(siteSetting('promotion_levels') ?? [], true);
        $account = RechargeMethod::where('method_type', 'bank')
            ->where('status', true)
            ->firstOrFail();
        return view('client.recharge.bank', compact('account', 'promotionLevels'));
    }
    public function crypto()
    {
        $promotionLevels = json_decode(siteSetting('promotion_levels') ?? [], true);
        $account = RechargeMethod::where('method_type', "crypto")
            ->where('status', true)
            ->firstOrFail();
        return view('client.recharge.crypto', compact('account', 'promotionLevels'));
    }
    public function cryptoStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'hashcode' => 'required|string|min:30|max:80|unique:recharge_logs,trans_id',
        ], [
            'hashcode.required' => 'Vui lòng nhập mã giao dịch (hashcode)',
            'hashcode.min' => 'Mã giao dịch không hợp lệ',
            'hashcode.unique' => 'Mã giao dịch này đã tồn tại',
        ]);
        $cryptoMethod = null;
        $validator->after(function ($validator) use (&$cryptoMethod) {
            $cryptoMethod = RechargeMethod::where('method_type', 'crypto')->where('status', true)->first();
            if (!$cryptoMethod) {
                $validator->errors()->add('hashcode', 'Phương thức nạp tiền qua Crypto hiện không khả dụng');
            }
        });
        $key = "user-attempt-error-" . Auth::id();
        if ($validator->fails()) {
            Cache::increment($key);
            return back()->withErrors($validator)->withInput();
        }
        RechargeLog::create([
            'user_id' => Auth::id(),
            'recharge_id' => $cryptoMethod->id,
            'trans_id' => $validator->validated()['hashcode'],
        ]);
        Cache::forget($key);
        UserHelper::logWrite("CreateCryptoInvoice", "Tạo Hoá Đơn Nạp Crypto");
        return back()->with('success', 'Đã ghi nhận giao dịch, hệ thống sẽ kiểm tra và xử lý trong giây lát.');
    }
    public function logsData($type, Request $request)
    {
        $request->merge(['type' => $type]);
        $validated = $request->validate([
            'type' => ['required', 'in:bank,crypto'],
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string', // Giới hạn độ dài search
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'], // Giới hạn số cột
            'order.0.dir' => 'nullable|string|in:asc,desc', // Chỉ asc hoặc desc
            'filterDate' => 'nullable|string|max:100', // "2023-01-01 to 2023-01-31"
            'filterStatus' => 'nullable|numeric', // Hoặc 'integer'
        ]);
        $type = $validated['type'];
        if ($type === 'bank') {
            $columns = [
                'id',               // Khóa 0: id (Dùng cho STT/Action)
                'recharge_name',    // Khóa 1: Tên phương thức nạp
                'created_at',       // Khóa 2: Thời gian
                'trans_id',         // Khóa 3: ID giao dịch
                'amount',           // Khóa 4: Số tiền nạp
                'promotion',        // Khóa 5: Khuyến mãi
                'amount_received',  // Khóa 6: Số tiền nhận được
                'note',             // Khóa 7: Ghi chú
            ];
        } elseif ($type === 'crypto') {
            $columns = [
                'id',               // Khóa 0: id (Dùng cho STT/Action)
                'recharge_name',    // Khóa 1: Tên phương thức nạp
                'created_at',       // Khóa 2: Thời gian
                'status',           // Khóa 3: Trạng thái
                'trans_id',         // Khóa 4: ID giao dịch
                'amount',           // Khóa 5: Số tiền nạp
                'promotion',        // Khóa 6: Khuyến mãi
                'amount_received',  // Khóa 7: Số tiền nhận được
            ];
        } else {
            $columns = [];
        }
        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $selectColumns = collect($columns)->filter(function ($column) {
            return $column !== 'recharge_name';
        })->merge(['recharge_id'])->values()->all();
        $query = RechargeLog::with("recharge:id,name,method_type")->select($selectColumns)
            ->whereHas('recharge', function ($q) use ($type) {
                $q->where('method_type', $type);
            })->where('user_id', Auth::id());
        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search, $type) {
                $q->where('trans_id', $search);
                if ($type === 'bank') {
                    $q->orWhere("note", "like", "%$search%");
                }
            });
        }
        // Filter date
        if (!empty($validated['filterDate'])) {
            $range = preg_split('/\s(to|đến)\s/', $validated['filterDate']);
            if (count($range) === 2) {
                $query->whereBetween('created_at', [trim($range[0]), trim($range[1])]);
            }
        }
        // Filter status
        if (isset($validated['filterStatus'])) {
            $query->where('status', $validated['filterStatus']);
        }
        $total = $query->count();
        // Order
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'recharge_name') {
                $query
                    ->join('recharge_methods', 'recharge_logs.recharge_id', '=', 'recharge_methods.id')
                    ->orderBy('recharge_methods.name', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'recharge_logs.' . $col;
                    }, $selectColumns));
            } else {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data = $data->map(function ($log) {
            $log->recharge_name = $log->recharge->name ?? 'N/A';
            unset($log->recharge);
            unset($log->recharge_id);
            return $log;
        });
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
}
