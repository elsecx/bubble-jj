@extends('layouts.auth.main')
@section('title', 'Verifikasi email')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-2">Verifikasi Email Kamu</h3>
                    <p class="mb-4">
                        Kami telah mengirim link verifikasi ke <b id="email">{{ auth()->user()->email }}</b>.
                        Silakan cek inbox (atau folder spam).
                        <a href="javascript:void(0);" id="btn-change-email" class="text-primary" data-bs-toggle="modal" data-bs-target="#changeEmailModal">
                            Email salah?
                        </a>
                    </p>

                    <div class="d-flex gap-2 justify-content-center">
                        <button id="btn-resend" class="btn btn-primary">Kirim Ulang Email</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-change-email" action="{{ route('verification.email.update') }}" method="POST">
                @csrf
                @method('POST')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeEmailModalLabel">Ganti Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="new-email" class="form-label">Email Baru</label>
                        <input type="email" class="form-control" id="new-email" name="email" placeholder="Masukkan email baru">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Email</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script data-partial="{{ isset($view) }}">
        $("#btn-resend").on("click", function() {
            let btn = $(this);
            btn.attr("disabled", true).text("Mengirim...");

            $.ajax({
                url: "{{ route('verification.resend') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    showToast("success", res.message);

                    let countdown = 25;
                    btn.text(`Tunggu ${countdown}d untuk kirim ulang`);

                    let interval = setInterval(() => {
                        countdown--;
                        if (countdown > 0) {
                            btn.text(`Tunggu ${countdown}d untuk kirim ulang`);
                        } else {
                            clearInterval(interval);
                            btn.attr("disabled", false).text("Kirim Ulang Email");
                        }
                    }, 1000);
                },
                error: function(xhr) {
                    let err = xhr.responseJSON?.message || "Terjadi kesalahan!";
                    showToast("error", err);
                    btn.attr("disabled", false).text("Kirim Ulang Email");
                }
            });
        });

        $("#form-change-email").on("submit", function(e) {
            e.preventDefault();

            let form = $(this);

            let btnSubmit = form.find("button[type=submit]");
            btnSubmit.prop("disabled", true).text("Loading...");

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        showToast('success', res.message);
                        btnSubmit.prop("disabled", false).text("Simpan Email");

                        $(email).text(res.email);

                        const modalEl = $("#changeEmailModal");
                        const modal = new bootstrap.Modal(modalEl);
                        modal.hide();
                    } else {
                        showToast("error", res.message);
                        btnSubmit.prop("disabled", false).text("Simpan Email");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        showToast('error', errors || "Data yang dimasukkan tidak valid");
                    } else {
                        showToast('error', xhr.responseJSON?.message || "Terjadi kesalahan, coba lagi.");
                    }

                    btnSubmit.prop("disabled", false).text("Simpan Email");
                },
            });
        });
    </script>
@endsection
