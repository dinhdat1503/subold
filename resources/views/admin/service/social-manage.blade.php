@extends('admin.layout.app')
@section('title', 'Social Media')

@section('content')
    <div class="row">
        <!-- Form thêm dịch vụ -->
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient bg-primary text-white fw-bold">
                    <i class="fa-solid fa-plus-circle"></i> Thêm dịch vụ MXH mới
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.service.social.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" value="{{ old('name') }}" name="name"
                                placeholder="Tên MXH">
                            <label><i class="fa-brands fa-facebook text-primary me-2"></i> <span
                                    class="border-0 border-start border-primary ps-3"> Tên dịch vụ MXH </span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" value="{{ old('slug') }}" name="slug"
                                placeholder="Path dịch vụ">
                            <label><i class="fa-solid fa-link text-primary me-2"></i><span
                                    class="border-0 border-start border-1 border-primary ps-3">Path Dịch vụ</span> </label>
                        </div>

                        <!-- Upload ảnh -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-image text-primary"></i> Ảnh dịch vụ
                            </label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <label class="form-label fw-semibold mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-eye me-1"></i> Trạng thái
                            </label>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="status"
                                    value="{{ old('status', 1) }}">
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fa-solid fa-plus-circle"></i> Thêm dịch vụ
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách dịch vụ -->
        <div class="col-md-7">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient bg-success text-white fw-bold">
                    <i class="fa-solid fa-list-check"></i> Danh sách Social
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="listSocial" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Path</th>
                                    <th>Ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
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
        #listSocial th,
        #listSocial td {
            text-align: center;
            vertical-align: middle;
        }

        #listSocial {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script>
        const tableSocial = createTable('#listSocial', '{{ route('admin.service.social.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            { data: 'name' },
            { data: 'slug' },
            {
                data: 'image',
                render: data => `<img src="${data}" alt="Ảnh" class="rounded shadow-sm" width="60">`
            },
            {
                data: 'status',
                render: data => {
                    let cls = data ? 'success' : 'secondary';
                    let text = data ? 'Hiển thị' : 'Ẩn';
                    return `<span class="badge rounded-pill bg-${cls} px-3 py-2">${text}</span>`;
                }
            },
            {
                data: null,
                searchable: false,
                orderable: false,
                render: row => `<div class="btn-group btn-group-sm" role="group"><button class="btn btn-outline-primary btn-edit" data-id="${row.id}" title="Sửa"><i class="fa fa-pencil-square"></i></button><button class="btn btn-outline-danger btn-delete" data-id="${row.id}" title="Xóa"><i class="fa fa-trash"></i></button></div>`
            }
        ]);

        // Sự kiện Edit
        $(document).on('click', '.btn-edit', function () {
            const id = $(this).data('id');
            const url = `{{ route('admin.service.social.edit', 'id') }}`.replace('id', id);
            window.location.href = url;
        });

        // Sự kiện Delete
        $(document).on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            swal({
                title: 'Xác nhận xoá?',
                text: 'Thao tác này sẽ xóa vĩnh viễn dịch vụ MXH.',
                icon: 'warning',
                showCancel: true,
                confirmText: 'Xóa ngay',
                cancelText: 'Hủy bỏ',
                onConfirm: () => {
                    ajaxRequest(
                        `{{ route('admin.service.social.destroy', 'id') }}`.replace('id', id),
                        'DELETE',
                        {},
                        (res) => {
                            if (res.status === 'success') {
                                swal({ title: 'Đã xóa!', text: 'Xóa thành công.', icon: 'success' });
                                tableSocial.ajax.reload();
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