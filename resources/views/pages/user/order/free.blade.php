@extends('layouts.user.main')
@section('title', 'Upload Video Gratis')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-sm d-flex align-items-center spa-link">
            <i class="bi bi-arrow-left-short fs-3"></i>
            <span class="fw-bold fs-5">Kembali</span>
        </a>
    </div>

    <div class="row">
        <div class="card">
            <form id="form-upload" action="{{ route('user.order.service', ['slug' => $menu->slug]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="card-header border-bottom">
                    <h3 class="fw-bold fs-3 lh-1">Upload {{ $menu->title }}</h3>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="file">Pilih File {{ $menu->title }}</label>
                        <input class="form-control form-control-sm" type="file" id="file" name="file" accept="video/*">
                    </div>
                    <div class="mb-3">
                        <label for="display_type">Pilih Jenis Tampil</label>
                        <select class="form-select form-select-sm" name="display_type" id="display_type" required>
                            <option selected disabled>=== Pilih Jenis Tampil JJ ===</option>
                            <option value="20">Jenis JJ Coin 20 : 15 detik</option>
                            <option value="30">Jenis JJ Coin 30 : 25 detik</option>
                            <option value="99">Jenis JJ Coin 99 : 60 detik</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <span>Biaya Upload:</span>
                        <h4 class="fw-bold text-success">{{ $menu->price }}</h4>
                    </div>
                    <div class="mb-3">
                        <h5 class="text-warning fw-bold">
                            <i class="ti ti-alert-circle"></i>
                            S&K (Syarat & Ketentuan):
                        </h5>
                        <ul>
                            <li>Video harus berdurasi maksimal 60 detik.</li>
                            <li>Video harus berukuran maksimal 2MB.</li>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Upload sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script data-partial="1">
        const MAX_SIZE = 2.5 * 1024 * 1024;
        const MAX_DURATION = 60;

        const $form = $("#form-upload");
        const $btnSubmit = $form.find("button[type=submit]");

        function toggleButton(state, text = "Upload Sekarang") {
            $btnSubmit.prop("disabled", !state).text(state ? text : "Loading...");
        }

        function validateVideo(file, callback) {
            if (!file) {
                showToast("error", "Pilih file terlebih dahulu!");
                toggleButton(true);
                return;
            }

            let video = document.createElement("video");
            video.preload = "metadata";
            video.src = URL.createObjectURL(file);

            video.onloadedmetadata = function() {
                window.URL.revokeObjectURL(video.src);

                if (file.size > MAX_SIZE) {
                    showToast("error", "Ukuran video melebihi 2MB!");
                    toggleButton(true);
                    return;
                }

                if (video.duration > MAX_DURATION) {
                    showToast("error", "Durasi video melebihi 60 detik!");
                    toggleButton(true);
                    return;
                }

                callback();
            };
        }

        function submitAjax() {
            let formData = new FormData($form[0]);

            $.ajax({
                url: $form.attr("action"),
                type: $form.attr("method"),
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        $form[0].reset();
                        showToast('success', res.message);
                    } else {
                        showToast("error", res.message);
                    }
                    toggleButton(true);
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        showToast('error', errors || "Data yang dimasukkan tidak valid");
                    } else {
                        showToast('error', xhr.responseJSON?.message || "Terjadi kesalahan, coba lagi.");
                    }
                    toggleButton(true);
                }
            });
        }

        $form.on("submit", function(e) {
            e.preventDefault();
            toggleButton(false);

            let fileInput = $form.find("input[name=file]")[0].files[0];

            $.get("{{ route('password.status') }}", function(res) {
                let processUpload = () => validateVideo(fileInput, submitAjax);

                if (res.confirmed) {
                    processUpload();
                } else {
                    confirmPassword(processUpload);
                }
            });
        });
    </script>
@endsection
