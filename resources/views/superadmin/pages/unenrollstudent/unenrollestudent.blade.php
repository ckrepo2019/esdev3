
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <style>
            .select2-selection{
                height: calc(2.25rem + 2px) !important;
            }
      </style>
    {{-- <link rel="stylesheet" href="{{asset('css/pagination.css')}}"> --}}

@endsection


@section('content')

      <div class="modal fade" id="proccess_count_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h4 class="modal-title">Proccessing ...</h4>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                              <div class="col-md-6"><label>Process : </label></div>
                              <div class="col-md-6"><span id="proccess_count"></span></div>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-primary" data-dismiss="modal" id="proccess_done" hidden>Done</button>
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
                                    <li class="breadcrumb-item active">Unenroll Student</li>
                              </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-primary p-1">
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-4 form-group">
                                                <label for="">Grade Level</label>
                                                <select class="form-control select2" id="gradelevel" >
                                                      <option value="">All</option>
                                                      @foreach ($gradelevel as $item)
                                                            <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                      @endforeach
                                                </select>
                                          </div>
                                          <div class="col-md-4 form-group">
                                                <label for="">Section</label>
                                                <select class="form-control select2" id="sections">
                                                      <option value="">All</option>
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2">
                                                <button class="btn btn-primary btn-block" id="filter"> <i class="fas fa-filter"></i> FILTER</button>
                                          </div>
                                          <div class="col-md-7">
                                          </div>
                                          <div class="col-md-3">
                                                <button class="btn btn-primary btn-block" id="unenroll_all" disabled = "disabled"> <i class="fas fa-filter"></i> UNENROLL ALL</button>
                                          </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-3">
                                          <div class="col-md-12">
                                                <table class="table" id="student_list">
                                                      <thead>
                                                            <tr>
                                                                  <th width="15%">SID</th>
                                                                  <th width="25%">Student Name</th>
                                                                  <th width="15%">Grade Level</th>
                                                                  <th width="15%">Section</th>
                                                                  <th width="15%">Status</th>
                                                                  <th width="15%">Action</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
      
                                                      </tbody>
                                                </table>    
                                          </div>
                                    </div>
                              </div>
                        
                        </div>
                  </div>
            </div>
      </section>

@endsection

@section('footerjavascript')
      <script src="{{asset('js/pagination.js')}}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script>

             $(document).ready(function(){

                  $('.select2').select2()

                  var sections = @json($sections)

                  $(document).on('change','#gradelevel',function(){
                        $('#unenroll_all').attr('disabled','disabled')
                  })

                  $(document).on('change','#sections',function(){
                        $('#unenroll_all').attr('disabled','disabled')
                  })

                  $(document).on('change','#gradelevel',function(){

                        $('#sections').empty()

                        var selected_grade_level = $(this).val()

                        var filteredsection = sections.filter(x => x.levelid == selected_grade_level)

                        $('#sections').append('<option value="">All</option>')

                        $.each(filteredsection,function(a,b){

                              $('#sections').append('<option value="'+b.id+'">'+b.sectionname+'</option>')

                        })
                  })

                  var students = @json($students);
                  loaddatatable(students)
                  
                  function loaddatatable(data){
  
                        $("#student_list").DataTable({
                        destroy: true,
                        data:data,
                        "columns": [
                                    { "data": "sid" },
                                    { "data": "lastname"},
                                    { "data": "levelname" },
                                    { "data": "sectionname" },
                                    { "data": "description" },
                                    { "data": null }
                              ],
                        columnDefs: [
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var studname = rowData.lastname+', '+rowData.firstname

                                                      $(td).text(studname.slice(0, 20) + ( studname.length > 10 ? "..." : "" ))
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      var sectionname =  rowData.sectionname;

                                                      if(sectionname != null && sectionname != ''){
                                                            
                                                            $(td).text(sectionname.slice(0, 10) + ( sectionname.length > 10 ? "..." : "" ))

                                                      }
                                                     
                                                     

                                                }
                                          },
                                          {
                                                'targets': 5,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      if(rowData.studstatus == 1 || rowData.studstatus == 2 || rowData.studstatus == 4){
                                                            $(td)[0].innerHTML = '<button class="btn btn-primary btn-block unenroll" data-fname="'+rowData.firstname+'" data-lname="'+rowData.lastname+'" data-sid="'+rowData.sid+'" data-studid="'+rowData.id+'">Unenroll</button>'
                                                      }
                                                      else{
                                                           $(td)[0].innerHTML = '<button class="btn btn-danger">Not Enrolled</button>'
                                                      }
                                                }
                                          }
                        
                                    ]
                        });

            
                  }

                  var array_length = students.length
                  var start = 0
                  var end = 10
                  var proccess = 0

                  $(document).on('click','#unenroll_all',function(){

                        array_length = students.length
                        start = 0
                        end = 10
                        proccess = 0

                        Swal.fire({
                              text: "Are you sure you want to unenroll students?",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes'
                        }).then((result) => {
                              if (result.value) {

                                    $('#proccess_count_modal').modal();
                                    $('#proccess_done').attr('hidden','hidden')
                                    $('#proccess_count_modal .modal-title').text('Proccessing')
                                    $('#proccess_count').empty()
                                    unenroll_all()
                              }
                        })

                  })


                  function unenroll_all(){

                        var students_sliced = students.slice(start,end);
                        var limit = 0;

                        $.each(students_sliced,function(a,b){

                              var studid = b.studid
                              var fname = b.firstname
                              var lname = b.lastname
                              var sid = b.sid

                              $.ajax({
                                    type:'GET',
                                    url:'/unenrollstudent?unenroll=unenroll',
                                    data:{
                                          studid: studid,
                                          fname: fname,
                                          lname: lname,
                                          sid: sid
                                    },
                                    success:function(data) {

                                          limit += 1;

                                          if(array_length == proccess + 1){

                                                $('#promote_all').removeAttr('disabled','disabled')

                                                proccess += 1
                                                $('#proccess_count').text( proccess +' / ' + array_length)
                                                $('#proccess_count_modal .modal-title').text('Complete')

                                                $('#proccess_done').removeAttr('hidden')

                                                students = []
                                                loaddatatable(students)


                                          }
                                          else{

                                                proccess += 1
                                                $('#proccess_count').text(  proccess +' / ' + array_length)

                                          }

                                          if(limit == 10){

                                                start += 10
                                                end += 10
                                                unenroll_all()


                                          }

                                    
                                    }
                              })

                        })



                  }
                  
                  $(document).on('click','#filter',function(){

                        var temp_section = $('#sections option:selected').text()
                        var temp_gradelevel = $('#gradelevel option:selected').text()
                      
                        temp_students = students.filter(function(x){

                              var valid_grade_level = false;
                              var valid_section  = false;

                              if(x.levelname == temp_gradelevel){
                                    valid_grade_level = true;
                              }
                              else if($('#gradelevel').val() == ''){
                                    valid_grade_level = true;
                              }

                              if(x.sectionname == temp_section){
                                    valid_section = true;
                              }
                              else if($('#sections').val() == ''){
                                    valid_section = true;
                              }

                              if(valid_section && valid_grade_level){

                                    return x

                              }

                        })


                        $('#unenroll_all').removeAttr('disabled')
                        loaddatatable(temp_students)
                        

                  })

                  $(document).on('click','.unenroll',function(){

                        var clicked_button = $(this)
                        var studid = $(this).attr('data-studid')
                        var fname = $(this).attr('data-fname')
                        var lname = $(this).attr('data-lname')
                        var sid = $(this).attr('data-sid')

                        var arrayID = students.findIndex(x => x.studid == studid)

                        $.ajax({
                              type:'GET',
                              url:'/unenrollstudent?unenroll=unenroll',
                              data:{
                                    studid: studid,
                                    fname: fname,
                                    lname: lname,
                                    sid: sid
                              },
                              success:function(data) {

                                    Swal.fire({
                                          type: 'success',
                                          text: 'Unenrolled Successfull'
                                    });

                                    loaddatatable(students)
                              
                              }
                        })

                  })
                  
             

             })

            
            
      </script>
      

@endsection

{{-- @section('modalSection')

  <div class="modal fade" id="passModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <form id="checkPassForm" method="POST" action="/matchPassword">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="message"></div>
                    <div class="form-group">
                        <label>Enter Password</label>
                        <input type="password"  id="password"  name="password" class="form-control">
                        <span class="invalid-feedback" role="alert">
                            <strong>Password does not match</strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">RESET</button>
                </div>
          </div>
      </form>
    </div>
  </div>

@endsection

@section('content')
      <section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">Unenroll Student</li>
                        </ol>
                  </div>
            </div>
      </div>
      </section>
    
    <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-info ">
                                    <span class="text-white h4"><b>Unenroll Student</b></span>
                                    <input type="text" id="search" name="search" class="form-control form-control-sm float-right w-25" placeholder="Search" >
                              </div>
                              <div class="card-body table-responsive p-0" 
                              id="parent_student_users_table">
                              </div>
                              <div class="card-footer">
                                    <div class="" id="data-container">

                                    </div>
                              </div> 
                        
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')

      <script src="{{asset('js/pagination.js')}}"></script> 

      <script>

            $(document).ready(function(){

                  $(document).on('click','.unenroll',function(){

                        $.ajax({
                              type:'GET',
                              url:'/unenrollstudent?unenroll=unenroll&studid='+$(this).attr('data-value')+'&fname='+$(this).attr('data-fname')+'&lname='+$(this).attr('data-lname')+'&sid='+$(this).attr('data-sid'),
                              success:function(data) {
                                   
                              
                              }
                        })

                  })


                  processpaginate(0,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/unenrollstudent?take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&facultynstaff=facultynstaff',
                              success:function(data) {
                                    $('#parent_student_users_table').empty();
                                    $('#parent_student_users_table').append(data);
                                    pagination($('#searchCount').val(),false)
                              
                              }
                        })

                  }

                  var pageNum = 1;

                  function pagination(itemCount,pagetype){

                        var result = [];

                        for (var i = 0; i < itemCount; i++) {
                              result.push(i);
                        }

                        $('#data-container').pagination({
                              dataSource: result,
                              hideWhenLessThanOnePage: true,
                              pageNumber: pageNum,
                              pageRange: 1,
                              callback: function(data, pagination) {

                                          if(pagetype){

                                                processpaginate(pagination.pageNumber,10,$('#search').val(),false)

                                          }

                                          pageNum = pagination.pageNumber
                                          pagetype=true
                                    }
                              })
                  }

                  $(document).on('keyup','#search',function() {
                        pageNum = 1
                        processpaginate(0,10,$('#search').val(),null)
                        
                  });


            })

      </script>
            
@endsection --}}

