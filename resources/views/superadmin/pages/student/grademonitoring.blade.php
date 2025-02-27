@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
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
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
            .view_info {
                  cursor: pointer;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')
            ->orderBy('sydesc')
            ->select(
                  'id',
                  'sydesc',
                  'sydesc as text',
                  'isactive'
            )
            ->get(); 

      $semester = DB::table('semester')
                  ->orderBy('semester')
                  ->select(
                        'id',
                        'semester',
                        'semester as text',
                        'isactive'
                  )
                  ->get(); 

      $gradelevel = DB::table('gradelevel')
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'levelname as text',
                              'levelname',
                              'sortid',
                              'acadprogid'
                        )
                        ->orderBy('sortid')
                        ->get(); 

      $academicprogram = DB::table('academicprogram')
                              ->get();

      


@endphp



<div class="modal fade" id="student_specialclass_form" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Student Special Class Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        {{-- <div class="row">
                              <div class="col-md-12">
                                    <p>This subject will be taken this S.Y.: <span id="sy_label_holder"></span> Semester: <span id="sem_label_holder"></span></p>
                              </div>
                        </div> --}}
                        <div class="row">
                             <div class="col-md-12 form-group">
                                   <label for="">Student</label>
                                   <select name="studid" id="studid" class="form-control select2"></select>
                             </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Grade Level</label>
                                    <select name="levelid" id="levelid" class="form-control select2" disabled></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Section</label>
                                    <select name="sectionid" id="sectionid" class="form-control select2" disabled>
                                          <option value="">Select Section</option>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject</label>
                                    <select name="subjid" id="subjid" class="form-control select2" disabled>
                                          <option value="">Select Subject</option>
                                    </select>
                              </div>
                        </div>
                        {{-- <div class="row">
                              <div class="col-md-12">
                                    <p>Apply grade to:</p>
                              </div>
                        </div> --}}
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">School Year to apply grade</label>
                                    <select name="input_sytograde" id="input_sytograde" class="form-control select2" disabled></select>
                              </div>
                        </div>
                        <div class="row"  hidden>
                              <div class="col-md-12 form-group">
                                    <label for="">Semester to appply grade</label>
                                    <select name="input_semtograde" id="input_semtograde" class="form-control select2"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" id="student_specialclass_create"><i class="fas fa-plus"></i> Add</button>
                                    <button hidden class="btn btn-success btn-sm" id="student_specialclass_update"><i class="fas fa-save"></i> Update</button>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-danger btn-sm" id="student_specialclass_delete"><i class="fas fa-trash-alt"></i> Delete</button>
                              </div>
                        </div>
                        
                  </div>
                  {{-- <div class="modal-footer border-0">
                        <div class="col-md-6">
                              
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                  </div> --}}
            </div>
      </div>
</div>   
<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Remedial Class</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Remedial Class</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
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
                                          <label for="">School Year</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sem">
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
                          </div>
                        </div>
                  </div>
            </div>
          
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    {{-- <div class="row">
                                          <div class="col-md-6">
                                                <button class="btn btn-sm btn-primary" id="student_specialclass_button">Add Student Special Class</button>
                                          </div>
                                    </div> --}}
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="studentspecialclass_datatable" width="100%" style="font-size:.9rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th width="25%">Student</th>
                                                                  <th width="30%">Subject</th>
                                                                  <th width="10%">Grade Level</th>
                                                                  <th width="15%">Section</th>
                                                                  <th width="10%" class="text-center p-0 align-middle">S.Y. to Apply</th>
                                                                  <th width="10%" class="text-center p-0 align-middle">Sem to Apply</th>
                                                                  {{-- <th width="5%"></th> --}}
                                                                  {{-- <th width="5%"></th> --}}
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


      <script>

            var sy = @json($sy);
            var sem = @json($semester);
            var gradelevel = @json($gradelevel);
            var all_students = []
            var all_student_specialclass = []
            var selected_info = []

            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $('.select2').select2()

                  $("#sectionid").select2({
                        data: [],
                        placeholder: "Select Section",
                  })

                  $("#subjid").select2({
                        data: [],
                        placeholder: "Select Subject",
                  })

                  $("#input_sytograde").empty();
                  $("#input_sytograde").append('<option value="">Select School Year to apply grade</option>');
                  $("#input_sytograde").select2({
                        data: sy,
                        allowClear: true,
                        placeholder: "Select School Year to apply grade",
                        dropdownCssClass: "myFont"
                  })

                  $("#input_semtograde").empty();
                  $("#input_semtograde").append('<option value="">Select Semester to apply grade</option>');
                  $("#input_semtograde").select2({
                        data: sem,
                        allowClear: true,
                        placeholder: "Select Semester to apply grade",
                        dropdownCssClass: "myFont"
                  })

                  update_gradelevel([])
                  
                  


                  load_student_special_class()
                  get_all_students()
                  // get_all_gradelevel()
                  
                  $(document).on('click','#student_specialclass_button',function(){
                        selected_info = null

                        $('#studid').val("").change()
                        $('#levelid').attr('disabled','disabled')
                        $('#sectionid').attr('disabled','disabled')
                        $('#subjid').attr('disabled','disabled')
                        $('#input_sytograde').attr('disabled','disabled')
                        $('#input_semtograde').attr('disabled','disabled')
                        
                        $('#levelid').val("").change()
                        $('#sectionid').val("").change()
                        $('#subjid').val("").change()
                        $('#input_sytograde').val("").change()
                        $('#input_semtograde').val("").change()

                        $('#student_specialclass_create').removeAttr('hidden')
                        $('#student_specialclass_update').attr('hidden','hidden')
                        $('#studid').removeAttr('disabled')

                        $('#student_specialclass_form').modal();
                  })

                  $(document).on('click','.view_info',function(){
                        var selected = $(this).attr('data-id')
                        selected_info = all_student_specialclass.filter(x=>x.id == selected)[0]
                        $('#studid').val(selected_info.studid).change()
                        $('#studid').attr('disabled','disabled')
                        $('#input_sytograde').val(selected_info.sytoapplygrade).change()
                        $('#input_semtograde').val(selected_info.semtoapplygrade).change()


                        $('#student_specialclass_update').removeAttr('hidden')
                        $('#student_specialclass_create').attr('hidden','hidden')

                        $('#student_specialclass_form').modal();
                  })

                  $(document).on('click','#student_specialclass_create',function(){
                        add_student_specialclass()
                  })

                  $(document).on('click','#student_specialclass_update',function(){
                        update_student_specialclass()
                  })

                  $(document).on('click','#student_specialclass_delete',function(){

                        Swal.fire({
                              text: 'Are you sure you want to remove special class?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    var specialclass_id = selected_info.id
                                    var specialclass_studid = selected_info.studid
                                    remove_specialclass(specialclass_id,specialclass_studid)
                              }
                        })
                  })

                  $(document).on('change','#filter_sy , #filter_sem',function(){
                        load_student_special_class()
                        get_all_students()
                        // get_all_gradelevel()
                  })



                  $(document).on('change','#levelid',function(){

                        if($(this).val() == 14 || $(this).val() == 15){
                              $('#input_semtograde_holder').removeAttr('hidden')
                        }else{
                              $('#input_semtograde_holder').attr('hidden','hidden')
                        }

                        if($(this).val() != null && $(this).val() != ""){
                              get_all_sections()
                              get_all_subjects()
                        }
                       
                  })

                  $(document).on('change','#studid',function(){
                        if($(this).val() != null && $(this).val() != ""){
                              var temp_student = all_students.filter(x=>x.studid == $(this).val())[0]
                              var temp_gradelevel = gradelevel.filter(x=>x.acadprogid == temp_student.acadprogid)
                              update_gradelevel(temp_gradelevel)
                              $('#levelid').removeAttr('disabled')
                              $('#sectionid').removeAttr('disabled')
                              $('#subjid').removeAttr('disabled')
                              $('#input_sytograde').removeAttr('disabled')
                              $('#input_semtograde').removeAttr('disabled')
                              
                        }else{
                              update_gradelevel([])
                              $('#levelid').attr('disabled','disabled')
                              $('#sectionid').attr('disabled','disabled')
                              $('#subjid').attr('disabled','disabled')
                              $('#input_sytograde').attr('disabled','disabled')
                              $('#input_semtograde').attr('disabled','disabled')

                              $('#levelid').val("").change()
                              $('#sectionid').val("").change()
                              $('#subjid').val("").change()
                              $('#input_sytograde').val("").change()
                              $('#input_semtograde').val("").change()
                        }
                      

                  })


                  function update_gradelevel(data){

                        $("#levelid").empty()
                        $("#levelid").append('<option value="">Select Grade Level</option>')
                        $("#levelid").select2({
                              data: data,
                              allowClear: true,
                              placeholder: "Select Grade Level",
                        })

                        if(selected_info != null){
                              $('#levelid').val(selected_info.levelid).change()
                        }
                  }

                  function remove_specialclass(specialclass_id,specialclass_studid){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/delete',
                              data:{
                                    dataid:specialclass_id,
                                    studid:specialclass_studid,
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
                                          $('#student_specialclass_form').modal('hide')
                                          load_student_special_class()
                                    }
                                  
                              }
                        })
                  }

                  function add_student_specialclass(){

                        if($('#studid').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No student selected"
                              })
                              return false
                        }
                        if($('#sectionid').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No section selected"
                              })
                              return false
                        }
                        if($('#subjid').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No subject selected"
                              })
                              return false
                        }

                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    studid:$('#studid').val(),
                                    levelid:$('#levelid').val(),
                                    sectionid:$('#sectionid').val(),
                                    subjid:$('#subjid').val(),
                                    sytoapplygrade:$('#input_sytograde').val(),
                                    semtoapplygrade:$('#filter_sem').val()
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
                                          load_student_special_class()
                                    }
                                  
                              }
                        })
                  }

                  function update_student_specialclass(){

                        if($('#studid').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No student selected"
                              })
                              return false
                        }
                        if($('#sectionid').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No section selected"
                              })
                              return false
                        }
                        if($('#subjid').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No subject selected"
                              })
                              return false
                        }

                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/update',
                              data:{
                                    id:selected_info.id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    studid:$('#studid').val(),
                                    levelid:$('#levelid').val(),
                                    sectionid:$('#sectionid').val(),
                                    subjid:$('#subjid').val(),
                                    sytoapplygrade:$('#input_sytograde').val(),
                                    semtoapplygrade:$('#filter_sem').val()
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
                                          load_student_special_class()
                                    }
                              
                              }
                        })
                  }



                  // function get_all_gradelevel(){
                  //       $.ajax({
                  //             type:'GET',
                  //             url:'/basiced/student/specialclass/gradelevel',
                  //             success:function(data) {
                  //                   $("#levelid").empty()
                  //                   $("#levelid").append('<option value="">Select Grade Level</option>')
                  //                   $("#levelid").select2({
                  //                         data: data,
                  //                         allowClear: true,
                  //                         placeholder: "Select Grade Level",
                  //                   })
                  //             }
                  //       })
                  // }

                  function get_all_sections(){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/sections',
                              data:{
                                    levelid:$('#levelid').val()
                              },
                              success:function(data) {
                                    $("#sectionid").empty()
                                    $("#sectionid").append('<option value="">Select Section</option>')
                                    $("#sectionid").select2({
                                          data: data,
                                          allowClear: true,
                                          placeholder: "Select Section",
                                    })

                                  
                                    if(selected_info != null){
                                          console.log(selected_info)
                                           $('#sectionid').val(selected_info.sectionid).change()
                                    }
                     
                              }
                        })
                  }

                  function get_all_subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/subjects',
                              data:{
                                    levelid:$('#levelid').val(),
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                              },
                              success:function(data) {
                                    $("#subjid").empty()
                                    $("#subjid").append('<option value="">Select Subject</option>')
                                    $("#subjid").select2({
                                          data: data,
                                          allowClear: true,
                                          placeholder: "Select Subject",
                                    })

                                    if(selected_info != null){
                                       $('#subjid').val(selected_info.subjid).change()
                                    }
                       
                              }
                        })
                  }

                  function get_all_students(){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    all_students = data
                                    $("#studid").empty()
                                    $("#studid").append('<option value="">Select Student</option>')
                                    $("#studid").select2({
                                          data: data,
                                          allowClear: true,
                                          placeholder: "Select Student",
                                    })
                              }
                        })
                  }

                  function load_student_special_class(){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/specialclass/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    all_student_specialclass = data
                                    student_specialclass_datatable()
                              }
                        })
                  }

                  function student_specialclass_datatable(){

                        $("#studentspecialclass_datatable").DataTable({
                              destroy: true,
                              data:all_student_specialclass,
                              lengthChange : false,
                              stateSave: true,
                              columns: [
                                          { "data": "full_name" },
                                          { "data": "subjtext" },
                                          { "data": "levelname" },
                                          { "data": "sectionname" },
                                          { "data": "sydesc" },
                                          // { "data": null },
                                          { "data": "semester" },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
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
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    // {
                                    //       'targets': 5,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    //             var buttons = '<a href="javascript:void(0)" class="udpate_strand" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                    //             $(td)[0].innerHTML =  null
                                    //             $(td).addClass('text-center')
                                    //             $(td).addClass('align-middle')
                                                
                                    //       }
                                    // },
                                    // {
                                    //       'targets': 5,
                                    //       'orderable': false, 
                                    //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    //             var disabled = '';
                                    //             var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_specialclass" data-id="'+rowData.id+'" data-studid="'+rowData.studid+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                    //             $(td)[0].innerHTML =  buttons
                                    //             $(td).addClass('text-center')
                                    //             $(td).addClass('align-middle')
                                    //       }
                                    // },
                              ],
                              createdRow: function (row, data, dataIndex) {
                                    $(row).attr("data-id",data.id);
                                    $(row).addClass("view_info");
                              },
                              
                        });

                        var label_text = $($('#studentspecialclass_datatable_wrapper')[0].children[0])[0].children[0]
                  
                        $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" id="student_specialclass_button">Add Student Special Class</button>'

                  }

              

            })
      </script>


@endsection


