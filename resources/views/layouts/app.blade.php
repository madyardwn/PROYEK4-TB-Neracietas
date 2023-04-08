<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/sass/app.scss')
    @yield('custom_styles')
    <!-- Custom styles for this Page-->
    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    {{-- tabler --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/libs/dropzone/dist/dropzone.css"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/libs/dropzone/dist/dropzone-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

    {{-- fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    {{-- sweetalert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="theme-light">
    <div class="page">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <div class="page-wrapper">
            <!-- Page header -->
            {{-- @include('layouts.header') --}}
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
    <!-- Core plugin JavaScript-->
    @vite('resources/js/app.js')


    <!-- Page level custom scripts -->
    @yield('custom_scripts')
    @stack('scripts')
</body>

</html>
