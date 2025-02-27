@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8){
        $extend = 'registrar.layouts.app';
      }
      else if(Session::get('currentPortal') == 4){
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
                  ->whereIn('id',[1,2,3,8])
                  ->orWhereIn('refid',[23])
                  ->select(
                        'id',
                        'utype',
                        'utype as text'
                  )
                  ->get(); 

      $academic_prog = DB::table('academicprogram')->select('id','acadprogcode','acadprogcode as text')->get();

@endphp


<div class="modal fade" id="reflist_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog ">
            <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Refrence List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row mt-2" style="font-size:.8rem !important">
                        <div class="col-md-12">
                              <i>Listed here are the user types that not included in the original user type.</i>
                        </div>
                  </div>
                            
                  <div class="row">
                        <div class="col-md-12">
                              <table class="table table-sm table-bordered mb-0" width="100%" style="font-size:.8rem !important">
                                    <thead  class="thead-light">
                                          <tr>
                                                <th width="40%">Designation</th>
                                                <th width="20%"  class="text-center">Ref. #</th>
                                                <th width="40%"  class="text-center">Resource Path</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <tr>
                                                <td>Accounting</td>
                                                <td class="text-center">19</td>
                                                <td></td>
                                          </tr>
                                          <tr>
                                                <td>Principal Assistant</td>
                                                <td class="text-center">20</td>
                                                <td >principalassistant.pages.home</td>
                                          </tr>
                                          <tr>
                                                <td>Scholarship Coordinator</td>
                                                <td class="text-center">21</td>
                                                <td >scholarshipcoor.pages.home</td>
                                          </tr>
                                          <tr>
                                                <td>Subject Coordinator</td>
                                                <td class="text-center">22</td>
                                                <td >principalcoor.pages.home</td>
                                          </tr>
                                          <tr>
                                                <td>School Clinic</td>
                                                <td class="text-center">23</td>
                                                <td>clinic.index</td>
                                          </tr>
                                          <tr>
                                                <td>Nurse</td>
                                                <td class="text-center">24</td>
                                                <td>clinic_nurse.index</td>
                                          </tr>
                                          <tr>
                                                <td>Doctor</td>
                                                <td class="text-center">25</td>
                                                <td>clinic_doctor.index</td>
                                          </tr>
                                          <tr>
                                                <td>Payroll Clerk</td>
                                                <td class="text-center">26</td>
                                                <td></td>
                                          </tr>
                                          <tr>
                                                <td>Academic Coordinator</td>
                                                <td class="text-center">27</td>
                                                <td>academiccoor.pages.home</td>
                                          </tr>
                                          <tr>
                                                <td>Office of the Students Affairs (OSAS)</td>
                                                <td class="text-center">28</td>
                                                <td></td>
                                          </tr>
                                          <tr>
                                                <td>ID Management</td>
                                                <td class="text-center">29</td>
                                                <td>idmanagement.pages.home</td>
                                          </tr>
                                          <tr>
                                                <td>Encoder</td>
                                                <td class="text-center">30</td>
                                                <td>encoder.pages.home</td>
                                          </tr>
                                          <tr>
                                                <td>Guidance</td>
                                                <td class="text-center">31</td>
                                                <td></td>
                                          </tr>
                                          <tr>
                                                <td>Finance SA</td>
                                                <td class="text-center">32</td>
                                                <td></td>
                                          </tr>
                                    </tbody>
                              </table>
                        </div>
                      
                  </div>
                         
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="usertype_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">User Type Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row">
                        <div class="col-md-12" id="usertype_alert_holder">
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Description</label>
                              <input type="text" class="form-control form-control-sm" id="usertype" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Reference #</label>
                              <input type="text" class="form-control form-control-sm" id="ref_num" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Resource Path</label>
                              <input type="text" class="form-control form-control-sm" id="resource_path">
                        </div>
                        <div class="col-md-12 form-group">
                              <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="withacad" >
                                    <label for="withacad">With Academic Program
                                    </label>
                              </div>
                        </div>
                        <div class="col-md-12 form-group">
                              <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="isdefault" >
                                    <label for="isdefault">Selection
                                    </label>
                              </div>
                        </div>
                        <div class="col-md-12 form-group">
                              <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="istatus" >
                                    <label for="istatus">Login
                                    </label>
                              </div>
                        </div>
                  </div>
                  <hr>
                  <div class="row">
                        <div class="col-md-12">
                              <button class="btn btn-sm btn-primary" id="usertype_button"><i class="fa fa-save"></i> Save</button>
                              <button class="btn btn-sm btn-success" id="usertype_button_update" hidden><i class="fa fa-save"></i> Save</button>
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
                        <h1>User Types</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">User Types</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<div id="alert_format" hidden>
      <div class="alert alert-danger alert-dismissible" role="alert" >
            <span id="usertype_alert_text">sdfsf</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
</div>


<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body" style="font-size:.8rem !important">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table-hover table table-striped table-sm table-bordered" id="usertype_datatable" width="100%" >
                                                                  <thead class="thead-light">
                                                                        <tr>
                                                                              <th width="30%" class="align-middle">User Type</th>
                                                                              <th width="11%" class="align-middle text-center p-0">With Acad</th>
                                                                              <th width="10%" class="align-middle text-center p-0">Selection</th>
                                                                              <th width="8%" class="align-middle text-center p-0">Login</th>
                                                                              <th width="8%" class="align-middle text-center p-0">Ref #</th>
                                                                              <th width="23%" class="align-middle text-center p-0"></th>
                                                                              <th width="5%" class="align-middle text-center p-0"></th>
                                                                              <th width="5%" class="align-middle text-center p-0"></th>
                                                                        </tr>
                                                                  </thead>
                                                            </table>
                                                      </div>
                                                </div>
                                                {{-- <div class="row">
                                                      <div class="col-md-12">
                                                            <i><b>Click the row to edit or delete user type.</b></i>
                                                      </div>
                                                </div> --}}
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-1 ">
                                                <div class="row"  style="font-size:.9rem !important">
                                                      <ul class="mb-0">
                                                            <li><label class="mb-0">Selection</label> : <i class="text-dark">determine if the type of user is included in the list of user types when creating accounts.</i></li>
                                                            <li><label class="mb-0">Login</label> : <i class="text-dark">determine if the type of user can log in to the system. </i></li>
                                                            <li><label class="mb-0">Reference #</label> : <i class="text-dark">reference to user types that are not included in the original user type</i></li>
                                                      </ul>
                                                     
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">

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

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var all_usertype = []
                  var selected_id = null


                  $(document).on('click','#reflist',function(){
                        $('#reflist_modal').modal()
                  })

                  $(document).on('click','#usertype_modal_create',function(){
                        $('#usertype_button').removeAttr('hidden')
                        $('#usertype_button_update').attr('hidden','hidden')
                        $('#usertype').val("")
                        $('#ref_num').val("")
                        $('#resource_path').val("")
                        $('#usertype').removeAttr('disabled')
                        $('#ref_num').removeAttr('disabled')
                        $('#isdefault').prop('checked',false)
                        $('#istatus').prop('checked',false)
                        $('#withacad').prop('checked',false)
                        $('#usertype_modal').modal()
                  })

                  $(document).on('click','.edit_utype',function(){

                        $('#usertype_button_update').removeAttr('hidden')
                        $('#usertype_button').attr('hidden','hidden')

                        selected_id = $(this).attr('data-id')

                        var temp_data = all_usertype.filter(x=>x.id == selected_id)
                        $('#usertype').val(temp_data[0].utype)
                        $('#ref_num').val(temp_data[0].refid)
                        $('#resource_path').val(temp_data[0].resourcepath)

                        if(selected_id <= 18){
                              $('#usertype').attr('disabled','disabled')
                              $('#ref_num').attr('disabled','disabled')
                              $('#ref_num').val(null)
                        }else{
                              $('#usertype').removeAttr('disabled')
                              $('#ref_num').removeAttr('disabled')
                        }

                       
                        $('#isdefault').prop('checked',false)
                        $('#istatus').prop('checked',false)
                        $('#withacad').prop('checked',false)

                        if(temp_data[0].constant == 1){
                              $('#isdefault').prop('checked',true)
                        }

                        if(temp_data[0].type_active == 1){
                              $('#istatus').prop('checked',true)
                        }

                        if(temp_data[0].with_acad == 1){
                              $('#withacad').prop('checked',true)
                        }

                        $('#usertype_modal').modal()
                  })

                  

                  $(document).on('click','.delete_utype',function(){

                        var id = $(this).attr('data-id')

                        Swal.fire({
                              title: 'Are you sure?',
                              text: "You want to remove user type?",
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, remove it!'
                        }).then((result) => {
                              if (result.value) {

                                    $.ajax({
                                          type:'GET',
                                          url:'/setup/usertype/delete',
                                          data:{
                                                id:id
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      display_usertype()
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
                              }
                        })
                  })

                  $(document).on('click','#usertype_button',function(){
                        $('#usertype_alert_holder').empty()

                        if($('#usertype').val() == ""){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Description is empty.'
                              })
                              return false;
                        }


                        var isstatus = 0;
                        var isdefault = 0;
                        var withacad = 0;

                        if($('#isdefault').prop('checked')){
                              isstatus = 1
                        }
                        if($('#istatus').prop('checked')){
                              isdefault = 1
                        }
                        if($('#withacad').prop('checked')){
                              withacad = 1
                        }

                        $.ajax({
                              type:'GET',
                              url:'/setup/usertype/create',
                              data:{
                                    description:$('#usertype').val(),
                                    ref_num:$('#ref_num').val(),
                                    resourcepath:$('#resource_path').val(),
                                    default:isstatus,
                                    status:isdefault,
                                    withacad:withacad

                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          display_usertype()
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


                  $(document).on('click','#usertype_button_update',function(){
                        $('#usertype_alert_holder').empty()
                        
                        if($('#usertype').val() == ""){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Description is empty.'
                              })
                              return false;
                        }


                        var isstatus = 0;
                        var isdefault = 0;
                        var withacad = 0;

                        if($('#isdefault').prop('checked')){
                              isstatus = 1
                        }
                        if($('#istatus').prop('checked')){
                              isdefault = 1
                        }
                        if($('#withacad').prop('checked')){
                              withacad = 1
                        }

                        $.ajax({
                              type:'GET',
                              url:'/setup/usertype/update',
                              data:{
                                    id:selected_id,
                                    description:$('#usertype').val(),
                                    ref_num:$('#ref_num').val(),
                                    resourcepath:$('#resource_path').val(),
                                    default:isstatus,
                                    status:isdefault,
                                    withacad:withacad
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          display_usertype()
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
                
                  display_usertype()

                  function display_usertype(){

                        $("#usertype_datatable").DataTable({
                              destroy: true,
                              autoWidth: false,
                              stateSave: true,
                              serverSide: true,
                              processing: true,
                              ajax:{
                                    url: '/setup/usertype/list',
                                    type: 'GET',
                                    dataSrc: function ( json ) {
                                          all_usertype = json.data
                                          return json.data;
                                    }
                              },

                              columns: [
                                          { "data": "utype" },
                                          { "data": "with_acad" },
                                          { "data": "constant" },
                                          { "data": "type_active" },
                                          { "data": "refid" },
                                          { "data": "resourcepath" },
                                          { "data": null },
                                          { "data": null },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.with_acad == 1){
                                                      $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                                }else{
                                                      $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }

                                                $(td).addClass('text-center')
                                               
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.constant == 1){
                                                      $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                                }else{
                                                      $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.type_active == 1){
                                                      $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                                }else{
                                                      $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.id > 18){
                                                      $(td).text(rowData.refid)
                                                }else{
                                                      $(td)[0].innerHTML = 'N / A'
                                                }
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                // if(rowData.id > 18){
                                                      var buttons = '<a href="javascript:void(0)" class="edit_utype" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                // }else{
                                                //       $(td).text(null)
                                                // }
                                          }
                                    },
                                    {
                                          'targets': 7,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.id > 18){
                                                      var buttons = '<a href="javascript:void(0)" class="delete_utype" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                }else{
                                                      $(td).text(null)
                                                }
                                          }
                                    },
                              ],
                              // createdRow: function (row, data, dataIndex) {
                              //       $(row).attr("data-id",data.teacherid);
                              //       $(row).addClass("update_fas");
                              // },
                        })

                        var label_text = $($('#usertype_datatable_wrapper')[0].children[0])[0].children[0]
                            
                        $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm mr-2" id="usertype_modal_create"><i class="fa fa-plus"></i> Create User Type</button> <button class="btn btn-secondary btn-sm mr-2" id="reflist"><i class="fa fa-book"></i> Reference List</button>'
                  }


            })
      </script>

@endsection


