

@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
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
             .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
      
      </style>
@endsection


@section('content')


<div class="modal fade" id="tchrEvlSetupModalForm" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Teacher Evaluation Setup Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Description</label>
                                    <input id="input_description" class="form-control form-control-sm" placeholder="Description" autocomplete="off">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Group</label>
                                    <input id="input_group" class="form-control form-control-sm" placeholder="Description" autocomplete="off">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input id="input_sort" class="form-control form-control-sm" placeholder="Description" autocomplete="off">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="tchrEvlSave">Save</button>
                                    <button class="btn btn-primary btn-sm" id="tchrEvlUpdate">Update</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Teacher Evaluation Setup</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Teacher Evaluation Setup</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card text-sm">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-hover  p-0" id="eveluationSetupDatable" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%">Sort</th>
                                                                  <th width="60%">Description</th>
                                                                  <th width="35%">Group</th>
                                                                  {{-- <th width="5%"></th>
                                                                  <th width="5%"></th> --}}
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
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      
      <script>
            $(document).on('click','#crttchrEvlSetup',function(){
                  $('#tchrEvlSetupModalForm').modal()
            })

            $(document).on('click','#gnrtetchrEvlSetup',function(){
                  gnrtetchrEvlSetup()
            })

            
      </script>

      <script>


            gettchrEvlSetup()

            function gettchrEvlSetup(){
               
               $.ajax({
                     type:'GET',
                     url:'/teacherevaluation/setup/list',
                     success:function(data) {
                        load_sy_datatable(data)
                     },error:function(){
                           Toast.fire({
                                 type: 'warning',
                                 title: 'Something went wrong!'
                           })
                     }
               })
            }

            function gnrtetchrEvlSetup(){
               
               $.ajax({
                     type:'GET',
                     url:'/teacherevaluation/setup/generate',
                     success:function(data) {
                        gettchrEvlSetup()
                     },error:function(){
                           Toast.fire({
                                 type: 'warning',
                                 title: 'Something went wrong!'
                           })
                     }
               })
            }

            function load_sy_datatable(data){

                  $("#eveluationSetupDatable").DataTable({
                        destroy: true,
                        data:data,
                        lengthChange : false,
                        stateSave: true,
                        autoWidth: false,
                        columns: [
                                    { "data": "sort" },
                                    { "data": "description" },
                                    { "data": "group" },
                                    // { "data": null },
                                    // { "data": null },
                                    
                              ],
                        // columnDefs: [
                        //       {
                        //             'targets': 3,
                        //             'orderable': false, 
                        //             'createdCell':  function (td, cellData, rowData, row, col) {
                        //                   var buttons = '<a href="#" class="dttble-tchrEvlUpdate" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                        //                   $(td)[0].innerHTML =  buttons
                        //                   $(td).addClass('text-center')
                        //                   $(td).addClass('align-middle')
                        //             }
                        //       },
                        //       {
                        //             'targets': 4,
                        //             'orderable': false, 
                        //             'createdCell':  function (td, cellData, rowData, row, col) {
                        //                   var buttons = '<a href="#" class="dttble-tchrEvlDelete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                        //                   $(td)[0].innerHTML =  buttons
                        //                   $(td).addClass('text-center')
                        //                   $(td).addClass('align-middle')
                        //             }
                        //       },
                              
                        // ]
                  });

                  var label_text = $($('#eveluationSetupDatable_wrapper')[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="gnrtetchrEvlSetup">Generate Setup</button>'


                  }
      </script>

@endsection


