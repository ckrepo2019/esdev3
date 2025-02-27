<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Guidance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables/DataTables/css/jquery.dataTables.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}"> 

  <style>
    .shadow {
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      border: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: 0px;
                color: black;
    }

    .select2-container .select2-selection--single{

              height: 40px !important;

    }


    .select2-selection__choice {
                    font-size: 16px; /* Change the font size */
                    background-color: #5cb85c !important; /* Change the background color */
                    color:rgb(255, 255, 255) !important;
                    border-radius: 5px; /* Add rounded corners */
                    padding: 2px 8px; /* Add some padding */
                    margin-right: 5px; /* Add some space between items */
    }


    


    .select2-container--default .select2-selection--single .select2-selection__arrow {

        top: 10px;

    }


  </style>

  @yield('pagespecificscripts')
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

    @include('guidance.inc.sidenav')

    <div class="content-wrapper">
      @yield('content')
    </div>

  </div>

 

    @include('sweetalert::alert') 
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    
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
    @yield('footerjavascript')

  </body>
</html>
