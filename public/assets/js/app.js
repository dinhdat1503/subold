function swal({
    title = "Thông báo",
    text = "",
    icon = "info",
    showCancel = false,
    confirmText = "OK",
    cancelText = "Hủy",
    showLoading = false, // 👈 thêm tham số loading
    onConfirm = null,
    onCancel = null,
}) {
    let colorMap = {
        success: "btn-success",
        error: "btn-danger",
        warning: "btn-warning",
        info: "btn-info",
        question: "btn-primary",
    };

    return Swal.fire({
        heightAuto: false,
        icon,
        title: `<h4 class="fw-bold mb-2 text-capitalize">${title}</h4>`,
        html: `<div class="fs-6 text-secondary">${text}</div>`,
        showCancelButton: showCancel,
        confirmButtonText: `<i class="fa fa-check-circle fa-fw"></i> ${confirmText}`,
        cancelButtonText: showCancel
            ? `<i class="fa fa-times-circle fa-fw"></i> ${cancelText}`
            : null,
        focusCancel: showCancel,
        buttonsStyling: false,
        customClass: {
            popup: "rounded-4 shadow-lg border-0",
            confirmButton: `btn ${
                colorMap[icon] || "btn-primary"
            } d-flex align-items-center justify-content-center gap-2 px-4 py-2 rounded-pill fw-semibold`,
            cancelButton: showCancel
                ? "btn btn-outline-secondary ms-2 d-flex align-items-center justify-content-center gap-2 px-4 py-2 rounded-pill fw-semibold"
                : "",
        },
        backdrop: `rgba(0,0,0,0.45)`,
        didOpen: () => {
            if (showLoading) {
                Swal.showLoading();
            }
        },
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === "function") onConfirm();
        if (
            result.dismiss === Swal.DismissReason.cancel &&
            typeof onCancel === "function"
        )
            onCancel();
        return result;
    });
}
function googleTranslateElementInit() {
    new google.translate.TranslateElement(
        {
            pageLanguage: "vi", // Ngôn ngữ gốc
            includedLanguages: "vi,en,zh-CN", // Ngôn ngữ cho phép
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        },
        "google_translate_element"
    );
}
function changeLanguage(langCode) {
    const fromLang = "vi";
    const toLang = langCode;
    const googTransValue = `/${fromLang}/${toLang}`;
    document.cookie = `googtrans=${googTransValue};path=/`;
    document.cookie = `googtrans=${googTransValue};domain=${window.location.hostname};path=/`;
    location.reload();
}
function copyToClipboard(text) {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            swal({
                title: "Thành công",
                text: "Đã copy vào clipboard!",
                icon: "success",
            });
        })
        .catch(() => {
            swal({
                title: "Lỗi",
                text: "Copy thất bại, vui lòng thử lại!",
                icon: "error",
            });
        });
}
function createTable(selector, url, columns, extraParams = () => ({})) {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().clear().destroy();
    }
    let orderableIndex = columns.findIndex((col) => col.orderable !== false);
    if (orderableIndex === -1) orderableIndex = 0;
    let table = $(selector).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "GET",
            data: function (d) {
                return $.extend({}, d, extraParams());
            },
            error: function (xhr, error, thrown) {
                // Lỗi request (404, 500, ...)
                let message = "Có lỗi xảy ra khi tải dữ liệu!";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
        },
        columns: columns,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json",
        },
        lengthMenu: [
            [10, 50, 100, 500, 1000, 5000, 10000],
            ["10", "50", "100", "500", "1.000", "5.000", "10.000"],
        ],
        pageLength: 10,
        order: [[orderableIndex, "desc"]],
    });

    // Bắt thêm sự kiện DataTables error (JSON sai format, parse lỗi, v.v...)
    $(selector).on("error.dt", function (e, settings, techNote, message) {
        toastr.error("Lỗi xử lý dữ liệu bảng!");
    });

    return table;
}
function formatNumber(number, decimals = null, locale = "vi-VN") {
    number = parseFloat(number);

    if (isNaN(number)) return "0";

    if (decimals === null) {
        // Giữ nguyên số thập phân từ input
        let str = number.toString();
        if (str.includes(".")) {
            decimals = str.split(".")[1].length;
        } else {
            decimals = 0;
        }
    }

    return number.toLocaleString(locale, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}
function formatMoney(number, unit = "VNĐ", decimals = 0, locale = "vi-VN") {
    return formatNumber(number, decimals, locale) + " " + unit;
}
function ajaxRequest(
    url,
    method = "GET",
    data = {},
    onSuccess = null,
    onError = null
) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (typeof onSuccess === "function") {
                onSuccess(response); // luôn trả response về callback
            }
        },
        error: function (xhr, status, error) {
            let msg = "Lỗi hệ thống, vui lòng thử lại sau";
            if (xhr.status === 404) msg = "Trang không tồn tại!";
            if (xhr.status === 500) msg = "Lỗi máy chủ, liên hệ admin!";
            if (xhr.status === 401)
                msg = "Phiên làm việc hết hạn. Vui lòng đăng nhập lại!";
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let firstErrorMsg = "";
                for (let field in errors) {
                    firstErrorMsg = errors[field][0];
                    break;
                }
                msg = firstErrorMsg;
            }
            toastr.error(msg);
            if (typeof onError === "function") {
                onError(xhr, status, error);
            }
        },
    });
}
function initFlatpickr(selector, options = {}) {
    const defaultOptions = {
        mode: "range",
        enableTime: true,
        time_24hr: true,
        enableSeconds: false,
        locale: "vn",
        dateFormat: "Y-m-d H:i:s",
        minDate: "2000-01-01",
        maxDate: "2035-12-31",
        onClose: function (selectedDates, dateStr, instance) {
            // chỉ chạy khi mode là "range"
            if (instance.config.mode === "range") {
                // chưa chọn đủ hoặc cùng 1 ngày
                if (
                    selectedDates.length < 2 ||
                    (selectedDates.length === 2 &&
                        selectedDates[0].toDateString() ===
                            selectedDates[1].toDateString())
                ) {
                    instance.clear();
                    instance.input.value = ""; // clear text trong input
                    $(instance.input).val(""); // clear input thật trên DOM
                }
            }
        },
    };
    return flatpickr(selector, Object.assign({}, defaultOptions, options));
}
function statusLog(status, html = true) {
    const map = {
        Login: ["Đăng Nhập", "warning"],
        Register: ["Đăng Ký", "success"],
        RequestChangePassword: ["Yêu Cầu Đổi Mật Khẩu", "info"],
        Balance: ["Thay Đổi Số Dư", "primary"],
        ChangePassword: ["Đổi Mật Khẩu", "danger"],
        ChangeProfile: ["Thay Đổi Thông Tin Cá Nhân", "secondary"],
        ChangeApiKey: ["Thay Đổi Api Key", "success"],
        RegenTwoFA: ["Tạo Lại Mã 2FA", "primary"],
        UpdateSecurity: ["Thay Đổi Bảo Mật", "danger"],
        SendOtpEmail: ["Yêu Cầu Mã OTP Mail", "dark"],
    };

    let label, color;

    if (map.hasOwnProperty(status)) {
        [label, color] = map[status];
    } else {
        label = "Không Xác Định";
        color = "secondary";
    }

    if (html) {
        return `<span class="badge bg-${color} badge-${color}">${label}</span>`;
    } else {
        return label;
    }
}
function renderOrderInfo(data) {
    if (!data) return "";

    let html = [];

    if (data.reaction) {
        html.push(`
            <span class="me-1">
                <img src="/assets/images/client/services/reaction/${data.reaction}.png"
                     alt="${data.reaction}"
                     style="height:20px;width:20px;object-fit:contain;">
            </span>
        `);
    }
    if (data.time) {
        html.push(`
            <span class="badge bg-warning text-dark me-1">
                <i class="fas fa-clock me-1"></i>${data.time}
            </span>
        `);
    }
    if (data.amount) {
        html.push(`
            <span class="badge bg-success me-1">
                <i class="fas fa-calendar-day me-1"></i>${data.amount}
            </span>
        `);
    }
    if (data.comment) {
        html.push(`
            <textarea class="form-control form-control-sm my-1" rows="2" readonly
                style="min-width:200px; min-height: 100px">${data.comment}</textarea>
        `);
    }

    return `<div class="text-break">${html.join("")}</div>`;
}
function statusOrder(status, html = true) {
    const map = {
        WaitingForRefund: ["Đang huỷ", "warning", "fa-hourglass-half"],
        Pending: ["Chờ xử lý", "warning", "fa-clock"],
        Active: ["Đang hoạt động", "info", "fa-play-circle"],
        Error: ["Lỗi đơn", "danger", "fa-times-circle"],
        Warranty: ["Bảo hành", "secondary", "fa-shield-alt"],
        Completed: ["Hoàn thành", "success", "fa-check-circle"],
        Refunded: ["Hoàn tiền", "primary", "fa-undo-alt"],
        Cancelled: ["Đã hủy", "danger", "fa-ban"],
    };

    if (!(status in map)) {
        return html
            ? '<span class="badge bg-secondary"><i class="fas fa-question-circle me-1"></i> Không xác định</span>'
            : "Không xác định";
    }
    const [label, color, icon] = map[status];
    return html
        ? `<span class="badge bg-${color}"><i class="fas ${icon} me-1"></i> ${label}</span>`
        : label;
}
function userLevel(level, html = true) {
    if (html) {
        switch (parseInt(level)) {
            case 1:
                return '<span class="badge bg-primary">Thành viên</span>';
            case 2:
                return '<span class="badge bg-success">Cộng tác viên</span>';
            case 3:
                return '<span class="badge bg-warning">Đại lý</span>';
            case 4:
                return '<span class="badge bg-danger">Nhà phân phối</span>';
            default:
                return '<span class="badge bg-secondary">Khách</span>';
        }
    } else {
        switch (parseInt(level)) {
            case 1:
                return "Thành viên";
            case 2:
                return "Cộng tác viên";
            case 3:
                return "Đại lý";
            case 4:
                return "Nhà phân phối";
            default:
                return "Khách";
        }
    }
}
$(document).ready(function () {
    $(".copy").on("click", function () {
        let text = $(this).text(); // hoặc .html() nếu có thẻ con
        copyToClipboard(text);
    });
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        rtl: false,
        positionClass: "toast-top-right",
        preventDuplicates: true,
        onclick: null,
        showDuration: "400",
        hideDuration: "1000",
        timeOut: "4000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        toastClass: "toast rounded-1 py-4",
        containerId: "toast-container",
    };
});
