@extends('layouts.auth.main')
@section('title', 'Login')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form id="form-login" action="{{ route('login.admin.post') }}" method="POST">
                @csrf
                @method('POST')

                <div class="card">
                    <div class="card-header pb-0 mb-0">
                        <h1 class="fw-bold">Halo Admin</h1>
                        <p>Silahkan login untuk melanjutkan.</p>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Username</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email anda"
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password kamu"
                                    value="{{ old('password') }}">
                                <button class="btn btn-light" type="button" onclick="togglePassword('password')">
                                    <i class='fa fa-eye'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary d-block w-100">
                            Login
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
                    } else {
                        showToast("error", res.message);
                        btnSubmit.prop("disabled", false).text("Login");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        showToast('error', errors || "Data yang dimasukkan tidak valid");
                    } else {
                        showToast('error', xhr.responseJSON?.message || "Terjadi kesalahan, coba lagi.");
                    }

                    btnSubmit.prop("disabled", false).text("Login");
                },
            });
        });
    </script>
@endsection
