<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailOtpCode extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * Tạo instance MailOtpCode
     *
     * @param string $otp Mã OTP cần gửi
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Tiêu đề email
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Mã Xác Nhận OTP',
        );
    }

    /**
     * Nội dung email
     */
    public function content()
    {
        return new Content(
            markdown: 'mail.otp-code', // tạo view mới mail/otp-code.blade.php
            with: [
                'otp' => $this->otp,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
