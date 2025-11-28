</div>
</div>
</div>
<!--  Import Js Files -->
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/moment.min.js"></script>
<script src="/assets/js/sweetalert2.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/toastr/toastr.min.js"></script>
<script src="/assets/js/admin/app.min.js"></script>
<script src="/assets/js/admin/app.init.js"></script>
<script src="/assets/js/admin/app-style-switcher.js"></script>
<script src="/assets/js/admin/sidebarmenu.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/admin/custom.js"></script>

@yield('script')

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
        @foreach ($errors->all() as $error)
            swal({
                text: "{{ $error }}",
                icon: "error"
            });
        @endforeach
    </script>
@endif

@if (session('error'))
    <script>
        swal({
            text: "{{ session('error') }}",
            icon: "error"
        });
    </script>
@elseif (session('success'))
    <script>
        swal({
            text: "{{ session('success') }}",
            icon: "success"
        });
    </script>
@endif


</body>

</html>