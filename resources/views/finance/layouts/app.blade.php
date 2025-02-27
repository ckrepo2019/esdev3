<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google" content="notranslate">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Essentiel | Finance</title>

    <!-- Scripts -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <link href="{{ asset('assets/css/gijgo.min.css') }}" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('assets/ckicon.ico') }}" type="image/x-icon" />

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="{{ asset('dist/css/adminlte.css') }}" rel="stylesheet">
    {{-- <link type="text/css" href="{{asset('css/fontawesome.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pace-progress/themes/blue/pace-theme-flat-top.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/fontfamily.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/googleapis-font.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/simplePagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\sideheaderfooter.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/croppie/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('plugins/ekko-lightbox/ekko-lightbox.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    @yield('jsUP')
    @yield('headerjavascript')
    @yield('pagespecificscripts')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">
    <div class="wrapper">
        @include('finance.layouts.navbar')
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    @include('general.queuingactionbutton.qab')

                    @yield('content')
                </div>
            </section>
        </div>

        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>

        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('dist/js/pages/dashboard3.js') }}"></script>

        <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('dist/js/adminlte.js') }}"></script>
        <script src="{{ asset('dist/js/demo.js') }}"></script>
        <script src="{{ asset('dist/js/select2.full.min.js') }}"></script>

        <script src="{{ asset('dist/js/jquery.simplePagination.js') }}"></script>
        <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js') }}"></script>
        <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('assets/scripts/gijgo.min.js') }}"></script>
        <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>
        <!-- dropzonejs -->
        <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
        <script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
        <!-- Filterizr-->
        <script src="{{ asset('plugins/filterizr/jquery.filterizr.min.js') }}"></script>

        @if(DB::table('schoolinfo')->first()->snr == 'ldcu')
            <script type="module">
                import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
                Chatbot.init({
                    chatflowid: "53ae11aa-4b02-4955-bdaf-ec1e6d86a940",
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
            $schoolinfo = db::table('schoolinfo')->first();
        @endphp

        @yield('modal')
        @yield('js')
        @yield('footerscripts')
        @yield('footerjavascript')
        @yield('qab_sript')
        <script>
            var school_setup = @json($schoolinfo);

            function get_last_index(tablename) {
                $.ajax({
                    type: 'GET',
                    url: '/monitoring/tablecount',
                    data: {
                        tablename: tablename
                    },
                    success: function(data) {
                        lastindex = data[0].lastindex
                        update_local_table_display(tablename, lastindex)
                    },
                })
            }

            function update_local_table_display(tablename, lastindex) {
                $.ajax({
                    type: 'GET',
                    url: school_setup.es_cloudurl + '/monitoring/table/data',
                    data: {
                        tablename: tablename,
                        tableindex: lastindex
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            process_create(tablename, 0, data)
                        }
                    },
                    error: function() {
                        $('td[data-tablename="' + tablename + '"]')[0].innerHTML = 'Error!'
                    }
                })
            }

            function process_create(tablename, process_count, createdata) {
                if (createdata.length == 0) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Save successfully'
                    })
                    return false;
                }

                var b = createdata[0]
                $.ajax({
                    type: 'GET',
                    url: '/synchornization/insert',
                    data: {
                        tablename: tablename,
                        data: b
                    },
                    success: function(data) {
                        process_count += 1
                        createdata = createdata.filter(x => x.id != b.id)
                        process_create(tablename, process_count, createdata)
                    },
                    error: function() {
                        process_count += 1
                        createdata = createdata.filter(x => x.id != b.id)
                        process_create(tablename, process_count, createdata)
                    }
                })
            }

            $(document).ready(function() {

                setInterval(function() {

                    // $('.pace').css('display','none !important')

                    window.paceOptoins = {
                        ajax: false,
                        restartOnRequestAfter: false,
                    }


                    Pace.ignore(function() {
                        $.ajax({
                            url: '/finance/salaryrateelevation/reloadcount',
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {

                                $('.countsalaryrateelevation').text(data.rateCount)
                                $('.viewolpaycount').text(data.OLpayCount);
                            },
                        });
                    });
                }, 30000);
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

</body>

</html>

{{-- ver 06.27.2020.0922 --}}
{{-- changelog:  - Fix Tuition Entry for College; --}}
