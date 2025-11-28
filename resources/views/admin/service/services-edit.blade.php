@extends('admin.layout.app')
@section('title', 'Chỉnh sửa dịch vụ')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa fa-pencil text-primary"></i> Chỉnh sửa dịch vụ
                    </h4>

                    <form action="{{ route('admin.service.services.update', $service->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <!-- Social -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-share text-info me-1"></i> Dịch vụ Social
                            </label>
                            <div class="col-sm-9">
                                <select name="social_id" class="form-select border border-info">
                                    @foreach ($social as $item)
                                        <option value="{{ $item->id }}" @if($service->social_id == $item->id) selected @endif>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Icon Preview -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-image text-info me-1"></i> Ảnh dịch vụ
                            </label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control border border-info" name="image" accept="image/*">
                                <div class="mt-2">
                                    @if($service->image)
                                        <img id="logoPreview" src="{{ $service->image }}" class="rounded shadow-sm" width="80"
                                            alt="Logo Preview">
                                    @else
                                        <img id="logoPreview" class="d-none rounded shadow-sm" width="80" alt="Logo Preview">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tên dịch vụ -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-tag text-info me-1"></i> Tên dịch vụ
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border border-info" name="name"
                                    value="{{ old('name', $service->name) }}">
                            </div>
                        </div>

                        <!-- Path -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-link text-info me-1"></i> Đường dẫn dịch vụ
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border border-info" name="slug"
                                    value="{{ old('slug', $service->slug) }}">
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
                                        value="1" @if(old('status', $service->status)) checked @endif>
                                    <label class="form-check-label" for="statusSwitch">Hoạt động</label>
                                </div>
                            </div>
                        </div>
                        <!-- Note -->
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label fw-semibold">
                                <i class="fa fa-exclamation-triangle text-warning me-1"></i> Lưu ý khi sử dụng
                            </label>
                            <div class="col-sm-9">
                                <textarea name="note" rows="5" class="form-control border border-info tinymce"
                                    placeholder="Nhập lưu ý khi sử dụng dịch vụ...">{!! old('note', $service->note) !!}</textarea>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fa fa-save"></i> Cập nhật dịch vụ
                        </button>
                    </form>
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