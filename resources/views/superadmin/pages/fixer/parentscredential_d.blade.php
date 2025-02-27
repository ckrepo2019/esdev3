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
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
@endsection

@section('content')
<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Contact Information</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Contact Information</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->get(); 
      $strand = DB::table('sh_strand')->orderBy('strandname')->where('deleted',0)->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
@endphp

<section class="content pt-0">
      <div class="container-fluid">
            
            <div class="row">
                  <div class="col-md-8">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span>
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-3  form-group">
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
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-3">
                                                            <button class="btn btn-info btn-block btn-sm mt-2" id="subjectplot_button"><i class="fas fa-filter"></i> Filter</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-6">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card">
                                          <div class="card-body">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">Not matching credentials</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="not_matching" width="100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="50%">SID</th>
                                                                              <th width="45%">Email</th>
                                                                              <th width="5%"></th>
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
                  <div class="col-md-6">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card card-primary">
                                          <div class="card-body">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">Multiple Parent Account</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="multiple_parentaccount" width="100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="95%">Email</th>
                                                                              <th width="5%"></th>
                                                                        </tr>
                                                                  </thead>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12">
                                    <div class="card card-primary">
                                          <div class="card-body">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">No Parent Account</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="multiple_parentaccount" width="100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="95%">Email</th>
                                                                              <th width="5%"></th>
                                                                        </tr>
                                                                  </thead>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12">
                                    <div class="card card-primary">
                                          <div class="card-body">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">Default Password Conflict</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" id="not_default_pass" width="100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="95%">Email</th>
                                                                              <th width="5%"></th>
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

                 
                  var all_conflict_credentials = [];

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                  })

                  get_students()

                  function get_students(){
                        $.ajax({
                              type:'GET',
                              url: '/student/credentials/list',
                           
                              success:function(data) {
                                    all_conflict_credentials = data
                                    loaddattable()
                              }
                        })
                  }


                 $(document).on('click','.remove_multiple_parentacount',function(){
                       var email = $(this).attr('data-email')
                       $.ajax({
                              type:'GET',
                              url: '/student/credentials/fix',
                              data:{
                                    email:email,
                                    type:3
                              },
                              success:function(data) {
                                    all_conflict_credentials = data
                                    get_students()
                              }
                        })
                 })
                  

                  function loaddattable(){

                        var multiple_parentaccount = all_conflict_credentials.filter(x=>x.status == 'multiple parent account')
                        var not_matching = all_conflict_credentials.filter(x=>x.status == 'not same credentials')
                        var not_default_pass = all_conflict_credentials.filter(x=>x.status == 'not default pass')

                        

                        $("#multiple_parentaccount").DataTable({
                              destroy: true,
                              data:multiple_parentaccount,
                              scrollX: true,
                              columns: [
                                    { "data": "email" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="remove_multiple_parentacount" data-email="'+rowData.email+'"><i class="fas fa-cog"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        })



                        $("#not_matching").DataTable({
                              destroy: true,
                              data:not_matching,
                              scrollX: true,
                              columns: [
                                    { "data": "sid" },
                                    { "data": "email" },
                                    { "data": null }
                              ],                              columnDefs: [
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="" data-email="'+rowData.email+'"><i class="fas fa-cog"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        })

                        $("#not_default_pass").DataTable({
                              destroy: true,
                              data:not_matching,
                              scrollX: true,
                              columns: [
                                    { "data": "email" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="remove_multiple_parentacount" data-email="'+rowData.email+'"><i class="fas fa-cog"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        })

                        


                        
                  }
                  
            })
      </script>

@endsection
