<form action="{{ url('/mahasiswa/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label>NIM</label>
                    <input value="" type="text" name="mahasiswa_nim" id="mahasiswa_nim" class="form-control" required>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input value="" type="text" name="mahasiswa_nama" id="mahasiswa_nama" class="form-control" required>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Kelas</label>
                    <input value="" type="text" name="mahasiswa_kelas" id="mahasiswa_kelas" class="form-control" required>
                    <small class="form-text text-muted">Contoh: 1A, 2B, 3C</small>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label>Program Studi</label>
                    <input value="" type="text" name="mahasiswa_prodi" id="mahasiswa_prodi" class="form-control" required>
                    <small id="error-mahasiswa_prodi" class="error-text form-text text-danger"></small>
                </div>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input value="" type="text" name="mahasiswa_noHp" id="mahasiswa_noHp" class="form-control" required>
                    <small id="error-mahasiswa_noHp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Jam alfa lunas</label>
                    <input value="" type="number" name="mahasiswa_alfa_lunas" id="mahasiswa_alfa_lunas" class="form-control" required>
                    <small id="error-mahasiswa_alfa_lunas" class="error-text form-text text-danger"></small>
                </div>
                </div>
                <hr>
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
        $("#form-tambah").validate({
            rules: {
                
                mahasiswa_nama: { required: true, maxlength: 100 },
                mahasiswa_nim: { number, required: true, maxlength: 50 },
                mahasiswa_kelas: { required: true, minlength: 2, maxlength: 2 },
                mahasiswa_prodi: { required: true, minlength: 2, maxlength: 10 },
                mahasiswa_noHp: { number, required: true, maxlength: 20 },
                mahasiswa_alfa_lunas: { required: true, maxlength: 100 },
                username: {required: true, maxlength: 100 },
                password: {required: true, minlength: 6, maxlength: 100 },

                
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
