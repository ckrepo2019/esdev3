@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8){
        $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }else if(Session::get('currentPortal') == 4){
         $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 15){
            $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 14){
            $extend =  'deanportal.layouts.app2';
      }else if(auth()->user()->type == 3 || auth()->user()->type == 8 ){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 4){
        $extend = 'finance.layouts.app';
      }else if(auth()->user()->type == 15 ){
            $extend = 'finance.layouts.app';
      }else if(auth()->user()->type == 14 ){
            $extend =  'deanportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 26){
                        $extend = 'registrar.layouts.app';
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
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css')}}">
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
                 
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }

            .select2{
                  width: 100% !important;
            }

            img{
                  border-radius: 0 !important
            }
            .myFont{
                  font-size:.8rem !important;
            }
            .tableFixHead {
                  overflow: auto;
                  height: 100px;
            }

            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .ribbon-wrapper.ribbon-lg .ribbon {
                  right: -16px;
                  top: 4px;
                  width: 160px;
            }

            .update_fas {
                  cursor: pointer;
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

      </style>
@endsection


@section('content')

@php
      
      $sy = DB::table('sy')   
                  ->orderBy('sydesc','desc')
                  ->select(
                        'id',
                        'sydesc',
                        'sydesc as text',
                        'isactive',
                        'ended'
                  )
                  ->get(); 

      $utype = DB::table('usertype')   
                  ->orderBy('utype')
                  ->where('with_acad',1)
                  ->select(
                        'id',
                        'utype',
                        'utype as text'
                  )
                  ->get(); 

      $schoolinfo = DB::table('schoolinfo')->first();

      $academic_prog = DB::table('academicprogram')->select('id','acadprogcode','acadprogcode as text')->get();

@endphp

<div class="modal fade" id="fasacad_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">FAS Academic Program Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row form-group">
                        <div class="col-md-12 ">
                              <label for="">Teacher</label>
                              <select name="" id="teacherid" class="form-contol select2 form-control-sm"></select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12 form-group">
                              <label for="">Teacher</label>
                              <select name="" id="acadprog" class="form-contol select2" multiple></select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <button class="btn btn-sm btn-primary" id="save_fasacadprog_button"><i class="fa fa-save"></i> Save</button>
                        </div>
                  </div>
            </div>
            </div>
      </div>
</div>


<div class="modal fade" id="copy_fasacad_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Copy FAS Academic Program</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row form-group">
                        <div class="col-md-12 ">
                              <label for="">Copy From</label>
                              <select class="form-control select2 form-control-sm" id="copy_sy_from">
                                    <option value="">Select S.Y.</option>
                                    @foreach ($sy as $item)
                                          <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                    @endforeach
                              </select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <button class="btn btn-sm btn-primary" id="copy_fasacadprog_button"><i class="fa fa-copy"></i> Copy</button>
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
                        <h1>FAS Academic Program</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">FAS Academic Program</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-4">
                                               <h5><i class="fa fa-filter"></i> Filter</h5> 
                                          </div>
                                          <div class="col-md-8">
                                                <h5 class="float-right">Active S.Y.: {{collect($sy)->where('isactive',1)->first()->sydesc}}</h5>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2  form-group  mb-0">
                                                <label for="" class="mb-1">School Year</label>
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
                                          <div class="col-md-3  form-group  mb-0">
                                                <label for="" class="mb-1">User Type</label>
                                                <select class="form-control select2 form-control-sm" id="filter_utype">
                                                      @foreach ($utype as $item)
                                                            @if($item->id == 1)
                                                                  <option value="{{$item->id}}" selected="selected">{{$item->utype}}</option>
                                                            @else
                                                                  <option value="{{$item->id}}">{{$item->utype}}</option>
                                                            @endif
                                                      @endforeach
                                                </select>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" id="teacher_type_holder">
                  <div class="col-md-12">
                        <di class="card shadow">
                              <div class="card-body p-1">
                                    <div class="col-md-12">
                                          <ul class="mb-0 pl-2" style="list-style-type:none;">
                                                <li> <p class="mb-0" style="font-size:.9rem !important">Please check advisory or schedule assignmnet if unable to remove academic program for selected school year.</p></li>
                                          </ul>
                                         
                                    </div>
                              </div>
                        </di>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body" style="font-size:.8rem !important">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered" id="fasacadprog_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="15%" class="align-middle prereg_head" data-id="0">TID #</th>
                                                                  <th width="25%" class="align-middle prereg_head" data-id="1">Teacher</th>
                                                                  <th width="60%" class="align-middle prereg_head" data-id="1">Academic Program</th>
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
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js')}}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>

      

      <script>
            $(document).ready(function(){

                  var school_setup = @json($schoolinfo);

                  $('#filter_sy').select2()
                  $('#filter_utype').select2()
                  $('.select2').select2()

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
                  
                  var fas_acadprog = []
                  var fas_teacher = []
                  var all_teacher = []
                  var selected_teacher = null
                  var enable_button = true

                  if(school_setup.projectsetup == 'online' &&  school_setup.processsetup == 'hybrid1'){
                        enable_button = false;
                  }
                  
                  $(document).on('change','#filter_sy',function(){
                        display_acad()
                        get_all_teachers()
                  })

                  $(document).on('change','#filter_utype',function(){
                        if($(this).val() == 1){
                              $('#teacher_type_holder').removeAttr('hidden')
                        }else{
                              $('#teacher_type_holder').attr('hidden','hidden')
                        }
                        display_acad()
                        get_all_teachers()
                  })

                  $(document).on('change','#teacherid',function(){
                        if(selected_teacher == null){
                              var temp_teacherid = $(this).val()

                              if(temp_teacherid == null){
                                    return false
                              }

                              var temp_acadprog = []
                              var filtered_acadprog = fas_acadprog.filter(x=>x.teacherid == temp_teacherid)
                              $.each(filtered_acadprog,function(a,b){
                                    temp_acadprog.push(b.acadprogid)
                              })
                              $('#acadprog').val(temp_acadprog).change()
                        }
                  })

                  $(document).on('click','#fasacad_modal_create',function(){

                        $('#teacherid').empty()
                        $("#teacherid").append('<option value="">Select Teacher</option>');
                        $("#teacherid").select2({
                              data: all_teacher,
                              placeholder: "Select Teacher",
                              allowClear:true
                        })


                        $('#teacherid').val("").change();
                        $('#acadprog').val("").change();
                        $('#teacherid').removeAttr('disabled')
                        selected_teacher = null
                        $('#fasacad_modal').modal()
                  })

                  $(document).on('click','.update_fas',function(){
                        var temp_teacherid = $(this).attr('data-id')

                        var temp_teachers = all_teacher
                        var temp_info = fas_teacher.filter(x=>x.teacherid == temp_teacherid)
                        var temp_teachers  = [{
                              id:temp_teacherid,
                              text:temp_info[0].tid + ' - ' +temp_info[0].teachername
                        }]

                        $('#teacherid').empty()
                        $("#teacherid").append('<option value="">Select Teacher</option>');
                        $("#teacherid").select2({
                              data: temp_teachers,
                              placeholder: "Select Teacher",
                              allowClear:true
                        })


                        selected_teacher = temp_teacherid
                        $('#teacherid').val(temp_teacherid).change();
                        $('#teacherid').attr('disabled','disabled')
                        var temp_acadprog = []
                        var filtered_acadprog = fas_acadprog.filter(x=>x.teacherid == temp_teacherid)
                        $.each(filtered_acadprog,function(a,b){
                              temp_acadprog.push(b.acadprogid)
                        })

                       

                        $('#acadprog').val(temp_acadprog).change()
                        $('#fasacad_modal').modal()
                  })

                  $(document).on('click','#fasacad_modal_copy',function(){
                        $('#copy_fasacad_modal').modal()
                  })
                  


                  var acad = @json($academic_prog);

                  $("#acadprog").select2({
                        data: acad,
                        placeholder: "Select a academic program",
                        theme: 'bootstrap4'
                  })

                  display_acad()
                  get_all_teachers()

                  $(document).on('click','#save_fasacadprog_button',function(){
                        save_acadprog()
                  })

                  $(document).on('click','#copy_fasacadprog_button',function(){
                        if($('#copy_sy_from').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: "No S.Y. Selecte"
                              })
                              return false
                        }
                        $.ajax({
                              type:'GET',
                              url:'/setup/useracadprog/copy',
                              data:{
                                    syid_to:$('#filter_sy').val(),
                                    syid_from:$('#copy_sy_from').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          display_acad()
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }
                              }
                        })


                  })

                  function save_acadprog(){
                        $.ajax({
                              type:'GET',
                              url:'/setup/useracadprog/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    usertype:$('#filter_utype').val(),
                                    teacherid:$('#teacherid').val(),
                                    acadprog:$('#acadprog').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          display_acad()
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }
                                  
                                    
                              }
                        })


                  }

                  function get_all_teachers(){
                     
                        $.ajax({
                              type:'GET',
                              url:'/setup/useracadprog/teachers',
                              data:{
                                    usertype:$('#filter_utype').val(),
                                    syid:$('#filter_sy').val()
                              },
                              success:function(data) {
                                    all_teacher = data
                                    $('#teacherid').empty()
                                    $("#teacherid").append('<option value="">Select Teacher</option>');
                                    $("#teacherid").select2({
                                          data: all_teacher,
                                          placeholder: "Select Teacher",
                                          allowClear:true
                                    })

                              }
                        })
                  }
                  

                  function display_acad(){
                        $("#fasacadprog_datatable").DataTable({
                              destroy: true,
                              autoWidth: false,
                              stateSave: true,
                              serverSide: true,
                              processing: true,
                              ajax:{
                                    url: '/setup/useracadprog/list',
                                    type: 'GET',
                                    data: {
                                          syid:$('#filter_sy').val(),
                                          usertype:$('#filter_utype').val()
                                    },
                                    dataSrc: function ( json ) {

                                          fas_teacher = json.data,
                                          fas_acadprog = json.acadprog
                                          return json.data;
                                    }
                              },

                              columns: [
                                          { "data": "tid" },
                                          { "data": "teachername" },
                                          { "data": null},
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var filter_acad = fas_acadprog.filter(x=>x.teacherid == rowData.teacherid)
                                                var text = ''
                                                $.each(filter_acad,function(a,b){
                                                      text += '<span class="badge badge-info ml-2">'+b.acadprogcode+'</span>'
                                                })
                                                $(td)[0].innerHTML = text
                                          }
                                    },
                              ],
                              createdRow: function (row, data, dataIndex) {
                                    if(enable_button){
                                          $(row).attr("data-id",data.teacherid);
                                          $(row).addClass("update_fas");
                                    }
                                  
                              },
                        })

                        
                        if(enable_button){
                              var label_text = $($('#fasacadprog_datatable_wrapper')[0].children[0])[0].children[0]
                              $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm mr-2" id="fasacad_modal_create"><i class="fa fa-plus"></i> Add</button><button class="btn btn-warning btn-sm" id="fasacad_modal_copy"><i class="fa fa-copy"></i> Copy</button>'
                        }else{
                              var label_text = $($('#fasacadprog_datatable_wrapper')[0].children[0])[0].children[0]
                              $(label_text)[0].innerHTML = ''
                        }
                  
                  
                  }

            })
      </script>

@endsection


