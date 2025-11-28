</div>
</div>
<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="row">
            <div class="col my-1">
                <p class="m-0"> Copyright <a href="#" class="text-primary">{{ $siteSettings['name'] }}</a> © 2025
                    All right reserved</p>
            </div>
            <div class="col-auto my-1">
                <ul class="list-inline footer-link mb-0">
                    <li class="list-inline-item"><a href="{{ route("web.policy") }}">Policy</a></li>
                    <li class="list-inline-item"><a href="{{ route("web.guide") }}">Guide</a></li>
                    <li class="list-inline-item"><a href="{{ route("web.terms") }}">Terms</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div class="pct-c-btn">
    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
        <svg class="pc-icon">
            <use xlink:href="#custom-setting-2"></use>
        </svg>
    </a>
</div>
<div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Settings</h5>
        <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="Close"><i
                class="ti ti-x"></i></button>
    </div>
    <div class="pct-body" style="height: calc(100% - 85px)">
        <div class="offcanvas-body py-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="pc-dark">
                        <h6 class="mb-1">Theme Mode</h6>
                        <p class="text-muted text-sm">Choose light or dark mode or Auto</p>
                        <div class="row theme-layout">
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="light" onclick="layout_change('light');">
                                        <svg class="pc-icon text-warning">
                                            <use xlink:href="#custom-sun-1"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="dark" onclick="layout_change('dark');">
                                        <svg class="pc-icon">
                                            <use xlink:href="#custom-moon"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="default"
                                        onclick="layout_change_default();">
                                        <svg class="pc-icon">
                                            <use xlink:href="#custom-setting-2"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Theme Contrast</h6>
                    <p class="text-muted text-sm">Choose theme contrast</p>
                    <div class="row theme-contrast">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn" data-value="true"
                                    onclick="layout_sidebar_change('true');">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-mask"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn" data-value="false"
                                    onclick="layout_sidebar_change('false');">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-mask-1-outline"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Custom Theme</h6>
                    <p class="text-muted text-sm">Choose your Primary color</p>
                    <div class="theme-color preset-color">
                        <a href="#!" data-value="preset-1"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-2"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-3"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-4"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-5"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-6"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-7"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-8"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-9"><i class="ti ti-check"></i></a>
                        <a href="#!" data-value="preset-10"><i class="ti ti-check"></i></a>
                    </div>
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Sidebar Caption</h6>
                    <p class="text-muted text-sm">Sidebar Caption Hide/Show</p>
                    <div class="row theme-nav-caption">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn" data-value="true"
                                    onclick="layout_caption_change('true');">
                                    <img src="/assets/images/client/settings/img-caption-1.svg" alt="img"
                                        class="img-fluid" width="70%" />
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn" data-value="false"
                                    onclick="layout_caption_change('false');">
                                    <img src="/assets/images/client/settings/img-caption-2.svg" alt="img"
                                        class="img-fluid" width="70%" />
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="pc-rtl">
                        <h6 class="mb-1">Theme Layout</h6>
                        <p class="text-muted text-sm">LTR/RTL</p>
                        <div class="row theme-direction">
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn active" data-value="false"
                                        onclick="layout_rtl_change('false');">
                                        <img src="/assets/images/client/settings/img-layout-1.svg" alt="img"
                                            class="img-fluid" width="70%" />
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="true"
                                        onclick="layout_rtl_change('true');">
                                        <img src="/assets/images/client/settings/img-layout-2.svg" alt="img"
                                            class="img-fluid" width="70%" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="pc-container-width">
                        <h6 class="mb-1">Layout Width</h6>
                        <p class="text-muted text-sm">Choose Full or Container Layout</p>
                        <div class="row theme-container">
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="false"
                                        onclick="change_box_container('false')">
                                        <img src="/assets/images/client/settings/img-container-1.svg" alt="img"
                                            class="img-fluid" width="70%" />
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn" data-value="true"
                                        onclick="change_box_container('true')">
                                        <img src="/assets/images/client/settings/img-container-2.svg" alt="img"
                                            class="img-fluid" width="70%" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-grid">
                        <button class="btn btn-light-danger" id="layoutreset">Reset Layout</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="support-pill">
    <a href="#" class="support-pill-btn bg-primary" id="supportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
        title="Liên hệ & Hỗ trợ">
        <i class="fas fa-comments fa-lg text-white me-3"></i>
        <span class="text-white fw-semibold">Hỗ Trợ</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" aria-labelledby="supportDropdown"
        style="min-width: 220px;">
        <li class="dropdown-header fw-semibold text-center text-primary">
            <i class="fas fa-headset me-2"></i>Liên hệ & Hỗ trợ
        </li>
        <li>
            <hr class="dropdown-divider my-2">
        </li>

        @if ($siteSettings['facebook'] != '')
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ $siteSettings['facebook'] }}" target="_blank">
                    <i class="fab fa-facebook text-primary me-2"></i> Facebook
                </a>
            </li>
        @endif

        @if ($siteSettings['telegram'] != '')
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ $siteSettings['telegram'] }}" target="_blank">
                    <i class="fab fa-telegram-plane text-info me-2"></i> Telegram
                </a>
            </li>
        @endif

        @if ($siteSettings['zalo'] != '')
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ $siteSettings['zalo'] }}" target="_blank">
                    <img src="/assets/images/client/zalo.png" style="height: 22px" class="me-2" alt=""> Zalo
                </a>
            </li>
        @endif
    </ul>
</div>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/fontawesome-free-7.1.0-web/js/fontawesome.min.js"></script>
<script src="/assets/libs/fontawesome-free-7.1.0-web/js/solid.min.js"></script>
<script src="/assets/libs/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/feather-icon/feather.min.js"></script>
<script src="/assets/libs/toastr/toastr.min.js"></script>
<script src="/assets/js/sweetalert2.js"></script>
<script src="/assets/js/moment.min.js"></script>
<script src="/assets/js/client/fonts/custom-font.js"></script>
<script src="/assets/js/client/pcoded.js"></script>
<script src="/assets/js/app.js"></script>
<!-- <script src="/assets/js/client/popper.min.js"></script> -->
<!-- <script src="/assets2/js/config.js"></script> -->

@yield('script')
{!! $siteSettings['script_footer'] !!}
@auth
    <script>
        $(function () {
            const $input = $("#liveSearchInput");
            const $list = $("#searchResultList");
            let delayTimer = null;
            $input.on("input", function () {
                const query = $(this).val().trim();
                $list.empty();

                if (query === "") {
                    $list.hide();
                    return;
                }
                clearTimeout(delayTimer);
                delayTimer = setTimeout(() => {
                    ajaxRequest("{{ route('tool.search') }}", "POST", { q: query }, function (results) {
                        $list.empty();
                        const realData = results?.data ?? [];
                        if (!Array.isArray(realData) || realData.length === 0) {
                            $list.append(`<li class="list-group-item text-muted small">Không tìm thấy kết quả</li>`);
                            $list.show();
                            return;
                        }
                        realData.forEach(item => {
                            const name = item.name ?? "Không tên";
                            const url = item.url ?? "#";
                            const img = item.img ?? "#";
                            $list.append(`<li role="button" class="list-group-item list-group-item-action search-item d-flex align-items-center" data-url="${url}"><img class="rounded me-2" src="${img}" style="width:20px; height:20px;"> ${name}</li>`);
                        });
                        $list.show();
                    });
                }, 300);
            });
            $(document).on("click", ".search-item", function () {
                const url = $(this).data("url");
                if (url && url !== "#") {
                    window.location.href = url;
                } else {
                    toastr.warning("Không có đường dẫn hợp lệ!");
                }
            });
            $(document).on("click", function (e) {
                if (!$(e.target).closest(".drp-search").length) {
                    $list.hide();
                }
            });
            $("form").on("submit", function (e) {
                swal({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    showLoading: true,
                });
            });
        });
    </script>
    <script>
        setInterval(() => {
            ajaxRequest(
                "{{ route('tool.user.poll') }}",
                "GET",
                {},
                function (res) {
                    if (!res) return;
                    if (res.status === "success") {
                        swal({
                            title: "Thông Báo",
                            text: res.message || "Có cập nhật mới!",
                            icon: "success",
                            confirmText: "OK",
                        });
                    } else if (res.status === "error") {
                        swal({
                            title: "Thông Báo",
                            text: res.message,
                            icon: "error",
                        }).then(() => {
                            window.location.href = res.redirect;
                        });
                    }
                }
            );
        }, 10000);
    </script>
@endauth
@if ($errors->any())
    <script>
        @foreach ($errors->all() as $error)
            swal({
                text: "{{ $error }}",
                icon: "error"
            });
        @endforeach
    </script>
@endif
@if (session('error'))
    <script>
        swal({
            text: "{{ session('error') }}",
            icon: "error"
        });
    </script>
@elseif (session('success'))
    <script>
        swal({
            text: "{{ session('success') }}",
            icon: "success"
        });
    </script>
@endif
<script>
    $(document).ready(function () {
        let theme = localStorage.getItem("data-pc-theme") ?? "light";
        if (theme) {
            $(".theme-layout .preset-btn").removeClass("active");
            $('.theme-layout .preset-btn[data-value="' + theme + '"]').addClass("active");
        }
        let themeConstrast = localStorage.getItem("data-pc-theme_contrast") ?? true;
        $(".theme-contrast .preset-btn").removeClass("active");
        $('.theme-contrast .preset-btn[data-value="' + (themeConstrast || "false") + '"]').addClass("active");

        let preset = localStorage.getItem("preset_change") ?? "preset-6";
        $(".preset-color a").removeClass("active");
        $('.preset-color a[data-value="' + preset + '"]').addClass("active");

        let sidebarCaption = localStorage.getItem("data-pc-sidebar-caption") ?? true;
        $(".theme-nav-caption button").removeClass("active");
        $('.theme-nav-caption button[data-value="' + sidebarCaption + '"]').addClass("active");

        let boxContainer = localStorage.getItem("data-pc-box-container") ?? false;
        $(".theme-container button").removeClass("active");
        $('.theme-container button[data-value="' + boxContainer + '"]').addClass("active");

    });
</script>
</body>

</html>