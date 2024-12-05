@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
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
                            @if ($item->level_nama != 'Mahasiswa') {{-- Kondisi pengeculian --}}
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
                    <th>Tgl.dibuat</th>
                    <th>Tgl.ditutup</th>
                    <th>Pembuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
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

    var tableTugasKompen;

    $(document).ready(function() {
        tableTugasKompen = $('#table_tugaskompen').DataTable({
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
            className: "",
            width: '5%',
            orderable: false,
            searchable: false
        },
        {
            data: "tugas_nama",
            className: "nama-tugas",
            orderable: true,
            searchable: true
        },
        {
            data: "tugas_tgl_dibuat",
            className: "",
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return moment(data).format('DD MMMM YYYY HH:mm');
            }
        },
        {
            data: "tugas_tgl_deadline",
            className: "",
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
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
            data: "aksi",
            className: "",
            orderable: false,
            searchable: false,
            width: '160px'
        }
    ]
});

// Reload the DataTable when the filter is changed
$('#level_id').on('change', function() {
    tableTugasKompen.ajax.reload();
});

        });
</script>
@endpush