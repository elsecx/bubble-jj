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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold">Edit Akun</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <a href="{{ asset('assets/images/profiles/' . Auth::user()->profile->picture ?? 'default.jpg') }}" class="glightbox"
                            data-gallery="profile">
                            <img src="{{ asset('assets/images/profiles/' . Auth::user()->profile->picture ?? 'default.jpg') }}"
                                class="rounded-circle mb-3" width="120" alt="Foto Profil">
                        </a>
                        <button type="button" class="btn btn-sm btn-primary">
                            Ganti Foto
                        </button>
                    </div>

                    <hr />

                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" value="{{ auth()->user()->email }}">
                            <small class="form-text">
                                Email digunakan untuk memulihkan password yang lupa.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Whatsapp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ auth()->user()->profile->no_telp }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="username_1" class="form-label">Username Tiktok (Utama)</label>
                            <input type="text" class="form-control" name="username_1" id="username_1" placeholder="Masukkan akun tiktok utama kamu"
                                value="{{ auth()->user()->profile->username_1 }}">
                        </div>
                        <div class="mb-3">
                            <label for="username_1" class="form-label">Username Tiktok Kedua</label>
                            <input type="text" class="form-control" name="username_1" id="username_1"
                                placeholder="Masukkan Username tiktok kedua (Opsional)" value="{{ auth()->user()->profile->username_2 }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0 mb-0">
                    <h5 class="fw-bold">Video JJ</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills justify-content-start nav-style-3 mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#20" aria-selected="true">
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
                        <div class="tab-pane show active" id="20" role="tabpanel">
                            Jenis JJ koin 20
                        </div>
                        <div class="tab-pane show" id="30" role="tabpanel">
                            Jenis JJ koin 30
                        </div>
                        <div class="tab-pane show" id="99" role="tabpanel">
                            Jenis JJ koin 99
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
