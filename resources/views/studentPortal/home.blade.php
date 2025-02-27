@extends('studentPortal.layouts.app2')

@section('pagespecificscripts')
    @if($enrollmentstatus == 'Enrolled')
        <script>
            let subjectattendanceevtSource = new EventSource("/homeevent", {withCredentials: true});
            subjectattendanceevtSource.onmessage = function (e) {
                let data = JSON.parse(e.data);
            $('#attendancetable').empty();
            $('#attendancetable').append(data);
            
            };
        </script>
        {{-- <script>
            let tapstate = new EventSource("/tapstate", {withCredentials: true});
            tapstate.onmessage = function (e) {
                    let data = JSON.parse(e.data);
                    $('.tapstate').empty();
                    $('.tapstate').append(data);
                        
                };
        </script> --}}
     @endif
@endsection

@section('content')
    @if($enrollmentstatus == 'Enrolled')    
   
  
        <section class="content pt-2">
            <div class="container-fluid">
                <div class="row">
               
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center mb-3">
                                <img class="profile-user-img img-fluid img-circle" src="{{asset($todaySchoolAttendance[0]->picurl)}}" alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center mb-0">{{$todaySchoolAttendance[0]->firstname}}  {{$todaySchoolAttendance[0]->lastname}}</h3>
                
                                <p class="text-muted text-center">{{$todaySchoolAttendance[0]->levelname}} - {{$todaySchoolAttendance[0]->sectionname}}</p>
                
                                <ul class="list-group list-group-unbordered mb-0 tapstate">
                                <li class="list-group-item">
                                    <b>Date</b> <a class="float-right  mb-0 h5 text-info">{{\Carbon\Carbon::now()->isoFormat('MMM DD, YYYY')}}</a>
                                </li>
                                @if(count($todaySchoolAttendance)==0)
                                    <li class="list-group-item">
                                        <b>Time In</b> <a class="float-right">
                                        <a class="float-right text-danger h5 mb-0">00:00</a></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Time Out</b>
                                        <a class="float-right text-danger h5 mb-0">00:00</a></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Status</b>
                                        <a class="float-right text-danger h5 mb-0">Outside Campus</a>
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <b>Time In</b> <a class="float-right">
                                        @if($todaySchoolAttendance[0]->intimepm != NULL && $todaySchoolAttendance[0]->intimepm != '12:00 am')
                                            <a class="float-right text-success h5 mb-0">{{\Carbon\Carbon::create($todaySchoolAttendance[0]->intimepm)->isoFormat('hh : mm a')}}</a> 
                                        @elseif($todaySchoolAttendance[0]->intimeam != NULL && $todaySchoolAttendance[0]->intimepm != '12:00 am')
                                            <a class="float-right text-success h5 mb-0">{{\Carbon\Carbon::create($todaySchoolAttendance[0]->intimeam)->isoFormat('hh : mm a')}}</a> 
                                        @else
                                            <a class="float-right text-danger h5 mb-0">00 : 00</a> 
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <b>Time Out</b>
                                        @if($todaySchoolAttendance[0]->outtimepm != NULL && $todaySchoolAttendance[0]->intimepm != '12:00 am')
                                            <a class="float-right text-success h5 mb-0">{{\Carbon\Carbon::create($todaySchoolAttendance[0]->outtimepm)->isoFormat('hh : mm a')}}</a> 
                                        @elseif($todaySchoolAttendance[0]->outtimeam != NULL && $todaySchoolAttendance[0]->intimepm != '12:00 am')
                                            <a class="float-right text-success h5 mb-0">{{\Carbon\Carbon::create($todaySchoolAttendance[0]->outtimeam)->isoFormat('hh : mm a')}}</a> 
                                        @else
                                            <a class="float-right text-danger h5 mb-0">00 : 00</a> 
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <b>Status</b>
                                        @if($todaySchoolAttendance[0]->tapstate == 'IN' )
                                            <a class="float-right text-success h5 mb-0">Inside Campus
                                                <br><span class="h6 float-right text-info mb-0">{{\Carbon\Carbon::create($todaySchoolAttendance[0]->updateddatetime)->isoFormat('hh : mm a')}}</span></a> 
                                        @else
                                            <a class="float-right text-danger h5 mb-0">Outside Campus
                                                <br><span class="h6 float-right text-info mb-0">{{\Carbon\Carbon::create($todaySchoolAttendance[0]->updateddatetime)->isoFormat('hh : mm a')}}</span></a> 
                                        @endif
                                    </li>
                                @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-12 ">
                        {{-- <div class="card ">
                            <div class="card-header bg-success  ">
                                Subject Attendance
                            </div>
                            <div class="card-body  p-0">
                                <table class="table" >
                                    <tbody id="attendancetable">
                                        @if(count($todaySubjectAttendance)>0)
                                            @foreach ($todaySubjectAttendance as $item)
                                                <tr>
                                                    <td  width=20%>{{$item->subjcode}}</td>
                                                
                                                    @if($item->classstatus=="class over")
                                                        <td class="text-center" width=10%>
                                                            <span class="badge bg-secondary  d-block p-2">{{$item->classstatus}}</span>
                                                        </td>
                                                    @elseif($item->classstatus=="current class")
                                                        <td class="text-center"  width=10%>
                                                            <span class="badge bg-success  d-block p-2">{{$item->classstatus}}</span>
                                                        </td>
                                                    @else
                                                        <td colspan="2" class="text-center"  width=10%>
                                                            <span class="badge bg-info  d-block p-2">Starts in {{$item->classstatus}} </span>
                                                        </td>
                                                    @endif
                                                    @if($item->subjectattendance=='ABSENT' && $item->classstatus=="current class" || ($item->subjectattendance=='ABSENT' && $item->classstatus=="class over"))
                                                        <td class="text-center" width=10%>
                                                            <span class="badge bg-danger p-2">Absent</span>
                                                        </td>
                                                    @elseif($item->subjectattendance=='PRESENT' && $item->classstatus=="current class" || ($item->subjectattendance=='PRESENT' && $item->classstatus=="class over"))
                                                        <td class="text-center" width=10%>
                                                            <span class="badge bg-success p-2">Present</span>
                                                        </td>
                                                    @elseif($item->subjectattendance=='LATE' && $item->classstatus=="current class" )
                                                        <td class="text-center" width=10%>
                                                            <span class="badge bg-warning p-2">Late</span>
                                                        </td>
                                                    @elseif($item->classstatus=="class over" || $item->classstatus=="current class")
                                                        <td class="text-center" width=10%>
                                                            <span class="badge bg-warning p-2">Not Checked</span>
                                                        </td>
                                                    @endif
                                                <tr>
                                            @endforeach
                                        @else
                                            <tr><td>You dont have Schedules for this day.<td><tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>         
        </section>
    @else
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
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
