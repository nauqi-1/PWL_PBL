<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Level Pengguna</label>
                    <select name="level_id" id="level_id" class="form-control check-level" required>
                        <option value="">- Pilih Level -</option>
                        @foreach($level as $l)
                            <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input value="" type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group" id="admin-fields" style="display:none;">
                    <label>Nama</label>
                    <input value="" type="text" name="admin_nama" id="admin_nama" class="form-control" required>
                    <small id="error-admin_nama" class="error-text form-text text-danger"></small>
                    <label>Program Studi</label>
                    <input value="" type="text" name="admin_prodi" id="admin_prodi" class="form-control" required>
                    <small id="error-admin_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group" id="dosen-fields" style="display:none;">
                    <label>Nama</label>
                    <input value="" type="text" name="dosen_nama" id="dosen_nama" class="form-control" required>
                    <small id="error-dosen_nama" class="error-text form-text text-danger"></small>
                    <label>Program Studi</label>
                    <input value="" type="text" name="dosen_prodi" id="dosen_prodi" class="form-control" required>
                    <small id="error-dosen_prodi" class="error-text form-text text-danger"></small>
                    <label>Nomor Handphone</label>
                    <input value="" type="text" name="dosen_noHp" id="dosen_noHp" class="form-control" required>
                    <small id="error-dosen_noHp" class="error-text form-text text-danger"></small>
                    <label>Nomor Induk Pegawai</label>
                    <input value="" type="text" name="dosen_nip" id="dosen_nip" class="form-control" required>
                    <small id="error-dosen_nip" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    
    $(document).ready(function() {

        function updateLevelFields() {
        $(document).on('change', '.check-level', function() {
            let levelId = $(this).data('level_id');
            console.log(levelId);
            if (levelId == '1') { 
                $('#admin-fields').show();
                $('#dosen-fields').hide();

            } else if (levelId == '2') {
                $('#dosen-fields').show();
                $('#admin-fields').hide();

            } else {
                $('#admin-fields').hide();
                $('#dosen-fields').hide();
            }
        });
    }

        $("#form-tambah").validate({
            rules: {
                level_id: { required: true, number: true },
                username: { required: true, minlength: 3, maxlength: 20 },
                nama: { required: true, minlength: 3, maxlength: 100 },
                password: { required: true, minlength: 6, maxlength: 20 },
            },
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
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
