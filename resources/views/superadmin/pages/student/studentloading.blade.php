@php
      if(Session::get('currentPortal') == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }else if(Session::get('currentPortal') == 14){
            $extend = 'deanportal.layouts.app2';
      }else if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }else if(auth()->user()->type == 14){
            $extend = 'deanportal.layouts.app2';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 ){
            $extend = 'registrar.layouts.app';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
            select[name=students_male_length]{
                  height: calc(1.7em + 2px) !important;
            }
            select[name=students_female_length]{
                  height: calc(1.7em + 2px) !important;
            }
            .custom-select-sm {
                  padding-top: 0.1rem;
            }
            .page-link {
                  line-height: .6;
                  font-size: .7rem !important;
            }
            div.dataTables_wrapper div.dataTables_info {
                  padding-top: 0.4em;
                  white-space: nowrap;
                  font-size: .7rem !important;
            }
            .dropped{
                  text-decoration: line-through!important;
            }

            
            .tooltip > .arrow {
                  visibility: hidden;
            }

            .modal {
                  overflow-y:auto;
            } 
            /* .table td , .table th{
                  padding: 1px 5px !important
            } */
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $semester = DB::table('semester')->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->where('acadprogid',6)->orderBy('sortid')->get(); 

      // if(auth()->user()->type == 16 || Session::get('currentPortal') == 16){

      //       $teacher = DB::table('teacher')
      //                         ->where('tid',auth()->user()->email)
      //                         ->first();

      //       $colleges = DB::table('college_courses')
      //                   ->join('college_colleges',function($join){
      //                         $join->on('college_courses.collegeid','=','college_colleges.id');
      //                         $join->where('college_colleges.deleted',0);
      //                   })
      //                   ->where('courseChairman',$teacher->id)
      //                   ->where('college_courses.deleted',0)
      //                   ->select('college_colleges.*')
      //                   ->get();

      // }else if(auth()->user()->type == 14  || Session::get('currentPortal') == 14){

      //       $teacher = DB::table('teacher')
      //                         ->where('tid',auth()->user()->email)
      //                         ->first();

      //       $colleges = DB::table('college_colleges')
      //                         ->where('dean',$teacher->id)
      //                         ->where('college_colleges.deleted',0)
      //                         ->select('college_colleges.*')
      //                         ->get();

      // }else{
      //       $colleges = DB::table('college_colleges')->where('deleted',0)->get();
      // }

      $colleges = DB::table('college_colleges')->where('cisactive',1)->where('deleted',0)->get();
      $college_gradelevel = DB::table('gradelevel')->where('acadprogid',6)->where('deleted',0)->get();
@endphp




<div class="modal fade" id="student_evaluation_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Grade Evaluation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table-sm table table-bordered" style="font-size:.7rem !important">
                                          <tr>
                                                <td colspan="2"><b>Student Name:</b> <span id="eval_studentname_label"></span><button class="btn btn-default btn-sm float-right" style="font-size:.7rem !important" id="print_student_evaluation"><i class="fa fa-print"></i> Print</button></td>
                                          </tr>
                                          <tr>
                                                <td width="50%"><b>Course:</b> <span id="eval_course_label"></span></td>
                                                <td width="50%"><b>Curriculum:</b> <span id="eval_curriculum_label"></span></td>
                                          </tr>
                                    </table>      
                              </div> 
                       </div>
                       <div id="student_evaluation_holder">

                       </div>
                  </div>
            </div>
      </div>
</div> 




<div class="modal fade" id="studentloading_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-2 form-group">
                                    <label for="">Sections</label>
                                    <select name="filter_section" id="filter_section" class="form-control select2"></select>
                              </div>
                              <div class="col-md-5 form-group">
                                    <label for="">Subjects</label>
                                    <select name="filter_subjects" id="filter_subjects" class="form-control select2"></select>
                              </div>
                              <div class="col-md-5 form-group">
                                    <label for="">All Subjects</label>
                                    <select name="filter_all_subjects" id="filter_all_subjects" class="form-control select2"></select>
                              </div>
                        </div>
                        <div class="row">
                             <div class="col-md-12">
                                    <table class="table table-bordered smfont  table-sm">
                                          <thead>
                                          <tr>
                                                <th class="text-center align-middle" width="5%"></th>
                                                <th class="align-middle" width="10%">Section</th>
                                                <th class="text-center align-middle" width="35%">Subject</th>
                                                <th class="text-center align-middle" width="5%">Units</th>
                                                <th class="text-center align-middle" width="10%">Day</th>
                                                <th class="text-center align-middle" width="7%">Time</th>
                                                <th class="text-center align-middle" width="8%" >Room</th>
                                                <th class="text-center align-middle" width="15%">Teacher</th>
                                                <th class="text-center align-middle" width="5%">Enrolled</th>
                                          </tr>
                                          </thead>
                                          <tbody class="schedule" id="sched_plot_holder">
                                                <tr>
                                                      <td colspan="8" class="text-center align-middle">NO AVAILABLE SCHEDULE</td>
                                                </tr>
                                          </tbody>
                                    </table>
                             </div>
                        </div>
                        <div class="row mt-2">
                              <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" id="button_add_all" disabled><i class="fas fa-plus"></i> Add All Subject</button>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                              </div>
                        </div>
                  </div>
                 
            </div>
      </div>
</div> 


{{-- <div class="modal fade" id="available_sched_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-2">
                                    <label for="">Sections</label>
                                    <select class="form-control form-control-sm" id="filter_sched_section">

                                    </select>
                              </div>
                              <div class="col-md-2">
                                    <label for="">Subject Code</label>
                                    <select class="form-control form-control-sm" id="filter_sched_subjcode">

                                    </select>
                              </div>
                              <div class="col-md-2">
                                    <label for="">Subject Description</label>
                                    <select class="form-control form-control-sm" id="filter_sched_subjdesc">

                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="available_sched_datatable" width="100%" style="font-size:.9rem !important">
                                          <thead>
                                                <tr>
                                                      <th width="8%"></th>
                                                      <th width="10%">Section</th>
                                                      <th width="8%">Subj. Code</th>
                                                      <th width="24%">Subject Description</th>
                                                      <th class="text-center" width="5%" colspan="2">Units</th>
                                                      <th class="text-center p-0 align-middle" width="5%">Cap.</th>
                                                      <th class="text-center" width="6%">Enrolled</th>
                                                      <th width="20%"></th>
                                                      <th width="14%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                        
                  </div>
            </div>
      </div>
</div>  --}}


<div class="modal fade" id="preenrolled_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Pre-enrolled List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                             <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm" id="datatable_preenrolled" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="50%">Student</th>
                                                      <th width="20%">Course / Year Level</th>
                                                      <th width="20%">Pre-enrollment date</th>
                                                      <th width="10%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                             </div>
                        </div>
                  </div>
                 
            </div>
      </div>
</div>

<div class="modal fade" id="updatestudentcourse_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12">
                                    <h5>Update Student Course</h5>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Course</label>
                                    <select name="input_course" id="input_course" class="form-control select2"></select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Curriculum</label>
                                    <select name="input_curriculum" id="input_curriculum" class="form-control select2"></select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Grade Level</label>
                                    <select name="input_level" id="input_level" class="form-control select2">
                                          @foreach($college_gradelevel as $item)
                                                <option value="{{$item->id}}">{{$item->levelname}}</option>
                                          @endforeach
                                    </select>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" id="update_coures">Update</button>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-danger btn-sm" id="close_updatestudentcourse_modal">Cancel</button>
                              </div>
                        </div>
                  </div>
                 
            </div>
      </div>
</div>  



<div class="modal fade" id="remove_sched_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 text-center">
                                    <h4>Student is already Enrolled!</h4>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-12">
                                    <label class="mb-0">Subject Code</label> : <span class="subj_desc_holder_code"></span>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <label class="mb-0">Subject Title</label> : <span class="subj_desc_holder_title"></span>
                              </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                              <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm btn-block remove_subject" data-id="dropped"><i class="fas fa-ban"></i> Drop Subject</button>
                              </div>
                              <div class="col-md-6">
                                    <button class="btn btn-danger btn-sm  btn-block remove_subject" data-id="delete"><i class="far fa-trash-alt"></i> Delete Subject</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="add_sched_modal" style="display: none; z-index:1500" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 text-center">
                                    <h4>Student is already Enrolled!</h4>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-12">
                                    <label class="mb-0">Subject Code</label> : <span class="subj_desc_holder_code"></span>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <label  class="mb-0">Subject Title</label> : <span class="subj_desc_holder_title"></span>
                              </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                              <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm btn-block add_subject" style="font-size:.8rem !important" data-status="regular">Regular Subject</button>
                              </div>
                              <div class="col-md-6">
                                    <button class="btn btn-danger btn-sm  btn-block add_subject" style="font-size:.8rem !important" data-status="additional">Additional Subject</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>


<div class="modal fade" id="sched_con_modal" style="display: none; z-index:1051" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.3rem !important">Schedule Conflict</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row" id="sched_con_holder">
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
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary btn-block add_subject" id="conflict_addsched_button">Conflict : Add Sched</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

{{-- <div class="modal fade" id="enrolled_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Student List <span id="student_list_type"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12">
                                    <label for="" id="list_label"></label>
                              </div>
                        </div>
                      <div class="row">
                            <div class="col-md-12" style="font-size:.7rem !important">
                              <table class="table table-striped table-sm table-bordered table-head-fixed p-0" id="student_list" width="100%"  >
                                        <thead>
                                              <tr>
                                                    <th width="50%">Students</th>
                                                    <th width="25%">Grade Level</th>
                                                    <th width="25%">Course</th>
                                              </tr>
                                        </thead>
                                  </table>
                            </div>
                      </div>
                  </div>
            </div>
      </div>
</div>    --}}

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Loading</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Loading</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <!-- <div class="row">
                  <div class="col-md-3">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label for="">Student</label>
                                          <select class="form-control select2" id="filter_student">
                                               <option value="">Select a student</option>
                                          </select>
                                    </div>
                                  
                              </div>
                              <div class="row mt-3">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div> -->
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-2" >
                                    <div class="row" style="font-size:.9rem !important">
                                          <div class="col-md-2">
                                                <label for="" class="mb-1">School Year</label>
                                                <select name="filter_sy" id="filter_sy" class="form-control form-control-sm select2">
                                                      @foreach($sy as $item)
                                                            <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected="selected"' : '' }}>{{$item->sydesc}}</option>
                                                      @endforeach
                                                </select>
                                          </div>
                                          <div class="col-md-2">
                                                <label for="" class="mb-1">Semester</label>
                                                <select name="filter_semester" id="filter_semester" class="form-control form-control-sm select2">
                                                      @foreach($semester as $item)
                                                            <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected="selected"' : '' }}>{{$item->semester}}</option>
                                                      @endforeach
                                                </select>
                                          </div>
                                          <div class="col-md-4" hidden>
                                                <label for="">Grade Level</label>
                                                <select name="filter_gradelevel" id="filter_gradelevel" class="form-control form-control-sm">
                                                      <option value="" >Select Grade Level</option>
                                                      @foreach($gradelevel as $item)
                                                            <option value="{{$item->id}}" >{{$item->levelname}}</option>
                                                      @endforeach
                                                </select>
                                          </div>
                                          <div class="col-md-8 pt-1">
                                                {{-- <div class="card shadow">
                                                      <div class="card-body p-2" style="font-size:.9rem !important"> --}}
                                                            <div class="input-group-prepend float-right mt-2">
                                                                  <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                                                    Enrollment List
                                                                  </button>
                                                                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);" id="enrollment_list_holder">
                                                                  </div>
                                                            </div>
                                                         
                                                      {{-- </div>
                                                </div> --}}
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" id="no_course_holder" hidden>
                  <div class="col-md-12">
                        <div class="card shadow bg-danger">
                              <div class="card-body p-1">
                                    You are not assigned to a course or college.
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" id="enrollment_setup_holder" hidden>
                  <div class="col-md-12">
                        <div class="card shadow bg-default">
                              <div class="card-body p-1" id="enrollment_setup_holder_desc">
                                    
                              </div>
                        </div>
                  </div>
            </div>
            {{-- <div class="row" >
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-2" style="font-size:.9rem !important">
                                    <div class="input-group-prepend">
                                          <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                            Enrollment List
                                          </button>
                                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);" id="enrollment_list_holder">
                                          </div>
                                    </div>
                                 
                              </div>
                        </div>
                  </div>
            </div> --}}
          
            <div class="row">
                  <div class="col-md-3">
                        <div class="card shadow">
                              <div class="card-body p-0">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm btn-block" id="pre_enrolled_button">Pre-Enrolled <span id="pre_enrollment_count"></span></button>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="info-box shadow-lg  p-2">
                          <div class="info-box-content p-0">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label for="" class="mb-1">Student</label>
                                          <select class="form-control select2" id="filter_student">
                                               <option value="">Select a student</option>
                                          </select>
                                    </div>
                              </div>
                              {{-- <div class="row mt-3">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div> --}}
                          </div>
                        </div>
                       

                        <div class="card shadow">
                              <div class="card-body box-profile">
                                    <h3 class="profile-username text-center" id="student_fullname">Student Name</h3>
                                    <p class="text-muted text-center" id="cur_glevel">Grade Level</p>
                              </div>
                        </div>

                        <div class="card shadow">
                              <div class="card-body" style="font-size:12px !important">
                                    <strong><i class="fas fa-book mr-1"></i> Enrollment Status</strong>
                                    <p class="text-muted" id="label_studstat">
                                         --
                                    </p>
                                    <strong><i class="fas fa-book mr-1"></i> SID</strong>
                                    <p class="text-muted" id="label_sid">
                                         --
                                    </p>
                                    <strong><i class="fas fa-book mr-1"></i> Course</strong>
                                    <p class="text-muted" id="label_course">
                                         --
                                    </p>
                                    <strong><i class="fas fa-book mr-1"></i> Curriculum</strong>
                                    <p class="text-muted" id="label_curriculum">
                                         --
                                    </p>
                                    <strong><i class="fas fa-book mr-1"></i> College</strong>
                                    <p class="text-muted" id="label_college">
                                         --
                                    </p>
                                    <strong><i class="fas fa-book mr-1"></i> Contact Information</strong>
                                    <p class="text-muted" id="label_contact">
                                         --
                                    </p>
                                    <strong><i class="fas fa-book mr-1"></i> Incase of Emergency</strong>
                                    <p class="text-muted" id="label_ic_contact">
                                         --
                                    </p>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-9">
                        
                             
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-4">
                                                <h5>Loaded Subjects</h5>
                                          </div>
                                          <div class="col-md-8 text-right">
                                                <button class="btn btn-primary btn-sm" id="student_evaluation" disabled><i class="fas fa-file-pdf" hidden></i> Student Evaluation</button>
                                                <button class="btn btn-secondary btn-sm"  disabled hidden><i class="fas fa-file-pdf"></i> Cor</button>
                                                <button class="btn btn-danger btn-sm" id="unload_all_subj" disabled hidden><i class="fas fa-ban"></i> Unload All Subjects</button>
                                                <button class="btn btn-primary btn-sm" id="button_to_studentloading_modal" disabled><i class="fas fa-plus"></i> Add Subjects</button>
                                          </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-12 text-danger"><i>Please click the section name to indicate the student section.</i></div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table table-bordered table-sm" style="font-size:12px !important">
                                                      <thead>
                                                            <tr>
                                                                  <th class="align-middle" width="10%">Section</th>
                                                                  <th class="text-center align-middle" width="40%">Subject</th>
                                                                  <th class="text-center align-middle" width="5%">Units</th>
                                                                  <th class="text-center align-middle" width="10%">Day</th>
                                                                  <th class="text-center align-middle" width="10%">Time</th>
                                                                  <th class="text-center align-middle" width="10%" >Room</th>
                                                                  <th class="text-center align-middle" width="15%">Teacher</th>
                                                                  <th class="text-center align-middle" width="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody class="schedule" id="studentsched_plot_holder">
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

      <script>
             const Toast = Swal.mixin({
                  toast: true,
                  position: 'top',
                  showConfirmButton: false,
                  showCloseButton: true,
                  timer: 5000,
            })

            $('#filter_sy').select2()
            $('#filter_semester').select2()
      </script>
      
      <script>
            var routes = {
                  'cllgschdgrpList': '{{route('cllgschdgrpList')}}' ,
                  'cllgschdgrpSelect': '{{route('cllgschdgrpSelect')}}' ,
                  'cllgschdgrpDatatable': '{{route('cllgschdgrpDatatable')}}' ,
                  'cllgschdgrpCreate': '{{route('cllgschdgrpCreate')}}' ,
                  'cllgschdgrpUpdate': '{{route('cllgschdgrpUpdate')}}' , 
                  'cllgschdgrpDelete': '{{route('cllgschdgrpDelete')}}' ,
            }

            var currentPortal = @json(Session::get('currentPortal'))

      </script>


      

      @include('superadmin.pages.college.js.college-subjsched')
      @include('superadmin.pages.college.js.college-schedlist')
      @include('superadmin.pages.college.js.college-schedgroup')

      <script>
            $(document).ready(function(){
                  schdgrpLoadResources($('#filter_sy').val(),$('#filter_semester').val())
            })
      </script>

      <script>
            getActiveEnrollmentSetup()
            var entype = null

            function getActiveEnrollmentSetup(){
                  $.ajax({
                        type:'GET',
                        url:'/student/loading/getActiveEnrollmentSetup',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                        },
                        success:function(data) {
                              $('#enrollment_setup_holder').removeAttr('hidden')
                              if(data.length > 0){
                                    entype = data[0].collegeentypedes.toUpperCase()
                                    $('#enrollment_setup_holder_desc')[0].innerHTML = '<b>Enrollment Setup </b>: '+data[0].collegeentypedes+'<b class="ml-5">Enrollment Period </b>: '+data[0].enrollmentstartdesc+' - '+data[0].enrollmentenddesc
                              }else{
                                    $('#enrollment_setup_holder_desc')[0].innerHTML = '<b>No Enrollment Setup</b>'
                              }
                        }
                  })
            }
      </script>

      
      <script>

           

            $(document).on('click','#schedgroup_to_modal',function(){
                  schedgroup_datatable()
            })

            $(document).ready(function(){

                  $(document).on('click','#button_to_studentloading_modal',function(){
                        console.log('studentloading');
                        load_csl_resource()
                        display_sched_csl('studentloading',stud_id,entype)
                        $('#cap_holder').removeAttr('hidden','hidden')
                        $('#createsection_input_capacity').val(50)
                        $('#available_sched_modal').modal()
                  })

                  // $('.select2').select2()

                  $(document).on('click','.view_enrollment_list',function(){
                        var temp_college = $(this).attr('data-id')
                        var syid = $('#filter_sy').val()
                        var semid =$('#filter_semester').val()
                        window.open('/student/loading/report/enrollment?college='+temp_college+'&syid='+syid+'&semid='+semid , '_blank');
                  })

                  
                 

                  var all_students = []
                  var all_sections = []
                  var stud_course = null
                  var stud_id = null
                  var section = null
                  var student_info = []
                  var all_preenrolled = []

                  $(document).on('change','#filter_semester , #filter_sy',function(){
                        $('#no_course_holder').attr('hidden','hidden')
                        $('#button_to_studentloading_modal').attr('disabled','disabled')
                        $('#student_evaluation').attr('disabled','disabled')
                        $("#filter_student").empty()
                        get_course()
                        get_preenrolled()
                        get_all_sched()
                        load_all_student()
                        getActiveEnrollmentSetup()
                        // if($('#filter_student').val() != null && $('#filter_student').val() != ""){
                        //       check_enrollment()
                           
                        // }
                  })

                  $(document).on('click','#pre_enrolled_button',function(){
                        $('#preenrolled_modal').modal()
                  })

                  $(document).on('click','.select_student_from_preenrolled',function(){
                        $('#filter_student').val($(this).attr('data-id')).change()
                        $('#preenrolled_modal').modal('hide')
                  })

                  get_preenrolled()

                  function get_preenrolled(){
                        
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/report/preenrolled',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    $('#pre_enrollment_count').text('('+data.length+')')
                                    all_preenrolled = data
                                    datatable_preenrollment()
                              }
                        })
            
                  }

                  var curriculum = null

                  function check_enrollment(){
                      
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/enrollment',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    studid:$('#filter_student').val(),
                              },
                              success:function(data) {
                                    
                                    var studid = $('#filter_student').val()
                                    student_info = all_students.filter(x=>x.id == studid)
                                    var student_index = all_students.findIndex(x=>x.id == studid)
                                    all_students[student_index].studstatus = data[0].studstatus;
                                    student_info[0].studstatus = data[0].studstatus

                                    all_students[student_index].description = data[0].description;
                                    student_info[0].description = data[0].description


                                
                                    if(data[0].curriculum != null){
                                          $('#updatestudentcourse_modal').modal('hide');
                                          curriculum = [data[0].curriculum]
                                    }else{
                                          $('#input_course').val(all_students[student_index].courseid).change()
                                          $('#input_level').val(all_students[student_index].levelid).change()
                                          $('#updatestudentcourse_modal').modal();
                                          curriculum = [{
                                                'id':null,
                                                'curriculum':null
                                          }]
                                    }
                                    
                                    load_student_enrollment_info()
                              }
                        })
                 

                  }

                  function datatable_preenrollment(){

                        $("#datatable_preenrolled").DataTable({
                              destroy: true,
                              data:all_preenrolled,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                                    { "data": null },
                                    { "data": "preenrollmentdatetime" },
                                    { "data": null },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          //'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td)[0].innerHTML = '<a href="javascript:void(0)" class="select_student_from_preenrolled" data-id="'+rowData.id+'">'+rowData.student+'</a>'
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          //'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')

                                                var year_level = null;
                                                if(rowData.levelid == 17){
                                                      year_level = 1
                                                }else if(rowData.levelid == 18){
                                                      year_level = 2
                                                }else if(rowData.levelid == 19){
                                                      year_level = 3
                                                }else if(rowData.levelid == 20){
                                                      year_level = 4
                                                }

                                                $(td).text(rowData.courseabrv+' - '+year_level)
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                if(rowData.with_sched){
                                                      $(td)[0].innerHTML = '<span class="badge badge-success">With Sched</span>'
                                                }else{
                                                      $(td).text(null)
                                                }
                                               
                                          }
                                    },
                                    
                              ]
                        });

                  }
                  

                  $(document).on('change','#filter_section',function(){
                        var temp_section = $(this).val()
                        if(temp_section == ""){
                              $('#sched_plot_holder').empty()
                              $('#button_add_all').attr('disabled','disabled')
                              return false
                        }

                        $('#filter_subjects').val("").change()
                        $('#filter_all_subjects').val("").change()

                        $.ajax({
                              type:'GET',
                              url:'/student/loading/availablesched',
                              data:{
                                    sectionid:temp_section,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    $('#sched_plot_holder').empty()
                                    $('#sched_plot_holder').append(data)

                                    $('.remove_schedule').each(function(a,b){
                                          $('.add_sched[data-id="'+$(b).attr('data-id')+'"]').remove()
                                    })
                                    

                                    if($('.add_sched').length > 0){
                                          $('#button_add_all').removeAttr('disabled')
                                    }
                              }
                        })
                  })

                  $(document).on('change','#filter_subjects , #filter_all_subjects',function(){
                        var temp_subject = $(this).val()

                        if(temp_subject == ""){
                              $('#sched_plot_holder').empty()
                              return false
                        }

                        $('#filter_section').val("").change()

                        $.ajax({
                              type:'GET',
                              url:'/student/loading/availablesched',
                              data:{
                                    subjid:temp_subject,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    $('#sched_plot_holder').empty()
                                    $('#sched_plot_holder').append(data)
                              }
                        })
                  })

                  load_all_student()

                  var userinfo = @json(auth()->user()->type)
                  

                  //add all schedule
                  $(document).on('click','#button_add_all',function(){

                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/schedule/add/all',
                              data:{
                                    studid:stud_id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    sectionid:$('#filter_section').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          student_sched()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong.'
                                    })
                              }
                        })


                  })

                  $(document).on('click','#button_to_updatestudentcourse_modal',function(){

                        var check_sched = $('.stud_sect').length


                        $('#input_course').val(student_info[0].courseid).change()
                        $('#input_curriculum').val(curriculum[0].id).change()

                        if(student_info[0].levelid == 15){
                              $('#input_level').val(17).change();
                        }else{
                              $('#input_level').val(student_info[0].levelid).change();
                        }
                      

                        if(userinfo != 17){
                              // if(student_info[0].studstatus == 1){
                              //       Toast.fire({
                              //             type: 'error',
                              //             title: 'Student is already enrolled.'
                              //       })
                              //       return false
                              // }else if(check_sched > 0){
                              //       Toast.fire({
                              //             type: 'error',
                              //             title: 'Please remove learners schedule'
                              //       })
                              //       return false
                              // }
                        }
                        
                        $('#updatestudentcourse_modal').modal()

                  })

                  $(document).on('click','#unload_all_subj',function(){

                        // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                        //       Swal.fire({
                        //             title: 'Are you sure?',
                        //             html: "You. <br>This subject will be marked as DROPPED!",
                        //             type: 'warning',
                        //             showCancelButton: true,
                        //             confirmButtonColor: '#3085d6',
                        //             cancelButtonColor: '#d33',
                        //             confirmButtonText: 'Proceed!'
                        //       })
                        //       .then((result) => {
                        //             if (result.value) {
                        //                   unload_subjects()
                        //             }
                        //       })
                        // }else{

                              Swal.fire({
                                    title: 'Are you sure?',
                                    html: "You are about to remove all <br>the students schedule!",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Proceed!'
                              })
                              .then((result) => {
                                    if (result.value) {
                                          unload_subjects()
                                    }
                              })
                        // }

                  })

                  function unload_subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/schedule/unload/all',
                              data:{
                                    studid:stud_id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    status:'delete'
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          student_sched()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong.'
                                    })
                              }
                        })

                  }


                  $(document).on('change','#filter_student',function(){

                        // load_student_enrollment_info()

                        if($(this).val() == ""){
                              $('#label_studstat').text("--")
                              $('#student_fullname').text("--")
                              $('#cur_glevel').text("--")
                              $('#cur_glevel').text("--")
                              $('#label_course').text("--")
                              $('#label_contact').text("--")
                              $('#label_ic_contact').text("--")
                              $('#label_curriculum').text("--")
                              $('#label_college').text("--")
                              $('#label_sid').text("--")
                              $('#sched_plot_holder').empty()
                        }else{
                              check_enrollment()
                        }
                  })

                  function load_student_enrollment_info(){
                        $('#button_to_studentloading_modal').removeAttr('disabled')
                        $('#student_evaluation').removeAttr('disabled')

                        var temp_id = $('#filter_student').val()

                        student_info = all_students.filter(x=>x.id == temp_id)

                        // if(student_info.length == 0){
                        //       Toast.fire({
                        //             type: 'error',
                        //             title: 'Student not found.'
                        //       })
                        // }else{
                        //       Toast.fire({
                        //             type: 'success',
                        //             title: 'Student found.'
                        //       })
                        // }

                        $('#label_studstat').text("--")
                        $('#student_fullname').text("--")
                        $('#cur_glevel').text("--")
                        $('#cur_glevel').text("--")
                        $('#label_course').text("--")
                        $('#label_contact').text("--")
                        $('#label_ic_contact').text("--")
                        $('#label_curriculum').text("--")
                        $('#label_college').text("--")
                        $('#label_sid').text("--")
                        $('#sched_plot_holder').empty()

                        $('#filter_gradelevel').val(student_info[0].levelid).change()

                        stud_course = student_info[0].courseid
                        stud_id = student_info[0].id
                        section = student_info[0].sectionid

                        if(stud_course == null || stud_course == 0){
                              $('#updatestudentcourse_modal').modal()
                        }
                        
                        student_sched()
                        // sections()
                        // subjects()
                        // all_college_subjects()
                  }

                  var all_subjects
                  var all_college_subjects_list = []
                  function subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/subjects',
                              data:{
                                    courseid:stud_course,
                                    curriculum:curriculum[0].id
                              },
                              success:function(data) {
                                    all_subjects = data;
                                    $("#filter_subjects").empty()
                                    $("#filter_subjects").append('<option value="">Select Subject</option>')
                                    $("#filter_subjects").val("")
                                    $("#filter_subjects").select2({
                                          allowClear: true,
                                          data: all_subjects,
                                          placeholder: "Select a subject",
                                    })
                              }
                        })
                  }

                  function all_college_subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/subjects/all',
                              success:function(data) {
                                    all_college_subjects_list = data;
                                    $("#filter_all_subjects").empty()
                                    $("#filter_all_subjects").append('<option value="">Select Subject</option>')
                                    $("#filter_all_subjects").val("")
                                    $("#filter_all_subjects").select2({
                                          allowClear: true,
                                          data: all_college_subjects_list,
                                          placeholder: "Select a subject",
                                    })
                              }
                        })
                  }

                  function load_all_student(){
                        all_students = []

                        $('#label_studstat').text("--")
                        $('#student_fullname').text("--")
                        $('#cur_glevel').text("--")
                        $('#cur_glevel').text("--")
                        $('#label_course').text("--")
                        $('#label_contact').text("--")
                        $('#label_ic_contact').text("--")
                        $('#label_curriculum').text("--")
                        $('#label_college').text("--")
                        $('#label_sid').text("--")
                        $('#studentsched_plot_holder').empty()

                        $.ajax({
                              type:'GET',
                              url:'/student/loading/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          // Toast.fire({
                                          //       type: 'warning',
                                          //       title: 'No student found'
                                          // })
                                          
                                          all_students = data
                                          $("#filter_student").empty()
                                          $("#filter_student").append('<option value="">Select a Student</option>')
                                          $("#filter_student").select2({
                                                data: all_students,
                                                placeholder: "Select a student",
                                          })
                                    }else{
                                          // Toast.fire({
                                          //       type: 'success',
                                          //       title: data.length+' students found.'
                                          // })
                                          all_students = data
                                          $("#filter_student").empty()
                                          $("#filter_student").append('<option value="">Select a Student</option>')
                                          $("#filter_student").select2({
                                                data: all_students,
                                                placeholder: "Select a student",
                                          })
                                    }
                                  
                              }
                        })
                  }

                  var all_courses = []
                  var all_colleges = @json($colleges);
                  load_courses()

                  function load_courses(){
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/courses',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    all_courses = data
                                    $("#input_course").empty()
                                    $("#input_course").append('<option value="">Select Course</option>')
                                    $("#input_course").val("")
                                    $('#input_course').select2({
                                          allowClear: true,
                                          data: all_courses,
                                          placeholder: "Select course",
                                    })    

                                    var temp_colleges = []
                                    $.each(all_courses, function(a,b){
                                         
                                          var check = temp_colleges.filter(x=>x.id == b.collegeid)
                                          if(check.length == 0){
                                                var get_college = all_colleges.filter(x=>x.id == b.collegeid)
                                                temp_colleges.push({
                                                      'id':get_college[0].id,
                                                      'collegeabrv':get_college[0].collegeabrv
                                                })
                                          }
                                    })

                                    var temp_text = ''
                                    $.each(temp_colleges, function(a,b){
                                          temp_text += ' <a class="dropdown-item view_enrollment_list" href="#" data-id="'+b.id+'">'+b.collegeabrv+'</a>'
                                    })

                                    $('#enrollment_list_holder')[0].innerHTML = temp_text


                              }
                        })
                  }

                  $(document).on('change','#input_course',function(){

                        if($(this).val() == "" || $(this).val() == null){
                              $("#input_curriculum").empty()
                              return false
                        }

                        var temp_id = $(this).val()
                  

                        var curriculum = all_courses.filter(x=>x.id == temp_id)[0].curriculum
                      
                        $("#input_curriculum").empty()
                        $("#input_curriculum").append('<option value="">Select curriculum</option>')
                        $("#input_curriculum").val("")
                        $('#input_curriculum').select2({
                              allowClear: true,
                              data: curriculum,
                              placeholder: "Select curriculum",
                        })
                  })

                  $(document).on('click','#update_coures',function(){

                        if($('#input_course').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please select course'
                              })
                              return false
                        }
                        if($('#input_curriculum').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please select curriculum'
                              })
                              return false
                        }

                        var temp_course = $('#input_course').val()
                        
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/set/course',
                              data:{
                                    studid:stud_id,
                                    courseid:$('#input_course').val(),
                                    curriculum:$('#input_curriculum').val(),
                                    levelid:$('#input_level').val(),
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                  if(data[0].status == 1){

                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].data
                                    })

                                    temp_course = all_courses.filter(x=>x.id == temp_course)
                                    
                                    if(stud_course != null && stud_course != 0){
                                          $('#label_course')[0].innerHTML = '<p class="mb-0">'+$('#input_course option:selected').text()+'</p>' + '<a href="#" id="button_to_updatestudentcourse_modal"><i class="far fa-edit text-primary"></i> Update Course</a>'

                                          $('#label_curriculum').text($('#input_curriculum option:selected').text())

                                    }else{
                                          $('#label_course')[0].innerHTML = '<p class="mb-0">--</p>' + '<a href="#" id="button_to_updatestudentcourse_modal"><i class="far fa-edit text-primary"></i> Update Course</a>'
                                    }
                                  



                                    $('#label_college').text(temp_course[0].collegeDesc)

                                    var student_index = all_students.findIndex(x=>x.id == stud_id)

                                    all_students[student_index].courseid = $('#input_course').val()
                                    all_students[student_index].courseDesc = temp_course[0].courseDesc
                                    all_students[student_index].collegeDesc = temp_course[0].collegeDesc
                                    all_students[student_index].levelid = $('#input_level').val()
                                    all_students[student_index].levelname = $('#input_level option:selected').text()

                                    stud_course = $('#input_course').val()
                                    sections()
                                    check_enrollment()
                                 
                                  }
                              }
                        })
                  })


                  function student_sched(){
                        console.log(student_info)
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/schedule',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    studid:stud_id,
                                    courseid:student_info[0].courseid
                              },
                              success:function(data) {
                                    

                                    $('#label_studstat').text(student_info[0].description)
                                    $('#student_fullname').text(student_info[0].student)
                                    $('#cur_glevel').text(student_info[0].levelname)
                                    $('#cur_glevel').text(student_info[0].levelname)
                                    if(student_info[0].courseid == null){
                                          $('#label_course')[0].innerHTML = '<a href="#" id="button_to_updatestudentcourse_modal"><i class="far fa-edit text-primary"></i> Update Course</a>'
                                    }else{
                                          $('#label_course')[0].innerHTML = '<p class="mb-0">'+student_info[0].courseDesc+'</p>' + '<a href="#" id="button_to_updatestudentcourse_modal" hidden><i class="far fa-edit text-primary"></i> Update Course</a>'
                                    }
                                    if(student_info[0].contactno == null){
                                          $('#label_contact').text('No Contact Number')
                                    }else{
                                          $('#label_contact').text(student_info[0].contactno)
                                    }
                                    if(student_info[0].ismothernum == 1){
                                          $('#label_ic_contact').text(student_info[0].mcontactno + ' [ MOTHER ] ')
                                    }else if(student_info[0].isfathernum == 1){
                                          $('#label_ic_contact').text(student_info[0].fcontactno + ' [ FATHER ] ')
                                    }
                                    else if(student_info[0].isguardannum == 1){
                                          $('#label_ic_contact').text(student_info[0].gcontactno + ' [ GUARDIAN ] ')
                                    }else{
                                          $('#label_ic_contact').text('No Contact Number')
                                    }
                                    $('#label_college').text(student_info[0].collegeDesc)
                                    $('#label_sid').text(student_info[0].sid)

                                    if(curriculum.length != 0){
                                          $('#label_curriculum').text(curriculum[0].curriculumname)
                                    }else{
                                          $('#label_curriculum').text('')
                                    }
                                    
                                   

                                    $('#studentsched_plot_holder').empty()
                                    $('#studentsched_plot_holder').append(data)

                                    $('.stud_sect[data-id="'+section+'"]').append('<br><span class="badge badge-success">Student Section</span>')
                                    $('#button_to_updatestudentcourse_modal').removeAttr('hidden')

                                    if($('.stud_sect').length > 0){
                                          $('#unload_all_subj').removeAttr('disabled')
                                    }else{
                                          $('#unload_all_subj').attr('disabled','disabled')
                                    }
                                   

                              }
                        })
                  }

                  get_course()

                  function get_course(){
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/getcourse',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    if(data.length > 0){
                                          $('#no_course_holder').attr('hidden','hidden')
                                    }else{
                                          $('#no_course_holder').removeAttr('hidden')
                                        
                                    }
                              }
                        })
                  }

                  function sections(){
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/sections',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    courseid:stud_course,
                                    levelid:$('#filter_gradelevel').val()
                              },
                              success:function(data) {
                                    all_sections = data
                                    $("#filter_section").empty()
                                    $("#filter_section").append('<option value="">Select Section</option>')
                                    $("#filter_section").val("")

                                    $('#filter_section').select2({
                                          allowClear: true,
                                          data: all_sections,
                                          placeholder: "Select sections",
                                    })
                              }
                        })
                  }

                  $(document).on('click','#close_updatestudentcourse_modal',function(){
                        // if($('#input_course').val() == ""){
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'No course selected'
                        //       })
                        //       return false
                        // }

                        // if($('#input_curriculum').val() == ""){
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'No curriculum selected'
                        //       })
                        //       return false
                        // }
                        $('#button_to_studentloading_modal').attr('disabled','disabled')
                        $('#student_evaluation').attr('disabled','disabled')
                       //$('#filter_student').val("").change()
                        $('#updatestudentcourse_modal').modal('hide')
                  })
                  // $(document).on('click','#button_to_studentloading_modal',function(){
                  //       // if(stud_course == null || stud_course == 0){
                  //       //       $('#updatestudentcourse_modal').modal()
                  //       //       return false
                  //       // }

                  //       // $('#button_add_all').attr('disabled','disabled')
                  //       // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                  //       //       Toast.fire({
                  //       //             type: 'warning',
                  //       //             title: 'Student is already enrolled'
                  //       //       })
                  //       //       return false
                  //       // }
                  //       // $('#studentloading_modal').modal();
                  //       allowconflict = 0
                  //       $('#available_sched_modal').modal()
                  // })

                  var selected_sched

                  
                  $(document).on('click','.remove_subject',function(){
                        remove_schedule($(this).attr('data-id'))
                  })

                  $(document).on('click','.remove_schedule',function(){

                        var valid = false
                        var schedstat = $(this).attr('data-schedstat')
                        selected_sched = $(this).attr('data-id')
                        var schedinfo = all_sched.filter(x=>x.id == selected_sched)
                        // $('.subj_desc_holder_code').text(schedinfo[0].subjCode)
                        // $('.subj_desc_holder_title').text(schedinfo[0].subjDesc)

                        // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                              // $('#remove_sched_modal').modal()
                              // if(schedstat == 'DROPPED'){
                              //       Swal.fire({
                              //             title: 'Are you sure you want to remove this subject?',
                              //             html: "This subject is already marked as dropped. It will be remove from student load!",
                              //             type: 'warning',
                              //             showCancelButton: true,
                              //             confirmButtonColor: '#3085d6',
                              //             cancelButtonColor: '#d33',
                              //             confirmButtonText: 'Proceed!'
                              //       })
                              //       .then((result) => {
                              //             if (result.value) {
                              //                   remove_schedule()
                              //             }
                              //       })
                              // }else{
                                    Swal.fire({
                                          title: 'Do you want to remove schedule?',
                                          html: "This subject will be removed.",
                                          type: 'warning',
                                          showCancelButton: true,
                                          confirmButtonColor: '#3085d6',
                                          cancelButtonColor: '#d33',
                                          confirmButtonText: 'Proceed!'
                                    })
                                    .then((result) => {
                                          if (result.value) {
                                                remove_schedule()
                                          }
                                    })
                              // }

                        // }else{
                              // remove_schedule()
                        // }

                  })

                  function remove_schedule(status="deleted"){

                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/schedule/remove',
                              data:{
                                    schedid:selected_sched,
                                    studid:stud_id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    status:status
                              },
                              success:function(data) {
                              if(data[0].status == 1){
                                    if(status == 'delete'){
                                          $('#remove_sched_modal').modal('hide')
                                    }
                                    student_sched()
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].data
                                    })
                                    // all_sched_student[loaded_allsched_student_index].enrolled -= 1;
                                    display_sched_csl('studentloading',stud_id,entype)
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].data
                                    })
                              }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong.'
                                    })
                              }
                        })
                  }

                  $('#sched_con_modal').on('hidden.bs.modal', function () {
                        allowconflict = 0
                  })

                  var allowconflict = 0

                  $(document).on('click','.add_sched',function(){
                        selected_sched = $(this).attr('data-id')
                        $('#conflict_addsched_button').removeAttr('data-id')
                        $('#conflict_addsched_button').removeAttr('data-status')

                        var schedinfo = all_sched.filter(x=>x.id == selected_sched)
                        $('.subj_desc_holder_code').text(schedinfo[0].subjCode)
                        $('.subj_desc_holder_title').text(schedinfo[0].subjDesc)
                        // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                        //       $('#add_sched_modal').modal()
                        // }else{
                              var isenrolled = 0
                              if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                                    isenrolled = 1
                              }

                              add_sched_function('regular',isenrolled);
                        // }
                  })

                  $(document).on('click','#addAllSched',function(){
                        Swal.fire({
                              title: 'Are you sure you want to add all schedule from this section?',
                              // html: "You you sure you want to all schedule from this section?",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Proceed!'
                        })
                        .then((result) => {
                              if (result.value) {
                                    add_all_sched()
                              }
                        })
                  })

                  function add_all_sched(){

                        if($('.add_sched').length == 0){
                              Toast.fire({
                                    type: 'success',
                                    title: 'Schedule Added.'
                              })
                              display_sched_csl('studentloading',stud_id,entype)
                              student_sched()
                              return false
                        }

                        schedid = $( $('.add_sched')[0]).attr('data-id')

                        var isenrolled = 0
                        if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                              isenrolled = 1
                        }

                        allowconflict = 1

                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/schedule/add',
                              data:{
                                    schedid:schedid,
                                    studid:stud_id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    allowconflict:allowconflict,
                                    status:status
                              },
                              success:function(data) {
                                    $('.add_sched[data-id='+schedid+']').addClass('remove_schedule')
                                    $('.add_sched[data-id='+schedid+']').addClass('btn-danger')
                                    $('.add_sched[data-id='+schedid+']').text('Remove Schedule')
                                    $('.add_sched[data-id='+schedid+']').removeClass('add_sched')
                                    $('.add_sched[data-id='+schedid+']').removeClass('btn-primary')
                                    add_all_sched()
                              }
                        })



                  }

                  $(document).on('click','.add_subject',function(){

                        var schedinfo = all_sched.filter(x=>x.id == selected_sched)
                        var isenrolled = 0
                        if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                              isenrolled = 1
                        }

                        add_sched_function($(this).attr('data-status'),isenrolled)
                  })

                  function add_sched_function(status="regular",isenrolled = 0){
                        $('#conflict_addsched_button').removeAttr('data-id')
                        $('#conflict_addsched_button').removeAttr('data-status')
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/schedule/add',
                              data:{
                                    schedid:selected_sched,
                                    studid:stud_id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    allowconflict:allowconflict,
                                    status:status
                              },
                              success:function(data) {
                                  if(data[0].status == 1){
                                          if(data[0].section != null){
                                                section = data[0].section
                                          }
                                          student_sched()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          allowconflict = 0
                                          $('#add_sched_modal').modal('hide')
                                          $('#sched_con_modal').modal('hide')
                                          student_sched()
                                          display_sched_csl('studentloading',stud_id,entype)

                                      
                                  }else{
                                          if(data[0].data == 'conflict'){
                                                allowconflict = 1
                                                $('#sched_con_holder').removeAttr('hidden')
                                                $('#sched_conflict_holder').empty()
                                                $('#con_sect').text(data[0].section)
                                                $('#con_subj').text(' ( '+data[0].subjcode+' ) '+data[0].subjdesc)
                                                $('#con_day').text(data[0].day)
                                                $('#con_time').text(data[0].time)
                                                $('#sched_con_modal').modal()
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'conflict'
                                                })
                                                $('#conflict_addsched_button').attr('data-id',selected_sched)
                                                $('#conflict_addsched_button').attr('data-status',status)
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].data
                                                })
                                          }
                                  }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong.'
                                    })
                              }
                        })

                  }


                  $(document).on('click','.mark_as_setionid',function(){

                        section = $(this).attr('data-id')
                 
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/student/set/section',
                              data:{
                                    section:section,
                                    studid:stud_id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    var temp_idex = all_students.findIndex(x=>x.id == stud_id)
                                    all_students[temp_idex].sectionid = section
                                    if(data[0].status == 1){
                                                student_sched()
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].data
                                                })
                                    }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].data
                                                })
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong.'
                                    })
                              }
                        })
                  })
                  
              

            })
      </script>

      
      <script>
            //studente evaluation


            $(document).on('click','#print_student_evaluation',function(){
                  var temp_studid = $('#filter_student').val()
                  window.open('/student/grade/evaluation/print?studid='+temp_studid, '_blank');
            })


            $(document).on('click','#student_evaluation',function(){

                  var temp_studid = $('#filter_student').val()

                  $.ajax({
                        type:'GET',
                        url:'/student/grade/evaluation',
                        data:{
                              studid:temp_studid,
                        },
                        success:function(data) {
                              
                              var temp_year = data[0].yearLevel
                              var temp_sem = data[0].semester
                              var temp_eval = data[0].evaluation

                              // $('#eval_course_label').text(temp_eval[0].curriculum[0]curriculumname)
                              $('#eval_course_label').text(data[0].course.courseDesc)
                              $('#eval_curriculum_label').text(data[0].curriculum.curriculumname)
                              $('#eval_studentname_label').text($('#student_fullname').text())

                              $('#student_evaluation_holder').empty()
                              $.each(temp_year,function(a,b){

                                    $.each(temp_sem,function(c,d){

                                          var temp_filtered_eval = temp_eval.filter(x=>x.yearId == b.id && x.semesterID == d.id)
                                       
                                          if(temp_filtered_eval.length > 0){

                                                $('#student_evaluation_holder').append('<div class="row"><div class="col-md-12"><table class="table table-sm table-bordered"  width="100%" style="font-size:.7rem !important"><thead ><tr><th colspan="6">'+b.levelname+' - '+d.semester+'</th></tr><tr><th width="10%">Code</th><th width="40%">Description</th><th wdith="8%"  class="text-center">Lec.</th><th  class="text-center" wdith="8%">Lab.</th><th wdith="8%" class="text-center">Total</th><th class="text-center" width="13%">Grades</th><th class="text-center" width="13%">Remarks</th></tr><thead><tbody id="'+b.id+'-'+d.id+'"></tbody></table></div></div>')

                                                $.each(temp_filtered_eval,function(e,f){

                                                      var temp_final = f.fg != null ? f.fg : '';
                                                      var bg = '';
                                                      var remarks = f.fgremarks != null ? f.fgremarks : '';

                                                      if(f.finalgrade != null){
                                                            if(f.fgremarks == 'PASSED'){
                                                                  bg = 'bg-success';
                                                            }else{
                                                                  bg = 'bg-danger';
                                                            }
                                                      }
													  
													  if(f.fg == 'INC'){
                                                            bg = 'bg-warning';
                                                            remarks = 'INC'
                                                      }else if (f.fg == 'DROPPED'){
                                                            bg = 'bg-danger';
                                                            remarks = 'DROPPED'
                                                      }


                                                      var units = ( parseFloat(f.lecunits) + parseFloat(f.labunits) ).toFixed(1)

                                                      // if(f.finalgrade != null && f.finalgrade > 75){
                                                      //       bg = 'bg-success'
                                                      //       remaks = 'PASSED'
                                                      // }else if(f.finalgrade != null && f.finalgrade  < 75){
                                                      //       bg = 'bg-danger'
                                                      //       remaks = 'FAILED'
                                                      // }

                                                      $('#'+b.id+'-'+d.id).append('<tr class="'+bg+'"><td>'+f.subjCode+'</td><td>'+f.subjDesc+'</td><td  class="text-center align-middle">'+f.lecunits+'</td><td  class="text-center align-middle">'+f.labunits+'</td><td  class="text-center align-middle">'+units+'</td><td class="text-center align-middle">'+temp_final+'</td><td class="text-center align-middle">'+remarks+'</td></tr>')
                                                })
                                          }

                                         
                                    })
                              })



                              $('#student_evaluation_modal').modal();
                        },
                        error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong.'
                              })
                        }
                  })
            })
      </script>

      <script>
            //studente evaluation

            // $(document).ready(function(){

                  get_all_sched()

                  function get_all_sched(){
                        return false
                        $.ajax({
                              type:'GET',
                              url:'/student/loading/allsched',
                              data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val()
                              },
                              success:function(data) {

                                    all_sched = data[0].college_classsched
                                    all_sched_section = data[0].section
                                    all_sched_enrolled = data[0].enrolled
                                    all_sched_detail = data[0].scheddetail
                                    all_sched_student = data[0].all_stud_sched

                                    $('#filter_sched_section').empty();
                                    $('#filter_sched_section').append('<option value=""></option>')
                                    $('#filter_sched_section').select2({
                                          allowClear:true,
                                          data:all_sched_section,
                                          placeholder:'All'
                                    })


                                    var temp_subjcode = [...new Map(all_sched.map(item => [item['subjCode'], item])).values()]
                                    $.each(temp_subjcode,function(c,d){
                                          // d.id = d.subjCode
                                          d.text = d.subjCode
                                    })

                                    // $('#filter_sched_subjcode').empty();
                                    // $('#filter_sched_subjcode').append('<option value=""></option>')
                                    // $('#filter_sched_subjcode').select2({
                                    //       allowClear:true,
                                    //       data:temp_subjcode,
                                    //       placeholder:'All'
                                    // })
                              
                                    var temp_subjects = [...new Map(all_sched.map(item => [item['subjDesc'], item])).values()]
                                    $.each(temp_subjects,function(e,f){
                                          f.text = f.subjDesc
                                    })

                                    // $('#filter_sched_subjdesc').empty();
                                    // $('#filter_sched_subjdesc').append('<option value=""></option>')
                                    // $('#filter_sched_subjdesc').select2({
                                    //       allowClear:true,
                                    //       data:temp_subjects,
                                    //       placeholder:'All'
                                    // })

                                    display_sched_csl('studentloading',stud_id,entype)

                              //      $('#available_sched_modal').modal()
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong.'
                                    })
                              }
                        })
                  }

                  // $(document).on('change','#filter_sched_section',function(){
                  //       display_sched_csl('studentloading',stud_id)
                  // })
                  // $(document).on('change','#filter_sched_subjdesc',function(){
                  //       display_sched_csl('studentloading',stud_id)
                  // })
                  // $(document).on('change','#filter_sched_subjcode',function(){
                  //       display_sched_csl('studentloading',stud_id)
                  // })
            // })

            var all_sched = []
            var all_sched_section = []
            var all_sched_enrolled = []
            var all_sched_detail = []
            var all_sched_student = []

            // function display_sched_csl('studentloading',stud_id){

            //       var temp_data = all_sched

            //       if($('#filter_sched_section').val() != ""){
            //             temp_data = temp_data.filter(x=>x.sectionID == $('#filter_sched_section').val())
            //       }
            //       if($('#filter_sched_subjdesc').val() != ""){
            //             var subjdesc = all_sched.filter(x=>x.id == $('#filter_sched_subjdesc').val())[0].subjDesc
            //             temp_data = temp_data.filter(x=>x.subjDesc == subjdesc)
            //       }
            //       if($('#filter_sched_subjcode').val() != ""){
            //             var subjcode = all_sched.filter(x=>x.id == $('#filter_sched_subjcode').val())[0].subjCode
            //             temp_data = temp_data.filter(x=>x.subjCode == subjcode)
            //       }

            //       $("#available_sched_datatable").DataTable({
            //             destroy: true,
            //             data:temp_data,
            //             autoWidth: false,
            //             stateSave: true,
            //             lengthChange : false,
            //             order: [
            //                               [ 1, "asc" ]
            //                         ],
            //             columns: [
            //                         { "data": null },
            //                         { "data": "id" },
            //                         { "data": "subjCode" },
            //                         { "data": "subjDesc" },
            //                         { "data": "lecunits" },
            //                         { "data": "labunits" },
            //                         { "data": "capacity" },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                   ],
            //             columnDefs: [
            //                   {
            //                         'targets': 0,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {

            //                               if(rowData.selected == 0){
            //                                     var all_loaded_student_count = all_sched_student.filter(x=>x.schedid == rowData.id)

            //                                     $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block add_sched" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="'+rowData.dataid+'">Add Sched</button>'

            //                                     if(all_loaded_student_count.length > 0){
            //                                           if(rowData.capacity <= all_loaded_student_count[0].enrolled){
            //                                                 $(td).text('Maximum')
            //                                                 $(td).addClass('text-center')
            //                                           }
            //                                     }
                                                

                                            
            //                               }else{
            //                                     $(td)[0].innerHTML = '<button class="btn btn-sm btn-danger btn-block remove_schedule" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="'+rowData.dataid+'">Remove Sched</button>'
            //                               }
                                          
            //                               $(td).addClass('align-middle')
            //                         }
            //                   },
            //                   {
            //                         'targets': 1,
            //                         'orderable': true, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               var sectiondesc = all_sched_section.filter(x=>x.id == rowData.sectionID)
            //                               if(sectiondesc.length > 0){
            //                                     $(td).text(sectiondesc[0].sectionDesc)
            //                               }else{
            //                                     $(td).text(null)
            //                               }
            //                               $(td).addClass('align-middle')
                                          
            //                         }
            //                   },
            //                   {
            //                         'targets': 2,
            //                         'orderable': true, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               $(td).addClass('align-middle')
            //                         }
            //                   },
            //                   {
            //                         'targets': 3,
            //                         'orderable': true, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               $(td).addClass('align-middle')
            //                         }
            //                   },
            //                   {
            //                         'targets': 4,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               $(td).addClass('text-center')
            //                               $(td).addClass('align-middle')
            //                         }
            //                   },
            //                   {
            //                         'targets': 5,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               $(td).addClass('text-center')
            //                               $(td).addClass('align-middle')
            //                         }
            //                   },  {
            //                         'targets': 6,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               $(td).addClass('text-center')
            //                               $(td).addClass('align-middle')
            //                         }
            //                   },
            //                   {
            //                         'targets': 7,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               var enrolled_count = all_sched_enrolled.filter(x=>x.schedid == rowData.id)
            //                               var all_loaded_student_count = all_sched_student.filter(x=>x.schedid == rowData.id)
            //                               var enrolled = 0
            //                               var loaded = 0
            //                               if(enrolled_count.length > 0){
            //                                     enrolled = enrolled_count[0].enrolled 
            //                               }
                                         
            //                               if(all_loaded_student_count.length > 0){
            //                                     loaded = all_loaded_student_count[0].enrolled
            //                               }

            //                               $(td)[0].innerHTML  = '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_students" data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">'+enrolled+'</a>' + ' / '+ '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_loaded_students" data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">'+loaded+'</a>'

            //                               $(td).addClass('text-center')
            //                               $(td).addClass('align-middle')
            //                         }

                                    
            //                   },
            //                   {
            //                         'targets': 8,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
            //                               var temp_sched = []
            //                               if(temp_data.length > 0){
            //                                     $.each(temp_data,function(a,b){
            //                                           var check = temp_sched.filter(x=>x.stime == b.stime && x.etime == b.etime)
            //                                           if(check.length == 0){
            //                                                 temp_sched.push({
            //                                                       'roomname':b.roomname,
            //                                                       'etime':b.etime,
            //                                                       'stime':b.stime,
            //                                                       'days':[]
            //                                                 });
            //                                                 var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime)
            //                                                 if(get_index != -1){
            //                                                       temp_sched[get_index].days.push(b.day)
            //                                                 }
            //                                           }else{
            //                                                 var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime)
            //                                                 if(get_index != -1){
            //                                                       temp_sched[get_index].days.push(b.day)
            //                                                 }
            //                                           }
            //                                     })
            //                                     var text = ''
            //                                     $.each(temp_sched,function(a,b){
            //                                           var temp_stime = moment(b.stime, 'HH:mm a').format('hh:mm a')
                                                      
            //                                           text += moment(b.stime, 'HH:mm a').format('hh:mm A')+' - '+moment(b.etime, 'HH:mm a').format('hh:mm A') +' / '
            //                                           $.each(b.days,function(c,d){
            //                                                 text += d == 1 ? 'M' :''
            //                                                 text += d == 2 ? 'T' :''
            //                                                 text += d == 3 ? 'W' :''
            //                                                 text += d == 4 ? 'Th' :''
            //                                                 text += d == 5 ? 'F' :''
            //                                                 text += d == 6 ? 'S' :''
            //                                           })
                                                      
            //                                           if(b.roomname != null){
            //                                                 text += ' / '+b.roomname
            //                                           }


            //                                           if(temp_sched.length != a+1){
            //                                                 text += ' <br> '
            //                                           }
            //                                     })
                                          
            //                                     $(td)[0].innerHTML = text
            //                               }else{
            //                                     $(td).text('No Sched')
            //                               }
            //                               $(td).attr('style','font-size:.7rem !important')
            //                               // $(td).addClass('text-center')
            //                         }
            //                   },
            //                   {
            //                         'targets': 9,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
            //                               if(rowData.lastname != null){
            //                                     $(td).text(rowData.lastname+', '+rowData.firstname)
            //                               }else{
            //                                     $(td).text(null)
            //                               }
                                         
            //                               $(td).addClass('align-middle')
            //                               $(td).attr('style','font-size:.7rem !important')
            //                         }

                                    
            //                   },
            //             ]
            //       });
            // }
      </script>

<script>

      // $(document).ready(function(){
      //       $(document).on('click','.sched_list_students',function(){
      //             $('#list_label').text('Subject : ' + $(this).attr('data-text'))
      //             $('#student_list_type').text('(Enrolled)')
      //             var temp_schedid = $(this).attr('data-id')
      //             sched_enrolled_learners(temp_schedid)
      //       })   
            
      //       $(document).on('click','.sched_list_loaded_students',function(){
      //             $('#list_label').text('Subject : ' + $(this).attr('data-text'))
      //             $('#student_list_type').text('(Loaded)')
      //             var temp_schedid = $(this).attr('data-id')
      //             sched_loaded_learners(temp_schedid)
      //       }) 
      
      // })

      // function sched_enrolled_learners(schedid){
            
      //       $.ajax({
      //             type:'GET',
      //             url:'/college/section/schedule/schedenrolledlearners',
      //             data:{
      //                   schedid:schedid,
      //                   syid:$('#filter_sy').val(),
      //                   semid:$('#filter_semester').val()
      //             },
      //             success:function(data) {
      //                   $('#enrolled_modal').modal()
      //                   sched_list_table(data)
      //             }
      //       })
      // }

      // function sched_loaded_learners(schedid){
      //       $.ajax({
      //             type:'GET',
      //             url:'/college/section/schedule/schedloadedlearners',
      //             data:{
      //                   schedid:schedid,
      //                   syid:$('#filter_sy').val(),
      //                   semid:$('#filter_semester').val()
      //             },
      //             success:function(data) {
      //                   $('#enrolled_modal').modal()
      //                   sched_list_table(data)
                        
      //             }
      //       })
      // }

      // function sched_list_table(data){
      //       $("#student_list").DataTable({
      //             destroy: true,
      //             lengthChange : false,
      //             data:data,
      //             columns: [
      //                   { "data": "student" },
      //                   { "data": "levelname" },
      //                   { "data": "courseabrv" },
      //             ],
      //             columnDefs: [
      //                   {
      //                         'targets': 0,
      //                         'orderable': true, 
      //                         'createdCell':  function (td, cellData, rowData, row, col) {
      //                               var enrolledstattus = ''
      //                               if(rowData.isenrolled == 1){
      //                                     enrolledstattus = '<span class="badge badge-success float-right">Enrolled</span>'
      //                               }
      //                               $(td)[0].innerHTML = rowData.student+enrolledstattus
      //                               $(td).addClass('align-middle')
      //                         }
      //                   },
      //             ]
      //       })
      // }

</script>


@endsection


