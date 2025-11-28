@extends('client.layout.app')

@section('title', 'Điều Khoản')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-bold text-primary mb-4 text-center">
                    Điều Khoản
                </h3>
                <div class="content fs-6" style="line-height: 1.8;">
                    {!! $siteSettings['terms']!!}
                </div>
            </div>
        </div>
    </div>
@endsection