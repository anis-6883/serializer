<!-- jQuery -->
<script src="{{ asset('public/backend/js/jquery-3.5.1.js') }}"></script>
<!-- plugins:js -->
<script src="{{ asset('public/backend/plugins/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('public/backend/plugins/chart.js/Chart.min.js') }}"></script>
<!-- inject:js -->
<script src="{{ asset('public/backend/js/off-canvas.js') }}"></script>
<script src="{{ asset('public/backend/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('public/backend/js/template.js') }}"></script>
<script src="{{ asset('public/backend/js/settings.js') }}"></script>
<!-- Custom js for this page-->
<script src="{{ asset('public/backend/js/dashboard.js') }}"></script>
<script src="{{ asset('public/backend/js/Chart.roundedBarCharts.js') }}"></script>
<!-- Sweet Alert 2 -->
<script src="{{ asset('public/backend/plugins/sweetalert2/sweetalert2@11.js') }}"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    @if (Session::has('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}',
            })
        @endif
        
    @if (Session::has('error'))
        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}',
        })
    @endif

    @if (Session::has('warning'))
        Toast.fire({
            icon: 'warning',
            title: '{{ session('warning') }}',
        })
    @endif

    @foreach ($errors->all() as $error)
        Toast.fire({
            icon: 'error',
            title: '{{ $error }}',
        })
    @endforeach
    
</script>

<script src="{{ asset('public/backend/js/app.js') }}"></script>

@yield('js-script')