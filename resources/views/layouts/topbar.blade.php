<style>
  .circular--portrait {
      position: relative;
      width: 50px;
      height: 50px;
      overflow: hidden;
      border-radius: 50%;
    }

    .circular--portrait img {
      width: 100%;
      height: auto;
    }
</style>
<!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="./index.html">@yield('page-title', 'Dashboard')</a>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <div class="circular--portrait mt--2">
                  <img alt="Image placeholder" src="{{ 
                    isset(auth()->user()->pegawai->image) ? 'http://simasn.pertanian.go.id/simasn/fotoprofil/' . auth()->user()->pegawai->image : asset('assets/img/theme/team-1-800x800.jpg')
                  }}">
                </div>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold">
                  {{  
                    auth()->user()->pegawai->nama ??
                    ucfirst(auth()->user()->username) 
                  }}
                  </span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
              </div>
             {{--  <a href="./examples/profile.html" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="./examples/profile.html" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <a href="./examples/profile.html" class="dropdown-item">
                <i class="ni ni-calendar-grid-58"></i>
                <span>Activity</span>
              </a>
              <a href="./examples/profile.html" class="dropdown-item">
                <i class="ni ni-support-16"></i>
                <span>Support</span>
              </a> --}}
              <div class="dropdown-divider"></div>
              <a href="#" id="logout-btn" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->