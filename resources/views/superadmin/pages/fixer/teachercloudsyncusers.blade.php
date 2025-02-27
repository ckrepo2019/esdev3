@php
      $extend = 'superadmin.layouts.app2';
@endphp
@extends($extend)


@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <style>
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }
      </style>
@endsection

@section('content')

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12">
                                    <h5>Link: <i><span id="cloud_link"></span></i></h5>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h6>Local Information</h6>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">Teacher Information</label>
                                                <table class="table table-sm" style="font-size:.9rem">
                                                      <thead>
                                                            <tr>
                                                                  <th width="10%">Type</th>
                                                                  <th width="80%">Teacher</th>
                                                                  <th width="10%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_info">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">User by ID</label>
                                                <table class="table table-sm" style="font-size:.9rem">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%">Type</th>
                                                                  <th width="20%">Name</th>
                                                                  <th width="10%">Email</th>
                                                                  <th width="10%">Password Text</th>
                                                                  <th width="45%">Password</th>
                                                                  <td width="10%"></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_by_id">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">User by TID</label>
                                                <table class="table table-sm" style="font-size:.9rem">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%">Type</th>
                                                                  <th width="20%">Name</th>
                                                                  <th width="10%">Email</th>
                                                                  <th width="10%">Password Text</th>
                                                                  <th width="45%">Password</th>
                                                                  <td width="10%"></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_by_tid">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">Student User</label>
                                                <table class="table table-sm" style="font-size:.9rem">
                                                      <thead>
                                                            <tr>
                                                                  <th width="10%">Type</th>
                                                                  <th width="75%">Student</th>
                                                                  <th width="15%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_student">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                  
                              </div>
                              {{-- <div class="col-md-6">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h6>Local Information</h6>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">Teacher Information</label>
                                                <table class="table table-sm">
                                                      <thead>
                                                            <tr>
                                                                  <th>Teacher</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_info_local">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">User by ID</label>
                                                <table class="table table-sm">
                                                      <thead>
                                                            <tr>
                                                                  <th>Name</th>
                                                                  <th>Email</th>
                                                                  <th>Password Text</th>
                                                                  <th>Password</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_by_id_local">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">User by TID</label>
                                                <table class="table table-sm" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">Name</th>
                                                                  <th width="20%">Email</th>
                                                                  <th width="20%">Password Text</th>
                                                                  <th width="40%">Password</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_by_tid_local">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-12">
                                                <label for="">Student User</label>
                                                <table class="table table-sm">
                                                      <thead>
                                                            <tr>
                                                                  <th>Student</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="teacher_student_local">
      
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div> --}}
                        </div>
                  </div>
            </div>
      </div>
</div> 

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Cloud Sync Teacher Users</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Cloud Sync Teacher Users</li>
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
                              <div class="card-body">
                                    <table class="table table-sm" id="teachers">
                                          <thead>
                                                <tr>
                                                      <th width="20%">Teacher</th>
                                                      <th width="60%">TID</th>
                                                      <th width="20%"></th>
                                                </tr>
                                          </thead>
                                    </table>
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


                  var all_tachers = []
                  get_teachers()

                  function get_teachers(){
                        $.ajax({
                              type:'GET',
                              url: '/cloudsync/get/user/localteacher',
                              success:function(data) {
                                    all_tachers = data
                                    loaddattable()
                              }
                        })
                  }

                  var link = []
                  get_cloudLink()

                  function get_cloudLink(){
                        $.ajax({
                              type:'GET',
                              url: '/cloudsync/get/user/link',
                              success:function(data) {
                                   $('#cloud_link').text(data)
                                   link = data
                              }
                        })
                  }

                  var temp_id = null

                  $(document).on('click','.evaluate',function(){
                        temp_id = $(this).attr('data-id')
                        $('#teacher_info').empty()
                        $('#teacher_by_id').empty()
                        $('#teacher_by_tid').empty()
                        $('#teacher_student').empty()
                        get_info_local()
                        $('#modal_1').modal();
                  })


                  var studid = null;
                  var studentuser = null;

                  //update from cloud
                  $(document).on('click','.update_student_cloud',function(){
                        studid = $(this).attr('data-studid')
                        update_student_cloud()
                  })

                  function update_student_cloud() {
                        $.ajax({
                              type:'GET',
                              url: link+'/cloudsync/get/update/student/cloud',
                              data:{
                                    studid:studid
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          studentuser = data[0].userid
                                          update_student_local()
                                    }
                              }
                        })
               
                  }


                  function update_student_local() {
                        $.ajax({
                              type:'GET',
                              url: '/cloudsync/get/update/student/local',
                              data:{
                                    studid:studid,
                                    userid:studentuser
                              },
                              success:function(data) {
                                    studid = null
                                    studentuser = null
                                    $('#teacher_info').empty()
                                    $('#teacher_by_id').empty()
                                    $('#teacher_by_tid').empty()
                                    $('#teacher_student').empty()
                                    get_info_local()
                              }
                        })
                  }
                  //update from cloud

                  var tid = null
                  $(document).on('click','.update_teacher_user_cloud',function(){
                        tid = $(this).attr('data-tid')
                        update_teacher_user_cloud()
                  })

                  function update_teacher_user_cloud() {

                        var temp_info = local_info[0].userbyid

                        $.ajax({
                              type:'GET',
                              url: link+'cloudsync/get/update/teacher/user/cloud',
                              data:{
                                    userid:tid,
                                    name:temp_info[0].name,
                                    email:temp_info[0].email,
                                    password:temp_info[0].password,
                                    passwordstr:temp_info[0].passwordstr,
                                    type:temp_info[0].type
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          if(data[0].type == 'student'){
                                                studid = data[0].studid
                                                studentuser = data[0].userid
                                                update_student_local()
                                          }else{
                                                $('#teacher_info').empty()
                                                $('#teacher_by_id').empty()
                                                $('#teacher_by_tid').empty()
                                                $('#teacher_student').empty()
                                                get_info_local()
                                          }
                                    }
                                   
                              }
                        })
               
                  }

                  

                  var local_info = []
                  function get_info_local(){
                        $.ajax({
                              type:'GET',
                              url: '/cloudsync/get/user/information',
                              data:{
                                    id:temp_id
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          local_info = data
                                          $.each(data[0].teacherinfo,function(a,b){
                                                $('#teacher_info').append('<tr><td class="align-middle">LOCAL</td><td class="align-middle">'+b.lastname+', '+b.firstname+'</td><td class="align-middle"><button class="btn btn-sm btn-success">Update Local</button></td></tr>')
                                          })
                                          $.each(data[0].userbyid,function(a,b){
                                                $('#teacher_by_id').append('<tr><td class="align-middle">LOCAL</td><td class="align-middle">'+b.name+'</td><td class="align-middle">'+b.email+'</td><td class="align-middle">'+b.passwordstr+'</td><td class="align-middle">'+b.password+'</td><td class="align-middle"><button class="btn btn-sm btn-success update_teacher_user_cloud" data-tid="'+b.id+'">Update Cloud</button></td></tr>')
                                          })
                                          $.each(data[0].userbytid,function(a,b){
                                                $('#teacher_by_tid').append('<tr><td class="align-middle">LOCAL</td><td class="align-middle">'+b.name+'</td><td class="align-middle">'+b.email+'</td><td class="align-middle">'+b.passwordstr+'</td><td>'+b.password+'</td><td class="align-middle"><button class="btn btn-sm btn-success">Update Local</button></td></tr>')
                                          })
                                          $.each(data[0].studentuser,function(a,b){
                                                $('#teacher_student').append('<tr><td class="align-middle text-right">LOCAL</td><td class="align-middle">'+b.lastname+', '+b.firstname+'</td><td class="align-middle"><button class="btn btn-sm btn-success update_student_local" data-studid="'+b.id+'">Update Student</button></td></tr>')
                                          })

                                          get_info_cloud()
                                    }
                              }
                        })
                  }

                  function get_info_cloud(){
                        $.ajax({
                              type:'GET',
                              url: link+'/cloudsync/get/user/information',
                              data:{
                                    id:temp_id
                              },
                              success:function(data) {
                                    if(data[0].status == 1){

                                          $.each(data[0].teacherinfo,function(a,b){
                                                $('#teacher_info').append('<tr><td class="align-middle">CLOUD</td><td class="align-middle">'+b.lastname+', '+b.firstname+'</td><td class="align-middle"><button class="btn btn-sm btn-primary update_teacher_user_cloud" data-tid="'+b.id+'">Update Cloud</button></td></tr>')
                                          })
                                          $.each(data[0].userbyid,function(a,b){
                                                $('#teacher_by_id').append('<tr><td class="align-middle">CLOUD</td><td class="align-middle">'+b.name+'</td><td class="align-middle">'+b.email+'</td><td class="align-middle">'+b.passwordstr+'</td><td class="align-middle">'+b.password+'</td><td class="align-middle"><button class="btn btn-sm btn-primary">Update Local</button></td></tr>')
                                          })
                                          $.each(data[0].userbytid,function(a,b){
                                                $('#teacher_by_tid').append('<tr><td>CLOUD</td><td>'+b.name+'</td><td>'+b.email+'</td><td>'+b.passwordstr+'</td><td>'+b.password+'</td><td class="align-middle"><button class="btn btn-sm btn-primary">Update Local</button></td></tr>')
                                          })
                                          $.each(data[0].studentuser,function(a,b){
                                                $('#teacher_student').append('<tr><td>CLOUD</td><td>'+b.lastname+', '+b.firstname+'</td><td class="align-middle  text-right" ><button class="btn btn-sm btn-primary update_student_cloud" data-studid="'+b.id+'">Update Student</button></td></tr>')
                                          })
                                    }
                              }
                        })
                  }
                  

                  

                  function loaddattable(){
                        $("#teachers").DataTable({
                              destroy: true,
                              data:all_tachers,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "tid" },
                                    { "data": null },
                                    { "data": null },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).text(rowData.lastname+', '+rowData.lastname)
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                                $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary evaluate" data-id="'+rowData.id+'">Evaluate</button>'
                                          }
                                    }
                              ]
                              
                        });
                  }
                  
            })
      </script>

@endsection
