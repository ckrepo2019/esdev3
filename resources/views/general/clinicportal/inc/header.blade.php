
  <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li> -->
    </ul>

    <div class="form-inline menunav" style="height: 50px!important">
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
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      {{-- <li class="nav-item">
        <a class="nav-link" href="/messagesindex" target="_blank">
          <i class="fa fa-envelope" style="line-height: unset;"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
      </li> --}}
    <li class="nav-item dropdown sideright">
      <a href="#" id="logout" class="nav-link">
          <!-- <i class="fas fa-sign-out-alt logouthover" style="margin-right: 7px; color: #fff"></i> -->
          <span class="logoutshow" id="logoutshow"> Logout</span>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </a>
        
       
      </li>
      <!-- <li class="nav-item">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" id="dashboard" class="nav-link">
              <i class="nav-icon fa fa-power-off"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
      </li> -->
    </ul>
  </nav>