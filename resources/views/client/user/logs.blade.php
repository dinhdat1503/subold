@extends('Client.Layout.App')
@section('title', 'Lịch sử tài khoản')
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
                                <label for="filterType" class="form-label fw-semibold">
                                    <i class="fa fa-filter text-secondary me-1"></i> Loại
                                </label>
                                <select id="filterType" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    <optgroup label="Tài Khoản">
                                        <option value="Login">Đăng Nhập</option>
                                        <option value="Register">Đăng Ký</option>
                                        <option value="RequestChangePassword">Yêu Cầu Đổi Mật Khẩu</option>
                                        <option value="ChangePassword">Đổi Mật Khẩu</option>
                                        <option value="SendOtpEmail">Yêu Cầu Mã OTP Mail</option>
                                    </optgroup>
                                    <optgroup label="Bảo mật">
                                        <option value="UpdateSecurity">Thay Đổi Bảo Mật</option>
                                        <option value="RegenTwoFA">Tạo Lại Mã 2FA</option>
                                        <option value="ChangeApiKey">Thay Đổi Api Key</option>
                                    </optgroup>
                                    <optgroup label="Thông tin">
                                        <option value="ChangeProfile">Thay Đổi Thông Tin Cá Nhân</option>
                                        <option value="Balance">Thay Đổi Số Dư</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="logs" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Loại</th>
                                        <th>Số dư trước</th>
                                        <th>Số tiền thay đổi</th>
                                        <th>Số dư hiện tại</th>
                                        <th>Ghi chú</th>
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
    <style>
        #logs th,
        #logs td {
            text-align: center;
            vertical-align: middle;
        }

        #logs {
            border: 2px solid;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script src="/assets/libs/flatpickr/flatpickr.js"></script>
    <script src="/assets/libs/flatpickr/dist/vn.js"></script>
    <script>
        initFlatpickr("#filterDateRange");
        let tableLogs = createTable('#logs', '{{ route('user.logs.data') }}', [{
            data: 'id',
        },
        {
            data: 'action_type',
            render: function (data) {
                return statusLog(data, true); // gọi hàm đã viết ở trên
            }
        },

        {
            data: 'old_value',
            render: function (data) {
                return `<span class="badge bg-warning">` + formatMoney(data) + `</span>`
            }
        },
        {
            data: 'value',
            render: function (data) {
                if (data == null) return '';

                let num = parseFloat(data);
                let formatted = formatMoney(num); // lấy giá trị tuyệt đối để format

                if (num > 0) {
                    return `<span class="badge bg-success">+${formatted}</span>`;
                } else if (num < 0) {
                    return `<span class="badge bg-danger">${formatted}</span>`;
                } else {
                    return `<span class="badge bg-secondary">0</span>`;
                }
            }
        },
        {
            data: 'new_value',
            render: function (data) {
                return `<span class="badge bg-info">` + formatMoney(data) + `</span>`
            }
        },
        {
            data: 'description',
            render: function (data, type, row) {
                if (!data) return '';
                return '<div class="text-wrap text-break" style="min-width: 300px">' + data + '</div>';
            }
        },
        {
            data: 'created_at',
            render: function (data) {
                return moment(data).format('DD/MM/YYYY HH:mm:ss');
            }
        },
        ], () => ({
            filterDate: $("#filterDateRange").val(),
            filterType: $("#filterType").val(),
        }));
    </script>
    <script>
        $('#filterDateRange, #filterType').on('change', function () {
            tableLogs.ajax.reload();
        })
    </script>
@endsection