<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
    
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
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
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">

    <style>
        img                                          { border-radius: 50%; }
        a                                            { text-decoration: none; }

        .bg-light-blue                               { background-color: #a0bfdc !important; }
        .text-light-blue                             { color: #a0bfdc !important; }
        .active-section                              { background-color: #a0bfdc !important; border: solid #a0bfdc 1px !important; }
        .scroll-area-lg                              { height:700px; }
        .subject                                     { font-size: 20px; }
        .vertical-nav-menu .widget-content-left a    { padding:0; height: 1.0rem; line-height: 1rem; }
        .card                                        { box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; }
        .card:hover                                  { box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2); }
        .closed-sidebar .app-sidebar:hover .app-sidebar__inner ul .widget-content-left a    { text-indent: initial; padding: 0 ; }
        .card                                        { box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; }
        .card:hover                                  { box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2); }
        /* .table-responsive                            { display: table; } */
        .clsDatePicker                               { z-index: 100000; }
        .dot                                         { height: 10px; width: 10px; background-color: #bbb; border-radius: 20%; display: inline-block; }
        .stats-info                                  { background-color: #fff; border: 1px solid #e5e5e5; border-radius: 4px; margin-bottom: 20px; padding: 15px; text-align: center; }
        .dash-widget-info                            { text-align: right; }
        .dash-widget-icon                            { background-color: rgba(255, 155, 68, 0.2); border-radius: 100%; color: #ff9b44; display: inline-block; float: left; font-size: 30px; height: 60px; line-height: 60px; margin-right: 10px; text-align: center; width: 60px; }

        #datepicker                                  { position:relative !important; display:inline-block !important }
        #datepicker-container                        { text-align:center; }
        #datepicker-center                           { display:inline-block; margin:0 auto; }
    .stats-info {
        background-color: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        margin-bottom: 20px;
        padding: 15px;
        text-align: center;
    }
    .dash-widget-icon {
        background-color: rgba(255, 155, 68, 0.2);
        border-radius: 100%;
        color: #ff9b44;
        display: inline-block;
        float: left;
        font-size: 30px;
        height: 60px;
        line-height: 60px;
        margin-right: 10px;
        text-align: center;
        width: 60px;
    }
    .dash-widget-info {
        text-align: right;
    }
    .experience-list {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
    }
    .experience-box {
        position: relative;
    }
    .experience-list > li {
        position: relative;
    }
    .experience-list > li .experience-user {
        background: #fff;
        height: 10px;
        left: 4px;
        margin: 0;
        padding: 0;
        position: absolute;
        top: 4px;
        width: 10px;
    }
    .before-circle {
        background-color: #ddd;
        border-radius: 50%;
        height: 10px;
        width: 10px;
    }
    .experience-list > li .experience-content {
        background-color: #fff;
        margin: 0 0 20px 40px;
        padding: 0;
        position: relative;
    }
    .experience-list > li .experience-content .timeline-content {
        color: #9e9e9e;
    }
    .experience-list > li .experience-content .timeline-content a.name {
        color: #616161;
        font-weight: bold;
    }.experience-list > li .time {
        color: #bdbdbd;
        display: block;
        font-size: 12px;
        line-height: 1.35;
    }
    .personal-info {
        list-style: none;
        margin-bottom: 0;
        padding: 0;
    }
    .personal-info li {
        margin-bottom: 10px;
    }
    .pro-overview .personal-info li .title {
        width: 50%;
    }
    .personal-info li .text {
        color: #8e8e8e;
        display: block;
        overflow: hidden;
    }
    .pro-overview .personal-info li .title {
        width: 50%;
    }
    .personal-info li .title {
        color: #4f4f4f;
        float: left;
        font-weight: 500;
        margin-right: 30px;
        width: 25%;
    }
    .profile-info-left {
        border-right: 2px dashed #ccc;
    }
    .widget-user .widget-user-image>img {
        border: 3px solid #fff !important;
        height: auto !important;
        width: 100% !important;
    }
    .widget-user .widget-user-image {
        /* left: 0;
        margin-left: 31px !important;
        position: absolute !important;
        top: 21px !important; */
        left: 50%;
        margin-left: -67px;
        position: absolute;
        top: 48px;
    }
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
        @include('clinic.inc.header')
        @include('clinic.inc.sidenav')
        <div class="content-wrapper"  >
            <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
            </section>
        </div>
    </div>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
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
    </script>
        
        
</body>