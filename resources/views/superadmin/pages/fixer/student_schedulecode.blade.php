@extends('superadmin.layouts.app2')


@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
@endsection

@section('content')
     
      <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Student Schedule Code</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Student Schedule Code</li>
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
                                    <div class="row">
                                          <div class="col-md-2">
                                                <h3 class="card-title"><b>Student Schedule Code</b></h3>
                                          </div>
                                          <div class="col-md-10 text-right">
                                               
                                          </div>
                                    </div>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="nocodesched_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th  width="30%">Section</th>
                                                                  <th  width="40%">Subject</th>
                                                                  <th  width="30%">Code</th>
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
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header">
                                    <div class="row">
                                          <div class="col-md-6">
                                                <h3 class="card-title"><b>Ready to generate code</b></h3>
                                          </div>
                                          <div class="col-md-6 text-right">
                                                <button class="btn btn-primary btn-sm" id="update_studentschedcode">Generate Student Code</button>
                                          </div>
                                    </div>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="readytogenerate_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th  width="15%">Student</th>
                                                                  <th  width="15%">Section</th>
                                                                  <th  width="40%">Subject</th>
                                                                  <th  width="30%">Code</th>
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
                  
                  var nocodesched_list = []
                  var readytogenerate_list = []
                  var selected_nocodesched = null;
                  var info_status = 'create'

                  $('.select2').select2()

                  get_nocodesched()
                  available_sched_code()

                  function get_nocodesched(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/fixer/studentschedulecoding/nocodesched',
                              success:function(data) {
                                    nocodesched_list = data
                                    loaddatatable_nocodesched()
                              }
                        })
                  }

                  function available_sched_code(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/fixer/studentschedulecoding/availablecodesched',
                              success:function(data) {
                                    readytogenerate_list = data
                                    loaddatatable_readytogenerate()
                                    $('#update_studentschedcode').text('Generate Student Code ( '+readytogenerate_list.length+' )')
                              }
                        })
                  }

                  var proces_count = 0;
                  
                  $(document).on('click','#update_studentschedcode',function(){
                        $('#update_studentschedcode')[0].innerHTML = 'Generating ( <span id="process_count">0</span> / ' + readytogenerate_list.length + ' )'
                        proces_count = 0
                        update_studentschedcode()
                  })

                  function update_studentschedcode() {
                        if(readytogenerate_list.length == 0){
                              Swal.fire({
                                    type: 'success',
                                    title: 'Generated Successfully',
                                    showConfirmButton: false,
                                    timer: 1000
                              });
                              return false     
                        }


                        var counter = 0;
                        console.log(readytogenerate_list)
                       
                        var temp_readytogenerate_list = readytogenerate_list

                        for(var x = 0; x <= 100; x++){
                              // var temp_info = readytogenerate_list[0]
                              var temp_info = temp_readytogenerate_list[x]

                              $.ajax({
                                    type:'GET',
                                    url: '/superadmin/fixer/studentschedulecoding/update',
                                    data:{
                                          studschedid:temp_info.schedid,
                                          schedcodeid:temp_info.codeid
                                    },
                                    success:function(data) {
                                          proces_count += 1
                                          counter +=  1
                                          $('#process_count').text(proces_count)
                                          readytogenerate_list = readytogenerate_list.filter(z=>z.schedid != temp_info.schedid)
                                          nocodesched_list = nocodesched_list.filter(z=>z.schedid != temp_info.schedid)
                                          loaddatatable_nocodesched()
                                          loaddatatable_readytogenerate()
                                          if(counter == 100){
                                                update_studentschedcode()
                                          }
                                    }
                              })
                        }
                 
                        
                        
                  }

                  $(document).on('click','#nocodesched_add_button',function(){
                        info_status = 'create'
                        $('#save_button').text('Create')
                        $('#nocodesched_modal').modal()
                  })

                  $(document).on('click','#filter_button',function(){
                        
                        get_nocodesched()
                  })


                  $(document).on('click','#nocodesched_save_button',function(){
                        if(info_status == 'create'){
                              add_nocodesched()
                        }else if(info_status == 'update'){
                              update_nocodesched()
                        }
                  })

                  $(document).on('click','.nocodesched_update_button',function(){
                        info_status = 'update'
                        $('#nocodesched_save_button').text('Update')
                        $('#nocodesched_modal').modal()
                        selected_nocodesched = $(this).attr('data-id')
                        var temp_nocodesched = nocodesched_list.filter(x=>x.id == selected_nocodesched)

                        $('#input_code').val(temp_nocodesched[0].code)
                        $('#input_sy').val(temp_nocodesched[0].syid).change()
                        $('#input_sem').val(temp_nocodesched[0].semid).change()
                      
                  })

                  $(document).on('click','.nocodesched_delete_button',function(){
                        selected_nocodesched = $(this).attr('data-id')
                        remove_nocodesched()
                  })

                  
                  function loaddatatable_nocodesched(){
                        $("#nocodesched_table").DataTable({
                              destroy: true,
                              data:nocodesched_list,
                              scrollX: true,
                              columns: [
                                    { "data": "sectionDesc" },
                                    { "data": "subjDesc" },
                                    { "data": "schedcodeid" },
                              ],
                              columnDefs: [
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).text(rowData.subjCode + ' - ' + rowData.subjDesc)
                                                }
                                          },
                              ]
                        })
                  }

                  function loaddatatable_readytogenerate(){
                        $("#readytogenerate_table").DataTable({
                              destroy: true,
                              data:readytogenerate_list,
                              scrollX: true,
                              columns: [
                                    { "data": "studid" },
                                    { "data": "sectionDesc" },
                                    { "data": "subjDesc" },
                                    { "data": "code" },
                              ],
                              columnDefs: [
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).text(rowData.subjCode + ' - ' + rowData.subjDesc)
                                                }
                                          },
                              ]
                        })
                  }
        
                  
                  var selected_nocodescheddetails = null;
                
                  var nocodescheddetails_status = 'create'
                  var timestart = null
                  var timeend = null

                  $(document).on('click','#nocodescheddetails_add_button',function(){
                        nocodescheddetails_status = 'create'
                        selected_nocodesched = $(this).attr('data-id')
                        $('#save_button').text('Create')
                        $('#nocodescheddetails_modal').modal()
                  })

                  $(document).on('click','#nocodescheddetails_save_button',function(){
                        if(nocodescheddetails_status == 'create'){
                              add_nocodescheddetails()
                        }else if(nocodescheddetails_status == 'update'){
                              update_nocodescheddetails()
                        }
                  })

                  $(document).on('click','.nocodescheddetails_update_button',function(){
                        nocodescheddetails_status = 'update'
                        $('#nocodescheddetails_save_button').text('Update')
                        $('#nocodescheddetails_modal').modal()
                        selected_nocodescheddetails = $(this).attr('data-id')
                        var temp_nocodescheddetails = nocodescheddetails_list.filter(x=>x.id == selected_nocodescheddetails)
                        $('#input_code').val(temp_nocodescheddetails[0].code)
                        $('#input_sy').val(temp_nocodescheddetails[0].syid).change()
                        $('#input_sem').val(temp_nocodescheddetails[0].semid).change()
                  })

                  $(document).on('click','.nocodescheddetails_delete_button',function(){
                        selected_nocodesched = $(this).attr('data-id')
                        timestart = $(this).attr('data-timestart')
                        timeend = $(this).attr('data-timeend')
                        remove_nocodescheddetails()
                  })

                  function add_nocodescheddetails(){
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
                                    headerid:selected_nocodesched
                              },
                              success:function(data) {
                                   if(data[0].status == 1){
                                          Swal.fire({
								type: 'success',
								title: data[0].message,
                                                showConfirmButton: false,
                                                timer: 1000
							});
                                          var temp_nocodesched = nocodesched_list.findIndex(x=>x.id == selected_nocodesched)
                                          var html = ''
                                          html += '<span data-id="'+selected_nocodesched+'" data-timestart="'+data[0].details.timestart+'" data-timeend="'+data[0].details.timeend+'"><a href="#" class="text-danger nocodescheddetails_delete_button" data-id="'+selected_nocodesched+'" data-timestart="'+data[0].details.timestart+'" data-timeend="'+data[0].details.timeend+'"><i class="far fa-trash-alt" ></i></a> '
                                          html += data[0].details.days + '  ' +data[0].details.timestart + ' - ' + data[0].details.timeend+ '<br></span>'

                                          nocodesched_list[temp_nocodesched].details.push(data[0].details)
                                          $('td[data-id="'+selected_nocodesched+'"]').append(html)
                                          $('#nocodescheddetails_modal').modal('hide')
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

                 

                  
                  function remove_nocodescheddetails(){
                        
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/details/delete',
                              data:{
                                    headerid:selected_nocodesched,
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
                                          $('span[data-id="'+selected_nocodesched+'"][data-timeend="'+timeend+'"][data-timestart="'+timestart+'"]').remove()
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
