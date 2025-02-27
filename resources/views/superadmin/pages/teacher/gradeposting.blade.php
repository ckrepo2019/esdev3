@php
      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      
      if($check_refid->refid != 0){
            if($check_refid->refid == 22){
                  $extend = 'principalcoor.layouts.app2';
            }else{
                  if(isset($check_refid->refid)){
                        if($check_refid->refid == 27){
                              $extend = 'academiccoor.layouts.app2';
                        }
                  }else{
                        $extend = 'general.defaultportal.layouts.app';
                  }
            }
      }else{
            if(Session::get('currentPortal') == 17){
                  $extend = 'superadmin.layouts.app2';
            }else if(Session::get('currentPortal')  == 1){
                  $extend = 'teacher.layouts.app';
            }
            else if(Session::get('currentPortal')  == 2){
                  $extend = 'principalsportal.layouts.app2';
            }else{
                  if(isset($check_refid->refid)){
                        if($check_refid->refid == 27){
                              $extend = 'academiccoor.layouts.app2';
                        }
                  }else{
                        $extend = 'general.defaultportal.layouts.app';
                  }
            }
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

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Grade Status</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grade Status</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->get(); 
      $temp_teacherid = 0;
      if(auth()->user()->type != 17){
            $temp_teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first();
            if(isset($temp_teacherid)){
                  $temp_teacherid = $temp_teacherid->id;
            }
      }
   
@endphp

<div class="modal fade" id="modal_4" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header bg-primary p-1">
              </div>
              <div class="modal-body">
                 <div class="row">
                        <div class="col-md-3">
                              <div class="row" style=" font-size:11px !important">
                                    <div class="col-md-12 form-group">
                                          <label for="">Status</label>
                                          <select name="" id="filter_status_4" class="form-control form-control-sm">
                                                <option value="" selected>Select Status</option>
                                                <option value="NOT SUBMITTED">Not Submitted</option>
                                                <option value="SUBMITTED">Submitted</option>
                                                <option value="COOR APPROVED">Coor. Approved</option>
                                                <option value="APPROVED">Approved</option>
                                                <option value="POSTED">Posted</option>
                                                <option value="PENDING">Pending</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group" style="font-size:11px !important">
                                          <label for="">Quarter</label>
                                          <select name="" id="filter_quarter_4" class="form-control form-control-sm">
                                                <option value="" selected>Select Quarter</option>
                                                <option value="1">1st Quarter</option>
                                                <option value="2">2nd Quarter</option>
                                                <option value="3">3rd Quarter</option>
                                                <option value="4">4th Quarter</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="filter_button_4" style=" font-size:11px !important"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                    <div class="col-md-6">
                                          <button class="btn btn-danger btn-sm float-right" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-9">
                             <div class="row">
                                   <div class="col-md-12"  style="font-size:11px !important">
                                          <table class="table table-sm table-bordered table-striped" id="datatable_4" width="100%">
                                                <thead>
                                                      <tr>
                                                            <th width="40%">Student</th>
                                                            <th width="60%">Section</th>
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
</div>

<div class="modal fade" id="modal_5" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header bg-primary p-1">
              </div>
              <div class="modal-body">
                  <div class="row">
                        <div class="col-md-12">
                              <h6 id="student_name"></h6>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <div class="row">
                                    <div class="col-md-12"  style="font-size:11px !important">
                                          <table class="table table-sm table-bordered table-striped" id="datatable_5" width="100%">
                                                <thead>
                                                      <tr>
                                                            <th width="20%">Student</th>
                                                            <th width="20%">Section</th>
                                                            <th width="35%">Subject</th>
                                                            <th width="5%">QG</th>
                                                            <th width="5%">Status</th>
                                                            <th width="15%">Action</th>
                                                      </tr>
                                                </thead>
                                          </table>
                                    </div>
                              </div>
                              <div class="row" >
                                    <div class="col-md-12" >
                                          <button class="btn btn-danger btn-sm" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
                                    </div>
                              </div>
                        </div>
                  </div>
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header bg-primary p-1">
              </div>
              <div class="modal-body">
                 <div class="row">
                        <div class="col-md-3">
                              <div class="row" style=" font-size:11px !important">
                                    <div class="col-md-12 form-group">
                                          <label for="">Status</label>
                                          <select name="" id="filter_status_3" class="form-control form-control-sm">
                                                <option value="" selected>Select Status</option>
                                                <option value="NOT SUBMITTED">Not Submitted</option>
                                                <option value="SUBMITTED">Submitted</option>
                                                <option value="COOR APPROVED">Coor. Approved</option>
                                                <option value="APPROVED">Approved</option>
                                                <option value="POSTED">Posted</option>
                                                <option value="PENDING">Pending</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group" style="font-size:11px !important">
                                          <label for="">Quarter</label>
                                          <select name="" id="filter_quarter_3" class="form-control form-control-sm">
                                                <option value="" selected>Select Quarter</option>
                                                <option value="1">1st Quarter</option>
                                                <option value="2">2nd Quarter</option>
                                                <option value="3">3rd Quarter</option>
                                                <option value="4">4th Quarter</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="filter_button_3" style=" font-size:11px !important"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                    <div class="col-md-6">
                                          <button class="btn btn-danger btn-sm float-right" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-9">
                             <div class="row">
                                   <div class="col-md-12"  style="font-size:11px !important">
                                          <table class="table table-sm table-bordered table-striped" id="datatable_3" width="100%">
                                                <thead>
                                                      <tr>
                                                            <th width="20%">Section</th>
                                                            <th width="60%">Subject</th>
                                                            <th width="20%" class="text-center">Teacher</th>
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
</div>

<div class="modal fade" id="ecr_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header bg-primary p-1">
              </div>
              <div class="modal-body">
                 <div class="row">
                        <div class="col-md-3">
                              <div class="row" style=" font-size:11px !important">
                                    <div class="col-md-5">
                                          <strong><i class="fas fa-book mr-1"></i> Grade Level</strong>
                                          <p class="text-muted" id="label_gradelevel">
                                                --
                                           </p>
                                    </div>
                                    <div class="col-md-7">
                                          <strong><i class="fas fa-book mr-1"></i> Section</strong>
                                          <p class="text-muted" id="label_section">
                                                --
                                           </p>
                                    </div>
                              </div>
                              <div class="row" style=" font-size:11px !important">
                                    <div class="col-md-12">
                                          <strong><i class="fas fa-book mr-1"></i> Subject</strong>
                                          <p class="text-muted mb-0" id="label_subject">
                                                --
                                          </p>
                                          <p class="text-danger" >
                                                <i id="label_subjectcode"> -- </i>
                                          </p>
                                    </div>
                              </div>
                              <div class="row" style=" font-size:11px !important">
                                    <div class="col-md-12">
                                          <strong><i class="fas fa-book mr-1"></i> Teacher</strong>
                                          <p class="text-muted mb-0" id="label_teacher">
                                                --
                                          </p>
                                          <p class="text-danger mb-0" >
                                                <i id="label_tid"> -- </i>
                                          </p>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Quarter</label>
                                          <select name="" id="filter_quarter" class="form-control form-control-sm">
                                                <option value="" selected>Select Quarter</option>
                                                <option value="1">1st Quarter</option>
                                                <option value="2">2nd Quarter</option>
                                                <option value="3">3rd Quarter</option>
                                                <option value="4">4th Quarter</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6"><button class="btn btn-primary btn-sm" id="ecr_filter" style=" font-size:11px !important"><i class="fas fa-filter"></i> Filter</button></div>
                                    <div class="col-md-6"><button class="btn btn-success btn-sm btn-block" id="ecr_status" disabled style=" font-size:11px !important"></button></div>
                              </div>
                              <hr>
                              <div class="row mt-3" style=" font-size:11px !important">
                                    <div class="col-md-12">
                                          <strong><i class="fas fa-book mr-1"></i> Last date Uploaded</strong>
                                          <p class="text-muted" id="label_dateuploaded">
                                                --
                                           </p>
                                    </div>
                                    <div class="col-md-5">
                                          <strong><i class="fas fa-book mr-1"></i> Grade Status</strong>
                                          <p class="text-muted" id="label_status">
                                                --
                                          </p>
                                    </div>
                                    <div class="col-md-7">
                                          <strong><i class="fas fa-book mr-1"></i> Grade Submitted</strong>
                                          <p class="text-muted" id="label_datesubmitted">
                                                --
                                          </p>
                                    </div>
                              </div>
                              <hr>
                              <div class="row" >
                                    <div class="col-md-12" >
                                          <button class="btn btn-danger btn-sm" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-9">
                              <div class="row">
                                    <div class="col-md-4">
                                          <h5>Class Record</h5>
                                    </div> 
                                    <div class="col-md-2 " >
                                          <button class="btn btn-warning btn-sm btn-block" style=" font-size:11px !important" id="pending_grade" disabled>Pending</button>
                                    </div>
                                    <div class="col-md-2">
                                          <button class="btn btn-primary btn-sm btn-block" style=" font-size:11px !important" id="approve_grade" disabled>Approve</button>
                                    </div>
                                    <div class="col-md-2 for_p" hidden>
                                          <button class="btn btn-info btn-sm btn-block" style=" font-size:11px !important" id="post_grade" disabled>Post</button>
                                    </div>
                                   
                                    <div class="col-md-2 for_p" hidden>
                                          <button class="btn btn-danger btn-sm btn-block" style=" font-size:11px !important" id="unpost_grade" disabled>Unpost</button>
                                    </div>
                              </div>
                             
                              <div class="row mt-2" >
                                    <div class="col-md-12" id="ecr_view_holder" style="font-size:11px !important">
                                         <table class="table table-sm table-bordered">
                                               <tr>
                                                     <th class="text-center">Select Quarter</th>
                                               </tr>
                                         </table>
                                    </div>
                              </div>
                        </div>
                 </div>
              </div>
              
          </div>
      </div>
  </div>



    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-5">
                        <div class="info-box shadow-lg">
                          <span class="info-box-icon bg-primary"><i class="fas fa-filter"></i></span>
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-5  form-group">
                                          <label for="">School Year</label>
                                          <select class="form-control select2  form-control-sm" id="filter_sy">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-5  form-group">
                                          <label for="">Semester</label>
                                          <select class="form-control select2  form-control-sm" id="filter_sem">
                                                @foreach ($semester as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->semester}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-5">
                        <div class="card shadow">
                              <div class="card-header border-0 pb-0">
                                    <h3 class="card-title">ECR List</h3>
                              </div>
                              <div class="card-body  pt-2">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:11px !important">
                                                <table class="table table-sm table-bordered table-striped" id="grade_status" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="28%">Status</th>
                                                                  <th width="18%" class="text-center">Q1</th>
                                                                  <th width="18%" class="text-center">Q2</th>
                                                                  <th width="18%" class="text-center">Q3</th>
                                                                  <th width="18%" class="text-center">Q4</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-7">
                        <div class="card shadow">
                              <div class="card-header border-0 pb-0">
                                    <h3 class="card-title">Student List</h3>
                              </div>
                              <div class="card-body pt-2">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:11px !important">
                                                <table class="table table-sm table-bordered table-striped" id="student_grade_status" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="28%">Status</th>
                                                                  <th width="18%" class="text-center">Q1</th>
                                                                  <th width="18%" class="text-center">Q2</th>
                                                                  <th width="18%" class="text-center">Q3</th>
                                                                  <th width="18%" class="text-center">Q4</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                       
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h3 class="mb-0 card-title">Grades Information</h3>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12"  style="font-size:11px !important">
                                                <table class="table table-sm table-bordered table-striped" id="subjectplot_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="17%">Section</th>
                                                                  <th width="20%">Subject</th>
                                                                  <th width="15%" class="text-center">Teacher</th>
                                                                  <th width="12%" class="text-center">Quarter 1</th>
                                                                  <th width="12%" class="text-center">Quarter 2</th>
                                                                  <th width="12%" class="text-center">Quarter 3</th>
                                                                  <th width="12%" class="text-center">Quarter 4</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="schedule">
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
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script>
            $(document).ready(function(){
                  var refid = @json($check_refid->refid);
                  if(refid != 22){
                        $('.for_p').removeAttr('hidden')
                  }else{
                        $('.for_p').remove()
                  }
            })
      </script>
  

      <script>
            $(document).ready(function(){

                  var table = $("#datatable_3").DataTable()
                  table.state.clear();
                  table.destroy();

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var all_grades = [];
                  load_grades()
                  
                  $(document).on('click','#button_filter',function(){
                        load_grades()
                  })

                  function load_grades(){
                        $.ajax({
                              type:'GET',
                              url: '/grades/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No grades Found!'
                                          })
                                          all_grades = []
                                          load_gradesetup_datatable()
                                    }else{
                                          all_grades = data
                                          load_gradesetup_datatable()
                                    }
                              }
                        })
                       
                  }

                  

                  var grades_info

                  $(document).on('click','.select_all',function(){
                        if($(this).prop('checked') == false){
                              $('.exclude').each(function(){
                                    if($(this).attr('disabled') == undefined){
                                          $(this).prop('checked',false)
                                    }
                              })
                        }else{
                              $('.exclude').each(function(){
                                    if($(this).attr('disabled') == undefined){
                                          $(this).prop('checked',true)
                                    }
                              })
                        }
                  })

                  $(document).on('click','#approve_grade',function(){

                        var excluded = []

                        $('.exclude').each(function(){
                              if($(this).prop('checked') == false && $(this).attr('data-studid') != undefined){
                                    excluded.push($(this).attr('data-studid'))
                              }
                        })


                        $.ajax({
                              url: '/ecr/approve',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    quarter:$('#filter_quarter').val(),
                                    levelid:grades_info[0].levelid,
                                    subjid:grades_info[0].subjid,
                                    sectionid:grades_info[0].sectionid,
                                    exclude:excluded
                              },
                              success:function(data) {
                                    var quarter = $('#filter_quarter').val()
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          var is_approved = true;

                                          $('.exclude').each(function(){
                                                if($(this).prop('checked') == true){
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').text('Approved')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').removeClass('badge-success')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').addClass('badge-primary')
                                                   
                                             
                                                }else{
                                                      is_approved = false
                                                }
                                          })

                                          if(is_approved){

                                                var selected_index = all_grades.findIndex(x=>x.sectionid==grades_info[0].sectionid && x.subjid == grades_info[0].subjid && x.levelid == grades_info[0].levelid)

                                                all_grades[selected_index].grades[quarter-1].stattext = 'APPROVED'
                                                all_grades[selected_index].grades[quarter-1].status = 2

                                                load_gradesetup_datatable()

                                                $('#approve_grade').attr('disabled','disabled')
                                                $('#label_status').text('Approved')

                                                var temp_stattext = $('#filter_status_3').val()
                                                var temp_quarter = $('#filter_quarter_3').val()

                                                filtered_all_status = all_status.filter(x=>x.stattext == temp_stattext && x.quarter == temp_quarter)
                                                load_datatable_3()

                                          }

                                          

                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })

                  $(document).on('click','#post_grade',function(){

                        var excluded = []

                        $('.exclude').each(function(){
                              if($(this).prop('checked') == false && $(this).attr('data-studid') != undefined){
                                    excluded.push($(this).attr('data-studid'))
                              }
                        })

                        $.ajax({
                              url: '/ecr/post',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    quarter:$('#filter_quarter').val(),
                                    levelid:grades_info[0].levelid,
                                    subjid:grades_info[0].subjid,
                                    sectionid:grades_info[0].sectionid,
                                    exclude:excluded
                              },
                              success:function(data) {
                                    var quarter = $('#filter_quarter').val()
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          var is_posted = true

                                          $('.exclude').each(function(){
                                                if($(this).prop('checked') == true){
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').text('Posted')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').removeClass('badge-success')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').removeClass('badge-primary')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').addClass('badge-info')
                                                }else{
                                                      is_posted = false
                                                }
                                          })

                                          if(is_posted){

                                                $('#pending_grade').attr('disabled','disabled')
                                                $('#post_grade').attr('disabled','disabled')
                                                $('#approve_grade').attr('disabled','disabled')
                                                $('#unpost_grade').removeAttr('disabled')
                                                $('#label_status').text('Posted')

                                                $('.student_view').attr('hidden','hidden')

                                                var selected_index = all_grades.findIndex(x=>x.sectionid==grades_info[0].sectionid && x.subjid == grades_info[0].subjid && x.levelid == grades_info[0].levelid)

                                                all_grades[selected_index].grades[quarter-1].stattext = 'POSTED'
                                                all_grades[selected_index].grades[quarter-1].status = 4

                                                load_gradesetup_datatable()

                                                var temp_stattext = $('#filter_status_3').val()
                                                var temp_quarter = $('#filter_quarter_3').val()
                                                filtered_all_status = all_status.filter(x=>x.stattext == temp_stattext && x.quarter == temp_quarter)
                                                load_datatable_3()
                                          }

                                          

                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })

                  $(document).on('click','#pending_grade',function(){

                        var excluded = []

                        $('.exclude').each(function(){
                              if($(this).attr('disabled') == undefined && $(this).attr('data-studid') != undefined ){
                                    if($(this).prop('checked') == false){
                                          excluded.push($(this).attr('data-studid'))
                                    }
                              }
                             
                        })

                        $.ajax({
                              url: '/ecr/pending',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    quarter:$('#filter_quarter').val(),
                                    levelid:grades_info[0].levelid,
                                    subjid:grades_info[0].subjid,
                                    sectionid:grades_info[0].sectionid,
                                    exclude:excluded
                              },
                              success:function(data) {
                                    var quarter = $('#filter_quarter').val()
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          var is_pendig = true;

                                          $('.exclude').each(function(){

                                                var checked_status = $(this).prop('checked')

                                                if(checked_status == true && $(this).attr('data-studid') != undefined){
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').text('Pending')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').removeClass('badge-info')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').removeClass('badge-primary')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').addClass('badge-warning')
                                                      $(this).attr('disabled','disabled')
                                                      $(this).prop('checked',false)
                                                }

                                                if(checked_status == false && $(this).attr('data-studid') != undefined){
                                                      if($(this).attr('disabled') == undefined){
                                                            is_pendig = false
                                                      }
                                                }
                                          })

                                          if(is_pendig){

                                                $('#unpost_grade').attr('disabled','disabled')
                                                $('#pending_grade').attr('disabled','disabled')
                                                $('#post_grade').attr('disabled','disabled')
                                                $('#approve_grade').attr('disabled','disabled')
                                                $('#label_status').text('Pending')

                                                var selected_index = all_grades.findIndex(x=>x.sectionid==grades_info[0].sectionid && x.subjid == grades_info[0].subjid && x.levelid == grades_info[0].levelid)

                                                all_grades[selected_index].grades[quarter-1].stattext = 'PENDING'
                                                all_grades[selected_index].grades[quarter-1].status = 3

                                                load_gradesetup_datatable()

                                                var temp_stattext = $('#filter_status_3').val()
                                                var temp_quarter = $('#filter_quarter_3').val()
                                                filtered_all_status = all_status.filter(x=>x.stattext == temp_stattext && x.quarter == temp_quarter)
                                                load_datatable_3()
                                          }

                                          
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })

                  $(document).on('click','#unpost_grade',function(){

                        var excluded = []

                        $('.exclude').each(function(){
                              if($(this).prop('checked') == false && $(this).attr('data-studid') != undefined){
                                    excluded.push($(this).attr('data-studid'))
                              }
                        })

                        
                        $.ajax({
                              url: '/ecr/unpost',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    quarter:$('#filter_quarter').val(),
                                    levelid:grades_info[0].levelid,
                                    subjid:grades_info[0].subjid,
                                    sectionid:grades_info[0].sectionid,
                                    exclude:excluded
                              },
                              success:function(data) {
                                    var quarter = $('#filter_quarter').val()
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          var is_unposted = true;

                                          $('.exclude').each(function(){

                                                var checked_status = $(this).prop('checked')

                                                if(checked_status == true && $(this).attr('data-studid') != undefined){
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').text('Approved')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').removeClass('badge-info')
                                                      $('.gd_status[data-studid="'+$(this).attr('data-studid')+'"]').addClass('badge-primary')
                                                }

                                                if(checked_status == false && $(this).attr('data-studid') != undefined){
                                                      if($(this).attr('disabled') == undefined){
                                                            is_unposted = false
                                                      }
                                                }
                                          })

                                          if(is_unposted){

                                                $('#unpost_grade').attr('disabled','disabled')
                                                $('#pending_grade').removeAttr('disabled')
                                                $('#post_grade').removeAttr('disabled')
                                                $('#label_status').text('Approved')

                                                var selected_index = all_grades.findIndex(x=>x.sectionid==grades_info[0].sectionid && x.subjid == grades_info[0].subjid && x.levelid == grades_info[0].levelid)

                                                all_grades[selected_index].grades[quarter-1].stattext = 'APPROVED'
                                                all_grades[selected_index].grades[quarter-1].status = 2

                                                load_gradesetup_datatable()

                                                var temp_stattext = $('#filter_status_3').val()
                                                var temp_quarter = $('#filter_quarter_3').val()
                                                filtered_all_status = all_status.filter(x=>x.stattext == temp_stattext && x.quarter == temp_quarter)
                                                load_datatable_3()
                                          }

                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })                      


             

                  $(document).on('click','.view_status',function(){
                        $('#ecr_view_holder').empty()
                        $('#approve_grade').attr('disabled','disabled')
                        $('#post_grade').attr('disabled','disabled')
                        $('#pending_grade').attr('disabled','disabled')
                        $('#unpost_grade').attr('disabled','disabled')

                        $('#ecr_modal').modal()
                        var subjid = $(this).attr('data-subjid')
                        var levelid = $(this).attr('data-levelid')
                        var sectionid = $(this).attr('data-sectionid')
                        var quarter = $(this).attr('data-quarter')

                        grades_info = all_grades.filter(x=>x.subjid == subjid && x.levelid == levelid && x.sectionid == sectionid)

                        $('#label_gradelevel').text(grades_info[0].levelname)
                        $('#label_section').text(grades_info[0].sectionname)
                        $('#label_subject').text(grades_info[0].subjdesc)
                        $('#label_subjectcode').text(grades_info[0].subjcode)
                        $('#label_teacher').text(grades_info[0].teacher)
                        $('#label_tid').text(grades_info[0].tid)
                        $('#filter_quarter').val(quarter).change()
                        
                        $('#filter_quarter').empty();
                        $('#filter_quarter').append('<option value="1">1st Quarter</option>');
                        $('#filter_quarter').append('<option value="2">2nd Quarter</option>');
                        $('#filter_quarter').append('<option value="3">3rd Quarter</option>');
                        $('#filter_quarter').append('<option value="4">4th Quarter</option>');
                        
                        if(grades_info[0].levelid == 14 || grades_info[0].levelid == 15){
                              $('#filter_quarter').empty();
                              if($('#filter_sem').val() == 1){
                                    $('#filter_quarter').append('<option value="1">1st Quarter</option>');
                                    $('#filter_quarter').append('<option value="2">2nd Quarter</option>');
                              }else{
                                    $('#filter_quarter').append('<option value="3">3rd Quarter</option>');
                                    $('#filter_quarter').append('<option value="4">4th Quarter</option>');
                              }
                        }  

                        $('#filter_quarter').val(quarter).change()
                        $('#ecr_filter').trigger('click')                      
                  })

                  $(document).on('click','#ecr_filter',function(){
                        load_ecr()
                  })

                  function load_ecr(){
                        $.ajax({
                              url: '/ecr/view',
                              type: 'GET',
                              data: {
                                    semid:$('#filter_sem').val(),
                                    syid:$('#filter_sy').val(),
                                    quarter:$('#filter_quarter').val(),
                                    levelid:grades_info[0].levelid,
                                    subjid:grades_info[0].subjid,
                                    sectionid:grades_info[0].sectionid,
                              },
                              success:function(data) {
                                    try{
                                          if(data[0].status == 0){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].message
                                                })
                                                if(data[0].message == 'Does not contain any detail.'){
                                                      $('#ecr_view_holder').empty()
                                                      $('#ecr_view_holder')[0].innerHTML = '<table class="table table-sm table-bordered"><tr><td class="text-center text-danger"><i>Please download the ECR first.</i></td></tr></table>';
                                                }
                                          }else{
                                                $('#ecr_view_holder').empty()
                                                $('#ecr_view_holder').append(data)
                                          }
                                    }catch(err){
                                          $('#ecr_view_holder').empty()
                                          $('#ecr_view_holder').append(data)
                                    }
                                   
                              }
                        })
                  }

                  var all_status = []
                
                  var table = load_gradesetup_datatable()
                  table.state.clear();
                  table.destroy();
                  load_gradesetup_datatable()

                  function load_gradesetup_datatable(){

                        all_submitted = []
                        all_pending = []
                        all_posting = []
                        all_approved = []
                        all_status = []

                        var options = { year: 'numeric', month: 'short', day: 'numeric'};
                        var time = { hour: 'numeric',  minute: 'numeric'};

                        var submitted = 0;
                        var temp_col_def = []

                        temp_col_def.push( 
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
                                                      var text = '<a class="mb-0">'+rowData.subjdesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjcode+'</p>';
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      text = null
                                                      if(rowData.teacher != null){
                                                            var text = '<a class="mb-0">'+rowData.teacher+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+'</p>';
                                                      }
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                }
                                          }
                                    )

                        for(var x=3; x<= 6; x++){
                              temp_col_def.push( 
                                          {
                                                'targets': x,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var temp_grades = rowData.grades.filter(x=>x.quarter == col-2)
                                                      var date = '';
                                                      var text = '';

                                                      var temp_grades_data = {}
                                                      temp_grades_data.quarter = col-2
                                                      temp_grades_data.gradeid = null
                                                      temp_grades_data.detail = rowData
                                                      temp_grades_data.stattext = 'NOT SUBMITTED'


                                                      if(temp_grades.length > 0){
                                                            temp_grades_data.stattext = temp_grades[0].stattext
                                                            temp_grades_data.gradeid = temp_grades[0].id
                                                            var not_submitted = false

                                                            if(temp_grades[0].stattext == 'SUBMITTED'){
                                                                  var date = new Date(temp_grades[0].date_submitted);
                                                                  all_submitted.push(temp_grades_data)
                                                            }
                                                            else if(temp_grades[0].stattext == 'NOT SUBMITTED' || temp_grades[0].stattext == 'PENDING'){
                                                                  not_submitted = true;
                                                            }
                                                            else{
                                                                  var date = new Date(temp_grades[0].updateddatetime);
                                                            }

                                                            if(not_submitted){
                                                                  if(temp_grades[0].stattext == 'NOT SUBMITTED'){
                                                                        var text = '<a class="mb-0 view_status">NOT SUBMITTED</a><p class="text-muted mb-0" style="font-size:.7rem">&nbsp;</p>';
                                                                  }else{
                                                                        var text = '<a class="mb-0 view_status">PENDING</a><p class="text-muted mb-0" style="font-size:.7rem">&nbsp;</p>';
                                                                  }
                                                            }else{
                                                                  var text = '<a href="javascript:void(0)" class="mb-0 view_status" data-subjid="'+rowData.subjid+'" data-sectionid="'+rowData.sectionid+'" data-levelid="'+rowData.levelid+'" data-quarter="'+temp_grades[0].quarter+'">'+temp_grades[0].stattext+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+date.toLocaleString('en-US', options)+' '+date.toLocaleString('en-US', time)+'</p>';
                                                            }
                                                          
                                                      }else{
                                                            var text = '<a class="mb-0 view_status">NOT SUBMITTED</a><p class="text-muted mb-0" style="font-size:.7rem">&nbsp;</p>';
                                                      }

                                                     
                                                      if(rowData.levelid == 14 || rowData.levelid == 15){
                                                           if($('#filter_sem').val() == 2){
                                                                 if(col == 3 || col == 4){
                                                                        text = 'N/A'
                                                                 }else{
                                                                        all_status.push(temp_grades_data)
                                                                 }
                                                                
                                                           }else if($('#filter_sem').val() == 1){
                                                                  if(col == 5 || col == 6){
                                                                        text = 'N/A'
                                                                 }else{
                                                                  all_status.push(temp_grades_data)
                                                                 }
                                                           }
                                                      }else{
                                                            all_status.push(temp_grades_data)
                                                      }

                                                      $(td)[0].innerHTML =  text;
                                                      $(td).addClass('align-middle')
                                                }
                                          }
                                    )
                        }
                        

                        var temp_table = $("#subjectplot_table").DataTable({
                              destroy: true,
                              data:all_grades,
                              lengthChange: false,
                              stateSave: true,
                              columns: [
                                          { "data": "sectionname" },
                                          { "data": "plotsort" },
                                          { "data": "search" },
                                          { "data": null },
                                          { "data": null },
                                          { "data": null },
                                          { "data": null },
                                    ],
                              order: [
                                    [ 0, "asc" ],
                                    [ 1, "asc" ]
                              ],
                              columnDefs:temp_col_def
                        });

                      
                        load_all_grade_status()
                        return temp_table;

                  }

                  var temp_quarter = null
                  var all_status = []
                  var filter_temp_status

                  $(document).on('click','.view_list_2',function(){
                        var table = $("#datatable_3").DataTable()
                        table.state.clear();
                        table.destroy();

                        var temp_stattext = $(this).attr('data-status')
                        var temp_quarter = $(this).attr('data-quarter')
                        filtered_all_status = all_status.filter(x=>x.stattext == temp_stattext && x.quarter == temp_quarter)
                      
                        $('#modal_3').modal()
                        $('#filter_quarter_3').val(temp_quarter).change()
                        $('#filter_status_3').val(temp_stattext).change()
                        load_datatable_3()
                        
                  })

                  $(document).on('click','#filter_button_3',function(){

                        var table = $("#datatable_3").DataTable()
                        table.state.clear();
                        table.destroy();

                        var temp_stattext = $('#filter_status_3').val()
                        var temp_quarter = $('#filter_quarter_3').val()
                        filtered_all_status = all_status.filter(x=>x.stattext == temp_stattext && x.quarter == temp_quarter)
                        load_datatable_3()
                  })

               

                  function load_datatable_3(){

                        $("#datatable_3").DataTable({
                              destroy: true,
                              data:filtered_all_status,
                              autoWidth: false,
                              stateSave: true,
                              columns: [
                                          { "data": "detail.search" },
                                          { "data": null },
                                          { "data": null }
                                    ],
                              order: [
                                    [ 1, "asc" ]
                              ],
                              columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var text = '<a class="mb-0">'+rowData.detail.sectionname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.detail.levelname+'</p>';
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      var view_status = rowData.detail.subjdesc
                                                      if(rowData.stattext != 'NOT SUBMITTED' && rowData.stattext != 'PENDING'){
                                                            var view_status = '<a href="javascript:void(0)" class="mb-0 view_status" data-subjid="'+rowData.detail.subjid+'" data-sectionid="'+rowData.detail.sectionid+'" data-levelid="'+rowData.detail.levelid+'" data-quarter="'+$('#filter_quarter_3').val()+'">'+rowData.detail.subjdesc+'</a>'
                                                      }else{

                                                      }

                                                      var text = view_status+'<p class="text-muted mb-0" style="font-size:.7rem">'+rowData.detail.subjcode+'</p>';
                                                      
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      text = null
                                                      if(rowData.detail.teacher != null){
                                                            var text = '<a class="mb-0">'+rowData.detail.teacher+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.detail.tid+'</p>';
                                                      }
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                }
                                          }
                              ]
                        })
                  }


                  function load_all_grade_status(){

                     
                        var temp_all_status = [
                              {'sort':'b','status':'SUBMITTED','data':all_status.filter(x=>x.stattext == 'SUBMITTED')},
                              {'sort':'c','status':'COOR APPROVED','data':all_status.filter(x=>x.stattext == 'COOR APPROVED')},
                              {'sort':'d','status':'APPROVED','data':all_status.filter(x=>x.stattext == 'APPROVED')},
                              {'sort':'e','status':'PENDING','data':all_status.filter(x=>x.stattext == 'PENDING')},
                              {'sort':'f','status':'POSTED','data':all_status.filter(x=>x.stattext == 'POSTED')},
                              {'sort':'a','status':'NOT SUBMITTED','data':all_status.filter(x=>x.stattext == 'NOT SUBMITTED')}
                        ]

                        var temp_columndef = []

                        for(var cn = 1; cn<=4; cn++){
                              temp_quarter = cn
                              temp_columndef.push({
                                    'targets': cn,
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var text = 0

                                          if(rowData.status == "SUBMITTED"){
                                                text = rowData.data.filter(x=>x.quarter == col).length
                                          }else if(rowData.status == "APPROVED"){
                                                text = rowData.data.filter(x=>x.quarter == col).length
                                          }else if(rowData.status == "COOR APPROVED"){
                                                text = rowData.data.filter(x=>x.quarter == col).length
                                          }else if(rowData.status == "POSTED"){
                                                text = rowData.data.filter(x=>x.quarter == col).length
                                          }else if(rowData.status == "PENDING"){
                                                text = rowData.data.filter(x=>x.quarter == col).length
                                          }else if(rowData.status == "NOT SUBMITTED"){
                                                text = rowData.data.filter(x=>x.quarter == col).length
                                          }

                                          $(td)[0].innerHTML =  '<a class="view_list_2" href="javascript:void(0)" data-quarter="'+col+'" data-status="'+rowData.status+'">'+text+'</a>';
                                         

                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                    }
                              })
                        }

                       $("#grade_status").DataTable({
                              destroy: true,
                              data:temp_all_status,
                              paging: false,
                              searching: false,
                              bInfo : false,
                              lengthChange: false,
                              columns: [
                                          { "data": "status" },
                                          { "data": "sort" },
                                          { "data": null },
                                          { "data": null },
                                          { "data": null }
                                          
                                    ],
                              order: [
                                    [ 1, "asc" ]
                              ],
                              columnDefs: temp_columndef
                        })

                  }
            })
                  
      </script>

      <script>
            $(document).ready(function(){

                  $(document).on('click','#button_filter',function(){
                        load_grades_by_student()
                  })

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  load_grades_by_student()
                  var status_data = []

                  var student_grade_status = []
                  function load_grades_by_student(){
                        $.ajax({
                              type:'GET',
                              url: '/grades/list/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No grades Found!'
                                          })
                                          student_grade_status = []
                                          load_all_grade_status_by_student()
                                    }else{
                                          student_grade_status = data
                                          load_all_grade_status_by_student()
                                    }
                              }
                        })
                       
                  }

                  load_all_grade_status_by_student()

                  function load_all_grade_status_by_student(){

                        var temp_all_status = [
                              {'sort':'b','status':'SUBMITTED','data':student_grade_status.filter(x=>x.gdstatus == 1)},
                              {'sort':'c','status':'COOR APPROVED','data':student_grade_status.filter(x=>x.gdstatus == 6)},
                              {'sort':'d','status':'APPROVED','data':student_grade_status.filter(x=>x.gdstatus == 2)},
                              {'sort':'e','status':'PENDING','data':student_grade_status.filter(x=>x.gdstatus == 3)},
                              {'sort':'f','status':'POSTED','data':student_grade_status.filter(x=>x.gdstatus == 4)},
                              {'sort':'a','status':'NOT SUBMITTED','data':student_grade_status.filter(x=>x.gdstatus == null)}
                        ]

                        status_data = temp_all_status

                        var temp_columndef = []

                        for(var cn = 1; cn<=4; cn++){
                              temp_quarter = cn
                              temp_columndef.push({
                                    'targets': cn,
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var text = 0
                                          var key = 'student'
                                         
                                          const arrayUniqueByKey = [...new Map(rowData.data.filter(x=>x.quarter == col).map(item =>
                                                                  [item[key], item])).values()];

                                          text = arrayUniqueByKey.length

                                          $(td)[0].innerHTML =  '<a class="view_student_list" href="javascript:void(0)" data-quarter="'+col+'" data-status="'+rowData.status+'">'+text+'</a>';
                                         
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                    }
                              })
                        }

                        $("#student_grade_status").DataTable({
                              destroy: true,
                              data:temp_all_status,
                              paging: false,
                              searching: false,
                              lengthChange: false,
                              bInfo : false,
                              columns: [
                                          { "data": "status" },
                                          { "data": "sort" },
                                          { "data": null },
                                          { "data": null },
                                          { "data": null }
                                          
                                    ],
                              order: [
                                    [ 1, "asc" ]
                              ],
                              columnDefs: temp_columndef
                        })

                  }

                  $(document).on('click','.view_student_list',function(){

                        var quarter  = $(this).attr('data-quarter')
                        var status  = $(this).attr('data-status')

                        $('#filter_status_4').val(status).change()
                        $('#filter_quarter_4').val(quarter).change()

                        load_student_list()
                        $('#modal_4').modal()
                  })


                  $(document).on('click','#filter_button_4',function(){
                        load_student_list()
                        $('#modal_4').modal()
                  })

                  function load_student_list(){

                        var quarter  = $('#filter_quarter_4').val()
                        var status  = $('#filter_status_4').val()

                        var temp_data = status_data.filter(x=>x.status == status)[0].data
                        var key = 'student'
                        var students = [...new Map(temp_data.filter(x=>x.quarter == quarter).map(item =>
                                                                  [item[key], item])).values()];

                        $("#datatable_4").DataTable({
                              destroy: true,
                              data:students,
                              columns: [
                                          { "data": "student" },
                                          { "data": "sectionname" },
                                          
                                    ],
                              order: [
                                    [ 0, "asc" ]
                              ],
                              columnDefs:[
                                    {
                                          'targets': 0,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  '<a class="view_student_subjects" href="javascript:void(0)" data-studid="'+rowData.studid+'">'+rowData.student+'</a>';
                                          }
                                    }
                              ]
                        })

                  }

                  $(document).on('click','.view_student_subjects',function(){
                        var temp_studid = $(this).attr('data-studid')
                        $('#student_name').text($(this).text())
                        display_student_grade(temp_studid)
                        $('#modal_5').modal()
                        
                  })

                  function display_student_grade(studid){
                        
                       
                        var temp_quarter = $('#filter_quarter_4').val()
                        var temp_status = $('#filter_status_4').val()
                        var temp_status_data = status_data.filter(x=>x.status == temp_status)
                        var student_grade = temp_status_data[0].data.filter(x=>x.studid == studid && x.quarter == temp_quarter)

                        $("#datatable_5").DataTable({
                              destroy: true,
                              autoWidth: false,
                              data:student_grade,
                              lengthChange: false,
                              columns: [
                                          { "data": "student" },
                                          { "data": "sectionname" },
                                          { "data": "subjdesc" },
                                          { "data": "qg" },
                                          { "data": null },
                                          { "data": null },
                                          
                                    ],
                              order: [
                                    [ 0, "asc" ]
                              ],
                              columnDefs:[
                                    {
                                          'targets': 0,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.qg >= 75 ? 'PASSED' : 'FAILED';
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var button = ''
                                                if(temp_status == 'SUBMITTED'){
                                                      button += '<button class="btn btn-warning btn-sm mr-1 student_to_pending" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'" style="font-size:.6rem">Pending</button>'
                                                     
                                                      button += '<button class="btn btn-primary btn-sm student_to_approve mr-1" style="font-size:.6rem" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'">Approve</button>'

                                                      button += '<button class="btn btn-info btn-sm student_to_post mr-1" style="font-size:.6rem" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'">Post</button>'


                                                }else if(temp_status == 'APPROVED'){

                                                      button += '<button class="btn btn-warning btn-sm mr-2 student_to_pending" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'" style="font-size:.6rem">Pending</button>'
                                                      button += '<button class="btn btn-info btn-sm student_to_post" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'" style="font-size:.6rem">Post</button>'

                                                }else if(temp_status == 'POSTED'){

                                                      button += '<button class="btn btn-warning btn-sm mr-1 student_to_pending" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'" style="font-size:.6rem">Pending</button>'

                                                      button += '<button class="btn btn-danger btn-sm student_to_unpost" data-id="'+rowData.detailid+'" data-studid="'+rowData.studid+'" style="font-size:.6rem">Unpost Grade</button>'
                                                }

                                                $(td)[0].innerHTML =  button;
                                          }
                                    }
                              ]
                        })

                        
                  }

                  $(document).on('click','.student_to_pending',function(){
                        var dataid = $(this).attr('data-id')
                        var studid = $(this).attr('data-studid')
                        $.ajax({
                              url: '/ecr/pending/student',
                              type: 'GET',
                              data: {
                                    studid:studid,
                                    id:dataid,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          $('.gd_status[data-studid="'+studid+'"]').attr('class')
                                          $('.gd_status[data-studid="'+studid+'"]').addClass('badge badge-warning gd_status')
                                          $('.gd_status[data-studid="'+studid+'"]').text('Pending')

                                          var temp_index = student_grade_status.findIndex(x=>x.studid == studid && x.detailid == dataid)
                                          student_grade_status[temp_index].gdstatus = 3
                                          load_all_grade_status_by_student()
                                          load_student_list()
                                          display_student_grade(studid)
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })

                  $(document).on('click','.student_to_post',function(){
                        var dataid = $(this).attr('data-id')
                        var studid = $(this).attr('data-studid')
                        $.ajax({
                              url: '/ecr/post/student',
                              type: 'GET',
                              data: {
                                    studid:studid,
                                    id:dataid,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          var temp_index = student_grade_status.findIndex(x=>x.studid == studid && x.detailid == dataid)
                                          student_grade_status[temp_index].gdstatus = 4
                                          load_all_grade_status_by_student()
                                          load_student_list()
                                          display_student_grade(studid)
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })

                  $(document).on('click','.student_to_approve',function(){
                        var dataid = $(this).attr('data-id')
                        var studid = $(this).attr('data-studid')
                        $.ajax({
                              url: '/ecr/approve/student',
                              type: 'GET',
                              data: {
                                    studid:studid,
                                    id:dataid,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          var temp_index = student_grade_status.findIndex(x=>x.studid == studid && x.detailid == dataid)
                                          student_grade_status[temp_index].gdstatus = 2
                                          load_all_grade_status_by_student()
                                          load_student_list()
                                          display_student_grade(studid)
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })

                  $(document).on('click','.student_to_unpost',function(){
                        var dataid = $(this).attr('data-id')
                        var studid = $(this).attr('data-studid')
                        $.ajax({
                              url: '/ecr/unpost/student',
                              type: 'GET',
                              data: {
                                    studid:studid,
                                    id:dataid,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          var temp_index = student_grade_status.findIndex(x=>x.studid == studid && x.detailid == dataid)
                                          student_grade_status[temp_index].gdstatus = 2
                                          load_all_grade_status_by_student()
                                          load_student_list()
                                          display_student_grade(studid)
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })
                  
         
            })

      </script>


@endsection


