<form action="{{ url('/tugaskompen/store_ajax') }}" method="POST" id="form-tambah-tugas" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Tugas Kompensasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Tugas -->
                <div class="form-group">
                    <label>Nama Tugas</label>
                    <input type="text" name="tugas_nama" id="tugas_nama" class="form-control" required>
                    <small id="error-tugas_nama" class="error-text form-text text-danger"></small>
                </div>

                <!-- Pembuat -->
                <div class="form-group">
                    <label>Pembuat</label>
                    <select name="tugas_pembuat_id" id="tugas_pembuat_id" class="form-control" required>
                        <option value="">-- Pilih Pembuat --</option>
                    </select>
                    <small id="error-tugas_pembuat_id" class="error-text form-text text-danger"></small>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="tugas_desc" id="tugas_desc" class="form-control" rows="3" required></textarea>
                    <small id="error-tugas_desc" class="error-text form-text text-danger"></small>
                </div>

                <!-- Kuota & Bobot -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Kuota</label>
                        <input type="number" name="kuota" id="kuota" class="form-control" required>
                        <small id="error-kuota" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <label>Bobot</label>
                        <input type="number" name="tugas_bobot" id="tugas_bobot" class="form-control" required>
                        <small id="error-tugas_bobot" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <!-- Jenis Tugas & Tanggal Deadline -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Jenis Tugas</label>
                        <select name="jenis_id" id="jenis_id" class="form-control" required>
                            <option value="">-- Pilih Jenis Tugas --</option>
                        </select>
                        <small id="error-jenis_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <label>Tgl. Ditutup</label>
                        <input type="text" name="tugas_tgl_deadline" id="tugas_tgl_deadline" class="form-control" required>
                        <small id="error-tugas_tgl_deadline" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <!-- Kompetensi (Tags) -->
                <div class="form-group">
                    <label>Kompetensi</label>
                    <select name="kompetensi[]" id="kompetensi" class="form-control" multiple="multiple" required>
                        <!-- Kompetensi options will be added dynamically -->
                    </select>
                    <small id="error-kompetensi" class="error-text form-text text-danger"></small>
                </div>

                <!-- File -->
                <div class="form-group">
                    <label>File (JPG, PNG, PDF, DOC, DOCX, XLSX, PPT, MP4, AVI, DLL)</label>
                    <input type="file" name="tugas_file" id="tugas_file" class="form-control">
                    <small id="error-tugas_file" class="error-text form-text text-danger"></small>
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
$(document).ready(function () {
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
    $("#form-tambah-tugas").validate({
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
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataTugasKompen.ajax.reload();
                    } else {
                        if (response.msgField) {
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data.'
                    });
                }
            });
            return false;
        }
    });
});
</script>
