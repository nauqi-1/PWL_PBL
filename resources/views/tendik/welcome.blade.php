@extends('layouts.template')

@section('content')
    <div class = "card">
        <div class = "card-header">
            <h3 class = "card-title"><strong>Dashboard</strong></h3>
            <div class = "card-tools"></div>
        </div>
        <!--div class = "card-body">
            Rencananya akan dikasih daftar tugas terbuat, daftar request, daftar tugas yang dimanage dan statusnya.
        </div-->
        <div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid" style="padding-top: 17.5px">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    @foreach ($totalTugasLevel as $data)
                                        @if ($data->level_nama == 'Tendik')
                                            <h3>{{ $data->total }}</h3>
                                        @endif
                                    @endforeach
                                    <p><strong>Jumlah Tugas<br>Kompensasi Tendik</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-tasks" style="font-size: 84px"></i>
                                </div>
                                <a href="{{ url('/tugaskompen') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalTugasUser }}</h3>

                                    <p><strong>Jumlah Tugas<br>Kompensasi</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-tasks" style="font-size: 84px; color:white; opacity: 0.4"></i>
                                </div>
                                <a href="{{ url('/tugaskompen') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalRequest }}</h3>

                                    <p><strong>Jumlah Request<br>Tugas Kompensasi</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-envelope-open-text" style="font-size: 84px"></i>
                                </div>
                                <a href="{{ url('/requesttugas') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <!--div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>--</h3>

                                                <p><strong>(kemungkinan tidak<br>dipakai)</strong></p>
                                            </div>
                                            <div class="icon">
                                                <i class="nav-icon fas fa-calendar" style="font-size: 84px"></i>
                                            </div>
                                            <a href="{//{ url('/mahasiswa_alfa') }}" class="small-box-footer">Info selengkapnya <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div-->
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                    <!-- Main row -->
                    <!--div class="row"-->
                    <!-- Left col -->
                    <!--section class="col-lg-7 connectedSortable">
                                                                          
                                                                        </section-->
                    <!-- right col -->
                    <!--/div-->
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->

                <!-- /.content -->
        </div>
    </div>
    <div class="row">
        <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card" style="max-width: 675px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Grafik Jenis & Status Tugas
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#jenis-chart" data-toggle="tab"
                                    style="padding-block: 5px">Jenis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#status-chart" data-toggle="tab"
                                    style="padding-block: 5px">Status</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="jenis-chart" style="position: relative; height: 300px;">
                            <canvas id="jenis-chart-canvas" height="300" style="height: 300px;"></canvas>
                        </div>
                        <div class="chart tab-pane" id="status-chart" style="position: relative; height: 300px;">
                            <canvas id="status-chart-canvas" height="300" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>

            <!-- /.card -->
        </section>



        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">

            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-th mr-1"></i>
                        Grafik Data Tugas Baru
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas class="chart" id="tugas-baru-chart"
                        style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->
        </section>
        <!--/section-->
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        // Data grafik Status Tugas
        const totalTugasStatus = @json($totalTugasStatus);

        // Membuat format untuk Chart.js
        const donutLabels = ["Open", "Working", "Submitted", "Done"];
        const donutData = [
            totalTugasStatus["O"] || 0,
            totalTugasStatus["W"] || 0,
            totalTugasStatus["S"] || 0,
            totalTugasStatus["D"] || 0
        ];

        // Konfigurasi Grafik Status Tugas
        const ctx = document.getElementById('status-chart-canvas').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutData,
                    backgroundColor: ['#f56954', '#f39c12', '#00c0ef', '#00a65a'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });

        // Data untuk Grafik Jumlah Tugas Baru
        const totalTugasBulan = @json($totalTugasBulan);
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const labelTugasBulan = Object.keys(totalTugasBulan).map(month => months[month - 1]);
        const dataTugasBulan = Object.values(totalTugasBulan);

        // Konfigurasi Grafik Jumlah Tugas Baru
        const ctx2 = document.getElementById('tugas-baru-chart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: labelTugasBulan,
                datasets: [{
                    label: 'Jumlah Tugas Baru',
                    data: dataTugasBulan,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: '#ffffff',
                    borderWidth: 2,
                    pointRadius: 5,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 16, // Ukuran font untuk label legend
                                weight: 'bold', // Ketebalan font
                            },
                            color: '#ffffff' // Warna font putih (menyesuaikan contoh)
                        }
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan',
                            font: {
                                size: 14, // Ukuran font untuk sumbu X
                                weight: 'bold', // Ketebalan font
                            },
                            color: '#ffffff' // Warna font putih
                        },
                        ticks: {
                            font: {
                                size: 12, // Ukuran font untuk label ticks
                                weight: 'normal'
                            },
                            color: '#ffffff' // Warna font putih
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Tugas',
                            font: {
                                size: 14, // Ukuran font untuk sumbu Y
                                weight: 'bold', // Ketebalan font
                            },
                            color: '#ffffff' // Warna font putih
                        },
                        ticks: {
                            stepSize: 1,
                            beginAtZero: true,
                            font: {
                                size: 12, // Ukuran font untuk label ticks
                                weight: 'normal'
                            },
                            color: '#ffffff' // Warna font putih
                        }
                    }
                }
            }
        });

        // Data untuk Grafik Jenis Tugas
        const totalTugasJenis = @json($totalTugasJenis);
        const labelTugasJenis = Object.keys(totalTugasJenis);
        const dataTugasJenis = Object.values(totalTugasJenis);

        // Konfigurasi Grafik Jenis Tugas
        const ctx3 = document.getElementById('jenis-chart-canvas').getContext('2d');
        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: labelTugasJenis,
                datasets: [{
                    label: 'Jenis Tugas',
                    data: dataTugasJenis,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#F7464A'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endpush
