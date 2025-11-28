<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảo trì hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', 'Roboto', sans-serif;
            background: radial-gradient(circle at top right, #667eea, #764ba2);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Hiệu ứng animation nền */
        .bg-effect {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: moveBg 10s linear infinite;
            z-index: 0;
        }

        @keyframes moveBg {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(40px, 40px);
            }
        }

        .maintenance-card {
            position: relative;
            z-index: 1;
            text-align: center;
            background: rgba(0, 0, 0, 0.3);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 1s ease;
            max-width: 600px;
            width: 90%;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .maintenance-icon {
            font-size: 80px;
            color: #ffd166;
            margin-bottom: 20px;
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        h1 {
            font-size: 2.3rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.1rem;
            color: #eee;
            margin-bottom: 30px;
        }

        .btn-refresh {
            display: inline-block;
            background: linear-gradient(135deg, #ff9800, #fbc531);
            color: #fff;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-refresh:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #fbc531, #ff9800);
        }

        footer {
            margin-top: 25px;
            font-size: 0.95rem;
            color: #ddd;
        }

        footer i {
            color: #ffcc00;
        }

        @media (max-width: 576px) {
            .maintenance-card {
                padding: 35px 25px;
            }

            h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="bg-effect"></div>
    <div class="maintenance-card">
        <i class="fa-solid fa-gear maintenance-icon"></i>
        <h1>Website đang bảo trì</h1>
        <p>Chúng tôi đang nâng cấp hệ thống để mang đến trải nghiệm tốt hơn.<br>
            Vui lòng quay lại sau ít phút nữa</p>
        <a href="/" class="btn-refresh">
            <i class="fa-solid fa-rotate-right me-2"></i> Thử lại
        </a>

        <footer class="mt-4">
            &copy; {{ date('Y') }} - <strong>Hệ thống đang bảo trì</strong>. Cảm ơn bạn đã kiên nhẫn ❤️
        </footer>
    </div>
</body>

</html>