<header class="app-header">
    <div class="main-header-container container-fluid">
        <div class="header-content-left">
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
        </div>
        <div class="header-content-right">
            <div class="header-element d-flex align-items-center">
                <a class="btn btn-danger" href="#!" id="logout-btn">
                    <i class='mdi mdi-logout'></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>
