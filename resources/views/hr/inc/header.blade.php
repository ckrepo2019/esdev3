
  <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/home" class="nav-link text-light">Home</a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="#" id="logout" class="nav-link">
              <!-- <i class="nav-icon fa fa-power-off"></i> -->
              <span class="logoutshow" id="logoutshow"> Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
      </li>
    </ul>
  </nav>