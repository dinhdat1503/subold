@extends('Client.Layout.App')
@section('title', $social->name . ' - ' . $service->name)
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(!Auth::check())
                        <div class="alert alert-primary d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="fa fa-exclamation-triangle fs-4 text-danger"></i>
                            <div>
                                <strong>Vui lòng đăng nhập hoặc đăng ký</strong> để sử dụng dịch vụ !
                                <div class="mt-1">
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-info me-2">
                                        <i class="fa fa-sign-in-alt me-1"></i> Đăng nhập
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-user-plus me-1"></i> Đăng ký
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <ul class="nav nav-pills d-flex flex-wrap gap-2 mb-4">
                        <li class="nav-item">
                            <a class="nav-link active d-flex align-items-center gap-2 px-3 py-2 rounded-pill"
                                data-bs-toggle="tab" href="#tab_order">
                                <i class="fa fa-shopping-basket"></i>
                                <span>Đơn Hàng</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light-warning"
                                data-bs-toggle="tab" href="#tab_history">
                                <i class="fa fa-history"></i>
                                <span>Danh Sách Đơn</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab_order">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <form action="" method="POST">
                                        <div>
                                            <!-- Toggle control -->
                                            <div id="toogleLinkControl" class="form-check form-switch mb-3"
                                                style="display: none;">
                                                <input class="form-check-input" type="checkbox" id="toggleOrderLink">
                                                <label class="form-check-label" for="toggleInput">Chế độ nhiều link</label>
                                            </div>
                                            <!-- Multiple orders textarea -->
                                            <div id="multiLinkOrder" class="form-group mb-3" style="display: none;">
                                                <label class="form-label fw-bold">Nhiều Link Order:</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control" name="multi_order_link" rows="4"
                                                        placeholder="Được Phép Nhập Nhiều ID, Link, Username Tùy Máy Chủ, Mỗi Cái Xuống Dòng 1 Lần"></textarea>
                                                </div>
                                            </div>
                                            <!-- Single order input -->
                                            <div id="singleLinkOrder" class="form-group mb-3 ">
                                                <label class="form-label fw-bold">Link Order:</label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="order_link"
                                                        placeholder="Nhập ID, Link, Username Tùy Máy Chủ">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Máy Chủ: </label>
                                            <div class="col-md-12">
                                                <div class="d-flex flex-column gap-2 overflow-auto pe-2" data-simplebar
                                                    style="max-height: 30vh;">
                                                    @foreach ($server as $item)
                                                        <button type="button"
                                                            class="btn btn-light-primary shadow-sm w-100 server-select m-1"
                                                            data-server-id="{{ $item->id }}"
                                                            data-server-price="{{ priceServer($item->price, Auth::user()->level ?? 0, null) }}">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between text-start">
                                                                <!-- Cột trái -->
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fa fa-check-circle text-light-dark selected-icon me-2"
                                                                        style="display:none;"></i>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span
                                                                            class="fw-bold text-light-info">MC{{ $item->server }}</span>
                                                                        @if(!empty($item->flag)) <i
                                                                            class="fi fi-{{ $item->flag }} rounded"></i>
                                                                        @else
                                                                        <i class="fa-solid fa-minus"></i> @endif
                                                                        <span
                                                                            class="text-light-dark">{!! $item->title !!}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex flex-column align-items-center ms-auto me-1"
                                                                    style="margin-right:-0.25rem;">
                                                                    <span class="badge bg-secondary text-light mb-1"
                                                                        data-bs-toggle="tooltip" title="Giá">
                                                                        {{ priceServer($item->price, Auth::user()->level ?? 0) }}
                                                                    </span>
                                                                    <div class="d-flex justify-content-end gap-2">
                                                                        <span
                                                                            class="btn {{ $item->action_order['refund'] ?? false ? 'btn-success' : 'btn-danger' }} btn-sm"
                                                                            data-bs-toggle="tooltip" title="Huỷ Đơn">
                                                                            <i class="fa-solid fa-sm fa-trash text-light"></i>
                                                                        </span>
                                                                        <span
                                                                            class="btn {{ $item->action_order['warranty'] ?? false ? 'btn-success' : 'btn-danger' }} btn-sm"
                                                                            data-bs-toggle="tooltip" title="Bảo Hành">
                                                                            <i class="fa-solid fa-sm fa-check-square"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3" style="display: none; " id="desDisplay">
                                            <label for="" class="form-label fw-bold">Mô Tả: </label>
                                            <div class="bg-light-warning p-2 border rounded" id="description"
                                                style="white-space: pre-line;">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3" style="display: none;" id="estimateDisplay">
                                            <!-- Divider có chữ -->
                                            <div class="d-flex align-items-center my-3">
                                                <div class="flex-grow-1 border-top"></div>
                                                <span class="px-3 text-primary fw-bold">
                                                    Số liệu ước tính trong <b class="text-danger">3</b> ngày gần đây với <b
                                                        id="estimateValue" class="text-danger"></b> đơn hàng
                                                </span>
                                                <div class="flex-grow-1 border-top"></div>
                                            </div>

                                            <!-- Thời gian bắt đầu & hoàn thành -->
                                            <div class="row text-center">
                                                <div class="col-md-6 mb-3">
                                                    <div class="p-3 border rounded shadow-sm bg-light">
                                                        <h6 class="fw-bold text-secondary mb-1">Thời gian bắt đầu</h6>
                                                        <div id="startEstimateValue" class="fs-6 text-dark">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="p-3 border rounded shadow-sm bg-light">
                                                        <h6 class="fw-bold text-secondary mb-1">Thời gian hoàn thành</h6>
                                                        <div id="endEstimateValue" class="fs-6 text-dark">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3" style="display: none;" id="reactionDisplay">
                                            <label class="form-label fw-bold">Chọn cảm xúc:</label>
                                            <div id="reactionList" class="d-flex flex-wrap gap-3 mt-3"></div>
                                        </div>
                                        <div class="form-group mb-3" style="display: none; " id="cmtDisplay">
                                            <label class="form-label fw-bold">Bình luận: <span
                                                    class="badge rounded-pill bg-warning"></span></label>
                                            <div class="col-md-12">
                                                <textarea type="text" class="form-control" rows="4" name="comment"
                                                    placeholder="Mỗi bình luận một dòng"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row">
                                            <div class="col-md" style="display: none; " id="timeDisplay">
                                                <label class="form-label fw-bold">Số <b id="timeType"></b>: </label>
                                                <select id="timeSelect" class="form-select mb-3" name="time">

                                                </select>
                                            </div>
                                            <div class="col-md" style="display: none; " id="amountDisplay">
                                                <label class="form-label fw-bold">Số <b id="amountType"></b> / <b
                                                        id="amountUnit"></b>
                                                </label>
                                                <select id="amountSelect" class="form-select mb-3" name="amount">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Số Lượng:
                                                <span id="dataQuantity" class="text-danger">100 ~ 1000000
                                                </span>
                                            </label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control mb-3" name="quantity" value="100"
                                                    placeholder="Nhập số lượng cần tăng">

                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Ghi Chú: </label>
                                            <div class="col-md-12">
                                                <textarea class="form-control mb-3" name="note" rows="3"
                                                    placeholder="Nhập ghi chú nếu cần"></textarea>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group mb-3">
                                            <div class="text-center">
                                                <h5 class="text-primary fw-semibold d-inline">
                                                    <span id="total_payment">0 đ</span>
                                                    <span>/ Link</span>
                                                </h5>
                                                <h3 class="fw-semibold">
                                                    <div id="total_payment1">Tổng tiền thanh toán [<b class="text-danger">0
                                                            đ</b>]</div>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button id="buyOrder" type="submit" class="btn btn-primary"><img
                                                    src="/assets/images/client/services/buy.svg" alt="" width="30"
                                                    height="30">
                                                Thêm đơn</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert bg-light-primary p-3 rounded shadow">
                                        <h5 class="mb-2 fw-bold text-danger">LƯU Ý!</h5>
                                        <div class="my-2 border-top border-dark"></div>
                                        <div>
                                            {!! $service->note  !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show" id="tab_history">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row m-1">
                                            <div class="col-md-4 p-2">
                                                <label for="filterDateRange" class="form-label fw-semibold">Khoảng
                                                    ngày</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fa fa-calendar-alt text-secondary"></i>
                                                    </span>
                                                    <input type="text" id="filterDateRange" class="form-control"
                                                        placeholder="Chọn khoảng ngày">
                                                </div>
                                            </div>
                                            <div class="col-md-3 p-2">
                                                <label for="filterStatus" class="form-label fw-semibold"> <i
                                                        class="fa-solid fa-chart-simple mx-1 text-success"></i>Trạng
                                                    Thái</label>
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
                                        </div>
                                        <div class="table-responsive">
                                            <table id="orderHistory" class="table table-hover table-striped text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center align-middle" style="width: 50px;">
                                                            <div
                                                                class="form-check form-check-lg d-flex justify-content-center mb-0">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="checkAll">
                                                            </div>
                                                        </th>
                                                        <th>ID</th>
                                                        <th>Hành Động</th>
                                                        <th>Trạng Thái</th>
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
                                            <div
                                                class="alert alert-secondary py-1 px-2 mb-2 small d-inline-flex align-items-center">
                                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                                <span>Có thể bấm chuột vào XUNG QUANH ô tick rồi di chuột trong cột
                                                    để chọn hàng loạt, bấm
                                                    trực tiếp vào ô tick thì chỉ đc chọn 1</span>
                                            </div>
                                            <div class="d-flex justify-content-start align-items-center gap-2">
                                                <button class="btn btn-outline-danger btn-sm px-3 py-1 order-action"
                                                    data-type="refund">
                                                    <i class="fas fa-trash me-1"></i> Huỷ Đơn
                                                </button>
                                                <button class="btn btn-outline-success btn-sm px-3 py-1 order-action"
                                                    data-type="warranty">
                                                    <i class="fas fa-check-square me-1"></i> Bảo Hành
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <link rel="stylesheet" href="/assets/css/client/flag-css/css/flag-icons.min.css">
    <style>
        .reaction-label {
            cursor: pointer;
            display: inline-block;
            border-radius: 50%;
            transition: all 0.25s ease;
        }

        .reaction-label img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            transition: all 0.25s ease;
            box-shadow: 0 0 0 2px transparent;
        }

        /* Khi được chọn */
        .reaction-radio:checked+.reaction-label img {
            transform: scale(1.2);
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.6);
        }

        /* Hover đẹp hơn */
        .reaction-label:hover img {
            transform: scale(1.1);
        }
    </style>
    <style>
        /* Giới hạn chiều cao list để không bị dài quá */
        #successList,
        #errorList {
            max-height: 20vh;
            overflow-y: auto;
        }

        .copy-btn {
            cursor: pointer;
            color: #0d6efd;
        }

        .copy-btn:hover {
            color: #0a58ca;
        }
    </style>
    <style>
        #orderHistory th,
        #orderHistory td {
            text-align: center;
            vertical-align: middle;
        }

        #orderHistory {
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
        const serverInfoUrl = "{{ route('service.server.info', ':id') }}";
        var serverInfo = {};
        initFlatpickr("#filterDateRange");
        let tableOrderHistory;
        var userLogin = @json(Auth::check());
        const ROUTES = {
            orderInfo: `{{ route('service.order.info', ['id' => '__id__']) }}`,
            orderUpdate: `{{ route('service.order.update', ['type' => '__type__']) }}`, // Đổi tên/cấu trúc route này cho linh hoạt
        };
        let multiLink = false;
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.server-select', function () {
                if (!userLogin) {
                    swal({
                        text: "Vui Lòng Đăng Nhập Hoặc Đăng Ký Để Dùng Dịch Vụ !",
                        icon: "error"
                    });
                    return;
                }
                $('.server-select').removeClass('active');
                $('.server-select .selected-icon').hide();

                $(this).addClass('active');
                $(this).find('.selected-icon').show();

                serverInfo.serverID = $(this).data('server-id');
                serverInfo.priceServer = $(this).data('server-price');
                let url = serverInfoUrl.replace(':id', serverInfo.serverID);

                ajaxRequest(url, "GET", {},
                    function (res) {
                        if (res.status === "success") {
                            $("#toogleLinkControl").toggle(res.server_action.multi_link);
                            $("#desDisplay").toggle(true);
                            $("#description").text(res.description);
                            $("#estimateDisplay").toggle(true);
                            $("#estimateValue").text(res.order_recent_count);
                            $("#startEstimateValue").text(res.order_recent_start);
                            $("#endEstimateValue").text(res.order_recent_end);

                            $("#reactionDisplay").toggle(res.reaction.status);
                            $("#reactionList").empty();
                            if (res.reaction.server) {
                                $.each(res.reaction.server, function (index, value) {
                                    const input = `<input class="d-none reaction-radio" type="radio" id="reaction_${index}" name="reaction" value="${index}" data-price="${value}">`;
                                    const label = `<label for="reaction_${index}" class="reaction-label"><img src="/assets/images/client/services/reaction/${index}.png" alt="${index.charAt(0).toUpperCase() + index.slice(1)}"></label>`;
                                    $("#reactionList").append(input + label);
                                });
                            }

                            $("#cmtDisplay").toggle(res.comment.status);
                            $('[name="quantity"]').prop('readonly', res.comment.status);

                            $("#timeDisplay").toggle(res.time.status);
                            $("#timeType").text(res.time.type);
                            $("#timeSelect").empty();
                            if (res.time.server) {
                                $.each(res.time.server, function (index, value) {
                                    $("#timeSelect").append(
                                        $("<option>", {
                                            value: index,
                                            text: index + " " + res.time.type,
                                            "data-price": value
                                        })
                                    );
                                });
                            }

                            $("#amountDisplay").toggle(res.amount.status);
                            $("#amountType").text(res.amount.type);
                            $("#amountUnit").text(res.amount.unit);
                            $("#amountSelect").empty();
                            if (res.amount.server) {
                                $.each(res.amount.server, function (index, value) {
                                    $("#amountSelect").append(
                                        $("<option>", {
                                            value: index,
                                            text: index + " " + res.amount.type,
                                            "data-price": value

                                        })
                                    );
                                });
                            }


                            $("#dataQuantity").text(formatNumber(res.min) + " ~ " + formatNumber(res.max));
                            $('[name="quantity"]').val(res.min);

                        } else {
                            swal({
                                text: "Có lỗi xảy ra!",
                                icon: "error"
                            });
                        }
                    })
            });
            $(document).on("change", "#toggleOrderLink", function () {
                $("#multiLinkOrder").toggle(this.checked);
                multiLink = this.checked;
                $("#singleLinkOrder").toggle(!this.checked);
            });
            $(document).on(
                "input change keyup paste blur click",
                '[name="quantity"], [name="comment"], [name="reaction"], [name="multi_order_link"], [name="note"], [name="order_link"], [name="time"], [name="amount"], .server-select',
                function () {
                    totalPayment();
                }
            );
            $(document).on("click", "#buyOrder", function (e) {
                e.preventDefault();
                if (!userLogin) {
                    swal({
                        text: "Vui Lòng Đăng Nhập Hoặc Đăng Ký Để Dùng Dịch Vụ !",
                        icon: "error"
                    });
                    return;
                }
                swal({
                    text: "Bạn có chắc chắn muốn tạo đơn hàng không?",
                    icon: "question",
                    title: "Xác nhận",
                    showCancel: true,
                    confirmText: "Đồng ý",
                    cancelText: "Hủy",
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('service.order.process') }}";
                        let baseData = {
                            server_id: serverInfo.serverID,
                            reaction: $('[name="reaction"]').val(),
                            quantity: $('[name="quantity"]').val(),
                            comment: $('[name="comment"]').val(),
                            time: $('[name="time"]').val(),
                            amount: $('[name="amount"]').val(),
                            note: $('[name="note"]').val()
                        };
                        if (multiLink) {
                            let links = $('[name="multi_order_link"]').val().split(/\r?\n/).map(l => l.trim()).filter(l => l !== "");
                            if (links.length === 0) {
                                swal({ text: "Vui lòng nhập link!", icon: "error" });
                                return;
                            }
                            let data = { ...baseData, links: links };
                            ajaxRequest(url, "POST", data, function (res) {
                                if (res.status === "success") {
                                    updateBatchProgressModal(res.data);
                                } else {
                                    swal({
                                        text: res.message || "Có Lỗi Xảy Ra",
                                        icon: "error"
                                    });
                                }
                            });
                        } else {
                            let link = $('[name="order_link"]').val().trim();
                            if (!link) {
                                swal({
                                    text: "Vui lòng nhập link!",
                                    icon: "error"
                                }); return;
                            }
                            let data = { ...baseData, links: link };
                            swal({
                                title: 'Đang đặt đơn',
                                text: 'Vui lòng chờ trong giây lát',
                                showLoading: true,
                            });
                            ajaxRequest(url, "POST", data, function (res) {
                                if (res.status === "success") {
                                    swal({
                                        text: "Đã thêm đơn hàng thành công!",
                                        icon: "success"
                                    });
                                } else {
                                    swal({
                                        text: res.message ?? "Có lỗi xảy ra!",
                                        icon: "error"
                                    });
                                }
                            });
                        }
                    }
                })
            });
            $(document).on("click", '[href="#tab_history"]', function (e) {
                if (!userLogin) {
                    swal({
                        text: "Vui Lòng Đăng Nhập Hoặc Đăng Ký Để Dùng Dịch Vụ !",
                        icon: "error"
                    });
                    return;
                }
                tableOrderHistory = createTable('#orderHistory',
                    '{{ route('service.orders.data', $service->id) }}',
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
                        { data: 'id' },
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (id) {
                                return `<button class="btn btn-primary btn-sm me-1 order-action" title="Xem chi tiết" data-id="${id}" data-type="info" ><i class="fas fa-eye"></i></button>`;
                            }
                        },
                        {
                            data: 'status',
                            render: function (data) {
                                return statusOrder(data, true); // render HTML badge
                            }
                        },
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
                        }], () => ({
                            filterDate: $("#filterDateRange").val(),
                            filterStatus: $("#filterStatus").val(),
                        })
                );
            })
            $('#filterDateRange, #filterStatus').on('change', function () {
                tableOrderHistory.ajax.reload();
            })
        });
    </script>
    <script>
        function totalPayment() {
            let quantity = parseInt($('[name="quantity"]').val()) || 0;
            if ($("#cmtDisplay").is(":visible")) {
                let comments = $('[name="comment"]').val().trim().split("\n").filter(line => line.trim() !== "");
                quantity = comments.length;
                $('[name="quantity"]').val(quantity);
            }
            let linkCount = 1;
            if (multiLink) {
                let links = $('[name="multi_order_link"]').val().trim().split("\n").filter(line => line.trim() !== "");
                linkCount = links.length || 1;
            }
            let price = parseFloat(serverInfo.priceServer) || 0;
            let timeMultiplier = 1;
            let amountMultiplier = 1;
            if ($("#reactionDisplay").is(":visible")) {
                let selected = $("input[name='reaction']:checked");
                let priceAction = selected.data("price");
                if (selected.length > 0 && priceAction != 0) {
                    price = parseFloat(priceAction);
                }
            }
            if ($("#timeDisplay").is(":visible")) {
                let selected = $("#timeSelect option:selected");
                let priceAction = selected.data("price");
                if (selected.length > 0) {
                    if (priceAction != 0) {
                        price = parseFloat(priceAction);
                        timeMultiplier = 1;

                    } else {
                        timeMultiplier = parseInt(selected.val()) || 1;
                    }
                }
            }
            if ($("#amountDisplay").is(":visible")) {
                let selected = $("#amountSelect option:selected");
                let priceAction = selected.data("price");
                if (selected.length > 0) {
                    if (priceAction != 0) {
                        price = parseFloat(priceAction);
                        amountMultiplier = 1;
                    } else {
                        amountMultiplier = parseInt(selected.val()) || 1;
                    }
                }
            }
            let pricePerLink = quantity * timeMultiplier * amountMultiplier * price;
            let total = pricePerLink * linkCount;
            $("#total_payment").text(formatMoney(pricePerLink, "đ", 2)); // mỗi link
            $("#total_payment1").html(
                `Tổng tiền thanh toán [<b class="text-danger">${formatMoney(total, "đ", 2)}</b>]`
            );
        }
    </script>
@endsection