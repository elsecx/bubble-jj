@extends('layouts.auth.main')
@section('title', 'Login')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form id="form-login" action="{{ route('login.user.post') }}" method="POST">
                @csrf
                @method('POST')

                <div class="card">
                    <div class="card-header pb-0 mb-0">
                        <h1 class="fw-bold">Selamat Datang!</h1>
                        <p>Silahkan login! Jika belum memiliki akun maka akan dibuatkan otomatis.</p>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username Tiktok</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username tiktok kamu"
                                value="{{ old('username') }}">
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Whatsapp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Masukkan nomor Whatsapp kamu"
                                    value="{{ old('no_telp') }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary d-block w-100">
                            Lanjut
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script data-partial="1">
        $("#form-login").on("submit", function(e) {
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
                        window.location.href = res.redirect; // not SPA because of different layout
                    } else if (res.status === 'register_required') {
                        showToast('info', res.message, 2500);
                        loadPage(res.redirect);
                        history.pushState(null, null, res.redirect);
                    } else if (res.status === 'setpassword_required') {
                        showToast('info', res.message, 2500);
                        loadPage(res.redirect);
                        history.pushState(null, null, res.redirect);
                    } else {
                        showToast("error", res.message);
                        btnSubmit.prop("disabled", false).text("Lanjut");
                    }
                },
                error: function(xhr) {
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
