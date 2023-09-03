<div class="navbar-collapse collapse show" id="sidebar-menu" style="">
    <ul class="navbar-nav pt-lg-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <polyline points="5 12 3 12 12 3 21 12 19 12"></polyline>
                        <path d="M19 12v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-8"></path>
                        <path d="M9 16v-4a2 2 0 0 1 2 -2h4"></path>
                    </svg>
                </span>
                <span class="nav-link-title">
                    Dashboard
                </span>
            </a>
        </li>


        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="false"
                role="button" aria-expanded="true">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <!-- Download SVG icon from http://tabler-icons.io/i/box -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                    </svg>
                </span>
                <span class="nav-link-title">
                    Data Himakom
                </span>
            </a>
            <div class="dropdown-menu show">
                @can('read user')
                    <a class="dropdown-item" href="{{ route('users.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/box -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 6l11 0"></path>
                                <path d="M9 12l11 0"></path>
                                <path d="M9 18l11 0"></path>
                                <path d="M5 6l0 .01"></path>
                                <path d="M5 12l0 .01"></path>
                                <path d="M5 18l0 .01"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Daftar Anggota
                        </span>
                    </a>
                @endcan


                @can('read permission')
                    <a class="dropdown-item" href="{{ route('permissions.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/box -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-open"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z">
                                </path>
                                <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                <path d="M8 11v-5a4 4 0 0 1 8 0"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Permissions
                        </span>
                    </a>
                @endcan

                @can('read role')
                    <a class="dropdown-item" href="{{ route('roles.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/box -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                <path d="M15 19l2 2l4 -4"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Roles
                        </span>
                    </a>
                @endcan

                @can('read cabinet')
                    <a class="dropdown-item" href="{{ route('cabinets.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8"></path>
                                <path d="M14 19l2 2l4 -4"></path>
                                <path d="M9 8h4"></path>
                                <path d="M9 12h2"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Kabinet
                        </span>
                    </a>
                @endcan

                @can('read department')
                    <a class="dropdown-item" href="{{ route('departments.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-bank"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M3 21l18 0"></path>
                                <path d="M3 10l18 0"></path>
                                <path d="M5 6l7 -3l7 3"></path>
                                <path d="M4 10l0 11"></path>
                                <path d="M20 10l0 11"></path>
                                <path d="M8 14l0 3"></path>
                                <path d="M12 14l0 3"></path>
                                <path d="M16 14l0 3"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Departemen
                        </span>
                    </a>
                @endcan

                @can('read program')
                    <a class="dropdown-item" href="{{ route('programs.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book-upload"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 20h-8a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12v5"></path>
                                <path d="M11 16h-5a2 2 0 0 0 -2 2"></path>
                                <path d="M15 16l3 -3l3 3"></path>
                                <path d="M18 13v9"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Program Kerja
                        </span>
                    </a>
                @endcan

                @can('read event')
                    <a class="dropdown-item" href="{{ route('events.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                </path>
                                <path d="M16 3l0 4"></path>
                                <path d="M8 3l0 4"></path>
                                <path d="M4 11l16 0"></path>
                                <path d="M8 15h2v2h-2z"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Events
                        </span>
                    </a>
                @endcan

                <a class="dropdown-item" href="">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-stairs-up"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M22 6h-5v5h-5v5h-5v5h-5"></path>
                            <path d="M6 10v-7"></path>
                            <path d="M3 6l3 -3l3 3"></path>
                        </svg>
                    </span>
                    <span class="nav-link-title">
                        Periode
                    </span>
                </a>
            </div>
        </li>


        <li class="nav-item">

        </li>

        <li class="nav-item">

        </li>

        <li class="nav-item">

        </li>

        <li class="nav-item">

        </li>
    </ul>

    <div class="mt-auto mb-3">
        <ul class="navbar-nav pt-lg-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.show') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z">
                            </path>
                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M15 8l2 0"></path>
                            <path d="M15 12l2 0"></path>
                            <path d="M7 16l10 0"></path>
                        </svg>
                    </span>
                    <span class="nav-link-title">
                        Profile
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <!-- logout -->
                <a class="nav-link" href="" id="logout">
                    <span class="nav-link-icon d-md-none
                    d-lg-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
                            </path>
                            <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                        </svg>
                    </span>
                    <span class="nav-link-title">
                        Logout
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#logout').click(function(e) {
                e.preventDefault();

                const url = "{{ route('logout') }}";
                const token = $('meta[name="csrf-token"]').attr('content');
                const method = 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            });
        });
    </script>

    <script>
        // if dropdown is open, add class active
        $('.dropdown').find('.dropdown-menu').each(function() {
            $(this).find('a').each(function() {
                if ($(this).attr('href') == window.location.href) {
                    $(this).addClass('active');
                    $(this).parents('.dropdown').addClass('active');
                }
            });
        });

        // if dropdown is open, add class active
        $('.nav-item').find('.nav-link').each(function() {
            if ($(this).attr('href') == window.location.href) {
                $(this).addClass('active');
                $(this).parents('.nav-item').addClass('active');
            }
        });
    </script>
@endpush
