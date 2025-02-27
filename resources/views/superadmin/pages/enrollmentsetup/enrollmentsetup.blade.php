
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <style>
            .select2-selection{
                height: calc(2.25rem + 2px) !important;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->get(); 
      $semester = DB::table('semester')->get(); 
      $acadprog = DB::table('academicprogram')->get(); 
@endphp

<div class="modal fade" id="modal_enrollmentsetup" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Academic Program</label>
                                    <select name="" id="input_acadprogid" class="form-control">
                                          @foreach ($acadprog as $item)
                                              <option value="{{$item->id}}">{{$item->progname}}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">School Year</label>
                                    <select name="" id="input_syid" class="form-control">
                                          @foreach ($sy as $item)
                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-12 form-group" hidden id="holder_semid">
                                    <label for="">School Year</label>
                                    <select name="" id="input_semid" class="form-control">
                                          @foreach ($semester as $item)
                                              <option value="{{$item->id}}">{{$item->semester}}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Date Start</label>
                                    <input id="input_datestart" class="form-control" type="date">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Date End</label>
                                    <input id="input_dateend" class="form-control" type="date">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Enrollment Type</label>
                                    <select name="" id="input_type" class="form-control">
                                          <option value="1">Regular Enrollment</option>
                                          <option value="2">Early Enrollment</option>
                                    </select>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="create_enrollmentsetup">Create</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>    

<section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Attendance Setup</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header border-0">
                                    <button class="btn btn-primary btn-sm" id="button_enrollmentsetup"><i class="fas fa-plus"></i> Add Enrollment Setup</button>
                              </div>
                              <div class="card-body">
                                   
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="attendance_setup" width="100%">
                                                      <thead>
                                                            <tr> 
                                                                  <th width="5%"></th>
                                                                  <th width="15%">Academic Program</th>
                                                                  <th width="10%">Type</th>
                                                                  <th width="20%">School Year</th>
                                                                  <th width="20%">Semester</th>
                                                                  <th width="10%">Date Start</th>
                                                                  <th width="10%">Date End</th>
                                                                  <th width="5%"></th>
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

                  var all_enrollmentsetup = []
                  var selected_enrollmentsetup
                  var process = 'create'

                  get_enrollmentsetup()

                  $(document).on('click','#button_enrollmentsetup',function(){
                        process = 'create'
                        $('#create_enrollmentsetup').text('Create')  
                        $('#modal_enrollmentsetup').modal()    
                  })

                  $(document).on('change','#input_acadprogid',function(){
                       if($(this).val() == 5 || $(this).val() == 6){
                             $('#holder_semid').removeAttr('hidden')
                       }else{
                              $('#holder_semid').attr('hidden','hidden')
                       }
                  })


                  $(document).on('click','#create_enrollmentsetup',function(){
                        if(process == 'create'){
                              create_enrollmentsetup()           
                        }else if(process == 'edit'){
                              update_enrollmentsetup()  
                        }              
                  })

                  $(document).on('click','.delete_enrollmentsetup',function(){
                        selected_enrollmentsetup = $(this).attr('data-id')
                        delete_enrollmentsetup()
                  })

                  $(document).on('click','.edit_enrollmentsetup',function(){
                        selected_enrollmentsetup = $(this).attr('data-id')
                        process = 'edit'
                        var temp_attendance_id = all_enrollmentsetup.filter(x=>x.id == selected_enrollmentsetup)
                        $('#input_syid').val(temp_attendance_id[0].syid).change()
                        $('#input_semid').val(temp_attendance_id[0].semid)
                        $('#input_acadprogid').val(temp_attendance_id[0].acadprogid).change()
                        $('#input_datestart').val(temp_attendance_id[0].enrollmentstart_format1)
                        $('#input_dateend').val(temp_attendance_id[0].enrollmentend_format1)
                        $('#input_type').val(temp_attendance_id[0].type)
                        $('#modal_enrollmentsetup').modal()   
                        $('#create_enrollmentsetup').text('Update')           
                  })

                  function get_enrollmentsetup(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/enrollmentsetup/list',
					success:function(data) {
						all_enrollmentsetup = data
                                    loaddatatable()
					}
				})
                  }
      
                  function create_enrollmentsetup(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/enrollmentsetup/create',
					data:{
						syid:$('#input_syid').val(),
                                    semid:$('#input_semid').val(),
                                    acadprogid:$('#input_acadprogid').val(),
                                    enrollmentstart:$('#input_datestart').val(),
                                    enrollmentend:$('#input_dateend').val(),
                                    type:$('#input_type').val(),
					},
					success:function(data) {
						if(data[0].status == 1){
							Swal.fire({
								type: 'success',
								title: data[0].data,
							});

							all_enrollmentsetup.push({
                                                'id':data[0].id,
                                                'syid':$('#input_sort').val(),
                                                'semid':$('#input_sy').val(),
                                                'acadprogid':$('#input_acadprogid').val(),
                                                'progname':$('#input_acadprogid option[value="'+$('#input_acadprogid').val()+'"]').text(),
                                                'enrollmentstart':data[0].enrollmentstart,
                                                'enrollmentend':data[0].enrollmentend,
                                                'enrollmentstart_format1':data[0].enrollmentstart_format1,
                                                'enrollmentend_format1':data[0].enrollmentend_format1,
                                                'sydesc':$('#input_syid option[value="'+$('#input_syid').val()+'"]').text(),
                                                'semester':$('#input_semid option[value="'+$('#input_semid').val()+'"]').text(),
                                                'type':$('#input_type').val(),
                                          })

                                          loaddatatable()

						}else{
							Swal.fire({
								type: 'error',
								title: data[0].data,
							});
						}
					}
				})
                  }

                  function update_enrollmentsetup(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/enrollmentsetup/update',
					data:{
						syid:$('#input_syid').val(),
                                    semid:$('#input_semid').val(),
                                    acadprogid:$('#input_acadprogid').val(),
                                    enrollmentstart:$('#input_datestart').val(),
                                    enrollmentend:$('#input_dateend').val(),
                                    type:$('#input_type').val(),
                                    id:selected_enrollmentsetup
					},
					success:function(data) {
						if(data[0].status == 1){
							Swal.fire({
								type: 'success',
								title: data[0].data,
							});
                                          get_enrollmentsetup()
						}else{
							Swal.fire({
								type: 'error',
								title: data[0].data,
							});
						}
					}
				})
                  }

                  function delete_enrollmentsetup(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/enrollmentsetup/delete',
					data:{
						id:selected_enrollmentsetup
					},
					success:function(data) {
						if(data[0].status == 1){
							Swal.fire({
								type: 'success',
								title: data[0].data,
							});
                                          all_enrollmentsetup = all_enrollmentsetup.filter(x=>x.id != selected_enrollmentsetup)
                                          loaddatatable()
						}else{
							Swal.fire({
								type: 'error',
								title: data[0].data,
							});
						}
					}
				})
                  }


                  $(document).on('click','.update_active',function(){
                        var temp_id = $(this).attr('data-id')
                        $.ajax({
					type:'GET',
					url: '/superadmin/enrollmentsetup/update/active',
					data:{
						id:temp_id
					},
					success:function(data) {
						if(data[0].status == 1){
							Swal.fire({
								type: 'success',
								title: data[0].data,
							});
                                          get_enrollmentsetup()
						}else{
							Swal.fire({
								type: 'error',
								title: data[0].data,
							});
						}
					}
				})
                  })

                  function loaddatatable(){
					
                        $("#attendance_setup").DataTable({
                                    destroy: true,
                                    scrollX: true,
                                     autoWidth: false,
                                    data:all_enrollmentsetup,
                                    fixedColumns:   {
                                          leftColumns: 2,
                                          rightColumns: 2
                                    },
                                    columns: [
                                          { "data": null},
                                          { "data": "progname" },
                                          { "data": "type" },
                                          { "data": "sydesc" },
                                          { "data": "semester" },
                                          { "data": "enrollmentend" },
                                          { "data": "enrollmentend" },
                                          { "data": "type" },
                                          { "data": "enrollmentend" },
                                        
                                    ],

                                    columnDefs: [
                                                      {
                                                            'targets': 0,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  $(td).addClass('text-center')
                                                                  var ischecked = ''
                                                                  if(rowData.isactive == 1){
                                                                        ischecked = 'checked="checked"'
                                                                  }
                                                                  $(td)[0].innerHTML = '<input type="checkbox" class="update_active" data-id="'+rowData.id+'" '+ischecked+'>'
                                                            }
                                                      },
                                                      {
									      'targets': 2,
										'orderable': true, 
										'createdCell':  function (td, cellData, rowData, row, col) {
											if(rowData.type == 1){
                                                                        $(td).text('Regular')
                                                                  }else if(rowData.type == 2){
                                                                        $(td).text('Early')
                                                                  }
										}
                                    			},
                                                      {
										'targets': 4,
										'orderable': true, 
										'createdCell':  function (td, cellData, rowData, row, col) {
											if(rowData.acadprogid == 5 || rowData.acadprogid == 6){
                                                                        $(td).text(rowData.semester)
                                                                  }else{
                                                                        $(td).text('N/A')
                                                                  }
										}
                                    			},
                                                      {
										'targets': 7,
										'orderable': true, 
										'createdCell':  function (td, cellData, rowData, row, col) {
											var buttons = '<a href="#" class="edit_enrollmentsetup" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
											$(td)[0].innerHTML =  buttons
											$(td).addClass('text-center')
                                                              
										}
                                    			},
                                                      {
										'targets': 8,
										'orderable': true, 
										'createdCell':  function (td, cellData, rowData, row, col) {
											var buttons = '<a href="#" class="delete_enrollmentsetup" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
											$(td)[0].innerHTML =  buttons
											$(td).addClass('text-center')
										}
                                    			},
								]
                        });
                  
                  }



            })
      </script>


@endsection


