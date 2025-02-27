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
        background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  28% 60%;
        background-size: 30%;
    }
</style>
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
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Current School Year</h3>
                        </div>
                        <div class="card-body">
                            @php    
                                $currentsy = DB::table('sy')
                                    ->where('isactive','1')
                                    ->first();
                                $syid = $currentsy->id;
                                if($currentsy)
                                {
                                    $currentsy = $currentsy->sydesc;
                                }else{
                                    $currentsy = "Not set!";
                                }
                                $studentstatus = DB::table('studentstatus')
                                    ->get();
        
                                if(count($studentstatus)>0)
                                {
                                    foreach($studentstatus as $studentstat)
                                    {
                                        $studinfo_1 = DB::table('studinfo')
                                            ->select(
                                                'studinfo.id',
                                                'studinfo.sid',
                                                'studinfo.firstname',
                                                'studinfo.middlename',
                                                'studinfo.lastname',
                                                'studinfo.suffix',
                                                'studinfo.gender',
                                                'studinfo.mol',
                                                'studinfo.grantee as granteeid',
                                                'enrolledstud.studstatus'
                                                )
                                            ->join('enrolledstud', 'studinfo.id','=','enrolledstud.studid')
                                            ->where('studinfo.deleted','0')
                                            ->where('enrolledstud.deleted','0')
                                            ->where('studinfo.studstatus','!=','0')
                                            ->where('enrolledstud.syid',$syid)
                                            ->where('enrolledstud.studstatus', $studentstat->id)
                                            // ->take(5)
                                            ->distinct()
                                            ->get();

                                        $studinfo_2 = DB::table('studinfo')
                                            ->select(
                                                'studinfo.id',
                                                'studinfo.sid',
                                                'studinfo.firstname',
                                                'studinfo.middlename',
                                                'studinfo.lastname',
                                                'studinfo.suffix',
                                                'studinfo.gender',
                                                'studinfo.mol',
                                                'studinfo.grantee as granteeid',
                                                'sh_enrolledstud.studstatus'
                                                )
                                            ->join('sh_enrolledstud', 'studinfo.id','=','sh_enrolledstud.studid')
                                            ->where('studinfo.deleted','0')
                                            ->where('sh_enrolledstud.deleted','0')
                                            ->where('studinfo.studstatus','!=','0')
                                            ->where('sh_enrolledstud.syid',$syid)
                                            ->where('sh_enrolledstud.studstatus', $studentstat->id)
                                            // ->take(5)
                                            ->distinct()
                                            ->get();

                                            $allItems = collect();
                                            $allItems = $allItems->merge($studinfo_1);
                                            $allItems = $allItems->merge($studinfo_2);
                                            
                                            $allItems = $allItems->unique('id');

                                            $studentstat->count = count($allItems);
        
                                    }
                                }
                            @endphp
                            <h1><strong>S.Y {{$currentsy}}</strong></h1>
                            @if(count($studentstatus)>0)
                                @foreach($studentstatus as $studstat)
                                    <small>{{$studstat->description}} STUDENTS : <strong>{{$studstat->count}}</strong></small><br/>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                
                    <div class="card card-primary tschoolcalendar">
                        <div class="card-header bg-success">
                            <h3 class="card-title">School Calendar</h3>
                        </div>
                        <div class="card-body p-1">
                            <div class="calendarHolder">
                                <div id='newcal'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- DONUT CHART -->
            <div class="card card-danger">
              <div class="card-header bg-warning">
                <small class="text-uppercase">Total no. of Teachers per Academic Program</small>
                {{-- <h3 class="card-title">Teachers</h3> --}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
              </div>
              <div class="card-body p-0">
                  <div class="donutTeachers">
                    <canvas id="donutChartTeachers" style="height:230px; min-height:230px"></canvas>
                  </div>
                  
              </div>
              <!-- /.card-body -->
            </div>
            <div class="card card-danger">
              <div class="card-header bg-warning">
                <small class="text-uppercase">Total no. of Students per Academic Program</small>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                  <div class="donutStudents">
                        <canvas id="donutChartStudents" style="height:230px; min-height:230px"></canvas>
                  </div>
                  
              </div>
              <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
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
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script> //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var donutChartCanvasTeachers = $('#donutChartTeachers').get(0).getContext('2d');
        var donutDataTeachers        = {
          labels: [
              '{{$preschoolTeachers}} Pre ',
              '{{$elemTeachers}} Elem', 
              '{{$juniorHighTeachers}} Junior', 
              '{{$seniorHighTeachers}} Senior'
          ],
          datasets: [
            {
              data: ['{{$preschoolTeachers}}','{{$elemTeachers}}','{{$juniorHighTeachers}}','{{$seniorHighTeachers}}'],
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
            }
          ]
        }
        var donutOptionsTeachers     = {
          maintainAspectRatio : false,
          responsive : true,
         legend: {
            position: 'right'
         }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var donutChartTeachers = new Chart(donutChartCanvasTeachers, {
          type: 'doughnut',
          data: donutDataTeachers,
          options: donutOptionsTeachers     
        })
    
        var donutChartCanvasStudents = $('#donutChartStudents').get(0).getContext('2d');
        var donutDataStudents        = {
          labels: [
              '{{$preschoolStudents}} Pre',
              '{{$elemStudents}} Elem', 
              '{{$juniorHighStudents}} Junior', 
              '{{$seniorHighStudents}} Senior'
          ],
          datasets: [
            {
              data: ['{{$preschoolStudents}}','{{$elemStudents}}','{{$juniorHighStudents}}','{{$seniorHighStudents}}'],
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
            }
          ]
        }
        var donutOptionsStudents     = {
          maintainAspectRatio : false,
          responsive : true,
         legend: {
            position: 'right'
         }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var donutChartStudents = new Chart(donutChartCanvasStudents, {
          type: 'doughnut',
          data: donutDataStudents,
          options: donutOptionsStudents      
        })
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
        
    $( document ).ready(function() {

        if($(window).width()<500){

        $('.fc-prev-button').addClass('btn-sm')
        $('.fc-next-button').addClass('btn-sm')
        $('.fc-today-button').addClass('btn-sm')
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
    });
    </script>
@endsection
