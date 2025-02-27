<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Parent's Portal</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="{{ asset('js/main.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <script src="{{ asset('js/js/jquery-3.4.1.min.js') }} " ></script>
    <script src="{{ asset('js/js/bootstrap.min.js') }} " ></script>
    

    <link href="{{ asset('calendar/packages/core/main.css') }}" rel='stylesheet'>
    <link href="{{ asset('calendar/packages/daygrid/main.css') }}" rel='stylesheet'>
    <link href="{{ asset('calendar/packages/timegrid/main.css') }}" rel='stylesheet'>
    
    <script src="{{ asset('calendar/packages/core/main.js') }}" ></script>

    <script src="{{ asset('calendar/packages/daygrid/main.js') }}" ></script>
    <script src="{{ asset('calendar/packages/interaction/main.js') }}" ></script>
    <script src="{{ asset('calendar/packages/timegrid/main.js') }}" ></script>


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
        @include('parentsportal.inc.header')
        <div class="app-main">
            @include('parentsportal.inc.sidenav')
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
