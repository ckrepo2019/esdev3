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
  <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">

  @yield('pagespecificscripts')

  @php
    $schoolinfo = DB::table('schoolinfo')->first();
  @endphp
  <style>
    .appadd {
        white-space: nowrap;
        overflow: hidden;
        height: 10px;
        text-overflow: ellipsis; 
    }
  </style>
	
	<style>
   
    .nav-bg {
      background-color: {!! $schoolinfo->schoolcolor !!} !important;
    }
    
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
      background-color: {!! $schoolinfo->schoolcolor !!};
    }
    .sidehead {
      background-color: #002833!important;
    }
  </style>


</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed pace-primary">


  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light  nav-bg">
      <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars" style="color: #fff"></i></a>
          </li>
        </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown sideright">
          <a class="nav-link" data-toggle="dropdown" href="#" id="logout">
            <span class="logoutshow" id="logoutshow"> Logout</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </a>
        </li>
      </ul>
    </nav>

    @yield('modalSection')

    @include('adminPortal.inc.sidenav')

    <div class="content-wrapper">
        @yield('content')
    </div>

  </div>

    

    @include('sweetalert::alert') 

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
	<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script> 
	<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">
	@yield('footerjavascript')
    <script>
      $(document).ready(function(){
    
          $(document).on('click','#logout',function(){
          
            Swal.fire({
              title: 'Are you sure you want to logout?',
              type: 'info',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Logout'
            })
            .then((result) => {
              if (result.value) {
                event.preventDefault(); 
                $('#logout-form').submit()
              
              }
            })
          })
      })
    </script> 
    <script>
      $(document).ready(function(){
        const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              showCloseButton: true,
        });

        $( document ).ajaxError(function() {
            Toast.fire({
                  type: 'error',
                  title: 'Unable to process online!'
            })
        });

      })
    </script>
	
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
