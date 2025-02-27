<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
@extends('registrar.layouts.app')

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
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
        $academicprograms = DB::table('academicprogram')->get();
        $numenrolled = 0;
        $numlateenrolled = 0;
        $numtransferredin = 0;
        $numdroppedout = 0;
        $numtransferredout = 0;
        $numwithdrawn = 0;
        $students = collect();
        foreach($academicprograms as $academicprogram)
        {
            
            if($academicprogram->id == '6')
            {
                $students1 = DB::table('college_enrolledstud')
                    ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.gender','college_enrolledstud.yearLevel as levelid','college_enrolledstud.studstatus')
                    ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                    ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
                    ->where('college_enrolledstud.syid', DB::table('sy')->where('isactive','1')->first()->id)
                    ->where('college_enrolledstud.semid', DB::table('semester')->where('isactive','1')->first()->id)
                    ->whereIn('college_enrolledstud.studstatus', [1,2,3,4,5,6])
                    ->where('college_enrolledstud.deleted','0')
                    ->where('studinfo.deleted','0')
                    ->where('college_colleges.deleted','0')
                    ->where('college_courses.deleted','0')
                    ->where('gradelevel.acadprogid',$academicprogram->id)
                    ->get();
                $students1 = collect($students1)->unique('id')->all();
                $academicprogram->count = collect($students1)->whereIn('studstatus',[1,2,4])->count();
                $academicprogram->color = '#17a2b8';

                $students = $students->merge($students1);
            }
            elseif($academicprogram->id == '5')
            {
                $students2 = DB::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.gender','sh_enrolledstud.levelid','sh_enrolledstud.studstatus')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->where('sh_enrolledstud.syid', DB::table('sy')->where('isactive','1')->first()->id)
                    ->where('sh_enrolledstud.semid', DB::table('semester')->where('isactive','1')->first()->id)
                    ->whereIn('sh_enrolledstud.studstatus', [1,2,3,4,5,6])
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('studinfo.deleted','0')
                    ->where('gradelevel.deleted','0')
                    ->where('gradelevel.acadprogid',$academicprogram->id)
                    ->get();
                $academicprogram->count = collect($students2)->whereIn('studstatus',[1,2,4])->count();
                $academicprogram->color = '#007bff';

                $students2 = collect($students2)->unique('id')->all();
                $students = $students->merge($students2);

            }else{
                $students3 = DB::table('enrolledstud')
                    ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.gender','enrolledstud.levelid','enrolledstud.studstatus')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->where('enrolledstud.syid', DB::table('sy')->where('isactive','1')->first()->id)
                    ->whereIn('enrolledstud.studstatus', [1,2,3,4,5,6])
                    ->where('enrolledstud.deleted','0')
                    ->where('studinfo.deleted','0')
                    ->where('gradelevel.deleted','0')
                    ->where('gradelevel.acadprogid',$academicprogram->id)
                    ->get();
                $academicprogram->count = collect($students3)->whereIn('studstatus',[1,2,4])->count();
                $students3 = collect($students3)->unique('id')->all();
                $students = $students->merge($students3);
                if($academicprogram->id == 2)
                {
                    $academicprogram->color = '#ffc107';
                }
                if($academicprogram->id == 3)
                {
                    $academicprogram->color = '#fd7e14';
                }
                if($academicprogram->id == 4)
                {
                    $academicprogram->color = '#28a745';
                }
            }
        }

        $academicprograms[0]->progname = "KINDERGARTEN";
        $academicprograms[1]->progname = "GRADE SCHOOL";
        $academicprograms[2]->progname = "JUNIOR HIGH SCHOOL";
        $academicprograms[3]->progname = "SENIOR HIGH SCHOOL";
        $students =$students->unique('id')->all();
        $numenrolled += collect($students)->where('studstatus',1)->count();
        $numlateenrolled += collect($students)->where('studstatus',2)->count();
        $numtransferredin += collect($students)->where('studstatus',4)->count();
        $numdroppedout += collect($students)->where('studstatus',3)->count();
        $numtransferredout += collect($students)->where('studstatus',5)->count();
        $numwithdrawn += collect($students)->where('studstatus',6)->count();
    @endphp
    <style>
        h1          { opacity: 0.5; }
        .noselect   { -webkit-touch-callout: none; /* iOS Safari */ -webkit-user-select: none; /* Safari */ -khtml-user-select: none; /* Konqueror HTML */ -moz-user-select: none; /* Old versions of Firefox */ -ms-user-select: none; /* Internet Explorer/Edge */ user-select: none; /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */ }
    </style>
                <div class="btn-group shadow-lg" style="width: 100%;">
                  <label class="btn btn-default text-center pr-0 pl-0" style="width: 20%;">
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-block">Masterlist</button>
                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <span class="sr-only">Toggle Dropdown</span>
                        <div class="dropdown-menu" role="menu">
                            @foreach(collect($academicprograms)->where('id','!=','6')->values() as $eachacadprog)
                            <a class="dropdown-item" href="/registar/schoolforms/index?sf=0&acadprogid={{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</a>
                            @endforeach
                        </div>
                      </button>
                    </div>
                  </label>
                  <label class="btn btn-default text-center pr-0 pl-0" style="width: 20%;">
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-block">School Form 1</button>
                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <span class="sr-only">Toggle Dropdown</span>
                        <div class="dropdown-menu" role="menu">
                            @foreach(collect($academicprograms)->where('id','!=','6')->values() as $eachacadprog)
                            <a class="dropdown-item" href="/registar/schoolforms/index?sf=1&acadprogid={{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</a>
                            @endforeach
                        </div>
                      </button>
                    </div>
                  </label>
                  <label class="btn btn-default text-center pr-0 pl-0" style="width: 20%;">
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-block">School Form 2</button>
                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <span class="sr-only">Toggle Dropdown</span>
                        <div class="dropdown-menu" role="menu">
                            @foreach(collect($academicprograms)->where('id','!=','6')->values() as $eachacadprog)
                            <a class="dropdown-item" href="/registar/schoolforms/index?sf=2&acadprogid={{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</a>
                            @endforeach
                        </div>
                      </button>
                    </div>
                  </label>
                  <label class="btn btn-default text-center" style="width: 20%;">
                        <button type="button" class="btn btn-default btn-block schoolforms" href="/reports_schoolform4/dashboard">School Form 4</button>
                  </label>
                  <label class="btn btn-default text-center pr-0 pl-0" style="width: 20%;">
                    <div class="btn-group dropleft">
                      <button type="button" class="btn btn-default btn-block">School Form 5</button>
                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <span class="sr-only">Toggle Dropdown</span>
                        <div class="dropdown-menu float-left" role="menu">
                            @foreach(collect($academicprograms)->where('id','!=','6')->where('id','!=','5')->values() as $eachacadprog)
                            <a class="dropdown-item" href="/registar/schoolforms/index?sf=5&acadprogid={{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</a>
                            @endforeach
                        </div>
                      </button>
                    </div>
                  </label>
            </div>
            <div class="btn-group shadow-lg mb-3" style="width: 100%;">              
            <label class="btn btn-default text-center" style="width: 20%;">
                <button type="button" class="btn btn-default btn-block schoolforms" href="/registar/schoolforms/index?sf=5a&acadprogid=5" style="font-size: 14px;">School Form 5A</button>
            </label>
            <label class="btn btn-default text-center" style="width: 20%;">
                <button type="button" class="btn btn-default btn-block schoolforms" href="/registar/schoolforms/index?sf=5b&acadprogid=5" style="font-size: 14px;">School Form 5B</button>
              </label>
              <label class="btn btn-default text-center" style="width: 20%;">
                  <button type="button" class="btn btn-default btn-block schoolforms" href="/reports_schoolform6/dashboard">School Form 6</button>
              </label>
              <label class="btn btn-default text-center pr-0 pl-0" style="width: 20%;">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-block">School Form 9</button>
                  <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span class="sr-only">Toggle Dropdown</span>
                    <div class="dropdown-menu" role="menu">
                        @foreach(collect($academicprograms)->where('id','!=','6')->values() as $eachacadprog)
                        <a class="dropdown-item" href="/registar/schoolforms/index?sf=9&acadprogid={{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</a>
                        @endforeach
                    </div>
                  </button>
                </div>
              </label>
              <label class="btn btn-default text-center" style="width: 20%;">
                  <button type="button" class="btn btn-default btn-block schoolforms" href="/schoolform10/index" style="font-size: 14px;">School Form 10</button>
              </label>
        </div>
        {{-- <div class="card" style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
            <div class="card-body p-0">
                <div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%;">
                    <label class="btn btn-default text-center" id="stud-manage-enrolled" style="width: 25%; cursor: pointer;">
                    ENROLLED<br/>STUDENTS
                    <br>
                    <i class="fas fa-circle fa-2x text-green"></i>
                  </label>
                  <label class="btn btn-default text-center" id="stud-manage-registered" style="width: 25%; cursor: pointer;">
                    STUDENTS<br/>FOR ENROLLMENT
                    <br>
                    <i class="fas fa-circle fa-2x text-blue"></i>
                  </label>
                  <label class="btn btn-default text-center" id="stud-manage-registered-online" style="width: 25%; cursor: pointer;">
                    ONLINE REGISTERED<br/>STUDENTS
                    <br>
                    <i class="fas fa-circle fa-2x text-purple"></i>
                  </label>
                  <label class="btn btn-default text-center" id="stud-manage-enrolled-online" style="width: 25%; cursor: pointer;">
                    ONLINE ENROLLED<br/>STUDENTS
                    <br>
                    <i class="fas fa-circle fa-2x text-red"></i>
                  </label>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-success" style="height: 15%; box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="inner">
                        <h3 class="m-0">S.Y. {{DB::table('sy')->where('isactive','1')->first()->sydesc}}</h3>        
                        <h3 class="m-0">{{DB::table('semester')->where('isactive','1')->first()->semester}}</h3>        
                    </div>
                </div>
                <div class="small-box bg-warning" style="height: 21%; box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="inner pt-1" style="font-size: 10px !important; width: 60%;">       
                        <p class="m-0">Enrolled: <span class="float-right text-bold">{{$numenrolled}}</span></p>
                        <p class="m-0">Late Enrolled: <span class="float-right text-bold">{{$numlateenrolled}}</span></p>
                        <p class="m-0">Transferred In: <span class="float-right text-bold">{{$numtransferredin}}</span></p>
                        <p class="m-0">Transferred Out: <span class="float-right text-bold">{{$numtransferredout}}</span></p>
                        <p class="m-0">Dropped Out: <span class="float-right text-bold">{{$numdroppedout}}</span></p>
                        <p class="m-0">Withdrawn: <span class="float-right text-bold">{{$numwithdrawn}}</span></p>
                    </div>
                </div>
                <div class="card" style="height: 60%; box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="card-header p-1">
                        <div class="donutStudents">
                            <canvas id="donutChartStudents"></canvas>
                    </div>
                    </div>
                    <div class="card-body pr-1 pl-1 pt-0 pb-1" style="font-size: 15px;">
                        @foreach($academicprograms as $eachacad)
                        <div class="row">
                            <div class="col-md-12">
                                <label class="mt-1 mb-1">{{$eachacad->progname}} </label><span class="float-right badge badge-warning mt-2" style="font-size: 12px;">{{$eachacad->count}}</span>
                            </div>
                            {{-- <div class="col-md-6">
                                <button type="button" class="btn btn-sm btn-default btn-block mt-1 pt-0 pb-0">Export to PDF</button>
                            </div> --}}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-primary tschoolcalendar h-100" style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="card-header">
                        <h3 class="card-title text-bold">School Calendar</h3>
                    </div>
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
    <script> //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        // var donutChartCanvasTeachers = $('#donutChartTeachers').get(0).getContext('2d');
        // var donutDataTeachers        = {
        //   labels: [
        //       '{{$preschoolTeachers}} Pre ',
        //       '{{$elemTeachers}} Elem', 
        //       '{{$juniorHighTeachers}} Junior', 
        //       '{{$seniorHighTeachers}} Senior'
        //   ],
        //   datasets: [
        //     {
        //       data: ['{{$preschoolTeachers}}','{{$elemTeachers}}','{{$juniorHighTeachers}}','{{$seniorHighTeachers}}'],
        //       backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
        //     }
        //   ]
        // }
        // var donutOptionsTeachers     = {
        //   maintainAspectRatio : false,
        //   responsive : true,
        //  legend: {
        //     position: 'right'
        //  }
        // }
        // //Create pie or douhnut chart
        // // You can switch between pie and douhnut using the method below.
        // var donutChartTeachers = new Chart(donutChartCanvasTeachers, {
        //   type: 'doughnut',
        //   data: donutDataTeachers,
        //   options: donutOptionsTeachers     
        // })
    
        var donutChartCanvasStudents = $('#donutChartStudents').get(0).getContext('2d');
        var acadprogcodes = '{{collect($academicprograms)->pluck('acadprogcode')}}'
        console.log(acadprogcodes);
        acadprogcodes=acadprogcodes.replace(/&quot;/gi,"");
        acadprogcodes=acadprogcodes.replace(/\[/gi,"");
        acadprogcodes=acadprogcodes.replace(/\]/gi,"");
        acadprogcodes=acadprogcodes.split(',');
        var acadcounts = '{{collect($academicprograms)->pluck('count')}}'
        acadcounts=acadcounts.replace(/&quot;/gi,"\"");
        acadcounts=acadcounts.replace(/\[/gi,"");
        acadcounts=acadcounts.replace(/\]/gi,"");
        acadcounts=acadcounts.split(',');
        var acadcolors = '{{collect($academicprograms)->pluck('color')}}'
        acadcolors=acadcolors.replace(/&quot;/gi,"");
        acadcolors=acadcolors.replace(/\[/gi,"");
        acadcolors=acadcolors.replace(/\]/gi,"");
        acadcolors=acadcolors.split(',');

        acadprogcodes[0] = "KINDERGARTEN";
        acadprogcodes[1] = "GRADE SCHOOL";
        acadprogcodes[2] = "JUNIOR HIGH SCHOOL";
        acadprogcodes[3] = "SENIOR HIGH SCHOOL";
   
        var donutDataStudents        = {
          labels: acadprogcodes,
          datasets: [
            {
              data: acadcounts,
              backgroundColor : acadcolors
            }
          ]
        }
        var donutOptionsStudents     = {
          maintainAspectRatio : false,
          responsive : true,
         legend: {
            position: 'top'
         },
         aspectRatio : 1.5,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var donutChartStudents = new Chart(donutChartCanvasStudents, {
          type: 'doughnut',
          data: donutDataStudents,
          options: donutOptionsStudents      
        })
        // $(function () {
        //     $("#example1").DataTable();
            
        // });
        // $(".notification").click(function () {
        //     var notification_id = $(this).attr("name");
        //     $.ajax({
        //         url: '/registrarNotification/'+notification_id,
        //         type:"GET",
        //         dataType:"json",
        //         success:function(data) {
        //             $(".badge-notify").text(data[0]);
                    
        //             if(data[0]==0){
        //                 $(".badge-notify").hide();
        //             }
        //             $.each(data[1], function(key, value){
        //                 console.log(value.status);
        //                 if(value.status){
        //                     $(".notif"+value.id).css('color','black');
        //                 }

        //             });
        //         },
        //     });
        // }); 
        
    $( document ).ready(function() {

		var syid = '<?php echo DB::table('sy')->where('isactive',1)->first()->id; ?>';
        var currentportal = @json(Session::get('currentPortal'));

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
            // events    : schedule,
			events    : '/school-calendar/getall-event/'+currentportal+'/'+syid,
            height : 'auto',
            themeSystem: 'bootstrap',
            eventStartEditable: false
        });

        calendar.render();
        $('.fc-header-toolbar').find('button').addClass('btn-sm')
		$('.fc-today-button').css('text-transform', 'capitalize')
        $('.fc-dayGridMonth-button').css('text-transform', 'capitalize')
        $('.fc-timeGridWeek-button').css('text-transform', 'capitalize')
        $('.fc-timeGridDay-button').css('text-transform', 'capitalize')
        $('.fc-listMonth-button').css('text-transform', 'capitalize')
        $('.dropdown-item').on('click', function(){
            window.open($(this).attr('href'),"_self")
        })
        $('.schoolforms').on('click', function(){
            window.open($(this).attr('href'),"_self")
        })
        $('#stud-manage-enrolled').on('click', function(){
            window.open('registrar/studentmanagement?sstatus=1',"_self")
        })
        $('#stud-manage-registered').on('click', function(){
            window.open('/registrar/studentmanagement?sstatus=2',"_self")
        })
        $('#stud-manage-registered-online').on('click', function(){
            window.open('registrar/studentmanagement?sstatus=3',"_self")
        })
        $('#stud-manage-enrolled-online').on('click', function(){
            window.open('/registrar/oe?syid={{DB::table('sy')->where('isactive','1')->first()->id}}&semid={{DB::table('semester')->where('isactive','1')->first()->id}}',"_blank")
        })
    });
    </script>
@endsection
