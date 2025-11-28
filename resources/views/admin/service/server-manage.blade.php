@extends('admin.layout.app')
@section('title', 'Các Gói Server')

@section('content')
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="bi bi-list-ul text-success"></i> Danh sách Các Gói Server
                    </h4>
                    <div class="row m-1">
                        <div class="col-md-3 p-2">
                            <label for="filterService" class="form-label fw-semibold"> Dịch Vụ</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-cubes text-warning me-1"></i>
                                </span>
                                <select id="filterService" class="form-control selectpicker" data-live-search="true"
                                    title="Chọn dịch vụ...">
                                    <option value="">-- Tất cả --</option>
                                    <option value="0">-- Không Có --</option>
                                    <option value="-1">-- Có --</option>
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
                            <label for="filterServiceApi" class="form-label fw-semibold">Dịch Vụ API
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-plug text-primary me-1"></i>
                                </span>
                                <select id="filterServiceApi" class="form-control selectpicker" data-live-search="true"
                                    title="Chọn dịch vụ API...">
                                    <option value="">-- Tất cả --</option>
                                    @foreach($suppliers as $supplier)
                                        <optgroup label="{{ strtoupper($supplier->name) }}">
                                            @foreach($supplier->unique_sorted_services as $service)
                                                <option value="{{ $supplier->name }}||---||{{ $service }}">
                                                    {{ $service }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 p-2">
                            <label for="filterStatus" class="form-label fw-semibold">
                                Trạng Thái </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-toggle-on text-info me-1"></i>
                                </span>
                                <select id="filterStatus" class="form-control selectpicker" data-title="Chọn trạng thái..."
                                    data-live-search="true">
                                    <option value="">-- Tất cả --</option>
                                    <option value="0">Tắt</option>
                                    <option value="1">Bật</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="listServer" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thao tác</th>
                                    <th>Tiêu Đề</th>
                                    <th>Dịch Vụ</th>
                                    <th>Máy chủ</th>
                                    <th>Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Trạng Thái</th>
                                    <th>Nguồn API</th>
                                    <th>Thời Gian</th>
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
@section("css")
    <link rel="stylesheet" href="/assets/libs/DataTables/datatables.min.css">
    <link rel="stylesheet" href="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.css">
    <style>
        #listServer th,
        #listServer td {
            text-align: center;
            vertical-align: middle;
        }

        #listServer {
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
    <script src="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.js"></script>
    <script>
        const tableServer = createTable('#listServer', '{{ route('admin.service.server.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            {
                data: null,
                orderable: false,
                render: row => `<div class="btn-group btn-group-sm" role="group"><button class="btn btn-outline-primary btn-edit" data-id="${row.id}" title="Chỉnh sửa"><i class="fa fa-pencil-square"></i></button><button class="btn btn-outline-danger btn-delete" data-id="${row.id}" title="Xoá"><i class="fa fa-trash"></i></button></div>`
            },

            {
                data: 'title',
                render: title => `<div class="text-wrap text-start" style="min-width: 250px;"><i class="fa fa-hdd-stack text-secondary me-1"></i> <span class="fw-semibold">${title}</span></div>`
            },

            {
                data: null,
                render: row => {
                    const socialIcon = row.social_image ? `<img src="${row.social_image}" width="20" height="20" class="rounded-circle me-1">` : `<i class="fa fa-share text-muted me-1"></i>`;
                    return `<div class="d-flex align-items-center justify-content-start text-start">${socialIcon}<div><div class="fw-semibold">${row.social_name}</div><small class="text-muted">${row.service_name}</small></div></div>`;
                }
            },

            {
                data: 'server',
                render: server => `<span class="badge bg-danger bg-opacity-75">MC ${server ?? 0}</span>`
            },

            {
                data: null,
                render: row => `<span class="fw-semibold text-success">${formatMoney(row.price, "đ", 2)}</span>`
            },

            {
                data: null,
                render: row => `<span class="text-primary fw-semibold">${row.min ?? 0} <i class="fa fa-arrows-left-right"></i> ${row.max ?? 0}</span>`
            },

            {
                data: 'status',
                render: status => status
                    ? `<span class="badge bg-success-subtle text-success fw-semibold"><i class="fa fa-check-circle me-1"></i> Hoạt động</span>`
                    : `<span class="badge bg-secondary-subtle text-secondary fw-semibold"><i class="fa fa-pause-circle me-1"></i> Tạm tắt</span>`
            },

            {
                data: null,
                render: row => {
                    return `<div class="text-start text-wrap" style="min-width: 220px;"><div class="d-flex align-items-center mb-1"><i class="fa fa-plug text-info me-1"></i><span class="fw-semibold text-dark">${row.supplier_name}</span></div><div class="ms-4 text-muted small lh-sm">${row.supplier_service}</div></div>`;
                }
            },
            {
                data: 'created_at',
                render: function (data) {
                    var formattedDate = moment(data).format('YYYY-MM-DD HH:mm:ss');
                    return `<i class="fa fa-clock text-primary me-1"></i> ${formattedDate}`;
                }
            },
        ], () => ({
            filterService: $("#filterService").val(),
            filterServiceApi: $("#filterServiceApi").val(),
            filterStatus: $("#filterStatus").val(),
        }));
    </script>
    <script>
        $(document).ready(function () {
            $('#filterService, #filterServiceApi, #filterStatus').on('change', function () {
                tableServer.ajax.reload();
            })
            $(document).on('click', '.btn-edit', function () {
                const id = $(this).data('id');
                const url = `{{ route('admin.service.server.edit', 'id') }}`.replace('id', id);
                window.open(url, '_blank');
            });
            $(document).on('click', '.btn-delete', function () {
                const id = $(this).data('id');
                swal({
                    title: 'Xác nhận xoá?',
                    text: 'Server này sẽ bị xoá vĩnh viễn khỏi hệ thống!',
                    icon: 'warning',
                    showCancel: true,
                    confirmText: 'Xoá ngay',
                    cancelText: 'Hủy',
                    onConfirm: () => {
                        ajaxRequest(
                            `{{ route('admin.service.server.destroy', 'id') }}`.replace('id', id),
                            'DELETE',
                            {},
                            res => {
                                if (res.status === 'success') {
                                    swal({ title: 'Thành công', text: 'Đã xoá server!', icon: 'success' });
                                    tableServer.ajax.reload();
                                } else {
                                    swal({ title: 'Lỗi', text: res.message || 'Xoá thất bại!', icon: 'error' });
                                }
                            }
                        );
                    }
                });
            });
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>

    </script>
@endsection