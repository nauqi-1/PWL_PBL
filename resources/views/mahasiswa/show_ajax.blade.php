@empty($mahasiswa)
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
                Data yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/mahasiswa') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">NIM :</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_nim }}</td>
                    </tr>
                    
                    <tr>
                        <th class="text-right col-3">Nama:</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kelas:</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_kelas }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Program Studi:</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nomor Handphone:</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_noHp }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jam Alfa Lunas:</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_alfa_sisa }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jam Alfa Total:</th>
                        <td class="col-9">{{ $mahasiswa->mahasiswa_alfa_total }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status:</th>
                        <td class="col-9" style="background-color: {{ $mahasiswa->mahasiswa_alfa_sisa < $mahasiswa->mahasiswa_alfa_total ? 'red' : 'green' }}; color: white;"> 
                            @if($mahasiswa->mahasiswa_alfa_sisa < $mahasiswa->mahasiswa_alfa_total)
                                Belum lunas, tidak diperbolehkan mengikuti UAS
                            @else
                                Sudah lunas, mahasiswa dipersilahkan mengikuti UAS
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
                        dataMahasiswa.ajax.reload();
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
