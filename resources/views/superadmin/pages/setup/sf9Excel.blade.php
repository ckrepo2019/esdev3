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
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
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
                        'sy.*',
                        'sydesc as text'
                  )
                  ->get();
      
      $gradelvl = DB::table('gradelevel')
                        ->join('academicprogram',function($join){
                              $join->on('gradelevel.acadprogid','=','academicprogram.id');
                              $join->where('academicprogram.id','!=',6);
                        })
                        ->select(
                              'gradelevel.*',
                              'levelname as text'
                        )   
                        ->orderBy('sortid')
                        ->get();

      $strands = DB::table('sh_strand')
                        ->select(
                              'sh_strand.*',
                              'strandcode as text'
                        )  
                        ->where('deleted',0) 
                        ->where('active',1)
                        ->get();


@endphp


<div class="modal fade" id="copy_template_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title" style="font-size: 1.1rem !important">Copy From</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body pt-0">
                 <div class="row">
                      <div class="col-md-12 form-group">
                          <label for="">From School Year</label>
                          <select class="form-control form-control-sm" id="input_copy_sy">

                          </select>
                      </div>
                 </div>
                 <div class="row">
                  <div class="col-md-12 form-group">
                      <label for="">From Template</label>
                      <select class="form-control form-control-sm" id="input_copy_template">

                      </select>
                  </div>
             </div>
                 <div class="row">
                      <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="save_copy_template"><i class="fa fa-clone"></i> Copy</button>
                      </div>
                 </div>
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="template_gradelevel_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title" style="font-size: 1.1rem !important">Copy From</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body pt-0">
                 <div class="row">
                      <div class="col-md-12 form-group">
                          <label for="">School Year</label>
                          <select class="form-control form-control-sm" id="template_gradelevels">

                          </select>
                      </div>
                 </div>
                 <div class="row">
                      <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="save_gradelevel_to_template"><i class="fa fa-save"></i> Save</button>
                      </div>
                 </div>
              </div>
          </div>
      </div>
</div>



<div class="modal fade" id="template_strand_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title" style="font-size: 1.1rem !important">Template Stramd</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body pt-0">
                 <div class="row">
                      <div class="col-md-12 form-group">
                          <label for="">Strand</label>
                          <select class="form-control form-control-sm" id="template_strands">

                          </select>
                      </div>
                 </div>
                 <div class="row">
                      <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="save_strand_to_template"><i class="fa fa-save"></i> Save</button>
                      </div>
                 </div>
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="create_studinfo_sf9detail_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title" style="font-size: 1.1rem !important">Student Information</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body pt-0">
                 <div class="row">
                      <div class="col-md-12 form-group">
                          <label for="">Student Information</label>
                          <select class="form-control form-control-sm" id="input_studentinfos">

                          </select>
                      </div>
                 </div>
                 <div class="row ">
                  <div class="col-md-12 form-group">
                      <label for="">Cell Value</label>
                      <input type="text" class="form-control form-control-sm" id="input_sutdinfo_cellvalue"  onkeyup="this.value = this.value.toUpperCase();">
                  </div>
             </div>
                 <div class="row">
                      <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="create_studinfo_sf9detail_button"><i class="fa fa-save"></i> Save</button>
                      </div>
                 </div>
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="template_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title" style="font-size: 1.1rem !important">SF9 Template</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body pt-0">
                 <div class="row">
                      <div class="col-md-12 form-group">
                          <label for="">Template Description</label>
                          <input type="text" class="form-control form-control-sm" id="sf9template_description">
                      </div>
                 </div>
                 <div class="row">
                      <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="template_create"><i class="fa fa-save"></i> Save</button>
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
                        <h1>SF9 Excel Setup</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">SF9 Excel Setup</li>
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
                  <div class="col-md-3 ">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12 form-group">
                                                <label for="">School Year</label>
                                                <select name="" id="filter_sy" class="form-control form-control-sm select2">
                                                     
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12 ">
                                                <label for="">Template
                                                      <a href="javascript:void(0)" hidden class="edit_sf9template pl-2"><i class="far fa-edit"></i></a>
                                                      <a href="javascript:void(0)" hidden class="delete_sf9template pl-2"><i class="far fa-trash-alt text-danger"></i></a>
                                                </label>
                                                <select name="" id="sf9templates" class="form-control form-control-sm select2">
                                                      <option></option>
                                                </select>
                                          </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                          
                                          <form id="uploadformat" 
                                                      action="/sf9template/upload" 
                                                      method="POST" 
                                                      enctype="multipart/form-data"
                                                      class="col-md-12 "
                                                      >
                                                      @csrf
                                                      <label for="">Upload Format</label>
                                                      <input type="file" id="sf9templates_file" name="sf9templates_file" class="form-control form-control-sm" accept=
                                                      "application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                                                      text/plain, application/pdf, image/*" disabled>
                                                      <button class="btn btn-sm btn-primary mt-2 btn-submit" type="submit" disabled>Submit</button>
                                          </form>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <button class="btn btn-sm btn-block btn-default" id="uploaded_file" disabled>No File Uploaded</button>
                                          </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <label for="">Grade Level</label>
                                                <table class="table table-sm" style="font-size:.rem !important">
                                                     <tbody id="gradelevel_holder">
                                                            
                                                     </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row shs_setup" hidden>
                                          <div class="col-md-12">
                                                <label for="">Strand</label>
                                                <table class="table table-sm" style="font-size:.rem !important">
                                                     <tbody id="strand_holder">
                                                            
                                                     </tbody>
                                                </table>
                                          </div>
                                    </div>
                               
                              </div>
                        </div>
                  </div>
                  <div class="col-md-9 ">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <button disabled class="btn btn-warning btn-sm process-btn" style="font-size:.8rem !important" id="copy-from"><i class="fa fa-clone"></i> Copy From</button>
                                                <button disabled class="btn btn-primary btn-sm process-btn" style="font-size:.8rem !important" id="save-grade"><i class="fa fa-save"></i> Save Cell Value</button>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-6">
                                                <label for="" style="font-size:.9rem !important">Student Information</label>
                                          </div>
                                          <div class="col-md-6 text-right">
                                                <button disabled class="btn btn-primary btn-sm process-btn" id="add_info" style="font-size:.8rem !important"><i class="fa fa-plus"></i> Add Information</button>
                                          </div>
                                    </div>
                                    <div class="row mt-3" id="studinfo_holder">

                                    </div>
                                    <hr>
                                    <div class="row  border-top-1 gshs_setup"  style="font-size:.9rem !important" hidden>
                                          <div class="col-md-4">
                                                <h5  style="font-size:.9rem !important">Grades</h5>
                                          </div>
                                          <div class="col-md-2">
                                               
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Row Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="grades" class="form-control form-control-sm-form rowinterval">
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Column Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="grades" class="form-control form-control-sm-form colinterval" >
                                          </div>
                                    </div>
                                    <div class="row gshs_setup" hidden>
                                          <div class="col-md-12" style="font-size:.8rem !important">
                                                <table class="table table-sm table-bordered">
                                                      <thead>
                                                            <tr>
                                                                  <th rowspan="2" width="40%" class="align-middle text-center">Subject</th>
                                                                  <th rowspan="2" width="5%" class="align-middle text-center"></th>
                                                                  <th colspan="4" class="text-center">Quarter</th>
                                                                  <th rowspan="2" width="10%" class="text-center align-middle">Final Grade</th>
                                                                  <th rowspan="2" width="10%" class="text-center align-middle">Remarks</th>
                                                            </tr>
                                                            <tr>
                                                                  <th width="10%" class="text-center">1</th>
                                                                  <th width="10%" class="text-center">2</th>
                                                                  <th width="10%" class="text-center">3</th>
                                                                  <th width="10%" class="text-center">4</th>
                                                            <tr>
                                                                  <th></th>
                                                                  <th class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a  hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="grades_holder">
                                                            
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row  shs_setup border-top-1"  style="font-size:.9rem !important">
                                          <div class="col-md-4">
                                                <h5  style="font-size:.9rem !important">Grades (1st Semester)</h5>
                                          </div>
                                          <div class="col-md-2">
                                               
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Row Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="grades1st" class="form-control form-control-sm-form rowinterval">
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Column Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="grades1st" class="form-control form-control-sm-form colinterval" >
                                          </div>
                                    </div>
                                    <div class="row shs_setup">
                                          <div class="col-md-12" style="font-size:.8rem !important">
                                                <table class="table table-sm table-bordered">
                                                      <thead>
                                                            <tr>
                                                                  <th rowspan="2" width="40%" class="align-middle text-center">Subject</th>
                                                                  <th rowspan="2" width="5%" class="align-middle text-center"></th>
                                                                  <th colspan="2" class="text-center">Quarter</th>
                                                                  <th rowspan="2" width="10%" class="text-center align-middle">Final Grade</th>
                                                                  <th rowspan="2" width="10%" class="text-center align-middle">Remarks</th>
                                                            </tr>
                                                            <tr>
                                                                  <th width="20%" class="text-center">1</th>
                                                                  <th width="20%" class="text-center">2</th>
                                                            </tr>
                                                            <tr>
                                                                  <th></th>
                                                                  <th class="text-center align-middle"><a href="javascript:void(0)" data-type="grades1st" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                 
                                                            </tr>
                                                      </thead>
                                                      <tbody id="grades_holder_sem1">
                                                            
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row shs_setup border-top-1"  style="font-size:.9rem !important">
                                          <div class="col-md-4">
                                                <h5  style="font-size:.9rem !important">Grades (2nd Semester)</h5>
                                          </div>
                                          <div class="col-md-2">
                                               
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Row Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="grades2nd" class="form-control form-control-sm-form rowinterval">
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Column Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="grades2nd" class="form-control form-control-sm-form colinterval" >
                                          </div>
                                    </div>
                                    <div class="row shs_setup" >
                                          <div class="col-md-12" style="font-size:.8rem !important">
                                                <table class="table table-sm table-bordered">
                                                      <thead>
                                                            <tr>
                                                                  <th rowspan="2" width="40%" class="align-middle text-center">Subject</th>
                                                                  <th rowspan="2" width="5%" class="align-middle text-center"></th>
                                                                  <th colspan="2" class="text-center">Quarter</th>
                                                                  <th rowspan="2" width="10%" class="text-center align-middle">Final Grade</th>
                                                                  <th rowspan="2" width="10%" class="text-center align-middle">Remarks</th>
                                                            </tr>
                                                            <tr>
                                                                  <th width="20%" class="text-center">3</th>
                                                                  <th width="20%" class="text-center">4</th>
                                                            </tr>
                                                            <tr>
                                                                  <th></th>
                                                                  <th class="text-center align-middle"><a href="javascript:void(0)" data-type="grades2nd" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="grades"  class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="grades_holder_sem2">
                                                            
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    <hr class="mb-1">
                                    <div class="row mt-2 border-top-1"  style="font-size:.9rem !important">
                                          <div class="col-md-4">
                                                <h5  style="font-size:.9rem !important" class="pt-1">Observed Values</h5>
                                          </div>
                                          <div class="col-md-2">
                                               
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Row Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="observedvalues" class="form-control form-control-sm-form rowinterval">
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Column Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="observedvalues" class="form-control form-control-sm-form colinterval" >
                                          </div>
                                    </div>
                                    {{-- <div class="row mt-2">
                                          <div class="col-md-12" style="font-size:.9rem !important">
                                                <h5>Observed Values</h5>
                                          </div>
                                    </div> --}}
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.8rem !important">
                                                <table class="table table-sm table-bordered">
                                                      <thead>
                                                            <tr>
                                                                  <th rowspan="2" width="55%" class="align-middle text-center">Values</th>
                                                                  <th rowspan="2" width="5%" class="align-middle text-center"></th>
                                                                  <th colspan="4" class="text-center">Quarter</th>
                                                            </tr>
                                                            <tr>
                                                                  <th width="10%" class="text-center">1</th>
                                                                  <th width="10%" class="text-center">2</th>
                                                                  <th width="10%" class="text-center">3</th>
                                                                  <th width="10%" class="text-center">4</th>
                                                            </tr>
                                                            <tr>
                                                                  <th></th>
                                                                  <th class="text-center align-middle"><a href="javascript:void(0)" data-type="observedvalues" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="observedvalues" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="observedvalues" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="observedvalues" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                                  <th class="text-center"><a hidden href="javascript:void(0)" data-type="observedvalues" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></th>
                                                            </tr>
                                                            
                                                      </thead>
                                                      <tbody id="observevalues_holder"></tbody>
                                                </table>
                                          </div>
                                    </div>
                                     <hr class="mb-1">
                                    <div class="row mt-2 border-top-1"  style="font-size:.9rem !important">
                                          <div class="col-md-4">
                                                <h5  style="font-size:.9rem !important" class="pt-1">Attendance</h5>
                                          </div>
                                          <div class="col-md-2">
                                               
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Row Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="attendance" class="form-control form-control-sm-form rowinterval">
                                          </div>
                                          <label for="inputEmail3" class="col-sm-2 col-form-label text-right pt-1">Column Interval</label>
                                          <div class="col-md-1 ">
                                                <input type="text" data-type="attendance" class="form-control form-control-sm-form colinterval" >
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-sm table-bordered" style="font-size:.8rem !important">
                                                      <tbody id="attendance_holder">
                                                           
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" hidden>
                  <div class="col-md-12 ">
                        <div class="card">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12 ">
                                               <button class="btn btn-primary btn-sm">Add detail Value</button>
                                          </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-3">
                                                <label for="">First Name</label>
                                                <input type="text" class=" form-control form-control-sm">
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
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js')}}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>


      

      

      <script>
            var sf9templates = []
            var gradelvl = @json($gradelvl);
            var strand = @json($strands);
            var sy = @json($sy);
            var activesy = sy.filter(x=>x.isactive == 1)[0].id

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            $('#filter_gradelevel').select2({
                  placeholder:'Select Grade Level',
                  data:gradelvl,
                  allowClear:true      
            })

           


            $('#filter_sy').select2({
                  placeholder:'Select School Year',
                  data:sy,
            })

            $('#filter_sy').val(activesy).change()
           
            $('#sf9templates').select2({
                  placeholder:'Select Template',
                  data:[],
                  allowClear:true      
            })

           

      </script>

      <script>
            $(document).ready(function(){


                  $('#template_gradelevels').select2({
                        data:gradelvl,
                        placeholder:'Select Grade Level',
                        allowClear: true
                  })

                  $('#template_strands').select2({
                        placeholder:'Select Strand',
                        data:strand
                  })

                  $(document).on('click','#add_info',function(){
                        $('#input_studentinfos').val("").change()
                        $('#input_sutdinfo_cellvalue').val("")
                        $('#create_studinfo_sf9detail_modal').modal()
                  })

                  $(document).on('input','.cellinput',function(){
                       $(this).addClass('updated')
                  })

                  $(document).on('click','#template_gradelvl_tomodal',function(){
                        $('#template_gradelevels').val("").change()
                        $('#template_gradelevel_modal').modal()
                  })

                  $(document).on('click','#template_strand_tomodal',function(){
                        $('#template_strands').val("").change()
                        $('#template_strand_modal').modal()
                  })

                  $(document).on('click','#save_gradelevel_to_template',function(){
                        add_gradelevel_to_tempate()
                  })

                  $(document).on('click','#save_strand_to_template',function(){
                        add_strand_to_tempate()
                  })

                  $(document).on('click','.remove_gradelevel',function(){
                        Swal.fire({
                              text: 'Are you sure you want to remove grade level?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    var temp_id = $(this).attr('data-id')
                                    delete_gradelevel_to_tempate(temp_id)
                              }
                        })
                  })

                  $(document).on('click','.remove_strand',function(){
                        Swal.fire({
                              text: 'Are you sure you want to remove grade level?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    var temp_id = $(this).attr('data-id')
                                    delete_strand_to_tempate(temp_id)
                              }
                        })
                  })

                  $(document).on('click','.delete_sf9template',function(){
                        Swal.fire({
                              text: 'Are you sure you want to remove Template?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    sf9template_delete()
                              }
                        })
                  })
                   
                  function add_gradelevel_to_tempate(){
                        $.ajax({
                              type:'GET',
                              url:'/sf9template/create/gradelevel',
                              data:{
                                    sf9templateid: $('#sf9templates').val(),
                                    levelid: $('#template_gradelevels').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          sf9template_detail()
                                    }
                              },
                        })
                  }

                  function delete_gradelevel_to_tempate(id){
                        $.ajax({
                              type:'GET',
                              url:'/sf9template/delete/gradelevel',
                              data:{
                                    id:id,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          sf9template_detail()
                                    }
                              },
                        })
                  }

                  function add_strand_to_tempate(){
                        $.ajax({
                              type:'GET',
                              url:'/sf9template/create/strand',
                              data:{
                                    sf9templateid: $('#sf9templates').val(),
                                    strandid: $('#template_strands').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          sf9template_detail()
                                    }
                              },
                        })
                  }

                  function delete_strand_to_tempate(id){
                        $.ajax({
                              type:'GET',
                              url:'/sf9template/delete/strand',
                              data:{
                                    id:id,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          sf9template_detail()
                                    }
                              },
                        })
                  }


                  $(document).on('change','#filter_sy',function(){
                        sf9template()
                  })
                  

                  $('#uploadformat' ).submit( function( e ) {

                        if($('#sf9templates').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'No template selected!'
                              })
                              return false
                        }

                        var inputs = new FormData(this)
                        inputs.append('sf9templateID',$('#sf9templates').val())

                        $.ajax({
                                url: '/sf9template/upload',
                                type: 'POST',
                                data: inputs,
                                processData: false,
                                contentType: false,
                                success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          // $('#uploaded_file').removeAttr('disabled')
                                          // $('#uploaded_file').text('Download Tempate')
                                          // $('#uploaded_file').removeClass('btn-default')
                                          // $('#uploaded_file').addClass('btn-success')
                                          var tempdata = sf9templates.findIndex(x=>x.id == $('#sf9templates').val())
                                          sf9template[tempdata].filelocation = 'SF9/Template/template'+$('#sf9templates').val()+'.xlsx'

                                    }
                                }
                         })

                        e.preventDefault();

                    })


                  //   $('#uploadformat' ).submit( function( e ) {

                  //       var inputs = new FormData(this)
                  //       inputs.append('sf9templateID',$('#sf9templates').val())
                  //       //insert ajax here
                  //       e.preventDefault();

                  //   })

                    

            })

            sf9template()

            function sf9template(){
                  $('#sf9templates').empty()
                  $.ajax({
                        type:'GET',
                        url:'/sf9template/list',
                        data:{
                              syid:$('#filter_sy').val()
                        },
                        success:function(data) {
                              sf9templates = data
                              var select_sf9template = sf9templates;
                              $('#sf9templates').append('<option value="">Select Template</option>')
                              $('#sf9templates').append('<option value="add">+ Add Template</option>')
                              $('#sf9templates').select2({
                                    placeholder:'Select Template',
                                    data:sf9templates,
                                    allowClear:true      
                              })
                              $('#sf9templates').val("").change()
                        },
                  })
            }

      </script>    
      
      <script>

            var gradesdetail = []
            var sf9templateinfos = []
            $(document).ready(function(){
                  $('#template_modal').on('hidden.bs.modal', function (e) {
                        $('#sf9templates').val("").change()
                  })


                  $(document).on('click','#uploaded_file',function(){
                        window.open("/sf9templatedetail/downloadformat?id="+$('#sf9templates').val());
                  })


                  $(document).on('change','#sf9templates',function(){
                        $('#sf9templates_file').attr('disabled','disabled')
                        $('#gradelevel_holder').empty()
                        $('#strand_holder').empty()
                        $('#grades_holder').empty()
                        $('#grades_holder_sem1').empty()
                        $('#grades_holder_sem2').empty()
                        $('#observevalues_holder').empty()
                        $('#studinfo_holder').empty()
                        $('.edit_sf9template').attr('hidden','hidden')
                        $('.delete_sf9template').attr('hidden','hidden')
                        $('.btn-submit').attr('disabled','disabled')
                        $('#uploaded_file').attr('disabled','disabled')
                        $('#uploaded_file').text('No File Uploaded')
                        $('#uploaded_file').removeClass('btn-success')
                        $('#uploaded_file').addClass('btn-default')
                        $('.cellinput').val("").change()
                        $('.process-btn').attr('disabled','disabled')

                        if($(this).val() == "add"){
                              $('#template_modal').modal()
                              $('#template_create').text('Create')
                              $('#template_create').removeClass('btn-success')
                              $('#template_create').addClass('btn-primary')
                              $('#sf9template_description').val("").change()
                              $('#gradelevel_holder').empty()
                              $('#strand_holder').empty()
                              $('#grades_holder').empty()
                              $('#observevalues_holder').empty()
                        }else if($(this).val() != ""){
                              $('#sf9templates_file').removeAttr('disabled','disabled')
                              $('.edit_sf9template').removeAttr('hidden')
                              $('.delete_sf9template').removeAttr('hidden')
                              $('.btn-submit').removeAttr('disabled')
                              var check_if_uploaded = sf9templates.filter(x=>x.id == $(this).val())
                              if(check_if_uploaded[0].filelocation != null){
                                    $('#uploaded_file').removeAttr('disabled')
                                    $('#uploaded_file').text('Download Format')
                                    $('#uploaded_file').removeClass('btn-default')
                                    $('#uploaded_file').addClass('btn-success')
                              }
                              $('.process-btn').removeAttr('disabled')
                              sf9template_detail()
                        }
                  })
                  $(document).on('click','#template_create',function(){
                        $('#template_create').attr('disabled','disabled')
                        sf9template_create()
                  })
            })




            sf9templateinfo()

            function sf9templateinfo(){
                  $.ajax({
                        type:'GET',
                        url:'/sf9templateinfo/list',
                        success:function(data) {
                              sf9templateinfos = data
                              $('#input_studentinfos').select2({
                                    placeholder:'Select Student Information',
                                    data:sf9templateinfos,
                                    allowClear:true      
                              })
                              
                        }
                  })
            }

            var dayschooldaysspresent = []
            var dayspresent = []
            var daysabsent = []
            var genave1st = []
            var genave2nd = []

            function sf9template_detail(){
                  $('#studinfo_holder').empty();
                  $.ajax({
                        type:'GET',
                        url:'/sf9templatedetail/list',
                        data:{
                              'sf9templateid':$('#sf9templates').val()
                        },
                        success:function(data) {
                              sf9templatesdetail = data[0].detail
                              sf9templategradelevel = data[0].gradelevel
                              sf9templatestrand = data[0].strand
                              var select_sf9template = sf9templatesdetail;
                              gradesdetail = sf9templatesdetail.filter(x=>x.type=="grades")
                              genavedetail = sf9templatesdetail.filter(x=>x.type=="genave")
                              studinformation = sf9templatesdetail.filter(x=>x.type=="information")
                              observedvaluesdetail = sf9templatesdetail.filter(x=>x.type=="observedvalues")
                              genave1st = sf9templatesdetail.filter(x=>x.type=="genave1st")
                              genave2nd = sf9templatesdetail.filter(x=>x.type=="genave2nd")

                              $.each(studinformation,function(a,b){
                                    var infodesc = sf9templateinfos.filter(x=>x.id == b.dataid)
                                    $('#studinfo_holder').append(
                                          `<div class="col-md-3 form-group mb-2">
                                                <label for="" class="mb-1">`+infodesc[0].infodesc+` <a href="javascript:void(0)" class="remove_s9templateinfo" data-id="`+b.id+`"><i class="far fa-trash-alt text-danger" style="font-size:.7rem !important"></i></a></label>
                                                <input type="text" class="form-control form-control-sm-form cellinput form-control-sm-form" data-quarter="null" data-id="`+b.dataid+`" data-type="information"  onkeyup="this.value = this.value.toUpperCase();" value="`+b.cellvalue+`">
                                          </div>`)
                              })


                              $('#gradelevel_holder').empty()
                              $('#strand_holder').empty()

                              $.each(sf9templategradelevel,function(a,b){
                                    $('#gradelevel_holder').append('<tr><td width="90%">'+b.levelname+'</td><td width="10%"><a href="javascript:void(0)" class="remove_gradelevel" data-id="'+b.id+'">x</a></td></tr>')
                              })

                              $('#gradelevel_holder').append('<tr><td colspan="2"><a href="javascript:void(0)" id="template_gradelvl_tomodal">Add Grade Level</a></td></tr>')

                              $.each(sf9templatestrand,function(a,b){
                                    $('#strand_holder').append('<tr><td width="90%">'+b.strandcode+'</td><td width="10%"><a href="javascript:void(0)" class="remove_strand" data-id="'+b.id+'">x</a></td></tr>')
                              })

                              $('#strand_holder').append('<tr><td colspan="2"><a href="javascript:void(0)" id="template_strand_tomodal">Add Strand</a></td></tr>')

                              schooldays = sf9templatesdetail.filter(x=>x.type=="schooldays") 
                              dayspresent = sf9templatesdetail.filter(x=>x.type=="dayspresent") 
                              daysabsent = sf9templatesdetail.filter(x=>x.type=="daysabsent") 


                              sf9template_grade_detail()
                              observedvaluessetup()
                              attendancessetup()
                        },
                  })
            }

            function sf9template_create(){
                  $.ajax({
                        type:'GET',
                        url:'/sf9template/create',
                        data:{
                              'syid':$('#filter_sy').val(),
                              'sf9templateDecription':$('#sf9template_description').val()
                        },
                        success:function(data) {
                              $('#template_create').removeAttr('disabled')
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    sf9template()
                              }
                        },
                  })
            }

            function sf9template_delete(){
                  $.ajax({
                        type:'GET',
                        url:'/sf9template/delete',
                        data:{
                              'sf9templateID':$('#sf9templates').val()
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    $('#gradelevel_holder').empty()
                                    $('#grades_holder').empty()
                                    $('#observevalues_holder').empty()
                                    sf9template()
                              }
                        },
                  })
            }

            function sf9templatedeitail_delete(id){
                  $.ajax({
                        type:'GET',
                        url:'/sf9templatedetail/delete',
                        data:{
                              'detailid':id
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    $('#gradelevel_holder').empty()
                                    $('#grades_holder').empty()
                                    $('#observevalues_holder').empty()
                                    sf9template_detail()
                              }
                        },
                  })
            }

            $(document).on('click','.remove_s9templateinfo',function(){
                  Swal.fire({
                        text: 'Are you sure you want to remove student information?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                  }).then((result) => {
                        if (result.value) {
                              var tempid = $(this).attr('data-id')
                              sf9templatedeitail_delete(tempid)
                        }
                  })
            })


      </script>

      <script>
            // $(document).ready(function(){


                  $(document).on('click','#create_studinfo_sf9detail_button',function(){
                        
                        var type="information"
                        var dataid = $('#input_studentinfos').val()
                        var dataquarter = null
                        var cell = $('#input_sutdinfo_cellvalue').val()
                        var headerid = $('#sf9templates').val()

                        $.ajax({
                              type:'GET',
                              url:'/sf9templatedetail/create',
                              data:{
                                    'type':type,
                                    'data-id':dataid,
                                    // 'data-quarter':dataquarter,
                                    'cell':cell,
                                    'headerid':headerid,
                              },
                              success:function(data) {
                                    sf9template_detail()
                              
                              },
                        })
                  
                  })

                  $(document).on('click','#save-grade',function(){

                        $('input[data-type="grades"]').each(function(a,b){
                              var type="grades"
                              var dataid = $(this).attr('data-id')
                              var dataquarter = $(this).attr('data-quarter')
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })

                        $('input[data-type="genave"]').each(function(a,b){
                              var type="genave"
                              var dataid = $(this).attr('data-id')
                              var dataquarter = $(this).attr('data-quarter')
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })

                        $('input[data-type="genave1st"]').each(function(a,b){
                              var type="genave1st"
                              var dataid = $(this).attr('data-id')
                              var dataquarter = $(this).attr('data-quarter')
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })

                        $('input[data-type="genave2nd"]').each(function(a,b){

                              console.log($(this).val())
                              var type="genave2nd"
                              var dataid = $(this).attr('data-id')
                              var dataquarter = $(this).attr('data-quarter')
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })

                        $('input[data-type="observedvalues"]').each(function(a,b){
                              var type="observedvalues"
                              var dataid = $(this).attr('data-id')
                              var dataquarter = $(this).attr('data-quarter')
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })

                        $('input[data-type="information"]').each(function(a,b){
                              var type="information"
                              var dataid = $(this).attr('data-id')
                              var dataquarter = null
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          // 'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })

                        $('.attendance').each(function(a,b){
                              var type= $(this).attr('data-type')
                              var dataid = $(this).attr('data-id')
                              var dataquarter = null
                              var cell = $(this).val()
                              var headerid = $('#sf9templates').val()
                              $.ajax({
                                    type:'GET',
                                    url:'/sf9templatedetail/create',
                                    data:{
                                          'type':type,
                                          'data-id':dataid,
                                          // 'data-quarter':dataquarter,
                                          'cell':cell,
                                          'headerid':headerid,
                                    },
                                    success:function(data) {
                                    },
                              })
                        })
                      
                  })


                  var col_interval = 2;
                  var row_interval = 1;
                  var allsubjects = []

                  $(document).on('click','.clone_cell',function(){
                        var thissubject = $(this).attr('data-id')
                        var temptext = $('input[data-type="grades"][data-id="'+thissubject+'"][data-quarter=quarter1]').val()
                        var separtedtext = temptext.match(/[a-zA-Z]+|[0-9]+/g)
                        var letterIndex = excelletters.indexOf(excelletters[1]);
                        $('input[data-type="grades"][data-id="'+thissubject+'"]').each(function(a,b){
                              var cellvalue = excelletters[letterIndex-1]+separtedtext[1]
                              $(this).val(cellvalue)
                              letterIndex += col_interval
                        })
                  })
                  
                  $(document).on('click','.clone_cell_all',function(){

                        var datatype = $(this).attr('data-type')

                        var rowinterval = $('.rowinterval[data-type="'+datatype+'"]').val()
                        var colinterval = $('.colinterval[data-type="'+datatype+'"]').val()
                    
                        if(rowinterval == "" || colinterval == ""){

                              Toast.fire({
                                    type: 'warning',
                                    title: "Row and column interval is empty"
                              })
                              return false
                        }

                        var temptext = $($('.'+datatype)[0]).val()

                        if(temptext == "" ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Fill in first input."
                              })
                              return false
                        }

                        var separtedtext = temptext.match(/[a-zA-Z]+|[0-9]+/g)
                        var rowIndex = parseInt(separtedtext[1])

                        var rowData = []

                        console.log(temptext)

                        if(datatype == "attendance"){
                              rowData = [
                                    {'type':'schooldays'},
                                    {'type':'dayspresent'},
                                    {'type':'daysabsent'}
                              ]

                              $.each(rowData,function(a,b){
                                    var letterIndex = parseInt(excelletters.indexOf(separtedtext[0]));
                                    $.each(attendance_setup,function(c,d){
                                          var cellvalue = excelletters[letterIndex]+rowIndex
                                          console.log(c.id + ' - ' + b)
                                          $('.'+datatype+'[data-id="'+d.id+'"][data-type="'+b.type+'"]').val(cellvalue)
                                          letterIndex += parseInt(colinterval)
                                    })
                                    rowIndex += parseInt(rowinterval)
                              })
                        }else if(datatype == "grades" ){
                               $.each(allsubjects,function(a,b){
                                    var letterIndex = excelletters.indexOf(separtedtext[0]);
                                    $('.'+datatype+'[data-id="'+b.subjid+'"]').each(function(a,b){
                                          var cellvalue = excelletters[letterIndex]+rowIndex
                                          $(this).val(cellvalue)
                                          letterIndex += parseInt(colinterval)
                                          console.log(letterIndex)
                                    })
                                    rowIndex += parseInt(rowinterval)
                              })
                              var letterIndex = excelletters.indexOf(separtedtext[0]);
                                    $('.'+datatype+'[data-type="genave"]').each(function(a,b){
                                          var cellvalue = excelletters[letterIndex]+rowIndex
                                          $(this).val(cellvalue)
                                          letterIndex += parseInt(colinterval)
                                          console.log(letterIndex)
                                    })
                             
                        }else if(datatype == "observedvalues"){
                               $.each(allobserved,function(a,b){
                                    var letterIndex = excelletters.indexOf(separtedtext[0]);
                                    $('.'+datatype+'[data-id="'+b.id+'"]').each(function(a,b){
                                          var cellvalue = excelletters[letterIndex]+rowIndex
                                          $(this).val(cellvalue)
                                          letterIndex += parseInt(colinterval)
                                          console.log(letterIndex)
                                    })
                                    rowIndex += parseInt(rowinterval)
                              })
                        }else if(datatype == "grades1st"){
                               $.each(allsubjects.filter(x=>x.semid == 1),function(a,b){
                                    var letterIndex = excelletters.indexOf(separtedtext[0]);
                                    $('.'+datatype+'[data-id="'+b.subjid+'"]').each(function(a,b){
                                          var cellvalue = excelletters[letterIndex]+rowIndex
                                          $(this).val(cellvalue)
                                          letterIndex += parseInt(colinterval)
                                          
                                    })
                                    rowIndex += parseInt(rowinterval)
                              })

                              var letterIndex = excelletters.indexOf(separtedtext[0]);
                              $('.'+datatype+'[data-type="genave1st"]').each(function(a,b){
                                    var cellvalue = excelletters[letterIndex]+rowIndex
                                    $(this).val(cellvalue)
                                    letterIndex += parseInt(colinterval)
                                    
                              })

                        }else if(datatype == "grades2nd"){
                               $.each(allsubjects.filter(x=>x.semid == 2),function(a,b){
                                    var letterIndex = excelletters.indexOf(separtedtext[0]);
                                    $('.'+datatype+'[data-id="'+b.subjid+'"]').each(function(a,b){
                                          var cellvalue = excelletters[letterIndex]+rowIndex
                                          $(this).val(cellvalue)
                                          letterIndex += parseInt(colinterval)
                                          console.log(letterIndex)
                                    })
                                    rowIndex += parseInt(rowinterval)
                              })

                              var letterIndex = excelletters.indexOf(separtedtext[0]);
                              $('.'+datatype+'[data-type="genave2nd"]').each(function(a,b){
                                    var cellvalue = excelletters[letterIndex]+rowIndex
                                    $(this).val(cellvalue)
                                    letterIndex += parseInt(colinterval)
                                    
                              })
                        }

                  })


                  
                  function sf9template_grade_detail(){
                        $('#grades_holder').empty()
                        $('#grades_holder_sem1').empty()
                        $('#grades_holder_sem2').empty()

                        $('.gshs_setup').attr('hidden','hidden')
                        $('.shs_setup').attr('hidden','hidden')


                        if(sf9templategradelevel.length == 0 ){
                              return false
                        }

                        $.ajax({
                              type:'GET',
                              url:'/superadmin/setup/subject/plot/list',
                              data:{
                                    'syid':$('#filter_sy').val(),
                                    'levelid':sf9templategradelevel[0].levelid
                              },
                              success:function(data) {


                                    var check_for_shs = sf9templategradelevel.filter(x=>x.levelid == 14 || x.levelid == 15)

                                    if(check_for_shs.length > 0){

                                          $('.shs_setup').removeAttr('hidden')

                                          allsubjects = data
                                          var filteredsubjects = []

                                          $.each(sf9templatestrand,function(a,b){
                                                var strand_subjects = allsubjects.filter(x=>x.strandid == b.strandid)
                                                $.each(strand_subjects,function(c,d){
                                                      var check_if_already_exist = filteredsubjects.filter(x=>x.subjid == d.subjid)
                                                      if(check_if_already_exist.length == 0){
                                                            filteredsubjects.push(d)
                                                      }
                                                })
                                          })

                                        

                                          $.each(filteredsubjects,function(a,b){

                                                if(b.semid == 1){
                                                      var trstring = ` <tr>
                                                                        <td>`+b.subjdesc+`</td>
                                                                        <td class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="`+b.subjid+`" class="clone_cell"><i class="fa fa-clone"></i></a></td>
                                                                        <td><input type="text" data-quarter="quarter1" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter2" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="finalgrade" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="remarks" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  </tr>`
                                                      $('#grades_holder_sem1').append(trstring)
                                                }else{
                                                      var trstring = ` <tr>
                                                                        <td>`+b.subjdesc+`</td>
                                                                        <td class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="`+b.subjid+`" class="clone_cell"><i class="fa fa-clone"></i></a></td>
                                                                        <td><input type="text" data-quarter="quarter3" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter4" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="finalgrade" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="remarks" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  </tr>`
                                                      $('#grades_holder_sem2').append(trstring)
                                                }
                                          })


                                          var trstring = ` <tr>
                                                                  <td>General Average</td>
                                                                  <td class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="999" class="clone_cell grades1st"><i class="fa fa-clone"></i></a></td>
                                                                  <td><input type="text" data-quarter="quarter1" data-type="genave1st" data-id="999" class="form-control form-control-sm-form cellinput grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  <td><input type="text" data-quarter="quarter2" data-type="genave1st" data-id="999" class="form-control form-control-sm-form cellinput grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  <td><input type="text" data-quarter="finalgrade" data-type="genave1st" data-id="999" class="form-control form-control-sm-form cellinput grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  <td><input type="text" data-quarter="remarks" data-type="genave1st" data-id="999" class="form-control form-control-sm-form grades1st" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                            </tr>`
                                                
                                                $('#grades_holder_sem1').append(trstring)

                                                var trstring = ` <tr>
                                                                        <td>General Average</td>
                                                                        <td class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="999" class="clone_cell grades2nd"><i class="fa fa-clone"></i></a></td>
                                                                        <td><input type="text" data-quarter="quarter3" data-type="genave2nd" data-id="999" class="form-control form-control-sm-form cellinput grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter4" data-type="genave2nd" data-id="999" class="form-control form-control-sm-form cellinput grgrades2ndades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="finalgrade" data-type="genave2nd" data-id="999" class="form-control form-control-sm-form cellinput grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="remarks" data-type="genave2nd" data-id="999" class="form-control form-control-sm-form grades2nd" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  </tr>`
                                                
                                                $('#grades_holder_sem2').append(trstring)
                                               

                                    }else{

                                          allsubjects = data

                                          $('.gshs_setup').removeAttr('hidden')

                                          $.each(data,function(a,b){

                                                var trstring = ` <tr>
                                                                        <td>`+b.subjdesc+`</td>
                                                                        <td class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="`+b.subjid+`" class="clone_cell"><i class="fa fa-clone"></i></a></td>
                                                                        <td><input type="text" data-quarter="quarter1" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter2" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter3" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter4" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="finalgrade" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="remarks" data-type="grades" data-id="`+b.subjid+`" class="form-control form-control-sm-form grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  </tr>`
                                                
                                                $('#grades_holder').append(trstring)
                                          
                                          })

                                          var trstring = ` <tr>
                                                                        <td>General Average</td>
                                                                        <td class="text-center align-middle"><a href="javascript:void(0)" data-type="grades" data-id="999" class="clone_cell"><i class="fa fa-clone"></i></a></td>
                                                                        <td><input type="text" data-quarter="quarter1" data-type="genave" data-id="999" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter2" data-type="genave" data-id="999" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter3" data-type="genave" data-id="999" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="quarter4" data-type="genave" data-id="999" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="finalgrade" data-type="genave" data-id="999" class="form-control form-control-sm-form cellinput grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                        <td><input type="text" data-quarter="remarks" data-type="genave" data-id="999" class="form-control form-control-sm-form grades" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                                  </tr>`
                                                
                                          $('#grades_holder').append(trstring)

                                          
                                   
                                    }

                                    $.each(gradesdetail,function(a,b){
                                          $('input[data-type="grades"][data-id="'+b.dataid+'"][data-quarter="'+b.quarter+'"]').val(b.cellvalue)
                                    })

                                    $.each(genavedetail,function(a,b){
                                          $('input[data-type="genave"][data-id="'+b.dataid+'"][data-quarter="'+b.quarter+'"]').val(b.cellvalue)
                                    })

                                    $.each(genave1st,function(a,b){
                                          $('input[data-type="genave1st"][data-id="'+b.dataid+'"][data-quarter="'+b.quarter+'"]').val(b.cellvalue)
                                    })

                                    $.each(genave2nd,function(a,b){
                                          $('input[data-type="genave2nd"][data-id="'+b.dataid+'"][data-quarter="'+b.quarter+'"]').val(b.cellvalue)
                                    })

                                    
                              },
                        })
                  }
            // })
      </script>

      <script>
            $(document).ready(function(){
                  $(document).on('change','#filter_gradelevel',function(){
                        $('#observevalues_holder').empty()
                        observedvaluessetup()
                  })
            })

            var allobserved = []

            function observedvaluessetup(){
                  $('#observevalues_holder').empty()

                  $.ajax({
                        type:'GET',
                        url:'/superadmin/setup/observed/values/list',
                        data:{
                              'syid':$('#filter_sy').val(),
                              'gradelevel':sf9templategradelevel[0].levelid
                        },
                        success:function(data) {

                              allobserved = data

                              $.each(data,function(a,b){

                                    var trstring = ` <tr>
                                                            <td>`+b.description+`</td>
                                                            <td class="text-center align-middle"><a href="javascript:void(0)" data-type="observedvalues" data-id="`+b.id+`" class="clone_cell"><i class="fa fa-clone"></i></a></td>
                                                            <td><input type="text" data-quarter="quarter1" data-type="observedvalues" data-id="`+b.id+`" class="form-control form-control-sm-form observedvalues" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                            <td><input type="text" data-quarter="quarter2" data-type="observedvalues" data-id="`+b.id+`" class="form-control form-control-sm-form observedvalues" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                            <td><input type="text" data-quarter="quarter3" data-type="observedvalues" data-id="`+b.id+`" class="form-control form-control-sm-form observedvalues" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                            <td><input type="text" data-quarter="quarter4" data-type="observedvalues" data-id="`+b.id+`" class="form-control form-control-sm-form observedvalues" onkeyup="this.value = this.value.toUpperCase();"></td>
                                                      </tr>`
                                    
                                    $('#observevalues_holder').append(trstring)


                                    $.each(observedvaluesdetail,function(a,b){
                                          $('input[data-type="observedvalues"][data-id="'+b.dataid+'"][data-quarter="'+b.quarter+'"]').val(b.cellvalue)
                                    })
                              
                              })
                        
                        },
                  })
            }


            var attendance_setup = []
            function attendancessetup(){

                  if($( "#myselect option:selected" ).text() == 'MWSP'){
                        
                  }

                  $('#attendance_holder').empty()
                  $.ajax({
                        type:'GET',
                        url:'/superadmin/attendance/list',
                        data:{
                              'syid':$('#filter_sy').val(),
                              'levelid':sf9templategradelevel[0].levelid
                        },
                        success:function(data) {

                              attendance_setup = data

                              if(attendance_setup.length > 0){

                                    var trheader = '<tr><td width="17%"></td><td width="5%"></td>'

                                    $.each(data,function(a,b){
                                          trheader += '<td width="6.5%" class="text-center">'+b.monthdesc.substring(0,3)+'</td>'
                                    })
                                    trheader += '</tr>'
                                    $('#attendance_holder').append(trheader)


                                    var trheader = '<tr><td></td><td class="text-center"><a href="javascript:void(0)" data-id="`+b.id+`" class="clone_cell_all" data-type="attendance"><i class="fa fa-clone"></i></a></td>'
                              
                                    $.each(data,function(a,b){
                                          trheader += '<td width="6.5%" class="text-center"><a href="javascript:void(0)" data-type="schooldays" data-id="'+b.id+'" class="clone_column"><i class="fa fa-clone"></i></a></td>'
                                    })

                                    trheader += '</tr>'

                                    $('#attendance_holder').append(trheader)

                                    var trheader = '<tr class="align-middle"><td>School Days</td><td class="text-center align-middle"><a href="javascript:void(0)" data-type="schooldays" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></td>'
                              
                                    $.each(data,function(a,b){
                                          trheader += '<td width="6.5%" class="text-center"><input type="text" data-id="'+b.id+'" data-type="schooldays" class="form-control form-control-sm-form cellinput attendance" onkeyup="this.value = this.value.toUpperCase();"></td>'
                                    })

                                    trheader += '</tr>'

                                    $('#attendance_holder').append(trheader)

                                    var trheader = '<tr class="align-middle"><td>Days Present</td><td class="text-center align-middle"><a href="javascript:void(0)" data-type="dayspresent" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></td>'
                              
                                    $.each(data,function(a,b){
                                          trheader += '<td width="6.5%" class="text-center"><input type="text" data-id="'+b.id+'" data-type="dayspresent" class="form-control form-control-sm-form cellinput attendance" onkeyup="this.value = this.value.toUpperCase();"></td>'
                                    })

                                    trheader += '</tr>'

                                    $('#attendance_holder').append(trheader)

                                    var trheader = '<tr class="align-middle"><td>Days Absent</td><td class="text-center align-middle"><a href="javascript:void(0)" data-type="daysabsent" data-id="`+b.id+`" class="clone_cell_all"><i class="fa fa-clone"></i></a></td>'
                                    
                                    $.each(data,function(a,b){
                                          trheader += '<td width="6.5%" class="text-center"><input type="text" data-id="'+b.id+'" data-type="daysabsent" class="form-control form-control-sm-form cellinput attendance" onkeyup="this.value = this.value.toUpperCase();"></td>'
                                    })

                                    trheader += '</tr>'

                                    $('#attendance_holder').append(trheader)


                                    
                                    $.each(schooldays,function(a,b){
                                          $('.cellinput[data-type="schooldays"][data-id="'+b.dataid+'"]').val(b.cellvalue)
                                    })

                                    $.each(dayspresent,function(a,b){
                                          $('.cellinput[data-type="dayspresent"][data-id="'+b.dataid+'"]').val(b.cellvalue)
                                    })

                                    $.each(daysabsent,function(a,b){
                                          $('.cellinput[data-type="daysabsent"][data-id="'+b.dataid+'"]').val(b.cellvalue)
                                    })
                              }

                        },
                  })
            }
            
      </script>

      
      <script>
            //copy
            var sy = @json($sy);
            var activesy = sy.filter(x=>x.isactive == 1)[0].id
            $('#input_copy_sy').select2({
                  placeholder:'Select School Year',
                  data:sy,
            })
            $('#input_copy_sy').val(activesy).change()
            sf9template_copy()
            
            $(document).on('change','#input_copy_sy',function(){
                  sf9template_copy()
            })

            $(document).on('click','#save_copy_template',function(){
                  save_copy_template()
            })

            $(document).on('click','#copy-from',function(){
                  sf9template_copy()
                  $('#copy_template_modal').modal()
            })

            function sf9template_copy(){
                  $('#input_copy_template').empty()
                  $.ajax({
                        type:'GET',
                        url:'/sf9template/list',
                        data:{
                              syid:$('#input_copy_sy').val()
                        },
                        success:function(data) {
                              var temp_sf9templates = data.filter(x=>x.id != $('#sf9templates').val())

                              $('#input_copy_template').append('<option value="">Select Template</option>')
                              $('#input_copy_template').select2({
                                    placeholder:'Select Template',
                                    data:temp_sf9templates,
                                    allowClear:true      
                              })
                              
                        },
                  })
            }

            function save_copy_template(){

                  $.ajax({
                        type:'GET',
                        url:'/sf9templatedetail/copy',
                        data:{
                              to:$('#sf9templates').val(),
                              from:$('#input_copy_template').val()
                        },
                        success:function(data) {

                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                                    sf9template_detail()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                              // var temp_sf9templates = data.filter(x=>x.id != $('#sf9templates').val())

                              // $('#input_copy_template').append('<option value="">Select Template</option>')
                              // $('#input_copy_template').select2({
                              //       placeholder:'Select Template',
                              //       data:temp_sf9templates,
                              //       allowClear:true      
                              // })
                              
                        },
                  })


            }
            
      </script>




      <script>
            var excelletters = []
            $(document).ready(function(){
                  $.ajax({
                        type:'GET',
                        url:'/sf9template/excelletters',
                        success:function(data) {
                              excelletters = data
                        }
                  })
            })
      </script>

@endsection


