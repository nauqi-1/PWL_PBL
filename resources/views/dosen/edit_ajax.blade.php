@empty($dosen)
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
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/dosen/' . $dosen->dosen_id . '/update_ajax') }}" method="POST" id="form-edit">
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
                    <input value="{{ $dosen->dosen_nama }}" type="text" name="dosen_nama" id="dosen_nama" class="form-control" required>
                    <small id="error-dosen_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <input value="{{ $dosen->dosen_prodi }}" type="text" name="dosen_prodi" id="dosen_prodi" class="form-control" required>
                    <small id="error-dosen_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input value="{{ $dosen->dosen_noHp }}" type="text" name="dosen_noHp" id="dosen_noHp" class="form-control" required>
                    <small id="error-dosen_noHp" class="error-text form-text text-danger"></small>
                </div>
                
                <hr>
                <small class="form-text text-muted">Abaikan jika tidak ingin mengubah username atau password.</small>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" placeholder=" {{$dosen->user->username}}" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group col-md-6">
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
                    dosen_nama: { required: true, maxlength: 100 },
                    dosen_prodi: { required: true, minlength: 2, maxlength: 10 },
                    dosen_noHp: { required: true, maxlength: 20 },
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
