<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Sistem Kompensasi JTI')</title>

  <meta name="csrf-token" content="{{csrf_token()}}"> <!--Script untuk mengirimkan token csrf setiap kali request ajax-->

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!--Datatables-->
  <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Sweetalert2 :3-->
  <link rel="stylesheet" href="{{asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('adminlte/dist/css/adminlte.min.css')}}">
  <!-- Tambahkan CSS Choices -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
  <!-- Tambahkan JavaScript Choices -->
  <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- Ion Slider -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider@2.3.0/css/ion.rangeSlider.min.css">





<style>
    .table .nama-tugas {
      max-width: 150px;
      word-wrap: break-word; /* Memastikan teks dapat dibagi */
      white-space: normal; /* Mengizinkan teks menjadi beberapa baris */
    }
    </style>
    
    @stack('css')
</head>
<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    @include('layouts.header') 
    <!-- /.navbar -->
    
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{url('/')}}" class="brand-link">
        <img src="{{url('images/jti-polinema-logo.png')}}" alt="JTI Logo" class="brand-image img-circle elevation-1" style="opacity: .8">
        <span class="brand-text font-weight-light">Sistem Kompensasi</span>
      </a>
      
      <!-- Sidebar -->
      @include('layouts.sidebar')
      <!-- /.sidebar -->
    </aside>
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      @include('layouts.breadcrumb')
      
      <!-- Main content -->
      <section class="content">
        @yield('content')
        
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    
    @include('layouts.footer')
  </div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Ion Slider -->
<script src="https://cdn.jsdelivr.net/npm/ion-rangeslider@2.3.0/js/ion.rangeSlider.min.js"></script>
<!-- Datatables & Plugins -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script> 
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!--Sweetalert library?-->
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!--Jquery Validation -w- -->
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js')}}"></script>
<script>
  $.ajaxSetup({headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')}});
</script>
@stack('js')
</body>
</html>
