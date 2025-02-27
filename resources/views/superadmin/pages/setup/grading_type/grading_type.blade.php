@php
      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
            }
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
            #datatable_1 tbody tr:hover {
                  background-color: rgb(233, 248, 255) !important;
            }
            .select2 ul {
                  
            }
            .select2 ul > .select2-selection__choice{
                  color: rgb(59, 59, 59) !important;
                  background-color: #fff !important;
            }
            .upload #file{
                  padding: 10px 0px 35px 10px!important;
            }
            .upload #image_logo{
                  padding: 7px 0px 30px 7px!important;
            }
            .image-container {
                  position: relative;
            }

            .camera-icon {
                  position: absolute;
                  top: 50%;
                  left: 52%;
                  transform: translate(-50%, -50%);
                  display: none;
            }

            .image-container:hover .camera-icon {
                  display: block;
                  transition: all 0.2s ease-in-out;
            }

            .fa-camera {
                  font-size: 70px;
                  color: rgb(255, 255, 255);
            }
            
            .align-middle{
                  vertical-align: middle !important;
            }

            .pt-1{
                  padding-top: 10px !important;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')
            ->select(
                  'sy.sydesc',
                  'sy.id',
                  'sy.isactive'
            )
            ->orderBy('sydesc')
            ->get(); 

      $semester = DB::table('semester')
            ->select(
                  'semester.id',
                  'semester.semester',
                  'semester.isactive'
            )
            ->get(); 

      $strand = DB::table('sh_strand')
            ->orderBy('sh_strand.strandname')
            ->where('sh_strand.active',1)
            ->where('sh_strand.deleted',0)
            ->select(
                  'sh_strand.strandname',
                  'sh_strand.strandcode',
                  'sh_strand.id'
            )
            ->get(); 

      $subject_gradessetup = DB::table('subject_gradessetup')
            ->where('deleted',0)
            ->select(
                  'description as text',
                  'id'
            )
            ->get();
      // $usertype = DB::table('usertype')
      //       ->select('id', 'utype as text')
      //       ->where('deleted', 0)
      //       ->get();

      $usertype = DB::table('usertype')
            ->where('deleted', 0)
            ->get();
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                        <h1>Grading Types</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grading Types</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<section class="content pt-0">
      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body">
                              <div class="row">
                                    <div class="col-md-12">
                                          <table width="100%" class="table table-bordered mb-0 table-sm" id="grading_type_table">
                                                <thead>
                                                      <tr>
                                                            <th class="text-left" width="90%">Grading Type</th>
                                                            <th class="text-center" width="10%">Activate</th>
                                                      </tr>
                                                </thead>
                                          </table>
                                    </div>
                              </div>
                        </div>
            
                  </div>
                  
            </div>
      </div>
      <div id="toast-container" class="toast-top-right"></div>
</section>

@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script>
      const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
      })
</script>
<script>
      $(document).ready(function() {

            // Variable declaration
            var gradetypes = []


            // Calling Functions
            load_gradetypes()

            // Events 
            $(document).on('change', '.checkgradingtype', function() {
                  var id = $(this).attr('gradetype-id')

                  $('.checkgradingtype').not(this).prop('checked', false);
                  
                  $.ajax({
                        type: "GET",
                        url: "/grading_type/setup/activation",
                        data: {
                              id: id
                        },
                        success: function (data) {
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }else{
                                    load_gradetypes()
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }
                        }
                  });
            })
         
            // load data functions
            function load_gradetypes(){
                  $.ajax({
                        type: "GET",
                        url: "/grading_type/setup/list",
                        success: function (data) {
                              gradetypes = data
                              grading_type_datatable()
                        }
                  });
            }
          

            // display functions
            function grading_type_datatable(){

                  $('#grading_type_table').DataTable({
                        lengthMenu: false,
                        info: false,
                        paging: true,
                        searching: true,
                        destroy: true,
                        lengthChange: false,
                        scrollX: false,
                        autoWidth: false,
                        order: false,
                        data: gradetypes,
                        columns : [
                              {"data" : 'gradetype_desc'},
                              {"data" : null}
                        ],
                        columnDefs: [
                              {
                              'targets': 0,
                              'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var text = '<span>'+rowData.gradetype_desc+'</span>';
                                          $(td)[0].innerHTML =  text
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-left')
                                    }
                              },
                              {
                              'targets': 1,
                              'orderable': false, 
                              'createdCell':  function (td, cellData, rowData, row, col) {
                                          if (rowData.isactive == 1) {
                                                var text = '<input type="checkbox" class="checkgradingtype pt-1" id="checkgradingtype'+rowData.id+'" gradetype-id="'+rowData.id+'" checked style="width: 20px; height: 20px;">';
                                          } else {
                                                var text = '<input type="checkbox" class="checkgradingtype" id="checkgradingtype'+rowData.id+'" gradetype-id="'+rowData.id+'" style="width: 20px; height: 20px;">';
                                          }
                                          $(td)[0].innerHTML =  text
                                          $(td).addClass('align-middle pt-1')
                                          $(td).addClass('text-center')
                                    }
                              }
                        ]
                  })

                  // var label_text = $($('#modal_perdepartment_wrapper')[0].children[0])[0].children[0]
                  // $(label_text)[0].innerHTML = '<input class="text-center" type="checkbox" id="checkalldepartment"><span>&nbsp;&nbsp;Check All Department</span>'
            }
      });
</script>
     

@endsection


