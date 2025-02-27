@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <style>
        .smfont{
            font-size:14px;
        }
    </style>
@endsection

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row">
      <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><span class="text-dark">TEACHER : </span><b><u>{{$teacherInfo[0]->lastname}}, {{$teacherInfo[0]->firstname}}</u></b></h4>
        <h5 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
        <span class="text-dark">CLASS ADVISORY :</span>
        <u>
        @if($teacherInfo[0]->levelname!=null)
            {{$teacherInfo[0]->levelname}} - {{$teacherInfo[0]->sectionname}}
        @else
            No advisory assigned
        @endif
        </u>
        </h5>
                <!-- <h3 class="profile-username text-center mb-0 mt-3">{{$teacherInfo[0]->lastname}}</h3>
                <p class="text-muted text-center ">{{$teacherInfo[0]->firstname}}</p> -->
      </div>
      <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item"><a href="/principalPortalTeachers">Teacher</a></li>
      </ol>
      </div>
  </div>
  </div>
</section>
<!-- <section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/principalPortalTeachers">Teacher</a></li>
                </ol>
            </div>
        </div>
    </div>
</section> -->


<section>
    <div class="row">
       
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        Class Schedule
                    </h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 268px;">
                    <table class="table table-head-fixed smfont table-bordered">
                        <thead>
                            <tr>
                                <th width="15%" class="text-center align-middle">Section</th>
                                <th width="30%" class="text-center align-middle">Subject</th>
                                <th width="20%" class="text-center align-middle">Day</th>
                                <th width="15%" class="text-center align-middle">Time</th>
                                <th width="15%" class="text-center align-middle">Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teaacherClassSched as $item)
                                <tr class="text-center align-middle">
                                    @if($item->subjinfo->levelname!=null)
                                        <td >{{$item->subjinfo->levelname}} <br> {{$item->subjinfo->sectionname}}</td>
                                        <td >{{$item->subjinfo->subjdesc}}</td>
                                    @elseif($item->subjinfo->shlevelname!=null)
                                        <td>{{$item->subjinfo->shlevelname}} <br> {{$item->subjinfo->shsectionname}}</td>
                                        <td>{{$item->subjinfo->subjtitle}}</td>
                                    @elseif($item->subjinfo->shblocklevelname!=null)
                                        <td>{{$item->subjinfo->shblocklevelname}} <br> {{$item->subjinfo->shblocksectionname}}</td>
                                        <td>{{$item->subjinfo->shblocksubjtitle}}</td>
                                    @else
                                        <td colspan="2" class="text-center" >No Schedule Set</td>
                                    @endif

                                    @if($item->subjinfo->levelname!=null)
                                        <td >{{$item->daysum}}</td>
                                        <td >{{\Carbon\Carbon::create($item->subjinfo->stime)->isoFormat('hh:mm a')}} <br> {{\Carbon\Carbon::create($item->subjinfo->etime)->isoFormat('hh:mm a')}}</td>
                                        <td >{{$item->subjinfo->roomname}}</td>
                                    @elseif($item->subjinfo->shlevelname!=null)
                                        <td >{{$item->daysum}}</td>
                                        <td >{{\Carbon\Carbon::create($item->subjinfo->shstime)->isoFormat('hh:mm a')}} <br> {{\Carbon\Carbon::create($item->subjinfo->shetime)->isoFormat('hh:mm a')}}</td>
                                        <td class="justify-content-start">{{$item->subjinfo->shroomname}}</td>
                                     @elseif($item->shblocklevelname!=null)
                                        <td >{{$item->daysum}}</td>
                                        <td >{{\Carbon\Carbon::create($item->subjinfo->shblockstime)->isoFormat('hh:mm a')}} <br> {{\Carbon\Carbon::create($item->subjinfo->shblocketime)->isoFormat('hh:mm a')}}</td>
                                        <td class="justify-content-start">{{$item->subjinfo->shblockroomname}}</td>
                                    @else
                                        <td colspan="3" class="text-center" >No Schedule Set</td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
         
            <div class="main-card mb-3 card">
                    <div class="card-header bg-primary">
                        <h5 class="card-title">Grade Reports</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="card-body table-responsive p-0" style="max-height: 315px;">
                            <table class="mb-0 table table-sm table-head-fixed smfont table-bordered" >
                                <thead>
                                    <tr>
                                        <th width="28%" class="pt-2 pb-2">Grade Level Section</th>
                                        <th class="text-center  p-2" width="18%">1st Quarter</th>
                                        <th class="text-center  p-2 " width="18%">2nd Quarter</th>
                                        <th class="text-center p-2 " width="18%">3rd Quarter</th>
                                        <th class="text-center p-2 " width="18%">4th Quarter</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @foreach ($teachersSubjects as $teachersSubject)
                                    
                                            <tr>
                                                <td>
                                                   
                                                    <span class="d-block">{{$teachersSubject->levelname}} - {{$teachersSubject->sectionname}} </span>
                                                    <span class="d-block ">{{$teachersSubject->subjcode}}</span>
                                                   
                                                </td>
        
                                                @for($x=1;$x<5;$x++)
                                                    @php
                                                        $date_submitted;
                                                        $countMatch=0;
                                                        $status="";
                                                        $id;
                                                    @endphp
                                                
                                                    @foreach($submittedGrades as $submittedGrade)
                                                   
                                                        @if($submittedGrade->quarter==$x 
                                                            && $submittedGrade->sectionid == $teachersSubject->sectionid
                                                            && $submittedGrade->subjid == $teachersSubject->subjid 
                                                            )
                                                                

                                                            @php
                                                                $countMatch+=1;
                                                                $id = $submittedGrade->gradeid;
                                                                $date_submitted = $submittedGrade->date_submitted;
                                                            @endphp
                                                        
                                                            @if( $submittedGrade->submitted==1) 
                                                                @php
                                                                    $status = "submitted";
                                                                @endphp
                                                                 
                                                            @endif

                                                            @if($submittedGrade->status==1  && $submittedGrade->submitted==1)
                                                      
                                                                @php
                                                                    $status = "submitted";
                                                                @endphp
                                                            @elseif($submittedGrade->status==3  && $submittedGrade->submitted==1)
                                                                @php
                                                                    $status = "pending";
                                                                @endphp
                                                            @elseif($submittedGrade->status==2  && $submittedGrade->submitted==1)
                                                                @php
                                                                    $status = "approve";
                                                                @endphp
                                                            @elseif($submittedGrade->status==4  && $submittedGrade->submitted==1)
                                                                @php
                                                                    $status = "posted";
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    
                                                    @if($countMatch>0)
                                                        <td width="18%" class="text-center">
                                                            @if($status == "approve")
                                                                @php echo '<a href="/principalPortalGradeInformation/'.$id.'"class="nav-link p-0 d-block"> <span>'.date_format(date_create($date_submitted),"m/d/y").'<span class=" badge badge-primary d-block">Aprroved</span></span>' @endphp
                                                            @elseif($status == "submitted")
                                                                <a href="/principalPortalGradeInformation/{{$id}}"class="nav-link p-0 d-block"><span>{{date_format(date_create($date_submitted),"m/d/y")}}<span class=" badge badge-success d-block">Sumbitted</span></span></a>
                                                            @elseif($status == "posted")
                                                                <a href="/principalPortalGradeInformation/{{$id}}"class="nav-link p-0 d-block"><span>{{date_format(date_create($date_submitted),"m/d/y")}}<span class=" badge badge-info d-block">Posted</span></span></a>
                                                            @elseif($status == "pending")
                                                                @php echo '<a href="/principalPortalGradeInformation/'.$id.'"class="nav-link p-0 d-block"> <span>'.date_format(date_create($date_submitted),"m/d/y").'<span class=" badge badge-warning d-block">Pending</span></span>' @endphp   
                                                            @endif
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td width="18%"  class="text-center"></td>
                                                    @endif
        
                                                @endfor
                                            </tr>
                                        @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
           
    </div>
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile bg-success">
                <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"  style="box-shadow: 1px 1px 7px #000000" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                </div>
                
                <h3 class="profile-username text-center mb-0 mt-3">{{$teacherInfo[0]->lastname}}</h3>
                <p class=" text-center ">{{$teacherInfo[0]->firstname}} <br>
                @if($teacherInfo[0]->usertypeid == 1)
                    <span><b>TEACHER</b></span>
                @elseif($teacherInfo[0]->usertypeid == 2)
                    <span><b>PRINCIPAL</b></span>
                @endif
                </p>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body bg-warning">
                <strong><i class="fas fa-book mr-1"></i>Class Advisory</strong>
                <p class="text-muted mb-0 ml-3">
                    @if(count($advisoryClass) > 0)
                        @foreach ($advisoryClass as $item)
                            {{$item->sectionname}} <br>
                        @endforeach
                    @else
                        No Advisory assigned
                    @endif
                </p>
            </div>
        </div>
        <div class="main-card mb-3 card">
                <div class="card-header bg-info">
                    <h5 class="card-title">Grade Logs</h5>  
                </div>
                <div class="card-body p-0">
                    <div class="card-body table-responsive p-0" style="height: 315px;">
                        <table class="table table-head-fixed small">
                            
                            @foreach($gradelogs[0]->data as $gradelog)
                                <tr>
                                    <td>
                                        @if($gradelog->acadprogid == 5)
                                            Subject: <span class="float-right" style="width:70%;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;">{{$gradelog->subjtitle}}</span><br>
                                        @else
                                            Subject: <span class="float-right"  >{{$gradelog->subjcode}}</span><br>
                                        @endif
                                        @if($gradelog->quarter==1)
                                            Quarter: <span class="float-right">1st Quarter</span><br>
                                        @elseif($gradelog->quarter==2)
                                            Quarter: <span class="float-right">2nd Quarter</span><br>
                                        @elseif($gradelog->quarter==3)
                                            Quarter: <span class="float-right">3rd Quarter</span><br>
                                        @elseif($gradelog->quarter==4)
                                            Quarter: <span class="float-right">4th Quarter</span><br>
                                        @endif
                                        Date: <span class="float-right">{{\Carbon\Carbon::create($gradelog->createddatetime)->isoFormat('MMM DD, YYYY')}}</span><br>
                                        Time: <span class="float-right">{{\Carbon\Carbon::create($gradelog->createddatetime)->isoFormat('hh:mm a')}}</span><br>
                                        @if($gradelog->action==1)
                                            Status: <span class="float-right badge bg-primary">Submitted</span><br>
                                        @elseif($gradelog->action==2)
                                            Status: <span class="float-right badge bg-success">Approved</span><br>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
    </div>
</section>

{{-- <div class="row">
    <div class="col-md-8">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <h5 class="card-title">Grade Reports</h5>
            </div>
            <div class="card-body p-0">
                <div class="card-body table-responsive p-0" style="height: 315px;">
                    <table class="mb-0 table table-sm table-head-fixed" >
                        <thead>
                            <tr>
                                <th width="28%" class="text-center  p-2">Grade Level Section</th>
                                <th class="text-center  p-2" width="18%">1st Quarter</th>
                                <th class="text-center  p-2 " width="18%">2nd Quarter</th>
                                <th class="text-center p-2 " width="18%">3rd Quarter</th>
                                <th class="text-center p-2 " width="18%">4th Quarter</th>
                            </tr>
                        </thead>
                            <tbody>
                                
                                @foreach ($teachersSubjects as $teachersSubject)
                            
                                    <tr>
                                        <td>
                                            <span class="d-block">{{$teachersSubject->levelname}} - {{$teachersSubject->sectionname}} </span>
                                            <span class="d-block ">{{$teachersSubject->subjcode}}</span>
                                        </td>

                                        @for($x=1;$x<5;$x++)
                                            @php
                                                $date_submitted;
                                                $countMatch=0;
                                                $status="";
                                                $id;
                                            @endphp
                                        
                                            @foreach($submittedGrades as $submittedGrade)
                                                
                                                @if($submittedGrade->quarter==$x 
                                                    && $submittedGrade->sectionid == $teachersSubject->sectionid
                                                    && $submittedGrade->subjid == $teachersSubject->subjid)
                                                    @php
                                                        $id = $submittedGrade->id;
                                                        $countMatch+=1;
                                                        $date_submitted = $submittedGrade->date_submitted;
                                                    @endphp
                                                
                                                    @if($submittedGrade->submitted==1)
                                                        @php
                                                            $status = "submitted";
                                                        @endphp
                                                    
                                                    @endif

                                                    @if($submittedGrade->status==1)
                                                        @php
                                                            $status = "approve";
                                                        @endphp
                                                    @elseif($submittedGrade->status==3)
                                                        @php
                                                            $status = "pending";
                                                        @endphp
                                                    @elseif($submittedGrade->status==2)
                                                        @php
                                                            $status = "posted";
                                                        @endphp
                                                    @endif
                                                
                                                @endif

                                            @endforeach
                                            
                                            @if($countMatch>0)
                                                
                                                <td width="18%" class="text-center">
                                                    
                                                    @if($status == "approve")
                                                        @php echo '<a href="/principalPortalGradeInformation/'.$id.'"class="nav-link p-0 d-block"> <span>'.date_format(date_create($date_submitted),"m/d/y").'<span class=" badge badge-primary d-block">Aprroved</span></span>' @endphp
                                                    @elseif($status == "submitted")
                                                        <a href="/principalPortalGradeInformation/{{$id}}"class="nav-link p-0 d-block"><span>{{date_format(date_create($date_submitted),"m/d/y")}}<span class=" badge badge-success d-block">Sumbitted</span></span></a>
                                                    @elseif($status == "posted")
                                                        <a href="/principalPortalGradeInformation/{{$id}}"class="nav-link p-0 d-block"><span>{{date_format(date_create($date_submitted),"m/d/y")}}<span class=" badge badge-info d-block">Posted</span></span></a>
                                                    @else
                                                        @php echo '<a href="/principalPortalGradeInformation/'.$id.'"class="nav-link p-0 d-block"> <span>'.date_format(date_create($date_submitted),"m/d/y").'<span class=" badge badge-warning d-block">Pending</span></span>' @endphp   
                                                    @endif
                                                    </a>
                                                </td>
                                            @else
                                                <td width="18%"  class="text-center"></td>
                                            @endif

                                        @endfor
                                    </tr>
                                @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <h5 class="card-title">Grade Logs</h5>  
            </div>
            <div class="card-body p-0">
                <div class="card-body table-responsive p-0" style="height: 315px;">
                    <table class="table table-head-fixed ">
                        @foreach($gradelogs as $gradelog)
                            <tr>
                                <td>
                                    @if($gradelog->action == 3)
                                   
                                        {{$gradelog->name}} is requesting you to evaluate the {{$gradelog->subjcode}}
                                        @if($gradelog->quarter==1)
                                            1st Quarter Grades
                                        @elseif($gradelog->quarter==2)
                                            2nd Quarter
                                        @elseif($gradelog->quarter==3)
                                            3rd Quarter Grades
                                        @elseif($gradelog->quarter==4)
                                            4th Quarter Grades
                                        @endif
                                        for {{$gradelog->levelname}} - {{$gradelog->sectionname}}
                                        that you submitted
                                 
                                @else
                                   
                                        {{$gradelog->subjcode}}
                                        @if($gradelog->quarter==1)
                                            1st Quarter Grades
                                        @elseif($gradelog->quarter==2)
                                            2nd Quarter
                                        @elseif($gradelog->quarter==3)
                                            3rd Quarter Grades
                                        @elseif($gradelog->quarter==4)
                                            4th Quarter Grades
                                        @endif

                                        for {{$gradelog->levelname}} - {{$gradelog->sectionname}} was 
                                        @if($gradelog->action==1)
                                            submitted
                                        @elseif($gradelog->action==2)
                                            approved
                                        @endif
                                        by {{$gradelog->name}} last {{\Carbon\Carbon::create($gradelog->date)->isoFormat('MMM DD, YYYY hh:mm a')}}
                              

                                @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="row">
        <div class="col-md-12">
                <div class="card card-primary card-outline">
                        <div class="card-header bg-info">
                          <h3 class="card-title">
                            <i class="fas fa-edit"></i>
                                Attendance Report
                          </h3>
                        </div>
                        <div class="card-body">
                          <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Daily</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Monthly</a>
                            </li>
                           
                          </ul>
                          <div class="tab-content" id="custom-content-below-tabContent">
                            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body table-responsive p-0" style="height: 440px;">
                                                <table class="table table-head-fixed">
                                                    <tbody>
                                                        @foreach ($dailyReport as $attendance)
                                                        <tr>
                                                            <td class="p-2">{{$attendance->date}}</td>
                                                            @if($attendance->status=="late")
                                                                 <td class="p-2"><span style="width:70px" class="float-right badge badge-warning badge-pill">{{$attendance->status}}
                                                                </span></td>
                                                            @elseif($attendance->status=="on time")
                                                                <td class="p-2"><span  style="width:70px" class="float-right badge badge-success badge-pill">{{$attendance->status}}
                                                                </span></td>
                                                            @else
                                                                <td class="p-2"><span  style="width:70px" class="float-right badge badge-danger badge-pill">{{$attendance->status}}
                                                                </span></td>
                                                            @endif
                                                            @if($attendance->status!="absent")
                                                                <td class="p-2"><span class="mr-2 float-right badge badge-primary badge-pill">{{$attendance->time}}</span></td>
                                                            @else 
                                                                <td class="p-2"><span class="mr-2 float-right badge badge-primary badge-pill">00:00</span></td>
                                                            @endif
    
                                                            
                                                            <td></td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
    
                                    </div>
                                    <div class="col-md-6">
                                            <canvas id="myChart" width="400" height="380"></canvas>
                                    </div>
                                </div>
                            </div>
                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <table class="mb-0 table table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th></th>
                                        @foreach($monthlyReports as $monthlyReport)
                                            <th class="text-center">{{$monthlyReport->month}}</th>
                                        @endforeach
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>No. of <br>School Days</th>
                                        @foreach($monthlyReports as $monthlyReport)
                                            <td class="text-center">{{$monthlyReport->numDays}}</td>
                                        @endforeach
                                        <th class="text-center">{{$yearlyReport->numDays}}</th>
                                    </tr>
                                    <tr>
                                        <th>No. of <br>Days Present</th>
                                        @foreach($monthlyReports as $monthlyReport)
                                            <td class="text-center"> {{$monthlyReport->present}}</td>
                                        @endforeach
                                        <th class="text-center">{{$yearlyReport->present}}</th>
                                    </tr>
                                    <tr>
                                        <th >No. of <br>Days Absent</th>
                                        @foreach($monthlyReports as $monthlyReport)
                                            <td class="text-center">{{$monthlyReport->absent}}</td>
                                        @endforeach
                                        <th class="text-center">{{$yearlyReport->absent}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- <div class="row">
    <div class="col-md-12">
            <div class="mb-3 card">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-bicycle icon-gradient bg-love-kiss"> </i>
                            Attendance Report
                        </div>
                        <ul class="nav">
                            <li class="nav-item"><a data-toggle="tab" href="#tab-eg5-0" class="nav-link show active">Daily</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#tab-eg5-1" class="nav-link show">Monthly</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="tab-eg5-0" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="scroll-area-sm" style="height: 410px !important">
                                            <div class="scrollbar-container ps--active-y ps">
                                                <ul class="list-group list-group-flush">
                                                @foreach ($dailyReport as $attendance)
                                                    <a href="javascript:void(0);" class="disabled list-group-item">{{$attendance->date}}
                                                    @if($attendance->status=="late")
                                                        <span style="width:70px" class="float-right badge badge-warning badge-pill">{{$attendance->status}}
                                                        </span>
                                                    @elseif($attendance->status=="on time")
                                                        <span  style="width:70px" class="float-right badge badge-success badge-pill">{{$attendance->status}}
                                                        </span>
                                                    @else
                                                        <span  style="width:70px" class="float-right badge badge-danger badge-pill">{{$attendance->status}}
                                                        </span>
                                                    @endif
                                                    <span class="mr-2 float-right badge badge-primary badge-pill">{{$attendance->time}}</span>
                                                </a>
                                                @endforeach
                                            <div class="ps__rail-x" style="left: 0px; bottom: -200px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 200px; height: 200px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 34px; height: 34px;"></div></div></div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        
                                        <canvas id="myChart" width="400" height="380"></canvas>
                                    </div>
                                </div>
                                    
                                </div>
                            <div class="tab-pane show " id="tab-eg5-1" role="tabpanel">
                                
                                <div class="scroll-area-md">
                                    <div class="scrollbar-container ps--active-y ps">
                                <table class="mb-0 table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($monthlyReports as $monthlyReport)
                                                <th class="text-center">{{$monthlyReport->month}}</th>
                                            @endforeach
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>No. of <br>School</th>
                                            @foreach($monthlyReports as $monthlyReport)
                                                <td class="text-center">{{$monthlyReport->numDays}}</td>
                                            @endforeach
                                            <th class="text-center">{{$yearlyReport->numDays}}</th>
                                        </tr>
                                        <tr>
                                            <th>No. of <br>Days<br>Present</th>
                                            @foreach($monthlyReports as $monthlyReport)
                                                <td class="text-center"> {{$monthlyReport->present}}</td>
                                            @endforeach
                                            <th class="text-center">{{$yearlyReport->present}}</th>
                                        </tr>
                                        <tr>
                                            <th >No. of <br>Days<br>Absent</th>
                                            @foreach($monthlyReports as $monthlyReport)
                                                <td class="text-center">{{$monthlyReport->absent}}</td>
                                            @endforeach
                                            <th class="text-center">{{$yearlyReport->absent}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            </div>
                            <div class="tab-pane show" id="tab-eg5-2" role="tabpanel">
                               
                            </div>
                        </div>
                    </div>
                
                </div>
    </div>
</div> --}}
<script>

    var ctx = document.getElementById('myChart');

    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Working Days', 'Present', 'Absent', 'On Time', 'Late'],
        datasets: [{
            label: '',
            data: [ '{{$yearlyReport->numDays}}', '{{$yearlyReport->present}}', '{{$yearlyReport->absent}}', '{{$yearlyReport->ontime}}', '{{$yearlyReport->late}}'],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

@endsection

@section('footerjavascript')

    <script>

        $(document).on('change','#subject',function(){
            $('.suggested').empty();
        })

        $(document).on('click','.generate',function(){

            var days = [];
            var gradelevel = [];
            var subject = $('#subject').val();

            $("input[name='D[]'").each(function(){
                if($(this).is(":checked")){
                    days.push($(this).val());
                }    
            })
            $("input[name='S[]'").each(function(){
                if($(this).is(":checked")){
                    gradelevel.push($(this).val());
                }    
            })

            $.ajax({
                type:'GET',
                url:'/generateClassSchedule',
                data:{days:days,gradelevel:gradelevel,subject:subject,t:'{{$teacherInfo[0]->id}}'},
                success:function(data) {
                    $('.suggested').empty();
                    $('.suggested').append(data);
                    }
            })

         
        })

        $(document).on('click','.savebutton',function(){

            var data = [];
            var days = [];

            $("input[name='D[]'").each(function(){
                if($(this).is(":checked")){
                    days.push($(this).val());
                }    
            })
            
            $('.suggested tr').each(function(){
               
                if($('input.'+$(this).attr('class')).is(':checked')){
                    var s = $('input.'+$(this).attr('class')).val();
                    var t =  $('td.'+($(this).attr('class'))+'.time').html()
                    var d = $('td.'+($(this).attr('class'))+'.day').html()
                    data.push({s:s,t:t,d:d})
                }
              
            })

            console.log(data);

            $.ajax({
                type:'GET',
                url:'/insertClassSchedule',
                data:{
                    // d:d,
                    s:$('#subject').val(),
                    i:data,
                    t:'{{$teacherInfo[0]->id}}'},
                success:function(data) {
                    location.reload();
                }
            })

        })

       


        
    </script>
        


@endsection

