<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class ="card-header text-center">
        <h2>Register New User</h2>
        <form action="{{ url('/register') }}" method="POST" id="form-register">
            
            @csrf
            <div class="input-group mb-3">
                <select name="level_id" class="form-control" required>
                    <option value="">Select Level</option>
                    @foreach ($level as $l)
                        <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="input-group mb-3">
                <input type="text" name="nama" class="form-control" placeholder="Name" required>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="row">
                
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#form-register").validate({
                rules: {
                    level_id: {required: true},
                    username: { required: true, minlength: 4, maxlength: 20 },
                    nama: { required: true, maxlength: 100 },
                    password: { required: true, minlength: 5, maxlength: 20 },
                    password_confirmation: { required: true, equalTo: '[name="password"]' }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then(function() {
                                    window.location = response.redirect || '{{ url('/login') }}'; // Redirect to login page or any other
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>
