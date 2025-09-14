@extends('layouts.admin.main')
@section('title', 'Data Admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="mb-3 row">
                        <div class="gap-3 col d-flex align-items-center">
                            <a href="{{ route('admin.operators.create') }}" class="btn btn-primary d-flex align-items-center spa-link">
                                <i class="me-2 ti ti-user-plus"></i>
                                Tambah
                            </a>
                            <button type="button" id="refresh-btn" class="btn btn-success d-flex align-items-center">
                                <i class="me-2 ti ti-rotate"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col d-flex align-items-center gap-3">
                            <div>
                                <label for="filter-status" class="form-label">Filter Status</label>
                                <select id="filter-status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="operators-table" class="table mb-0 table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Tanggal dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('templates/libs/datatables/datatables.min.js') }}" data-partial="1"></script>
    <script data-partial="1">
        window.operatorsTable = window.operatorsTable || null;

        function initOperatorsTable() {
            if ($.fn.DataTable.isDataTable('#operators-table')) {
                $('#operators-table').DataTable().destroy();
            }

            window.operatorsTable = $('#operators-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.operators.data') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                    },
                    error: function(xhr) {
                        if (xhr.status === 401 || xhr.status === 419) {
                            showToast("error", "Sesi login habis, silakan login ulang!");
                            window.location.href = "{{ route('login') }}";
                        }
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ], // default order
                pageLength: 5,
                lengthMenu: [
                    [5, 15, 30, 50, 75, 100],
                    [5, 15, 30, 50, 75, 100]
                ],
                language: {
                    url: "{{ asset('vendor/js/i18n/id.json') }}"
                }
            });
        }

        initOperatorsTable();

        // Refresh table
        $("#refresh-btn").on('click', function() {
            window.operatorsTable.ajax.reload();
        });

        // Filter status
        $('#filter-status').on('change', function() {
            window.operatorsTable.ajax.reload();
        });
    </script>
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
