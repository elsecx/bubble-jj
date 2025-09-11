@extends('layouts.admin.main')
@section('title', 'Detail ' . $operator->name)

@section('content')
    <div class="row">
        {{-- Informasi Akun --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header pb-0 mb-0">
                    <h4 class="card-title mb-0">Informasi Akun</h4>
                    <small class="text-muted">
                        Dibuat pada {{ formatDate($operator->created_at) }} -
                        Terakhir diperbarui {{ formatDate($operator->updated_at) }}
                    </small>
                </div>
                <div class="card-body pt-2">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="mb-0 mark">{{ $operator->name }}</h3>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Email/Username:</strong>
                            <span class="float-end">{{ $operator->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Role:</strong>
                            <span class="float-end">{{ $operator->role->label }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Status Akun:</strong>
                            <span class="float-end">
                                <span class="badge text-bg-{{ $operator->status_color }}">
                                    {{ $operator->status_label }}
                                </span>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <strong>Email Terverifikasi:</strong>
                            <span class="float-end">
                                {{ $operator->email_verified_at ? formatDate($operator->email_verified_at) : 'Belum' }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('admin.operators.edit', $operator->id) }}" class="btn btn-sm btn-primary spa-link">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <button class="btn btn-sm btn-danger btn-delete" data-url="{{ route('admin.operators.destroy', $operator->id) }}">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- Ruang Tambahan (misalnya log aktivitas, statistik, dsb.) --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Belum ada data aktivitas untuk operator ini.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script data-partial="1">
        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus operator ini?",
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

                                loadPage(res.redirect);
                                history.pushState(null, null, res.redirect);
                            } else {
                                showToast("success", res.message);
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
                    })
                }
            });
        });
    </script>
@endsection
