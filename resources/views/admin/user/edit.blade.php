@extends('admin.layout.app')
@section('title', 'Chỉnh sửa thành viên')

@section('content')
    <div class="row form-edit">
        <div class="col-lg-6">
            {{-- Thông tin cơ bản --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-user text-primary me-2"></i> Thông tin cơ bản</h5>
                    <form action="{{ route('admin.user.update', ["info", $user->id]) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="row g-3">

                            {{-- Avatar --}}
                            <div class="col-md-12 d-flex align-items-center gap-3 mb-3">
                                <img src="{{ old('avatar_url', $user->avatar_url) ?? '/assets/images/guest/profile/user-1.jpg'}}"
                                    class="avatar-preview">
                                <div class="flex-grow-1">
                                    <label class="form-label"><span class="label-icon"><i
                                                class="fa-solid fa-image"></i></span>
                                        Avatar URL</label>
                                    <input type="text" class="form-control" name="avatar_url"
                                        value="{{ old('avatar_url', $user->avatar_url) }}">
                                </div>
                            </div>

                            {{-- Họ tên + Email --}}
                            <div class="col-md-6">
                                <label class="form-label"><span class="label-icon"><i class="fa-solid fa-user"></i></span>
                                    Họ và
                                    tên</label>
                                <input type="text" class="form-control" name="full_name"
                                    value="{{ old('full_name', $user->full_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><span class="label-icon"><i
                                            class="fa-solid fa-envelope"></i></span>
                                    Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}">
                            </div>

                            {{-- Username + Vai trò --}}
                            <div class="col-md-6">
                                <label class="form-label"><span class="label-icon"><i
                                            class="fa-solid fa-id-badge"></i></span>
                                    Username</label>
                                <input type="text" class="form-control" name="username"
                                    value="{{ old('username', $user->username) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><span class="label-icon"><i
                                            class="fa-solid fa-user-gear"></i></span>
                                    Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="member" @if(old('role', $user->role) == 'member') selected @endif>Thành
                                        viên</option>
                                    <option value="ctv" @if(old('role', $user->role) == 'ctv') selected @endif>CTV</option>
                                    <option value="admin" @if(old('role', $user->role) == 'admin') selected @endif>Admin
                                    </option>
                                </select>
                            </div>

                            {{-- Level + Status --}}
                            <div class="col-md-6">
                                <label class="form-label"><span class="label-icon"><i class="fa-solid fa-award"></i></span>
                                    Cấp
                                    độ</label>
                                <select name="level" class="form-select">
                                    <option value="1" @if(old('level', $user->level) == 1) selected @endif>Thành viên</option>
                                    <option value="2" @if(old('level', $user->level) == 2) selected @endif>CTV</option>
                                    <option value="3" @if(old('level', $user->level) == 3) selected @endif>Đại lý</option>
                                    <option value="4" @if(old('level', $user->level) == 4) selected @endif>Nhà phân phối
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-flex align-items-center justify-content-start mb-2">
                                    <span class="label-icon"></span>
                                    Trạng thái
                                </label>
                                <div class="d-flex align-item-center px-1 py-1">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            id="statusSwitch" @if(old('status', $user->status)) checked @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 d-grid">
                                <button type="submit" class="btn btn-primary py-2"><i class="fa-solid fa-save me-1"></i> Lưu
                                    thay
                                    đổi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tài chính --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-coins text-success me-2"></i> Tài chính</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tổng nạp</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ formatMoney($user->total_recharge, "VNĐ", 2) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số dư</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ formatMoney($user->balance, "VNĐ", 2) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Đã tiêu</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ formatMoney($user->total_deduct, "VNĐ", 2) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Khuyến mãi</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ formatMoney($user->recharge_promotion, "VNĐ", 2) }}" readonly>
                        </div>
                        <form action="{{ route('admin.user.update', ['money', $user->id]) }}" method="POST" class="g-3">
                            @csrf
                            @method("PUT")
                            <!-- Nhóm thay đổi số dư + hành động -->
                            <div class="col-md-12">
                                <label class="form-label">
                                    <span class="label-icon"><i class="fa-solid fa-money-bill"></i></span> Thay đổi số dư
                                </label>
                                <div class="input-group">
                                    <input type="number" name="balance_delta" class="form-control"
                                        value="{{ old('balance_delta', 0) }}" step="any">
                                    <select name="balance_action" class="form-select" style="max-width: 200px;">
                                        <option value="add_no_total" @if(old('balance_action') == 'add_no_total') selected
                                        @endif>+ Cộng</option>
                                        <option value="add_with_total" @if(old('balance_action') == 'add_with_total') selected
                                        @endif>++ Cộng (Tổng nạp)</option>
                                        <option value="sub_with_total" @if(old('balance_action') == 'sub_with_total') selected
                                        @endif>- Trừ</option>
                                        <option value="sub_no_total" @if(old('balance_action') == 'sub_no_total') selected
                                        @endif>-- Trừ (Tổng nạp)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Nút cập nhật -->
                            <div class="col-md-12 d-grid mt-2">
                                <button type="submit" class="btn btn-warning py-2">
                                    <i class="fa-solid fa-money-bills me-1"></i> Cập nhật số dư
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- Thông tin hệ thống --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-shield text-info me-2"></i> Hệ thống</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">IP</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->last_ip }}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">User Agent</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->last_useragent }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Online</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->last_online }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày tạo</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->created_at }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            {{-- Bảo mật --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-shield-halved text-danger me-2"></i> Bảo mật tài khoản
                    </h5>
                    <form id="formSecurity" action="{{ route('admin.user.update', ['security', $user->id]) }}"
                        method="POST">
                        @csrf
                        @method("PUT")
                        <div class="row g-3 align-items-start">
                            <div class="col-md-4">
                                <label class="form-label">Login Lỗi</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ $user->security?->attempt_login ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Action Lỗi</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ $user->security?->attempt_error ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Lý do khóa</label>
                                <input type="text" class="form-control bg-light text-danger fw-bold"
                                    value="{{ $user->security?->banned_reason ?? 'Không có' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-flex align-items-center gap-2 mb-2"> 2FA</label>
                                <div class="border rounded p-3">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $user->security?->twofa_secret ?? 'Chưa kích hoạt' }}" readonly>
                                        <button type="button" data-action="twofa" class="btn btn-outline-danger">
                                            <i class="fa-solid fa-arrow-rotate-right"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="form-check form-switch m-0 d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" name="twofa_enabled"
                                                value="1" id="switch2FA" @checked($user->security?->twofa_enabled)>
                                            <label class="form-check-label mb-0" for="switch2FA">
                                                {{ $user->security?->twofa_enabled ? 'Đang bật' : 'Đang tắt' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- OTP Email --}}
                            <div class="col-md-6">
                                <label class="form-label d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-envelope text-success"></i> OTP Email
                                </label>

                                <div class="border rounded p-3 d-flex align-items-center justify-content-between">
                                    <input type="text" class="form-control bg-light me-2"
                                        value="{{ $user->security?->otp_email_enabled ? 'Đang bật' : 'Đang tắt' }}" readonly>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="otp_email_enabled" value="1"
                                            id="switchOtpEmail" @checked($user->security?->otp_email_enabled)>
                                    </div>
                                </div>
                            </div>

                            {{-- API Token --}}
                            <div class="col-md-12">
                                <label class="form-label d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-key text-warning"></i> API Token
                                </label>

                                <div class="input-group">
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $user->security?->api_token }}" readonly>
                                    <button type="button" data-action="api-token" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-arrow-rotate-right"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center mt-3 mx-1">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fa-solid fa-save me-1"></i> Lưu thay đổi bảo mật
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Đổi mật khẩu --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-key text-primary me-2"></i> Đổi mật khẩu</h5>
                    <form action="{{ route('admin.user.update', ["password", $user->id]) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <div class="col-md-12 d-grid">
                                <button class="btn btn-primary"><i class="fa-solid fa-shield me-1"></i> Đổi mật
                                    khẩu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <style>
        .form-edit .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .form-edit .form-control,
        .form-edit .form-select {
            border-radius: 8px;
            padding: .6rem .9rem;
        }

        .form-edit label span.label-icon {
            margin-right: .5rem;
            color: #0d6efd;
        }

        .avatar-preview {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
        }

        .form-switch .form-check-input {
            width: 3rem;
            height: 1.5rem;
        }
    </style>
@endsection
@section("script")
    <script>
        $(document).ready(function () {
            const userId = "{{ $user->id }}";
            $(document).on("click", "[data-action]", function () {
                const action = $(this).data("action");
                if (!action) {
                    toastr.error("Không xác định hành động!");
                    return;
                }
                const url = "{{ route('admin.user.update', [':type', ':id']) }}"
                    .replace(':type', action)
                    .replace(':id', userId);

                swal({
                    title: "Đang xử lý...",
                    text: "Vui lòng chờ trong giây lát...",
                    icon: "info",
                    showLoading: true,
                });

                ajaxRequest(url, "PUT", {}, function (res) {
                    swal({
                        title: "Thành công",
                        text: "Đã reset thành công!",
                        icon: "success",
                    });
                    window.location.reload();
                });
            });
        });
    </script>
@endsection