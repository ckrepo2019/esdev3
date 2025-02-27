<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tesda Trainer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: unset !important;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            border: 0 !important;
        }

        .shadow-none {
            box-shadow: none !important;
            border: 1px solid rgb(224, 222, 222) !important;
        }

        body {
            /* font-family: "Source Sans Pro", sans-serif; */
            /* font-family: "Source Sans 3", sans-serif; */
            /* font-family: "Roboto", sans-serif; */
            /* font-family: "Poppins", sans-serif; */
            /* font-size: 12px !important; */
        }

        aside {
            /* font-family: "Roboto", sans-serif; */
        }
    </style>

    @yield('pagespecificscripts')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">
    @php
        $randomnum = rand(1, 4);
        $avatar =
            'assets/images/avatars/unknown.png' .
            '?random="' .
            \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
            '"';
        $picurl = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()->picurl;
        $picurl =
            str_replace('jpg', 'png', $picurl) .
            '?random="' .
            \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
            '"';
    @endphp
    @yield('modalSection')
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            {{-- <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge" id="notification_count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification_holder">
                        <a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All
                            Messages</a>
                    </div>
                </li>

                <li class="nav-item dropdown sideright">
                    <a class="nav-link" data-toggle="dropdown" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt logouthover" style="margin-right: 6px; color: #fff"></i>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul> --}}
            <ul class="navbar-nav ml-auto">
                @include('components.headerprofile')
            </ul>
        </nav>

        @include('tesda_trainer.inc.sidenav')

        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>

    @include('sweetalert::alert')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- dropzonejs -->
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        $(document).ready(function() {
            var keysPressed = {}
            document.addEventListener("keydown", function(event) {
                keysPressed[event.key] = true;
                if (keysPressed['g'] && (event.key === '1' || event.key === '1')) {
                    window.location = '/changeUser/1'
                }
            });
            document.addEventListener('keyup', (event) => {
                keysPressed = {}
            });
        })

        function notify(code, message) {
            Toast.fire({
                type: code,
                title: message,
            });

        }
    </script>

    <script>
        $(document).ready(function() {
            renderAllMessages2();

            $(document).on('click', '#logout', function() {
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

        function getAllMessages2() {

            return $.ajax({
                type: "GET",
                data: {
                    header: 1,
                },
                url: "/hr/settings/notification/getAllMessages",
            });
        }

        function getAllMessages3() {

            return $.ajax({
                type: "GET",
                data: {
                    header2: 1,
                },
                url: "/hr/settings/notification/getAllMessages",
            });
        }

        function renderAllMessages2() {
            getAllMessages2().then(function(data) {
                var renderHtml = data.length > 0 ? data.map(entry => {
                    return ` <a class="media" href="/hr/settings/notification/index?id=${entry.data_id}">
                            <img src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}"  alt="User Avatar" onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'"  class="img-size-50 img-circle mr-3">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    ${entry.name}
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">
                                    ${entry.additionalmessage.length > 50 ? entry.additionalmessage.substring(0, 50) + '...' : entry.additionalmessage}
                                </p>
                                <p class="text-sm text-muted">
                                    <i class="far fa-clock mr-1" style="color: gray !important;"></i>
                                    ${entry.time_ago}
                                </p>
                            </div>

                            </div>
                        </a>
                        <div class="dropdown-divider"></div>`;

                }).join('') : `<div class="text-center"><p class="text-muted">No message found</p></div>`;
                $('#notification_holder').prepend(renderHtml);
            })

            getAllMessages3().then(function(data) {

                var count = data.length;
                $('#notification_count').text(count);

            })
        }
    </script>
    @yield('footerjavascript')

    {{-- @include('websockets.realtimenotification') --}}
</body>

</html>
