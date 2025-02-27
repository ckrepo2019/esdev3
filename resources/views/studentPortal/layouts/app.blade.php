<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Students Portal</title>

    <script src="{{ asset('dist/Chart.bundle.js') }}" ></script>
    <script src="{{ asset('dist/Chart.bundle.min.js') }}" defer></script>
     
     <script src="{{ asset('js/main.js') }}" defer></script>
     <script src="{{ asset('js/js/jquery-3.4.1.min.js') }} " ></script>
     <script src="{{ asset('js/js/bootstrap.min.js') }} " ></script>
     
     
   
 
     <link href="{{ asset('css/main.css') }}" rel="stylesheet">
 
     <link href="{{ asset('calendar/packages/core/main.css') }}" rel='stylesheet'>
     <link href="{{ asset('calendar/packages/daygrid/main.css') }}" rel='stylesheet'>
     <link href="{{ asset('calendar/packages/timegrid/main.css') }}" rel='stylesheet'>
     
     <script src="{{ asset('calendar/packages/core/main.js') }}" ></script>
 
     <script src="{{ asset('calendar/packages/daygrid/main.js') }}" ></script>
     <script src="{{ asset('calendar/packages/interaction/main.js') }}" ></script>
     <script src="{{ asset('calendar/packages/timegrid/main.js') }}" ></script>

    @if (db::table('schoolinfo')->first()->abbreviation == 'LDCU')
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
    </style>

    <style>
        .card.card-cascade.wider .card-body.card-body-cascade {
            z-index: 1;
            margin-right: 4%;
            margin-left: 4%;
            background: #fff;
            border-radius: 0 0 .25rem .25rem;
            box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
        }

        .view {
            position: relative;
            overflow: hidden;
            cursor: default;
        }

        
        .card.card-cascade.wider .view.view-cascade {
            z-index: 2;
        }
        .card.card-cascade .view.view-cascade.gradient-card-header {
            padding: .3rem 1rem;
            color: #fff;
            text-align: center;
        }
        .card.card-cascade.wider {
            background-color: transparent;
            box-shadow: none;
        }

        .card.card-cascade .view.view-cascade {
            border-radius: .25rem;
            box-shadow: 0 5px 11px 0 rgba(0,0,0,.18), 0 4px 15px 0 rgba(0,0,0,.15);
        }

        .peach-gradient {
            background: linear-gradient(40deg,#ffd86f,#fc6262)!important;
        }   

    </style>
    {{-- architect UI --}}
    <style>
        .dropdown-item {
            border-bottom:1px solid rgba(26,54,126,0.125);
           
        }
        .dropdown-menu {
            padding: 0;
        }

    </style>

  
    

</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div id="calendarModal" class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="position-relative form-group">
                          <label for="eventDate" class="">Date</label>
                          <input disabled name="eventDate" id="eventDate" type="date" class="form-control">
                        </div>
                        <div class="position-relative form-group">
                            <label for="eventTitle" class="">Event Title</label>
                            <input name="eventTitle" id="eventTitle" placeholder="Event Title" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEventButton" data-dismiss="modal" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        
        @include('studentPortal.inc.header')
        <div class="app-main">
            @include('studentPortal.inc.sidenav')
            <div class="app-main__outer">
                <div class="app-main__inner">
                  
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div id="activateModal"></div>
</body>
</html>
