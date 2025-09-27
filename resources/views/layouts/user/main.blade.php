<!DOCTYPE html>
<html lang="en" data-theme-mode="light" data-menu-styles="light" style="--primary-rgb: 190, 144, 48;" data-default-header-styles="light">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ SettingsHelper::get('app_name', config('app.name')) }}</title>
    <meta name="Description" content="{{ SettingsHelper::get('description') }}">
    <meta name="Author" content="{{ SettingsHelper::get('author') }}">
    <meta name="keywords" content="{{ SettingsHelper::get('keywords') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset(SettingsHelper::get('favicon')) }}" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('templates/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('templates/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('templates/css/icons.css') }}" rel="stylesheet">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('templates/libs/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/libs/glightbox/css/glightbox.min.css') }}">

    <!-- Sweetalert Css -->
    <link rel="stylesheet" href="{{ asset('templates/libs/sweetalert2/sweetalert2.all.min.js') }}">

    <style>
        .footer {
            position: fixed;
            width: 100%;
            bottom: 0;
            padding: 10px 0;
            text-align: center;
            box-shadow: none;
            z-index: 999;
            background: #fff;
        }
    </style>

</head>

<body>
    <!-- Loader -->
    <div id="loader">
        <img src="{{ asset('templates/images/media/media-75.svg') }}" alt="">
    </div>
    <!-- Loader -->

    <div class="page">
        @include('layouts.user.navbar')

        <!-- main-user-wrapper -->
        <div class="my-auto">
            <div id="main-content" class="container py-5 my-5 mx-auto">
                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <small class="text-muted">
                    Copyright ©<span id="year"></span>
                    <a href="https://www.livetok.online/license.html" target="_blank" class="text-dark fw-semibold">
                        {{ SettingsHelper::get('copyright') }}
                    </a>. All rights reserved
                </small>
            </div>
        </footer>
    </div>

    <div class="scrollToTop">
        <a href="javascript:void(0);" class="arrow">
            <i class="las la-angle-double-up fs-20 text-fixed-white"></i>
        </a>
    </div>

    <div id="responsive-overlay"></div>

    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="form-password-confirm" action="{{ route('password.check') }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Kepemilikan Akun</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password kamu">
                                <button class="btn btn-light" type="button" onclick="togglePassword('password')">
                                    <i class='fa fa-eye'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    {{-- Bootstrap JS --}}
    <script src="{{ asset('templates/js/landing/bootstrap.min.js') }}"></script>

    <!-- Choices JS -->
    <script src="{{ asset('templates/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Gallery JS -->
    <script src="{{ asset('templates/libs/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('templates/js/gallery.js') }}"></script>

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
