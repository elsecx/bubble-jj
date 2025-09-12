<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Selamat Datang - {{ config('app.name') }}</title>
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('templates/css/landing/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('templates/css/landing/style-preset.css') }}">

    <link rel="stylesheet" href="{{ asset('templates/css/landing/landing.css') }}">

    <style>
        .footer {
            position: fixed;
            width: 100%;
            bottom: 0;
            padding: 10px 0;
            background: #000;
            text-align: center;
            z-index: 999;
        }

        .footer span {
            color: #fff;
        }
    </style>
</head>

<body>
    <header id="home">
        <nav class="navbar navbar-dark default">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('templates/images/brand-logos/desktop-dark.png') }}" alt="logo" width="150">
                </a>
                <div>
                    <div class="d-flex align-items-center gap-3">
                        @if (Auth::check())
                            <a class="btn btn-primary" href="{{ route(Auth::user()->role->direct) }}">
                                Dashboard
                            </a>
                        @else
                            <a class="btn btn-primary" href="{{ route('login') }}">
                                Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-9 col-xl-6">
                    <h1 class="mt-sm-3 text-white mb-4 f-w-600" data-wow-delay="0.2s">
                        Selamat datang sobat <span class="text-primary">Bubble</span>
                    </h1>
                    <h6 class="mb-4 text-white opacity-75" data-wow-delay="0.4s">
                        Tempat untuk melakukan pengajuan pembuatan Video Jedag-jedug, Silahkan daftar terlebih dulu lalu
                        Login untuk pembuatan Video.
                    </h6>
                    <div class="my-5">
                        <a href="#menu" class="btn btn-outline-primary me-2" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="menu">
                            Lihat Menu
                        </a>
                        @if (Auth::check())
                            <a href="{{ route(Auth::user()->role->direct) }}" class="btn btn-primary">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="collapse" id="menu">
                    <div class="row">
                        @php
                            use App\Models\UploadCategory;

                            $menus = UploadCategory::rememberCache('menus_all', 3600, function () {
                                return UploadCategory::all();
                            });
                        @endphp
                        @foreach ($menus as $menu)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header p-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h4 class="fw-bold">{{ $menu->title }}</h4>
                                            <h6 class="fw-bold text-success">{{ $menu->price }}</h6>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="text-muted fw-bold">
                                            Keterangan:
                                        </h6>
                                        <p>
                                            {{ $menu->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </header>

    <footer class="footer">
        <div class="container">
            <span class="text-secondary">
                Copyright ©<span id="year"></span>
                <a href="https://www.livetok.online/license.html" target="_blank" class="text-light fw-semibold">
                    PT Digjaya Mahakarya Teknologi
                </a>. All rights reserved
            </span>
        </div>
    </footer>

    <!-- JQuery JS -->
    <script src="{{ asset('templates/js/jquery.min.js') }}"></script>

    {{-- Bootstrap --}}
    <script src="{{ asset('templates/js/landing/bootstrap.min.js') }}"></script>

    <!-- Date & Time Picker JS -->
    <script src="{{ asset('templates/libs/moment/moment.js') }}"></script>

    <script>
        let date = moment(new Date());
        $("#year").text(date.format("YYYY"));
    </script>
</body>

</html>
