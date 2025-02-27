@php
      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
            $extend = 'teacher.layouts.app';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
      }else if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
	}else if(Session::get('currentPortal') == 12){    
		$extend = 'adminITPortal.layouts.app';
	}
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
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
            .calendar-table{
            display: none;
        }

        .drp-buttons{
            display: none !important;
        }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get();
      $semester = DB::table('semester')->get();  
      $rooms = DB::table('rooms')->where('deleted',0)->select('id','roomname as text')->get();  
      $temp_teacherid = 0;
      $schedtimetemplate = DB::table('schedtimetemplate')->where('deleted',0)->select('id','description as text')->get();
      $acad = array();
      $nationality = DB::table('nationality')
                        ->select(
                              'id',
                              'nationality as text'
                        )
                        ->get();

      $civilstatus = DB::table('civilstatus')
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'civilstatus as text'
                        )
                        ->get();

      if(auth()->user()->type != 17){

            $temp_teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first();

            if(isset($temp_teacherid)){
                  $temp_teacherid = $temp_teacherid->id;
            }

            if(auth()->user()->type == 2 || Session::get('currentPortal') == 2 ){
                  $acad = DB::table('academicprogram')
                                    ->where('principalid',$temp_teacherid)
                                    ->select('id')
                                    ->get();
            }
      }else{
            $acad = DB::table('academicprogram')
                        ->select('id')
                        ->get();
      }

@endphp
    
<div class="modal fade" id="fas_info_modal" style="display: none; padding-right: 17px;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Personal Information</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0" style="font-size: .8rem! important">
                  <div class="row">
                      <div class="col-md-4">
                          <strong>First Name</strong>
                          <p class="text-muted" id="first_name">--</p>
                      </div>
                      <div class="col-md-3">
                          <strong>Middle Name</strong>
                          <p class="text-muted" id="middle_name">--</p>
                      </div>
                      <div class="col-md-4">
                          <strong>Last Name</strong>
                          <p class="text-muted" id="last_name">--</p>
                      </div>
                      <div class="col-md-1">
                          <strong>Suffix</strong>
                          <p class="text-muted" id="suffix">--</p>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 ">
                        <strong>Title</strong>
                        <p class="text-muted" id="title">--</p>
                    </div>
                    <div class="col-md-3 ">
                        <strong>Academic Title</strong>
                        <p class="text-muted" id="acadtitle">--</p>
                    </div>
                   
                 </div>
                  <div class="row">
                      <div class="col-md-4">
                          <strong>Date of birth</strong>
                          <p class="text-muted" id="dob">--</p>
                      </div>
                      <div class="col-md-3">
                          <strong><i class="fas fa-book mr-1"></i>Gender</strong>
                          <p class="text-muted" id="gender">--</p>
                      </div>
                      <div class="col-md-4">
                          <strong><i class="fas fa-book mr-1"></i>Nationality</strong>
                          <p class="text-muted" id="nationality">--</p>
                      </div>
                  </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong><i class="fas fa-book mr-1"></i>Marital Status</strong>
                            <p class="text-muted" id="maritalstatus">--</p>
                        </div>
                         <div class="col-md-3">
                          <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                          <p class="text-muted" id="contact_number">--</p>
                        </div>
                        <div class="col-md-4">
                          <strong><i class="fas fa-book mr-1"></i>Email Address</strong>
                          <p class="text-muted" id="email">--</p>
                        </div>
                    </div>
                    <div class="row">
                          <div class="col-md-6">
                                <strong><i class="fas fa-book mr-1"></i>Address</strong>
                                <p class="text-muted" id="address">--</p>
                          </div>
                    </div>
            </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
                  <div class="modal-header bg-primary p-1">
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <label for="">Subjects <i>( <span id="label_acad"></span> )</i></label>
                                    <select name="subject_list" id="subject_list" class="form-control select2"></select>
                              </div>
                        </div>
                       <div class="row">
                             <div class="col-md-12 table-responsive p-0" style="height: 500px;">
                                    <table class="table-sm table table-bordered" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="5%" class="text-center"> 
                                                            <input disabled="disabled" type="checkbox" class="sched_list_all" >
                                                      </th>
                                                      <th width="30%">Section</th>
                                                      <th width="15%"  class="text-center">Day</th>
                                                      <th width="10%" class="text-center">Time</th>
                                                      <th width="10%"  class="text-center">Room</th>
                                                      <th width="20%">Teacher</th>
                                                      <th width="10%"  class="text-center">Enrolled</th>
                                                </tr>
                                          </thead>
                                          <tbody class="sched_holder">

                                          </tbody>
                                    </table>
                                   
                             </div>
                       </div>
                       <div class="row mt-2">
                             <div class="col-md-6">
                                    <button class="btn btn-sm btn-primary" id="add_sched_button" disabled>Add Sched</button>
                             </div>
                             <div class="col-md-6">
                                    <button class="btn btn-danger btn-sm float-right" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                              </div>
                       </div>
                  </div>
            </div>
      </div>
</div>



<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Teacher Teaching Load</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Teacher Teaching Load</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<div class="modal fade" id="modal_schedule" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title">Schedule Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-md-12">
                    <label>Section</label>
                    <select data-placeholder="Select a Section"  name="input_section" id="input_section" class="form-control select2"   >
                    </select>
                </div>
              </div>
                   
              <div class="row">
                <div class="form-group col-md-12">
                    <label>Subject</label>
                    <select data-placeholder="Select a Subject"  name="input_subject" id="input_subject" class="form-control select2"   >
                    </select>
                </div>
              </div>
            <div class="row">
                  <div class="form-group col-md-12">
                        <label>Room</label>
                        <select data-placeholder="Select a Room"  name="input_room" id="input_room" class="form-control select2"   >
                        </select>
                  </div>
            </div>  
              <div class="row">
                  <div class="form-group col-md-12">
                      <label for="">Schedule Classification  <a href="javascript:void(0)" class="edit_schedclass pl-2" hidden><i class="far fa-edit"></i></a>
                          <a href="javascript:void(0)" class="delete_schedclass pl-2" hidden><i class="far fa-trash-alt text-danger"></i></a></label>
                          <select name="classification" id="classification" class="form-control select2"></select>
                  </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                    <label for="">Time</label>
                    <input type="text" class="form-control  form-control-sm reservationtime" name="time" id="time">
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
              <div class="row" id="sched_con_holder">
                  <div class="col-md-12">
                      <hr class="mb-2">
                  </div>
                  <div class="col-md-12">
                      <label for="">Schedule Conflict:</label>
                  </div>
                  <div class="col-md-12">
                      <hr class="mt-0">
                  </div>
                  <div class="col-md-12">
                      <strong>Conflict Status:</strong> <span id="con_stat"></span>
                  </div>
                  <div class="col-md-12">
                      <strong>Section:</strong> <span id="con_sect"></span>
                  </div>
                  <div class="col-md-12">
                      <strong>Subject:</strong> <span id="con_subj"></span>
                  </div>
                  <div class="col-md-12">
                      <strong>Day:</strong> <span id="con_day"></span>
                  </div>
                  <div class="col-md-12">
                      <strong>Time:</strong> <span id="con_time"></span>
                  </div>
              </div>
              <hr>
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

<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                          {{-- <span class="info-box-icon bg-primary"><i class="fas fa-search"></i></span> --}}
                          <div class="info-box-content">
                              <div class="row">
                                  
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">School Year</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sy">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">Semester</label>
                                          <select class="form-control  form-control-sm select2 " id="filter_sem">
                                                @foreach ($semester as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->semester}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-8 form-group mb-0">
                                          <label for="">Teacher</label>
                                          <select class="form-control select2 form-control-sm"" id="filter_teacher">
                                          </select>
                                    </div>
                                  
                              </div>
                              {{-- <div class="row">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div> --}}
                          </div>
                        </div>
                  </div>
            </div>
          
            <div class="row mt-3">
                  <div class="col-md-3">
                        <div class="card shadow">
                              <div class="card-body box-profile">
                                    <div class="text-center" id="image_holder">
                                    
                                    </div>
                                    <h3 class="profile-username text-center" id="label_name"></h3>
                                    <p></p>
                                    <ul class="list-group list-group-unbordered mb-3">
                                          <li class="list-group-item">
                                            <b>ID No.</b> <a class="float-right" id="label_id"></a>
                                          </li>
                                          <li class="list-group-item" id="print_sched_holder" hidden>
                                                <button disabled type="button" class="btn btn-sm btn-outline-primary btn-block" id="print_sched"><i class="fa fa-print mr-1"></i>Print Teacher Schedule</button>
                                          </li>
                                          <li class="list-group-item">
                                                <button disabled class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#more-info" data-widget="chat-pane-toggle" id="edit_more_info" style="font-size:.8rem !important">
                                                      <i class="fa fa-edit"></i> More Information
                                                    </button>
                                          </li>

                                          
                                          
                                    </ul>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-9">
                        <div class="card shadow">
                              {{-- <div class="card-header border-0  pb-0">
                                    <h3 class="card-title">Teaching Load</h3>
                                    <div class="card-tools">
                                          @foreach ($acad as $item)
                                                @if($item->id == 2)
                                                      <button class="btn btn-primary btn-sm modal_subject" data-acad="2" data-text="Pre-School" disabled>PS</button>
                                                @elseif($item->id == 3)
                                                      <button class="btn btn-primary btn-sm modal_subject" data-acad="3" data-text="Grade School" disabled>GS</button>
                                                @elseif($item->id == 4)
                                                      <button class="btn btn-primary btn-sm modal_subject" data-acad="4" data-text="High School" disabled>HS</button>
                                                @elseif($item->id == 5)
                                                      <button class="btn btn-primary btn-sm modal_subject" data-acad="5" data-text="Senior High School" disabled>SHS</button>
                                                @elseif($item->id == 6)
                                                      <button class="btn btn-primary btn-sm modal_subject" data-acad="6" data-text="College" disabled>College</button>
                                                @endif
                                          @endforeach
                                    </div>
                              </div> --}}
                              <div class="card-body pt-3">
                                    <div class="row">
                                          <div class="col-md-12"  style="font-size:11px !important">
                                                <table class="table table-sm table-bordered" id="subjectplot_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="15%">Section</th>
                                                                  <th width="45%">Subject</th>
                                                                  <th width="25%" class="text-center">Time & Day</th>
                                                                  <th width="10%" class="text-center">Enrolled</th>
                                                                  <th width="5%" class="text-center"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="schedule">
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row" id="basic_ed_sched_holder" hidden>
                              <div class="col-md-12">
                                <div class="card shadow">
                                  <div class="card-body p-2">
                                    <div class="row">
                                          <div class="col-md-6">
                                                <label for="">Basic Education Schedule</label>
                                          </div>
                                      
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2">
                                                <button class="btn btn-sm btn-primary add_sched" >Add Schedule</button>
                                          </div>
                                          <div class="col-md-3"></div>
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
                                      <div class="col-md-12 table-responsive tableFixHead" style="height: 400px;">
                                        <table class="table-sm table-bordered table table-head-fixed mb-0" id="sched_holder"  style="font-size:.7rem !important">
                                          
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>
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

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

      <script>
            $(document).on('click','#edit_more_info',function(){
                  get_teacherinfo()
                  $('#fas_info_modal').modal()
            })

            function get_teacherinfo(){
                $.ajax({
                    type:'GET',
                    data:{
                        id:$('#filter_teacher').val()
                    },
                    url:'/teacher/profile/get',
                    success:function(data) {
                        if(data.length != 0){
                              $('#first_name').text(data[0].firstname != null ? data[0].firstname : '--')
                              $('#middle_name').text(data[0].middlename  !=  null ? data[0].middlename : '--')
                              $('#last_name').text(data[0].lastname  !=  null ? data[0].lastname : '--')
                              $('#suffix').text(data[0].suffix  !=  null ? data[0].suffix : '--')
                              $('#dob').text(data[0].dob != null ? data[0].dob : '--')
                              $('#gender').text(data[0].gender  !=  null ? data[0].gender.toString().toUpperCase() : '--')
                              $('#nationality').text(data[0].nationality  !=  null ? data[0].nationality : '--')
                              $('#contact_number').text(data[0].contactnum  !=  null ? data[0].contactnum : '--')
                              $('#email').text(data[0].email  !=  null ? data[0].email : '--')
                              $('#address').text(data[0].address  !=  null ? data[0].address : '--')
                              $('#maritalstatus').text(data[0].maritalstatus  !=  null ? data[0].maritalstatus : '--')
                              $('#label_tid').text(data[0].tid)
                              $('#utype').text(data[0].utype  !=  null ? data[0].utype : '--')
                              $('#title').text(data[0].title  !=  null ? data[0].title : '--')
                              $('#acadtitle').text(data[0].acadtitle  !=  null ? data[0].acadtitle : '--')
                        }
                        // else{
                        //       $('#edit_dob').val("").change()
                        //       $('#edit_g').val("").change()
                        //       $('#edit_n').val("").change()
                        //       $('#edit_ms').val("").change()
                        //       $('#edit_mobile').val("")
                        //       $('#edit_email').val("")
                        //       $('#edit_ad').val("")
                        // }
                    },
                })
            }

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
            var usertype_session = @json(Session::get('currentPortal'));
            var selected_timetemp = null;

            if(usertype_session == 14 || usertype_session == 16){
                  $('#print_sched_holder').remove()
            }else{
                  $('#print_sched_holder').removeAttr('hidden','hidden')
            }

            if(usertype_session == 12){
                  $('.add_sched').remove();
                  $('#time_templatelist').remove();
            }
        
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
            var all_rooms = @json($rooms);
            var all_sections = []
            var all_subjects = []
            var iscreate = true
            var allowconflict = 0

            $('#input_room').empty()
            $('#input_room').append('<option value="">Select Room</option>')
            $("#input_room").select2({
                  data: all_rooms,
                  allowClear: true,
                  placeholder: "Select Room",
            })

            
            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })


      </script>

      <script>

            var all_timetemplate = @json($schedtimetemplate);
            display_time_template()

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

            function getsections(){
                  $.ajax({
                        type:'GET',
                        url:'/scheduling/teacher/getsections',
                        data:{
                              syid:$('#filter_sy').val()
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    all_sections = data[0].data
                                    $('#input_section').empty()
                                    $('#input_section').append('<option value="">Select Section</option>')
                                    $("#input_section").select2({
                                                data: all_sections,
                                                allowClear: true,
                                                placeholder: "Select Section",
                                    })
                              }else{

                              }
                        },
                  })
            }

            function getsubjects(sectionid){

                  var temp_levelid = all_sections.filter(x=>x.id == sectionid)[0].levelid

                  $.ajax({
                        type:'GET',
                        url:'/scheduling/teacher/getsubjects',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              sectionid:sectionid,
                              levelid:temp_levelid
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
            $(document).ready(function(){

                  getsections()

                  $(document).on('change','#secttea, #input_section , #input_subject, #classification, #reservationtime, #input_room',function(){
                        $('#sched_con_holder').attr('hidden','hidden')
                        allowconflict = 0
                        if(iscreate){
                              $('.eval').text('Create Schedule')
                        }else{
                              $('.eval').text('Update Schedule')
                        }
                  })

                  $(document).on('click','.day',function(){
                        $('#sched_con_holder').attr('hidden','hidden')
                        allowconflict = 0
                        if(iscreate){
                              $('.eval').text('Create Schedule')
                        }else{
                              $('.eval').text('Update Schedule')
                        }
                  })

                  $(document).on('change','#input_section',function(){
                        if($(this).val() == "" || $(this).val() == null){
                              all_subjects = []
                              $('#input_subject').empty()
                              $('#input_subject').append('<option value="">Select Subject</option>')
                              $("#input_subject").select2({
                                    data: all_subjects,
                                    allowClear: true,
                                    placeholder: "Select Subject",
                              })
                        }else{
                              var temp_sectionid = $(this).val()
                              var temp_sectioninfo = all_sections.filter(x=>x.id == temp_sectionid)
                              if(temp_sectioninfo.length > 0){
                                    $('#input_room').val(temp_sectioninfo[0].roomid).change()
                                    // $('#secttea').val(temp_sectioninfo[0].teacherid).change()
                              }
                              getsubjects(temp_sectionid)
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


                        if(selected_detailinfo[0].levelid == 14 || selected_detailinfo[0].levelid == 15){
                              copy_com = 0;
                        }else{
                              if($('#apptocon').prop('checked') == true){
                                    copy_com = 1;
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
                                                load_schedule()
                                                get_sched()
                                          }else{
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].message
                                                })
                                          }
                                          
                                    }
                              })
                        })

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

                  $(document).on('click','.edit_sched',function(){

                        var temp_sched_info = all_sched.filter(x=>x.detailid == $(this).attr('data-id'))
                        selected_detailinfo = temp_sched_info
                        $('#apptocon').prop('checked',false)

                        if($(this).attr('iscon') == 1){
                              $('#apptocon_holder').removeAttr('hidden')
                        
                        }else{
                              $('#apptocon_holder').attr('hidden','hidden')
                        }

                        $('.remove_sched').removeAttr('hidden')

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
                        $('.apptocon').prop('checked',false)

                        $('#sched_con_holder').attr('hidden','hidden')
                        $('#con_stat').text("")
                        $('#con_sect').text("")
                        $('#con_subj').text("")
                        $('#con_day').text("")
                        $('#con_time').text("")
                        allowconflict = 0
                        conflicts = []

                        $('#conflict_holder').empty()

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

                        $('#conflict_holder').empty()

                  })

                  $(document).on('click','.eval',function(){

                        var section = all_sections.filter(x=>x.id == $('#input_section').val())[0]

                        $('#modal-primary').modal('hide')
                        var days = [];
                        var valid_data = true

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
                                    if($('#apptocon').prop('checked') == true){
                                          copy_com = 1;
                                    }
                              }

                        $.ajax({
                              type:'GET',
                              url:temp_url,
                              data:{
                                    applycom:copy_com,
                                    section:$('#input_section').val(),
                                    t:$('#time').val(),
                                    s:$('#input_subject').val(),
                                    tea:$('#filter_teacher').val(),
                                    r:$('#input_room').val(),
                                    days:days,
                                    class:$('#classification').val(),
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    allowconflict:allowconflict,
                                    iscreate:iscreate,
                                    schedinfo:selected_detailinfo
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          if(iscreate){
                                                $('.eval').text('Create Schedule')
                                          }else{
                                                $('.eval').text('Update Schedule')
                                          }
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Successful!'
                                          })
                                          allowconflict = 0
                                          get_sched()
                                          load_schedule()
                                          $('#sched_con_holder').attr('hidden','hidden')
                                          $('#sched_conflict_holder').empty()
                                          $('#con_stat').text("")
                                          $('#con_sect').text("")
                                          $('#con_subj').text("")
                                          $('#con_day').text("")
                                          $('#con_time').text("")
                                    }else{
                                          if(data[0].data == 'conflict'){
                                                $('#sched_con_holder').removeAttr('hidden')
                                                $('#sched_conflict_holder').empty()
                                                $('#con_stat')[0].innerHTML = '<span class="text-danger">'+data[0].conflicttype+'</span>'
                                                $('#con_sect').text(data[0].section)
                                                $('#con_subj').text(' ( '+data[0].subjcode+' ) '+data[0].subjdesc)
                                                $('#con_day').text(data[0].day)
                                                $('#con_time').text(data[0].time)
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Conflict: '+ data[0].conflicttype
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
                 
            })
      </script>

      <script>
            $(document).ready(function(){

                  $('#filter_sy').select2()
                  $('#filter_sem').select2()

                  get_teachers()
                  var all_teacher = []

                  $(document).on('click','#print_sched',function(){
                        window.open('/principal/setup/section/print?schedtype=teacher&syid='+$('#filter_sy').val()+'&semid='+$('#filter_sem').val()+'&teacherid='+$('#filter_teacher').val()+'&timetemp='+$('#filter_timetemplate').val(), '_blank');
                  })

                  function get_teachers(){
                        $.ajax({
                              type:'GET',
                              url: '/teacher/profile/list',
                              data:{
                                    syid:$('#filter_sy').val()
                              },
                              success:function(data) {
                                    all_teacher = data
                                    $("#filter_teacher").empty()
                                    $("#filter_teacher").append('<option value="">Select Teacher</option>')
                                    $("#filter_teacher").val("")
                                    $('#filter_teacher').select2({
                                          allowClear: true,
                                          data: all_teacher,
                                          placeholder: "Select teacher",
                                    })
                              }
                        })

                        
                  }

                  $(document).on('change','#filter_teacher',function(){

                        get_sched()

                        var tid = $('#filter_teacher').val()
                        var temp_data = all_teacher.filter(x=>x.id == tid)
                        var onerror_url = @json(asset('dist/img/download.png'));

                        if( temp_data[0].picurl != null){
                              var picurl = temp_data[0].picurl.replace('jpg','png')+"?random="+new Date().getTime()
                              var image = '<img width="100%" src="/'+picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid" >'
                              $('#image_holder')[0].innerHTML = image
                        }else{
                              var onerror_url = @json(asset('dist/img/download.png'));
                              var picurl = @json(asset('dist/img/download.png'));
                              var image = '<img width="100%" src="/'+picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid" >'
                              $('#image_holder')[0].innerHTML = image
                        }
                       
                        $('#label_id').text(temp_data[0].tid)
                        $('#label_name').text(temp_data[0].fullname)

                        if($(this).val() == "" || $(this).val() == null){
                              $('#print_sched').attr('disabled','disabled')
                              $('#edit_more_info').attr('disabled','disabled')
                        }else{
                              $('#print_sched').removeAttr('disabled')
                              $('#edit_more_info').removeAttr('disabled')
                        }
                       
                        
                  })

            })
      </script>

  

      <script>

            var acad = @json($acad);
            var temp_teacher = @json($temp_teacherid);

            $(document).ready(function(){


                  var onerror_url = @json(asset('dist/img/download.png'));
                  var picurl = @json(asset('dist/img/download.png'));
                  var image = '<img width="100%" src="/'+picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid" >'

                  $('#image_holder')[0].innerHTML = image

                  // var all_sched = [];
                  // var all_subjects = []
                  var selected_acad = null
                 

                  $(document).on('change','#filter_sy , #filter_sem',function(){
                        get_sched()
                  })

                  $(document).on('change','#filter_timetemplate',function(){
                        get_sched()
                  })

                  $(document).on('change','#filter_teacher, #filter_sy , #filter_sem',function(){
                        if($('#filter_teacher').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No teacher selected!'
                              })
                              all_sched = []
                              load_gradesetup_datatable()
                              $('.modal_subject').attr('disabled','disabled')
                              return false
                        }
                        $('.modal_subject').removeAttr('disabled')
                        load_schedule()
                        // get_sched()
                  })



                  $(document).on('click','.modal_subject',function(){
                        var acad = $(this).attr('data-acad')
                        $('#label_acad').text($(this).attr('data-text'));
                        $('#subject_list').empty();
                        $('.sched_holder').empty()
                        selected_acad = acad
                        get_subjects(acad)
                        $('#modal_1').modal();
                  })

                  $(document).on('change','#subject_list',function(){
                        if($(this).val() == ""){
                              $('#add_sched_button').attr('disabled','disabled')
                        }else{
                              $('#add_sched_button').removeAttr('disabled')
                        }
                        get_subject_ched()
                  })

                  // $(document).on('change','#filter_sy',function(){
                  //       load_schedule()
                  // })

                  $(document).on('click','.sched_list_all',function(){
                        if($(this).prop('checked') == false){
                              $('.sched_list').each(function(){
                                    if($(this).attr('disabled') == undefined){
                                          $(this).prop('checked',false)
                                    }
                              })
                        }else{
                              $('.sched_list').each(function(){
                                    if($(this).attr('disabled') == undefined){
                                          $(this).prop('checked',true)
                                    }
                              })
                        }
                  })

                  $(document).on('click','#add_sched_button',function(){
                        add_sched_to_teacher()
                  })

                  $(document).on('click','.delete',function(){
                        var acad  = $(this).attr('data-acad')
                        var schedid  = $(this).attr('data-id')
                        remove_sched(acad,schedid)
                  })

                  load_gradesetup_datatable()

                  function remove_sched(acad,schedid){
                        $.ajax({
                              type:'GET',
                              url: '/scheduling/teacher/remove/sched',
                              data:{
                                    acad:acad,
                                    schedid:schedid
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'danger',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          all_sched_format2 = all_sched_format2.filter(x=>x.schedid != schedid)
                                          load_gradesetup_datatable()
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'danger',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }


                  function add_sched_to_teacher(){

                        var teacherid = $('#filter_teacher').val()
                        var schedule = []

                        $('.sched_list').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined){
                                    schedule.push($(this).attr('data-id'))
                              }
                        })

                        if(schedule.length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'No schedule selected.'
                              })
                              return false
                        }

                        $.ajax({
                              type:'GET',
                              url: '/scheduling/teacher/add/sched',
                              data:{
                                    teacherid:teacherid,
                                    schedule:schedule,
                                    acad:selected_acad,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'danger',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          $('.sched_list').each(function(){
                                                if($(this).prop('checked') == true && $(this).attr('disabled') == undefined){
                                                     $(this).attr('disabled','disabled')
                                                }
                                          })
                                          get_subject_ched()
                                          load_schedule()
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'danger',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  

                  function get_subject_ched(){
                        var syid = $("#filter_sy").val()
                        var semid = $("#filter_sem").val()
                        var subjid =  $("#subject_list").val()
                        var teacherid =  $("#filter_teacher").val()

                        if($('#subject_list').val() == ""){
                              $('.sched_list_all').attr('disabled','disabled')
                              $('.sched_holder').empty()
                              return false;
                        }

                        $.ajax({
                              type:'GET',
                              url: '/scheduling/teacher/subjects/sched',
                              data:{
                                    subjid:subjid,
                                    acad:selected_acad,
                                    syid:syid,
                                    semid:semid
                              },
                              success:function(data) {
                                    $('.sched_list_all').removeAttr('disabled')
                                    $('.sched_holder').empty()
                                    $('.sched_holder').append(data)
                                    $('.sched_list[data-tid="'+teacherid+'"]').attr('disabled','disabled')
                                    $('.sched_list[data-tid="'+teacherid+'"]').prop('checked',true)
                                    
                                    if($('.sched_list').length == 0){
                                          $('#add_sched_button').attr('disabled','disabled')
                                    }
                                   
                                    
                              }
                        })
                  }


                  function get_subjects(acad = null){
                        var syid = $("#filter_sy").val()
                        var semid = $("#filter_sem").val()

                        $.ajax({
                              type:'GET',
                              url: '/scheduling/teacher/subjects',
                              data:{
                                    acad:acad,
                                    syid:syid,
                                    semid:semid,
                              },
                              success:function(data) {
                                    all_subjects = data
                                    $("#subject_list").empty()
                                    $("#subject_list").append('<option value="">Select Subject</option>')
                                    $("#subject_list").val("")
                                    $("#subject_list").select2({
                                          data: all_subjects,
                                          allowClear: true,
                                          placeholder: "Select Subject",
                                    })
                              }
                        })
                  }

                  

                  

                  
            })

            function load_schedule(){

                  var teacherid = $('#filter_teacher').val()

                  if($('#filter_teacher').val() != ""){
                        $.ajax({
                              type:'GET',
                              url: '/scheduling/teacher/schedule',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    teacherid:teacherid
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No schedule found!'
                                          })
                                          all_sched_format2 = []
                                          load_gradesetup_datatable()
                                    }else{
                                          if(data[0].status == undefined){
                                                all_sched_format2 = data
                                                load_gradesetup_datatable()
                                          }else{
                                                all_sched_format2 = []
                                                load_gradesetup_datatable()
                                          }
                                    }
                              }
                        })
                  }else{
                        Toast.fire({
                              type: 'info',
                              title: 'No student selected!'
                        })
                        all_sched_format2 = [];
                        load_gradesetup_datatable()
                  }

            }

            function load_gradesetup_datatable(){

                  $("#subjectplot_table").DataTable({
                        destroy: true,
                        data:all_sched_format2,
                        pageLength: 10,
                        lengthChange: false,
                        columns: [
                                    { "data": "sortid" },
                                    { "data": "search" },
                                    { "data": null },
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
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var table = 'table-borderless'
                                                var multiple = ''

                                                if(rowData.schedule.length > 1){
                                                      table = 'table-bordered'
                                                      multiple = 'no-border-col'
                                                }

                                                var text = '<table class="table table-sm mb-0 '+table+'">'
                                                $.each(rowData.schedule,function(a,b){
                                                      // text += '<tr style="background-color:transparent !important"><td width="50%" class="'+multiple+'">'+b.start + ' - ' + b.end + '</td><td width="50%">'+b.day +'</td></tr>'
                                                      text += '<tr style="background-color:transparent !important"><td width="50%" class="'+multiple+'">'+b.start + ' - ' + b.end + '<p class="text-muted mb-0" style="font-size:.7rem">'+b.day+'</p></td></tr>'
                                                })
                                                text += '</table>'
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('p-0')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = rowData.enrolled
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var get_acad = acad.filter(x=>x.id == rowData.acadprogid)
                                                var buttons = ''
                                                if(get_acad.length > 0){
                                                      var buttons = '<a href="javascript:void(0)" class="delete" data-id="'+rowData.schedid+'" data-acad="'+rowData.acadprogid+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                }
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },

                              ]
                        
                  });
            }
      </script>

      <script>
            $(document).on('click','.add_sched',function(){

                  selected_detailinfo = null
                  $('#apptocon').prop('checked',false)
                  if($(this).attr('iscon') == 1){
                  $('#apptocon_holder').removeAttr('hidden')
                  }else{
                  $('#apptocon_holder').attr('hidden','hidden')
                  }

                  $('.remove_sched').attr('hidden','hidden')
                

                  iscreate = true
                  $('.eval').addClass('btn-primary')
                  $('.eval').removeClass('btn-success')
                  $('.eval').text('Create Schedule')
                  $('.eval').removeClass('.evalupdate')
                  $('.apptocon').prop('checked',false)

                  $('#sched_con_holder').attr('hidden','hidden')
                  $('#con_stat').text("")
                  $('#con_sect').text("")
                  $('#con_subj').text("")
                  $('#con_day').text("")
                  $('#con_time').text("")
                  allowconflict = 0
                  conflicts = []
                  $('#conflict_holder').empty()
                  $('.evalupdate').addClass('eval')
                  $('.eval').removeClass('.evalupdate')
                  $('#modal_schedule').modal()
                  $('#secttea').val("").change()
                  $('#input_subject').val("").change()
                  $('#input_section').val("").change()
                  
                  $('#sectroo').val("").change()
                  $('.day').prop('checked',false)
                  $('.eval').removeAttr('disabled')


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

                  var temp_classid = $('#classification option:eq(2)').val()
                  $('#classification').val(temp_classid).change()

                  // add_sched
                  $('#time').removeAttr('disabled')
                  selectedSubject = $(this).attr('data-id')
                  $('#conflict_holder').empty()
            })

      </script>
      <script>

            var all_sched = []
            var all_sched_format2 = []
      
            get_sched()

            function get_sched(){

                  $('#basic_ed_sched_holder').attr('hidden','hidden')

                  if($('#filter_teacher').val() == "" || $('#filter_teacher').val() == null){
                        return false
                  }else{
                        if(usertype_session != 14 && usertype_session != 16){
                              $('#basic_ed_sched_holder').removeAttr('hidden')
                        }
                  }

            $('#sched_holder').empty()
                  $.ajax({
                        type:'GET',
                        url: '/principal/setup/schedule/get/sched',
                        data:{
                              teacherid:$('#filter_teacher').val(),
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              schedtype:'teacher',
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

                              if(usertype_session == 12){
                                    $('.add_sched').remove();
                                    $('.edit_sched').removeClass('edit_sched');
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
      //schedule classification
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
@endsection


