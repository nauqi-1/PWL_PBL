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
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            @if(Auth::user()->level->level_kode == 'ADM')

            <li class="nav-header">Data Master</li>

            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Level User</p>
                </a>
            </li>
            <li class="nav-item has-treeview {{ ($activeMenu == 'user') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ ($activeMenu == 'user') ? 'active' : '' }}">
        <i class="nav-icon far fa-user"></i>
        <p>
            Data User
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('/mahasiswa') }}" class="nav-link {{$activeMenu == 'mahasiswa' ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Mahasiswa</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/dosen') }}" class="nav-link {{$activeMenu == 'dosen' ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Dosen</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/tendik') }}" class="nav-link {{$activeMenu == 'tendik' ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Tendik</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin') }}" class="nav-link {{$activeMenu == 'admin' ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Admin</p>
            </a>
        </li>
    </ul>
</li>

            <li class="nav-item">
                <a href="{{ url('/tugaskompen') }}" class="nav-link {{ ($activeMenu == 'tugaskompen') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Daftar Tugas</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/kompetensi') }}" class="nav-link {{ ($activeMenu == 'kompetensi') ? 'active' : '' }}">
                    <i class="nav-icon far fa-bookmark"></i>
                    <p>Kompetensi Tugas</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang') ? 'active' : '' }}">
                    <i class="nav-icon far fa-list-alt"></i>
                    <p>Data Barang</p>
                </a>
            </li>
            @endif
            <li class="nav-header">Data Transaksional</li>
            <li class="nav-item">
                <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>Stok Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/penjualan') }}" class="nav-link {{ ($activeMenu == 'penjualan') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Transaksi Penjualan</p>
                </a>
            </li>
            
        </ul>
    </nav>
</div>
