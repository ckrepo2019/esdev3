@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      } else if(Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      } 

      // dd(Session::get('currentPortal'))
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

      $fas = DB::table('teacher')
                  ->where('deleted',0)
                  ->where('isactive',1)
                  ->where('usertypeid',3)
                  ->select(
                        DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as username"),
                        'tid',
                        'id'
                  )
                  ->get();

      $faspriv = DB::table('faspriv')
                  ->where('faspriv.deleted',0)
                  ->join('teacher',function($join){
                        $join->on('faspriv.userid','=','teacher.userid');
                        $join->where('teacher.deleted',0);
                        $join->where('teacher.isactive',1);
                  })
                  ->where('usertype',3)
                  ->select(
                        DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as username"),
                        'teacher.tid',
                        'teacher.id'
                  )
                  ->distinct()
                  ->get();

      $fas = $fas->merge($faspriv);

@endphp


<div class="modal fade" id="add_user_modal" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog ">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">User Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12  form-group">
                                    <label for="">User</label>
                                    <select class="form-control select2 form-control-sm" id="input_user">
                                          @foreach ($fas as $item)
                                                <option value="{{$item->id}}">{{$item->tid}} - {{$item->username}}</option>
                                          @endforeach
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="add_user">Add User</button>
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
                        <h1>Grade Completion Access</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grade Completion Access</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
           
            <div class="row">
                  <div class="col-md-12">
                        <div class="row ">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-1 pl-3">
                                                <p class="mb-0">This module is designed to set up users access to college completion grades.</p>
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
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="grade_access_list" width="100%" style="font-size:.9rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th width="8%">TID</th>
                                                                  <th width="22%">User</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">All</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Edit</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Pending</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Approve</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Post</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Unpost</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Deadline</th>
                                                                  <th width="8%" class="text-center" style="font-size:.8rem !important">Print</th>
                                                                  <th width="6%" class="text-center" style="font-size:.8rem !important"></th>
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

            var all_list = []
            var all_users = @json($fas)

            $('#input_user').select2({
                  'placeholder':'Select User',
                  'data':all_users,
                  'allowclear':true
            })

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })


            $(document).on('click','.gradeaccess_assign',function(){

                  var status = 0;
                  var access = $(this).attr('data-priv')
                  var tid = $(this).attr('data-tid')

                  if($(this).prop('checked')){
                        status = 1
                  }

                  if(status == 0){
                        $('#'+tid+'-all').prop('checked',false)
                  }else{
                        var all = true
                        $('.gradeaccess_assign[data-tid="'+tid+'"]').each(function(a,b){
                              if($(b).attr('id') != $(b).attr('data-tid')+'-all'){
                                    if(!$(b).prop('checked')){
                                          all = false
                                    }
                              }
                        })
                        if(all){
                              $('#'+tid+'-all').prop('checked',true)
                        }
                  }

                  update_status(tid,access,status)
                  
            })

            $(document).on('click','#add_user_modal_button',function(){
                  $('#add_user_modal').modal()
                  $('#input_user').val("").change()
            })

            
            $(document).on('click','.remove_user',function(){
                  var id = $(this).attr('data-id')
                  Swal.fire({
                              title: 'Do you want to remove user?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    remove_user(id)
                              }
                        })    
            })

            function remove_user(id){
                  $.ajax({
                        type:'GET',
                        url: '/college/grade/access/removeuser',
                        data:{
                              id:id,
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    get_gradeaccess()
                              }else{
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].message
                                    })
                                  
                              }
                        }
                  })
            }

            function update_status(tid,access,status){
                  $.ajax({
                        type:'GET',
                        url: '/college/grade/access/updatestatus',
                        data:{
                              syid:$('#filter_sy').val(),
                              tid:tid,
                              access:access,
                              status:status
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })

                                    if(access == 'all'){
                                          $('.gradeaccess_assign[data-tid="'+tid+'"]').prop('checked',true)
                                    }
                              }else{
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].message
                                    })
                                  
                              }

                              
                        }
                  })
            }

            $(document).on('click','#add_user',function(){
                  add_users()
            })

            function add_users(){

                  if($('#input_user').val() == "" || $('#input_user').val() == null){
                        Toast.fire({
                              type: 'warning',
                              title: "Please Select User!"
                        })
                        return false;
                  }
                  
                  $.ajax({
                        type:'GET',
                        url:'/college/grade/access/adduser',
                        data:{
                              user:$('#input_user').val()
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    get_gradeaccess()
                              }else{
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].message
                                    })
                                  
                              }
                              
                        }
                  })
            }


            get_gradeaccess()

             function get_gradeaccess(){
                  
                  $.ajax({
                        type:'GET',
                        url:'/college/grade/access/list',
                        success:function(data) {
                              all_list = data
                              gradeaccess_datatable()
                        }
                  })
            }
            

            function gradeaccess_datatable(){

                  $("#grade_access_list").DataTable({
                        destroy: true,
                        data:all_list,
                        autoWidth: false,
                        stateSave: true,
                        lengthChange : false,
                        columns: [
                                    { "data": "tid" },
                                    { "data": "username" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null }
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
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canapprove== 1 &&
                                                rowData.canedit== 1 &&
                                                rowData.canpending== 1 &&
                                                rowData.canpost== 1 &&
                                                rowData.canprint== 1 &&
                                                rowData.cansetupdeadline== 1 &&
                                                rowData.canunpost== 1
                                          ){
                                                selected = 'checked="checked"'
                                          }

                                          var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-all" data-tid="`+rowData.teacherid+`" data-priv="all"> <label for="`+rowData.teacherid+`-all" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canedit== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-canedit" data-tid="`+rowData.teacherid+`" data-priv="canedit"> <label for="`+rowData.teacherid+`-canedit" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canpending== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-canpending" data-tid="`+rowData.teacherid+`" data-priv="canpending"> <label for="`+rowData.teacherid+`-canpending" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canapprove== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-canapprove" data-tid="`+rowData.teacherid+`" data-priv="canapprove"> <label for="`+rowData.teacherid+`-canapprove" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canpost== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-canpost" data-tid="`+rowData.teacherid+`" data-priv="canpost"> <label for="`+rowData.teacherid+`-canpost" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 7,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canunpost== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-canunpost" data-tid="`+rowData.teacherid+`" data-priv="canunpost"> <label for="`+rowData.teacherid+`-canunpost" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 8,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.cansetupdeadline== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-cansetupdeadline" data-tid="`+rowData.teacherid+`" data-priv="cansetupdeadline"> <label for="`+rowData.teacherid+`-cansetupdeadline" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 9,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.canprint== 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="gradeaccess_assign" id="`+rowData.teacherid+`-canprint" data-tid="`+rowData.teacherid+`" data-priv="canprint"> <label for="`+rowData.teacherid+`-canprint" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 10,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var buttons = '<a href="#" class="remove_user" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                             
                        ]
                  });

                  var label_text = $($('#grade_access_list_wrapper')[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm" id="add_user_modal_button"><i class="fa fa-plus"></i>Add User</button>'
            }

      </script>

@endsection


