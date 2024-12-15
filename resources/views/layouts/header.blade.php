

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search 
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      -->

      
      <!-- Notifications Dropdown Menu -->
      @php
      $user = auth()->user();
      $unreadCount = $user ? $user->notifications()->where('status_notification', 'unread')->count() : 0;
      $notifications = $user ? $user->notifications()->orderBy('tgl_notification', 'desc')->take(10)->get() : [];
      @endphp
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">{{ $unreadCount }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">{{ $unreadCount }} Notifications</span>
            @foreach ($notifications as $notification)
                <div class="dropdown-divider"></div>
                <a href="{{ route('notification.redirect', ['id' => $notification->notification_id]) }}" 
                  class="dropdown-item" onclick="markAsRead({{ $notification->notification_id }})">
                    <i class="fas fa-info-circle mr-2"></i> {{ $notification->konten_notification }}
                    <span class="float-right text-muted text-sm">{{ $notification->tgl_notification->diffForHumans() }}</span>
                </a>
            @endforeach
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
    </li>
    
      {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> --}}
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          @if (Auth::user()->level->level_kode == 'ADM')
          {{Auth::user()->admin->admin_nama}}
          @elseif (Auth::user()->level->level_kode == 'DSN')
          {{Auth::user()->dosen->dosen_nama}}
          @elseif (Auth::user()->level->level_kode == 'TDK')
          {{Auth::user()->tendik->tendik_nama}}
          @elseif (Auth::user()->level->level_kode == 'MHS')
          {{Auth::user()->mahasiswa->mahasiswa_nama}}
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a onclick="modalAction('{{url('/user/'.Auth::user()->user_id.'/edit_ajax')}}')" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Edit Data Diri
        </a>
        <div class="dropdown-divider"></div>

        <a class="nav-link" data-widget="logout" data-slide="true" href="{{url('/logout')}}" role="button">
          <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

  <script>
    function modalAction(url = '') {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                $('#myModal').html(response); 
                $('#myModal').modal('show'); 
            },
            error: function(error) {
                console.log('Error:', error);
                alert('Failed to load content.');
            }
        });
    }
  </script>