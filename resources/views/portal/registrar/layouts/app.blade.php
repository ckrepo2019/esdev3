<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
{{-- <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}"> --}}
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
<style>
        .bg-light-blue{
            background-color: #a0bfdc !important;
        }
        .text-light-blue{
            color: #a0bfdc !important;
        }

        .active-section{
            background-color: #a0bfdc !important;
            border: solid #a0bfdc 1px !important;
        }

        .scroll-area-lg{
            height:700px;
        }
        .subject{
            font-size: 20px;
        }
        .vertical-nav-menu .widget-content-left a{
            padding:0;
            height: 1.0rem;
            line-height: 1rem;
        }

        .closed-sidebar .app-sidebar:hover .app-sidebar__inner ul .widget-content-left a {
            text-indent: initial;
            padding: 0 ;
        }


        @media only screen and (max-width: 600px) {
            .report-card-table{
                width:500px;
            }
            .scroll-area-lg{
                height:230px;
            }
          
        }
        @media (max-width: 991.98px){
            .sidebar-mobile-open .app-sidebar .app-sidebar__inner ul .widget-content-left a {
                text-indent: initial;
                padding: 0 ;
            }
        } 
        img{
border-radius: 50%;
}
img {
border-radius: 50%;
}
a {
text-decoration: none;
}
.card {
box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
transition: 0.3s;
}

.card:hover {
box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
#datepicker{
position:relative !important;
display:inline-block !important
}

.table-responsive {
display: table;
}
.clsDatePicker {
z-index: 100000;
}
#datepicker-container{
text-align:center;
}
#datepicker-center{
display:inline-block;
margin:0 auto;
}
.dot {
height: 10px;
width: 10px;
background-color: #bbb;
border-radius: 20%;
display: inline-block;
}
    </style>
<body class="sidebar-mini layout-fixed layout-navbar-fixed accent-info" style="height: auto;">
        <div class="wrapper">
          <!-- Navbar -->
          @include('registrar.inc.header')
          <!-- /.navbar -->
        
          <!-- Main Sidebar Container -->
          @include('registrar.inc.sidenav')
        
          <!-- Content Wrapper. Contains page content -->
          
          <!-- /.content-wrapper -->
        
          <!-- Control Sidebar -->
          <div class="content-wrapper" style="min-height: 809px;">
                <!-- Content Header (Page header) -->
                
            
                <!-- Main content -->
                <section class="content">
                        <div class="container-fluid">
                            <br>
                            @yield('content')
                        </div>
                </section>

                <!-- /.content -->
              </div>
          <!-- /.control-sidebar -->
        
          <!-- Main Footer -->
          
        </div>
        <!-- ./wrapper -->
        
        <!-- REQUIRED SCRIPTS -->
        
        <!-- jQuery -->
        {{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
        <!-- Bootstrap -->
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE -->
        <script src="{{asset('dist/js/adminlte.js')}}"></script>
        
        <!-- OPTIONAL SCRIPTS -->
        <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('dist/js/demo.js')}}"></script>
        <script src="{{asset('dist/js/pages/dashboard3.js')}}"></script>
        
        
        </body>