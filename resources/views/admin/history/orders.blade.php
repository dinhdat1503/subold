@extends('admin.layout.app')
@section('title', 'Lịch Sử Đơn Hàng')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-tilte">Lịch Sử Đơn Hàng</h4>
                    <div class="mb-3">
                        <div class="row m-1">
                            <div class="col-md-3 p-2">
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
                                    Trạng Thái
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa-solid fa-clipboard-list me-1 text-danger"></i>
                                    </span>
                                    <select id="filterStatus" class="form-control selectpicker" data-live-search="true"
                                        title="Chọn trạng thái...">
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
                            </div>
                            <div class="col-md-3 p-2">
                                <label for="filterService" class="form-label fw-semibold">
                                    Dịch Vụ
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-cubes text-warning me-1"></i>
                                    </span>
                                    <select id="filterService" class="form-control selectpicker" data-live-search="true"
                                        title="Chọn dịch vụ...">
                                        <option value="">-- Tất cả --</option>
                                        @foreach($socials as $social)
                                            <optgroup label="{{ strtoupper($social->name) }}">
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
                            <div class="col-md-3 p-2">
                                <label for="filterID" class="form-label fw-semibold">ID Đơn</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-receipt text-info"></i>
                                    </span>
                                    <input type="number" id="filterID" class="form-control" placeholder="Nhập ID Đơn">
                                </div>
                            </div>
                            <div class="col-md-3 p-2">
                                <label for="filterApiID" class="form-label fw-semibold">ID Đơn API</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-receipt text-primary"></i>
                                    </span>
                                    <input type="text" id="filterApiID" class="form-control" placeholder="Nhập ID Đơn">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="orders" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hành Động</th>
                                        <th>Api</th>
                                        <th>Username</th>
                                        <th>Trạng Thái</th>
                                        <th>Dịch Vụ</th>
                                        <th>Link</th>
                                        <th>Thông Tin Đơn</th>
                                        <th>Bắt Đầu / Đã Tăng</th>
                                        <th>Buff</th>
                                        <th>Ghi Chú</th>
                                        <th>Thời Gian / Cập Nhật</th>
                                        <th>Bắt Đầu / Hoàn Thành</th>
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
    <div id="orderInfo" class="modal fade" tabindex="-1" aria-labelledby="orderInfo" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Chi tiết đơn hàng #<span id="orderId"></span>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <p class="text-primary mb-2">Chi tiết thanh toán</p>
                        Số tiền đã thanh toán : <span id="paymentOrder" class="text-danger fw-semibold">0</span>
                    </div>
                    <div class="p-3 mb-5">
                        <p class="text-primary mb-2">Dòng thời gian</p>
                        <div class="timeline-widget">
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="text-primary mb-2">Chi tiết đơn hàng</p>

                        <li class="p-1 text-start text-break">Link: <span id="orderLink"></span></li>
                        <li class="p-1">Số lượng: <span id="orderQuantity"></span></li>
                        <li class="p-1">Bắt đầu: <span id="orderStart"></span></li>
                        <li class="p-1">Đã tăng: <span id="orderBuff"></span></li>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white font-medium waves-effect"
                        data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalRefund" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Huỷ đơn hàng #<span id="refundOrderId"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Số tiền cần hoàn lại (VNĐ)</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-coins text-warning"></i></span>
                        <input type="number" id="refundAmount" class="form-control" min="0"
                            placeholder="Nhập số tiền hoàn lại">
                    </div>
                    <small class="text-muted">
                        Nhập chính xác số tiền cần hoàn cho khách hàng.<br>
                        Nếu để trống hoặc nhập 0, hệ thống sẽ hoàn toàn bộ giá trị đơn hàng.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmRefund">
                        <i class="fa-solid fa-rotate-left me-1"></i> Xác nhận hoàn tiền
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalStatus" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật trạng thái đơn #<span id="statusOrderId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Chọn trạng thái mới:</label>
                    <select id="newStatus" class="form-select">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-warning" id="confirmStatus">Cập nhật</button>
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
        #orders th,
        #orders td {
            text-align: center;
            vertical-align: middle;
        }

        #orders {
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
        function orderAction(id, type) {
            switch (type) {
                case 'info': {
                    // Mở modal chi tiết
                    $('#orderInfo').modal('show');
                    $('#orderId').text(id);

                    ajaxRequest(
                        `{{ route('admin.service.orders.info', ['id' => '__id__']) }}`.replace('__id__', id),
                        "GET",
                        {},
                        function (res) {
                            if (res.status === "success") {
                                // hiển thị thông tin
                                $('#orderLink').text(res.order_link);
                                $('#orderQuantity').text(formatNumber(res.quantity));
                                $('#orderStart').text(formatNumber(res.start));
                                $('#orderBuff').text(formatNumber(res.buff));
                                $('#paymentOrder').text(formatMoney(res.payment));
                                // reset timeline
                                $('.timeline-widget').html('');

                                if (Array.isArray(res.logs)) {
                                    res.logs.forEach(item => {
                                        let html = `<div class="d-flex align-items-center mb-2"><small class="flex-shrink-0 mx-2">${item.time}</small><div class="flex-shrink-0"><span class="p-1 d-block bg-${item.status} rounded-circle"></span></div><div class="flex-grow-1 mx-2"><h6 class="mb-0 text-start">${item.title}</h6></div></div>`;
                                        $('.timeline-widget').append(html);
                                    });
                                }
                            } else {
                                swal({
                                    text: res.message ?? "Có lỗi xảy ra!",
                                    icon: "error"
                                });
                                $('#orderInfo').modal('hide');
                            }
                        }
                    );
                    break;
                }
                case 'refund': {
                    $('#refundOrderId').text(id);
                    $('#refundAmount').val('');
                    $('#modalRefund').modal('show');
                    break;
                }
                case 'status': {
                    $('#statusOrderId').text(id);
                    $('#newStatus').val('');
                    $('#modalStatus').modal('show');
                    break;
                }
                default:
                    toastr.error("Không Xác Định Hành Động");
            }
        }
        let tableOrders = createTable('#orders',
            '{{ route('admin.service.orders.manage.data', 0) }}',
            [
                {
                    data: 'id',
                    render: function (data) {
                        return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                    }
                },
                {
                    data: 'id',
                    searable: false,
                    orderable: false,
                    render: function (id) {
                        return `<button class="btn btn-primary btn-sm me-1 order-action" title="Xem chi tiết" data-id="${id}" data-type="info"><i class="fas fa-eye"></i></button><button class="btn btn-danger btn-sm me-1 order-action" title="Hủy đơn" data-id="${id}" data-type="refund"><i class="fas fa-trash"></i></button><button class="btn btn-warning btn-sm me-1 order-action" title="Cập nhật trạng thái" data-id="${id}" data-type="status"><i class="fas fa-sync-alt"></i></button>`;
                    }
                },
                {
                    data: null,
                    render: function (row) {
                        return `<div class="d-flex flex-column align-items-center text-center"><div class="mb-1"><span class="badge bg-info text-white px-3 py-2 shadow-sm"><i class="fa-solid fa-network-wired me-1"></i> ${row.supplier_name ?? '<span class="text-muted">Không có</span>'}</span></div><div><small class="text-muted"><i class="fa-solid fa-code-branch me-1 text-secondary"></i> ${row.supplier_order_id ?? '—'}</small></div></div>`;
                    }
                },
                {
                    data: 'username',
                    render: data => `<i class="fa fa-user text-primary me-1"></i> <span class="fw-semibold">${data}</span>`
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
                    render: row => `<div class="text-break d-flex flex-column align-items-center justify-content-center text-center"><div class="border rounded-3 p-2 mb-2 bg-body-tertiary shadow-sm w-100"><div class="d-flex flex-wrap justify-content-center align-items-center gap-3"><div class="text-secondary fw-semibold"><i class="fas fa-server me-1"></i>${row.serverService.index}</div><div class="text-info fw-semibold"><i class="fas fa-tag me-1"></i>${formatMoney(row.serverService.price, "đ", 2)}</div><div class="text-success fw-semibold"><i class="fas fa-sort-numeric-up me-1"></i>Số Lượng: ${formatNumber(row.quantity)}</div></div></div><div class="border rounded-3 p-2 bg-light shadow-sm w-100"><div class="d-flex flex-wrap justify-content-center align-items-center gap-3"><div class="text-danger fw-semibold"><i class="fas fa-coins me-1"></i>Thành tiền: ${formatMoney(row.payment)}</div><div class="text-success fw-bold"><i class="fas fa-money-bill-wave me-1"></i>Thực trả: ${formatMoney(row.payment_real)}</div></div></div></div>`
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
                    data: null,
                    render: function (row) {
                        const created = row.created_at ? moment(row.created_at).format('DD/MM/YYYY HH:mm:ss') : '—';
                        const updated = row.updated_at ? moment(row.updated_at).format('DD/MM/YYYY HH:mm:ss') : '—';
                        return `<div class="text-break small lh-sm"><div class="mb-1"><span class="badge bg-primary bg-opacity-75 text-white"><i class="fa-solid fa-calendar-plus me-1"></i> ${created}</span></div><div><span class="badge bg-secondary bg-opacity-75 text-white"><i class="fa-solid fa-calendar-check me-1"></i> ${updated}</span></div></div>`;
                    }
                },
                {
                    data: null,
                    render: function (row) {
                        const start = row.time_start ? moment(row.time_start).format('DD/MM/YYYY HH:mm:ss') : '—';
                        const end = row.time_end ? moment(row.time_end).format('DD/MM/YYYY HH:mm:ss') : '—';
                        return `<div class="text-break small lh-sm"><div class="mb-1"><span class="badge bg-info bg-opacity-75 text-white"><i class="fa-solid fa-hourglass-start me-1"></i>${start}</span></div><div><span class="badge bg-danger bg-opacity-75 text-white"><i class="fa-solid fa-hourglass-end me-1"></i>${end}</span></div></div>`;
                    }
                }
            ]
            , () => ({
                filterDate: $("#filterDateRange").val(),
                filterStatus: $("#filterStatus").val(),
                filterService: $("#filterService").val(),
                filterID: $("#filterID").val(),
                filterApiID: $("#filterApiID").val(),
            }));
    </script>
    <script>
        $('#filterDateRange, #filterStatus, #filterService, #filterID, #filterApiID').on('change', function () {
            tableOrders.ajax.reload();
        })
        $(document).on('click', '.order-action', function () {
            const id = $(this).data('id');
            const action = $(this).data('type');
            orderAction(id, action);
        });
        $('#confirmRefund').on('click', function () {
            const id = $('#refundOrderId').text();
            const refundAmount = $('#refundAmount').val();

            swal({
                title: "Đang xử lý",
                text: "Vui lòng chờ...",
                showLoading: true
            });

            ajaxRequest(
                `{{ route('admin.service.orders.update', ['id' => '__id__', 'type' => 'refund']) }}`.replace('__id__', id),
                "PUT",
                { refund_amount: refundAmount },
                function (res) {
                    if (res.status === "success") {
                        swal({ text: res.message, icon: "success" });
                        $('#modalRefund').modal('hide');
                        tableOrders.ajax.reload();
                    } else {
                        swal({ text: res.message ?? "Có lỗi xảy ra!", icon: "error" });
                    }
                }
            );
        });
        $('#confirmStatus').on('click', function () {
            const id = $('#statusOrderId').text();
            const status = $('#newStatus').val();

            swal({
                title: "Đang cập nhật trạng thái",
                text: "Vui lòng chờ...",
                showLoading: true
            });

            ajaxRequest(
                `{{ route('admin.service.orders.update', ['id' => '__id__', 'type' => 'status']) }}`.replace('__id__', id),
                "PUT",
                { status: status },
                function (res) {
                    if (res.status === "success") {
                        swal({ text: res.message, icon: "success" });
                        $('#modalStatus').modal('hide');
                        tableOrders.ajax.reload();
                    } else {
                        swal({ text: res.message ?? "Có lỗi xảy ra!", icon: "error" });
                    }
                }
            );
        });
        $('.selectpicker').selectpicker();

    </script>
@endsection