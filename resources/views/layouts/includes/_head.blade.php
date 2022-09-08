<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('public/backend/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/plugins/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/plugins/css/vendor.bundle.base.css') }}">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/vertical-layout-light/style.css') }}">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('public/default/h&s.jpg') }}" />
    <!-- fontawsome -->
    <script src="https://kit.fontawesome.com/fbcd216f09.js" crossorigin="anonymous"></script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @include('layouts.others.variables')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    @yield('css-stylesheet')
</head>