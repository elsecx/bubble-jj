@extends('layouts.auth.main')
@section('title', 'Register')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form id="form-register" action="{{ route('register.post') }}" method="POST">
                @csrf
                @method('POST')

                <div class="card">
                    <div class="card-header pb-0 mb-0">
                        <h1 class="fw-bold">Lanjutkan</h1>
                        <p>Lengkapi data untuk menggunakan aplikasi.</p>
                    </div>

                    <div class="card-body">
                        <input type="text" id="username" name="username" value="{{ request('username') }}" hidden>
                        <input type="text" id="no_telp" name="no_telp" value="{{ request('no_telp') }}" hidden>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email kamu"
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Password</label>
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
        $("#form-register").on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btnSubmit = form.find("button[type=submit]");

            btnSubmit.prop("disabled", true).text("Loading...");

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(res) {
                    if (res.status === "success") {
                        showToast("success", res.message);
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
                }
            });
        });
    </script>
@endsection
