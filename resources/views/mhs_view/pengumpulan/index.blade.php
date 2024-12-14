@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <!-- Nav Pills -->
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" id="data-pengumpulan-tab" data-toggle="pill" href="#data-pengumpulan" role="tab" aria-controls="data-pengumpulan" aria-selected="true">Pengumpulan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="data-status-pengumpulan-tab" data-toggle="pill" href="#data-status-pengumpulan" role="tab" aria-controls="data-status-pengumpulan" aria-selected="false">Status Pengumpulan</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab for Data Tugas -->
            <div class="tab-pane fade show active" id="data-pengumpulan" role="tabpanel" aria-labelledby="data-pengumpulan-tab">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-1 control-labe; col-form-label">Filter:</label>
                            <div class="col-3">
                                <select class="form-control" id="level_id" name="level_id" required>
                                    <option value="">- Semua -</option>
                                    @foreach ($level as $item)
                                    @if ($item->level_nama != 'Mahasiswa') {{-- Kondisi pengecualian --}}
                                        <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Level Pembuat</small>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover table-sm" id="table_pengumpulan">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tugas</th>
                            <th>Tenggat</th>
                            <th>Progres</th>
                            <th>Pembuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Tab for Status Request -->
            <div class="tab-pane fade" id="data-status-pengumpulan" role="tabpanel" aria-labelledby="data-status-pengumpulan-tab">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <table class="table table-bordered table-striped table-hover table-sm" id="table_statuspengumpulan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tugas</th>
                            <th>Tenggat</th>
                            <th>Tgl. Dikerjakan</th>
                            <th>Pembuat</th>
                            <th>Status</th>
                            <th>Cetak</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var tablePengumpulan;
    var tableStatusPengumpulan;
    $(document).ready(function() {
        // DataTables for Tugas Kompensasi
        tablePengumpulan = $('#table_pengumpulan').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('mhs_kumpultugas/list') }}",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    d.level_id = $('#level_id').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center", 
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas.tugas_nama",
                    className: "", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tugas.tugas_tgl_deadline",
                    className: "", 
                    render: function(data) {
                        return moment(data).format('DD MMMM YYYY HH:mm');
                    }
                },
                {
                data: "progress",
                className: "progress-column",
                render: function(data, type, row) {
                    // Pastikan nilai data tidak null, gunakan default 0 jika null
                    let progressValue = data !== null ? data : 0;

                    return `
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-green" role="progressbar" aria-valuenow="${progressValue}" aria-valuemin="0" aria-valuemax="100" style="width: ${progressValue}%">
                                </div>
                            </div>
                            <small>
                                ${progressValue}% Selesai
                            </small>
                        </td>`;
                }
                },
                {
                    data: "pembuat",
                    className: "", 
                    orderable: false,
                    searchable: false
                },
                {
                    data: "aksi",
                    className: "", 
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Reload DataTable when filter is changed
        $('#level_id').on('change', function() {
            console.log('Filter changed');
            tablePengumpulan.ajax.reload(); // Memanggil reload pada DataTable
        });

    });
    function updateProgress(tugasMahasiswaId, value) {
    $.ajax({
        url: `{{ url('mhs_kumpultugas/update-progress') }}/${tugasMahasiswaId}`,
        type: 'PUT',
        data: {
            progress: value,
            _token: '{{ csrf_token() }}' // CSRF token untuk keamanan
        },
        success: function(response) {
            alert('Progress berhasil diperbarui!');
        },
        error: function(xhr) {
            alert('Terjadi kesalahan. Coba lagi.');
        }
    });
    }

    // DataTables for Status Request
    $(document).ready(function() {
    tableStatusPengumpulan = $('#table_statuspengumpulan').DataTable({
        serverSide: true,
        autoWidth: false,  // Disable auto width calculation by DataTables
        responsive: true, 
        ajax: {
            url: "{{ url('mhs_kumpultugas/liststatuspengumpulan') }}",
            type: "POST", 
            dataType: "json",
        },
        columns: [
            {
                data: "DT_RowIndex",
                className: "", 
                orderable: false,
                searchable: false
            },
            {
                data: "tugas.tugas_nama",
                className: "nama-tugas",
                orderable: true,
                searchable: true
            },
            {
                data: "tugas.tugas_tgl_deadline",
                className: "", 
                render: function(data) {
                    return moment(data).format('DD MMMM YYYY HH:mm');
                }
            },
            {
                data: "tanggal_disubmit",
                className: "", 
                render: function(data) {
                    return moment(data).format('DD MMMM YYYY HH:mm');
                }
            },
            {
                data: "pembuat",
                className: "", 
                orderable: false,
                searchable: false
            },
            {
                data: "tugas.tugas_status",
                className: "", 
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    switch (data) {
                        case 'S':
                            return '<span class="badge badge-warning">Pending</span>';
                            case 'D':
                                return '<span class="badge badge-success">Sukses</span>';
                                case 'F':
                            return '<span class="badge badge-danger">Ditolak</span>';
                        default:
                            return '<span class="badge badge-secondary">Unknown</span>';
                    }
                }
            },
            {
                data: "cetak",
                className: "", 
                orderable: false,
                searchable: false
            }
        ]
    });
    });
</script>
@endpush
