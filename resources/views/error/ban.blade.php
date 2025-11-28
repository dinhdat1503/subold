<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản bị khóa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa, #ffe2e2);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }

        .ban-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 30px;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.6s ease-in-out;
        }

        .ban-icon {
            font-size: 70px;
            color: #dc3545;
            margin-bottom: 20px;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            font-size: 1.9rem;
            font-weight: 700;
            color: #dc3545;
        }

        p {
            color: #555;
            font-size: 1rem;
        }

        .support-links a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #f1f1f1;
            border-radius: 50px;
            padding: 10px 20px;
            margin: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .support-links a:hover {
            transform: scale(1.05);
            color: #fff;
        }

        .facebook:hover {
            background: #1877f2;
        }

        .zalo:hover {
            background: #0068ff;
        }

        .telegram:hover {
            background: #0088cc;
        }

        .btn-home {
            margin-top: 25px;
            border-radius: 50px;
            background: linear-gradient(135deg, #ff6b6b, #f94d6a);
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #f94d6a, #ff6b6b);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="ban-card">
        <i class="fa-solid fa-user-lock ban-icon"></i>
        <h1>Tài khoản bị khóa</h1>
        <p><strong>Địa chỉ IP:</strong> {{ $ip ?? request()->ip() }}</p>
        <p><strong>Lý do:</strong> {{ $reason ?? 'Hệ thống phát hiện hoạt động bất thường hoặc vi phạm chính sách.' }}
        </p>
        <hr>
        <p class="text-muted mb-3">Nếu bạn cho rằng đây là nhầm lẫn, vui lòng liên hệ bộ phận hỗ trợ qua các kênh sau:
        </p>
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