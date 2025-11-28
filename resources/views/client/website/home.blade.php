@extends('client.layout.app')

@section('title', 'Trang chủ')

@section('content')
    @if(Auth::check())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 row-cols-xxl-4">
            <div class="col">
                <div class="card radius-10 border-0 border-start border-info border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Số dư</p>
                                <h4 class="mb-0 text-info">{{ formatMoney(Auth::user()->balance) }}</h4>
                            </div>
                            <div class="ms-auto bg-info rounded-circle text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="tabler-icon tabler-icon-currency-dollar">
                                    <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2">
                                    </path>
                                    <path d="M12 3v3m0 12v3"></path>
                                </svg></div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $balanceProgress }}%;"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-0 border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Tổng tiền nạp</p>
                                <h4 class="mb-0 text-success"> {{ formatMoney(Auth::user()->total_recharge) }}
                                </h4>
                            </div>
                            <div class="ms-auto bg-success rounded-circle text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-cards" width="40" height="40" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M3.604 7.197l7.138 -3.109a.96 .96 0 0 1 1.27 .527l4.924 11.902a1 1 0 0 1 -.514 1.304l-7.137 3.109a.96 .96 0 0 1 -1.271 -.527l-4.924 -11.903a1 1 0 0 1 .514 -1.304z">
                                    </path>
                                    <path d="M15 4h1a1 1 0 0 1 1 1v3.5"></path>
                                    <path d="M20 6c.264 .112 .52 .217 .768 .315a1 1 0 0 1 .53 1.311l-2.298 5.374"></path>
                                </svg></div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $totalRechargeProgress }}%;" aria-valuenow="75" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-0 border-start border-danger border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Tổng nạp tháng</p>
                                <h4 class="mb-0 text-danger"> {{ formatMoney($monthlyRecharge) }}</h4>
                            </div>
                            <div class="ms-auto bg-danger rounded-circle text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-activity-heartbeat" width="40" height="40"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M3 12h4.5l1.5 -6l4 12l2 -9l1.5 3h4.5"></path>
                                </svg></div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ $monthlyRechargeProgress }}%;" aria-valuenow="75" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-0 border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <p class="mb-1">Cấp bậc</p>
                                <h4 class="mb-0 ">{{ userLevel(false) }}</h4>
                            </div>
                            <div class="ms-auto bg-warning rounded-circle text-white"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-shield-lock" width="40" height="40" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3">
                                    </path>
                                    <path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                    <path d="M12 12l0 2.5"></path>
                                </svg></div>
                        </div>
                        <div class="progress mt-3" style="height: 4.5px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $levelProgress }}%;"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="mb-0 text-uppercase text-center text-danger">Thông báo chung</h4>
            <div class="my-3 border-top"></div>
            <div class="content-body">
                <p>
                    {!! $siteSettings['notice_main'] !!}
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2 mb-2">
            <div class="card">
                <div class="card-body">
                    @foreach ($socials as $social)
                        <div class="mb-4">
                            <div class="text-center h4 fw-bold mb-2">
                                {{ $social->name }}
                            </div>
                            <hr class="border-success">
                            <div class="row g-3">
                                @foreach ($social->services as $service)
                                    <div class="col-lg-6 col-xxl-4">
                                        <a @if(Auth::check()) href="{{ route('service.view', [$social->slug, $service->slug]) }}"
                                        @else href="{{ route('guest.service.view', [$social->slug, $service->slug]) }}" @endif
                                            class="btn btn-light-primary border w-100 d-flex align-items-center justify-content-center shadow-sm">
                                            <img src="{{ $service->image }}" alt="{{ $service->name }}" class="me-2"
                                                style="width: 24px; height: 24px; object-fit: contain;">
                                            <span class="fw-semibold text-light-dark text-truncate">
                                                {{ $service->name }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-md-4 mt-2 mb-2">
            <div class="col">
                @if(Auth::check())
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Báo Cáo</h4>
                            <div class="row align-items-center">
                                <div class="col-sm-6 card-statistical mb-2">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-primary text-primary"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-database">
                                                        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                                        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3">
                                                        </path>
                                                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1"
                                                data-countup="{&quot;endValue&quot;:0,&quot;suffix&quot;:&quot;\u0111&quot;}"
                                                style="color: #3498db">
                                                {{ $waitRefundOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Chờ huỷ</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical mb-2">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-info text-info" style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-shopping-cart">
                                                        <circle cx="9" cy="21" r="1"></circle>
                                                        <circle cx="20" cy="21" r="1"></circle>
                                                        <path
                                                            d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #7ed6df">{{ $warrantyOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Bảo Hành</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-success text-success"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-refresh-ccw" style="color: #2abfd3">
                                                        <polyline points="1 4 1 10 7 10">
                                                        </polyline>
                                                        <polyline points="23 20 23 14 17 14">
                                                        </polyline>
                                                        <path
                                                            d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #2abfd3">
                                                {{ $pendingOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Đang Chờ</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-danger text-danger"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-activity" style="color: #42c081">
                                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12">
                                                        </polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #42c081">{{ $activeOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Đang chạy</p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-success text-success"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-check">
                                                        <polyline points="20 6 9 17 4 12">
                                                        </polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #44bd32">
                                                {{ $completedOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Chạy xong</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-success text-success"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-triangle" style="color: #e0d20d">
                                                        <path
                                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                                        <line x1="12" y1="9" x2="12" y2="13" />
                                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #e0d20d">{{ $errorOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Đơn Lỗi</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-success text-success"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash-2" style="color: #ff0404">
                                                        <polyline points="3 6 5 6 21 6">
                                                        </polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17">
                                                        </line>
                                                        <line x1="14" y1="11" x2="14" y2="17">
                                                        </line>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #ff0404">
                                                {{ $cancelledOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Huỷ Đơn</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 card-statistical">
                                    <div class="d-flex position-relative">
                                        <div class="col-auto">
                                            <div class="card bg-soft-success text-success"
                                                style="max-width: 100%;max-height: 100%;">
                                                <div class="card-body p-3 text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-check-circle" style="color: #1136d8">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14">
                                                        </path>
                                                        <polyline points="22 4 12 14.01 9 11.01">
                                                        </polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pl-3 p-2">
                                            <h6 class="fs-0 mb-1" data-countup="{&quot;endValue&quot;:0}"
                                                style="color: #1136d8">
                                                {{ $refundedOrders }}
                                            </h6>
                                            <p class="mb-0 fs--1 text-500">Hoàn Tiền</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header card-header-border d-flex justify-content-between">
                            <div class="header-title">
                                <div class=" h4" style="padding: 5px">
                                    <span>Hoạt Động Mới Nhất</span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="position-relative ps-4" data-simplebar style="max-height: 50vh;">
                            @foreach ($activities as $item)
                                <div class="position-relative mb-4 pb-2 me-3">
                                    <div
                                        class="position-absolute start-0 top-0 h-100 border-start border-2 border-dark border-opacity-10">
                                    </div>
                                    <span
                                        class="position-absolute start-0 top-100 translate-middle bg-primary rounded-circle border border-light"
                                        style="width: 12px; height: 12px;"></span>
                                    <div class="card shadow-sm border-0 border-top border-primary border-2 ms-3 hover-shadow">
                                        <div class="card-body py-3 px-4 d-flex flex-column justify-content-between"
                                            style="min-height: 100px;">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fa fa-user text-primary me-2 fs-5"></i>
                                                <h6 class="fw-semibold mb-0">{{ $item->username }}</h6>
                                            </div>
                                            @if (!empty($item->content))
                                                <div class="flex-grow-1 mb-2 text-body small">
                                                    {!! $item->content !!}
                                                </div>
                                            @endif
                                            <div class="text-end mt-auto">
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ timeAgo($item->created_at) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container py-5">
                <div class="row g-4">
                    <!-- Giao dịch gần đây -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="fw-bold border-0 border-bottom border-light-dark border-4 py-2 px-2 mb-3">
                                    <h4><i class="fas fa-wallet me-2"></i>Đơn hàng gần đây</h4>
                                </div>
                                <div style="max-height: 40vh" data-simplebar>
                                    @foreach ($recentOrders as $recentOrder)
                                        <div
                                            class="d-flex justify-content-center align-items-center border border-0 border-bottom border-2">
                                            <div class="d-flex flex-grow-1 gap-1 align-items-center flex-wrap p-2 text-dark">
                                                <div class="d-flex align-items-center">
                                                    <span class="p-1 rounded-circle mx-1"><i
                                                            class="fa-solid fa-user"></i></span>
                                                    <div>
                                                        <span
                                                            class="text-success">{{ Str::substr($recentOrder->user->username, 0, 4) . '*' }}</span>
                                                        <span>đã mua</span>
                                                        <strong
                                                            class="text-primary">{{ Str::substr($recentOrder->server->title ?? "", 0, 30) . "..." }}</strong>
                                                        <span>giá</span>
                                                        <strong
                                                            class="text-danger">{{ formatMoney($recentOrder->payment) }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-success rounded-start-2 p-1 text-light text-nowrap">
                                                <i class="fa-solid fa-clock"></i> {{ timeAgo($recentOrder->created_at) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nạp tiền gần đây -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="fw-bold border-0 border-bottom border-light-dark border-4 py-2 px-2 mb-3">
                                    <h4><i class="fas fa-wallet me-2"></i>Nạp tiền gần đây</h4>
                                </div>
                                <div style="max-height: 40vh" data-simplebar>
                                    @foreach ($recentRecharges as $recentRecharge)
                                        <div
                                            class="d-flex justify-content-center align-items-center border border-0 border-bottom border-2">
                                            <div class="d-flex flex-grow-1 gap-1 align-items-center flex-wrap p-2 text-dark">
                                                <div class="d-flex align-items-center">
                                                    <span class="p-1 rounded-circle mx-1"><i
                                                            class="{{ $recentRecharge->recharge->method_type != 'crypto' ? 'fa-solid fa-university' : 'fa-brands fa-bitcoin' }}"></i>
                                                    </span>
                                                    <div>
                                                        <span
                                                            class="text-success">{{ Str::substr($recentRecharge->user->username, 0, 4) . '*' }}</span>
                                                        <span>đã nạp</span>
                                                        <strong
                                                            class="text-danger">{{ formatMoney($recentRecharge->amount_received) }}</strong>
                                                        <span>qua</span>
                                                        <strong
                                                            class="text-primary">{{ $recentRecharge->recharge->name }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-success rounded-start-2 p-1 text-light text-nowrap">
                                                <i class="fa-solid fa-clock"></i> {{ timeAgo($recentRecharge->created_at) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="noticeModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="modal-header text-white py-3 bg-primary">
                    <h5 class="modal-title d-flex align-items-center gap-2 text-light">
                        <i class="feather icon-info"></i>
                        <span>Thông báo hệ thống</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <div class="text-dark fs-6">
                        {!! $siteSettings['notice_modal'] !!}
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between border-1 px-4 pb-4">
                    <button type="button"
                        class="btn btn-warning text-white rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2"
                        data-bs-dismiss="modal">
                        <i class="feather icon-clock"></i>
                        Tắt 1 Tiếng
                    </button>

                    <button type="button"
                        class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2"
                        data-bs-dismiss="modal">
                        <i class="feather icon-x-circle"></i>
                        Đóng
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.onload = function () {
            var modalLastClosed1 = localStorage.getItem('modalLastClosed');
            var delayTime = 5000 * 1000;
            if (!modalLastClosed1 || Date.now() - modalLastClosed1 > delayTime) {
                $('#noticeModal').modal('show');
            }
        };
        $('#close-hourly').on('click', function () {
            localStorage.setItem('modalLastClosed', Date.now());
        });
    </script>
@endsection