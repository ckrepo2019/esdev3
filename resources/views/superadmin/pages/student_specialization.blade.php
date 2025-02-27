

@php

      if(!Auth::check()){
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }else if($check_refid->refid == 22){
                        $extend = 'principalcoor.layouts.app2';
                  }else{
                        $extend = 'general.defaultportal.layouts.app';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
            }
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
      $sy = DB::table('sy')
                  ->orderBy('sydesc','desc')
                  ->get(); 
@endphp

<div class="modal fade" id="assign_student_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Assign Student</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-5 form-group">
                                    <label for="">Subject</label>
                                    <select class="form-control select2" id="input_subject_assign">
                                          <option value="">Select a subject</option>
                                    </select>
                              </div>
                              <div class="col-md-3 form-group mb-0">
                                    <label for="">Grade</label>
                                    <select class="form-control select2" id="input_subject_assign_grade" disabled>
                                          <option value="">All</option>
                                    </select>
                              </div>
                              <div class="col-md-4 form-group mb-0">
                                    <label for="">Section</label>
                                    <select class="form-control select2" id="input_subject_assign_section" disabled>
                                          <option value="">All</option>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" hidden id="add_all_student">Add All</button>
                              </div>
                        </div>
                        <div class="row mt-3" style="font-size:11px !important">
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered" id="student_assign_list" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="50%">Student Name</th>
                                                      <th width="50%">Section/Grade</th>
                                                      <th width="50%">Action</th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div> 

<div class="modal fade" id="student_assign" tabindex="-1" role="dialog" aria-labelledby="student_assign_label" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="student_assign_label">Assign Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                        </button>
                  </div>
                  <div class="modal-body">
                        <div class="form-group">
                             
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <label for="student_name">Student Name:</label> <span id="studname"></span>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-sm table-bordered">
                                          <thead>
                                                <tr>
                                                      <th width="50%">Subject</th>
                                                      <th width="10%" class="text-center">Q1</th>
                                                      <th width="10%" class="text-center">Q2</th>
                                                      <th width="10%" class="text-center">Q3</th>
                                                      <th width="10%" class="text-center">Q4</th>
                                                      <th width="10%" class="text-center">All</th>
                                                </tr>
                                          </thead>
                                          <tbody id="perstudentsubj">

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
            <div class="row">
                  <div class="col-sm-6">
                        <h1> Student Specialization <h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Specialization</li>
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
                                                      <div class="col-md-2 ">
                                                            <label for="">School Year</label>
                                                            <select class="form-control select2" id="filter_schoolyear">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <label for="">Subject</label>
                                                            <select class="form-control select2" id="filter_subject">
                                                                  <option value="">Select a subject</option>
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2">
                                                            <label for="">Grade Level</label>
                                                            <select class="form-control select2" id="filter_gradelevel">
                                                            
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3">
                                                            <label for="">Section</label>
                                                            <select class="form-control select2" id="filter_section">
                                                            
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
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-sm table-bordered" id="student_specialization" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="30%">Learner</th>
                                                                  <th width="30%">Section / Grade Level</th>
                                                                  <th width="7.5%" class="text-center">Q1</th>
                                                                  <th width="7.5%" class="text-center">Q2</th>
                                                                  <th width="7.5%" class="text-center">Q3</th>
                                                                  <th width="7.5%" class="text-center">Q4</th>
                                                                  <th width="10%"></th>
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
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

      {{-- functions --}}
      <script>

            function get_subjects(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/subjects',
                        data:{
                              syid:$('#filter_schoolyear').val()
                        },
                        success:function(data) {
                              all_subjects = data
                              $.each( data[0].subjects,function(a,b){
                                    var subj_num = 'S'+('000'+b.id).slice (-3)
                                    b.text = subj_num + ' - ('+b.subjcode+') ' + b.text
                              })
                              $("#filter_subject").empty()
                              $("#filter_subject").append('<option value="">All</option>')
                              $("#filter_subject").select2({
                                    data: all_subjects[0].subjects,
                                    allowClear: true,
                                    placeholder: "All",
                              })


                              $("#input_subject_assign").empty()
                              $("#input_subject_assign").append('<option value="">Select a subject</option>')
                              $("#input_subject_assign").select2({
                                    data: all_subjects[0].subjects,
                                    allowClear: true,
                                    placeholder: "Select a subject",
                              })
                        }
                  })
            }

            function student_specialization_delete(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/delete',
                        data:{
                              id:selectedstudid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].data
                                    })
                                    get_subjects_studspec()
                              }else{
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].data
                                    })
                              }
                        }
                  })
            }



            


            function student_specialization_create_all(sectionid,levelid,subjid,syid,q1,q2,q3,q4){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/create/all',
                        data:{
                              subjid:subjid,
                              syid:syid,
                              q1:q1,
                              q2:q2,
                              q3:q3,
                              q4:q4,
                              sectionid:sectionid,
                              levelid:levelid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Added Successfully!'
                                    })
                                    get_subjects_studspec()
                              }else{
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].data
                                    })
                              }
                              $('.tlesubj').removeAttr('disabled')
                        }
                  })
            }
            
            function student_specialization_create(studid,subjid,syid,q1,q2,q3,q4){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/create',
                        data:{
                              subjid:subjid,
                              syid:syid,
                              q1:q1,
                              q2:q2,
                              q3:q3,
                              q4:q4,
                              studid:studid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Added Successfully!'
                                    })
                                    get_subjects_studspec()
                              }else{
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].data
                                    })
                              }
                              $('.tlesubj').removeAttr('disabled')
                        }
                  })
            }

            function get_subjects_studspec(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/data',
                        data:{
                              subjid:$('#filter_subject').val(),
                              levelid:$('#filter_gradelevel').val(),
                              sectionid:$('#filter_section').val(),
                              syid:$('#filter_schoolyear').val()
                        },
                        success:function(data) {
                              subjects_studspec = data
                              loaddatatable()
                        }
                  })
            }

            function filter_section(inputid,subjid,levelid){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/section',
                        data:{
                              syid:$('#filter_schoolyear').val(),
                              subjid:subjid,
                              levelid:levelid
                        },
                        success:function(data) {
                              $(inputid).empty()
                              $(inputid).append('<option value="">All<option>')
                              $(inputid).select2({
                                    data: data,
                                    allowClear: true,
                                    placeholder: "All",
                              })

                        }
                  })
            }

            function gradelevel_list(inputid,subjid){

                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/subjects/gradelevel',
                        data:{
                              syid:$('#filter_schoolyear').val(),
                              subjid:subjid
                        },
                        success:function(data) {
                              $(inputid).empty()
                              $(inputid).append('<option value="">All<option>')
                              $(inputid).select2({
                                    data: data,
                                    allowClear: true,
                                    placeholder: "All",
                              })
                        }
                  })
            }

            function student_assign_datatable(filteredstudents){                     

                  $("#student_assign_list").DataTable({
                        destroy: true,
                        data:filteredstudents,
                        lengthChange: false,
                        columns: [
                              { "data": null },
                              { "data": null },
                              { "data": "search"}
                        ],
                        columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      var tempmiddle = rowData.middlename != null ? ' '+rowData.middlename : ''

                                                      var text = '<a class="mb-0">'+rowData.student+"&nbsp;"+tempmiddle+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var text = '<a class="mb-0">'+rowData.sectionname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>';
                                                      $(td)[0].innerHTML =  text
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var buttons = '<a href="#" class="add_student_assign" data-id="'+rowData.studid+'">Add</a>';
                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                    ]
                  });

                  var check_text = ''
                  for(var x = 1 ; x <= 4 ; x++){
                        check_text += `<div class="col-md-3 form-group mb-0">
                                          <div class="icheck-primary d-inline pt-2">
                                                <input type="checkbox" id="q`+x+`" checked class="form-control">
                                                <label for="q`+x+`">Quarter `+x+`</label>
                                          </div>
                                    </div>`
                  }

                  var label_text = $($("#student_assign_list_wrapper")[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = `<div class="row">`+check_text+`</div>`

            }

            function get_students_assign(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/student/specialization/students',
                        data:{
                              syid:$('#filter_schoolyear').val(),
                              levelid:$('#input_subject_assign_grade').val(),
                              subjid:$('#input_subject_assign').val(),
                              sectionid:$('#input_subject_assign_section').val()
                        },
                        success:function(data) {
                              all_students = data

                              $('#add_all_student').removeAttr('hidden')
                              student_assign_datatable(data)  
                        }
                  })
            }

            function filter_change_grade(grade){
                  subjects_studspec = subjects_studspec.filter(x=>x.grade == grade)
                  loaddatatable();
            }

            function loaddatatable(){

                  $.each(subjects_studspec,function (a,b) {
                        all_students = all_students.filter(x=>x.studid != b.studid)
                  })

                  $.each(all_students,function(a,b){
                        b.text = b.lastname + ', '+b.firstname
                        b.id = b.studid
                  })

                  $("#filter_student").select2({
                        data: all_students,
                        placeholder: "Select a student",
                  })

                  $("#student_specialization").DataTable({
                              destroy: true,
                              data:subjects_studspec,
                              order: [[0, 'asc']],
                              stateSave: true,
                              lengthChange: false,
                              responsive: true,
                              columns: [
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                                    { "data": "search"}
                              ],

                              columnDefs: [
                                                {
                                                      'targets': 0,
                                                      'orderable': true, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                            $(td)[0].innerHTML =  text
                                                      
                                                      }
                                                },
                                                {
                                                      'targets': 1,
                                                      'orderable': true, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            var text = '<a class="mb-0">'+rowData.sectionname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>';
                                                            $(td)[0].innerHTML =  text
                                                      
                                                      }
                                                },
                                                {
                                                      'targets': 2,
                                                      'orderable': false, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            
                                                            if(rowData.q1 == 0){
                                                                  var text = 'N/S'   
                                                                  $(td).addClass('bg-danger')
                                                            }else{
                                                                  var text = '<a class="mb-0">'+rowData.subjtext1+'</a>'
                                                            }
                                                            $(td)[0].innerHTML = text
                                                            $(td).addClass('align-middle')
                                                            $(td).addClass('text-center')
                                                      }
                                                },
                                                {
                                                      'targets': 3,
                                                      'orderable': false, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            if(rowData.q2 == 0){
                                                                  var text = 'N/S'    
                                                                  $(td).addClass('bg-danger')
                                                            }else{
                                                            var text = '<a class="mb-0">'+rowData.subjtext2+'</a>'
                                                            }
                                                            $(td)[0].innerHTML = text
                                                      
                                                            $(td).addClass('align-middle')
                                                            $(td).addClass('text-center')
                                                      }
                                                },
                                                {
                                                      'targets': 4,
                                                      'orderable': false, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            if(rowData.q3 == 0){
                                                                  var text = 'N/S'      
                                                                  $(td).addClass('bg-danger') 
                                                            }else{
                                                            var text = '<a class="mb-0">'+rowData.subjtext3+'</a>'
                                                            }
                                                      
                                                            $(td)[0].innerHTML = text
                                                            
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('align-middle')
                                                      }
                                                },
                                                {
                                                      'targets': 5,
                                                      'orderable': false, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            if(rowData.q4 == 0){
                                                                  var text = 'N/S'    
                                                                  $(td).addClass('bg-danger')
                                                            }else{
                                                            var text = '<a class="mb-0">'+rowData.subjtext4+'</a>'
                                                            }
                                                      
                                                            $(td)[0].innerHTML = text
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('align-middle')
                                                      }
                                                },
                                                {
                                                      'targets': 6,
                                                      'orderable': false, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            var buttons = '<a href="#" class="assign ml-4" data-id="'+rowData.studid+'" value="'+rowData.student+'">Assign</a>';
                                                            $(td)[0].innerHTML = buttons;
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('align-middle')
                                                      }
                                                },
                                                {
                                                      'targets': 7,
                                                      'orderable': false, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {

                                                            
                                                            var buttons = '<a href="#" class="delete" data-id="'+rowData.studid+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                            if(rowData.q1 == 0 && rowData.q2 == 0 && rowData.q3 == 0 && rowData.q4 == 0 ){
                                                                  buttons = ''
                                                            }
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('align-middle')
                                                      }
                                                },

                                          ] ,      
                                          initComplete:function( settings, json){
                                                $('.delete').attr('data-toggle', 'popover').attr('data-html', 'true');
                                                $('.delete').popover({
                                                      trigger: 'hover',
                                                      offset: '0 5',
                                                      content: `<span>Remove Specialize Subject</span>`,
                                                });
                                          }                                               
                  });

                  var printable_options = 
                                    '<div class="btn-group ml-2">'+
                                         '<button type="button" class="btn btn-default btn-sm">Printables</button>'+
                                          '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">'+
                                          '<span class="sr-only">Toggle Dropdown</span>'+
                                          '</button>'+
                                          '<div class="dropdown-menu" role="menu">'+
                                                '<a class="dropdown-item print_specialization" data-id="bysubject" href="#">By Subject</a>'+
                                                '<a class="dropdown-item print_specialization" data-id="bysection" href="#">By Section</a>'+
                                                '<a hidden class="dropdown-item print_specialization" data-id="bysection" href="#">By Schedule</a>'+
                                          '</div>'+
                                    '</div>'
                                    
                  var label_text = ''
                        var label_text = $($("#student_specialization_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<div class="row"><div class="col-md-12" ><button class="btn btn-primary btn-sm" id="assign_student" > Assign Student</button>'+printable_options+'</div></div>'


            }


      </script>

      {{-- variables --}}
      <script>
             const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            var all_students = []
            var subjects_studspec = []
            var selectedstudid = null
            var all_subjects = []
      </script>

      <script>
            $(document).ready(function(){

                  

                  $(document).on('click','.print_specialization',function(){
                        var type = $(this).attr('data-id')
                        var syid = $('#filter_schoolyear').val()
                        window.open('/superadmin/student/specialization/print?syid='+syid+'&type='+type, '_blank');
                  })

                  filter_section('#filter_section',null,null)
                  gradelevel_list('#filter_gradelevel',null)
                  get_subjects()
                  get_subjects_studspec()
                  student_assign_datatable()

                  $('.select2').select2()
                 
                  $(document).on('click','#assign_student',function(){
                        $('#assign_student_modal').modal()
                  })

                  $(document).on('click','.delete',function(){
                        selectedstudid = $(this).attr('data-id')
                        Swal.fire({
                              text: 'Are you sure you want to remove detail?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    student_specialization_delete()
                              }
                        })
                       
                  })

                  $(document).on('click','.add_student_assign',function(){
                        var q1 = $('#q1').prop('checked') == true ? 1 : 0 ;
                        var q2 = $('#q2').prop('checked') == true ? 1 : 0 ;
                        var q3 = $('#q3').prop('checked') == true ? 1 : 0 ;
                        var q4 = $('#q4').prop('checked') == true ? 1 : 0 ;
                        var id = $(this).attr('data-id')
                        var subjid = $('#input_subject_assign').val()
                        var syid = $('#filter_schoolyear').val()
                        student_specialization_create(id,subjid,syid,q1,q2,q3,q4)
                  })


                  $(document).on('click','#add_all_student',function(){


                        
                        Swal.fire({
                              title:'Are you sure?',
                              text:'This will add all students to this subjects',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Add all'
                        }).then((result) => {
                              if (result.value) {
                                    var q1 = $('#q1').prop('checked') == true ? 1 : 0 ;
                                    var q2 = $('#q2').prop('checked') == true ? 1 : 0 ;
                                    var q3 = $('#q3').prop('checked') == true ? 1 : 0 ;
                                    var q4 = $('#q4').prop('checked') == true ? 1 : 0 ;
                                    var id = $(this).attr('data-id')
                                    var subjid = $('#input_subject_assign').val()
                                    var syid = $('#filter_schoolyear').val()
                                    var levelid = $('#input_subject_assign_grade').val()
                                    var sectionid = $('#input_subject_assign_section').val()
                                    student_specialization_create_all(levelid,sectionid,subjid,syid,q1,q2,q3,q4)
                              }
                        })


                      
                  })

                  $(document).on('click','.tlesubj',function(){
                        var ischecked = $(this).prop('checked') == false ? false : true
                        var tempdataquarter = $(this).attr('data-quarter')
                        var tempcheckedlink = 0
                        var tempdataid = $(this).attr('data-id')
                        var syid = $('#filter_schoolyear').val()
                        var q1, q2, q3, q4 = 0;
                        $('.tlesubj').attr('disabled','disabled')

                        if(tempdataquarter == "all"){
                              if($(this).prop('checked')){
                                    $('.tlesubj').prop('checked',false)
                                    $('.tlesubj[data-id="'+$(this).attr('data-id')+'"]').prop('checked',true)
                              }else{
                                    $('.tlesubj').prop('checked',false)
                                    $('.tlesubj[data-id="'+$(this).attr('data-id')+'"]').prop('checked',false)
                              }
                        }else{
                              $('.tlesubj[data-quarter="all"]').prop('checked',false)
                              $('.tlesubj[data-quarter="'+tempdataquarter+'"]').prop('checked',false)
                        }
                        
                        $(this).prop('checked',true)
                        
                        $('.tlesubj[data-id="'+$(this).attr('data-id')+'"]').each(function(a,b){
                              if($(this).attr('data-quarter') != "all" && $(b).prop('checked') == true){
                                    if($(this).attr('data-quarter') == 1){ q1 = 1}
                                    else if($(this).attr('data-quarter') == 2){ q2 = 1}
                                    else if($(this).attr('data-quarter') == 3){ q3 = 1}
                                    else if($(this).attr('data-quarter') == 4){ q4 = 1}
                                    tempcheckedlink += 1;
                              }
                        })

                        if(tempcheckedlink == 4 && ischecked){
                              $('.tlesubj[data-quarter="all"][data-id="'+$(this).attr('data-id')+'"]').prop('checked',true)
                              q1, q2, q3, q4 = 1
                        }

                        $(this).prop('checked',true)

                        if(!ischecked){
                              $(this).prop('checked',false)
                              if(tempdataquarter == 1){ q1 = 0 }
                              else if(tempdataquarter == 2){ q2 = 0 }
                              else if(tempdataquarter == 3){ q3 = 0 }
                              else if(tempdataquarter == 4){ q4 = 0 }
                        }

                        student_specialization_create(selectedstudid,tempdataid,syid,q1,q2,q3,q4)
                  })
                  
                  $(document).on('click','.assign',function(){
                        selectedstudid = $(this).attr('data-id')
                        var tempstudid = $(this).attr('data-id')
                        var studinfo = subjects_studspec.filter(x=>x.studid == tempstudid )
                        var filteredsubjects = all_subjects[0].info.filter(x=>x.levelid == studinfo[0].levelid)
                        $('#studname').text(studinfo[0].student)
                        $('#perstudentsubj').empty()
                        $.each(filteredsubjects,function(a,b){
                              var check_text = ''
                              for(var x = 1; x <= 4 ; x++){
                                    check_text += ` <td class="text-center align-middle">
                                                      <div class="icheck-primary d-inline pt-2">
                                                            <input type="checkbox" data-quarter="`+x+`" data-id="`+b.subjid+`" id="subj`+b.subjid+`q`+x+`" class="form-control studtleq`+x+` tlesubj">
                                                            <label for="subj`+b.subjid+`q`+x+`"></label>
                                                      </div>
                                                </td>`
                              }

                              $('#perstudentsubj').append(`<tr><td>`+b.subjdesc+`</td>`+check_text+`
                                                                  <td class="text-center align-middle">
                                                                        <div class="icheck-primary d-inline pt-2">
                                                                              <input type="checkbox" data-quarter="all"  data-id="`+b.subjid+`"id="subjall`+b.subjid+`"  class="form-control studtleqall tlesubj">
                                                                              <label for="subjall`+b.subjid+`"></label>
                                                                        </div>
                                                                  </td>
                                                            </tr>`)
                        })

                        studinfo[0].q1 == 1 ? $('.studtleq1[data-id="'+studinfo[0].q1subj+'"]').prop('checked',true) : false
                        studinfo[0].q2 == 1 ? $('.studtleq2[data-id="'+studinfo[0].q2subj+'"]').prop('checked',true) : false
                        studinfo[0].q3 == 1 ? $('.studtleq3[data-id="'+studinfo[0].q3subj+'"]').prop('checked',true) : false
                        studinfo[0].q4 == 1 ? $('.studtleq4[data-id="'+studinfo[0].q4subj+'"]').prop('checked',true) : false

                       console.log(studinfo)
                        
                        if(studinfo[0].q1subj == studinfo[0].q2subj && studinfo[0].q1subj == studinfo[0].q3subj && studinfo[0].q1subj ==  studinfo[0].q4subj ){
                              $('.tlesubj[data-id="'+studinfo[0].q1subj+'"][data-quarter="all"]').prop('checked',true)
                        }

                        $('#student_assign').modal()
                  })

                  $(document).on('change','#filter_subject',function(){
                        get_subjects_studspec()
                        gradelevel_list('#filter_gradelevel',$(this).val())
                        filter_section('#filter_section',$(this).val(),null)
                  })

                  $(document).on('change','#input_subject_assign',function(){
                        if($(this).val() == ""){
                              $('#add_all_student').attr('hidden','hidden')
                              $('#input_subject_assign_grade').attr('disabled','disabled')
                              $('#input_subject_assign_section').attr('disabled','disabled')
                              $('#input_subject_assign_grade').val("").change()
                              $('#input_subject_assign_section').val("").change()
                        }else{
                              get_students_assign()
                              filter_section('#input_subject_assign_section',$(this).val(),null)
                              gradelevel_list('#input_subject_assign_grade',$(this).val())
                              $('#input_subject_assign_grade').removeAttr('disabled')
                              $('#input_subject_assign_section').removeAttr('disabled')
                        }
                  })

                  $(document).on('change','#input_subject_assign_grade',function(){
                        var value = $(this).val();
                        filter_section('#input_subject_assign_section',$('#input_subject_assign').val(),$(this).val())
                        get_students_assign()
                  })

                  $(document).on('change','#input_subject_assign_section',function(){
                        var section = $(this).val();
                        get_students_assign()
                  })

                  $(document).on('change','#filter_gradelevel',function(){
                        var gradelevel = $(this).val()
                        // if(gradelevel == "" || gradelevel == null){
                        //       get_subjects_studspec()
                        // }else{
                        //       $('#filter_section').removeAttr('disabled')
                        //       filter_change_grade(gradelevel)
                        // }

                        get_subjects_studspec()
                        filter_section('#filter_section',$('#filter_subject').val(),$(this).val())
                  })

                  $(document).on('change','#filter_section',function(){
                        section = $('#filter_section').val()
                        // if(section == null || section == ""){
                        //       get_subjects_studspec()
                        // }else{
                        //       subjects_studspec = subjects_studspec.filter(x=>x.sectionid == section)
                        //       loaddatatable();
                        // }

                        get_subjects_studspec()
                  })

                  $(document).on('change','#filter_schoolyear',function(){
                        filter_section('#filter_section',null,null)
                        gradelevel_list('#filter_gradelevel',null)
                        get_subjects()
                        get_subjects_studspec()
                  })

            })
      </script>

    

@endsection


