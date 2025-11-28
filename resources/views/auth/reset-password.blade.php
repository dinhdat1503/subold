@extends('auth.layout.app')

@section('title', 'Đặt mật khẩu mới')

@section('content')
    <div class="auth-container d-flex align-items-center justify-content-center min-vh-100 position-relative">
        <div class="animated-bg"></div>
        <div class="card shadow-lg border-0 rounded-4 bg-white bg-opacity-75 backdrop-blur p-4"
            style="max-width: 420px; width: 100%; z-index: 10;">
            <div class="card-body">

                {{-- Logo --}}
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ $siteSettings['logo'] }}" width="130" alt="Logo" class="mb-3">
                    </a>
                    <h4 class="fw-bold text-dark">Đặt lại mật khẩu</h4>
                    <p class="text-muted small">Nhập mật khẩu mới để khôi phục tài khoản của bạn</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('password.reset.update') }}" method="POST" class="needs-validation">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="token" value="{{ $token->token }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu mới</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control shadow-sm" name="password" id="password"
                                placeholder="Nhập mật khẩu mới" required>
                            <span class="input-group-text toggle-password cursor-pointer bg-light">
                                <div class="icon-show">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <div class="icon-hide" style="display: none;">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </div>
                            </span>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div id="passwordStrength" class="progress-bar bg-danger" style="width: 0%;"></div>
                        </div>
                        <small id="passwordStrengthText" class="text-muted d-block mt-1">Nhập mật khẩu để đánh giá độ
                            mạnh</small>
                    </div>

                    {{-- Xác nhận mật khẩu --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control shadow-sm" name="password_confirmation"
                                id="password_confirmation" placeholder="Nhập lại mật khẩu..." required>
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

                    {{-- Submit --}}
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-gradient rounded-3 shadow-sm py-2 fw-semibold">
                            <i class="fa-solid fa-unlock-keyhole me-2"></i> Xác nhận mật khẩu mới
                        </button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none text-primary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại đăng nhập
                    </a>
                </div>

                {{-- Hỗ trợ khách hàng --}}
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