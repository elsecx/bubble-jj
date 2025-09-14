@extends('layouts.user.main')
@section('title', 'Pengaturan')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-sm d-flex align-items-center spa-link">
            <i class="bi bi-arrow-left-short fs-3"></i>
            <span class="fw-bold fs-5">Kembali</span>
        </a>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold">Edit Akun</h5>
                </div>
                <div class="card-body mt-0 pt-0">
                    <form id="form-profile" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="d-flex gap-2 justify-content-center mb-2">
                            @for ($i = 1; $i <= 4; $i++)
                                @php
                                    $picture = Auth::user()->profile->{'picture_' . $i};
                                @endphp

                                <label for="picture_{{ $i }}">
                                    <img src="{{ $picture ? asset('storage/profiles/' . $picture) : asset('storage/profiles/default.jpg') }}"
                                        class="img-thumbnail profile-picture" style="width:65px; height:65px; object-fit:cover; cursor:pointer;"
                                        id="preview_{{ $i }}">
                                </label>

                                <input type="file" name="picture_{{ $i }}" id="picture_{{ $i }}" accept="image/*" hidden>
                            @endfor
                        </div>

                        <hr />

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control form-control-sm" name="name" id="name" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control form-control-sm" name="email" id="email" value="{{ auth()->user()->email }}">
                            <small class="form-text">
                                Email digunakan untuk memulihkan password yang lupa.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Whatsapp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" class="form-control form-control-sm" id="no_telp" name="no_telp"
                                    value="{{ auth()->user()->profile->no_telp }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="username_1" class="form-label">Username Tiktok (Utama)</label>
                            <input type="text" class="form-control form-control-sm" name="username_1" id="username_1"
                                placeholder="Masukkan akun tiktok utama kamu" value="{{ auth()->user()->profile->username_1 }}">
                        </div>
                        <div class="mb-3">
                            <label for="username_2" class="form-label">Username Tiktok Kedua</label>
                            <input type="text" class="form-control" name="username_2" id="username_2"
                                placeholder="Masukkan Username tiktok kedua (Opsional)" value="{{ auth()->user()->profile->username_2 }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header pb-0 mb-0">
                    <h5 class="fw-bold">Video JJ Kamu</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills justify-content-start nav-style-3 mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#15" aria-selected="true">
                                10 Detik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#20" aria-selected="true">
                                15 Detik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#30">
                                25 Detik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#99">
                                60 Detik
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="15" role="tabpanel">
                            @forelse ($videos[15] ?? [] as $video)
                                <div class="card">
                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top" controls></video>
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
                        <div class="tab-pane show" id="20" role="tabpanel">
                            @forelse ($videos[20] ?? [] as $video)
                                <div class="card">
                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top" controls></video>
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
                        <div class="tab-pane show" id="30" role="tabpanel">
                            @forelse ($videos[30] ?? [] as $video)
                                <div class="card">
                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top" controls></video>
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
                        <div class="tab-pane show" id="99" role="tabpanel">
                            @forelse ($videos[99] ?? [] as $video)
                                <div class="card">
                                    <video src="{{ asset('storage/videojj/' . $video->filename) }}" class="card-img-top" controls></video>
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
@endsection

@section('scripts')
    <script data-partial="1">
        for (let i = 1; i <= 4; i++) {
            $("#picture_" + i).on("change", function() {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    $("#preview_" + i).attr("src", e.target.result);
                }
                reader.readAsDataURL(file);
            });
        }


        $("#form-profile").on('submit', function(e) {
            e.preventDefault();

            let formElement = this;
            let formData = new FormData(formElement);

            let btnSubmit = $(this).find("button[type=submit]");
            btnSubmit.prop("disabled", true).text("Loading...");

            $.ajax({
                url: formElement.action,
                type: formElement.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res);
                    if (res.status === 'success') {
                        showToast('success', res.message);
                        loadPage(res.redirect);
                        history.pushState(null, null, res.redirect);
                    } else {
                        showToast("error", res.message);
                        btnSubmit.prop("disabled", false).text("Lanjut");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON?.message);
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        showToast('error', errors || "Data yang dimasukkan tidak valid");
                    } else {
                        showToast('error', xhr.responseJSON?.message || "Terjadi kesalahan, coba lagi.");
                    }
                    btnSubmit.prop("disabled", false).text("Lanjut");
                },
            });
        });
    </script>
@endsection
