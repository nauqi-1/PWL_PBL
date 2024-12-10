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
    <form action="{{ url('/user/' . $user->user_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(Auth::user()->level->level_kode == 'ADM')
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{$user->admin->admin_nama}}" type="text" name="admin_nama" id="admin_nama" class="form-control" required>
                        <small id="error-admin_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input value="{{ $user->admin->admin_prodi }}" type="text" name="admin_prodi" id="admin_prodi" class="form-control" required>
                        <small id="error-admin_prodi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No. Handphone</label>
                        <input value="{{ $user->admin->admin_noHp }}" type="text" name="admin_noHp" id="admin_noHp" class="form-control" required>
                        <small id="error-admin_noHp" class="error-text form-text text-danger"></small>
                    </div>
                    @elseif(Auth::user()->level->level_kode == 'DSN')
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{$user->dosen->dosen_nama}}" type="text" name="dosen_nama" id="dosen_nama" class="form-control" required disabled>
                        <small id="error-dosen_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>NIP</label>
                        <input value="{{$user->dosen->dosen_nip}}" type="text" name="dosen_nip" id="dosen_nip" class="form-control" required disabled>
                        <small id="error-dosen_nip" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input value="{{ $user->dosen->dosen_prodi }}" type="text" name="dosen_prodi" id="dosen_prodi" class="form-control" required> 
                        <small id="error-dosen_prodi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No. Handphone</label>
                        <input value="{{ $user->dosen->dosen_noHp }}" type="text" name="dosen_noHp" id="dosen_noHp" class="form-control" required>
                        <small id="error-dosen_noHp" class="error-text form-text text-danger"></small>
                    </div>
                    @elseif(Auth::user()->level->level_kode == 'TDK')
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{$user->tendik->tendik_nama}}" type="text" name="tendik_nama" id="tendik_nama" class="form-control" required disabled>
                        <small id="error-tendik_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>NIP</label>
                        <input value="{{$user->tendik->tendik_nip}}" type="text" name="tendik_nip" id="tendik_nip" class="form-control" required disabled>
                        <small id="error-tendik_nip" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No. Handphone</label>
                        <input value="{{ $user->tendik->tendik_noHp }}" type="text" name="tendik_noHp" id="tendik_noHp" class="form-control" required>
                        <small id="error-tendik_noHp" class="error-text form-text text-danger"></small>
                    </div>
                    @elseif(Auth::user()->level->level_kode == 'MHS')
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{$user->mahasiswa->mahasiswa_nama}}" type="text" name="mahasiswa_nama" id="mahasiswa_nama" class="form-control" required disabled>
                        <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input value="{{$user->mahasiswa->mahasiswa_nim}}" type="text" name="mahasiswa_nim" id="mahasiswa_nip" class="form-control" required disabled>
                        <small id="error-mahasiswa_nim" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                    <label>Program Studi</label>
                    <input value="{{$user->mahasiswa->mahasiswa_prodi}}" type="text" name="mahasiswa_prodi" id="mahasiswa_prodi" class="form-control" required disabled>
                    <small id="error-mahasiswa_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label>Kelas</label>
                    <input value="{{$user->mahasiswa->mahasiswa_kelas}}" type="text" name="mahasiswa_kelas" id="mahasiswa_kelas" class="form-control" required disabled>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                
                </div>
                    <div class="form-group">
                        <label>No. Handphone</label>
                        <input value="{{ $user->mahasiswa->mahasiswa_noHp }}" type="text" name="mahasiswa_noHp" id="mahasiswa_noHp" class="form-control" required>
                        <small id="error-mahasiswa_noHp" class="error-text form-text text-danger"></small>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Username</label>
                        <input value="{{ $user->username }}" type="text" name="username" id="username" class="form-control" required disabled>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input value="" type="password" name="password" id="password" class="form-control">
                        <small class="form-text text-muted">Abaikan jika tidak ingin mengubah password</small>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>
                    <!-- LevelKode -->
                    <input type="hidden" id="level_kode" value="{{ Auth::user()->level->level_kode }}">
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
            const commonNoHpMessages = {
    required: "Nomor HP harus diisi.",
    minlength: "Nomor HP minimal 10 karakter.",
    maxlength: "Nomor HP maksimal 13 karakter.",
    digits: "Nomor HP harus berupa angka saja."
};
    const validator = $("#form-edit").validate({
        rules: {
            username: { required: true, minlength: 3, maxlength: 20 },
            password: { minlength: 6, maxlength: 20 }
        }, 
        

messages: {
    admin_noHp: commonNoHpMessages,
    dosen_noHp: commonNoHpMessages,
    tendik_noHp: commonNoHpMessages,
    mahasiswa_noHp: commonNoHpMessages,
    admin_nama: {
        required: "Nama harus diisi.",
        minlength: "Nama minimal 3 karakter.",
        maxlength: "Nama maksimal 100 karakter."
    },
    admin_prodi: {
        required: "Program studi harus diisi.",
        minlength: "Program studi minimal 2 karakter.",
        maxlength: "Program Studi maksimal 10 karakter."
    },
    password: {
        required: "Password harus diisi.",
        minlength: "Password minimal 6 karakter.",
        maxlength: "Password maksimal 100 karakter."
    },
    username: {
        required: "Username harus diisi.",
        maxlength: "Username maksimal 100 karakter."
    }
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
            return false; // Prevent default form submission
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

    /**
     * Function to apply validation rules dynamically 
     * based on the value of #level_kode
     */
    function applyDynamicRules(levelKode) {
        // Clear all existing dynamic rules
        $('#admin_nama, #admin_prodi, #admin_noHp, #dosen_nama, #dosen_prodi, #dosen_noHp, #tendik_nama, #tendik_noHp, #mahasiswa_noHp').each(function() {
            $(this).rules('remove');
        });

        if (levelKode === 'ADM') {
            $("#admin_nama").rules('add', {
                required: true,
                minlength: 3,
                maxlength: 100
            });
            $("#admin_prodi").rules('add', {
                required: true,
                minlength: 2,
                maxlength: 50
            });
            $("#admin_noHp").rules('add', {
                required: true,
                minlength: 10,
                maxlength: 13,
                digits: true
            });
        } else if (levelKode === 'DSN') {
            $("#dosen_nama").rules('add', {
                required: true,
                minlength: 3,
                maxlength: 100
            });
            $("#dosen_prodi").rules('add', {
                required: true,
                minlength: 2,
                maxlength: 50
            });
            $("#dosen_noHp").rules('add', {
                required: true,
                minlength: 10,
                maxlength: 13,
                digits: true
            });
        } else if (levelKode === 'TDK') {
            $("#tendik_nama").rules('add', {
                required: true,
                minlength: 3,
                maxlength: 100
            });
            $("#tendik_noHp").rules('add', {
                required: true,
                minlength: 10,
                maxlength: 13,
                digits: true
            });
        } else if (levelKode === 'MHS') {
            $("#mahasiswa_noHp").rules('add', {
                required: true,
                minlength: 10,
                maxlength: 13,
                digits: true
            });
        }
    }

    // Call the function on page load to apply initial rules
    const initialLevelKode = $('#level_kode').val();
    applyDynamicRules(initialLevelKode);

    // Update validation rules if level code changes
    $('#level_kode').on('change', function() {
        const newLevelKode = $(this).val();
        applyDynamicRules(newLevelKode);
    });
});

    </script>
@endempty
