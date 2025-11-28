<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="/assets/libs/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/fontawesome-free-7.1.0-web/js/fontawesome.min.js"></script>
<script src="/assets/libs/fontawesome-free-7.1.0-web/js/solid.min.js"></script>
<script src="/assets/libs/toastr/toastr.min.js"></script>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/sweetalert2.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/client/auth.js"></script>

<script>
    $("form").on("submit", function (e) {
        swal({
            title: 'Đang xử lý...',
            text: 'Vui lòng chờ trong giây lát',
            showLoading: true,
        });
    });
</script>
@if ($errors->any())
    <script>
        swal({
            text: "{{ $errors->first() }}",
            icon: "error"
        });
    </script>
@endif

@if (session('success'))
    <script>
        swal({
            text: "{{ session('success') }}",
            icon: "success"
        });
    </script>
@elseif (session('error'))
    <script>
        swal({
            text: "{{ session('error') }}",
            icon: "error"
        });
    </script>
@endif
@yield('script')
</body>

</html>