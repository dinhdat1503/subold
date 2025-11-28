const ORDER_INFO_MODAL = `
    <div id="orderInfo" class="modal fade" tabindex="-1" aria-labelledby="orderInfo" style="display: none;"
        aria-hidden="true">
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
    `;
const BATCH_PROGRESSIVE_MODAL = ` 
    <div id="modalBatchProgress" class="modal fade" tabindex="-1" aria-labelledby="modalBatchProgress" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        <i class="fas fa-tasks me-2 text-primary"></i> Tiến Trình Xử Lý Hàng Loạt
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="p-2 bg-light-success rounded">
                                <h5 class="text-success mb-3 d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-check-circle me-1"></i> Thành Công (<span
                                            id="successCount"></span>)
                                    </span>
                                    <button class="btn btn-sm btn-outline-success copy-all-btn" data-type="success"
                                        title="Sao chép tất cả link thành công">
                                        <i class="fa-solid fa-copy me-1"></i>
                                    </button>
                                </h5>
                                <div class="list-group" data-simplebar style="max-height: 30vh;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="p-2 bg-light-danger rounded">
                                <h5 class="text-danger mb-3 d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-exclamation-triangle me-1"></i> Lỗi Thất Bại (<span
                                            id="errorCount">1</span>)
                                    </span>
                                    <button class="btn btn-sm btn-outline-danger copy-all-btn" data-type="error"
                                        title="Sao chép tất cả link lỗi">
                                        <i class="fa-solid fa-copy me-1"></i>
                                    </button>
                                </h5>
                                <div class="list-group" data-simplebar style="max-height: 30vh;">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-white font-medium waves-effect"
                        data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>`;
function orderAction({ id = null, type, data = null } = {}) {
    const urlOrder = ROUTES.orderUpdate.replace("__type__", type);
    switch (type) {
        case "info": {
            $("#orderInfo").modal("show");
            $("#orderId").text(id);
            const url = ROUTES.orderInfo.replace("__id__", id);
            ajaxRequest(url, "GET", {}, function (res) {
                if (res.status === "success") {
                    $("#orderLink").text(res.order_link);
                    $("#orderQuantity").text(formatNumber(res.quantity));
                    $("#orderStart").text(formatNumber(res.start));
                    $("#orderBuff").text(formatNumber(res.buff));
                    $("#paymentOrder").text(formatMoney(res.payment));
                    $(".timeline-widget").html("");
                    if (Array.isArray(res.logs)) {
                        res.logs.forEach((item) => {
                            let html = `<div class="d-flex align-items-center mb-2"><small class="flex-shrink-0 mx-2">${item.time}</small><div class="flex-shrink-0"><span class="p-1 d-block bg-${item.status} rounded-circle"></span></div><div class="flex-grow-1 mx-2"><h6 class="mb-0 text-start">${item.title}</h6></div></div>`;
                            $(".timeline-widget").append(html);
                        });
                    }
                } else {
                    swal({
                        text: res.message ?? "Có lỗi xảy ra!",
                        icon: "error",
                    });
                    $("#orderInfo").modal("hide");
                }
            });
            break;
        }
        case "refund": {
            swal({
                text: "Huỷ đơn sẽ mất 10% phí trên tổng số tiền đã thanh toán để tránh SPAM. Bạn có muốn huỷ đơn không ?",
                icon: "question",
                showCancel: true,
                confirmText: "Đồng ý",
                cancelText: "Hủy",
            }).then((result) => {
                if (result.isConfirmed) {
                    if (data.length === 0) {
                        return swal({
                            text: "Vui Lòng Chọn Đơn Cần Huỷ",
                            icon: "warning",
                            confirmText: "Đồng ý",
                        });
                    }
                    swal({
                        title: "Đang huỷ đơn",
                        text: "Vui lòng chờ trong giây lát",
                        showLoading: true,
                    });
                    ajaxRequest(
                        urlOrder,
                        "PUT",
                        { data: data },
                        function (res) {
                            if (res.status === "success") {
                                Swal.close();
                                setTimeout(() => {
                                    updateBatchProgressModal(res.data);
                                }, 200);
                            } else {
                                swal({
                                    text: res.message ?? "Có lỗi xảy ra!",
                                    icon: "error",
                                });
                            }
                        }
                    );
                }
            });
            break;
        }
        case "warranty": {
            swal({
                text: "Bạn có muốn bảo hành đơn không ?",
                icon: "question",
                showCancel: true,
                confirmText: "Đồng ý",
                cancelText: "Hủy",
            }).then((result) => {
                if (result.isConfirmed) {
                    if (data.length === 0) {
                        return swal({
                            text: "Vui Lòng Chọn Đơn Cần Bảo Hành",
                            icon: "warning",
                            confirmText: "Đồng ý",
                        });
                    }
                    swal({
                        title: "Đang bảo hành",
                        text: "Vui lòng chờ trong giây lát",
                        showLoading: true,
                    });
                    ajaxRequest(
                        urlOrder,
                        "PUT",
                        { data: data },
                        function (res) {
                            if (res.status === "success") {
                                Swal.close();
                                setTimeout(() => {
                                    updateBatchProgressModal(res.data);
                                }, 200);
                            } else {
                                swal({
                                    text: res.message ?? "Có lỗi xảy ra!",
                                    icon: "error",
                                });
                            }
                        }
                    );
                }
            });
            break;
        }
        default:
            toastr.error("Không Xác Định Hành Động");
    }
}
let successLink = "";
let errorLink = "";
function updateBatchProgressModal(data) {
    $("#modalBatchProgress").modal("show");
    const $successContainer = $("#modalBatchProgress").find(
        ".col-md-6:first-child .list-group"
    );
    const $errorContainer = $("#modalBatchProgress").find(
        ".col-md-6:last-child .list-group"
    );
    $successContainer.empty();
    $errorContainer.empty();
    $("#successCount").text(data.success.length);
    $("#errorCount").text(data.error.length);
    let createSuccessItemHtml = (item) => {
        return `<div class="list-group-item py-2 mb-2 border-0 rounded-2"><div class="d-flex w-100 justify-content-between"><strong class="mb-1 text-success"><i class="fa-solid fa-hashtag me-1"></i> ${item.id}</strong></div><small class="text-muted text-break copy"><i class="fa-solid fa-link me-1"></i> ${item.link}</small></div>`;
    };
    let createErrorItemHtml = (item) => {
        return `<div class="list-group-item py-2 mb-2 border-0 rounded-2"><div class="d-flex w-100 justify-content-between"><strong class="mb-1 text-danger"><i class="fa-solid fa-hashtag me-1"></i> ${item.id}</strong></div><p class="mb-1 small fw-bold text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> ${item.reason}</p><small class="text-muted text-break copy"><i class="fa-solid fa-link me-1"></i> ${item.link}</small></div>`;
    };
    if (data.success.length > 0) {
        data.success.forEach((item) => {
            successLink += item.link + "\n";
            $successContainer.append(createSuccessItemHtml(item));
        });
    } else {
        $successContainer.append(
            '<div class="p-2 text-muted">Không có đơn hàng nào thành công.</div>'
        );
    }
    if (data.error.length > 0) {
        data.error.forEach((item) => {
            errorLink += item.link + "\n";
            $errorContainer.append(createErrorItemHtml(item));
        });
    } else {
        $errorContainer.append(
            '<div class="p-2 text-muted">Không có đơn hàng nào bị lỗi.</div>'
        );
    }
    $(document).on("click", ".list-group-item small.copy", function () {
        let text = $(this).text();
        copyToClipboard(text);
    });
}
$(document).ready(function () {
    $(document).on("click", ".order-action", function () {
        const action = $(this).data("type");
        if (action == "refund" || action == "warranty") {
            const selectedIds = $(".order-checkbox:checked")
                .map(function () {
                    return $(this).val();
                })
                .get();
            orderAction({ type: action, data: selectedIds });
            return;
        }
        const id = $(this).data("id");
        orderAction({ id: id, type: action });
    });
    $(document).on("click", ".copy-all-btn", function () {
        const action = $(this).data("type");
        if (action === "success") {
            return copyToClipboard(successLink);
        }
        return copyToClipboard(errorLink);
    });
    $(document).on("click", "#checkAll", function (e) {
        let checkedState = $(this).prop("checked");
        $(".order-checkbox").prop("checked", checkedState).trigger("change");
    });
    let startState = null;
    $(document).on("mousedown", ".order-cell", function (e) {
        $("body").addClass("no-select");
        if ($(e.target).hasClass("order-checkbox")) {
            startState = null;
            return;
        }
        e.preventDefault();
        const checkbox = $(this).find(".order-checkbox");
        const currentState = checkbox.prop("checked");
        startState = !currentState;
        checkbox.prop("checked", startState).trigger("change");
    });
    $(document).on("mouseenter", ".order-cell", function (e) {
        if (startState === null) return;
        const checkbox = $(this).find(".order-checkbox");
        checkbox.prop("checked", startState).trigger("change");
    });
    $("body").append(BATCH_PROGRESSIVE_MODAL);
    $("body").append(ORDER_INFO_MODAL);
});
