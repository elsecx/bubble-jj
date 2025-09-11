@extends('layouts.admin.main')
@section('title', 'Edit Operator')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form id="form-edit" action="{{ route('admin.operators.update', $operator->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $operator->name }}">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="{{ $operator->email }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Isi jika ingin mengganti password">
                                <button class="btn btn-light" type="button" onclick="togglePassword('password')">
                                    <i class='fa fa-eye'></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                <button class="btn btn-light" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class='fa fa-eye'></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.operators.index') }}" class="btn btn-secondary spa-link">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script data-partial="1">
        $("#form-edit").on("submit", function(e) {
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
