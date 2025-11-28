@extends('Client.Layout.App')
@section('title', 'Nạp tiền chuyển khoản')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card mb-4 card-tab">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 d-grid gap-2">
                            <a href="{{ route("recharge.bank") }}" class="btn btn-primary"><img
                                    src="/assets/images/client/finance/bank.png" alt="" width="25" height="25">
                                Ngân hàng</a>
                        </div>
                        <div class="col-6 d-grid gap-2">
                            <a href="{{ route("recharge.crypto") }}" class="btn btn-outline-primary"><img
                                    src="/assets/images/client/finance/crypto.png" alt="" width="25" height="25">
                                Crypto</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="alert alert-warning border-2 border-warning-subtle bg-light shadow-sm rounded-4 p-3">
                                <div class="d-flex align-items-start">
                                    <i class="ti ti-info-circle fs-4 me-2"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark mb-1">Lưu ý nạp tiền:</div>
                                        <div>{!! $account->note !!}</div>
                                    </div>
                                </div>
                            </div>
                            @if ($siteSettings['promotion_show'])
                                @if (!empty($promotionLevels))
                                    <div class="mt-4">
                                        <h5 class="fw-bold text-success text-center mb-3">
                                            Ưu đãi khuyến mãi khi nạp tiền
                                        </h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped text-nowrap">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th>Mức nạp tối thiểu</th>
                                                        <th>Khuyến mãi (%)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($promotionLevels as $level)
                                                        <tr>
                                                            <td><b class="text-primary">{{ formatMoney($level['money'], 'VNĐ') }}</b>
                                                            </td>
                                                            <td><b class="text-danger">{{ $level['promotion'] }}%</b></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="text-muted text-center fst-italic mt-2 small">
                                            * Chương trình áp dụng tự động khi nạp đạt mức tương ứng
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="card mb-4">
                            <div class="card-header text-center">
                                <h4 class="mb-0">
                                    <img src="/assets/images/client/finance/mb.png" height="30px" alt="MB Bank Logo"
                                        class="mr-2">
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 d-flex flex-column align-items-center mb-3 mb-md-0">
                                        <div class="p-3 bg-light rounded shadow-sm border">
                                            <img src="https://api.vietqr.io/{{ $account->name }}/{{ $account->account_index }}/0/{{ $siteSettings['bank_code'] }}{{ Auth::id() }}/qr_only.jpg?accountName={{ $account->account_name }}"
                                                height="220px" alt="QR Code" class="rounded">
                                        </div>
                                        <small class="mt-2 text-info font-weight-bold">Quét mã QR để chuyển khoản</small>
                                    </div>

                                    <div class="col-lg-7 col-md-7">

                                        <h5 class="text-primary mb-3 font-weight-bold text-center">Thông tin chuyển khoản
                                        </h5>

                                        <div class="p-3 border rounded">

                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Ngân hàng:</span>
                                                <b class="text-dark">{{ strtoupper($account->name) }}</b>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Số tài khoản:</span>
                                                <b class="text-danger copy pointer"
                                                    data-copy-value="{{ $account->account_index }}">
                                                    {{ $account->account_index }}
                                                    <i class="fa fa-clone ml-1"></i>
                                                </b>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Chủ tài khoản:</span>
                                                <b class="text-warning">{{ $account->account_name }}</b>
                                            </div>

                                            <div class="d-flex justify-content-between pt-2 border-top">
                                                <span class="text-muted font-weight-bold">Nạp tối thiểu:</span>
                                                <b
                                                    class="text-success font-weight-bold">{{ formatMoney($account->recharge_min, "VNĐ") }}</b>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <h5 class="font-weight-bold text-dark">NỘI DUNG CHUYỂN KHOẢN</h5>

                                    <div class="border border-1 p-3 rounded mx-auto" style="max-width: 350px;">
                                        <b class="text-warning copy pointer h4 mb-0 d-block"
                                            data-copy-value="{{ $siteSettings['bank_code'] }}{{ Auth::id() }}">
                                            <span
                                                class="text-uppercase">{{ $siteSettings['bank_code'] }}{{ Auth::id() }}</span>
                                            <i class="fa fa-clone ml-2"></i>
                                        </b>
                                    </div>

                                    <p class="mt-3 text-danger font-italic small">
                                        Vui lòng chuyển khoản **CHÍNH XÁC** nội dung trên để hệ thống tự động cộng tiền.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lịch sử nạp tiền</h4>
                <div class="mb-3">
                    <div class="row m-1">
                        <div class="col-md-4">
                            <label for="filterDateRange" class="form-label fw-semibold">Khoảng ngày</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-calendar-alt text-secondary"></i>
                                </span>
                                <input type="text" id="filterDateRange" class="form-control" placeholder="Chọn khoảng ngày">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="history" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ngân Hàng</th>
                                    <th>Thời gian</th>
                                    <th>Mã giao dịch</th>
                                    <th>Tiền nạp</th>
                                    <th>Khuyến mãi</th>
                                    <th>Thực nhận</th>
                                    <th>Nội dung</th>
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
        .table th,
        .table td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .table {
            border: 2px solid !important;
            border-radius: 15px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script src="/assets/libs/flatpickr/flatpickr.js"></script>
    <script src="/assets/libs/flatpickr/dist\vn.js"></script>
    <script>
        initFlatpickr("#filterDateRange");
        let tableHistory = createTable('#history', '{{ route('recharge.logs.data', 'bank') }}', [
            { data: 'id' },
            { data: 'recharge_name' },   // ngân hàng
            {
                data: 'created_at',
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY HH:mm:ss');
                }
            },
            { data: 'trans_id' },
            {
                data: 'amount',
                render: function (data) {
                    return `<b class="text-danger">${formatMoney(data)}</b>`;
                }
            },
            {
                data: 'promotion',
                render: function (data) {
                    return `<b class="text-danger">${formatMoney(data)}</b>`;
                }
            },
            {
                data: 'amount_received',
                render: function (data) {
                    return `<b class="text-danger">${formatMoney(data)}</b>`;
                }
            },
            {
                data: 'note',
                render: function (data, type, row) {
                    if (!data) return '';
                    return '<div class="text-wrap text-break" style="min-width: 300px">' + data + '</div>';
                }
            }
        ], () => ({
            filterDate: $("#filterDateRange").val()
        }));
    </script>
    <script>
        $('#filterDateRange').on('change', function () {
            tableHistory.ajax.reload();
        });
    </script>
@endsection