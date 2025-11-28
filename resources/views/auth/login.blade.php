@extends('auth.layout.app')
@section('title', 'Đăng nhập tài khoản')

@section('content')
    <div class="auth-container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg border-0 rounded-4 bg-white bg-opacity-75 backdrop-blur p-4"
            style="max-width: 420px; width: 100%; z-index: 10;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ $siteSettings['logo'] }}" width="140" alt="Logo" class="mb-3">
                    </a>
                    <h4 class="fw-bold text-dark">Chào mừng trở lại!</h4>
                    <p class="text-muted small">Đăng nhập để tiếp tục sử dụng dịch vụ</p>
                </div>
                <form action="{{ route('login.process') }}" method="POST" class="needs-validation">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tài khoản</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa-solid fa-user text-muted"></i>
                            </span>
                            <input type="text" name="username" class="form-control shadow-sm" value="{{ old('username') }}"
                                placeholder="Nhập tài khoản...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input class="form-control shadow-sm" name="password" id="password" type="password"
                                placeholder="Nhập mật khẩu">
                            <span class="input-group-text toggle-password cursor-pointer bg-light">
                                <div class="icon-show">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <div class="icon-hide" style="display: none;">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3" id="otpTwofaGroup" style="display: none;">
                        <label class="form-label fw-semibold">Nhập mã OTP 2FA</label>
                        <input type="number" class="form-control text-center shadow-sm" name="twofa_code" maxlength="6"
                            placeholder="Nhập mã OTP...">
                        <small class="text-muted">Mã OTP được gửi qua 2FA</small>
                    </div>
                    <div class="mb-3" id="otpEmailGroup" style="display: none;">
                        <label class="form-label fw-semibold">Nhập mã OTP Mail</label>
                        <input type="number" class="form-control text-center shadow-sm" name="otp_email_code" maxlength="6"
                            placeholder="Nhập mã OTP...">
                        <small class="text-muted">Mã OTP được gửi qua Email</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember" checked>
                            <label class="form-check-label small text-muted ms-2" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>
                        <a href="{{ route('password.forgot') }}" class="small auth-link">Quên mật khẩu?</a>
                    </div>
                    @if($siteSettings['google_recaptcha'])
                        <div class="mb-3 text-center">
                            <div class="g-recaptcha d-inline-block" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                        </div>
                    @endif
                    <div class="d-grid">
                        <button class="btn btn-gradient rounded-3 shadow-sm py-2 fw-semibold">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng nhập
                        </button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <p class="text-muted mb-2">Bạn chưa có tài khoản?</p>
                    <a href="{{ route('register') }}"
                        class="btn btn-outline-gradient rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="fa-solid fa-user-plus me-2"></i> Đăng ký ngay
                    </a>
                </div>
                <div class="text-center mt-4">
                    <hr class="my-3">
                    <p class="fw-semibold text-dark mb-3">Hỗ trợ khách hàng</p>
                    <div class="d-flex justify-content-center gap-3">
                        @if(!empty($siteSettings['telegram']))
                            <a href="{{ $siteSettings['telegram'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm" title="Telegram">
                                <i class="fa-brands fa-telegram-plane"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['facebook']))
                            <a href="{{ $siteSettings['facebook'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm" title="Facebook">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['zalo']))
                            <a href="{{ $siteSettings['zalo'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm" title="Zalo">
                                <img src="/assets/images/client/zalo.png" style="height: 22px" alt="">
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="animated-bg"></div>
    </div>
@endsection

@section('css')
    <style>
        .btn-gradient {
            background: linear-gradient(135deg, #ff512f, #dd2476);
            border: none;
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: scale(1.05);
        }

        .btn-outline-gradient {
            border: 2px solid #ff512f;
            color: #ff512f;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #ff512f, #dd2476);
            color: #fff;
        }

        .auth-link {
            color: #ff512f;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection
@section('script')
    <script>
        @if(session('otpTwoFa'))
            $('#otpTwofaGroup').show();
        @endif
        @if(session('otpMail'))
            $('#otpEmailGroup').show();
        @endif
    </script>
@endsection