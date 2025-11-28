@extends('admin.layout.app')
@section('title', 'Thêm dịch vụ mới')

@section('content')
    <div class="row">
        <!-- Form thêm dịch vụ -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa fa-circle-check text-primary"></i> Thêm dịch vụ mới
                    </h4>
                    <form action="{{ route('admin.service.services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Social -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-share text-info me-1"></i> Dịch vụ Social
                            </label>
                            <div class="col-sm-9">
                                <select name="social_id" class="form-select border border-info">
                                    @foreach ($social as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Icon -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-image text-info me-1"></i> Ảnh dịch vụ
                            </label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control border border-info" name="image" accept="image/*"
                                    id="logoInput">
                            </div>
                        </div>

                        <!-- Tên dịch vụ -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-tag text-info me-1"></i> Tên dịch vụ
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border border-info" name="name"
                                    value="{{ old('name') }}">
                            </div>
                        </div>

                        <!-- Path -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-link text-info me-1"></i> Đường dẫn dịch vụ
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border border-info" name="slug"
                                    value="{{ old('slug') }}">
                                <small class="text-muted">Ví dụ: like-facebook</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-eye text-info me-1"></i> Trạng thái
                            </label>
                            <div class="col-sm-9">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusSwitch" name="status"
                                        value="{{ old('status', 1) }}" checked>
                                    <label class="form-check-label" for="statusSwitch">Hoạt động</label>
                                </div>
                            </div>
                        </div>

                        <!-- Lưu ý -->
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-exclamation-triangle text-warning me-1"></i> Lưu ý khi sử dụng
                            </label>
                            <div class="col-sm-9">
                                <textarea name="note" rows="5" class="form-control border border-info tinymce"
                                    placeholder="Nhập lưu ý khi sử dụng dịch vụ...">{{ old('note') }}</textarea>
                            </div>
                        </div>


                        <button class="btn btn-primary w-100">
                            <i class="fa fa-circle-check"></i> Thêm dịch vụ mới
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách dịch vụ -->
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa fa-list-ul text-success"></i> Danh sách Các dịch Vụ
                    </h4>
                    <div class="table-responsive">
                        <table id="listServices" class="table table-hover table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Social</th>
                                    <th>Path</th>
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
        #listServices th,
        #listServices td {
            text-align: center;
            vertical-align: middle;
        }

        #listServices {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script src="/assets/libs/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            license_key: 'gpl',
            selector: 'textarea.tinymce',
            height: 500,
            menubar: true,
            toolbar_mode: 'wrap',
            toolbar_sticky: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount',
                'nonbreaking', 'pagebreak', 'autosave', 'quickbars', 'emoticons',
            ],

            toolbar: [
                'undo redo | styles blocks | fontfamily fontsize forecolor backcolor | bold italic underline strikethrough | removeformat',
                'align | bullist numlist outdent indent | link image media table charmap emoticons anchor | nonbreaking pagebreak',
                'preview fullscreen | searchreplace | insertdatetime | code help wordcount | visualblocks',
            ],
            font_size_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
            font_family_formats: "Arial=arial,helvetica,sans-serif; Times New Roman='Times New Roman',serif; Tahoma=tahoma,sans-serif; Verdana=verdana,sans-serif;",
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
        });
    </script>
    <script>
        const tableServices = createTable('#listServices', '{{ route('admin.service.services.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            {
                data: 'image',
                render: function (data) {
                    return data
                        ? `<img src="${data}" class="rounded-circle shadow-sm" width="40" height="40" alt="Logo">`
                        : `<i class="fa fa-image text-muted fs-4"></i>`;
                }
            },
            {
                data: 'name',
                render: function (data) {
                    return `<span class="fw-semibold"><i class="fa fa-tag me-1 text-info"></i>${data}</span>`;
                }
            },
            {
                data: 'social_name',
                render: function (data) {
                    return `<span class="badge bg-info px-3 py-2"><i class="fa fa-share me-1"></i>${data}</span>`;
                }
            },
            {
                data: 'slug',
                render: function (data) {
                    return `<code><i class="fa fa-link me-1"></i>${data}</code>`;
                }
            },
            {
                data: 'status',
                render: function (data) {
                    return data ? '<span class="badge bg-success px-3 py-2"><i class="fa fa-check-circle me-1"></i>Hoạt động</span>' : '<span class="badge bg-secondary px-3 py-2"><i class="fa fa-eye-slash me-1"></i>Ẩn</span>';
                }
            },
            {
                data: null,
                searchable: false,
                orderable: false,
                render: row => `<div class="btn-group btn-group-sm" role="group"><button class="btn btn-outline-primary btn-edit" data-id="${row.id}" title="Sửa"><i class="fa fa-pencil-square"></i></button><button class="btn btn-outline-danger btn-delete" data-id="${row.id}" title="Xoá"><i class="fa fa-trash"></i></button></div>`
            }
        ])
    </script>
    <script>
        $(document).on('click', '.btn-edit', function () {
            const id = $(this).data('id');
            const url = `{{ route('admin.service.services.edit', 'id') }}`.replace('id', id);
            window.location.href = url;
        });
        $(document).on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            swal({
                title: 'Xác nhận xoá?',
                text: 'Dịch vụ này sẽ bị xoá vĩnh viễn khỏi hệ thống!',
                icon: 'warning',
                showCancel: true,
                confirmText: 'Xoá ngay',
                cancelText: 'Hủy',
                onConfirm: () => {
                    ajaxRequest(
                        `{{ route('admin.service.services.destroy', 'id') }}`.replace('id', id),
                        'DELETE',
                        {},
                        res => {
                            if (res.status === 'success') {
                                swal({ title: 'Thành công', text: 'Đã xoá dịch vụ!', icon: 'success' });
                                tableServices.ajax.reload();
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