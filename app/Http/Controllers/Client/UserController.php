<?php

namespace App\Http\Controllers\Client;

use App\Helpers\UserHelper;
use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function profile()
    {
        $currentYear = now()->year;
        $order = \App\Models\Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_year'),
            DB::raw('SUM(payment) as total_spent'),
            DB::raw('COUNT(id) as total_orders')
        )->where('user_id', Auth::id())->whereYear('created_at', $currentYear)->groupBy('month_year')->orderBy('month_year', 'asc')->get();
        $spentData = [];
        $ordersData = [];
        $period = collect(range(1, 12))->map(function ($month) use ($currentYear) {
            $date = \Illuminate\Support\Carbon::create($currentYear, $month, 1);
            return [
                'month_year' => $date->format('Y-m'),
            ];
        });
        foreach ($period as $expectedMonth) {
            $stat = $order->firstWhere('month_year', $expectedMonth['month_year']);
            $spentData[] = $stat ? (float) $stat->total_spent : 0;
            $ordersData[] = $stat ? (int) $stat->total_orders : 0;
        }
        return view('client.user.profile', compact('spentData', 'ordersData'));
    }
    public function profileUpdate($type, Request $request)
    {
        $userSecurity = Auth::user()->security;
        $keyAttempts = "user-attempt-error-" . Auth::user()->id;
        $ip = $request->ip();
        switch ($type) {
            case 'profile':
                $validator = Validator::make($request->all(), [
                    'full_name' => 'required|string|max:255',
                    'avatar_url' => 'nullable|string',
                ], [
                    'full_name.required' => 'Vui lòng nhập họ và tên',
                    'full_name.string' => 'Họ và tên phải là chuỗi ký tự',
                    'full_name.max' => 'Họ và tên không được vượt quá 100 ký tự',
                    'avatar_url.string' => 'Đường dẫn ảnh đại diện không hợp lệ.',
                ]);
                if ($validator->fails()) {
                    Cache::increment($keyAttempts);
                    return redirect()->back()->withErrors($validator)->withInput(['full_name' => $request->full_name, 'avatar_url' => $request->avatar_url]);
                }
                Auth::user()->update($validator->validated());
                Cache::forget($keyAttempts);
                UserHelper::logWrite("ChangeProfile", "Đổi Thông Tin Profile Tại IP - " . $ip);
                return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
            case 'password':
                $validator = Validator::make($request->all(), [
                    'current_password' => 'required|string|min:8',
                    'password' => 'required|string|min:8|confirmed|different:current_password',
                ], [
                    'current_password.required' => 'Vui lòng nhập mật khẩu cũ',
                    'current_password.min' => 'Mật khẩu cũ phải có ít nhất 8 ký tự',
                    'password.required' => 'Vui lòng nhập mật khẩu mới',
                    'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
                    'password.confirmed' => 'Xác nhận mật khẩu mới không khớp',
                    'password.different' => 'Mật khẩu mới phải khác mật khẩu cũ',
                ]);
                $validator->after(function ($validator) use ($request) {
                    if (!Hash::check($request->input('current_password'), Auth::user()->password)) {
                        UserHelper::userBlockLoginCheck(Auth::user(), "Bạn Đã Bị Khóa Đăng Nhập Vì Nhập Sai Mật Khẩu Quá Nhiều Lần");
                        $validator->errors()->add('current_password', 'Mật khẩu cũ không chính xác');
                    }
                });
                if ($validator->fails()) {
                    Cache::increment($keyAttempts);
                    return redirect()->back()->withErrors($validator);
                }
                $validated = $validator->validated();
                Auth::user()->update([
                    'password' => Hash::make($validated['password']),
                ]);
                $userSecurity->update([
                    'attempt_login' => 0,
                ]);
                Cache::forget($keyAttempts);
                Auth::logoutOtherDevices($validated['password']);
                UserHelper::logWrite("ChangePassword", "Đổi Mật Khẩu Tại IP - " . $ip);
                return redirect()->back()->with('success', 'Đổi mật khẩu thành công');
            case 'api-token':
                $newToken = \Str::random(100);
                $userSecurity->update([
                    'api_token' => $newToken,
                ]);
                UserHelper::logWrite("ChangeApiKey", "Đổi Api Key Tại IP - " . $ip);
                return BackendHelper::resApi('success', 'Tạo token thành công', [
                    'api_token' => $newToken
                ]);
            case 'two-fa':

                $regenTwoFA = UserHelper::regenTwoFA(Auth::user());
                $userSecurity->update([
                    'twofa_secret' => $regenTwoFA['secret'],
                    'twofa_qr' => $regenTwoFA['qr_image'],
                ]);
                UserHelper::logWrite("RegenTwoFA", "Đổi lại mã 2FA tại IP - " . $ip);
                return BackendHelper::resApi('success', 'Đã tạo mã 2FA mới', [
                    'secret' => $regenTwoFA['secret'],
                    'qr' => $regenTwoFA['qr_image']
                ]);
            case 'otp-mail':
                $otpCode = rand(100000, 999999); // Tạo OTP 6 số
                $expiredAt = \Illuminate\Support\Carbon::now()->addMinutes(5); // Hết hạn sau 5 phút
                $userSecurity->update([
                    'otp_email_code' => $otpCode,
                    'otp_email_expires' => $expiredAt,
                ]);
                \Mail::to(Auth::user()->email)->send(new \App\Mail\MailOtpCode($otpCode));
                UserHelper::logWrite("SendOtpEmail", "Gửi mã OTP xác nhận bảo mật tại IP - " . $ip);
                return BackendHelper::resApi('success', 'Mã OTP đã được gửi qua email');
            case 'security':
                $validator = Validator::make($request->all(), [
                    'twofa_code' => [
                        'nullable',
                        'digits:6',
                        'regex:/^[0-9]{6}$/'
                    ],
                    'otp_email_code' => [
                        'nullable',
                        'digits:6',
                        'regex:/^[0-9]{6}$/'
                    ],
                    'twofa_enabled' => 'nullable|boolean',
                    'otp_email_enabled' => 'nullable|boolean',
                ], [
                    'otp_email_code.digits' => 'Mã OTP Email phải là 6 chữ số.',
                    'otp_email_code.regex' => 'Mã OTP Email không hợp lệ.',
                    'otp_email_enabled.boolean' => 'Trạng thái bật/tắt OTP Email không hợp lệ.',
                    'twofa_code.regex' => 'Mã OTP 2FA không hợp lệ.',
                    'twofa_code.digits' => 'Mã OTP 2FA phải là 6 chữ số.',
                    'twofa_enabled.boolean' => 'Trạng thái bật/tắt 2FA không hợp lệ.',
                ]);
                $validator->after(function ($validator) use ($request, $userSecurity) {
                    $twofaEnabled = $request->input('twofa_enabled', false);
                    $otpEmailEnabled = $request->input('otp_email_enabled', false);
                    if ($userSecurity->twofa_enabled != $twofaEnabled) {
                        $verify = UserHelper::verifyOTP(Auth::user(), $request->input('twofa_code') ?? null, '2fa');
                        if (!$verify['status']) {
                            if ($verify['message'] == "InvalidOTP") {
                                UserHelper::userBlockLoginCheck(Auth::user(), "Bạn Đã Bị Khóa Đăng Nhập Vì Nhập Sai OTP 2FA Quá Nhiều Lần");
                                $validator->errors()->add('twofa_code', "Mã OTP 2FA không chính xác");
                            }
                        }
                    }
                    if ($userSecurity->otp_email_enabled != $otpEmailEnabled) {
                        $verify = UserHelper::verifyOTP(Auth::user(), $request->input('otp_email_code') ?? null, 'email');
                        if (!$verify['status']) {
                            if ($verify['message'] == "InvalidOTP") {
                                UserHelper::userBlockLoginCheck(Auth::user(), "Bạn Đã Bị Khóa Đăng Nhập Vì Nhập Sai OTP Email Quá Nhiều Lần");
                                $validator->errors()->add('otp_email_code', "Mã OTP Email không chính xác");
                            } else if ($verify['message'] == "RegenOTP") {
                                $validator->errors()->add('otp_email_code', "Mã OTP Email đã được gửi lại, vui lòng kiểm tra email");
                            }
                        }
                    }
                });
                if ($validator->fails()) {
                    Cache::increment($keyAttempts);
                    return redirect()->back()->withErrors($validator);
                }
                $validated = $validator->validated();
                $userSecurity->update([
                    'twofa_enabled' => $validated['twofa_enabled'] ?? false,
                    'otp_email_enable' => $validated['otp_email_enabled'] ?? false,
                    'otp_email_code' => NULL,
                    'otp_email_expires' => NULL,
                    'attempt_login' => 0,
                ]);
                Cache::forget($keyAttempts);
                UserHelper::logWrite("UpdateSecurity", "Cập nhật cài đặt bảo mật tại IP - " . $ip);
                return redirect()->back()->with('success', 'Cập nhật cài đặt bảo mật thành công');
            default:
                return redirect()->back()->with('error', 'Hành động không hợp lệ');
        }
    }
    public function logs()
    {
        return view('client.user.logs');
    }
    public function logsData(Request $request)
    {
        $columns = [
            'id',
            'action_type',
            'old_value',
            'value',
            'new_value',
            'description',
            'created_at',
        ];
        $validated = $request->validate([
            'start' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:1|max:10000',
            'search.value' => 'nullable|string',
            'order.0.column' => ['nullable', 'numeric', 'min:0', 'max:' . (count($columns) - 1)],
            'order.0.dir' => 'nullable|string|in:asc,desc',
            'filterDate' => 'nullable|string|max:100',
            'filterType' => 'nullable|string',
        ]);

        $search = $validated['search']['value'] ?? '';
        $order = $validated['order'][0] ?? null;
        $query = \App\Models\Log::select(array_values($columns)) // chỉ lấy các cột cần
            ->where('user_id', Auth::id());
        // Nếu có search
        if (!empty($search)) {
            $query->where('description', 'like', "%$search%");
        }
        // Nếu có lọc theo khoảng ngày
        if (!empty($validated['filterDate'])) {
            $range = preg_split('/\s(to|đến)\s/', $validated['filterDate']);
            if (count($range) === 2) {
                $query->whereBetween('created_at', [trim($range[0]), trim($range[1])]);
            }
        }
        if (!empty($validated['filterType'])) {
            $query->where('action_type', $validated['filterType']);
        }
        $total = $query->count();
        // Xử lý order
        if ($order) {
            $columnName = $columns[$order['column']] ?? 'id';
            $query->orderBy($columnName, $order['dir'] ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }
        // Phân trang
        $data = $query->skip($validated['start'])->take($validated['length'])->get();
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
    public function level()
    {
        $baseLevels = [
            2 => [
                'title' => 'Cộng tác viên',
                'icon' => '/assets/images/client/level/level-2.png',
                'link' => route('recharge.bank'),
            ],
            3 => [
                'title' => 'Đại lý',
                'icon' => '/assets/images/client/level/level-3.png',
                'link' => route('recharge.bank'),
            ],
            4 => [
                'title' => 'Nhà phân phối',
                'icon' => '/assets/images/client/level/level-4.png',
                'link' => route('recharge.bank'),
            ],
        ];
        $userLevels = json_decode(siteSetting('user_levels'), true);
        $levels = [];
        foreach ($baseLevels as $id => $info) {
            $levels[$id] = array_merge($info, $userLevels[$id] ?? [
                'discount' => 0,
                'money' => 0
            ]);
        }
        $total = Auth::user()->total_recharge ?? 0;
        $maxMoney = collect($levels)->max('money');
        $progress = $maxMoney > 0 ? min(100, ($total / $maxMoney) * 100) : 0;
        $milestones = [];
        foreach ($levels as $id => $lvl) {
            if (!empty($lvl['money'])) {
                $milestones[] = [
                    'id' => $id,
                    'title' => $lvl['title'],
                    'money' => $lvl['money'],
                    'percent' => $maxMoney > 0 ? ($lvl['money'] / $maxMoney) * 100 : 0,
                    'achieved' => $total >= $lvl['money'],
                ];
            }
        }

        // cấp hiện tại
        $currentLevel = collect($levels)
            ->filter(fn($lvl) => $total >= ($lvl['money'] ?? 0))
            ->keys()
            ->last();

        return view('client.user.level', compact(
            'levels',
            'milestones',
            'total',
            'progress',
            'maxMoney',
            'currentLevel'
        ));
    }

}
