<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserHelper;
use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function loginProcess(Request $request)
    {
        $valid = \Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/^[a-zA-Z0-9_]+$/', // chỉ cho phép chữ, số, gạch dưới
            ],
            'password' => 'required|string|min:8',
            'otp_email_code' => [
                'nullable',
                'digits:6', // nếu nhập thì phải đúng 6 số
                'regex:/^[0-9]{6}$/'
            ],
            'twofa_code' => [
                'nullable',
                'digits:6', // tương tự OTP 2FA
                'regex:/^[0-9]{6}$/'
            ],
            'remember' => 'nullable|boolean',
            'g-recaptcha-response' => 'nullable|string',
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.min' => 'Tên tài khoản phải có ít nhất 8 ký tự',
            'username.regex' => 'Tên tài khoản chỉ được chứa chữ cái, số, dấu gạch dưới và không dấu cách',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'otp_email_code.digits' => 'Mã OTP Email phải có đúng 6 chữ số',
            'otp_email_code.regex' => 'Mã OTP Email không hợp lệ',
            'twofa_code.digits' => 'Mã OTP 2FA phải có đúng 6 chữ số',
            'twofa_code.regex' => 'Mã OTP 2FA không hợp lệ',
            'remember.boolean' => 'Giá trị "Ghi nhớ đăng nhập" không hợp lệ.',
            'g-recaptcha-response.string' => 'Captcha không hợp lệ',
        ]);
        $valid->after(function ($validator) use ($request) {
            if ($request->username === $request->password) {
                $validator->errors()->add('password', 'Tài khoản và mật khẩu không được giống nhau');
            }
            if (!AuthController::verifyRecaptcha($request->input('g-recaptcha-response'), $request->ip())) {
                $validator->errors()->add('g-recaptcha-response', 'Xác minh captcha thất bại');
            }
        });
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }
        $validatedData = $valid->validated();
        $ip = $request->ip();

        $user = \App\Models\User::where('username', $validatedData['username'])->first();
        if (!$user) {
            BackendHelper::ipBlockCheck($ip, "Bạn Đã Bị Chặn Truy Cập Vì Đăng Nhập Sai Quá Nhiều");
            return redirect()->back()->with('error', 'Tài khoản không tồn tại')->withInput(['username' => $validatedData['username']]);
        }

        $security = $user->security;
        if ($user->status == 0 && $user->role != "admin") {
            return redirect()->route('user.ban');
        }
        if (!Auth::attempt(['username' => $validatedData['username'], 'password' => $validatedData['password']], $validatedData['remember'] ?? false)) {
            BackendHelper::ipBlockCheck($ip, "Bạn Đã Bị Chặn Truy Cập Vì Đăng Nhập Sai Quá Nhiều");
            UserHelper::userBlockLoginCheck($user, "Bạn Đã Bị Khóa Đăng Nhập Vì Đăng Nhập Sai Quá Nhiều");
            return redirect()->back()->with('error', 'Sai mật khẩu')->withInput(['username' => $validatedData['username']]);
        }
        $user->update([
            'last_ip' => $ip,
            'last_useragent' => $request->userAgent(),
            'last_online' => now(),
        ]);
        Auth::logoutOtherDevices($request->password);
        $otpMailCode = $validatedData['otp_email_code'] ?? null;
        $otpCode = $validatedData['twofa_code'] ?? null;
        $errors = [];
        $error = "";
        if ($security->otp_email_enabled) {
            $verify = UserHelper::verifyOTP($user, $otpMailCode, "email");
            if (!$verify['status']) {
                if ($verify['message'] == "RegenOTP") {
                    $errors[] = 'OTP Email';
                    session()->put('otpMail', true);
                } else if ($verify['message'] == "InvalidOTP") {
                    $error = 'Mã OTP Email không chính xác';
                }
            }
        }
        if ($security->twofa_enabled) {
            $verify = UserHelper::verifyOTP($user, $otpCode, "2fa");
            if (!$verify['status']) {
                if (!$otpCode) {
                    $errors[] = 'OTP 2FA';
                    session()->put('otpTwoFa', true);
                } else if ($verify['message'] == "InvalidOTP") {
                    $error = 'Mã OTP 2FA không chính xác';
                }
            }
        }
        if (!empty($error)) {
            UserHelper::userBlockLoginCheck($user, "Bạn Đã Bị Khóa Đăng Nhập Vì Nhập Sai OTP Quá Nhiều Lần");
            Auth::logout();
            return redirect()->back()->with('error', $error)->withInput(['username' => $validatedData['username']]);
        }
        if (!empty($errors)) {
            UserHelper::userBlockLoginCheck($user, "Bạn Đã Bị Khóa Đăng Nhập Vì Nhập Sai OTP Quá Nhiều Lần");
            Auth::logout();
            return redirect()->back()->with('error', 'Vui lòng nhập ' . implode(' và ', $errors))->withInput(['username' => $validatedData['username']]);
        }
        session()->forget('otpMail');
        session()->forget('otpTwoFa');
        $security->update([
            'otp_email_code' => null,
            'otp_email_expires' => null,
            'attempt_login' => 0,
        ]);
        UserHelper::logWrite("Login", "Đăng Nhập Vào Tài Khoản Tại IP - " . $request->ip());
        return redirect()->route('home')->with('success', 'Đăng Nhập Thành Công, Xin Chào ' . $request->username);
    }
}
