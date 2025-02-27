<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>College Instructor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <style>
    .shadow {
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      border: 0 !important;
    }
  </style>
	@php
		$schoolinfo = DB::table('schoolinfo')->first();
	@endphp
	
	 <style>
     
		.nav-bg {
			background-color: {{$schoolinfo->schoolcolor}} !important;
		}
		
		.sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
			background-color: {{$schoolinfo->schoolcolor}} !important;
			color: white;
		}
  </style>

   
    @yield('pagespecificscripts')
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
      <nav class="main-header navbar navbar-expand navbar-white navbar-light nav-bg">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
          </ul>
        <div class="form-inline ml-3">
            <div class="input-group input-group-sm">
              
            </div>
        </div>
        <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown notification">
          <a class="nav-link notnum" data-toggle="dropdown" href="#">
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notificationholder">
          </div>
        </li>
      <li class="nav-item dropdown sideright">
        <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout-form').submit();" >
          <i class="fas fa-sign-out-alt logouthover" style="margin-right: 7px; color: #fff"></i>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </a>
      </li>
        </ul>
      </nav>
      <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
      <div class="ckheader">
        <a href="#" class="brand-link nav-bg">
          <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}"
               alt="AdminLTE Logo"
               class="brand-image img-circle elevation-3"
               style="opacity: .8">
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">{{DB::table('schoolinfo')->first()->abbreviation}}</span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>COLLEGE INSTRUCTOR</b></span>
        </a>
    </div>
	@php
        $randomnum = rand(1, 4);
        $avatar = 'assets/images/avatars/unknown.png'.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
        $picurl = DB::table('teacher')->where('userid',auth()->user()->id)->first()->picurl;
        $picurl = str_replace('jpg','png',$picurl).'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
    @endphp
        <div class="sidebar">
            <div class="row">
				<div class="col-md-12">
				<div class="text-center">
					<img class="profile-user-img img-fluid img-circle" src="{{asset($picurl)}}"" onerror="this.onerror=null; this.src='{{asset($avatar)}}'" alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
				</div>
				</div>
			</div>
			<div class="row  user-panel">
				<div class="col-md-12 info text-center">
				<a class=" text-white mb-0 ">{{auth()->user()->name}}</a>
				<h6 class="text-warning text-center">{{auth()->user()->email}}</h6>
				</div>
			</div>
          @include('ctportal.inc.sidenav')
        </div>
      </aside>
      <div class="content-wrapper">
        @yield('content')
      </div>
      
    </div>

   
    {{-- @include('sweetalert::alert')  --}}

	
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
	<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    {{-- <script>
      $(document).ready(function(){

          get_pending_grades()

          function move_pending() {
            $('#pending_grade_count').effect( "shake", {distance  : 2}, "slow" )
            setTimeout( move_pending, 1000);
          }

          function get_pending_grades(){

            var teacherid = '{{DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id}}'

            $.ajax({
                type:'GET',
                url:'/college/assignedsubj',
                data:{
                  teacherid:teacherid
                },
                success:function(data) {
                  var count_pending_grade = 0
                  $.each(data,function(a,b){
                    if(b.prelimstatus == 4){
                        count_pending_grade+=1
                    }
                    else if(b.midtermstatus == 4){
                      count_pending_grade+=1
                    }
                    else if(b.prefistatus == 4){
                      count_pending_grade+=1
                    }
                    else if(b.finalstatus == 4){
                      count_pending_grade+=1
                    }

                    if(count_pending_grade == 0){
                      $('#pending_grade_count').text('')
                    }else{
                      $('#pending_grade_count').text(count_pending_grade)
                    }

                  
                    move_pending()

                })
                }
            })
            
          }
      })
    </script> --}}
	
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

     @yield('footerscript')
     @yield('footerjavascript')

  </body>
</html>
