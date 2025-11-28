@extends('admin.layout.app')
@section('title', 'Quản lí các blog')
@section('content')

    <div class="col-md-12">
        <!-- Form thêm blog -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="bi bi-journal-plus text-primary"></i> Thêm blogs mới
                </h4>
                <form action="{{ route('admin.blogs.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border border-info" name="slug" value="{{ old('slug') }}"
                                    placeholder="vd: gioi-thieu" required>
                                <label>
                                    <i class="ti ti-link text-info me-2"></i>
                                    <span class="border-start border-info ps-3">Slug</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border border-info" name="utm_source" value="{{ old('utm_source') }}"
                                    placeholder="vd: facebook, tiktok, google" required>
                                <label>
                                    <i class="ti ti-target text-danger me-2"></i>
                                    <span class="border-start border-info ps-3"> Nguồn</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-body-text text-info"></i> Nội dung blog
                            </label>
                            <div class="card shadow-sm border border-info rounded-3">
                                <div class="card-body p-2">
                                    <textarea class="form-control border-0 shadow-none"
                                        style="min-height:150px; font-family:monospace; font-size:14px; background:#f8f9fa;"
                                        name="content">{{ old('content') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">
                        <i class="fa-solid fa-plus-circle"></i> Thêm nội dung mới
                    </button>
                </form>
            </div>
        </div>

        <!-- Danh sách blogs -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="bi bi-list-ul text-success"></i> Các blogs
                </h4>
                <div class="table-responsive">
                    <table id="blogsTable" class="table table-hover table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Slug</th>
                                <th>Nguồn</th>
                                <th>Nội dung</th>
                                <th>Preview</th>
                                <th>Thời gian</th>
                                <th style="max-width: 100px">Thao tác</th>
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
        #blogsTable th,
        #blogsTable td {
            text-align: center;
            vertical-align: middle;
        }

        #blogsTable {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
@section('script')
    <script src="/assets/libs/DataTables/datatables.min.js"></script>
    <script>
        const table = createTable('#blogsTable', '{{ route('admin.blogs.manage.data') }}', [
            {
                data: 'id',
                render: function (data) {
                    return `<div class="d-flex align-items-center"><i class="fa fa-hashtag text-primary"></i> <span class="text-dark fw-bold">${data}</span></div>`;
                }
            },
            {
                data: 'slug',
                render: (data, type, row) => {
                    const full = "{{ route('blog', ':name') }}".replace(':name', data);
                    return `<div class="d-flex flex-wrap justify-content-center align-items-center gap-2"><input type="text" class="slug-input form-control form-control-sm text-center" data-id="${row.id}" value="${data}" style="min-width:300px;"><a href="${full}" target="_blank" class="blog-link text-primary small text-truncate">${full}</a></div>`;
                }
            },
            {
                data: 'utm_source',
                render: (data, type, row) =>
                    `<input type="text" class="utm-input form-control form-control-sm text-center" data-id="${row.id}" value="${data ?? ''}" placeholder="utm_source" style="min-width:300px">`
            },
            {
                data: 'content',
                render: (data, type, row) => `<textarea class="content-input form-control border border-info" data-id="${row.id}" rows="8" style="resize:vertical;min-width:300px;min-height:300px;">${data}</textarea>`
            },
            {
                data: 'content',
                render: (data, type, row) => {
                    const id = `preview-${row.id}`;
                    setTimeout(() => {
                        const iframe = document.getElementById(id);
                        if (iframe) {
                            const doc = iframe.contentWindow.document;
                            doc.open(); doc.write(data); doc.close();
                            iframe.style.height = Math.min(350, iframe.contentWindow.document.body.scrollHeight) + "px";
                        }
                    }, 80);
                    return `<iframe id="${id}" class="preview-frame" style="width:100%;min-height:300px;min-width:300px;max-height:300px;border:1px solid #ddd;border-radius:6px;overflow:hidden;background:#fff;"></iframe>`;
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
                render: (data) => `<div class="d-flex justify-content-center align-items-center gap-2"><button class="btn btn-outline-danger btn-sm delete-btn" data-id="${data.id}"><i class="ti ti-trash"></i></button></div>`
            }
        ]);
    </script>
    <script>
        $(document).on('change', '.slug-input, .content-input, .utm-input', function () {
            const id = $(this).data('id');
            const slug = $(`.slug-input[data-id="${id}"]`).val().trim();
            const utm = $(`.utm-input[data-id="${id}"]`).val().trim();
            const content = $(`.content-input[data-id="${id}"]`).val().trim();
            if (!content) return swal({ title: "Lỗi", text: "Nội dung không được để trống", icon: "error" });
            swal({
                title: "Cập nhật blog?",
                text: "Xác nhận lưu thay đổi.",
                icon: "question",
                showCancel: true,
                confirmText: "Lưu",
                onConfirm: () => {
                    swal({
                        title: "Đang Lưu",
                        text: "Vui Lòng Chờ....",
                        showLoading: true,
                    })
                    ajaxRequest(`{{ route('admin.blogs.update', 'id') }}`.replace('id', id), 'PUT', { detail: content, slug: slug, utm_source: utm }, (res) => {
                        swal({
                            title: res.status === 'success' ? "Thành công" : "Lỗi",
                            text: res.message || (res.status === 'success' ? "Đã cập nhật blog." : "Không thể cập nhật."),
                            icon: res.status === 'success' ? "success" : "error"
                        });
                        $('#blogsTable').DataTable().ajax.reload(null, false);
                    });
                }
            });
        });
        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            swal({
                title: "Xóa blog?",
                text: "Thao tác này không thể khôi phục!",
                icon: "warning",
                showCancel: true,
                confirmText: "Xóa",
                onConfirm: () => {
                    swal({
                        title: "Đang Xoá",
                        text: "Vui Lòng Chờ....",
                        showLoading: true,
                    })
                    ajaxRequest(`{{ route('admin.blogs.delete', 'id') }}`.replace('id', id), 'DELETE', {}, (res) => {
                        swal({
                            title: res.status === 'success' ? "Đã xóa!" : "Lỗi!",
                            text: res.message || (res.status === 'success' ? "Blog đã bị xóa." : "Không thể xóa blog."),
                            icon: res.status === 'success' ? "success" : "error"
                        });
                        $('#blogsTable').DataTable().ajax.reload(null, false);
                    });
                }
            });
        });
    </script>
@endsection