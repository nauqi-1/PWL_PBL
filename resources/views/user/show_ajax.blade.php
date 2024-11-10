@empty($user)
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
            <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/user/' . $user->user_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID User :</th>
                        <td class="col-9">{{ $user->user_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level Pengguna :</th>
                        <td class="col-9">{{ $user->level->level_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username :</th>
                        <td class="col-9">{{ $user->username }}</td>
                    </tr>
                    
                    @if($user->level_id == 1)
                    <tr>
                        <th class="text-right col-3">Nama :</th>
                        <td class="col-9">{{ $user->admin->admin_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Program Studi :</th>
                        <td class="col-9">{{ $user->admin->admin_prodi }}</td>
                    </tr>
                    @elseif($user->level_id == 2)
                    <tr>
                        <th class="text-right col-3">Nama :</th>
                        <td class="col-9">{{ $user->dosen->dosen_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Program Studi :</th>
                        <td class="col-9">{{ $user->dosen->dosen_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nomor HP :</th>
                        <td class="col-9">{{ $user->dosen->dosen_noHp }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nomor Induk Pegawai :</th>
                        <td class="col-9">{{ $user->dosen->dosen_nip }}</td>
                    </tr>
                    @elseif($user->level_id == 3)
                    <tr>
                        <th class="text-right col-3">Nama :</th>
                        <td class="col-9">{{ $user->tendik->tendik_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nomor HP :</th>
                        <td class="col-9">{{ $user->tendik->tendik_noHp }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nomor Induk Pegawai :</th>
                        <td class="col-9">{{ $user->tendik->tendik_nip }}</td>
                    </tr>
                    @elseif($user->level_id == 4)
                    <tr>
                        <th class="text-right col-3">Nama :</th>
                        <td class="col-9">{{ $user->mahasiswa->mahasiswa_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kelas :</th>
                        <td class="col-9">{{ $user->mahasiswa->mahasiswa_nim }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Program Studi :</th>
                        <td class="col-9">{{ $user->mahasiswa->mahasiswa_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nomor HP :</th>
                        <td class="col-9">{{ $user->mahasiswa->mahasiswa_noHp }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jumlah Alfa Total :</th>
                        <td class="col-9">{{ $user->mahasiswa->mahasiswa_alfa_total }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jumlah Alfa Terbayar :</th>
                        <td class="col-9">{{ $user->mahasiswa->mahasiswa_alfa_sisa }}</td>
                    </tr>
                    @endif
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
                        dataUser.ajax.reload();
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
