<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">

@extends('teacher.layouts.app')
<style>
.dataTable{
    font-size:80%;
}
</style>
@section('content')
@php
    use \Carbon\Carbon;
    $now = Carbon::now();
    $comparedDate = $now->toDateString();
@endphp
<div class="row">
    <div class="col-md-4">
        <div class="card p-0">
            <div class="card-header">
                <h3 class="card-title">Notifications</h3>
            </div>
            <div class="card-body table-responsive d-flex p-0" style="height: 450px;">
                <div class="col-md-12">
                    <center>
                        <div id="accordion" class="">
                            @if(isset($notifications))
                                @if (count($notifications)!=0)
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
                                                                        ->leftJoin('teacher','announcements.createdby','=','teacher.userid')
                                                                        ->where('announcements.id',$notification->headerid) 
                                                                        ->get();
                                                                @endphp
                                                                @if(count($content)==0)
                                                                @else
                                                                {{$content[0]->lastname}},{{$content[0]->firstname}} {{$content[0]->middlename[0]}}.
                                                                @endif
                                                            </td>
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
                                                                    @if($comparedDate == $date)
                                                                        Today at {{$time}}
                                                                    @else
                                                                    @php
                                                                        $date = Carbon::create($date)->isoFormat('ddd, MMMM d, YYYY');
                                                                    @endphp
                                                                    {{$date}}&nbsp;&nbsp;&nbsp;{{$time}}
                                                                    @endif
                                                                    <br>
                                                                    @if(count($content)!=0)
                                                                    <strong>{{$content[0]->title}}</strong>
                                                                    @endif
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
                                                        @if(count($content)!=0)
                                                            <strong>{{$content[0]->title}}</strong>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                No notifications
                                @endif
                            @endif
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Class Schedule</h3>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Grade & Section</th>
                                        <th>Subject</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mondayArray as $schedule)
                                        <tr>
                                            <td>
                                                <span hidden>{{$schedule->day}}</span>{{$schedule->description}}
                                            </td>
                                            <td>{{$schedule->levelname}} -
                                                @if(isset($schedule->sectionname))
                                                {{$schedule->sectionname}}
                                                @endif
                                            </td>
                                            <td>{{$schedule->subjdesc}}</td>
                                            <td>{{$schedule->stime}} - {{$schedule->etime}}</td>
                                            <td>{{$schedule->roomname}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($tuesdayArray as $schedule)
                                        <tr>
                                            <td>
                                                <span hidden>{{$schedule->day}}</span>{{$schedule->description}}
                                            </td>
                                            <td>{{$schedule->levelname}} -
                                                @if(isset($schedule->sectionname))
                                                {{$schedule->sectionname}}
                                                @endif
                                            </td>
                                            <td>{{$schedule->subjdesc}}</td>
                                            <td>{{$schedule->stime}} - {{$schedule->etime}}</td>
                                            <td>{{$schedule->roomname}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($wednesdayArray as $schedule)
                                        <tr>
                                            <td>
                                                <span hidden>{{$schedule->day}}</span>{{$schedule->description}}
                                            </td>
                                            <td>{{$schedule->levelname}} -
                                                @if(isset($schedule->sectionname))
                                                {{$schedule->sectionname}}
                                                @endif
                                            </td>
                                            <td>{{$schedule->subjdesc}}</td>
                                            <td>{{$schedule->stime}} - {{$schedule->etime}}</td>
                                            <td>{{$schedule->roomname}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($thursdayArray as $schedule)
                                        <tr>
                                            <td>
                                                <span hidden>{{$schedule->day}}</span>{{$schedule->description}}
                                            </td>
                                            <td>{{$schedule->levelname}} -
                                                @if(isset($schedule->sectionname))
                                                {{$schedule->sectionname}}
                                                @endif
                                            </td>
                                            <td>{{$schedule->subjdesc}}</td>
                                            <td>{{$schedule->stime}} - {{$schedule->etime}}</td>
                                            <td>{{$schedule->roomname}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($fridayArray as $schedule)
                                        <tr>
                                            <td>
                                                <span hidden>{{$schedule->day}}</span>{{$schedule->description}}
                                            </td>
                                            <td>{{$schedule->levelname}} -
                                                @if(isset($schedule->sectionname))
                                                {{$schedule->sectionname}}
                                                @endif
                                            </td>
                                            <td>{{$schedule->subjdesc}}</td>
                                            <td>{{$schedule->stime}} - {{$schedule->etime}}</td>
                                            <td>{{$schedule->roomname}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
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
            <!-- /.col -->
</div>
{{-- </div> --}}
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
// $('table').each(function() {
//     $('table').find('td').each(function() {
//       var $this = $(this);
//       var col = $this.index();
//       var html = $this.html();
//       var row = $(this).parent()[0].rowIndex; 
//       var span = 1;
//       var cell_above = $($this.parent().prev().children()[col]);

//       // look for cells one above another with the same text
//       while (cell_above.html() === html) { // if the text is the same
//         span += 1; // increase the span
//         cell_above_old = cell_above; // store this cell
//         cell_above = $(cell_above.parent().prev().children()[col]); // and go to the next cell above
//       }

//       // if there are at least two columns with the same value, 
//       // set a new span to the first and hide the other
//       if (span > 1) {
//         // console.log(span);
//         $(cell_above_old).attr('rowspan', span);
//         $this.hide();
//       }
      
//     });
//   });


    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
    });
    $(".notification").click(function () {
        var notification_id = $(this).attr("name");
        var parent_class = $(this).parent().parent();
        parent_class.css('border','solid','#218838');
        $.ajax({
            url: '/teacherNotification/'+notification_id,
            type:"GET",
            dataType:"json",
            data:{
                getStudents:'getGradeLevel'
            },
            success:function(data) {
                $(".badge-notify").text(data[0]);
                if(data[0]==0){
                    $(".badge-notify").hide();
                    $(".card"+data[2]).css('color','#218838');
                }
                $.each(data[1], function(key, value){
                    if(value.status){
                        $(".notification").click(function () {
                            $(".notif"+value.id).css('color','black');
                            $(".card"+data[2]).css('color','white');
                            // #218838
                        });
                    }
                });
            }
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
