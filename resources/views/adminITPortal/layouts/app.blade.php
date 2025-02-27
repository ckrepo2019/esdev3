<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin IT Portal</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    @yield('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>


    <style>
        .appadd {
            white-space: nowrap;
            overflow: hidden;
            height: 10px;
            text-overflow: ellipsis;
        }
    </style>



</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed pace-primary">

    @yield('modalSection')
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->

        <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"
                            style="color: #fff"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown sideright">
                    <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt logouthover" style="margin-right: 6px; color: #fff"></i>
                        <!-- <span class="logoutshow" id="logoutshow"> Logout</span> -->
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
                    <img src="../../dist/img/CK Logo for ico.png" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    {{-- <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">{{Session::get('schoolinfo')->abbreviation}}</span> --}}
                    <span class="brand-text font-weight-light"
                        style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>SCHOOL ADMIN
                            PORTAL</b></span>
                </a>
            </div>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../../dist/img/download.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info pt-0">
                        <a href="#" class="d-block">{{ strtoupper(auth()->user()->name) }}</a>
                        <h6 class="text-white m-0 text-warning">ADMIN</h6>
                    </div>
                </div>
                @include('adminITPortal.inc.sidenav')

            </div>
            <li class="nav-item">
                <a class="nav-link" href="/admingetrooms">
                    <img class="essentiellogo" src="{{ asset('assets\images\essentiel.png') }}" alt="">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admingetrooms">
                    <img class="cklogo" src="{{ asset('assets\images\CK_Logo.png') }}" alt="">
                </a>
            </li>
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            {{-- <section class="content"> --}}
            @yield('content')
            {{-- </section> --}}
        </div>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>

    <!-- ./wrapper -->
    {{-- <footer class="main-footer">
</footer> --}}



    @include('sweetalert::alert')

    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>

    @yield('footerjavascript')

</body>

</html>


{{-- <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1">
    </section>
    <!-- Main content -->
    @yield('content')
    <section class="content ">
         
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->



    @yield('footerjavascript')
    @include('sweetalert::alert') 

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
  </body>
</html> --}}
