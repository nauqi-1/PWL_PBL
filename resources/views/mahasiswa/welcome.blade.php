@extends('layouts.template')

@section('content')
    <div class = "card">
        <div class = "card-header">
            <h3 class = "card-title"><strong>Dashboard</strong></h3>
            <div class = "card-tools"></div>
        </div>
        <!--div class = "card-body">
            Rencananya akan dikasih daftar tugas terambil dan status, jumlah alfa total, jumlah alfa terbayar, sama status boleh uas atau engga.
        </div-->
        <div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid" style="padding-top: 17.5px">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-dark">
                                <div class="inner">
                                    <h3>{{ $totalAlfa }}</h3>

                                    <p><strong>Total Keseluruhan<br>Jam Alfa</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-user-clock" style="font-size: 84px; color: white; opacity: 0.4"></i>
                                </div>
                                <!--a href="{//{ url('/tugaskompen') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a-->
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalAlfaLunas }}</h3>

                                    <p><strong>Total Jam Alfa<br>Terlunasi</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-check-circle" style="font-size: 84px"></i>
                                </div>
                                <!--a href="{//{ url('/tugaskompen') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a-->
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalAlfaHutang }}</h3>

                                    <p><strong>Total Jam Alfa<br>Belum Terlunasi</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-times-circle" style="font-size: 84px"></i>
                                </div>
                                <!--a href="{//{ url('/requesttugas') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a-->
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box {{ $warnaUAS }}">
                                <div class="inner">
                                    <h3>{!! $statusUAS !!}</h3>
                                    <!--p style="font-size: 40.5px"><strong>{/!! $statusUAS !!}</strong></p-->
                                    
                                    <p><strong>Status Ujian Akhir<br>Semester (UAS)</strong></p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-pen-alt" style="font-size: 84px"></i>
                                </div>
                                <!--a href="{//{ url('/mahasiswa_alfa') }}" class="small-box-footer">Info selengkapnya <i
                                        class="fas fa-arrow-circle-right"></i></a-->
                            </div>
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
                        Grafik Aktivitas Mahasiswa per Tugas
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

        <!-- STACKED BAR CHART -->
        <section class="col-lg-6 connectedSortable">
            <div class="card card-warning" style="max-width: 675px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Grafik Alfa & Kompen per Periodik
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="alfaPeriodeChart"
                            style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </section>

        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        
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
        const tugasStatusMhs = @json($tugasStatusMhs);

        // Membuat format untuk Chart.js
        const labelTugasStatus = ["Working", "Submitted", "Done"];
        const dataTugasStatus = [
            tugasStatusMhs["W"] || 0,
            tugasStatusMhs["S"] || 0,
            tugasStatusMhs["D"] || 0
        ];

        // Konfigurasi Grafik Status Tugas
        const ctx = document.getElementById('status-chart-canvas').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labelTugasStatus,
                datasets: [{
                    data: dataTugasStatus,
                    backgroundColor: ['#f56954', '#f39c12', '#00c0ef', '#00a65a'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });

        // Data untuk Grafik Jenis Tugas
        const tugasJenisMhs = @json($tugasJenisMhs);
        const labelTugasJenis = Object.keys(tugasJenisMhs);
        const dataTugasJenis = Object.values(tugasJenisMhs);

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

        // Data untuk Grafik Alfa & Kompen Lunas per Periodik
        const totalAlfaPeriode = @json($totalAlfaPeriode);
        const labels = totalAlfaPeriode.map(item => item.periode);
        const dataAlfa = totalAlfaPeriode.map(item => item.total_alfa);
        const dataKompen = totalAlfaPeriode.map(item => item.total_lunas);

        // Konfigurasi Grafik Alfa & Kompen Lunas per Periodik
        const ctx4 = document.getElementById('alfaPeriodeChart').getContext('2d');
        new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Jumlah Alfa',
                        data: dataAlfa,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    },
                    {
                        label: 'Jumlah Kompen',
                        data: dataKompen,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    },
                ],
            },
            options: {
                responsive: true,
                //maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top', // Posisi legend
                    },
                },
                scales: {
                    x: {
                        //stacked: true,
                        title: {
                            display: true,
                            text: 'Periode',
                            font: {
                                size: 14,
                                weight: 'bold',
                            }
                        },
                    },
                    y: {
                        //stacked: true,
                        title: {
                            display: true,
                            text: 'Jumlah Alfa & Kompen',
                            font: {
                                size: 14,
                                weight: 'bold',
                            }
                        },
                        beginAtZero: true,
                    },
                },
            },
        });
    </script>
@endpush
