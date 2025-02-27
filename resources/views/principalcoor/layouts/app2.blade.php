<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Academic Coordinator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
	  {{-- <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}"> --}}
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  
  @php
    $schoolinfo = DB::table('schoolinfo')->first();
  @endphp

  <style>
    .shadow {
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      border: 0 !important;
    }
  </style>

	<style>
		
		.nav-bg {
		  background-color: {!! $schoolinfo->schoolcolor !!} !important;
		}

		.school-bg {
		    background-color: {!! $schoolinfo->schoolcolor !!} !important;
			color: #fff!important;
		}
		
		.sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
		  background-color: {!! $schoolinfo->schoolcolor !!};
		}
		.sidehead {
		  background-color: #002833!important;
		}
	</style>
  

  @yield('pagespecificscripts')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">
  @yield('modalSection')
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light nav-bg">
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

    @include('principalcoor.inc.sidenav')

    <div class="content-wrapper">
      @yield('content')
    </div>

  </div>

 

    @include('sweetalert::alert') 

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

    @yield('footerjavascript')
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
  </body>
</html>
