<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.includes._head')

<body>
    <div class="container-scroller">

        @include('layouts.includes._navbar')

        <div class="container-fluid page-body-wrapper" style="padding-left: 0; padding-right: 0">

            @include('layouts.includes._settings-panel')

            @include('layouts.includes._sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                @include('layouts.includes._footer')
            </div>
            <!-- main-panel ends -->

        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    @include('layouts.includes._script')
</body>
</html>
