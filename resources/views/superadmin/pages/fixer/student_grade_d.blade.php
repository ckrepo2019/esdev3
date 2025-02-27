
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
   

@endsection


@section('content')

      <div class="modal fade" id="student_grade" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header bg-primary">
                        <h4 class="modal-title">Rating Value</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <table class="table" id="student_grade_table">
                              <thead>
                                    <tr>
                                          <td>Subject</td>
                                          <td>Q1</td>
                                          <td>Q2</td>
                                          <td>Q3</td>
                                          <td>Q4</td>
                                    </tr>
                              </thead>
                        </table>
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
                                    <li class="breadcrumb-item active">Section Detail</li>
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
                                          <div class="col-md-3 form-group">
                                                <label for="">Mode of Learning</label>
                                                <select name="" id="section_name" class="form-control select2">
                                                      <option value="">All</option>
                                                      @foreach (DB::table('modeoflearning')->where('deleted',0)->get() as $item)
                                                            <option value="{{$item->id}}">{{$item->description}}</option>
                                                      @endforeach
                                            
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2">
                                                <button class="btn btn-primary btn-block" id="filter"> <i class="fas fa-filter"></i> FILTER</button>
                                          </div>
                                          <div class="col-md-7">
                                          </div>
                                         
                                    </div>
                                    <hr>
                                    <div class="row mt-3">
                                          <div class="col-md-12">
                                                <table class="table" id="student_detail_list">
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">SID</th>
                                                                  <th width="40%">Student Name</th>
                                                                  <th width="20%">MOD</th>
                                                                  <th width="20%">Grade Level</th>
                                                                  <th width="20%">Section</th>
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

                  var students = @json($students);

                  loaddatatable(students)

                  function loaddatatable(data){

                        $("#student_detail_list").DataTable({
                        destroy: true,
                        data:data,
                              "columns": [
                                    { "data": 'sid' },
                                    { "data": null },
                                    { "data": 'description' },
                                    { "data": 'sectionname' },
                                    { "data": 'levelname' },

                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                               $(td)[0].innerHTML = '<a href="#" class="get_student_grade" data-id="'+rowData.id+'">'+rowData.sid+'</a>'

                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                               $(td).text(rowData.lastname + ', ' +rowData.firstname)

                                          }
                                    }
                              ]
                        })

                  }

                  function student_grade(data){

                        $("#student_grade_table").DataTable({
                        destroy: true,
                        data:data,
                              "columns": [
                                    { "data": 'subjdesc' },
                                    { "data": 'q1' },
                                    { "data": 'q2' },
                                    { "data": 'q3' },
                                    { "data": 'q4' },
                              ],
                              
                        })

                  }

                  $(document).on('click','#filter',function(){

                        var mode_of_learning = $('#section_name').val()
            
                        var filteredsection = students.filter(function(x){

                              var validmol;

                              if(mode_of_learning == x.mol){
                                    validmol = true;
                              }
                              else if(mode_of_learning == ''){
                                    validmol = true;
                              }
                              else{
                                    validmol = false;
                              }
                              
                              if(validmol){
                                    return x;
                              }

                              
                        })

                        loaddatatable(filteredsection)

                  })

                  $(document).on('click','.get_student_grade',function(){

                        $('#student_grade').modal()

                        var studid = $(this).attr('data-id');

                        $.ajax({
                              type:'GET',
                              url:'/fixer/grades?final_grade=final_grade',
                              data:{
                                    studid: studid,
                              },
                              success:function(data){

                                    student_grade(data)

                              }

                        })


                  })

                        





            })
      </script>

@endsection
