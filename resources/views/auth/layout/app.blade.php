@include('auth.layout.header')

<div class="auth-main">
    <div class="auth-wrapper v1">
        <div class="auth-form">
            @yield('content')
        </div>
    </div>
</div>

@include('auth.layout.footer')