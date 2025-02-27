<nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          </li>
          {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="/home" class="nav-link">Home</a>
          </li> --}}
          
        </ul>
        {{-- Menu --}}

        <div class="form-inline ml-3 menunav" style="height: 50px!important">
          <div class="input-group input-group-sm">
            <ul class="nicemenu">
              <li>
                <a href="/home">
                  <div class="icon">
                    <i class="fas fa-home"></i>
                    <i class="fas fa-home"></i>
                  </div>
                  <div class="name"><span  data-text="Home">Home</span></div>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <!-- SEARCH FORM -->
    
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Notifications Dropdown Menu -->
          
          {{-- <li class="nav-item dropdown notification">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"  style="color: #fff"></i>
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

          
          <li class="nav-item dropdown sideright">
        
            <a href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" id="dashboard" class="nav-link">
              <i class="fas fa-sign-out-alt logouthover" style="margin-right: 7px; color: #fff"></i>
              <span class="logoutshow" id="logoutshow"> Logout</span>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </a>
            
           
          </li>
          {{-- <li class="nav-item dropdown">
            
            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" id="dashboard" class="nav-link">
                  <i class="nav-icon fa fa-power-off"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
          </li> --}}
        </ul>
      </nav>