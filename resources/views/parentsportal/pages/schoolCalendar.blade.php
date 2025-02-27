@extends('parentsportal.layouts.app2')

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
           font-size: .9rem!important;
       }
       .fc-day-number{
            font-size: .9rem!important;
       }
       .fc-header-toolbar{
           margin-bottom: 0 !important;
           padding-top: 0 !important;
       }
       

    </style>

@endsection

@section('modalSection')

    <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="holidayform" method="GET" action="/admininsertholiday">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Description:</label>
                        </div>
                        <div class="col-md-9"  id="des">
                            Sample Description
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3" >
                            <label>Date:</label>
                        </div>
                        <div class="col-md-9" id="date">
                            
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3" >
                            <label>Event Type:</label>
                        </div>
                        <div class="col-md-9" id="type">
                                Sample event type
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="icheck-success d-inline">
                                    <input disabled type="checkbox" id="noclass"  name="noclass" value="1">
                                    <label  for="noclass" >No Class</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="icheck-success d-inline ">
                                    <input disabled  type="checkbox" id="annual"  name="annual" value="1">
                                    <label  for="annual" >Annual Event</label>
                                </div>
                            </div>
                        </div>
                        
                       
                    </div>
                    
                  
                </div>
            <form>
            </div>
        </div>
  </div>
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>School Calendar</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">School Calendar</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 schoolcalendar container-f">
                <div class="main-card mb-3 card shadow">
                    <div class="card-body p-2" >
                        <div class="calendarHolder"  style="font-size:.9rem;">
                                <div id='newcal' ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 studentinfo">
                <div class="card shadow">
                    <div class="card-header bg-primary">
                        <h3 class="card-title"><i class="fas fa-calendar-check"></i> Events</h3>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 471px;" >
                        <table class="table table-sm table-bordered table-head-fixed table-striped" style="font-size:.9rem;">
                            <tr>
                                <th width="40%">Date</th>
                                <th width="60%">Description</th>
                                
                            </tr>
                            @foreach(collect($schoolcalendar)->sortByDesc('datefrom') as $item)
                                <tr>
                                    <td class="align-middle">
                                        @if(\Carbon\Carbon::create(($item->datefrom))->isoformat('MMM DD, YYYY') == \Carbon\Carbon::create(($item->dateto))->isoformat('MMM DD, YYYY'))
                                            {{\Carbon\Carbon::create(($item->datefrom))->isoformat('MMM DD, YYYY')}}</td>
                                        @else
                                            {{\Carbon\Carbon::create(($item->datefrom))->isoformat('MMM DD, YYYY')}} -
                                            {{\Carbon\Carbon::create(($item->dateto))->isoformat('MMM DD, YYYY')}}
                                        @endif
                                    </td>
                                    <td class="align-middle">{{$item->description}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
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

        if($(window).width()<500){

            var header = {
                left:   'title',
                center: '',
                right:  'today prev,next'
            }
        

        }
        else{
            var header = {
                left:   'title',
                center: '',
                right:  'prev,next'
            }
           
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


            @if( \Carbon\Carbon::create($item->dateto)->addDays(1)->isoFormat('hh:mm a') == '12:00 am')

                var endDate = '{{\Carbon\Carbon::create($item->dateto)->addDays(1)->isoFormat('YYYY-MM-DD')}}'

            @else

                var endDate = '{{\Carbon\Carbon::create($item->dateto)->isoFormat('YYYY-MM-DD')}}'

            @endif

            schedule.push({
                title          : '{{$item->description}}',
                start          : '{{\Carbon\Carbon::create($item->datefrom)->isoFormat('YYYY-MM-DD hh:mm:ss')}}',
                end            : endDate,
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

        if($(window).width()<500){
            $('.fc-prev-button').addClass('btn-sm')
            $('.fc-next-button').addClass('btn-sm')
            $('.fc-today-button').addClass('btn-sm')
            $('.fc-left').css('font-size','12px')
            $('.fc-toolbar').css('margin','0')
            $('.fc-toolbar').css('padding-top','0')
        }
       
    });
        

</script>
@endsection

