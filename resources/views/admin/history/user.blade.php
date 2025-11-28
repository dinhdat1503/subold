@extends('admin.layout.app')
@section('title', 'Lịch sử người dùng')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Nhật kí hoạt động</h4>
                    <div class="mb-3">
                        <div class="row m-1">
                            <div class="col-md-4 p-2">
                                <label for="filterDateRange" class="form-label fw-semibold">Khoảng ngày</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-calendar-alt text-secondary"></i>
                                    </span>
                                    <input type="text" id="filterDateRange" class="form-control"
                                        placeholder="Chọn khoảng ngày">
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <label for="filterType" class="form-label fw-semibold">Loại
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-filter text-secondary me-1"></i> </span>
                                    <select id="filterType" class="form-control selectpicker" data-live-search="true"
                                        title="Chọn loại...">
                                        <option value="">-- Tất cả --</option>
                                        <optgroup label="Người dùng">
                                            <option value="Login">Đăng Nhập</option>
                                            <option value="Register">Đăng Ký</option>
                                            <option value="RequestChangePassword">Yêu Cầu Đổi Mật Khẩu</option>
                                            <option value="Balance">Thay Đổi Số Dư</option>
                                            <option value="ChangePassword">Đổi Mật Khẩu</option>
                                            <option value="ChangeProfile">Thay Đổi Thông Tin Cá Nhân</option>
                                            <option value="ChangeApiKey">Thay Đổi Api Key</option>
                                            <option value="RegenTwoFA">Tạo Lại Mã 2FA</option>
                                            <option value="UpdateSecurity">Thay Đổi Bảo Mật</option>
                                            <option value="SendOtpEmail">Yêu Cầu Mã OTP Mail</option>
                                        </optgroup>
                                        <optgroup label="Quản trị viên">
                                            <option value="AdminSettings">AdminSettings</option>
                                            <option value="AdminNotification">AdminNotification</option>
                                            <option value="AdminActivity">AdminActivity</option>
                                            <option value="AdminSupplier">AdminSupplier</option>
                                            <option value="AdminUser">AdminUser</option>
                                            <option value="AdminIPBlock">AdminIPBlock</option>
                                            <option value="AdminRecharge">AdminRecharge</option>
                                            <option value="AdminService">AdminService</option>
                                            <option value="AdminBlog">AdminBlog</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="history" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tài Khoản</th>
                                        <th>Loại</th>
                                        <th>Số dư trước</th>
                                        <th>Số tiền thay đổi</th>
                                        <th>Số dư hiện tại</th>
                                        <th>Ghi chú</th>
                                        <th>IP</th>
                                        <th>Useragent</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="/assets/libs/DataTables/datatables.min.css">
    <link rel="stylesheet" href="/assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.css">
    <style>
        #history th,
        #history td {
            text-align: center;
            vertical-align: middle;
        }

        #history {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .bootstrap-select .dropdown-menu .dropdown-header {
            font-weight: bold !important;
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script src="/assets/libs/flatpickr/flatpickr.js"></script>
    <script src="/assets/libs/flatpickr/dist/vn.js"></script>
    <script src="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.js"></script>
    <script>
        initFlatpickr("#filterDateRange");
        let tableHistory = createTable('#history', '{{ route('admin.user.logs.data', "all") }}',
            [
                {
                    data: 'id',
                    render: function (data) {
                        return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                    }
                },
                {
                    data: 'username',
                    render: function (data) {
                        return `<i class="fa fa-user text-primary me-1"></i> <span class="fw-semibold">${data}</span>`;
                    }
                },
                {
                    data: 'action_type',
                    render: function (data) {
                        return statusLog(data, true); // bạn đã có hàm custom icon cho action
                    }
                },
                {
                    data: 'old_value',
                    render: function (data) {
                        return `<span class="badge bg-secondary"><i class="fa fa-wallet me-1"></i> ${formatMoney(data)}</span>`;
                    }
                },
                {
                    data: 'value',
                    render: function (data) {
                        if (data == null) return '';
                        let num = parseFloat(data);
                        let formatted = formatMoney(num);

                        if (num > 0) {
                            return `<span class="badge bg-success"></i> +${formatted}</span>`;
                        } else if (num < 0) {
                            return `<span class="badge bg-danger"></i> ${formatted}</span>`;
                        } else {
                            return `<span class="badge bg-dark"> 0</span>`;
                        }
                    }
                },
                {
                    data: 'new_value',
                    render: function (data) {
                        return `<span class="badge bg-info"><i class="fa fa-coins me-1"></i> ${formatMoney(data)}</span>`;
                    }
                },
                {
                    data: 'description',
                    render: function (data) {
                        return `<div class="text-break form-control" style="min-width:300px">${data}</div>`;
                    }
                },
                {
                    data: 'ip_address',
                    render: function (data) {
                        if (!data) return '<span class="badge bg-light text-muted">N/A</span>';
                        return `<span class="badge bg-light border text-dark" style="min-width:100px"><i class="fa fa-network-wired text-info me-1"></i> ${data}</span>`;
                    }
                },
                {
                    data: 'useragent',
                    render: function (data) {
                        if (!data) return '<span class="text-muted">—</span>';
                        return `<div class="small text-break" style="min-width:300px; display:inline-block; white-space:normal;"><i class="fa fa-desktop text-secondary me-1"></i> ${data}</div>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function (data) {
                        var formattedDate = moment(data).format('YYYY-MM-DD HH:mm:ss');
                        return `<i class="fa fa-clock text-primary me-1"></i> ${formattedDate}`;
                    }
                },
            ]
            , () => ({
                filterDate: $("#filterDateRange").val(),
                filterType: $("#filterType").val(),
            }));
    </script>
    <script>
        $('#filterType, #filterDateRange').on('change', function () {
            tableHistory.ajax.reload();
        })
        $('.selectpicker').selectpicker();

    </script>
@endsection