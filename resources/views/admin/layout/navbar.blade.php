<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo position-relative text-center py-2">
            <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img d-inline-block">
                <img src="{{ $siteSettings['logo'] }}" class="dark-logo" width="200" alt="" />
                <img src="{{ $siteSettings['logo'] }}" class="light-logo" width="200" alt="" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer position-absolute end-0 top-50 translate-middle-y me-2"
                id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <!-- ============================= -->
                <!-- Home -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Trang quản trị</span>
                </li>
                <!-- =================== -->
                <!-- Dashboard -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/business-report.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Trang thống kê</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.config.manage') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/process.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Cấu hình website</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.ip.manage') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/ip.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">IP Block</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.notice.notification') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/notifications.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Quản lí thông báo</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.notice.activity') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/lifestyle.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Quản lí hoạt động</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.blogs.manage') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/diversity.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Quản lí blogs</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.supplier.manage') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/development.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Quản lí Api</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.recharge.manage') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/admin/sidebar/bank.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Cấu Hình Nạp Tiền</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.user.list') }}">
                        <span class="d-flex">
                            <img src="/assets/images/admin/sidebar/contact-list.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Quản lý thành viên</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <img src="/assets/images/admin/sidebar/server.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Cấu hình dịch vụ</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.service.social.manage') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Social</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.service.services.manage') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Dịch Vụ</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.service.server.manage') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Máy Chủ</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <img src="/assets/images/admin/sidebar/clock.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Lịch sử dữ liệu</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.user.logs') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Lịch sử người dùng</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.service.orders.manage') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Lịch sử đặt đơn</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.recharge.logs') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Lịch sử nạp tiền</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-secondary col-12">
                    <i class="ti ti-eye"></i>
                    Về trang chủ
                </a>
            </div>
        </nav>
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
<!--  Main wrapper -->
<div class="body-wrapper">
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse"
                        href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class="ti ti-search"></i>
                    </a>
                </li>
            </ul>
            <div class="d-block d-lg-none">
                <img src="{{ $siteSettings['logo'] }}" class="dark-logo" width="230" alt="" />
                <img src="{{ $siteSettings['logo'] }}" class="light-logo" width="230" alt="" />
            </div>
            <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="p-2">
                    <i class="ti ti-dots fs-7"></i>
                </span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between">
                </div>
            </div>
        </nav>
    </header>
    <!--  Header End -->
    <div class="container-fluid">
        <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">@yield('title')</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted" href="#">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('content')