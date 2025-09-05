@extends('layouts.admin.main')
@section('title', 'Detail Pesanan')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold fs-5">Pesanan <mark>{{ $order->user->profile->username_1 }}</mark></h5>
                        <div class="d-flex-align-items-center gap-3">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalReject">
                                <i class='fe fe-x'></i>
                                Tolak
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadResult">
                                <i class='fe fe-upload'></i>
                                Upload hasil
                            </button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fs-6 fst-italic">
                            Jenis pesanan: {{ $order->category->title }}
                        </h6>
                        @if ($order->status == 'pending')
                            <span class="badge bg-warning">{{ ucfirst($order->status) }}
                            @elseif ($order->status == 'approved')
                                <span class="badge bg-success">{{ ucfirst($order->status) }}
                                @elseif ($order->status == 'rejected')
                                    <span class="badge bg-danger">{{ ucfirst($order->status) }}
                        @endif
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
                                                <a href="{{ asset('storage/orders/photos/' . $file->filename) }}" target="_blank"
                                                    class="btn btn-sm btn-success">
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

@section('scripts')
    <script data-partial="1">
        // 
    </script>
@endsection
