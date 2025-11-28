<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .email-wrapper {
            width: 100%;
            padding: 30px 0;
            background: #f9f9f9;
        }

        .email-container {
            width: 600px;
            background: #fff;
            border-radius: 12px;
            margin: 0 auto;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .email-header {
            background: linear-gradient(135deg, #d32f2f, #f9a825);
            text-align: center;
            padding: 25px;
        }

        .email-header img {
            width: 150px;
            display: block;
            margin: 0 auto;
        }

        .email-body {
            padding: 35px 40px;
            color: #333;
            line-height: 1.6;
            font-size: 16px;
        }

        .email-body h2 {
            margin-top: 0;
            color: #d32f2f;
            font-size: 22px;
        }

        .email-body p {
            margin: 10px 0;
        }

        .btn-reset {
            background: linear-gradient(135deg, #f44336, #fbc02d);
            color: #fff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            margin: 25px 0;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .note-box {
            background: #fff8e1;
            border-left: 4px solid #fbc02d;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }

        .email-footer {
            background: #f4f4f4;
            color: #888;
            font-size: 13px;
            text-align: center;
            padding: 18px;
        }

        a {
            color: #d32f2f;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <img src="{{ url($siteSettings['logo']) }}" alt="Logo">
            </div>
            <!-- Body -->
            <div class="email-body">
                <h2>Xin chào {{ $user->full_name ?? 'bạn' }},</h2>
                <p>Bạn vừa gửi yêu cầu <strong>đặt lại mật khẩu</strong> tại
                    <strong>{{ getDomain() }}</strong>.
                </p>
                <p>Để tiếp tục, hãy nhấp vào nút bên dưới:</p>
                <div style="text-align:center;">
                    <a href="{{ $url }}" class="btn-reset">Đặt lại mật khẩu</a>
                </div>
                <div class="note-box">
                    <strong>Lưu ý quan trọng:</strong><br>
                    - Liên kết này chỉ có hiệu lực trong <strong>5 phút</strong>.<br>
                    - Nếu bạn không yêu cầu, vui lòng bỏ qua email này.
                </div>
                <p style="margin-top:25px; font-size:14px; color:#777;">
                    Nếu nút trên không hoạt động, hãy copy và dán liên kết này vào trình duyệt:
                    <br>
                    <a href="{{ $url }}">{{ $url }}</a>
                </p>
            </div>
            <!-- Footer -->
            <div class="email-footer">
                © {{ date('Y') }} {{ getDomain() }} - {{ $siteSettings['name'] }}<br>
                Cần hỗ trợ? Liên hệ với chúng tôi.
            </div>
        </div>
    </div>
</body>

</html>