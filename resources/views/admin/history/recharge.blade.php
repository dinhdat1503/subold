@extends('admin.layout.app')
@section('title', 'Lịch sử nạp tiền')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch sử nạp tiền</h4>
                    <div class="row m-1">
                        <div class="col-md-4 p-2">
                            <label for="filterDateRange" class="form-label fw-semibold">Khoảng ngày</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-calendar-alt text-secondary"></i>
                                </span>
                                <input type="text" id="filterDateRange" class="form-control" placeholder="Chọn khoảng ngày">
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <label for="filterStatus" class="form-label fw-semibold">
                                <i class="fa-solid fa-clipboard-list me-1 text-danger"></i> Trạng Thái
                            </label>
                            <select id="filterStatus" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="0">Đang chờ</option>
                                <option value="1">Thành công</option>
                                <option value="2">Đã huỷ</option>
                            </select>
                        </div>
                        <div class="col-md-4 p-2">
                            <label for="filterTransID" class="form-label fw-semibold">Transaction ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-hashtag text-primary"></i>
                                </span>
                                <input type="text" id="filterTransID" class="form-control" placeholder="Nhập ID cần tìm">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="history" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thời gian</th>
                                    <th>Trạng Thái</th>
                                    <th>Tài khoản</th>
                                    <th>Loại</th>
                                    <th>Mã giao dịch</th>
                                    <th>Tiền nạp</th>
                                    <th>Khuyến mãi</th>
                                    <th>Thực nhận</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script src="/assets/libs/flatpickr/flatpickr.js"></script>
    <script src="/assets/libs/flatpickr/dist/vn.js"></script>
    <script>
        initFlatpickr("#filterDateRange");
        const tableHistory = createTable('#history', '{{ route('admin.recharge.logs.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            {
                data: 'created_at',
                render: function (data) {
                    var formattedDate = moment(data).format('YYYY-MM-DD HH:mm:ss');
                    return `<i class="fa fa-clock text-primary me-1"></i> ${formattedDate}`;
                }
            },
            {
                data: 'status',
                render: d => {
                    let label, color;
                    switch (parseInt(d)) {
                        case 0: label = "Đang chờ"; color = "warning"; break;
                        case 1: label = "Hoàn thành"; color = "success"; break;
                        case 2: label = "Đã huỷ"; color = "danger"; break;
                        default: label = "Không xác định"; color = "secondary";
                    }
                    return `<span class="badge bg-${color} px-3 py-2 fw-semibold">${label}</span>`;
                }
            },
            {
                data: 'username',
                render: function (data) {
                    return `<i class="fa fa-user text-primary me-1"></i> <span class="fw-semibold">${data}</span>`;
                }
            },
            {
                data: 'recharge_name',
                render: d => `<i class="fa fa-id-badge text-primary me-1"></i>${d ?? '-'}`
            },
            {
                data: 'trans_id',
                render: d => `<code><i class="fa fa-barcode me-1 text-muted"></i>${d}</code>`
            },
            {
                data: 'amount',
                render: (d, t, r) =>
                    r.type === 'crypto'
                        ? `<span class="text-danger fw-bold"><i class="fa fa-bitcoin me-1"></i>${formatMoney(d, "USD", 2)}</span>`
                        : `<span class="text-danger fw-bold"><i class="fa fa-money-bill me-1"></i>${formatMoney(d)}</span>`
            },
            {
                data: 'promotion',
                render: d => `<span class="text-success fw-bold"><i class="fa fa-gift me-1"></i>${formatMoney(d)}</span>`
            },
            {
                data: 'amount_received',
                render: d => `<span class="text-primary fw-bold"><i class="fa fa-wallet me-1"></i>${formatMoney(d)}</span>`
            },
            {
                data: 'note',
                render: d => d ? `<i class="fa fa-sticky-note me-1 text-muted"></i><span class="text-wrap">${d}</span>` : '-'
            }
        ], () => ({
            filterDate: $("#filterDateRange").val(),
            filterStatus: $("#filterStatus").val(),
            filterTransID: $("#filterTransID").val(),
        }));

    </script>
    <script>
        $('#filterDateRange, #filterStatus, #filterTransID').on('change', function () {
            tableHistory.ajax.reload();
        })
    </script>
@endsection