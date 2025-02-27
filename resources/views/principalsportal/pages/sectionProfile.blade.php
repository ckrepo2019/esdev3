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
    @endif

@endif

@extends($xtend)

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <style>
        .smfont{
            font-size:14px;
        }
        .select2-container--default .select2-selection--single, .form-control {
            border-radius: 0 !important; 
            font-size:14px !important;
        }
        .calendar-table{
            display: none;
        }
        .drp-buttons{
            display: none !important;
        }
        #et{
            height: 10px;
            visibility: hidden;
        }
       
    </style>
@endsection

@section('modalSection')
    <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header ">
              <h4 class="modal-title">Add Class Schedule
               
            </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
                <input id="temp" type="hidden">
                <div class="scheduleInfo mt-1">
                    
                </div>
                <hr>
                <label for="">Schedule classification</label>
                <div class="form-group">
                    <label for=""></label>
                    <select name="classification" id="classification" class="form-control">
                        @foreach(DB::table('schedclassification')->where('deleted','0')->get() as $item)
                            <option value="{{$item->id}}">{{$item->description}}</option>
                        @endforeach
                       
                    </select>
                </div>
                <p>Apply changes to:</p>
                <div class="form-group clearfix">
                    
                    <div class="icheck-primary d-inline mr-3">
                      <input type="checkbox" id="Mon" class="day" value="1" checked>
                      <label for="Mon">Mon
                      </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Tue" class="day" value="2" checked>
                        <label for="Tue">Tue
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Wed" class="day" value="3" checked>
                        <label for="Wed">Wed
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Thu" class="day" value="4" checked>
                        <label for="Thu">Thu
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Fri" class="day" value="5" checked>
                        <label for="Fri">Fri
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Sat" class="day" value="6" checked>
                        <label for="Sat">Sat
                        </label>
                    </div>
                    <div class="retun-message mt-1">

                    </div>
                    
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button onClick="this.form.submit(); this.disabled=true;" type="button" class="btn btn-primary eval">Proceed</button>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-section" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
            <h4 class="modal-title">Section Form</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
                <form method="GET" action="/updateSectionInformation">
                    <div class="modal-body">
                        <input type="hidden" name="sid" value="{{Crypt::encrypt($sectionInfo->id)}}}">
                        <div class="form-group">
                            <label>Section Name</label>
                            <input 
                                value="@if($errors->any()){{old('sn')}}@else{{$sectionInfo->sn}}@endif" 
                                name="sn" 
                                class="form-control @error('sn') is-invalid @enderror"  
                                id="sn" 
                                placeholder="Enter section name">
                            @if($errors->has('sn'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('sn') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Grade Level</label>
                            <select class="form-control @error('gl') is-invalid @enderror" name="gl" id="gl">
                                <option value="" selected>Select Grade Level</option>
                                @foreach (\App\Models\Principal\LoadData::loadGradeLevelByDepartment() as $item)
                                    <option value="{{$item->id}}" {{ old('gl') == $item->id || $sectionInfo->levelid ==  $item->id ? 'selected' : '' }}>{{$item->levelname}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gl'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('gl') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Teacher</label>
                            <select class="form-control" name="t" id="t">
                                
                                @php
                                    $vacantTeachers =  \App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,null,null,$sectionInfo->acadprogid,'vacant')[0]->data;
                                @endphp

                                    <option value="">SELECT TEACHER</option>
                                    @if($sectionInfo->fn != null)

                                        <option value="{{$sectionInfo->tid}}" selected > {{$sectionInfo->fn}} {{$sectionInfo->ln}}</option>

                                    @else

                                        <option value="" selected >Select teacher</option>

                                    @endif

                                    @foreach ($vacantTeachers as $teacher)

                                        @if($teacher->tid ==  $sectionInfo->tid)
                                            <option selected value="{{$teacher->id}}" {{ old('gl') == $item->id || $sectionInfo->teacherid ==  $teacher->id ? 'selected' : '' }}>{{$teacher->firstname}} {{$teacher->lastname}}</option>
                                        @else
                                            <option value="{{$teacher->id}}" {{ old('gl') == $item->id || $sectionInfo->teacherid ==  $teacher->id ? 'selected' : '' }}>{{$teacher->firstname}} {{$teacher->lastname}}</option>

                                        @endif
                                          
                                     

                                    @endforeach

                            </select>
                           
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <select class="form-control" name="r" id="r">
                                @php
                                    $vacantRooms = \App\Models\Principal\Room::getVacantRoom($sectionInfo->roomid);
                                @endphp
                                @if(count($vacantRooms)==0)
                                    <option value="" selected disabled id="rat">No more vacant room</option>
                                @else
                                    <option value="0" selected>Select Room</option>
                                    @foreach ($vacantRooms  as $room)
                                        <option value="{{$room->id}}" {{ old('r') == $room->id || $sectionInfo->roomid ==  $room->id ? 'selected' : '' }}>{{$room->roomname}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Session</label>
                            <select name="sectsession" id="" class="form-control">
                                <option value="">Select Section Session</option>
                                @foreach(DB::table('sectionsession')->where('deleted','0')->get() as $item)
                                    @if($item->id == $sectionInfo->session)
                                        <option value="{{$item->id}}" selected>{{$item->sessionDesc}}</option>
                                    @else
                                        <option value="{{$item->id}}" >{{$item->sessionDesc}}</option>
                                    @endif
                                @endforeach
                              
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                @if($sectionInfo->sundaySchool == 1)
                                    <input type="checkbox" id="nightClass"  name="nightClass" value="1" checked>
                                @else
                                    <input type="checkbox" id="nightClass"  name="nightClass" value="1">
                                @endif
                                <label for="nightClass">Sunday Class
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn btn-outline-success"><i class="far fa-edit mr-1"></i>Update Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-7">
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><span class="text-dark">GRADE LEVEL :  </span><b><u>{{$sectionInfo->lvn}}</u></b>  <span class="text-dark pl-5"> SECTION : </span><b><u>{{$sectionInfo->sn}}</u></b></h4>
            </div>
            <div class="col-sm-5">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/principalPortalSchedule">Sections</a></li>
                <li class="breadcrumb-item active">{{$sectionInfo->lvn}} - {{$sectionInfo->sn}}</li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    @if($status == "Subjects Added")
        <section class="content">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header  bg-primary">
                                <div class="card-tools">
                                    @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)
                                        <div class="btn-group">
                                            @if($sectionInfo->acadprogid == 5 && count($blockassignment)==0)
                                                <small><button class="btn btn-outline-light btn-sm" id="ab"><i class="far fa-edit mr-1"></i>Add Block</button></small>

                                            @elseif($sectionInfo->acadprogid == 5 && count($blockassignment)>0)

                                                <small><button class="btn btn-outline-light btn-sm" id="ab"><i class="far fa-edit mr-1"></i>Change Block</button></small>

                                            @endif
                                            <small><button type="button" class="btn btn-outline-light btn-sm mr-1 ml-2" id="e">
                                                <i class="far fa-edit mr-1"></i>Edit Schedule
                                            </button> </small>
                                        </div>
                                    @endif
                                </div>
                            <h3 class="card-title">Class Schedule 
                            </h3>
                            <div>
                                <i class="fa fa-refresh fa-spin"></i>
                            </div>
                            
                        </div>
                        <div class="card-body table-responsive p-0" id="cs" style="height: 400px;">
                            <div class="options">
                                <div id="message"></div>
                            </div>
                            <table class="table smfont" style="min-width:790px;">
                                <thead class="bg-warning">
                                    <tr>
                                        <th class="p-0" width="1%"></th>
                                        <th class="text-center align-middle" width="17%">Subject</th>
                                        <th class="text-center align-middle" width="21%">Day</th>
                                        <th class="text-center align-middle" width="20%">Time</th>
                                        <th class="text-center align-middle" width="15%" >Room</th>
                                        <th class="text-center align-middle" width="20%">Teacher</th>
                                        <th class="text-center align-middle" width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody class="schedule">
                                    @foreach ($testingSched as $item)
                                    <tr>
                                        @if($sectionInfo->acadprogid == 5)
                                            @if($item->subjinfo->type==1)
                                                <td class="pl-2 text-center text-white align-middle bg-red-50"><b>C</b></td>
                                            @elseif($item->subjinfo->type==2)
                                                <td class="pl-2 text-center text-white align-middle bg-blue-50"><b>SP</b></td>
                                            @else
                                                <td class="pl-2 text-center text-white align-middle bg-green-50"><b>AS</b></td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                        <td class="align-middle tablesub appadd">
                                            {{Str::limit($item->subjinfo->subjdesc,  20, $end='...')}}<br>
                                            {{Str::limit($item->subjinfo->schedclass,  20, $end='...')}}
                                        </td>
                                        <td class="text-center align-middle tablesub">{{$item->daysum}}</td>

                                        @if($item->subjinfo->stime!='00:00')
                                            <td class="text-center align-middle">
                                                {{\Carbon\Carbon::create($item->subjinfo->stime)->isoFormat('hh : mm a')}}
                                                <br>
                                                {{\Carbon\Carbon::create($item->subjinfo->etime)->isoFormat('hh : mm a')}}
                                            </td>
                                        @else
                                            <td class="text-red align-middle">Not Set</td>
                                        @endif
                                        
                                        <td class="text-center align-middle tablesub appadd">{{$item->subjinfo->roomname}}</td>

                                        @if($item->subjinfo->teacherid!=0)
                                            <td class="text-center align-middle appadd">
                                                {{$item->subjinfo->lastname}}, 
                                                {{explode(' ',trim($item->subjinfo->firstname))[0]}}
                                            </td>
                                        @else
                                            <td class="text-red align-middle text-center">
                                                No Assigned Teacher
                                            </td>
                                        @endif
                                        <td class="p-0 align-middle align-middle">
                                            @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)
                                                @if($sectionInfo->acadprogid == 5)
                                                    @if($item->subjinfo->type != 2)
                                                        <a type="button" href="/removeshsched/{{Crypt::encrypt($item->subjinfo->detailid)}}" class="text-danger btn p-0 del"><i class="fa fa-trash-alt"></i></a>
                                                    @endif
                                                @else
                                                    <a type="button" href="/removesched/{{Crypt::encrypt($item->subjinfo->detailid)}}" class="text-danger btn p-0 del"><i class="fa fa-trash-alt"></i></a>

                                                @endif
                                            @endif
                                        </td>
                                    </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer p-0">
                            <div class="sc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 card h-100">
                                <div class="card-header border-0  bg-info">
                                    <h3 class="card-title ">
                                        <i class="fas fa-users mr-1"></i>
                                        Students
                                    </h3>
                                </div>
                                <div class="card-body table-responsive p-0 teachers_attendance" style="height: 300px;">
                                    @if(count($enrolledstud)>0)
                                        <table class="table table-head-fixed">
                                            @foreach ($enrolledstud[0]->data as $studinfo)
                                                <tr>
                                                    <td class="small"> <a href="/principalPortalStudentProfile/{{Crypt::encrypt($studinfo->id)}}/{{Crypt::encrypt($sectionInfo->acadprogid)}}">{{strtoupper($studinfo->firstname)}}, {{strtoupper($studinfo->lastname)}}</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        <p class="p-2 text-center">No Students Enrolled</p>
                                    @endif
                                </div>
                                
                                <div class="card-footer">
                                    <span class="float-right">Number of Students: <span class="h4 text-success">{{$enrolledstud[0]->count}}</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card  h-100">
                                <div class="card-header  bg-info">
                                    <h3 class="card-title ">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Grades Status
                                    </h3>
                                </div>
                                <div class="card-body table-responsive p-0" style="height: 354px;">
                                    <table class="mb-0 table table-bordered  report-card-table smfont table-head-fixed">
                                        <thead class="bg-warning">
                                            <tr>
                                                <td rowspan="2" width="25%" class="text-center align-middle h6">SUBJECTS</td>
                                                <td align="center"  colspan="4" width="75%" class="text-center align-middle h6 p-1">PERIODIC RATINGS</td>
                                            </tr>
                                            <tr align="center">
                                                <td width="15%" class="text-center align-middle h6 p-1">1</td>
                                                <td width="15%" class="text-center align-middle h6 p-1">2</td>
                                                <td width="15%" class="text-center align-middle h6 p-1">3</td>
                                                <td width="15%" class="text-center align-middle h6 p-1">4</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classassignsubj as $key=>$subject)
                                            <tr id="{{$key}}">
                                                <td class="pt-2 pb-2">{{$subject->subjcode}}</td>
                                                @for($x = 1 ; $x <=4; $x++)
                                                    @php
                                                        $availablegrade = 0;
                                                    @endphp
                                                    @if(auth()->user()->type == 2)
                                                        @foreach($subject->gradestatus as $gstatus)

                                                            @if(($gstatus->status=='0' || $gstatus->status=='1')  && $gstatus->quarter == $x && $gstatus->submitted == 1)
                                                                <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0 "><div class=" badge badge-success w-100">Submitted</div></a></td>
                                                                @php
                                                                    $availablegrade+=1;  
                                                                    break;   
                                                                @endphp
                                                            @elseif($gstatus->status=='3' && $gstatus->quarter == $x)
                                                                <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0"><div class=" badge badge-warning w-100">Pending</div></a></td>
                                                                @php
                                                                    $availablegrade+=1;  
                                                                    break;   
                                                                @endphp
                                                            @elseif($gstatus->status=='4' && $gstatus->quarter == $x)
                                                                <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0"><div class=" badge badge-info w-100">Posted</div></a></td>
                                                                @php
                                                                    $availablegrade+=1;  
                                                                    break;   
                                                                @endphp
                                                            @elseif($gstatus->status=='2' && $gstatus->quarter == $x)
                                                                <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0"><div class=" badge badge-primary w-100">Approved</div></a</td>
                                                                @php
                                                                    $availablegrade+=1;  
                                                                    break;   
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        @if($availablegrade==0)
                                                            <td></td>
                                                        @endif
                                                    @else
                                                        <td></td>
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
                </div>
            <div class="col-md-3">
                <div class="card card-primary h-100">
                    <div class="card-header  bg-success">
                      <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>About Section</h3>
                      <br>
                    </div>
                    <div class="card-body">
                      
                        <strong><i class="fas fa-signature mr-1"></i>Section Name</strong>
                            <p class="small" style="color: #af8402">
                                {{$sectionInfo->sn}}
                            </p>
                        <hr>
                        <strong><i class="fas fa-layer-group mr-1"></i>Grade Level</strong>
                            <p class="small" style="color: #af8402">
                                {{$sectionInfo->lvn}}
                            </p>
                        @if($sectionInfo->acadprogid == 5)
                            <hr>
                            <strong><i class="fas fa-book mr-1"></i>Block</strong>
                            <p class="small" style="color: #af8402">
                                {{$sectionInfo->blockname}}
                            </p>
                        @endif
                            <hr>
                            <strong><i class="fas fa-user mr-1"></i>Class Adviser</strong>
                            <p class="small" style="color: #af8402">
                        
                            @if($sectionInfo->fn!=null)
                                {{$sectionInfo->fn}} {{$sectionInfo->ln}}
                            @else
                                No Teacher Assigned
                            @endif
                            
                        </p>
                        <hr>
                        <strong><i class="fas fa-chair mr-1"></i>Room</strong>
                        <p class="small" style="color: #af8402">
                            
                            @if($sectionInfo->rn!=null)
                                {{$sectionInfo->rn}}
                            @else
                                No Room Assigned
                            @endif
                            
                        </p>
                        <hr>
                        <strong><i class="fas fa-users mr-1"></i>Enrolled Students</strong>
                        <p class="small" style="color: #af8402">
                            {{$enrolledstud[0]->count}}
                        </p>
                        <hr>
                    <strong><i class="fas fa-pen-square mr-1"></i>Created</strong>
                        <p class="small" style="color: #af8402">
                            
                            By: <span class="float-right">{{$sectionInfo->cbname}}</span><br>
                            Date: <span class="float-right">
                                @if($sectionInfo->createddatetime!='0000-00-00 00:00:00'){{\Carbon\Carbon::create($sectionInfo->createddatetime)->isoFormat('MMM DD, YYYY - hh:mm a')}}@endif
                            </span>
                            
                        </p>
                        <hr>
                        <strong><i class="fas fa-plus-square mr-1"></i></i>Updated</strong>
                        
                        <p class="small" style="color: #af8402">
                            
                            By: <span class="float-right">@if($sectionInfo->ubname!=null){{$sectionInfo->ubname}}@endif</span><br>
                            Date: <span class="float-right">@if($sectionInfo->editeddatetime!='0000-00-00 00:00:00'){{\Carbon\Carbon::create($sectionInfo->editeddatetime)->isoFormat('MMM DD, YYYY - hh:mm a')}}@endif</span>
                            
                        </p>
                        @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)
                            <hr>
                            <span><button type="button" class="btn btn-sm btn-outline-primary btn-block" id="us" data-toggle="modal" data-target="#modal-section"><i class="far fa-edit mr-1"></i>Edit Section</button></span>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        {{-- <div class="row mt-3">
            <div class="col-md-3">
                <div class="card">
                   <div class="card-header">
                        Quarter 1 Ranking
                   </div>
                   <div class="card-body">

                   </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                   <div class="card-header">
                        Quarter 2 Ranking
                   </div>
                   <div class="card-body">

                   </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                   <div class="card-header">
                        Quarter 3 Ranking
                   </div>
                   <div class="card-body">

                   </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                   <div class="card-header">
                        Quarter 4 Ranking
                   </div>
                   <div class="card-body">

                   </div>
                </div>
            </div>
        </div> --}}
    <section>
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
@if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)
    @section('footerjavascript')
    

        <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

        <script>
            $(document).on('click','#ab',function(){
                $('.options').empty();
                $('#e').prop('disabled',false);

                $(function () {
                    //Initialize Select2 Elements
                    $('.select2').select2()
                
                    //Initialize Select2 Elements
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    })
                });
                
                var st = '<table class="table mb-0">'+
                            '<tr >'+
                                '<td  class="p-1 pb-0"  width="25%">'+
                                    '<div class="form-group mb-0 ">'+
                                        '<select  multiple="multiple" class="form-control select2 rounded-0 block"  style="width: 100%;">';
                                
                @foreach(DB::table('sh_block')->where('deleted','0')->get() as $item)

                    @php
                        $select = false;
                    @endphp

                    @foreach($blockassignment as $assblockitem)

                            @if($assblockitem->blockid ==  $item->id)
                                @php
                                    $select = true;
                                @endphp
                            @endif

                    @endforeach

                    @if($select)
                        st+='<option selected value="'+'{{$item->id}}'+'">'+'{{$item->blockname}}'+'</option>'
                    @else
                        st+='<option value="'+'{{$item->id}}'+'">'+'{{$item->blockname}}'+'</option>'
                    @endif

                   

                @endforeach

                st+='</select></div></td></tr></table>'

                @if($sectionInfo->blockid!=null)
                    $('.options').prepend('<button class="btn btn-sm btn-success m-1 abb float-right">Update</button><button class="btn btn-sm btn-danger m-1 float-right cc">Cancel</button>')
                @else
                    $('.options').prepend('<button class="btn btn-sm btn-primary m-1 abb float-right">Save</button><button class="btn btn-sm btn-danger m-1 float-right cc">Cancel</button>')
                @endif
                $('.options').prepend(st)
                // $('.options').addClass('mt-5')

                @if($sectionInfo->blockid!=null)
                    $.ajax({
                        type:'GET',
                        url:'/prinicipalblockinfoby',
                        data:{
                            blockid:'{{$sectionInfo->blockid}}',
                            },
                        success:function(data) {
                            
                        }
                    })
                    console.log($('.block').val('{{$sectionInfo->blockid}}').change());
                @endif

            })

            $(document).on('click','.abb',function(){

                $.ajax({
                    type:'GET',
                    url:'/prinicipaladdblocktoshsection',
                    data:{
                        section:'{{$sectionInfo->id}}',
                        b:$('.block').val()
                        },
                    success:function(data) {
                        location.reload()
                    }
                })
            })



            $(document).on('click','#e',function(){


                $('#e').attr('disabled', 'disabled');
                $('.options').empty();

                $(function () {
                    //Initialize Select2 Elements
                    $('.select2').select2()
                
                    //Initialize Select2 Elements
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    })
                
                    //Date range picker
                    $('#reservation').daterangepicker()
                    //Date range picker with time picker
                    $('#reservationtime').daterangepicker({
                        timePicker: true,
                        startDate: '07:30 AM',
                        endDate: '05:30 PM',
                        timePickerIncrement: 5,
                        locale: {
                            format: 'hh:mm A',
                            cancelLabel: 'Clear'

                        }
                    })
                })

                var st = '<table class="table mb-0"><tbody>'+
                            '<tr >'+
                                '<td  class="p-1 pb-0" width="25%">'+
                                    '<div class="form-group">'+
                                        '<input type="text" class="form-control float-right secttime" id="reservationtime">'+
                                        '</div>'+
                                '</td>'+
                                '<td  class="p-1 pb-0"  width="25%">'+
                                    '<div class="form-group mb-0 ">'+
                                        '<select class="form-control select2 rounded-0 sectsub" style="width: 100%;">'+
                                            '<option selected="selected" value="0">Select Subject</option>';
                
                @if($sectionInfo->acadprogid == 5)                    
                    @foreach(\App\Models\Principal\SPP_Subject::getAllSubject(null,null,null,null,Crypt::encrypt($sectionInfo->acadprogid),[1,3])[0]->data as $item)
                        st+='<option value="'+'{{$item->id}}'+'">'+'{{Str::limit($item->subjtitle,10, $end='...').' ('.$item->subjcode.')'}}'+'</option>'
                    @endforeach
                @else
                    @foreach(\App\Models\Principal\SPP_Subject::getAllSubject(null,null,null,null,Crypt::encrypt($sectionInfo->acadprogid))[0]->data as $item)
                        st+='<option value="'+'{{$item->id}}'+'">'+'{{Str::limit($item->subjdesc,10, $end='...')}}'+'</option>'
                    @endforeach
                @endif

                st+='</select></div></td><td class="p-1 pb-0"  width="25%"><div class="form-group mb-0 "><select class="form-control select2 rounded-0 secttea" style="width: 100%;"><option selected="selected"  value="">Select Teacher</option>';


                @foreach(\App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,null,null,$sectionInfo->acadprogid)[0]->data as $item)
                    st+='<option value="'+'{{$item->id}}'+'">'+'{{strtoupper($item->lastname)}}'+' '+'{{strtoupper(substr($item->firstname,0,1))}}.'+'</option>';
                @endforeach 

                st+='</select></div></td><td class="p-1 pb-0"  width="25%"><div class="form-group mb-0 "><select class="form-control select2 rounded-0 sectroo" style="width: 100%;"><option selected="selected"  value="0">Select Room</option>';
                
                @foreach(\App\Models\Principal\Room::getAllRoom() as $item)
                    st+='<option value="'+'{{$item->id}}'+'">'+'{{$item->roomname}}'+'</option>'
                @endforeach
                    st+=' </select></div></td></tr></tbody></table>'

                $('.sc').empty()

                $('.act').each(function(){
                    
                    @if($sectionInfo->acadprogid == 5)
                        $(this).append('<button class="text-danger btn p-0 del"><i class="fa fa-trash"></i></button>')
                        $(this).append('')
                    @else
                        $(this).append('<button class="text-danger btn p-0 del"><i class="fa fa-trash"></i></button>')
                        $(this).append('')
                    @endif
                    

                })
            

                $('.options').prepend('<button class="btn btn-sm btn-outline-primary m-2 is float-right"><i class="fas fa-save mr-1"></i>Insert Schedule</button><button class="btn btn-sm btn-outline-danger m-2 float-right cc">Cancel</button>')
                $('.options').prepend(st)
                // $('.options').addClass('mt-5')
            
            })  

            $(document).on('click','#as',function(){

                $('#modal-primary').modal('show')

            })

            $(document).on('click','.eval',function(){



                // var data = [];
                var days = [];

                $('.day').each(function(){
                    if($(this).is(":checked")){
                        days.push($(this).val())
                    }
                })

                @if($sectionInfo->acadprogid == 5)   
                    $.ajax({
                        type:'GET',
                        url:'/principalstoreshclassschedule',
                        data:{
                            section:'{{$sectionInfo->id}}',
                            t:$('.secttime').val(),
                            s:$('.sectsub').val(),
                            tea:$('.secttea').val(),
                            r:$('.sectroo').val(),
                            days:days,
                            class:$('#classification').val()
                            },
                        success:function(data) {

                            if(data[0].storestatus){
                                location.reload()
                            }
                            else{
                                $('.retun-message').empty();
                                $('.retun-message').append(data[0].schedinfo);
                            }

                            location.reload()
                            
                        }
                    })
                @else  
                    $.ajax({
                        type:'GET',
                        url:'/evaluateSchedule',
                        data:{
                            section:'{{$sectionInfo->id}}',
                            t:$('.secttime').val(),
                            s:$('.sectsub').val(),
                            tea:$('.secttea').val(),
                            r:$('.sectroo').val(),
                            class:$('#classification').val(),
                            days:days
                        },
                        success:function(data) {
                            location.reload()
                        }
                    })
                @endif

            })




            $(document).on('click','#cc , .cc',function(){

                $('.period.new').remove();
                $('#e').removeAttr('disabled')
                $('.options').empty();
                $('.sc').empty();
                $('.act').empty();
                // $('.options').removeClass('mt-5')

            })

            $(document).on('click','.is', function(){

                console.log('hello');

                $('.error').remove();

                var tdid = this.id-1;

                var validSTring = true;

                var validateString = '<ul class="mb-0 text-danger smfont error">'

                if($('.sectsub').val() == 0){
                    validateString+='<li>No Subject Selected</li>'
                    validSTring = false;
                }
                // if($('.secttea').val() == 0){
                //     validateString+='<li>No Teacher Selected</li>'
                //     validSTring = false;
                // }
                if($('.sectroo').val() == 0){
                    validateString+='<li>No Room Selected</li>'
                    validSTring = false;
                }

                // $('td.tablesub').each(function(index,value){
                //     if($(this)[0].id == $('.sectsub').val() && value.closest('tr').id != tdid){

                //         validateString+='<li>Subject Already Exist</li>'
                //         validSTring = false;
                //     }
                //     if(value.closest('tr').id == tdid){
                //         trinfo = value.closest('tr');
                //     }
                // })

                validateString += '</ul>'

                if(validSTring){

                    $('#modal-primary').modal('show')

                    $('.scheduleInfo').empty();

                    $('.scheduleInfo').append(
                        '<table class="table table-borderless">'+
                            '<tr><th>Time:</th><td>'+$('.secttime').val()+'</td></tr>'+
                            '<tr><th>Subject:</th><td>'+$('.sectsub option:selected').text()+'</td></tr>'+
                            '<tr><th>Teacher:</th><td>'+$('.secttea option:selected').text()+'</td></tr>'+
                            '<tr><th>Room:</th><td>'+$('.sectroo option:selected').text()+'</td></tr>'+
                        '</table>'
                    )

                }else{

                    $('.error').remove();
                    $('.options').prepend(validateString);
                }
            
            })

            $(document).on('click','.ee',function(){
            
                if($(this).closest('tr')[0].childNodes[0].innerHTML!="Time Not Set"){
                    var time = $(this).closest('tr')[0].children[2].innerHTML.split('<br>');
                }

                else{
                    var time =['00:00 AM', '00:00 AM']
                }
                
                $(function () {
            
                    $('.select2').select2()
                
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    })
                    $('#reservation').daterangepicker()
                    $('#reservationtime').daterangepicker({
                        timePicker: true,
                        startDate: time[0],
                        endDate: time[1],
                        timePickerIncrement: 5,
                        locale: {
                            format: 'hh:mm A',
                            cancelLabel: 'Clear'

                        }
                    })
                })

                $('#temp').val($(this).closest('tr')[0].children[0].id);

                $('.secttime').val($(this).closest('tr')[0].children[0].innerHTML).change();
                $('.sectsub').val($(this).closest('tr')[0].children[0].id).change();
                $('.secttea').val($(this).closest('tr')[0].children[4].id).change();
                $('.sectroo').val($(this).closest('tr')[0].children[3].id).change();

                $('.is').empty()
                $('.is').append('<i class="fas fa-save mr-1"></i>Update Schedule')
                $('.is').removeClass('btn-outline-primary')
                $('.is').addClass('btn-outline-success')
                $('.is').attr('id',$(this).closest('tr')[0].id)
                $('.is').addClass('us')
                $('.us').attr('id',$(this).closest('tr')[0].id)
                $('.is').removeClass('is')
            
                $('.sectsub').val()
            
            })

            $(document).on('click','.re',function(){

            $(this).closest('tr').remove();

            })

            $(document).on('click','.us',function(){

                $('.error').remove();
                $('.scheduleInfo').empty();

                var tdid = this.id;
                var validSTring = true;

                var validateString = '<ul class="mb-0 text-danger smfont error">'

                if($('.sectsub').val() == 0){
                    validateString+='<li>No Subject Selected</li>'
                    validSTring = false;
                }
                // if($('.secttea').val() == 0){
                //     validateString+='<li>No Teacher Selected</li>'
                //     validSTring = false;
                // }
                if($('.sectroo').val() == 0){
                    validateString+='<li>No Room Selected</li>'
                    validSTring = false;
                }

                var trinfo = null;

                // $('td.tablesub').each(function(index,value){

                
                //     if($(this)[0].id == $('.sectsub').val() && value.closest('tr').id != tdid){

                //         validateString+='<li>Subject Already Exist</li>'
                //         validSTring = false;

                //     }

                //     if(value.closest('tr').id == tdid){

                //         trinfo = value.closest('tr');

                        
                //     }
                
                // })

                validateString += '</ul>'

                if(validSTring){

                    $('#modal-primary').modal('show')

                    $('.scheduleInfo').append(
                        '<table class="table table-borderless">'+
                            '<tr><th>Time:</th><td>'+$('.secttime').val()+'</td></tr>'+
                            '<tr><th>Subject:</th><td>'+$('.sectsub option:selected').text()+'</td></tr>'+
                            '<tr><th>Teacher:</th><td>'+$('.secttea option:selected').text()+'</td></tr>'+
                            '<tr><th>Room:</th><td>'+$('.sectroo option:selected').text()+'</td></tr>'+
                        '</table>'
                    )

                    var scheddays = trinfo.children[1].innerHTML.split(' / ');

                    console.log(scheddays);

                    $('.day').each(function(index, dayvalue){

                        $(this).prop('checked',false)

                        var sameDay = false

                        scheddays.forEach(function(index, item){

                            if(dayvalue.id == index){

                                sameDay = true

                            }

                        })

                        if(sameDay){

                            $(this).prop('checked',true)

                        }

                    })

                    $('.eval').text('Update');
                    $('.eval').removeClass('btn-primary');
                    $('.eval').addClass('btn-success');
                    $('.eval').addClass('update');
                    $('.update').removeClass('eval');
                    
                }
                else{
                    $('.error').remove();
                    $('.options').prepend(validateString);
                }

            })

            $(document).on('click','.update',function(){

                var days = [];

                $('.day').each(function(){
                    if($(this).is(":checked")){
                        days.push($(this).val())
                    }
                })

                @if($sectionInfo->acadprogid == 5)
                
                    $.ajax({
                        type:'GET',
                        url:'/principalupdateshclassschedule',
                        data:{
                            section:'{{$sectionInfo->id}}',
                            'sub':$('.sectsub').val(),
                            'tea':$('.secttea').val(),
                            'roo':$('.sectroo').val(),
                            'tim':$('.secttime').val(),
                            'csid':$('.us')[0].id,
                            'days':days
                            },
                        success:function(data) {
                            location.reload()
                        }
                    })
                @else
                    $.ajax({
                        type:'GET',
                        url:'/principalupdateshclassschedulejhs',
                        data:{
                            section:'{{$sectionInfo->id}}',
                            'sub':$('.sectsub').val(),
                            'tea':$('.secttea').val(),
                            'roo':$('.sectroo').val(),
                            'tim':$('.secttime').val(),
                            'csid':$('.us')[0].id,
                            'days':days,
                            'temp':$('#temp').val()
                            },
                        success:function(data) {
                            location.reload()
                        }
                    })
                @endif

            })


        
        </script>

        <script>
            $(document).on('click','.days',function(){
                @if($sectionInfo->acadprogid == 5)     
                    $.ajax({
                        type:'GET',
                        url:'/principalsearchshschedulebyday',
                        data:{section:'{{$sectionInfo->id}}',days:$(this).attr('id')},
                        success:function(data) {
                                $('.schedule').empty()
                                $('.schedule').append(data)
                            }
                    })
                @else
                    $.ajax({
                        type:'GET',
                        url:'/searchschedulebyday',
                        data:{section:'{{$sectionInfo->id}}',days:$(this).attr('id')},
                        success:function(data) {
                                $('.schedule').empty()
                                $('.schedule').append(data)
                            }
                    })
                @endif
            $('.options').empty()
            $('.sc').empty()
            $('#e').removeAttr('disabled');
            $('.day-select-text').empty();
            $('.day-select-text').append($(this).text());
        
            })
        </script>

        <script>
            
            $(document).ready(function(){
                @if ($errors->any())
                    $('#modal-section').modal('show');
                @endif



                $(document).on('change','#gl',function(){
                    $.ajax({
                        type:'GET',
                        url:'/principalGetTeacher',
                        data:{
                            data:$(this).val(),
                        },
                        success:function(data) {
                            $('#t').empty();
                            $('#t').append('<option value="">Select Teacher</option>')
                            $.each(data[0].data,function(key,value){
                                $('#t').append('<option value='+value.id+'>'+value.lastname+', '+value.firstname+'</option>')
                            })

                            @if($sectionInfo->fn != null)

                                $('#t').append('<option value='+'{{strtoupper($sectionInfo->tid)}}'+'>'+'{{strtoupper($sectionInfo->fn)}}'+' '+'{{strtoupper(substr($sectionInfo->ln,0,1))}}.'+'</option>')

                            @endif
                            

                        }
                    })
                })




            });

           
        </script>
            


    @endsection
@endif
