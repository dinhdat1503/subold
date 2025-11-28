@extends('client.layout.app')
@section('title', 'Thông tin cá nhân')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body py-0">
                    <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1" role="tab"
                                aria-selected="true">
                                <i class="ti ti-user me-2"></i> Thông tin
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2" role="tab"
                                aria-selected="false" tabindex="-1">
                                <i class="ti ti-lock me-2"></i> Thay đổi mật khẩu
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3" role="tab"
                                aria-selected="false" tabindex="-1">
                                <i class="ti ti-shield-lock me-2"></i> Bảo mật
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active show" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Thông Tin Tài Khoản</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 text-center mb-3">
                                            <div class="user-upload wid-75">
                                                <img src="{{ Auth::user()->avatar_url }}" alt="" class="img-fluid">
                                            </div>
                                        </div>
                                        <form action="{{ route('user.profile.update', 'profile') }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-6 mb-3">
                                                    <label class="form-label" for="">Họ và tên</label>
                                                    <input type="text" class="form-control" name="full_name"
                                                        value="{{ old('full_name', Auth::user()->full_name) }}">
                                                </div>
                                                <div class="form-group col-md-6 mb-3">
                                                    <label class="form-label" for="">Email</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->email }}" disabled="">
                                                </div>
                                                <div class="form-group col-md-6 mb-3">
                                                    <label class="form-label" for="">Tài khoản</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->username }}" disabled="">
                                                </div>

                                                <div class="form-group col-md-6 mb-3">
                                                    <label class="form-label" for="">Cấp độ</label>
                                                    <input type="text" class="form-control" value="{{ userLevel(false) }}"
                                                        disabled="">
                                                </div>

                                                <div class="form-group col-md-6 mb-3">
                                                    <label class="form-label" for="">Thời gian tham gia</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->created_at }}" disabled="">
                                                </div>
                                                <div class="form-group col-md-6 mb-3">
                                                    <label class="form-label" for="">Địa chỉ IP</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->last_ip }}" disabled="">
                                                </div>

                                                <div class="form-group col-md-12 mb-3">
                                                    <label class="form-label" for="">Link ảnh đại diện</label>
                                                    <input type="text" class="form-control" name="avatar_url"
                                                        placeholder="/assets/images/guest/profile/user-1.jpg"
                                                        value="{{ old('avatar_url', Auth::user()->avatar_url) }}">
                                                    <small class="text-muted d-block mt-1">
                                                        Có Thể Thay Đổi Ảnh Đại Diện Bằng Cách Thay Đổi Link
                                                    </small>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="form-label" for="">Api Token</label>
                                                    <div class="input-group">
                                                        <input class="form-control copy" type="text"
                                                            value="{{ Auth::user()->security->api_token }}" id="apiToken"
                                                            readonly>
                                                        <button type="button" data-type="change" data-value="api-token"
                                                            class="btn btn-primary"><i class="fa fa-sync"></i> Thay
                                                            đổi</button>
                                                    </div>
                                                    <small class="text-danger d-block mt-1">
                                                        Lưu ý! Không được tiết lộ Api Token ra ngoài nếu không kẻ gian có
                                                        thể sử dụng để mua dịch vụ trên tài khoản của bạn
                                                    </small>
                                                </div>
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-primary col-12">
                                                        <i class="ti ti-device-floppy me-2 fs-4"></i>
                                                        Lưu thông tin
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Tài Chính</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avtar avtar-xs btn-light-twitter">
                                                        <i class="fas fa-coins f-16"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">Đã Nạp:</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button
                                                class="btn btn-link-primary">{{ formatMoney(Auth::user()->total_recharge) }}</button>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avtar avtar-xs btn-light-facebook">
                                                        <i class="fas fa-coins f-16"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">Số Dư:</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button
                                                class="btn btn-link-success">{{ formatMoney(Auth::user()->balance) }}</button>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avtar avtar-xs btn-light-linkedin">
                                                        <i class="fas fa-coins f-16"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">Đã Tiêu</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button
                                                class="btn btn-link-danger">{{ formatMoney(Auth::user()->total_deduct) }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5>Thống Kê</h5>
                                </div>
                                <div class="card-body">
                                    <div id="monthlyRevenueChart" class="bg-light p-2 rounded shadow-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Thay Đổi Mật Khẩu</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <form action="{{ route('user.profile.update', 'password') }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3 position-relative">
                                                <label class="form-label">Mật khẩu cũ</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="current_password"
                                                        id="current_password" placeholder="Nhập mật khẩu cũ">
                                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                                        data-target="#current_password">
                                                        <div class="icon-show">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </div>
                                                        <div class="icon-hide" style="display: none;">
                                                            <i class="fa-solid fa-eye-slash"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mb-3 position-relative">
                                                <label class="form-label">Mật khẩu mới</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password"
                                                        id="password" placeholder="Nhập mật khẩu mới">
                                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                                        data-target="#password">
                                                        <div class="icon-show">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </div>
                                                        <div class="icon-hide" style="display: none;">
                                                            <i class="fa-solid fa-eye-slash"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mb-3 position-relative">
                                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password_confirmation"
                                                        id="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                                        data-target="#password_confirmation">
                                                        <div class="icon-show">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </div>
                                                        <div class="icon-hide" style="display: none;">
                                                            <i class="fa-solid fa-eye-slash"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-lock"></i> Thay đổi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fa fa-shield-alt me-2"></i> Cài Đặt Bảo Mật</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('user.profile.update', 'security') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            {{-- Google Authenticator --}}
                                            <div class="col-md-6 mb-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-otp" type="checkbox"
                                                        id="twofaEnabled" name="twofa_enabled" value="1" {{ Auth::user()->security->twofa_enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="twofaEnabled">
                                                        <i class="fa fa-mobile-alt me-2 text-primary"></i> Bật 2FA (Google
                                                        Authenticator)
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    Khi bật, bạn sẽ cần dùng ứng dụng Google Authenticator để quét mã QR và
                                                    nhập mã OTP mỗi lần đăng nhập.
                                                </small>
                                                <div id="twofaSection" class="border rounded p-3 mt-3 bg-light">
                                                    <p><strong>Mã bí mật (Secret Key):</strong></p>
                                                    <div class="input-group mb-2">
                                                        <input id="twofaSecret" type="text"
                                                            class="form-control text-center copy"
                                                            value="{{ Auth::user()->security->twofa_secret }}" readonly>
                                                        <button type="button" data-type="change" data-value="two-fa"
                                                            class="btn btn-outline-danger">
                                                            <i class="fa fa-sync-alt"></i> Đổi mã
                                                        </button>
                                                    </div>
                                                    <div class="text-center">
                                                        <img id="twofaQrImg" src="{{ Auth::user()->security->twofa_qr }}"
                                                            alt="QR Code 2FA" class="img-fluid my-2"
                                                            style="max-width:200px;">
                                                        <p><small>Quét mã QR bằng Google Authenticator.</small></p>
                                                    </div>

                                                    {{-- Input OTP --}}
                                                    <div id="twofaOtpInput" class="mt-3" style="display:none;">
                                                        <label class="form-label">Nhập mã OTP để xác nhận</label>
                                                        <input type="text" name="twofa_code"
                                                            class="form-control text-center" maxlength="6">
                                                        <small class="text-muted">
                                                            Mã OTP gồm 6 số, thay đổi sau mỗi 30 giây trên ứng dụng Google
                                                            Authenticator.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- OTP Gmail --}}
                                            <div class="col-md-6 mb-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-otp" type="checkbox" id="otpEmail"
                                                        name="otp_email_enabled" value="1" {{ Auth::user()->security->otp_email_enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="otpEmail">
                                                        <i class="fa fa-envelope me-2 text-info"></i> Nhận mã OTP qua Email
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    Khi bật, hệ thống sẽ gửi mã xác thực về email của bạn. Bạn cần nhập mã
                                                    này để hoàn tất đăng nhập.
                                                </small>
                                                <div id="emailSection" class="border rounded p-3 mt-3 bg-light text-center">
                                                    <p><strong>Email xác thực:</strong></p>
                                                    <div class="input-group mb-2">
                                                        <input id="otpEmailAddr" type="text"
                                                            class="form-control text-center copy"
                                                            value="{{ Auth::user()->email }}" readonly>
                                                    </div>
                                                    <div id="emailOtpInput" class="mt-3" style="display:none;">
                                                        <label class="form-label d-block">Nhập mã OTP từ email</label>
                                                        <div class="input-group">
                                                            <input type="text" name="otp_email_code"
                                                                class="form-control text-center" maxlength="6">
                                                            <button type="button" id="sendOtpEmail"
                                                                class="btn btn-outline-primary">
                                                                <i class="fa fa-paper-plane"></i> Gửi mã
                                                            </button>
                                                        </div>
                                                        <small class="text-muted d-block mt-2">
                                                            Sau khi bấm <strong>Gửi mã</strong>, hãy kiểm tra hộp thư email
                                                            và nhập 6 số OTP để xác nhận.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save me-2"></i> Lưu cài đặt
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="/assets/libs/apexcharts-bundle/dist/apexcharts.min.js"></script>
    <script>
        var spentData = @json($spentData);
        var ordersData = @json($ordersData);
        var options = {
            chart: {
                type: 'area',
                height: 350,
                stacked: false,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                zoom: { enabled: true },
                animations: { enabled: true, easing: 'easeinout' }
            },
            colors: ['#48bb78', '#4299e1'],
            series: [{
                name: 'Đã Tiêu',
                data: spentData
            },
            {
                name: 'Đơn hàng',
                data: ordersData
            }],
            dataLabels: {
                enabled: false
            },
            fill: {
                opacity: [0.8, 0.5],
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: [0.8, 0.5],
                    opacityTo: [0.2, 0.1],
                    stops: [0, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#38a169', '#3182ce']
            },
            xaxis: {
                categories: [
                    'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                ],
                axisBorder: { show: false },
                tickPlacement: 'on'
            },
            yaxis: [
                {
                    axisTicks: { show: true },
                    axisBorder: { show: true, color: '#4299e1' },
                    labels: {
                        style: { colors: '#4299e1' },
                        formatter: function (value) {
                            return (value / 1000).toFixed(0) + 'K';
                        }
                    },
                    title: { text: 'Đã Tiêu', style: { color: '#4299e1' } }
                },
                {
                    opposite: true,
                    axisTicks: { show: true },
                    axisBorder: { show: true, color: '#48bb78' },
                    labels: { style: { colors: '#48bb78' } },
                    title: { text: 'Đơn hàng', style: { color: '#48bb78' } }
                }
            ],
            grid: {
                borderColor: '#E5E7EB',
                strokeDashArray: 3,
                padding: { left: 10, right: 10, top: 0, bottom: 0 }
            },
            markers: {
                size: 0,
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 5 }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (value, { seriesIndex }) {
                        if (seriesIndex === 0) {
                            return formatMoney(value);
                        } else {
                            return formatNumber(value) + ' đơn';
                        }
                    }
                }
            },
            legend: {
                show: true,
                position: 'top',
            }
        };
        var chart = new ApexCharts($("#monthlyRevenueChart").get(0), options);
        chart.render();
    </script>
    <script>
        $(".copy").on("click", function () {
            let text = $(this).val();
            copyToClipboard(text);
        });
        $('[data-type="change"]').click(function () {
            const actionType = $(this).data('value');
            const updateUrl = "{{ route('user.profile.update', ['type' => 'REPLACE_ME']) }}";
            const finalUrl = updateUrl.replace('REPLACE_ME', actionType);
            const successText = (actionType === 'api-token') ? "Thay đổi API Token thành công" : "Đổi mã 2FA thành công";
            swal({
                title: "'Đang xử lý...",
                text: 'Vui lòng chờ trong giây lát',
                showLoading: true,
            });
            ajaxRequest(
                finalUrl,
                "PUT",
                {},
                function (res) {
                    if (res.status === "success") {
                        if (actionType === 'api-token') {
                            $('#apiToken').val(res.api_token);
                        } else if (actionType === 'two-fa') {
                            $('#twofaSecret').val(res.secret);
                            $('#twofaQrImg').attr('src', res.qr);
                        }
                        swal({
                            text: successText,
                            icon: "success"
                        });
                    } else {
                        swal({
                            text: res.message ?? "Thay đổi thất bại!",
                            icon: "error"
                        });
                    }
                }
            );
        });
    </script>
    <script>
        $(document).ready(function () {
            let initialTwoFA = $("#twofaEnabled").is(":checked");
            let initialEmail = $("#otpEmail").is(":checked");
            $("#twofaOtpInput, #emailOtpInput").hide();
            $("#twofaEnabled").on("change", function () {
                if ($(this).is(":checked") !== initialTwoFA) {
                    $("#twofaOtpInput").slideDown();
                } else {
                    $("#twofaOtpInput").slideUp();
                }
            });
            $("#otpEmail").on("change", function () {
                if ($(this).is(":checked") !== initialEmail) {
                    $("#emailOtpInput").slideDown();
                } else {
                    $("#emailOtpInput").slideUp();
                }
            });
            $("#sendOtpEmail").click(function () {
                swal({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    showLoading: true,
                });
                ajaxRequest(
                    "{{ route('user.profile.update', 'otp-mail') }}",
                    "PUT",
                    {},
                    function (res) {
                        if (res.status === "success") {
                            swal({
                                text: "Mã OTP đã được gửi về email!",
                                icon: "success"
                            });
                        } else {
                            swal({
                                text: res.message ?? "Gửi OTP thất bại!",
                                icon: "error"
                            });
                        }
                    }
                );
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.toggle-password').on('click', function () {
                let $input = $(this).closest(".input-group").find("input");
                let $icon_show = $(this).find(".icon-show");
                let $icon_hide = $(this).find(".icon-hide");

                if ($input.attr("type") === "password") {
                    $input.attr("type", "text");
                    $icon_show.hide();
                    $icon_hide.show();
                } else {
                    $input.attr("type", "password");
                    $icon_show.show();
                    $icon_hide.hide();
                }
            });
        });
    </script>
@endsection