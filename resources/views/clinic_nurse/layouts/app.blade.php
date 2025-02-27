<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">


    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
    <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jquery/jquery.min.js:2:29755')}}">
    <style>
    /* @media only screen and (max-width: 1366px) {
        body {
            /* overflow-y: hidden; */
        /* }
    } */
    @media only screen and (max-width: 600px) {
        .report-card-table  { width:500px; }
        .scroll-area-lg     { height:230px; }
        
    }
    /* @media only screen and (max-width: 500px) {
        body {
            overflow-y: scroll;
        }
        
    } */
    @media (max-width: 991.98px){
        .sidebar-mobile-open .app-sidebar .app-sidebar__inner ul .widget-content-left a { text-indent: initial; padding: 0 ; }
    } 
    /* The side navigation menu */
.custom-sidenav {
    height: 100%; /* 100% Full-height */
    width: 0; /* 0 width - change this with JavaScript */
    position: fixed; /* Stay in place */
    z-index: 1; /* Stay on top */
    top: 0;
    right: 0;
    background-color: #111; /* Black*/
    overflow-x: hidden; /* Disable horizontal scroll */
    padding-top: 60px; /* Place content 60px from the top */
    transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    font-size: 12px;
}

/* The navigation menu links */
.custom-sidenav a {
    padding: 8px 8px 8px 32px;
    text-decoration: none;
    font-size: 12px;
    font-family: Gotham;
    color: #818181;
    display: block;
    transition: 0.3s
}

/* When you mouse over the navigation links, change their color */
.custom-sidenav a:hover, .offcanvas a:focus{
    color: #f1f1f1;
}

/* Position and style the close button (top right corner) */
.custom-sidenav .closebtn {
    position: absolute;
    top: 0;
    left: 25px;
    font-size: 36px;
    margin-right: 50px;
}

/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
#main {
    transition: margin-left .5s;
    /* padding: 20px; */
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
    .custom-sidenav {padding-top: 15px;}
    .custom-sidenav a {font-size: 18px;}
}
</style>
    {{-- <script>
    let evtSource = new EventSource("/serverEventGetNotifications", {withCredentials: true});
    evtSource.onmessage = function (e) {
            let data = JSON.parse(e.data);
            $('.notificationholder').empty();
            $('.notificationholder').append(data[0].notifcations);
            $('.notnum').empty();
            $('.notnum').append(data[0].count);
        };
    </script> --}}

    @yield('headerjavascript')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed accent-info pace-primary" >
    <div class="wrapper">
        <div id="custom-sidenav" class="custom-sidenav">
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Clients</a>
        <a href="#">Contact</a>
        </div>
        @include('clinic_nurse.inc.header')
        @include('clinic_nurse.inc.sidenav')
        <div class="content-wrapper" id="main"  >
            <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
            </section>
        </div>
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Toastr -->
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('dist/js/demo.js')}}"></script>
    <script src="{{asset('dist/js/pages/dashboard3.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
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
          $(document).on('click','#authorizedinput',function(){
            if($(this).attr('viewtarget') == 'pdf'){
                var viewtarget = '_blank';
            }else{
                var viewtarget = '';
            }
            Swal.fire({
              title: 'School Head',
              html: 
                    '<form action="'+$(this).attr('route')+'" method="get" id="submitschoolhead" target="'+viewtarget+'">'+
                        '<input type="text" name="schoolhead" class="form-control" placeholder="School Head" required/>'+
                    '</form>',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Submit'
            })
            .then((result) => {
              if (result.value) {
                event.preventDefault(); 
                $('#submitschoolhead').submit()
              }
            })
          })
      })
      $('#show-notifications').on('click', function(){
          if($(this).attr('clicked') == 0)
          {
            $(this).attr('clicked','1')
            openNav();
          }else{
            $(this).attr('clicked','0')
            closeNav();
          }
      })
      /* Set the width of the side navigation to 250px */
        function openNav() {
            document.getElementById("custom-sidenav").style.width = "300px";
            $(this).removeAttr('onclick')
            $(this).attr('onclick','closeNav()')
        }

        /* Set the width of the side navigation to 0 */
        function closeNav() {
            document.getElementById("custom-sidenav").style.width = "0";
            $(this).removeAttr('onclick')
            $(this).attr('onclick','openNav()')
        }
    </script>
        
        
</body>