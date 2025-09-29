@extends('layouts.user.main')
@section('title', 'Upload Video Gratis')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-sm d-flex align-items-center spa-link">
            <i class="bi bi-arrow-left-short fs-3"></i>
            <span class="fw-bold fs-5">Kembali</span>
        </a>
    </div>

    <div class="row">
        <div class="card">
            <form id="form-upload" action="{{ route('user.upload.service', ['slug' => $menu->slug]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="card-header border-bottom">
                    <h4 class="fw-bold fs-3 lh-1">Upload {{ $menu->title }}</h4>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="file">Pilih File {{ $menu->title }}</label>
                        <input class="form-control form-control-sm" type="file" id="file" name="file" accept="video/*">
                        <div id="preview-container" class="d-flex flex-wrap mt-3"></div>
                    </div>
                    <div class="mb-3">
                        <label for="display_type">Pilih Jenis Tampil</label>
                        <select class="form-select form-select-sm" name="display_type" id="display_type">
                            <option selected disabled>=== Pilih Jenis Tampil JJ ===</option>
                            @foreach (\App\Models\Order::DISPLAY_TYPES as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <span>Biaya Upload:</span>
                        <h4 class="fw-bold text-success">{{ $menu->price }}</h4>
                    </div>
                    <div class="mb-3">
                        <h5 class="text-warning fw-bold">
                            <i class="ti ti-alert-circle"></i>
                            S&K (Syarat & Ketentuan):
                        </h5>
                        <ul>
                            <!--<li>Video harus berdurasi maksimal 60 detik.</li>-->
                            <li>Video harus berukuran maksimal 3MB.</li>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Upload sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/order.js') }}" data-partial="1"></script>
    <script data-partial="1">
        initUploadHandler({
            previewSelector: "#preview-container",
            previewType: "video",
            rules: {
                max_size: 5 * 1024 * 1024, // 5MB
                // max_duration: 60,
            },
        });
    </script>
@endsection
