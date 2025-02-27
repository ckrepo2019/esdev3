<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
@extends('registrar.layouts.app')

@section('content')
    @php
        use \Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
    @endphp
    <style>
        h1          { opacity: 0.5; }
        .noselect   { -webkit-touch-callout: none; /* iOS Safari */ -webkit-user-select: none; /* Safari */ -khtml-user-select: none; /* Konqueror HTML */ -moz-user-select: none; /* Old versions of Firefox */ -ms-user-select: none; /* Internet Explorer/Edge */ user-select: none; /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */ }
    </style>
    <div class="row noselect">
        <div class="col-md-4">
            <div class="card p-0">
                <div class="card-header">
                    <h3 class="card-title">Notifications</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive d-flex p-0" style="height: 450px;">
                    <div class="col-md-12">
                        @if($messageWarning == true)
                            <br>
                            <center>{{$messageWarning}}</center>
                        @else
                            <center>
                                <div id="accordion" class="">
                                    <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                                    @if(isset($notifications))
                                        @foreach($notifications as $notification)
                                            <div class="card card{{$notification->id}}">
                                                <div class="card-header p-3" >
                                                    <a data-toggle="collapse" name="{{$notification->id}}" href="#collapseOne{{$notification->id}}" data-parent="#accordion" class="collapsed notification" aria-expanded="false" style="font-size: 11px;">
                                                        @php
                                                            $date = substr($notification->created_at, 0, 10);
                                                            $time = substr($notification->created_at, -8);
                                                        @endphp
                                                        <table style="width: 100%;font-size:11px;b">
                                                            <tr>
                                                                <td style="width:6%;">
                                                                    @if ($notification->status == '0')
                                                                        <i class="fa fa-circle notif{{$notification->id}}" style="color:greenyellow"></i>
                                                                    @else
                                                                        <i class="fa fa-circle notif{{$notification->id}}" style="color:black"></i>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $content = DB::table('announcements')
                                                                                ->join('teacher','announcements.createdby','=','teacher.userid')
                                                                                ->where('announcements.id',$notification->headerid)   
                                                                                ->get();
                                                                    @endphp
                                                                    {{-- {{$content}} --}}
                                                                    {{$content[0]->lastname}},{{$content[0]->firstname}} {{$content[0]->middlename[0]}}.
                                                                </td>
                                                                <td>Today at {{$time}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td colspan="2">
                                                                    @if ($notification->type == '1')
                                                                        @php
                                                                            $content = DB::table('announcements')
                                                                                    ->join('teacher','announcements.createdby','=','teacher.userid')
                                                                                    ->where('announcements.id',$notification->headerid)   
                                                                                    ->get();
                                                                        @endphp
                                                                            <strong>{{$content[0]->title}}</strong>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </a>
                                                </div>
                                                <div id="collapseOne{{$notification->id}}" class="panel-collapse in collapse" style="text-align: left;">
                                                    <div class="card-body">
                                                        @if ($notification->type == '1')
                                                            @php
                                                                $content = DB::table('announcements')
                                                                        ->join('teacher','announcements.createdby','=','teacher.userid')
                                                                        ->where('announcements.id',$notification->headerid)   
                                                                        ->get();
                                                            @endphp
                                                            {{$content[0]->content}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </center>
                        @endif
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-8">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-success">
                @php
                    $words = explode(" ", 'Old Students');
                    $os = "";
                    foreach ($words as $w) {
                        $os .= $w[0];
                    }
                @endphp
                <span class="info-box-icon"><h1>{{$os}}</h1></span>
                <div class="info-box-content">
                    <center>
                        <span class="info-box-text">
                            <strong>Old Students</strong>
                        </span>
                    </center>
                    <table class="table p-0 m-0">
                        <tr>
                            {{-- <td><span class="info-box-text">Old Students</span></td> --}}
                            <td>Elementary</td>
                            <td>JHS</td>
                            <td>SHS</td>
                        </tr>
                        <tr>
                            {{-- <td></td> --}}
                            <td>
                                <span class="info-box-number" >
                                @if (isset($oldElemStudents))
                                {{$oldElemStudents}}
                                @endif
                            </span>
                            </td>
                            <td>
                                <span class="info-box-number" >
                                    @if (isset($oldJuniorStudents))
                                    {{$oldJuniorStudents}}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="info-box-number" >
                                    @if (isset($oldSeniorStudents))
                                    {{$oldSeniorStudents}}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-warning">
                @php
                    $words = explode(" ", 'Transferee Students');
                    $ts = "";
                    foreach ($words as $w) {
                        $ts .= $w[0];
                    }
                @endphp
                <span class="info-box-icon">
                    <h1>{{$ts}}</h1>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Transferee Students</span>
                    <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-info">
                @php
                    $words = explode(" ", 'New Students');
                    $ns = "";
                    foreach ($words as $w) {
                        $ns .= $w[0];
                    }
                @endphp
                <span class="info-box-icon">
                    <h1>{{$ns}}</h1>
                </span>
                <div class="info-box-content">
                    <center>
                        <span class="info-box-text">
                            <strong>New Students</strong>
                        </span>
                    </center>
                    <table class="table p-0 m-0">
                        <tr>
                            {{-- <td><span class="info-box-text">Old Students</span></td> --}}
                            <td>Elementary</td>
                            <td>JHS</td>
                            <td>SHS</td>
                        </tr>
                        <tr>
                            {{-- <td></td> --}}
                            <td>
                                <span class="info-box-number" >
                                @if (isset($newElemStudents))
                                {{$newElemStudents}}
                                @endif
                            </span>
                            </td>
                            <td>
                                <span class="info-box-number" >
                                    @if (isset($newJuniorStudents))
                                    {{$newJuniorStudents}}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="info-box-number" >
                                    @if (isset($newSeniorStudents))
                                    {{$newSeniorStudents}}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /.info-box-content -->
            </div>
            <div class="info-box mb-3 bg-primary">
                <span class="info-box-icon">
                    <i class="fa fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Teachers</span>
                    <span class="info-box-number">
                        @if (isset($activeTeachers))
                            {{$activeTeachers}}
                        @endif
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">School Calendar</h3>
                </div>
                <div class="card-body p-0" style="overflow:scroll;">
                <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script>
        $(function () {
            $("#example1").DataTable();
        });
        $(".notification").click(function () {
            var notification_id = $(this).attr("name");
            $.ajax({
                url: '/registrarNotification/'+notification_id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $(".badge-notify").text(data[0]);
                    
                    if(data[0]==0){
                        $(".badge-notify").hide();
                    }
                    $.each(data[1], function(key, value){
                        console.log(value.status);
                        if(value.status){
                            $(".notif"+value.id).css('color','black');
                        }

                    });
                },
            });
        }); 
        
        $(function () {
            /* initialize the external events
            -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {
                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }
                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex        : 1070,
                        revert        : true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    })
                })
            }   
            ini_events($('#external-events div.external-event'))
            /* initialize the calendar
            -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear()

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendarInteraction.Draggable;

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------


            var calendar = new Calendar(calendarEl, {
                plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                //Random default events
                events    : [
                    {
                    title          : 'All Day Event',
                    start          : new Date(y, m, 1),
                    backgroundColor: '#f56954', //red
                    borderColor    : '#f56954' //red
                    },
                    {
                    title          : 'Long Event',
                    start          : new Date(y, m, d - 5),
                    end            : new Date(y, m, d - 2),
                    backgroundColor: '#f39c12', //yellow
                    borderColor    : '#f39c12' //yellow
                    },
                    {
                    title          : 'Meeting',
                    start          : new Date(y, m, d, 10, 30),
                    allDay         : false,
                    backgroundColor: '#0073b7', //Blue
                    borderColor    : '#0073b7' //Blue
                    },
                    {
                    title          : 'Lunch',
                    start          : new Date(y, m, d, 12, 0),
                    end            : new Date(y, m, d, 14, 0),
                    allDay         : false,
                    backgroundColor: '#00c0ef', //Info (aqua)
                    borderColor    : '#00c0ef' //Info (aqua)
                    },
                    {
                    title          : 'Birthday Party',
                    start          : new Date(y, m, d + 1, 19, 0),
                    end            : new Date(y, m, d + 1, 22, 30),
                    allDay         : false,
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor    : '#00a65a' //Success (green)
                    },
                    {
                    title          : 'Click for Google',
                    start          : new Date(y, m, 28),
                    end            : new Date(y, m, 29),
                    url            : 'http://google.com/',
                    backgroundColor: '#3c8dbc', //Primary (light-blue)
                    borderColor    : '#3c8dbc' //Primary (light-blue)
                    }
                ],
                editable  : true,
                droppable : true, // this allows things to be dropped onto the calendar !!!
                drop      : function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                    // if so, remove the element from the "Draggable Events" list
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                }    
            });
            calendar.render();
        })
    </script>
@endsection
