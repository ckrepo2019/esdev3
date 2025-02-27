@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
        {{-- <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}"> --}}
        <link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.min.css')}}">

        <style>
            .fc-event-container:hover{
                cursor: pointer;
            }
            .schoolcalendar {
                font-size: 10px!important;
            }
        </style>
        <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
      </style>
@endsection

@section('content')

<link rel='stylesheet' href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}" />

<section class="content pt-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4" id="card_1">
                <div class="row">
                    <div class="col-md-12" hidden id="current_enrollmentinfo_card">
                        <div class="card shadow bg-primary">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0">
                              <h4 class="mb-0" style="font-size:15px !important">Enrollment Information <b><i id="sydesc" class="float-right"></i></b></h4>
                              
                            </div>
                            <div class="card-footer bg-white p-0">
                              <ul class="nav nav-pills flex-column" style="font-size:12px !important">
                                <li class="nav-item">
                                  <span class="nav-link">
                                    Student Name :
                                    <span class="float-right text-secondary" id="name"></span>
                                  </span>
                                </li>
                                <li class="nav-item">
                                  <span class="nav-link">
                                    ID No.:
                                    <span class="float-right text-secondary" id="sid"></span>
                                  </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                      Grade Level:
                                      <span class="float-right text-secondary" id="gradelevel"></span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                      Section:
                                      <span class="float-right text-secondary" id="section"></span>
                                    </span>
                                </li>
                                <li class="nav-item" id="strand_holder" hidden>
                                    <span class="nav-link">
                                      Strand:
                                      <span class="float-right text-secondary" id="strand_name"></span>
                                    </span>
                                </li>
                                <li class="nav-item" id="adviser_holder" hidden>
                                    <span class="nav-link">
                                      Adviser:
                                      <span class="float-right text-secondary" id="adviser"></span>
                                    </span>
                                </li>
                                <li class="nav-item" id="course_holder" hidden>
                                    <span class="nav-link">
                                      Course:
                                      <span class="float-right text-secondary" id="course"></span>
                                    </span>
                                </li>
                              </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="ba_sched_card" hidden>
                        <div class="card shadow bg-warning">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0">
                                <h4 class="mb-0" style="font-size:15px !important">Billing Assessment<b class="float-right"> <i>{{strtoupper(\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM'))}}</b></i> </h4>
                            </div>
                            <div class="card-footer bg-white p-0">
                                <table class="table table-sm text-secondary mb-0 table-striped" style="font-size:.9rem;">
                                    <thead>
                                        <tr>
                                            <th width="70%">Particular</th>
                                            <th width="30%" class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="balance">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                
                <div class="row">
                    <div class="col-md-6" hidden id="current_sched_card">
                        <div class="card shadow">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0 bg-success" >
                                <h3 style="font-size:15px !important" class="mb-0">
                                    <i class="fas fa-clipboard-list"></i>
                                    Current Class <i class="float-right">{{\Carbon\Carbon::now('Asia/Manila')->format('l')}}</i>
                                </h3>
                            </div>
                            <div class="card-body pl-3 pr-3 pt-2 pb-2 user-block">
                                <span class="username m-0" style="font-size:13px !important">
                                    <a  id="curr_sched_subj">--</a>
                                </span>
                                <span class="description m-0" id="curr_sched_time">--</span>
                                <span class="description m-0" id="curr_sched_teacher">--</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"  hidden id="next_sched_card">
                        <div class="card shadow">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0 bg-secondary" >
                                <h3 style="font-size:15px !important"  class="mb-0">
                                    <i class="fas fa-clipboard-list"></i>
                                    Next Class <i class="float-right">{{\Carbon\Carbon::now('Asia/Manila')->format('l')}}</i>
                                </h3>
                            </div>
                            <div class="card-body pl-3 pr-3 pt-2 pb-2 user-block">
                                <span class="username m-0" style="font-size:13px !important">
                                    <a id="next_sched_subj">--</a>
                                </span>
                                <span class="description m-0"  id="next_sched_time">--</span>
                                <span class="description m-0" id="next_sched_teacher">--</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" hidden id="no_sched_card">
                        <div class="card shadow">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0 bg-secondary" >
                                <h3 style="font-size:15px !important" class="mb-0">
                                    <i class="fas fa-clipboard-list"></i>
                                    Class Schedule <i class="float-right">{{\Carbon\Carbon::now('Asia/Manila')->format('l')}}</i>
                                </h3>
                            </div>
                            <div class="card-body pl-4 pr-5 pt-2 pb-2 user-block">
                                <span class="description m-0">
                                    <i>No available class for today.</i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"  id="calendar_holder" hidden>
                        <div class="card shadow">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0 bg-success" >
                                <h3 style="font-size:15px !important" class="mb-0">
                                    <i class="fa fa-calendar-alt"></i>
                                    Calendar Activities 
                                    <i class="float-right">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM')}}</i>
                                </h3>
                            </div>
                            <div class="card-body p-3" style="height: 50vh;">
                                
                                {{-- <table  class="table table-sm text-secondary mb-0 table-striped" style="font-size:.9rem;">
                                    <tbody id="calendar_activity">  
                                        
                                    </tbody>
                                </table> --}}

                                <div class="fc fc-ltr fc-bootstrap" style="font-size: 12px;" id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"  id="not_enrolled_holder" hidden>
                        <div class="card shadow">
                            <div class="card-body p-0">
                                <table  class="table table-sm text-secondary mb-0 table-striped" >
                                    <tbody>
                                        <tr>
                                            <td><i>You are currently not enrolled for school year <b><span class="sy_holder"></b></span></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->type == 7)
                    <div class="row" id="links" hidden>
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body p-2 pb-0">
                                    <a class="btn btn-app" href="student/enrollment/record/reportcard">
                                        <i class="far fa-folder-open"></i> Report Card
                                    </a>
                                    <a class="btn btn-app" href="student/enrollment/record/classschedule">
                                        <i class="fas fa-clipboard-list"></i> Class Schedule
                                    </a>
                                    <a class="btn btn-app" href="/payment" hidden>
                                        <i class="fas fa-file-upload"></i> Payment Upload
                                    </a>
                                    <a class="btn btn-app" href="/student/enrollment/record/billinginformation" >
                                        <i class="nav-icon fas fa-receipt"></i> Billing Information
                                    </a>
                                    <a class="btn btn-app" href="/student/enrollment/record/cashier" >
                                        <i class="nav-icon fas fa-cash-register"></i> Payment Transactions
                                    </a>
                                    {{-- <a class="btn btn-app" href="schoolCalendar">
                                        <i class="fas fa-stream"></i>Enrollment
                                    </a> --}}
                                    <a class="btn btn-app" href="/school-calendar">
                                        <i class="far fa-calendar-alt"></i>School Calendar
                                    </a>
                                    <a class="btn btn-app"  href="/student/enrollment/record/profile">
                                        <i class="fas fa-user"></i> Student Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row" id="links" hidden>
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body p-2 pb-0">
                                    <a class="btn btn-app" href="/student/enrollment/record/reportcard">
                                        <i class="far fa-folder-open"></i> Report Card
                                    </a>
                                    <a class="btn btn-app" href="student/enrollment/record/classschedule">
                                        <i class="fas fa-clipboard-list"></i> Class Schedule
                                    </a>
                                    <a class="btn btn-app" href="/payment" hidden>
                                        <i class="fas fa-file-upload"></i> Payment Upload
                                    </a>
                                    <a class="btn btn-app" href="/student/enrollment/record/cashier" >
                                        <i class="nav-icon fas fa-cash-register"></i> Payment Transactions
                                    </a>
                                    <a class="btn btn-app" href="/student/enrollment/record/billinginformation" >
                                        <i class="nav-icon fas fa-receipt"></i> Billing Information
                                    </a>
                                    <a class="btn btn-app" href="/school-calendar">
                                        <i class="far fa-calendar-alt"></i>School Calendar
                                    </a>
                                    <a class="btn btn-app"  href="student/enrollment/record/profile">
                                        <i class="fas fa-user"></i> Student Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
<section>

<script src="{{asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
{{-- <script src="{{ asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script> --}}

@php
    $sy = DB::table('sy')->where('isactive',1)->first()->sydesc;
    $syid = DB::table('sy')->where('isactive',1)->first()->id;

@endphp

<script>
    $(document).ready(function(){

        var month = @json(\Carbon\Carbon::now('Asia/Manila')->isoFormat('MM'));
        var type = @json(auth()->user()->type);
        var sy = @json($sy);

        get_enrollment()
        function get_enrollment(){
            $.ajax({
                type:'GET',
                url: '/current/enrollment',
                success:function(data) {
                    if(data.length > 0 ){
                        $('#name').text(data[0].student)
                        $('#sydesc')[0].innerHTML = data[0].sydesc
                        $('#sid')[0].innerHTML = data[0].sid
                        $('#gradelevel')[0].innerHTML = data[0].levelname
                        $('#section')[0].innerHTML = data[0].sectionname
          
                        $('#current_enrollmentinfo_card').removeAttr('hidden')

                        if( data[0].levelid == 14 || data[0].levelid == 15 ){
                            $('#strand_holder').removeAttr('hidden')
                            $('#strand_name').text(data[0].strandname)
                        }else{
                            $('#strand_holder').attr('hidden','hidden')
                            $('#strand_name').text("")
                        }


                        if(data[0].acadprogid == 6){
                            $('#course_holder').removeAttr('hidden')
                            $('#course')[0].innerHTML = data[0].courseabrv
                        }else{
                            $('#adviser_holder').removeAttr('hidden')
                            $('#adviser')[0].innerHTML = data[0].adviser
                        }

                        load_all()
                        $('#with_enrollment').removeAttr('hidden')
                    }else{
                        if(type == 7){
                            window.location='/student/preenrollment'
                        }
                       
                        $('#card_1').attr('hidden','hidden')
                        $('.sy_holder').text(sy)
                        $('#not_enrolled_holder').removeAttr('hidden')
                        $('#links').removeAttr('hidden')
                    }
                }
            })
        }

        function load_all(){
            $.ajax({
                type:'GET',
                url: '/current/billingassesment',
                    data:{
                        month:month,
                    },
                success:function(data) {
                    if(data.length > 0){
                        var abalance = parseFloat(0).toFixed(2)
                        $.each(data.filter(x=>x.particulars),function(a,b){
                            abalance = parseFloat(abalance) + parseFloat(b.balance.replace(",", ""))
                            $('#balance').append('<tr><td>'+b.particulars+'</td><td class="text-right align-middle">&#8369; '+b.balance+'</td></tr>')
                        })

                        $('#balance').append('<tr><td class="align-middle"><h6>TOTAL ASSESSMENT</h6></td><td class="text-right align-middle"><h6 class="text-primary">&#8369; '+abalance.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")+'<h6></td></tr>')
                        $('#ba_sched_card').removeAttr('hidden')

                    }else{
                        $('#balance').append('<tr><td class="align-middle" colspan="2"><i>No remaining balance for the month. Please visit Billing Information.</i></tr>')
                        $('#ba_sched_card').removeAttr('hidden')
                    }
                }
            })

            $.ajax({
                type:'GET',
                url: '/current/schedule',
                    data:{
                        month:month,
                    },
                success:function(data) {

                    var current_sched = data.filter(x=>x.sched == "current");
                    if(current_sched.length > 0){
                        $('#curr_sched_subj').text(current_sched[0].subject)
                        $('#curr_sched_time').text(current_sched[0].time)
                        $('#curr_sched_teacher').text(current_sched[0].teacher)
                        $('#current_sched_card').removeAttr('hidden')
                    }else{
                        $('#curr_sched_subj').text('No schedule')
                    }

                    var next_sched = data.filter(x=>x.sched == "next");
                    if(next_sched.length > 0){
                        $('#next_sched_subj').text(next_sched[0].subject)
                        $('#next_sched_time').text(next_sched[0].time)
                        $('#next_sched_teacher').text(next_sched[0].teacher)
                        $('#next_sched_card').removeAttr('hidden')
                    }else{
                        $('#next_sched_subj').text('No schedule')
                    }

                    $('#links').removeAttr('hidden')
                    if( current_sched.length == 0 && next_sched == 0){
                        $('#no_sched_card').removeAttr('hidden')
                    }

                }
            })

            get_calendar_activity()

        }
        
        function get_calendar_activity(){
            // $.ajax({
            //     type:'GET',
            //     url: '/calendar',
            //     success:function(data) {
            //         $('#calendar_holder').removeAttr('hidden')
            //         if(data.length > 0){
            //             $.each(data,function(a,b){
            //                 var current = ''
            //                 if(b.current == 1){
            //                     current = 'bg-success'
            //                 }
            //                 $('#calendar_activity').append('<tr class="'+current+'"><td width="30%" class="align-middle">'+b.date+'</td><td width="70%">'+b.description+'</td></tr>')
            //             })
            //         }else{
            //             $('#calendar_activity').append('<tr><td><i>No calendar activities.</i></td></tr>')
            //         }
                    
            //     }
            // })

            $('#calendar_holder').removeAttr('hidden')

            var type = @json(Session::get('currentPortal'));
            var syid = '<?php echo $syid; ?>';

            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
          
                initialView: 'listMonth',
                events: '/school-calendar/getall-event/'+type+'/'+syid,
                height : '100%',
                contentHeight : '100%',
                timeZone: 'UTC +8',
                themeSystem: 'bootstrap',
                selectable: true,
                editable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                headerToolbar: false,

                views: {
                    dayGridMonth: { // name of view
                        titleFormat: 
                            { year: 'numeric', month: 'long' } 
                    },

                    timeGridWeek: { // name of view
                        titleFormat: 
                            { year: 'numeric', month: 'short', day: 'numeric' }
                        
                    },

                    timeGridDay: { // name of view
                        titleFormat: 
                            { year: 'numeric', month: 'long', day: 'numeric' }
                        
                    },
                },
                businessHours: {
                    
                    startTime: '06:00', // a start time (6am in this example)
                    endTime: '19:00', // an end time (6pm in this example)
                },

                select: function (info){

                    var start = info.startStr;
                    var end = info.endStr;
                    var id = info.id;

                    $('#addEventLabel').text("Add Event")
                    $('#act_desc').val("")
                    $('#act_venue').val("")
                    $('.update').addClass('add_event')
                    $('.add_event').removeClass('update')
                    $('.add_event').text("Add")
                    $('#person_involved').val(0).trigger('change');
                    $('#acad_prog').val(0).trigger('change');
                    $('#grade_level').val(0).trigger('change');
                    $('#courses').val(0).trigger('change');
                    $('#colleges').val(0).trigger('change');
                    $('#isNoClass').prop('checked', false);
                    $('#faculty').val(0).trigger('change');
                    // $(".addEvent-modal-content").load(location.href + " .addEvent-modal-content");
                    $('#addEvent').modal('toggle');

                    $('#start').val(start);
                    $('#end').val(end);
                    
                    
                },
                eventDrop: function (info){

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;

                    $.ajax({

                        url:'{{ route("update.event") }}',
                        type:"GET",
                        data:{

                            id: id,
                            start: start,
                            end: end,
                        },
                        success:function(data){
                            
                            weeklist.refetchEvents();


                        }
                    });
                }, 
                eventClick: function(info){ 

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;

                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },
                eventResize: function( info ) {

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;

                    $.ajax({

                        url:'{{ route("update.event") }}',
                        type:"GET",
                        data:{

                            id: id,
                            start: start,
                            end: end,
                        },
                        success:function(data){
                            

                        }
                    });
                },

                
            });


            calendar.render();
        }


    })
</script>



@endsection
