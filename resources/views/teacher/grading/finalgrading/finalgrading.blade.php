@php
      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
            $extend = 'teacher.layouts.app';
      }else if(auth()->user()->type == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(auth()->user()->type == 1){
            $extend = 'teacher.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
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
@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->get(); 
@endphp



<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary p-1">
              </div>
              <div class="modal-body">
                  <div class="row mt-3" style=" font-size:14px !important">
                        <div class="col-md-5">
                              <strong><i class="fas fa-book mr-1"></i> Grade Level</strong>
                              <p class="text-muted" id="label_gradelevel_1">
                                    --
                               </p>
                        </div>
                        <div class="col-md-7">
                              <strong><i class="fas fa-book mr-1"></i> Section</strong>
                              <p class="text-muted" id="label_section_1">
                                    --
                               </p>
                        </div>
                  </div>
                  <div class="row" style=" font-size:14px !important">
                        <div class="col-md-5">
                              <strong><i class="fas fa-book mr-1"></i> Subject</strong>
                              <p class="text-muted mb-0" id="label_subject_1">
                                    --
                              </p>
                              <p class="text-danger mb-0" >
                                    <i id="label_subjectcode_1"> -- </i>
                              </p>
                        </div>
                        <div class="col-md-5">
                              <strong><i class="fas fa-book mr-1"></i> Quarter</strong>
                              <p class="text-muted mb-0" id="label_quarter_1">
                                    --
                              </p>
                        </div>
                  </div>
                 <div class="row mt-2">
                        <div class="col-md-12">
                              <div class="card-body table-responsive p-0" style="height: 500px;">
                                    <table class="table table-sm table-bordered table-head-fixed">
                                          <thead>
                                                <tr>
                                                      <th width="5%" class="text-center align-middle p-0"><input type="checkbox" checked="checked" class="exclude select_all"></th>
                                                      <th width="15%">SID</th>
                                                      <th width="60%">Student</th>
                                                      <th width="10%" class="text-center">Grade</th>
                                                      <th width="10%" class="text-center">Status</th>
                                                </tr>
                                          </thead>
                                          <tbody id="students_from_pending">
                                          
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                 </div>
                 <div class="row mt-2">
                        <div class="col-md-6">
                              <button class="btn btn-sm btn-success" id="btnSubmit" style=" font-size:11px !important"> Submit Grades</button>
                        </div>
                        <div class="col-md-6">
                              <button class="btn btn-danger btn-sm float-right" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
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
                        <h1>Final Grade</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Final Grade</li>
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
                                                      <div class="col-md-2  form-group mb-0">
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
                                                      <div class="col-md-6 form-groupp mb-0" >
                                                            <label for="">Subjects</label>
                                                            <select class="form-control  select2" id="filter_subjects">
                                                                  <option value="">Select Subject</option>
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4 form-groupp mb-0" >
                                                            <label for="">Section</label>
                                                            <select class="form-control  select2" id="filter_section">
                                                                  <option value="">Select Section</option>
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
                  <div class="col-md-3">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body  p-2">
                                                <div class="row mt-3" style=" font-size:11px !important">
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
                                                            <p class="text-danger mb-0" >
                                                                  <i id="label_subjectcode"> -- </i>
                                                            </p>
                                                      </div>
                                                </div>
                                                <div class="row" hidden>
                                                      <div id="subject_sem"></div>
                                                      <div id="subject_levelid"></div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <div class="row" style=" font-size:11px !important">
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i> 1st Quarter Status</strong>
                                                            <p class="text-muted grade_status"  data-quarter="1">
                                                                  --
                                                            </p>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i>Date Submitted</strong>
                                                            <p class="text-muted label_date" data-quarter="1">
                                                                  --
                                                            </p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-warning btn-sm btn-block submit_pending_grades" data-quarter="1"  hidden>Submit Pending Grade</button>
                                                      </div>
                                                      <div class="col-md-12">
                                                            <button class="btn btn-primary btn-sm btn-block submit_grades" data-quarter="1"  hidden>Submit 1st Quarter Grades</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <div class="row" style=" font-size:11px !important">
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i> 2nd Quarter Status</strong>
                                                            <p class="text-muted grade_status"  data-quarter="2">
                                                                  --
                                                            </p>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i>Date Submitted</strong>
                                                            <p class="text-muted label_date" data-quarter="2">
                                                                  --
                                                            </p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-warning btn-sm btn-block submit_pending_grades" data-quarter="2"  hidden>Submit Pending Grade</button>
                                                      </div>
                                                      <div class="col-md-12">
                                                            <button class="btn btn-primary btn-sm btn-block submit_grades" data-quarter="2" hidden>Submit 2nd Quarter Grades</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <div class="row" style=" font-size:11px !important">
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i> 3rd Quarter Status</strong>
                                                            <p class="text-muted grade_status"  data-quarter="3">
                                                                  --
                                                            </p>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i>Date Submitted</strong>
                                                            <p class="text-muted label_date" data-quarter="3">
                                                                  --
                                                            </p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-warning btn-sm btn-block submit_pending_grades" data-quarter="3"  hidden>Submit Pending Grades</button>
                                                      </div>
                                                      <div class="col-md-12">
                                                            <button class="btn btn-primary btn-sm btn-block submit_grades" data-quarter="3" hidden>Submit 3rd Quarter Grades</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <div class="row" style=" font-size:11px !important">
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i> 4th Quarter Status</strong>
                                                            <p class="text-muted grade_status"  data-quarter="4">
                                                                  --
                                                            </p>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <strong><i class="fas fa-book mr-1"></i>Date Submitted</strong>
                                                            <p class="text-muted label_date" data-quarter="4">
                                                                  --
                                                            </p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-warning btn-sm btn-block submit_pending_grades" data-quarter="4"  hidden>Submit Pending Grades</button>
                                                      </div>
                                                      <div class="col-md-12">
                                                            <button class="btn btn-primary btn-sm btn-block submit_grades" data-quarter="4" hidden>Submit 4th Quarter Grades</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        
                  </div>
                  <div class="col-md-9">
                        <div class="card shadow">
                              <div class="card-body  p-2">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <div class="card-body table-responsive p-0" style="height: 500px;">
                                                      <table class="table table-sm table-bordered table-head-fixed" style="font-size:.7rem !important">
                                                            <thead>
                                                                  <tr>
                                                                        <th width="12%">SID</th>
                                                                        <th width="40%">Student</th>
                                                                        <th width="8%" class="text-center" id="dq1">1st</th>
                                                                        <th width="8%" class="text-center" id="dq2">2nd</th>
                                                                        <th width="8%" class="text-center" id="dq3">3rd</th>
                                                                        <th width="8%" class="text-center" id="dq4">4th</th>
                                                                        <th width="8%" class="text-center">FG</th>
                                                                        <th width="8%" class="text-center">Remarks</th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody id="students">

                                                            </tbody>
                                                      </table>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row mt-4">
                                          <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm" id="save_button_1" disabled><i class="fas fa-save"></i> Save Grades</button>
                                                <button class="btn btn-primary btn-sm" id="download_final_grade" disabled><i class="fas fa-save"></i> Download Grades</button>
                                          </div>
                                          <div class="col-md-12 mt-2">
                                                <div class="progress progress-xxs">
                                                      <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span class="sr-only">60% Complete (warning)</span>
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

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      
      <script>
            $(document).ready(function(){

                  $(document).on('click','#download_final_grade',function(){
                  
                        var semid = null
                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()

                        var filter_data = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid)
                        var levelid = filter_data[0].levelid
                        
                        if(levelid == 14 || levelid == 15){
                              semid = filter_data[0].semid
                        }
                        
                        var syid = $('#filter_sy').val()
                        
                        window.open('/teacher/finalgrades/extract?syid='+syid+'&levelid='+levelid+'&sectionid='+sectionid+'&subjid='+subjid+'&semid='+semid, '_blank');
                  })

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  window.onbeforeunload = function(){
                        var updated_length = $('.updated').length
                        if(updated_length){
                              return ""
                        }
                       
                  };

                  $('.select2').select2()
            
                  getload()

                  var all_sched = []

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
                  


                  function load_subject_select(subjid = null){

                        var grade_subjects = []

                        $.each(all_sched,function(a,b){

                              var count = grade_subjects.filter(x=>x.subjcode == b.subjcode && x.id == b.subjid).length
                              var pending = '';

                              $.each(all_sched.filter(x=>x.subjcode == b.subjcode && x.subjid == b.subjid) , function(c,d){
                                    if(d.with_pending){
                                          pending = ' <div class="badge badge-warning">Pending</div>'
                                    }
                              })

                              if(count == 0){
                                    grade_subjects.push({
                                          'subjcode':b.subjcode,
                                          'id':b.subjid,
                                          'text': b.optiondisplay+pending,
                                          'html': b.optiondisplay+pending,
                                    })
                              }
                        })

                              $("#filter_subjects").empty()
                              $("#filter_section").empty()
                              $("#filter_gradelevel").empty()
                              $("#filter_subjects").append('<option value="">Select Grade Level</option>')
                              $('#students').empty()
                              $("#filter_subjects").select2({
                                    data: grade_subjects,
                                    allowClear: true,
                                    placeholder: "Select Subject",
                                    escapeMarkup: function(markup) {
                                          return markup;
                                    }
                              })

                        if(subjid != null){
                              $("#filter_subjects").val(subjid).change()
                        }
                  }

                  function load_section_select(sectionid = null){

                        var sections = []
                        var subjid = $('#filter_subjects').val()
                        var subjdesc = $("#filter_subjects option:selected").text().replace(' <div class="badge badge-warning">Pending</div>','');
                        
                        $.each(all_sched.filter(x=>x.subjid == subjid && x.optiondisplay == subjdesc),function(a,b){
                              var count = sections.filter(x=>x.id == b.sectionid).length
                              if(count == 0){
                                    var pending = b.with_pending ? '<div class="badge badge-warning">Pending</div>':'' 
                                    if(count == 0){
                                          sections.push({
                                                'id':b.sectionid,
                                                'text': b.sectionname+' '+pending,
                                                'html': b.sectionname+' '+pending,
                                          })
                                    }
                              }
                        })
                        $("#filter_section").empty()
                        $("#filter_section").append('<option value="">Select Section</option>')
                        $("#filter_section").select2({
                              data: sections,
                              allowClear: true,
                              placeholder: "Select Section",
                              escapeMarkup: function(markup) {
                                    return markup;
                              }
                        })

                        if(sectionid != null){
                              $("#filter_section").val(sectionid).change()
                        }
                  }

                  var selected_quarter = null

                  $(document).on('click','#btnSubmit[type="perst"]',function(){

                        return false;

                        var updated_length = $('.updated').length
                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }

                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()
                        var syid = $('#filter_sy').val()
                        var quarter = selected_quarter
                        var levelid = $('#subject_levelid').text()
                        var semid = levelid == 14 || levelid == 15 ? $('#subject_sem').text() : 1

                        var excluded = []

                        $('.exclude').each(function(){
                              if($(this).prop('checked') == false && $(this).attr('data-studid') != undefined){
                                    excluded.push($(this).attr('data-studid'))
                              }
                        })

                        var text = $('#label_subject_1').text()

                        Swal.fire({
                              title: 'Are you sure you want to<br>submit '+text+' grades?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Submit '+text+' grades'
                        }).then((result) => {
                                    if (result.value) {

                                          
                                          $('#btnSubmit').attr('disabled','disabled')
                                          $('#updateGrade').attr('disabled','disabled')
                                          $('.exclude').attr('disabled','disabled')

                                          $.ajax({
                                                url: '/teacher/pending/grade/submit/grades',
                                                type:"GET",
                                                data:{
                                                      syid: syid,
                                                      levelid:levelid,
                                                      sectionid: sectionid,
                                                      subjid :subjid,
                                                      quarter :quarter,
                                                      semid:semid,
                                                      excluded:excluded
                                                },
                                                success:function(data) {
                                                      if(data[0].status == 1){
                                                            Toast.fire({
                                                                  type: 'success',
                                                                  title: data[0].data
                                                            })

                                                            $('.exclude').each(function(a,b){
                                                                  var temp_studid = $(this).attr('data-studid')
                                                                  if($(this).prop('checked') == true && $(this).attr('data-studid') != undefined){
                                                                        $('.pending_list[data-studid="'+temp_studid+'"]').remove()
                                                                  }
                                                            })

                                                            if(excluded.length == 0){

                                                                  $('#btnSubmit').attr('disabled','disabled')
                                                                  $('.exclude').attr('disabled','disabled')
                                                                  $('.submit_pending_grades[data-quarter="'+quarter+'"]').attr('hidden','hidden')

                                                                  var temp_index = all_sched.findIndex(x=>x.subjid == subjid && x.sectionid == sectionid)
                                                                  all_sched[temp_index].quarter_pending_perst = all_sched[temp_index].quarter_pending_perst.filter(x=>x!=selected_quarter)
                                                                  all_sched[temp_index].with_pending = false

                                                                  // load_subject_select(subjid)
                                                                  // load_section_select(sectionid)
                                                                  view_grades()
                                                                  update_sidenav()

                                                            }else{
                                                                  students()
                                                                  $('#btnSubmit').removeAttr('disabled')
                                                                  $('.exclude').removeAttr('disabled')
                                                            }

                                                      }else{
                                                            $('#btnSubmit').removeAttr('disabled')
                                                            $('.exclude').removeAttr('disabled')
                                                            Toast.fire({
                                                                  type: 'error',
                                                                  title: 'Something went wrong!'
                                                            })
                                                      }
                                                },
                                                error:function(){
                                                      $('#btnSubmit').removeAttr('disabled')
                                                      $('.exclude').removeAttr('disabled')
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          })
                                    }
                              }
                        )
                  })


                  $(document).on('click','.submit_pending_grades, .submit_grades',function(){

                        var temp_quarter = $(this).attr('data-quarter')
                        selected_quarter = temp_quarter
                        $('#btnSubmit').attr('type','perst')

                        $('#students_from_pending').empty()
                        $('#label_quarter_1').text(temp_quarter)

                        $('#btnSubmit').removeAttr('disabled')
                        $('.exclude').removeAttr('disabled')
                       
                        male = 0
                        female = 0

                        $.each(all_students,function(a,b){

                              
                              if(temp_quarter == 1){
                                    var is_pending = b.gstatus1 == 3 || b.gstatus1 == 0 ? true:false;
                              }else if(temp_quarter == 2){
                                    var is_pending = b.gstatus2 == 3 || b.gstatus2 == 0 ? true:false;
                              }else if(temp_quarter == 3){
                                    var is_pending = b.gstatus3 == 3 || b.gstatus3 == 0 ? true:false;
                              }else if(temp_quarter == 4){
                                    var is_pending = b.gstatus4 == 3 || b.gstatus4 == 0 ? true:false;
                              }


                              if(is_pending){

                                    if(male == 0 && b.gender == 'MALE'){
                                          $('#students_from_pending').append('<tr class="bg-secondary"><th colspan="8">MALE</th></tr>')
                                          male = 1
                                    }else if(female == 0 && b.gender == 'FEMALE'){
                                          $('#students_from_pending').append('<tr class="bg-secondary"><th colspan="8">FEMALE</th></tr>')
                                          female = 1
                                    }

                                    if(temp_quarter == 1){
                                          var qgrade = b.qgrade1 != null ? b.qgrade1 : '';
                                          var qid = b.qid1 != null ? b.qid1 : '';
                                    }else if(temp_quarter == 2){
                                          var qgrade = b.qgrade2 != null ? b.qgrade2 : '';
                                          var qid = b.qid2 != null ? b.qid2 : '';
                                    }else if(temp_quarter == 3){
                                          var qgrade = b.qgrade3 != null ? b.qgrade3 : '';
                                          var qid = b.qid3 != null ? b.qid3 : '';
                                    }else if(temp_quarter == 4){
                                          var qgrade = b.qgrade4 != null ? b.qgrade4 : '';
                                          var qid = b.qid4 != null ? b.qid4 : '';
                                    }
                                    
                                    var remarks = '';

                                  

                               
                                    qgrade = $('.studgrades[data-studid="'+b.studid+'"][data-id="'+qid+'"]').text()


                                    if(qgrade != ''){
                                          remarks = qgrade >= 75 ? 'PASSED' : 'FAILED'
                                    }


                                    $('#students_from_pending').append('<tr data-studid="'+b.studid+'" class="pending_list"><td class="text-center p-0 align-middle"><input type="checkbox"  class="exclude" checked="checked" data-studid="'+b.studid+'"></td><td>'+b.sid+'</td><td>'+b.student+'</td><td class="text-center">'+qgrade+'</td><td class="text-center">'+remarks+'</td></tr>')

                              }

                        })
                        
                        $('#modal_1').modal()
                  })

                  function getload(){
                        $.ajax({
                              type:'GET',
                              url: '/teacher/get/teacheingload',
                              data:{
                                    syid:$('#filter_sy').val(),
                              },
                              success:function(data) {
                                    var grade_subjects = []
                                    all_sched = data
                                    load_subject_select()
                              }
                        })
                  }

                  function gradestatus(){

                        $('.submit_grades').attr('hidden','hidden')
                        $('.submit_grades').attr('disabled','disabled')

                        var semid = null
                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()

                        var filter_data = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid)
                        var levelid = filter_data[0].levelid
                        
                        if(levelid == 14 || levelid == 15){
                              semid = filter_data[0].semid
                        }

                        $('#subject_sem').text(semid)
                        $('#subject_levelid').text(levelid)

                        $.ajax({
                              type:'GET',
                              url: '/teacher/get/gradestatus',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:levelid,
                                    sectionid:sectionid,
                                    quarter:$('#filter_quarter').val(),
                                    subjid:subjid,
                                    semid:semid
                              },
                              success:function(data) {
                                    all_status = data
                                    for(var x=1;x<=4;x++){
                                         if(levelid == 14 || levelid == 15){
                                                if(semid == 1){
                                                      if(x == 1 || x == 2){
                                                            update_quarter_display(x,data)
                                                      }
                                                }else{
                                                      if(x == 3 || x == 4){
                                                            update_quarter_display(x,data)
                                                      }
                                                }
                                         }else{
                                                update_quarter_display(x,data)
                                         }
                                    }
                                    students()
                              }
                        })
                  }

                  function update_quarter_display(quarter = null , data){
                        var quarterinfo = data.filter(y=>y.quarter == quarter)
                        if(quarterinfo[0].submitted == 0){
                              if(quarterinfo[0].status == 3){
                                    $('.grade_status[data-quarter="'+quarter+'"]').text('Pending')
                              }else{
                                    $('.grade_status[data-quarter="'+quarter+'"]').text('Not Submitted')
                              }
                              
                              $('.submit_grades[data-quarter="'+quarter+'"]').removeAttr('hidden')
                              $('.submit_grades[data-quarter="'+quarter+'"]').removeAttr('disabled')
                              $('.submit_grades[data-quarter="'+quarter+'"]').attr('data-id',quarterinfo[0].id)
                        }
                        if(quarterinfo[0].submitted == 1){
                              $('.label_date[data-quarter="'+quarter+'"]').text(quarterinfo[0].date_submitted)
                              if(quarterinfo[0].status == 0){
                                    $('.grade_status[data-quarter="'+quarter+'"]').text('Submitted')
                              }else if(quarterinfo[0].status == 2){
                                    $('.grade_status[data-quarter="'+quarter+'"]').text('Approved')
                              }else if(quarterinfo[0].status == 4){
                                    $('.grade_status[data-quarter="'+quarter+'"]').text('Posted')
                              }
                        }
                  }

                  var all_students = [];

                  function students(){
                        var semid = null
                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()
                        var filter_data = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )
                        var levelid = filter_data[0].levelid


                         if(filter_data[0].with_pending){
                              $.each(filter_data[0].quarter_pending,function(a,b){
                                    $('.submit_grades[data-quarter="'+b+'"]').removeAttr('hidden')
                              })
                              $.each(filter_data[0].quarter_pending_perst,function(a,b){
                                    $('.submit_pending_grades[data-quarter="'+b+'"]').removeAttr('hidden')
                              })
                        }

                        if(levelid == 14 || levelid == 15){
                              semid = filter_data[0].semid
                        }

                        $('#dq1').removeAttr('hidden')
                        $('#dq2').removeAttr('hidden')
                        $('#dq3').removeAttr('hidden')
                        $('#dq4').removeAttr('hidden')

                        $.ajax({
                              type:'GET',
                              url: '/teacher/get/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:levelid,
                                    sectionid:sectionid,
                                    quarter:$('#filter_quarter').val(),
                                    subjid:subjid,
                                    semid:semid,
                              },
                              success:function(data) {

                                    all_students = data

                                    if(levelid == 14 || levelid == 15){
                                          if(semid == 2){
                                                $('#dq1').attr('hidden','hidden')
                                                $('#dq2').attr('hidden','hidden')
                                          }else{
                                                $('#dq3').attr('hidden','hidden')
                                                $('#dq4').attr('hidden','hidden')
                                          }
                                    }

                                    $('#students').empty()
                                    male = 0
                                    female = 0
                                    $.each(data,function(a,b){
                                          if(male == 0 && b.gender == 'MALE'){
                                                $('#students').append('<tr class="bg-secondary"><th colspan="8">MALE</th></tr>')
                                                male = 1
                                          }else if(female == 0 && b.gender == 'FEMALE'){
                                                $('#students').append('<tr class="bg-secondary"><th colspan="8">FEMALE</th></tr>')
                                                female = 1
                                          }
                                          var qgrade1 = b.qgrade1 != null ? parseInt(b.qgrade1) : '';
                                          var qgrade2 = b.qgrade2 != null ? parseInt(b.qgrade2) : '';
                                          var qgrade3 = b.qgrade3 != null ? parseInt(b.qgrade3) : '';
                                          var qgrade4 = b.qgrade4 != null ? parseInt(b.qgrade4) : '';

                                          var qid1 = b.qid1 != null ? b.qid1 : '';
                                          var qid2 = b.qid2 != null ? b.qid2 : '';
                                          var qid3 = b.qid3 != null ? b.qid3 : '';
                                          var qid4 = b.qid4 != null ? b.qid4 : '';

                                          var gstatus1 = b.gstatus1 == 0 || b.gstatus1 == 3 ? 'input_grades studgrades' : 'studgrades';
                                          var gstatus2 = b.gstatus2 == 0 || b.gstatus2 == 3 ? 'input_grades studgrades' : 'studgrades';
                                          var gstatus3 = b.gstatus3 == 0 || b.gstatus3 == 3 ? 'input_grades studgrades' : 'studgrades';
                                          var gstatus4 = b.gstatus4 == 0 || b.gstatus4 == 3 ? 'input_grades studgrades' : 'studgrades';

                                          var fg = ''
                                          var remarks = ''
                                          var bg = ''
                                          var s1hidden = ''
                                          var s2hidden = ''

                                          if(levelid == 14 || levelid == 15){
                                                if(semid == 1){
                                                      s2hidden = 'hidden="hidden"'
                                                      if(qgrade1 != '' && qgrade2 != ''){
                                                            nums = [qgrade1,qgrade2,qgrade3,qgrade4]
                                                            fg = parseFloat((qgrade1 + qgrade2) / 2).toFixed();
                                                            remarks = fg >= 75 ? 'PASSED' : 'FAILED'
                                                            bg = fg >= 75 ? 'bg-success' : 'bg-danger'
                                                      }
                                                }else{
                                                      s1hidden = 'hidden="hidden"'
                                                      if(qgrade3 != '' && qgrade4 != ''){
                                                            nums = [qgrade1,qgrade2,qgrade3,qgrade4]
                                                            fg = parseFloat((qgrade3 + qgrade4) / 2).toFixed();
                                                            remarks = fg >= 75 ? 'PASSED' : 'FAILED'
                                                            bg = fg >= 75 ? 'bg-success' : 'bg-danger'
                                                      }
                                                }
                                                
                                          }else{
                                                if(qgrade1 != '' && qgrade2 != '' && qgrade3 != '' && qgrade4 != ''){
                                                      nums = [qgrade1,qgrade2,qgrade3,qgrade4]
                                                      fg = parseFloat((qgrade1 + qgrade2 + qgrade3 + qgrade4) / 4).toFixed();
                                                      remarks = fg >= 75 ? 'PASSED' : 'FAILED'
                                                      bg = fg >= 75 ? 'bg-success' : 'bg-danger'
                                                }
                                          }
                                         

                                          $('#students').append('<tr><td>'+b.sid+'</td><td>'+b.student+'</td><td class="text-center '+gstatus1+' '+b.gdcolor1+'" '+s1hidden+' data-quarter="1" data-id="'+qid1+'" data-header="'+b.qheader1+'" data-studid="'+b.studid+'">'+qgrade1+'</td><td class="text-center '+gstatus2+' '+b.gdcolor2+'" '+s1hidden+' data-quarter="2" data-id="'+qid2+'" data-header="'+b.qheader2+'"  data-studid="'+b.studid+'">'+qgrade2+'</td><td class="text-center  '+gstatus3+' '+b.gdcolor3+'" '+s2hidden+' data-quarter="3" data-id="'+qid3+'" data-header="'+b.qheader3+'"  data-studid="'+b.studid+'">'+qgrade3+'</td><td class="text-center  '+gstatus4+' '+b.gdcolor4+'" '+s2hidden+' data-quarter="4" data-header="'+b.qheader4+'" data-id="'+qid4+'"  data-studid="'+b.studid+'">'+qgrade4+'</td><td class="text-center fg '+bg+'" data-studid="'+b.studid+'" >'+fg+'</td><td class="text-center actiontaken '+bg+'" data-studid="'+b.studid+'">'+remarks+'</td></tr>')

                                          $('.studgrades[data-studid="'+b.studid+'"]').attr('is_sp',false)


                                          if(levelid == 14 || levelid == 15){}
                                          else{
                                                var cell = null
                                                if(b.q1 == 0){
                                                      cell = $('.studgrades[data-studid="'+b.studid+'"][data-quarter="1"]')
                                                      cell.removeAttr('class')
                                                      cell.text('')
                                                      // cell.removeAttr('data-studid')
                                                      cell.removeAttr('data-quarter')
                                                      cell.removeAttr('data-id')
                                                      cell.removeAttr('data-header')
                                                      cell.addClass('bg-secondary')
                                                      cell.addClass('studgrades')
                                                      cell.attr('is_sp',true)
                                                }
                                                if(b.q2 == 0){
                                                      cell = $('.studgrades[data-studid="'+b.studid+'"][data-quarter="2"]')
                                                      cell.removeAttr('class')
                                                      cell.text('')
                                                      // cell.removeAttr('data-studid')
                                                      cell.removeAttr('data-quarter')
                                                      cell.removeAttr('data-id')
                                                      cell.removeAttr('data-header')
                                                      cell.addClass('bg-secondary')
                                                      cell.addClass('studgrades')
                                                      cell.attr('is_sp',true)
                                                }
                                                if(b.q3 == 0){
                                                      cell = $('.studgrades[data-studid="'+b.studid+'"][data-quarter="3"]')
                                                      cell.removeAttr('class')
                                                      cell.text('')
                                                      // cell.removeAttr('data-studid')
                                                      cell.removeAttr('data-quarter')
                                                      cell.removeAttr('data-id')
                                                      cell.removeAttr('data-header')
                                                      cell.addClass('bg-secondary')
                                                      cell.addClass('studgrades')
                                                      cell.attr('is_sp',true)
                                                }
                                                if(b.q4 == 0){
                                                      cell = $('.studgrades[data-studid="'+b.studid+'"][data-quarter="4"]')
                                                      cell.removeAttr('class')
                                                      cell.text('')
                                                      // cell.removeAttr('data-studid')
                                                      cell.removeAttr('data-quarter')
                                                      cell.removeAttr('data-id')
                                                      cell.removeAttr('data-header')
                                                      cell.addClass('bg-secondary')
                                                      cell.addClass('studgrades')
                                                      cell.attr('is_sp',true)
                                                }

                                          //      if(cell != null){
                                          //             cell.removeAttr('class')
                                          //             cell.text('')
                                          //             cell.removeAttr('data-studid')
                                          //             cell.removeAttr('data-quarter')
                                          //             cell.removeAttr('data-id')
                                          //             cell.removeAttr('data-header')
                                          //             cell.addClass('bg-secondary')
                                          //      }
                                               
                                          }




                                    })
                              }
                        })

                  }
                  
                  $(document).on('change','#filter_subjects',function(){
                        $('.submit_pending_grades').attr('hidden','hidden')
                        var updated_length = $('.updated').length
                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }
                        $('#students').empty()
                        $("#filter_section").empty()
                        $("#filter_gradelevel").empty()
                
                        var subjid = $(this).val()
                        // var subjdesc = $("#filter_subjects option:selected").text().replace(' <div class="badge badge-warning">Pending</div>','');
                        // var sections = []
                        // $.each(all_sched.filter(x=>x.subjid == subjid && x.optiondisplay == subjdesc),function(a,b){
                        //       var count = sections.filter(x=>x.id == b.sectionid).length
                        //       if(count == 0){
                        //             var pending = b.with_pending ? '<div class="badge badge-warning">Pending</div>':'' 
                        //             if(count == 0){
                        //                   sections.push({
                        //                         'id':b.sectionid,
                        //                         'text': b.sectionname+' '+pending,
                        //                         'html': b.sectionname+' '+pending,
                        //                   })
                        //             }
                        //       }
                        // })
                        // $("#filter_section").empty()
                        // $("#filter_section").append('<option value="">Select Section</option>')
                        // $("#filter_section").select2({
                        //       data: sections,
                        //       allowClear: true,
                        //       placeholder: "Select Section",
                        //       escapeMarkup: function(markup) {
                        //             return markup;
                        //       }
                        // })
                        load_section_select()
                        clear_data()
                  })

                  $(document).on('change','#filter_sy',function(){
                        $('.submit_pending_grades').attr('hidden','hidden')
                        var updated_length = $('.updated').length
                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }
                        $('#students').empty()
                        clear_data()
                        getload()
                  })

                  $(document).on('change','#filter_section',function(){

                        if($(this).val() == ""){
                              $('#download_final_grade').attr('disabled','disabled')
                        }else{
                              $('#download_final_grade').removeAttr('disabled')
                        }

                        $('.submit_pending_grades').attr('hidden','hidden')
                        var updated_length = $('.updated').length
                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }
                        clear_data()
                  })


                  

                  $(document).on('click','.submit_grades',function(){
                        $('#modal_1').modal()
                        $('#btnSubmit').attr('type','all')
                        selected_quarter = $(this).attr('data-quarter')
                  })

                  $(document).on('click','#btnSubmit[type="all"]',function(){

                        var updated_length = $('.updated').length
                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }

                        var id = $(this).attr('data-id')
                        var text  = $(this).text().replace("Submit ","")

                        Swal.fire({
                              title: 'Are you sure you want to<br>submit '+text+'?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Submit '+text
                        }).then((result) => {
                              if (result.value) {

                                    var excluded = []

                                    $('.exclude').each(function(){
                                          if($(this).prop('checked') == false && $(this).attr('data-studid') != undefined){
                                                excluded.push($(this).attr('data-studid'))
                                          }
                                    })

                                    var subjid = $('#filter_subjects').val()
                                    var sectionid = $('#filter_section').val()
                                    var sy = $('#filter_sy').val()
                                    var filter_data = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid)
                                    var levelid = filter_data[0].levelid
                                    var semid = 1

                                    if(levelid == 14 || levelid == 15){
                                          semid = filter_data[0].semid
                                    }
                              
                                    $.ajax({
                                          url: '/gradesSubmit/'+selected_quarter,
                                          type:"GET",
                                          data:{
                                                syid: sy,
                                                gradelevelid: levelid,
                                                section: sectionid,
                                                quarter : selected_quarter,
                                                subjectid: subjid,
                                                dataHolder: 'submit',
                                                semid:semid,
                                                excluded:excluded

                                          },
                                          success:function(data) {
                                          
                                                $('.exclude').each(function(a,b){
                                                      var temp_studid = $(this).attr('data-studid')
                                                      if($(this).prop('checked') == true && $(this).attr('data-studid') != undefined){
                                                            $('.pending_list[data-studid="'+temp_studid+'"]').remove()
                                                      }
                                                })

                                                if(excluded.length == 0){

                                                      var temp_index = all_sched.findIndex(x=>x.subjid == subjid && x.sectionid == sectionid)
                                                      all_sched[temp_index].quarter_pending_perst = all_sched[temp_index].quarter_pending_perst.filter(x=>x!=selected_quarter)
                                                      all_sched[temp_index].with_pending = false

                                                      // load_subject_select(subjid)
                                                      // load_section_select(sectionid)
                                                      view_grades()
                                                      update_sidenav()
                                                      $('#btnSubmit').attr('disabled','disabled')
                                                      $('.exclude').attr('disabled','disabled')
                                                      $('.submit_pending_grades[data-quarter="'+selected_quarter+'"]').attr('hidden','hidden')
                                                }else{
                                                      students()
                                                      $('#btnSubmit').removeAttr('disabled')
                                                      $('.exclude').removeAttr('disabled')
                                                }

                                             

                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Grades submitted successfully!'
                                                })

                                          }
                                    });
                              }
                        })
                       
                  })

                  function update_sidenav(){
                        $.ajax({
                              url: '/teacher/get/pending',
                              type:"GET",
                              success:function(data) {
                                    if(data[0].with_pending){
                                          $('.pending_status_holder').removeAttr('hidden')
                                          if(data[0].student_pending_count > 0 ){
                                                $('.student_pending').removeAttr('hidden')
                                                $('.student_pending').text(data[0].student_pending_count)
                                          }else{
                                                $('#student_pending').attr('hidden','hidden')
                                          }
                                          if(data[0].section_pending_count > 0 ){
                                                $('.section_pending').removeAttr('hidden')
                                                $('.section_pending').text(data[0].section_pending_count)
                                          }else{
                                                $('#section_pending').attr('hidden','hidden')
                                          }
                                    }else{
                                          $('.student_pending').attr('hidden','hidden')
                                          $('.section_pending').attr('hidden','hidden')
                                          $('.pending_status_holder').attr('hidden','hidden')
                                    }
                              }
                        });
                  }

                  $(document).on('change','#filter_section',function(){
                        view_grades()
                  })

                  function view_grades(){
                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()
                        var selected = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )
                        $('.submit_pending_grades').attr('hidden','hidden')

                        if(selected.length == 0){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No results found!'
                              })
                              return false
                        }

                        clear_data()
                        gradestatus()

                        $('#label_gradelevel').text(selected[0].levelname)
                        $('#label_section').text(selected[0].sectionname)
                        $('#label_subject').text(selected[0].subjdesc)
                        $('#label_subjectcode').text(selected[0].subjcode)

                        $('#label_gradelevel_1').text(selected[0].levelname)
                        $('#label_section_1').text(selected[0].sectionname)
                        $('#label_subject_1').text(selected[0].subjdesc)
                        $('#label_subjectcode_1').text(selected[0].subjcode)
                  }

                  function clear_data(){
                        for(var x=1;x<=4;x++){
                              $('.submit_grades[data-quarter="'+x+'"]').attr('hidden','hidden')
                              $('.submit_grades[data-quarter="'+x+'"]').attr('disabled','disabled')
                              $('.label_date[data-quarter="'+x+'"]').text('--')
                              $('.grade_status[data-quarter="'+x+'"]').text('--')
                        }
                        $('#label_gradelevel').text('--')
                        $('#label_section').text('--')
                        $('#label_subject').text('--')
                        $('#label_subjectcode').text('--')
                        $('#students').empty()
                        $('#dq1').removeAttr('hidden')
                        $('#dq2').removeAttr('hidden')
                        $('#dq3').removeAttr('hidden')
                        $('#dq4').removeAttr('hidden')
                  }

                  // const Toast = Swal.mixin({
                  //       toast: true,
                  //       position: 'top-end',
                  //       showConfirmButton: false,
                  //       timer: 2000,
                  // })

                  function enable_filter(){
                        $('#filter_subjects').removeAttr('disabled')
                        $('#filter_sy').removeAttr('disabled')
                        $('#filter_gradelevel').removeAttr('disabled')
                        $('#filter_section').removeAttr('disabled')
                        $('#filter_button_1').removeAttr('disabled')
                        $('.submit_grades').removeAttr('disabled')
                  }
                  
                  $(document).on('click','#save_button_1',function(){
                        var length_width = parseFloat(100 / $('.updated').length)

                        $('#save_button_1')[0].innerHTML = '<i class="fas fa-save"></i> Saving Grades...'
                        $('#save_button_1').removeClass('btn-primary')
                        $('#save_button_1').addClass('btn-secondary')
                        $('#save_button_1').attr('disabled','disabled')
                        $('.progress-bar').css('width','0%')

                        $('.updated').each(function(a,b){
                              var td = $(this)
                              var studid = $(this).attr('data-studid')
                              var headerid = $(this).attr('data-header')
                              var id = $(this).attr('data-id')
                              var qg = $(this).text()
                              $.ajax({
                                    type:'GET',
                                    url: '/teacher/finalgrades/savegrades',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          levelid:$('#filter_gradelevel').val(),
                                          sectionid:$('#filter_section').val(),
                                          quarter:$('#filter_quarter').val(),
                                          subjid:$('#filter_subjects').val(),
                                          id:id,
                                          headerid:headerid,
                                          studid:studid,
                                          qg:qg
                                    },
                                    success:function(data) {
                                          $(td).removeClass('updated')
                                          const element = document.querySelector('.progress-bar')
                                          const width = parseFloat( (element.style.width).replace('%','') )
                                          var temp_width = width + length_width
                                          $('.progress-bar').css('width',temp_width+'%')
                                          if($('.updated').length == 0){
                                                $('#save_button_1').attr('disabled','disabled')
                                                $('#save_button_1').addClass('btn-primary')
                                                $('#save_button_1').removeClass('btn-secondary')
                                                $('#save_button_1')[0].innerHTML = '<i class="fas fa-save"></i> Save Grades'
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Saved Successfully!'
                                                })
                                                enable_filter()
                                          }
                                    },
                                    error:function(){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                          enable_filter()
                                          $('#save_button_1').removeAttr('disabled')
                                          $('#save_button_1').addClass('btn-primary')
                                          $('#save_button_1').removeClass('btn-secondary')
                                          $('#save_button_1')[0].innerHTML = '<i class="fas fa-save"></i> Save Grades'
                                        
                                    }
                              })
                        })
                  })
                  
            })
      </script>

      <script>
            $(document).ready(function () {

                  var isSaved = false;
                  var isvalidHPS = true;
                  var hps = []
                  var currentIndex 
                  var can_edit = true
                  
                  $(document).on('click','.input_grades',function(){
                        if(currentIndex != undefined){
                              if(isvalidHPS){
                                    if(can_edit){
                                          string = $(this).text();
                                          currentIndex = this;
                                          $('#start').length > 0 ? dotheneedful(this) : false
                                          $('td').removeAttr('style');
                                          $('#start').removeAttr('id')
                                          $(this).attr('id','start')
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
                                    var start = document.getElementById('start');
                                                      start.focus();
                                                      start.style.backgroundColor = 'green';
                                                      start.style.color = 'white';

                              }
                        }
                  })


                  function dotheneedful(sibling) {
                        if (sibling != null) {
                              currentIndex = sibling
                              start.focus();
                              start.style.backgroundColor = '';
                              start.style.color = '';
                              sibling.focus();
                              sibling.style.backgroundColor = 'green';
                              sibling.style.color = 'white';
                              start = sibling;
                              $('#message').empty();
                              string = $(currentIndex)[0].innerText
                        }
                  }

                  document.onkeydown = checkKey;

                  function checkKey(e) {
            
                        e = e || window.event;
                        if (e.keyCode == '38' && currentIndex != undefined)  {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.previousElementSibling;
                              $('#curText').text(string)
                              if (nextrow != null) {
                                    var sibling = nextrow.cells[idx];
                                    if(!$(sibling).hasClass('input_grades')){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                              

                        } else if (e.keyCode == '40' && currentIndex != undefined) {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.nextElementSibling;
                              $('#curText').text(string)
                              var sibling = nextrow.cells[idx];
                              if (nextrow != null) {
                                    if(!$(sibling).hasClass('input_grades')){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '37' && currentIndex != undefined) {
                              var sibling = start.previousElementSibling;
                              if(!$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                        } else if (e.keyCode == '39' && currentIndex != undefined) {
                              var sibling = start.nextElementSibling;
                              if(!$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
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
                              $(currentIndex).addClass('updated')
                              $('#save_button_1').removeAttr('disabled')
                              var temp_studid = $(currentIndex).attr('data-studid')
                              disabled_filter()
                              calcfg(temp_studid)
                        }
                        else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                             
                              if( $(currentIndex).text() == 0){
                                    string = ""
                              }

                              string += e.key;
                              if(string > 100){
                                    string = 100 
                              }
                              $(currentIndex).text(string)
                              $(currentIndex).addClass('updated')
                              $('#save_button_1').removeAttr('disabled')
                              $('#curText').text(string)
                              var temp_studid = $(currentIndex).attr('data-studid')
                              disabled_filter()
                              calcfg(temp_studid)
                        }
                  
                  }

                  function disabled_filter(){
                        $('#filter_subjects').attr('disabled','disabled')
                        $('#filter_sy').attr('disabled','disabled')
                        $('#filter_gradelevel').attr('disabled','disabled')
                        $('#filter_section').attr('disabled','disabled')
                        $('#filter_button_1').attr('disabled','disabled')
                        $('.submit_grades').attr('disabled','disabled')
                  }

                  function calcfg(studid){

                        var with_fg = true
                        var temp_sum = parseInt(0)
                        var semid = $('#subject_sem').text()
                        var levelid = $('#subject_levelid').text()

                        if(levelid == 14 || levelid == 15){
                              if(semid == 1){
                                    for(var x=1;x<=2;x++){
                                          $('.studgrades[data-studid="'+studid+'"][data-quarter="'+x+'"]').each(function(a,b){
                                                if($(this).text() == ""){
                                                      with_fg = false
                                                }
                                                temp_sum += parseInt($(this).text())
                                          })
                                    }
                              }else{
                                    for(var x=3;x<=4;x++){
                                          $('.studgrades[data-studid="'+studid+'"][data-quarter="'+x+'"]').each(function(a,b){
                                                if($(this).text() == ""){
                                                      with_fg = false
                                                }
                                                temp_sum += parseInt($(this).text())
                                          })
                                    }
                              }
                              var fg = parseFloat(temp_sum/2).toFixed()
                        }else{

                              var is_sp =  $('.studgrades[data-studid="'+studid+'"][is_sp="true"]').length > 0 ? true : false;

                              if(!is_sp){
                                    $('.studgrades[data-studid="'+studid+'"]').each(function(a,b){
                                          if($(this).text() == ""){
                                                with_fg = false
                                          }
                                          temp_sum += parseInt($(this).text())
                                    })
                                    var fg = parseFloat(temp_sum/4).toFixed()
                              }else{
                                    with_fg = false
                              }

                             
                        }

                       

                       

                        if(with_fg){
                        
                              $('.fg[data-studid="'+studid+'"]').text(fg)
                              $('.actiontaken[data-studid="'+studid+'"]').text(fg >= 75 ? 'PASSED' : 'FAILED')
                              if(fg >= 75){
                                    $('.actiontaken[data-studid="'+studid+'"]').addClass('bg-success')
                                    $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-danger')
                              }else{
                                    $('.actiontaken[data-studid="'+studid+'"]').addClass('bg-danger')
                                    $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-success')
                              }
                        }else{
                              $('.fg[data-studid="'+studid+'"]').text('')
                              $('.actiontaken[data-studid="'+studid+'"]').text('')
                              $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-success')
                              $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-danger')
                        }

                  }


            })

      </script>

@endsection


