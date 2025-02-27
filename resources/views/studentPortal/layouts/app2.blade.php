<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    @if (DB::table('schoolinfo')->first()->abbreviation == 'LDCU')
        <script type="module">
            import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
            Chatbot.init({
                chatflowid: "c58e7c2d-4d0a-4d53-861a-814c5853436f",
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
    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }
    </style>
    @yield('pagespecificscripts')

    @php
        $randomnum = rand(1, 4);

        if (auth()->user()->type == 9) {
            $studid = DB::table('studinfo')
                ->where('sid', str_replace('P', '', auth()->user()->email))
                ->first();
        } else {
            $studid = DB::table('studinfo')
                ->where('sid', str_replace('S', '', auth()->user()->email))
                ->first();
        }

        if ($studid->gender == 'FEMALE') {
            $avatar = 'avatars/S(F) ' . $randomnum . '.png';
        } else {
            $avatar = 'avatars/S(M) ' . $randomnum . '.png';
        }

        $picurl =
            str_replace('jpg', 'png', $studid->picurl) .
            '?random="' .
            \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
            '"';

    @endphp
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item dropdown notification">
                    <a class="nav-link notnum" data-toggle="dropdown" href="#">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notificationholder">
                    </div>
                </li> --}}

                <li class="nav-item dropdown user user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                            class="user-image img-circle elevation-2 alt="User Image">
                        <span class="hidden-xs">{{ strtoupper(auth()->user()->name) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header nav-bg" style="height:auto !important">
                            <img src="{{ asset($picurl) }}"
                                onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                                class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ strtoupper(auth()->user()->name) }}
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="/student/enrollment/record/profile" class="btn btn-default ">Profile</a>
                            <a class="nav-link btn btn-default float-right" data-toggle="dropdown"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        @include('studentPortal.modal.calendarModal')
        <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
            <div class="ckheader">
                <a href="#" class="brand-link sidehead">
                    @php

                    @endphp
                    <img src="{{ asset(DB::table('schoolinfo')->first()->picurl . '?random="' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')) }}"
                        alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"
                        style="position: absolute;top: 6%;">{{ DB::table('schoolinfo')->first()->abbreviation }}</span>
                    <span class="brand-text font-weight-light"
                        style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>STUDENT'S
                            PORTAL</b></span>
                </a>
            </div>
            <div class="sidebar">

                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset($picurl) }}"
                                onerror="this.onerror=null; this.src='{{ asset($avatar) }}'" alt="User Image"
                                width="100%" style="width:130px; border-radius: 12% !important;">
                        </div>
                    </div>
                </div>
                <div class="row  user-panel">
                    <div class="col-md-12 info text-center">
                        <a class=" text-white mb-0 ">{{ strtoupper($studid->firstname) }}
                            {{ strtoupper($studid->lastname) }}</a>
                        <h6 class="text-warning text-center">{{ auth()->user()->email }}</h6>
                    </div>
                </div>

                @include('studentPortal.inc.sidenav')
            </div>
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    @yield('footerscript')
    @yield('footerjavascript')
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    @include('sweetalert::alert')

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

    @include('websockets.realtimenotification')
</body>

</html>
