
@php
if(auth()->user()->type == 16){
      $extend = 'chairpersonportal.layouts.app2';
}else if(Session::get('currentPortal') == 14){
      $extend = 'deanportal.layouts.app2';
}else if(Session::get('currentPortal') == 17){
      $extend = 'superadmin.layouts.app2';
}else if(Session::get('currentPortal') == 3){
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
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css') }}">
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
      #et{
            height: 10px;
            visibility: hidden;
      }

      .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }
</style>
@endsection


@section('content')

@php

$sy = DB::table('sy')
            ->orderBy('sydesc')
            ->select(
                  'id',
                  'sydesc as text',
                  'isactive'
            )
            ->get(); 
$semester = DB::table('semester')
                  ->select(
                        'id',
                        'semester as text',
                        'isactive'
                  )
                  ->get(); 

$schoolinfo = DB::table('schoolinfo')->first()->abbreviation;

$registrar = DB::table('teacher')
                  ->where('usertypeid',3)
                  ->select(
                        'teacher.id',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->get();

$teacherid = DB::table('teacher')
                  ->where('tid',auth()->user()->email)
                  ->select('id')
                  ->first();

$gradepriv = array();

if(auth()->user()->type != 17){
      $gradepriv = DB::table('college_gradepriv')
                  ->where('deleted',0)
                  ->where('teacherid',$teacherid->id)
                  ->get();
                  
}else{
      $gradepriv = array((object)[
            'canedit'=>1,
            'canpending'=>1,
            'canapprove'=>1,
            'canpost'=>1,
            'canunpost'=>1,
            'cansetupdeadline'=>1,
            'canprint'=>1
      ]);
}



if(count($gradepriv) == 0){
      $gradepriv = array((object)[
            'canedit'=>0,
            'canpending'=>0,
            'canapprove'=>0,
            'canpost'=>0,
            'canunpost'=>0,
            'cansetupdeadline'=>0,
            'canprint'=>0
      ]);
}
                  

@endphp

<div class="modal fade" id="inputperiods_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Grades Deadline Setup</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row">
                        <div class="col-md-12" style="font-size:.8rem !important">
                              <table class="table table-sm table-striped table-bordered table-hovered table-hover " >
                                    <thead>
                                          <tr>
                                                <th width="25%" class="text-center">Prelim</th>
                                                <th width="25%" class="text-center">Midterm</th>
                                                <th width="25%" class="text-center">Prefi</th>
                                                <th width="25%" class="text-center">Final Term</th>
                                          </tr>
                                    </thead>
                                    <tbody id="date_holder">
                                          
                                    </tbody>
                              </table>
                        </div>  
                  </div> 
            </div>
          </div>
      </div>
</div>


<div class="modal fade" id="registrar_holder_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Registar</label>
                                    <select class="form-control select2" id="printable_registrar">

                                    </select>
                              </div>
                       </div>
                       <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="print_grades">Print</button>
                              </div>
                       </div>
                  </div>
            </div>
      </div>
</div> 

<div class="modal fade" id="create_inputperiods_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title">Grades Deadline Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                  <div class="row">
                        <div class="col-md-12  form-group">
                              <label for="" class="mb-1"><span id="term_input_label"></span> Grades Deadline</label>
                              <input type="text" class="form-control select2 form-control-sm" id="input_inputperiod">
                        </div>
                  </div> 
                  <div class="row">
                        <div class="col-md-12">
                              <button class="btn btn-primary btn-sm" id="create_inputperiods_button">Create</button>
                              <button class="btn btn-success btn-sm" id="update_inputperiods_button" hidden>Update</button>
                        </div>
                  </div>
            </div>
          </div>
      </div>
</div>

<div class="modal fade" id="modal_8" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
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
                                                <option value="1" >Prelim</option>
                                                <option value="2" >Midterm</option>
                                                <option value="3" >PreFinal</option>
                                                <option value="4" >Final</option>
                                          </select>
                                    <small class="text-danger"><i>Select a term to view and submit grades.</i></small>
                              </div>
                              <div class="col-md-6">
                                    <button class="btn btn-primary float-right btn-sm" id="process_button">Approve</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed"  style="font-size:.8rem" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="3%"><input type="checkbox" disabled checked="checked" class="select_all"> </th>
                                                      <th width="12%">SID</th>
                                                      <th width="28%">Student</th>
                                                      <th width="45%">Subject</th>
                                                      <th width="8%" class="text-center">Grade</th>
                                                      <th width="5%" class="text-center"></th>
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

<div class="modal fade" id="comment_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size:.9rem">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Student</label>
                                    <input type="text" class="form-control form-control-sm" id="comment_student" readonly>
                              </div>
                       </div>
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject</label>
                                    <input type="text" class="form-control form-control-sm" id="comment_subject" readonly>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Term</label>
                                    <input type="text" class="form-control form-control-sm" id="comment_term" readonly>
                              </div>
                        </div>
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Remarks</label>
                                    <textarea name="" rows="6" class="form-control form-control-sm" id="comment_remarks"></textarea>
                              </div>
                       </div>
                       <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="add_comment_button">Add Remarks</button>
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
                        <h1>College Grade Completion</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">College Grade Completion</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>


<section class="content pt-0">
<div class="container-fluid">
      <div class="row ">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body p-3" style="font-size:.9rem !important">
                              <div class="row">
                                    <div class="col-md-2  form-group mb-1">
                                          <label for="" class="mb-1">School Year</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sy"></select>
                                    </div>
                                    <div class="col-md-2  form-group mb-1">
                                          <label for="" class="mb-1">Semester</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sem"></select>
                                    </div>
                              </div>
                              {{-- <hr class="mt-0 mb-1"> --}}
                              <div class="row">
                                    <div class="col-md-4 ">
                                          <label for="" class="mb-1">Teacher</label>
                                          <select class="form-control select2 form-control-sm" id="filter_teacher"></select>
                                    </div>
                                    <div class="col-md-4 ">
                                          <label for="" class="mb-1">Subject</label>
                                          <select class="form-control select2 form-control-sm" id="filter_subjects" disabled></select>
                                    </div>
                                    <div class="col-md-4 ">
                                          <label for="" class="mb-1">Student</label>
                                          <select class="form-control select2 form-control-sm" id="filter_student"></select>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      {{-- <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body p-2 pt-1 pb-1"  style="font-size:.9rem !important">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label class="mb-0">Grades Deadline:</label><span class="ml-2" id="activeInputPeriodHolder"></span>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div> --}}
      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body p-2 pt-1 pb-1"  style="font-size:.9rem !important">
                              <div class="row" id="grades_setup_holder" hidden>
                                    <div class="col-md-3" >
                                          <label for="" class="mb-0">Term</label>
                                          <div id="setup_term_holder"></div>
                                    </div>
                                    <div class="col-md-4">
                                          <label for=""  class="mb-0">Final Grade Computation</label>
                                          <div id="setup_fgc_holder"></div>
                                    </div>
                                    <div class="col-md-3" >
                                          <label for="" class="mb-0">Grading Scale</label>
                                          <div id="setup_gs_holder"></div>
                                    </div>
                                    <div class="col-md-1 text-center" >
                                          <label for="" class="mb-0">Dec. Pl.</label>
                                          <div id="setup_dp_holder"></div>
                                    </div>
                                    <div class="col-md-1 text-center" >
                                          <label for="" class="mb-0">Pass. Rate</label>
                                          <div id="setup_pg_holder"></div>
                                    </div>
                              </div>
                              <div class="row" id="no_grades_setup_holder" hidden>

                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body pt-1 pb-1" style="font-size:.9rem !important">
                              <div class="row">
                                    <div class="col-md-12">
                                          <span class="badge badge-default">Unsubmitted</span>
                                          <span class="badge badge-success">Submitted</span>
                                          <span class="badge badge-primary">Approved</span>
                                          <span class="badge badge-secondary">Dean Approved</span>
                                          <span class="badge badge-info">Posted</span>
                                          <span class="badge badge-warning">Pending</span>
                                          <span class="badge badge-warning">INC</span>
                                          <span class="badge badge-danger">Dropped</span>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body" style="font-size:.9rem !important">
                              
                              <div class="row">
                                    <div class="col-md-4">
                                          @if($gradepriv[0]->cansetupdeadline == 1)
                                                <button class="btn btn-primary btn-sm inputperiod"  >Deadline Setup</button>
                                          @endif
                                          @if($gradepriv[0]->canprint == 1)
                                                <a  class="btn btn-default btn-sm disabled ml-2" id="print_grades_to_modal"  ><i class="fas fa-print"></i> Print Grade</a>
                                          @endif
                                    </div>
                                    <div class="col-md-8 text-right">
                                         
                                          
                                          @if($gradepriv[0]->canpending == 1)
                                                <button id="grade_pending" class="btn btn-warning btn-sm" disabled data-id="3">Pending Grades</button>
                                          @endif

                                          @if($gradepriv[0]->canapprove == 1)
                                                <button id="grade_approve" class="btn btn-primary btn-sm " disabled data-id="7">Approve Grades</button>
                                          @endif

                                          @if($gradepriv[0]->canpost == 1)
                                                <button id="grade_posting" class="btn btn-info btn-sm " disabled data-id="4">Post Grades</button>
                                          @endif
                                          {{-- <button id="grade_unposting" class="btn btn-danger btn-sm" disabled data-id="3">Unpost Grades</button> --}}
                                          @if($gradepriv[0]->canunpost == 1)
                                                <button id="grade_unposting" class="btn btn-danger btn-sm " disabled data-id="10">Unpost Grades</button>
                                          @endif

                                          @if($gradepriv[0]->canedit == 1)
                                                 <button class="btn btn-primary btn-sm save_grades" disabled>Save Grades</button>
                                          @endif
                                        
                                    </div>
                              </div>
                              <hr class="mt-2 mb-2"> 
                              <div class="row">
                                    <div class="col-md-2">
                                          <div class="icheck-primary d-inline mr-3">
                                                <input type="checkbox" id="filter_showdates" class="filter_showdates" >
                                                <label for="filter_showdates">Show Dates</label>
                                          </div>
                                    </div>
                                    <div class="col-md-8" id="term_holder" hidden>
                                          <div class="row">
                                                <div class="col-md-2 ">
                                                      <div class="icheck-success d-inline" data-id="1">
                                                            <input class="form-control" type="radio" id="filter_all" name="filter_term" value="all" checked="checked" >
                                                            <label for="filter_all">All
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-2 filter_holder" data-id="1">
                                                      <div class="icheck-success d-inline " >
                                                            <input class="form-control" type="radio" id="filter_prelim" name="filter_term" value="prelim" >
                                                            <label for="filter_prelim" >Prelim
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-2 filter_holder"  data-id="2">
                                                      <div class="icheck-success d-inline ">
                                                            <input class="form-control" type="radio" id="filter_midterm" name="filter_term" value="midterm" >
                                                            <label for="filter_midterm" >Midterm
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-2 filter_holder"  data-id="3">
                                                      <div class="icheck-success d-inline ">
                                                            <input class="form-control" type="radio" id="filter_prefi" name="filter_term" value="prefi"  >
                                                            <label for="filter_prefi" >Prefi
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-2 filter_holder" data-id="4">
                                                      <div class="icheck-success d-inline " >
                                                            <input class="form-control" type="radio" id="filter_final" name="filter_term" value="final"  >
                                                            <label for="filter_final" >Final Term
                                                            </label>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              {{-- <div class="row mt-1">
                                    <div class="col-md-12">
                                          <span class="badge badge-default">Unsubmitted</span>
                                          <span class="badge badge-success">Submitted</span>
                                          <span class="badge badge-primary">Program Head Approved</span>
                                          <span class="badge badge-secondary">Dean Approved</span>
                                          <span class="badge badge-info">Posted</span>
                                          <span class="badge badge-warning">Pending</span>
                                          <span class="badge badge-warning">INC</span>
                                          <span class="badge badge-danger">Dropped</span>
                                    </div>
                              </div> --}}
                              <div class="row mt-2">
                                    <div class="col-md-12">
                                          <div class=" table-responsive tableFixHead  " style="height: 400px">
                                                <table class="table table-sm table-bordered table-head-fixed" style="font-size:.7rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th id="gdth0" class="text-center"  width="3%">#</th>
                                                                  <th id="gdth1" class="text-center"  width="7%">SID</th>
                                                                  <th id="gdth2" class="text-center" width="15%">Student</th>
                                                                  <th id="gdth3" class="text-center" width="31%">Subject</th>
                                                                  <th id="gdth4" width="7%" class="text-center term_holder p-0 align-middle" data-term="1">Prelim</th>
                                                                  <th id="gdth5" width="7%" class="text-center term_holder p-0 align-middle" data-term="2">Midterm</th>
                                                                  <th id="gdth6" width="7%" class="text-center term_holder p-0 align-middle" data-term="3">PreFi</th>
                                                                  <th id="gdth7" width="7%" class="text-center term_holder p-0 align-middle" data-term="4">Final Term</th>
                                                                  <th id="gdth8" width="7%" class="text-center term_holder p-0 align-middle" data-term="5">Final Grade</th>
                                                                  <th id="gdth9" width="8%" class="text-center term_holder p-0 align-middle" data-term="6">Remarks</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="subject_holder">

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6 text-right">
                                          <button class="btn btn-info btn-sm save_grades" disabled>Save Grades</button>
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
{{-- <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script> --}}
{{-- <script src="jquery.datetimepicker.js"></script> --}}
<script src="{{asset('plugins/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>

<script>
      var gradepriv = @json($gradepriv)
</script>

{{-- Status Updating --}}
<script>

      var gradesetup = [];

      $('#input_inputperiod').datetimepicker({
            format:'M d, Y H:i',
      });

      function getgradesetup(){
            $.ajax({
                  type:'GET',
                  url:'/semester-setup/getactive-setup',
                  async: false,  
                  data:{
                        syid:$('#filter_sy').val(),
                        semid:$('#filter_sem').val(),
                  },
                  success:function(data) {
                        $('#grades_setup_holder').attr('hidden','hidden')
                        $('#no_grades_setup_holder').attr('hidden','hidden')
                        gradesetup = data

                        if(gradesetup.length == 0){
                              $('#no_grades_setup_holder').removeAttr('hidden')
                              $('#no_grades_setup_holder')[0].innerHTML = '<div class="col-md-12"><p class="mb-0 text-danger">* No available grade setup.</p></div>'
                        }else{
                              $('#grades_setup_holder').removeAttr('hidden')
                              gradesetup = gradesetup[0]
                              
                              var termtext = ''
                              if(gradesetup.prelim == 1){
                                    termtext += '<span class="badge badge-primary ml-1">Prelim</span>'
                              }
                              if(gradesetup.midterm == 1){
                                    termtext += '<span class="badge badge-primary ml-1">Midterm</span>'
                              }
                              if(gradesetup.prefi == 1){
                                    termtext += '<span class="badge badge-primary ml-1">Prefi</span>'
                              }
                              if(gradesetup.final == 1){
                                    termtext += '<span class="badge badge-primary ml-1">Final</span>'
                              }
                              $('#setup_term_holder')[0].innerHTML = termtext
                              $('#setup_fgc_holder').text(gradesetup.f_frontend)
                              $('#setup_dp_holder').text(gradesetup.decimalPoint)
                              $('#setup_pg_holder').text(gradesetup.passingRate)

                              if(gradesetup.isPointScaled == 1){
                                    $('#setup_gs_holder').text('Decimal Point Scale ( 1 - 5 )')
                              }else{
                                    $('#setup_gs_holder').text('Numerical Point Scale ( 60 - 100 )')
                              }

                        }
                        display_columns()
                  }
            })
      }
      
      function display_columns(){
            var disprelim = 0
            var dismidterm = 0
            var disprefi = 0
            var disfinal = 0

            
            $('#quarter_select').select2({
                  allowClear:true,
                  placeholder: "Select Term",
            })

            if(gradesetup != null){
                  disprelim = gradesetup.prelim
                  dismidterm = gradesetup.midterm
                  disprefi = gradesetup.prefi
                  disfinal = gradesetup.final
            }

            if(disprelim == 0){
                  $('#quarter_select option[value="1"]').remove()
                  // $('.filter_holder[data-id="1"]').remove()
                  $('.term_holder[data-term=1]').attr('hidden','hidden')
            }
            
            if(dismidterm == 0){
                  $('#quarter_select option[value="2"]').remove()
                  // $('.term_holder[data-term=2]').remove()
                  $('.term_holder[data-term=2]').attr('hidden','hidden')
            }
            
            if(disprefi == 0){
                  $('#quarter_select option[value="3"]').remove()
                  // $('.term_holder[data-term=3]').remove()
                  $('.term_holder[data-term=3]').attr('hidden','hidden')
            }
            
            if(disfinal == 0){
                  $('#quarter_select option[value="4"]').remove()
                  // $('.term_holder[data-term=4]').remove()
                  $('.term_holder[data-term=4]').attr('hidden','hidden')
            }
      }

      $('#filter_status').select2({
            allowClear:true,
            placeholder: "All",
      })

      $(document).on('click','#grade_posting , #grade_pending , #grade_unposting , #grade_approve , #grade_unpost ' ,function(){
            $('#quarter_select').val("").change()
            $('.select').attr('disabled','disabled')
            $('#process_button').attr('disabled','disabled')
            $('#process_button').text($(this).text())
            $('#process_button').removeAttr('class')
            $('#process_button').addClass($(this).attr('class'))
            $('#process_button').attr('data-id',$(this).attr('data-id'))
            $('#modal_8').modal()
            $('.select').prop('checked',true)
      })

      $(document).on('change','#quarter_select',function(){
            $('.select_all').prop('checked',true)
            if($(this).val() != null && $(this).val() != ""){
                  $('#process_button').removeAttr('disabled')
                  $('.select').removeAttr('disabled')
                  $('.select_all').removeAttr('disabled')
                  display_for_posting(all_subjinfo)
            }else{
                  $('#datatable_8').empty()
                  $('#process_button').attr('disabled','disabled')
                  $('.select').attr('disabled','disabled')
                  $('.select_all').attr('disabled','disabled')
                  
            }
      })

      $(document).on('click','#process_button',function(){
            update_status($(this).attr('data-id'))
      })

      $(document).on('click','.select_all',function() {
            if($(this).prop('checked') == true){
                  $('.select').prop('checked',true)
            }else{
                  $('.select').each(function(){
                        if($(this).attr('disabled') == undefined){
                              $(this).prop('checked',false)
                        }
                  })
            }
      })

      function update_status(status){
            var selected = []
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

            var url = null
            var remarks = []

            if(status == 7){
                  url = '/college/grades/approve/ph'
            }else if(status == 10){
                  url = '/college/grades/unpost/ph'
            }if(status == 4){
                  url = '/college/grades/post'
            }else if(status == 3){
                  url = '/college/grades/pending/ph'

                  $('.with_comments').each(function(a,b){
                        remarks.push({
                              'id':$(this).attr('data-id'),
                              'remarks':$(this).text()
                        })
                  })


            }

            var term = ''

            if($('#quarter_select').val() == "1"){
                  term = 'prelemgrade';
            }else if($('#quarter_select').val() == "2"){
                  term = 'midtermgrade';
            }else if($('#quarter_select').val() == "3"){
                  term = 'prefigrade';
            }else if($('#quarter_select').val() == "4"){
                  term = 'finalgrade';
            }

            $.ajax({
                  type:'POST',
                  url: url,
                  data:{
                        syid:$('#filter_sy').val(),
                        semid:$('#filter_sem').val(),
                        term:term,
                        selected:selected,
                        remarks:remarks
                  },
                  success:function(data) {
                        if(data[0].status == 1){
                              var message = ''
                              if(status == 4){
                                    message = 'Grades Posted'
                              }else if(status == 7){
                                    message = 'Grades Approved'
                              }else if(status == 10){
                                    message = 'Grades Unposted'
                              }else if(status == 3){
                                    message = 'Added to pending'
                                    $('.select').each(function(){
                                          if($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this).attr('data-id') != undefined){
                                                $(this).attr('disabled','disabled')
                                          }
                                    })
                                    // display_for_posting()
                              }

                              getsubjects()
                              Toast.fire({
                                    type: 'success',
                                    title: message
                              })
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

      
      $(document).on('click','.add_comment',function(){

            var temp_term = ''
            var temp_studid = $(this).attr('data-studid')
            var temp_student = $(this).attr('data-student')
            var temp_subject = $(this).attr('data-subjcode') + ' - ' + $(this).attr('data-subj')
            var temp_dataid = $(this).attr('data-id')

            if($('#quarter_select').val() == "1"){
                  temp_term = 'Prelim'
            }else if($('#quarter_select').val() == "2"){
                  temp_term = 'Midterm'
            }else if($('#quarter_select').val() == "3"){
                  temp_term = 'Prefi'
            }else if($('#quarter_select').val() == "4"){
                  temp_term = 'Final'
            }

            $('#comment_remarks').val("")
            $('#comment_student').val(temp_student)
            $('#comment_subject').val(temp_subject)
            $('#comment_term').val(temp_term)

            $('#add_comment_button').attr('data-id',temp_dataid)
            $('#add_comment_button').attr('data-studid',temp_studid)

            $('#comment_form_modal').modal()      
      
      })


      $(document).on('click','#add_comment_button',function(){

            var temp_id = $(this).attr('data-id')
            var temp_studid = $(this).attr('data-studid')

            $('#comment_form_modal').modal('hide')   
            Toast.fire({
                  type: 'info',
                  title: 'Remarks Added'
            })  


            $('.temp_comment[data-id="'+temp_id+'"][data-studid="'+temp_studid+'"]')[0].innerHTML = '"<span class="with_comments" data-id="'+temp_id+'" data-studid="'+temp_studid+'">'+$('#comment_remarks').val()+ '</span>"'
            // $('.temp_comment[data-id="'+temp_id+'"][data-studid="'+temp_studid+'"]').addClass('with_comments')

      })
      
      function display_for_posting(data){

            var temp_sid = null
            var temp_studname = null
            var temp_studid = null
            var temp_course = null
            var temp_subjects = data[0].subjects
            var temp_grades = data[0].grades

            $('#datatable_8').empty()
          
            if($('#filter_student').val() != null && $('#filter_student').val() != ""){
                  var temp_studinfo = all_students.filter(x=>x.id == $('#filter_student').val() )
                  
                  if(temp_studinfo.length > 0){
                        temp_sid = temp_studinfo[0].sid
                        temp_studname = temp_studinfo[0].studname
                        temp_studid =  temp_studinfo[0].studid
                        temp_course = temp_studinfo[0].courseid
                  }

                  temp_subjects = temp_subjects.filter(x=>x.studid ==  $('#filter_student').val())

                  $.each(temp_subjects,function(a,b){
                      
                        var input_grade_class_midterm = 'input_grades'
                        var input_grade_class_final = 'input_grades'
                        var grade = ''
                        var is_disabled = ''
                        var temp_dataid = ''
                        
                        var comment  = ''
                        var comment_icon = ''

                        var stud_grade = temp_grades.filter(x=>x.studid == b.studid && x.prospectusID == b.id)

                        if(stud_grade.length > 0){

                              if($('#quarter_select').val() == "1"){
                                    grade = stud_grade[0].prelemgrade != null ? stud_grade[0].prelemgrade : ''
                                    if(stud_grade[0].prelemstatus == $('#process_button').attr('data-id')){
                                          is_disabled = 'disabled="disabled"'
                                    }
                              }else if($('#quarter_select').val() == "2"){
                                    grade = stud_grade[0].midtermgrade != null ? stud_grade[0].midtermgrade : ''
                                    if(stud_grade[0].midtermstatus == $('#process_button').attr('data-id')){
                                          is_disabled = 'disabled="disabled"'
                                    }
                              }
                              else if($('#quarter_select').val() == "3"){
                                    grade = stud_grade[0].prefigrade != null ? stud_grade[0].prefigrade : ''
                                    if(stud_grade[0].prefistatus == $('#process_button').attr('data-id')){
                                          is_disabled = 'disabled="disabled"'
                                    }
                              }
                              else if($('#quarter_select').val() == "4"){
                                    grade = stud_grade[0].finalgrade != null ? stud_grade[0].finalgrade : ''
                                    if(stud_grade[0].finalstatus == $('#process_button').attr('data-id')){
                                          is_disabled = 'disabled="disabled"'
                                    }
                              }

                              // console.log(is_disabled)

                              temp_dataid = stud_grade[0].id

                              var grade_dates = temp_dates.filter(x=>x.headerid == stud_grade[0].id )
                                    
                              if($('#process_button').attr('data-id') == 3 && is_disabled == 'disabled="disabled"'){
                                    comment = '';
                                    if($('#quarter_select').val() == "1"){
                                          var datedetail = grade_dates.filter(x=>x.term == 'prelem' && x.pendstat == 1)
                                    }else if($('#quarter_select').val() == "2"){
                                          var datedetail = grade_dates.filter(x=>x.term == 'midterm' && x.pendstat == 1)
                                    }else if($('#quarter_select').val() == "3"){
                                          var datedetail = grade_dates.filter(x=>x.term == 'prefi' && x.pendstat == 1)
                                    }else if($('#quarter_select').val() == "4"){
                                          var datedetail = grade_dates.filter(x=>x.term == 'final' && x.pendstat == 1)
                                    }
                                    if(datedetail.length > 0){
                                          datedetail = datedetail[datedetail.length - 1]
                                          comment = '<br><i>"'+datedetail.pendcom+'"<i>';
                                    }

                              }else{
                                    comment = '<br><i class="temp_comment" data-studid="'+temp_studid+'" data-id="'+stud_grade[0].id+'" ><i>';
                              }

                              if($('#process_button').attr('data-id') == 3 && is_disabled != 'disabled="disabled"' && grade_dates.length > 0){
                                    comment_icon = '<a href="javascript:void(0)" class="add_comment" data-student="'+temp_studname+'" data-subjcode="'+b.subjCode+'" data-subj="'+b.subjDesc+'" data-id="'+stud_grade[0].id+'" data-studid="'+temp_studid+'"><i class="fas fa-comment-alt"></i></a>'
                              }

                              if($('#process_button').attr('data-id') == 3 && grade_dates.length == 0){
                                    is_disabled = 'disabled="disabled"'
                              }

                        }else{
                              is_disabled = 'disabled="disabled"'
                        }

                        $('#datatable_8').append('<tr><td><input '+is_disabled+' checked="checked" type="checkbox" class="select" data-studid="'+b.studid+'" data-id="'+temp_dataid+'"></td><td>'+temp_sid+'</td><td>'+temp_studname+comment+'</td><td>'+b.subjCode+' - '+b.subjDesc+'</td>'+
                              '<td class="text-center align-middle '+'" data-term="midtermgrade" data-pid="'+b.id+'" data-studid="'+temp_studid+'" data-course="'+temp_course+'" data-section="'+b.sectionid+'">'+grade+'</td>'+
                              '<td  class="text-center">'+comment_icon+'</td>'+
                              '</tr>')    
   
                  })

            }else if($('#filter_teacher').val() != null && $('#filter_teacher').val() != ""){

                  $.each(all_students,function(a,b){

                        temp_sid = b.sid
                        temp_studname = b.studname
                        temp_studid =  b.studid
                        temp_course = b.courseid

                        if($('#filter_subjects').val() != null && $('#filter_subjects').val() != ""){
                              d_subjects = temp_subjects.filter(x=>x.subjCode == $('#filter_subjects').val())
                        }else{
                              d_subjects = temp_subjects
                        }

                        
                        $.each(d_subjects.filter(x=>x.studid == b.studid),function(c,d){

                              var grade = ''
                              var is_disabled = ''
                              var temp_dataid = ''
                              var stud_grade = temp_grades.filter(x=>x.studid == d.studid && x.prospectusID == d.id)
                              var comment  = ''
                              var comment_icon = ''

                              if(stud_grade.length > 0){

                                    temp_dataid = stud_grade[0].id

                                    if($('#quarter_select').val() == "1"){
                                          grade = stud_grade[0].prelemgrade != null ? stud_grade[0].prelemgrade : ''
                                          if(stud_grade[0].prelemstatus == $('#process_button').attr('data-id')){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].prelemstatus == 4 && $('#process_button').attr('data-id') == 4){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].prelemstatus == 4 && $('#process_button').attr('data-id') == 10){
                                                is_disabled = ''
                                          }

                                    }else if($('#quarter_select').val() == "2"){
                                          grade = stud_grade[0].midtermgrade != null ? stud_grade[0].midtermgrade : ''
                                          if(stud_grade[0].midtermstatus == $('#process_button').attr('data-id')){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].midtermstatus == 4  && $('#process_button').attr('data-id') == 4){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].midtermstatus == 4 && $('#process_button').attr('data-id') == 10){
                                                is_disabled = ''
                                          }
                                    }
                                    else if($('#quarter_select').val() == "3"){
                                          grade = stud_grade[0].prefigrade != null ? stud_grade[0].prefigrade : ''
                                          if(stud_grade[0].prefistatus == $('#process_button').attr('data-id')){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].prefistatus == 4  && $('#process_button').attr('data-id') == 4){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].prefistatus == 4 && $('#process_button').attr('data-id') == 10){
                                                is_disabled = ''
                                          }
                                    }
                                    else if($('#quarter_select').val() == "4"){
                                          grade = stud_grade[0].finalgrade != null ? stud_grade[0].finalgrade : ''
                                          if(stud_grade[0].finalstatus == $('#process_button').attr('data-id')){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].finalstatus == 4  && $('#process_button').attr('data-id') == 4){
                                                is_disabled = 'disabled="disabled"'
                                          }

                                          if(stud_grade[0].finalstatus == 4 && $('#process_button').attr('data-id') == 10){
                                                is_disabled = ''
                                          }

                                    }

                                    var grade_dates = temp_dates.filter(x=>x.headerid == stud_grade[0].id )
                                    
                                    if($('#process_button').attr('data-id') == 3){

                                          comment = '';
                                   
                                          if($('#quarter_select').val() == "1"){
                                                var datedetail = grade_dates.filter(x=>x.term == 'prelem' && x.pendstat == 1)
                                          }else if($('#quarter_select').val() == "2"){
                                                var datedetail = grade_dates.filter(x=>x.term == 'midterm' && x.pendstat == 1)
                                          }else if($('#quarter_select').val() == "3"){
                                                var datedetail = grade_dates.filter(x=>x.term == 'prefi' && x.pendstat == 1)
                                          }else if($('#quarter_select').val() == "4"){
                                                var datedetail = grade_dates.filter(x=>x.term == 'final' && x.pendstat == 1)
                                          }

                                          if(datedetail.length > 0){
                                                datedetail = datedetail[datedetail.length - 1]
                                                comment = '<br><i class="temp_comment" data-studid="'+b.studid+'" data-id="'+stud_grade[0].id+'" >"'+datedetail.pendcom+'"<i>';
                                          }

                                    }else{
                                          comment = '<br><i class="temp_comment" data-studid="'+b.studid+'" data-id="'+stud_grade[0].id+'" ><i>';
                                    }

                                    if($('#process_button').attr('data-id') == 3 && is_disabled != 'disabled="disabled"' && grade_dates.length > 0){
                                          comment_icon = '<a href="javascript:void(0)" class="add_comment" data-student="'+temp_studname+'" data-subjcode="'+d.subjCode+'" data-subj="'+d.subjDesc+'" data-id="'+stud_grade[0].id+'" data-studid="'+b.studid+'"><i class="fas fa-comment-alt"></i></a>'
                                    }

                                    
                                    if($('#process_button').attr('data-id') == 3 && grade_dates.length == 0){
                                          is_disabled = 'disabled="disabled"'
                                    }

                                    if(grade == '' || grade == null){
                                          //is_disabled = 'disabled="disabled"'
                                    }

                                 

                              }else{
                                    is_disabled = 'disabled="disabled"'
                              }


                              $('#datatable_8').append('<tr><td class="align-middle"><input '+is_disabled+' checked="checked" type="checkbox" class="select align-middle" data-studid="'+b.studid+'" data-id="'+temp_dataid+'"></td><td>'+temp_sid+'</td><td>'+temp_studname+comment+'</td><td>'+d.subjCode+' - '+d.subjDesc+'</td>'+
                                    '<td class="text-center align-middle '+'" data-term="midtermgrade" data-pid="'+d.id+'" data-studid="'+temp_studid+'" data-course="'+temp_course+'" data-section="'+d.sectionid+'">'+grade+'</td>'+
                                    '<td class="text-center align-middle">'+comment_icon+'</td>'+
                                    '</tr>')  
                        
                        })

                  })

            }
      }


</script>

{{-- Grade Input Deadline --}}
<script>
      var all_inputperiods = []
      var selected_inputperiods = null

      function evaluate_caninput(){
            if(all_inputperiods.length == 0){
                  can_edit_status = 'Please Create Input Period'
            }else{
                  var check_started_period = all_inputperiods.filter(x=>x.startstatus == 1)
                  if(check_started_period.length == 0){
                        can_edit = true
                        can_edit_status = 'No Active Period'
                  }else{
                        var check_ended_period = check_started_period.filter(x=>x.endstatus == 0)
                        if(check_ended_period.length > 0 ){
                              can_edit = true
                              can_edit_status = 'Please End Active Period'
                        }else{
                              can_edit = true
                        }
                  }
            }
      }

      $(document).on('change','#filter_status',function(){
            if($(this).val() == ""){
                  var tempPeriod = all_inputperiods
            }else if($(this).val() == 1){
                  var tempPeriod = all_inputperiods.filter(x=>x.startstatus == 1 && x.endstatus == 0)
            }else if($(this).val() == 2){
                  var tempPeriod = all_inputperiods.filter(x=>x.startstatus == 0 && x.endstatus == 0)
            }else if($(this).val() == 3){
                  var tempPeriod = all_inputperiods.filter(x=>x.startstatus == 1 && x.endstatus == 1)
            }
            inputperiods_datatable(tempPeriod)
      })

      function inputperiodslist(){
          $.ajax({
              type:'GET',
              url:'/college/inputperiods/list',
              data:{
                  syid:$('#filter_sy').val(),
                  semid:$('#filter_sem').val()
              },
              success:function(data) {
                  all_inputperiods = data
                  // updateInputPeriodCard()
                  // evaluate_caninput()
                  inputperiods_datatable(all_inputperiods)
              },
          })
      }

      function inputperiodscreate(){
            $.ajax({
                  type:'GET',
                  url:'/college/inputperiods/create',
                  data:{
                        date:$('#input_inputperiod').val(),
                        syid:$('#filter_sy').val(),
                        semid:$('#filter_sem').val(),
                        term:selectedterm
                  },
                  success:function(data) {
                        if(data[0].status == 1){
                              all_inputperiods = data[0].data
                              inputperiods_datatable(all_inputperiods)
                              $('#create_inputperiods_form_modal').modal('hide')
                        }
                        Toast.fire({
                              type: data[0].icon,
                              title: data[0].message
                        })
                  },
            })
      }
      function inputperiodsupdate(){
            $.ajax({
                  type:'GET',
                  url:'/college/inputperiods/update',
                  data:{
                        date:$('#input_inputperiod').val(),
                        id:selected_inputperiods,
                        syid:$('#filter_sy').val(),
                        semid:$('#filter_sem').val(),
                  },
                  success:function(data) {
                        if(data[0].status == 1){
                              all_inputperiods = data[0].data
                              inputperiods_datatable(all_inputperiods)
                        }
                        Toast.fire({
                              type: data[0].icon,
                              title: data[0].message
                        })
                  },
            })
      }
      
      function delete_datedeadline(){
          $.ajax({
              type:'GET',
              url:'/college/inputperiods/delete',
              data:{
                  id:selected_inputperiods,
                  syid:$('#filter_sy').val(),
                  semid:$('#filter_sem').val(),
              },
              success:function(data) {
                  if(data[0].status == 1){
                        all_inputperiods = data[0].data
                        inputperiods_datatable(all_inputperiods)
                  }
                  Toast.fire({
                      type: data[0].icon,
                      title: data[0].message
                  })
              },
          })
      }

      function update_form_display(){
            var temp_info = all_inputperiods.filter(x=>x.id == selected_inputperiods)
            $('#input_start').prop('checked',false)
            $('#input_end').prop('checked',false)
            $('#input_end_holder').attr('hidden','hidden')
            $('#input_start').removeAttr('disabled')
            $('#input_end').removeAttr('disabled')
            if(temp_info[0].startstatus == 1){
                  $('#input_start').prop('checked',true)
                  $('#input_end_holder').removeAttr('hidden')
                  $('#input_start').attr('disabled','disabled')
                  $('#datestartholder').text(' : '+temp_info[0].startdatetimeformat2)
                  if(temp_info[0].endstatus == 1){
                        $('#input_end').prop('checked',true)
                        $('#input_end').attr('disabled','disabled')
                        $('#update_inputperiods_button').attr('hidden','hidden')
                        $('#dateendholder').text(' : '+temp_info[0].startdatetimeformat2)
                  }
            }
            $('#input_inputperiod').datetimepicker()
      }

      function inputperiods_datatable(data){

            var text = '<tr>'
            var buttonstext = '<tr>' 

            var prelimdate = data.filter(x=>x.term == 'prelim')
            var midtermdate = data.filter(x=>x.term == 'midterm')
            var prefidate = data.filter(x=>x.term == 'prefi')
            var finaldate = data.filter(x=>x.term == 'finalterm')

            if(prelimdate.length > 0){
                  text += '<td class="text-center">'+prelimdate[0].endformatdate2+'<br>'+prelimdate[0].endformattime2+'</td>';
                  buttonstext += '<td class="text-center align-middle"><a href="javascript:void(0)" data-id="'+prelimdate[0].id+'" class="edit_date_to_modal" data-label="Prelim"><i class="nav-icon fas fa-edit mr-2"></i></a><a href="javascript:void(0)"  data-id="'+prelimdate[0].id+'" class="delete_datedeadline"><i class="nav-icon fas fa-trash-alt text-danger"></i></a></td>'
            }else{
                  text += '<td class="text-center">No Date Added</td>';
                  buttonstext += ' <td class="text-center align-middle"><a href="javascript:void(0)" data-id="prelim" class="create_gradedeadline_to_modal" data-label="Prelim"><i class="nav-icon fas fa-plus text-primary"></i> Add Date</a></td>'
            }
            if(midtermdate.length > 0){
                  text += '<td class="text-center">'+midtermdate[0].endformatdate2+'<br>'+midtermdate[0].endformattime2+'</td>';
                  buttonstext += '<td class="text-center align-middle"><a href="javascript:void(0)" data-id="'+midtermdate[0].id+'" class="edit_date_to_modal" data-label="Midterm"><i class="nav-icon fas fa-edit mr-2"></i></a><a href="javascript:void(0)"  data-id="'+midtermdate[0].id+'" class="delete_datedeadline"><i class="nav-icon fas fa-trash-alt text-danger"></i></a></td>'
            }else{
                  text += '<td class="text-center">No Date Added</td>';
                  buttonstext += ' <td class="text-center align-middle "><a href="javascript:void(0)" data-id="midterm" class="create_gradedeadline_to_modal"  data-label="Midterm"><i class="nav-icon fas fa-plus text-primary"></i> Add Date</a></td>'
            }
            if(prefidate.length > 0){
                  text += '<td class="text-center">'+prefidate[0].endformatdate2+'<br>'+prefidate[0].endformattime2+'</td>';
                  buttonstext += '<td class="text-center align-middle"><a href="javascript:void(0)" data-id="'+prefidate[0].id+'" class="edit_date_to_modal" data-label="Prefi"><i class="nav-icon fas fa-edit mr-2"></i></a><a href="javascript:void(0)"  data-id="'+prefidate[0].id+'" class="delete_datedeadline"><i class="nav-icon fas fa-trash-alt text-danger"></i></a></td>'
            }else{
                  text += '<td class="text-center">No Date Added</td>';
                  buttonstext += ' <td class="text-center align-middle"><a href="javascript:void(0)" data-id="prefi" class="create_gradedeadline_to_modal" data-label="Prefi"><i class="nav-icon fas fa-plus text-primary"></i> Add Date</a></td>'
            }
            if(finaldate.length > 0){
                  text += '<td class="text-center">'+finaldate[0].endformatdate2+'<br>'+finaldate[0].endformattime2+'</td>';
                  buttonstext += '<td class="text-center align-middle"><a href="javascript:void(0)" data-id="'+finaldate[0].id+'" class="edit_date_to_modal" data-label="Final Term"><i class="nav-icon fas fa-edit mr-2"></i></a><a href="javascript:void(0)"  data-id="'+finaldate[0].id+'" class="delete_datedeadline"><i class="nav-icon fas fa-trash-alt text-danger"></i></a></td>'
            }else{
                  text += '<td class="text-center">No Date Added</td>';
                  buttonstext += ' <td class="text-center align-middle"><a href="javascript:void(0)" data-id="finalterm" class="create_gradedeadline_to_modal" data-label="Final Term"><i class="nav-icon fas fa-plus text-primary"></i> Add Date</a></td>'
            }

            text += '</tr>'
            buttonstext += '</tr>'

            $('#date_holder').empty()
            $('#date_holder').append(text)
            $('#date_holder').append(buttonstext)
         
        }


      $(document).on('change','#inputperiods',function(){
          if($(this).val() != "" && $(this).val() != "create"){
              $('.edit_inputperiods').removeAttr('hidden')
              $('.delete_inputperiods').removeAttr('hidden')
          }else{
              if($(this).val() == "create"){
                  selected_inputperiods = null
                  $('#inputperiods').val("").change()
                  $('#create_inputperiods_button').removeAttr('hidden')
                  $('#update_inputperiods_button').attr('hidden','hidden')
                  $('#inputperiods_form_modal').modal()
              }
              $('.edit_inputperiods').attr('hidden','hidden')
              $('.delete_inputperiods').attr('hidden','hidden')
          }
      })
      
      
      $(document).on('click','#create_inputperiods_button',function(){
            if($('#input_inputperiod').val() == ""){
                  Toast.fire({
                        type: 'error',
                        title: 'Date time is empty!'
                  })
                  return false
            }
            inputperiodscreate()
      })

      $(document).on('click','#update_inputperiods_button',function(){
           if($('#input_inputperiod').val() == ""){
                  Toast.fire({
                        type: 'error',
                        title: 'Date time is empty!'
                  })
                  return false
            }
            inputperiodsupdate()
      
      })

      $(document).on('click','.inputperiod',function(){

          $('#inputperiods_form_modal').modal()
      })

      var selectedterm = null
      $(document).on('click','.create_gradedeadline_to_modal',function(){
            selectedterm = $(this).attr('data-id')
            $('#input_inputperiod').val("")
            $('#term_input_label').text($(this).attr('data-label'))
             $('#create_inputperiods_form_modal').modal()
             $('#create_inputperiods_button').removeAttr('hidden')
            $('#update_inputperiods_button').attr('hidden','hidden')
      })

      $(document).on('click','.edit_date_to_modal',function(){
            selected_inputperiods = $(this).attr('data-id')
            $('#term_input_label').text($(this).attr('data-label'))
            var tempInfo = all_inputperiods.filter(x=>x.id == selected_inputperiods)
            $('#input_inputperiod').val(tempInfo[0].endformat2)
            $('#create_inputperiods_form_modal').modal()
            $('#create_inputperiods_button').attr('hidden','hidden')
            $('#update_inputperiods_button').removeAttr('hidden')
      })

      $(document).on('click','.delete_datedeadline',function(){
            selected_inputperiods = $(this).attr('data-id')

            Swal.fire({
                  text: 'Are you sure you want to remove input period?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Remove'
            }).then((result) => {
                  if (result.value) {
                        delete_datedeadline()
                  }
            })
      })

</script>

{{-- Display Grades --}}
<script>
      var sy = @json($sy);
      var sem = @json($semester);
      var all_teachers = []
      var all_students = []
      var all_subjinfo = []
      var first = true;
      var active_sy = sy.filter(x=>x.isactive == 1)[0]
      var active_sem = sem.filter(x=>x.isactive == 1)[0]

      const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

      $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });

      function getteachers(){
            $.ajax({
                  type:'GET',
                  url:'/college/completiongrades/getteachers',
                  data:{
                        syid:$("#filter_sy").val(),
                        semid:$("#filter_sem").val()
                  },
                  success:function(data) {
                        all_teachers = data
                        $("#filter_teacher").empty();
                        $("#filter_teacher").append('<option value="">Select Teacher</option>');
                        $("#filter_teacher").select2({
                              data: all_teachers,
                              allowClear: true,
                              placeholder: "Select Teacher",
                        })
                  }
            })
      }

      function getstudents(){

            $('#subject_holder').empty()

            $.ajax({
                  type:'GET',
                  url:'/college/completiongrades/getstudents',
                  data:{
                        syid:$("#filter_sy").val(),
                        semid:$("#filter_sem").val(),
                        teacherid:$('#filter_teacher').val()
                  },
                  success:function(data) {
                        all_students = data

                        var temp_student = $('#filter_student').val()

                        $("#filter_student").empty();
                        $("#filter_student").append('<option value="">Select Student</option>');
                        $("#filter_student").select2({
                              data: all_students,
                              allowClear: true,
                              placeholder: "Select Student",
                        })

                        if(temp_student != null && temp_student != ""){
                              $('#filter_student').val(temp_student).change()
                        }


                        if($('#filter_teacher').val() == "" || $('#filter_teacher').val() == null){
                              return false
                        }

                        getsubjects()
                        
                        
                  }
            })
      }

      $('#filter_subjects').empty()
      $('#filter_subjects').append('<option value="">Subject</option>')
      $('#filter_subjects').select2({
            'data':[],
            'placeholder':'Subject',
            'allowClear':true
      })

      $(document).on('change','#filter_subjects',function(){
            displaysubjects(all_subjinfo)
            display_for_posting(all_subjinfo)

      })

      function getsubjects(){

            $('#subject_holder').empty()

            $.ajax({
                  type:'GET',
                  url:'/college/completiongrades/getsubjects',
                  data:{
                        syid:$("#filter_sy").val(),
                        semid:$("#filter_sem").val(),
                        teacherid:$('#filter_teacher').val(),
                        studid:$('#filter_student').val()
                  },
                  success:function(data) {
                        $('#subject_holder').empty()
                        all_subjinfo = data

                        if($('#filter_subjects').val() == "" || $('#filter_subjects') == null){
                              $('#filter_subjects').empty()
                              $('#filter_subjects').append('<option value="">Subject</option>')
                              $('#filter_subjects').select2({
                                    'data':all_subjinfo[0].subjectload,
                                    'placeholder':'Subject',
                                    'allowClear':true
                              })
                        }
                       
                        displaysubjects(data)
                  }
            })
      }


      var registrar = @json($registrar)

      $('#printable_registrar').select2({
            'data':registrar,
            'placeholder':'Select Registrar'
      })

      var temp_subjects
      var temp_grades
      var temp_dates

      function displaysubjects(data){

            temp_subjects = data[0].subjects
            temp_grades = data[0].grades
            temp_dates = data[0].dates

            $('#subject_holder').empty()

            var disprelim = 0
            var dismidterm = 0
            var disprefi = 0
            var disfinal = 0


            if(gradesetup != null){
                  disprelim = gradesetup.prelim
                  dismidterm = gradesetup.midterm
                  disprefi = gradesetup.prefi
                  disfinal = gradesetup.final
            }

            if($('#filter_student').val() != null && $('#filter_student').val() != ""){
                  var temp_studinfo = all_students.filter(x=>x.id == $('#filter_student').val() )
                  
                  if(temp_studinfo.length > 0){
                        temp_sid = temp_studinfo[0].sid
                        temp_studname = temp_studinfo[0].studname
                        temp_studid =  temp_studinfo[0].studid
                        temp_course = temp_studinfo[0].courseid
                        temp_studinfo = temp_studinfo[0]
                  }else{
                        temp_studinfo = temp_studinfo[0]
                  }

                  if($('#filter_subjects').val() != null && $('#filter_subjects').val() != ''){
                        temp_subjects = temp_subjects.filter(x=>x.studid ==  $('#filter_student').val() && x.subjCode == $('#filter_subjects').val())
                  }else{
                        temp_subjects = temp_subjects.filter(x=>x.studid ==  $('#filter_student').val())
                  }

                  var count = 0;

                  $.each(temp_subjects,function(a,b){
                        count += 1;
                        display_grades_table(b,temp_grades,count,temp_studinfo,temp_dates)
                  })

            }else if($('#filter_teacher').val() != null && $('#filter_teacher').val() != ""){

                  count = 0
                  var d_subjects = temp_subjects

                  if($('#filter_subjects').val() != null && $('#filter_subjects').val() != ""){
                        d_subjects = temp_subjects.filter(x=>x.subjCode == $('#filter_subjects').val())
                  }

                  $.each(all_students,function(a,b){

                        temp_sid = b.sid
                        temp_studname = b.studname
                        temp_studid =  b.studid
                        temp_course = b.courseid
                     
                        $.each(d_subjects.filter(x=>x.studid == b.studid),function(c,d){
                              count += 1;
                              display_grades_table(d,temp_grades,count,b,temp_dates)
                        })

                  })

                  if($('.filter_showdates').prop('checked')){
                        $('.input_grades').removeClass('input_grades')
                  }

            }

      }


      function display_grades_table(b,temp_grades,count,studinfo,temp_dates){

            var disprelim = 0
            var dismidterm = 0
            var disprefi = 0
            var disfinal = 0

            if(gradesetup != null){
                  disprelim = gradesetup.prelim
                  dismidterm = gradesetup.midterm
                  disprefi = gradesetup.prefi
                  disfinal = gradesetup.final
            }

            var temp_sid = studinfo.sid
            var temp_studname = studinfo.studname
            var temp_studid =  studinfo.studid
            var temp_course = studinfo.courseid

            var midgrades = ''
            var finalgrades = ''
            
            var prelimgrades = ''
            var prefigrades = ''

            var prelimstat = '';
            var midstat = '';
            var prefistat = '';
            var finalstat = '';
            
            var fg = '';
            var fgremarks = '';

            var input_grade_class_midterm = 'input_grades'
            var input_grade_class_final = 'input_grades'
            var input_grade_class_prefi = 'input_grades'
            var input_grade_class_prelim = 'input_grades'
            
            var stud_grade = temp_grades.filter(x=>x.studid == b.studid && x.prospectusID == b.id)
           
            if(stud_grade.length > 0){

                  var grade_dates = temp_dates.filter(x=>x.headerid == stud_grade[0].id )

                  prelimgrades = stud_grade[0].prelemgrade != null ? stud_grade[0].prelemgrade : ''
                  prefigrades  = stud_grade[0].prefigrade != null ? stud_grade[0].prefigrade : ''
                  midgrades = stud_grade[0].midtermgrade != null ? stud_grade[0].midtermgrade : ''
                  finalgrades  = stud_grade[0].finalgrade != null ? stud_grade[0].finalgrade : ''

                  fg = stud_grade[0].fg != null ? stud_grade[0].fg : ''
                  fgremarks  = stud_grade[0].fgremarks != null ? stud_grade[0].fgremarks : ''

                  input_grade_class_midterm = stud_grade[0].midtermclass
                  input_grade_class_final = stud_grade[0].
                  
                  midstat = stud_grade[0].midtermstatus
                  finalstat = stud_grade[0].finalstatus
                  prefistat = stud_grade[0].prefistatus
                  prelimstat = stud_grade[0].preli

                  if(stud_grade[0].midtermstatus == 8){
                        midgrades = 'INC'
                  }else if(stud_grade[0].midtermstatus == 9){
                        midgrades = 'DROPPED'
                  }

                  if(stud_grade[0].finalstatus == 8){
                        finalgrades = 'INC'
                        fg = 'INC'
                        fgremarks = 'INC'
                  }else if(stud_grade[0].finalstatus == 9){
                        finalgrades = 'DROPPED'
                        fg = 'DROPPED'
                        fgremarks = 'DROPPED'
                  }

                  if(stud_grade[0].prefistatus == 8){
                        prefigrades = 'INC'
                  }else if(stud_grade[0].prefistatus == 9){
                        prefigrades = 'DROPPED'
                  }

                  if(stud_grade[0].prelemstatus == 8){
                        prelimgrades = 'INC'
                  }else if(stud_grade[0].prelemstatus == 9){
                        prelimgrades = 'DROPPED'
                  }
                  var input_grade_class_midterm = stud_grade[0].midtermclass
                  var input_grade_class_final = stud_grade[0].finalclass
                  var input_grade_class_prefi = stud_grade[0].preficlass
                  var input_grade_class_prelim = stud_grade[0].prelemclass
                  var input_grade_other = input_grade_class_final.replace('input_grades','');

                  if($('.filter_showdates').prop('checked')){

                        if($('input[name="filter_term"]:checked').val() == "all"){

                              prelimgrades = stud_grade[0].prelemdate
                              prefigrades = stud_grade[0].prefidate
                              midgrades = stud_grade[0].midtermdate
                              finalgrades = stud_grade[0].finaldate
                              fg = stud_grade[0].finaldate
                              fgremarks = stud_grade[0].finaldate
                        }else{

                              if($('input[name="filter_term"]:checked').val() == "prelim"){
                                    var datedetail = grade_dates.filter(x=>x.term == 'prelem')
                              }else if($('input[name="filter_term"]:checked').val() == "midterm"){
                                    var datedetail = grade_dates.filter(x=>x.term == 'midterm')
                              }else if($('input[name="filter_term"]:checked').val() == "prefi"){
                                    var datedetail = grade_dates.filter(x=>x.term == 'prefi')
                              }else if($('input[name="filter_term"]:checked').val() == "final"){
                                    var datedetail = grade_dates.filter(x=>x.term == 'final')
                              }
                              // if(datedetail.length > 1){
                              //       // datedetail = datedetail.filter(x=>x.pendstatdatetime == 0)

                              // }
                              input_grade_class_prelim = 'bg-success'
                              input_grade_class_midterm = 'bg-primary'
                              input_grade_class_prefi = 'bg-info'
                              input_grade_class_final = 'bg-warning'
                              input_grade_other = ''

                              if(datedetail.length == 0){
                                    prelimgrades = ''
                                    prefigrades = ''
                                    midgrades = ''
                                    finalgrades =''
                                    fg = ''
                                    fgremarks = ''
                              }else{
                                    var temp_index = datedetail.length  - 1;
                                    prelimgrades = datedetail[temp_index].subjstatdatetime
                                    midgrades = datedetail[temp_index].appstatdatetime
                                    prefigrades = datedetail[temp_index].poststatdatetime
                                    finalgrades = datedetail[temp_index].pendstatdatetime
                                    fg = datedetail[temp_index].pendcom != null ? datedetail[temp_index].pendcom : ''
                                    fgremarks = ''
                              }
                        }
                  }
            }
            
            var subject = b.subjCode+' - '+b.subjDesc
            subject = subject.length > 70 ? 
                              subject.substring(0, 50 - 3) + "..." : 
                              subject;
            
                              sectionid = b.sectionid
            
            var text = '<tr><td class="text-center align-middle">'+count+'</td><td class="text-center align-middle">'+temp_sid+'</td><td class=" align-middle">'+temp_studname+'</td><td class=" align-middle">'+subject+'</td>'

            
            if($('.filter_showdates').prop('checked')){

                        text += '<td  data-studid="'+b.studid+'" data-section="'+sectionid+'" class="'+input_grade_class_prelim+' text-center grade_td term_holder" data-term="1" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+prelimstat+'">'+prelimgrades+'</td>'

                        text += '<td  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_class_midterm+' text-center grade_td term_holder" data-term="2" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+midstat+'">'+midgrades+'</td>'

                        text += '<td  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_class_prefi+' text-center grade_td term_holder" data-term="3" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+prefistat+'">'+prefigrades+'</td>'

                        text += '<td  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_class_final+'  text-center grade_td term_holder" data-term="4" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+finalstat+'">'+finalgrades+'</td>'

            }else{
                  if(disprelim == 1){
                        text += '<td  data-studid="'+b.studid+'" data-section="'+sectionid+'" class="'+input_grade_class_prelim+' text-center grade_td term_holder" data-term="1" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+prelimstat+'">'+prelimgrades+'</td>'
                  }

                  if(dismidterm == 1){
                        text += '<td  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_class_midterm+' text-center grade_td term_holder" data-term="2" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+midstat+'">'+midgrades+'</td>'
                  }

                  if(disprefi == 1){
                        text += '<td  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_class_prefi+' text-center grade_td term_holder" data-term="3" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+prefistat+'">'+prefigrades+'</td>'
                  }

                  if(disfinal == 1){
                        text += '<td  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_class_final+'  text-center grade_td term_holder" data-term="4" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+finalstat+'">'+finalgrades+'</td>'
                  }
            }
                  

            
            

            
            text += '<th  data-studid="'+b.studid+'" data-section="'+sectionid+'" class="'+input_grade_other+' term_holder text-center" data-term="5" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+finalstat+'">'+fg+'</th>'

            text += '<th  data-studid="'+b.studid+'"  data-section="'+sectionid+'" class="'+input_grade_other+' term_holder text-center" data-term="6" data-pid="'+b.id+'"  data-course="'+temp_course+'" data-section="'+b.sectionid+'" data-stat="'+finalstat+'">'+fgremarks+'</th>'

            text += '</tr>'


            $('#subject_holder').append(text)
      }

      $(document).ready(function(){

            $("#filter_sy").empty();
            $("#filter_sy").append('<option value="">Select School Year</option>');
            $("#filter_sy").select2({
                  data: sy,
                  // allowClear: true,
                  placeholder: "Select School Year",
            })

            $("#filter_sem").empty();
            // $("#filter_sem").append('<option value="">Select Semester</option>');
            $("#filter_sem").select2({
                  data: sem,
                  // allowClear: true,
                  placeholder: "Select Semester",
            })

            $('#filter_sy').val(active_sy.id).change()
            $('#filter_sem').val(active_sem.id).change()

            getteachers()
            getstudents()
            inputperiodslist()
            getgradesetup()

            $(document).on('change','#filter_sy , #filter_sem',function(){
                  can_edit = false
                  $('#grade_pending').attr('disabled','disabled')
                  $('#grade_posting').attr('disabled','disabled')
                  $('#grade_approve').attr('disabled','disabled')
                  $('#grade_unposting').attr('disabled','disabled')
                  inputperiodslist()
                  getstudents()
                  getteachers()
                  getgradesetup()
            })
            

            $(document).on('change','#filter_showdates',function(){
                  if($(this).prop('checked') == true){
                        $('#term_holder').removeAttr('hidden')
                        
                        $('#gdth4').removeAttr('hidden')
                        $('#gdth5').removeAttr('hidden')
                        $('#gdth6').removeAttr('hidden')
                        $('#gdth7').removeAttr('hidden')
                        $('#gdth8').removeAttr('hidden')
                        $('#gdth9').removeAttr('hidden')
                  }else{
                        $('#term_holder').attr('hidden','hidden')
                        $('#gdth4').text('Prelim')
                        $('#gdth5').text('Midterm')
                        $('#gdth6').text('PreFi')
                        $('#gdth7').text('Final Term')
                        $('#gdth8').text('Final Grade')
                        $('#gdth9').text('Remarks')
                        display_columns()

                  }

                  if(all_subjinfo.length != 0){
                        displaysubjects(all_subjinfo)
                  }
            })

            $(document).on('change','input[name="filter_term"]',function(){

                  if($(this).val() == "all"){
                        $('#gdth4').text('Prelim')
                        $('#gdth5').text('Midterm')
                        $('#gdth6').text('PreFi')
                        $('#gdth7').text('Final Term')
                        $('#gdth8').text('Final Grade')
                        $('#gdth9').text('Remarks')
                  }else{
                        $('#gdth4').text('Submitted')
                        $('#gdth5').text('Approved')
                        $('#gdth6').text('Posted')
                        $('#gdth7').text('Pending')
                        $('#gdth8').text('Remarks')
                        $('#gdth9').text('')
                  }

                  displaysubjects(all_subjinfo)
                  
            })

            $(document).on('change','#filter_teacher',function(){

                 
                  if($(this).val() != null && $(this).val() != ""){
                        $('#grade_pending').removeAttr('disabled')
                        $('#grade_posting').removeAttr('disabled')
                        $('#grade_approve').removeAttr('disabled')
                        $('#grade_unposting').removeAttr('disabled')
                        $('#filter_student').val("").change()
                        $('#filter_subjects').removeAttr('disabled')
                        getstudents()
                  }else{
                        $('#filter_subjects').attr('disabled','disabled')
                        if( ( $('#filter_student').val() == null || $('#filter_student').val() == "" ) &&  ( $(this).val() == null || $(this).val() == "" )){
                              $('#grade_approve').attr('disabled','disabled')
                              $('#grade_unposting').attr('disabled','disabled')
                              getstudents()
                              return false
                        }

                        if($('#filter_student').val() != null && $('#filter_student').val() != ""){
                              getstudents()
                        }
                  }
            })

            $(document).on('click','#print_grades_to_modal',function(){
                  $('#registrar_holder_modal').modal()
            })


            $(document).on('click','#print_grades',function(){
                  print_grades()
            })

            function print_grades() {
                  window.open('/college/grades/summary/print/pdf?semid='+$('#filter_sem').val()+'&syid='+$('#filter_sy').val()+'&studid='+$('#filter_student').val()+'&registrar='+$('#printable_registrar').val(), '_blank');
            }


            $(document).on('change','#filter_student',function(){
                  $('#print_grades_to_modal').addClass('disabled','disabled')
                  $('#print_grades_to_modal').attr('href','#')
                  $('#print_grades_to_modal').removeAttr('target')
                  
                  if($(this).val() != null && $(this).val() != ""){
                        $('#grade_pending').removeAttr('disabled')
                        $('#grade_posting').removeAttr('disabled')
                        $('#grade_approve').removeAttr('disabled')
                        $('#grade_unposting').removeAttr('disabled')
                        $('#print_grades_to_modal').removeClass('disabled')
                        // $('#print_grade').attr('href','/college/grades/summary/print/pdf?semid='+$('#filter_sem').val()+'&syid='+$('#filter_sy').val()+'&studid='+$(this).val())
                        // $('#print_grade').attr('target','_blank')

                        if($('#filter_teacher').val() == null || $('#filter_teacher').val() == ""){
                              getsubjects()
                        }
                        else if(all_subjinfo.length != 0){
                              displaysubjects(all_subjinfo)
                              // display_for_posting(all_subjinfo)
                        }else{
                              getsubjects()
                        }
                  }else{

                        if( ( $('#filter_teacher').val() == null || $('#filter_teacher').val() == "" ) &&  ( $(this).val() == null || $(this).val() == "" )){
                              $('#grade_pending').attr('disabled','disabled')
                              $('#grade_posting').attr('disabled','disabled')
                              $('#grade_approve').attr('disabled','disabled')
                              $('#grade_unposting').attr('disabled','disabled')
                              getstudents()
                        }
                        else if($('#filter_teacher').val() == null || $('#filter_teacher').val() == ""){
                              getsubjects()
                        }else{
                              if(all_subjinfo.length != 0){
                                    displaysubjects(all_subjinfo)
                                    // display_for_posting(all_subjinfo)
                              }
                        }
                  }
            })

      })



</script>

{{-- Grades Input --}}
<script>

      var school = @json($schoolinfo);

      var isSaved = false;
      var isvalidHPS = true;
      var hps = []
      var currentIndex 
      var can_edit = false
      var can_edit_status = ''

      $(document).on('click','.input_grades',function(){
            
            can_edit = true

            if(gradepriv[0].canedit == 0){
                  return false
            }

            if(!can_edit){
                  Toast.fire({
                        type: 'warning',
                        title: can_edit_status
                  })
            }

           

            if(currentIndex != undefined){
                  if(!check_higherthan65()){ return false}
                  if(isvalidHPS){
                        if(can_edit){
                              string = $(this).text();
                              currentIndex = this;
                              $('#start').length > 0 ? dotheneedful(this) : false
                              $('td').removeAttr('style');
                              $('#start').removeAttr('id')
                              $(this).attr('id','start')
                              $(currentIndex).removeClass('bg-danger')
                              $(currentIndex).removeClass('bg-warning')
                              var start = document.getElementById('start');
                                                start.focus();
                                                start.style.backgroundColor = 'green';
                                                start.style.color = 'white';
                        }
                  }
            }
            else{
                  if(can_edit){
                        string = $(this).text();
                        currentIndex = this;
                        $('#start').length > 0 ? dotheneedful(this) : false
                        $('td').removeAttr('style');
                        $('#start').removeAttr('id')
                        $(this).attr('id','start')
                        $(currentIndex).removeClass('bg-danger')
                        $(currentIndex).removeClass('bg-warning')
                        var start = document.getElementById('start');
                                          start.focus();
                                          start.style.backgroundColor = 'green';
                                          start.style.color = 'white';

                  }
            }
            $('.updated').css("background-color",'#0080005e')
      })


      function dotheneedful(sibling) {
            if (sibling != null) {
                  currentIndex = sibling
                  $(sibling).removeClass('bg-danger')
                  $(sibling).removeClass('bg-warning')
                  if($(start).text() == 'DROPPED'){
                        $(start).addClass('bg-danger')
                  }else if($(start).text() == 'INC' || $(start).attr('data-status') == 3){
                        $(start).addClass('bg-warning')
                  }else if($(start).attr('data-stat') == 3){
                        if(!$(start).hasClass('updated')){
                              $(start).addClass('bg-warning')
                        }
                  }else{
                        start.style.backgroundColor = '';
                  }
                
                  start.style.color = '';
                  sibling.focus();
                  $('.updated').css("background-color",'#0080005e')
                  sibling.style.backgroundColor = 'green';
                  sibling.style.color = 'white';
                  start = sibling;
                  $('#message').empty();
                  string = $(currentIndex)[0].innerText
                  
            }
      }


      document.onkeydown = checkKey;

      function check_higherthan65(){
            $('#save_grades').removeAttr('disabled')
            if(gradesetup.isPointScaled == 0){
                  
                  if(currentIndex.innerText < 65 && currentIndex.innerText != ''){
                        Toast.fire({
                              type: 'info',
                              title: 'Grades should be higher than 65!'
                        })
                        $('#save_grades').attr('disabled','disabled')
                        return false;
                  }else if(currentIndex.innerText > 100 && currentIndex.innerText != ''){
                        Toast.fire({
                              type: 'info',
                              title: 'Grades should not be higher than 100!'
                        })
                        $('#save_grades').attr('disabled','disabled')
                        return false;
                  }else{
                        return true;
                  }
            }else{
                  return true
            }
      }

      function checkKey(e) {

            e = e || window.event;

            if (e.keyCode == '38' && currentIndex != undefined)  {

                  if(!check_higherthan65()){ return false}

                  $('.updated').css("background-color",'#0080005e')
                  var idx = start.cellIndex;
                  var nextrow = start.parentElement.previousElementSibling;
                  if(nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')){
                        return false;
                  }
                  
                  $('#curText').text(string)
                  var sibling = nextrow.cells[idx];
                  if(sibling == undefined){
                        return false;
                  }
                  string = sibling.innerText;
                  dotheneedful(sibling);
            } else if (e.keyCode == '40' && currentIndex != undefined) {
                  
                  if(!check_higherthan65()){ return false}

                  var idx = start.cellIndex;
                  var nextrow = start.parentElement.nextElementSibling;
                  if(nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')){
                        return false;
                  }
                  $('#curText').text(string)
                  var sibling = nextrow.cells[idx];
                  if(sibling == undefined){
                        return false;
                  }
                  string = sibling.innerText;
                  dotheneedful(sibling);
            } else if (e.keyCode == '37' && currentIndex != undefined) {
                  if(!check_higherthan65()){ return false}
                
                  var sibling = start.previousElementSibling;
                  if(sibling == null || !$(sibling).hasClass('input_grades')){
                        return false;
                  }
                  else if($(sibling)[0].nodeName != "TD" ){
                        return false;
                  }
                  $('#curText').text(string)
                  if($(sibling).cellIndex != 0){
                        string = sibling.innerText;
                        dotheneedful(sibling);
                  }

            } else if (e.keyCode == '39' && currentIndex != undefined) {
                  if(!check_higherthan65()){ return false}
                  var sibling = start.nextElementSibling;
                  if(sibling == null || !$(sibling).hasClass('input_grades')){
                        return false;
                  }
                  else if($(sibling)[0].nodeName != "TD" ){
                        return false;
                  }
                  $('#curText').text(string)
                  if($(sibling)[0].cellIndex != 0){
                        string = sibling.innerText;
                        dotheneedful(sibling);
                  }
            }
            else if (e.keyCode == '73' && currentIndex != undefined) {
                  $(currentIndex).text("INC")
                  $(currentIndex).addClass('updated')
                  $('.save_grades').removeAttr('disabled')
                  $('#grade_submit').attr('disabled','disabled')
            }
            else if (e.keyCode == '68' && currentIndex != undefined) {
                  $(currentIndex).text("DROPPED")
                  $(currentIndex).addClass('updated')
                  $('.save_grades').removeAttr('disabled')
                  $('#grade_submit').attr('disabled','disabled')
            }
            else if( e.key == "Backspace" && currentIndex != undefined){
                  string = currentIndex.innerText
                  string = string.slice(0 , -1);

                  if(string.length == 0){
                        string = '';
                        currentIndex.innerText = string
                  }else{
                        currentIndex.innerText = parseInt(string)
                        inputIndex = currentIndex
                  }

                  if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                        string = ''
                  }

                  $(currentIndex).addClass('updated')
                  $('#save_grades').removeAttr('disabled')
                  $('#grade_submit').attr('disabled','disabled')

                  $(currentIndex).text(string)
                  $('#curText').text(string)

                  isstudtext = '[data-pid="'+$(currentIndex).attr('data-pid')+'"]'

                  

                  var temp_studid = $(currentIndex).attr('data-studid')
                  var prelim =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="1"]'+isstudtext).text());
                  var midterm =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="2"]'+isstudtext).text());
                  var prefi = parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="3"]'+isstudtext).text());
                  var final =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="4"]'+isstudtext).text());

                  if(gradesetup.f_frontend != '' || gradesetup.f_frontend != null){

                        var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)

                        if(!isNaN(fg)){
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).text(fg)
                              // if(fg >= gradesetup.passingRate){
                              //       $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('PASSED')
                              // }else{
                              //       $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('FAILED')
                              // }


                              if(gradesetup.isPointScaled == 0){
                                    if(fg >= gradesetup.passingRate){
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('PASSED')
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('FAILED')
                                    }

                              }else{
                                    if(fg <= gradesetup.passingRate){
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('PASSED')
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('FAILED')
                                    }
                              }

                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).addClass('updated')
                              $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).addClass('updated')
                        }else{
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).text(null)
                              $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text(null)
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).addClass('updated')
                              $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).addClass('updated')
                        }
                  }

                  $('#grade_submit').attr('disabled','disabled')
                  $('.save_grades').removeAttr('disabled')
            }
            else if ( ( ( e.key >= 0 && e.key <= 9 ) || e.key == '.' ) && currentIndex != undefined) {

                  if(e.key == '.'){
                        if(gradesetup.decimalPoint == 0){
                              return false
                        }
                        var checkForPoint = string.includes('.')
                        if(checkForPoint){
                              return false
                        }
                  }

                  var check_string = string + e.key;
                  var decimalcount = count_decimal(check_string)

                  
                  
                  if(decimalcount <= gradesetup.decimalPoint){
                        string += e.key;
                  }else{
                        string = string;
                  }


                  if(gradesetup.isPointScaled == 0){
                        if(check_string > 100){
                              string = 100 
                        }
                  }else{
                        if(check_string > 5){
                              return false
                        }
                  }

                  
                  if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                        string = ''
                  }
                  
                  $(currentIndex).addClass('updated')
                  $('#save_grades').removeAttr('disabled')
                  $('#grade_submit').attr('disabled','disabled')

                  $(currentIndex).text(string)
                  $('#curText').text(string)

                  var isstudtext = ''
                  isstudtext = '[data-pid="'+$(currentIndex).attr('data-pid')+'"]'

                  var temp_studid = $(currentIndex).attr('data-studid')
                  
                  var prelim =  parseFloat($('td[data-studid="'+temp_studid+'"][data-term="1"]'+isstudtext).text());
                  var midterm =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="2"]'+isstudtext).text());
                  var prefi = parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="3"]'+isstudtext).text());
                  var final =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="4"]'+isstudtext).text());

                  if(gradesetup.f_frontend != '' || gradesetup.f_frontend != null){

                        var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)

                        if(!isNaN(fg)){
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).text(fg)
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).addClass('updated')
                              $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).addClass('updated')

                              if(gradesetup.isPointScaled == 0){
                                    if(fg >= gradesetup.passingRate){
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('PASSED')
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('FAILED')
                                    }

                              }else{
                                    if(fg <= gradesetup.passingRate){
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('PASSED')
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('FAILED')
                                    }
                              }
                              
                              $('.grade_td[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).addClass('updated')
                        }
                        else{
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).text('')
                              $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).text('')
                              $('th[data-studid="'+temp_studid+'"][data-term="5"]'+isstudtext).addClass('updated')
                              $('th[data-studid="'+temp_studid+'"][data-term="6"]'+isstudtext).addClass('updated')

                        }
                  }

                  $('#grade_submit').attr('disabled','disabled')
                  $('.save_grades').removeAttr('disabled')

            }
      }

      function count_decimal(num) {
            const converted = num.toString();
            if (converted.includes('.')) {
            return converted.split('.')[1].length;
            };
            return 0;
      }

      $(document).on('click','.save_grades',function() {

            $('.save_grades').text('Saving Grades...')
            $('.save_grades').removeClass('btn-primary')
            $('.save_grades').addClass('btn-secondary')
            $('.save_grades').attr('disabled','disabled')

            if( $('.updated[data-term="1"]').length == 0){
                  save_midterm()
            }

            $('.updated[data-term="1"]').each(function(a,b){
                  var studid = $(this).attr('data-studid')
                  var term = $(this).attr('data-term')
                  var courseid = $(this).attr('data-course')
                  var sectionid = $(this).attr('data-section')
                  var pid = $(this).attr('data-pid')
                  var termgrade = $(this).text()
                  var td = $(this)
                  $.ajax({
                        type:'POST',
                        url: '/college/teacher/student/grades/save',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              term:"prelemgrade",
                              sectionid:sectionid,
                              termgrade:termgrade,
                              studid:studid,
                              courseid:courseid,
                              pid:pid,
                        },
                        success:function(data) {
                              $(td).removeClass('updated')
                              if($(td).attr('data-stat') == 3){
                                    $(td).addClass('bg-warning')
                              }else{
                                    $(td).css("background-color",'white')
                                    $(td).css("color",'black')
                              }
                              if($('.updated[data-term="1"]').length == 0){
                                          save_midterm()
                              }
                        },
                  })
            })


      })


      function save_midterm(){
            if( $('.updated[data-term="2"]').length == 0){
                  save_prefi()
            }
            $('.updated[data-term="2"]').each(function(a,b){
                  var studid = $(this).attr('data-studid')
                  // var term = $(this).attr('data-term')
                  var courseid = $(this).attr('data-course')
                  var sectionid = $(this).attr('data-section')
                  var pid = $(this).attr('data-pid')
                  var termgrade = $(this).text()
                  var td = $(this)
                  $.ajax({
                        type:'POST',
                        url: '/college/teacher/student/grades/save',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              term:"midtermgrade",
                              sectionid:sectionid,
                              termgrade:termgrade,
                              studid:studid,
                              courseid:courseid,
                              pid:pid,
                        },
                        success:function(data) {
                              $(td).removeClass('updated')
                              if($(td).attr('data-stat') == 3){
                                    $(td).addClass('bg-warning')
                              }else{
                                    $(td).css("background-color",'white')
                                    $(td).css("color",'black')
                              }
                              if($('.updated[data-term="2"]').length == 0){
                                    save_prefi()
                              }
                        }
                  })
            })

      }

      function save_prefi(){
            if( $('.updated[data-term="3"]').length == 0){
                  save_final()
            }
            $('.updated[data-term="3"]').each(function(a,b){
                  var studid = $(this).attr('data-studid')
                  // var term = $(this).attr('data-term')
                  var courseid = $(this).attr('data-course')
                  var sectionid = $(this).attr('data-section')
                  var pid = $(this).attr('data-pid')
                  var termgrade = $(this).text()
                  var td = $(this)
                  $.ajax({
                        type:'POST',
                        url: '/college/teacher/student/grades/save',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              term:"prefigrade",
                              sectionid:sectionid,
                              termgrade:termgrade,
                              studid:studid,
                              courseid:courseid,
                              pid:pid,
                        },
                        success:function(data) {
                              $(td).removeClass('updated')
                              if($(td).attr('data-stat') == 3){
                                    $(td).addClass('bg-warning')
                              }else{
                                    $(td).css("background-color",'white')
                                    $(td).css("color",'black')
                              }
                              if($('.updated[data-term="3"]').length == 0){
                                    save_final()
                              }
                        }
                  })
            })

      }

      function save_final(){
            if( $('.updated[data-term="4"]').length == 0){
                  save_fg()
            }
            $('.updated[data-term="4"]').each(function(a,b){
                  var studid = $(this).attr('data-studid')
                  // var term = $(this).attr('data-term')
                  var courseid = $(this).attr('data-course')
                  var sectionid = $(this).attr('data-section')
                  var pid = $(this).attr('data-pid')
                  var termgrade = $(this).text()
                  var td = $(this)
                  $.ajax({
                        type:'POST',
                        url: '/college/teacher/student/grades/save',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              term:"finalgrade",
                              sectionid:sectionid,
                              termgrade:termgrade,
                              studid:studid,
                              courseid:courseid,
                              pid:pid,
                        },
                        success:function(data) {
                              $(td).removeClass('updated')
                              if($(td).attr('data-stat') == 3){
                                    $(td).addClass('bg-warning')
                              }else{
                                    $(td).css("background-color",'white')
                                    $(td).css("color",'black')
                              }
                              if($('.updated[data-term="4"]').length == 0){
                                    save_final()
                              }
                        }
                  })
            })
      }


      function save_fg(){
            if( $('.updated[data-term="5"]').length == 0){
                  save_fgremarks()
            }
            $('.updated[data-term="5"]').each(function(a,b){
                  var studid = $(this).attr('data-studid')
                  // var term = $(this).attr('data-term')
                  var courseid = $(this).attr('data-course')
                  var sectionid = $(this).attr('data-section')
                  var pid = $(this).attr('data-pid')
                  var termgrade = $(this).text()
                  var td = $(this)
                  $.ajax({
                        type:'POST',
                        url: '/college/teacher/student/grades/save',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              term:"fg",
                              sectionid:sectionid,
                              termgrade:termgrade,
                              studid:studid,
                              courseid:courseid,
                              pid:pid,
                        },
                        success:function(data) {
                              $(td).removeClass('updated')
                              if($(td).attr('data-stat') == 3){
                                    $(td).addClass('bg-warning')
                              }else{
                                    $(td).css("background-color",'white')
                                    $(td).css("color",'black')
                              }
                              if($('.updated[data-term="5"]').length == 0){
                                    save_fg()
                              }
                        }
                  })
            })

      }

      function save_fgremarks(){
            if( $('.updated[data-term="6"]').length == 0){
                  Toast.fire({
                        type: 'success',
                        title: 'Saved Successfully!'
                  })
                  $('.save_grades').attr('disabled','disabled')
                  $('.save_grades').removeClass('btn-secondary')
                  $('.save_grades').addClass('btn-primary')
                  $('.save_grades').text('Save Grades')
                  $('.grade_submit').removeAttr('disabled')
            }
            $('.updated[data-term="6"]').each(function(a,b){
                  var studid = $(this).attr('data-studid')
                  // var term = $(this).attr('data-term')
                  var courseid = $(this).attr('data-course')
                  var sectionid = $(this).attr('data-section')
                  var pid = $(this).attr('data-pid')
                  var termgrade = $(this).text()
                  var td = $(this)
                  $.ajax({
                        type:'POST',
                        url: '/college/teacher/student/grades/save',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_sem').val(),
                              term:"fgremarks",
                              sectionid:sectionid,
                              termgrade:termgrade,
                              studid:studid,
                              courseid:courseid,
                              pid:pid,
                        },
                        success:function(data) {
                              $(td).removeClass('updated')
                              if($(td).attr('data-stat') == 3){
                                    $(td).addClass('bg-warning')
                              }else{
                                    $(td).css("background-color",'white')
                                    $(td).css("color",'black')
                              }
                              if($('.updated[data-term="finalgrade"]').length == 0){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Saved Successfully!'
                                    })
                                    $('.save_grades').attr('disabled','disabled')
                                    $('.save_grades').removeClass('btn-secondary')
                                    $('.save_grades').addClass('btn-primary')
                                    $('.save_grades').text('Save Grades')
                                    $('.grade_submit').removeAttr('disabled')
                              }
                        }
                  })
            })

      }


</script>


@endsection


