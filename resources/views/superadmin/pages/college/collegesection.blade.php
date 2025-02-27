
@php
      if(Session::get('currentPortal') == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }else if ( Session::get('currentPortal') == 14){
            $extend = 'deanportal.layouts.app2';
      }else if( Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 16){
            $extend = 'registrar.layouts.app';
      }else if( Session::get('currentPortal') == 14){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'deanportal.layouts.app2';
      }else if(auth()->user()->type == 14 ){
            $extend = 'deanportal.layouts.app2';
      }else if(auth()->user()->type == 17 ){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 ){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 16 ){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 14 ){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 3 ){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 2 ){
            $extend = 'deanportal.layouts.app2';
      }
@endphp

@extends($extend)
@section('pagespecificscripts')
      
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}"> --}}
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
      <style>
            /* .select2-selection{
                height: calc(2.25rem + 2px) !important;
            } */
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
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
            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }

            .btn-group-sm>.btn, .btn-sm {
                  padding: 0.25rem 0.5rem;
                  font-size: .7rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }

            .tooltip > .arrow {
                  visibility: hidden;
            }
      </style>


@endsection


@section('content')

@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->get(); 
   $schoolinfo = DB::table('schoolinfo')
                  ->select('abbreviation')
                  ->first(); 
     
           
      $gradelevel = DB::table('gradelevel')
                        ->where('acadprogid',6)
                        ->where('deleted',0)
                        ->orderBy('sortid')
                        ->select('gradelevel.*','levelname as text')
                        ->get(); 

      // $rooms = DB::table('rooms')
      //             ->where('deleted',0)
      //             ->select('id','roomname as text')
      //             ->get();

      $dean = DB::table('college_colleges')
                  ->join('teacher',function($join){
                        $join->on('college_colleges.dean','=','teacher.id');
                        $join->where('teacher.deleted',0);
                  })
                  ->where('college_colleges.deleted',0)
                  ->select(
                        'teacher.id',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->distinct()
                  ->get();
                                             
@endphp




<div class="modal fade" id="sectionsched_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <h5>Section : <span id="section_name"></span></h5>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-primary btn-sm" id="print_schedule">Print Schedule</button>
                                    <button class="btn btn-primary btn-sm" id="update_subject_button" hidden>Update Subjects</button>
                                    <button class="btn btn-primary btn-sm" id="add_subject_button" hidden>Add Subject</button>
                              </div>
                        </div>
                        <div class="row" id="">
                             <div class="col-md-12">
                                    <table class="table table-bordered smfont table-sm" style="font-size: 12px !important">
                                          <thead>
                                          <tr>
                                                <th class="text-center align-middle" width="3%"></th>
                                                <th class="text-center align-middle" width="6%"></th>
                                                <th class="text-center align-middle" width="33%">Subject</th>
                                                <th class="text-center align-middle" width="4%">Units</th>
                                                <th class="text-center align-middle" width="8%">Day</th>
                                                <th class="text-center align-middle" width="6%">Time</th>
                                                <th class="text-center align-middle" width="6%" >Room</th>
                                                <th class="text-center align-middle" width="16%">Teacher</th>
                                                <th class="text-center align-middle" width="4%">Cap.</th>
                                                <th class="text-center align-middle" width="4%">Enrolled</th>
                                                <th class="text-center align-middle" width="3%"></th>
                                                <th class="text-center align-middle" width="3%"></th>
                                          </tr>
                                          </thead>
                                          <tbody class="schedule" id="sched_plot_holder">
                                          </tbody>
                                    </table>
                             </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                             
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>   



<div class="modal fade" id="all_subject_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                  <label for="">Subjects</label>
                                  <select name="input_subject" id="input_subject" class="form-control select2"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                              <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" id="add_subject_to_section"> Add Subject</button>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                              </div>
                        </div>
                  </div>
                 
            </div>
      </div>
</div>   
 


<div class="modal fade" id="createsection_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-body">
                       <div class="row">
                             <div class="col-md-6" style="font-size:12px !important">
                                    <div class="row">
                                          <div class="col-md-4">
                                                <strong>School Year</strong>
                                                <p class="text-muted" id="createsection_sy_label"></p>
                                          </div>
                                          <div class="col-md-4">
                                                <strong>Semester</strong>
                                                <p class="text-muted" id="createsection_semester_label"></p>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <strong>Included Subjects</strong>
                                                <table class="table table-sm mt-2" width="100%">
                                                      <thead>
                                                            <tr hidden>
                                                                  <td width="3%"></td>
                                                                  <td width="97%"></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="subject_package">
                                                            <tr>
                                                                  <td colspan="2"><i>Included subjects will display after selecting a grade level, course and curriculum .</i></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                                {{-- <ul id="subject_package" class="list-group mt-2">
                                                      <li class="list-group-item p-2"><i>Included subjects will display after selecting a grade level, course and curriculum .</i></li>
                                                </ul> --}}
                                          </div>
                                    </div>
                             </div>
                             <div class="col-md-6">
                                    <div class="row">
                                          <div class="col-md-12 form-group">
                                                <label for="">Specification</label>
                                                <select name="createsection_input_specification" id="createsection_input_specification" class="form-control select2">

                                                </select>
                                          </div>
                                          <div class="col-md-12 form-group">
                                                <label for="">College</label>
                                                <select name="createsection_input_college" id="createsection_input_college" class="form-control select2"></select>
                                          </div>
                                          <div class="col-md-12 form-group">
                                                <label for="">Course</label>
                                                <select name="createsection_input_course" id="createsection_input_course" class="form-control select2"></select>
                                          </div>
                                          
                                          <div class="col-md-12 form-group">
                                                <label for="">Grade Level</label>
                                                <select name="createsection_input_gradelevel" id="createsection_input_gradelevel" class="form-control select2"></select>
                                          </div>
                                          <div class="col-md-12 form-group" hidden>
                                                <label for="">Curriculum</label>
                                                <select name="createsection_input_curriculum" id="createsection_input_curriculum" class="form-control select2"></select>
                                          </div>
                                          <div class="col-md-12 form-group" id="cap_holder">
                                                <label for="">Schedule Capacity</label>
                                                <input name="createsection_input_capacity" id="createsection_input_capacity" class="form-control form-control-sm" oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="50">
                                          </div>
                                          <div class="col-md-12 form-group">
                                                <label for="">Section Name</label>
                                                <input name="createsection_input_name" id="createsection_input_name" class="form-control form-control-sm">
                                          </div>
                                          
                                    </div>
                                    <hr>
                                    <div class="row">
                                          <div class="col-md-6">
                                                <button class="btn btn-primary btn-sm" id="button_to_createsection_function"> Create Schedule</button>
                                          </div>
                                          <div class="col-md-6 text-right">
                                                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                          </div>
                                    </div>
                             </div>
                             
                             
                       </div>
                       
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="edit_capacity_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content modal-sm">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Capacity</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size:.8rem !important">
                        <div class="row">
                              <div class="col-md-12">
                                    <strong>Subject</strong>
                                    <p class="text-muted list_label"></p>
                              </div>
                        </div>
                        <div class="row" >
                              <div class="col-md-12 form-group">
                                    <label for="">Schedule Capacity</label>
                                    <input class="form-control form-control-sm" id="edit_capacity">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="update_capacity_button">Update</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="add_schedule_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-body">
                       <div class="row">
                             <div class="col-md-6" style="font-size:12px !important">
                                    <div class="row">
                                          <div class="col-md-4">
                                                <strong>School Year</strong>
                                                <p class="text-muted sy_label" id="sy_label"></p>
                                          </div>
                                          <div class="col-md-4">
                                                <strong>Semester</strong>
                                                <p class="text-muted semester_label" id="semester_label"></p>
                                          </div>
                                          
                                    </div>
                                    <div class="row">
                                          <div class="col-md-4">
                                                <strong>Section</strong>
                                                <p class="text-muted" id="section_label"></p>
                                          </div>
                                          <div class="col-md-4">
                                                <strong>Grade Level</strong>
                                                <p class="text-muted" id="gl_label"></p>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <strong>Course</strong>
                                                <p class="text-muted" id="course_label"></p>
                                          </div>
                                    </div>
                                    <div class="row">
                                                <div class="col-md-6">
                                                      <strong>Subject</strong>
                                                      <p class="text-muted" id="subj_label"></p>
                                                </div>
                                    </div>
                                    <div class="row" id="sched_con_holder">
                                          <div class="col-md-12">
                                                <label for="">Schedule Conflict:</label>
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
                                    <div class="row">
                                          <div class="col-md-12" id="sched_conflict_holder">

                                          </div>
                                    </div>
                             </div>
                             <div class="col-md-6">
                                    <div class="row">
                                          <div class="col-md-6 form-group">
                                                <label for="">Classification</label>
                                                <select name="input_term" id="input_term" class="form-control select2">
                                                      <option value="">Select Term</option>
                                                      <option value="Lecture">Lecture</option>
                                                      <option value="Laboratory">Laboratory</option>
                                                </select>
                                          </div>
                                          <div class="col-md-6 form-group">
                                                <label for="">Room</label>
                                                <select name="input_room" id="input_room" class="form-control"></select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <div class="form-group">
                                                      <label for="">Time</label>
                                                      <input type="text" class="form-control reservationtime form-control-sm" name="time" id="time">
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <label>Day</label>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline mr-3">
                                                      <input type="checkbox" id="Mon" class="day" value="1" >
                                                      <label for="Mon">Mon</label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline mr-3">
                                                      <input type="checkbox" id="Tue" class="day" value="2" >
                                                      <label for="Tue">Tue</label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline mr-3">
                                                      <input type="checkbox" id="Wed" class="day" value="3" >
                                                      <label for="Wed">Wed</label>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Thu" class="day" value="4" >
                                                      <label for="Thu">Thu
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Fri" class="day" value="5" >
                                                      <label for="Fri">Fri
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Sat" class="day" value="6" >
                                                      <label for="Sat">Sat
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Sun" class="day" value="7" >
                                                      <label for="Sun">Sun
                                                      </label>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-12 form-group">
                                                <label for="">Teacher</label>
                                                <select name="input_teacher" id="input_teacher" class="form-control"></select>
                                          </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                          <div class="col-md-6">
                                                <button class="btn btn-primary btn-sm" id="create_schedule"> Create Schedule</button>
                                          </div>
                                          <div class="col-md-6 text-right">
                                                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                          </div>
                                    </div>
                             </div>
                       </div>
                  </div>
            </div>
      </div>
</div>



@php
      $teachers = DB::table('teacher')
                        ->where('isactive',1)
                        ->where('deleted',0)
                        ->select(
                              'firstname',
                              'lastname',
                              'middlename'
                        )
                        ->get();
@endphp


{{-- <div class="modal fade" id="edit_teacher_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content modal-sm">
                  <div class="modal-header pb-2 pt-2 border-0" >
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Teacher</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" >×</span></button>
                  </div>
                  <div class="modal-body pt-1" style="font-size:.8rem !important" >
                        <div class="row">
                              <div class="col-md-12">
                                    <strong>Subject</strong>
                                    <p class="text-muted list_label"></p>
                              </div>
                        </div>
                      <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Teacher</label>
                                    <select name="collegeschedlist_edit_teacher" id="collegeschedlist_edit_teacher" class="form-control select2 form-control-sm"></select>
                              </div>
                      </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="update_teacher_button">Update</button>
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
                        <h1>College Section</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">College Section</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row" style="font-size:12px !important">
                  <div class="col-md-12">
                       
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-4">
                                                           <label><i class="fa fa-filter"></i> Filter</label> 
                                                      </div>
                                                      <div class="col-md-8">
                                                            
                                                      </div>
                                                </div>
                                               
                                                <div class="row">
                                                      <div class="col-md-2  form-group mb-0">
                                                            <label for="" class="mb-1">School Year</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_sy">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2 form-group mb-0" >
                                                            <label for="" class="mb-1">Semester</label>
                                                            <select class="form-control form-control-sm   select2" id="filter_semester">
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2 form-group mb-0" >
                                                            <label for="" class="mb-1">Section</label>
                                                            <select class="form-control form-control-sm select2" id="filter_schedulegroup">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3 form-group mb-0" >
                                                            <label for="" class="mb-1">College Instructor</label>
                                                            <select class="form-control form-control-sm select2" id="filter_teacher">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2 form-group mb-0" >
                                                            <label for="" class="mb-1">Room</label>
                                                            <select class="form-control form-control-sm select2" id="filter_room">
                                                            </select>
                                                      </div>
                                                </div>
                                                <div class="row mt-2">
                                                      <div class="col-md-2">
                                                            <label for="" class="mb-1">Class Type</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_classtype">
                                                                 <option value="">All</option>
                                                                 <option value="1">Regular Class</option>
                                                                 <option value="2">Special Class</option>
                                                            </select>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" id="no_course_holder" hidden style="font-size:12px !important">
                  <div class="col-md-12">
                        <div class="card shadow bg-danger">
                              <div class="card-body p-1">
                                    You are not assigned to a course or college.
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" >
                              <div class="card-body" style="font-size:12px !important">
                                    <div class="row "  >
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered" id="collegesection_datatable" width="100%" >
                                                      <thead class="thead-light">
                                                            <tr>
                                                                  <th width="3%" rowspan="2"></th>
                                                                  <th width="12%" rowspan="2" class="p-0 align-middle pl-2 text-center" class="p-0 align-middle pl-2">Section</th>
                                                                  <th width="30%" rowspan="2" class="align-middle">Subject Description</th>
                                                                  <th colspan="2" class="p-0 align-middle text-center" width="4%" colspan="2">Units</th>
                                                                  <th rowspan="2" class="text-center p-0 align-middle" width="4%">Cap.</th>
                                                                  <th rowspan="2" class="text-center p-0 align-middle" width="6%">Students</th>
                                                                  <th rowspan="2" width="25%" class="align-middle">Schedule</th>
                                                                  <th rowspan="2" width="10%" class="align-middle">Instructor</th>
                                                                  <th rowspan="2" width="6%"></th>
                                                            </tr>
                                                            <tr>
                                                                  <th class="text-center p-1 border-right-1" style="font-size:.6rem !important">Lec</th>
                                                                  <th class="text-center p-1" style="font-size:.6rem !important; border-right: 1px solid #dee2e6;font-size:.6rem !important">Lab</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                               
                                          </div>
                                          <div class="col-md-12">
                                                <span class="badge badge-primary">RS</span> - Regular Schedule <span class="ml-2 mr-2"> | </span> <span class="badge badge-warning">SP</span> - Special Schedule
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
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      {{-- <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script> --}}
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>


     
      
      <script>
            var routes = {
                  'cllgschdgrpList': '{{route('cllgschdgrpList')}}' ,
                  'cllgschdgrpSelect': '{{route('cllgschdgrpSelect')}}' ,
                  'cllgschdgrpDatatable': '{{route('cllgschdgrpDatatable')}}' ,
                  'cllgschdgrpCreate': '{{route('cllgschdgrpCreate')}}' ,
                  'cllgschdgrpUpdate': '{{route('cllgschdgrpUpdate')}}' , 
                  'cllgschdgrpDelete': '{{route('cllgschdgrpDelete')}}' ,
            }

            var allowButtons = true;
            var currentPortal = @json(Session::get('currentPortal'));
            var school = @json($schoolinfo->abbreviation)

            // if(currentPortal == 17 || currentPortal == 3 || ( school == 'SBC' )){
            //       allowButtons = true;
            // }

            

      </script>
      
      @include('superadmin.pages.college.js.college-schedgroup')
      @include('superadmin.pages.college.js.college-schedlist')
      @include('superadmin.pages.college.js.college-subjsched')
     



      <script>
            $(document).ready(function(){
                  var dean = @json($dean)

                  $('#printable_dean').select2({
                        'data':dean,
                        'placeholder':'Select Dean'
                  })
            })
      </script>

      <script>
            $(document).ready(function(){
                  schdgrpLoadResources($('#filter_sy').val(),$('#filter_semester').val())
            })
      </script>

      <script>
            $('#filter_schedulegroup').select2({
                  allowClear:true,
                  placeholder: "All",
                  ajax: {
                        url: routes.cllgschdgrpSelect,
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });

            $('#filter_room').select2({
                  placeholder: "All",
                  allowClear:true,
                  ajax: {
                        url: '/college/subject/schedule/rooms',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });


            $('#filter_teacher').select2({
                  placeholder: "All",
                  allowClear:true,
                  ajax: {
                        url: '/college/subject/schedule/teachers',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });

      </script>

      <script>


            display_sched_collegesections()

            var all_sched = []
            var all_sched_section = []
            var all_sched_enrolled = []
            var all_sched_detail = []
            var all_sched_student = []
            var all_sched_groupdetail = []
            var seleted_id = null
            var selected_detail = []
            var firstPrompt = true

            function display_sched_collegesections(){

                  $("#collegesection_datatable").DataTable({
                        destroy: true,
                        autoWidth: false,
                        stateSave: true,
                        lengthChange : false,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: '/student/loading/allsched',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    filtersubjgroup:$('#filter_schedulegroup').val(),
                                    filterroom:$('#filter_room').val(),
                                    filterteacher:$('#filter_teacher').val(),
                                    filterclasstype:$('#filter_classtype').val(),
                              },
                              dataSrc: function ( json ) {
                                    all_sched = json.data[0].college_classsched
                                    all_sched_section = json.data[0].section
                                    all_sched_enrolled = json.data[0].enrolled
                                    all_sched_detail = json.data[0].scheddetail
                                    all_sched_student = json.data[0].all_stud_sched
                                    all_sched_groupdetail = json.data[0].sched_group_detail

                                    if(seleted_id != null){
                                          empty_form()
                                          csl_sched_form_detail()
                                    }

                                    if(firstPrompt){
                                          
                                          Toast.fire({
                                                type: 'info',
                                                title: json.recordsTotal+' sections(s) found.'
                                          })

                                          firstPrompt = false
                                    }


                                    return all_sched;
                              }
                        },
                        order: [
                                          [ 1, "asc" ]
                                    ],
                        columns: [
                                    { "data": null },
                                    { "data": "schedgroupdesc" },
                                    // { "data": "subjCode" },
                                    { "data": "subjDesc" },
                                    { "data": "lecunits" },
                                    { "data": "labunits" },
                                    { "data": "capacity" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 8,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
                                          if(rowData.lastname != null){
                                                $(td)[0].innerHTML = rowData.lastname+', '+rowData.firstname+'<p class="mb-0" style="font-size:.7rem" data-se>'+rowData.tid+'</p>';

                                          }else{
                                                $(td)[0].innerHTML = null
                                                // $(td)[0].innerHTML = '<a style="font-size:.65rem !important" href="javascript:void(0)" class="add_teacher" data-id="'+rowData.id+'" data-subjdesc="Push-up"  data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">Add Teacher</a>'
                                          }
                                          
                                          $(td).addClass('align-middle')
                                          $(td).attr('style','font-size:.6rem !important')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var schedgroup_detail = all_sched_groupdetail.filter(x=>x.schedid == rowData.id)
                                          var text = '';
                                    
                                          $.each(schedgroup_detail,function(a,b){
                                               
                                                var collegecourse = b.courseabrv
                                                if(b.courseabrv == null){
                                                      collegecourse = b.collegeabrv
                                                }

                                                text += '<span class=" badge badge-primary  mt-1" style="font-size:.65rem !important; white-space:normal" >'+collegecourse+' - '+(b.levelid -  16 )+' '+b.schedgroupdesc+'</span> <br>'
                                          })
                                          // var sectiondesc = all_sched_section.filter(x=>x.id == rowData.sectionID)
                                          // if(sectiondesc.length > 0){
                                          //       $(td).text(sectiondesc[0].sectionDesc)
                                          // }else{
                                          //       $(td).text(null)
                                          // }
                                          
                                          $(td)[0].innerHTML = text
                                          $(td).addClass('align-middle')
                                          $(td).addClass('p-1')
                                    }
                              },
                              {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var specificationBadge = '<span class="badge badge-primary">RS</span>'
                                          if(rowData.section_specification == 2){
                                                specificationBadge = '<span class="badge badge-warning">SP</span>'
                                          }
                                          $(td)[0].innerHTML = specificationBadge
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          // var text = ''+rowData.subjDesc+'<p class="mb-0" style="font-size:.7rem" data-se>'+rowData.subjCode+'</p>';

                                          var text = rowData.subjDesc+'<p class="mb-0" style="font-size:.7rem" >'+rowData.subjCode+'</p>';
                                          $(td)[0].innerHTML = text
                                          
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                    
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var enrolled_count = all_sched_enrolled.filter(x=>x.schedid == rowData.id)
                                          var all_loaded_student_count = all_sched_student.filter(x=>x.schedid == rowData.id)
                                          var enrolled = 0
                                          var loaded = 0
                                          if(enrolled_count.length > 0){
                                                enrolled = enrolled_count[0].enrolled 
                                          }
                                          
                                          if(all_loaded_student_count.length > 0){
                                                loaded = all_loaded_student_count[0].enrolled
                                          }

                                          $(td)[0].innerHTML  = '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_students" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-offset="55%" data-placement="top" data-original-title="Enrolled Students">'+enrolled+'</a>' + ' / '+ '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_loaded_students" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-offset="55%" data-placement="top"  data-original-title="Loaded Students">'+loaded+'</a>'

                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }

                                    
                              },
                              {
                                    'targets': 7,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
                                          var temp_sched = []
                                          if(temp_data.length > 0){
                                                $.each(temp_data,function(a,b){
                                                      var check = temp_sched.filter(x=>x.stime == b.stime && x.etime == b.etime && x.schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                                                      if(check.length == 0){
                                                            temp_sched.push({
                                                                  'schedotherclass':b.schedotherclass,
                                                                  'roomname':b.roomname,
                                                                  'etime':b.etime,
                                                                  'stime':b.stime,
                                                                  'days':[],
                                                                  'roomid':b.roomid
                                                            });
                                                            var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime && x.schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                                                            if(get_index != -1){
                                                                  temp_sched[get_index].days.push(b.day)
                                                            }
                                                      }else{
                                                            var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime && x.schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                                                            if(get_index != -1){
                                                                  temp_sched[get_index].days.push(b.day)
                                                            }
                                                      }
                                                })
                                                var text = ''
                                                $.each(temp_sched,function(a,b){
                                                      var temp_stime = moment(b.stime, 'HH:mm a').format('hh:mm a')
                                                      
                                                      if(b.schedotherclass != null){
                                                           text += b.schedotherclass.substring(0, 3)+'.: '
                                                      }
                                                     

                                                      text += moment(b.stime, 'HH:mm a').format('hh:mm A')+' - '+moment(b.etime, 'HH:mm a').format('hh:mm A') +' / '

                                                      var sorted_days = b.days.sort()

                                                      $.each(sorted_days,function(c,d){
                                                            text += d == 1 ? 'M' :''
                                                            text += d == 2 ? 'T' :''
                                                            text += d == 3 ? 'W' :''
                                                            text += d == 4 ? 'Th' :''
                                                            text += d == 5 ? 'F' :''
                                                            text += d == 6 ? 'Sat' :''
                                                            text += d == 7 ? 'Sun' :''
                                                      })

                                                      if(b.roomname != null){
                                                            text += ' / '+b.roomname
                                                      }
                                                      
                                                      if(temp_sched.length != a+1){
                                                            text += ' <br> '
                                                      }
                                                })

                                                
                                                $(td)[0].innerHTML = text
                                                // $(td)[0].innerHTML = text + '<br><a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
                                          }else{
                                                $(td)[0].innerHTML = null
                                                // $(td)[0].innerHTML = '<a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
                                          }
                                          $(td).addClass('align-middle')
                                    },
                                    
                              },
                              {
                                    'targets': 9,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          if(allowButtons){
                                                var text = 
                                                
                                                '<a style="font-size:.75rem !important" href="javascript:void(0)" class="btn-to-modal" data-id="'+rowData.id+'" data-subjdesc="Push-up" data-toggle="tooltip" data-placement="top" title="Assign Instructor" data-modal="modal-csl-update-instructor"><i class="nav-icon fas fa-user-plus"></i></a>' +
                                                
                                                '<a style="font-size:.75rem !important" href="javascript:void(0)" class="btn-to-modal ml-2" data-id="'+rowData.id+'" data-subjdesc="Push-up" data-toggle="tooltip" data-placement="top" title="Update Schedule" data-modal="csl-schedule-form"><i class="nav-icon fa fa-calendar"></i></a> <br>' +
                                                
                                                '<a style="font-size:.75rem !important" href="javascript:void(0)" class="btn-to-modal" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Update Schedule Information" data-modal="csl-schedule-information-form"><i class="nav-icon fas fa-edit" ></i></a>'+

                                                '<a style="font-size:.75rem !important" href="javascript:void(0)" class=" ml-2 btn-csl-remove-schedule" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Remove Schedule" ><i class="nav-icon fas fa-trash-alt text-danger" ></i></a>' +
                                         
                                                ' <a style="font-size:.75rem !important" href="javascript:void(0)" class=" ml-2 btn-csl-" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Created by: '+rowData.name+'" ><i class="nav-icon fas fa-info text-warning" ></i></a>'
                                          }else{
                                                text = ''
                                          }

                                          $(td)[0].innerHTML = text
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              }
                        ],
                        "initComplete": function(settings, json) {
                              $(function () {
                                    $('[data-toggle="tooltip"]').tooltip()
                              })

                        }

                  });

                  var label_text = $($("#collegesection_datatable_wrapper")[0].children[0])[0].children[0]

                  if(allowButtons){
                        $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="button_to_createsection_modal" hidden>Create Section</button><button class="btn btn-primary btn-sm subjsched_form" >Create Subject Sched</button><button class="btn btn-primary btn-sm ml-2" id="schedgroup_to_modal">Sections</button><button hidden class="btn btn-primary btn-sm ml-2" id="button_to_studentloading_modal">Sched List</button><button class="btn btn-default btn-sm ml-2" id="printSched"><i class="fa fa-print"></i> Print Schedule</button>'
                  }else{
                        $(label_text)[0].innerHTML = null
                  }
                 

                  

            }



      </script>

      <script>


            $(document).on('click','#printSched',function(){

                  var filtersyid = $('#filter_sy').val()
                  var filtersemid = $('#filter_semester').val()
                  var filtersubjgroup = $('#filter_schedulegroup').val() == null ? '' : $('#filter_schedulegroup').val()
                  var filterroom = $('#filter_room').val() == null ? '' : $('#filter_room').val()
                  var filterteacher = $('#filter_teacher').val()  == null ? '' : $('#filter_teacher').val()
                  var filterclasstype = $('#filter_classtype').val()  == null ? '' : $('#filter_classtype').val()

               
                  const search = JSON.stringify({"value":$('input[type="search"]').val(),"regex":false});


                  window.open('/student/loading/allsched/printable?syid='+filtersyid+'&semid='+filtersemid+'&filtersubjgroup='+filtersubjgroup+'&filterroom='+filterroom+'&filterteacher='+filterteacher+'&filterclasstype='+filterclasstype+'&search='+search, '_blank');
            })

            $(document).on('click','#schedgroup_to_modal',function(){
                  schedgroup_datatable()
            })

            $('#collegeschedlist_edit_teacher').select2({
                  placeholder: "Select Teacher",
                  allowClear:true,
                  ajax: {
                        url: '/college/subject/schedule/teachers',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });


           

      </script>

      <script>

            $(document).ready(function(){

                  $('#filter_course').select2({
                        placeholder: "All",
                        allowClear:true,
                        // delay: 250 ,
                        ajax: {
                              url: '/college/section/courses',
                              data: function (params) {
                                    var query = {
                                          search: params.term,
                                          page: params.page || 0
                                    }
                                    return query;
                              },
                              dataType: 'json',
                              processResults: function (data, params) {
                                    params.page = params.page || 0;
                                    return {
                                          results: data.results,
                                          pagination: {
                                                more: (params.page * 10) < data.count_filtered
                                          }
                                    };
                              }
                        }
                  });

            //       $('#createsection_input_course').select2({
            //             placeholder: "All",
            //             allowClear:true,
            //             // delay: 250 ,
            //             ajax: {
            //                   url: '/college/section/courses',
            //                   data: function (params) {
            //                         var query = {
            //                               search: params.term,
            //                               page: params.page || 0
            //                         }
            //                         return query;
            //                   },
            //                   dataType: 'json',
            //                   processResults: function (data, params) {
            //                         params.page = params.page || 0;
            //                         return {
            //                               results: data.results,
            //                               pagination: {
            //                                     more: (params.page * 10) < data.count_filtered
            //                               }
            //                         };
            //                   }
            //             }
            //       });

            //       $('#createsection_input_college').select2({
            //             placeholder: "All",
            //             allowClear:true,
            //             // delay: 250 ,
            //             ajax: {
            //                   url: '/setup/college/list/select2',
            //                   data: function (params) {
            //                         var query = {
            //                               withfilter:false,
            //                               search: params.term,
            //                               page: params.page || 0
            //                         }
            //                         return query;
            //                   },
            //                   dataType: 'json',
            //                   processResults: function (data, params) {
            //                         params.page = params.page || 0;
            //                         return {
            //                               results: data.results,
            //                               pagination: {
            //                                     more: data.more
            //                               }
            //                         };
            //                   }
            //             }
            //       });

            //       $('#createsection_input_curriculum').select2({
            //             placeholder: "All",
            //             allowClear:true,
            //             ajax: {
            //                   url: '/college/section/course/curriculum',
            //                   data: function (params) {
            //                         var query = {
            //                               courseid:$('#createsection_input_course').val(),
            //                               search: params.term,
            //                               page: params.page || 0
            //                         }
            //                         return query;
            //                   },
            //                   dataType: 'json',
            //                   processResults: function (data, params) {
            //                         params.page = params.page || 0;
            //                         return {
            //                               results: data.results,
            //                               pagination: {
            //                                     more: (params.page * 10) < data.count_filtered
            //                               }
            //                         };
            //                   }
            //             }
            //       });

                 

                  
                  
            //       $('#input_room').select2({
            //             placeholder: "All",
            //             allowClear:true,
            //             ajax: {
            //                   url: '/college/section/schedule/rooms',
            //                   data: function (params) {
            //                         var query = {
            //                               search: params.term,
            //                               page: params.page || 0
            //                         }
            //                         return query;
            //                   },
            //                   dataType: 'json',
            //                   processResults: function (data, params) {
            //                         params.page = params.page || 0;
            //                         return {
            //                               results: data.results,
            //                               pagination: {
            //                                     more: data.more
            //                               }
            //                         };
            //                   }
            //             }
            //       });

            //       $('#input_teacher').select2({
            //             placeholder: "All",
            //             allowClear:true,
            //             ajax: {
            //                   url: '/college/section/schedule/teacher',
            //                   data: function (params) {
            //                         var query = {
            //                               search: params.term,
            //                               page: params.page || 0
            //                         }
            //                         return query;
            //                   },
            //                   dataType: 'json',
            //                   processResults: function (data, params) {
            //                         params.page = params.page || 0;
            //                         return {
            //                               results: data.results,
            //                               pagination: {
            //                                     more: data.more
            //                               }
            //                         };
            //                   }
            //             }
            //       });
                 
            })



          

      </script>

      <script>
            $(document).ready(function(){
                  $('#filter_sy').select2();
                  $('#filter_semester').select2();
                  $('#filter_classtype').select2({
                        allowClear:true,
                        placeholder:'All'
                  });
            })
      </script>


     
      <script>

            var selected_sect = null

             $(document).ready(function(){

                  var filter_courses = [];
                  var all_gradelevel = @json($gradelevel);
                  var rooms = [];
                
                  var specification = [
                        {
                              'text':'Regular',
                              'id':1
                        },
                        {
                              'text':'Special',
                              'id':2
                        },
                  ]


                  $("#createsection_input_specification").empty()
                  $('#createsection_input_specification').select2({
                        data: specification,
                        placeholder: "Select specification",
                  })

                  $("#createsection_input_gradelevel").empty()
                  $("#createsection_input_gradelevel").append('<option value="">Select gradelevel</option>')
                  $("#createsection_input_gradelevel").val("")
                  $('#createsection_input_gradelevel').select2({
                        allowClear: true,
                        data: all_gradelevel,
                        placeholder: "Select gradelevel",
                  })

                 

                  // var all_courses = []
                  // get_courses()

                  // function get_courses(){
                  //       return false
                  //       $.ajax({
			// 		type:'GET',
			// 		url: '/college/section/courses',
                  //             data:{
                  //                   syid:$('#filter_sy').val(),
                  //                   semid:$('#filter_semester').val()
                  //             },
			// 		success:function(data) {

                  //                   if(data.length == 0){
                  //                         $('#no_course_holder').removeAttr('hidden')
                  //                   }else{
                  //                         $('#no_course_holder').attr('hidden','hidden')
                  //                   }
                                    
                  //                   filter_courses = data
                  //                   all_courses = data
                  //                   $("#createsection_input_course").empty()
                  //                   $("#createsection_input_course").append('<option value="">Select Course</option>')
                  //                   $("#createsection_input_course").val("")
                  //                   $('#createsection_input_course').select2({
                  //                         allowClear: true,
                  //                         data: filter_courses,
                  //                         placeholder: "Select courses",
                  //                   })

                  //                   $("#createsection_input_gradelevel").empty()
                  //                   $("#createsection_input_gradelevel").append('<option value="">Select gradelevel</option>')
                  //                   $("#createsection_input_gradelevel").val("")
                  //                   $('#createsection_input_gradelevel').select2({
                  //                         allowClear: true,
                  //                         data: all_gradelevel,
                  //                         placeholder: "Select gradelevel",
                  //                   })

                  //                   $("#filter_course").empty()
                  //                   $('#filter_course').append('<option value="">All</option>')
                  //                   $("#filter_course").select2({
                  //                         allowClear: true,
                  //                         data: filter_courses,
                  //                         placeholder: "All",
                  //                   })

                                   

			// 		}
			// 	})
                  // }

                  var all_subjects = []

                  $(document).on('click','#add_subject_button',function(){

                        var temp_sect = all_collegesection.filter(x=>x.id == selected_sect)
                    
                        $('#all_subject_modal').modal()
                        $.ajax({
                              type:'GET',
                              url: '/college/section/subjects/all',
                              data:{
                                    'courseid':temp_sect[0].courseID,
                                    'curriculumid':temp_sect[0].curriculumid
                              },
                              success:function(data) {
                                    all_subjects = data
                                    $("#input_subject").empty()
                                    $("#input_subject").append('<option value="">Select subjects</option>')
                                    $("#input_subject").val("")
                                    $('#input_subject').select2({
                                          allowClear: true,
                                          data: all_subjects,
                                          placeholder: "Select subjects",
                                    })
                              }
                        })
                  })

                  $(document).on('click','#update_subject_button',function(){
                        var temp_section = all_collegesection.filter(x=>x.id == selected_sect)
                        update_section_subjects(temp_section)
                  })

                  $(document).on('click','#add_subject_to_section',function(){
                        var temp_section = all_collegesection.filter(x=>x.id == selected_sect)
                        $('#all_subject_modal').modal()
                        update_section_subjects(temp_section)
                       
                  })
                  
                  function update_section_subjects(temp_section){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/sections/add/subject',
                              data:{
                                    subjid:$('#input_subject').val(),
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    sectionid:selected_sect,
                                    courseid:temp_section[0].courseID
                              },
                              success:function(data) {
                                    try {
                                          if(data[0].status == 0){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].data 
                                                })
                                          }else{
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Added successfully'
                                                })
                                                $('#sched_plot_holder').empty()
                                                $('#sched_plot_holder').append(data)
                                          }
                                    }
                                    catch(err) {
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Added successfully'
                                          })
                                          $('#sched_plot_holder').empty()
                                          $('#sched_plot_holder').append(data)
                                    }

                              }
                        })
                  }

                  $(document).on('click','.remove_subject',function(){

                        var temp_schedid = $(this).attr('data-id')

                        var temp_section = all_collegesection.filter(x=>x.id == selected_sect)
                        // if(temp_section[0].section_specification == 1){
                        //       // Toast.fire({
                        //       //       type: 'error',
                        //       //       title: 'Cannont remove subject'
                        //       // })
                        //       // return false
                        // }else{
                              $.ajax({
                                    type:'GET',
                                    url: '/chairperson/sections/remove/subject',
                                    data:{
                                          schedid:temp_schedid,
                                          sectionid:selected_sect
                                    },
                                    success:function(data) {
                                          try {
                                                if(data[0].status == 0){
                                                      Toast.fire({
                                                            type: 'warning',
                                                            title: data[0].data
                                                      })
                                                }else{
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                      load_sched()
                                                }
                                          }
                                          catch(err) {
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Deleted successfully'
                                                })
                                                $('#sched_plot_holder').empty()
                                                $('#sched_plot_holder').append(data)
                                          }
                                    }
                              })
                        // }
                       
                  })

                  $(document).on('change','#createsection_input_course',function(){
                        var courseid = $(this).val()
                        $("#createsection_input_curriculum").empty()
                        // if(courseid != ""){
                        //       var temp_curr = all_courses.filter(x=>x.id == courseid)[0].curriculum
                        //       $('#createsection_input_curriculum').select2({
                        //             data: temp_curr,
                        //             placeholder: "Select curriculum",
                        //       })
                        //       $('#createsection_input_curriculum option:eq(0)').prop('selected',true);
                        // }
                  })

                  var trigger_notification = true

                  $(document).on('change','#createsection_input_curriculum, #createsection_input_gradelevel, #createsection_input_course',function(){
                        if(trigger_notification){
                              load_subject_package()
                        }
                  })

                  function load_subject_package(){
                        var levelid = $('#createsection_input_gradelevel').val()
                        var courseid = $('#createsection_input_course').val()
                        var curriculum = $('#createsection_input_curriculum').val()
                        var specification = $('#createsection_input_specification').val()
                        var syid = $('#filter_sy').val()
                        var semid = $('#filter_semester').val()

                        $.ajax({
                              type:'GET',
                              url: '/college/section/courses/subjects',
                              data:{
                                    levelid:levelid,
                                    courseid:courseid,
                                    curriculum:curriculum,
                                    specification:specification,
                                    syid:syid,
                                    semid:semid,
                                    tempsect:sectionid
                              },
                              success:function(data) {

                                    $('#subject_package').empty()

                                    if(data.length == 0){
                                    
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No subjects found.'
                                          })

                                          $('#subject_package').append('<li class="list-group-item p-2">No subjects found</li>')

                                          // $('#subject_package').append('<tr><td colspan="2"><i>No subjects found</i></td></tr>')

                                          
                                    }
                                    else{
                                          $.each(data,function(a,b){
                                                // $('#subject_package').append('<li class="list-group-item p-2"><input class="mt-2" type="checkbox">'+b.subjCode+' - '+b.subjDesc+'</li>')
                                                var checked = 'checked="checked"'
                                                if(b.included == 0){
                                                      checked = ''
                                                }

                                                var disabled = ''
                                                if(sectionid != null){
                                                      disabled = 'disabled'
                                                }

                                                $('#subject_package').append(' <tr><td class="text-center align-middle p-0"><input type="checkbox" class="mt-1 inc_subj" data-id="'+b.id+'" '+checked+' '+disabled+'></td><td class="align-middle">'+b.subjCode+' - '+b.subjDesc+'</td></tr>')
                                          })


                                    }
                                    
                              }
                        })

                  }


                  $(document).on('change','#createsection_input_specification',function(){

                        if($(this).val() == 1){
                              if(trigger_notification){
                                    load_subject_package()
                              }
                              $('#createsection_input_gradelevel').removeAttr('disabled')
                              $('#createsection_input_curriculum').removeAttr('disabled')
                        }else{

                              $('#createsection_input_gradelevel').val("").change()
                              $('#createsection_input_curriculum').val("").change()
                              $('#createsection_input_gradelevel').attr('disabled','disabled')
                              $('#subject_package').empty()

                              
                              // $('#subject_package').append(' <tr><td colspan="2"><i>Included subjects will display after selecting a grade level, course and curriculum .</i></td></tr>')

                              $('#subject_package').append('<li class="list-group-item p-2"><i>Included subjects will display after selecting a grade level, course and curriculum.</i></li>')
                             
                        }

                        
                  })


                  $(document).on('click','#button_to_createsection_modal',function(){

                        console.log("sdfs")
                        trigger_notification = false

                        section_action = 'create'
                        $('#createsection_input_gradelevel').val("").change()
                        $('#createsection_input_course').val("").change()
                        $('#createsection_input_curriculum').val("").change()
                        $('#createsection_input_specification').val(1).change()
                        $('#createsection_input_name').val("")

                        $('#button_to_createsection_function').text('Create Section')
                        $('#button_to_createsection_function').removeClass('btn-success')
                        $('#button_to_createsection_function').addClass('btn-primary')

                        $('#createsection_input_gradelevel').removeAttr('disabled')
                        $('#createsection_input_course').removeAttr('disabled')
                        $('#createsection_input_curriculum').removeAttr('disabled')
                        $('#createsection_input_specification').removeAttr('disabled')

                        $('#createsection_sy_label').text( $("#filter_sy option:selected").text())
                        $('#createsection_semester_label').text( $("#filter_semester option:selected").text())
                        $('#createsection_modal').modal()

                        $('#subject_package').empty()
                        $('#subject_package').append('<li class="list-group-item p-2"><i>Included subjects will display after selecting a grade level, course and curriculum.</i></li>')

                        sectionid = null
                        trigger_notification = true

                   
                      

                  })


                  $(document).on('click','.delete',function(){

                        var temp_sectionid = $(this).attr('data-id')

                        Swal.fire({
                              title: 'Are you sure?',
                              text: "You won't be able to revert this!",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, delete section!'
                        })
                        .then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/chairperson/sections/delete',
                                          data:{
                                                sectionid:temp_sectionid,
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                      collegesection_datatable()
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].data
                                                      })
                                                }
                                                
                                          }
                                    })
                              }
                        })
                       
                  })

                  $(document).on('click','#button_to_createsection_function',function(){
                        if(section_action == 'create'){
                              create_section()
                        }else{
                              update_section()
                        }
                  })


                  function update_section(){

                        if(sectionname == ""){
                              Toast.fire({
                                    type: 'success',
                                    title: "Section name is required"
                              })
                        }

                        var syid = $('#filter_sy').val()
                        var semid = $('#filter_semester').val()
                        var sectionname = $('#createsection_input_name').val()

                        $.ajax({
					type:'GET',
					url: '/chairperson/sections/update',
                              data:{
                                    sectionid:sectionid,
                                    sectionname:sectionname,
                                    syid:syid,
                                    semid:semid,
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          collegesection_datatable()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                                    
					}
				})

                  }

                  function create_section(){

                        var levelid = $('#createsection_input_gradelevel').val()
                        var courseid = $('#createsection_input_course').val()
                        var collegeid = $('#createsection_input_college').val()
                        var curriculum = $('#createsection_input_curriculum').val()
                        var specification = $('#createsection_input_specification').val()
                        var sectionname = $('#createsection_input_name').val()
                        var syid = $('#filter_sy').val()
                        var semid = $('#filter_semester').val()

                        if(levelid == "" && specification == 1){
                              Toast.fire({
                                    type: 'error',
                                    title: "Grade level is required"
                              })
                              return false;
                        }
                        else if(courseid == "" && collegeid == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Course/College is required"
                              })
                              return false;
                        }
                        // else if(curriculum == "" && specification == 1){
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: "Curriculum is required"
                        //       })
                        //       return false;
                        // }
                        else if(specification == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Specification is required"
                              })
                              return false;
                        }else if(sectionname == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Section name is required"
                              })
                              return false;
                        }
                        

                        var excluded_subj = []

                        $('.inc_subj').each(function(a,b){
                              if($(this).prop('checked') == false){
                                    excluded_subj.push($(this).attr('data-id'))
                              }
                        })
                        

                        $.ajax({
					type:'GET',
					url: '/chairperson/sections/create',
                              data:{
                                    levelid:levelid,
                                    collegeid:collegeid,
                                    courseid:courseid,
                                    curriculum:curriculum,
                                    specification:specification,
                                    sectionname:sectionname,
                                    syid:syid,
                                    semid:semid,
                                    excluded_subj:excluded_subj,
                                    capacity:$('#createsection_input_capacity').val()
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          collegesection_datatable()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                                    
					}
				})

                  }

                  $('.reservationtime').daterangepicker({
                        timePicker: true,
                        startDate: '07:00 AM',
                        endDate: '08:00 AM',
                        timePickerIncrement: 60,
                        locale: {
                              format: 'hh:mm A',
                              cancelLabel: 'Clear'
                        }
                  })

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  // $('.select2').select2()

                 

                  $('#filter_gradelevel').empty()
                  $('#filter_gradelevel').append('<option value="">All</option>')
                  $("#filter_gradelevel").select2({
                        data: all_gradelevel,
                        allowClear: true,
                        placeholder: "All",
                  })


                  

                  $("#input_term").val("")
                  $('#input_term').select2({
                        allowClear: true,
                        placeholder: "Select Classification",
                  })


                  // $("#input_room_1").val("")
                  // $('#input_room_1').select2({
                  //       allowClear: true,
                  //       placeholder: "Select Room",
                  // })

                  // $("#input_teacher_1").val("")
                  // $('#input_teacher_1').select2({
                  //       allowClear: true,
                  //       placeholder: "Select Teacher",
                  // })


                  
                  collegesection_datatable()

                  $(document).on('change','#filter_sy, #filter_semester, #filter_gradelevel, #filter_course',function(){
                        collegesection_datatable()
                  })
                  $(document).on('change','#filter_sy, #filter_semester , #filter_schedulegroup,  #filter_teacher,  #filter_room, #filter_classtype',function(){
                        // get_courses()
                        // get_all_sched()
                        display_sched_collegesections()
                  })

                  

                  var selected_subj = null;
                  var selected_scheddetail = null

                  
            

                  $(document).on('click','.add_sched',function(){
                        allowconflict = 0
                        var sy_label = $("#filter_sy option:selected").text();
                        var sem_label = $("#filter_semester option:selected").text();
                        selected_subj = $(this).attr('data-id')
                        selected_scheddetail = null
                        $('.sy_label').text(sy_label)
                        $('.semester_label').text(sem_label)
                        $('#subj_label').text($(this).attr('data-subjdesc'))
                        $('#add_schedule_modal').modal()
                        action = 'create'
                        $('#create_schedule').text('Create Schedule')
                        $('#create_schedule').addClass('btn-primary')
                        $('#create_schedule').removeClass('btn-success')

                        $('#input_term').val("").change()
                        $('#input_room').val("").empty()
                        $('#input_teacher').val("").empty()
                        $('#time').val('7:30 AM - 8:30 AM').change()

                        $('#con_stat').text("")
                        $('#con_sect').text("")
                        $('#con_subj').text("")
                        $('#con_day').text("")
                        $('#con_time').text("")
                        $('#sched_con_holder').attr('hidden','hidden')

                        $('.day').prop('checked',false)
                  })

                  $(document).on('click','.day',function(){
                        allowconflict = 0
                        $('#sched_con_holder').attr('hidden','hidden')
                        $('#sched_conflict_holder').empty()
                        $('#con_stat').text("")
                        $('#con_sect').text("")
                        $('#con_subj').text("")
                        $('#con_day').text("")
                        $('#con_time').text("")
                        if(section_action == 'create'){
                              $('#create_schedule').text('Create Schedule')
                        }else{
                              $('#create_schedule').text('Update Schedule')
                        }
                  })

                  $(document).on('change','#input_teacher , #time , #input_room',function(){
                        allowconflict = 0
                        $('#sched_con_holder').attr('hidden','hidden')
                        $('#sched_conflict_holder').empty()
                        $('#con_stat').text("")
                        $('#con_sect').text("")
                        $('#con_subj').text("")
                        $('#con_day').text("")
                        $('#con_time').text("")
                        if(section_action == 'create'){
                              $('#create_schedule').text('Create Schedule')
                        }else{
                              $('#create_schedule').text('Update Schedule')
                        }

                  })
                  

                  $(document).on('click','.remove_schedule',function(){
                        var temp_id = $(this).attr('data-id')
                        $.ajax({
					type:'GET',
					url: '/college/section/schedule/remove',
                              data:{
                                    scheddetailid:temp_id,
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          load_sched()
                                          load_sched_ajax()
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
					}
				})
                  })

                  var action = 'create'
                  var selected_sched  = null;
                  var hederid = null

                  $(document).on('click','.update_schedule',function(){
                        allowconflict = 0
                        $('#con_stat').text("")
                        $('#con_sect').text("")
                        $('#con_subj').text("")
                        $('#con_day').text("")
                        $('#con_time').text("")
                        $('#sched_con_holder').attr('hidden','hidden')

                        var temp_id = $(this).attr('data-id')
                        var sy_label = $("#filter_sy option:selected").text();
                        var sem_label = $("#filter_semester option:selected").text();
                        selected_subj = $(this).attr('data-id')
                        selected_scheddetail = $(this).attr('data-id')
                        $('.sy_label').text(sy_label)
                        $('.semester_label').text(sem_label)
                        $('#subj_label').text($(this).attr('data-subjdesc'))
                        $('#add_schedule_modal').modal()
                        
                        headerid = $(this).attr('data-header')

                        var header_info = all_sched.filter(x=>x.id == headerid)
                        var shed_detail = header_info[0].schedule.filter(x=>x.detailid == temp_id)

                        $('#input_term').val(shed_detail[0].classification).change()

                        $('#input_room').append('<option value="'+shed_detail[0].roomid+'">'+shed_detail[0].roomname+'</option')
                        $('#input_room').val(shed_detail[0].roomid).change()

                        $('#input_teacher').append('<option value="'+header_info[0].teacherID+'">'+header_info[0].teacher+'</option')
                        $('#input_teacher').val(header_info[0].teacherID).change()

                        $('#time').val(shed_detail[0].time).change()

                        $('.day').prop('checked',false)
                        $.each(shed_detail[0].days,function(a,b){
                              $('.day[value="'+b+'"]').prop('checked',true)
                        })
                        $('#subj_label').text(header_info[0].subjDesc)
                        from_time = shed_detail[0].time
                        $('#create_schedule').text('Update Schedule')
                        $('#create_schedule').removeClass('btn-primary')
                        $('#create_schedule').addClass('btn-success')
                        action = 'update'
                     
                  })


                  
                  var all_sched = []
                  var from_time = null
                  
                  $(document).on('click','.view_schedule',function(){
                        var temp_id = $(this).attr('data-id')
                        selected_sect = temp_id
                        var temp_sect = all_collegesection.filter(x=>x.id == temp_id)

                        if(temp_sect[0].section_specification == 1 ){
                              $('#update_subject_button').removeAttr('hidden','hidden')
                              $('#add_subject_button').attr('hidden')
                        }else{
                              $('#add_subject_button').removeAttr('hidden','hidden')
                              $('#update_subject_button').attr('hidden','hidden')
                        }

                        if(temp_sect[0].issubjsched == 1){
                              $('#update_subject_button').attr('hidden','hidden')
                              $('#add_subject_button').attr('hidden','hidden')
                              $('#print_schedule').attr('hidden','hidden')
                        }

                        $('#section_name')[0].innerHTML = '<i>'+temp_sect[0].sectionDesc+'</i>'
                        $('#section_label').text(temp_sect[0].sectionDesc)
                        $('#course_label').text(temp_sect[0].courseDesc)
                        $('#gl_label').text(temp_sect[0].levelname)
                        $('#sectionsched_modal').modal()
                        load_sched_ajax()
                        load_sched()
                  })

                  function load_sched_ajax(){
                        $.ajax({
					type:'GET',
					url: '/college/section/schedule/list',
                              data:{
                                    sectionid:selected_sect,
                              },
					success:function(data) {
                                    all_sched = data
					}
				})
                  }

                  function load_sched(){
                        $.ajax({
					type:'GET',
					url: '/college/section/schedule/list/plot',
                              data:{
                                    sectionid:selected_sect,
                              },
					success:function(data) {
                                   $('#sched_plot_holder').empty()
                                   $('#sched_plot_holder').append(data)
                                   
					}
				})
                  }

                  $(document).on('click','#create_schedule',function(){
                        if(action == 'create'){
                              create_sched()
                        }else{
                              update_sched()
                        }
                  })


                  var section_action = 'create'

                  function update_sched(){

                        var term = $('#input_term').val()
                        var room = $('#input_room').val()
                        var time = $('#time').val()
                        var days = []
                        var teacher = $('#input_teacher').val()
                        $('.day').each(function(){
                              if($(this).is(":checked")){
                                    days.push($(this).val())
                              }
                        })
                        if(days.length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No days selected"
                              })
                              return false
                        }
                        $.ajax({
					type:'GET',
					url: '/college/section/schedule/update',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    term:term,
                                    room:room,
                                    time:time,
                                    from_time:from_time,
                                    days:days,
                                    headerid:headerid,
                                    teacherid:teacher,
                                    schedid:selected_subj,
                                    allowconflict:allowconflict
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          load_sched()
                                          load_sched_ajax()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          $('#create_schedule').text('Update Schedule')
                                          allowconflict = 0
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
                                                $('#con_stat').text(data[0].conflicttype)
                                                $('#con_sect').text(data[0].section)
                                                $('#con_subj').text(' ( '+data[0].subjcode+' ) '+data[0].subjdesc)
                                                $('#con_day').text(data[0].day)
                                                $('#con_time').text(data[0].time)
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'conflict'
                                                })
                                                $('#create_schedule').text('Conflict : Proceed Update')
                                                allowconflict = 1
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].data
                                                })
                                                $('#create_schedule').text('Update Schedule')
                                                allowconflict = 0
                                          }
                                    }
                                   
					}
				})
                  }

                  var allowconflict = 0;

                  function create_sched(){

                        var term = $('#input_term').val()
                        var room = $('#input_room').val()
                        var time = $('#time').val()
                        var days = []
                        var teacher = $('#input_teacher').val()
                        $('.day').each(function(){
                              if($(this).is(":checked")){
                                    days.push($(this).val())
                              }
                        })
                        if(days.length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No days selected"
                              })
                              return false
                        }
                        $.ajax({
					type:'GET',
					url: '/college/section/schedule/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    term:term,
                                    room:room,
                                    time:time,
                                    days:days,
                                    headerid:selected_subj,
                                    teacherid:teacher,
                                    allowconflict:allowconflict
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          load_sched()
                                          load_sched_ajax()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          
                                          $('#create_schedule').text('Create Schedule')
                                          allowconflict = 0
                                          $('#sched_con_holder').attr('hidden','hidden')
                                          $('#sched_conflict_holder').empty()
                                          $('#con_stat').text("")
                                          $('#con_sect').text("")
                                          $('#con_subj').text("")
                                          $('#con_day').text("")
                                          $('#con_time').text("")
                                          // get_all_sched()
                                    }else{
                                          if(data[0].data == 'conflict'){
                                                $('#sched_con_holder').removeAttr('hidden')
                                                $('#sched_conflict_holder').empty()
                                                $('#con_stat').text(data[0].conflicttype)
                                                $('#con_sect').text(data[0].section)
                                                $('#con_subj').text(' ( '+data[0].subjcode+' ) '+data[0].subjdesc)
                                                $('#con_day').text(data[0].day)
                                                $('#con_time').text(data[0].time)
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'conflict'
                                                })
                                                $('#create_schedule').text('Conflict : Proceed Create')
                                                allowconflict = 1
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].data
                                                })
                                                $('#create_schedule').text('Create Schedule')
                                                allowconflict = 0
                                          }
                                    }
                                   
					}
				})
                  }

                  get_teachers()

                  function get_teachers(){
                        return false
                        $.ajax({
					type:'GET',
					url: '/college/section/schedule/teacher',
                              data:{
                                    syid:$('#filter_sy').val(),
                              },
					success:function(data) {
                                    var all_teacher = data
                                    $("#input_teacher").empty()
                                    $("#input_teacher").append('<option value="">Select Teacher</option>')
                                    $("#input_teacher").val("")
                                    $('#input_teacher').select2({
                                          allowClear: true,
                                          data: all_teacher,
                                          placeholder: "Select teacher",
                                    })


                                    $("#edit_teacher").empty()
                                    $("#edit_teacher").append('<option value="">Select Teacher</option>')
                                    $("#edit_teacher").val("")
                                    $('#edit_teacher').select2({
                                          allowClear: true,
                                          data: all_teacher,
                                          placeholder: "Select teacher",
                                    })

                                    $("#input_room").empty()
                                    $("#input_room").append('<option value="">Select Room</option>')
                                    $("#input_room").val("")
                                    $('#input_room').select2({
                                          allowClear: true,
                                          data: rooms,
                                          placeholder: "Select Room",
                                    })

                                    $("#input_term").val("")
                                    $('#input_term').select2({
                                          allowClear: true,
                                          placeholder: "Select Classification",
                                    })

					}
				})
                  }

                  var sectionid = null


                  
                  $(document).on('click','.view_students',function(){
                        sectionid = $(this).attr('data-id')
                        $('#list_label').text('Section : '+$(this).attr('data-text'))
                        // $('#list_label').text( $(this).attr('data-text'))
                        var temp_students = all_collegesection.filter(x=>x.id == sectionid)[0].students

                        $('#enrolled_modal').modal()

                        $("#student_list").DataTable({
                              destroy: true,
                              lengthChange : false,
                              data:temp_students,
                              columns: [
                                    { "data": "student" },
                                    { "data": "levelname" },
                                    { "data": "courseabrv" },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var enrolledstattus = ''
                                                if(rowData.isenrolled == 1){
                                                      enrolledstattus = '<span class="badge badge-success float-right">Enrolled</span>'
                                                }
                                                $(td)[0].innerHTML = rowData.student+enrolledstattus
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        })

                        

                  })

                  $(document).on('click','.update_section',function(){
                        trigger_notification = false
                        sectionid = $(this).attr('data-id')
                        var temp_section = all_collegesection.filter(x=>x.id == sectionid)


                        $('#createsection_input_gradelevel').val(temp_section[0].yearID).change()
                       

                        $('#createsection_input_course').append('<option value="'+temp_section[0].courseID+'">'+temp_section[0].courseDesc+'</option>')
                        $('#createsection_input_course').val(temp_section[0].courseID).change()

                        trigger_notification = true

                        $('#createsection_input_curriculum').append('<option value="'+temp_section[0].curriculumid+'">'+temp_section[0].curriculumname+'</option>')
                        $('#createsection_input_curriculum').val(temp_section[0].curriculumid).change()

                        $('#createsection_input_specification').val(temp_section[0].section_specification).change()
                        $('#createsection_input_name').val(temp_section[0].sectionDesc).change()
                        $('#cap_holder').attr('hidden','hidden')



                        $('#createsection_input_college').empty()
                        $('#createsection_input_college').append('<option value="'+temp_section[0].collegeID+'">'+temp_section[0].collegeDesc+'</option>')
                        $('#createsection_input_college').val(temp_section[0].collegeID).change()
                        
                        // $('#createsection_input_gradelevel').attr('disabled','disabled')
                        // $('#createsection_input_course').attr('disabled','disabled')
                        $('#createsection_input_curriculum').attr('disabled','disabled')
                        // $('#createsection_input_specification').attr('disabled','disabled')

                        $('#createsection_modal').modal()

                        $('#button_to_createsection_function').text('Update Section')
                        $('#button_to_createsection_function').addClass('btn-success')
                        $('#button_to_createsection_function').removeClass('btn-primary')

                        section_action = 'update'
                  })


                  // function get_collegesections(){
                  //       return false
                  //       $.ajax({
			// 		type:'GET',
			// 		url: '/college/section/list',
                  //             data:{
                  //                   syid:$('#filter_sy').val(),
                  //                   semid:$('#filter_semester').val(),
                  //                   levelid:$('#filter_gradelevel').val(),
                  //                   course:$('#filter_course').val(),
                  //             },
			// 		success:function(data) {
                  //                   if(data[0].status == 1){
                  //                         all_collegesection = data[0].info
                  //                         collegesection_datatable()
                  //                         if(all_collegesection.length > 0){
                  //                               Toast.fire({
                  //                                     type: 'info',
                  //                                     title: all_collegesection.length + ' sections found.'
                  //                               })
                  //                         }else{
                  //                               all_collegesection = []
                  //                               collegesection_datatable()
                  //                               Toast.fire({
                  //                                     type: 'error',
                  //                                     title: 'No sections found.'
                  //                               })
                  //                         }
                                         
                  //                   }else{
                  //                         all_collegesection = []
                  //                         collegesection_datatable()
                  //                         Toast.fire({
                  //                               type: 'error',
                  //                               title: data[0].data
                  //                         })
                  //                   }
                                    
                                   
			// 		}
			// 	})

                  // }

                 

                  $(document).on('click','#button_to_studentloading_modal',function(){
                        load_csl_resource()
                        display_sched_csl()
                        $('#cap_holder').removeAttr('hidden','hidden')
                        $('#createsection_input_capacity').val(50)
                        $('#available_sched_modal').modal()
                  })

            })

      </script>

      <script>

            var all_collegesection = []

            function collegesection_datatable(){

                  return false
                  
                  $("#collegesection_table").DataTable({
                        destroy: true,
                        // data:all_collegesection,
                        // lengthChange: false,
                        // scrollX: true,
                        autoWidth: false,
                        stateSave:true,
                        serverSide: true,
                        processing: true,
                        // ajax:'/student/preregistration/list',
                        ajax:{
                              url: '/college/section/list',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    course:$('#filter_course').val(),
                              },
                              dataSrc: function ( json ) {
                                    
                                    all_collegesection = json.data
                                    // all_collegesection = []
                                    return []
                                    return json.data;
                              }
                        },
                        order: [
                                    [ 0, "asc" ]
                              ],
                        columns: [
                              { "data": null },
                              { "data": "sectionDesc" },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": "search" }
                        ],
                        columnDefs: [
                        
                              {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          // if(rowData.issubjsched == 1){
                                          //       var text = ''+rowData.sectionDesc+'<a href="#" class="mb-0 edit_subjsched" data-id="'+rowData.id+'" data-schedid="'+rowData.schedid+'"><p class="mb-0" style="font-size:.7rem" >View Schedule</p></a>';
                                          // }else{
                                          var text = ''+rowData.sectionDesc+'<a href="#" class="mb-0 view_schedule" data-id="'+rowData.id+'"><p class="mb-0" style="font-size:.7rem" data-se>View Schedule</p></a>';
                                          // }
                                         
                                          $(td)[0].innerHTML = text
                                          $(td).addClass('align-middle')
                                    
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.courseabrv != null){
                                                var text = '<a class="mb-0">'+rowData.courseabrv+'</a><p class="text-muted mb-0"    style="font-size:.7rem">CC - '+rowData.courseID+'</p>';
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                          }else{
                                                $(td).text(null)
                                          }
                                    
                                    
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.levelname != null){
                                                var text = '<a class="mb-0">'+rowData.levelname+'</a><p class="text-muted mb-0" style="font-size:.7rem">GL - '+rowData.yearID+'</p>';
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                          }else{
                                                $(td).text(null)
                                          }
                                    
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          // $(td).text(null)
                                          $(td)[0].innerHTML = '<a data-text="'+rowData.sectionDesc+'" href="#" class="mb-0 view_students" data-id="'+rowData.id+'">'+rowData.enrolled+'</a>'
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.issubjsched == 0){
                                                var disabled = '';
                                                var buttons = '<a href="#" '+disabled+' class="update_section" data-id="'+rowData.id+'"><i class="far fa-edit text-primary"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }else{
                                                $(td).text(null)
                                          }
                                          
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var disabled = '';
                                          var buttons = '<a href="#" '+disabled+' class="delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                        ]
                        
                  });

                  // var label_text = $($("#collegesection_table_wrapper")[0].children[0])[0].children[0]
                  // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="button_to_createsection_modal" hidden>Create Section</button><button class="btn btn-primary btn-sm ml-2 subjsched_form" >Create Subject Sched</button><button class="btn btn-primary btn-sm ml-2" id="schedgroup_to_modal">Sched Group</button><button class="btn btn-primary btn-sm ml-2" id="button_to_studentloading_modal">Sched List</button>'

            }

      </script>

      {{-- <script>
            var sched_cap_id = null;
            $(document).ready(function(){
                  $(document).on('click','.edit_capacity',function(){
                        $('.list_label').text($(this).attr('data-text'))
                        var temp_schedid = $(this).attr('data-id')
                        sched_cap_id = temp_schedid

                        

                        $('#edit_capacity').val($(this).text())
                        $('#edit_capacity_modal').modal()
                  })   

                  $(document).on('click','#update_capacity_button',function(){
                        update_capcity()
                  })   

                  function update_capcity(){
                        $.ajax({
                              type:'GET',
                              url:'/college/section/schedule/updatecapacity',
                              data:{
                                    schedid:sched_cap_id,
                                    capacity:$('#edit_capacity').val()
                              },
                              success:function(data) {
                                    $('.edit_capacity[data-id="'+sched_cap_id+'"]').text($('#edit_capacity').val())
                                    if(data.status == 0 ){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong.'
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                    }
                              }
                        })

                  }
            })
      </script> --}}

      <script>
            var sched_cap_id = null;
            $(document).ready(function(){
                  $(document).on('click','.add_teacher',function(){
                        $('.list_label').text($(this).attr('data-text'))
                        var temp_schedid = $(this).attr('data-id')

                        sched_cap_id = temp_schedid
                        var temp_sched_info = all_sched.filter(x=>x.id == sched_cap_id)

                        if(temp_sched_info[0].teacherID != null){
                              $('#collegeschedlist_edit_teacher').empty()
                              $('#collegeschedlist_edit_teacher').append('<option value="'+temp_sched_info[0].teacherID+'">'+temp_sched_info[0].lastname+', '+temp_sched_info[0].firstname+'</option>')
                              
                              $('#collegeschedlist_edit_teacher').val($(this).attr('data-teacherid')).change()

                        }

                        
                        $('#edit_teacher_modal').modal()
                  })   

                  $(document).on('click','#update_teacher_button',function(){
                        update_capcity()
                  })   

                  function update_capcity(){
                        $.ajax({
                              type:'GET',
                              url:'/college/section/schedule/updateteacher',
                              data:{
                                    schedid:sched_cap_id,
                                    teacher:$('#collegeschedlist_edit_teacher').val()
                              },
                              success:function(data) {
                                    if(data.status == 0 ){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong.'
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          display_sched_csl()
                                    }
                              }
                        })

                  }
            })
      </script>

      <script>
            $(document).on('click','#print_schedule',function(){
                  window.open('/college/section/schedule/print?sectionid='+selected_sect, '_blank');
            })   
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
            //                   // {
            //                   //       'targets': 2,
            //                   //       'orderable': true, 
            //                   //       'createdCell':  function (td, cellData, rowData, row, col) {
            //                   //             $(td).addClass('align-middle')
            //                   //             $(td).addClass('text-center')
            //                   //       }
            //                   // },
            //                   // {
            //                   //       'targets': 2,
            //                   //       'orderable': true, 
            //                   //       'createdCell':  function (td, cellData, rowData, row, col) {
            //                   //             $(td).addClass('align-middle')
            //                   //             $(td).addClass('text-center')
            //                   //       }
            //                   // },

            //             ]
            //       })
            // }

      </script>

      <script>
            //studente evaluation
            // $(document).ready(function(){

                  // get_all_sched()

                  // function get_all_sched(){

                  //       return false
                  //       $.ajax({
                  //             type:'GET',
                  //             url:'/student/loading/allsched',
                  //             data:{
                  //                   syid:$('#filter_sy').val(),
                  //                   semid:$('#filter_semester').val()
                  //             },
                  //             success:function(data) {

                  //                   all_sched = data[0].college_classsched
                  //                   all_sched_section = data[0].section
                  //                   all_sched_enrolled = data[0].enrolled
                  //                   all_sched_detail = data[0].scheddetail
                  //                   all_sched_student = data[0].all_stud_sched

                  //                   $('#filter_sched_section').empty();
                  //                   $('#filter_sched_section').append('<option value=""></option>')
                  //                   $('#filter_sched_section').select2({
                  //                         allowClear:true,
                  //                         data:all_sched_section,
                  //                         placeholder:'All'
                  //                   })


                  //                   var temp_subjcode = [...new Map(all_sched.map(item => [item['subjCode'], item])).values()]
                  //                   $.each(temp_subjcode,function(c,d){
                  //                         // d.id = d.subjCode
                  //                         d.text = d.subjCode
                  //                   })

                  //                   $('#filter_sched_subjcode').empty();
                  //                   $('#filter_sched_subjcode').append('<option value=""></option>')
                  //                   $('#filter_sched_subjcode').select2({
                  //                         allowClear:true,
                  //                         data:temp_subjcode,
                  //                         placeholder:'All'
                  //                   })
                              
                  //                   var temp_subjects = [...new Map(all_sched.map(item => [item['subjDesc'], item])).values()]
                  //                   $.each(temp_subjects,function(e,f){
                  //                         f.text = f.subjDesc
                  //                   })

                  //                   $('#filter_sched_subjdesc').empty();
                  //                   $('#filter_sched_subjdesc').append('<option value=""></option>')
                  //                   $('#filter_sched_subjdesc').select2({
                  //                         allowClear:true,
                  //                         data:temp_subjects,
                  //                         placeholder:'All'
                  //                   })

                  //                   display_sched_csl()

                  //             //      $('#available_sched_modal').modal()
                  //             },
                  //             error:function(){
                  //                   Toast.fire({
                  //                         type: 'error',
                  //                         title: 'Something went wrong.'
                  //                   })
                  //             }
                  //       })
                  // }

            //       $(document).on('change','#filter_sched_section',function(){
            //             $('#filter_sched_subjdesc').empty();
            //             $('#filter_sched_subjcode').empty();
            //             display_sched_csl()
            //       })
            //       $(document).on('change','#filter_sched_subjdesc',function(){
            //             $('#filter_sched_section').empty();
            //             $('#filter_sched_subjcode').empty();
            //             display_sched_csl()
            //       })
            //       $(document).on('change','#filter_sched_subjcode',function(){
            //             $('#filter_sched_section').empty();
            //             $('#filter_sched_subjdesc').empty();
            //             display_sched_csl()
            //       })
            // // })

            // var all_sched = []
            // var all_sched_section = []
            // var all_sched_enrolled = []
            // var all_sched_detail = []
            // var all_sched_student = []

            // function display_sched_csl(){

            //       $("#available_sched_datatable").DataTable({
            //             destroy: true,
            //             autoWidth: false,
            //             stateSave: true,
            //             lengthChange : false,
            //             serverSide: true,
            //             processing: true,
            //             ajax:{
            //                   url: '/student/loading/allsched',
            //                   type: 'GET',
            //                   data: {
            //                         syid:$('#filter_sy').val(),
            //                         semid:$('#filter_semester').val(),
            //                         filtersection:$('#filter_sched_section').val(),
            //                         filtersubjcode:$('#filter_sched_subjdesc').val(),
            //                         filtersubjdesc:$('#filter_sched_subjcode').val(),
            //                   },
            //                   dataSrc: function ( json ) {
            //                         all_sched = json.data[0].college_classsched
            //                         all_sched_section = json.data[0].section
            //                         all_sched_enrolled = json.data[0].enrolled
            //                         all_sched_detail = json.data[0].scheddetail
            //                         all_sched_student = json.data[0].all_stud_sched
            //                         return all_sched;
            //                   }
            //             },
            //             order: [
            //                               [ 1, "asc" ]
            //                         ],
            //             columns: [
                                   
            //                         { "data": "sectionDesc" },
            //                         { "data": "subjCode" },
            //                         { "data": "subjDesc" },
            //                         { "data": "lecunits" },
            //                         { "data": "labunits" },
            //                         { "data": "capacity" },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                   ],
            //             columnDefs: [
            //                   {
            //                         'targets': 8,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
            //                               if(rowData.lastname != null){
            //                                     $(td)[0].innerHTML = rowData.lastname+', '+rowData.firstname + '<br><a hidden="hidden" style="font-size:.65rem !important" href="javascript:void(0)" class="add_teacher" data-id="'+rowData.id+'" data-subjdesc="Push-up" data-teacherid="'+rowData.teacherID+'" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'">Edit Teacher</a>'

            //                               }else{
            //                                     $(td)[0].innerHTML = null
            //                                     // $(td)[0].innerHTML = '<a style="font-size:.65rem !important" href="javascript:void(0)" class="add_teacher" data-id="'+rowData.id+'" data-subjdesc="Push-up"  data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">Add Teacher</a>'
            //                               }
                                         
            //                               $(td).addClass('align-middle')
            //                               $(td).attr('style','font-size:.6rem !important')
            //                         }
            //                   },
            //                   {
            //                         'targets': 0,
            //                         'orderable': true, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               // var sectiondesc = all_sched_section.filter(x=>x.id == rowData.sectionID)
            //                               // if(sectiondesc.length > 0){
            //                               //       $(td).text(sectiondesc[0].sectionDesc)
            //                               // }else{
            //                               //       $(td).text(null)
            //                               // }
            //                               $(td).text(rowData.sectionDesc)
            //                               $(td).addClass('align-middle')
                                          
            //                         }
            //                   },
            //                   {
            //                         'targets': 1,
            //                         'orderable': true, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
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
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               $(td).addClass('text-center')
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
            //                   },
            //                   {
            //                         'targets': 6,
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

            //                               $(td)[0].innerHTML  = '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_students" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enrolled Students">'+enrolled+'</a>' + ' / '+ '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_loaded_students" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="Loaded Students">'+loaded+'</a>'

            //                               $(td).addClass('text-center')
            //                               $(td).addClass('align-middle')
            //                         }

                                    
            //                   },
            //                   {
            //                         'targets': 7,
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
            //                                     // $(td)[0].innerHTML = text + '<br><a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
            //                               }else{
            //                                     $(td)[0].innerHTML = null
            //                                     // $(td)[0].innerHTML = '<a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
            //                               }
            //                               // $(td).addClass('text-center')
            //                         },
                                  
            //                   },
            //                   {
            //                         'targets': 9,
            //                         'orderable': false, 
            //                         'createdCell':  function (td, cellData, rowData, row, col) {
            //                               var text = 
                                          
            //                               '<a style="font-size:.65rem !important" href="javascript:void(0)" class="add_teacher" data-id="'+rowData.id+'" data-subjdesc="Push-up" data-teacherid="'+rowData.teacherID+'" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-placement="top" title="Assign Instructor"><i class="nav-icon fas fa-user-plus"></i></a>' +
                                          
            //                               '<a style="font-size:.65rem !important" href="javascript:void(0)" class="add_sched ml-2" data-id="'+rowData.id+'" data-subjdesc="Push-up" data-teacherid="'+rowData.teacherID+'" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-placement="top" title="Update Schedule"><i class="nav-icon fa fa-calendar"></i></a>' +

            //                               '<a style="font-size:.65rem !important" href="javascript:void(0)" class=" ml-2" data-id="'+rowData.id+'" data-teacherid="'+rowData.teacherID+'"  data-toggle="tooltip" data-placement="top" title="Update Room"><i class="nav-icon fas fa-door-open"></i></a>' +

            //                               '<a style="font-size:.65rem !important" href="javascript:void(0)" class="edit_capacity ml-2" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Update Capcity" ><i class="nav-icon fas fa-box-open"></i></a>'

            //                               $(td)[0].innerHTML = text
            //                               $(td).addClass('text-center')
            //                               $(td).addClass('align-middle')
            //                         }
            //                   }
            //             ],
            //             "initComplete": function(settings, json) {
            //                   $(function () {
            //                         $('[data-toggle="tooltip"]').tooltip()
            //                   })
            //             }
            //       });

                  
                  

            // }

            $(document).on('click','[data-toggle="tooltip"]',function(){
                  $(this).tooltip('hide')
            })
      </script>

      <script>

         
            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            $(document).ready(function(){
                  var keysPressed = {};
                 
                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/28/2021 14:34'
                                    })
                        }
                  });
                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });
            })
      </script>



@endsection


