@extends('auth.layout.app')

@section('title', 'Quên mật khẩu')

@section('content')
    <div class="auth-container d-flex align-items-center justify-content-center min-vh-100 position-relative">
        <div class="animated-bg"></div>
        <div class="card shadow-lg border-0 rounded-4 bg-white bg-opacity-75 backdrop-blur p-4"
            style="max-width: 420px; width: 100%; z-index: 10;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ $siteSettings['logo'] }}" width="130" alt="Logo" class="mb-3">
                    </a>
                    <h4 class="fw-bold text-dark">Quên mật khẩu</h4>
                    <p class="text-muted small">Nhập địa chỉ email để nhận liên kết đặt lại mật khẩu</p>
                </div>
                <form action="{{ route('password.forgot.send') }}" method="POST" class="needs-validation">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Địa chỉ Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control border-start-0 rounded-end-3 shadow-sm" name="email"
                                value="{{ old('email') }}" placeholder="Nhập email đã đăng ký..." required>
                        </div>
                    </div>
                    @if($siteSettings['google_recaptcha'])
                        <div class="mb-3 text-center">
                            <div class="g-recaptcha d-inline-block" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                        </div>
                    @endif
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-gradient rounded-3 shadow-sm py-2 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-2"></i> Gửi liên kết đặt lại
                        </button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none text-primary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại đăng nhập
                    </a>
                </div>
                <div class="text-center mt-4">
                    <hr class="my-3">
                    <p class="fw-semibold text-dark mb-3">Hỗ trợ khách hàng</p>
                    <div class="d-flex justify-content-center gap-3">
                        @if(!empty($siteSettings['telegram']))
                            <a href="{{ $siteSettings['telegram'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm" title="Telegram">
                                <i class="fa-brands fa-telegram"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['facebook']))
                            <a href="{{ $siteSettings['facebook'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm" title="Facebook Messenger">
                                <i class="fa-brands fa-facebook-messenger"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['zalo']))
                            <a href="{{ $siteSettings['zalo'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm" title="Zalo Chat">
                                <img src="/assets/images/client/zalo.png" style="height: 22px" alt="">
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('css')
    <style>
        .btn-gradient {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
            border: none;
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: scale(1.03);
            opacity: 0.95;
        }
    </style>
@endsection