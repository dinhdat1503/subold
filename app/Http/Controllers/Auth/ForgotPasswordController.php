<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
    public function resetPassword($token, Request $request)
    {
        $validatedData = $request->validate([
            'token' => 'required|string|max:60',
        ], [
            'token.required' => 'Thiếu token xác thực. Vui lòng kiểm tra lại đường dẫn.',
            'token.string' => 'Token không hợp lệ.',
            'token.max' => 'Token không được vượt quá 60 ký tự.',
        ]);
        $token = PasswordReset::where('token', $validatedData['token'])->first();
        if ($token) {
            if (now()->gt($token->token_expries)) {
                $token->delete();
                return redirect()->route('password.forgot')->with('error', 'Token đã hết hạn, vui lòng yêu cầu lại !');
            }
            return view('auth.reset-password', compact('token'));
        } else {
            BackendHelper::ipBlockCheck($request->ip(), "Bạn Đã Bị Chặn Truy Cập Vì Truy Cập Token Sai Nhiều Lần");
            return redirect()->route('password.forgot')->with('error', 'Token không hợp lệ');
        }
    }

    public function forgotPasswordSend(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:150',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.string' => 'Email phải là chuỗi ký tự hợp lệ',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 150 ký tự',
        ]);
        $email = $validatedData['email'];
        $user = User::where('email', $email)->first();
        if ($user) {
            $token = \Str::random(60);
            $check = PasswordReset::where('email', $email)->first();
            if ($check) {
                if (!now()->gt($check->token_expries)) {
                    return redirect()->back()->with('error', 'Bạn đã yêu cầu đổi mật khẩu gần đây. Vui lòng thử lại sau 5 phút');
                }
                $check->update([
                    'token' => $token,
                    'token_expries' => now()->addMinutes(5),
                ]);
            } else {
                PasswordReset::create([
                    'email' => $email,
                    'token' => $token,
                    'token_expries' => now()->addMinutes(5),
                ]);
            }
            UserHelper::logWrite("RequestChangePassword", "Yêu Cầu Khỏi Phục Mật Khẩu Tại IP - " . $request->ip());
            \Mail::to($email)->send(new \App\Mail\MailForgotPassword(route('password.reset', $token)));
            return redirect()->back()->with('success', 'Vui lòng kiểm tra email để lấy lại mật khẩu');
        } else {
            return redirect()->back()->with('error', 'Mail không tồn tại');
        }

    }
    public function resetPasswordUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'token' => 'required|string|max:60',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'token.required' => 'Token không được bỏ trống.',
            'token.max' => 'Token không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);
        $tokenData = PasswordReset::where('token', $validatedData['token'])->first();
        if (!$tokenData) {
            BackendHelper::ipBlockCheck($request->ip(), "Bạn đã bị chặn truy cập vì nhập token sai nhiều lần");
            return redirect()->route('password.forgot')->with('error', 'Token không hợp lệ');
        }
        if (now()->gt($tokenData->token_expries)) {
            $tokenData->delete();
            return redirect()->route('password.forgot')->with('error', 'Token đã hết hạn, vui lòng yêu cầu lại.');
        }
        $user = User::where('email', $tokenData->email)->first();
        if (!$user) {
            $tokenData->delete();
            return redirect()->route('password.forgot')->with('error', 'Email không tồn tại');
        }
        $user->update([
            'password' => \Hash::make($validatedData['password'])
        ]);
        $tokenData->delete();
        \App\Models\Session::where('user_id', $user->id)->delete();
        UserHelper::logWrite("ChangePassword", "Đổi mật khẩu thành công tại IP - " . $request->ip());
        return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công! Bạn có thể đăng nhập lại ngay.');
    }

}
