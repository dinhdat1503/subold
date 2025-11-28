@extends('client.layout.app')

@section('title', 'Chính Sách')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-bold text-primary mb-4 text-center">Chính Sách</h3>
                <div class="content fs-6" style="line-height: 1.8;">
                    {!! $siteSettings['policy'] !!}
                </div>
            </div>
        </div>
    </div>
@endsection