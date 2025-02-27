@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
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
            .select2-selection--single{
                height: calc(2.25rem + 2px) !important;
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
      $schoolinfo = DB::table('schoolinfo')->first();
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Project Setup</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Project Setup</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-6">
                        <div class="card">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <label for="">Project Setup</label>
                                          </div>
                                    </div>
                                    <div class="row form-group">
                                          <div class="col-md-12">
                                                <div class="icheck-primary d-inline">
                                                      <input type="radio" name="projectsetup" id="online" value="online">
                                                      <label for="online">Online</label>
                                                </div>
                                                <div class="icheck-primary d-inline ml-3">
                                                      <input type="radio" name="projectsetup" id="offline" value="offline">
                                                      <label for="offline"> Offline</label>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <label for="">Proccess Type</label>
                                          </div>
                                    </div>
                                    <div class="row form-group">
                                          <div class="col-md-12">
                                                <div class="icheck-primary d-inline">
                                                      <input type="radio" name="processsetup" id="all" value="all">
                                                      <label for="all">All Online / Offline</label>
                                                </div>
                                                <div class="icheck-primary d-inline ml-3">
                                                      <input type="radio" name="processsetup" id="hybrid1" value="hybrid1">
                                                      <label for="hybrid1"> Hybrid 1</label>
                                                </div>
                                                <div class="icheck-primary d-inline ml-3">
                                                      <input type="radio" name="processsetup" id="hybrid2" value="hybrid2">
                                                      <label for="hybrid2"> Hybrid 2</label>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row form-group" hidden id="onlinelink_holder">
                                          <div class="col-md-12 form-group">
                                                <label for="">Cloud Link</label>
                                                <input id="onlinelink" class="form-control form-control-sm">
                                          </div>
                                          <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm" id="onlinelink_button">Update Cloud Link</button>
                                          </div>
                                    </div>
                                    
                              </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="card">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-sm display table-striped p-0" id="usertype_datatable" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="95%">Usertype</th>
                                                                  <th width="5%"></th>
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
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var essentiellink = @json(url('/'))

                

                  var schoolinfo = @json($schoolinfo);
                  var usertype = []


                  if(schoolinfo.projectsetup != null){
                        $('input[name="projectsetup"][value="'+schoolinfo.projectsetup+'"]').prop('checked',true)
                  }
                 
                  if(schoolinfo.processsetup != null){
                        $('input[name="processsetup"][value="'+schoolinfo.processsetup+'"]').prop('checked',true)
                        $('#onlinelink').val(schoolinfo.es_cloudurl)
                        if(schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2'){
                              $('#onlinelink_holder').removeAttr('hidden')
                        }else{
                              $('#onlinelink_holder').attr('hidden','hidden')
                        }
                  }

                  if(schoolinfo.essentiellink != essentiellink){
                        $.ajax({
					type:'GET',
					url: '/project/setup/update/essentiellink',
                              data:{
                                    'essentiellink':essentiellink
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                                   
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  }
            
                  $(document).on('click','#onlinelink_button',function(){
                        var onlinelink = $('#onlinelink').val()
                        $.ajax({
					type:'GET',
					url: '/project/setup/update/onlinelink',
                              data:{
                                    'onlinelink':onlinelink
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                                   
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  })

                  $(document).on('change','input[name="projectsetup"]',function(){
                        var projectype = $(this).val()
                        $.ajax({
					type:'GET',
					url: '/project/setup/update/projectsetup',
                              data:{
                                    'projectsetup':projectype
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          schoolinfo.projectsetup = projectype
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })

                                          if( ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' ) && schoolinfo.projectsetup == 'offline'){
                                                $('#onlinelink_holder').removeAttr('hidden')
                                          }else{
                                                $('#onlinelink_holder').attr('hidden','hidden')
                                          }
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                                   
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  })

                  $(document).on('change','input[name="processsetup"]',function(){
                        var processsetup = $(this).val()
                        $.ajax({
					type:'GET',
					url: '/project/setup/update/processsetup',
                              data:{
                                    'processsetup':processsetup
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          schoolinfo.processsetup = processsetup
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          if( ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' ) && schoolinfo.projectsetup == 'offline'){
                                                $('#onlinelink_holder').removeAttr('hidden')
                                          }else{
                                                $('#onlinelink_holder').attr('hidden','hidden')
                                          }
                                          get_all_usertype()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  })

                  get_all_usertype()
                  function get_all_usertype(){
                        $.ajax({
					type:'GET',
					url: '/project/setup/usertypes',
					success:function(data) {
                                    usertype = data
                                    load_usertype_datatable()
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  }

                  function load_usertype_datatable(){

                        $("#usertype_datatable").DataTable({
                              destroy: true,
                              data:usertype,
                              lengthChange : false,
                              autoWidth:false,
                              columns: [
                                          { "data": "utype" },
                                          { "data": null }
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var selected = '';
                                                if(rowData.type_active == 1){
                                                      selected = 'checked="checked"'
                                                }
                                                var text = '<div class="icheck-success d-inline"><input '+selected+' type="checkbox" id="type_stat'+rowData.id+'" class="update_type_status" data-id="'+rowData.id+'" disabled="disabled"><label for="type_stat'+rowData.id+'"></label></div>'
                                                $(td)[0].innerHTML = text
                                          }
                                    },
                              ]
                              
                        });

                        var label_text = $($("#usertype_datatable_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<label for="">Enabled User Types</label>'
                  }

            })
      </script>
      
@endsection


