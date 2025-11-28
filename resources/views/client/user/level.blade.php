@extends('Client.Layout.App')
@section('title', 'Cấp bậc tài khoản')

@section('content')
    <!-- Thanh tiến trình -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body position-relative">
            <h5 class="fw-bold mb-3">Tiến trình nạp tiền</h5>

            <!-- Progressbar -->
            <div class="progress" style="height: 25px; border-radius: 50px; overflow:hidden;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%;"
                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                    {{ number_format($progress, decimals: 0) }}%
                </div>
            </div>

            <!-- Mốc -->
            <div class="d-flex justify-content-between mt-4">
                @foreach($milestones as $ms)
                    <div class="text-center" style="width: {{ 100 / count($milestones) }}%">
                        <span class="badge {{ $ms['achieved'] ? 'bg-success' : 'bg-secondary' }}">
                            {{ formatMoney($ms['money']) }}
                        </span>
                        <div class="mt-1 small {{ $ms['achieved'] ? 'text-success fw-bold' : 'text-muted' }}">
                            {{ $ms['title'] }}
                            @if($currentLevel == $ms['id']) ⭐ @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between small text-muted mt-3">
                <span>Đã nạp: <strong class="text-primary">{{ formatMoney($total) }}</strong></span>
                <span>Mốc cao nhất: <strong>{{ formatMoney($maxMoney) }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Danh sách cấp bậc -->
    <div class="row mb-3">
        @foreach($levels as $id => $level)
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100 {{ $currentLevel == $id ? 'border border-3 border-warning' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <h3 class="fw-bold text-center text-uppercase mt-2">{{ $level['title'] }}</h3>

                        <div class="my-4 py-2 text-center">
                            <img src="{{ $level['icon'] }}" alt="Icon {{ $level['title'] }}" height="80">
                        </div>

                        <div class="text-center mb-4">
                            <h1 class="fw-bold h1 mb-0 text-primary">
                                {{ formatMoney($level['money']) }}
                            </h1>
                        </div>

                        <ul class="list-unstyled text-center mb-4">
                            <li class="mb-2">
                                Giảm giá <span class="fw-bold text-success">{{ $level['discount'] }}%</span>
                            </li>
                        </ul>

                        <a href="{{ $level['link'] }}" class="btn btn-lg btn-primary btn-animated mt-auto d-grid">
                            Nạp ngay
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection