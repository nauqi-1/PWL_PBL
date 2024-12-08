@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <!-- Nav Pills -->
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" id="data-tugas-tab" data-toggle="pill" href="#data-tugas" role="tab" aria-controls="data-tugas" aria-selected="true">Daftar Tugas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="data-request-tab" data-toggle="pill" href="#data-request" role="tab" aria-controls="data-request" aria-selected="false">Status Request</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab for Data Tugas -->
            <div class="tab-pane fade show active" id="data-tugas" role="tabpanel" aria-labelledby="data-tugas-tab">
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_tugaskompen">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Tgl. dibuat</th>
                            <th>Tgl. ditutup</th>
                            <th>Pembuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Tab for Status Request -->
            <div class="tab-pane fade" id="data-request" role="tabpanel" aria-labelledby="data-request-tab">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <table class="table table-bordered table-striped table-hover table-sm" id="table_statusrequest">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Nama Tugas</th>
                            <th>Tgl. Request</th>
                            <th>Pembuat</th>
                            <th>Status</th>
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

    $(document).ready(function() {
        // DataTables for Tugas Kompensasi
        var tableTugasKompen = $('#table_tugaskompen').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('mhs_listtugas/list') }}",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    d.level_id = $('#level_id').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    width: '5%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tugas_tgl_dibuat",
                    render: function(data) {
                        return moment(data).format('DD MMMM YYYY HH:mm');
                    }
                },
                {
                    data: "tugas_tgl_deadline",
                    render: function(data) {
                        return moment(data).format('DD MMMM YYYY HH:mm');
                    }
                },
                {
                    data: "pembuat",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "aksi",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Reload DataTable when filter is changed
        $('#level_id').on('change', function() {
            tableTugasKompen.ajax.reload();
        });

        // DataTables for Status Request
        var tableStatusRequest = $('#table_statusrequest').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('mhs_listtugas/listrequest') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    width: '10%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: "mahasiswa.mahasiswa_nama",
                    width: '25%'
                },
                {
                    data: "tugas.tugas_nama",
                    className: "nama-tugas",
                    width: '20%',
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tgl_request",
                    width: '15%',
                    render: function(data) {
                        return moment(data).format('DD MMMM YYYY HH:mm');
                    }
                },
                {
                    data: "pembuat",
                    width: '15%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: "status_request",
                    orderable: false,
                    searchable: false,
                    width: '15%',
                    render: function(data, type, row) {
                switch (data) {
                    case 'rejected':
                        return '<span class="badge badge-danger">Ditolak</span>';
                    case 'pending':
                        return '<span class="badge badge-warning">Pending</span>';
                    case 'accepted':
                        return '<span class="badge badge-success">Diterima</span>';
                    default:
                        return '<span class="badge badge-secondary">Unknown</span>';
                }
            }
                }
            ]
        });
    });
</script>
@endpush
