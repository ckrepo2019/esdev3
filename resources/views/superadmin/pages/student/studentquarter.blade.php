@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            .view_info {
                  cursor: pointer;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')
            ->orderBy('sydesc','desc')
            ->select(
                  'id',
                  'sydesc',
                  'sydesc as text',
                  'isactive'
            )
            ->get(); 

@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Quarter</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Quarter</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
           
            <div class="row">
                  
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">School Year</label>
                                          <select class="form-control select2" id="filter_sy">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-7">
                                          <i class="mb-0" style="font-size:1rem !important"><i class="fas fa-info"></i> This module is designed to set up the quarters for transferred-out, dropped out and withdrawn students. Check only the quarter that should be displayed in the report card.</i>
                                    </div>
                                   
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
          
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="student_quarter" width="100%" style="font-size:.9rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th width="28%">Student</th>
                                                                  <th width="30%">Grade Level / Section</th>
                                                                  <th width="14%"  class="text-center p-0 align-middle">Status</th>
                                                                  <th width="7%" class="text-center p-0 align-middle">Q1</th>
                                                                  <th width="7%" class="text-center p-0 align-middle">Q2</th>
                                                                  <th width="7%" class="text-center p-0 align-middle">Q3</th>
                                                                  <th width="7%" class="text-center p-0 align-middle">Q4</th>
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

            var all_student = []

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            $('.select2').select2({theme: 'bootstrap4'})


            $(document).on('click','.studentquarter_assign',function(){

                  var status = 0;
                  var quarter = $(this).attr('data-quarter')
                  var studid = $(this).attr('data-studid')

                  if($(this).prop('checked')){
                        status = 1
                  }

                  update_status(studid,quarter,status)
                  
            })


            $(document).on('change','#filter_sy',function(){
                  get_students()
            })

            function update_status(studid,quarter,status){
                  $.ajax({
                        type:'GET',
                        url: '/student/quarter/updatestatus',
                        data:{
                              syid:$('#filter_sy').val(),
                              studid:studid,
                              quarter:quarter,
                              status:status
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].message
                                    })
                                  
                              }

                              
                        }
                  })
            }


            get_students()

             function get_students(){
                  
                  $.ajax({
                        type:'GET',
                        url:'/student/quarter/students',
                        data:{
                              syid:$('#filter_sy').val()
                        },
                        success:function(data) {
                              all_student = data

                              console.log(all_student);
                              student_datatable()
                        }
                  })
            }

            function student_datatable(){

                  $("#student_quarter").DataTable({
                        destroy: true,
                        data:all_student,
                        autoWidth: false,
                        stateSave: true,
                        lengthChange : false,
                        columns: [
                                    // { "data": "sid" },
                                    { "data": "studentname" },
                                    // { "data": "levelname" },
                                    { "data": "sectionname" },
                                    { "data": "description" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML = rowData.studentname+'<p style="font-size:.8rem !important" class="mb-0"><i>'+rowData.sid+'</i></p>'
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML = rowData.sectionname+'<p style="font-size:.8rem !important" class="mb-0"><i>'+rowData.levelname+'</i></p>'
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML = '<span class="badge badge-primary">'+rowData.description+'</span><p style="font-size:.8rem !important" class="mb-0"><i>'+rowData.date+'</i></p>'

                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.q1 == 1){
                                                selected = 'checked="checked"'
                                          }

                                         var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="studentquarter_assign" id="`+rowData.studid+`-1" data-studid="`+rowData.studid+`" data-quarter="1"> <label for="`+rowData.studid+`-1" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.q2 == 1){
                                                selected = 'checked="checked"'
                                          }

                                          var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="studentquarter_assign" id="`+rowData.studid+`-2" data-studid="`+rowData.studid+`" data-quarter="2"> <label for="`+rowData.studid+`-2" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {


                                          var selected = ''
                                          if(rowData.q3 == 1){
                                                selected = 'checked="checked"'
                                          }

                                          var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="studentquarter_assign" id="`+rowData.studid+`-3" data-studid="`+rowData.studid+`" data-quarter="3"> <label for="`+rowData.studid+`-3" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var selected = ''
                                          if(rowData.q4 == 1){
                                                selected = 'checked="checked"'
                                          }

                                          var icheck = `<div class="icheck-success d-inline"><input `+selected+` type="checkbox"  class="studentquarter_assign" id="`+rowData.studid+`-4" data-studid="`+rowData.studid+`" data-quarter="4"> <label for="`+rowData.studid+`-4" style="font-size:.65rem !important">&nbsp;</label></div>`

                                          $(td)[0].innerHTML = icheck
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                             
                        ]
                  });
            }

      </script>

@endsection


