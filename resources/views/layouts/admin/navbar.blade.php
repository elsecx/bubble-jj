<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a class="header-logo" href="{{ route('user.dashboard') }}">
                        <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                        <img src="{{ asset('assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="anchor" href="javascript:void(0);" class="sidemenu-toggle header-link" data-bs-toggle="sidebar">
                    <span class="open-toggle me-2">
                        <i class="fe fe-align-left header-link-icon border-0"></i>
                    </span>
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">

            <!-- Start::header-element -->
            <div class="header-element mainuserProfile">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="d-sm-flex wd-100p lh-0">
                            <div class="avatar avatar-md">
                                <img alt="avatar" class="rounded-circle" src="{{ asset('assets/images/profiles/default.jpg') }}">
                            </div>
                            <div class="ms-2 my-auto d-none d-xl-flex">
                                <h6 class=" font-weight-semibold mb-0 fs-13 user-name d-sm-block d-none">
                                    {{ Auth::user()->name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- End::header-link|dropdown-toggle -->
                <div class="main-header-dropdown dropdown-menu pt-0 border-0 header-profile-dropdown dropdown-menu-end dropdown-menu-arrow"
                    aria-labelledby="mainHeaderProfile">
                    <div class="p-3 menu-header-content text-fixed-white rounded-top text-center">
                        <div class="">
                            <div class="avatar avatar-xl rounded-circle">
                                <img alt="" class="rounded-circle" src="{{ asset('assets/images/profiles/default.jpg') }}">
                            </div>
                            <p class="text-fixed-white fs-18 fw-semibold mb-0">
                                {{ Auth::user()->email }}
                            </p>
                            <span class="fs-13 text-fixed-white">
                                {{ Auth::user()->name }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <hr class="dropdown-divider">
                    </div>
                    <div>
                        <a class="dropdown-item" href="settings.html">
                            <i class="fa fa-sliders-h me-1"></i>
                            Settings
                        </a>
                        <a id="logout-btn" class="dropdown-item text-danger" href="#!">
                            <i class="fa fa-sign-out-alt me-1"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
