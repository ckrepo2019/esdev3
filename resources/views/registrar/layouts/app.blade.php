<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/css/gijgo.min.css') }}" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Ionicons -->
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/fontfamily.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/googleapis-font.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pace-progress/themes/blue/pace-theme-flat-top.css') }}">


    @if (DB::table('schoolinfo')->first()->snr == 'ldcu')
        <script type="module">
            import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
            Chatbot.init({
                chatflowid: "c8993e2b-1fd3-48f5-93a4-bcc7456c0c1c",
                apiHost: "https://flowisechatbot-nxra.onrender.com",
                chatflowConfig: {
                    // topK: 2
                },
                theme: {
                    button: {
                        backgroundColor: "#EC0C8C",
                        right: 20,
                        bottom: 20,
                        size: 48, // small | medium | large | number
                        dragAndDrop: true,
                        iconColor: "white",
                        customIconSrc: "https://raw.githubusercontent.com/itsnothyun/CK-Resources/main/bot2.png",
                    },
                    tooltip: {
                        showTooltip: false,
                        tooltipMessage: 'Hi There! 👋 How can I help you today?',
                        tooltipBackgroundColor: '#EC0C8C',
                        tooltipTextColor: 'white',
                        tooltipFontSize: 16,
                    },
                    chatWindow: {
                        showTitle: true,
                        title: 'CK Bot',
                        titleAvatarSrc: 'https://raw.githubusercontent.com/itsnothyun/CK-Resources/main/Logo%20Designs.png',
                        showAgentMessages: true,
                        welcomeMessage: 'Hello! This is CK Bot, How can I help you?',
                        errorMessage: 'This is a custom error message',
                        backgroundColor: "#ffffff",
                        height: 700,
                        width: 400,
                        fontSize: 16,
                        poweredByTextColor: "#303235",
                        botMessage: {
                            backgroundColor: "#f7f8ff",
                            textColor: "#303235",
                            showAvatar: true,
                            avatarSrc: "https://raw.githubusercontent.com/itsnothyun/CK-Resources/main/bot3.png",
                        },
                        userMessage: {
                            backgroundColor: "#EC0C8C",
                            textColor: "#ffffff",
                            showAvatar: true,
                            avatarSrc: "https://raw.githubusercontent.com/zahidkhawaja/langchain-chat-nextjs/main/public/usericon.png",
                        },
                        textInput: {
                            placeholder: 'Type your question',
                            backgroundColor: '#ffffff',
                            textColor: '#303235',
                            sendButtonColor: '#EC0C8C',
                            maxChars: 50,
                            maxCharsWarningMessage: 'You exceeded the characters limit. Please input less than 50 characters.',
                            autoFocus: true, // If not used, autofocus is disabled on mobile and enabled on desktop. true enables it on both, false disables it on both.
                            sendMessageSound: true,
                            // sendSoundLocation: "send_message.mp3", // If this is not used, the default sound effect will be played if sendSoundMessage is true.
                            receiveMessageSound: true,
                            // receiveSoundLocation: "receive_message.mp3", // If this is not used, the default sound effect will be played if receiveSoundMessage is true.
                        },
                        feedback: {
                            color: '#303235',
                        },
                        footer: {
                            textColor: '#303235',
                            text: 'Powered by',
                            company: 'Flowise',
                            companyLink: 'https://flowiseai.com',
                        }
                    }
                }
            })
        </script>
    @endif

    @php
        $schoolinfo = DB::table('schoolinfo')->first();
    @endphp
    <style>
        .nav-bg {
            background-color: {!! $schoolinfo->schoolcolor !!} !important;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: {!! $schoolinfo->schoolcolor !!};
        }

        .sidehead {
            background-color: #002833 !important;
        }
    </style>



    @yield('jsUP')
    @yield('pagespecificscripts')
    @yield('headerjavascript')
    @yield('modalSection')

    <style>
        .bg-light-blue {
            background-color: #a0bfdc !important;
        }

        .text-light-blue {
            color: #a0bfdc !important;
        }

        .active-section {
            background-color: #a0bfdc !important;
            border: solid #a0bfdc 1px !important;
        }

        .scroll-area-lg {
            height: 700px;
        }

        .subject {
            font-size: 20px;
        }

        .vertical-nav-menu .widget-content-left a {
            padding: 0;
            height: 1.0rem;
            line-height: 1rem;
        }

        .closed-sidebar .app-sidebar:hover .app-sidebar__inner ul .widget-content-left a {
            text-indent: initial;
            padding: 0;
        }


        @media only screen and (max-width: 600px) {
            .report-card-table {
                width: 500px;
            }

            .scroll-area-lg {
                height: 230px;
            }

        }

        @media (max-width: 991.98px) {
            .sidebar-mobile-open .app-sidebar .app-sidebar__inner ul .widget-content-left a {
                text-indent: initial;
                padding: 0;
            }
        }

        img {
            border-radius: 50%;
        }

        img {
            border-radius: 50%;
        }

        a {
            text-decoration: none;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            border: none !important;
        }

        .small-box {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2) !important;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        #datepicker {
            position: relative !important;
            display: inline-block !important
        }


        .clsDatePicker {
            z-index: 100000;
        }

        #datepicker-container {
            text-align: center;
        }

        #datepicker-center {
            display: inline-block;
            margin: 0 auto;
        }

        .dot {
            height: 10px;
            width: 10px;
            background-color: #bbb;
            border-radius: 20%;
            display: inline-block;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed accent-info" style="height: auto;">
    <div class="wrapper">
        @include('registrar.inc.header')
        @include('registrar.inc.sidenav')
        <div class="content-wrapper" style="min-height: 809px;">
            <section class="content">
                <div class="container-fluid">
                    @include('general.queuingactionbutton.qab')
                    @yield('content')
                </div>
            </section>
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboard3.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/croppie/croppie.css') }}">



    @yield('footerjavascript')
    @yield('footerscripts')
    @yield('qab_sript')
    <script>
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
            });

            $(document).on('click', '.authorizedinput', function() {
                if ($(this).attr('viewtarget') == 'pdf') {
                    var viewtarget = '_blank';
                } else {
                    var viewtarget = '';
                }
                Swal.fire({
                        title: 'School Head',
                        html: '<form action="/registrarschoolforms/updateschoolhead" method="get" id="submitschoolhead">' +
                            '<input type="text" name="schoolhead" class="form-control" placeholder="School Head" required/>' +
                            '</form>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Submit',
                        allowOutsideClick: false
                    })
                    .then((result) => {
                        if (result.value) {
                            event.preventDefault();
                            $('#submitschoolhead').submit()
                        }
                    })
            })
        })
        $('#li-student-management').on('click', function() {
            window.open('/registrar/studentmanagement', '_self')
        })
    </script>

    <script>
        $(document).ready(function() {
            $('.active')[0].scrollIntoView({
                behavior: 'instant',
                block: 'center',
                inline: 'center'
            });
        })
    </script>

    <script>
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
    </script>

    <!-- dropzonejs -->
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
    @include('websockets.realtimenotification')


</body>

</html>
