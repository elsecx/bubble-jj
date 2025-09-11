<aside class="app-sidebar stickybg" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="" class="header-logo">
            <img src="{{ asset(SettingsHelper::get('logo')) }}" alt="logo" class="desktop-logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                @php
                    function isActive($route)
                    {
                        return request()->routeIs($route) ? 'active' : '';
                    }
                @endphp

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ isActive('admin.dashboard') }} spa-link">
                        <span class="side-menu__icon">
                            <i class='fe fe-airplay'></i>
                        </span>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <!-- End::slide -->

                @role('super')
                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="{{ route('admin.operators.index') }}" class="side-menu__item {{ isActive('admin.operators.*') }} spa-link">
                            <span class="side-menu__icon">
                                <i class='fe fe-users'></i>
                            </span>
                            <span class="side-menu__label">Data Admin</span>
                        </a>
                    </li>
                    <!-- End::slide -->
                @endrole

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('admin.orders.view') }}" class="side-menu__item {{ isActive('admin.orders.*') }} spa-link">
                        <span class="side-menu__icon">
                            <i class='fe fe-shopping-bag'></i>
                        </span>
                        <span class="side-menu__label">Pesanan</span>
                    </a>
                </li>
                <!-- End::slide -->
            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
