<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Administrator Portal</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  @yield('pagespecificscripts')
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
 
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

  

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

<!-- @yield('modalSection') -->
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
      <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars" style="color: #fff"></i></a>
          </li>
        </ul>
        {{-- Menu --}}

        <div class="form-inline ml-3 menunav" style="height: 50px!important">
          <div class="input-group input-group-sm">
            <ul class="nicemenu">
              <li>
                <a href="#">
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
        {{-- End --}}

    
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
     
        <li class="nav-item dropdown notification">
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
        </li>
      
        <li class="nav-item dropdown sideright">
        
          <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
            <i class="fas fa-sign-out-alt logouthover" style="margin-right: 7px; color: #fff"></i>
            <span class="logoutshow" id="logoutshow"> Logout</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </a>
          
         
        </li>
      
      
      </ul>
    </nav>
  <!-- /.navbar -->

  <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
   
   <div class="ckheader">
    <a href="#" class="brand-link sidehead">
      <img src="../../dist/img/CK Logo for ico.png"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">CK High School</span>
    </a>
   </div>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/download.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info pt-0">
            <a href="#" class="d-block">{{strtoupper(auth()->user()->name)}}</a>
            <h6 class="text-white m-0 text-warning"> ADMIN'S PORTAL</h6>
        </div>
      </div>
      @include('template.sidenav.sidenav')
     
    </div>
    <li class="nav-item">
      <a class="nav-link" href="/admingetrooms">
        <img class="essentiellogo" src="{{asset('assets\images\essentiel.png')}}" alt="">
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/admingetrooms">
        <img class="cklogo" src="{{asset('assets\images\CK_Logo.png')}}" alt="">
      </a>
    </li>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1">
    </section>
    <!-- Main content -->
    @yield('content')
    <section class="content">
         @include('template.pages.homepage')
    </section>
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>

<!-- ./wrapper -->
<footer class="main-footer">
    @include('template.footer.footer')
</footer>


    @yield('footerjavascript')
    @include('sweetalert::alert') 

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>

    {{-- <script>
      $( document ).ready(function() {
        // var delay = 800, setTimeoutConst;
        $(document).on('click','.logouthover',function(){
            $(".logoutshow").prop("hidden", false);
        })
        
      })
    
    </script> --}}
  </body>
</html>
