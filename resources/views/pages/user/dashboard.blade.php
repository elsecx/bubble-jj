@extends('layouts.user.main')
@section('title', 'Beranda')

@section('content')
    {{-- Pengaturan Akun --}}
    <section id="profile" class="row">
        <h3 class="fw-bold mb-3">Pengaturan Akun</h3>
        <div class="card">
            <div class="card-body mb-0 pb-0">
                <div class="main-profile-overview d-flex flex-column align-items-center justify-content-center">
                    <div class="d-flex gap-2 justify-content-center mb-2">
                        @for ($i = 1; $i <= 4; $i++)
                            @php
                                $picture = Auth::user()->profile->{'picture_' . $i};
                            @endphp

                            <label for="picture_{{ $i }}">
                                <img src="{{ $picture ? asset('storage/profiles/' . $picture) : asset('assets/images/profiles/upload.png') }}"
                                    class="img-thumbnail profile-picture" style="width:65px; height:65px; object-fit:cover; cursor:pointer;">
                            </label>

                            <input type="file" name="picture" id="picture_{{ $i }}"
                                data-url="{{ route('user.profile.update.picture', $i) }}" accept="image/*" hidden>
                        @endfor
                    </div>
                    <div class="text-center">
                        <h5 class="main-profile-name">{{ Auth::user()->name }}</h5>
                        <p class="main-profile-name-text">{{ Auth::user()->profile->no_telp }}</p>
                    </div>
                </div>
                <hr />
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Daftar Akun TikTok</label>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Akun 1</strong>: <mark class="fst-italic">{{ Auth::user()->profile->username_1 ?? '-' }}</mark>
                            </li>
                            <li class="list-group-item">
                                <strong>Akun 2</strong>: <mark class="fst-italic">{{ Auth::user()->profile->username_2 ?? '-' }}</mark>
                            </li>
                        </ul>
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
                        <a href="{{ route('user.upload.view', ['slug' => $category->slug]) }}" class="btn btn-primary spa-link">
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
                            @if ($orders->isEmpty())
                                <p class="text-center text-muted mt-3">Belum ada riwayat order.</p>
                            @else
                                <div class="row g-3">
                                    @foreach ($orders as $order)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="card h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <span class="font-monospace text-secondary mb-1">
                                                        {{ formatDate($order->created_at) }}
                                                    </span>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h5 class="fw-bold mb-2">{{ $order->category->title }}</h5>
                                                        <span class="badge text-bg-{{ $order->status_color }} mb-2">
                                                            {{ $order->status_label }}
                                                        </span>
                                                    </div>
                                                    <h6 class="fw-bold text-success mb-2">{{ $order->category->price }}</h6>

                                                    <div class="mt-auto d-flex gap-2 flex-wrap">
                                                        <a href="{{ route('user.order.show', $order->id) }}" class="btn btn-sm btn-primary w-100">
                                                            Detail
                                                            <i class="fe fe-corner-down-right text-white"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane" id="data-jj" role="tabpanel">
                            <div class="accordion" id="displayType">
                                <div class="accordion-item">
                                    <div class="d-flex gap-2 align-items-center">
                                        <h2 class="accordion-header flex-1">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#display-10" aria-expanded="false" aria-controls="display-10">
                                                10 detik
                                            </button>
                                        </h2>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-jj me-2"
                                            data-url="{{ route('user.profile.jj.destroy', 10) }}" {{ empty($videos[10] ?? []) ? 'disabled' : '' }}>
                                            Hapus
                                        </button>
                                    </div>
                                    <div id="display-10" class="accordion-collapse collapse" data-bs-parent="#displayType">
                                        <div class="accordion-body">
                                            @forelse ($videos[10] ?? [] as $video)
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
                                    <div class="d-flex gap-2 align-items-center">
                                        <h2 class="accordion-header flex-1">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#display-20" aria-expanded="false" aria-controls="display-20">
                                                15 detik
                                            </button>
                                        </h2>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-jj me-2"
                                            data-url="{{ route('user.profile.jj.destroy', 20) }}" {{ empty($videos[20] ?? []) ? 'disabled' : '' }}>
                                            Hapus
                                        </button>
                                    </div>
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
                                    <div class="d-flex gap-2 align-items-center">
                                        <h2 class="accordion-header flex-1">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#display-30" aria-expanded="false" aria-controls="display-30">
                                                25 detik
                                            </button>
                                        </h2>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-jj me-2"
                                            data-url="{{ route('user.profile.jj.destroy', 30) }}" {{ empty($videos[30] ?? []) ? 'disabled' : '' }}>
                                            Hapus
                                        </button>
                                    </div>
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
                                    <div class="d-flex gap-2 align-items-center">
                                        <h2 class="accordion-header flex-1">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#display-99" aria-expanded="false" aria-controls="display-99">
                                                60 detik
                                            </button>
                                        </h2>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-jj me-2"
                                            data-url="{{ route('user.profile.jj.destroy', 99) }}" {{ empty($videos[99] ?? []) ? 'disabled' : '' }}>
                                            Hapus
                                        </button>
                                    </div>
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
        $('input[type="file"][id^="picture_"]').on('change', function() {
            let url = $(this).data('url');
            let slot = $(this).attr('id').split('_')[1];
            let file = this.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append('picture', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            function updatePicture() {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast('success', res.message);
                            location.reload();
                        } else {
                            showToast('error', res.message || "Gagal update foto");
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                            showToast('error', errors || "Data yang dimasukkan tidak valid");
                        } else {
                            showToast('error', xhr.responseJSON?.message || "Terjadi kesalahan, coba lagi.");
                        }
                    },
                });
            }

            $.get("{{ route('password.status') }}", function(res) {
                if (res.confirmed) {
                    updatePicture();
                } else {
                    confirmPassword(function() {
                        updatePicture();
                    })
                }
            })
        });

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

    <script data-partial="1">
        $(".btn-delete-jj").on("click", function(e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus video JJ ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#adb5bd",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function(res) {
                            if (res.status === 'success') {
                                showToast('success', res.message, 2500);
                                location.reload();
                            } else {
                                showToast('error', res.message);
                            }
                        },
                        error: function(xhr) {
                            showToast('error', xhr.responseJSON?.message || "Terjadi kesalahan, coba lagi.");
                        }
                    });
                }
            });
        });
    </script>
@endsection
