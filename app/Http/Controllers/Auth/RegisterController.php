<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerProcess(\Illuminate\Http\Request $request)
    {
        $valid = \Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:150|unique:users,email',
            'username' => ['required', 'string', 'min:8', 'max:100', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'accepted',
            'g-recaptcha-response' => 'nullable|string',
        ], [
            'full_name.required' => 'Vui lòng nhập họ và tên',
            'full_name.max' => 'Họ và tên tối đa 100 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'username.min' => 'Tên tài khoản phải có ít nhất 8 ký tự',
            'username.regex' => 'Tên tài khoản chỉ được chứa chữ cái, số, dấu gạch dưới và không dấu cách',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'agree_terms.accepted' => 'Bạn phải đồng ý với điều khoản & dịch vụ để tiếp tục đăng ký.',
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

        if (!AuthController::verifyRecaptcha($request->input('g-recaptcha-response'), $ip)) {
            return back()->with('error', 'Xác minh captcha thất bại')->withInput();
        }

        // Tạo user mới
        $newUser = \App\Models\User::create([
            'full_name' => $validatedData['full_name'],
            'username' => $validatedData['usename'],
            'email' => strtolower($validatedData['email']),
            'avatar_url' => "/assets/images/client/profile/user-" . rand(1, 10) . ".jpg",
            'password' => \Hash::make($validatedData['password']),
            'last_ip' => $ip,
            'last_useragent' => $request->userAgent(),
            'last_online' => now(),
            'utm_source' => $request->cookie('utm_source') ?? null,
        ]);
        if ($newUser) {
            $regenTwoFA = \App\Helpers\UserHelper::regenTwoFA($newUser);
            \App\Models\UserSecurity::create([
                'user_id' => $newUser->id,
                'twofa_secret' => $regenTwoFA['secret'],
                'twofa_qr' => $regenTwoFA['qr_image'],
                'api_token' => \Str::random(100),
            ]);
            \App\Helpers\UserHelper::logWrite("Register", "Đăng Ký Tài Khoản Tại IP - " . $ip);
            return redirect()->route('login')
                ->with('success', 'Đăng Ký Thành Công')
                ->withInput(['username' => $validatedData['usename']]);
        } else {
            return redirect()->back()->with('error', 'Đăng Ký Thất Bại')->withInput();
        }
    }
}
