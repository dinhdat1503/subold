<?php

namespace App\Helpers;
use BaconQrCode as QR;
use PragmaRX\Google2FA\Google2FA;

class UserHelper
{
    public static function verifyOTP($user, $code, $type)
    {
        $userSecurity = $user->security;
        $msg = '';
        $status = false;
        if (empty($code)) {
            return ['status' => false, 'message' => 'InvalidOTP'];
        }
        switch ($type) {
            case 'email':
                if (!$userSecurity->otp_email_code || now()->gt($userSecurity->otp_email_expires)) {
                    $newCode = rand(100000, 999999);
                    $userSecurity->update([
                        'otp_email_code' => $newCode,
                        'otp_email_expires' => now()->addMinutes(5),
                    ]);
                    \Mail::to($user->email)->send(new \App\Mail\MailOtpCode($newCode));
                    $msg = 'RegenOTP';
                } elseif ($userSecurity->otp_email_code !== $code) {
                    $msg = 'InvalidOTP';
                } else {
                    $status = true;
                }
                return ['status' => $status, 'message' => $msg];
            case '2fa':
                $google2fa = new Google2FA();
                $valid = $google2fa->verifyKey($userSecurity->twofa_secret, $code);
                if ($valid) {
                    $status = true;
                } else {
                    $msg = 'InvalidOTP';
                }
                return ['status' => $status, 'message' => $msg];
            default:
                return ['status' => $status, 'message' => $msg];
        }
    }
    public static function regenTwoFA($user)
    {
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );
        $renderer = new QR\Renderer\ImageRenderer(
            new QR\Renderer\RendererStyle\RendererStyle(200),
            new QR\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new QR\Writer($renderer);
        $qrImage = 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($qrCodeUrl));
        return [
            'secret' => $secretKey,
            'qr_image' => $qrImage,
        ];
    }
    public static function userBlockLoginCheck($user, $reason)
    {
        $security = $user->security;
        if (!$security) {
            return;
        }
        $security->attempt_login++;
        if ($security->attempt_login >= siteSetting('max_user_login_attempts')) {
            $user->status = 0;
            $security->banned_reason = $reason;
        }
        $security->save();
        $user->save();
    }
    public static function logWrite($actionType, $description, $value = 0, $customUser = null)
    {
        $user = $customUser ?? \Auth::user();
        if (!$user) {
            return;
        }
        $oldBalance = (float) $user->balance;
        $newBalance = (float) $oldBalance + ((float) $value ?? 0);
        \App\Models\Log::create([
            'user_id' => $user->id,
            'action_type' => $actionType,
            'value' => (float) $value, // giá trị thay đổi (âm hoặc dương)
            'old_value' => $oldBalance,
            'new_value' => $newBalance,
            'ip_address' => request()->ip(),
            'useragent' => request()->userAgent(),
            'description' => $description,
        ]);
    }
    public static function addCredits($user, $amount, $description, $total = false)
    {
        $user->balance += (float) $amount;
        if ($total) {
            $user->total_recharge += (float) $amount;
        }
        $user->save();
        self::logWrite('Balance', $description, $amount, $user);
    }
    public static function deductCredits($user, $amount, $description, $total = false)
    {
        $user->balance -= (float) $amount;
        if ($user->balance < 0) {
            $user->balance = 0;
        }
        $user->save();
        self::logWrite('Balance', $description, -$amount, $user);
    }
}