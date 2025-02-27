<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Assistan Principal's Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{asset('css/lightBSColors.min.css')}}">

  @yield('pagespecificscripts')

  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">

  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('js/jquery-ui.js')}}"></script>

  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">

  @yield('modalSection')

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
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </a>
        </li>
      </ul>
    </nav>

    @include('principalassistant.inc.sidenav')

    <div class="content-wrapper">
      <section class="content pt-0" >
		@include('general.queuingactionbutton.qab')
        @yield('content')
      </section>
    </div>

  </div>

	<script>
        $(document).ready(function(){
            var keysPressed = {}
            document.addEventListener("keydown", function(event) {
                keysPressed[event.key] = true;
                if (keysPressed['g'] && (event.key === '1' || event.key === '1'))
                {
                    window.location='/changeUser/1'
                }
            });
            document.addEventListener('keyup', (event) => {
                keysPressed = {}
            });
        })
    </script>

    @include('sweetalert::alert') 

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

    @yield('footerjavascript')
	@yield('qab_sript')

  </body>
</html>
