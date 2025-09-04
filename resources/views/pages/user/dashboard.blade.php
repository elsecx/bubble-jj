@extends('layouts.user.main')
@section('title', 'Beranda')

@section('content')
    {{-- Pengaturan Akun --}}
    <section id="profile" class="row">
        <h3 class="fw-bold mb-3">Pengaturan Akun</h3>
        <div class="card">
            <div class="card-body mb-0 pb-0">
                <div class="main-profile-overview d-flex flex-column align-items-center justify-content-center">
                    <div class="main-img-user profile-user">
                        <img alt="" src="{{ asset('storage/images/profiles/' . Auth::user()->profile->picture ?? 'default.jpg') }}">
                    </div>
                    <div class="text-center">
                        <h5 class="main-profile-name">{{ Auth::user()->name }}</h5>
                        <p class="main-profile-name-text">{{ Auth::user()->profile->no_telp }}</p>
                    </div>
                </div>
                <hr />
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="username2" class="form-label">Akun 1 (Akun utama)</label>
                        <input type="text" class="form-control" id="username2" name="username2" placeholder="Masukkan akun tiktok utama kamu"
                            value="{{ Auth::user()->profile->username_1 ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="username2" class="form-label">Akun 2</label>
                        <input type="text" class="form-control" id="username2" name="username2" placeholder="Akun tiktok kedua (Opsional)"
                            value="{{ Auth::user()->profile->username_2 ?? '' }}" readonly>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('user.profile.view') }}" id="btn-edit" class="btn btn-primary w-100">
                    Edit akun
                </a>
            </div>
        </div>
    </section>

    {{-- Daftar Menu Upload --}}
    <section id="upload-categories" class="row">
        <h3 class="fw-bold mb-3">Daftar Menu Upload</h3>
        @foreach ($menus as $menu)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="fw-bold">{{ $menu->title }}</h4>
                            <h6 class="fw-bold text-success">{{ $menu->price }}</h6>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <h6 class="text-muted fw-bold">
                            Keterangan:
                        </h6>
                        <p>
                            {{ $menu->description }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('user.order.view', ['slug' => $menu->slug]) }}" class="btn btn-primary spa-link">
                            Pilih
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Riwayat order & Video JJ Kamu --}}
    <section id="other" class="row">
        <h3 class="fw-bold mb-3">Lainnya</h3>
        <div class="card p-0">
            <div class="card-header">
                <ul class="nav nav-pills justify-content-start nav-style-3 mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#history" aria-selected="true">
                            Riwayat Order
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#data-jj">
                            Video JJ Kamu
                        </a>
                    </li>
                </ul>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="history" role="tabpanel">
                            Riwayat Order
                        </div>
                        <div class="tab-pane" id="data-jj" role="tabpanel">
                            Video JJ Kamu
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script data-partial="1">
        $("#btn-edit").on("click", function(e) {
            e.preventDefault();
            const redirect = $(this).attr("href");

            $.get("{{ route('password.status') }}", function(res) {
                if (res.confirmed) {
                    loadPage(redirect);
                    history.pushState(null, null, redirect);
                } else {
                    confirmPassword(function() {
                        loadPage(redirect);
                        history.pushState(null, null, redirect);
                    });
                }
            });
        });
    </script>
@endsection
