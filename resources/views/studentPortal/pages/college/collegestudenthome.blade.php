@extends('studentPortal.layouts.app2')

@section('pagespecificscripts')

        <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-timegrid/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-interaction/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
        <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

        <style>
            .fc-event-container:hover{
                cursor: pointer;
            }
        .schoolcalendar {
            font-size: 10px!important;
        }
        </style>
  
        {{-- @if(Session::get('enrollmentstatus'))
            <script>
                let subjectattendanceevtSource = new EventSource("/homeevent/{{$studentInfo->id}}", {withCredentials: true});
                subjectattendanceevtSource.onmessage = function (e) {
                    let data = JSON.parse(e.data);
                $('#attendancetable').empty();
                $('#attendancetable').append(data);
                };
            </script>
            <script>
                let tapstate = new EventSource("/tapstate/{{$studentInfo->id}}", {withCredentials: true});
                tapstate.onmessage = function (e) {
                    let data = JSON.parse(e.data);
                    $('.tapstate').empty();
                    $('.tapstate').append(data);
                };
            </script>
        @endif --}}
@endsection

@section('content')

    @if(Session::get('enrollmentstatus'))    
        <section class="content pt-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 studentprof ">
                        <div class="card card-primary card-outline h-100">
                            <div class="card-body box-profile ">
                                <div class="text-center mb-3">
                                @php
                                    $randomnum = rand(1, 4);
                                    if(Session::get('studentInfo')->gender == 'FEMALE'){
                                        $avatar = 'avatars/S(F) '.$randomnum.'.png';
                                    }
                                    else{
                                        $avatar = 'avatars/S(M) '.$randomnum.'.png';
                                    }
                                @endphp
                                <img class="profile-user-img img-fluid img-circle" 
                                        src="{{asset($studentInfo->picurl)}}" 
                                        onerror="this.onerror=null; this.src='{{asset($avatar)}}'"
                                        alt="User profile picture">
                                </div>
                                <center><h5>STUDENT</h5></center>
                                <h5 class="profile-username text-center mb-0">{{strtoupper($studentInfo->firstname)}}  {{strtoupper($studentInfo->lastname)}}</h5>
                                
                
                                <p class="text-muted text-center mb-0">{{$studentInfo->enlevelname}}</p>

                                <p class="text-muted text-center">{{$studentInfo->ensectname}}</p>
                             
                                <ul class="list-group list-group-unbordered mb-0 tapstate">
                                    <li class="list-group-item">
                                        <b>Date:</b> <a class="float-right  mb-0 text-primary">{{\Carbon\Carbon::now()->isoFormat('MMM DD, YYYY')}}</a>
                                    </li>
                                    {{-- <li class="list-group-item">
                                          <b>Year Level:</b> <a class="float-right  mb-0 text-primary">{{str_replace('COLLEGE','',$studentInfo->levelname)}}</a>
                                    </li>
                                    <li class="list-group-item">
                                          <b>Section:</b> <a class="float-right  mb-0 text-primary">{{$studentInfo->sectionDesc}}</a>
                                    </li> --}}
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-12 studentsubatt">
                        <div class="card h-100">
                            <div class="card-header bg-primary">
                                <h5 class="card-title">TODAY SCHED</h5>
                            </div>
                            <div class="card-body  p-0 table-responsive" style="height: 400px;" >
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>Code</td>
                                            <td>STIME</td>
                                            <td>ETIME</td>
                                        </tr>
                                    </thead>
                                    <tbody id="attendancetable">
                                          @foreach($todaySched as $item)
                                                <tr>
                                                      <td>{{$item->subjCode}}</td>
                                                      <td>{{\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A')}}</td>
                                                      <td>{{\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A')}}</td>
                                                </tr>
                                          @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 schoolcalendar">
                        <div class="main-card mb-3 card h-100">
                            <div class="card-header bg-primary">
                               <p class="card-title">School Calendar</p>
                            </div>
                            <div class="card-body p-2 pt-4">
                                <div class="calendarHolder">
                                        <div id='newcal'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-8 parentassessment">
                        <div class="card">
                            <div class="card-header bg-success">
                                <span class="h5">Billing Assessment</span> for the month of <span class="h5">{{\Carbon\Carbon::now()->isoFormat('MMMM')}}</span>
                            </div>
                            <div class="card-body p-0" style="height: 300px; overflow-y: scroll">
                                <div class="table-responsive" >
                                    <table class="table" style="min-width:800px">
                                        <tr>
                                            <th>Particulars</th>
                                            <th class="text-right">Amount Due</th>
                                            
                                        </tr>
                                        @foreach($assesment as $item)
                                            @if(\Carbon\Carbon::create($item->duedate)->isoFormat('MM')==\Carbon\Carbon::now()->isoFormat('MM'))
                                                <tr class="text-success">
                                            @else
                                                <tr class="text-danger">
                                            @endif
                                                    <td>{{$item->particulars}}</td>
                                                    <td class="text-right">&#8369; {{number_format($item->balance,2)}}</td>
                                                </tr>
                                            
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if(count($assesment) > 0)
                                    <span class=" h6 float-right">Total Balance for the month of {{\Carbon\Carbon::now()->isoFormat('MMMM')}} : <span class="h4 text-success">&#8369; {{number_format($assesment->sum('balance'),2)}}</span></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @php
                        $exam_permit = DB::table('zversion_control')->where('module',3)->where('isactive',1)->get();
                    @endphp
                    @if(collect($exam_permit)->where('version','v1')->count() == 1)
                        <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        Examination Permit
                                    </div>
                                    <div class="card-body p-0">
                                        @if($exampermit != null)
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link">
                                                       
                                                        Active Term: <span class="float-right badge bg-primary" style="font-size:15px; !important">
                                                            @if($exampermit[0]->quarter == '1st Quarter')
                                                                Prelim
                                                            @elseif($exampermit[0]->quarter == '2nd Quarter')
                                                                Midterm
                                                            @elseif($exampermit[0]->quarter == '3rd Quarter')
                                                                Prefi
                                                            @elseif($exampermit[0]->quarter == '4th Quarter')
                                                                Final
                                                            @endif
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link">
                                                        Exam Permit Status: 
                                                        @if($exampermit[0]->status == 1)
                                                            <span class="float-right badge bg-success" style="font-size:15px; !important">Permitted</span>
                                                        @else
                                                            <span class="float-right badge bg-danger" style="font-size:15px; !important">With Remaining Balance</span>
                                                        @endif
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link">
                                                        Balance: <span class="float-right badge bg-secondary" style="font-size:15px; !important">&#8369; {{number_format($exampermit[0]->balance,2)}}</span>
                                                    </a>
                                                </li>
                                                @if($exampermit[0]->promisory == 1)
                                                    <li>
                                                        <a class="nav-link">
                                                            Promissory Exam Permit: 
                                                            <span class="float-right badge bg-success" style="font-size:15px; !important">Approved</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if($exampermit[0]->status == 0)
                                                    <li>
                                                        <a class="nav-link">
                                                            Please settle your accounts for the month of <span class="badge badge-success" style="font-size:15px; !important">{{\Carbon\Carbon::create($exampermit[0]->month)->isoFormat('MMMM')}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        @else
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link">
                                                       Examination permit is not yet available.
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    @endif
                </div>
            </div>         
        </section>
        <script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/moment/moment-timezone.js')}}"></script>

<script src="{{ asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>



<script>
    $( document ).ready(function() {
            
     

        var header = {
                left:   'title',
                center: '',
                right:  'today prev,next'
            }

        $(function () {
            $('#day').daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                }
            })
        })

      
        moment().tz('Asia/Manila').format('YYYY-MM-DD')
        
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
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
            header    : header,
            events    : schedule,
            height : 'auto',
            themeSystem: 'bootstrap',
            eventStartEditable: false,
            timeZone: 'UTC',
            eventClick: function(info) {
               
                $('#modal-primary').modal('show')
                $.ajax({
                type:'GET',
                url:'/studentgetevent',
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
                      url:'/studentgeteventtype',
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

        $('.fc-prev-button').addClass('btn-sm')
            $('.fc-next-button').addClass('btn-sm')
            $('.fc-today-button').addClass('btn-sm')
            $('.fc-left').css('font-size','12px')
            $('.fc-toolbar').css('margin','0')
            $('.fc-toolbar').css('padding-top','0')
       
    });
        

</script>
    @else
        <section class="content>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card  mt-3">
                            <div class="card-body">
                                <p>You are not yet enrolled.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    

@endsection
