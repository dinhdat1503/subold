@extends('auth.layout.app')
@section('title', 'Đăng ký tài khoản')

@section('content')
    <div class="auth-container d-flex align-items-center justify-content-center min-vh-100 position-relative">
        <div class="animated-bg"></div>

        <div class="card shadow-lg border-0 rounded-4 bg-white bg-opacity-75 backdrop-blur p-4"
            style="max-width: 480px; width: 100%; z-index: 10;">
            <div class="card-body">

                {{-- Logo --}}
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ $siteSettings['logo'] }}" width="140" alt="Logo" class="mb-3">
                    </a>
                    <h4 class="fw-bold text-dark">Tạo tài khoản mới</h4>
                    <p class="text-muted small">Điền thông tin bên dưới để bắt đầu hành trình của bạn</p>
                </div>

                {{-- FORM MULTI-STEP --}}
                <form action="{{ route('register.process') }}" method="POST" id="registerForm">
                    @csrf
                    {{-- STEP 1 --}}
                    <div id="step1">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-user"></i></span>
                                <input class="form-control shadow-sm" name="full_name" value="{{ old('full_name') }}"
                                    type="text" placeholder="Nhập họ và tên của bạn">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-envelope"></i></span>
                                <input class="form-control shadow-sm" name="email" value="{{ old('email') }}" type="email"
                                    placeholder="Nhập email hợp lệ">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên tài khoản</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-id-card"></i></span>
                                <input class="form-control shadow-sm" name="username" value="{{ old('username') }}"
                                    type="text" placeholder="Tối thiểu 8 ký tự, không dấu cách">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-gradient rounded-3 shadow-sm py-2 fw-semibold"
                                id="nextStep">
                                Tiếp tục <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div id="step2" style="display:none;">
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
                            <div class="progress mt-2" style="height: 6px;">
                                <div id="passwordStrength" class="progress-bar bg-danger" style="width: 0%;"></div>
                            </div>
                            <small id="passwordStrengthText" class="text-muted d-block mt-1">Nhập mật khẩu để đánh giá độ
                                mạnh</small>

                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                                <input class="form-control shadow-sm" name="password_confirmation"
                                    id="password_confirmation" type="password" placeholder="Nhập lại mật khẩu">
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

                        {{-- reCAPTCHA --}}
                        @if($siteSettings['google_recaptcha'])
                            <div class="mb-3 text-center">
                                <div class="g-recaptcha d-inline-block" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}">
                                </div>
                            </div>
                        @endif

                        {{-- Điều khoản --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="agree" name="agree_terms" value="1"
                                required>
                            <label class="form-check-label small text-muted" for="agree">
                                Tôi đồng ý với
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#termsModal"
                                    class="text-danger fw-semibold">Điều khoản & Dịch vụ</a>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-3 px-3" id="prevStep">
                                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                            </button>
                            <button class="btn btn-gradient rounded-3 shadow-sm px-4 fw-semibold" type="submit">
                                <i class="fa-solid fa-user-plus me-2"></i> Đăng ký
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Đăng nhập --}}
                <div class="text-center mt-4">
                    <p class="text-muted mb-2">Đã có tài khoản?</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-gradient rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng nhập ngay
                    </a>
                </div>

                {{-- Hỗ trợ --}}
                <div class="text-center mt-4">
                    <hr class="my-3">
                    <p class="fw-semibold text-dark mb-3">Hỗ trợ khách hàng</p>
                    <div class="d-flex justify-content-center gap-3">
                        @if($siteSettings['telegram'])
                            <a href="{{ $siteSettings['telegram'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm">
                                <i class="fa-brands fa-telegram"></i>
                            </a>
                        @endif
                        @if($siteSettings['facebook'])
                            <a href="{{ $siteSettings['facebook'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm">
                                <i class="fa-brands fa-facebook-messenger"></i>
                            </a>
                        @endif
                        @if($siteSettings['zalo'])
                            <a href="{{ $siteSettings['zalo'] }}" target="_blank"
                                class="btn btn-outline-primary rounded-circle shadow-sm">
                                <img src="/assets/images/client/zalo.png" style="height: 22px" alt="">
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Điều khoản --}}
        <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                    <div class="modal-header text-white py-3"
                        style="background: linear-gradient(135deg, #ff512f, #dd2476);">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-contract fa-lg"></i>
                            <span>Điều khoản & Dịch vụ</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                        {!! $siteSettings['terms'] !!}
                    </div>
                    <div class="modal-footer border-0 bg-light justify-content-center py-3">
                        <button class="btn btn-gradient rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check-circle me-2"></i> Tôi đã hiểu
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
            transition: 0.3s;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #ff512f, #dd2476);
            color: #fff;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#nextStep').click(function () {
                $('#step1').fadeOut(300, function () {
                    $('#step2').fadeIn(400);
                });
            });
            $('#prevStep').click(function () {
                $('#step2').fadeOut(300, function () {
                    $('#step1').fadeIn(400);
                });
            });

        });
    </script>
@endsection