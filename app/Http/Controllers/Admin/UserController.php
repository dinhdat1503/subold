<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UserHelper;
use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{
    public function logs()
    {
        return view("admin.history.user");
    }
    public function logsData($type, Request $request)
    {
        $request->merge(['type' => $type]);
        $validated = $request->validate(
            [
                'type' => 'required|string|in:all,days',
                'start' => 'required|numeric|min:0',
                'length' => 'required|numeric|min:1|max:10000',
                'search.value' => 'nullable|string',
                'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
                'order.0.dir' => 'nullable|string|in:asc,desc',
                'filterDate' => 'nullable|string|max:100',
                'filterType' => 'nullable|string|max:50',
            ]
        );
        $type = $validated['type'];

        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;

        $columns = ['id', 'username', 'action_type', 'old_value', 'value', 'new_value', 'description', 'ip_address', 'useragent', 'created_at',];
        $selectColumns = collect($columns)->filter(function ($column) {
            return $column !== 'username';
        })->merge(['user_id'])->values()->all();

        $query = \App\Models\Log::with("user:id,username")->select($selectColumns);

        if ($type === 'days') {
            $query->whereBetween('logs.created_at', [now()->startOfDay(), now()->endOfDay()]);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($queryUser) use ($search) {
                    $queryUser->where('username', 'like', "%$search%");
                });
                $q->orWhere('description', 'like', "%$search%");

            });
        }

        // Nếu có lọc theo khoảng ngày
        if (!empty($validated['filterDate'])) {
            $range = preg_split('/\s(to|đến)\s/', $validated['filterDate']);
            if (count($range) === 2) {
                $query->whereBetween('logs.created_at', [trim($range[0]), trim($range[1])]);
            }
        }

        // Nếu có lọc theo loại hành động
        if (!empty($validated['filterType'])) {
            $query->where('logs.action_type', $validated['filterType']);
        }

        $total = $query->count();

        // Xử lý order
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $sortDir = $order['dir'] ?? 'desc';
            if ($columnName === 'username') {
                $query->join('users', 'logs.user_id', '=', 'users.id')
                    ->orderBy('users.username', $sortDir)
                    ->select(array_map(function ($col) {
                        return 'logs.' . $col;
                    }, $selectColumns));
            } else {
                $query->orderBy($columnName, $sortDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Phân trang
        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        $data = $data->map(function ($log) {
            $log->username = $log->user->username ?? 'N/A';
            unset($log->user);
            unset($log->user_id);
            return $log;
        });
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function list()
    {
        $userCount = User::count(); // tổng tất cả user
        $ctvCount = User::where('level', 2)->count(); // cộng tác viên
        $dailyCount = User::where('level', 3)->count(); // đại lý
        $nppCount = User::where('level', 4)->count(); // nhà phân phối
        $adminCount = User::where('role', 'ctv')->count(); // quản trị viên

        return view("admin.user.list", compact(
            'userCount',
            'ctvCount',
            'dailyCount',
            'nppCount',
            'adminCount'
        ));
    }
    public function listData(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'order.0.dir' => 'nullable|string|in:asc,desc',
            'filterDate' => 'nullable|string|max:100',
            'filterID' => 'nullable|integer|min:1',
        ]);
        $columns = [
            'id',
            'id',
            'username',
            'balance',
            'total_recharge',
            'total_deduct',
            'utm_source',
            'last_ip',
            'last_online',
            'level'
        ];
        $search = $validated['search']['value'] ?? '';
        $order = $request->order[0] ?? null;
        $selectColumns = collect($columns)->merge(['email', 'full_name', 'last_useragent', 'created_at', 'status'])->values()->all();
        $query = User::select($selectColumns);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        if (!empty($validated['filterDate'])) {
            $range = preg_split('/\s(to|đến)\s/', $validated['filterDate']);
            if (count($range) === 2) {
                $query->whereBetween('logs.created_at', [trim($range[0]), trim($range[1])]);
            }
        }
        if (!empty($validated['filterID'])) {
            $id = $validated['filterID'];
            $query->where('id', $id);
        }
        $total = $query->count();

        // mapping cột từ datatable


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
    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return BackendHelper::resApi('error', 'Bạn không thể tự xoá chính mình !');
        }
        UserHelper::logWrite("AdminUser", "Xoá Người Dùng $id - " . $user->username);
        $user->delete();
        Session::where('user_id', $user->id)->delete();
        return BackendHelper::resApi('success', 'Xóa người dùng thành công');
    }
    public function edit($id, Request $request)
    {
        $user = User::findOrFail($id);
        return view("admin.user.edit", compact("user"));
    }
    public function update($type, $id, Request $request)
    {
        $user = User::findOrFail($id);
        $userSecurity = $user->security;

        switch ($type) {
            /* -------------------- CẬP NHẬT THÔNG TIN CƠ BẢN -------------------- */
            case 'info':
                $validated = $request->validate([
                    'full_name' => 'nullable|string|max:255',
                    'email' => 'required|email|max:150|unique:users,email,' . $user->id,
                    'avatar_url' => 'nullable|string',
                    'username' => 'required|string|min:8|max:100|unique:users,username,' . $user->id,
                    'level' => 'required|integer|min:1|max:4',
                    'role' => 'required|in:member,ctv,admin',
                    'status' => 'nullable|boolean',
                ]);
                $validated['status'] = isset($validated['status']);
                if ($validated['status']) {
                    $userSecurity->update(
                        [
                            'banned_reason' => null,
                            'attempt_error' => 0,
                            'attempt_login' => 0,
                        ]
                    );
                }
                if (Auth::user()->role !== 'admin') {
                    $validated['role'] = $user->role;
                }
                $user->update($validated);

                UserHelper::logWrite("AdminUser", "Chỉnh sửa thông tin cơ bản User - {$user->username}");
                UserHelper::logWrite("ChangeProfile", "Admin Cập Nhật Thông Tin Tài Khoản ", 0, $user);

                return back()->with('success', 'Cập nhật thông tin thành công!');

            /* -------------------- CẬP NHẬT MẬT KHẨU -------------------- */
            case 'password':
                $validated = $request->validate([
                    'password' => 'required|string|min:8|confirmed',
                ]);

                $user->update(['password' => \Hash::make($validated['password'])]);
                Session::where('user_id', $user->id)->delete();

                UserHelper::logWrite("AdminUser", "Thay Đổi Mật Khẩu User - {$user->username}");
                UserHelper::logWrite("ChangePassword", "Admin Thay Đổi Mật Khẩu Tài Khoản", 0, $user);

                return back()->with('success', 'Đổi mật khẩu thành công!');

            /* -------------------- CẬP NHẬT SỐ DƯ -------------------- */
            case 'money':
                $validated = $request->validate([
                    'balance_delta' => 'required|numeric|min:0',
                    'balance_action' => 'required|in:add_no_total,add_with_total,sub_with_total,sub_no_total',
                ]);

                $amount = $validated['balance_delta'];
                $action = $validated['balance_action'];

                $isSub = str_starts_with($action, 'sub_');
                $value = $isSub ? -$amount : $amount;

                $newBalance = round($user->balance + $value, 2);
                if ($isSub && $newBalance < 0) {
                    return back()->with('error', 'Không thể trừ quá số dư hiện tại của người dùng!');
                }

                $user->balance = $newBalance;
                if (in_array($action, ['add_with_total', 'sub_with_total'])) {
                    $user->total_recharge = round($user->total_recharge + $value, 2);
                }
                $user->save();

                UserHelper::logWrite("AdminUser", "Thay Đổi Money User - {$user->username}");
                UserHelper::logWrite("Balance", "Admin Thay Đổi Số Dư Tài Khoản", $value, $user);

                return back()->with('success', 'Cập nhật số dư thành công!');

            /* -------------------- RESET API TOKEN -------------------- */
            case 'api-token':
                $userSecurity->update(['api_token' => \Str::random(100)]);

                UserHelper::logWrite("AdminUser", "Thay Đổi API Token User - {$user->username}");
                UserHelper::logWrite("ChangeApiKey", "Admin Thay Dổi API Token", 0, $user);
                return BackendHelper::resApi('success', 'Đã reset API Token thành công!');

            /* -------------------- RESET 2FA -------------------- */
            case 'twofa':
                $newTwoFA = UserHelper::regenTwoFA($user);
                $userSecurity->update([
                    'twofa_secret' => $newTwoFA['secret'],
                    'twofa_qr' => $newTwoFA['qr_image'],
                ]);

                UserHelper::logWrite("AdminUser", "Thay Đổi 2FA User - {$user->username}");
                UserHelper::logWrite("RegenTwoFA", "Admin Thay Dổi Mã 2FA", 0, $user);
                return BackendHelper::resApi('success', 'Đã reset mã 2FA thành công!', ['data' => $newTwoFA['secret']]);

            /* -------------------- CẬP NHẬT BẢO MẬT -------------------- */
            case 'security':
                $validated = $request->validate([
                    'twofa_enabled' => 'nullable|boolean',
                    'otp_email_enabled' => 'nullable|boolean',
                ]);

                $userSecurity->update([
                    'twofa_enabled' => $validated['twofa_enabled'] ?? false,
                    'otp_email_enabled' => $validated['otp_email_enabled'] ?? false,
                ]);
                UserHelper::logWrite("AdminUser", "Thay Đổi Bảo Mật User - {$user->username}");
                UserHelper::logWrite("UpdateSecurity", "Admin Thay Đổi Bảo Mật", 0, $user);
                return back()->with('success', 'Cập nhật bảo mật thành công!');

            default:
                return response()->json(['status' => 'error', 'message' => 'Hành động không hợp lệ!']);
        }

    }
}
