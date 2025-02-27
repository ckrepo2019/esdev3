<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Principal's Portal</title>

    @php
        $schoolinfo = DB::table('schoolinfo')->first();
    @endphp

    <link href="{{ asset('assets/css/gijgo.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightBSColors.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>

    @if (db::table('schoolinfo')->first()->abbreviation == 'LDCU')
        <script type="module">
            import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
            Chatbot.init({
                chatflowid: "f42b9b1b-6218-429b-b8a1-fe7d858ad74d",
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
    <link rel="stylesheet" href="{{ asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">

    <style>
        .nav-bg {
            background-color: {!! $schoolinfo->schoolcolor !!} !important;
            color: white;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: {!! $schoolinfo->schoolcolor !!};
        }

        .sidehead {
            background-color: #002833 !important;
        }
    </style>
    @yield('pagespecificscripts')

    @yield('headerjavascript')
    <style>
        .dropdown-toggle::after {
            display: none;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
        }
    </style>


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

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
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
    </style>


    <style>
        .vc_column_container {
            padding-left: 0;
            padding-right: 0;
        }

        .vc_user_item.default {
            padding: 10px;
            position: relative;
        }


        .vc_user_item.default .vc_user_item_content {
            /* border-radius: 0 0 4px 4px; */
            overflow: hidden;
            background: #fff;
            position: absolute;
            width: calc(100% - 20px);
            bottom: 9px;
            padding: 5px 10px 5px 10px;
            text-align: center;
            background-size: cover;
            background-position: center;
            -webkit-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.1);
            box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.1);
        }

        .vc_user_item.default .vc_user_item_avatar {
            /* border-radius: 4px 4px 0 0; */
            overflow: hidden;
            -webkit-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.1);
            box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.1);
            padding-bottom: 35px;
            height: 290px;
        }


        .vc_user_item.default .vc_user_item_avatar img {
            width: 100%;
        }

        .entry-content img {
            max-width: 100%;
            height: auto;

        }

        img {
            vertical-align: middle;

        }

        img {
            border: 0;

        }

        .vc_user_item.default .vc_user_item_content .vc_user_item_name a {
            color: #222;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: none;
        }

        .vc_user_item.default .vc_user_item_content .vc_user_item_class {
            font-size: 14px;
            color: #e05a36;
        }

        .vc_user_item.default .vc_user_item_content .vc_user_item_name a:hover {
            color: #e05a36;
        }

        .vc_user_item.default:hover .vc_user_item_avatar a:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 0;
            opacity: 1;
            -wekbit-transition: all 0.4s;
            -moz-transition: all 0.4s;
            -o-transition: all 0.4s;
            transition: all 0.4s;
            border-radius: 0 0 4px 4px;
        }

        .vc_user_item.default:hover .vc_user_item_avatar {
            position: relative;
        }
    </style>
    <style>
        .scrolling table {
            table-layout: inherit;
            *margin-left: -150px;
            /*ie7*/
        }

        .scrolling td,
        th {
            vertical-align: top;
            padding: 10px;
            max-width: 50%;

        }

        .scrolling th {
            position: absolute;
            *position: relative;
            /*ie7*/
            left: 20px;
            width: 230px;
            height: 43px;
        }

        .inner {
            overflow-x: auto;
            overflow-y: visible;
            /* margin-left: 250px; */


        }

        @media only screen {
            .inner {
                width: auto;
                max-width: 716px;
            }

        }

        @media (max-width:1024px) {
            .inner {
                width: auto;
                max-width: 660px;
            }

        }
    </style>

    <style>
        .scroll-area-sm {
            height: 180px !important;
        }

        @media only screen and (max-width: 600px) {
            .scroll-area-sm {
                height: 230px !important;
            }

            #myChart {
                margin-top: 25px;
            }
        }
    </style>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed pace-primary">
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
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark pace-primary nav-bg">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                @include('components.headerprofile')
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4 ">
            <div class="ckheader">
                <a href="#" class="brand-link nav-bg">
                    <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">
                        {{ DB::table('schoolinfo')->first()->abbreviation }}</span>
                    <span class="brand-text font-weight-light"
                        style="position: absolute;top: 50%;font-size: 20px!important;"><b>PRINCIPAL'S PORTAL</b></span>
                </a>
            </div>

            <div class="sidebar ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset($picurl) }}"
                                onerror="this.onerror=null; this.src='{{ asset($avatar) }}'" alt="User Image"
                                width="100%" style="width:130px; border-radius: 12% !important;">
                        </div>
                    </div>
                </div>
                <div class="row  user-panel">
                    <div class="col-md-12 info text-center">
                        <a class=" text-white mb-0 ">{{ auth()->user()->name }}</a>
                        <h6 class="text-warning text-center">{{ auth()->user()->email }}</h6>
                    </div>
                </div>
                @include('principalsportal.inc.sidenav')
            </div>
        </aside>
        <div class="content-wrapper">
            @yield('modalSection')
            <section class="content pt-0">
                @include('general.queuingactionbutton.qab')
                @yield('content')
            </section>
        </div>
    </div>


    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

    <script>
        if ($(window).width() < 1024) {
            $('body').addClass('sidebar-collapse');
        } else {
            $('body').removeClass('sidebar-collapse');
        }
    </script>

    <script>
        $(document).ready(function() {
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

    @yield('footerjavascript')
    @yield('footerscripts')
    @include('sweetalert::alert')
    @yield('qab_sript')




</body>

</html>
