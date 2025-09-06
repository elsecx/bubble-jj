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
                        <h5 class="fw-bold fs-5">Pesanan <mark>{{ $order->user->name }}</mark></h5>
                        @if ($order->status === 'pending')
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-danger">
                                    <i class='fe fe-x'></i>
                                    Batal
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
                                            <td>{{ basename($file->filename) }}</td>
                                            <td>{{ $file->duration ?? 0 }}</td>
                                            <td>{{ $file->size }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $file->filename) }}" target="_blank" class="btn btn-sm btn-success">
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
@endsection
