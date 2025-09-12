<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-menu-styles="light" loader="disable"
    style="--primary-rgb: 181, 71, 226;" data-default-header-styles="color">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ SettingsHelper::get('app_name', config('app.name')) }}</title>
    <meta name="Author" content="{{ SettingsHelper::get('description') }}">
    <meta name="Description" content="{{ SettingsHelper::get('author') }}">
    <meta name="keywords" content="{{ SettingsHelper::get('keywords') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset(SettingsHelper::get('favicon')) }}" type="image/x-icon">

    <!-- Choices JS -->
    <script src="{{ asset('templates/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('templates/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('templates/css/styles.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('templates/css/icons.css') }}" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="{{ asset('templates/libs/node-waves/waves.min.css') }}" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="{{ asset('templates/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

    <!-- Sweetalert2 JS -->
    <link rel="stylesheet" href="{{ asset('templates/libs/sweetalert2/sweetalert2.min.css') }}">

    @yield('styles')

</head>

<body>

    <!-- Loader -->
    <div id="loader">
        <img src="{{ asset('templates/images/media/media-75.svg') }}" alt="">
    </div>
    <!-- Loader -->

    <div class="page">

        <!-- app-header -->
        @include('layouts.admin.navbar')
        <!-- /app-header -->

        <!-- Start::app-sidebar -->
        @include('layouts.admin.sidebar')
        <!-- End::app-sidebar -->

        <div class="d-sm-flex align-items-center page-header-breadcrumb z-n1">
            <div>
                <h4 id="breadcrumb" class="fw-medium mb-2">@yield('title')</h4>
            </div>
        </div>

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div id="main-content" class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- End::app-content -->

        <!-- Footer Start -->
        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted">
                    Copyright ©<span id="year"></span>
                    <a href="https://www.livetok.online/license.html" target="_blank" class="text-dark fw-semibold">
                        {{ SettingsHelper::get('copyright') }}
                    </a>. All rights reserved
                </span>
            </div>
        </footer>
        <!-- Footer End -->

    </div>

    <div class="scrollToTop">
        <a href="javascript:void(0);" class="arrow">
            <i class="las la-angle-double-up fs-20 text-fixed-white"></i>
        </a>
    </div>

    <div id="responsive-overlay"></div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        @method('POST')
    </form>

    <script>
        window.appName = @json(SettingsHelper::get('app_name', config('app.name')));
    </script>

    <!-- Main Theme Js -->
    <script src="{{ asset('templates/js/main.js') }}"></script>

    <!-- JQuery JS -->
    <script src="{{ asset('templates/libs/jquery/dist/jquery.min.js') }}"></script>

    <!-- Date & Time Picker JS -->
    <script src="{{ asset('templates/libs/moment/moment.js') }}"></script>

    <!-- Popper JS -->
    <script src="{{ asset('templates/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('templates/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('templates/js/defaultmenu.min.js') }}"></script>

    <!-- Node Waves JS-->
    <script src="{{ asset('templates/libs/node-waves/waves.min.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('templates/js/sticky.js') }}"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('templates/libs/simplebar/simplebar.min.js') }}"></script>

    <!-- Sweetalert2 JS -->
    <script src="{{ asset('templates/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- SPA JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <!-- Copyright year handler -->
    <script>
        let date = moment(new Date());
        $("#year").text(date.format("YYYY"));
    </script>

    <!-- Logout handler -->
    <script>
        $(document).on("click", "#logout-btn", function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Apakah Anda yakin ingin keluar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#adb5bd",
                confirmButtonText: "Ya, Keluar",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#logout-form").submit();
                }
            });
        });
    </script>

    @yield('scripts')

</body>

</html>
