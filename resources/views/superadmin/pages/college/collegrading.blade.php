
@php
      if(auth()->user()->type == 16 || Session::get('currentPortal') == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }else if(auth()->user()->type == 14 || Session::get('currentPortal') == 14){
            $extend = 'deanportal.layouts.app2';
      }else if(auth()->user()->type == 17 || Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }


@endphp

@extends($extend)
@section('pagespecificscripts')
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
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
      </style>
      <style>
            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .grade_td{
                  cursor: pointer;
                  vertical-align: middle !important;
            }
      </style>
@endsection


@section('content')

@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->get(); 

      if(auth()->user()->type == 16){

            $teacher = DB::table('teacher')
                              ->where('userid',auth()->user()->id)
                              ->first();

            $courses = DB::table('college_courses')
                              ->where('courseChairman',$teacher->id)
                              ->where('college_courses.deleted',0)
                              ->get();

      }
      else if(auth()->user()->type == 14){

            $teacher = DB::table('teacher')
                              ->where('userid',auth()->user()->id)
                              ->first();

            $courses = DB::table('college_colleges')
                              ->join('college_courses',function($join){
                                    $join->on('college_colleges.id','=','college_courses.collegeid');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->where('dean',$teacher->id)
                              ->where('college_colleges.deleted',0)
                              ->select('college_courses.*')
                              ->get();

      }
      else{

            $courses = DB::table('college_courses')
                        ->where('college_courses.deleted',0)
                        ->get();

      }
           
      $gradelevel = DB::table('gradelevel')
                        ->where('acadprogid',6)
                        ->where('deleted',0)
                        ->orderBy('sortid')
                        ->select('gradelevel.*','levelname as text')
                        ->get(); 
@endphp

@php
   $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
@endphp

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title modal_title_1"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-bordered table-sm" id="datatable_1">
                                          <thead>
                                                <tr>
                                                      <td width="70%">Student</td>
                                                      <td width="30%"></td>
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


<div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">1</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body " style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-bordered table-sm" id="datatable_2">
                                          <thead>
                                                <tr>
                                                      <td width="15%">Code</td>
                                                      <td width="50%">Description</td>
                                                      <td width="25%">Instructor</td>
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


<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title"><a class="mb-0" id="modal_title_3"></a><p class="text-muted mb-0" style="font-size:.7rem" id="student_name"></p></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-bordered table-sm" id="datatable_3">
                                          <thead>
                                                <tr>
                                                      <th width="3%" class="text-center p-0"><input type="checkbox" checked="checked" name="" id="" class="select_all"></th>
                                                      <th width="15%">Section</th>
                                                      <th width="52%">Subject</td>
                                                      <th width="20%">Instructor</td>
                                                      <th width="10%" class="text-center p-0">Grade</th>
                                                </tr>
                                          </thead>
                                          <tbody>

                                          </tbody>
                                    </table>
                              </div>
                       </div>
                       <div class="row mt-4">
                             <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm approve_grade">Approve ( <span class="selected_count">0</span> )</button>
                                    <button class="btn btn-warning btn-sm pending_grade">Pending ( <span class="selected_count">0</span> )</button>
                             </div>
                       </div>
                  </div>
            </div>
      </div>
</div>   



<div class="modal fade" id="modal_4" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title modal_title_1"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body " style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-bordered table-sm" id="datatable_4">
                                          <thead>
                                                <tr>
                                                      <td width="80s%">Section</td>
                                                      <td width="20%"></td>
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

<div class="modal fade" id="modal_5" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0 pb-0">
                        <h4 class="modal-title"><a class="mb-0">Sections</a><p class="text-muted mb-0" style="font-size:.7rem" id="modal_sectionname"></p></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12" style="font-size:.8rem">
                                    <table class="table table-bordered table-sm" id="datatable_5">
                                          <thead>
                                                <tr>
                                                      <td width="55%">Subject</td>
                                                      <td width="25%">Subject</td>
                                                      <td width="20%" class="text-center">Enrolled</td>
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

<div class="modal fade" id="modal_6" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Student Grade</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body " style="font-size:.9rem">
                        <div class="row">
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed"  style="font-size:.9rem" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="5%"><input type="checkbox" checked="checked" class="select_all"> </th>
                                                      <th width="20%">ID No.</th>
                                                      <th width="60%">Student</th>
                                                      <th width="15%" class="text-centerv">Grade</th>
                                                </tr>
                                          </thead>
                                          <tbody id="datatable_6">

                                          </tbody>
                                    </table>
                              </div>
                        </div>
                         <div class="row mt-2">
                              <div class="col-md-12">
                                   
                                    <button class="btn btn-primary btn-sm approve_grade">Approve ( <span class="selected_count">1</span> )</button>
                                    <button class="btn btn-warning btn-sm pending_grade">Pending ( <span class="selected_count">1</span> )</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>  


<div class="modal fade" id="modal_7" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12">
                                    <span class="badge badge-success">Submitted</span>
                                    <span class="badge badge-primary">Approved</span>
                                    <span class="badge badge-warning">Pending</span>
                                    <span class="badge badge-warning">INC</span>
                                    <span class="badge badge-danger">Dropped</span>
                              </div>
                        </div>
                        <div class="row mt-2">
                              <div class="col-md-12">
                                    <table class="table table-sm table-striped mb-0"  style="font-size:.9rem">
                                          <tr>
                                                <th id="subject" width="70%"></th>
                                                <th id="section" width="30%" hidden></th>
                                          </tr>
                                    </table>
                              </div>
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 420px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed table-hover"  style="font-size:.8rem" width="100%">
                                          <thead>
                                                <tr>
                                                      @if(strtoupper($schoolinfo) == 'SPCT')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="39%">Student</th>
                                                            <th width="23%">Course</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Final</th>
                                                            <th width="15%" class="text-center" >Term Grade</th>
                                                      @elseif(strtoupper($schoolinfo) == 'APMC')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="37%">Name of students</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Midterm</th>
                                                            <th width="10%" class="text-center">Semi</th>
                                                            <th width="10%" class="text-center" >Final</th>
                                                            <th width="10%" class="text-center" >FG</th>
                                                            <th width="10%" class="text-center" >Remarks</th>
                                                      @elseif(strtoupper($schoolinfo) == 'GBBC')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="47%">Name of students</th>
                                                            <th width="40%">Course</th>
                                                            <th width="10%" class="text-center" >Final</th>
                                                      @else
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="45%">Student</th>
                                                            <th width="15%">Course</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Midterm</th>
                                                            <th width="10%" class="text-center">PreFinal</th>
                                                            <th width="10%" class="text-center" >Final</th>
                                                      @endif
                                                      
                                                </tr>
                                          </thead>
                                          <tbody id="student_list_grades">
            
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 mt-2">
                                    <button id="grade_appove" class="btn btn-primary btn-sm">Approve Grades</button>
                                    <button id="grade_pending" class="btn btn-warning btn-sm">Pending Grades</button>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer pt-1 pb-1"  style="font-size:.7rem">
                        <i id="message_holder"></i>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="modal_8" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grade Submission</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body " style="font-size:.9rem">
                        <div class="row">
                              <div class="col-md-6 form-group mb-0">
                                    <select name="quarter_select" id="quarter_select" class="form-control form-control-sm">
                                          <option value="">Select Term</option>
                                          @if(strtoupper($schoolinfo) == 'SPCT')
                                                <option value="midtermgrade">Prelim</option>
                                                <option value="prefigrade">Final</option>
                                                <option value="finalgrade">Term Grade</option>
                                          @elseif(strtoupper($schoolinfo) == 'APMC')
                                                <option value="prelemgrade">Prelim</option>
                                                <option value="midtermgrade">Midterm</option>
                                                <option value="prefigrade">Semi</option>
                                                <option value="finalgrade">Final</option>
                                          @elseif(strtoupper($schoolinfo) == 'GBBC')
                                                <option value="finalgrade">Final</option>
                                          @else
                                                <option value="prelemgrade">Prelim</option>
                                                <option value="midtermgrade">Midterm</option>
                                                <option value="prefigrade">PreFinal</option>
                                                <option value="finalgrade">Final</option>
                                          @endif
                                    </select>
                                    <small class="text-danger"><i>Select a term to view and submit grades.</i></small>
                              </div>
                              <div class="col-md-6">
                                    <button class="btn btn-primary float-right btn-sm" id="process_button">Approve</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed"  style="font-size:.9rem" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="5%"><input type="checkbox" disabled checked="checked" class="select_all"> </th>
                                                      <th width="20%">SID</th>
                                                      <th width="60%">Student</th>
                                                      <th width="15%" class="text-centerv">Grade</th>
                                                </tr>
                                          </thead>
                                          <tbody id="datatable_8">

                                          </tbody>
                                    </table>
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
                        <h1>Student Grade</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Grade</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-2  form-group  mb-2">
                                                            <label for="">School Year</label>
                                                            <select class="form-control select2" id="filter_sy">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2 form-group mb-2" >
                                                            <label for="">Semester</label>
                                                            <select class="form-control  select2" id="filter_semester">
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-0 pl-2">
                                    <small>Status: <span id="p_status">Proccessing...</span></small>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-6">
                        <div class="card shadow" >
                              <div class="card-body">
                                    <div class="row">
                                          <table class="table table-bordered table-sm"  style="font-size:.9rem !important">
                                                <tr>
                                                      <th width="60%">Status (Section)</th>
                                                      <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</th>
                                                      <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</th>
                                                      <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</th>
                                                      <th width="10%" class="text-center" >Final</th>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Unsubmitted</td>
                                                      <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="4" ></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Submitted</td>
                                                      <td width="10%" class="section_submitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_submitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_submitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_submitted text-center" data-status="1" data-term="4" ></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Approved</td>
                                                      <td width="10%" class="section_approved text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_approved text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_approved text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_approved text-center" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Pending</td>
                                                      <td width="10%" class="section_pending text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_pending text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_pending text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_pending text-center" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">INC</td>
                                                      <td width="10%" class="section_inc text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_inc text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_inc text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_inc text-center" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Dropped</td>
                                                      <td width="10%" class="section_drop text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_drop text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_drop text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="section_drop text-center" data-term="4"></td>
                                                </tr>
                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="card shadow" >
                              <div class="card-body">
                                    <div class="row">
                                          <table class="table table-bordered table-sm"  style="font-size:.9rem !important">
                                                <tr>
                                                      <th width="60%">Status (Student)</th>
                                                      <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</th>
                                                      <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</th>
                                                      <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</th>
                                                      <th width="10%" class="text-center">Final</th>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Unsubmitted</td>
                                                      <td width="10%" class="unsubmitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="unsubmitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="unsubmitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="unsubmitted text-center" data-status="1" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Submitted</td>
                                                      <td width="10%" class="submitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="submitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="submitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="submitted text-center" data-status="1" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Approved</td>
                                                      <td width="10%" class="approved text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="approved text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="approved text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="approved text-center" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Pending</td>
                                                      <td width="10%" class="pending text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="pending text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="pending text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="pending text-center" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">INC</td>
                                                      <td width="10%" class="inc text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="inc text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="inc text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="inc text-center" data-term="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="60%">Dropped</td>
                                                      <td width="10%" class="drop text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="drop text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="drop text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="10%" class="drop text-center" data-term="4"></td>
                                                </tr>
                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body"  style="font-size:.8rem !important">
                                    <div class="row">
                                          <div class="col-md-2">
                                                <label for="">Grades Status (Subject)</label>
                                                <select name="" class="form-control form-control-sm select2" id="filter_status_by_subject">
                                                      <option value="uns">Unsubmitted</option>
                                                      <option value="sub">Submitted</option>
                                                      <option value="app">Approved</option>
                                                      <option value="pen">Pending</option>
                                                      <option value="inc">INC</option>
                                                      <option value="drop">Dropped</option>
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-sm" id="datatable_7">
                                                      <thead>
                                                            <tr>
                                                                  <th width="90%">Subject</th>
                                                                  {{-- <th width="10%">Course</th> --}}
                                                                  <th width="10%"  class="text-center p-0 align-middle" >Students</th>
                                                                  {{-- <th width="10%" class="text-center p-0 align-middle" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</th>
                                                                  <th width="10%" class="text-center p-0 align-middle" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</th>
                                                                  <th width="10%" class="text-center p-0 align-middle" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</th>
                                                                  <th width="10%" class="text-center p-0 align-middle">Final</th> --}}
                                                            </tr>
                                                      </thead>
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
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

      <script>
             $(document).ready(function(){

                  $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
                  
                  $('.select2').select2()

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var school = @json(strtoupper($schoolinfo))
                 

                  var grade = [];
                  var students = [];
                  var subjects = [];
                  var sched = [];
                  var teacher = []
                  var unsubmitted_grades = []
                  var submitted_grades = []
                  var approved_grades = []
                  var pending_grades = []
                  var selected_term = null
                  var current_status = null;
                  var selected_section = null;
                  var selected_subject = null

                  $(document).on('click','.approve_grade',function(){
                        approve_grade()
                  })

                  $(document).on('click','.pending_grade',function(){
                        pending_grade()
                  })

                  $(document).on('click','.view_inc',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 0
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  $(document).on('click','.view_submitted',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 1
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  function approve_grade(){

                        var selected = []
                        var term = selected_term
                        var grade_term = ''

                        if(term == 1){
                              term = 'prelemstatus'
                              grade_term = 'prelemgrade'
                        }else if(term == 2){
                              term = 'midtermstatus'
                              grade_term = 'midtermgrade'
                        }else if(term == 3){
                              term = 'prefistatus'
                              grade_term = 'prefigrade'
                        }else if(term == 4){
                              term = 'finalstatus'
                              grade_term = 'finalgrade'
                        }

                        $('.select').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this).attr('data-id') != undefined){
                                    selected.push($(this).attr('data-id'))
                              }
                        })

                        if(selected.length == 0){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No student selected'
                              })
                              return false
                        }
                        
                        $.ajax({
                              type:'POST',
                              url: '/college/grades/approve/ph',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    term:term,
                                    selected:selected,
                              },
                              success:function(data) {
                                    if(data[0].status == 1){

                                          Toast.fire({
                                                type: 'success',
                                                title: 'Grades Approved!'
                                          })

                                          var term = selected_term
                                          var status = current_status
                                          var section = selected_section
                                          var subjid = selected_subject

                                          if(all_grades.length > 0){
                                                $.each(selected,function(a,b){
                                                      $('.select[data-id="'+b+'"]').attr('disabled','disabled')
                                                      $('.grade_td[data-id="'+b+'"][data-term="'+grade_term+'"]').attr('data-status',7)
                                                      $('.grade_td[data-id="'+b+'"][data-term="'+grade_term+'"]').removeClass('bg-success')
                                                      $('.grade_td[data-id="'+b+'"][data-term="'+grade_term+'"]').addClass('bg-primary')
                                                      var temp_id = all_grades.findIndex(x=>x.id == b)
                                                      if(grade_term == "prelemgrade"){
                                                            all_grades[temp_id].prelemstatus = 7
                                                      }else if(grade_term == "midtermgrade"){
                                                            all_grades[temp_id].midtermstatus = 7
                                                      }else if(grade_term == "prefigrade"){
                                                            all_grades[temp_id].prefistatus = 7
                                                      }else if(grade_term == "finalgrade"){
                                                            all_grades[temp_id].finalstatus = 7
                                                      }
                                                })
                                          }

                                          $('.grade_td').addClass('input_grades')
                                          plot_subject_grades(all_grades)

                                          $.each(selected,function(a,b){
                                                if(term == 1){
                                                      var temp_grade_data = sub_stud_1.filter(x=>x.id == b)
                                                      sub_stud_1 = sub_stud_1.filter(x=>x.id != b)
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].prelemstatus = 7
                                                            app_stud_1.push(temp_grade_data[0])
                                                      }
                                                }else if(term == 2){
                                                      var temp_grade_data = sub_stud_2.filter(x=>x.id == b)
                                                      sub_stud_2 = sub_stud_2.filter(x=>x.id != b)
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].midtermstatus = 7
                                                            app_stud_2.push(temp_grade_data)
                                                      }
                                                }else if(term == 3){
                                                      var temp_grade_data = sub_stud_3.filter(x=>x.id == b)
                                                      sub_stud_3 = sub_stud_3.filter(x=>x.id != b)
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].prefistatus = 7
                                                            app_stud_3.push(temp_grade_data)
                                                      }
                                                }else if(term == 4){
                                                      var temp_grade_data = sub_stud_4.filter(x=>x.id == b)
                                                      sub_stud_4 = sub_stud_4.filter(x=>x.id != b)
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].finalstatus = 7
                                                            app_stud_4.push(temp_grade_data)
                                                      }
                                                }
                                          })

                                          update_data(term)
                                          update_list_display()
                                          view_list(term,status)

                                          if(all_grades.length == 0){
                                                show_section_grades(term,status,section,subjid)
                                                show_section_subject(term, status, section)
                                                view_section_list_modal(term,status)
                                                view_student_subjects(selected_student)
                                          }

                                          
                                         
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function update_data(term){

                        if(term == 1){

                              app_sec_1 = []
                              var temp_section = [...new Map(app_stud_1.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          app_sec_1.push(check[0])
                                    }
                              })

                              sub_sec_1 = []
                              var temp_section = [...new Map(sub_stud_1.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          sub_sec_1.push(check[0])
                                    }
                              })

                              pen_sec_1 = []
                              var temp_section = [...new Map(pen_stud_1.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          pen_sec_1.push(check[0])
                                    }
                              })

                        }else if(term == 2){

                              app_sec_2 = []
                              var temp_section = [...new Map(app_stud_2.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          app_sec_2.push(check[0])
                                    }
                              })

                              sub_sec_2 = []
                              var temp_section = [...new Map(sub_stud_2.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          sub_sec_2.push(check[0])
                                    }
                              })

                              pen_sec_2 = []
                              var temp_section = [...new Map(pen_stud_2.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          pen_sec_2.push(check[0])
                                    }
                              })

                        }else if(term == 3){
                              app_sec_3 = []
                              var temp_section = [...new Map(app_stud_3.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          app_sec_3.push(check[0])
                                    }
                              })

                              sub_sec_3 = []
                              var temp_section = [...new Map(sub_stud_3.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          sub_sec_3.push(check[0])
                                    }
                              })

                              pen_sec_3 = []
                              var temp_section = [...new Map(pen_stud_3.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          pen_sec_3.push(check[0])
                                    }
                              })

                        }else if(term == 4){
                              app_sec_4 = []
                              var temp_section = [...new Map(app_stud_4.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          app_sec_4.push(check[0])
                                    }
                              })

                              sub_sec_4 = []
                              var temp_section = [...new Map(sub_stud_4.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          sub_sec_4.push(check[0])
                                    }
                              })

                              pen_sec_4 = []
                              var temp_section = [...new Map(pen_stud_4.map(item => [item['sectionID'], item])).values()]
                              $.each(temp_section,function(c,d){
                                    var check = all_sections.filter(x=>x.id == d.sectionID)
                                    if(check.length > 0){
                                          pen_sec_4.push(check[0])
                                    }
                              })

                        }

                  }

                  function get_current_selecton_data(term = null, status = null){

                        var data = []
                        if(term == 1){
                              if(status == 1){ data = sub_stud_1 }
                              else if(status == 7){ data = app_stud_1 }
                              else if(status == 3){ data = pen_stud_1 }
                              else if(status == 0){ data = uns_stud_1 }
                              else if(status == 8){ data = inc_stud_1 }
                              else if(status == 9){ data = drop_stud_1 }
                              else{ data = uns_stud_1 }
                        }else if(term == 2){
                              if(status == 1){ data = sub_stud_2 }
                              else if(status == 7){ data = app_stud_2 }
                              else if(status == 3){ data = pen_stud_2 }
                              else if(status == 0){ data = uns_stud_2 }
                              else if(status == 8){ data = inc_stud_2 }
                              else if(status == 9){ data = drop_stud_2 }
                              else{ data = uns_stud_2 }
                        }else if(term == 3){
                              if(status == 1){ data = sub_stud_3 }
                              else if(status == 7){ data = app_stud_3 }
                              else if(status == 3){ data = pen_stud_3 }
                              else if(status == 0){ data = uns_stud_3 }
                              else if(status == 8){ data = inc_stud_3 }
                              else if(status == 9){ data = drop_stud_3 }
                              else{ data = uns_stud_3 }
                        }else if(term == 4){
                              if(status == 1){ data = sub_stud_4 }
                              else if(status == 7){ data = app_stud_4 }
                              else if(status == 3){ data = pen_stud_4 }
                              else if(status == 0){ data = uns_stud_4 }
                              else if(status == 8){ data = inc_stud_4 }
                              else if(status == 9){ data = drop_stud_4 }
                              else{ data = uns_stud_4 }
                        }
                        return data;

                  }

                  get_enrolled()

                  function get_enrolled(){
                        $.ajax({
                              type:'GET',
                              url: '/college/grades/student',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    students = data
                                    get_sections()
                                    $('#p_status').text('Fetching enrolled students.')
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#p_status').text('Something went wrong. Please reload the page')
                              }
                        })
                  }

                  function get_sections(){
                        $.ajax({
                              type:'GET',
                              url: '/college/grades/sections',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    all_sections = data[0].sections
                                    all_section_sched = data[0].sectionsched
                                    get_teachers()
                                    $('#p_status').text('Fetching course sections.')
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#p_status').text('Something went wrong. Please reload the page')
                              }
                        })

                  }

                  function get_teachers(){
                        $.ajax({
                              type:'GET',
                              url: '/college/grades/teachers',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    teacher = data
                                    $('#p_status').text('Fetching course teachers.')
                                    get_subjects()
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#p_status').text('Something went wrong. Please reload the page')
                              }
                        })

                  }

                  function get_subjects(){
                        $.ajax({
                              type:'GET',
                              url: '/college/grades/subjects',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    subjects = data
                                    $('#p_status').text('Fetching course subjects.')
                                    get_studentsgrade()
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#p_status').text('Something went wrong. Please reload the page')
                              }
                        })

                  }

                  function get_studentsgrade(){

                        var temp_courses = @json($courses);
                        var count = 0

                        $.each(temp_courses,function(a,b){
                              $.ajax({
                                    type:'GET',
                                    url: '/college/grades/get',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          courseid:b.id
                                    },
                                    success:function(data) {
                                       
                                          $.each(data,function(c,d){
                                                var check = students.filter(x=>x.studid == d.studid)
                                                if(check.length > 0){
                                                      grade.push(d)
                                                }
                                          })
                                          count += 1

                                          $('#p_status').text('Fetching grades ('+count+'/'+temp_courses.length+').')

                                          if(temp_courses.length == count){
                                                
                                                get_data()
                                                
                                          }
                                    },error:function(){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                          $('#p_status').text('Something went wrong. Please reload the page')
                                    }
                                    
                              })

                        })


                  }

                  function get_data(){

                        var temp_courses = @json($courses);
                        var count = 0

                        $.each(temp_courses,function(a,b){

                              var temp_coursid = b.id

                              $.ajax({
                                    type:'GET',
                                    url: '/college/grades/gradesched/info',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          courseid:b.id
                                    },
                                    success:function(data) {
                                   
                                          $.each(data,function(c,d){
                                                var check = students.filter(x=>x.studid == d.studid)
                                                if(check.length > 0){
                                                      sched.push(d)
                                                }
                                          })
                                          var last = false;
                                          count += 1
                                          $('#p_status').text('Fetching student schedule ('+count+'/'+temp_courses.length+').')
                                          if(temp_courses.length == count){
                                                last = true
                                          }
                                          generate_record(all_sections,all_section_sched,data,last)
                                          
                                    },error:function(){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              })
                        })

                       
                  }

                  var all_sections = []
                  var all_section_sched = []

                  var sub_sec_1 = []
                  var sub_sec_2 = []
                  var sub_sec_3 = []
                  var sub_sec_4 = []

                  var sub_stud_1 = []
                  var sub_stud_2 = []
                  var sub_stud_3 = []
                  var sub_stud_4 = []

                  var app_sec_1 = []
                  var app_sec_2 = []
                  var app_sec_3 = []
                  var app_sec_4 = []

                  var app_stud_1 = []
                  var app_stud_2 = []
                  var app_stud_3 = []
                  var app_stud_4 = []

                  var pen_sec_1 = []
                  var pen_sec_2 = []
                  var pen_sec_3 = []
                  var pen_sec_4 = []

                  var pen_stud_1 = []
                  var pen_stud_2 = []
                  var pen_stud_3 = []
                  var pen_stud_4 = []

                  var uns_sec_1 = []
                  var uns_sec_2 = []
                  var uns_sec_3 = []
                  var uns_sec_4 = []

                  var inc_sec_1 = []
                  var inc_sec_2 = []
                  var inc_sec_3 = []
                  var inc_sec_4 = []

                  var drop_sec_1 = []
                  var drop_sec_2 = []
                  var drop_sec_3 = []
                  var drop_sec_4 = []

                  var uns_stud_1 = []
                  var uns_stud_2 = []
                  var uns_stud_3 = []
                  var uns_stud_4 = []

                  var drop_stud_1 = []
                  var drop_stud_2 = []
                  var drop_stud_3 = []
                  var drop_stud_4 = []

                  var inc_stud_1 = []
                  var inc_stud_2 = []
                  var inc_stud_3 = []
                  var inc_stud_4 = []

                  

                  function generate_record(section, sectionsched,studsched,last = false){

                        uns_stud_1 = []
                        uns_stud_2 = []
                        uns_stud_3 = []
                        uns_stud_4 = []

                        sub_stud_1 = []
                        sub_stud_2 = []
                        sub_stud_3 = []
                        sub_stud_4 = []

                        app_stud_1 = []
                        app_stud_2 = []
                        app_stud_3 = []
                        app_stud_4 = []

                        pen_stud_1 = []
                        pen_stud_2 = []
                        pen_stud_3 = []
                        pen_stud_4 = []

                        inc_stud_1 = []
                        inc_stud_2 = []
                        inc_stud_3 = []
                        inc_stud_4 = []

                        drop_stud_1 = []
                        drop_stud_2 = []
                        drop_stud_3 = []
                        drop_stud_4 = []

                        $.each(section,function(a,b){
                              var temp_all_section_sched = sectionsched.filter(x=>x.sectionID == b.id)
                              $.each(temp_all_section_sched,function(c,d){
                                    var temp_stud_schd = studsched.filter(x=>x.schedid == d.id)
                                    $.each(temp_stud_schd,function(e,f){
                                          var stud_grade = grade.filter(x=>x.prospectusID == f.subjectID && x.studid == f.studid).length
                                          if(stud_grade == 0){
                                                grade.push({
                                                      subjid:f.subjid,
                                                      finalgrade: null,
                                                      finalstatus: null,
                                                      id: null,
                                                      midtermgrade: null,
                                                      midtermstatus: null,
                                                      prefigrade: null,
                                                      prefistatus: null,
                                                      prelemgrade: null,
                                                      prelemstatus: null,
                                                      prospectusID: f.subjectID,
                                                      sectionID: b.id,
                                                      studid: f.studid,
                                                })
                                          }
                                    })
                              })
                        })

                        $.each(section,function(a,b){

                              var ws1, ws2, ws3, ws4 = false
                              var wa1, wa2, wa3, wa4 = false
                              var wp1, wp2, wp3, wp4 = false
                              var wu1, wu2, wu3, wu4 = false
                              var wi1, wi2, wi3, wi4 = false
                              var wd1, wd2, wd3, wd4 = false

                              var temp_sched = sectionsched.filter(x=>x.sectionID  == b.id)

                              $.each(temp_sched,function(c,d){

                                    var temp_grade = grade.filter(x=>x.prospectusID == d.subjectID && x.sectionID == b.id)

                                    //submitted
                                    if(temp_grade.filter(x=>x.prelemstatus == 1).length > 0){ 
                                          ws1 = true; 
                                          $.each(temp_grade.filter(x=>x.prelemstatus == 1),function(e,f){ sub_stud_1.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.midtermstatus == 1).length > 0){ 
                                          ws2 = true;
                                          $.each(temp_grade.filter(x=>x.midtermstatus == 1),function(e,f){ sub_stud_2.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.prefistatus == 1).length > 0){ 
                                          ws3 = true;
                                          $.each(temp_grade.filter(x=>x.prefistatus == 1),function(e,f){ sub_stud_3.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.finalstatus == 1).length > 0){ 
                                          ws4 = true; 
                                          $.each(temp_grade.filter(x=>x.finalstatus == 1),function(e,f){ sub_stud_4.push(f); })
                                    }

                                    //approved
                                    if(temp_grade.filter(x=>x.prelemstatus == 7).length > 0){ 
                                          wa1 = true; 
                                          $.each(temp_grade.filter(x=>x.prelemstatus == 7),function(e,f){ app_stud_1.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.midtermstatus == 7).length > 0){ 
                                          wa2 = true;
                                          $.each(temp_grade.filter(x=>x.midtermstatus == 7),function(e,f){ app_stud_2.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.prefistatus == 7).length > 0){ 
                                          wa3 = true;
                                          $.each(temp_grade.filter(x=>x.prefistatus == 7),function(e,f){ app_stud_3.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.finalstatus == 7).length > 0){ 
                                          wa4 = true; 
                                          $.each(temp_grade.filter(x=>x.finalstatus == 7),function(e,f){ app_stud_4.push(f); })
                                    }

                                    //pending
                                    if(temp_grade.filter(x=>x.prelemstatus == 3).length > 0){ 
                                          wp1 = true; 
                                          $.each(temp_grade.filter(x=>x.prelemstatus == 3),function(e,f){ pen_stud_1.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.midtermstatus == 3).length > 0){ 
                                          wp2 = true;
                                          $.each(temp_grade.filter(x=>x.midtermstatus == 3),function(e,f){ pen_stud_2.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.prefistatus == 3).length > 0){ 
                                          wp3 = true;
                                          $.each(temp_grade.filter(x=>x.prefistatus == 3),function(e,f){ pen_stud_3.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.finalstatus == 3).length > 0){ 
                                          wp4 = true; 
                                          $.each(temp_grade.filter(x=>x.finalstatus == 3),function(e,f){ pen_stud_4.push(f); })
                                    }

                                    //unsubmitted
                                    if(temp_grade.filter(x=>x.prelemstatus == null).length > 0){ 
                                          wu1 = true; 
                                          $.each(temp_grade.filter(x=>x.prelemstatus == null),function(e,f){ uns_stud_1.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.midtermstatus == null).length > 0){ 
                                          wu2 = true;
                                          $.each(temp_grade.filter(x=>x.midtermstatus == null),function(e,f){ uns_stud_2.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.prefistatus == null).length > 0){ 
                                          wu3 = true;
                                          $.each(temp_grade.filter(x=>x.prefistatus == null),function(e,f){ uns_stud_3.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.finalstatus == null).length > 0){ 
                                          wu4 = true; 
                                          $.each(temp_grade.filter(x=>x.finalstatus == null),function(e,f){ uns_stud_4.push(f); })
                                    }

                                    //inc
                                    if(temp_grade.filter(x=>x.prelemstatus == 8).length > 0){ 
                                          wi1 = true; 
                                          $.each(temp_grade.filter(x=>x.prelemstatus == 8),function(e,f){ inc_stud_1.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.midtermstatus == 8).length > 0){ 
                                          wi2 = true;
                                          $.each(temp_grade.filter(x=>x.midtermstatus == 8),function(e,f){ inc_stud_2.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.prefistatus == 8).length > 0){ 
                                          wi3 = true;
                                          $.each(temp_grade.filter(x=>x.prefistatus == 8),function(e,f){ inc_stud_3.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.finalstatus == 8).length > 0){ 
                                          wi4 = true; 
                                          $.each(temp_grade.filter(x=>x.finalstatus == 8),function(e,f){ inc_stud_4.push(f); })
                                    }

                                    //drop
                                    if(temp_grade.filter(x=>x.prelemstatus == 9).length > 0){ 
                                          wd1 = true; 
                                          $.each(temp_grade.filter(x=>x.prelemstatus == 9),function(e,f){ drop_stud_1.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.midtermstatus == 9).length > 0){ 
                                          wd2 = true;
                                          $.each(temp_grade.filter(x=>x.midtermstatus == 9),function(e,f){ drop_stud_2.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.prefistatus == 9).length > 0){ 
                                          wd3 = true;
                                          $.each(temp_grade.filter(x=>x.prefistatus == 9),function(e,f){ drop_stud_3.push(f); })
                                    }
                                    if(temp_grade.filter(x=>x.finalstatus == 9).length > 0){ 
                                          wd4 = true; 
                                          $.each(temp_grade.filter(x=>x.finalstatus == 9),function(e,f){ drop_stud_4.push(f); })
                                    }

                                    //unsubmitted
                                    if(temp_grade.length == 0){ 
                                          wu1 = true; 
                                    }
                                    if(temp_grade.length == 0){ 
                                          wu2 = true;
                                    }
                                    if(temp_grade.length == 0){ 
                                          wu3 = true;
                                    }
                                    if(temp_grade.length == 0){ 
                                          wu4 = true; 
                                    }

                              })

                              if(ws1){ sub_sec_1.push(b) }
                              if(ws2){ sub_sec_2.push(b) }
                              if(ws3){ sub_sec_3.push(b) }
                              if(ws4){ sub_sec_4.push(b) }

                              if(wa1){ app_sec_1.push(b) }
                              if(wa2){ app_sec_2.push(b) }
                              if(wa3){ app_sec_3.push(b) }
                              if(wa4){ app_sec_4.push(b) }

                              if(wp1){ pen_sec_1.push(b) }
                              if(wp2){ pen_sec_2.push(b) }
                              if(wp3){ pen_sec_3.push(b) }
                              if(wp4){ pen_sec_4.push(b) }

                              if(wu1){ uns_sec_1.push(b) }
                              if(wu2){ uns_sec_2.push(b) }
                              if(wu3){ uns_sec_3.push(b) }
                              if(wu4){ uns_sec_4.push(b) }

                              if(wi1){ inc_sec_1.push(b) }
                              if(wi2){ inc_sec_2.push(b) }
                              if(wi3){ inc_sec_3.push(b) }
                              if(wi4){ inc_sec_4.push(b) }

                              if(wd1){ drop_sec_1.push(b) }
                              if(wd2){ drop_sec_2.push(b) }
                              if(wd3){ drop_sec_3.push(b) }
                              if(wd4){ drop_sec_4.push(b) }

                        })


                        // sub_stud_1 = [...new Map(sub_stud_1.map(item => [item['studid'], item])).values()]
                     

                        update_list_display()
                        if(last){
                              $('#p_status').text('Complete')
                              generate_by_subject()
                        }

                  }


                  $(document).on('click','.by_subj_view_stud',function(){
                        var temp_id = $(this).attr('data-id')
                        var temp_students = sched.filter(x=>x.subjid == temp_id)
                        $('#modal_7').modal()
                        var female = 0;
                        var male = 0;
                        var count = 0;
                        var pid = ''
                        var sectionid = ''

                        var temp_subj_info = subjects.filter(x=>x.subjectID == temp_id)
                       
                        $('#student_list_grades').empty()
                        
                        $('#subject')[0].innerHTML = '<a class="mb-0">'+temp_subj_info[0].subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+temp_subj_info[0].subjCode+'</p>'

                        $.each(temp_students,function (a,b) {

                              b.pid = b.subjectID
                              var temp_shed_info = students.filter(x=>x.studid == b.studid)
                              b.student = ''
                              b.courseabrv = ''
                              b.levelid = ''

                              if(temp_shed_info.length > 0){
                                    b.sid = temp_shed_info[0].sid
                                    b.student = temp_shed_info[0].studentname
                                    b.levelid = temp_shed_info[0].levelid
                                    b.courseabrv = temp_shed_info[0].courseabrv
                                    b.sectionid = temp_shed_info[0].sectionid
                              }else{
                                   
                              }

                              var q1hidden = ''
                              var q2hidden = ''
                              var q3hidden = ''
                              var q4hidden = ''

                              if(school == 'spct'.toUpperCase()){
                                    q1hidden = 'hidden="hidden"'
                              }
                              else{

                                    var colspan = 7
                                    if(school == 'apmc'.toUpperCase()){
                                          colspan = 8
                                    }else if(school == 'gbbc'.toUpperCase()){
                                          colspan = 4
                                    }


                                    if(male == 0 && b.gender == 'MALE'){
                                          $('#student_list_grades').append('<tr class="bg-secondary"><th colspan="'+colspan+'">MALE</th></tr>')
                                          $('#datatable_4').append('<tr class="bg-secondary"><th colspan="4">MALE</th></tr>')
                                          male = 1
                                          count = 0
                                    }else if(female == 0 && b.gender == 'FEMALE'){
                                          $('#student_list_grades').append('<tr class="bg-secondary"><th colspan="'+colspan+'">FEMALE</th></tr>')
                                          $('#datatable_4').append('<tr class="bg-secondary"><th colspan="4">FEMALE</th></tr>')
                                          female = 1
                                          count = 0
                                    }

                              }

                              count += 1

                              if(!school == 'spct'.toUpperCase()){
                                    $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prelemgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="midtermgrade" '+q2hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prefigrade" '+q3hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q4hidden+'></td></tr>')
                              }else{

                                    var gradelevel = null
                                    if(b.levelid == 17){
                                          gradelevel = 1
                                    }else if(b.levelid == 18){
                                          gradelevel = 2
                                    }else if(b.levelid == 19){
                                          gradelevel = 3
                                    }else if(b.levelid == 20){
                                          gradelevel = 4
                                    }else if(b.levelid == 21){
                                          gradelevel = 5
                                    }

                                    if(school == 'apmc'.toUpperCase()){
                                          $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prelemgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="midtermgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prefigrade" '+q2hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q3hidden+'></td><th class="text-center align-middle" data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" data-term="fg" '+q1hidden+'></th><th class="text-center align-middle" data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" data-term="remarks" '+q1hidden+'></th></tr>')
                                    }else if(school == 'gbbc'.toUpperCase()){
                                          pid = b.pid
                                          sectionid = b.sectionid

                                          $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+' '+gradelevel+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q4hidden+'></td></tr>')
                                    }
                                    else{
                                          $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+' '+gradelevel+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prelemgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="midtermgrade" '+q2hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prefigrade" '+q3hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q4hidden+'></td></tr>')

                                    }

                              }

                              $('#datatable_8').append('<tr><td><input disabled checked="checked" type="checkbox" class="select" data-studid="'+b.studid+'"></td><td>'+b.sid+'</td><td>'+b.student+'</td><td data-studid="'+b.studid+'" class="grade_submission_student text-center"></td></tr>')
                        })


                        get_grades(temp_id,false,temp_students)
                  
                  })

                  var all_grades = []

                  function get_grades(schedid, prompt = true, students) {

                        // var sched = sched.filter(x=>x.schedid == schedid)
                        // var pid = sched[0].pid
                        // var sectionid = sched[0].sectionID

                        // if(school == 'gbbc'.toUpperCase()){
                              var pid = []
                              var sectionid = []
                              var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                              var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]
                              $.each(temp_pid,function(a,b){
                                    pid.push(b.pid)
                              })
                              $.each(temp_sectionid,function(a,b){
                                    sectionid.push(b.sectionid)
                              })
                        // }

                        $('.p_count').text(0)
                        $('.f_count').text(0)
                        $('.ng_count').text(0)

                        $('.drop_count').text(0)
                        $('.inc_count').text(0)
                        $('.pen_count').text(0)
                        $('.sub_count').text(0)
                        $('.app_count').text(0)

                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/get',
                              data:{

                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    pid:pid,
                                    sectionid:sectionid
                              },
                              success:function(data) {

                                    $('.grade_td').addClass('input_grades')
                                    all_grades = data

                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No grades found!'
                                          })
                                          $('#message_holder').text('No grades found. Please input student grades.')
                                    }else{

                                          $('.drop_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 9).length)
                                          $('.drop_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 9).length)
                                          $('.drop_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 9).length)
                                          $('.drop_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 9).length)

                                          $('.inc_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 8).length)
                                          $('.inc_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 8).length)
                                          $('.inc_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 8).length)
                                          $('.inc_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 8).length)

                                          $('.pen_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 3).length)
                                          $('.pen_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 3).length)
                                          $('.pen_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 3).length)
                                          $('.pen_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 3).length)

                                          $('.sub_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 1).length)
                                          $('.sub_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 1).length)
                                          $('.sub_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 1).length)
                                          $('.sub_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 1).length)

                                          $('.app_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 2 || x.prelemstatus == 7).length)
                                          $('.app_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 2  || x.midtermstatus == 7).length)
                                          $('.app_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 2  || x.prefistatus == 7).length)
                                          $('.app_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 2  || x.finalstatus == 7).length)


                                          $('.p_count[data-stat="1"]').text(data.filter(x=>x.prelemgrade != null && x.prelemgrade >= 75).length)
                                          $('.p_count[data-stat="2"]').text(data.filter(x=>x.midtermgrade != null && x.midtermgrade >= 75).length)
                                          $('.p_count[data-stat="3"]').text(data.filter(x=>x.prefigrade != null && x.prefigrade >= 75).length)
                                          $('.p_count[data-stat="4"]').text(data.filter(x=>x.finalgrade != null && x.finalgrade >= 75).length)

                                          $('.f_count[data-stat="1"]').text(data.filter(x=>x.prelemgrade != null && x.prelemgrade < 75).length)
                                          $('.f_count[data-stat="2"]').text(data.filter(x=>x.midtermgrade != null && x.midtermgrade < 75).length)
                                          $('.f_count[data-stat="3"]').text(data.filter(x=>x.prefigrade != null && x.prefigrade < 75).length)
                                          $('.f_count[data-stat="4"]').text(data.filter(x=>x.finalgrade != null && x.finalgrade < 75).length)

                                          if(school == 'spct'.toUpperCase()){
                                                $('.ng_count[data-stat="2"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="2"]').text()) + parseInt($('.f_count[data-stat="2"]').text()) ))
                                                $('.ng_count[data-stat="3"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="3"]').text()) + parseInt($('.f_count[data-stat="3"]').text()) ))
                                                $('.ng_count[data-stat="4"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="4"]').text()) + parseInt($('.f_count[data-stat="4"]').text()) ))
                                          }
                                          else{
                                                $('.ng_count[data-stat="1"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="1"]').text()) + parseInt($('.f_count[data-stat="1"]').text()) )) 
                                                $('.ng_count[data-stat="2"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="2"]').text()) + parseInt($('.f_count[data-stat="2"]').text()) ))

                                                $('.ng_count[data-stat="3"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="3"]').text()) + parseInt($('.f_count[data-stat="3"]').text()) ))

                                                $('.ng_count[data-stat="4"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="4"]').text()) + parseInt($('.f_count[data-stat="4"]').text()) ))
                                          }

                                          plot_subject_grades(data)
                                          if(prompt){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Grades found!'
                                                })
                                                $('#message_holder').text('Grades found.')
                                          }

                                    
                                    }

                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#message_holder').text('Unable to load grades.')
                              }
                        })
                  }

                  function plot_subject_grades(data){

                        $.each(data,function(a,b){
                                                
                              var q1status = 'input_grades'
                              var q2status = 'input_grades'
                              var q3status = 'input_grades'
                              var q4status = 'input_grades'

                              if(school == 'spct'.toUpperCase()){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text(b.prelemgrade != null ? parseFloat(b.prelemgrade).toFixed() : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text(b.midtermgrade != null ? parseFloat(b.midtermgrade).toFixed() : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text(b.prefigrade != null ? parseFloat(b.prefigrade).toFixed() : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text(b.finalgrade != null ? parseFloat(b.finalgrade).toFixed() : '')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text(b.prelemgrade != null ? parseFloat(b.prelemgrade).toFixed(2) : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text(b.midtermgrade != null ? parseFloat(b.midtermgrade).toFixed(2) : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text(b.prefigrade != null ? parseFloat(b.prefigrade).toFixed(2) : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text(b.finalgrade != null ? parseFloat(b.finalgrade).toFixed(2) : '')
                              }

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').attr('data-id',b.id)

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').attr('data-status',b.prelemstatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').attr('data-status',b.midtermstatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').attr('data-status',b.prefistatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').attr('data-status',b.finalstatus)

                              if(b.prelemstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-success')
                              }else if(b.prelemstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text("")
                              }else if(b.prelemstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-primary')
                              }else if(b.prelemstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text('DROPPED')
                              }else if(b.prelemstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text('INC')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.midtermstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-success')
                              }else if(b.midtermstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text("")
                              }else if(b.midtermstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-primary')
                              }else if(b.midtermstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text('DROPPED')
                              }else if(b.midtermstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text('INC')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.prefistatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-success')
                              }else if(b.prefistatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text("")
                              }else if(b.prefistatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-primary')
                              }else if(b.prefistatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text('DROPPED')
                              }else if(b.prefistatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text('INC')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.finalstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-success')
                              }else if(b.finalstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-primary')
                              }else if(b.finalstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text("")
                              }else if(b.finalstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text('DROPPED')
                              }else if(b.finalstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text('INC')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').removeAttr('class')
                                    if(school == 'spct'.toUpperCase()){
                                          $('th[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('grade_td text-center align-middle input_grades')
                                    }else{
                                          $('td[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('grade_td text-center align-middle input_grades')
                                    }
                              }

                              if(b.prelemstatus == 1 || b.prelemstatus == 7){
                                    $('.select[data-studid="'+b.studid+'"]').removeAttr('disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').removeClass('input_grades')
                              }

                              if(b.midtermstatus == 1 || b.midtermstatus == 7){
                                    $('.select[data-studid="'+b.studid+'"]').removeAttr('disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').removeClass('input_grades')
                              }

                              if(b.prefistatus == 1 || b.prefistatus == 7){
                                    $('.select[data-studid="'+b.studid+'"]').removeAttr('disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').removeClass('input_grades')
                              }

                              if(b.finalstatus == 1 || b.finalstatus == 7){
                                    $('.select[data-studid="'+b.studid+'"]').removeAttr('disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').removeClass('input_grades')
                              }

                              var temp_studid = b.studid
                              var average = parseFloat( ( parseFloat ( $('.grade_td[data-studid="'+temp_studid+'"][data-term="prelemgrade"]').text()) + parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="midtermgrade"]').text() )  + parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="prefigrade"]').text() )  + parseFloat( $('.grade_td[data-studid="'+temp_studid+'"][data-term="finalgrade"]').text() ) ) / 4 ).toFixed(2)

                              if(average != 'NaN'){
                                    $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text(average)

                                    if(average >= 3){
                                          $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('FAILED')
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('PASSED')
                                    }
                              }else{
                                    $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text('')
                                    $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('')
                              }
                        })

                        $('.grade_td').addClass('text-center')

                  }

                  $(document).on('change','#filter_status_by_subject',function(){
                        generate_by_subject(false)
                  })

                  $("#datatable_7").DataTable({
                        destroy: true,
                        data:[],
                        autoWidth: false,
                        lengthChange: false,
                  })

                  function generate_by_subject(){

                        var temp_subjects = [...new Map(subjects.map(item =>
                              [item['subjectID'], item])).values()];

                        $.each(temp_subjects,function(a,b){
                              
                              var temp_count = sched.filter(x=>x.subjid == b.subjectID)
                              b.student_count = temp_count.length
                              b.student_list = temp_count
                              b.withsub = sub_stud_1.filter(x=>x.subjid == b.subjectID).length || sub_stud_2.filter(x=>x.subjid == b.subjectID).length || sub_stud_3.filter(x=>x.subjid == b.subjectID).length || sub_stud_4.filter(x=>x.subjid == b.subjectID).length > 0 ? true : false;

                              b.withapp = app_stud_1.filter(x=>x.subjid == b.subjectID).length || app_stud_2.filter(x=>x.subjid == b.subjectID).length || app_stud_3.filter(x=>x.subjid == b.subjectID).length || app_stud_4.filter(x=>x.subjid == b.subjectID).length > 0 ? true : false;

                              b.withpen = pen_stud_1.filter(x=>x.subjid == b.subjectID).length || pen_stud_2.filter(x=>x.subjid == b.subjectID).length || pen_stud_3.filter(x=>x.subjid == b.subjectID).length || pen_stud_4.filter(x=>x.subjid == b.subjectID).length > 0 ? true : false;

                              b.withuns = uns_stud_1.filter(x=>x.subjid == b.subjectID).length || uns_stud_2.filter(x=>x.subjid == b.subjectID).length || uns_stud_3.filter(x=>x.subjid == b.subjectID).length || uns_stud_4.filter(x=>x.subjid == b.subjectID).length > 0 ? true : false;

                              b.withinc = inc_stud_1.filter(x=>x.subjid == b.subjectID).length || inc_stud_2.filter(x=>x.subjid == b.subjectID).length || inc_stud_3.filter(x=>x.subjid == b.subjectID).length || inc_stud_4.filter(x=>x.subjid == b.subjectID).length > 0 ? true : false;

                              b.withdrop = drop_stud_1.filter(x=>x.subjid == b.subjectID).length || drop_stud_2.filter(x=>x.subjid == b.subjectID).length || drop_stud_3.filter(x=>x.subjid == b.subjectID).length || drop_stud_4.filter(x=>x.subjid == b.subjectID).length > 0 ? true : false;

                        })

                        

                        var selected_status =  $('#filter_status_by_subject').val()

                        var uns_checked = selected_status == "uns" ? 'selected="selected"':''
                        var sub_checked = selected_status == "sub" ? 'selected="selected"':''
                        var app_checked = selected_status == "app" ? 'selected="selected"':''
                        var pen_checked = selected_status == "pen" ? 'selected="selected"':''
                        var inc_checked = selected_status == "inc" ? 'selected="selected"':''
                        var drop_checked = selected_status == "drop" ? 'selected="selected"':''


                        $('#filter_status_by_subject').empty();
                        $('#filter_status_by_subject').append('<option value="uns" '+uns_checked+'>Unsubmitted ('+temp_subjects.filter(x=>x.withuns == true).length+')</option>');
                        $('#filter_status_by_subject').append('<option value="sub" '+sub_checked+'>Submitted ('+temp_subjects.filter(x=>x.withsub == true).length+')</option>');
                        $('#filter_status_by_subject').append('<option value="app" '+app_checked+'>Approved ('+temp_subjects.filter(x=>x.withapp == true).length+')</option>');
                        $('#filter_status_by_subject').append('<option value="pen" '+pen_checked+'>Pending ('+temp_subjects.filter(x=>x.withpen == true).length+')</option>');
                        $('#filter_status_by_subject').append('<option value="inc" '+inc_checked+'>INC ('+temp_subjects.filter(x=>x.withinc == true).length+')</option>');
                        $('#filter_status_by_subject').append('<option value="drop" '+drop_checked+'>Dropped ('+temp_subjects.filter(x=>x.withdrop == true).length+')</option>');
                        
                     
                        temp_subjects = temp_subjects.filter(x=>x.student_count != 0)

                        subjects = temp_subjects

                        if($('#filter_status_by_subject').val() == 'sub'){
                              temp_subjects = temp_subjects.filter(x=>x.withsub == true)
                        }else if($('#filter_status_by_subject').val() == 'pen'){
                              temp_subjects = temp_subjects.filter(x=>x.withpen == true)
                        }else if($('#filter_status_by_subject').val() == 'app'){
                              temp_subjects = temp_subjects.filter(x=>x.withapp == true)
                        }else if($('#filter_status_by_subject').val() == 'uns'){
                              temp_subjects = temp_subjects.filter(x=>x.withuns == true)
                        }else if($('#filter_status_by_subject').val() == 'inc'){
                              temp_subjects = temp_subjects.filter(x=>x.withinc == true)
                        }else if($('#filter_status_by_subject').val() == 'drop'){
                              temp_subjects = temp_subjects.filter(x=>x.withdrop == true)
                        }

                        

                        $("#datatable_7").DataTable({
                              destroy: true,
                              data:temp_subjects,
                              autoWidth: false,
                              lengthChange: false,
                              columns: [
                                    { "data": "subjDesc"},
                                    // { "data": "courseabrv"},
                                    { "data": "student_count"},
                                    // { "data": null},
                                    // { "data": null},
                                    // { "data": null},
                                    // { "data": null},
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    // {
                                    //       'targets': 1,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    //             var text = '<a class="mb-0">'+rowData.courseabrv+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname.replace(' COLLEGE','')+'</p>';
                                    //             $(td)[0].innerHTML =  text
                                    //             $(td).addClass('align-middle')
                                    //       }
                                    // },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0 by_subj_view_stud" href="#" data-id="'+rowData.subjectID+'">'+rowData.student_count+'</a>';
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    // {
                                    //       'targets': 3,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                                
                                    //             var text = null
                                    //             if($('#filter_status_by_subject').val() == ""){
                                    //                   text = 'N/A';
                                    //             }

                                    //             if(school == 'GBBC'){
                                    //                   $(td).attr('hidden','hidden')
                                    //             }

                                    //             $(td)[0].innerHTML = text
                                    //             $(td).addClass('align-middle')
                                    //             $(td).addClass('text-center')
                                    //       }
                                    // },
                                    // {
                                    //       'targets': 4,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    //             var text = null
                                    //             if($('#filter_status_by_subject').val() == ""){
                                    //                   text = 'N/A';
                                    //             }

                                    //             if(school == 'GBBC'){
                                    //                   $(td).attr('hidden','hidden')
                                    //             }

                                    //             $(td)[0].innerHTML = text
                                    //             $(td).addClass('align-middle')
                                    //             $(td).addClass('text-center')
                                    //       }
                                    // },
                                    // {
                                    //       'targets': 5,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    //             var text = null
                                    //             if($('#filter_status_by_subject').val() == ""){
                                    //                   text = 'N/A';
                                    //             }

                                    //             if(school == 'GBBC'){
                                    //                   $(td).attr('hidden','hidden')
                                    //             }

                                    //             $(td)[0].innerHTML = text
                                    //             $(td).addClass('align-middle')
                                    //             $(td).addClass('text-center')
                                    //       }
                                    // },
                                    // {
                                    //       'targets': 6,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    //             var text = null

                                    //             if($('#filter_status_by_subject').val() == ""){
                                    //                   text = 'N/A';
                                    //             }

                                               

                                    //             $(td)[0].innerHTML = text
                                    //             $(td).addClass('align-middle')
                                    //             $(td).addClass('text-center')
                                    //       }
                                    // },
                              ]
                        })




                  }

                  function update_list_display(){

                        $('.section_submitted[data-term="1"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="1" data-status="1">'+[...new Map(sub_sec_1.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_submitted[data-term="2"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="2" data-status="1">'+[...new Map(sub_sec_2.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_submitted[data-term="3"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="3" data-status="1">'+[...new Map(sub_sec_3.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_submitted[data-term="4"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="4" data-status="1">'+[...new Map(sub_sec_4.map(item => [item['id'], item])).values()].length+'</a>'

                        $('.section_approved[data-term="1"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="1" data-status="7">'+[...new Map(app_sec_1.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_approved[data-term="2"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="2" data-status="7">'+[...new Map(app_sec_2.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_approved[data-term="3"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="3" data-status="7">'+[...new Map(app_sec_3.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_approved[data-term="4"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="4" data-status="7">'+[...new Map(app_sec_4.map(item => [item['id'], item])).values()].length+'</a>'

                        $('.section_pending[data-term="1"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="1" data-status="3">'+[...new Map(pen_sec_1.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_pending[data-term="2"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="2" data-status="3">'+[...new Map(pen_sec_2.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_pending[data-term="3"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="3" data-status="3">'+[...new Map(pen_sec_3.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_pending[data-term="4"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="4" data-status="3">'+[...new Map(pen_sec_4.map(item => [item['id'], item])).values()].length+'</a>'

                        $('.section_inc[data-term="1"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="1" data-status="8">'+[...new Map(inc_sec_1.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_inc[data-term="2"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="2" data-status="8">'+[...new Map(inc_sec_2.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_inc[data-term="3"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="3" data-status="8">'+[...new Map(inc_sec_3.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_inc[data-term="4"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="4" data-status="8">'+[...new Map(inc_sec_4.map(item => [item['id'], item])).values()].length+'</a>'

                        $('.section_drop[data-term="1"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="1" data-status="9">'+[...new Map(drop_sec_1.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_drop[data-term="2"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="2" data-status="9">'+[...new Map(drop_sec_2.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_drop[data-term="3"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="3" data-status="9">'+[...new Map(drop_sec_3.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_drop[data-term="4"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="4" data-status="9">'+[...new Map(drop_sec_4.map(item => [item['id'], item])).values()].length+'</a>'
                        
                        $('.section_unsubmitted[data-term="1"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="1" data-status="0">'+[...new Map(uns_sec_1.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_unsubmitted[data-term="2"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="2" data-status="0">'+[...new Map(uns_sec_2.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_unsubmitted[data-term="3"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="3" data-status="0">'+[...new Map(uns_sec_3.map(item => [item['id'], item])).values()].length+'</a>'
                        $('.section_unsubmitted[data-term="4"]')[0].innerHTML = '<a href="#" class="view_sections" data-term="4" data-status="0">'+[...new Map(uns_sec_4.map(item => [item['id'], item])).values()].length+'</a>'

                        $('.unsubmitted[data-term="1"]')[0].innerHTML = '<a href="#" class="view_uns" data-term="1" data-status="7">'+[...new Map(uns_stud_1.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.unsubmitted[data-term="2"]')[0].innerHTML = '<a href="#" class="view_uns" data-term="2" data-status="7">'+[...new Map(uns_stud_2.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.unsubmitted[data-term="3"]')[0].innerHTML = '<a href="#" class="view_uns" data-term="3" data-status="7">'+[...new Map(uns_stud_3.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.unsubmitted[data-term="4"]')[0].innerHTML = '<a href="#" class="view_uns" data-term="4" data-status="7">'+[...new Map(uns_stud_4.map(item => [item['studid'], item])).values()].length+'</a>'

                        $('.submitted[data-term="1"]')[0].innerHTML = '<a href="#" class="view_submitted" data-term="1" data-status="7">'+[...new Map(sub_stud_1.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.submitted[data-term="2"]')[0].innerHTML = '<a href="#" class="view_submitted" data-term="2" data-status="7">'+[...new Map(sub_stud_2.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.submitted[data-term="3"]')[0].innerHTML = '<a href="#" class="view_submitted" data-term="3" data-status="7">'+[...new Map(sub_stud_3.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.submitted[data-term="4"]')[0].innerHTML = '<a href="#" class="view_submitted" data-term="4" data-status="7">'+[...new Map(sub_stud_4.map(item => [item['studid'], item])).values()].length+'</a>'

                        $('.approved[data-term="1"]')[0].innerHTML = '<a href="#" class="view_approved" data-term="1" data-status="7">'+[...new Map(app_stud_1.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.approved[data-term="2"]')[0].innerHTML = '<a href="#" class="view_approved" data-term="2" data-status="7">'+[...new Map(app_stud_2.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.approved[data-term="3"]')[0].innerHTML = '<a href="#" class="view_approved" data-term="3" data-status="7">'+[...new Map(app_stud_3.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.approved[data-term="4"]')[0].innerHTML = '<a href="#" class="view_approved" data-term="4" data-status="7">'+[...new Map(app_stud_4.map(item => [item['studid'], item])).values()].length+'</a>'

                        $('.pending[data-term="1"]')[0].innerHTML = '<a href="#" class="view_pending" data-term="1" data-status="7">'+[...new Map(pen_stud_1.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.pending[data-term="2"]')[0].innerHTML = '<a href="#" class="view_pending" data-term="2" data-status="7">'+[...new Map(pen_stud_2.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.pending[data-term="3"]')[0].innerHTML = '<a href="#" class="view_pending" data-term="3" data-status="7">'+[...new Map(pen_stud_3.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.pending[data-term="4"]')[0].innerHTML = '<a href="#" class="view_pending" data-term="4" data-status="7">'+[...new Map(pen_stud_4.map(item => [item['studid'], item])).values()].length+'</a>'

                        $('.inc[data-term="1"]')[0].innerHTML = '<a href="#" class="view_inc" data-term="1" data-status="8">'+[...new Map(inc_stud_1.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.inc[data-term="2"]')[0].innerHTML = '<a href="#" class="view_inc" data-term="2" data-status="8">'+[...new Map(inc_stud_2.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.inc[data-term="3"]')[0].innerHTML = '<a href="#" class="view_inc" data-term="3" data-status="8">'+[...new Map(inc_stud_3.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.inc[data-term="4"]')[0].innerHTML = '<a href="#" class="view_inc" data-term="4" data-status="8">'+[...new Map(inc_stud_4.map(item => [item['studid'], item])).values()].length+'</a>'

                        $('.drop[data-term="1"]')[0].innerHTML = '<a href="#" class="view_drop" data-term="1" data-status="9">'+[...new Map(drop_stud_1.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.drop[data-term="2"]')[0].innerHTML = '<a href="#" class="view_drop" data-term="2" data-status="9">'+[...new Map(drop_stud_2.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.drop[data-term="3"]')[0].innerHTML = '<a href="#" class="view_drop" data-term="3" data-status="9">'+[...new Map(drop_stud_3.map(item => [item['studid'], item])).values()].length+'</a>'
                        $('.drop[data-term="4"]')[0].innerHTML = '<a href="#" class="view_drop" data-term="4" data-status="9">'+[...new Map(drop_stud_4.map(item => [item['studid'], item])).values()].length+'</a>'


                  }

                  $(document).on('click','.view_sections',function(){
                        var term  = $(this).attr('data-term')
                        var status  = $(this).attr('data-status')

                        var title_text = ''

                        if(status == 0){
                              title_text += 'Section Unsubmitted Grades'
                        }else if(status == 1){
                              title_text += 'Section Submitted Grades'
                        }else if(status == 7){
                              title_text += 'Section Approved Grades'
                        }else if(status == 3){
                              title_text += 'Section Pending Grades'
                        }else if(status == 8){
                              title_text += 'Section INC Grades'
                        }else if(status == 9){
                              title_text += 'Section Dropped Grades'
                        }

                        if(term == 1){
                              title_text += ' <i>['+'Prelim'+']</i>'
                        }else if(term == 2){
                              title_text += ' <i>['+'Midterm'+']</i>'
                        }else if(term == 3){
                              title_text += ' <i>['+'Prefi'+']</i>'
                        }else if(term == 4){
                              title_text += ' <i>['+'Final'+']</i>'
                        }

                        $('.modal_title_1').each(function(){
                              $(this)[0].innerHTML = title_text;
                        })

                        current_status = status
                        view_section_list_modal(term,status)
                        $('#modal_4').modal()
                  })

                  $(document).on('click','.view_section_grades',function(){

                        var term  = $(this).attr('data-term')
                        var status  = $(this).attr('data-status')
                        var section  = $(this).attr('data-section')
                        var subjid  = $(this).attr('data-subj')
                        selected_subject = subjid
                        show_section_grades(term,status,section,subjid)
                        $('#modal_6').modal()

                  })

                  $(document).on('click','#grade_appove',function(){
                        $('.select').attr('disabled','disabled')
                        $('#quarter_select').val("").change()
                        $('#process_button').text('Approve')
                        $('#process_button').removeClass('btn-warning')
                        $('#process_button').addClass('btn-primary')
                        $('#process_button').attr('data-id',7)
                        $('#modal_8').modal()
                        current_status = 7
                  })

                  $(document).on('click','#grade_pending',function(){
                        $('#quarter_select').val("").change()
                        $('.select').attr('disabled','disabled')
                        $('#process_button').text('Pending')
                        $('#process_button').removeClass('btn-primary')
                        $('#process_button').addClass('btn-warning')
                        $('#modal_8').modal()
                        $('#process_button').attr('data-id',3)
                        current_status = 3
                  })

                  $(document).on('change','#quarter_select',function() {
                        var term = $(this).val()
                        if(term == ""){
                              $('.select_all').attr('disabled','disabled')
                              $('.select').attr('disabled','disabled')
                              $('.grade_submission_student').text()
                              $('#submit_selected_grade').attr('disabled','disabled')
                              $('.select').removeAttr('data-id')
                              $('.grade_submission_student').empty()
                              return false
                        }else if(term == "prelemgrade"){
                              selected_term = 1;
                        }else if(term == "midtermgrade"){
                              selected_term = 2;
                        }else if(term == "prefigrade"){
                              selected_term = 3;
                        }else if(term == "finalgrade"){
                              selected_term = 4;
                        }

                        $('#submit_selected_grade').removeAttr('disabled')
                        $('.select_all').removeAttr('disabled')
                        $('.select').removeAttr('disabled')


                        $('.grade_td[data-term="'+term+'"]').each(function(a,b){

                              if(( current_status == 7 && $(this).attr('data-status') == 7 ) || ( current_status == 7 && $(this).attr('data-status') == 3 )){
                                    $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                              }else if(current_status == 3 && $(this).attr('data-status') == 3){
                                    $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                              }else if($(this).attr('data-status') == 9 || $(this).attr('data-status') == 8){
                                    $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                              }
                              




                              $('.grade_submission_student[data-studid="'+$(this).attr('data-studid')+'"]').text($(this).text())
                              $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('data-id',$(this).attr('data-id'))
                        })
                  })

                  $(document).on('click','#process_button',function(){
                        if($(this).attr('data-id') == 7){
                              approve_grade()
                        }else if($(this).attr('data-id') == 3){
                              pending_grade()
                        }
                        
                  })

                  

                  function show_section_grades(term = null, status = null, section = null, subjid = null){

                        var data = get_current_selecton_data(term,status)

                        data = [...new Map(data.map(item => [item['studid'], item])).values()]

                        $('#datatable_6').empty()

                        if(data.length == 0){
                              $('.approve_grade').attr('hidden','hidden')
                              $('.pending_grade').attr('hidden','hidden')
                              $('#select_all').attr('disabled','disabled')
                              return false
                        }

                        if(status == 1){
                              $('.approve_grade').removeAttr('hidden')
                              $('.pending_grade').removeAttr('hidden')
                        }else if(status == 7){
                              $('.approve_grade').attr('hidden','hidden')
                              $('.pending_grade').removeAttr('hidden')
                        }else{
                              $('.approve_grade').attr('hidden','hidden')
                              $('.pending_grade').attr('hidden','hidden')
                        }
                       
                        $('.select_all').removeAttr('disabled')
                        $('.select_all').prop('checked',true)
                        data = data.filter(x=>x.prospectusID == subjid)
                        $('.selected_count').text(data.length)

                        var female = 0;
                        var male = 0;

                        $.each(data,function (a,b) {

                              var grade = null
                              if(term == 1){ grade = b.prelemgrade }
                              else if(term == 2){ grade = b.midtermgrade }
                              else if(term == 3){ grade = b.prefigrade }
                              else if(term == 4){ grade = b.finalgrade }

                              var temp_student = students.filter(x=>x.studid == b.studid)

                              if(male == 0 && temp_student[0].gender == 'MALE'){
                                    $('#datatable_6').append('<tr class="bg-secondary"><th colspan="4">MALE</th></tr>')
                                    male = 1
                              }else if(female == 0 && temp_student[0].gender == 'FEMALE'){
                                    $('#datatable_6').append('<tr class="bg-secondary"><th colspan="4">FEMALE</th></tr>')
                                    female = 1
                              }

                              $('#datatable_6').append('<tr><td><input data-id="'+b.id+'" checked="checked" type="checkbox" class="select"></td><td>'+temp_student[0].sid+'</td><td>'+temp_student[0].studentname+'</td><td data-studid="'+temp_student[0].sid+'" class="grade_submission_student text-center">'+grade+'</td></tr>')
                              
                        })
                  }

                  function view_section_list_modal(term = null, status = null){

                        var data = []

                        if(term == 1){
                              if(status == 1){ data = sub_sec_1 }
                              else if(status == 7){ data = app_sec_1 }
                              else if(status == 3){ data = pen_sec_1 }
                              else if(status == 0){ data = uns_sec_1 }
                              else if(status == 8){ data = inc_sec_1 }
                              else if(status == 9){ data = drop_sec_1 }
                        }else if(term == 2){
                              if(status == 1){ data = sub_sec_2 }
                              else if(status == 7){ data = app_sec_2 }
                              else if(status == 3){ data = pen_sec_2 }
                              else if(status == 0){ data = uns_sec_2 }
                              else if(status == 8){ data = inc_sec_2 }
                              else if(status == 9){ data = drop_sec_2 }
                        }else if(term == 3){
                              if(status == 1){ data = sub_sec_3 }
                              else if(status == 7){ data = app_sec_3 }
                              else if(status == 3){ data = pen_sec_3 }
                              else if(status == 0){ data = uns_sec_3 }
                              else if(status == 8){ data = inc_sec_3 }
                              else if(status == 9){ data = drop_sec_3 }
                        }else if(term == 4){
                              if(status == 1){ data = sub_sec_4 }
                              else if(status == 7){ data = app_sec_4 }
                              else if(status == 3){ data = pen_sec_4 }
                              else if(status == 0){ data = uns_sec_4 }
                              else if(status == 8){ data = inc_sec_4 }
                              else if(status == 9){ data = drop_sec_4 }
                        }

                        var student_data = get_current_selecton_data(term,status)

                        data = [...new Map(data.map(item => [item['id'], item])).values()]

                        $.each(data,function(a,b){
                              var temp_data = student_data.filter(x=>x.sectionID == b.id)
                              var count = [...new Map(temp_data.map(item => [item['prospectusID'], item])).values()].length
                              b.subj_count = count
                        })

                        $("#datatable_4").DataTable({
                              destroy: true,
                              data:data,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "sectionDesc"},
                                    { "data": null}
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem" >'+rowData.levelname.replace(' COLLEGE','')+'</p>';  
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<button class="btn btn-primary btn-sm view_section_subjects btn-block" data-term="'+term+'" data-status="'+status+'" data-section="'+rowData.id+'">View Subjects ( ' +rowData.subj_count+' )</button>'   
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                              ]
                        })
                  }




                  $(document).on('click','.view_section_subjects',function(){
                        var temp_section = $(this).attr('data-section')
                        var term = $(this).attr('data-term')
                        var status = $(this).attr('data-status')
                        var section_info = all_sections.filter(x=>x.id == temp_section)
                        $('#modal_sectionname').text(section_info[0].sectionDesc)
                        selected_term = term
                        current_status = status
                        selected_section = temp_section
                        show_section_subject(term, status, temp_section)
                        $('#modal_5').modal()

                  })

                  function show_section_subject(term = null, status = null, section = null){

                        var temp_subjects = all_section_sched.filter(x=>x.sectionID == section)
                        var data = get_current_selecton_data(term, status)

                        if(term == 1){
                              if(status == 0){
                                    $.each(temp_subjects,function(a,b){
                                          b.student_count = sched.filter(x=>x.schedid == b.id).length
                                    })
                              }else if(status == 1 || status == 7 || status == 3 || status == 8 || status == 9){
                                    var subject_holder = []
                                    $.each(temp_subjects,function(a,b){
                                          student_count = data.filter(x=>x.prospectusID == b.subjectID && x.sectionID == section)
                                          student_count = [...new Map(student_count.map(item => [item['studid'], item])).values()].length
                                          if(student_count > 0){
                                                b.student_count = student_count
                                                subject_holder.push(b)
                                          }
                                    })
                                    temp_subjects = subject_holder
                              }
                        }else if(term == 2){
                              if(status == 0){
                                    $.each(temp_subjects,function(a,b){
                                          b.student_count = sched.filter(x=>x.schedid == b.id).length
                                    })
                              }else if(status == 1 || status == 7 || status == 3 || status == 8 || status == 9){
                                    var subject_holder = []
                                    $.each(temp_subjects,function(a,b){
                                          student_count = data.filter(x=>x.prospectusID == b.subjectID && x.sectionID == section)
                                          student_count = [...new Map(student_count.map(item => [item['studid'], item])).values()].length
                                          if(student_count > 0){
                                                b.student_count = student_count
                                                subject_holder.push(b)
                                          }
                                    })
                                    temp_subjects = subject_holder
                              }
                        }else if(term == 3){
                              if(status == 0){
                                    $.each(temp_subjects,function(a,b){
                                          b.student_count = sched.filter(x=>x.schedid == b.id).length
                                    })
                              }else if(status == 1 || status == 7 || status == 3 || status == 8 || status == 9){
                                    var subject_holder = []
                                    $.each(temp_subjects,function(a,b){
                                          student_count = data.filter(x=>x.prospectusID == b.subjectID && x.sectionID == section)
                                          student_count = [...new Map(student_count.map(item => [item['studid'], item])).values()].length
                                          if(student_count > 0){
                                                b.student_count = student_count
                                                subject_holder.push(b)
                                          }
                                    })
                                    temp_subjects = subject_holder
                              }
                        }else if(term == 4){
                              if(status == 0){
                                    $.each(temp_subjects,function(a,b){
                                          b.student_count = sched.filter(x=>x.schedid == b.id).length
                                    })
                              }else if(status == 1 || status == 7 || status == 3 || status == 8 || status == 9){
                                    var subject_holder = []
                                    $.each(temp_subjects,function(a,b){
                                          student_count = data.filter(x=>x.prospectusID == b.subjectID && x.sectionID == section)
                                          student_count = [...new Map(student_count.map(item => [item['studid'], item])).values()].length
                                          if(student_count > 0){
                                                b.student_count = student_count
                                                subject_holder.push(b)
                                          }
                                    })
                                    temp_subjects = subject_holder
                              }
                        }

                       

                        $.each(temp_subjects,function(a,b){
                              b.teacher = null
                              b.tid = null
                              var temp_teacher = teacher.filter(x=>x.id == b.teacherID)
                              if(temp_teacher.length > 0){
                                    b.teacher = temp_teacher[0].teachername
                                    b.tid = temp_teacher[0].tid
                              }
                             
                        })

                        $("#datatable_5").DataTable({
                              destroy: true,
                              data:temp_subjects,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "subjDesc"},
                                    { "data": "teacher"},
                                    { "data": "student_count"}
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem" >'+rowData.subjCode+'</p>';
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.teacher+'</a><p class="text-muted mb-0" style="font-size:.7rem" >'+rowData.tid+'</p>';
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(status == 1 || status == 7){
                                                      var text = '<button class="btn btn-primary btn-sm view_section_grades btn-block" style="font-size:.8rem"  data-term="'+term+'" data-status="'+status+'" data-section="'+rowData.id+'" data-subj="'+rowData.subjectID+'">View Grades ( '+rowData.student_count+' )</button>';
                                                      $(td)[0].innerHTML = text
                                                      $(td).addClass('align-middle')
                                                      $(td).addClass('text-center')
                                                }else{
                                                      var text = rowData.student_count;
                                                }
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                              ]
                        })

                  }

                  function pending_grade(){

                        var selected = []
                        var term = selected_term
                        var grade_term = ''

                        if(term == 1){
                              term = 'prelemstatus'
                              grade_term = 'prelemgrade'
                        }else if(term == 2){
                              term = 'midtermstatus'
                              grade_term = 'midtermgrade'
                        }else if(term == 3){
                              term = 'prefistatus'
                              grade_term = 'prefigrade'
                        }else if(term == 4){
                              term = 'finalstatus'
                              grade_term = 'finalgrade'
                        }

                        $('.select').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this).attr('data-id') != undefined){
                                    selected.push($(this).attr('data-id'))
                              }
                        })

                        if(selected.length == 0){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No student selected'
                              })
                              return false
                        }

                        $.ajax({
                              type:'POST',
                              url: '/college/grades/pending/ph',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    term:term,
                                    selected:selected,
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Added to pending!'
                                          })
                                          var term = selected_term
                                          var status = current_status
                                          var section = selected_section
                                          var subjid = selected_subject

                                          if(all_grades.length > 0){
                                                $.each(selected,function(a,b){
                                                      $('.select[data-id="'+b+'"]').attr('disabled','disabled')
                                                      $('.grade_td[data-id="'+b+'"][data-term="'+grade_term+'"]').attr('data-status',3)
                                                      $('.grade_td[data-id="'+b+'"][data-term="'+grade_term+'"]').addClass('bg-warning')
                                                      var temp_id = all_grades.findIndex(x=>x.id == b)
                                                      if(grade_term == "prelemgrade"){
                                                            all_grades[temp_id].prelemstatus = 3
                                                      }else if(grade_term == "midtermgrade"){
                                                            all_grades[temp_id].midtermstatus = 3
                                                      }else if(grade_term == "prefigrade"){
                                                            all_grades[temp_id].prefistatus = 3
                                                      }else if(grade_term == "finalgrade"){
                                                            all_grades[temp_id].finalstatus = 3
                                                      }
                                                })
                                          }

                                          $('.grade_td').addClass('input_grades')
                                          plot_subject_grades(all_grades)

                                          $.each(selected,function(a,b){
                                                if(term == 1){
                                                      if(status == 7){
                                                            var temp_grade_data = app_stud_1.filter(x=>x.id == b)
                                                            app_stud_1 = app_stud_1.filter(x=>x.id != b)
                                                      }else{
                                                            var temp_grade_data = sub_stud_1.filter(x=>x.id == b)
                                                            sub_stud_1 = sub_stud_1.filter(x=>x.id != b)
                                                      }
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].prelemstatus = 3
                                                            pen_stud_1.push(temp_grade_data[0])
                                                      }
                                                }else if(term == 2){
                                                      if(status == 7){
                                                            var temp_grade_data = app_stud_2.filter(x=>x.id == b)
                                                            app_stud_2 = app_stud_2.filter(x=>x.id != b)
                                                      }else{
                                                            var temp_grade_data = sub_stud_2.filter(x=>x.id == b)
                                                            sub_stud_2 = sub_stud_2.filter(x=>x.id != b)
                                                      }
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].midtermstatus = 3
                                                            pen_stud_1.push(temp_grade_data[0])
                                                      }
                                                }else if(term == 3){
                                                      if(status == 7){
                                                            var temp_grade_data = app_stud_3.filter(x=>x.id == b)
                                                            app_stud_3 = app_stud_3.filter(x=>x.id != b)
                                                      }else{
                                                            var temp_grade_data = sub_stud_3.filter(x=>x.id == b)
                                                            sub_stud_3 = sub_stud_3.filter(x=>x.id != b)
                                                      }
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].prefistatus = 3
                                                            pen_stud_1.push(temp_grade_data[0])
                                                      }
                                                }else if(term == 4){
                                                      if(status == 7){
                                                            var temp_grade_data = app_stud_4.filter(x=>x.id == b)
                                                            app_stud_4 = app_stud_4.filter(x=>x.id != b)
                                                      }else{
                                                            var temp_grade_data = sub_stud_4.filter(x=>x.id == b)
                                                            sub_stud_4 = sub_stud_4.filter(x=>x.id != b)
                                                      }
                                                      if(temp_grade_data.length > 0){
                                                            temp_grade_data[0].finalstatus = 3
                                                            pen_stud_4.push(temp_grade_data[0])
                                                      }
                                                }
                                          })

                                          update_data(term)
                                          update_list_display()
                                          view_list(term,status)

                                          if(all_grades.length == 0){
                                                show_section_grades(term,status,section,subjid)
                                                show_section_subject(term, status, section)
                                                view_section_list_modal(term,status)
                                                view_student_subjects(selected_student)
                                          }
                                          
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })

                  }

                  function view_list(term = null, status = null){

                        data = []
                        var temp_data = []
                        var button_text = ''
                        var button_class = ''
                        var title_text = ''

                        if(status == 0){
                              button_text = 'View Unsubmitted Grades'
                              button_class = 'view_unsubmitted_grades'
                              title_text += 'Student Unsubmitted Grades'
                        }else if(status == 1){
                              button_text = 'View Submitted Grades'
                              button_class = 'view_submitted_grades'
                              title_text += 'Student Submitted Grades'
                        }else if(status == 7){
                              button_text = 'View Aproved Grades'
                              button_class = 'view_approve_grades'
                              title_text += 'Student Approved Grades'
                        }else if(status == 3){
                              button_text = 'View Pending Grades'
                              button_class = 'view_pending_grades'
                              title_text += 'Student Pending Grades'
                        }else if(status == 8){
                              button_text = 'View INC Grades'
                              button_class = 'view_inc_grades'
                              title_text += 'Student INC Grades'
                        }else if(status == 9){
                              button_text = 'View Dropped Grades'
                              button_class = 'view_drop_grades'
                              title_text += 'Student Dropped Grades'
                        }else{
                              button_text = 'View Unsubmitted Grades'
                              button_class = 'view_uns_grades'
                              title_text += 'Student Unsubmitted Grades'
                        }

                        if(term == 1){
                              if(status == 1){ data = sub_stud_1 }
                              else if(status == 7){ data = app_stud_1 }
                              else if(status == 3){ data = pen_stud_1 }
                              else if(status == 0){ data = uns_stud_1 }
                              else if(status == 8){ data = inc_stud_1 }
                              else if(status == 9){ data = drop_stud_1 }
                              else{ data = uns_stud_1 }
                        }else if(term == 2){
                              if(status == 1){ data = sub_stud_2 }
                              else if(status == 7){ data = app_stud_2 }
                              else if(status == 3){ data = pen_stud_2 }
                              else if(status == 0){ data = uns_stud_2 }
                              else if(status == 8){ data = inc_stud_2 }
                              else if(status == 9){ data = drop_stud_2 }
                              else{ data = uns_stud_2 }
                        }else if(term == 3){
                              if(status == 1){ data = sub_stud_3 }
                              else if(status == 7){ data = app_stud_3 }
                              else if(status == 3){ data = pen_stud_3 }
                              else if(status == 0){ data = uns_stud_3 }
                              else if(status == 8){ data = inc_stud_3 }
                              else if(status == 9){ data = drop_stud_3 }
                              else{ data = uns_stud_3 }
                        }else if(term == 4){
                              if(status == 1){ data = sub_stud_4 }
                              else if(status == 7){ data = app_stud_4 }
                              else if(status == 3){ data = pen_stud_4 }
                              else if(status == 0){ data = uns_stud_4 }
                              else if(status == 8){ data = inc_stud_4 }
                              else if(status == 9){ data = drop_stud_4 }
                              else{ data = uns_stud_4 }
                        }

                        $.each(data,function (a,b) {
                              var temp_student = students.filter(x=>x.studid == b.studid)
                              if(temp_student.length == 0){
                                    b.studentname = ''
                              }else{
                                    b.studentname = temp_student[0].studentname
                              }
                        })

                        data = [...new Map(data.map(item => [item['studid'], item])).values()]

                        if(selected_term == 1){
                              title_text += ' <i>['+'Prelim'+']</i>'
                        }else if(selected_term == 2){
                              title_text += ' <i>['+'Midterm'+']</i>'
                        }else if(selected_term == 3){
                              title_text += ' <i>['+'Prefi'+']</i>'
                        }else if(selected_term == 4){
                              title_text += ' <i>['+'Final'+']</i>'
                        }

                        $('.modal_title_1')[0].innerHTML = title_text;
                        $('#modal_title_3')[0].innerHTML = title_text;

                        $("#datatable_1").DataTable({
                              destroy: true,
                              data:data,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "studentname"},
                                    { "data": null},
                              ],
                              columnDefs: [
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  '<button class="btn btn-primary btn-sm '+button_class+'" data-studid="'+rowData.studid+'" style="font-size:.8rem">'+button_text+'</button>'
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                              ]
                        })

                  }

                  $(document).on('click','.view_approved',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 7
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  $(document).on('click','.view_uns',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 0
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  $(document).on('click','.view_pending',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 3
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  $(document).on('click','.view_drop',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 9
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  $(document).on('click','.view_inc',function(){
                        selected_term = $(this).attr('data-term')
                        current_status = 8
                        view_list(selected_term,current_status)
                        $('#modal_1').modal()
                  })

                  $(document).on('click','.view_approve_grades',function(){
                        selected_student = $(this).attr('data-studid')
                        view_student_subjects(selected_student)
                        $('.approve_grade').attr('hidden','hidden')
                        $('.pending_grade').removeAttr('hidden')
                        $('#modal_3').modal()
                  })

                  $(document).on('click','.view_pending_grades',function(){
                        selected_student = $(this).attr('data-studid')
                        view_student_subjects(selected_student)
                        $('.approve_grade').attr('hidden','hidden')
                        $('.pending_grade').attr('hidden','hidden')
                        $('#modal_3').modal()
                  })

                  $(document).on('click','.view_inc_grades',function(){
                        selected_student = $(this).attr('data-studid')
                        view_student_subjects(selected_student)
                        $('.approve_grade').attr('hidden','hidden')
                        $('.pending_grade').attr('hidden','hidden')
                        $('#modal_3').modal()
                  })

                  $(document).on('click','.view_drop_grades',function(){
                        selected_student = $(this).attr('data-studid')
                        view_student_subjects(selected_student)
                        $('.approve_grade').attr('hidden','hidden')
                        $('.pending_grade').attr('hidden','hidden')
                        $('#modal_3').modal()
                  })

                  $(document).on('click','.view_unsubmitted_grades',function(){
                        selected_student = $(this).attr('data-studid')
                        view_student_subjects(selected_student)
                        $('.approve_grade').attr('hidden','hidden')
                        $('.pending_grade').attr('hidden','hidden')
                        $('#modal_3').modal()
                  })
                        
                  $(document).on('click','.select_all',function(){
                        if($(this).prop('checked') == true){
                              $('.selected_count').text($('.select[checked="checked"]').length)
                              $('.select').prop('checked',true)
                        }else{
                              $('.selected_count').text(0)
                              $('.select').prop('checked',false)
                        }
                  })

            $(document).on('change','.select',function(){
                  var checked_count = 0;
                  var unchecked_count = 0;
                  $('.select').each(function(){
                        if($(this).prop('checked') == true){
                              checked_count += 1;
                        }else{
                              unchecked_count += 1;
                        }
                  })
                  $('.selected_count').text(checked_count)

                  if(unchecked_count != 0){
                        $('.select_all').prop('checked',false)
                  }else{
                        $('.select_all').prop('checked',true)
                  }
            })

            var selected_student = null 

            $(document).on('click','.view_submitted_grades',function(){
                  selected_student = $(this).attr('data-studid')
                  view_student_subjects(selected_student)
                  $('.select_all').removeAttr('disabled')
                  $('.approve_grade').removeAttr('hidden')
                  $('.pending_grade').removeAttr('hidden')
                  $('#modal_3').modal()
            })

            function view_student_subjects(selected_student){

                  var term = selected_term
                  var status = current_status

                  if(term == 1){
                        if(status == 1){ data = sub_stud_1 }
                        else if(status == 7){ data = app_stud_1 }
                        else if(status == 3){ data = pen_stud_1 }
                        else if(status == 0){ data = uns_stud_1 }
                  }else if(term == 2){
                        if(status == 1){ data = sub_stud_2 }
                        else if(status == 7){ data = app_stud_2 }
                        else if(status == 3){ data = pen_stud_2 }
                        else if(status == 0){ data = uns_stud_2 }
                  }else if(term == 3){
                        if(status == 1){ data = sub_stud_3 }
                        else if(status == 7){ data = app_stud_3 }
                        else if(status == 3){ data = pen_stud_3 }
                        else if(status == 0){ data = uns_stud_3 }
                  }else if(term == 4){
                        if(status == 1){ data = sub_stud_4 }
                        else if(status == 7){ data = app_stud_4 }
                        else if(status == 3){ data = pen_stud_4 }
                        else if(status == 0){ data = uns_stud_4 }
                  }

                  data = data.filter(x=>x.studid == selected_student)
           
                  data = [...new Map(data.map(item => [item['prospectusID'], item])).values()]
                  var studentinfo = students.filter(x=>x.studid == selected_student)

                  $.each(data,function(a,b){
                        var temp_prospectus = sched.filter(x=>x.subjectID == b.prospectusID)
                        var temp_teacher = teacher.filter(x=>x.id == b.teacherID)

                        b.teachername = null
                        b.tid = null
                        b.section = null
                        b.levelname = null
                        b.subjDesc = null
                        b.subjCode = null

                        if(temp_prospectus.length > 0){
                              var schedinfo = all_section_sched.filter(x=>x.id == temp_prospectus[0].schedid)
                              var sectionInfo = all_sections.filter(x=>x.id == schedinfo[0].sectionID)
                              var temp_teacher = teacher.filter(x=>x.id == schedinfo[0].teacherID)
                              if(temp_teacher.length > 0){
                                    b.teachername = temp_teacher[0].teachername
                                    b.tid = temp_teacher[0].tid
                              }

                              b.section = sectionInfo[0].sectionDesc
                              b.levelname = sectionInfo[0].levelname
                              b.subjDesc = schedinfo[0].subjDesc
                              b.subjCode = schedinfo[0].subjCode
                        }
                  })

                  if(studentinfo.length > 0){
                        $('#student_name').text(studentinfo[0].studentname)
                  }
                 
                 
                  $("#datatable_3").DataTable({
                        destroy: true,
                        data:data,
                        lengthChange: false,
                        autoWidth: false,
                        paging:false,
                        bInfo:false,
                        order: [],
                        columns: [
                              { "data": null},
                              { "data": "section"},
                              { "data": "subjDesc"},
                              { "data": "teachername"},
                              { "data": null},
                        ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_id = ''
                                          temp_id = 'data-id="'+rowData.id+'"'
                                          $(td)[0].innerHTML = '<input '+temp_id+' checked="checked" type="checkbox" class="select">'
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var text = '<a class="mb-0">'+rowData.section+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname.replace('COLLEGE','')+'</p>';
                                          $(td)[0].innerHTML = text
                                          $(td).addClass('align-middle')
                                          
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+'</p>';
                                          $(td)[0].innerHTML = text
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var text = '<a class="mb-0">'+rowData.teachername+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+'</p>';
                                          $(td)[0].innerHTML = text
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_grade = null
                                     
                                          if(selected_term == 1 && rowData.prelemstatus != null ){
                                                temp_grade = rowData.prelemgrade 
                                          }else if(selected_term == 2 && rowData.midtermstatus != null){
                                                temp_grade = rowData.midtermgrade
                                          }else if(selected_term == 3 && rowData.prefistatus != null){
                                                temp_grade = rowData.prefigrade
                                          }else if(selected_term == 4 && rowData.finalstatus != null){
                                                temp_grade = rowData.finalgrade
                                          }
                                          
                                          
                                          $(td).text(temp_grade)
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                    }
                              },
                        ]
                  })

                  $('.selected_count').text($('.select[checked="checked"]').length)

                  if(current_status == 0 || current_status == 3 || $('.select').length == 0 || current_status == 8 || current_status == 9){
                        $('.pending_grade').attr('hidden','hidden')
                        $('.approve_grade').attr('hidden','hidden')
                        $('.select').attr('disabled','disabled')
                        $('.select_all').attr('disabled','disabled')
                  }

            }

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


