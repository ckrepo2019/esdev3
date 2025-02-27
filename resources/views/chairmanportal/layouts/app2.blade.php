<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Administrator Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

  @yield('pagespecificscripts')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">


<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
      <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars" style="color: #fff"></i></a>
          </li>
        </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown sideright">
          <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
            <i class="fas fa-sign-out-alt logouthover" style="margin-right: 6px; color: #fff"></i>
            <!-- <span class="logoutshow" id="logoutshow"> Logout</span> -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </a>
        </li>
      </ul>
    </nav>

    @include('deanportal.inc.sidenav')

    <div class="content-wrapper">
		@include('general.queuingactionbutton.qab')

        @yield('content')
    </div>

  </div>

    @yield('footerjavascript')

    @include('sweetalert::alert') 
	
	@yield('qab_sript')

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>

  </body>
</html>
