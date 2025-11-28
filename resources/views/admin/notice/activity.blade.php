@extends('admin.layout.app')
@section('title', 'Quản lí các hoạt động')

@section('content')
    <div class="col-md-12">
        <!-- Form thêm hoạt động -->
        <div class="card border shadow-sm rounded-4">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="ti ti-plus text-success me-2"></i> Thêm hoạt động mới
                </h4>
                <form action="{{ route('admin.notice.activity.store') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-info" name="username"
                            placeholder="Tên người tạo" required>
                        <label><i class="ti ti-user text-info me-2"></i><span class="border-start border-info ps-3">Tên
                                người tạo</span></label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control border border-info" style="min-height: 100px" name="content"
                            placeholder="Nội dung mới" required></textarea>
                        <label><i class="ti ti-message text-warning me-2"></i><span
                                class="border-start border-warning ps-3">Nội dung mới</span></label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary col-12 rounded-pill fw-semibold">
                            <i class="ti ti-device-floppy me-1"></i> Lưu hoạt động
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách lịch sử -->
        <div class="card border shadow-sm rounded-4 mt-4">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="ti ti-history me-2 text-secondary"></i> Lịch sử hoạt động
                </h4>
                <div class="table-responsive">
                    <table id="history" class="table table-hover table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người tạo</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
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

@section("css")
    <link rel="stylesheet" href="/assets/libs/DataTables/datatables.min.css">
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
    </style>
@endsection

@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script>
        // Khởi tạo datatable
        let table = createTable('#history', '{{ route('admin.notice.activity.data') }}', [
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
                data: 'content',
                render: function (data) {
                    return `<textarea class="form-control" readonly style="min-width:300px; min-height:100px">${data}</textarea>`
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
                render: function (data) {
                    return `<button class="btn btn-outline-danger btn-sm rounded-circle action" title="Xoá" data-id="${data.id}"><i class="ti ti-trash"></i></button>`;
                }
            }
        ]);

        $(document).on('click', '.action', function () {
            let id = $(this).data('id');
            swal({
                icon: 'question',
                text: 'Bạn có chắc chắn muốn xoá hoạt động này?',
                showCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    swal({ title: 'Đang xoá...', showLoading: true });
                    ajaxRequest(
                        `{{ route('admin.notice.activity.destroy', 'ID') }}`.replace('ID', id),
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