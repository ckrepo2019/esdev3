<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CKID SYSTEM</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">


    {{-- <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">

    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> --}}
    @yield('pagespecificscripts')

    <style>
        .profile-image-box {
            width: 80px;
            /* Set the default width of the profile image box */
            height: 80px;
            /* Set the default height of the profile image box */
            overflow: hidden;
            /* Ensure the image doesn't exceed the box boundaries */
        }

        .profile-image-box img {
            width: 100%;
            /* Ensure the image covers the entire box */
            transition: all 0.3s ease;
            /* Add a smooth transition effect */
        }

        @media (max-width: 768px) {
            .profile-image-box {
                width: 40px;
                /* Set a smaller width when collapsing to fit within the sidebar */
                height: 40px;
                /* Set a smaller height when collapsing to fit within the sidebar */
            }
        }

        #searchInput {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .school-list-container {
            max-height: 200px;
            overflow-y: auto;
        }

        .school-item {
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .school-logo {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .school-name {
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            /* max-width: 150px; */
            /* Adjust the max-width as needed */
            display: inline-block;
            /* Ensure ellipsis works with inline content */
        }
    </style>



</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed pace-primary">

    @yield('modalSection')

    <div class="modal fade" id="addSystemModal" tabindex="-1" role="dialog" aria-labelledby="addSchoolModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSchoolModalLabel">System Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="organization" class="required">Organization Name</label>
                                    <input type="text" class="form-control" id="organization"
                                        placeholder="Enter organization" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone2">Phone</label>
                                    <input type="text" class="form-control" id="phone2" placeholder="Enter Phone"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="text" class="form-control" id="website"
                                        placeholder="Enter Website" required>
                                </div>
                                <div class="form-group">
                                    <label for="logo">Organization Logo</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="logo">
                                            <label class="custom-file-label" for="schoollogo">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success add_systeminfo">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->

        <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
            <ul class="navbar-nav">
                <li class="nav-item">
                    {{-- <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a> --}}
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">
                    {{-- <a class="" data-toggle="dropdown" href="#" aria-expanded="true">
                        <img src="{{ Session::get('schoolinfo')->schoollogo }}" alt="School Logo"
                            class="img-circle elevation-3" style="opacity: .8; height: 35px;">
                    </a> --}}
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2"
                        style="left: inherit; right: 0px;">
                        <div class="input-group">
                            <input type="search" id="searchInput" class="form-control form-control-sm"
                                placeholder="Search school here">
                        </div>

                        {{-- <div class="school-list-container">
                            @foreach (DB::table('schoollist')->where('deleted', 0)->where('id', '!=', Session::get('schoolid'))->where('diocese', 'dioign')->get() as $school)
                                <a href="/viewschool/{{ $school->id }}" class="school-item dropdown-item">
                                    <img src="{{ $school->schoollogo }}" alt="{{ $school->schoolname }} Logo"
                                        class="school-logo">
                                    <span class="school-name">{{ strtoupper($school->schoolname) }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endforeach
                        </div> --}}


                    </div>
                </li>

                <li class="nav-item dropdown sideright">
                    <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt logouthover" style="margin-right: 6px; "></i>
                        <!-- <span class="logoutshow" id="logoutshow"> Logout</span> -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar elevation-4 asidebar"
            style="background: linear-gradient(to bottom right, #03142a, #1b5b8c);">

            <div class="ckheader">
                {{-- <a href="#" class="brand-link sidehead">
                    <img src="{{ Session::get('schoolinfo')->schoollogo }}" alt="Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight"
                        style="position: absolute;top: 6%; color:deepskyblue">{{ Session::get('schoolinfo')->abbr }}</span>
                    <span class="brand-text font-weight-light"
                        style="position: absolute;top: 50%;font-size: 15px!important;color:#ffc107"><b>SCHOOL ADMIN
                            PORTAL</b></span>
                </a> --}}
            </div>


            <div class="sidebar">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="http://essentiel.ck/assets/images/avatars/unknown.png?random=&quot;111523114227&quot;"
                                onerror="this.onerror=null; this.src='http://essentiel.test/assets/images/avatars/unknown.png?random=&quot;111523114227&quot;'"
                                alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
                        </div>
                    </div>
                </div>

                <div class="row  user-panel">
                    <div class="col-md-12 info text-center">
                        <a class=" text-white mb-0 ">
                            @if (auth()->check())
                                {{ strtoupper(auth()->user()->name) }}
                            @endif
                        </a>
                        <h6 class="text-warning text-center" style="font-size: 15px"> CKID ADMIN </h6>
                    </div>
                </div>

                <!-- Include Other Sidebar Content -->
                @include('id_creator_system.inc.sidenav')
            </div>


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




    @include('sweetalert::alert')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    {{-- <script>
        $(document).ready(function() {

            $(document).on('click', '#addSystemModal', function(event) {
                $('#addSystemModal').modal();
            });

            $(document).on('click', '.add_systeminfo', function(event) {
                var organization = $('#organization').val();
                var phone = $('#phone2').val();
                var email = $('#email').val();
                var website = $('#website').val();
                // var logo = $('#logo').val();


                $.ajax({
                    type: 'GET',
                    data: {
                        organization: organization,
                        phone: phone,
                        email: email,
                        website: website,
                    },
                    url: '{{ route('add.systeminfo') }}',
                    success: function(data) {
                        console.log(data);
                    }
                })
            });

        })
    </script> --}}
    @yield('footerjavascript')

    @include('websockets.realtimenotification')

</body>

</html>
