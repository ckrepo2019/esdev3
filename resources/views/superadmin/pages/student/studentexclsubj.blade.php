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
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Student Excluded Subject Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                             <div class="col-md-12 form-group">
                                   <label for="">Student</label>
                                   <select name="input_studid" id="input_studid" class="form-control select2"></select>
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
                                    <button hidden class="btn btn-danger btn-sm" id="student_specialclass_delete"><i class="fas fa-trash-alt"></i> Delete</button>
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
                        <h1>Student Excluded Subject</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Excluded Subject</li>
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
                                    <div class="col-md-4">
                                         <h5><i class="fa fa-filter"></i> Filter</h5> 
                                    </div>
                                    <div class="col-md-8">
                                          <h5 class="float-right">Active S.Y.: {{collect($sy)->where('isactive',1)->first()->sydesc}}</h5>
                                    </div>
                              </div>
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
                                    <div class="col-md-1"></div>
                                    <div class="col-md-7">
                                          <i class="mb-0" style="font-size:.9rem !important"><i class="fas fa-info"></i> This module is designed to remove student subjects. It is only applicable for Basic Education.</i>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12" >
                        <div class="card shadow">
                              <div class="card-body pt-1 pb-1">
                                    <i class="mb-0" style="font-size:.9rem !important"><i class="fas fa-info"></i> Click the row to edit or delete information.</i>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row mt-2">
                                          <div class="col-md-12" style="font-size:.9rem !important">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="studentspecialclass_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="25%">Student</th>
                                                                  <th width="35%">Subject</th>
                                                                  <th width="25%">Grade Level / Section</th>
                                                                  <th width="15%" class="text-center p-0 align-middle">S.Y. to Apply</th>
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


            var syncEnabled = false;
            var button_enable = null;
            var connected_stat = false
            
            function getProjectSetup(){
                  $.ajax({
                        type:'GET',
                        url:'/api/schoolinfo/projectsetup',
                        success:function(data) {
                              projectsetup = data
                              if(projectsetup[0].projectsetup == 'offline' && ( projectsetup[0].processsetup == 'hybrid1' || projectsetup[0].processsetup == 'hybrid2' ) ){
                                    syncEnabled = true
                                    check_online_connection()
                              }

                              if(projectsetup[0].projectsetup == 'online' && ( projectsetup[0].processsetup == 'hybrid1' || projectsetup[0].processsetup == 'hybrid2' ) ){
                                    button_enable = false
                              }else{
                                    button_enable = true
                              }
                              student_specialclass_datatable()
                              
                        }
                  })
            }

            function check_online_connection(){
                  $.ajax({
                        type:'GET',
                        url: projectsetup[0].es_cloudurl+'/checkconnection',
                        success:function(data) {
                              connected_stat = true
                              get_last_index('student_exclsubj',true)
                        }, 
                        error:function(){
                              $('#online_status').text('Not Connected')
                        }
                  })
            }

      </script>

      <script>
            
            function get_last_index(tablename,first=false){
                  if(!connected_stat){
                  return false
                  }
                  $.ajax({
                        type:'GET',
                        url: '/api/basiced/student/excludedsubj/getnewinfo',
                        data:{
                              tablename: tablename
                        },
                        success:function(data) {
                        process_create(tablename,data,first)
                        },
                  })
            }

            function process_create(tablename,createdata,first=false){
                  if(createdata.length == 0){
                        if(first){
                              get_updated(tablename)
                              get_deleted(tablename)
                        }
                        return false;
                  }
                  var b = createdata[0]
                  $.ajax({
                        type:'GET',
                        url: projectsetup[0].es_cloudurl+'/api/basiced/student/excludedsubj/syncnew',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              createdata = createdata.filter(x=>x.id != b.id)
                              if(data[0].status == 1){
                                    update_local_status(tablename,createdata,b,'create',first)
                              }else{
                                    process_create(tablename,createdata)
                              }
                        },
                        error:function(){
                              createdata = createdata.filter(x=>x.id != b.id)
                              if(data[0].status == 1){
                                    update_local_status(tablename,createdata,b,'create',first)
                              }else{
                                    process_create(tablename,createdata)
                              }
                        }
                  })
            }

            //get_updated
            function get_updated(tablename){
                  if(!connected_stat){
                  return false
                  }
                  $.ajax({
                        type:'GET',
                        url: '/api/basiced/student/excludedsubj/getupdated',
                        data:{
                              tablename: tablename,
                        },
                        success:function(data) {
                              process_update(tablename,data)
                        }
                  })
            }

            function process_update(tablename , updated_data){
                  if (updated_data.length == 0){
                        return false
                  }
                  var b = updated_data[0]
                  $.ajax({
                        type:'GET',
                        url:  projectsetup[0].es_cloudurl+'/api/basiced/student/excludedsubj/syncupdate',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              updated_data = updated_data.filter(x=>x.id != b.id)
                              if(data[0].status == 1){
                                    update_local_status(tablename,updated_data,b,'update')
                              }else{
                                    process_update(tablename,updated_data)
                              }
                             
                        },
                  })
            }


            //get deleted
            function get_deleted(tablename){
                  if(!connected_stat){
                        return false
                  }
                  $.ajax({
                        type:'GET',
                        url: '/api/basiced/student/excludedsubj/getdeleted',
                        data:{
                              tablename: tablename
                        },
                        success:function(data) {
                        process_deleted(tablename,data)
                        }
                  })
            }
                  

            function process_deleted(tablename , deleted_data){
                  if (deleted_data.length == 0){
                              return false
                  }
                  var b = deleted_data[0]
                  $.ajax({
                        type:'GET',
                        url: projectsetup[0].es_cloudurl+'/api/basiced/student/excludedsubj/syncdelete',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              deleted_data = deleted_data.filter(x=>x.id != b.id)
                              if(data[0].status == 1){
                                    update_local_status(tablename,deleted_data,b,'delete')
                              }else{
                                    process_create(tablename,deleted_data)
                              }
                        },
                  })
            }

            function update_local_status(tablename,alldata,info,status,first=false){
                  $.ajax({
                        type:'GET',
                        url:  '/api/basiced/student/excludedsubj/updatestat',
                        data:{
                              tablename: tablename,
                              data:info
                        },
                        success:function(data) {
                              if(status == 'delete'){
                                    process_update(tablename,alldata)
                              }else if(status == 'update'){
                                    process_update(tablename,alldata)
                              }else if(status == 'create'){
                                    process_create(tablename,alldata,data,first)
                              }
                        },
                  })
            }
      </script>


      {{-- <script>

      </script> --}}

      <script>

            var sy = @json($sy);
            var sem = @json($semester);
            var gradelevel = @json($gradelevel);
            var all_students = []
            var all_student_specialclass = []
            var selected_info = []

            // $(document).ready(function(){

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

                        $('#input_studid').val("").change()
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
                        $('#student_specialclass_delete').attr('hidden','hidden')
                        $('#input_studid').removeAttr('disabled')

                        $('#student_specialclass_form').modal();
                  })

                  $(document).on('click','.view_info',function(){
                        var selected = $(this).attr('data-id')
                        selected_info = all_student_specialclass.filter(x=>x.id == selected)[0]
                        $('#input_studid').val(selected_info.studid).change()
                        $('#input_studid').attr('disabled','disabled')
                        $('#input_sytograde').val(selected_info.sytoapplygrade).change()
                        $('#input_semtograde').val(selected_info.semtoapplygrade).change()


                        $('#student_specialclass_update').removeAttr('hidden')
                        $('#student_specialclass_create').attr('hidden','hidden')
                        $('#student_specialclass_delete').removeAttr('hidden')

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
                              text: 'Are you sure you want to remove excluded subject?',
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

                  $(document).on('change','#input_studid',function(){
                        if($(this).val() != null && $(this).val() != ""){
                              var temp_student = all_students.filter(x=>x.studid == $(this).val())[0]
                              var temp_gradelevel = gradelevel.filter(x=>x.acadprogid == temp_student.acadprogid)
                              update_gradelevel(temp_gradelevel)
                              $('#levelid').val(temp_student.levelid).change()
                              $('#input_sytograde').val($('#filter_sy').val()).change()
                              // $('#sectionid').val(temp_student.sectionid).change()
                              // $('#levelid').removeAttr('disabled')
                              // $('#sectionid').removeAttr('disabled')
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
                              url:'/basiced/student/excludedsubj/delete',
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
                                          get_deleted('student_exclsubj')
                                          $('#student_specialclass_form').modal('hide')
                                          load_student_special_class()
                                    }
                                  
                              }
                        })
                  }

                  function add_student_specialclass(){

                        if($('#input_studid').val() == ""){
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
                              url:'/basiced/student/excludedsubj/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    studid:$('#input_studid').val(),
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
                                          get_last_index('student_exclsubj')
                                          load_student_special_class()
                                    }
                                  
                              }
                        })
                  }

                  function update_student_specialclass(){

                        if($('#input_studid').val() == ""){
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
                              url:'/basiced/student/excludedsubj/update',
                              data:{
                                    id:selected_info.id,
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    studid:$('#input_studid').val(),
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
                                          get_updated('student_exclsubj')
                                          load_student_special_class()
                                    }
                              
                              }
                        })
                  }

                  function get_all_sections(){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/excludedsubj/sections',
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

                                    var temp_student = all_students.filter(x=>x.studid == $('#input_studid').val())[0]
                                    $('#sectionid').val(temp_student.sectionid).change()

                                    if(selected_info != null){
                                           $('#sectionid').val(selected_info.sectionid).change()
                                    }
                     
                              }
                        })
                  }

                  function get_all_subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/basiced/student/excludedsubj/subjects',
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
                              url:'/basiced/student/excludedsubj/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    all_students = data
                                    $("#input_studid").empty()
                                    $("#input_studid").append('<option value="">Select Student</option>')
                                    $("#input_studid").select2({
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
                              url:'/basiced/student/excludedsubj/list',
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

                        if(button_enable == null){
                              getProjectSetup()
                              return false
                        }

                        $("#studentspecialclass_datatable").DataTable({
                              destroy: true,
                              data:all_student_specialclass,
                              lengthChange : false,
                              stateSave: true,
                              columns: [
                                          { "data": "full_name" },
                                          { "data": "subjtext" },
                                          { "data": "sectionname" },
                                          { "data": "sydesc" },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.full_name+' </a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.subjtext+' </a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjcode+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                console.log(rowData.levelname)
                                                var text = '<a class="mb-0">'+rowData.sectionname+' </a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var semester = ''
                                                if(rowData.levelid == 14 || rowData.levelid == 15){
                                                      semester = rowData.semester
                                                }
                                                var text = '<a class="mb-0">'+rowData.sydesc+' </a><p class="text-muted mb-0" style="font-size:.7rem">'+semester+'</p>';
                                                $(td)[0].innerHTML =  text
                                              
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    }
                              ],
                              createdRow: function (row, data, dataIndex) {
                                    if(button_enable){
                                          $(row).attr("data-id",data.id);
                                          $(row).addClass("view_info");
                                    }else{
                                    }
                              },
                              
                        });


                        var label_text = $($('#studentspecialclass_datatable_wrapper')[0].children[0])[0].children[0]

                        if(button_enable){
                              $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" id="student_specialclass_button">Add Excluded Subject</button>'

                        }else{
                              $(label_text)[0].innerHTML = ''
                        }

                       
                  
                       
                  }

              

            // })
      </script>


@endsection


