<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Parents Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.js')}}" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @yield('pagespecificscripts')
    <style>
      .shadow {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border: 0 !important;
      }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navss">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <li class="nav-item d-sm-inline-block">
          <a href="/home" class="nav-link text-light">Home</a>
        </li>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link notnum" data-toggle="dropdown" href="#">
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notificationholder">
                </div>
            </li>
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

    <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <div class="ckheader">
            <a href="#" class="brand-link sidehead">
            <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}"
                alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">{{DB::table('schoolinfo')->first()->abbreviation}}</span>
            <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>PARENT'S PORTAL</b></span>
            </a>
    </div>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        @php
          $randomnum = rand(1, 4);
          if(Session::get('studentInfo')->gender == 'FEMALE'){
              $avatar = 'avatars/S(F) '.$randomnum.'.png';
          }
          else{
              $avatar = 'avatars/S(M) '.$randomnum.'.png';
          }
          
          $picurl = str_replace('jpg','png',Session::get('studentInfo')->picurl).'?random="'.\Carbon\Carbon::now('Asia/Manila').'"';

        @endphp
        <div class="image">
          <img  
              src="{{asset($picurl)}}" 
              onerror="this.onerror=null; this.src='{{asset($avatar)}}'"
              class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info pt-0" style="    margin-top: -7px;">
            <a href="#" class="d-block">{{auth()->user()->name}}</a>
            <h6 class="text-white m-0 text-warning">{{auth()->user()->email}}</h6>
        </div>
      </div>
      @include('parentsportal.inc.sidenav')
    </div>
  </aside>
  <div class="content-wrapper">
    @yield('content')
  </div>
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

@yield('footerscript')

<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>


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
