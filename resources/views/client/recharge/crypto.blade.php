@extends('Client.Layout.App')
@section('title', 'Nạp tiền Crypto')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4 card-tab">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 d-grid gap-2">
                            <a href="{{ route("recharge.bank") }}" class="btn btn-outline-primary "><img
                                    src="/assets/images/client/finance/bank.png" alt="" width="25" height="25">
                                Ngân hàng</a>
                        </div>
                        <div class="col-6 d-grid gap-2">
                            <a href="{{ route("recharge.crypto") }}" class="btn btn-primary"><img
                                    src="/assets/images/client/finance/crypto.png" alt="" width="25" height="25">
                                Crypto</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="alert alert-primary border-2 border-primary-subtle bg-light shadow-sm rounded-4 p-3">
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
                        <div class="mb-3 col-sm-12">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">
                                        <img src="/assets/images/client/finance/tether.png" height="30px" alt="Tether Logo"
                                            class="mr-2">
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 d-flex flex-column align-items-center mb-3 mb-md-0">
                                            <div class="p-3 bg-light rounded shadow-sm border">
                                                <img src="{{ $account->wallet_qr }}" alt="Wallet QR Code" height="200px"
                                                    class="rounded">
                                            </div>

                                            <div class="mt-3 text-center">
                                                <span class="d-block font-weight-bold text-muted mb-1">Mạng lưới bắt
                                                    buộc:</span>
                                                <span
                                                    class="d-block bg-warning text-dark font-weight-bold p-1 px-3 rounded-pill shadow-sm h5 mb-0">
                                                    {{ $account->network }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">

                                            <h5 class="text-primary mb-3 font-weight-bold text-center">Chi tiết giao dịch
                                            </h5>

                                            <div class="p-3 border rounded mb-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Coin:</span>
                                                    <b class="text-primary">{{ strtoupper($account->name) }}</b>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Nạp tối thiểu:</span>
                                                    <b
                                                        class="text-success font-weight-bold">{{ formatMoney($account->recharge_min, "USD") }}</b>
                                                </div>

                                                <div class="d-flex justify-content-between pt-2 border-top">
                                                    <span class="text-muted font-weight-bold">Tỷ giá quy đổi:</span>
                                                    <b class="text-info font-weight-bold">1 USDT =
                                                        {{ formatMoney($account->exchange_rate) }}</b>
                                                </div>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <div class="font-weight-bold mb-2 text-muted">ĐỊA CHỈ VÍ (Wallet Address)
                                                </div>
                                                <div class="p-3 rounded mx-auto border border-1"
                                                    style="max-width: 450px; overflow-wrap: break-word;">
                                                    <b class="text-warning copy pointer h6 mb-0 d-block"
                                                        data-copy-value="{{ $account->account_index }}">
                                                        {{ $account->account_index }}
                                                        <i class="fa fa-clone ml-2"></i>
                                                    </b>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <h5 class="fw-bold text-primary text-center mb-3">
                                            Xác nhận giao dịch (Bước 2)
                                        </h5>
                                        <form action="{{ route('recharge.crypto.store') }}" method="POST"
                                            class="p-4 border rounded shadow-sm mx-auto" style="max-width: 600px;">
                                            @csrf
                                            <div class="mb-3 text-center">
                                                <label for="hashcode" class="form-label text-muted">Dán mã Hash (TXID) của
                                                    giao dịch:</label>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text bg-white border-end-0">
                                                        <i class="fa fa-link text-primary"></i>
                                                    </span>
                                                    <input type="text" id="hashcode" name="hashcode"
                                                        class="form-control border-start-0" placeholder="Hash/TXID"
                                                        required>
                                                </div>
                                                <div class="form-text text-muted mt-2">
                                                    * Mã Hash (TXID) là mã giao dịch duy nhất bạn nhận được sau khi chuyển
                                                    tiền thành công.
                                                </div>
                                            </div>

                                            <div class="text-center mt-4 d-flex justify-content-center">
                                                <button type="submit"
                                                    class="btn btn-success px-5 rounded-pill shadow-sm d-flex align-items-center">
                                                    <i class="fa fa-check-circle me-2"></i> Gửi xác nhận
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
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lịch sử nạp tiền</h4>
                <div class="mb-3">
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
                            <label for="filterStatus" class="form-label fw-semibold">Trạng thái</label>
                            <select id="filterStatus" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="0">Đang chờ</option>
                                <option value="1">Thành công</option>
                                <option value="2">Đã huỷ</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="history" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Coin</th>
                                    <th>Thời gian</th>
                                    <th>Trạng Thái</th>
                                    <th>Mã giao dịch</th>
                                    <th>Tiền nạp</th>
                                    <th>Khuyến mãi</th>
                                    <th>Thực nhận</th>
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
    <script src="/assets/libs/flatpickr/dist/vn.js"></script>
    <script>
        initFlatpickr("#filterDateRange");
        let tableHistory = createTable('#history', '{{ route('recharge.logs.data', 'crypto') }}', [
            {
                data: 'id'
            },
            { data: 'recharge_name' },   // ngân hàng
            {
                data: 'created_at',
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY HH:mm:ss');
                }
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    let label = '';
                    let statusClass = '';

                    switch (parseInt(data)) {
                        case 0:
                            label = "Đang chờ";
                            statusClass = "bg-warning";
                            break;
                        case 1:
                            label = "Hoàn thành";
                            statusClass = "bg-success";
                            break;
                        case 2:
                            label = "Đã huỷ";
                            statusClass = "bg-danger";
                            break;
                        default:
                            label = "Không xác định";
                            statusClass = "bg-secondary";
                    }

                    return `<span class="badge ${statusClass}">${label}</span>`;
                }
            },
            {
                data: 'trans_id'
            },
            {
                data: 'amount',
                render: function (data) {
                    return `<b class="text-danger">${formatMoney(data, "USD", 2)}</b>`;
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
            }
        ], () => ({
            filterDate: $("#filterDateRange").val(),
            filterStatus: $("#filterStatus").val(),
        }));
    </script>
    <script>
        $('#filterDateRange, #filterStatus').on('change', function () {
            tableHistory.ajax.reload();
        })
    </script>
@endsection