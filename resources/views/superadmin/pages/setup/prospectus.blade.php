
@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 14){
            $extend = 'deanportal.layouts.app2';
      }
      else if(Session::get('currentPortal') == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }
      else if(auth()->user()->type == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 14){
            $extend = 'deanportal.layouts.app2';
      }
      else if(auth()->user()->type == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }
@endphp


@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
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
                  border: 0;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection


@section('content')

@php
      $yearlevel = DB::table('gradelevel')
                        ->where('acadprogid',6)
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'levelname as text'
                        )
                        ->get();

      foreach ($yearlevel as $item) {
            $item->text = str_replace(' COLLEGE','',$item->text);
      }

      $semester = DB::table('semester')
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'semester as text'
                        )
                        ->get();

@endphp




<div class="modal fade" id="prospectus_modal" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: .9rem !important">Prospectus: <span id="course_label"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-4 form-group">
                                    <label for="">Curriculum  
                                          <a href="javascript:void(0)" hidden class="edit_curriculum pl-2"><i class="far fa-edit"></i></a>
                                          <a href="javascript:void(0)" hidden class="delete_curriculum pl-2"><i class="far fa-trash-alt text-danger"></i></a>
                                    </label>
                                  <select name="" id="curriculum_filter" class=" form-control select2"></select>
                              </div>
                              <div class="col-md-2 form-group">
                                    <label for="">Year Level</label>
                                    <select name="" id="year_level_filter" class=" form-control select2"></select>
                              </div>
                              <div class="col-md-2 form-group">
                                    <label for="">Semester</label>
                                    <select name="" id="semester_filter" class=" form-control select2"></select>
                              </div>
                              <div class="col-md-4 form-group">
                                    <label for="">Subjects</label>
                                    <select name="" id="subject_filter" class=" form-control select2"></select>
                              </div>
                              {{-- <div class="col-md-4">
                                    <button class="btn btn-primary btn-sm float-right" style="font-size:.7rem !important; margin-top: 31px !important;" >Add Subject</button>
                              </div> --}}
                        </div>
                        <div class="row mb-2">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm print" data-id="prospectus_pdf" disabled style="font-size:.7rem !important"><i class="fas fa-print" ></i> PDF</button>
                                    {{-- <button class="btn btn-primary btn-sm print" data-id="prospectus_excel" disabled><i class=" fa fa-file-excel"></i> Print Excel</button> --}}
                              </div>
                        </div>
                        <div class="row table-responsive " style="height: 450px;">
                              <div class="col-md-12">
                                    @foreach ($yearlevel as $key=>$item)
                                          @if($key == 0)
                                                <hr class="div-holder div-{{$item->id}} mt-0" hidden>
                                          @else
                                                <hr class="div-holder div-{{$item->id}}" hidden>
                                          @endif
                                          @foreach ($semester as $sem_item)
                                                <div class="row div-holder sy-{{$item->id}} sem-{{$sem_item->id}} div-{{$item->id}}-{{$sem_item->id}}" style="font-size:.8rem !important" hidden>
                                                      <div class="col-md-12">
                                                            <table class="table-hover table table-striped table-sm table-bordered" id="table-{{$item->id}}-{{$sem_item->id}}" width="100%" >
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="5%" class="align-middle">Sort</th>
                                                                              <th width="10%" class="align-middle">Code</th>
                                                                              <th width="50%">Subject Description</th>
                                                                              <th width="15%">Prerequisite</th>
                                                                              <th width="5%" class="align-middle text-center p-0">Lect.</th>
                                                                              <th width="5%" class="align-middle text-center p-0">Lab.</th>
                                                                              <th width="5%" class="align-middle"></th>
                                                                              <th width="5%" class="align-middle"></th>
                                                                        </tr>
                                                                  </thead>
                                                            </table>
                                                      </div>
                                                </div>
                                          @endforeach
                                         
                                    @endforeach
                              </div>
                        </div>
                       
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="curriculum_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Curriculum Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                  <label for="">Curriculum Description</label>
                                  <input class="form-control form-control-sm" id="input_curriculum">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="curriculum-f-btn">Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="addsubject_form_modal" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Add Subject Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-3 text-sm">
                                    <strong>Year Level</strong>
                                    <p class="text-muted mb-0" id="sy_label">2</p>
                              </div>
                              <div class="col-md-3 text-sm">
                                    <strong>Semester</strong>
                                    <p class="text-muted mb-0" id="sem_label">2</p>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-primary btn-sm mt-3" style="font-size:.7rem !important" id="reload_list"><i class="fas fa-sync"></i> Reload List</button>
                                    <button class="btn btn-primary btn-sm mt-3" style="font-size:.7rem !important" id="new_subject_btn"><i class="fas fa-plus"></i> New Subject</button>
                                    <button class="btn btn-primary btn-sm mt-3" style="font-size:.7rem !important" id="subjgroup_to_modal"><i class="fas fa-plus"></i> Subject Group</button>
                              </div>
                        </div>
                        <div class="row mt-2" style="font-size:.8rem !important">
                              <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="availsubj_datatable" width="100%" >
                                          <thead>
                                                <tr>
                                                      <th width="7%" class="align-middle text-center p-0">Year Level</th>
                                                      <th width="7%" class="align-middle text-center p-0">Semester</th>
                                                      <th width="7%" class="align-middle">Code</th>
                                                      <th width="41%">Subject Description</th>
                                                      <th width="18%">Subj. Group</th>
                                                      <th width="5%" class="align-middle text-center p-0">Lect.</th>
                                                      <th width="5%" class="align-middle text-center p-0">Lab.</th>
                                                      <th width="4%" class="align-middle text-center p-0"></th>
                                                      <th width="4%" class="align-middle text-center p-0"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   




<div class="modal fade" id="newsubject_form_modal" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">New Subject Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Description</label>
                                    <textarea class="form-control form-control-sm" id="input_subj_desc" rows="2"  placeholder="Subject Description"></textarea>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Code</label>
                                    <input class="form-control form-control-sm" id="input_subj_code"  placeholder="Subject Code">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <label for="">Lecture Units</label>
                                    <input class="form-control form-control-sm" id="input_subj_lecunit" min="1" oninput="this.value=this.value.replace(/[^0-9\.]/g,'');" placeholder="Lecture Units">
                              </div>
                              <div class="col-md-6 form-group">
                                    <label for="">Laboratory Units</label>
                                    <input class="form-control form-control-sm" id="input_subj_labunit" min="1" oninput="this.value=this.value.replace(/[^0-9\.]/g,'');" placeholder="Laboratory Units">
                              </div>
                        </div>
                        <div class="row" id="subjgroup_holder">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Group  
                                          <a href="javascript:void(0)" hidden class="edit_subjgroup pl-2"><i class="far fa-edit"></i></a>
                                          <a href="javascript:void(0)" hidden class="delete_subjgroup pl-2"><i class="far fa-trash-alt text-danger"></i></a>
                                    </label>
                                  <select name="" id="input_subj_group" class=" form-control select2 form-control-sm"></select>
                              </div>
                        </div>
                        <div class="row" id="sort_holder">
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input class="form-control form-control-sm" id="input_subj_sort" rows="2" onkeyup="this.value = this.value.toUpperCase();">
                              </div>
                        </div>
                        <div class="row" id="subj_prereq_holder" hidden>
                              <div class="col-md-12 form-group">
                                    <label for="">Prerequiste Subjects</label>
                                    <select class=" form-control-sm form-control select2" multiple id="subj_prereq"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="newsubj-f-btn">Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="set_subjgroup_form_modal" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Group Assignment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Code</label>
                                    <input class="form-control form-control-sm" id="input_subj_code_holder"  placeholder="Subject Code" disabled>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Description</label>
                                    <textarea class="form-control form-control-sm" id="input_subj_desc_holder" rows="2"  placeholder="Subject Description" disabled></textarea>
                              </div>
                        </div>
                        <div class="row" id="subjgroup_holder">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Group </label>
                                  <select name="" id="input_subj_group_assign" class=" form-control select2 form-control-sm"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" id="subjgroup-assign-f-btn"><i class="fa fa-save"></i> Update</button>
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
                        <h1>Prospectus Setup</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Prospectus Setup</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" style="">
                              <div class="card-body">
                                    <div class="row mt-2">
                                          <div class="col-md-12" style="font-size:.9rem !important">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="college_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="60%">Course Description</th>
                                                                  <th width="20%" class="align-middle">Abbreviation</th>
                                                                  <th width="10%" class="align-middle">Curr. Count</th>
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
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('js/setupjs/college-subjgroup.js') }}"></script>

      
      <script>

            $(document).ready(function(){

                  $(document).on('click','#subjgroup_to_modal',function(){
                        subjgroup_datatable()
                  })

                  var subjgroup_selectedsubj = null

                  subjgroup_select('#input_subj_group')
                  subjgroup_select('#input_subj_group_assign')

                  $(document).on('click','.assign_subjgroup',function(){
                        var temp_id = $(this).attr('data-id')
                        subjgroup_selectedsubj = temp_id
                        var temp_info = all_students.filter(x=>x.id == temp_id)
                        $('#input_subj_code_holder').val(temp_info[0].subjCode)
                        $('#input_subj_desc_holder').val(temp_info[0].subjDesc)
                        $('#input_subj_group_assign').val(temp_info[0].subjgroup).change()
                        console.log(temp_info)
                        $('#set_subjgroup_form_modal').modal()
                  })

                  $(document).on('click','#subjgroup-assign-f-btn',function(){
                        var temp_subjgroup = $('#input_subj_group_assign').val()
                        get_all_subjects(subjgroup_selectedsubj,temp_subjgroup)
                  })


                  function get_all_subjects(subjid, subjgroup){
                      
                        $.ajax({
                              type:'GET',
                              url: '/setup/prospectus/update/subjgroup',
                              data:{
                                    subjid:subjid,
                                    subjgroup:subjgroup,
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          available_subject_datatable()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })
                  }

            })
      </script>

      <script>

            var selected_course = null
            var selected_curri = null
            var yearlevel = @json($yearlevel);
            var semester = @json($semester);
            var course_list = []
            var subject_list = []
            var all_subjects = []
            var subject_prereq = []
            var selected_pid = null

            
            
            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })


            // function get_all_subjects(){
            //       available_subject_datatable()
            //       return false;
            //       $.ajax({
            //             type:'GET',
            //             url: '/setup/prospectus/subjets/all',
            //             data:{
            //                   courseid:selected_course,
            //                   curriculumid:$('#curriculum_filter').val(),
            //             },
            //             success:function(data) {
            //                   all_subjects = data
            //                   available_subject_datatable()
            //             }
            //       })
            // }

            function display_subjects_select(temp_subj){
                  $('#subject_filter').empty()
                  $('#subject_filter').append('<option value="">All</option>')
                  $("#subject_filter").select2({
                        data: temp_subj,
                        allowClear: true,
                        placeholder: "All",
                  })
            }

            function get_subjects(prompt=false){
                  $.ajax({
                        type:'GET',
                        url: '/setup/prospectus/courses/curriculum/subjects',
                        data:{
                              courseid:selected_course,
                              curriculumid:$('#curriculum_filter').val(),
                        },
                        success:function(data) {
                              subject_list = data[0].subjects
                              subject_prereq = data[0].prereq

                              $('#subj_prereq').empty()
                              $("#subj_prereq").select2({
                                    data: subject_list,
                                    theme: 'bootstrap4',
                                    placeholder: "Select Prerequisite",
                              })

                              if(selected_pid != null){
                                    $('#subj_prereq_holder').removeAttr('hidden','hidden')
                                    var check_prereq = subject_prereq.filter(x=>x.subjID == selected_pid)
                                    var prereq = []
                                   
                                    $.each(check_prereq,function(a,b){
                                          prereq.push(b.prereqsubjID)
                                    })

                                    if(prereq.length > 0){
                                          $('#subj_prereq').val(prereq).change()
                                    }else{
                                          $("#subj_prereq").val("").change();
                                    }
                                    
                              }

                              if($('#curriculum_filter').val() == "" || $('#curriculum_filter').val() == null){
                                    prompt = false
                              }

                              if(prompt){
                                    Toast.fire({
                                          type: 'info',
                                          title: subject_list.length+' subject(s) found'
                                    })  
                              }
                              
                              
                              display_subjects_select(subject_list)
                              plot_subjects()
                              available_subject_datatable()
                        }
                  })
            }


            
            $(document).on('click','.delete_subject',function(){
                  var tempsubjid = $(this).attr('data-id')

                  Swal.fire({
                        text: 'Are you sure you want to remove subject?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                  }).then((result) => {
                        if (result.value) {
                              delete_general_subject(tempsubjid)
                        }
                  })
                  
                  
            })

            function delete_general_subject(subjid){
                  $.ajax({
                        type:'GET',
                        url: '/setup/prospectus/subjets/remove',
                        data:{
                              subjid:subjid,
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    available_subject_datatable()
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                        }
                  })
            }

            function available_subject_datatable(){

                  // var temp_subj = all_subjects


                  // $.ajax({
                  //       type:'GET',
                  //       url: '/setup/prospectus/subjets/all',
                  //       data:{
                  //             courseid:selected_course,
                  //             curriculumid:$('#curriculum_filter').val(),
                  //       },
                  //       success:function(data) {
                  //             all_subjects = data
                  //             available_subject_datatable()
                  //       }
                  // })


                
                  

                  $("#availsubj_datatable").DataTable({
                        destroy: true,
                        // data:temp_subj,
                        bInfo: false,
                        autoWidth: false,
                        lengthChange: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: '/setup/prospectus/subjets/all',
                              type: 'GET',
                              data:{
                                    courseid:selected_course,
                                    curriculumid:$('#curriculum_filter').val(),
                              },
                              dataSrc: function ( json ) {
                                    all_students = json.data
                                    return json.data;
                              }
                        },
                        columns: [
                                    { "data": null },
                                    { "data": null },
                                    { "data": "subjCode" },
                                    { "data": "subjDesc" },
                                    { "data": "description" },
                                    { "data": "lecunits" },
                                    { "data": "labunits" },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                         
                                          var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                                          if(temp_subjinfo.length > 0){

                                                temp_subjinfo = temp_subjinfo[0]
                                                var temp_yearlevel = ''
                                                
                                                if(temp_subjinfo.yearID == 17){
                                                      temp_yearlevel = '1ST YEAR';
                                                }else if(temp_subjinfo.yearID == 18){
                                                      temp_yearlevel = '2ND YEAR';
                                                }else if (temp_subjinfo.yearID == 19){
                                                      temp_yearlevel = '3RD YEAR';
                                                }else if (temp_subjinfo.yearID == 20){
                                                      temp_yearlevel = '4TH YEAR';
                                                }else if (temp_subjinfo.yearID == 21){
                                                      temp_yearlevel = '5TH YEAR';
                                                }

                                                $(td)[0].innerHTML = temp_yearlevel
                                          }else{
                                                $(td)[0].innerHTML = null
                                          }
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                                          if(temp_subjinfo.length > 0){

                                                temp_subjinfo = temp_subjinfo[0]

                                                var temp_semester = ''
                                                
                                                if(temp_subjinfo.semesterID == 1){
                                                      temp_semester = '1st Sem.';
                                                }else if(temp_subjinfo.semesterID == 2){
                                                      temp_semester = '2nd Sem.';
                                                }else if (temp_subjinfo.semesterID == 3){
                                                      temp_semester = 'Summer';
                                                }

                                                $(td)[0].innerHTML = temp_semester
                                          }else{
                                                $(td)[0].innerHTML = null
                                          }
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              // {
                              //       'targets': 2,
                              //       'orderable': true, 
                              //       'createdCell':  function (td, cellData, rowData, row, col) {

                              //             // var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                              //             // if(temp_subjinfo.length > 0){
                                              
                              //             //       var text = rowData.subjDesc';
                              //             //       $(td)[0].innerHTML = text
                              //             // }else{
                              //             //       $(td)[0].innerHTML = '<span class="all_subj_info" id="'+rowData.id+'">'+rowData.subjDesc+'</span>'
                              //             // }

                              //             var text = rowData.subjDesc;
                              //             $(td)[0].innerHTML = text
                              //             $(td).addClass('align-middle')
                              //       }
                              // },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.description == null){
                                                $(td)[0].innerHTML = '<a href="javascript:void(0)" class="assign_subjgroup text-danger" data-id="'+rowData.id+'">Not Assigned</a>'
                                          }else {
                                                $(td)[0].innerHTML = '<a href="javascript:void(0)" class="assign_subjgroup" data-id="'+rowData.id+'">'+rowData.description+'</a>'
                                          }
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
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                             
                              {
                                    'targets': 7,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(subject_list.filter(x=>x.subjectID == rowData.id).length > 0){
                                                $(td)[0].innerHTML = ''
                                          }else{
                                                var buttons = '<a href="javascript:void(0)" class="add_subject_to_prospectus" data-id="'+rowData.id+'"><i class="fas fa-plus text-primary"></i></a>';
                                                $(td)[0].innerHTML = buttons
                                          }
                                         
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 8,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var buttons = '<a href="javascript:void(0)" class="delete_subject" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                          $(td)[0].innerHTML = buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              
                        ]
                        
                  });

                  var label_text = $($("#availsubj_datatable_wrapper")[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<label for="" class="mb-0 pt-2">Available Subjects</label>'

            }

            $('#curriculum_filter').empty()
            $('#curriculum_filter').append('<option value="">Select curriculum</option>')
            $('#curriculum_filter').append('<option value="add">Add curriculum</option>')
            $("#curriculum_filter").select2({
                  data: [],
                  allowClear: true,
                  placeholder: "Select curriculum",
            })
          

            function get_curriculum(prompt=false){
                  $.ajax({
                        type:'GET',
                        url: '/setup/prospectus/courses/curriculum',
                        data:{
                              courseid:selected_course    
                        },
                        success:function(data) {
                              $('#curriculum_filter').empty()
                              $('#curriculum_filter').append('<option value="">Select curriculum</option>')
                              $('#curriculum_filter').append('<option value="add">Add curriculum</option>')
                              $("#curriculum_filter").select2({
                                    data: data,
                                    allowClear: true,
                                    placeholder: "Select curriculum",
                              })
                              if(selected_curri != null){
                                    $('#curriculum_filter').val(selected_curri).change()
                              }
                              if(prompt){
                                    Toast.fire({
                                          type: 'info',
                                          title: data.length+' curriculum(s) found.'
                                    })
                              }
                        }
                  })
            }

            function plot_subjects(){

                  var temp_subjid = $('#subject_filter').val()

                  $.each(yearlevel,function(a,b){
                        
                        $.each(semester,function(c,d){

                              if(temp_subjid != null && temp_subjid != ""){
                                    var temp_subjects = subject_list.filter(x=>x.yearID == b.id && x.semesterID == d.id && x.id == temp_subjid)
                              }else{
                                    var temp_subjects = subject_list.filter(x=>x.yearID == b.id && x.semesterID == d.id)
                              }

                              

                              var lecunits = 0
                              var labunits = 0

                              $.each(temp_subjects,function(e,f){
                                    lecunits += parseFloat(f.lecunits)
                                    labunits += parseFloat(f.labunits)
                              })

                              temp_subjects.push({
                                    'psubjsort':'Z999',
                                    'subjDesc':'',
                                    'subjCode':'Z999',
                                    'lecunits':lecunits,
                                    'labunits':labunits,
                                    'id':'total'
                              })
                             
                              if($('#curriculum_filter').val() == null || $('#curriculum_filter').val() == "add" || $('#curriculum_filter').val() == ""){}
                              else{
                                    temp_subjects.push({
                                          'psubjsort':'Z998',
                                          'subjDesc':'',
                                          'subjCode':'Z998',
                                          'lecunits':'',
                                          'labunits':'',
                                          'yearID':b.id,
                                          'semesterID':d.id,
                                          'id':'addnew'
                                    })
                              }

                              if($('#year_level_filter').val() == "" && $('#semester_filter').val() == ""){
                                    $(".div-"+b.id+'-'+d.id).removeAttr('hidden')
                                    $(".div-"+b.id).removeAttr('hidden')
                              }else if($('#year_level_filter').val() != "" && $('#semester_filter').val() != ""){
                                    $(".div-"+$('#year_level_filter').val()+'-'+$('#semester_filter').val()).removeAttr('hidden')
                              }else if($('#year_level_filter').val() != ""){
                                    $(".sy-"+$('#year_level_filter').val()).removeAttr('hidden')
                              }else if($('#semester_filter').val() != ""){
                                    $(".div-"+b.id).removeAttr('hidden')
                                    $(".sem-"+$('#semester_filter').val()).removeAttr('hidden')
                              }

                              if(temp_subjid != null && temp_subjid != ""){
                                    if(temp_subjects.length - 2 > 0){
                                          $(".div-"+b.id+'-'+d.id).removeAttr('hidden')
                                    }else{
                                          if(d.id == 3){
                                                $(".div-"+b.id).attr('hidden','hidden')
                                          }
                                          $(".div-"+b.id+'-'+d.id).attr('hidden','hidden')
                                    }
                              }

                              $("#table-"+b.id+'-'+d.id).DataTable({
                                    destroy: true,
                                    data:temp_subjects,
                                    paging: false,
                                    bInfo: false,
                                    lengthChange: false,
                                    columns: [
                                                { "data": "psubjsort" },
                                                { "data": "subjCode" },
                                                { "data": "subjDesc" },
                                                { "data": null },
                                                { "data": "lecunits" },
                                                { "data": "labunits" },
                                                { "data": null },
                                                { "data": null },
                                          ],
                                    columnDefs: [
                                          {
                                                'targets': 0,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.id == 'total'){
                                                            $(td).text(null)
                                                      }else if(rowData.id == 'addnew'){
                                                            $(td).text(null)
                                                      }
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.id == 'total'){
                                                            $(td).text(null)
                                                      }else if(rowData.id == 'addnew'){
                                                            var buttons = '<a href="javascript:void(0)" class="add_subject" data-yearlevel="'+rowData.yearID+'" data-sem="'+rowData.semesterID+'"><i class="fas fa-plus"></i> Add Subject</a>';
                                                            $(td)[0].innerHTML =  buttons
                                                      }

                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.id == 'total'){
                                                            $(td)[0].innerHTML = '<b>Total</b>'
                                                            $(td).addClass('text-right')
                                                            $(td).addClass('pr-3')
                                                      }

                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var check_prereq = subject_prereq.filter(x=>x.subjID == rowData.id)

                                                      if(check_prereq.length > 0){
                                                            var text = ''
                                                            $.each(check_prereq,function(a,b){
                                                                  var temp_subj_info = subject_list.filter(x=>x.id == b.prereqsubjID)
                                                                  if(temp_subj_info.length  > 0){
                                                                              text += temp_subj_info[0].subjCode
                                                                        if(check_prereq.length - 1 != a){
                                                                              text += ', ' 
                                                                        } 
                                                                  }
                                                                 
                                                            })
                                                            $(td).text(text)
                                                      }else{
                                                            $(td).text(null)
                                                      }

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
                                                      if(rowData.id != 'total' && rowData.id != 'addnew'){
                                                            var buttons = '<a href="javascript:void(0)" class="edit_prospectus" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('align-middle')
                                                      }else{
                                                            $(td)[0].innerHTML =  null
                                                      }
                                                }
                                          },
                                          {
                                                'targets': 7,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.id != 'total' && rowData.id != 'addnew'){
                                                            var buttons = '<a href="javascript:void(0)" class="delete_prospectus" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('align-middle')
                                                      }else{
                                                            $(td)[0].innerHTML =  null
                                                      }
                                                }
                                          },
                                    ]
                                    
                              });

                              var label_text = $($("#table-"+b.id+'-'+d.id+'_wrapper')[0].children[0])[0].children[0]
                              $(label_text)[0].innerHTML = '<label for="" class="mb-0 pt-2">'+b.text+' - '+d.text+'</label>'
                        
                        })
                  })


                  }

      </script>
      

      <script>
            //curriculum
            $(document).ready(function(){

                  $(document).on('click','.print',function(){
                        console.log($('#input_curriculum').val())
                        window.open('/setup/prospectus/courses/print?filetype='+$(this).attr('data-id')+'&curriculumid='+$('#curriculum_filter').val()+'&courseid='+selected_course, '_blank');
                  })

                  display_subjects_select([])

                  $(document).on('change','#curriculum_filter',function(){
                        $('.edit_curriculum').attr('hidden','hidden')
                        $('.delete_curriculum').attr('hidden','hidden')
                        
                        if($(this).val() == "add"){
                              $('#curriculum-f-btn').text('Create')
                              $('#curriculum-f-btn').removeClass('btn-success')
                              $('#curriculum-f-btn').addClass('btn-primary')
                              $('#input_curriculum').val("").change()
                              $('#curriculum_form_modal').modal()
                              $('#curriculum_filter').val("").change()

                              $('.print').attr('disabled','disabled')


                              selected_curri = null
                              subject_list = []
                              plot_subjects()
                        }else if($(this).val() != ""){
                              selected_curri = $(this).val()
                              $('.edit_curriculum').removeAttr('hidden')
                              $('.delete_curriculum').removeAttr('hidden')
                              $('.print').removeAttr('disabled')
                              get_subjects(true)
                        }
                  })

                   
                  $(document).on('click','#curriculum-f-btn',function(){
                        if($('#input_curriculum').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Curriculum Name is empty'
                              })
                              return false;
                        }
                        if(selected_curri == null){
                              create_curriculum()
                        }else{
                              update_curriculum()
                        }
                       
                  })

                  $(document).on('click','.delete_curriculum',function(){
                        Swal.fire({
                              text: 'Are you sure you want to remove curriculum?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    delete_curriculum()
                              }
                        })
                  })

                  $(document).on('click','.edit_curriculum',function(){
                        $('#input_curriculum').val($( "#curriculum_filter option:selected" ).text())
                        $('#curriculum-f-btn').text('Update')
                        $('#curriculum-f-btn').addClass('btn-success')
                        $('#curriculum-f-btn').removeClass('btn-primary')
                        $('#curriculum_form_modal').modal()
                  })

                  function create_curriculum(){
                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/courses/curriculum/create',
                              data:{
                                    courseid:selected_course,
                                    curriculumname:$('#input_curriculum').val()
                              },
					success:function(data) {
                                    if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          get_curriculum()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }

                  function delete_curriculum(){
                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/courses/curriculum/delete',
                              data:{
                                    courseid:selected_course,
                                    id:$('#curriculum_filter').val()
                              },
					success:function(data) {
                                    if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          get_curriculum()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }

                  function update_curriculum(){
                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/courses/curriculum/update',
                              data:{
                                    courseid:selected_course,
                                    id:$('#curriculum_filter').val(),
                                    curriculumname:$('#input_curriculum').val()
                              },
					success:function(data) {
                                    if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          get_curriculum()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }

            })
            //curriculum
      </script>

      <script>
            //prospectus

            $(document).ready(function(){
                 
                  var available_subj = []
                  var temp_yearid = null
                  var temp_semid = null
                  var temp_subjid = null

                  $(document).on('click','#reload_list',function(){
                        available_subject_datatable()
                  })

                  $(document).on('click','.add_subject',function(){

                        var yearid = $(this).attr('data-yearlevel')
                        var semid = $(this).attr('data-sem')

                        $(this).attr('data-type')

                        if($(this).attr('data-type') == 'list'){
                              sydesc = ""
                              temp_yearid = null
                              semdesc = ""
                              temp_semid = null
                              $('.add_subject_to_prospectus').attr('hidden','hidden')
                        }else{
                              var sydesc = yearlevel.filter(x=>x.id == yearid)[0].text
                              temp_yearid = yearid
                              var semdesc = semester.filter(x=>x.id == semid)[0].text 
                              temp_semid = semid
                              $('.add_subject_to_prospectus').removeAttr('hidden')
                        }
                       
                        $('#sy_label').text(sydesc)
                        $('#sem_label').text(semdesc)
                        $('#subj_prereq_holder').attr('hidden','hidden')

                        available_subject_datatable()
                        $('#addsubject_form_modal').modal()
                  })

                  $(document).on('click','#new_subject_btn',function(){
                        selected_pid = null
                        $('#sort_holder').attr('hidden','hidden')
                        $('#subjgroup_holder').removeAttr('hidden')
                        $('#input_subj_desc').val("")
                        $('#input_subj_code').val("")
                        $('#input_subj_labunit').val("")
                        $('#input_subj_lecunit').val("")
                        $('#newsubj-f-btn').text('Create')
                        $('#newsubj-f-btn').addClass('btn-primary')
                        $('#newsubj-f-btn').removeClass('btn-success')
                        $('#newsubject_form_modal').modal()
                  })

                  $(document).on('click','.delete_prospectus',function(){
                        selected_pid = $(this).attr('data-id') 
                        Swal.fire({
                              text: 'Are you sure you want to remove subject?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    delete_prospectus()
                              }
                        })
                       
                  })

                  $(document).on('click','.add_subject_to_prospectus',function(){
                   
                        
                        if($('#curriculum_filter').val() == ""){
                              
                              Toast.fire({
                                    type: 'warning',
                                    title: "No Course Selected"
                              })

                              return false
                        }

                        temp_subjid = $(this).attr('data-id') 
                        $(this).attr('hidden','hidden')

                        add_subject_to_prospectus()
                  })

                  $(document).on('click','#newsubj-f-btn',function(){
                        if($('#input_subj_desc').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Subject Description is empty"
                              })
                              return false
                        }
                        if($('#input_subj_code').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Subject Code is empty"
                              })
                              return false
                        }

                   

                        if(selected_pid == null){
                              add_new_subject()
                        }else{
                              update_subject()
                        }
                        
                  })


                  $(document).on('click','.edit_prospectus',function(){
                        selected_pid = $(this).attr('data-id') 
                        $('#newsubj-f-btn').text('Update')
                        $('#newsubj-f-btn').removeClass('btn-primary')
                        $('#newsubj-f-btn').addClass('btn-success')
                        var temp_pid = $(this).attr('data-id')
                        var temp_subject = subject_list.filter(x=>x.id == temp_pid)
                        $('#input_subj_desc').val(temp_subject[0].subjDesc)
                        $('#input_subj_code').val(temp_subject[0].subjCode)
                        $('#input_subj_labunit').val(temp_subject[0].labunits)
                        $('#input_subj_lecunit').val(temp_subject[0].lecunits)
                        $('#input_subj_sort').val(temp_subject[0].psubjsort)
                        $('#subjgroup_holder').attr('hidden','hidden')
                        $('#sort_holder').removeAttr('hidden')

                        $('#input_subj_desc').attr('readonly','readonly')
                        $('#input_subj_code').attr('readonly','readonly')
                        $('#input_subj_lecunit').attr('readonly','readonly')
                        $('#input_subj_labunit').attr('readonly','readonly')

                        $('#subj_prereq_holder').removeAttr('hidden','hidden')
                        var check_prereq = subject_prereq.filter(x=>x.subjID == selected_pid)
                        var prereq = []
                        $.each(check_prereq,function(a,b){
                              prereq.push(b.prereqsubjID)
                        })

                        if(prereq.length > 0){
                              $('#subj_prereq').val(prereq).change()
                        }else{
                              $("#subj_prereq").val("").change();
                        }
                        
                        $('#newsubject_form_modal').modal()
                  })


                  
                  
                  function add_new_subject(){
                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/subjets/new',
                              data:{
                                    subjgroup:$('#input_subj_group').val(),
                                    subjdesc:$('#input_subj_desc').val(),
                                    subjcode:$('#input_subj_code').val(),
                                    labunit:$('#input_subj_labunit').val(),
                                    lecunit:$('#input_subj_lecunit').val(),
                                    curriculumid:$('#curriculum_filter').val(),
                                    semid:temp_semid,
                                    levelid:temp_yearid,
                                    courseid:selected_course,
                              },
					success:function(data) {
						if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          subject_list = subject_list.filter(x=>x.id != selected_pid)
                                          available_subject_datatable()
                                          get_subjects()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }

                  function update_subject(){

                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/subjets/update',
                              data:{
                                    // subjgroup:$('#input_subj_group').val(),
                                    pid:selected_pid,
                                    // subjdesc:$('#input_subj_desc').val(),
                                    // subjcode:$('#input_subj_code').val(),
                                    // labunit:$('#input_subj_labunit').val(),
                                    // lecunit:$('#input_subj_lecunit').val(),
                                    curriculumid:$('#curriculum_filter').val(),
                                    sort:$('#input_subj_sort').val(),
                                    semid:temp_semid,
                                    levelid:temp_yearid,
                                    courseid:selected_course,
                                    prereq:$('#subj_prereq').val(),
                              },
					success:function(data) {
						if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          available_subject_datatable()
                                          get_subjects()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }

                  

                  function add_subject_to_prospectus(){

                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/add',
                              data:{
                                    curriculumid:$('#curriculum_filter').val(),
                                    semid:temp_semid,
                                    levelid:temp_yearid,
                                    courseid:selected_course,
                                    subjid:temp_subjid
                                    
                              },
					success:function(data) {
						if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          // subject_list = subject_list.filter(x=>x.id != selected_pid)
                                          // $('.all_subj_info[id="'+temp_subjid+'"]')[0].innerHTML = $('.all_subj_info[id="'+temp_subjid+'"]').text() + '<span class="text-primary"> ( '+$('#sy_label').text() + ' : '+ $('#sem_label').text() +' )</span>'

                                          $('.add_subject_to_prospectus[data-id="'+temp_subjid+'"]').remove()
                                          subject_list.push(data[0].data)
                                          plot_subjects()
                                          available_subject_datatable()
                                          display_subjects_select(subject_list)
                                          // get_subjects()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})

                  }

                  function delete_prospectus(){
                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/delete',
                              data:{
                                    curriculumid:$('#curriculum_filter').val(),
                                    id:selected_pid
                              },
					success:function(data) {
						if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          subject_list = subject_list.filter(x=>x.id != selected_pid)
                                          display_subjects_select(subject_list)
                                          plot_subjects()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }
            })

            //prospectus
      </script>

      <script>
            $(document).ready(function(){

                  $('#year_level_filter').empty()
                  $('#year_level_filter').append('<option value="">All</option>')
                  $("#year_level_filter").select2({
                        data: yearlevel,
                        allowClear: true,
                        placeholder: "All",
                  })

                  $('#semester_filter').empty()
                  $('#semester_filter').append('<option value="">All</option>')
                  $("#semester_filter").select2({
                        data: semester,
                        allowClear: true,
                        placeholder: "All",
                  })

                  load_course_datatable()
                  get_course()

                  $(document).on('change','#year_level_filter , #semester_filter ',function(){
                        $('.div-holder').attr('hidden','hidden')

                        var temp_subjlist = subject_list

                        if($('#year_level_filter').val() != ""){
                              temp_subjlist = temp_subjlist.filter(x=>x.yearID == $('#year_level_filter').val())
                        }
                        if($('#semester_filter').val() != ""){
                              temp_subjlist = temp_subjlist.filter(x=>x.semesterID == $('#semester_filter').val())
                        }

                        display_subjects_select(temp_subjlist)

                        plot_subjects()
                  })

                  $(document).on('change','#subject_filter',function(){
                        plot_subjects()
                  })

                  $(document).on('click','.view_prospetus',function(){
                        selected_curri = null
                        $('.edit_curriculum').attr('hidden','hidden')
                        $('.delete_curriculum').attr('hidden','hidden')
                        selected_course = $(this).attr('data-id')
                        var temp_course_info = course_list.filter(x=>x.id == selected_course)
                        $('#course_label').text(temp_course_info[0].courseDesc)
                        $('#prospectus_modal').modal()
                        subject_list = []
                        $('.div-holder').attr('hidden','hidden')
                        $('#curriculum_filter').empty();
                        // available_subject_datatable()
                        plot_subjects()
                        get_curriculum(true)
                  })

                  function get_course(){
                        $.ajax({
					type:'GET',
					url: '/setup/prospectus/courses',
                             
					success:function(data) {
						course_list = data
                                    Toast.fire({
                                          type: 'success',
                                          title: course_list.length+' courses found.'
                                    })
                                    load_course_datatable()
					}
				})
                  }
                  
                  function load_course_datatable(){
                        $("#college_datatable").DataTable({
                              destroy: true,
                              data:course_list,
                              lengthChange : false,
                              columns: [
                                          { "data": "courseDesc" },
                                          { "data": "courseabrv" },
                                          { "data": "curriculumcount" },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)"  class="view_prospetus" data-id="'+rowData.id+'">'+rowData.courseDesc+'</a>';
                                                $(td)[0].innerHTML =  buttons
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                          }
                                    },
                              ]
                              
                        });

                        var label_text = $($('#college_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary add_subject" data-type="list">Subject List</button>'

                  }

                  
            })
      </script>

      {{-- IU --}}
      <script>

            $(document).ready(function(){

                  var keysPressed = {};

                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/26/2021 16:34'
                                    })
                        }
                  });

                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });


                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $(document).on('input','#per',function(){
                        if($(this).val() > 100){
                              $(this).val(100)
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Subject percentage exceeds 100!'
                              })
                        }
                  })
            })
      </script>

@endsection


