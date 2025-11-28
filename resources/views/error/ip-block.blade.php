<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập bị chặn - IP Firewall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, #0f0f0f 0%, #1a1a1a 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Hiệu ứng nền động */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(45deg, rgba(255, 0, 0, 0.03) 0, rgba(255, 0, 0, 0.03) 2px, transparent 2px, transparent 6px);
            z-index: 0;
            animation: moveBg 6s linear infinite;
        }

        @keyframes moveBg {
            from {
                background-position: 0 0;
            }

            to {
                background-position: 100px 100px;
            }
        }

        .blocked-card {
            position: relative;
            z-index: 1;
            background: rgba(25, 25, 25, 0.9);
            border: 1px solid rgba(255, 0, 0, 0.3);
            border-radius: 15px;
            padding: 40px;
            max-width: 550px;
            text-align: center;
            box-shadow: 0 0 25px rgba(255, 0, 0, 0.25);
            animation: fadeIn 0.7s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .blocked-icon {
            font-size: 80px;
            color: #ff4d4d;
            animation: flicker 1.8s infinite alternate;
        }

        @keyframes flicker {
            0% {
                opacity: 1;
                text-shadow: 0 0 10px #ff4d4d, 0 0 20px #ff0000;
            }

            50% {
                opacity: 0.7;
                text-shadow: 0 0 5px #ff6666;
            }

            100% {
                opacity: 1;
                text-shadow: 0 0 15px #ff4d4d;
            }
        }

        h1 {
            font-weight: 800;
            color: #ff4d4d;
            margin-top: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        p {
            color: #ccc;
            margin-bottom: 10px;
        }

        .alert-box {
            background: rgba(255, 0, 0, 0.1);
            border-left: 4px solid #ff4d4d;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        .support-links {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .support-links a {
            text-decoration: none;
            color: #fff;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .facebook {
            background: #1877f2;
        }

        .facebook:hover {
            background: #145dbf;
        }

        .zalo {
            background: #0068ff;
        }

        .zalo:hover {
            background: #0052c9;
        }

        .telegram {
            background: #0088cc;
        }

        .telegram:hover {
            background: #006da1;
        }

        .btn-home {
            background: linear-gradient(135deg, #ff4d4d, #ff7575);
            border: none;
            border-radius: 50px;
            color: #fff;
            font-weight: 600;
            padding: 12px 30px;
            margin-top: 30px;
            transition: 0.3s;
        }

        .btn-home:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #ff7575, #ff4d4d);
        }
    </style>
</head>

<body>
    <div class="blocked-card">
        <i class="fa-solid fa-shield-halved blocked-icon"></i>
        <h1>IP Bị Chặn</h1>
        <p><strong>Địa chỉ IP:</strong> {{ $ip ?? request()->ip() }}</p>
        <div class="alert-box">
            <strong>Lý do:</strong> {{ $reason ?? 'Hệ thống phát hiện truy cập bất thường hoặc nghi ngờ tấn công.' }}
        </div>

        <p class="mt-3">Nếu bạn nghĩ đây là nhầm lẫn, hãy liên hệ bộ phận hỗ trợ qua các kênh sau:</p>

        <div class="support-links">
            @if(!empty($siteSettings['facebook']))
                <a href="{{ $siteSettings['facebook'] }}" class="facebook" target="_blank">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
            @endif
            @if(!empty($siteSettings['zalo']))
                <a href="{{ $siteSettings['zalo'] }}" class="zalo" target="_blank">
                    <i class="fa-solid fa-comments"></i> Zalo
                </a>
            @endif
            @if(!empty($siteSettings['telegram']))
                <a href="{{ $siteSettings['telegram'] }}" class="telegram" target="_blank">
                    <i class="fab fa-telegram-plane"></i> Telegram
                </a>
            @endif
        </div>

        <a href="{{ url('/') }}" class="btn btn-home mt-4">
            <i class="fa-solid fa-house"></i> Quay lại Trang chủ
        </a>
    </div>
</body>

</html>