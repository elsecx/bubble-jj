@extends('layouts.admin.main')
@section('title', 'Detail Pesanan')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold fs-5">Pesanan <mark>{{ $order->user->name }}</mark></h5>
                        @if ($order->status === 'pending')
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalReject">
                                    <i class='fe fe-x'></i>
                                    Tolak
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalResult">
                                    <i class='fe fe-upload'></i>
                                    Upload hasil
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fs-6">
                            <strong>Jenis pesanan</strong>: <mark class="fst-italic">{{ $order->category->title }}</mark>
                        </h6>
                        <span class="badge text-bg-{{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <h6 class="fs-6">
                        <strong>Jenis Tampil</strong>: <mark class="fst-italic">{{ $order->display_type_label }}</mark>
                    </h6>
                </div>

                <hr />

                <div class="card-body">
                    <div class="mb-3">
                        <span class="fw-bold">Catatan: </span>
                        <p>
                            {{ $order->notes }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <span class="fw-bold">Files: </span>
                        <div class="table-responsive">
                            <table id="table-order" class="table mb-0 table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Filename</th>
                                        <th>Durasi</th>
                                        <th>Ukuran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->files as $row => $file)
                                        <tr>
                                            <td>{{ $row }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $file->filename) }}" class="text-primary" target="_blank">
                                                    {{ basename($file->filename) }}
                                                </a>
                                            </td>
                                            <td>{{ $file->duration ?? 0 }}</td>
                                            <td>{{ $file->size }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $file->filename) }}" class="btn btn-sm btn-success" download>
                                                    <i class='fe fe-download'></i>
                                                    Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResult" tabindex="-1" aria-labelledby="modalResultLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-result" action="{{ route('admin.orders.result', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalResultLabel">Upload Hasil</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file_result_input">Video JJ</label>
                            <input type="file" class="form-control" name="file_result" id="file_result_input" accept="video/*">
                        </div>
                        <div class="mb-3">
                            <label for="proof_payment">Bukti Transfer</label>
                            <input type="file" class="form-control" name="proof_payment" id="proof_payment" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-reject" action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalRejectLabel">Alasan Ditolak</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="reject_reason">Alasan</label>
                        <textarea class="form-control" id="reject_reason" name="reject_reason" rows="3" placeholder="Masukkan alasan disini..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script data-partial="1">
        $("#form-result").on("submit", function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = new FormData(this);

            const modalEl = document.getElementById("modalResult");
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

            let btnSubmit = form.find("button[type=submit]");
            btnSubmit.prop("disabled", true).text("Loading...");

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        showToast('success', res.message);

                        modal.hide();

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

        $("#form-reject").on("submit", function(e) {
            e.preventDefault();

            let form = $(this);

            const modalEl = document.getElementById("modalReject");
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

            let btnSubmit = form.find("button[type=submit]");
            btnSubmit.prop("disabled", true).text("Loading...");

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        showToast('success', res.message);

                        modal.hide();

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
