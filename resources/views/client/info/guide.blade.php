@extends('client.layout.app')

@section('title', 'Hướng Dẫn Sử Dụng')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-bold text-primary mb-4 text-center">Hướng Dẫn Sử Dụng</h3>
                <div class="content fs-6" style="line-height: 1.8;">
                    {!! $siteSettings['guide'] !!}
                </div>
            </div>
        </div>
    </div>
@endsection