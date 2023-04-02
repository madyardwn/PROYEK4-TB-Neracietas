<div class="navbar-nav flex-row d-lg-none">
    <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
            <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
            <div class="d-none d-xl-block ps-2">
                <div>Paweł Kuna</div>
                <div class="mt-1 small text-muted">UI Designer</div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <a href="#" class="dropdown-item">Status</a>
            <a href="./profile.html" class="dropdown-item">Profile</a>
            <a href="#" class="dropdown-item">Feedback</a>
            <div class="dropdown-divider"></div>
            <a href="./settings.html" class="dropdown-item">Settings</a>
            <a href="./sign-in.html" class="dropdown-item">Logout</a>
        </div>
    </div>
</div>
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
                role="button" aria-expanded="false">
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
                    Keanggotaan
                </span>
            </a>
            <div class="dropdown-menu">
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
                <a class="dropdown-item" href="./changelog.html">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/box -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-open"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                <a class="dropdown-item" href="./changelog.html">
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
                <a class="dropdown-item" href="./changelog.html">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/box -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                            <path d="M7 9l5 -5l5 5"></path>
                            <path d="M12 4l0 12"></path>
                        </svg>
                    </span>
                    <span class="nav-link-title">
                        Upload Data Angkatan
                    </span>
                </a>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="{{ route('cabinets.index') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('departments.index') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-bank"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('programs.index') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book-upload"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('events.index') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        </li>
    </ul>

    <div class="mt-auto mb-3">
        <ul class="navbar-nav pt-lg-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
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
                <a class="nav-link" href="{{ route('home') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
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
