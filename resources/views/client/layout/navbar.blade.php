<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header text-center">
            <a href="#" class="b-brand text-primary d-inline-block">
                <img src="{{ $siteSettings['logo'] }}" width="200" />
                <span class="badge bg-light-success rounded-pill ms-2 theme-version"></span>
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ Auth::user()->avatar_url ?? '/assets/images/client/profile/user-1.jpg' }}"
                                alt="user-image" class="user-avtar wid-45 rounded-circle" />
                            @if (Auth::check() && Auth::user()->level)
                                <span class="badge bg-danger mt-1"
                                    style="font-size: 0.65em; padding: 0.25em 0.5em; display: block; margin: 0 auto;">
                                    {{ json_decode($siteSettings['user_levels'], true)[(string) Auth::user()->level]['discount'] }}%
                                </span>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <span class="mb-0 fw-bold d-block">{{ Auth::user()->full_name ?? 'Khách' }}</span>
                            <small class="text-danger"><b>{{ formatMoney(Auth::user()->balance ?? 0) }}</b></small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        @if(Auth::check())
                            <div class="pt-3">
                                <a href="{{ route('user.profile') }}">
                                    <svg class="pc-icon mx-1">
                                        <use xlink:href="#custom-user-bold"></use>
                                    </svg>
                                    <span>Tài khoản cá nhân</span>
                                </a>
                                <a href="{{ route('recharge.bank') }}">
                                    <svg class="pc-icon mx-1">
                                        <use xlink:href="#custom-dollar-square"></use>
                                    </svg>
                                    <span>Nạp tiền tài khoản</span>
                                </a>
                                @if($siteSettings['facebook'] != '')
                                    <a href="{{ $siteSettings['facebook'] }}" target="_bank">
                                        <svg class="pc-icon mx-1">
                                            <use xlink:href="#custom-facebook"></use>
                                        </svg>
                                        <span>Hỗ trợ Facebook</span>
                                    </a>
                                @endif
                                <a href="{{ route('logout') }}">
                                    <svg class="pc-icon mx-1">
                                        <use xlink:href="#custom-logout"></use>
                                    </svg>
                                    <span>Đăng xuất</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <ul class="pc-navbar">
                @if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'ctv'))
                    <li class="pc-item">
                        <a href="{{ route('admin.dashboard') }}" target="_blank" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-setting-outline"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Trang quản trị</span>
                        </a>
                    </li>
                @endif
                <li class="pc-item pc-caption">
                    <label>HỆ THỐNG</label>
                </li>
                @if(Auth::check())
                    <li class="pc-item">
                        <a href="{{ route('home') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-home"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Trang chủ</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('user.profile') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-user"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Tài khoản của tôi</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('recharge.bank') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-dollar-square"></use>
                                </svg> </span>
                            <span class="pc-mtext">Nạp tiền tài khoản</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('user.logs') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-folder-open"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Nhật kí hoạt động</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('service.orders') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-shopping-bag"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Lịch sử đặt đơn</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('user.level') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-level"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Cấp bậc tài khoản</span>
                        </a>
                    </li>
                @else
                    <li class="pc-item">
                        <a href="{{ route('login') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon text-success">
                                    <use xlink:href="#custom-login"></use>
                                </svg> </span>
                            <span class="pc-mtext">Đăng nhập</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('register') }}" class="pc-link">
                            <span class="pc-micon text-danger">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-logout"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Đăng ký</span>
                        </a>
                    </li>
                @endif
                <li class="pc-item pc-caption">
                    <label>BẢNG GIÁ & DỊCH VỤ</label>
                </li>
                @if(Auth::check())
                    <li class="pc-item">
                        <a href="{{ route('service.price') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-wallet-2"></use>
                                </svg> </span>
                            <span class="pc-mtext">Bảng giá máy chủ</span></a>
                    </li>
                @endif
                @foreach ($navbarSocials as $social)
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon mb-1">
                                <img src="{{ $social->image }}" width="25" alt="">
                            </span>
                            <span class="pc-mtext">{{ $social->name }}</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            @foreach ($social->services as $service)
                                <li class="pc-item">
                                    <a class="pc-link" @if(Auth::check())
                                    href="{{ route('service.view', [$social->slug, $service->slug]) }}" @else
                                            href="{{ route('guest.service.view', [$social->slug, $service->slug]) }}"
                                        @endif>{{ $service->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                <li class="pc-item pc-caption">
                    <label>Website</label>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-message-2"></use>
                            </svg> </span>
                        <span class="pc-mtext">Liên hệ & hỗ trợ</span><span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        @if ($siteSettings['facebook'] != '')
                            <li class="pc-item"><a class="pc-link" href="{{ $siteSettings['facebook'] }}"
                                    target="_blank">Facebook</a></li>
                        @endif
                        @if ($siteSettings['telegram'] != '')
                            <li class="pc-item"><a class="pc-link" href="{{ $siteSettings['telegram'] }}"
                                    target="_blank">Telegram</a></li>
                        @endif
                        @if ($siteSettings['zalo'] != '')
                            <li class="pc-item"><a class="pc-link" href="{{ $siteSettings['zalo'] }}"
                                    target="_blank">Zalo</a></li>
                        @endif
                    </ul>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-document-filter"></use>
                            </svg> </span>
                        <span class="pc-mtext">Điều khoản & Dịch vụ</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>

                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('web.terms') }}">
                                Điều khoản
                            </a>
                        </li>

                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('web.policy') }}">
                                Chính sách
                            </a>
                        </li>

                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('web.guide') }}">
                                Hướng dẫn
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
<header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-menu"></use>
                        </svg>
                        <!-- <i data-feather="menu"></i> -->

                        <!-- <i class="ti ti-menu-2"></i> -->
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-menu"></use>
                        </svg>
                    </a>
                </li>
                <li class="dropdown pc-h-item position-relative">
                    <a class="pc-head-link dropdown-toggle arrow-none m-0 trig-drp-search" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-search-normal"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu pc-h-dropdown drp-search p-3" style="min-width: 300px;">
                        <div class="position-relative">
                            <input type="search" id="liveSearchInput" class="form-control border shadow-none"
                                placeholder="Tìm kiếm..." autocomplete="off">
                            <ul id="searchResultList" class="list-group position-absolute w-100 mt-1 shadow-sm"
                                style="z-index: 1000; display: none; max-height: 500px; overflow-y: auto;">
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button">
                        <i data-feather="globe"></i>
                    </a>
                    <div id="google_translate_element" style="display: none;"></div>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="#!" class="dropdown-item" onclick="changeLanguage('vi')">
                            <img src="https://img.icons8.com/?size=24&id=2egPD0I7yi4-&format=png&color=000000"
                                class="me-2" alt="Tiếng Việt">
                            <span>Tiếng Việt</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="changeLanguage('en')">
                            <img src="https://img.icons8.com/?size=24&id=aRiu1GGi6Aoe&format=png&color=000000"
                                class="me-2" alt="English">
                            <span>Tiếng Anh</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="changeLanguage('zh-CN')">
                            <img src="https://img.icons8.com/?size=24&id=Ej50Oe3crXwF&format=png&color=000000"
                                class="me-2" alt="China">
                            <span>Tiếng Trung</span>
                        </a>
                    </div>
                </li>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-sun-1"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-moon"></use>
                            </svg>
                            <span>Giao diện tối</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sun-1"></use>
                            </svg>
                            <span>Giao diện sáng</span>
                        </a>

                    </div>
                </li>

                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-notification"></use>
                        </svg>
                        <span class="badge bg-success pc-h-badge">{{ $activities->count() }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Thông báo mới nhất</h5>
                        </div>
                        <div class="dropdown-body text-wrap header-notification-scroll position-relative"
                            style="max-height: 50vh">
                            @foreach ($activities as $item)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <svg class="pc-icon text-primary">
                                                    <use xlink:href="#custom-layer"></use>
                                                </svg>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <span
                                                    class="float-end text-sm text-muted">{{ timeAgo($item->created_at) }}</span>
                                                <h5 class="text-body mb-2">{{ $item->username }}</h5>
                                                <p class="mb-0">{{ $item->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </li>
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        {{-- Bắt đầu: Đóng gói Avatar trong một div để định vị tuyệt đối --}}
                        <img src="{{ Auth::user()->avatar_url ?? '/assets/images/client/profile/user-1.jpg' }}"
                            alt="user-image" class="user-avtar" />


                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Profile</h5>
                        </div>
                        <div class="dropdown-body">
                            <div class="profile-notification-scroll position-relative"
                                style="max-height: calc(100vh - 225px);">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ Auth::user()->avatar_url ?? '/assets/images/client/profile/user-1.jpg' }}"
                                            alt="user-image" class="user-avtar wid-35" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ Auth::user()->username ?? 'Khách' }} 🖖</h6>
                                        <span>{{ Auth::user()->email ?? 'guest@subold.com' }}</span>
                                    </div>
                                </div>
                                <hr class="border-secondary border-opacity-50" />

                                <p class="text-span">Thông Tin</p>
                                <a href="{{ route('user.profile') }}" class="dropdown-item">
                                    <span>
                                        <!-- <i class="fa fa-user"></i> -->
                                        <svg class="pc-icon">
                                            <use xlink:href="#custom-user-square"></use>
                                        </svg>
                                        <span>Tài khoản của tôi</span>
                                    </span>
                                </a>
                                <a href="{{ route('recharge.bank') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon">
                                            <use xlink:href="#custom-dollar-square"></use>
                                        </svg>
                                        <span>Nạp tiền tài khoản</span>
                                    </span>
                                </a>
                                <hr class="border-secondary border-opacity-50" />
                                <p class="text-span">Hỗ Trợ</p>
                                <a href="{{ $siteSettings['facebook'] }}" class="dropdown-item" target="_blank">
                                    <span>
                                        <i data-feather="facebook"></i>
                                        <span>Hỗ Trợ Facebook</span>
                                    </span>
                                </a>
                                <a href="{{ $siteSettings['telegram'] }}" class="dropdown-item" target="_blank">
                                    <span>
                                        <i data-feather="send"></i>
                                        <span>Hỗ Trợ Telegram</span>
                                    </span>
                                </a>
                                <hr class="border-secondary border-opacity-50" />
                                <div class="d-grid mb-3">
                                    @if (Auth::user())
                                        <a href="{{ route('logout') }}" class="btn btn-primary">
                                            <svg class="pc-icon me-2">
                                                <use xlink:href="#custom-logout-1-outline"></use>
                                            </svg>Đăng Xuất
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-info">
                                            <svg class="pc-icon me-2" fill="#000000" height="1000px" width="1000px"
                                                version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 297 297"
                                                xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M255.75,0h-148.5c-5.522,0-10,4.478-10,10v112h-39.5c-14.612,0-26.5,11.888-26.5,26.5S43.138,175,57.75,175h39.5v112c0,5.522,4.478,10,10,10h148.5c5.522,0,10-4.478,10-10V10C265.75,4.478,261.272,0,255.75,0z M57.75,155c-3.584,0-6.5-2.916-6.5-6.5s2.916-6.5,6.5-6.5h104.5c3.687,0,7.076-2.03,8.816-5.281c1.74-3.252,1.55-7.197-0.496-10.266L158.936,109h19.963l26.333,39.5L178.898,188h-19.963l11.635-17.453c2.046-3.068,2.236-7.014,0.496-10.266c-1.74-3.251-5.129-5.281-8.816-5.281H57.75z M245.75,277h-128.5V175h26.314l-11.635,17.453c-2.046,3.068-2.236,7.014-0.496,10.266c1.74,3.251,5.129,5.281,8.816,5.281h44c3.344,0,6.466-1.671,8.32-4.453l33-49.5c2.239-3.358,2.239-7.735,0-11.094l-33-49.5c-1.854-2.782-4.977-4.453-8.32-4.453h-44c-3.688,0-7.076,2.03-8.816,5.281c-1.74,3.252-1.55,7.197,0.496,10.266L143.564,122H117.25V20h128.5V277z" />
                                                    </g>
                                                </g>
                                            </svg>Đăng Nhập
                                        </a>
                                    @endif
                                </div>
                                <div class="card border-0 shadow-none drp-upgrade-card mb-0"
                                    style="background-image: url(/assets2/images/layout/img-profile-card.jpg)">
                                    <div class="card-body">
                                        <div class="user-group">
                                            <img src="{{ Auth::user()->avatar_url ?? '/assets/images/client/profile/user-1.jpg' }}"
                                                alt="user-image" class="avtar" />
                                            <img src="/assets2/images/user/avatar-3.jpg" alt="user-image"
                                                class="avtar" />
                                            <img src="/assets2/images/user/avatar-4.jpg" alt="user-image"
                                                class="avtar" />
                                            <img src="/assets2/images/user/avatar-5.jpg" alt="user-image"
                                                class="avtar" />
                                            <span class="avtar bg-light-primary text-primary">+20</span>
                                        </div>
                                        <h3 class="my-3 text-dark">245.3k <small class="text-muted">Followers</small>
                                        </h3>
                                        <div class="btn btn btn-warning">
                                            <a href="{{ route('user.level') }}" class="text-white">
                                                <svg class="pc-icon me-2">
                                                    <use xlink:href="#custom-logout-1-outline"></use>
                                                </svg>
                                                Nâng Cấp Tài Khoản
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<div class="pc-container">
    <div class="pc-content">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a
                                href="{{ Auth::check() ? route('home') : route('guest.home') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">@yield('title')</li>
                    </ul>
                </div>
            </div>
        </div>
        @yield('content')