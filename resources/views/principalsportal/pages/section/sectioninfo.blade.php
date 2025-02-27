@php
    $refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

    if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else{
        if( $refid->refid == 20){
            $extend = 'principalassistant.layouts.app2';
        }elseif( $refid->refid == 22){
            $extend = 'principalcoor.layouts.app2';
        }else if($refid->refid == 27){
            $extend = 'academiccoor.layouts.app2';
        }
    }
@endphp

@extends($extend)

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <style>
        .smfont{
            font-size:14px;
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

        .isHPS {
            position: sticky;
            top: 27px !important;
            background-color: #fff;
            outline: 2px solid #dee2e6 ;
            outline-offset: -1px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
        }
       
    </style>

   

  
@endsection

@section('modalSection')

@php
    $acadprogid = $sectionInfo->acadprogid;
    $gradelevel = [];
    $vacantTeachers = \App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,null,null,$acadprogid)[0]->data;
    $session = DB::table('sectionsession')->where('deleted','0')->get();
    $rooms = DB::table('rooms')->where('deleted','0')->get();
    $schedtimetemplate = DB::table('schedtimetemplate')->where('deleted',0)->select('id','description as text')->get();
@endphp


<div class="modal fade" id="modal_schedule" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title">Schedule Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Subject</label>
                        <select data-placeholder="Select a Subject"  name="input_subject" id="input_subject" class="form-control select2"></select>
                        {{-- <input class="form-control form-control-sm" readonly id="subject_desc"> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Subject Teacher</label>
                        <select data-placeholder="Select a teacher"  name="secttea" id="secttea" class="form-control select2"   >
                                <option value="">Select Teacher</option>
                                @foreach(\App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,null,null,$sectionInfo->acadprogid)[0]->data as $item)
                                    <option value="{{$item->id}}">{{$item->tid}} - {{$item->lastname.', '.$item->firstname}}</option>
                                @endforeach 
                        </select>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="">Time</label>
                        <input type="text" class="form-control  form-control-sm reservationtime" name="time" id="time">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group  col-md-12">
                        <label>Room</label>
                        <select data-placeholder="Select a Room" class="form-control select2 @error('r') is-invalid @enderror"  name="sectroo" id="sectroo" style="width: 100%;">
                                @php
                                    $vacantRooms = App\Models\Principal\SPP_Rooms::getRooms(null,null,null,null);
                                @endphp
                                <option value="" selected>Select Room</option>
                                @foreach ($vacantRooms[0]->data  as $room)
                                    <option value="{{$room->id}}" {{$room->id == $sectionInfo->roomid? 'selected':''}}>{{$room->roomname}}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="">Schedule Classification  <a href="javascript:void(0)" class="edit_schedclass pl-2" hidden><i class="far fa-edit"></i></a>
                            <a href="javascript:void(0)" class="delete_schedclass pl-2" hidden><i class="far fa-trash-alt text-danger"></i></a></label>
                        <select name="classification" id="classification" class="form-control select2">
                            @foreach(DB::table('schedclassification')->where('deleted','0')->get() as $item)
                                <option value="{{$item->id}}">{{$item->description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label>Day</label>
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Mon" class="day" value="1" >
                        <label for="Mon">Mon</label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                            <input type="checkbox" id="Tue" class="day" value="2" >
                            <label for="Tue">Tue</label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                            <input type="checkbox" id="Wed" class="day" value="3" >
                            <label for="Wed">Wed</label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                            <input type="checkbox" id="Thu" class="day" value="4" >
                            <label for="Thu">Thu</label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                            <input type="checkbox" id="Fri" class="day" value="5" >
                            <label for="Fri">Fri</label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Sat" class="day" value="6" >
                        <label for="Sat">Sat</label>
                    </div>
                    <div class="retun-message mt-1">
                    </div>
                </div>
                <div class="row" id="apptocon_holder" hidden>
                    <div class="col-md-12">
                        <hr class="mb-2 mt-2">
                    </div>
                    <div class="col-md-12">
                        <div class="icheck-primary d-inline mr-3">
                            <input type="checkbox" id="apptocon" >
                            <label for="apptocon">Apply to all components.</label>
                        </div>
                    </div>
                </div>
                <div id="conflict_holder" hidden>
                    <hr class="mb-2">
                    <a href="#" id="view_conflict">Conflict Information</a>
                    <hr class="mt-2">
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm eval">Create Schedule</button>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-danger btn-sm remove_sched" hidden>Remove Schedule</button>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div>


<div class="modal fade" id="conflict_info_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Conflict Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
          </div>
            <div class="modal-body pt-0" id="conflict_holder_detail">
                <div class="row">
                    <div class="col-md-12 rc_holder">
                        <label for="">Room Conflict:</label>
                        <div class="row" id="room_conflict" >
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 sc_holder">
                        <label for="">Section Conflict:</label>
                        <div class="row" id="section_conflict" >
                        </div>
                    </div>
                    
                    <div class="col-md-12 mt-3 tc_holder">
                        <label for="">Teacher Conflict:</label>
                        <div class="row" id="teacher_conflict">
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="schedclass_form_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Classification Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
               <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Schedule Classification Description</label>
                        <input class="form-control form-control-sm" id="schedclass_desc">
                    </div>
               </div>
               <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary" id="create_schedclass_button">Create Schedule Classification</button>
                        <button class="btn btn-success btn-primary" id="update_schedclass_button" hidden>Update Schedule Classification</button>
                    </div>
                   
               </div>
            </div>
        </div>
    </div>
</div>

@php
    $temp_blocks = DB::table('sh_strand')
                ->where('deleted',0)
                ->where('active',1)
                ->select(
                    'id',
                    'strandcode as text'
                )
                ->get()
@endphp

<div class="modal fade" id="timetemp_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title">Time Template List</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="">Time Template <a href="javascript:void(0)" class="edit_timetemp pl-2" hidden><i class="far fa-edit"></i></a>
                    <a href="javascript:void(0)" class="delete_timetemp pl-2" hidden><i class="far fa-trash-alt text-danger"></i></a></label>
                    <select name="" id="input_timetemplate" class="form-control select2">
                        @foreach ($schedtimetemplate as $item)
                            <option value="{{$item->id}}">{{$item->text}}</option>
                        @endforeach
                    </select>
                </div>
            </div>   
            <div class="row">
              <div class="col-md-12" style="font-size:.8rem !important">
                <table class="table table-sm table-striped table-bordered table-hovered table-hover " id="timetempdetail_datatable">
                  <thead>
                    <tr>
                      <th width="40%">Start Time</th>
                      <th width="40%">End Time</th>
                      <th width="10%"></th>
                      <th width="10%"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>  
            </div> 
          </div>
        </div>
    </div>
  </div>
  
  <div class="modal fade" id="timetemp_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Time Template Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
               <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Time Template Description</label>
                        <input class="form-control form-control-sm" id="timetemp_desc">
                    </div>
               </div>
               <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary" id="create_timetemp_button" style="font-size:.8rem !important">Create Time</button>
                        <button class="btn btn-success btn-primary" id="update_timetemp_button" hidden style="font-size:.8rem !important">Update Time</button>
                    </div>
               </div>
            </div>
        </div>
    </div>
  </div>
  
  <div class="modal fade" id="timetempdeatil_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Time Detail Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
               <div class="row">
                  <div class="form-group col-md-12">
                    <label for="">Time</label>
                    <input type="text" class="form-control  form-control-sm" name="timetempdetail_input" id="timetempdetail_input">
                  </div>
               </div>
               <div class="row">
                    <div class="col-md-12" >
                        <button class="btn btn-sm btn-primary" id="create_timetempdetail_button" style="font-size:.8rem !important">Create Time Template</button>
                        <button class="btn btn-success btn-primary" id="update_timetempdetail_button" hidden style="font-size:.8rem !important">Update Time Tempate</button>
                    </div>
               </div>
            </div>
        </div>
    </div>
  </div>
  
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
        <div class="row  mb-2">
            <div class="col-sm-6">
                <h1>Section Information</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/setup/sections">Sections</a></li>
                <li class="breadcrumb-item active">{{$sectionInfo->levelname}} - {{$sectionInfo->sectionname}}</li>
            </ol>
            </div>
        </div>
        </div>
    </section>
        @php
            $schoolyear = DB::table('sy')->select('sydesc','id','isactive')->orderBy('sydesc')->get();
            $semester = DB::table('semester')->where('id','!=',3)->get();
        @endphp
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="info-box shadow-lg">
                        <div class="info-box-content">
                            <div class="row">
                                <div class="col-md-2 ">
                                    <label for="">School Year</label>
                                    <select class="form-control form-control-sm select2" id="filter_schoolyear">
                                        @foreach ($schoolyear as $item)
                                            <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected="selected"':''}}>{{$item->sydesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 filter_semester_holder " hidden>
                                    <label for="">Semester</label>
                                    <select class="form-control form-control-sm select2" id="filter_semester">
                                        @foreach ($semester as $item)
                                            <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected="selected"':''}}>{{$item->semester}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 filter_semester_holder"  hidden>
                                    <label for="">Strand</label>
                                    <select class="form-control form-control-sm select2" id="filter_strand"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card shadow">
                        <div class="card-header  bg-primary">
                            <div class="row">
                                <div class="col-md-5">
                                    <h3 class="card-title mt-1"><i class="fas fa-clipboard-list"></i> Class Schedule</h3>
                                </div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-3">
                                    <select class="form-control form-control-sm select2"  id="filter_format">
                                        <option value="1">Format 1</option>
                                        <option value="2">Format 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1" >
                            <div class="row" id="sched_format1">
                                <div class="col-md-12 table-responsive" id="cs" style="height: 400px;">
                                    <table class="table table-bordered smfont table-head-fixed" >
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle bg-warning" width="40%">Subject</th>
                                                <th class="text-center align-middle bg-warning" width="20%">Day</th>
                                                <th class="text-center align-middle bg-warning" width="12%">Time</th>
                                                <th class="text-center align-middle bg-warning" width="13%" >Room</th>
                                                <th class="text-center align-middle bg-warning" width="15%">Teacher</th>
                                                <th class="text-center align-middle bg-warning" width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="schedule" id="scheduletable">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row"  id="sched_format2" hidden>
                                <div class="col-md-12">
                                    <div class="row mt-2">
                                        <div class="col-md-5">
                                              <button class="btn btn-sm btn-primary add_sched" data-type="regular">Add Schedule</button>
                                              <button hidden class="btn btn-sm btn-primary add_sched_specsubj" data-type="special">Special Subject</button>
                                        </div>
                                        {{-- <div class="col-md-3"></div> --}}
                                        <div class="col-md-3">
                                              <button class="btn btn-sm btn-primary btn-block" id="time_templatelist">Time Template List</button>
                                        </div>
                                        <div class="col-md-4">
                                              <select class="form-control form-control-sm teacher select2"  id="filter_timetemplate">
                                              </select>
                                        </div>
                                  </div>
                                  <div class="row mt-3">
                                    <div class="col-md-12">
                                      <p class="mb-0 text-sm"><i>Click the subject name to edit or delete schedule.</i></p>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12 table-responsive tableFixHead" style="height: 323px;">
                                        <table class="table-sm table-bordered table table-head-fixed mb-0" id="sched_holder"  style="font-size:.7rem !important">
                                          
                                        </table>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-0">
                            <div class="sc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 card h-100 shadow">
                                <div class="card-header border-0  bg-info">
                                    <h3 class="card-title ">
                                        <i class="fas fa-users mr-1"></i>
                                        Students
                                    </h3>
                                </div>
                                <div class="card-body table-responsive p-0 teachers_attendance" style="height: 300px;">
                                    <table class="table table-head-fixed" id="student_holder">
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <span class="float-right">Number of Students: <span class="h4 text-success" id="student_count"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card  h-100 shadow">
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
                                                <th rowspan="2" width="25%" class="text-center align-middle h6 bg-warning">SUBJECTS</th>
                                                <th  colspan="4" width="75%" class="text-center align-middle h6 p-1 bg-warning">PERIODIC RATINGS</th>
                                            </tr>
                                            <tr class="text-center">
                                                <th width="15%" class="text-center align-middle h6 p-1 isHPS bg-warning">1</th>
                                                <th width="15%" class="text-center align-middle h6 p-1 isHPS bg-warning">2</th>
                                                <th width="15%" class="text-center align-middle h6 p-1 isHPS bg-warning">3</th>
                                                <th width="15%" class="text-center align-middle h6 p-1 isHPS bg-warning">4</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sectionsubjects">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-md-3">
                <div class="card card-primary h-100 shadow">
                    <div class="card-header  bg-success">
                      <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>About Section</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-signature mr-1"></i>Section Name</strong>
                            <p class="small pl-4 mb-0" style="color: #af8402" id="label_sectionname">{{$sectionInfo->sectionname}}</p>
                            @if($sectionInfo->session == 1)
                                <p class="small pl-4 mb-0" ><span class="badge badge-secondary">Morning Sessio</span></p>
                            @elseif($sectionInfo->session == 2)
                                <p class="small pl-4 mb-0" > <span class="badge badge-secondary">Afternoon Session</span></p>
                            @elseif($sectionInfo->session == 3)
                                <p class="small pl-4 mb-0" ><span class="badge badge-secondary">Night Session</span></p>
                            @else 
                                <p class="small pl-4 mb-0" ><span class="badge badge-secondary">Whole Day</span></p>
                            @endif
                            @if($sectionInfo->sd_issp)
                                <p class="small pl-4" >
                                    <span class="badge badge-primary">Special Section</span>
                                </p>
                            @elseif($sectionInfo->sd_ismwsp)
                                <p class="small pl-4" >
                                    <span class="badge badge-primary">MWSP</span>
                                </p>
                            @endif
                        <hr>
                        <strong><i class="fas fa-layer-group mr-1"></i>Grade Level</strong>
                            <p class="small pl-4" style="color: #af8402"  id="label_levelname">{{$sectionInfo->levelname}}</p>
                        @if($sectionInfo->acadprogid == 5)
                            <hr>
                            <strong><i class="fas fa-book mr-1"></i>Strand</strong>
                            <span id="block_list"></span>
                           
                        @endif
                            <hr>
                            <strong><i class="fas fa-user mr-1"></i>Class Adviser</strong>
                            <p class="small mb-0 pl-4" style="color: #af8402" id="adviser_holder"></p>
                            <p class="small mb-0 pl-4" id="adviser_holder_tid"></p>
                        <hr>
                        <strong><i class="fas fa-chair mr-1"></i>Room</strong>
                        <p class="small pl-4" style="color: #af8402" id="label_roomname">{{ $sectionInfo->roomname!=null ? $sectionInfo->roomname : 'No Room Assigned' }}</p>
                        <hr>
                        <strong><i class="fas fa-users mr-1"></i>Students</strong>
                        <p class=" pl-4 mb-0">
                            <label class="small mb-0">Enrolled</label> : <span id="s-1" class="badge badge-success">0</span>
                        </p>
                        <p class="pl-4 mb-0">
                            <label class="small mb-0">Late Enrolled</label> : <span  id="s-2" class="badge badge-primary">0</span>
                        </p>
                        <p class=" pl-4 mb-0">
                            <label class="small mb-0">Transferred In</label> : <span  id="s-4" class="badge badge-warning">0</span>
                        </p>
                        <p class=" pl-4 mb-0">
                            <label class="small mb-0">Dropped Out</label> : <span  id="s-3" class="badge badge-danger">0</span>
                        </p>
                        <p class=" pl-4 mb-0">
                            <label class="small mb-0">Transferred Out</label> <span  id="s-5" class="badge badge-secondary">0</span>
                        </p>
                        <p class=" pl-4 mb-0">
                            <label class="small mb-0">Withdrawn</label> <span  id="s-6" class="badge bg-orange" style="color:white !important">0</span>
                        </p>
                        <p class=" pl-4 mb-0">
                            <label class="small mb-0">Not Enrolled</label>  <span  id="s-0" class="badge badge-info">0</span>
                        </p>
                       
                       
                        @if($sectionInfo->acadprogid == 5)
                            <hr>
                            <span id="student_strandholder">

                            </span>
                       
                            <p class="small pl-4 mb-0">
                                Total : <span  id="ste-total">0</span>
                            </p>
                            <hr>
                        @else
                            <hr>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-primary btn-block" id="print_sched"><i class="fa fa-print mr-1"></i>Print Section Schedule</button>
                        {{-- <span>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-block" id="us" data-toggle="modal" data-target="#modal-section"><i class="far fa-edit mr-1"></i>Edit Section</button>
                        </span>
                         --}}
                        @if($sectionInfo->acadprogid == 2)
                            <hr>
                            <span>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-block" id="grade_status"><i class="far fa-edit mr-1"></i>View {{$sectionInfo->sectionname}} Grade Status</button>
                            </span>
                        @endif
                    </div>
                </div>
                </div>
            </div>
            <div class="modal fade" id="kinder_grade" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                            <div class="modal-header ">
                                <h4 class="modal-title">Student Grade
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                            </div>
                            <div class="modal-body" id="grade_holder">
                               
                            </div>
                            
                            
                    </div>
                </div>
            </div>

            <div class="modal fade" id="kinder_grade_status" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h4 class="modal-title">Grade Status
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        </div>
                        <div class="modal-body" >
                            <table class="table table-sm" id="grade_status_holder" width="100%">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Quarter 1</th>
                                        <th>Quarter 2</th>
                                        <th>Quarter 3</th>
                                        <th>Quarter 4</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
    <section>
@endsection

@section('footerjavascript')

    <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

    <script>
        var section = @json($sectionInfo);
        var sy = @json($schoolyear);
        var block = @json($temp_blocks);
        var utype = @json(Session::get('currentPortal'));
    </script>

    <script>
        $(document).on('click','#view_conflict',function(){
            $('#conflict_info_modal').modal()
        })
    </script>

    <script>
        //time temp detail
        var all_timetempdetail = [];
        var selected_timetempdetail = null;
    
    
        function get_timetempdetail(){
            $.ajax({
                type:'GET',
                url:'/setup/timetempdetails/list',
                data:{
                  headerid:$('#input_timetemplate').val()
                },
                success:function(data) {
                  all_timetempdetail = data
                  timetempdetail_datatable()
                },
            })
        }
    
        function create_timetempdetail(){
            $.ajax({
                type:'GET',
                url:'/setup/timetempdetails/create',
                data:{
                  timetempdetail:$('#timetempdetail_input').val(),
                  headerid:$('#input_timetemplate').val()
                },
                success:function(data) {
                    if(data[0].status == 1){
                      all_timetempdetail = data[0].data
                      timetempdetail_datatable()
                        if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ""){
                            get_sched()
                        }
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
    
        function update_timetempdetail(){
            $.ajax({
                type:'GET',
                url:'/setup/timetempdetails/update',
                data:{
                  id:selected_timetempdetail,
                  timetempdetail:$('#timetempdetail_input').val(),
                  headerid:$('#input_timetemplate').val()
                },
                success:function(data) {
                    if(data[0].status == 1){
                      all_timetempdetail = data[0].data
                      timetempdetail_datatable()
                        if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ""){
                            get_sched()
                        }
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
    
        function delete_timetempdetail(){
            $.ajax({
                type:'GET',
                url:'/setup/timetempdetails/delete',
                data:{
                    id:selected_timetempdetail,
                    headerid:$('#input_timetemplate').val()
                },
                success:function(data) {
                    if(data[0].status == 1){
                      all_timetempdetail = data[0].data
                      timetempdetail_datatable()
                        if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ""){
                            get_sched()
                        }
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
    
        function timetempdetail_datatable(){
          $("#timetempdetail_datatable").DataTable({
                destroy: true,
                data:all_timetempdetail,
                lengthChange : false,
                stateSave: true,
                autoWidth: false,
                columns: [
                      { "data": "stime_display" },
                      { "data": "etime_display" },
                      { "data": null },
                      { "data": null },
                ],
                columnDefs: [
                  {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                          var buttons = '<a href="javascript:void(0)" class="edit_timetempdetail" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                          $(td)[0].innerHTML =  buttons
                          $(td).addClass('text-center')
                          $(td).addClass('align-middle')
                          
                    }
                  },
                  {
                    'targets': 3,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                          var disabled = '';
                          var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_timetempdetail" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                          $(td)[0].innerHTML =  buttons
                          $(td).addClass('text-center')
                          $(td).addClass('align-middle')
                    }
                  },
                ],
          });
    
          var label_text = $($('#timetempdetail_datatable_wrapper')[0].children[0])[0].children[0]
          $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" id="create_timetempdetail_modal_button">Create Time</button>'
                        
        }
    
    
        $(document).on('click','#create_timetempdetail_button',function(){
          create_timetempdetail()
        })
    
        $(document).on('click','#update_timetempdetail_button',function(){
          update_timetempdetail()
        })
    
    
        $(document).on('click','#create_timetempdetail_modal_button',function(){
          $('#timetempdetail_input').daterangepicker({
            timePicker: true,
            startDate: '07:30 AM',
            endDate: '08:30 AM',
            timePickerIncrement: 5,
            locale: {
                format: 'hh:mm A',
                cancelLabel: 'Clear'
            }
          })
          $('#update_timetempdetail_button').attr('hidden','hidden')
          $('#create_timetempdetail_button').removeAttr('hidden')
          $('#timetempdeatil_form_modal').modal()
        })
    
    
        
    
        $(document).on('click','.edit_timetempdetail',function(){
          selected_timetempdetail = $(this).attr('data-id')
          var temp_detail = all_timetempdetail.filter(x=>x.id == selected_timetempdetail)
          $('#timetempdetail_input').daterangepicker({
            timePicker: true,
            startDate: temp_detail[0].stime_display,
            endDate: temp_detail[0].etime_display,
            timePickerIncrement: 5,
            locale: {
                format: 'hh:mm A',
                cancelLabel: 'Clear'
            }
          })
    
          $('#update_timetempdetail_button').removeAttr('hidden')
          $('#create_timetempdetail_button').attr('hidden','hidden')
    
          $('#timetempdeatil_form_modal').modal()
        })
        
        $(document).on('click','.delete_timetempdetail',function(){
            selected_timetempdetail = $(this).attr('data-id')
            Swal.fire({
                    text: 'Are you sure you want to remove detail?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
            }).then((result) => {
                    if (result.value) {
                      delete_timetempdetail()
                    }
            })
        })
    
    
      </script>


    <script>
        //time template
        var all_timetemplate = @json($schedtimetemplate);
        var selected_timetemp = null;
    
        function display_option(){
          $('#input_timetemplate').empty()
          $('#input_timetemplate').append('<option value="">Time Template</option>')
          $('#input_timetemplate').append('<option value="create"><i class="fas fa-plus"></i>Create Time Template</option>')
          $("#input_timetemplate").select2({
                  data: all_timetemplate,
                  allowClear: true,
                  placeholder: "Time Template",
          })
          if(selected_timetemp != null){
            $('#input_timetemplate').val(selected_timetemp).change()
          }
        }
    
        // get_timetempn()
        function get_timetemp(){
            $.ajax({
                type:'GET',
                url:'/setup/scheduletimetemp/list',
                success:function(data) {
                    all_timetemplate = data[0].data
                    display_option()
                },
            })
        }
    
        function create_timetemp(){
            if($('#timetemp_desc').val() == ""){
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }
            $.ajax({
                type:'GET',
                url:'/setup/scheduletimetemp/create',
                data:{
                    timetemp_desc:$('#timetemp_desc').val()
                },
                success:function(data) {
                    if(data[0].status == 1){
                        all_timetemplate = data[0].data
                        display_option()
                        display_time_template()
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
        
        function update_timetemp(){
            if($('#timetemp_desc').val() == ""){
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }
            $.ajax({
                type:'GET',
                url:'/setup/scheduletimetemp/update',
                data:{
                    timetemp_desc:$('#timetemp_desc').val(),
                    id:selected_timetemp
                },
                success:function(data) {
                    if(data[0].status == 1){
                      all_timetemplate = data[0].data
                      display_time_template()
                      display_option()
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
    
        function delete_timetemp(){
            $.ajax({
                type:'GET',
                url:'/setup/scheduletimetemp/delete',
                data:{
                    id:selected_timetemp
                },
                success:function(data) {
                    if(data[0].status == 1){
                        all_timetemplate = data[0].data
                        display_time_template()
                        display_option()
                        $('.edit_timetemp').attr('hidden','hidden')
                        $('.delete_timetemp').attr('hidden','hidden')
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
    
        display_option()
    
        $(document).on('change','#input_timetemplate',function(){
            if($(this).val() != "" && $(this).val() != "create"){
                get_timetempdetail()
                $('.edit_timetemp').removeAttr('hidden')
                $('.delete_timetemp').removeAttr('hidden')
            }else{
                if($(this).val() == "create"){
                    selected_timetemp = null
                    $('#timetemp_desc').val("")
                    $('#create_timetemp_button').removeAttr('hidden')
                    $('#update_timetemp_button').attr('hidden','hidden')
                    $('#timetemp_form_modal').modal()
                }else{
                    all_timetempdetail = []
                    timetempdetail_datatable()
                }
                $('.edit_timetemp').attr('hidden','hidden')
                $('.delete_timetemp').attr('hidden','hidden')
            }
        })
    
        $(document).on('click','#time_templatelist',function(){
            $('#input_timetemplate').val("").change()
            $('#timetemp_modal').modal()
        })
        
        $(document).on('click','#create_timetemp_button',function(){
            create_timetemp()
        })
    
        $(document).on('click','#update_timetemp_button',function(){
            update_timetemp()
        })
    
        $(document).on('click','.delete_timetemp',function(){
            selected_timetemp = $('#input_timetemplate').val()
            Swal.fire({
                    text: 'Are you sure you want to remove time template?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
            }).then((result) => {
                    if (result.value) {
                        delete_timetemp()
                    }
            })
        })
    
        $(document).on('click','.edit_timetemp',function(){
            $('#create_timetemp_button').attr('hidden','hidden')
            $('#update_timetemp_button').removeAttr('hidden')
            $('#timetemp_desc').val($('#input_timetemplate option:selected').text())
            selected_timetemp = $('#input_timetemplate').val()
            $('#timetemp_form_modal').modal()
        })
    
        // input_timetemplate
  </script>

    <script>

        var all_timetemplate = @json($schedtimetemplate);
        var selected_detailinfo = null
        display_time_template()

        getsubjects()

        function display_time_template(){
              var temp_id = null
              if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ''){
                    temp_id = $('#filter_timetemplate').val()
              }
              $('#filter_timetemplate').empty()
              $('#filter_timetemplate').append('<option value="">Time Template</option>')
              $("#filter_timetemplate").select2({
                    data: all_timetemplate,
                    allowClear: true,
                    placeholder: "Time Template",
              })
              if(temp_id != null){
               $('#filter_timetemplate').val(temp_id).change()
              }
        }

        function getsubjects(sectionid){
              $.ajax({
                    type:'GET',
                    url:'/principal/setup/schedule/getsubjects',
                    data:{
                          syid:$('#filter_schoolyear').val(),
                          semid:$('#filter_semester').val(),
                          sectionid:section.id,
                          levelid:section.levelid
                    },
                    success:function(data) {
                          if(data[0].status == 1){
                                all_subjects = data[0].data
                                $('#input_subject').empty()
                                $('#input_subject').append('<option value="">Select Subject</option>')
                                $("#input_subject").select2({
                                            data: all_subjects,
                                            allowClear: true,
                                            placeholder: "Select Subject",
                                })
                                if(selected_detailinfo != null){
                                      $('#input_subject').val(selected_detailinfo[0].subjid).change()
                                }
                          }else{

                          }
                    },
              })
        }
  </script>

    <script>

        // var all_sched = []
        var all_sched_format2 = []
  
        get_sched()
        $(document).on('click','#time_templatelist',function(){
            $('#input_timetemplate').val("").change()
            $('#timetemp_modal').modal()
        })

        $(document).on('change','#filter_timetemplate',function(){
            get_sched()
        })

        function get_sched(){

            $('#sched_holder').empty()
              $.ajax({
                    type:'GET',
                    url: '/principal/setup/schedule/get/sched',
                    data:{
                          sectionid:section.id,
                          syid:$('#filter_schoolyear').val(),
                          semid:$('#filter_semester').val(),
                          schedtype:'section',
                          timetemp:$('#filter_timetemplate').val()
                    },
                    success:function(data) {
                          all_sched = data[0].sched

                    

                          var temp_sched = data[0].sched
                          var temp_daylist = data[0].day_list
                          var temp_timelist = data[0].time_list
                          var temp_width =  88 / temp_daylist.length;
              
                          //display header
                          if(temp_daylist.length != 0){
                          var text = '<thead><tr><th width="12%" style="background-color:CornflowerBlue; color:white" class="text-center"></th>';
                          $.each(temp_daylist, function(a,b){
                                text += '<th width="'+temp_width+'%"  class="text-center align-middle" style="background-color:CornflowerBlue; color:white">'+b+'</th>'
                          })
                          text += '</tr></thead><tbody>'
                          $('#sched_holder').append(text)
              
                          $.each(temp_timelist, function(a,b){
                                var timeinfo = temp_sched.filter(x=>x.time == b)[0];
                                var text = '<tr><td class="text-center align-middle">'+timeinfo.stime+'<br>'+timeinfo.etime+'<p class="mb-0"><a href="javascript:void(0)" class="add_sched " data-stime="'+timeinfo.stime+'" data-etime="'+timeinfo.etime+'">Add Schedule</a></p>'+'</td>';
                                $.each(temp_daylist, function(c,d){
              
                                var day_sched = temp_sched.filter(x=>x.description == d && x.time == b)
                                var temp_bg = '';
                                
                                if(day_sched.length > 1){
                                temp_bg = 'bg-warning'
                                }
              
                                text += '<td class="text-center align-middle '+temp_bg+'">'
              
                                $.each(day_sched, function(e,f){
                                text += '<p  class="mb-0"><b><a href="javascript:void(0)" class="edit_sched" data-id="'+f.detailid+'">'+f.subjdesc+'</a></b></p>'
                                text += '<p style="font-size:.6rem !important" class="mb-0">'+f.sectionname+'</p>'
                                text += '<p style="font-size:.6rem !important;"  class="mb-0"><i>'+f.teacher+'</i></p>'
                                text += ' <p style="font-size:.6rem !important; color:red" class="mb-1">'+f.classification+'</p>'
                                })
              
                                text += '</td>'
                                })
                                text += '</tr>'
                                $('#sched_holder').append(text)
                          })
                          text = '</tbody>'
                          $('#sched_holder').append(text)
                          }else{
              
                          var text = '<tr><td class="text-center" width="100%">No Schedule Available</td></tr>'
                          $('#sched_holder').append(text)
                          }
                          
                          if(utype == 1){
                            $('.add_sched').remove()
                            $('.edit_sched').removeClass('edit_sched')
                          }
                        
  
                    },
                    error:function(){
                          $('#create_room').removeAttr('disabled')
                          Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                          })
                    }
              })
        
        }
    </script>

    <script>
        $(document).ready(function(){

            $('#filter_format').select2()

            $(document).on('change','#filter_format', function() {
                if($(this).val() == 1){
                    loadSched()
                    $('#sched_format1').removeAttr('hidden')
                    $('#sched_format2').attr('hidden','hidden')
                }else{
                    get_sched()
                    $('#sched_format2').removeAttr('hidden')
                    $('#sched_format1').attr('hidden','hidden')
                }
            })

            $(document).on('click','#enrollment_form', function() {

                if($('#sn').val() == ""){
                    Toast.fire({
                        type: 'warning',
                        title: "Name is empty!"
                    })
                    return false
                }

                if($('#gl').val() == ""){
                    Toast.fire({
                        type: 'warning',
                        title: "Grade Level is empty!"
                    })
                    return false
                }

                var sunday_class = 0
                if($('#nightClass').prop('checked')){
                    sunday_class = 1
                }
                $.ajax( {
                    url: '/updateSectionInformation',
                    type: 'GET',
                    data: {
                        'section':section.id,
                        'sectionname':$('#sn').val(),
                        'levelid':$('#gl').val(),
                        'teacherid':$('#t').val(),
                        'roomid':$('#r').val(),
                        'session':$('#sectsession').val(),
                        'sunday':sunday_class
                    },
                    
                    success:function(data) {
                        if(data[0].status == 2){
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        }else{
                            section.sectionname = $('#sn').val()
                            section.levelid = $('#gl').val()
                            section.teacherid = $('#t').val()
                            section.roomid = $('#r').val()
                            section.session = $('#sectsession').val()
                            section.sundayClass = sunday_class

                            var levelname = gradelevel.filter(x=>x.id == section.levelid)
                            var roomname = all_rooms.filter(x=>x.id == section.roomid)
                            var teacherinfo = all_teacher.filter(x=>x.id == section.teacherid)

                            $('#label_sectionname').text(section.sectionname)
                            $('#label_levelname').text(levelname[0].levelname)
                            $('#label_roomname').text(roomname[0].roomname)
                            var active_sy = sy.filter(x=>x.isactive == 1)
                            var syid = $('#filter_schoolyear').val()
                            if(syid == active_sy[0].id){
                                if(teacherinfo.length > 0){
                                    $('#adviser_holder_tid').text(roomname[0].tid)
                                    $('#adviser_holder').text(teacherinfo[0].firstname+' '+teacherinfo[0].lastname)
                                }
                            }
                        

                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                })
            })

         
            section_adviser()
            
            $('#input_block').append('<option value="">Select Strand</option>')
            $('#input_block').select2({
                data: block,
                allowClear: true,
                placeholder: "Select Strand",
            })
            
            if(section.acadprogid == 5){
                select_strand()
            }else{
                load_students()
                loadSched_ajax()
                loadsubjectstatus()
            }

            $(document).on('change','#filter_schoolyear',function(){
                $('#filter_strand').empty();
                $('#block_list').empty();
                $('#student_strandholder').empty()
                $('#adviser_holder').text('')
                $('#adviser_holder_tid').text('')
                section_adviser()
                if(section.acadprogid == 5){
                    select_strand()
                }
               
            })

            function select_strand(){
                load_strand()
                var active_sy = sy.filter(x=>x.isactive == 1)
                var syid = $('#filter_schoolyear').val()
                if(syid != active_sy[0].id){
                    $('.add_block').attr('hidden','hidden')
                    $('.remove_block').attr('hidden','hidden')
                }else{
                    $('.add_block').removeAttr('hidden')
                    $('.remove_block').removeAttr('hidden')
                }
            }
            
            $(document).on('change','#filter_strand',function(){
                var temp_strand = $('#filter_strand').val()
                plot_student()
            })

            $(document).on('change','#filter_schoolyear, #filter_semester',function(){
                get_sched()
                getsubjects()
                load_students()
            })

            $(document).on('click','#reload_student',function(){
                load_students()
            })
            
        })
    </script>
  
<script>

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
    })

    var section = @json($sectionInfo);
    var all_rooms = @json($rooms);
    var all_teacher = @json($vacantTeachers);
    var all_session = @json($session);
    var gradelevel = @json($gradelevel);
    var all_sched = [];
    var all_strand = []
    var all_students = []
    var adviser = []
   
    $.each(all_rooms,function(a,b){b.text = b.roomname})
    $.each(all_session,function(a,b){b.text = b.sessionDesc})
    $.each(gradelevel,function(a,b){b.text = b.levelname})
    $.each(all_teacher,function(a,b){b.text = b.tid+' - '+b.lastname+', '+b.firstname})

    $('#gl').append('<option value="">Select Gradelevel</option>')
    $('#gl').select2({
        data: gradelevel,
        allowClear: true,
        placeholder: "Select Gradelevel",
    })

    $('#t').append('<option value="">Select Adviser</option>')
    $('#t').select2({
        data: all_teacher,
        allowClear: true,
        placeholder: "Select Adviser",
    })

    $('#sectsession').append('<option value="">Select Session</option>')
    $('#sectsession').select2({
        data: all_session,
        allowClear: true,
        placeholder: "Select Session",
    })

    $('#r').append('<option value="">Select Room</option>')
    $('#r').select2({
        data: all_rooms,
        allowClear: true,
        placeholder: "Select Room",
    })

    $('#filter_schoolyear').select2()
    $('#filter_semester').select2()
    $('#filter_strand').select2()

    $('#secttea').select2({allowClear:true})
    $('#sectroo').select2({allowClear:true})
    $('#classification').select2()

    load_section_form()

    function load_section_form(){
        $('#sn').val(section.sectionname)
        $('#r').val(section.roomid).change()
        $('#t').val(section.teacherid).change()
        $('#sectsession').val(section.session).change()
        $('#gl').val(section.levelid).change()
        if(section.sundaySchool == 1){
            $('#nightClass').prop('checked',true)
        }else{
            $('#nightClass').prop('checked',false)
        }
    }

        function enrollment_count(){
            $.ajax({
                type:'GET',
                url:'/principal/records/section/enrollment/count',
                data:{
                    section:section.id,
                    levelid:section.levelid,
                },
                success:function(data) {
                    
                    if(data > 0){
                        $('#gl').attr('disabled','disabled')
                        $('#sn').attr('readonly','readonly')
                    }else{
                        $('#gl').removeAttr('disabled')
                        $('#sn').removeAttr('readonly')
                    }
                },
            })
        }

        function section_adviser(){
            $.ajax({
                type:'GET',
                url:'/principal/records/section/adviser',
                data:{
                    section:section.id,
                    syid: $('#filter_schoolyear').val(),
                },
                success:function(data) {
                    if(data.length > 0 ){
                        $('#adviser_holder').text(data[0].firstname+' '+data[0].lastname)
                        $('#adviser_holder_tid').text(data[0].tid)
                    }else{
                        $('#adviser_holder').text('No adviser')
                        $('#adviser_holder_tid').text('')
                    }
                },
            })
        }

        function plot_student(){

            data = all_students

            if(section.acadprogid == 5){
                $.each(all_strand,function(a,b){
                    $('#ste-'+b.strandid).text(0)
                })
                $.each(all_strand,function(a,b){
                    var student_count = data.filter(x=>x.strandid == b.strandid).length
                    $('#ste-'+b.strandid).text(student_count)
                })
                $('#ste-total').text(data.length)
            }

            if(data.length == 0){
                $('#s-1').text(0)
                $('#s-2').text(0)
                $('#s-3').text(0)
                $('#s-4').text(0)
                $('#s-5').text(0)
                $('#s-6').text(0)
                $('#s-0').text(0)
                $('#student_count').text(0)
                var text = '<tr><td><i>No enrolled learners. Please contact the Registrar\'s Office.</i></td></tr>'
                $('#student_holder')[0].innerHTML = text
            }else{
                var text = ''
                
                $('#s-1').text(data.filter(x=>x.studstatus == 1).length)
                $('#s-2').text(data.filter(x=>x.studstatus == 2).length)
                $('#s-3').text(data.filter(x=>x.studstatus == 3).length)
                $('#s-4').text(data.filter(x=>x.studstatus == 4).length)
                $('#s-5').text(data.filter(x=>x.studstatus == 5).length)
                $('#s-6').text(data.filter(x=>x.studstatus == 6).length)
                $('#s-0').text(data.filter(x=>x.studstatus == 0).length)

                if(section.acadprogid == 5){
                    var temp_strand = $('#filter_strand').val()
                    data = data.filter(x=>x.strandid == temp_strand)
                    $('#student_count').text(data.length)
                }else{
                    $('#student_count').text(data.length)
                }

                $.each(data,function(a,b){
                    var strand = ''
                    var bg = ''

                    if(b.studstatus == 1){
                        var bg = 'bg-success'
                    }else if(b.studstatus == 2){
                        var bg = 'bg-primary'
                    }else if(b.studstatus == 3){
                        var bg = 'bg-warning'
                    }else if(b.studstatus == 4){
                        var bg = 'bg-danger'
                    }else if(b.studstatus == 5){
                        var bg = 'bg-secondary'
                    }else if(b.studstatus == 6){
                        var bg = 'bg-orange'
                    }else if(b.studstatus == 0){
                        var bg = 'bg-info'
                    }

                    var desc = '<span class="badge '+bg+'">'+b.description+'</span>'
                    if(b.levelid == 14 || b.levelid == 15){
                        strand = '<span class="badge '+bg+'">'+b.strandcode+'</span>'
                    }
                    text +=   '<tr><td class="small">'+
                                    '<p class="mb-0">'+b.student+'</p>'+
                                    '<p class="text-muted mb-0" style="font-size:.7rem">'+b.sid+' '+strand+' '+desc+'</p>'
                                '</td></tr>'
                })

                $('#student_holder')[0].innerHTML = text
            }
        }

        function load_students(params) {

            $('#student_holder').empty()
            $.ajax({
                type:'GET',
                url:'/principal/section/students/enrolled',
                data:{
                    section:section.id,
                    semid: $('#filter_semester').val(),
                    syid: $('#filter_schoolyear').val(),
                    acad:section.acadprogid
                },
                success:function(data) {
                    all_students = data 
                    plot_student()
                },
                error:function(){
                    var text = '<tr><td>Something went wrong! <br><a href="#" id="reload_student">Click here</a> to reload students.</td></tr>'
                    $('#student_holder')[0].innerHTML = text
                }

            })
        }

        function load_strand(){
            $('#block_list').empty();
            $('#filter_strand').empty();
            $('#student_strandholder').empty()
            $.ajax({
                type:'GET',
                url:'/principal/records/blockassignment',
                data:{
                    'section':section.id,
                    'syid':$('#filter_schoolyear').val(),
                },
                success:function(data) {
                    if(data.length == 0){
                        $('#scheduletable')[0].innerHTML = '<tr><td colspan="6">No strand added. Please add strand to section.<a href="javascript:void(0)" class="add_block">Click here</a> to add strand</td></tr>'
                    }else{
                        all_strand = data;
                        $.each(data,function(a,b){
                            $('#filter_strand').append('<option value="'+b.strandid+'">'+b.strandcode+'</option>')
                            $('#block_list').append('<p class="small mb-0 pl-4" style="color: #af8402">  <span class="text-success">[ '+b.strandcode+' ]</span>  '+b.blockname+'</p>')
                            $('#student_strandholder').append(' <p class="small pl-4 mb-0">'+b.strandcode+' : <span  id="ste-'+b.strandid+'">0</span></p>')
                        })
                        load_students()
                        loadSched_ajax()
                        loadsubjectstatus()
                        $('.ste-total').text(0)
                    }
                },
            })
        }

        function loadSched_ajax(){
            loadSched()
        }
        
        function loadsubjectstatus(){
            var strand = null
            var semid = null
            if(section.acadprogid == 5){
                strand = $('#filter_strand').val()
                semid = $('#filter_semester').val()
                if(strand == null){
                    return false
                }
            }
            $.ajax({
                    type:'GET',
                    url:'/principal/grades/status',
                    data:{
                        acadprogid:section.acadprogid,
                        section:section.id,
                        levelid:section.levelid,
                        syid:$('#filter_schoolyear').val(),
                        semid:semid,
                        strandid:strand,
                    },
                    success:function(data) {
                        $('#sectionsubjects').empty()
                        $('#sectionsubjects').append(data)
                    },
            })
        }

        function loadSched(){
            var strand = null
            var semid = null
            if(section.acadprogid == 5){
                strand = $('#filter_strand').val()
                semid = $('#filter_semester').val()
            }
            $.ajax({
                    type:'GET',
                    url:'/principal/setup/schedule/plot',
                    data:{
                        sectionid:section.id,
                        levelid:section.levelid,
                        syid:$('#filter_schoolyear').val(),
                        semid:semid,
                        strandid:strand,
                        issp:true
                    },
                    success:function(data) {
                        $('#scheduletable').empty()
                        $('#scheduletable').append(data)
                        if(utype == 1){
                            $('button[id="add_sched"]').remove();
                            $('button[id="dropdownMenuButton"]').remove();
                            $('.add_sched').remove();
                            $('#time_templatelist').remove();
                        }
                    },
            })
        }
</script>

<script>
    $(document).ready(function(){

        var section = @json($sectionInfo);
        var selectedSubject = null;
        var newTeacherSubject = null;
        var selectedSubject = null;
        var selectedtype = null;
        var selectedHeader = null;

        if(section.acadprogid == 5){
            $('.filter_semester_holder').removeAttr('hidden')
        }

        $(document).on('click','.add_block',function(){
            $('#block_modal').modal()
        })

        $(document).on('change','#filter_strand',function(){
            loadSched_ajax()
            loadsubjectstatus()
        })

        $(document).on('change','#filter_schoolyear , #filter_semester',function(){
            $('#scheduletable').empty()
            $('#sectionsubjects').empty()
            loadSched_ajax()
            loadsubjectstatus()
        })

        $(document).on('click','#as',function(){
            $('#modal-primary').modal('show')
        })

        $(document).on('click','.abb',function(){
            var input_strand = $('#input_block').val()
            $.ajax({
                type:'GET',
                url:'/prinicipaladdblocktoshsection',
                data:{
                    sectionid:section.id,
                    levelid:section.levelid,
                    syid:$('#filter_schoolyear').val(),
                    strandid:input_strand
                },
                success:function(data) {
                    if(data[0].status == 2){
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        })
                    }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        getsubjects()
                        load_strand()
                    }
                    
                }
            })
        })

        $(document).on('click','.remove_block',function(){
            var temp_blockid = $(this).attr('data-id')
            $.ajax({
                type:'GET',
                url:'/principal/setup/section/block/remove',
                data:{
                    section:'{{$sectionInfo->id}}',
                    b:temp_blockid,
                    syid:$('#filter_schoolyear').val()
                },
                success:function(data) {
                    if(data[0].status == 1){
                        Toast.fire({
                            type: 'success',
                            title: data[0].data
                        })
                        getsubjects()
                        load_strand()
                    }
                    
                }
            })
        })

        var allowconflict = 0

        $(document).on('change','#sectroo, #classification, #time, .day, #secttea, #input_subject',function(){

            $('#sched_con_holder').attr('hidden','hidden')

            if(iscreate){
                $('.eval').text('Create Schedule')
            }else{
                $('.eval').text('Update Schedule')
            }

            $('#conflict_holder').attr('hidden','hidden')

            allowconflict = 0
        })

        $(document).on('change','#input_subject',function(){
            var temp_subjid = $(this).val()
            if(temp_subjid != "" && temp_subjid != null){
                var temp_subjinfo = all_subjects.filter(x=>x.id == temp_subjid)
                if(temp_subjinfo[0].subjCom != null){
                    $('#apptocon_holder').removeAttr('hidden')
                }else{
                    $('#apptocon_holder').attr('hidden','hidden')
                }
            }
        })

        $(document).on('click','.remove_sched',function(){

            var days = [];

            $('.day').each(function(){
                if($(this).is(":checked")){
                        days.push($(this).val())
                }
            })

            if(days.length == 0){
                Toast.fire({
                        type: 'error',
                        title: 'No days selected!'
                })
                return false;
            }

            var temp_sched_info = all_sched.filter(x=>x.detailid == $(this).attr('data-id'))
            var copy_com = 0;


            if(section.acadprogid == 5){
                copy_com = 0;
            }else{
                var check_subject = all_subjects.filter(x=>x.id == $('#input_subject').val())
                if(check_subject[0].subjCom == null){
                    copy_com = 0;
                }else{
                    if($('#apptocon').prop('checked') == true){
                        copy_com = 1;
                    }
                }
            }
            Swal.fire({
                text: 'Are you sure you want to remove schedule?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:'GET',
                        url:'/principal/setup/schedule/removesched',
                        data:{
                                applycom:copy_com,
                                days:days,
                                dataid:selected_detailinfo[0].detailid,
                                levelid:selected_detailinfo[0].levelid
                        },
                        success:function(data) {
                                if(data[0].status == 1){
                                    Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                    })
                                    $('#modal_schedule').modal('hide')
                                    loadSched()
                                    get_sched()
                                }else{
                                    Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                    })
                                }
                        }
                    })
                }
            })
        })

            $(document).on('click','.edit_sched, .update_sched',function(){

                if($(this).hasClass('update_sched')){
                    var temp_sched_info = all_sched.filter(x=>x.detailid == $(this).attr('data-headerid'))
                }else{
                    var temp_sched_info = all_sched.filter(x=>x.detailid == $(this).attr('data-id'))
                }

                $('#conflict_holder').attr('hidden','hidden')
                
                selected_detailinfo = temp_sched_info
                $('#apptocon').prop('checked',false)

                if($(this).attr('iscon') == 1){
                    $('#apptocon_holder').removeAttr('hidden')

                }else{
                    $('#apptocon_holder').attr('hidden','hidden')
                }

                $('.remove_sched').removeAttr('hidden')
                $("#apptocon").removeAttr('checked');

                iscreate = false

                if(iscreate){
                    $('.eval').text('Create Schedule')
                    $('.eval').addClass('btn-primary')
                    $('.eval').removeClass('btn-success')
                }else{
                    $('.eval').text('Update Schedule')
                    $('.eval').addClass('btn-success')
                    $('.eval').addClass('btn-primary')
                }

                $('.eval').removeClass('.evalupdate')

                $('#sched_con_holder').attr('hidden','hidden')
                $('#con_stat').text("")
                $('#con_sect').text("")
                $('#con_subj').text("")
                $('#con_day').text("")
                $('#con_time').text("")
                allowconflict = 0
                conflicts = []

                $('#conflict_holder').attr('hidden','hidden')

                $('.evalupdate').addClass('eval')
                $('.eval').removeClass('.evalupdate')
                $('#modal_schedule').modal()

                $('#secttea').val(temp_sched_info[0].teacherid).change()
                $('#input_section').val(temp_sched_info[0].sectionid).change()
                $('#input_subject').val(temp_sched_info[0].subjid).change()
                $('#input_room').val(temp_sched_info[0].roomid).change()
                $('#classification').val(temp_sched_info[0].schedclassid).change()

                $('.day').prop('checked',false)
                $('.eval').removeAttr('disabled')



                $('.reservationtime').daterangepicker({
                    timePicker: true,
                    startDate: temp_sched_info[0].stime,
                    endDate: temp_sched_info[0].etime,
                    timePickerIncrement: 5,
                    locale: {
                            format: 'hh:mm A',
                            cancelLabel: 'Clear'
                    }
                })

                var temp_days = all_sched.filter(x=>x.time == temp_sched_info[0].time && x.subjid == temp_sched_info[0].subjid && x.schedclassid == temp_sched_info[0].schedclassid)

                $.each(temp_days,function(a,b){
                    $('.day[value="'+b.days+'"]').prop('checked',true)
                })

                selectedSubject = $(this).attr('data-id')
                $('#conflict_holder').attr('hidden','hidden')

        })

        $(document).on('click','.eval',function(){

            $('#modal-primary').modal('hide')
            var days = [];
            var valid_data = true

            if($('#input_subject').val() == ""){
                Toast.fire({
                    type: 'error',
                    title: 'No Subject Selected!'
                })
                valid_data= false
            }

            $('.day').each(function(){
                if($(this).is(":checked")){
                    days.push($(this).val())
                }
            })

            if(days.length == 0){
                Toast.fire({
                    type: 'error',
                    title: 'No days selected!'
                })
                valid_data= false
            }
            
            if($('#classification').val() == ""){
                Toast.fire({
                    type: 'error',
                    title: 'No Classification Selected'
                })
                valid_data= false
            }

            var copy_com = 0;

            if(valid_data){

                var temp_url = section.acadprogid == 5 ? '/principal/setup/schedule/sh/add' : '/principal/setup/schedule/gshs/add'
                $(this).attr('disabled','disabled')

                if(section.acadprogid == 5){
                    copy_com = 0;
                }else{
                    var check_subject = all_subjects.filter(x=>x.id == $('#input_subject').val())
                    if(check_subject[0].subjCom == null){
                        copy_com = 0;
                    }else{
                        if($('#apptocon').prop('checked') == true){
                            copy_com = 1;
                        }
                    }
                }
                

                $.ajax({
                    type:'GET',
                    url:temp_url,
                    data:{
                        applycom:copy_com,
                        section:'{{$sectionInfo->id}}',
                        t:$('#time').val(),
                        s:$('#input_subject').val(),
                        tea:$('#secttea').val(),
                        r:$('#sectroo').val(),
                        days:days,
                        class:$('#classification').val(),
                        syid:$('#filter_schoolyear').val(),
                        semid:$('#filter_semester').val(),
                        iscreate:iscreate,
                        allowconflict:allowconflict,
                        schedinfo:selected_detailinfo
                    },
                    success:function(data) {

                            $('#conflict_holder').attr('hidden','hidden')

                            if(data[0].status == 1){
                                   
                                    if(iscreate){
                                        $('.eval').text('Create Schedule')
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Created Successfully!'
                                        })
                                    }else{
                                        $('.eval').text('Update Schedule')
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Updated Successfully!'
                                        })
                                    }
                                    
                                    
                                    allowconflict = 0
                                   
                                    loadSched()
                                    get_sched()
                                    
                                    $('#sched_con_holder').attr('hidden','hidden')
                                    $('#con_stat').text("")
                                    $('#con_sect').text("")
                                    $('#con_subj').text("")
                                    $('#con_day').text("")
                                    $('#con_time').text("")
                            }else{
                                    if(data[0].data == 'conflict'){

                                        $('#conflict_holder').removeAttr('hidden')

                                        $('#sched_con_holder').removeAttr('hidden')

                                        $('.rc_holder').attr('hidden','hidden')
                                        $('.sc_holder').attr('hidden','hidden')
                                        $('.tc_holder').attr('hidden','hidden')
                                        
                                        $('#room_conflict').empty();
                                        $('#section_conflict').empty();
                                        $('#teacher_conflict').empty();

                                        $.each(data[0].details,function(a,b){
                                            console.log(b.conflict)
                                            if(b.conflict == 'TSC'){
                                                $('.tc_holder').removeAttr('hidden')
                                                var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                                <div class="card shadow">
                                                                    <div class="card-body p-1">
                                                                        <p class="mb-0">Subject: `+b.subject+`</p>
                                                                        <p class="mb-0">Section: `+b.section+`</p>
                                                                        <p class="mb-0">Day: `+b.days+`</p>
                                                                    </div>
                                                                </div>
                                                            </div>`

                                                $('#teacher_conflict').append(text)
                                            }else if(b.conflict == 'RSC'){
                                                $('.rc_holder').removeAttr('hidden')
                                                var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                                <div class="card shadow">
                                                                    <div class="card-body p-1">
                                                                        <p class="mb-0">Subject: `+b.subject+`</p>
                                                                        <p class="mb-0">Section: `+b.section+`</p>
                                                                        <p class="mb-0">Day: `+b.days+`</p>
                                                                    </div>
                                                                </div>
                                                            </div>`

                                                $('#room_conflict').append(text)
                                            }else if(b.conflict == 'SSC'){
                                                $('.sc_holder').removeAttr('hidden')
                                                var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                                <div class="card shadow">
                                                                    <div class="card-body p-1">
                                                                        <p class="mb-0">Subject: `+b.subject+`</p>
                                                                        <p class="mb-0">Day: `+b.days+`</p>
                                                                    </div>
                                                                </div>
                                                            </div>`

                                                $('#section_conflict').append(text)
                                            }
                                        })
                                    
                                        Toast.fire({
                                                type: 'error',
                                                title: 'Schedule Conflict'
                                        })
                                        if(iscreate){
                                            $('.eval').text('Conflict : Proceed Create')
                                        }else{
                                            $('.eval').text('Conflict : Proceed Update')
                                        }
                                       
                                        allowconflict = 1
                                    }else{
                                        Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                        })
                                        $('.eval').text('Update Schedule')
                                        allowconflict = 0
                                    }
                            }

                            $('.eval').removeAttr('disabled')
                      
                    }
                })
            }

        })

        $(document).on('click','#add_sched, .add_sched , .add_sched_specsubj',function(){

            if($(this).attr('iscon') == 1){
                $('#apptocon_holder').removeAttr('hidden')
            }else{
                $('#apptocon_holder').attr('hidden','hidden')
            }



            $('#conflict_holder').attr('hidden','hidden')
            $('#input_subject').val("").change()


            // $('#classification option:eq(2)').attr('selected', 'selected')


            iscreate = true
            $('.eval').addClass('btn-primary')
            $('.eval').removeClass('btn-success')
            $('.eval').text('Create Schedule')
            $('.eval').removeClass('.evalupdate')
            $('#apptocon').prop('checked',false)

            $('#sched_con_holder').attr('hidden','hidden')
            $('#con_stat').text("")
            $('#con_sect').text("")
            $('#con_subj').text("")
            $('#con_day').text("")
            $('#con_time').text("")
            allowconflict = 0
            conflicts = []
            $('#conflict_holder').attr('hidden','hidden')
            $('.evalupdate').addClass('eval')
            $('.eval').removeClass('.evalupdate')
            $('#modal_schedule').modal()
            $('#secttea').val("").change()
            $('#sectroo').val("").change()
            $('.day').prop('checked',false)
            $('.eval').removeAttr('disabled')
            $('.reservationtime').daterangepicker({
                timePicker: true,
                startDate: '07:30 AM',
                endDate: '08:30 AM',
                timePickerIncrement: 5,
                locale: {
                    format: 'hh:mm A',
                    cancelLabel: 'Clear'
                }
            })
            
            // add_sched

            if($(this).attr('data-stime') != null){
                $('.reservationtime').daterangepicker({
                        timePicker: true,
                        startDate: $(this).attr('data-stime'),
                        endDate: $(this).attr('data-etime'),
                        timePickerIncrement: 5,
                        locale: {
                            format: 'hh:mm A',
                            cancelLabel: 'Clear'
                        }
                })
            }else{

                $('.reservationtime').daterangepicker({
                        timePicker: true,
                        startDate: '07:30 AM',
                        endDate: '08:30 AM',
                        timePickerIncrement: 5,
                        locale: {
                            format: 'hh:mm A',
                            cancelLabel: 'Clear'
                        }
                })
            }

            if($(this).attr('data-id') != undefined){
                $('#input_subject').val( $(this).attr('data-id')).change()
            }

            if($('#filter_format').val() == 1){
                $('#input_subject').attr('disabled','disabled')
            }else{
                $('#input_subject').removeAttr('disabled')
            }
           

            var temp_classid = $('#classification option:eq(2)').val()
            $('#classification').val(temp_classid).change()
          
            selectedSubject = $(this).attr('data-id')
          
            $('#secttea').val(section.teacherid).change()
            $('#sectroo').val(section.roomid).change()
       
            $('#conflict_holder').attr('hidden','hidden')
        })

        var iscreate = true;

        $(document).on('click','#us',function(){
            enrollment_count()
            
        })

    })

</script>

<script>
        //kinder 
        $(document).ready(function(){
                var section = @json($sectionInfo);

                $(document).on('click','#print_sched',function(){
                    window.open('/principal/setup/section/print?schedtype=section&syid='+$('#filter_schoolyear').val()+'&semid='+$('#filter_semester').val()+'&sectionid='+section.id+'&timetemp='+$('#filter_timetemplate').val(), '_blank');
                })

                $(document).on('click','.view_reportcard',function(){
                    var studid = $(this).attr('id')
                    $('#kinder_grade').modal()
                    $.ajax({
                        type:'GET',
                        url:'/gradestudent/preschool',
                        data:{
                            'evaluate':'evaluate',
                            'studid':studid,
                            'gsid':2
                        },
                        success:function(data) {
                    
                                $('#grade_holder').append(data)
                                $('.grade_select').attr('readonly','readonly')

                        },
                    })
                })

                var gradestatus_list = []

                $(document).on('click','#grade_status',function(){
               
                    $('#kinder_grade_status').modal()

                    $.ajax({
                        type:'GET',
                        url:'/principal/ps/gradestatus/list',
                        data:{
                            'syid':$('#filter_schoolyear').val(),
                            'sectionid':section.id
                        },
                        success:function(data) {
                            gradestatus_list = data
                            loadgradestatus()
                        },
                    })
                    
                })

                $(document).on('click','.button_post',function(){
                    var studid = $(this).attr('data-studid')
                    var psgradestatusid = $(this).attr('data-id')
                    var status = $(this).attr('data-status')
                    var quarter = $(this).attr('data-quarter')
                    $.ajax({
                        type:'GET',
                        url:'/principal/ps/gradestatus/update',
                        data:{
                            studid:studid,
                            psgradestatusid:psgradestatusid,
                            status:status,
                            quarter:quarter,
                        },
                        success:function(data) {
                            var tem_gradestatus_index = gradestatus_list.findIndex(x => x.studid == studid && x.id == psgradestatusid)
                            if(quarter == 1){
                                gradestatus_list[tem_gradestatus_index].q1status = status
                            }else if(quarter == 2){
                                gradestatus_list[tem_gradestatus_index].q2status = status
                            }
                            else if(quarter == 3){
                                gradestatus_list[tem_gradestatus_index].q3status = status
                            }
                            else if(quarter == 4){
                                gradestatus_list[tem_gradestatus_index].q4status = status
                            }
                            loadgradestatus()
                        },
                    })
                })

                $(document).on('click','.view_printable',function(a,b){
                  
                    if(section.levelid == 3){
                        window.open('/grade/preschool/pdf?studid='+$(this).attr('data-id')+'&syid='+3, '_blank');
                    }else{
                        window.open('/grade/prekinder/pdf?studid='+$(this).attr('data-id')+'&syid='+3, '_blank');
                    }
                })
                

                function loadgradestatus(){

                    $("#grade_status_holder").DataTable({
                        destroy: true,
                        stateSave: true,
                        data:gradestatus_list,
                        "columns": [
                                    { "data": "lastname" },
                                    { "data": "firstname" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                            ],
                        columnDefs: [
                            {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                       $(td)[0].innerHTML = '<a class="view_printable" data-id="'+rowData.studid+'" href="#">'+rowData.lastname + ', ' + rowData.firstname+'</a>'
                                    }
                            },
                            {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.q1status == null || rowData.q1status == ""){
                                           $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="1" data-quarter="1">Approve</button>'
                                        }
                                        else if(rowData.q1status == 1){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-info btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="2" data-quarter="1">Post</button>'
                                        }
                                        else if(rowData.q1status == 2){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-danger btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="" data-quarter="1">Unpost</button>'
                                        }
                                    }
                            },
                            {
                                    'targets': 2,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.q2status == null || rowData.q2status == ""){
                                           $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="1" data-quarter="2">Approve</button>'
                                        }
                                        else if(rowData.q2status == 1){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-info btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="2" data-quarter="2">Post</button>'
                                        }else if(rowData.q2status == 2){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-danger  btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="" data-quarter="2">Unpost</button>'
                                        }
                                    }
                            },
                            {
                                    'targets': 3,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.q3status == null || rowData.q3status == ""){
                                           $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="1" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="" data-quarter="3">Approve</button>'
                                        }else if(rowData.q3status == 1){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-info btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="2" data-quarter="3">Post</button>'
                                        }else if(rowData.q3status == 2){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-danger btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="" data-quarter="3">Unpost</button>'
                                        }
                                    }
                            },
                            {
                                    'targets': 4,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.q4status == null || rowData.q4status == ""){
                                           $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="1" data-quarter="4">Approve</button>'
                                        }else if(rowData.q4status == 1){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-info btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="2" data-quarter="4">Post</button>'
                                        }else if(rowData.q4status == 2){
                                            $(td)[0].innerHTML = '<button class="btn btn-sm btn-info btn-block button_post" data-studid="'+rowData.studid+'" data-id="'+rowData.id+'" data-status="" data-quarter="4">Unpost</button>'
                                        }
                                    }
                            },
                        ]
                    });
                }
        })
        //kinder 
    </script>

    <script>
        $(document).ready(function(){
            @if ($errors->any())
                $('#modal-section').modal('show');
            @endif
        });
    </script>

    <script>
        var scheduleclassification = []
        var selected_schedclass = null
        get_schedclassification()
        function get_schedclassification(){
            $.ajax({
                type:'GET',
                url:'/setup/scheduleclassification/list',
                success:function(data) {
                    scheduleclassification = data
                    $('#classification').empty()
                    $('#classification').append('<option value="">Schedule Classification</option>')
                    $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                    $("#classification").select2({
                            data: scheduleclassification,
                            allowClear: true,
                            placeholder: "Schedule Classification",
                    })
                },
            })
        }
        function create_schedclassification(){

            if($('#schedclass_desc').val() == ""){

                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })

                return false
            }

            $.ajax({
                type:'GET',
                url:'/setup/scheduleclassification/create',
                data:{
                    schedclass_desc:$('#schedclass_desc').val()
                },
                success:function(data) {
                    if(data[0].status == 1){
                        scheduleclassification = data[0].data
                        $('#classification').empty()
                        $('#classification').append('<option value="">Schedule Classification</option>')
                        $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                        $("#classification").select2({
                                data: scheduleclassification,
                                allowClear: true,
                                placeholder: "Schedule Classification",
                        })
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                    
                },
            })
        }
        function update_schedclassification(){
            $.ajax({
                type:'GET',
                url:'/setup/scheduleclassification/update',
                data:{
                    schedclass_desc:$('#schedclass_desc').val(),
                    id:selected_schedclass
                },
                success:function(data) {
                    if(data[0].status == 1){
                        scheduleclassification = data[0].data
                        $('#classification').empty()
                        $('#classification').append('<option value="">Schedule Classification</option>')
                        $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                        $("#classification").select2({
                                data: scheduleclassification,
                                allowClear: true,
                                placeholder: "Schedule Classification",
                        })
                        $('#classification').val(selected_schedclass).change()
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
        function delete_schedclassification(){
            $.ajax({
                type:'GET',
                url:'/setup/scheduleclassification/delete',
                data:{
                    id:selected_schedclass
                },
                success:function(data) {
                    if(data[0].status == 1){
                        scheduleclassification = data[0].data
                        $('#classification').empty()
                        $('#classification').append('<option value="">Schedule Classification</option>')
                        $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                        $("#classification").select2({
                                data: scheduleclassification,
                                allowClear: true,
                                placeholder: "Schedule Classification",
                        })
                        $('.edit_schedclass').attr('hidden','hidden')
                        $('.delete_schedclass').attr('hidden','hidden')
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }
        $(document).on('change','#classification',function(){
            if($(this).val() != "" && $(this).val() != "create"){
                $('.edit_schedclass').removeAttr('hidden')
                $('.delete_schedclass').removeAttr('hidden')
            }else{
                if($(this).val() == "create"){
                    selected_schedclass = null
                    $('#schedclass_desc').val("")
                    $('#classification').val("").change()
                    $('#create_schedclass_button').removeAttr('hidden')
                    $('#update_schedclass_button').attr('hidden','hidden')
                    $('#schedclass_form_modal').modal()
                }
                $('.edit_schedclass').attr('hidden','hidden')
                $('.delete_schedclass').attr('hidden','hidden')
            }
        })
        
        $(document).on('click','#create_schedclass_button',function(){
            create_schedclassification()
        })

        $(document).on('click','#update_schedclass_button',function(){
            update_schedclassification()
        })


        $(document).on('click','.delete_schedclass',function(){
            selected_schedclass = $('#classification').val()

            Swal.fire({
                    text: 'Are you sure you want to remove schedule classification?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
            }).then((result) => {
                    if (result.value) {
                        delete_schedclassification()
                    }
            })
        })

        $(document).on('click','.edit_schedclass',function(){
            $('#create_schedclass_button').attr('hidden','hidden')
            $('#update_schedclass_button').removeAttr('hidden')
            $('#schedclass_desc').val($('#classification option:selected').text())
            selected_schedclass = $('#classification').val()
            $('#schedclass_form_modal').modal()
        })
    </script>


    <script>
        $(document).ready(function(){
            var keysPressed = {};
            const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
            })
            document.addEventListener('keydown', (event) => {
                    keysPressed[event.key] = true;
                    if (keysPressed['p'] && event.key == 'v') {
                        Toast.fire({
                                    type: 'warning',
                                    title: 'Date Version: 07/27/2021 11:19'
                                })
                    }
            });
            document.addEventListener('keyup', (event) => {
                    delete keysPressed[event.key];
            });
        })
    </script>

    
@endsection

