<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Mã Xác Nhận OTP</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fafafa;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 0;
            background: #fafafa;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border-top: 6px solid #f44336;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header img {
            max-width: 150px;
        }

        h2 {
            color: #d32f2f;
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            color: #333;
            font-size: 15px;
            line-height: 1.6;
            margin: 10px 0;
        }

        .otp-box {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            text-align: center;
            color: #d32f2f;
            background: linear-gradient(135deg, #fff3e0, #fff8e1);
            border: 2px solid #fbc02d;
            border-radius: 10px;
            padding: 20px 30px;
            margin: 25px auto;
            width: fit-content;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .panel {
            background: #fff8e1;
            border-left: 5px solid #fbc02d;
            padding: 15px 18px;
            margin: 25px 0;
            border-radius: 8px;
            color: #555;
            font-size: 14px;
        }

        .panel ul {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        .panel li {
            margin-bottom: 5px;
        }

        .thankyou {
            text-align: center;
            font-size: 15px;
            margin-top: 20px;
            color: #333;
        }

        .footer {
            text-align: center;
            margin-top: 35px;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        a {
            color: #d32f2f;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <img src="{{ url($siteSettings['logo']) }}" alt="Logo">
            </div>

            <h2>Mã Xác Nhận OTP</h2>

            <p>Xin chào,</p>
            <p>Bạn vừa yêu cầu mã xác thực OTP cho tài khoản tại <strong>{{ getDomain() }}</strong>.</p>

            <div class="otp-box">
                {{ $otp }}
            </div>

            <div class="panel">
                <strong>Lưu ý:</strong>
                <ul>
                    <li>Mã OTP này chỉ có hiệu lực trong <strong>5 phút</strong>.</li>
                    <li>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</li>
                </ul>
            </div>

            <p class="thankyou">
                Cảm ơn bạn đã sử dụng dịch vụ của
                <strong>{{ $siteSettings['name'] ?? config('app.name') }}</strong>.
            </p>

            <div class="footer">
                © {{ date('Y') }} {{ getDomain() }} - {{ $siteSettings['name'] ?? config('app.name') }}
            </div>
        </div>
    </div>
</body>

</html>