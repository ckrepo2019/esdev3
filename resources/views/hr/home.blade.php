
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
@extends('hr.layouts.app')

@section('content')

<style>
    
    .donutTeachers{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  28% 60%;
        background-size: 30%;
    }
    .donutStudents{
        margin-top: 90px;
        margin: 0 auto; 
        /* background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  28% 60%; */
        background-size: 30%;
    }
    .fc-header-toolbar{
        padding: 1px;
    }
    .card{
        box-shadow: unset !important;
        box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%) !important;
    }
    .form-control {
        padding: 0px !important;
        height: unset !important;
    }
    .btn-group-vertical>.btn.active, .btn-group-vertical>.btn:active, .btn-group-vertical>.btn:focus, .btn-group>.btn.active, .btn-group>.btn:active, .btn-group>.btn:focus {
    z-index: unset;
}
.fc-view, .fc-view>table{
    z-index: unset;
}
</style>
    @php
        use \Carbon\Carbon;
        use App\Models\HR\HREmployeeAttendance;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
        
        $employees = DB::table('teacher')
            ->where('deleted','0')
            ->where('isactive','1')
            ->orderBy('lastname','asc')
            ->get();

        if(count($employees) > 0)
        {
            foreach($employees as $employee)
            {
                $employee->lastactivity = HREmployeeAttendance::getattendance(date('Y-m-d'),$employee)->lastactivity;
            }
        }
        $departments = Db::table('hr_departments')
            ->where('hr_departments.deleted','0')
            ->orderBy('department','asc')
            ->get();
        $designations = Db::table('usertype')
            ->select(
                'usertype.id',
                'usertype.utype as designation'
                )
            ->where('usertype.deleted','0')
            ->where('usertype.utype','!=','PARENT')
            ->where('usertype.utype','!=','STUDENT')
            ->where('usertype.utype','!=','SUPER ADMIN')
            ->get();
    @endphp
    <style>
        h1          { opacity: 0.5; }
        .noselect   { -webkit-touch-callout: none; /* iOS Safari */ -webkit-user-select: none; /* Safari */ -khtml-user-select: none; /* Konqueror HTML */ -moz-user-select: none; /* Old versions of Firefox */ -ms-user-select: none; /* Internet Explorer/Edge */ user-select: none; /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */ }
        .card{
            border: none;
        }
    </style>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-lg" style="color: #004085;
              background-color: #cce5ff;
              border-color: #b8daff;">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Employees</span>
                  <span class="info-box-number">{{count($employees)}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-lg" style="color: #856404;
              background-color: #fff3cd;
              border-color: #ffeeba;">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Attendance</span>
                  <span class="info-box-number">{{collect($employees)->where('lastactivity','!=','')->where('lastactivity','!=',null)->count()}}/{{count($employees)}} Present</span>
  
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-lg" style="color: #155724;
              background-color: #d4edda;
              border-color: #c3e6cb;">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Departments</span>
                  <span class="info-box-number">{{count($departments)}}</span>
  
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box shadow-lg" style="color: #721c24;
              background-color: #f8d7da;
              border-color: #f5c6cb;">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Designations</span>
                  <span class="info-box-number">{{count($designations)}}</span>
  
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
    
        <div class="row">
            <div class="col-md-5">
                <div class="card" style="height: 100%; box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="card-header p-1">
                        <input type="text" class="form-control" placeholder="Search Employee..." id="filter-attendance"/>
                    </div>
                    <div class="card-body p-0">
                        <div style="height: 500px; overflow-y: scroll; width: 100%;" class="p-2">
                            @if(count($employees)> 0)
                                @foreach($employees as $employee)
                                    <div class="row eachemployee m-1" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}<" style="border: 1px solid green; border-radius: 15px;">
                                        <div class="col-md-7">
                                            <small><strong>{{strtoupper($employee->lastname)}}</strong>,</small> <small>{{ucwords(strtolower($employee->firstname))}}</small>
                                        </div>
                                        <div class="col-md-5">
                                            <small>@if($employee->lastactivity == '' || $employee->lastactivity == null)<span class="float-right badge badge-warning">ABSENT</span>@else<span class="float-right badge badge-success">PRESENT</span>@endif</small>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <small><span class="badge badge-info float-right">{{$employee->lastactivity}}</span></small>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card card-primary tschoolcalendar h-100" style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    {{-- <div class="card-header">
                        <h3 class="card-title text-bold">School Calendar</h3>
                    </div> --}}
                    <div class="card-body p-1">
                        <div class="calendarHolder">
                            <div id='newcal'></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection
    @section('footerjavascript')
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <script> 
    
    $( document ).ready(function() {
        $("#filter-attendance").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".eachemployee").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });

        if($(window).width()<500){

        $('.fc-left').css('font-size','13px')
        $('.fc-toolbar').css('margin','0')
        $('.fc-toolbar').css('padding-top','0')

        var header = {
            left:   'title',
            center: '',
            right:  'today prev,next'
        }
        console.log(header)


        }
        else{
        var header = {
            left  : 'prev,next today',
            center: 'title',
            right : 'dayGridMonth,timeGridWeek,timeGridDay'
        }
        console.log(header)
        }

        var date = new Date()
        var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

        var schedule = [];

        @foreach($schoolcalendar as $item)

            @if($item->noclass == 1)
                var backgroundcolor = '#dc3545';
            @else
                var backgroundcolor = '#00a65a';
            @endif

            schedule.push({
                title          : '{{$item->description}}',
                start          : '{{$item->datefrom}}',
                end            : '{{$item->dateto}}',
                backgroundColor: backgroundcolor,
                borderColor    : backgroundcolor,
                allDay         : true,
                id: '{{$item->id}}'
            })

        @endforeach


        var Calendar = FullCalendar.Calendar;

        console.log(schedule);

        var calendarEl = document.getElementById('newcal');

        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
            header    : header,
            events    : schedule,
            height : 'auto',
            themeSystem: 'bootstrap',
            eventStartEditable: false
        });

        calendar.render();
        $('.fc-header-toolbar').find('button').addClass('btn-sm')
        $('.dropdown-item').on('click', function(){
            window.open($(this).attr('href'),"_self")
        })
        // $('.schoolforms').on('click', function(){
        //     window.open($(this).attr('href'),"_self")
        // })
        // $('#stud-manage-enrolled').on('click', function(){
        //     window.open('/registrar/enrolled',"_self")
        // })
        // $('#stud-manage-registered').on('click', function(){
        //     window.open('/registrar/registered',"_self")
        // })
        // $('#stud-manage-enrolled-online').on('click', function(){
        //     window.open('/registrar/oe?syid={{DB::table('sy')->where('isactive','1')->first()->id}}&semid={{DB::table('semester')->where('isactive','1')->first()->id}}',"_self")
        // })
    });
    </script>
@endsection
