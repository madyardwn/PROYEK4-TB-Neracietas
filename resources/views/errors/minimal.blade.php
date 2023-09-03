<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="shortcut icon" href="{{ asset('img/neracietas-logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.25.0/tabler-icons.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"
        integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>
    @vite('resources/sass/app.scss')

    <!-- Custom styles for this Page-->
    @include('layouts._custom_styles')
</head>

<body>
    <div class="page">

        <div class="page-wrapper">

            <div class="page page-center">
                <div class="container-tight py-4">
                    <div class="empty">
                        <div class="empty-header">@yield('code', '404')</div>
                        <p class="empty-title">Oops… You just found an error page</p>
                        <p class="empty-subtitle text-secondary">
                            @yield('message', __('Not Found'))
                        </p>
                        <div class="empty-action">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l14 0"></path>
                                    <path d="M5 12l6 6"></path>
                                    <path d="M5 12l6 -6"></path>
                                </svg>
                                {{ __('Back to dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Core plugin JavaScript-->
    @vite('resources/js/app.js')

    <!-- Custome level JavaScript-->
    @include('layouts._custom_scripts')
</body>

</html>
