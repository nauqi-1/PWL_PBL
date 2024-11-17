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
    <form action="{{ url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input value="{{ $mahasiswa->mahasiswa_nama }}" type="text" name="mahasiswa_nama" id="mahasiswa_nama" class="form-control" required>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Kelas</label>
                    <input value="{{ $mahasiswa->mahasiswa_kelas }}" type="text" name="mahasiswa_kelas" id="mahasiswa_kelas" class="form-control" required>
                    <small class="form-text text-muted">Contoh: 1A, 2B, 3C</small>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label>Program Studi</label>
                    <input value="{{ $mahasiswa->mahasiswa_prodi }}" type="text" name="mahasiswa_prodi" id="mahasiswa_prodi" class="form-control" required>
                    <small id="error-mahasiswa_prodi" class="error-text form-text text-danger"></small>
                </div>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input value="{{ $mahasiswa->mahasiswa_noHp }}" type="text" name="mahasiswa_noHp" id="mahasiswa_noHp" class="form-control" required>
                    <small id="error-mahasiswa_noHp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Jam alfa lunas</label>
                    <input value="{{ $mahasiswa->mahasiswa_alfa_sisa }}" type="number" name="mahasiswa_alfa_sisa" id="mahasiswa_alfa_sisa" class="form-control" required>
                    <small id="error-mahasiswa_alfa_sisa" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label>Jam alfa total</label>
                    <input value="{{ $mahasiswa->mahasiswa_alfa_total }}" type="number" name="mahasiswa_alfa_total" id="mahasiswa_alfa_total" class="form-control" required>
                    <small id="error-mahasiswa_alfa_total" class="error-text form-text text-danger"></small>
                </div>
                </div>
                <hr>
                <small class="form-text text-muted">Abaikan jika tidak ingin mengubah username atau password.</small>

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
            $("#form-edit").validate({
                rules: {
                    mahasiswa_nama: { required: true, maxlength: 100 },
                    mahasiswa_kelas: { required: true, minlength: 2, maxlength: 2 },
                    mahasiswa_prodi: { required: true, minlength: 2, maxlength: 10 },
                    mahasiswa_noHp: { required: true, maxlength: 20 },
                    mahasiswa_alfa_sisa: { required: true, maxlength: 100 },
                    mahasiswa_alfa_total: { required: true, maxlength: 100 },
                    username: {required: false, maxlength: 100 },
                    password: {required: false, minlength: 6, maxlength: 100 },
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
                                dataMahasiswa.ajax.reload(); // memanggil dataMahasiswa utk instant reload
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
@endempty