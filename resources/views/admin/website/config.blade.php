@extends('admin.layout.App')
@section('title', 'Cấu hình website')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3"><i class="fa-solid fa-gear me-2 text-primary"></i> Cấu hình chung</h4>

                    <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="accordion" id="configAccordion">
                            {{-- ================= Thông tin SEO ================= --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="seoHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#seoCollapse">
                                        <i class="fa-solid fa-magnifying-glass me-2 text-primary"></i> SEO
                                    </button>
                                </h2>
                                <div id="seoCollapse" class="accordion-collapse collapse show"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-globe text-success me-1"></i> Tên site
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="name" value="{{ old('name', $siteSettings['name']) }}"
                                                placeholder="Nhập tên site...">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-heading text-primary me-1"></i> Tiêu đề
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="title" value="{{ old('title', $siteSettings['title']) }}"
                                                placeholder="Nhập tiêu đề...">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-align-left text-purple me-1"></i> Mô tả
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="description"
                                                value="{{ old('description', $siteSettings['description']) }}"
                                                placeholder="Nhập mô tả...">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-key text-warning me-1"></i> Từ khóa
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="keywords" value="{{ old('keywords', $siteSettings['keywords']) }}"
                                                placeholder="Nhập từ khóa SEO...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= Thông tin Admin ================= --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="adminHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#adminCollapse">
                                        <i class="fa-solid fa-user-shield me-2 text-success"></i> Thông tin Admin
                                    </button>
                                </h2>
                                <div id="adminCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body row g-3">

                                        <!-- Tên Admin -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-user text-primary me-1"></i> Tên Admin
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="admin_name"
                                                value="{{ old('admin_name', $siteSettings['admin_name']) }}"
                                                placeholder="Nhập tên admin...">
                                        </div>

                                        <!-- Facebook -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-brands fa-facebook text-primary me-1"></i> Facebook Admin
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="facebook" value="{{ old('facebook', $siteSettings['facebook']) }}"
                                                placeholder="Link Facebook...">
                                        </div>

                                        <!-- Zalo -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-comment-dots text-info me-1"></i> Zalo Admin
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="zalo" value="{{ old('zalo', $siteSettings['zalo']) }}"
                                                placeholder="Số Zalo...">
                                        </div>

                                        <!-- Telegram -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-brands fa-telegram text-primary me-1"></i> Telegram Admin
                                            </label>
                                            <input type="text" class="form-control rounded-3 shadow-sm border border-info"
                                                name="telegram" value="{{ old('telegram', $siteSettings['telegram']) }}"
                                                placeholder="@username Telegram...">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="themeHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#themeCollapse">
                                        <i class="fa-solid fa-palette me-2 text-warning"></i> Cấu hình giao diện
                                    </button>
                                </h2>
                                <div id="themeCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <!-- Logo Website -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa-solid fa-image text-primary me-1"></i> Logo Website
                                                </label>
                                                <input type="file"
                                                    class="form-control rounded-3 shadow-sm border border-info" name="logo"
                                                    accept="image/*">
                                                <div class="mt-2">
                                                    <img id="logoPreview" src="{{ $siteSettings['logo'] }}"
                                                        alt="Logo Preview" class="img-thumbnail" style="max-height: 80px;">
                                                </div>
                                            </div>

                                            <!-- Favicon -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa-solid fa-star text-warning me-1"></i> Favicon
                                                </label>
                                                <input type="file"
                                                    class="form-control rounded-3 shadow-sm border border-info"
                                                    name="favicon" accept="image/*">
                                                <div class="mt-2">
                                                    <img id="faviconPreview" src="{{ $siteSettings['favicon'] }}"
                                                        alt="Favicon Preview" class="img-thumbnail"
                                                        style="max-height: 40px;">
                                                </div>
                                            </div>

                                            <!-- Image SEO -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa-solid fa-magnifying-glass text-danger me-1"></i> Ảnh SEO
                                                </label>
                                                <input type="file"
                                                    class="form-control rounded-3 shadow-sm border border-info"
                                                    name="image_seo" accept="image/*">
                                                <div class="mt-2">
                                                    <img id="seoPreview" src="{{ $siteSettings['image_seo'] }}"
                                                        alt="SEO Preview" class="img-thumbnail" style="max-height: 80px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- ================= Cấu hình bảo mật ================= --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="securityHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#securityCollapse">
                                        <i class="fa-solid fa-shield-halved me-2 text-danger"></i> Cấu hình bảo mật
                                    </button>
                                </h2>
                                <div id="securityCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body row g-3">

                                        <!-- Số lần đăng nhập sai tối đa (User) -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-user-lock text-primary me-1"></i>
                                                Số lần đăng nhập sai tối đa (User)
                                            </label>
                                            <input type="number" min="1"
                                                class="form-control rounded-3 shadow-sm border border-info"
                                                name="max_user_login_attempts"
                                                value="{{ old('max_user_login_attempts', $siteSettings['max_user_login_attempts']) }}"
                                                placeholder="Nhập số lần tối đa (mặc định 5)">
                                            <small class="text-muted">Nếu người dùng đăng nhập sai quá số lần này, tài khoản
                                                sẽ bị khoá tạm thời.</small>
                                        </div>

                                        <!-- Số lần đăng nhập sai tối đa (IP) -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-network-wired text-danger me-1"></i>
                                                Số lần sai tối đa (IP)
                                            </label>
                                            <input type="number" min="1"
                                                class="form-control rounded-3 shadow-sm border border-info"
                                                name="max_ip_attempts"
                                                value="{{ old('max_ip_attempts', $siteSettings['max_ip_attempts']) }}"
                                                placeholder="Nhập số lần tối đa (mặc định 10)">
                                            <small class="text-muted">Nếu IP sai quá số lần này, IP sẽ bị tạm thời
                                                chặn truy cập.</small>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-user-xmark text-danger me-1"></i>
                                                Số lần làm sai tối đa (User)
                                            </label>
                                            <input type="number" min="1"
                                                class="form-control rounded-3 shadow-sm border border-info"
                                                name="max_user_error_attempts"
                                                value="{{ old('max_user_error_attempts', $siteSettings['max_user_error_attempts']) }}"
                                                placeholder="Nhập số lần tối đa (mặc định 10)">
                                            <small class="text-muted">Nếu User Làm sai quá số lần này, User sẽ bị tạm thời
                                                chặn truy cập.</small>
                                        </div>

                                        <!-- Các switch -->
                                        <div class="col-md-3 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input shadow-sm" type="checkbox" name="order_allow"
                                                    value="1" id="order_allow" {{ old('order_allow', $siteSettings['order_allow']) ? 'checked' : '' }}>
                                                <label for="order_allow" class="form-check-label fw-semibold ms-2">
                                                    <i class="fa-brands fa-telegram text-info me-1"></i> Cho phép đặt đơn
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input shadow-sm" type="checkbox"
                                                    name="google_recaptcha" value="1" id="google_recaptcha" {{ old('google_recaptcha', $siteSettings['google_recaptcha']) ? 'checked' : '' }}>
                                                <label for="google_recaptcha" class="form-check-label fw-semibold ms-2">
                                                    <i class="fa-solid fa-shield-halved text-warning me-1"></i> Google
                                                    Recaptcha
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input shadow-sm" type="checkbox" name="status"
                                                    value="1" id="status" {{ old('status', $siteSettings['status']) ? 'checked' : '' }}>
                                                <label for="status" class="form-check-label fw-semibold ms-2">
                                                    <i class="fa-solid fa-globe text-success me-1"></i> Trạng thái Website
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input shadow-sm" type="checkbox"
                                                    name="cloudflare_mode" value="1" id="cloudflare_mode" {{ old('cloudflare_mode', $siteSettings['cloudflare_mode']) ? 'checked' : '' }}>
                                                <label for="cloudflare_mode" class="form-check-label fw-semibold ms-2">
                                                    <i class="fa-solid fa-cloud text-primary me-1"></i> Sử dụng Cloudflare
                                                    Proxy
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- ================= Cấu hình cấp độ người dùng ================= --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="userLevelHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#userLevelCollapse">
                                        <i class="fa-solid fa-users-gear me-2 text-primary"></i> Cấp độ người dùng
                                    </button>
                                </h2>
                                <div id="userLevelCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            @foreach([2, 3, 4] as $level)
                                                <div class="col-md-6">
                                                    <div class="card border border-info-subtle shadow-sm rounded-4">
                                                        <div class="card-body">
                                                            <h6 class="fw-bold text-primary mb-3">
                                                                <i class="fa-solid fa-user-shield me-1"></i>
                                                                Cấp độ {{ $level }}
                                                            </h6>
                                                            <div class="mb-3">
                                                                <label class="form-label">Số tiền nạp yêu cầu (VNĐ)</label>
                                                                <input type="number" min="0" step="1000" class="form-control"
                                                                    name="user_levels_money_{{ $level }}"
                                                                    value="{{ $userLevels[$level]['money']}}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Giảm giá (%)</label>
                                                                <input type="number" min="0" class="form-control"
                                                                    name="user_levels_discount_{{ $level }}"
                                                                    value="{{ $userLevels[$level]['discount']}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= Script & Cấu hình khác ================= --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="scriptHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#scriptCollapse">
                                        <i class="fa-solid fa-code me-2 text-secondary"></i> Script
                                    </button>
                                </h2>
                                <div id="scriptCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body row g-3">

                                        <!-- Script Header -->
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-code text-primary me-1"></i> Script Header
                                            </label>
                                            <div class="card shadow-sm border border-info rounded-3">
                                                <div class="card-body p-2">
                                                    <textarea class="form-control border-0 shadow-none"
                                                        style="min-height:150px; font-family:monospace; font-size:14px; background:#f8f9fa;"
                                                        name="script_header">{{ old('script_header', $siteSettings['script_header']) }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Script Footer -->
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-code text-success me-1"></i> Script Footer
                                            </label>
                                            <div class="card shadow-sm border border-success rounded-3">
                                                <div class="card-body p-2">
                                                    <textarea class="form-control border-0 shadow-none"
                                                        style="min-height:150px; font-family:monospace; font-size:14px; background:#f8f9fa;"
                                                        name="script_footer">{{ old('script_footer', $siteSettings['script_footer']) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- ================= Nội dung & Điều khoản ================= --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="termsHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#termsCollapse">
                                        <i class="fa-solid fa-file-contract me-2 text-info"></i> Nội dung & Điều khoản
                                    </button>
                                </h2>
                                <div id="termsCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#configAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-lines text-primary me-1"></i> Điều khoản & Chính
                                                sách
                                            </label>
                                            <textarea name="terms" class="form-control tinymce"
                                                rows="10">{{ old('terms', $siteSettings['terms'] ?? '') }}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-scroll text-warning me-1"></i> Chính sách sử dụng
                                            </label>
                                            <textarea name="policy" class="form-control tinymce"
                                                rows="10">{{ old('policy', $siteSettings['policy'] ?? '') }}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fa-solid fa-book-open text-success me-1"></i> Hướng dẫn sử dụng
                                            </label>
                                            <textarea name="guide" class="form-control tinymce"
                                                rows="10">{{ old('guide', $siteSettings['guide'] ?? '') }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> {{-- End accordion --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary col-12">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu cấu hình
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
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