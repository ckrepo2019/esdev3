<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google" content="notranslate">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ESSENTIEL | Registrar</title>

    <link rel="shortcut icon" href="{{asset('assets/ckicon.ico')}}" type="image/x-icon"/>

    <link href="{{asset('dist/css/adminlte.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}"> 
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/blue/pace-theme-flat-top.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">    


    <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/magnify-white-theme.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/fontfamily.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/googleapis-font.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
    @yield('jsUP') 
    <style type="text/css">
    </style>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed accent-info" style="height: auto;">

<div class="wrapper">
          <!-- Navbar -->
          @include('registrar.inc.header')
          <!-- /.navbar -->
        
          <!-- Main Sidebar Container -->
          @include('registrar.inc.sidenav')
          <!-- /.content-wrapper -->
        
          <!-- Control Sidebar -->
          <div class="content-wrapper" style="min-height: 809px;">
                <!-- Content Header (Page header) -->
                
            
                <!-- Main content -->
                <section class="content">
                        <div class="container-fluid">
                            @yield('content')
                        </div>
                </section>

                <!-- /.content -->
              </div>
          <!-- /.control-sidebar -->
        
          <!-- Main Footer -->
          {{-- @include('enrollment.layouts.footer') --}}
        </div>
</body>
</html>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{asset('dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/datatables/DataTables/js/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/DataTables/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('js/pagination.js')}}"></script>
<script src="{{asset('plugins/pace-progress/pace.min.js')}}"></script>
<script src="{{asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js')}}"></script>
<script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
{{-- <script src="{{asset('plugins/sparklines/sparkline.js')}}"></script> --}}
{{-- <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script> --}}
{{-- <script src="{{asset('plugins/moment/moment.min.js')}}"></script> --}}
{{-- <script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script> --}}
{{-- <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script> --}}
{{-- <script src="{{asset('dist/js/pages/dashboard.js')}}"></script> --}}
{{-- <script src="{{asset('dist/js/jquery.simplePagination.js')}}"></script> --}}
@yield('modal')
@yield('js')

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
        
        setInterval(function(){

            // $('.pace').css('display','none !important')
        
            window.paceOptoins = {
                ajax:false,
                restartOnRequestAfter: false,
            }


            Pace.ignore(function(){
              $.ajax({
                url:"{{ route('getstudpaid') }}",
                method:'GET',
                data:{
                
                },
                dataType:'json',
                success:function(data)
                {
                  $('#studpaidlist').html(data.studlist);
                  $('.studpaid').text(data.studcount);
                }
              });
            });
        }, 30000);
    })
</script>
