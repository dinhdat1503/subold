@extends('Client.Layout.App')
@section('title', 'Lịch sử đặt đơn')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch Sử Đặt Đơn</h4>
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
                            <div class="col-md-3 p-2">
                                <label for="filterStatus" class="form-label fw-semibold">
                                    <i class="fa-solid fa-chart-simple mx-1 text-success"></i>Trạng Thái</label>
                                <select id="filterStatus" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    <option value="WaitingForRefund">Đang huỷ</option>
                                    <option value="Pending">Chờ xử lý</option>
                                    <option value="Active">Đang hoạt động</option>
                                    <option value="Error">Lỗi đơn</option>
                                    <option value="Warranty">Bảo hành</option>
                                    <option value="Completed">Hoàn thành</option>
                                    <option value="Refunded">Hoàn tiền</option>
                                    <option value="Cancelled">Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-md-3 p-2">
                                <label for="filterService" class="form-label fw-semibold">
                                    <i class="fa-solid fa-server mx-1 text-warning"></i>Dịch Vụ</label>
                                <select id="filterService" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    @foreach($socials as $social)
                                        <optgroup label="{{ strtoupper($social->slug) }}">
                                            @foreach($social->services as $service)
                                                <option value="{{ $service->id }}">
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="orders" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" style="width: 50px;">
                                            <div class="form-check form-check-lg d-flex justify-content-center mb-0">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Hành Động</th>
                                        <th>Trạng Thái</th>
                                        <th>Dịch Vụ</th>
                                        <th>Link</th>
                                        <th>Thông Tin Đơn</th>
                                        <th>Bắt Đầu / Đã Tăng</th>
                                        <th>Buff</th>
                                        <th>Ghi Chú</th>
                                        <th>Thời Gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <div class="alert alert-secondary py-1 px-2 mb-2 small d-inline-flex align-items-center">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                <span>Có thể bấm chuột vào XUNG QUANH ô tick rồi di chuột trong cột để chọn hàng loạt, bấm
                                    trực tiếp vào ô tick thì chỉ đc chọn 1</span>
                            </div>

                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <button class="btn btn-outline-danger btn-sm px-3 py-1 order-action" data-type="refund">
                                    <i class="fas fa-trash me-1"></i> Huỷ Đơn
                                </button>
                                <button class="btn btn-outline-success btn-sm px-3 py-1 order-action" data-type="warranty">
                                    <i class="fas fa-check-square me-1"></i> Bảo Hành
                                </button>
                            </div>
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
        #orders th,
        #orders td {
            text-align: center;
            vertical-align: middle;
        }

        #orders {
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
    <script src="/assets/js/client/order.js"></script>
    <script>
        // Khởi tạo date range picker
        initFlatpickr("#filterDateRange");
        let tableOrders = createTable('#orders',
            '{{ route('service.orders.data', 0) }}',
            [
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('order-cell');
                    },
                    render: function (id) {
                        return `<div class="order-cell form-check form-check-lg d-flex justify-content-center"><input class="form-check-input order-checkbox" type="checkbox" value="${id}"></div>`;
                    }
                },
                // STT
                { data: 'id' },
                // Hành động (cột ảo)
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (id) {
                        return `<button class="btn btn-primary btn-sm me-1 order-action" title="Xem chi tiết" data-id="${id}" data-type="info" ><i class="fas fa-eye"></i></button>`;
                    }
                },

                // Trạng thái
                {
                    data: 'status',
                    render: function (data) {
                        return statusOrder(data, true); // render HTML badge
                    }
                },

                // Tên dịch vụ
                {
                    data: null,
                    render: row => `<div class="d-flex flex-column"><span class="badge bg-success mb-1"><i class="fas fa-hashtag me-1"></i> ${row.social_name ?? ''}</span><span class="fw-bold text-primary"><i class="fas fa-cogs me-1"></i> ${row.service_name ?? ''}</span></div>`
                },

                // Link
                {
                    data: 'order_link',
                    render: d => `<textarea class="form-control form-control-sm" rows="2" readonly style="min-width:300px; min-height:100px">${d}</textarea>`
                },

                // Thông tin đơn
                {
                    data: null,
                    render: row => `<div class="text-break"><div class="mb-1"><span class="badge bg-secondary"><i class="fas fa-server me-1"></i>MC: ${row.serverService.index}</span></div><div class="mb-1"><span class="badge bg-warning text-dark"><i class="fas fa-tag me-1"></i>Giá: ${formatMoney(row.serverService.price, "đ", 2)}</span></div><div class="mb-1"><span class="badge bg-success"><i class="fas fa-sort-numeric-up me-1"></i>Số Lượng: ${formatNumber(row.quantity)}</span></div><div><span class="badge bg-danger"><i class="fas fa-coins me-1"></i>Thành Tiền: ${formatMoney(row.payment)}</span></div></div>`
                },

                // Bắt đầu / Buff
                {
                    data: null,
                    render: row => `<div class="text-break"><div class="mb-1"><span class="badge bg-info"><i class="fas fa-play me-1"></i> Start: ${formatNumber(row.count_start)}</span></div><div class="mb-1"><span class="badge bg-dark"><i class="fas fa-plus me-1"></i> Buff: ${formatNumber(row.count_buff)}</span></div></div>`
                },

                // Loại buff (decode JSON ở client)
                {
                    data: 'order_info',
                    render: renderOrderInfo
                },

                // Ghi chú
                {
                    data: 'note',
                    render: d => `<textarea class="form-control form-control-sm" rows="2" readonly style="min-width:300px; min-height:100px">${d ?? ''}</textarea>`
                },

                // Thời gian
                {
                    data: 'created_at',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY HH:mm:ss');
                    }
                }
            ]
            , () => ({
                filterDate: $("#filterDateRange").val(),
                filterStatus: $("#filterStatus").val(),
                filterService: $("#filterService").val(),
            }));
    </script>
    <script>
        $('#filterDateRange, #filterStatus, #filterService').on('change', function () {
            tableOrders.ajax.reload();
        })
    </script>
    <script>
        const ROUTES = {
            orderInfo: `{{ route('service.order.info', ['id' => '__id__']) }}`,
            orderUpdate: `{{ route('service.order.update', ['type' => '__type__']) }}`, // Đổi tên/cấu trúc route này cho linh hoạt
        };
    </script>
@endsection