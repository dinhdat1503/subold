@extends('admin.layout.App')
@section('title', 'Quản lí thông báo')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card border shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
                    <i class="fa-solid fa-bullhorn me-2"></i>
                    <h5 class="mb-0 fw-bold text-white">Quản lí thông báo</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="noticeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="modal-tab" data-bs-toggle="tab"
                                data-bs-target="#noticeModal" type="button" role="tab">
                                <i class="fa-regular fa-window-maximize me-1"></i> Thông báo Modal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="main-tab" data-bs-toggle="tab" data-bs-target="#noticeMain"
                                type="button" role="tab">
                                <i class="fa-solid fa-bell me-1"></i> Thông báo Hệ thống
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="noticeTabContent">
                        <!-- Tab Thông báo Modal -->
                        <div class="tab-pane fade show active" id="noticeModal" role="tabpanel">
                            <form action="{{ route('admin.notice.notification.update') }}" method="POST" class="mt-3">
                                @method("PUT")
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-pen-to-square text-primary me-1"></i> Nội dung Modal
                                    </label>
                                    <textarea name="notice_modal"
                                        class="form-control border rounded-3 shadow-sm tinymce" 
                                        rows="10">{{ $siteSettings['notice_modal'] }}</textarea>
                                </div>
                                <button class="btn btn-success w-100 rounded-pill py-2 fw-semibold">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thông báo Modal
                                </button>
                            </form>
                        </div>

                        <!-- Tab Thông báo Hệ thống -->
                        <div class="tab-pane fade" id="noticeMain" role="tabpanel">
                            <form action="{{ route('admin.notice.notification.update') }}" method="POST" class="mt-3">
                                @csrf
                                @method("PUT")
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-pen-to-square text-warning me-1"></i> Nội dung Hệ thống
                                    </label>
                                    <textarea name="notice_main"
                                        class="form-control border rounded-3 shadow-sm tinymce"
                                        rows="10">{{ $siteSettings['notice_main'] }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Cập nhật thông báo Hệ thống
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
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
@endsection