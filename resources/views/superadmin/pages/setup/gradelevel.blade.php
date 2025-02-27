@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8){
        $extend = 'registrar.layouts.app';
      }
      else if(Session::get('currentPortal') == 4){
         $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 15){
            $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 14){
            $extend =  'deanportal.layouts.app2';
      }else if(auth()->user()->type == 3 || auth()->user()->type == 8 ){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 4){
        $extend = 'finance.layouts.app';
      }else if(auth()->user()->type == 15 ){
            $extend = 'finance.layouts.app';
      }else if(auth()->user()->type == 14 ){
            $extend =  'deanportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 26){
                        $extend = 'registrar.layouts.app';
                  }
            }
            
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
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css')}}">
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
                 
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }

            .select2{
                  width: 100% !important;
            }

            img{
                  border-radius: 0 !important
            }
            .myFont{
                  font-size:.8rem !important;
            }
            .tableFixHead {
                  overflow: auto;
                  height: 100px;
            }

            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .ribbon-wrapper.ribbon-lg .ribbon {
                  right: -16px;
                  top: 4px;
                  width: 160px;
            }

            .update_fas {
                  cursor: pointer;
            }

            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
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
                        'isactive',
                        'ended'
                  )
                  ->get(); 

      $utype = DB::table('usertype')   
                  ->orderBy('utype')
                  ->whereIn('id',[1,2,3,8])
                  ->orWhereIn('refid',[23])
                  ->select(
                        'id',
                        'utype',
                        'utype as text'
                  )
                  ->get(); 

      $academic_prog = DB::table('academicprogram')->select('id','acadprogcode','acadprogcode as text')->get();

@endphp


<div class="modal fade" id="glvlFormModal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                              <h4 class="modal-title" style="font-size: 1.1rem !important">Grade Level Form</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12" id="usertype_alert_holder">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Level Name</label>
                                    <input type="text" class="form-control form-control-sm" id="glvlLevelName" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" readonly>
                              </div>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="glvlActive" >
                                          <label for="glvlActive">Active
                                          </label>
                                    </div>
                              </div>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="glvlNoDP" >
                                          <label for="glvlNoDP">No DP
                                          </label>
                                    </div>
                              </div>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="glvlESC" >
                                          <label for="glvlESC">ESC
                                          </label>
                                    </div>
                              </div>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="glvlVoucher" >
                                          <label for="glvlVoucher">Voucher
                                          </label>
                                    </div>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" id="glvlFormUpdate"><i class="fa fa-save"></i> Update</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>


<div class="modal fade" id="acdprgFormModal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                              <h4 class="modal-title" style="font-size: 1.1rem !important">Academic Program Form</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12" id="usertype_alert_holder">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Academic Program</label>
                                    <input type="text" class="form-control form-control-sm" id="acdprgProgname" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" readonly>
                              </div>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="acdprgNoDP" >
                                          <label for="acdprgNoDP">No DP
                                          </label>
                                    </div>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" id="acdprgFormUpdate"><i class="fa fa-save"></i> Update</button>
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
                        <h1>Grade Level</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grade Level</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<div id="alert_format" hidden>
      <div class="alert alert-danger alert-dismissible" role="alert" >
            <span id="usertype_alert_text">sdfsf</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
</div>


<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-10">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow" style="font-size:.8rem !important">
                                          <div class="card-body" >
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table-hover table table-striped table-sm table-bordered" id="acdprgDatatable" width="100%" >
                                                                  <thead class="thead-light">
                                                                        <tr>
                                                                              <th width="10%" class="align-middle text-center">Ref #</th>
                                                                              <th width="50%" class="align-middle">Description</th>
                                                                              <th width="25%" class="align-middle">Code</th>
                                                                              <th width="10%" class="align-middle text-center p-0">No DP</th>
                                                                              <th width="5%" class="align-middle text-center p-0"></th>
                                                                        </tr>
                                                                  </thead>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12" style="font-size:.8rem !important">
                                    <div class="card shadow">
                                          <div class="card-body" >
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table-hover table table-striped table-sm table-bordered" id="glvlDatatable" width="100%" >
                                                                  <thead class="thead-light">
                                                                        <tr >
                                                                              <th width="10%" class="align-middle text-center">Ref #</th>
                                                                              <th width="25%" class="align-middle">Level Name</th>
                                                                              <th width="20%" class="align-middle">Acad. Prog</th>
                                                                              <th width="10%" class="align-middle text-center p-0">Active</th>
                                                                              <th width="10%" class="align-middle text-center p-0">No DP</th>
                                                                              <th width="10%" class="align-middle text-center p-0">ESC</th>
                                                                              <th width="10%" class="align-middle text-center p-0">Voucher</th>
                                                                              <th width="5%" class="align-middle text-center p-0"></th>
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
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js')}}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>

      <script>

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            var glvlAll = []
            var glvlSelected = null
            
            glvlListDatatable()

            function glvlListDatatable(){

                  $("#glvlDatatable").DataTable({
                        lengthChange : false,
                        destroy: true,
                        autoWidth: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: "{{route('glvlListDatatable')}}",
                              type: 'GET',
                              dataSrc: function ( json ) {
                                    glvlAll = json.data
                                    return json.data;
                              }
                        },
                        columns: [
                                    { "data": "id" },
                                    { "data": "levelname" },
                                    { "data": "acadprogcode" },
                                    { "data": null },
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
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.deleted == 0){
                                                $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                          }else{
                                                $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                          }
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.nodp == 1){
                                                $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                          }else{
                                                $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                          }
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.esc == 1){
                                                $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                          }else{
                                                $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                          }
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.voucher == 1){
                                                $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                          }else{
                                                $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                          }
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 7,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var buttons = '<a href="javascript:void(0)" class="glvlEdit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                    }
                              },
                        ]
                  });

                  var label_text = $($('#glvlDatatable_wrapper')[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<h5>Grade Level</h5>'
            }

            function prompt(data){
                  Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                  })
            }

            function glvlUpdated(){

                  var glvlActive = $('#glvlActive').prop('checked') == true ? 0 : 1;
                  var glvlNoDP = $('#glvlNoDP').prop('checked') == true ? 1 : 0;
                  var glvlESC = $('#glvlESC').prop('checked') == true ? 1 : 0;
                  var glvlVoucher = $('#glvlVoucher').prop('checked') == true ? 1 : 0;
                  var glvlLevelName = $('#glvlLevelName').val()

                  $.ajax({
                        type:'GET',
                        url:"{{route('glvlUpdate')}}",
                        data:{
                              glvlID:glvlSelected,
                              glvlActive:glvlActive,
                              glvlNoDP:glvlNoDP,
                              glvlESC:glvlESC,
                              glvlVoucher:glvlVoucher,
                              glvlLevelName:glvlLevelName
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    prompt(data)
                              }else{
                                    prompt(data)
                                    glvlListDatatable()
                                    acdprgListDatatable()
                              }
                              
                        },
                  })

            }

            $(document).on('click','#glvlFormUpdate',function(){
                  glvlUpdated()
            })

            $(document).on('click','.glvlEdit',function(){

                  glvlSelected = $(this).attr('data-id')
                  var tempglvlInfo = glvlAll.filter(x=>x.id == glvlSelected)
                  $('#glvlLevelName').val(tempglvlInfo[0].levelname)


                  if(tempglvlInfo[0].deleted == 0){
                        $('#glvlActive').prop('checked','checked')
                  }else{
                        $('#glvlActive').prop('checked',false)
                  }

                  if(tempglvlInfo[0].nodp == 1){
                        $('#glvlNoDP').prop('checked','checked')
                  }else{
                        $('#glvlNoDP').prop('checked',false)
                  }

                  if(tempglvlInfo[0].esc == 1){
                        $('#glvlESC').prop('checked','checked')
                  }else{
                        $('#glvlESC').prop('checked',false)
                  }

                  if(tempglvlInfo[0].voucher == 1){
                        $('#glvlVoucher').prop('checked','checked')
                  }else{
                        $('#glvlVoucher').prop('checked',false)
                  }

                  if(glvlSelected == 4 || glvlSelected == 3 || glvlSelected == 2){
                        $('#glvlLevelName').removeAttr('readonly')
                  }else{
                        $('#glvlLevelName').attr('readonly','readonly')
                  }

                  $('#glvlFormModal').modal();
            })
                  
      </script>

      <script>

            var acdprgAll = []
            var acdprgSelected = null
                  
            acdprgListDatatable()

            function acdprgListDatatable(){

                  $("#acdprgDatatable").DataTable({
                        lengthChange : false,
                        destroy: true,
                        autoWidth: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: "{{route('acdprgListDatatable')}}",
                              type: 'GET',
                              dataSrc: function ( json ) {
                                    acdprgAll = json.data
                                    return json.data;
                              }
                        },
                        columns: [
                                    { "data": "id" },
                                    { "data": "progname" },
                                    { "data": "acadprogcode" },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.nodp == 1){
                                                $(td)[0].innerHTML = '<i class="fa fa-check text-success"></i>'
                                          }else{
                                                $(td)[0].innerHTML = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                          }
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var buttons = '<a href="javascript:void(0)" class="acdprgEdit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                    }
                              },

                        ]

                  })

                  var label_text = $($('#acdprgDatatable_wrapper')[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<h5>Academic Program</h5>'
            }

            $(document).on('click','#acdprgFormUpdate',function(){
                  acdprgUpdated()
            })

            $(document).on('click','.acdprgEdit',function(){

                  acdprgSelected = $(this).attr('data-id')
                  var acdprgTempInfo = acdprgAll.filter(x=>x.id == acdprgSelected)
                  $('#acdprgProgname').val(acdprgTempInfo[0].progname)

                  if(acdprgTempInfo[0].nodp == 1){
                        $('#acdprgNoDP').prop('checked','checked')
                  }else{
                        $('#acdprgNoDP').prop('checked',false)
                  }

                  $('#acdprgFormModal').modal();
            
            })

            function prompt(data){
                  Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                  })
            }

            function acdprgUpdated(){

                  var acdprgNoDP = $('#acdprgNoDP').prop('checked') == true ? 1 : 0;

                  $.ajax({
                        type:'GET',
                        url:"{{route('acdprgUpdate')}}",
                        data:{
                              acdprgID:acdprgSelected,
                              acdprgNoDP:acdprgNoDP,
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    prompt(data)
                              }else{
                                    prompt(data)
                                    acdprgListDatatable()
                                    glvlListDatatable()
                              }
                        
                        }
                  })

            }

      </script>

@endsection


