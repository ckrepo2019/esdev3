
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('assets/css/gijgo.min.css') }}"> 

      <style>
            .select2-container .select2-selection--single {
                  height: 40px;
            }
      </style>

      <style>
            .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }
      </style>
@endsection



@section('content')

      @php
            $sylist = DB::table('sy')->select('id','sydesc','isactive')->get();
            $semlist = DB::table('semester')->select('id','semester','isactive')->get();
            $gradelevellist = DB::table('gradelevel')->select('id','levelname')->where('deleted',0)->orderBy('sortid')->get();
      @endphp

      <div class="modal fade" id="proccess_count_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header bg-success p-2">
                        <h4 class="modal-title" id="proccess_message"></h4>
                  </div>
                  <div class="modal-body">
                        <div class="progress">
                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progress_bar">
                        </div>
                        </div>
                        <p class="mb-1"><code id="percentage">0%</code></p>
                        <div class="text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="proccess_done" hidden>Done</button>
                        </div>
                        
                  </div>
            </div>
            </div>
      </div>
      


      <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h4>SECTIONS</h4>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/principalPortalSchedule">Sections</a></li>
                </ol>
                </div>
            </div>
            </div>
      </section>


      <section class="content">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header bg-primary p-1"></div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-3">
                                              <label for="">School Year</label>
                                              <select name="syid" id="syid" class="form-control select2">
                                                @foreach ($sylist as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-3">
                                              <label for="">Semester</label>
                                              <select name="semester" id="semester" class="form-control select2">
                                                      @foreach ($semlist as $item)
                                                            @if($item->isactive == 1)
                                                                  <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                            @else
                                                                  <option value="{{$item->id}}">{{$item->semester}}</option>
                                                            @endif
                                                      @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-3">
                                                <label for="">Grade Level</label>
                                                <select name="gradelevel" id="gradelevel" class="form-control select2">
                                                      <option value="">Select Grade Level</option>
                                                      @foreach ($gradelevellist as $item)
                                                            <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                      @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-4">
                                                <button class="btn btn-primary" id="filter_sched"><i class="fas fa-filter"></i> FILTER</button>
                                          </div>
                                          <div class="col-md-5">

                                          </div>
                                          <div class="col-md-3">
                                                <button class="btn btn-primary btn-block" id="promote_student">PROMOTE STUDENT</button>
                                          </div>
                                    </div>
                                    {{-- <div class="row mt-3">
                                          <div class="col-md-4">
                                                <label for="">Course</label>
                                                <select name="courses" id="courses" class="form-control select2">
                                                      <option value="">ALL</option>
                                                </select>
                                          </div>
                                          <div class="col-md-4">
                                                <label for="">Curriculum</label>
                                                <select name="curriculum_filter" id="curriculum_filter" class="form-control select2">
                                                      <option value="">ALL</option>
                                                </select>
                                          </div>
                                          <div class="col-md-4">
                                              <label for="">Specification</label>
                                              <select name="specification" id="specification" class="form-control select2">
                                                      <option value="1">Regular</option>
                                                      <option value="2">Special</option>
                                              </select>
                                          </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-4">
                                                <button class="btn btn-primary" id="filter_sched"><i class="fas fa-filter"></i> FILTER</button>
                                                
                                          </div>
                                          <div class="col-md-3">

                                          </div>
                                          <div class="col-md-5">
                                                <button class="btn btn-primary float-right ml-1 mr-1" id="add_schedule"> <i class="fas fa-plus-square"></i> <b>ADD SCHEDULE</b></button>
                                                <button class="btn btn-primary float-right ml-1 mr-1" id="createSection"> <i class="fas fa-plus-square"></i> <b>CREATE SECTION</b></button>
                                          </div>
                                    </div> --}}
                                    <hr>
                                    <div class="row">
                                          <div class="col-md-12">
                                          <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" style="width:100%" id="student_promotion_table">
                                                <thead>
                                                      <tr>
                                                            <th width="25%">SID</th>
                                                            <th width="15%">Last Name</th>
                                                            <th width="15%">First Name</th>
                                                            <th width="20%">Grade Level</th>
                                                            <th width="15%">Promotion</th>
                                                            <th width="10%">Enrollment</th>
                                                      </tr>
                                                </thead>
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

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('assets/scripts/gijgo.min.js') }}"></script>
      <script>
            $(document).ready(function(){

                  $('.select2').select2()

                  $("#student_promotion_table").DataTable({
                        destroy: true,
                  });

                  function promotion_datatable(data){

                        $("#student_promotion_table").DataTable({
                              destroy: true,
                              data:data,
                              "scrollX": true,
                              "order": [[ 2, "asc" ]],
                              columns: [
                                          { "data": "sid" },
                                          { "data": "lastname" },
                                          { "data": "firstname" },
                                          { "data": "levelname" },
                                          { "data": "promotionstatus" },
                                          { "data": "studstatus" },
                                    ],
                              "columnDefs": [
                                    {  "targets": 4,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                             if(rowData.promotionstatus == null){
                                                $(td).text(null)
                                             }
                                             else if(rowData.promotionstatus == 1){
                                                $(td).text('PROMOTED')
                                             }
                                          } 
                                    },
                                    {  "targets": 5,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                             if(rowData.studstatus == 0){
                                                $(td).text('NOT ENROLLED')
                                             }
                                             else if(rowData.studstatus == 1){
                                                $(td).text('ENROLLED')
                                             }
                                          } 
                                    }
                              ]
                        });
                  }

                  var student_promotion = [];

                  $(document).on('click','#filter_sched',function(){


                        load_student_promotion()
                  })

                  function load_student_promotion(){
                        $.ajax({
                              type:'GET',
                              url:'/student/promotion',
                              data:{
                                    syid:$('#syid').val(),
                                    semid:$('#semester').val(),
                                    levelid:$('#gradelevel').val(),
                              },
                              success:function(data) {
                                    student_promotion = data;
                                    promotion_datatable(data)
                              }
                        })
                  }

                  var process_count = 0
                  var process_length = 0

                  $(document).on('click','#promote_student',function(){

                        var valid_filter

                        if($('#gradelevel').val() == ""){

                              Swal.fire({
                                    type: 'info',
                                    text: "Please select a gradelevel!"
                              });

                              return false

                        }
                        if(student_promotion.length == 0){
                              Swal.fire({
                                    type: 'info',
                                    text: "Please click filter!"
                              });

                              return false
                        }

                     

                        Swal.fire({
                              html:
                                    'Are you sure you want <br>' +
                                    'to post promote students?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, promote students!'
                        }).then((result) => {
                              if (result.value) {
                                    $('#proccess_count_modal').modal();
                                    process_count = 0
                                    process_length = student_promotion.length
                                    
                                    $.each(student_promotion,function(a,b){
                                          var studid = b.studid
                                          $.ajax({
                                                type:'GET',
                                                url:'/student/promotion/promote',
                                                data:{
                                                      syid:$('#syid').val(),
                                                      semid:$('#semester').val(),
                                                      studid:studid,
                                                },
                                                success:function(data) {
                                                      process_count += 1
                                                      temp_percentage = ( process_count / process_length ) * 100
                                                      $('#progress_bar').css('width',temp_percentage.toFixed()+'%')
                                                      $('#percentage').text(temp_percentage.toFixed()+'%')

                                                      if(temp_percentage.toFixed() == 100){
                                                            $('#proccess_done').removeAttr('hidden')
                                                            load_student_promotion()
                                                      }
                                                }
                                          })
                                    })
                              
                              }
                        })

                       
                  })
                

            })
      </script>
@endsection


