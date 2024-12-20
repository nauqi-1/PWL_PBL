<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">Personal</li>
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            @if (Auth::user()->level->level_kode == 'MHS')
                <li class="nav-item">
                    <a href="{{ url('/mhs_listtugas') }}"
                        class="nav-link {{ $activeMenu == 'mhs_listtugas' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Daftar Tugas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/mhs_kumpultugas') }}"
                        class="nav-link {{ $activeMenu == 'mhs_kumpultugas' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>Pengumpulan Tugas</p>
                    </a>
                </li>
            @endif

            @if (Auth::user()->level->level_kode == 'ADM' ||
                    Auth::user()->level->level_kode == 'DSN' ||
                    Auth::user()->level->level_kode == 'TDK')
                <li class="nav-item">
                    <a href="{{ url('/tugaskompen') }}"
                        class="nav-link {{ $activeMenu == 'tugaskompen' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Daftar Tugas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/requesttugas') }}"
                        class="nav-link {{ $activeMenu == 'requesttugas' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Request Tugas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/pengumpulan_tugas') }}"
                        class="nav-link {{ $activeMenu == 'pengumpulan_tugas' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Pengumpulan</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->level->level_kode == 'DSN' || Auth::user()->level->level_kode == 'TDK')
            <li class="nav-item">
                            <a href="{{ url('/mahasiswa') }}"
                                class="nav-link {{ $activeMenu == 'mahasiswa' ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>Data Mahasiswa Kompen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                    <a href="{{ url('/mahasiswa_alfa') }}"
                        class="nav-link {{ $activeMenu == 'mahasiswa_alfa' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Data Alfa Periodik</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->level->level_kode == 'ADM')
                <li class="nav-header">Data Master</li>

                <!--<li class="nav-item">
                    <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Level User</p>
                    </a>
                </li>-->
                <li class="nav-item has-treeview {{ $activeMenu == 'user' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Data User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/mahasiswa') }}"
                                class="nav-link {{ $activeMenu == 'mahasiswa' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mahasiswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/dosen') }}"
                                class="nav-link {{ $activeMenu == 'dosen' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dosen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/tendik') }}"
                                class="nav-link {{ $activeMenu == 'tendik' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tendik</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin') }}"
                                class="nav-link {{ $activeMenu == 'admin' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/tugasjenis') }}"
                        class="nav-link {{ $activeMenu == 'tugasjenis' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Jenis Tugas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/kompetensi') }}"
                        class="nav-link {{ $activeMenu == 'kompetensi' ? 'active' : '' }}">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>Kompetensi Tugas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
                        <i class="nav-icon far fa-clock"></i>
                        <p>Periode Perkuliahan</p>
                    </a>
                </li>

                <li class="nav-header">Data Transaksional</li>
                <!--
            <li class="nav-item">
                <a href="{{ url('/tugaskompen') }}" class="nav-link {{ $activeMenu == 'tugaskompen' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tasks"></i>
                    <p>Daftar Tugas</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/requesttugas') }}" class="nav-link {{ $activeMenu == 'requesttugas' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-envelope-open-text"></i>
                    <p>Request Tugas</p>
                </a>
            </li>
-->
                <li class="nav-item">
                    <a href="{{ url('/mahasiswa_alfa') }}"
                        class="nav-link {{ $activeMenu == 'mahasiswa_alfa' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Data Alfa Periodik</p>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a href="{{ url('/tugas_mahasiswa') }}"
                        class="nav-link {{ $activeMenu == 'tugas_mahasiswa' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Data Pekerja Tugas</p>
                    </a>
                </li>-->
            @endif


        </ul>
    </nav>
</div>
