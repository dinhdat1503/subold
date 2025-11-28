@extends('Client.Layout.App')
@section('title', 'Bảng giá dịch vụ')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body py-0">
                    <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                        @foreach ($socials as $social)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab-{{ $social->id }}" data-bs-toggle="tab"
                                    href="#profile-{{ $social->id }}" role="tab" aria-selected="true">
                                    <img src="{{ $social->image }}" width="23"> &ensp;{{ $social->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @foreach ($socials as $social)
                        <div class="tab-content">
                            <div class="tab-pane" id="profile-{{ $social->id }}" role="tabpanel"
                                aria-labelledby="profile-tab-{{ $social->id }}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive overflow-x-auto w-100">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Dịch Vụ</th>
                                                        <th>Máy Chủ</th>
                                                        <th>Thành viên</th>
                                                        <th>Cộng tác viên</th>
                                                        <th>Đại lý</th>
                                                        <th>Nhà phân phối</th>
                                                        <th>Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($social->services as $service)
                                                        @foreach ($service->servers as $server)
                                                            <tr>
                                                                @if ($loop->first)
                                                                    <td class="text-wrap align-middle"
                                                                        rowspan="{{ $service->servers->count() }}">
                                                                        <span class="badge bg-danger me-1"></span>
                                                                        <strong>{{ $service->name }}</strong>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    <span class="badge bg-secondary">{{ $server->server }}</span>
                                                                    <span class="text-muted small d-block">{{ $server->title }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-success">
                                                                        {{ priceServer($server->price, 1) ?? 0 }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-primary">
                                                                        {{ priceServer($server->price, 2) ?? 0 }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-warning">
                                                                        {{ priceServer($server->price, 3) ?? 0 }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-info">
                                                                        {{ priceServer($server->price, 4) ?? 0 }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    {!! statusService($server->status) !!}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if ($service->servers->isEmpty())
                                                            <tr>
                                                                <td class="text-wrap align-middle">
                                                                    <span class="badge bg-danger me-1"></span>
                                                                    <strong>{{ $service->name }}</strong>
                                                                </td>
                                                                <td colspan="6" class="text-center text-muted">
                                                                    Không có máy chủ nào đang hoạt động
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection