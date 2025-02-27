<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>College Instructor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    @if (DB::table('schoolinfo')->first()->abbreviation == 'LDCU')
        <script type="module">
            import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
            Chatbot.init({
                chatflowid: "a069efe1-72a4-4c26-9a13-bf3e30806d3a",
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
        :root {
            --school-color: {{ $schoolinfo->schoolcolor }};
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .nav-bg {
            background-color: var(--school-color) !important;
        }
        .nav-link, .nav-header {
        color: #343A30 !important;
    }

    .nav-link.active{
        color: #F0F0F0 !important;
        background: #787c7f !important;
    }

    .user-panel {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .brand-link {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .side li i {
        font-size: 14px !important;
        color: #343A30 !important;
    }
    .side li:hover .udernavs p {
        transition: none;
        font-size: 15px;
        padding-left: none;
    }
    .side li:hover i {
        color: #343A30 !important;
    }
        .nav-link.active .nav-icon {
            color: white !important;
        }
        .sidebar {
        overflow-y: auto; /* Enable scrolling */
        max-height: 100vh; /* Ensure sidebar has limited height */
        position: relative;
    }
    .sidehead {
        background-color: #002833 !important;
    }
    .status-dot {
        height: 7px;
        width: 7px;
        background-color: #ffffff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        100% {
            transform: scale(1);
        }
    }
    </style>


    @yield('pagespecificscripts')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light nav-bg">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <span class="ml-2 text-white" style="font-size: 12px">
                    <div class="d-flex align-items-center">
                        <span class="status-dot mb-0"></span>
                        <strong class="text-white">Active</strong>
                    </div>
                    <b>SY: @php
                        $sydesc = DB::table('sy')->where('isactive', 1)->first();
                    @endphp {{ $sydesc->sydesc }} |
                    @php
                        $semester = DB::table('semester')->where('isactive', 1)->first();
                    @endphp {{ $semester->semester }}</b>
                </span>
            </ul>
            <div class="form-inline ml-3">
                <div class="input-group input-group-sm">

                </div>
            </div>
            <ul class="navbar-nav ml-auto">
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
                        onclick="event.preventDefault(); $('#logout-form').submit();">
                        <i class="fas fa-sign-out-alt logouthover" style="margin-right: 7px; color: #fff"></i>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar" style="background-color: white !important">
            <div class="ckheader">
                <a href="/home" class="brand-link nav-bg text-white">
                    <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8; width: 33px; height: 33px;"
                        onerror="this.src='{{ asset('assets/images/department_of_Education.png') }}'">
                        <span class="brand-text font-weight-light"
                        style="position: absolute;top: 6%;">{{ DB::table('schoolinfo')->first()->abbreviation }}</span>
                    <span class="text-white font-weight-light"
                        style="position: absolute;top: 50%;font-size: 16px!important;"><b>COLLEGE
                            INSTRUCTOR</b></span>
                </a>
            </div>
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
            <div class="sidebar">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset($picurl) }}"
                            onerror="this.onerror=null; this.src='{{ asset($avatar) }}';"
                            alt="User Image"
                            style="max-width:130px; width:100%; height:auto; aspect-ratio:1/1; border-radius:20px !important; object-fit:cover; background:#f0f0f0;">
                        </div>
                    </div>
                </div>
                <div class="row  user-panel">
                    <div class="col-md-12 info text-center">
                        <h6 class="mb-0 ">{{ auth()->user()->name }}</h6>
                        <h6 class="text-center">{{ auth()->user()->email }}</h6>
                    </div>
                </div>
                @include('ctportal.inc.sidenav')
            </div>
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>




    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    {{-- @include('websockets.realtimenotification') --}}
    {{-- <script>
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

        function renderAllMessages2(){

            getAllMessages2().then(function (data) {


                var renderHtml = data.length > 0 ? data.map(entry => {
                return ` <a class="media" href="/hr/settings/notification/index?id=${entry.data_id}">
                            <img src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}"  alt="User Avatar" onerror="this.onerror = null; this.src='{{asset('dist/img/download.png')}}'"  class="img-size-50 img-circle mr-3">
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


            getAllMessages3().then(function (data) {

                var count = data.length;
                $('#notification_count').text(count);

            })

        }



        $(document).ready(function(){

            renderAllMessages2();

          get_pending_grades()

          function move_pending() {
            $('#pending_grade_count').effect( "shake", {distance  : 2}, "slow" )
            setTimeout( move_pending, 1000);
          }

          function get_pending_grades(){

            var teacherid = '{{DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id}}'

            $.ajax({
                type:'GET',
                url:'/college/assignedsubj',
                data:{
                  teacherid:teacherid
                },
                success:function(data) {
                  var count_pending_grade = 0
                  $.each(data,function(a,b){
                    if(b.prelimstatus == 4){
                        count_pending_grade+=1
                    }
                    else if(b.midtermstatus == 4){
                      count_pending_grade+=1
                    }
                    else if(b.prefistatus == 4){
                      count_pending_grade+=1
                    }
                    else if(b.finalstatus == 4){
                      count_pending_grade+=1
                    }

                    if(count_pending_grade == 0){
                      $('#pending_grade_count').text('')
                    }else{
                      $('#pending_grade_count').text(count_pending_grade)
                    }


                    move_pending()

                })
                }
            })

          }
      })
    </script> --}}

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
        window.onload = function () {
    const activeItem = document.querySelector(".sidebar .active");

    if (activeItem) {
        setTimeout(function () {
            activeItem.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }, 100);
    }

    // Monitor window resize events to ensure the scroll stays in place
    window.addEventListener("resize", () => {
        const activeItem = document.querySelector(".sidebar .active");
        if (activeItem) {
            activeItem.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    });
};
    </script>

    <!-- dropzonejs -->
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
    @include('websockets.realtimenotification')

    @yield('footerscript')
    @yield('footerjavascript')

</body>

</html>
