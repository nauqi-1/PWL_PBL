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
                <a href="{{ url('/tugaskompen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/tugaskompen/' . $tugas->tugas_id . '/update_ajax') }}" method="POST" id="form-edit-tugas" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Tugas Kompensasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Tugas</label>
                        <input value="{{ $tugas->tugas_nama }}" type="text" name="tugas_nama" id="tugas_nama" class="form-control" required>
                        <small id="error-tugas_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <!-- Pembuat -->
                <div class="form-group">
                    <label>Pembuat</label>
                    <select name="tugas_pembuat_id" id="tugas_pembuat_id" class="form-control" required>
                        <option value="">-- Pilih Pembuat --</option>
                    </select>
                    <small id="error-tugas_pembuat_id" class="error-text form-text text-danger"></small>
                </div>                    <div class="form-group">
                        <label>Deskripsi Tugas</label>
                        <textarea name="tugas_desc" id="tugas_desc" class="form-control" rows="4" required>{{ $tugas->tugas_desc }}</textarea>
                        <small id="error-tugas_desc" class="error-text form-text text-danger"></small>
                    </div>
                    <!-- Kuota & Bobot -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Kuota</label>
                        <input value="{{ $tugas->kuota }}" type="number" name="kuota" id="kuota" class="form-control" required>
                        <small id="error-kuota" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <label>Bobot</label>
                        <input value="{{ $tugas->tugas_bobot }}" type="number" name="tugas_bobot" id="tugas_bobot" class="form-control" required>
                        <small id="error-tugas_bobot" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                    <!-- Jenis Tugas & Tanggal Deadline -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Jenis Tugas</label>
                        <select name="jenis_id" id="jenis_id" class="form-control" required>
                            <option value="">- Pilih Jenis Tugas -</option>
                            @foreach($jenisTugas as $jenis)
                                <option {{ $tugas->jenis_id == $jenis->jenis_id ? 'selected' : '' }} value="{{ $jenis->jenis_id }}">
                                    {{ $jenis->jenis_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-jenis_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Deadline</label>
                        <input type="text" name="tugas_tgl_deadline" id="tugas_tgl_deadline" class="form-control" value="{{ old('tugas_tgl_deadline', $tugas->tugas_tgl_deadline) }}" required>
                        <small id="error-tugas_tgl_deadline" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                    <!-- Kompetensi (Tags) -->
                <div class="form-group">
                    <label>Kompetensi</label>
                    <select name="kompetensi[]" id="kompetensi" class="form-control" multiple="multiple" required>
                        @foreach($kompetensi as $kompet)
                            <option value="{{ $kompet->kompetensi_id }}" {{ in_array($kompet->kompetensi_id, $tugas->kompetensi->pluck('kompetensi_id')->toArray()) ? 'selected' : '' }}>
                                {{ $kompet->kompetensi_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kompetensi" class="error-text form-text text-danger"></small>
                </div>
                    <div class="form-group">
                        <label>File Tugas</label>
                        <input type="file" name="tugas_file" id="tugas_file" class="form-control">
                        <!-- Jika file sudah ada, tampilkan nama file yang ada -->
                        @if($tugas->tugas_file)
                        <small>File Saat Ini: {{ $tugas->tugas_file }}</small>
                        @endif
                        <small id="error-tugas_file" class="error-text form-text text-danger"></small>
                        @if($tugas->tugas_file)
                            <a href="{{ asset('storage/' . $tugas->tugas_file) }}" target="_blank" class="d-block mt-2">Lihat file saat ini</a>
                        @endif
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
        // Initialize Choices.js for Pembuat
    const pembuatData = @json($pembuat);
    const pembuatSelect = new Choices('#tugas_pembuat_id', {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: '-- Pilih Pembuat --',
        shouldSort: false
    });

    pembuatSelect.setChoices(
        pembuatData.map(pembuat => ({
            value: pembuat.id,
            label: pembuat.nama
        })),
        'value',
        'label',
        true
    );

    // Initialize Choices.js for Kompetensi
    const kompetensiData = @json($kompetensi);
    const kompetensiSelect = new Choices('#kompetensi', {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: '-- Pilih Kompetensi --',
        shouldSort: false
    });

    kompetensiSelect.setChoices(
        kompetensiData.map(kompetensi => ({
            value: kompetensi.kompetensi_id,
            label: kompetensi.kompetensi_nama
        })),
        'value',
        'label',
        true
    );

    // Initialize Flatpickr for Deadline Date
    flatpickr("#tugas_tgl_deadline", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        allowInput: false
    });

    // Initialize Choices.js for Jenis Tugas
    const jenisTugasData = @json($jenisTugas);
    const jenisTugasSelect = new Choices('#jenis_id', {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: '-- Pilih Jenis Tugas --',
        shouldSort: false
    });

    jenisTugasSelect.setChoices(
        jenisTugasData.map(jenis => ({
            value: jenis.jenis_id,
            label: jenis.jenis_nama
        })),
        'value',
        'label',
        true
    );

    // Form Validation and Submission
    $("#form-edit-tugas").validate({
        rules: {
            tugas_file: {
                required: false,
                extension: "pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png|gif|mp4|avi|mkv|txt|zip"
            },
            tugas_nama: { required: true, maxlength: 255 },
            tugas_desc: { required: true },
            kuota: { required: true, min: 1 },
            tugas_pembuat_id: { required: true },
            tugas_bobot: { required: true },
            jenis_id: { required: true },
            tugas_tgl_deadline: { required: true },
            kompetensi: { required: true }
        },
        messages: {
            tugas_file: {
                extension: "File harus memiliki format yang valid (PDF, DOCX, JPG, PNG, dll)."
            }
        },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: new FormData(form),
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.error-text').text('');
                },
                success: function (response) {
                    if (response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: response.message
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            $('#' + key + '-error').text(value);
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Tidak dapat menyimpan data.'
                    });
                }
            });
        }
    });
    </script>
@endempty
