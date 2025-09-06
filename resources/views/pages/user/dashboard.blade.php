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
                        <img alt="" src="{{ asset('storage/profiles/' . Auth::user()->profile->picture ?? 'default.jpg') }}">
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

    {{-- Daftar Kategori Upload --}}
    <section id="upload-categories" class="row">
        <h3 class="fw-bold mb-3">Daftar Kategori Upload</h3>
        @foreach ($categories as $category)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="fw-bold">{{ $category->title }}</h4>
                            <h6 class="fw-bold text-success">{{ $category->price }}</h6>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <h6 class="text-muted fw-bold">
                            Keterangan:
                        </h6>
                        <p>
                            {{ $category->description }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('user.order.view', ['slug' => $category->slug]) }}" class="btn btn-primary spa-link">
                            Pilih
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Riwayat pesanan & Video JJ Kamu --}}
    <section id="other" class="row">
        <h3 class="fw-bold mb-3">Lainnya</h3>
        <div class="card p-0">
            <div class="card-header">
                <ul class="nav nav-pills justify-content-start nav-style-3 mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#history" aria-selected="true">
                            Riwayat Pesanan
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
                        <div class="tab-pane show active border-0 p-0" id="history" role="tabpanel">
                            <ol class="list-group list-group-numbered">
                                @forelse ($orders as $order)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <span class="font-monospace text-secondary">
                                                {{ formatDate($order->created_at) }}
                                            </span>
                                            <div class="d-flex gap-2">
                                                <h5 class="fw-bold">
                                                    {{ $order->category->title }}
                                                </h5>
                                                <span class="badge text-bg-{{ $order->status_color }}" style="height: fit-content;">
                                                    {{ $order->status_label }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                            <a href="" class="btn btn-sm btn-primary">
                                                Detail
                                                <i class="fe fe-corner-down-right text-white"></i>
                                            </a>
                                            <h6 class="fw-bold text-success">
                                                {{ $order->category->price }}
                                            </h6>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">
                                        Belum ada riwayat order.
                                    </li>
                                @endforelse
                            </ol>
                        </div>
                        <div class="tab-pane" id="data-jj" role="tabpanel">
                            <div class="accordion" id="displayType">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#display-20"
                                            aria-expanded="false" aria-controls="display-20">
                                            15 detik
                                        </button>
                                    </h2>
                                    <div id="display-20" class="accordion-collapse collapse" data-bs-parent="#displayType">
                                        <div class="accordion-body">
                                            @forelse ($videos[20] ?? [] as $video)
                                                <div class="card">
                                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top"
                                                        controls></video>
                                                    <div class="card-body">
                                                        <p class="card-text">Durasi: {{ $video->duration }} detik | Size:
                                                            {{ formatSize($video->size) }}
                                                        </p>
                                                        <p class="card-text">
                                                            <small class="text-body-secondary">
                                                                {{ formatDate($video->updated_at) }}
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted">Belum ada video untuk jenis ini.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#display-30" aria-expanded="false" aria-controls="display-30">
                                            25 detik
                                        </button>
                                    </h2>
                                    <div id="display-30" class="accordion-collapse collapse" data-bs-parent="#displayType">
                                        <div class="accordion-body">
                                            @forelse ($videos[30] ?? [] as $video)
                                                <div class="card">
                                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top"
                                                        controls></video>
                                                    <div class="card-body">
                                                        <p class="card-text">Durasi: {{ $video->duration }} detik | Size:
                                                            {{ formatSize($video->size) }}
                                                        </p>
                                                        <p class="card-text">
                                                            <small class="text-body-secondary">
                                                                {{ formatDate($video->updated_at) }}
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted">Belum ada video untuk jenis ini.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#display-99" aria-expanded="false" aria-controls="display-99">
                                            60 detik
                                        </button>
                                    </h2>
                                    <div id="display-99" class="accordion-collapse collapse" data-bs-parent="#displayType">
                                        <div class="accordion-body">
                                            @forelse ($videos[99] ?? [] as $video)
                                                <div class="card">
                                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top"
                                                        controls></video>
                                                    <div class="card-body">
                                                        <p class="card-text">Durasi: {{ $video->duration }} detik | Size:
                                                            {{ formatSize($video->size) }}
                                                        </p>
                                                        <p class="card-text">
                                                            <small class="text-body-secondary">
                                                                {{ formatDate($video->updated_at) }}
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted">Belum ada video untuk jenis ini.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
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
