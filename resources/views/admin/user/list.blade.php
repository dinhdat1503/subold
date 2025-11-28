@extends('admin.layout.app')
@section('title', 'Danh sách thành viên')
@section('content')

    <div class="row g-4">
        <!-- Tổng thành viên -->
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                        <i class="fa-solid fa-person"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tổng thành viên</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ formatNumber($userCount) }}</h4>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Tổng cộng tác viên -->
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tổng cộng tác viên</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ formatNumber($ctvCount) }}</h4>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Tổng đại lý -->
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="fa-solid fa-shop"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tổng đại lý</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ formatNumber($dailyCount) }}</h4>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Tổng nhà phân phối -->
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tổng nhà phân phối</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ formatNumber($nppCount) }}</h4>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Tổng quản trị viên -->
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 p-3 me-3">
                        <i class="fa-solid fa-shield"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tổng quản trị viên</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ formatNumber($adminCount) }}</h4>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-body">
                    <h4 class="card-title fw-bold mb-3">
                        <i class="bi bi-people-fill text-primary me-2"></i> Danh sách thành viên
                    </h4>
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
                            <label for="filterID" class="form-label fw-semibold">Lọc theo ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-hashtag text-primary"></i>
                                </span>
                                <input type="number" id="filterID" class="form-control" placeholder="Nhập ID cần tìm">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="userList" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thao tác</th>
                                    <th>Thông tin</th>
                                    <th>Số dư</th>
                                    <th>Tổng nạp</th>
                                    <th>Đã tiêu</th>
                                    <th>Utm Source</th>
                                    <th>IP / User Agent</th>
                                    <th>Lần cuối / Tạo lúc</th>
                                    <th>Level / Status</th>
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
        #userList th,
        #userList td {
            text-align: center;
            vertical-align: middle;
        }

        #userList {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-group .btn {
            padding: 4px 8px;
        }
    </style>
@endsection

@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script src="/assets/libs/flatpickr/flatpickr.js"></script>
    <script src="/assets/libs/flatpickr/dist/vn.js"></script>
    <script>
        initFlatpickr("#filterDateRange");
        let tableUser = createTable('#userList', '{{ route('admin.user.list.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            {
                data: null,
                render: row => `<div class="btn-group btn-group-sm"><button class="btn btn-outline-primary btn-edit" data-id="${row.id}" title="Sửa"><i class="fa-solid fa-pen"></i></button><button class="btn btn-outline-danger btn-delete" data-id="${row.id}" title="Xoá"><i class="fa-solid fa-trash"></i></button></div>`,
                orderable: false,
                searchable: false,

            },
            {
                data: null,
                render: row => `<div><div class="fw-bold text-dark"><i class="fa-solid fa-user me-1"></i> ${row.full_name ?? ''}</div><div class="text-muted small"><i class="fa-solid fa-envelope me-1"></i> ${row.email ?? ''}</div><div class="text-muted small"><i class="fa-solid fa-id-badge me-1"></i> ${row.username ?? ''}</div></div>`
            },
            {
                data: 'balance',
                render: data => `<span class="badge bg-success rounded-pill"><i class="fa-solid fa-bitcoin-sign"></i> ${formatMoney(data)}</span>`
            },
            {
                data: 'total_recharge',
                render: data => `<span class="badge bg-primary rounded-pill"><i class="fa-solid fa-wallet"></i> ${formatMoney(data)}</span>`
            },
            {
                data: 'total_deduct',
                render: data => `<span class="badge bg-warning text-dark rounded-pill"><i class="fa-solid fa-credit-card"></i> ${formatMoney(data)}</span>`
            },
            {
                data: 'utm_source',
                render: data => `${data ?? ""}`
            },
            {
                data: null,
                render: row => `<div><div class="text-muted small"><i class="fa fa-network-wired text-info me-1"></i> ${row.last_ip ?? '-'}</div><div class="text-muted small text-break text-wrap" style="min-width: 300px"><i class="fa fa-desktop text-secondary me-1"></i> ${row.last_useragent ?? '-'}</div></div>`
            },
            {
                data: null,
                render: row => `<div><div class="text-muted small"><i class="fa-solid fa-clock-rotate-left"></i> ${row.last_online ? moment(row.last_online).fromNow() : '-'}</div><div class="text-muted small"><i class="fa fa-clock text-primary me-1"></i> ${row.created_at ? moment(row.created_at).format('DD/MM/YYYY HH:mm') : '-'}</div></div>`
            },
            {
                data: null,
                render: row => `<div class="d-flex gap-1 flex-wrap justify-content-center"><div class="fw-semibold">${userLevel(row.level)}</div>${row.status == 1 ? `<span class="badge bg-success"><i class="fa-solid fa-circle-check"></i> Hoạt động</span>` : `<span class="badge bg-danger"><i class="fa-solid fa-circle-xmark"></i> Khóa</span>`}</div>`
            }
        ], () => ({
            filterDate: $("#filterDateRange").val(),
            filterID: $("#filterID").val()
        }));
    </script>
    <script>
        $('#filterDateRange, #filterID').on('change', function () {
            tableUser.ajax.reload();
        })
        $(document).on('click', '.btn-edit', function () {
            const id = $(this).data('id');
            window.location.href = `{{ route('admin.user.edit', ':id') }}`.replace(':id', id);

        });
        $(document).on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            swal({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Bạn sẽ không thể khôi phục lại dữ liệu!",
                icon: 'warning',
                showCancel: true,
                confirmText: 'Xóa',
                cancelText: 'Hủy',
                onConfirm: () => {
                    ajaxRequest(
                        `{{ route('admin.user.destroy', ':id') }}`.replace(':id', id),
                        'DELETE',
                        {},
                        res => {
                            if (res.status === 'success') {
                                swal({ title: 'Đã xóa!', text: 'Xóa thành công.', icon: 'success' });
                                tableUser.DataTable().ajax.reload();
                            } else {
                                swal({ title: 'Lỗi', text: res.message || 'Xóa thất bại!', icon: 'error' });
                            }
                        }
                    );
                }
            });
        });
    </script>
@endsection