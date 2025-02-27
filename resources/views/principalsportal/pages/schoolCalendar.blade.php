@if(auth()->user()->type == 2)

    @php
        $xtend = 'principalsportal.layouts.app2';
    @endphp

@else

    @php
        $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
    @endphp
    
    @if( $refid->refid == 20)
        @php
            $xtend = 'principalassistant.layouts.app2';
        @endphp
    @elseif( $refid->refid == 22)
        @php
            $xtend = 'principalcoor.layouts.app2';
        @endphp
    @endif

@endif

@extends($xtend)

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
</section>

<div class="row ">
    <div class="col-md-9 container-fluid">
        <div class="main-card mb-3 card">
            <div class="card-body p-2">
                <div class="calendarHolder">
                    <div class="calendarHolder">
                        <div id='newcal'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>

<script src="{{ asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>


<script>
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
        }
        else{
            var header = {
                left  : 'prev,next today',
                center: 'title',
                right : 'dayGridMonth,timeGridWeek,timeGridDay'
            }
        }

        $(function () {
            $('#day').daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                }
            })
        })

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
    

        console.log(schedule)

        var Calendar = FullCalendar.Calendar;

        var calendarEl = document.getElementById('newcal');

        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
            header    : header,
            events    : schedule,
            height : 'auto',
            themeSystem: 'bootstrap',
            timeZone: 'UTC',
            eventStartEditable: false,
            eventClick: function(info) {
               
                $('#modal-primary').modal('show')
                $.ajax({
                type:'GET',
                url:'/principalgetevent',
                data:{
                  id:info.event.id
                },
                
                success:function(data) {
                    console.log(data);
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

        if($(window).width()<500){
            
            $('.fc-prev-button').addClass('btn-sm')
            $('.fc-next-button').addClass('btn-sm')
            $('.fc-today-button').addClass('btn-sm')
            $('.fc-left').css('font-size','11px')
            $('.fc-toolbar').css('margin','0')
            $('.fc-toolbar').css('padding-top','0')

        }
        });
</script>

@endsection
