<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function Logout(\Illuminate\Http\Request $request)
    {
        \DB::table('sessions')->where('user_id', \Auth::id())->delete();
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('guest.home')->with('success', 'Đăng xuất thành công');
    }
    public static function verifyRecaptcha($response, $ip)
    {
        $settingCaptcha = siteSetting('google_recaptcha', 0);
        if (!$settingCaptcha)
            return true;

        $secret = env('GOOGLE_RECAPTCHA_SITE_SECRET');
        $verify = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $response,
            'remoteip' => $ip,
        ]);
        return $verify->json('success');
    }
}
