@extends('layouts.user.main')
@section('title', 'Detail Pesanan')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-sm d-flex align-items-center spa-link">
            <i class="bi bi-arrow-left-short fs-3"></i>
            <span class="fw-bold fs-5">Kembali</span>
        </a>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold fs-6">Pesanan: <mark>{{ $order->user->name }}</mark></h6>
                        @if ($order->status === 'pending')
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-sm btn-danger btn-cancel-order"
                                    data-url="{{ route('user.order.destroy', $order->id) }}">
                                    <i class='fe fe-x'></i>
                                    Batalkan
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fs-6 fst-italic">
                            Jenis pesanan: {{ $order->category->title }}
                        </h6>
                        <span class="badge text-bg-{{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <hr />

                <div class="card-body">
                    <div class="mb-3">
                        <span class="fw-bold">
                            {{ $order->status === 'rejected' ? 'Alasan Penolakan:' : 'Catatan:' }}
                        </span>
                        <p>
                            {{ $order->status === 'rejected' ? $order->reject_reason : $order->notes }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <span class="fw-bold">Files: </span>
                        <div class="row g-3">
                            @forelse ($order->files as $file)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card h-100">
                                        @php
                                            $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
                                        @endphp

                                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <a href="{{ asset('storage/' . $file->filename) }}" class="glightbox" data-gallery="files">
                                                <img src="{{ asset('storage/' . $file->filename) }}" class="card-img-top"
                                                    alt="{{ basename($file->filename) }}">
                                            </a>
                                        @elseif(in_array($ext, ['mp4', 'webm', 'ogg']))
                                            <a href="{{ asset('storage/' . $file->filename) }}" class="glightbox" data-gallery="files">
                                                <video class="card-img-top" controls>
                                                    <source src="{{ asset('storage/' . $file->filename) }}" type="video/{{ $ext }}">
                                                </video>
                                            </a>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                <i class="fe fe-file fs-1 text-secondary"></i>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title text-truncate" title="{{ basename($file->filename) }}">{{ basename($file->filename) }}
                                            </h6>
                                            <p class="card-text mb-1">
                                                Durasi: {{ $file->duration ?? 0 }} detik <br>
                                                Ukuran: {{ formatSize($file->size) }}
                                            </p>
                                            @if ($order->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-danger mt-auto btn-delete-file"
                                                    data-url="{{ route('user.order.file.remove', $file->id) }}">
                                                    <i class='fe fe-trash'></i> Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">File pesanan ini telah di hapus.</p>
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
        $(".btn-cancel-order").on("click", function(e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: "Apakah Anda yakin ingin membatalkan pesanan ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#adb5bd",
                confirmButtonText: "Ya, Batalkan",
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

        $(".btn-delete-file").on("click", function(e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus file ini?",
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
        })
    </script>
@endsection
