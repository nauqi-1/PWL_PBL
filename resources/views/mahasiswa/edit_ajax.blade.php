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
                    <input value="{{ $mahasiswa->mahasiswa_alfa_lunas }}" type="number" name="mahasiswa_alfa_lunas" id="mahasiswa_alfa_lunas" class="form-control" required>
                    <small id="error-mahasiswa_alfa_lunas" class="error-text form-text text-danger"></small>
                </div> 
                </div>
                <hr>
                <small class="form-text text-muted">Abaikan jika tidak ingin mengubah username atau password.</small>

                <div class="form-group">
                    <label>Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" placeholder=" {{$mahasiswa->user->username}}" required>
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
                    mahasiswa_noHp: { required: true, maxlength: 20, digits: true },
                    mahasiswa_alfa_lunas: { required: true, maxlength: 100 },
                    username: {required: false, maxlength: 100 },
                    password: {required: false, minlength: 6, maxlength: 100 },
                },
                messages :
                {mahasiswa_nama : {
                    required: "Nama harus diisi.",
                    maxlength: "Data yang diisi tidak melebih 100 karakter."
                },
                mahasiswa_kelas: {
                    required: "Kelas harus diisi.",
                    minlength: "Kelas hanya terdiri dari 2 karakter.",
                    maxlength: "Kelas hanya terdiri dari 2 karakter."
                },
                mahasiswa_prodi: {
                    required: "Program studi harus diisi.",
                    minlength: "Program studi minimal 2 karakter.",
                    maxlength: "Program studi maksimal 10 karakter."
                },
                mahasiswa_noHp: {
                    required: "Nomor HP harus diisi.",
                    minlength: "Nomor HP minimal 10 karakter.",
                    maxlength: "Nomor HP maksimal 13 karakter.",
                    digits: "Nomor HP harus berupa angka saja."
                },
                mahasiswa_alfa_lunas: {
                    required: "Jumlah alfa lunas harus diisi.",
                    maxlength: "Jumlah alfa lunas maksimal 100 karakter.",
                    digits: "Jumlah alfa lunas harus berupa angka saja."
                },
                username: {
                    required: "Username harus diisi.",
                    maxlength: "Username maksimal 100 karakter."
                },
                password: {
                    required: "Password harus diisi.",
                    minlength: "Password minimal 6 karakter.",
                    maxlength: "Password maksimal 100 karakter."
                },},
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
