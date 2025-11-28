@extends('admin.layout.app')
@section('title', 'Trang thống kê')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="card border shadow-sm rounded-4 position-relative">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary bg-gradient text-white rounded-3 me-3 p-3">
                                <i class="fa-solid fa-users fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 text-wrap">Tổng thành viên</h6>
                                <h4 class="fw-bold text-dark mb-0 total-user">0</h4>
                            </div>
                            <div class="ms-auto px-3 py-2 bg-white border rounded-3 shadow-sm text-end">
                                <small class="text-muted d-block">Hôm nay</small>
                                <span class="fw-bold text-success d-block users-today">
                                    <i class="fa-solid fa-user-plus me-1"></i>0
                                </span>
                                <small class="users-percent text-success">
                                    <i class="fa-solid fa-arrow-up users-icon me-1"></i>
                                    0% So Với Hôm Qua
                                </small>
                            </div>
                        </div>
                        <div id="userChart"></div>
                    </div>
                </div>
            </div>
            <!-- Tổng lợi nhuận -->
            <div class="col-md-12">
                <div class="card border shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-success bg-gradient text-white rounded-3 me-3 p-3">
                                <i class="fa-solid fa-coins fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Tổng lợi nhuận</h6>
                                <h4 class="fw-bold text-dark mb-0 total-profit">0</h4>
                            </div>
                            <div class="ms-auto px-3 py-2 bg-white border-bottom rounded-3 shadow-sm text-end">
                                <small class="text-muted d-block">Hôm nay</small>
                                <span class="fw-bold text-info d-block profit-today">
                                    <i class="fa-solid fa-arrow-trend-up me-1"></i>
                                    0
                                </span>
                                <small class="profit-percent text-success">
                                    <i class="fa-solid fa-arrow-up profit-icon me-1"></i>
                                    0% So Với Hôm Qua
                                </small>
                            </div>
                        </div>
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Tổng nạp -->
            <div class="col-md-12">
                <div class="card border shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-info bg-gradient text-white rounded-3 me-3 p-3">
                                <i class="fa-solid fa-credit-card fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Tổng nạp</h6>
                                <h4 class="fw-bold text-dark mb-0 total-recharge">0</h4>
                            </div>
                            <div class="ms-auto px-3 py-2 bg-white border-bottom rounded-3 shadow-sm text-end">
                                <small class="text-muted d-block">Hôm nay</small>
                                <span class="fw-bold text-warning d-block recharge-today">
                                    <i class="fa-solid fa-circle-plus me-1"></i>
                                    0
                                </span>
                                <small class="recharge-percent text-success">
                                    <i class="fa-solid fa-arrow-up recharge-icon me-1"></i>
                                    0% So Với Hôm Qua
                                </small>
                            </div>
                        </div>
                        <div id="rechargeChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- Tổng đơn hàng -->
                <div class="card border shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-warning bg-gradient text-white rounded-3 me-3 p-3">
                                <i class="fa-solid fa-cart-shopping fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Tổng đơn hàng</h6>
                                <h4 class="fw-bold text-dark mb-0 total-order">0</h4>
                            </div>
                            <div class="ms-auto px-3 py-2 bg-white border-bottom rounded-3 shadow-sm text-end">
                                <small class="text-muted d-block">Hôm nay</small>
                                <span class="fw-bold text-primary d-block order-today">
                                    <i class="fa-solid fa-cart-shopping me-1"></i>
                                    0
                                </span>
                                <small class="order-percent text-success">
                                    <i class="fa-solid fa-arrow-up order-icon me-1"></i>
                                    0% So Với Hôm Qua
                                </small>
                            </div>
                        </div>
                        <div id="orderChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3 fw-bold">
                            <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i> Nhật kí hoạt động hôm nay
                        </h4>
                        <div class="row m-1">
                            <div class="col-md-4 p-2">
                                <label for="filterType" class="form-label fw-semibold">Loại
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-filter text-secondary me-1"></i> </span>
                                    <select id="filterType" class="selectpicker" data-live-search="true"
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
                                <tbody></tbody>
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
    <script src="/assets/libs/apexcharts-bundle/dist/apexcharts.min.js"></script>
    <script src="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.js"></script>
    <script>
        let userChart, revenueChart, rechargeChart, orderChart;
        let lastUpdateTime = 1;
    </script>
    <script>
        function pollDashboard() {
            ajaxRequest("{{ route("admin.dashboard.poll") }}", "GET", { last_update: lastUpdateTime }, function (res) {
                if (res && res.status == "success") {
                    if (!res.has_new_data) return;
                    let data = res.data;
                    updateDashboardElements(data);
                    updateCharts(data);
                    tableHistory.ajax.reload();
                    lastUpdateTime = res.timestamp
                }
            });
        }
        function updateDashboardElements(data) {
            $('.total-user').text(formatNumber(data.totalUser));
            $('.total-order').text(formatNumber(data.totalOrder));
            $('.total-profit').text(formatMoney(data.totalProfit));
            $('.total-recharge').text(formatMoney(data.totalRecharge));
            // Update daily stats arrays
            const ud = data.usersDay;
            $('.users-today').text(formatNumber(ud[3]));
            $('.users-icon').removeClass('fa-arrow-up fa-arrow-down').addClass(ud[1]);
            $('.users-percent').text(ud[2] + '%')
                .removeClass('text-success text-danger')
                .addClass(ud[1] === 'fa-arrow-up' ? 'text-success' : 'text-danger');
            const od = data.orderDay;
            $('.order-today').text(formatNumber(od[3]));
            $('.order-icon').removeClass('fa-arrow-up fa-arrow-down').addClass(od[1]);
            $('.order-percent').text(od[2] + '%')
                .removeClass('text-success text-danger')
                .addClass(od[1] === 'fa-arrow-up' ? 'text-success' : 'text-danger');
            const pd = data.profitDay;
            $('.profit-today').text(formatMoney(pd[3]));
            $('.profit-icon').removeClass('fa-arrow-up fa-arrow-down').addClass(pd[1]);
            $('.profit-percent').text(pd[2] + '%')
                .removeClass('text-success text-danger')
                .addClass(pd[1] === 'fa-arrow-up' ? 'text-success' : 'text-danger');
            const rd = data.rechargeDay;
            $('.recharge-today').text(formatMoney(rd[3]));
            $('.recharge-icon').removeClass('fa-arrow-up fa-arrow-down').addClass(rd[1]);
            $('.recharge-percent').text(rd[2] + '%')
                .removeClass('text-success text-danger')
                .addClass(rd[1] === 'fa-arrow-up' ? 'text-success' : 'text-danger');
        }
        function renderChart(el, name, data, colors) {
            var options = {
                chart: {
                    type: 'area',
                    height: 250,
                    toolbar: { show: false },
                    foreColor: '#adb5bd',
                    dropShadow: {
                        enabled: true,
                        top: 5,
                        left: 0,
                        blur: 5,
                        color: '#000',
                        opacity: 0.1
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                series: [{
                    name: name,
                    data: Object.values(data)
                }],
                xaxis: {
                    categories: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                    labels: {
                        style: {
                            fontWeight: 600,
                            colors: '#495057'
                        }
                    },
                    title: {
                        text: new Date().getFullYear().toString()
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return val.toLocaleString(); // 1000000 -> 1,000,000
                        },
                        style: { fontWeight: 600, colors: '#495057' }
                    }
                },
                colors: [colors[0]],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.4,
                        gradientToColors: [colors[1]],
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.2,
                        stops: [0, 100]
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#fff'],
                    strokeColors: colors[1],
                    strokeWidth: 3,
                    hover: { size: 8 }
                },
                grid: {
                    borderColor: "#e9ecef",
                    strokeDashArray: 4
                }
            };
            const chart = new ApexCharts(document.querySelector(el), options);
            chart.render();
            return chart;
        }
        function updateCharts(data) {
            userChart.updateSeries([{ data: Object.values(data.usersByMonth) }]);
            revenueChart.updateSeries([{ data: Object.values(data.profitsByMonth) }]);
            rechargeChart.updateSeries([{ data: Object.values(data.rechargesByMonth) }]);
            orderChart.updateSeries([{ data: Object.values(data.ordersByMonth) }]);
        }
    </script>
    <script>
        userChart = renderChart("#userChart", "Thành viên", [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], ["#4e73df", "#1cc88a"]);
        revenueChart = renderChart("#revenueChart", "Lợi nhuận", [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], ["#e74a3b", "#f6c23e"]);
        rechargeChart = renderChart("#rechargeChart", "Tổng nạp", [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], ["#36b9cc", "#6610f2"]);
        orderChart = renderChart("#orderChart", "Đơn hàng", [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], ["#858796", "#20c997"]);
        let tableHistory = createTable('#history', '{{ route('admin.user.logs.data', "days") }}',
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
                filterType: $("#filterType").val(),
            }));
    </script>
    <script>
        $(document).ready(() => {
            $('#filterType').on('change', function () {
                tableHistory.ajax.reload();
            })
            $('.selectpicker').selectpicker();
            pollDashboard();
            setInterval(pollDashboard, 10000);
        });
    </script>
@endsection