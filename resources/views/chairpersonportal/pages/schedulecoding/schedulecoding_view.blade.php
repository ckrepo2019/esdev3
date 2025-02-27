@extends('pages.monitoring.layouts.app')


@section('headerscript')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
@endsection

@section('content')
      <div class="modal fade" id="add_versionnotes">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Notes</label>
                                          <textarea name="" id="input_notes" class="form-control" rows="2"></textarea>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button class="btn btn-primary btn-sm" id="save_versionnotes">Create</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="add_modulesversion">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Module Name</label>
                                          <input class="form-control" id="input_versionname">
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button class="btn btn-primary btn-sm" id="save_modulesversion">Create</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="add_schoolversion">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">School</label>
                                          <input class="form-control" id="input_schoolname" readonly>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Module</label>
                                          <input class="form-control" id="input_module" readonly>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Version</label>
                                          <select class="form-control" id="input_versionid">

                                          </select>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button class="btn btn-primary btn-sm" id="save_schoolversion"">Create</button>
                        </div>
                  </div>
            </div>
      </div>
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row mb-2">
                  <div class="col-sm-6">
                  </div>
                  <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/monitoring/modules">Modules</a></li>
                        <li class="breadcrumb-item active" id="breadcrumbs_modulesversion">...</li>
                        </ol>
                  </div>
                  </div>
            </div>
      </section>
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-3">
                        <div class="card card-primary">
                              <div class="card-header">
                                <h3 class="card-title">Module Information</h3>
                              </div>
                              <div class="card-body">
                                    <strong><i class="fas fa-book mr-1"></i> Module Name</strong>
                                    <p class="text-muted" id="label_modulename"></p>
                                    <hr>
                                    <strong><i class="fas fa-book mr-1"></i>Date Created</strong>
                                    <p class="text-muted" id="label_created"></p>
                              </div>
                            </div>
                  </div>
                  <div class="col-md-9">
                        <div class="card">
                              <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                          <li class="nav-item"><a class="nav-link active" href="#patch" data-toggle="tab">Patch</a></li>
                                          <li class="nav-item"><a class="nav-link " href="#schoollist" data-toggle="tab">School List</a></li>
                                          <li class="nav-item"><a class="nav-link" href="#update" data-toggle="tab">Update</a></li>
                                    </ul>
                              </div>
                              <div class="card-body">
                                    <div class="tab-content">
                                          <div class="tab-pane  active" id="patch">
                                                <div class="row">
                                                      <div class="col-md-6">
                                                           
                                                      </div>
                                                      <div class="col-md-6 text-right">
                                                            <button class="btn btn-sm btn-default mb-3" id="button_modulesversion"><i class="fas fa-plus"></i> Add Version</button>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-bordered  table-sm" id="module_version_table" width="100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="5%"></th>
                                                                              <th width="85%">Version</th>
                                                                              <th width="5%"></th>
                                                                              <th width="5%"></th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody >
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                      <div class="col-md-6">
                                                            <label for="">Patch Notes <span id="label_version"></span></label>
                                                      </div>
                                                      <div class="col-md-6 text-right">
                                                            <button class="btn btn-sm btn-default" id="button_versionnotes"><i class="fas fa-plus"></i>  Add Notes</button>
                                                      </div>
                                                </div>
                                            
                                                <div class="row mt-3">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-bordered  table-sm" id="patch_note_table" width="100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="90%">Version</th>
                                                                              <th width="5%"></th>
                                                                              <th width="5%"></th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody >
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                </div>
                                               
                                               
                                               
                                          </div>
                                          <div class="tab-pane " id="schoollist">
                                                <table class="table table-striped table-bordered table-sm" id="schoollist_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">School</th>
                                                                  <th width="40%">Version</th>
                                                                  <th width="35%">Updated Date</th>
                                                                  <th width="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody >
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="tab-pane" id="update">
                                                <div class="row">
                                                      <div class="col-md-12 form-group">
                                                            <label for="">Module Name</label>
                                                            <input class="form-control" id="input_modulename">
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-primary btn-sm" id="save_moduleinfo">Update Module</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
@endsection


@section('footerjavascript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
	<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
	<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
	<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
  

      
      <script>
            $(document).ready(function(){
                  var module = []
                  var module = @json($id);
                  get_module()
                  function get_module(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modules/list',
                              data:{
                                    id:module
                              },
                              success:function(data) {
                                    $('#label_modulename').text(data[0].modulename)
                                    $('#breadcrumbs_modulesversion').text(data[0].modulename)
                                    $('#input_modulename').val(data[0].modulename)
                                    $('#label_created').text(data[0].createddatetime)
                              }
                        })
                  }

                  $(document).on('click','#save_moduleinfo',function(){
                        update_module()
                  })


                  function update_module(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modules/update',
                              data:{
                                    modulename:$('#input_modulename').val(),
                                    id:module
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          get_module()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }

            })
      </script>
      <script>
            $(document).ready(function(){
                  get_schoollist()
                  var module = @json($id);
                  var school_list = []
                  loaddatatable_schoollist()
                  function get_schoollist(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/schoollist/module/version',
                              data:{
                                    moduleid:@json($id)
                              },
                              success:function(data) {
                                    school_list = data
                                    // temp_schoollist = data
                                    loaddatatable_schoollist()
                                    // get_version()
                              }
                        })
                  }

                  function loaddatatable_schoollist(){
                        $("#schoollist_table").DataTable({
                              destroy: true,
                              data:school_list,
                              columns: [
                                    { "data": "schoolabrv" },
                                    { "data": "versionname" },
                                    { "data": "createddatetime" },
                                    { "data": null },
                              ],
                              columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).attr('data-cell','schoolname')
                                                      $(td).attr('data-id',rowData.id)
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).attr('data-id',rowData.id)
                                                      $(td).attr('data-cell','version')
                                                      $(td).attr('data-version',rowData.versionid)
                                                      if(rowData.isactive == 1){
                                                            $(td).addClass("bg-success")
                                                      }
                                                      else{
                                                            $(td).addClass("bg-danger") 
                                                      }
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).attr('data-id',rowData.id)
                                                      $(td).attr('data-cell','updateddatetime')
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="button_schoolversion" data-id="'+rowData.id+'"><i class="fas fa-edit "></i></a>'
                                                      $(td).addClass('text-center')
                                                }
                                          },
                              ]
                              
                        })
                  }


                  //school module version
                  var schoolversion_list = []
                  var selected_school = null;
                  var module = @json($id);

                  var info_status = 'create'

                  $(document).on('click','.button_schoolversion',function(){
                        selected_school = $(this).attr('data-id')
                        info_status = 'create'
                        $('#input_schoolname').val($('td[data-id="'+selected_school+'"][data-cell="schoolname"]').text())
                        $('#input_module').val($('#label_modulename').text())
                        $('#input_versionid').val($('td[data-id="'+selected_school+'"][data-cell="version"]').attr('data-version')).change()
                        $('#save_schoolversion').text('Update Version')
                        $('#add_schoolversion').modal()
                  })

                  $(document).on('click','#save_schoolversion',function(){
                        add_schoolversion()
                  })

                  $(document).on('click','.update_schoolversion',function(){
                        info_status = 'update'
                        $('#save_schoolversion').text('Update')
                        $('#add_schoolversion').modal()
                        selected_moduleversion = $(this).attr('data-id')
                        var temp_schoolversion = schoolversion_list.filter(x=>x.id == selected_moduleversion)
                        $('#input_versionname').val(temp_schoolversion[0].versionname)
                  })

                  function add_schoolversion(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/schoolmodulesversion/create',
                              data:{
                                    schoolid:selected_school,
                                    moduleid:module,
                                    versionid:$('#input_versionid').val()
                              },
                              success:function(data) {
                                    console.log(data)
                                    if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
                                          });
                                          var temp_schoollist_index = school_list.findIndex(x=>x.id==selected_school)
                                          school_list[temp_schoollist_index].versionname = $('option[value="'+$('#input_versionid').val()+'"]').text()
                                          school_list[temp_schoollist_index].createddatetime = data[0].date
                                          school_list[temp_schoollist_index].versionid = $('#input_versionid').val()
                                          if($('.check_button[data-id="'+$('#input_versionid').val()+'"]').prop('checked') == true){
                                                $('td[data-cell="version"][data-id="'+selected_school+'"]').removeClass('bg-danger')
                                                $('td[data-cell="version"][data-id="'+selected_school+'"]').addClass('bg-success')
                                                school_list[temp_schoollist_index].isactive = 1
                                          }else{
                                                $('td[data-cell="version"][data-id="'+selected_school+'"]').removeClass('bg-success')
                                                $('td[data-cell="version"][data-id="'+selected_school+'"]').addClass('bg-danger')
                                                school_list[temp_schoollist_index].isactive = 0
                                          }
                                          $('td[data-cell="version"][data-id="'+selected_school+'"]').text($('option[value="'+$('#input_versionid').val()+'"]').text())
                                          $('td[data-cell="version"][data-id="'+selected_school+'"]').attr('data-version',$('#input_versionid').val())
                                          $('#add_schoolversion').modal('hide')
                                          $('td[data-cell="updateddatetime"][data-id="'+selected_school+'"]').text(data[0].date)
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'success',
                                                title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
                                          });
                                    }
                              }
                        })
                  }
                  //school module version

            })


            
      </script>


      <script>
            $(document).ready(function(){
                  
                  var modulesversion_list = []
                  var selected_moduleversion = null;
                  var module = @json($id);

                  var info_status = 'create'

                  get_modulesversion()

                  $(document).on('click','#button_modulesversion',function(){
                        info_status = 'create'
                        $('#save_modulesversion').text('Create')
                        $('#add_modulesversion').modal()
                  })

                  $(document).on('click','#save_modulesversion',function(){
                        if(info_status == 'create'){
                              add_modulesversion()
                        }else if(info_status == 'update'){
                              update_modulesversion()
                        }
                  })

                  $(document).on('click','.update_modulesversion',function(){
                        info_status = 'update'
                        $('#save_modulesversion').text('Update')
                        $('#add_modulesversion').modal()
                        selected_moduleversion = $(this).attr('data-id')
                        var temp_modulesversion = modulesversion_list.filter(x=>x.id == selected_moduleversion)
                        $('#input_versionname').val(temp_modulesversion[0].versionname)
                  })


                  $(document).on('click','.check_button',function(){
                        selected_moduleversion = $(this).attr('data-id')
                        updateactive_modulesversion()
                  })
                  
                  $(document).on('click','.delete_modulesversion',function(){
                        selected_moduleversion = $(this).attr('data-id')
                        remove_modulesversion()
                  })



                  

                  function add_modulesversion(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modulesversion/create',
                              data:{
                                    versionname:$('#input_versionname').val(),
                                    moduleid:module
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});

                                          modulesversion_list.push({
                                                versionname:$('#input_versionname').val(),
                                                id:data[0].id
                                          })

                                          $('#input_versionid').append('<option data-id="hello" value="'+data[0].id+'">'+$('#input_versionname').val()+'</option>')
                                          loaddatatable_modulesversion()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }

                  function update_modulesversion(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modulesversion/update',
                              data:{
                                    versionname:$('#input_versionname').val(),
                                    moduleid:module,
                                    id:selected_moduleversion
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          var modulesversion_list_index = modulesversion_list.findIndex(x=>x.id == selected_moduleversion)
                                          modulesversion_list[modulesversion_list_index].versionname = $('#input_versionname').val()
                                          $('#add_modulesversion').modal('hide')
                                          loaddatatable_modulesversion()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }

                  
                  function remove_modulesversion(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modulesversion/delete',
                              data:{
                                    id:selected_moduleversion
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          modulesversion_list = modulesversion_list.filter(x=>x.id != selected_moduleversion)
                                          loaddatatable_modulesversion()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                        
                  }

                  function updateactive_modulesversion(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modulesversion/update/active',
                              data:{
                                    moduleid:module,
                                    id:selected_moduleversion
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          $.each(modulesversion_list,function(a,b){
                                                b.isactive = 0
                                          })

                                          var modulesversion_list_index = modulesversion_list.findIndex(x=>x.id == selected_moduleversion)
                                          modulesversion_list[modulesversion_list_index].isactive = 1

                                         

                                          loaddatatable_modulesversion()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }
                  
                  function get_modulesversion(){

                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modulesversion/list',
                              data:{
                                    moduleid:module
                              },
                              success:function(data) {
                                    modulesversion_list = data
                                    loaddatatable_modulesversion()

                                    $.each(modulesversion_list,function(a,b){
                                          $('#input_versionid').append('<option value="'+b.id+'">'+b.versionname+'</option>')
                                    })
                                    
                              }
                        })
                  }

                  function loaddatatable_modulesversion(){
                        $("#module_version_table").DataTable({
                              destroy: true,
                              data:modulesversion_list,
                              columns: [
                                    { "data": null },
                                    { "data": "versionname" },
                                    { "data": null },
                                    { "data": null },
                              ],
                              columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.isactive == 1){
                                                            $(td)[0].innerHTML = '<input class="check_button" checked="checked" type="checkbox" data-id="'+rowData.id+'">'
                                                      }else{
                                                            $(td)[0].innerHTML = '<input class="check_button" type="checkbox" data-id="'+rowData.id+'">'
                                                      }
                                                     
                                                      $(td).addClass('text-center')
                                                   
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="version" data-id="'+rowData.id+'">'+rowData.versionname+'</a>'
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="update_modulesversion" data-id="'+rowData.id+'"><i class="fas fa-edit "></i></a>'
                                                      $(td).addClass('text-center')
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="text-danger delete_modulesversion" data-id="'+rowData.id+'"><i class="far fa-trash-alt" ></i></a>'
                                                      $(td).addClass('text-center')
                                                }
                                          }

                                                
                              ]
                              
                        })
                  }

            })
      </script>
      <script>
            $(document).ready(function(){
                  
                  var versionnotes_list = []
                  var selected_moduleversion = null;
                  var selected_versionnotes
                  var module = @json($id);

                  var info_status = 'create'

                  get_modulesversion()

                  function get_modulesversion(){

                        $.ajax({
                              type:'GET',
                              url: '/monitoring/modulesversion/list',
                              data:{
                                    moduleid:module
                              },
                              success:function(data) {
                                    $.each(data,function(a,b){
                                          if(b.isactive == 1){
                                                selected_moduleversion = b.id
                                                $('#label_version').text('( ' + b.versionname + ' )')
                                                get_versionnotes()
                                          }
                                    })
                              }
                        })
                  }


                  $(document).on('click','#button_versionnotes',function(){
                        info_status = 'create'
                        $('#save_versionnotes').text('Create')
                        $('#add_versionnotes').modal()
                  })

                  $(document).on('click','#save_versionnotes',function(){
                        if(info_status == 'create'){
                              add_versionnotes()
                        }else if(info_status == 'update'){
                              update_versionnotes()
                        }
                  })


                  
                  $(document).on('click','.version',function(){
                        selected_moduleversion = $(this).attr('data-id')
                        $('#label_version').text('( ' + $(this).text() + ' )')
                        get_versionnotes()
                  })

                  $(document).on('click','.update_versionnotes',function(){
                        info_status = 'update'
                        $('#save_versionnotes').text('Update')
                        $('#add_versionnotes').modal()
                        selected_versionnotes = $(this).attr('data-id')
                        var temp_versionnotes = versionnotes_list.filter(x=>x.id == selected_versionnotes)
                        $('#input_notes').val(temp_versionnotes[0].notes)
                  })
                  
                  $(document).on('click','.delete_versionnotes',function(){
                        selected_versionnotes = $(this).attr('data-id')
                        remove_versionnotes()
                  })

                  function add_versionnotes(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/versionnotes/create',
                              data:{
                                    notes:$('#input_notes').val(),
                                    moduleid:module,
                                    versionid:selected_moduleversion
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          get_versionnotes()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }

                  function update_versionnotes(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/versionnotes/update',
                              data:{
                                    notes:$('#input_notes').val(),
                                    moduleid:module,
                                    versionid:selected_moduleversion,
                                    id:selected_versionnotes
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          var versionnotes_list_index = versionnotes_list.findIndex(x=>x.id == selected_versionnotes)
                                          versionnotes_list[versionnotes_list_index].notes = $('#input_notes').val()
                                          $('#add_versionnotes').modal('hide')
                                          loaddatatable_versionnotes()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }

                  
                  function remove_versionnotes(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/versionnotes/delete',
                              data:{
                                    id:selected_versionnotes
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          versionnotes_list = versionnotes_list.filter(x=>x.id != selected_versionnotes)
                                          loaddatatable_versionnotes()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                        
                  }

                  function updateactive_versionnotes(){
                        $.ajax({
                              type:'GET',
                              url: '/monitoring/versionnotes/update/active',
                              data:{
                                    moduleid:module,
                                    id:selected_moduleversion
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          $.each(versionnotes_list,function(a,b){
                                                b.isactive = 0
                                          })

                                          var versionnotes_list_index = versionnotes_list.findIndex(x=>x.id == selected_moduleversion)
                                          versionnotes_list[versionnotes_list_index].isactive = 1

                                         

                                          loaddatatable_versionnotes()
                                   }
                                   else{
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                   }
                              }
                        })
                  }
                  
                  function get_versionnotes(){

                        $.ajax({
                              type:'GET',
                              url: '/monitoring/versionnotes/list',
                              data:{
                                    moduleid:module,
                                    versionid:selected_moduleversion
                              },
                              success:function(data) {
                                    versionnotes_list = data
                                    loaddatatable_versionnotes()
                                    
                              }
                        })
                  }

                  function loaddatatable_versionnotes(){
                        $("#patch_note_table").DataTable({
                              destroy: true,
                              data:versionnotes_list,
                              columns: [
                                    { "data": "notes" },
                                    { "data": null },
                                    { "data": null },
                              ],
                              columnDefs: [
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="update_versionnotes" data-id="'+rowData.id+'"><i class="fas fa-edit "></i></a>'
                                                      $(td).addClass('text-center')
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="text-danger delete_versionnotes" data-id="'+rowData.id+'"><i class="far fa-trash-alt" ></i></a>'
                                                      $(td).addClass('text-center')
                                                }
                                          }

                                                
                              ]
                              
                        })
                  }
            })
      </script>

@endsection
