
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">

@extends('teacher.layouts.app')

<style>
    .dataTable                  { font-size:80%; }
    /* .tschoolschedule .card-body { height:250px; } */
    /* .tschoolcalendar            { font-size: 12px; }
    .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; } */
    .small-box                  { box-shadow: 1px 2px 2px #001831c9; overflow-y: auto scroll; }
    .small-box h5               { text-shadow: 1px 1px 2px gray; }
</style>

@section('content')

    @php
        use \Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();

        $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select(
                            'id'
                        )
                        ->first()
                        ->id;

        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->first()
                    ->id;
                
        $semid = DB::table('semester')
                    ->where('isactive',1)
                    ->first()
                    ->id;

        $syinfo = DB::table('sy')
                    ->where('isactive',1)
                    ->first();

        $seminfo =  DB::table('semester')
                    ->where('isactive',1)
                    ->first();

    @endphp
    @php
        $msteamsaccount = DB::table('msteams_creds')
            ->where('studid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)
            ->where('department', 'TEACHER')
            ->first();
    @endphp

    @if(isset($msteamsaccount->username))
        <div class="row mt-5">
            <div class="col-12">
                <div class="card collapsed-card shadow">
                <div class="card-header" data-card-widget="collapse" style="cursor: pointer;">
                    <h3 class="card-title"><i class="fa fa-exclamation-triangle"></i> MSTeams Credentials</h3>
                </div>
                <div class="card-body p-2">
                    @php
                        $msteamsaccount = DB::table('msteams_creds')
                            ->where('studid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)
                            ->where('department', 'TEACHER')
                            ->first();
                    @endphp
                    
                    <div class="row">
                        <div class="col-2">
                            <label>Username</label>
                        </div>
                        <div class="col-4">
                            @if($msteamsaccount)
                                <em>{{$msteamsaccount->username}}</em>
                            @endif
                        </div>
                        <div class="col-2">
                            <label>Password</label>
                        </div>
                        <div class="col-4">
                                <div class="input-group" data-target-input="nearest">
                                    @if($msteamsaccount)
                                        <input type="password" value="{{$msteamsaccount->password}}" id="msteamspassword" class="form-control form-control-sm" readonly/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text" id="msteamsvisible"><i class="fa fa-eye"></i></div>
                                        </div>
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row pt-3">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow ">
                        <div class="card-header p-2 pl-3 border-0">
                            <h3 class="card-title"><i class="fas fa-clipboard-list text-indigo"></i> Class Schedule</h3>
                        </div>
                        <div class="card-body pt-0 h-100"  style="min-height:574px">
                            <div class="row">
                                <div class="col-md-12" style="font-size:.7rem">
                                    <table class="table table-sm table-bordered" id="teachersched_table" width="100%" style="font-size:.7rem">
                                            <thead>
                                                <tr>
                                                        <th width="15%">Section</th>
                                                        <th width="45%">Subject</th>
                                                        <th width="25%" >Time & Day</th>
                                                        <th width="10%" class="text-center p-0 align-middle">Enrolled Student</th>
                                                </tr>
                                            </thead>
                                    </table>
                                </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="small-box bg-success shadow">
                        <div class="inner">
                          <h3 class="mb-0">SY: {{$syinfo->sydesc}}</h3>
                          <p class="mb-0">{{$seminfo->semester}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header p-2 border-0">
                            <h3 class="card-title"><i class="fas fa-calendar-alt text-danger"></i> School Calendar</h3>
                        </div>
                        <div class="card-body p-1 pt-1" >
                            <div id="newcal" ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header p-2 border-0">
                            <h3 class="card-title"><i class="nav-icon fas fa-layer-group text-warning"></i> Student Information</h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/students/advisory">Advisory</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/students/bysubject">By Subject</a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/teacher/teachingload">Teaching Load</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header p-2 border-0">
                            <h3 class="card-title"><i class="nav-icon fas fa-list text-orange"></i> Attendance</h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/students/advisory">Advisory</a>
                                </div>
                                <div class="col-md-6">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/students/bysubject">By Subject</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header p-2  border-0">
                            <h3 class="card-title"><i class="nav-icon fa fa-star text-success"></i> Grades</h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/grades/index">System Grading</a>
                                </div>
                                <div class="col-md-6">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/grades/index">Upload Excel</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6  mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/grades/index">Final Grading</a>
                                </div>
                                <div class="col-md-6  mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/grade/observedvalues">Observed Values</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12  mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/teacher/pending/grades/view">Pending Grades <span class="badge badge-warning pending_status_holder right" hidden>WP</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header p-2 border-0">
                            <h3 class="card-title"><i class="nav-icon fas fa-chart-pie text-info"></i> School Forms</h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/forms/index/form1">SF1</a>
                                </div>
                                <div class="col-md-6">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/forms/index/form2">SF2</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/forms/index/form5a">SF5A</a>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/forms/index/form5b">SF5B</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/forms/index/form9">Report Card</a>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/teacher/grade/summary">Grade Summary</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/teacher/grade/summary/quarter">Quarter Grades</a>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <a class="btn btn-primary text-white btn-block btn-sm" href="/teacher/student/ranking">Student Awards</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>

    $(document).ready(function() {

        var teacherid = @json($teacherid);
        var syid = @json($syid);
        var semid = @json($semid);

        var all_sched = []
        teacher_sched_datatable()

        load_schedule()

        function load_schedule(){
            
            $.ajax({
                    type:'GET',
                    url: '/scheduling/teacher/schedule',
                    data:{
                        syid:syid,
                        semid:semid,
                        teacherid:teacherid
                    },
                    success:function(data) {
                        if(data.length == 0){
                                all_sched = []
                                teacher_sched_datatable()
                        }else{
                            if(data[0].status == undefined){
                                all_sched = data
                                teacher_sched_datatable()
                            }else{
                                all_sched = []
                                teacher_sched_datatable()
                            }
                        }
                    }
            })

        }

        function teacher_sched_datatable(){

            $("#teachersched_table").DataTable({
                destroy: true,
                data:all_sched,
                pageLength: 10,
                lengthChange: false,
                columns: [
                            { "data": "sortid" },
                            { "data": "search" },
                            { "data": null },
                            { "data": null },
                        ],
                columnDefs: [
                            {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.sectionname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>';
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                    }
                            },
                            {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var comp = '';
                                        var consolidate = ''
                                        var spec = ''
                                        var type = ''
                                        var percentage = ''
                                        var visDis = ''
                                        
                                        if($('#filter_gradelevel').val() != 14 && $('#filter_gradelevel').val() != 15){
                                                if(rowData.isCon == 1){
                                                }

                                                if(rowData.isSP == 1){
                                                    spec = '-  <i class="text-danger"> Specialization </i>'
                                                }

                                                if(rowData.subjCom != null){
                                                }

                                                if(rowData.subj_per != 0){
                                                    percentage = '-  <i class="text-danger">'+rowData.subj_per+'%</i>'
                                                }

                                                var visDis = '<span class="badge badge-success">V</span>'
                                                if(rowData.isVisible == 0){
                                                    visDis = '<span class="badge badge-danger badge-danger">V</span>'
                                                }

                                        }else{
                                                if(rowData.type == 1){
                                                    type = '-  <i class="text-danger">Core</i>'
                                                }else if(rowData.type == 2){
                                                    type = '-  <i class="text-danger">Specialized</i>'
                                                }else if(rowData.type == 3){
                                                    type = '-  <i class="text-danger">Applied</i>'
                                                }
                                        }

                                        var pending = ''
                                        if(rowData.with_pending){
                                                pending = '<span class="badge badge-warning">With Pending</span>'
                                        }

                                        var subj_num = 'S'+('000'+rowData.subjid).slice (-3)

                                        var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjcode+' '+type+'</p>';
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                    }
                            },
                            {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                        var table = 'table-borderless'
                                        var multiple = ''

                                        if(rowData.schedule.length > 1){
                                                table = 'table-bordered'
                                                multiple = 'no-border-col'
                                        }

                                        var text = '<table class="table table-sm mb-0 '+table+'">'
                                        $.each(rowData.schedule,function(a,b){
                                                text += '<tr style="background-color:transparent !important"><td width="50%" class="'+multiple+'" style="font-size:.7rem">'+b.start + ' - ' + b.end + '<p class="text-muted mb-0" style="font-size:.7rem">'+b.day+'</p></td></tr>'
                                        })
                                        text += '</table>'
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('p-0')
                                    }
                            },
                            {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = rowData.enrolled
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('text-center')
                                    }
                            }
                        ]
                
            });
        }
    });
</script>



<script>

    $( document ).ready(function() {
        
        var syid = '<?php echo DB::table('sy')->where('isactive',1)->first()->id; ?>';
        var currentportal = @json(Session::get('currentPortal'));

        function showTime(){

            var datetime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"})
           
         
        }

        setInterval(showTime,1000);

        var datefrom = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"})

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

        var calendarEl = document.getElementById('newcal');

        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid'],
            header    : {
                left:   'title',
                center: '',
                right:  'today prev,next'
            },
            // events    : schedule,
            events    : '/school-calendar/getall-event/'+currentportal+'/'+syid,
            themeSystem: 'bootstrap',
            eventStartEditable: false,
            timeZone: 'UTC',
            height : 'auto',
            eventClick: function(info) {
               
                $('#modal-primary').modal('show')
                $.ajax({
                type:'GET',
                url:'/principalgetevent',
                data:{
                  id:info.event.id
                },
                
                success:function(data) {

                    $('#des').empty()
                    $('#date').empty()
                    $('#des').empty()
                    $('#type').empty()

                    if(data[0].data[0].annual == 1){
                    
                      $('#annual').prop('checked','checked')
                    }

                    if(data[0].data[0].noclass == 1){
                      $('#noclass').prop('checked','checked')
                    }
           
                    // $('#clas').val(data[0].data[0].eventtype)
            
                    var typeid = data[0].data[0].eventtype
                    var scholcaltypeid = data[0].data[0].typeid

              
                 
                    $.ajax({
                      type:'GET',
                      url:'/principalgeteventtype',
                      data:{
                        id:typeid
                      },
                      success:function(data) {
              
                        $('#type').empty();

                        $.each(data,function(key,value){

                          if(scholcaltypeid == value.id){
                            $('#type').append(value.typename)
                          }
                         
                         
                        })
                          
                      }
                    })
                    var datefrom = new Date(data[0].data[0].datefrom)
                    var dateto = new Date(data[0].data[0].dateto)
                    $('#des').append(data[0].data[0].description)
                    $('#date').append(moment(datefrom).format('MMM DD, YYYY'))
                        
                    if(moment(datefrom).format('MMM DD, YYYY') != moment(dateto).format('MMM DD, YYYY')){
                        $('#date').append(' - '+ moment(dateto).format('MMM DD, YYYY'))
                    }

                }
              })
     
            },
        
        });

        calendar.render();

        // $('.fc-prev-button').addClass('btn-sm')
        // $('.fc-next-button').addClass('btn-sm')
        $('.fc-today-button').addClass('btn-sm')
		$('.fc-today-button').css('text-transform', 'capitalize')
		
        $('.fc-left').css('font-size','13px')
        $('.fc-toolbar').css('margin','0')
        $('.fc-toolbar').css('padding-top','0')
        
        });

</script>

@endsection
