@extends('parentsPortal.layouts.app2')

@section('pagespecificscripts')

        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
        
    
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">



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


<section class="content pt-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    {{-- <div class="col-md-12" hidden id="current_enrollmentinfo_card">
                        <div class="small-box bg-light shadow">
                            <div class="inner">
                                <h5 id="name">&nbsp;</h5>
                                <p class="mb-0" id="sid">&nbsp;</p>
                                <p class="mb-0" id="gradelevel">&nbsp;</p>
                                <p class="mb-0" id="section">&nbsp;</p>
                                <p id="adviser">&nbsp;</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user"></i>
                            </div>
                          </div>
                    </div> --}}
                    <div class="col-md-12" hidden id="current_enrollmentinfo_card">
                        <div class="card shadow bg-primary">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0">
                              <h4 class="mb-0" style="font-size:15px !important">Enrollment Information <b><i id="sydesc" class="float-right"></i></b></h4>
                              
                            </div>
                            <div class="card-footer bg-white p-0">
                              <ul class="nav nav-pills flex-column" style="font-size:12px !important">
                                <li class="nav-item">
                                  <span class="nav-link">
                                    Student Name
                                    <span class="float-right text-secondary" id="name"></span>
                                  </span>
                                </li>
                                <li class="nav-item">
                                  <span class="nav-link">
                                    SID
                                    <span class="float-right text-secondary" id="sid"></span>
                                  </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                      Grade Level
                                      <span class="float-right text-secondary" id="gradelevel"></span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                      Section
                                      <span class="float-right text-secondary" id="section"></span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                      Adviser
                                      <span class="float-right text-secondary" id="adviser"></span>
                                    </span>
                                </li>
                              </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="ba_sched_card" hidden>
                        <div class="card shadow bg-warning">
                            <div class="card-header pl-3 pr-3 pt-2 pb-2 border-0">
                                <h4 class="mb-0" style="font-size:15px !important">Billing assesment<b class="float-right"> <i>{{strtoupper(\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM'))}}</b></i> </h4>
                            </div>
                            <div class="card-footer bg-white p-0">
                              <ul class="nav nav-pills flex-column" style="font-size:12px !important" id="balance">
                               
                              </ul>
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
                                    Current Schedule <i class="float-right">{{\Carbon\Carbon::now('Asia/Manila')->format('l')}}</i>
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
                                    Next Schedule <i class="float-right">{{\Carbon\Carbon::now('Asia/Manila')->format('l')}}</i>
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
                            <div class="card-body pl-3 pr-3 pt-2 pb-2 user-block">
                                <span class="description m-0" style="font-size:13px !important">
                                    All class has ended for today <i>( {{\Carbon\Carbon::now('Asia/Manila')->format('l, M d, Y')}})</i>.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="links" hidden>
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body p-2 pb-0">
                                <a class="btn btn-app"  href="parentsstudentprofile">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <a class="btn btn-app" href="parentsPortalGrades">
                                    <i class="far fa-folder-open"></i> Reports
                                </a>
                                <a class="btn btn-app" href="parentschoolCalendar">
                                    <i class="far fa-calendar-alt"></i> Calendar
                                </a>
                                <a class="btn btn-app" href="/payment" hidden>
                                    <i class="fas fa-file-upload"></i> Payment Upload
                                </a>
                                <a class="btn btn-app" href="/student/enrollment/record/cashier" >
                                    <i class="nav-icon fas fa-cash-register"></i> Payment transactions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
<section>

<script src="{{ asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>

<script>
    $(document).ready(function(){

        var month = @json(\Carbon\Carbon::now('Asia/Manila')->isoFormat('MM'));
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
                        $('#adviser')[0].innerHTML = data[0].adviser
                        $('#current_enrollmentinfo_card').removeAttr('hidden')
                        load_all()
                        $('#with_enrollment').removeAttr('hidden')
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

                    $.each(data.filter(x=>x.particulars != 'TOTAL'),function(a,b){
                        $('#balance').append('<li class="nav-item">'+
                                  '<span class="nav-link">'+b.particulars+
                                    '<span class="float-right text-secondary">&#8369; '+b.balance+'</span>'+
                                  '</span>'+
                                '</li>'
                        )
                    })
                    var temp_asssesment = data.filter(x=>x.particulars == 'TOTAL')
                    $('#balance').append('<li class="nav-item">'+
                                  '<span class="nav-link">TOTAL ASSESSMENT'+
                                    '<span class="float-right text-primary"><h5>&#8369; '+temp_asssesment[0].balance+'<h5></span>'+
                                  '</span>'+
                                '</li>'
                        )
                    // $('.billing_assesment')[0].innerHTML = '&#8369; ' + temp_asssesment[0].balance
                    $('#ba_sched_card').removeAttr('hidden')
                        
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
        }

        

    })
</script>



@endsection
