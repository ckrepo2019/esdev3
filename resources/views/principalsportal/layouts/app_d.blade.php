<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Principal's Portal</title>

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

<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <script src="{{ asset('js/principal.js') }}" ></script>
    
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
            a {
                color: #e05a36;
            }
            {
                background-color: transparent;
            }
            a {
                color: #337ab7;
                text-decoration: none;
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
                *margin-left: -100px;/*ie7*/
            }
            .scrolling td, th {
                vertical-align: top;
                padding: 10px;
                max-width: 50%;
              
            }
            .scrolling th {
                position: absolute;
                *position: relative; /*ie7*/
                left: 20px;
                width: 180px;
                height: 43px;
            }
            .inner {
                overflow-x: auto;
                overflow-y: visible;
                margin-left: 180px;
                
                
            }
            @media only screen{
                .inner{
                    width: auto; 
                    max-width: 720px;
                }
               
            }
            @media (max-width:1024px){
                .inner{
                    width: auto; 
                    max-width: 660px;
                }
               
            }
        </style>

        <style>
            .scroll-area-sm{
                height: 180px !important;
            }

           @media only screen and (max-width: 600px){
                .scroll-area-sm{
                    height: 230px !important;
                }
                #myChart{
                    margin-top:25px;
                }
            }

        </style>

   
    

</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('principalsportal.inc.header')
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form> 
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@sm</span>
                                    </div>
                                    <input type="text" class="form-control">
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        
        @if(Request::url() == url('/principalPortalSchoolCalendar'))
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
                            <div class="position-relative form-group">
                                <label  for="eventType" class="">Event Type</label>
                                <select class="mb-2 form-control" name="eventType" id="eventType">
                                    <option  disabled selected value="">Select Event Type</option>
                                    @foreach($eventypes as $eventype)
                                        <option value="{{$eventype->id}}">{{$eventype->typename}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="saveEventButton" data-dismiss="modal" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif












        <div class="app-main">
            @include('principalsportal.inc.sidenav')
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
                @yield('footer')
            </div>
        </div>
    </div>
    @yield('footer_script')
</body>

</html>
