@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <style>
      /* div.dataTables_wrapper {
            width: 800px;
            margin: 0 auto;
      } */
      .subj_tr td{
            vertical-align: middle!important;
            cursor: pointer;
      }
      .stud_subj_tr td{
            vertical-align: middle!important;
            cursor: pointer;
      }

      </style>

      <style>
            
			
			.select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
			input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>

@endsection

@php 
      $college_sections = DB::table('college_sections')->where('deleted',0)->select('id','sectionDesc','courseid','syid','semesterID')->get();
@endphp

@section('content')
     

      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-6">
                        
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">Promotional Report</li>
                        </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-md-12">
                              <div class="card">
                                    <div class="card-header p-1 bg-primary"></div>
                                    <div class="card-body">
                                          <div class="row">
                                                <div class="col-md-8">
                                                      <h5 for="">Promotional Report</h5>
                                                </div>
                                          </div>
                                          <hr>
                                          <div class="row">
                                                <div class="form-group col-md-3">
                                                      <label for="">School Year</label>
                                                      <select name="sy" id="sy" class="form-control form-control-sm select2">
                                                            
                                                            @foreach (DB::table('sy')->get() as $item)
                                                                  @if($item->isactive == 1)
                                                                        <option value="{{$item->id}}" selected>{{$item->sydesc}}</option>
                                                                  @else
                                                                        <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                  @endif
                                                            @endforeach
                                                      </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Semester</label>
                                                      <select name="semester" id="semester" class="form-control form-control-sm select2">
                                                            @foreach (DB::table('semester')->where('deleted',0)->get() as $item)
                                                                  @if($item->isactive == 1)
                                                                        <option value="{{$item->id}}" selected>{{$item->semester}}</option>
                                                                  @else
                                                                        <option value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endif
                                                            @endforeach
                                                      </select>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="form-group col-md-4">
                                                      <label for="">Course</label>
                                                      <select name="course" id="course" class="form-control form-control-sm select2">
                                                            <option value="">ALL</option>
                                                            @foreach (DB::table('college_courses')->where('deleted',0)->select('id','courseDesc')->get() as $item)
                                                                  <option value="{{$item->id}}">{{$item->courseDesc}}</option>
                                                            @endforeach
                                                      </select>
                                                </div>
                                                {{-- <div class="form-group col-md-4">
                                                      <label for="">Sections</label>
                                                      <select name="sections" id="sections" class="form-control form-control-sm select2">
                                                            <option value="">ALL</option>
                                                      </select>
                                                </div> --}}
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <button class="btn btn-primary" id="filter"><i class="fas fa-filter"></i> FILTER</button>
                                                      <button class="btn btn-default" id="print"><i class="fas fa-print"></i> Print Promotional Report</button>
                                                      <button class="btn btn-default" id="print_br"><i class="fas fa-print"></i> Print Beginning Report</button>
                                                </div>
                                          </div>
                                          <hr>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" id="student_list" style="width:100%">
                                                            <thead>
                                                                  <tr>
                                                                        <th>Name of the student/s</th>
                                                                        <th>Sex</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
                                                                        <th class="text-center">Subject Code</th>
                                                                        <th class="text-center">Grade</th>
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
      
      <script>
            $(document).ready(function(){

                  var sections = @json($college_sections)

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $(document).on('change','#course',function(){

                        var syid = $('#sy').val()
                        var semid = $('#semester').val()
                        var course = $('#course').val()

                        var temp_sections = sections.filter(x=>x.syid == syid && x.semesterID == semid && x.courseid == course)
                        $('#sections').empty()
                        $('#sections').append('<option value="">ALL</option>')
                        $.each(temp_sections,function(a,b){
                              $('#sections').append('<option value="'+b.id+'">'+b.sectionDesc+'</option>')
                        })
                  })


                  $(document).on('click','#filter',function(){
                        get_promotionalreport()
                  })

                  $(document).on('click','#print',function(){

                        var syid = $('#sy').val()
                        var semid = $('#semester').val()
                        var course = $('#course').val()
                        var sectionid = $('#sections').val()

                        window.open("/sc/report/promotional/excel?syid="+syid+"&semid="+semid+"&courseid="+course+"&sectionid="+sectionid);
                  })

                  $(document).on('click','#print_br',function(){

                        var syid = $('#sy').val()
                        var semid = $('#semester').val()
                        var course = $('#course').val()
                        var sectionid = $('#sections').val()

                        window.open("/sc/report/beginning/excel?syid="+syid+"&semid="+semid+"&courseid="+course+"&sectionid="+sectionid);

                  })


                  $(document).on('change','#semester',function(){
                        $('#course').val("").change()
                        $('#sections').empty()
                        $('#sections').append('<option value="">ALL</option>')
                  })

                  $(document).on('change','#sy',function(){
                        $('#course').val("").change()
                        $('#sections').empty()
                        $('#sections').append('<option value="">ALL</option>')
                  })


                  var new_data = []
                  load_datatable()

                  $('.select2').select2()
                  

                  function get_promotionalreport(){
                        $.ajax({
                              type:'GET',
                              url:'/sc/report/promotional/generate',
                              data:{
                                    syid:$('#sy').val(),
                                    semid:$('#semester').val(),
                                    courseid:$('#course').val(),
                                    sectionid:$('#sections').val(),
                              },
                              success:function(data) {
                                    new_data = data
                                    load_datatable()
                              }
                        })
                  }

                  
                  function load_datatable(){


                        $.each(new_data,function(a,b){
                      

                              if(b.gender == null || b.gender == ""){
                                    console.log('a')
                              }
                        })

                        if(new_data.length == 0){
                              $("#student_list").DataTable({
                                    destroy: true,
                                    data:new_data,
                                    "scrollX": true,
                              })

                              Toast.fire({
                                    type: 'warning',
                                    title: "No Reports Found!"
                              })
                        }
                        else{

                              $("#student_list").DataTable({
                                    destroy: true,
                                    data:new_data,
                                    "scrollX": true,
                                    columns: [
                                                { "data": "student" },
                                                { "data": "gender" },
                                                { "data": "subjects[0].data" },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                                { "data": null },
                                    ],
                                    "order": [[2, 'asc']],
                                    "columnDefs":[
                                          {
                                                "targets": 2,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[0].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 3,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[1].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 4,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[2].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 5,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[3].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 6,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[4].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 7,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[5].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 8,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[6].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 9,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[7].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 10,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[8].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 11,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[9].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 12,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[10].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 13,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[11].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 14,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[12].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 15,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[13].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 16,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[14].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 17,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[15].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 18,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[16].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 19,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[17].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 20,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[18].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },
                                          {
                                                "targets": 21,
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                            $(td).text(rowData.subjects[19].data)
                                                            $(td).addClass('text-center')
                                                      } 
                                          },

                                    ]
                              })
                        }
                  }


                  
            })
      </script>

@endsection

