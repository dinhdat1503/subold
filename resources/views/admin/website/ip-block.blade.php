@extends('admin.layout.app')
@section('title', 'Quản lý IP')

@section('content')
    <div class="col-md-12">
        <!-- Form thêm IP -->
        <div class="card border shadow-sm rounded-4">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="ti ti-ban text-danger me-2"></i> Thêm IP cần chặn
                </h4>
                <form action="{{ route('admin.ip.store') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-danger" name="ip_address"
                            value="{{ old('ip_address') }}" placeholder="Địa chỉ IP" required>
                        <label><i class="ti ti-world text-danger me-2"></i><span class="border-start border-danger ps-3">Địa
                                chỉ IP</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-secondary" name="reason"
                            value="{{ old('reason') }}" placeholder="Lý do chặn (tuỳ chọn)">
                        <label><i class="ti ti-info-circle text-secondary me-2"></i><span
                                class="border-start border-secondary ps-3">Lý do (tuỳ chọn)</span></label>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-danger col-12 rounded-pill fw-semibold">
                            <i class="ti ti-lock me-1"></i> Chặn IP
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách IP bị chặn -->
        <div class="card border shadow-sm rounded-4 mt-4">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="ti ti-list-check me-2 text-secondary"></i> Danh sách IP bị chặn
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
                        <label for="filterBanned" class="form-label fw-semibold">Trạng thái cấm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa fa-lock text-danger"></i>
                            </span>
                            <select id="filterBanned" class="form-control selectpicker" data-live-search="true"
                                title="Chọn trạng thái cấm...">
                                <option value="">--Tất cả--</option>
                                <option value="0">Không bị cấm</option>
                                <option value="1">Đang bị cấm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="blocked_ips" class="table table-hover table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Địa chỉ IP</th>
                                <th>Lý do</th>
                                <th>Lần thử</th>
                                <th>Bị cấm</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
        #blocked_ips th,
        #blocked_ips td {
            text-align: center;
            vertical-align: middle;
        }

        #blocked_ips {
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
    <script src="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.js"></script>

    <script>
        initFlatpickr("#filterDateRange");
        let table = createTable('#blocked_ips', '{{ route('admin.ip.manage.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            {
                data: 'ip_address',
                render: function (data) {
                    return `<span class="badge bg-light border text-dark" style="min-width:100px"><i class="fa fa-network-wired text-info me-1"></i> ${data}</span>`;
                }
            },
            {
                data: 'reason',
                render: function (data) {
                    return `<div class="text-break form-control" style="min-width:300px">${data}</div>`;
                }
            },
            { data: 'attempts' },
            {
                data: 'banned',
                render: function (data) {
                    const badge = data ? '<span class="badge bg-danger">Có</span>' : '<span class="badge bg-success">Không</span>';
                    return `<div class="text-center">${badge}</div>`;
                }
            },
            {
                data: 'created_at',
                render: function (data) {
                    var formattedDate = moment(data).format('YYYY-MM-DD HH:mm:ss');
                    return `<i class="fa fa-clock text-primary me-1"></i> ${formattedDate}`;
                }
            },
            {
                data: null,
                searchable: false,
                orderable: false,
                render: function (data) {
                    return `<button class="btn btn-outline-danger btn-sm rounded-circle action" title="Xoá" data-id="${data.id}"><i class="ti ti-trash"></i></button>`;
                }
            }
        ], () => ({
            filterDate: $("#filterDateRange").val(),
            filterBanned: $("#filterBanned").val()
        }));
        $('#filterBanned, #filterDateRange').on('change', function () {
            table.ajax.reload();
        });
        $('.selectpicker').selectpicker();
        $(document).on('click', '.action', function () {
            let id = $(this).data('id');
            swal({
                icon: 'question',
                text: 'Bạn có chắc chắn muốn xoá IP này khỏi danh sách chặn?',
                showCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    swal({ title: 'Đang xoá...', showLoading: true });
                    ajaxRequest(
                        `{{ route('admin.ip.destroy', 'ID') }}`.replace('ID', id),
                        "DELETE",
                        {},
                        function () {
                            swal({ text: "Đã xoá thành công!", icon: "success" });
                            table.ajax.reload();
                        }
                    );
                }
            });
        });
    </script>
@endsection