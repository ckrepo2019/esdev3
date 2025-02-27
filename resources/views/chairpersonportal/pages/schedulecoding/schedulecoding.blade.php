@extends('chairpersonportal.layouts.app2')


@section('headerscript')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <style>
            .select2-container .select2-selection--single {
                    height: 40px;
              }
      </style>
@endsection

@section('content')
      @php
            $sy = DB::table('sy')->get();
            $semester = DB::table('semester')->get();
      @endphp

      <div class="modal fade" id="schedulecoding_modal">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">School Year</label>
                                          <select class="form-control" id="input_sy">
                                                @foreach ($sy as $item)
                                                      <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected' : ''}}>{{$item->sydesc}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group" >
                                          <label for="">Semester</label>
                                          <select class="form-control" id="input_sem">
                                                @foreach ($semester as $item)
                                                      <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected' : ''}}>{{$item->semester}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Type</label>
                                          <select class="form-control select2" id="input_type">
                                                <option value="">Regular Subject</option>
                                                <option value="RTS">Requested Subject (Less than 10 students)</option>
                                                <option value="RS">Requested Subject (More than 10 students)</option>
                                                <option value="CRE">Credited Subject</option>
                                                <option value="RE">Re-enrolled Subject</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Mode of Learning (Optional)</label>
                                          <select class="form-control select2" id="input_mod">
                                                <option value="">Select Mode of Learning</option>
                                                <option value="Online">Online</option>
                                                <option value="Modular">Modular</option>
                                          </select>
                                    </div>
                              </div>
                               <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Course (Optional)</label>
                                          @php
                                                $courses = DB::table('college_courses')->where('deleted',0)->select('courseabrv','coursedesc')->get();  
                                          @endphp
                                          <select class="form-control select2" id="input_course">
                                                <option value="">Select Course</option>
                                                @foreach ($courses as $item)
                                                      <option value="{{$item->courseabrv}}">{{$item->coursedesc}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Subjects</label>
                                          <select class="form-control select2" id="input_subject">

                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Schedule Code</label>
                                          <input class="form-control" id="input_code" readonly>
                                    </div>
                              </div>
                             
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button class="btn btn-primary btn-sm" id="schedulecoding_save_button">Create</button>
                        </div>
                  </div>
            </div>
      </div>
      <div class="modal fade" id="schedulecodingdetails_modal">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label>Time</label>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6 form-group">
                                          <label for="">Time Start</label>
                                          <input class="form-control" id="input_start" type="time">
                                    </div>
                                    <div class="col-md-6 form-group">
                                          <label for="">Time End</label>
                                          <input class="form-control" id="input_end" type="time">
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <label>Days</label>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Mon" value="1" class="day_list">
                                          <label for="Mon">Monday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Tue" value="2" class="day_list">
                                          <label for="Tue">Tuesday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Wed" value="3" class="day_list">
                                          <label for="Wed">Wednesday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Thu" value="4" class="day_list">
                                          <label for="Thu">Thursday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Fri" value="5" class="day_list">
                                          <label for="Fri">Friday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Sat" value="6" class="day_list">
                                          <label for="Sat">Saturday</i>
                                          </label>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button class="btn btn-primary btn-sm" id="schedulecodingdetails_save_button">Create</button>
                        </div>
                  </div>
            </div>
      </div>
      <div class="modal fade" id="classlimit_modal">
            <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label>Class Limit</label>
                                          <input id="input_classlimit" class="form-control">
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button class="btn btn-primary btn-sm" id="schedulecodingdetails_save_button_cl">Update</button>
                        </div>
                  </div>
            </div>
      </div>
      <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Schedule Coding</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Schedule Coding</li>
                </ol>
                </div>
            </div>
            </div>
      </section>
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header">
                                    <h3 class="card-title"><b>Filter</b></h3>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-4 form-group">
                                                <label for="">School Year</label>
                                                <select class="form-control" id="filter_sy">
                                                      @foreach ($sy as $item)
                                                            <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected' : ''}}>{{$item->sydesc}}</option>
                                                      @endforeach
                                                </select>
                                          </div>
                                          <div class="col-md-4 form-group" >
                                                <label for="">Semester</label>
                                                <select class="form-control" id="filter_sem">
                                                      @foreach ($semester as $item)
                                                            <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected' : ''}}>{{$item->semester}}</option>
                                                      @endforeach
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-4">
                                                <button class="btn btn-primary" id="filter_button">Filter</button>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header">
                                    <div class="row">
                                          <div class="col-md-2">
                                                <h3 class="card-title"><b>Schedule Coding</b></h3>
                                          </div>
                                          <div class="col-md-10 text-right">
                                                {{-- <button class="btn btn-sm btn-primary" id="schedulecoding_generate_button"><i class="fas fa-plus"></i> Generate Schedule Code</button> --}}
                                                <button class="btn btn-sm btn-default" id="schedulecoding_add_button"><i class="fas fa-plus"></i> Add Schedule Code</button>
                                          </div>
                                    </div>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="schedulecoding_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th  width="5%">CL</th>
                                                                  <th  width="20%">Code</th>
                                                                  <th  width="25%">Subject</th>
                                                                  <th  width="30%">Sched</th>
                                                                  <th  width="10%"></th>
                                                                  <th  width="5%"></th>
                                                                  <th  width="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody >
                                                            
                                                      </tbody>
                                                </table>
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
                  
                  var schedulecoding_list = []
                  var selected_schedulecoding = null;
                  var info_status = 'create'

                  get_schedulecoding()
                  loaddatatable_schedulecodings()

                  $('.select2').select2()
                  
                  function get_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                              },
                              success:function(data) {
                                    schedulecoding_list = data
                                    loaddatatable_schedulecodings()
                              }
                        })
                  }

                  var all_subjects = []
                  get_subjects()

                  function get_subjects(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/collegesubjects/list',
                              success:function(data) {
                                    all_subjects = data
                                    $('#input_subject').append('<option value="">Select Subject</option>')
                                    $.each(all_subjects,function(a,b){
                                          $('#input_subject').append('<option value="'+b.id+'">'+b.subjCode+' - '+b.subjDesc+'</option>')
                                    })
                                   
                              }
                        })
                  }

                  

                

                  $(document).on('click','#schedulecoding_add_button',function(){
                        info_status = 'create'
                        $('#input_subject').val("").change()
                        $('#input_course').val("").change()
                        $('#input_mod').val("").change()
                        $('#input_code').val("")
                        $('#save_button').text('Create')
                        $('#schedulecoding_modal').modal()
                  })

                  $(document).on('click','#filter_button',function(){
                        get_schedulecoding()
                  })

                  var temp_code = null;

                  $(document).on('change','#input_type',function(){
                        
                        $('#input_subject').val("").change()
                        $('#input_course').val("").change()
                        $('#input_mod').val("").change()
                        $('#input_code').val("")
                  })

                  $(document).on('change','#input_subject',function(){
                        var count = parseInt( schedulecoding_list.length ) + 1

                        temp_code = $('#input_type').val() + 20 + '/' + $('#filter_sem').val() + '-'+("000"+count).slice(-3)

                        if($('#input_mod').val() != ""){
                              temp_code += ' (' + $('#input_mod').val() + ')'
                        }

                        if($('#input_course').val() != ""){
                              temp_code += '(' + $('#input_course').val() + ')'
                        }


                        $('#input_code').val(temp_code)
                        
                  })

                  $(document).on('click','#schedulecoding_save_button',function(){
                        if(info_status == 'create'){
                              add_schedulecoding()
                        }else if(info_status == 'update'){
                              update_schedulecoding()
                        }
                  })

                  $(document).on('click','.schedulecoding_update_button',function(){
                        info_status = 'update'
                        $('#schedulecoding_save_button').text('Update')
                        $('#schedulecoding_modal').modal()
                        selected_schedulecoding = $(this).attr('data-id')
                        var temp_schedulecoding = schedulecoding_list.filter(x=>x.id == selected_schedulecoding)

                        $('#input_code').val(temp_schedulecoding[0].code)
                        $('#input_sy').val(temp_schedulecoding[0].syid).change()
                        $('#input_sem').val(temp_schedulecoding[0].semid).change()
                      
                  })

                  $(document).on('click','.schedulecoding_update_button_cl',function(){
                        selected_schedulecoding = $(this).attr('data-id')
                        $('#classlimit_modal').modal()
                  })

                  $(document).on('click','#schedulecodingdetails_save_button_cl',function(){
                        update_schedulecoding_cl()
                  })

                  $(document).on('click','.schedulecoding_delete_button',function(){
                        selected_schedulecoding = $(this).attr('data-id')
                        remove_schedulecoding()
                  })

                  var temp_subjects = []
                  var process_count  = 0;
                  var to_be_generated = []

                  $(document).on('click','#schedulecoding_generate_button',function(){
                        temp_subjects = all_subjects

                        $.each(temp_subjects,function(a,b){
                              var code_exist = schedulecoding_list.filter(x=>x.subjid == b.id)
                              if(code_exist.length == 0){
                                    to_be_generated.push(b)
                              }
                        })
                      
                        process_count  = 0
                        $('#schedulecoding_generate_button').empty()
                        $('#schedulecoding_generate_button')[0].innerHTML = 'Generating ( <span id="process_count">0</span> / '+to_be_generated.length + ')'

                        add_schedulecoding_automatic()
                        
                  })

                  function update_schedulecoding_cl(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/update/cl',
                              data:{
                                    id:selected_schedulecoding,
                                    cl:$('#input_classlimit').val(),
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!',
                                                showConfirmButton: false,
                                                timer: 1000
                                          });
                                          var temp_index = schedulecoding_list.findIndex(x=>x.id == selected_schedulecoding)
                                          schedulecoding_list[temp_index].maxenrollee = $('#input_classlimit').val()
                                          
                                          $('.schedulecoding_update_button_cl[data-column="1"][data-id="'+selected_schedulecoding+'"]').text( $('#input_classlimit').val())
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
                  
                  function add_schedulecoding_automatic(){

                        if(to_be_generated.length == 0){
                              Swal.fire({
                                    type: 'success',
                                    title: 'Generated Successfully!',
                                    showConfirmButton: false,
                                    timer: 1000
                              });
                        }

                        var subjdetail = to_be_generated[0]

                        var count = parseInt( schedulecoding_list.length ) + 1
                      
                        var temp_code = $('#input_type').val() + 20 + '/' + $('#filter_sem').val() + '-'+("000"+count).slice(-3)
                        if($('#input_mod').val() != ""){
                              temp_code += ' (' + $('#input_mod').val() + ')'
                        }
                        if($('#input_course').val() != ""){
                              temp_code += '(' + $('#input_course').val() + ')'
                        }
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/create',
                              data:{
                                    code:temp_code,
                                    syid:$('#input_sy').val(),
                                    semid:$('#input_sem').val(),
                                    subjid:subjdetail.id
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          process_count += 1
                                          $('#process_count').text(process_count)
                                          to_be_generated = to_be_generated.filter(x=>x.id != subjdetail.id)

                                          schedulecoding_list.push({
                                                id:data[0].id,
                                                code:temp_code,
                                                syid:$('#input_sy').val(),
                                                semid:$('#input_sem').val(),
                                                subjDesc:subjdetail.subjDesc,
                                                semester:$('#input_sem option[value="'+$('#input_sem').val()+'"]').text(),
                                                sydesc:$('#input_sy option[value="'+$('#input_sy').val()+'"]').text(),
                                                details:[]
                                          })

                                          add_schedulecoding_automatic()
                                          loaddatatable_schedulecodings()

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

                  function add_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/create',
                              data:{
                                    code:temp_code,
                                    syid:$('#input_sy').val(),
                                    semid:$('#input_sem').val(),
                                    subjid:$('#input_subject').val()
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});

                                          schedulecoding_list.push({
                                                id:data[0].id,
                                                code:temp_code,
                                                syid:$('#input_sy').val(),
                                                semid:$('#input_sem').val(),
                                                semester:$('#input_sem option[value="'+$('#input_sem').val()+'"]').text(),
                                                sydesc:$('#input_sy option[value="'+$('#input_sy').val()+'"]').text(),
                                                subjDesc:$('#input_subject option[value="'+$('#input_subject').val()+'"]').text(),
                                                details:[]
                                          })
                                          $('#schedulecoding_modal').modal('hide')
                                          loaddatatable_schedulecodings()
                                          temp_code = null
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

                  function update_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/update',
                              data:{
                                    code:$('#input_code').val(),
                                    id:selected_schedulecoding,
                                    subjid:$('#input_subject').val()
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          var schedulecoding_list_index = schedulecoding_list.findIndex(x=>x.id == selected_schedulecoding)
                                          schedulecoding_list[schedulecoding_list_index].code = $('#input_code').val()
                                          loaddatatable_schedulecodings()
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

                  
                  function remove_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/delete',
                              data:{
                                    id:selected_schedulecoding
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          schedulecoding_list = schedulecoding_list.filter(x=>x.id != selected_schedulecoding)
                                          loaddatatable_schedulecodings()
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

                  function loaddatatable_schedulecodings(){

                        $("#schedulecoding_table").DataTable({
                              destroy: true,
                              data:schedulecoding_list,
                              scrollX: true,
						scrollCollapse: true,
						fixedColumns:   {
							leftColumns: 2,
							rightColumns: 2
						},
                              columns: [
                                    // { "data": "sydesc" },
                                    // { "data": "semester" },
                                    { "data": null },
                                    { "data": "code" },
                                    { "data": "subjDesc" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                              columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                     
                                                      $(td)[0].innerHTML = '<a href="#" class="schedulecoding_update_button_cl" data-id="'+rowData.id+'" data-column="1">'+rowData.maxenrollee+'</a>'
                                                    
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      $(td).attr('data-id',rowData.id)
                                                      var html = ''
                                                      $.each(rowData.details,function(a,b) {
                                                            html += '<span data-id="'+rowData.id+'" data-timestart="'+b.timestart+'" data-timeend="'+b.timeend+'"><a href="#" class="text-danger schedulecodingdetails_delete_button" data-id="'+rowData.id+'" data-timestart="'+b.timestart+'" data-timeend="'+b.timeend+'"><i class="far fa-trash-alt" ></i></a> '
                                                            html += b.days + '  ' + b.timestart + ' - ' + b.timeend + '<br></span>'
                                                      })
                                                      $(td)[0].innerHTML = html
                                                     
                                                    
                                                }
                                          },
                                          {
                                                'targets': 4,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a data-id="'+rowData.id+'" href="#" id="schedulecodingdetails_add_button" ><i class="fas fa-plus" ></i> Add Schedule</a>' 
                                                      $(td).addClass('text-center')
                                                     
                                                }
                                          },
                                          {
                                                'targets': 5,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="schedulecoding_update_button" data-id="'+rowData.id+'"><i class="fas fa-edit "></i></a>'
                                                      $(td).addClass('text-center')
                                                      $(td).text(null)
                                                }
                                          },
                                          {
                                                'targets': 6,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="#" class="text-danger schedulecoding_delete_button" data-id="'+rowData.id+'"><i class="far fa-trash-alt" ></i></a>'
                                                      $(td).addClass('text-center')
                                                }
                                          }
                              ]
                              
                        })
                  }
        
                  
                  var selected_schedulecodingdetails = null;
                
                  var schedulecodingdetails_status = 'create'
                  var timestart = null
                  var timeend = null

                  $(document).on('click','#schedulecodingdetails_add_button',function(){
                        schedulecodingdetails_status = 'create'
                        selected_schedulecoding = $(this).attr('data-id')
                        $('#save_button').text('Create')
                        $('#schedulecodingdetails_modal').modal()
                  })

                  $(document).on('click','#schedulecodingdetails_save_button',function(){
                        if(schedulecodingdetails_status == 'create'){
                              add_schedulecodingdetails()
                        }else if(schedulecodingdetails_status == 'update'){
                              update_schedulecodingdetails()
                        }
                  })

                  $(document).on('click','.schedulecodingdetails_update_button',function(){
                        schedulecodingdetails_status = 'update'
                        $('#schedulecodingdetails_save_button').text('Update')
                        $('#schedulecodingdetails_modal').modal()
                        selected_schedulecodingdetails = $(this).attr('data-id')
                        var temp_schedulecodingdetails = schedulecodingdetails_list.filter(x=>x.id == selected_schedulecodingdetails)
                        $('#input_code').val(temp_schedulecodingdetails[0].code)
                        $('#input_sy').val(temp_schedulecodingdetails[0].syid).change()
                        $('#input_sem').val(temp_schedulecodingdetails[0].semid).change()
                  })

                  $(document).on('click','.schedulecodingdetails_delete_button',function(){
                        selected_schedulecoding = $(this).attr('data-id')
                        timestart = $(this).attr('data-timestart')
                        timeend = $(this).attr('data-timeend')
                        remove_schedulecodingdetails()
                  })

                  function add_schedulecodingdetails(){
                        var temp_days = []
                        $('.day_list').each(function(){
                              if($(this).prop('checked') == true){
                                    temp_days.push($(this).val())
                              }
                        })
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/details/create',
                              data:{
                                    day:temp_days,
                                    timestart:$('#input_start').val(),
                                    timeend:$('#input_end').val(),
                                    headerid:selected_schedulecoding
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          var temp_schedulecoding = schedulecoding_list.findIndex(x=>x.id == selected_schedulecoding)
                                          var html = ''
                                          html += '<span data-id="'+selected_schedulecoding+'" data-timestart="'+data[0].details.timestart+'" data-timeend="'+data[0].details.timeend+'"><a href="#" class="text-danger schedulecodingdetails_delete_button" data-id="'+selected_schedulecoding+'" data-timestart="'+data[0].details.timestart+'" data-timeend="'+data[0].details.timeend+'"><i class="far fa-trash-alt" ></i></a> '
                                          html += data[0].details.days + '  ' +data[0].details.timestart + ' - ' + data[0].details.timeend+ '<br></span>'

                                          schedulecoding_list[temp_schedulecoding].details.push(data[0].details)
                                          $('td[data-id="'+selected_schedulecoding+'"]').append(html)
                                          $('#schedulecodingdetails_modal').modal('hide')
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
                  
                  function remove_schedulecodingdetails(){
                        
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/details/delete',
                              data:{
                                    headerid:selected_schedulecoding,
                                    timestart:timestart,
                                    timeend:timeend
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          $('span[data-id="'+selected_schedulecoding+'"][data-timeend="'+timeend+'"][data-timestart="'+timestart+'"]').remove()
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

@endsection
