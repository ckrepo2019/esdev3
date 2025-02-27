@php
      if(auth()->user()->type == 17){
           $extend = 'superadmin.layouts.app2';
      }
      elseif(auth()->user()->type == 6){
            $extend = 'adminPortal.layouts.app2';
      }
@endphp
@extends($extend)


@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
      </style>
@endsection

@section('content')
<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student / Parent Credentails</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student / Parent Credentails</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->get(); 
      $gradelevel = DB::table('gradelevel')
                        ->where('deleted',0)
                        ->select('id','levelname as text')
                        ->orderBy('sortid')
                        ->get(); 
@endphp

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header p-2 pl-3 pr-2">
                        <h6 class="modal-title pt-1">No Student Account </h6>
                        <button type="button btn-sm " class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body" style="font-size:.7rem">
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="generate_student_account_all">Generate Student Account</button>
                              </div>
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered " id="no_student_account" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="90%">Student</th>
                                                      <th width="10%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>
<div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header p-2 pl-3 pr-2">
                        <h6 class="modal-title pt-1">Multiple Student Account</h6>
                        <button type="button btn-sm " class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body" style="font-size:.7rem">
                        <div class="row">
                              <div class="col-md-12">
                                    <button  class="btn btn-primary btn-sm" id="fix_multiple_student_account">Remove Multiple Student Account</button>
                              </div>
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="multiple_student_account" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="40%">Student</th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header p-2 pl-3 pr-2">
                        <h6 class="modal-title pt-1">No Student Account Password</h6>
                        <button type="button btn-sm " class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body" style="font-size:.7rem">
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="update_student_password">Generate Student Password</button>
                              </div>
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="no_student_password" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="90%">Student</th>
                                                      <th width="10%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="modal_4" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header p-2 pl-3 pr-2">
                        <h6 class="modal-title pt-1">No Parent Account</h6>
                        <button type="button btn-sm " class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body" style="font-size:.7rem">
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="generate_parent_account_all">Generate Parent Account</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="no_parent_account" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="90%">Student</th>
                                                      <th width="10%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="modal_5" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header p-2 pl-3 pr-2">
                        <h6 class="modal-title pt-1">Multiple Parent Account</h6>
                        <button type="button btn-sm " class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body" style="font-size:.7rem">
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="fix_multiple_parent_account">Remove Multiple Parent Account</button>
                              </div>
                        </div>
                        <div class="row mt-2">
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="multiple_parent_account" width="100%">
                                          <thead>
                                                <tr>
                                                      <th>Student</th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="modal_6" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h4 class="modal-title">No Parent Account Password</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body" style="font-size:.7rem">
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="update_parent_password">Update Parent Password</button>
                              </div>
                        </div>
                        <div class="row mt-2">
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="no_parent_password" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="90%">Student</th>
                                                      <th width="10%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>


<section class="content pt-0">
      <div class="container-fluid">
            
            <div class="row">
                  <div class="col-md-3">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-12  form-group">
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
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12  form-group">
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
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12 form-group">
                                                            <label for="">Grade Level</label>
                                                            <select class="form-control select2" id="filter_level"></select>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-header border-0 p-2 bg-primary">
                                                <h5 class="card-title">Student Account</h5>
                                          </div>
                                          <div class="card-body p-0">
                                                <table class="table table-sm">
                                                      <tr>
                                                            <td width="80%" class="pl-2"><a  class="fixer_count" data-toggle="modal" data-target="#modal_1">No Account</a></td>
                                                            <td width="20%" class="pl-2" id="no_student_account_count">--</td>
                                                      </tr>
                                                      <tr>
                                                            <td class="pl-2"><a class="fixer_count" data-toggle="modal" data-target="#modal_2">Multiple Account</a></td>
                                                            <td class="pl-2"  id="multiple_student_account_count">--</td>
                                                      </tr>
                                                      <tr>
                                                            <td class="pl-2"><a  class="fixer_count" data-toggle="modal" data-target="#modal_3">No Password</a></td>
                                                            <td class="pl-2"  id="no_password_student_account_count">--</td>
                                                      </tr>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-header border-0 p-2 bg-success">
                                                <h5 class="card-title">Parent Account</h5>
                                          </div>
                                          <div class="card-body p-0">
                                                <table class="table table-sm">
                                                      <tr>
                                                            <td width="80%" 
                                                            class="pl-2"><a  class="fixer_count" data-toggle="modal" data-target="#modal_4">No Account</a></td>
                                                            <td width="20%" class="pl-2"  id="no_parent_account_count">--</td>
                                                      </tr>
                                                      <tr>
                                                            <td class="pl-2"><a  class="fixer_count" data-toggle="modal" data-target="#modal_5">Multiple Account</a></td>
                                                            <td class="pl-2" id="multiple_parent_account_count">--</td>
                                                      </tr>
                                                      <tr>
                                                            <td class="pl-2"><a  class="fixer_count" data-toggle="modal" data-target="#modal_6">No Password</a></td>
                                                            <td class="pl-2" id="no_password_parent_account_count">--</td>
                                                      </tr>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-9">
                        <div class="card shadow" style="">
                              <div class="card-body" style="font-size:.7rem">
                                    <div class="row">
                                          <div class="col-md-8">
                                                <h5>Student / Parent Credentails</h5>
                                          </div>
                                          <div class="col-md-4 text-right">
                                                <button class="btn btn-primary btn-sm" id="send_credentials">Send Credentials</button>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="students_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="50%">Student</th>
                                                                  <th width="25%">Student Account</th>
                                                                  <th width="25%">Parent Account</th>
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

                  $('.select2').select2()

                  var gradelevel = @json($gradelevel);

                  $("#filter_level").empty()
                  $("#filter_level").append('<option value="">All</option>')
                  $("#filter_level").select2({
                        data: gradelevel,
                        allowClear: true,
                        placeholder: "All",
                  })

                  var all_credentials = [];

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                  })

                  $(document).on('change','#filter_sy',function(){
                        get_credentials()
                  })

                  $(document).on('change','#filter_level',function(){
                        get_credentials()
                  })

                  all_credentials = [{
                        'student':'status',
                        'status':[{
                                    multiple_parent_account: [],
                                    multiple_student_account: [],
                                    no_parent_account: [],
                                    no_parent_password: [],
                                    no_student_account: [],
                                    no_student_password: [],
                                    unmatched_parent_account: [],
                                    unmatched_student_account: [],
                              }]
                  }]

                  loaddattable()
                  get_credentials()

                  $(document).on('click','#send_credentials',function(){
                        $('#message_output_holder').removeAttr('hidden')
                        $('#send_message').removeAttr('hidden')

                        $('#parent_count_holder').text('Parent Sent: ' + '0')
                        $('#student_count_holder').text('Student Sent: ' +'0')

                        $('#send_message')[0].innerHTML = '<i class="text-danger">Status: Sending...</i>'
                        
                        var student_count = 0
                        var parent_count = 0
                        var p_count = 0
                        $.each(all_credentials.filter(x=>x.student != 'status'),function(a,b){
                          
                              var semid = $('#filter_sem').val()

                              $.ajax({
                                    type:'GET',
                                    url: '/sp/credentials/send',
                                    data:{
                                          studid:b.id,
                                          semid:semid,
                                          syid:$('#filter_sy').val()
                                    },
                                    success:function(data) {

                                          p_count += 1;
                                          if(p_count == all_credentials.filter(x=>x.student != 'status').length){
                                                $('#send_message')[0].innerHTML = '<i class="text-danger">Please check student contact information.</i>'
                                          }

                                          student_count += parseInt(data[0].student_count)
                                          parent_count += parseInt(data[0].parent_count)

                                          $('#student_count_holder').text('Student Sent: ' + student_count)
                                          $('#parent_count_holder').text('Parent Sent: ' + parent_count)
                                    }
                              })
                        })
                  })

                  
                  function get_credentials(){
                        var levelid = $('#filter_level').val()
                        var semid = $('#filter_sem').val()
                        $.ajax({
                              type:'GET',
                              url: '/sp/credentials/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:levelid,
                                    semid:semid
                              },
                              success:function(data) {
                                    $('.fixer_count').attr('href','#')
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No student found.'
                                          })

                                          all_credentials = []

                                          all_credentials.push({
                                          'student':'status', 
                                          'status':[{
                                                'unmatched_parent_account':0,
                                                'multiple_parent_account':0,
                                                'no_parent_account':0,
                                                'no_parent_password':0,
                                                'unmatched_student_account':0,
                                                'multiple_student_account':0,
                                                'no_student_account':0,
                                                'no_student_password': 0
                                          }]});
                                          
                                          loaddattable()
                                    }else{
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length + ' student(s) found.'
                                          })
                                          all_credentials = data
                                          loaddattable()
                                    }
                                   
                              }
                        })
                  }

                  function multiple_student_account_datatable(){
                        $("#multiple_student_account").DataTable({
                              destroy: true,
                              data:multiple_student_account,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                              ]
                              
                        });
                  }

                  
                  function multiple_parent_account_datatable(){
                        $("#multiple_parent_account").DataTable({
                              destroy: true,
                              data:multiple_parent_account,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                              ]
                              
                        });
                  }

                  function no_parent_account(){
                        $("#no_parent_account").DataTable({
                              destroy: true,
                              autoWidth: false,
                              data:no_parent_credentials,
                              lengthChange: false,
                              columns: [
                                    { "data": "student" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" class="generate_parent_account" data-id="'+rowData.id+'"><i class="fas fa-edit text-priamry"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                              
                        });
                  }

                  $(document).on('click','.generate_parent_account',function(){
                        var temp_id = $(this).attr('data-id')
                        generate_parent_account(temp_id)
                  })

                  $(document).on('click','#generate_parent_account_all',function(){
                        $.each(no_parent_credentials,function(a,b){
                              var temp_id = b.id
                              generate_parent_account(temp_id)
                        })
                  })


                  $(document).on('click','#fix_multiple_student_account',function(){

                        var temp_multiple = multiple_student_account

                        $.each(temp_multiple,function(a,b){
                              var temp_sid = b.sid
                              var id = b.id
                              $.ajax({
                                    type:'GET',
                                    url: '/sp/credentials/remove/multiple/student',
                                    data:{
                                          sid:temp_sid,
                                          id:id
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].message
                                                })
                                                multiple_student_account = multiple_student_account.filter(x=>x.id != id)
                                                multiple_student_account_datatable()
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].message
                                                })
                                          }
                                    },error:function(){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'Something went wrong'
                                          })
                                    }
                              })
                        })
                  })

                  $(document).on('click','#fix_multiple_parent_account',function(){

                        var temp_multiple = multiple_parent_account
                        $.each(temp_multiple,function(a,b){
                              var temp_sid = b.sid
                              var id = b.id
                              $.ajax({
                                    type:'GET',
                                    url: '/sp/credentials/remove/multiple/parent',
                                    data:{
                                          sid:temp_sid,
                                          id:id
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].message
                                                })
                                                multiple_parent_account = multiple_parent_account.filter(x=>x.id != id)
                                                multiple_parent_account_datatable()
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].message
                                                })
                                          }
                                    },error:function(){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'Something went wrong'
                                          })
                                    }
                              })
                        })
                  })




                  function generate_parent_account(id = null){

                        var temp_data = no_parent_credentials.filter(x=>x.id == id)[0]

                        $.ajax({
                              type:'GET',
                              url: '/sp/credentials/generate/parent/credentials',
                              data:temp_data,
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          var student_info = all_credentials.findIndex(x=>x.id == temp_data.id)
                                          all_credentials[student_info].parent_credentials = [data[0].data]
                                          console.log(all_credentials[student_info])
                                          var status_index = all_credentials.findIndex(x=>x.student == 'status')
                                          var no_parent_account = all_credentials[status_index].status[0].no_parent_account
                                          all_credentials[status_index].status[0].no_parent_account = no_parent_account.filter(x=>x.id != temp_data.id)
                                          loaddattable()
                                         
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'info',
                                          title: 'Something went wrong'
                                    })
                              }
                        })

                  }

                  function no_student_account(){

                        $("#no_student_account").DataTable({
                              destroy: true,
                              data:no_student_credentials,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                                    { "data": "search" }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" class="generate_student_account" data-id="'+rowData.id+'"><i class="fas fa-edit text-priamry"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                              ]
                              
                        });

                  }


                  // no student password
                  function no_student_password_function(){
                        $("#no_student_password").DataTable({
                              destroy: true,
                              data:no_student_password,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" class="update_student_password" data-id="'+rowData.userid+'"><i class="fas fa-edit text-priamry"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        });
                  }

                  $(document).on('click','.update_student_password',function(){
                        var temp_id = $(this).attr('data-id')
                        update_student_password(temp_id)
                  })

                  $(document).on('click','#update_student_password',function(){
                        $.each(no_student_password,function(a,b){
                              var temp_id = b.userid
                              update_student_password(temp_id)
                        })

                  })

                  function update_student_password(id = null){

                        $.ajax({
                              type:'GET',
                              url: '/sp/credentials/update/student/password',
                              data:{
                                    userid:id
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          no_student_password = no_student_password.filter(x=>x.userid != id)
                                          no_student_password_function()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'info',
                                          title: 'Something went wrong'
                                    })
                              }
                        })

                  }
                  // no student password


                  // no parent password
                  function no_parent_password_function(){
                        $("#no_parent_password").DataTable({
                              destroy: true,
                              data:no_parent_password,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" class="update_parent_password" data-id="'+rowData.userid+'"><i class="fas fa-edit text-priamry"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        });
                  }

                  $(document).on('click','.update_parent_password',function(){
                        var temp_id = $(this).attr('data-id')
                        update_parent_password(temp_id)
                  })

                  $(document).on('click','#update_parent_password',function(){
                        $.each(no_parent_password,function(a,b){
                              var temp_id = b.userid
                              update_parent_password(temp_id)
                        })

                  })

                  function update_parent_password(id = null){

                        $.ajax({
                              type:'GET',
                              url: '/sp/credentials/update/parent/password',
                              data:{
                                    userid:id
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          no_parent_password = no_parent_password.filter(x=>x.userid != id)
                                          no_parent_password_function()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'info',
                                          title: 'Something went wrong'
                                    })
                              }
                        })

                  }
                  // no parent password

                  $(document).on('click','.generate_student_account',function(){
                        var temp_id = $(this).attr('data-id')
                        generate_student_account(temp_id)
                  })

                  $(document).on('click','#generate_student_account_all',function(){
                        $.each(no_student_credentials,function(a,b){
                              var temp_id = b.id
                              generate_student_account(temp_id)
                        })
                  })

                  function generate_student_account(id = null){

                        var temp_data = no_student_credentials.filter(x=>x.id == id)[0]

                        $.ajax({
                              type:'GET',
                              url: '/sp/credentials/generate/student/credentials',
                              data:temp_data,
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          var student_info = all_credentials.findIndex(x=>x.id == temp_data.id)
                                          all_credentials[student_info].student_credentials = [data[0].data]
                                          var status_index = all_credentials.findIndex(x=>x.student == 'status')
                                          var no_student_account = all_credentials[status_index].status[0].no_student_account
                                          all_credentials[status_index].status[0].no_student_account = no_student_account.filter(x=>x.id != temp_data.id)
                                          loaddattable()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'info',
                                          title: 'Something went wrong'
                                    })
                              }
                        })
                  }


                  var status = []
                  var no_student_credentials = []
                  var no_parent_credentials = []
                  var no_parent_password = []
                  var no_student_password = []
                  var multiple_parent_account = []
                  var multiple_student_account = []

                  function loaddattable(){

                        var temp_levelid = $('#filter_level').val()
                       
                        
                        status = all_credentials.filter(x=>x.student == 'status')
                        temp_data = all_credentials.filter(x=>x.student != 'status')

                        no_student_credentials = status[0].status[0].no_student_account
                        no_parent_credentials = status[0].status[0].no_parent_account

                        
                        no_student_password = status[0].status[0].no_student_password
                        no_parent_password = status[0].status[0].no_parent_password

                        no_student_password = status[0].status[0].no_student_password
                        no_parent_password = status[0].status[0].no_parent_password

                        multiple_parent_account = status[0].status[0].multiple_parent_account
                        multiple_student_account = status[0].status[0].multiple_student_account


                        multiple_student_account_datatable()
                        multiple_parent_account_datatable()

                        no_parent_account()
                        no_student_account()

                        no_student_password_function()
                        no_parent_password_function()

                       
                        $('#no_student_account_count').text(no_student_credentials.length)
                        $('#multiple_student_account_count').text(multiple_student_account.length)
                        $('#no_password_student_account_count').text(no_student_password.length)

                        $('#no_parent_account_count').text(no_parent_credentials.length)
                        $('#multiple_parent_account_count').text(multiple_parent_account.length)
                        $('#no_password_parent_account_count').text(no_parent_password.length)

                        $("#students_table").DataTable({
                              destroy: true,
                              data:temp_data,
                              scrollX: true,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "student" },
                                    { "data": "search" },
                                    { "data": null },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = ''
                                                $.each(rowData.student_credentials,function(a,b){

                                                      var passwordstr = b.passwordstr != null ? b.passwordstr : '<span class="badge badge-danger">No Password</span>'

                                                      text += '<a class="mb-0">'+b.email+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+passwordstr+'</p>';
                                                })
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = ''
                                                $.each(rowData.parent_credentials,function(a,b){

                                                      var passwordstr = b.passwordstr != null ? b.passwordstr : '<span class="badge badge-danger">No Password</span>'

                                                      text += '<a class="mb-0">'+b.email+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+passwordstr+'</p>';
                                                })
                                                $(td)[0].innerHTML =  text
                                          }
                                    }
                              ]
                              
                        });

                        var label_text = $($("#students_table_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<span id="message_output_holder" hidden><span id="student_count_holder">Student Sent: </span> | <span id="parent_count_holder">Parent Sent: </span><br><span  id="send_message" hidden><i class="text-danger">Please check student contact information.</i></span>'
                  }
                  
            })
      </script>

@endsection
