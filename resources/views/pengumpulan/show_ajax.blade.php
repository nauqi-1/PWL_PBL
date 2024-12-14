@empty($tugas)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data tugas yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/pengumpulan_tugas') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/pengumpulan_tugas/' . $tugas->tugas_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengumpulan Tugas Kompen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Tugas :</th>
                        <td class="col-9">{{ $tugas->tugas->tugas_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Tugas :</th>
                        <td class="col-9">{{ $tugas->tugas->jenis->jenis_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">
                            @switch($tugas->tugas->tugas_status)
                                @case('W')
                                    <span class="badge badge-warning">Working</span>
                                    @break
                                @case('S')
                                    <span class="badge badge-primary">Submitted</span>
                                    @break
                                @case('D')
                                    <span class="badge badge-success">Done</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">Unknown</span>
                            @endswitch
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="text-right col-3">Tanggal Deadline :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($tugas->tugas->tugas_tgl_deadline)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Mahasiswa :</th>
                        <td class="col-9">{{ $tugas->mahasiswa->mahasiswa_nama}}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Progres :</th>
                        <td class="col-9 project_progress">
                            <div class="progress progress-sm" style="width: 50%;">
                                <div class="progress-bar bg-green" role="progressbar" 
                                     aria-valuenow="{{ $tugas->progress }}" 
                                     aria-valuemin="0" aria-valuemax="100" 
                                     style="width: {{ $tugas->progress }}%">
                                </div>
                            </div>
                            <small>
                                {{ $tugas->progress }}% Selesai
                            </small>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi Progres :</th>
                        <td class="col-9">
                            @if ($tugas->progress_deskripsi)
                                {{ $tugas->progress_deskripsi }}
                            @else
                                Tidak ada deskripsi progres
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Dikumpulkan :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($tugas->tanggal_disubmit)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">File Tugas :</th>
                            <td class="col-9">
                                @if ($tugas->file_path)
                                    <a href="{{ url('storage/' . $tugas->file_path) }}" target="_blank">
                                        {{ basename($tugas->file_path) }}
                                    </a>
                                @else
                                    Tidak ada file
                                @endif
                            </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    $("#form-delete").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataTugasKompen.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
