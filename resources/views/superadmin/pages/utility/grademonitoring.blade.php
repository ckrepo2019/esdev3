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
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
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
            .view_info {
                  cursor: pointer;
            }
            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')
            ->orderBy('sydesc')
            ->select(
                  'id',
                  'sydesc',
                  'sydesc as text',
                  'isactive'
            )
            ->get(); 

      $semester = DB::table('semester')
                  ->orderBy('semester')
                  ->select(
                        'id',
                        'semester',
                        'semester as text',
                        'isactive'
                  )
                  ->get(); 

      $gradelevel = DB::table('gradelevel')
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'levelname as text',
                              'levelname',
                              'sortid',
                              'acadprogid'
                        )
                        ->orderBy('sortid')
                        ->get(); 

      $academicprogram = DB::table('academicprogram')
                              ->get();

      


@endphp


<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Grade Monitoring</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grade Monitoring</li>
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
                          <div class="info-box-content" style="font-size:.9rem !important">
                              <div class="row">
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="" class="mb-1">School Year</label>
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
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="" class="mb-1">Action</label>
                                          <select class="form-control select2 form-control-sm" id="filter_action">
                                              <option value="">All</option>
                                              <option value="Download">Download</option>
                                              <option value="System Grading">System Grading</option>
                                              <option value="Upload">Upload</option>
                                              <option value="Submit">Submit</option>
                                              <option value="Approve">Approve</option>
                                              <option value="Pending">Pending</option>
                                              <option value="Post">Post</option>
                                             
                                          </select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="" class="mb-1">Quarter</label>
                                          <select class="form-control select2 form-control-sm" id="filter_quarter">
                                              <option value="">All</option>
                                              <option value="1">Quarter 1</option>
                                              <option value="2">Quarter 2</option>
                                              <option value="3">Quarter 3</option>
                                              <option value="4">Quarter 4</option>
                                          </select>
                                    </div>
                                    <div class="col-md-3  form-group mb-0" >
                                          <label for=""  class="mb-1">Date</label>
                                          <input class="form-control  form-control-sm" id="filter_date" >
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
          
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-2">
                                    <div class="row ">
                                          <div class="col-md-12" style="font-size:.7rem !important">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="studentspecialclass_datatable" width="100%">
                                                      <thead class="thead-light">
                                                            <tr>
                                                                  <th width="15%">Date</th>
                                                                  <th width="10%">Subject</th>
                                                                  <th width="25%">TID</th>
                                                                  <th width="25%">Teacher</th>
                                                                  <th width="15%">Action</th>
                                                                  <th width="10%">Quarter</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                       
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-6">
                        <div class="card shadow">
                              <div class="card-body p-2">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.7rem !important">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="gradehps_datatable" width="100%" >
                                                      <thead class="thead-light">
                                                            <tr>
                                                                  <th width="26%">Updated Date</th>
                                                                  <th width="26%">Created Date</th>
                                                                  <th width="15%">TID</th>
                                                                  <th width="33%">Teacher</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                       
                  </div>
                  <div class="col-md-6">
                        <div class="card shadow">
                              <div class="card-body p-2">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.7rem !important">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed " id="gradedetail_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="25%">Updated Date</th>
                                                                  <th width="25%">Created Date</th>
                                                                  <th width="20%">TID</th>
                                                                  <th width="25%">Teacher</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                       
                  </div>
            </div>
            <div class="row">
                 
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>


      <script>


            $(document).ready(function(){

                  $('#filter_date').daterangepicker({});
                  $('#filter_date').val("")

                  $('#filter_date').daterangepicker({
                        autoUpdateInput: false,
                        locale: {
                        cancelLabel: 'Clear'
                        }
                  });

                  $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        gradeLogs_datatable()
                        gradesHPSInformation_datatable()
                        gradesDetailInformation_datatable()
                  });

                  $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        gradeLogs_datatable()
                        gradesHPSInformation_datatable()
                        gradesDetailInformation_datatable()
                  });
                  gradeLogs_datatable()

                  $(document).on('change','#filter_sy , #filter_quarter , #filter_action, #filter_date',function(){
                        gradeLogs_datatable()
                        gradesHPSInformation_datatable()
                        gradesDetailInformation_datatable()
                  })

                  function gradeLogs_datatable(){

                        $("#studentspecialclass_datatable").DataTable({
                              destroy: true,
                              lengthChange : false,
                              stateSave: true,
                              serverSide: true,
                              processing: true,
                              ajax:{
                                    url: '/api/grade/log/monitoring',
                                    type: 'GET',
                                    data: {
                                          syid:$('#filter_sy').val(),
                                          action:$('#filter_action').val(),
                                          quarter:$('#filter_quarter').val(),
                                          date:$('#filter_date').val()
                                    },
                                    dataSrc: function ( json ) {
                                          return json.data;
                                    }
                              },
                              columns: [
                                          { "data": "date" },
                                          { "data": "subjcode" },
                                          { "data": "email" },
                                          { "data": "name" },
                                          { "data": "actiontext" },
                                          { "data": "quarter" },
                                    ],
                              
                        });

                        var label_text = $($('#studentspecialclass_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<h6>Grade Logs</h6>'

                  }

                  gradesHPSInformation_datatable()

                  function gradesHPSInformation_datatable(){

                        $("#gradehps_datatable").DataTable({
                              destroy: true,
                              lengthChange : false,
                              stateSave: true,
                              serverSide: true,
                              processing: true,
                              ajax:{
                                    url: '/api/grade/hps/monitoring',
                                    type: 'GET',
                                    data: {
                                          syid:$('#filter_sy').val(),
                                          action:$('#filter_action').val(),
                                          quarter:$('#filter_quarter').val(),
                                          date:$('#filter_date').val()
                                    },
                                    dataSrc: function ( json ) {
                                          return json.data;
                                    }
                              },
                              columns: [
                                          { "data": "date" },
                                          { "data": "createddate" },
                                          { "data": "email" },
                                          { "data": "name" }
                                    ],
                              
                        });

                        var label_text = $($('#gradehps_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<h6>Grade HPS</h6>'

                  }

                  gradesDetailInformation_datatable()

                  function gradesDetailInformation_datatable(){

                        $("#gradedetail_datatable").DataTable({
                              destroy: true,
                              lengthChange : false,
                              stateSave: true,
                              serverSide: true,
                              processing: true,
                              ajax:{
                                    url: '/api/grade/detail/monitoring',
                                    type: 'GET',
                                    data: {
                                          syid:$('#filter_sy').val(),
                                          action:$('#filter_action').val(),
                                          quarter:$('#filter_quarter').val(),
                                          date:$('#filter_date').val()
                                    },
                                    dataSrc: function ( json ) {
                                          return json.data;
                                    }
                              },
                              columns: [
                                          { "data": "date" },
                                          { "data": "createddate" },
                                          { "data": "email" },
                                          { "data": "name" }
                                    ],
                              
                        });

                        var label_text = $($('#gradedetail_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<h6>Grade Detail</h6>'

                  }


            })
      </script>

      <script>
            $(document).ready(function(){

                  var keysPressed = {};

                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 11/21/2022'
                                    })
                        }
                  });

                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
            })
      </script>


@endsection


