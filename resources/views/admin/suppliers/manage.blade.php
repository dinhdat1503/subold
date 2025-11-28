@extends('admin.layout.app')
@section('title', 'Quản Lý Api')
@section('content')
    <div class="row g-4">
        {{-- FORM THÊM SUPPLIER MỚI --}}
        <div class="col-md-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient bg-primary text-white fw-bold">
                    <i class="fa fa-plus-circle me-1"></i> Thêm Supplier mới
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supplier.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                                        placeholder="Tên supplier" required>
                                    <label for="name">
                                        <i class="fas fa-building me-2 text-primary"></i>
                                        <span class="border-start border-info ps-3">Tên supplier</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="url" class="form-control" name="base_url" id="base_url"
                                        value="{{ old('base_url') }}" placeholder="https://domain.com/api" required>
                                    <label for="base_url">
                                        <i class="fas fa-link me-2 text-info"></i>
                                        <span class="border-start border-info ps-3">Base URL</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="api_key" id="api_key"
                                        value="{{ old('api_key') }}" placeholder="API key" required>
                                    <label for="api_key">
                                        <i class="fas fa-key me-2 text-warning"></i>
                                        <span class="border-start border-warning ps-3">API key</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="proxy" id="proxy"
                                        value="{{ old('proxy') }}" placeholder="user:pass:host:port">
                                    <label for="proxy">
                                        <i class="fas fa-plug me-2 text-secondary"></i>
                                        <span class="border-start border-secondary ps-3">Proxy (tuỳ chọn)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <select class="form-select" id="api" name="api" required>
                                        <option value="SMM" {{ old('api', 'SMM') === 'SMM' ? 'selected' : '' }}> SMM</option>
                                        <option value="2MXH" {{ old('api') === '2MXH' ? 'selected' : '' }}>2MXH</option>
                                        <option value="TRUMVIP" {{ old('api') === 'TRUMVIP' ? 'selected' : '' }}>TRUMVIP
                                        </option>
                                    </select>
                                    <label for="api">
                                        <i class="fas fa-code-branch me-2 text-success"></i>
                                        <span class="border-start border-success ps-3">Loại API</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="number" min="1" class="form-control" name="price_unit_value"
                                        id="price_unit_value" value="{{ old('price_unit_value', 1000) }}" required>
                                    <label for="price_unit_value">
                                        <i class="fas fa-hand-holding-usd me-2 text-success"></i>
                                        <span class="border-start border-success ps-3">Giá Theo Số Lượng</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <select name="currency" id="currency" class="form-select" required>
                                        <option value="VND" {{ old('currency', 'VND') == 'VND' ? 'selected' : '' }}>VND
                                        </option>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="Xu" {{ old('currency') == 'Xu' ? 'selected' : '' }}>Xu</option>
                                        <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR</option>
                                        <option value="THB" {{ old('currency') == 'THB' ? 'selected' : '' }}>THB</option>
                                        <option value="CNY" {{ old('currency') == 'CNY' ? 'selected' : '' }}>CNY</option>
                                    </select>
                                    <label for="currency">
                                        <i class="fas fa-money-bill me-2 text-info"></i>
                                        <span class="border-start border-info ps-3">Đơn vị tiền tệ</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="number" step="0.0001" min="0" class="form-control" name="exchange_rate"
                                        id="exchange_rate" value="{{ old('exchange_rate', 1.0000) }}" required>
                                    <label for="exchange_rate">
                                        <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                        <span class="border-start border-primary ps-3">Tỷ giá quy đổi</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="number" step="0.01" min="0" max="1000" class="form-control"
                                        name="price_percent" id="price_percent" value="{{ old('price_percent', 30) }}"
                                        required>
                                    <label for="price_percent">
                                        <i class="fas fa-percentage me-2 text-danger"></i>
                                        <span class="border-start border-danger ps-3">% Giá</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-center justify-content-start">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        <span>Trạng Thái</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-plus-circle me-1"></i> Thêm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- DANH SÁCH SUPPLIER --}}
        <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-gradient bg-success text-white fw-bold">
                    <i class="fa fa-list me-1"></i> Danh sách Các API
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="listSupplier" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Thao tác</th>
                                    <th>Tên Supplier</th>
                                    <th>Link</th>
                                    <th>Username</th>
                                    <th>Số dư</th>
                                    <th>Loại API</th>
                                    <th>Trạng thái</th>
                                    <th>Cập nhật</th>
                                    <th>Ngày Thêm</th>
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
    <style>
        #listSupplier th,
        #listSupplier td {
            text-align: center;
            vertical-align: middle;
        }

        #listSupplier {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script>
        const tableSupplier = createTable('#listSupplier', '{{ route('admin.supplier.data') }}', [
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
                data: 'name',
                render: data => `<span class="fw-semibold"><i class="bi bi-truck me-1 text-primary"></i>${data}</span>`
            },

            {
                data: 'base_url',
                render: data => data
                    ? `<a href="${data}" target="_blank" class="text-decoration-none"><i class="bi bi-link-45deg me-1 text-info"></i>${data}</a>`
                    : `<span class="text-muted">-</span>`
            },
            {
                data: 'username',
                render: function (data) {
                    return `<i class="fa fa-user text-primary me-1"></i> <span class="fw-semibold">${data}</span>`;
                }
            },
            {
                data: 'money',
                render: function (data, type, row, meta) {
                    let cur = row.currency == "VND" || row.currency == "Xu" ? 0 : 4;
                    return `<div><strong>${formatMoney(data, row.currency, cur)}</strong><br></div>`;
                }
            },
            {
                data: 'api',
                render: function (data) {
                    return `<span class="badge bg-info px-3 py-2">${data}</span>`;
                }
            },

            {
                data: 'status',
                render: function (data) {
                    return data == 1
                        ? '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Hoạt động</span>'
                        : '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-eye-slash me-1"></i>Tắt</span>';
                }
            },
            {
                data: 'last_synced_at',
                render: function (data) {
                    return data ? moment(data).fromNow() : '-';
                }
            },
            {
                data: 'created_at',
                render: function (data) {
                    var formattedDate = moment(data).format('YYYY-MM-DD HH:mm:ss');
                    return `<i class="fa fa-clock text-primary me-1"></i> ${formattedDate}`;
                }
            },
        ]);
    </script>
    <script>
        $(document).on('click', '.btn-edit', function () {
            const id = $(this).data('id');
            const url = `{{ route('admin.supplier.edit', 'id') }}`.replace('id', id);
            window.location.href = url;
        });
        $(document).on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            swal({
                title: 'Xác nhận xoá?',
                text: 'Supplier này sẽ bị xoá vĩnh viễn khỏi hệ thống!',
                icon: 'warning',
                showCancel: true,
                confirmText: 'Xoá ngay',
                cancelText: 'Hủy',
                onConfirm: () => {
                    ajaxRequest(
                        `{{ route('admin.supplier.destroy', 'id') }}`.replace('id', id),
                        'DELETE',
                        {},
                        res => {
                            if (res.status === 'success') {
                                swal({ title: 'Thành công', text: 'Đã xoá Supplier!', icon: 'success' });
                                tableSupplier.ajax.reload();
                            } else {
                                swal({ title: 'Lỗi', text: res.message || 'Xoá thất bại!', icon: 'error' });
                            }
                        }
                    );
                }
            });
        });

    </script>
@endsection