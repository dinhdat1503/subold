@extends('admin.layout.app')
@section('title', 'Chỉnh sửa dịch vụ MXH')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-pencil-square me-2"></i> Cập nhật dịch vụ MXH
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.service.social.update', $social->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Tên dịch vụ -->
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" value="{{ old('name', $social->name) }}" name="name"
                                id="socialName" placeholder="Tên MXH">
                            <label for="socialName">
                                <i class="fa-solid fa-id-badge text-primary"></i> Tên dịch vụ MXH
                            </label>
                        </div>

                        <!-- Slug -->
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" value="{{ old('slug', $social->slug) }}" name="slug"
                                id="socialSlug" placeholder="Path dịch vụ">
                            <label for="socialSlug">
                                <i class="fa-solid fa-link me-1 text-primary"></i> Path dịch vụ (VD: sg-facebook)
                            </label>
                        </div>

                        <!-- Ảnh dịch vụ -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-image text-primary"></i> Ảnh Dịch vụ
                            </label>
                            <input type="file" class="form-control" name="image" id="socialImageInput" accept="image/*">

                            @if($social->image)
                                <div class="mt-3 text-center">
                                    <span class="text-muted small d-block mb-2">Ảnh hiện tại:</span>
                                    <img src="{{ $social->image }}" id="socialImagePreview" alt="Ảnh dịch vụ"
                                        class="img-thumbnail rounded shadow-sm" style="max-height: 160px;">
                                </div>
                            @endif
                        </div>

                        <!-- Trạng thái -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <label class="form-label fw-semibold mb-0">
                                <i class="fa-solid fa-eye me-1 text-primary"></i> Trạng thái
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="socialStatusSwitch" name="status"
                                    value="1" {{ old('status', $social->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="socialStatusSwitch">
                                    {{ old('status', $social->status) ? 'Hiển thị' : 'Ẩn' }}
                                </label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection