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
            body {
                  z-index: -1;
            }
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
            .no-cursor {
                  cursor: none;
            }
            iframe {
                  -webkit-user-select: none; /* Safari */
                  -moz-user-select: none; /* Firefox */
                  -ms-user-select: none; /* IE10+/Edge */
                  user-select: none; /* Standard syntax */
            }
            #pdf_viewer_topdesc {
                  /* border: 5px solid blue; */
                  position: relative;
                  width: 100%;
                  height: 810px;
                  overflow: hidden;
                  cursor:grab;
                  opacity: 1;
                  z-index: 1000;
                  padding: 0 !important;
            }

            #topdescpdf {
                  /* border: 5px solid blue; */
                  z-index: -1;
            }
            #pdf-wrapper {
                  position: relative;
                  width: 100%;
                  height: 810px;
                  overflow: hidden;
                  
                  opacity: .5;
                  z-index: 1000;
                  padding: 0 !important;

            }

            #pdf-viewer {
                  position: absolute;
                  /* left: 15px;
                  top: 15px; */
                  /* background-color: blue; */
                  left: 0;
                  top: 0;
                  width: 100%;
                  height: 810px;
                  border: none;
                  display: none;
                  z-index: inherit;
                  -webkit-touch-callout: none;
                  -webkit-user-select: none;
                  -moz-user-select: none;
                  -ms-user-select: none;
                  user-select: none;
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
                        <h1>User Guide</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">User Guide</li>
                  </ol>
                  </div>
            </div>
            <div class="row">
                  <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-right" style="display:flex!importanrt">
                              <li><span class=""><span class="badge badge-info">M</span> - <b>Manual</b></span></li> &nbsp;&nbsp;&nbsp;
                              <li><span class=""><span class="badge badge-secondary">T</span> - <b>FAQ's</b></span> </li>
                        </ol>
                  </div>
            </div>
      </div>
</section>

<section class="content pt-0">

      

      <!-- Modal When Add FAQ / Manual button is click -->
      <div class="modal fade" id="modal_adddetails">
            <div class="modal-dialog modal-md">
            <div class="modal-content">
                  <div class="modal-header  bg-primary addetails pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="">Add Topic / Description</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-header bg-primary updetails pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="">Update Topic / Description</h4>

                        <input type="text" id="userguideid" hidden>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>

                  {{-- <form action="/faqs/setup/createfaq" id="submitfile" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                        
                              <div class="form-group" style="text-align: justify!important;">
                                    <label for="textare_topicdesc">Topic / Description</label>
                                    <textarea required name="topdesc" class="form-control" id="textare_topicdesc" rows="3" onkeyup="this.value = this.value.toUpperCase();"></textarea>
                              </div>
                              <div class="form-group">
                                    <select name="ftype" class="form-control form-control-sm select2" id="select_filetype">
                                          <option value="0" selected="selected">Select File Type</option>
                                          <option value="1">Faqs</option>
                                          <option value="2">Manual</option>
                                    </select>
                              </div>
                              <div class="input-group input-group-sm upload">
                                    <input type="file" class="form-control" name="file" id="file">
                              </div>
                        </div>
                        <div class="modal-footer border-0">
                              <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn_create_details"> Create</button>
                                    <button class="btn btn-success btn-sm" id="btn_update_details"> Update</button>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                              </div>
                        </div>
                  </form> --}}

                  <form id="submitfile" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                        
                              <div class="form-group" style="text-align: justify!important;">
                                    <label for="textare_topicdesc">Topic / Description</label>
                                    <textarea required name="topdesc" class="form-control" id="textare_topicdesc" rows="3" onkeyup="this.value = this.value.toUpperCase();"></textarea>
                              </div>
                              {{-- <div class="form-group">
                                    <h6>Users</h6>
                                    <select class=" form-control-sm select2" name="usersselect[]" id="usersselect" multiple="multiple">
                                    </select>
                              </div> --}}
                              <div class="form-group">
                                    {{-- <label for="">School Year</label> --}}
                                    <select name="ftype" class="form-control form-control-sm select2" id="select_filetype">
                                          <option value="0" selected="selected">Select File Type</option>
                                          <option value="1">Faqs</option>
                                          <option value="2">Manual</option>
                                    </select>
                              </div>
                              <div class="input-group input-group-sm upload">
                                    <input type="file" class="form-control" name="file" id="file">
                              </div>
                        </div>
                        <div class="modal-footer">
                              <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn_create_details"> Create</button>
                                    <button type="submit" class="btn btn-success btn-sm" id="btn_update_details"> Update</button>
                              </div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                              </div>
                        </div>
                  </form>

            </div>
            </div>
      </div>
      {{-- ========================================================================================================= --}}

      <div class="modal fade" id="modal_userfaqs">
            <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Frequently Ask Questions</h4>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        {{-- <div class="row">
                              <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btn_assignfaqs" style="font-size:15px"><i class="fas fa-plus"></i> Assign FAQ</button>
                              </div>
                        </div> <br> --}}
                        <div class="row">
                            <div class="col-md-12" style="font-size: 14px">
                                <table class="table table-sm table-bordered" id="datatable_faqs" width="100%">
                                    <input type="text" id="utype_id" hidden>
                                    <thead>
                                          <tr>
                                                <th width="80%">List of FAQ's</th>
                                                <th class="text-center"  width="10%">View PDF</th>
                                                <th class="text-center"  width="10%"></th>
                                          </tr>
                                    </thead>
                              </table>
                            </div>
                        </div>
                        
                    </div>
            </div>
            </div>
      </div>
      {{-- ========================================================================================================= --}}


      <div class="modal fade" id="modal_manuallist">
            <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">List of Manual Added</h4>
                        {{-- <input type="text" id="utypeid" hidden> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        {{-- <div class="row">
                              <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btn_assignfaqs" style="font-size:15px"><i class="fas fa-plus"></i> Assign FAQ</button>
                              </div>
                        </div> <br> --}}
                        <div class="row">
                            <div class="col-md-12" style="font-size: 14px">
                                <table class="table table-sm table-bordered" id="datatable_manuallist" width="100%">
                                    <input type="text" id="utypeid" hidden>
                                    <thead>
                                          <tr>
                                                <th width="80%">Manual List</th>
                                                <th class="text-center"  width="10%">View PDF</th>
                                                <th class="text-center"  width="10%"></th>
                                          </tr>
                                    </thead>
                              </table>
                            </div>
                        </div>
                        
                    </div>
            </div>
            </div>
      </div>
      {{-- ========================================================================================================= --}}


      <!-- Modal When Assign FAQ button is click -->
      <div class="modal fade" id="modal_assignfaqs">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-body">
                        <input type="text" id="usertype_id" hidden>
                        <input type="text" id="usertypeid" hidden>
                        
                        <div class="row">
                              <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                    <table width="100%" class="table table-sm table-bordered table-head-fixed" id="datatable_assignedfaqs"  style="font-size:14px">
                                          <thead>
                                                <tr>
                                                      <th width="85%">Frequently Ask Questions</th>
                                                      <th class="text-center" width="15%"></th>
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
      {{-- ========================================================================================================= --}}


      <!-- Modal When + Assign Manual button is click -->
      <div class="modal fade" id="modal_assignmanual">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <input type="text" id="userid" hidden>
                              <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                    <table width="100%" class="table table-sm table-bordered table-head-fixed" id="datatable_assignmanual"  style="font-size:14px">
                                          <thead>
                                                <tr>
                                                      <th width="85%">Manuals</th>
                                                      <th class="text-center" width="15%"></th>
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
      {{-- ========================================================================================================= --}}


      <!-- Modal When Assign FAQ button is click -->
      <div class="modal fade" id="modal_addmanual">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <input type="text" id="utypeid" hidden>
                              <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                    <table width="100%" class="table table-sm table-bordered table-head-fixed" id="datatable_manuals"  style="font-size:14px">
                                          <thead>
                                                <tr>
                                                      <th width="85%">Manuals</th>
                                                      <th class="text-center" width="15%"></th>
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
      {{-- ========================================================================================================= --}}


      <div class="row">
            <div class="col-md-6">
                  <div class="card">
                        <div class="card-body">
                        <div class="row">
                              <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="datatable_1"  style="font-size:15px">
                                    <thead>
                                          <tr>
                                                <th width="60%">Topic / Description</th>
                                                <th class="text-center" width="10%"></th>
                                                <th class="text-center" width="15%">File Type</th>
                                                <th class="text-center" width="15%"></th>
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



            <div class="col-md-6">
                  <div class="card">
                        <div class="card-body">
                        <div class="row">
                              <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="datatable_2"  style="font-size:14px">
                                    <thead>
                                          <tr>
                                                <th width="70%">User Type</th>
                                                <th class="text-center" width="15%">FAQ's</th>
                                                <th class="text-center" width="15%">Manual</th>
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

      {{-- when pdf is click --}}

      

      <div class="unselectable">
            <div class="modal fade" id="modal_topdescpdf">
                  <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                              {{-- <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span>×</span>
                                    </button>
                              </div> --}}
                              
                              <div class="modal-body">
                                    {{-- <div id="pdf-wrapper">
                                          <iframe id="pdf-viewer" ></iframe>
                                    </div> --}}
                                    <div id="pdf_viewer_topdesc">
                                          <div id="topdescpdf"></div>
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
            $(document).ready(function() {
                  $('#select_filetype').select2()

                  
            });

            
      </script>
      
      <script>
            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            $(document).ready(function(){
                  // **************************************************************************************************************************
                  // declare ang mga variable
                  var usertype = @json($usertype);
                  var usertypev2 = [];
                  var topdesc = [];
                  var loadedassignfaqs = [];
                  var loadedassignmanual = [];
                  var loadmanual = [];
                  var loaduserguidedetail = [];
                  $('.addetails').show()
                  $('.updetails').hide()
                  $('#btn_create_details').show()
                  $('#btn_update_details').hide()
                  // **************************************************************************************************************************
                  // Tawag mga functions
                  loadusertype()
                  datatable_2()
                  loadtopdesc()
                  datatable_1()
                  datatable_manuals()
                  load_userguidedetail()

                  var usertypeid = $('#usertypeid').val();
                  // usersselection()

                  // **************************************************************************************************************************
                  // Events

                  $('#modal_adddetails').on('hidden.bs.modal', function () {
                        $('#file').val('')
                        $('#select_filetype').val(0).trigger('change')
                        // $('#updatesubmitfile').attr('id', 'submitfile');
                  })
                  $('#modal_userfaqs').on('hidden.bs.modal', function () {
                        var utypeid = '';
                        loadassignfaqs(utypeid)
                  })
                  $('#modal_manuallist').on('hidden.bs.modal', function () {
                        var utypeid = '';
                        loadassignmanual(utypeid)
                  })


                  // click +Add FAQ / Manual button para mmag pop up ang modal nga "Add Topic / Description"
                  $(document).on('click', '#btn_adddetails', function(){
                        $('#updatesubmitfile').attr('id', 'submitfile');
                        $('#select_filetype').removeAttr('disabled', 'disabled')
                        // $('#submitfile').removeAttr('action', '/faqs/setup/update_details');
                        // // $('#submitfile').submit();
                        // $('#file').prop('disabled', false);


                        $('.addetails').show()
                        $('.updetails').hide()
                        $('#btn_create_details').show()
                        $('#btn_update_details').hide()

                        
                        $('#textare_topicdesc').val('')
                        $('#select_filetype').val()

                        $('#modal_adddetails').modal('show')
                  })
                  
                  // click ang create button para ma save ang details
                  $(document).on('click', '#btn_create_details', function(){
                        
                        createfaq()
                  })

                  // click View FAQs button Data Table 2
                  $(document).on('click', '.btn_viewfaqs', function(){
                        var utypeid = $(this).attr('utypeid');

                        $('#utype_id').val(utypeid);
                        $('#modal_userfaqs').modal('show')
                        loadassignfaqs(utypeid)
                        
                  })
                  
                  // click + Assign FAQ button
                  $(document).on('click', '#btn_assignfaqs', function(){
                        var utypeid = $('#utype_id').val();

                        loadassignfaqs(utypeid)
                        $('#usertype_id').val(utypeid);
                        $('#usertypeid').val(utypeid);
                        $('#modal_assignfaqs').modal('show')
                  })
                  
                  // click the check Icon
                  $(document).on('click', '.assignfaqs', function(){
                        var utypeid = $('#usertype_id').val()
                        var descriptionid = $(this).attr('descriptionid')
                        
                        assignfaqs(descriptionid, utypeid)
                  })

                  // Click plus icon in datatable 2 under Manual Column
                  $(document).on('click', '.add_manual', function(){
                        var utypeid = $(this).attr('utypeid');

                        datatable_manuals()
                        $('#utypeid').val(utypeid);
                        $('#modal_addmanual').modal('show')
                  })
                  // Click plus icon in datatable 2 under Manual Column
                  $(document).on('click', '.assignmanual', function(){
                        var descriptionid = $(this).attr('descriptionid')
                        var utypeid = $('#utypeid').val();

                        assignmanual(descriptionid, utypeid)
                  })

                  // click trash icon to remove Topic/Description
                  $(document).on('click', '.delete_topdesc', function(){
                        var topdescid = $(this).attr('userguideid');

                        delete_topdesc(topdescid)
                  })

                  // delete assigned manual
                  $(document).on('click', '.delete_manual', function(){
                        var userid = $(this).attr('userid')
                        var descid = $(this).attr('descriptionid')
                        
                        delete_manual(userid, descid)
                  })


                  // view manual list
                  $(document).on('click', '.view_manuallist', function(){
                        var utypeid = $(this).attr('utypeid');

                        $('#utypeid').val(utypeid);
                        $('#modal_manuallist').modal('show')
                        loadassignmanual(utypeid)
                  })
                  
                  // click button + Assign Manual inside list of manual added modal
                  $(document).on('click', '#btn_assignmanual', function(){
                        var usertypeid =  $('#utypeid').val();

                        $('#userid').val(usertypeid);
                        $('#modal_assignmanual').modal('show')
                        
                  })

                  // click check box assign to assign manual modal # 2
                  $(document).on('click', '.assignmanualv2', function(){
                        var manualid = $(this).attr('descriptionid')
                        var usertypeid = $('#userid').val()

                        assignmanualv2(manualid, usertypeid)
                  })
                  
                  // delete assign FAQ modal 1
                  $(document).on('click', '.delete_assignedfaq', function(){
                        var userid = $(this).attr('userid')
                        var descid = $(this).attr('descriptionid')

                        delete_faqs(userid, descid)
                  })

                  // Edit Topic Description 
                  $(document).on('click', '.edit_topdesc', function(){

                        $('#submitfile').attr('id', 'updatesubmitfile');
                        $('#select_filetype').attr('disabled', 'disabled')
                        // $('#file').hide();
                        // $('#submitfile').attr('action', '/faqs/setup/update_details');
                        // $('#submitfile').submit();
                        // $('#file').prop('disabled', true);


                        $('.addetails').hide()
                        $('.updetails').show()
                        $('#btn_create_details').hide()
                        $('#btn_update_details').show()


                        var userguideid = $(this).attr('userguideid')
                        var usefiletype = $(this).attr('usefiletype')
                        var userguidedesc = $(this).attr('userguidedesc')

                        var publicPath = '{{ asset('') }}';
                        var filepath = $(this).attr('filepath')


                  

                        $('#userguideid').val(userguideid)
                        $('#textare_topicdesc').val(userguidedesc)
                        $('#select_filetype').val(usefiletype).change()

                        // $('#file').change(publicPath+filepath)
                        // $('#file').attr("disabled", true)
                       
                        
                        $('#modal_adddetails').modal('show')
                  })

                  $(document).on('click', '#btn_update_details', function(){
                        update_details()
                  })
                  
                  // 
                  $(document).on('click', '.viewtopdescpdf', function(){

                        var topdescid = $(this).attr('userguideid')
                        var filepath = $(this).attr('filepath')
                        console.log(filepath);
                        
                        viewtopdescpdf(filepath)
                  })
                  // **************************************************************************************************************************
                  // functions

                  // |||||||||||||||||| Selec2 functions ||||||||||||||||||

                  //  select2 for all dropdown USERTYPE
                  // function usersselection(){
                  //       $('#usersselect').empty()
                  //       $('#usersselect').append('<option value="">Select Users</option>')
                  //       $('#usersselect').select2({
                  //       data: usertype,
                  //       allowClear : true,
                  //       placeholder: 'Select Users'
                  //       });
                  // }


                  // |||||||||||||||||| ajax functions ||||||||||||||||||
                  
                  // |||||||||||||||||| LOADING  functions ||||||||||||||||||
                  //load all usertype

                  function loadusertype(){

                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/loadusertype",
                              // data: "data",
                              // dataType: "dataType",
                              success: function (data) {
                                    usertypev2 = data
                                    // console.log(usertypev2);
                                    datatable_2()
                              }
                        });
                  }

                  // load faqs and manual 
                  function load_userguidedetail(){
                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/loaduserguidedetail",
                              // data: "data",
                              // dataType: "dataType",
                              success: function (data) {
                                    loaduserguidedetail = data
                                    // console.log(loaduserguidedetail);
                                    datatable_2()
                                    // datatable_manuals()
                                    datatable_faqs()
                                    manual_list()
                                    datatable_assignmanual()
                              }
                        });
                  }

                  
                  // load all the Topic / Description
                  function loadtopdesc(){

                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/loadtopdesc",
                              success: function (data) {
                                    topdesc = data
                                    datatable_1()
                                    load_userguidedetail()
                                    datatable_manuals()
                              }
                        });
                  }
                  
                  // load all the assigned FAQ's
                  function loadassignfaqs(utypeid){
                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/loadassignfaqs",
                              data: {
                                    utypeid : utypeid
                              },
                              success: function (data) {
                                    loadedassignfaqs = data
                                    datatable_assignedfaqs()
                                    datatable_faqs()
                                    load_userguidedetail()
                              }
                        });
                  }

                  // load all the assigned manual
                  function loadassignmanual(utypeid){

                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/loadassignmanual",
                              data: {
                                    utypeid : utypeid
                              },
                              success: function (data) {
                                    loadedassignmanual = data
                                    manual_list()
                                    datatable_assignmanual()
                              }
                        });
                  }

                  // load all Manuals
                  function loadmanuals(){
                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/loadmanuals",
                              // data: "data",
                              // dataType: "dataType",
                              success: function (data) {
                                    loadmanual = data
                                    datatable_2()
                                    
                                    
                              }
                        });
                  }

                  

                  // click viewtopdescpdf button 
                  function viewtopdescpdf(filepath){
                        $('#modal_topdescpdf').modal('show')

                        // $('#topdescpdf').html('<iframe id="pdfview" src="/'+filepath+'#toolbar=0&navpanes=0&scrollbar=0" height="1000px" width="100%" allowfullscreen webkitallowfullscreen></iframe>');
                        $('#topdescpdf').html('<iframe id="pdfview" src="/'+filepath+'" height="1000px" width="100%" allowfullscreen webkitallowfullscreen></iframe>');
                    
                       
                  } 
                  
                  
                  // |||||||||||||||||| OTHERS  functions ||||||||||||||||||

                  // click the check box icon 
                  function assignfaqs(descriptionid, utypeid){
                        
                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/assignfaqs",
                              data: {
                                    utypeid : utypeid,
                                    descriptionid : descriptionid
                              },
                              success: function (data) {
                                    
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                          
                                    }else{
                                          loadassignfaqs(utypeid)
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              }
                        });
                  }
                  // click the  assign button (to assign manual) modal 2
                  function assignmanualv2(manualid, usertypeid){

                        $.ajax({
                              type: "GET",
                              url: "/faqs/setup/assignmanualv2",
                              data: {
                                    manualid : manualid,
                                    usertypeid : usertypeid
                              },
                              success: function (data) {
                                    // console.log(data);
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                          
                                    }else{
                                          loadassignmanual(usertypeid)
                                          load_userguidedetail()
                                          // $('#modal_assignmanual').modal('hide')
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              }
                        });
                  }


                  // click the plus box icon under manual column
                  function assignmanual(descriptionid, utypeid){
                        var username = usertype.filter(x=>x.id == utypeid)[0];
                        var manual_description = topdesc.filter(x=>x.id == descriptionid)[0];

                        Swal.fire({
                        // text: 'Are you sure you want to add '+manual_description.description+' to user '+username.utype+' ?',
                        html: 'Are you sure you want to add <b>'+manual_description.description+'</b> <br> to user <b>'+username.utype+'</b>',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Add'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type: "GET",
                                          url: "/faqs/setup/assignmanual",
                                          data: {
                                                utypeid : utypeid,
                                                descriptionid : descriptionid
                                          },
                                          success: function (data) {
                                                if(data){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Successfully Added'
                                                      })
                                                loadtopdesc()
                                                $('#modal_addmanual').modal('hide')
                                                datatable_2()
                                                loadusertype()
                                                
                                                }else{
                                                      Toast.fire({
                                                      type: 'success',
                                                      title: 'Already Exist'
                                                      })
                                                }
                                          }
                                    });   
                              }
                        })


                        
                  }

                  function createfaq(){
                        $('#submitfile').submit( function(e){
                        var inputs = new FormData(this)

                        console.log(inputs);
                        $.ajax({
                              url: '/faqs/setup/createfaq',
                              type:'POST',
                              data: inputs,
                              // dataType: 'json',
                              processData: false,
                              contentType: false,
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                          
                                    }else{
                                          loadtopdesc()
                                          load_userguidedetail()
                                          $('#modal_adddetails').modal('hide')
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })

                        e.preventDefault();

                        })
                  }

                  // edit topic description
                  function update_details(){

                        var userguideid = $('#userguideid').val()
                        var description = $('#textare_topicdesc').val()
                        var filetype = $('#select_filetype').val()

                        // console.log(userguideid);
                        // console.log(description);
                        // console.log(filetype);

                        $('#updatesubmitfile').submit( function(e){

                              var inputs = new FormData(this)
                              inputs.append('userguideid', userguideid)
                              inputs.append('description', description)
                              inputs.append('filetype', filetype)

                              // return false;
                              $.ajax({
                                    url: '/faqs/setup/update_details',
                                    type:'POST',
                                    data: inputs,
                                    // dataType: 'json',
                                    processData: false,
                                    contentType: false,
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                loadtopdesc()
                                                load_userguidedetail()
                                                $('#modal_adddetails').modal('hide')
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].message
                                                })
                                                
                                          }else{
                                                
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].message
                                                })
                                          }
                                    }
                                    // ,
                                    // error: function (response) {
                                    //       var errors = response.responseJSON.errors;
                                    //       console.log(errors );
                                    //       if (errors.file) {
                                    //             Toast.fire({
                                    //                   type: 'error',
                                    //                   title: 'You need to upload a file'
                                    //             })
                                    //       } 
                                    // }
                              })

                              e.preventDefault();

                        })


                        // $.ajax({
                        //       type: "GET",
                        //       url: "/faqs/setup/update_details",
                        //       data: {
                        //             userguideid : userguideid,
                        //             description : description,
                        //             filetype : filetype
                        //       },
                        //       success: function (data) {
                        //             if(data[0].status == 0){
                        //                   Toast.fire({
                        //                         type: 'error',
                        //                         title: data[0].message
                        //                   })
                                          
                        //             }else{
                        //                   loadtopdesc()
                        //                   load_userguidedetail()
                        //                   $('#modal_adddetails').modal('hide')
                        //                   Toast.fire({
                        //                         type: 'success',
                        //                         title: data[0].message
                        //                   })
                        //             }
                        //       }
                        // });

                  }

                  // |||||||||||||||||| DELETING  functions ||||||||||||||||||
                  // delete Topic ? Description in table 1
                  function delete_topdesc(topdescid){
                        var topic_description = topdesc.filter(x=>x.id == topdescid)[0];

                        Swal.fire({
                        // text: '',
                        html: 'Are you sure you want to Remove',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                     $.ajax({
                                          type: "GET",
                                          url: "/faqs/setup/deletetopdesc",
                                          data: {
                                                topdescid : topdescid
                                          },
                                          success: function (data) {
                                                if(data[0].status == 0){
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                      
                                                }else{
                                                      loadtopdesc()
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                }
                                          }
                                     });
                              }
                        })
                  }


                  // delete Assigned Manual in Table 2
                  function delete_manual(userid, descid){
                        var usertypeid = userid;
                        var descriptionid = descid;

                        console.log(usertypeid);
                        console.log(descriptionid);
                        Swal.fire({
                        // text: '',
                        html: 'Are you sure you want to Remove Assigned Manual?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type: "GET",
                                          url: "/faqs/setup/deletemanualv2",
                                          data: {
                                                usertypeid : usertypeid,
                                                descriptionid : descriptionid
                                          },
                                          success: function (data) {
                                                if (data) {
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                      loadassignmanual(usertypeid)
                                                      load_userguidedetail()
                                                }
                                          }
                                    });
                              }
                        })
                  }


                  // delete Assigned Manual in Table 2
                  function delete_faqs(userid, descid){
                        var utypeid = userid;
                        var descriptionid = descid;

                        Swal.fire({
                        // text: '',
                        html: 'Are you sure you want to Remove Assigned FAQs?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type: "GET",
                                          url: "/faqs/setup/deletefaqs",
                                          data: {
                                                utypeid : utypeid,
                                                descriptionid : descriptionid
                                          },
                                          success: function (data) {
                                                if (data) {
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                      loadassignfaqs(utypeid)
                                                      load_userguidedetail()
                                                }
                                          }
                                    });
                              }
                        })
                  }

                  // |||||||||||||||||| DATATABLES  functions ||||||||||||||||||

                  // datatable for all the add Topics / Description
                  function datatable_1(){
                        
                        var datatable_1 = topdesc;
                        console.log(datatable_1);
                        $('#datatable_1').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: true,
                              order: false,
                              data: datatable_1,
                              columns : [
                                    {"data" : "description"},
                                    {"data" : null},
                                    {"data" : null},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" class="viewtopdescpdf" userguideid="'+rowData.id+'" filepath="'+rowData.filepath+'"><i  class="far fa-file-pdf"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1 text-center')
                                          if (rowData.filetype == 1) {
                                                // var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">FAQs</a>';
                                                var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important"><span class="badge badge-secondary mr-2">F</span></a>';
                                                
                                          } else {
                                                // var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">Manual</a>';
                                                var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important"><span class="badge badge-info mr-2">M</span></a>';
                                          }
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" class="delete_topdesc" userguideid="'+rowData.id+'"><i class="fas fa-trash-alt text-danger"></i></a> &nbsp;&nbsp; <a href="javascript:void(0)" class="edit_topdesc"  userguidedesc="'+rowData.description+'" userguideid="'+rowData.id+'" usefiletype="'+rowData.filetype+'"  filepath="'+rowData.filepath+'"><i class="fas fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    }
                                    

                              ]
                        }); 
                        var label_text = $($('#datatable_1_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button type="button" class="btn btn-primary" id="btn_adddetails" style="font-size:15px"><i class="fas fa-plus"></i> Add FAQ / Manual</button>'
                  }

                  // datatable for all the add Topics / Description
                  function datatable_2(){

                        $('#datatable_2').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: true,
                              order: false,
                              data: usertypev2,
                              columns : [
                                    {"data" : "utype"},
                                    {"data" : null},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.utype+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          
                                          var usersmanualcount = loaduserguidedetail.filter(x=>x.utype == rowData.id && x.filetype == 1)
                                          if (usersmanualcount) {

                                                var count = usersmanualcount.length;

                                          } else {
                                                var count = 0;
                                          }

                                          $(td).addClass('align-middle text-center')
                                          var text = '<a href="javascript:void(0)" class="btn_viewfaqs" utypeid="'+rowData.id+'" style="font-size: 16px"><span class="badge badge-white text-primary mr-2">'+count+'</span> <i class="far fa-window-restore"></i></a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                               
                                                // if (rowData.utype != '' && rowData.utype != null && rowData.filetype == 2) {
                                                //       var buttons = '<a href="javascript:void(0)" class="view_manual"><i  class="far fa-file-pdf"></i></a></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="delete_manual" ><i class="far fa-trash-alt text-danger"></i></a>';
                                                // } else {
                                                //       var buttons = '<a href="javascript:void(0)" class="add_manual" utypeid="'+rowData.id+'"><i class="fas fa-plus"></i></a>';
                                                // }

                                                // var buttons = '<a href="javascript:void(0)" class="add_manual" utypeid="'+rowData.id+'"><i class="fas fa-plus"></i> view</a>';
                                          var usersmanualcount = loaduserguidedetail.filter(x=>x.utype == rowData.id && x.filetype == 2)
                                          if (usersmanualcount) {

                                                var count = usersmanualcount.length;

                                          } else {
                                                var count = 0;
                                          }
                                          
                                          var buttons = '<a href="javascript:void(0)" class="view_manuallist" utypeid="'+rowData.id+'" style="font-size: 16px"><span class="badge badge-white text-primary mr-2">'+count+'</span> <i class="far fa-window-restore"></i></a>';

                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                                
                                          }
                                    }

                              ]
                        }); 
                        
                  }

                  
                  // DataTables for list of usertype FAQS
                  function datatable_faqs(){
                        var uid = $('#utype_id').val();

                        var loadassignfaqs = loadedassignfaqs
                        $('#datatable_faqs').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: false,
                              autoWidth: false,
                              order: false,
                              data: loadassignfaqs,
                              columns : [
                                    {"data" : "description"},
                                    {"data" : null},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1 text-center')
                                          var text = '<a href="javascript:void(0)" style="font-size: 14px!important;" class="viewtopdescpdf" utypeid="'+rowData.id+'" filepath="'+rowData.filepath+'"><i style="font-size: 15px" class="far fa-file-pdf"></i></a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1 text-center')
                                          var text = '<a href="javascript:void(0)" style="font-size: 14px!important;" class="btn btn-danger delete_assignedfaq" descriptionid="'+rowData.descriptionid+'" userid="'+uid+'">remove</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    }
                              ]
                        });   

                        var label_text = $($('#datatable_faqs_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button type="button" class="btn btn-primary" id="btn_assignfaqs" style="font-size:15px"><i class="fas fa-plus"></i> Assign FAQ</button>'
                  

                  }

                  // datatable_assignedfaqs 
                  function datatable_assignedfaqs(){
                        var loadtopdesc = topdesc.filter(x=>x.filetype == 1);
                        
                        
                        $('#datatable_assignedfaqs').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: false,
                              autoWidth: false,
                              order: false,
                              data: loadtopdesc,
                              columns : [
                                    {"data" : "description"},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var loadedaf = loadedassignfaqs; 
                                                var filterfaqs = loadedaf.filter(x=>x.descriptionid == rowData.id);

                                                if (filterfaqs != '' && filterfaqs != null) {
                                                      var buttons = '<a href="javascript:void(0)" class="assignfaqs text-success" descriptionid="'+rowData.id+'"><i class="far fa-check-square"  style="font-size:15px"></i> assigned</a>';
                                                } else {
                                                      var buttons = '<a href="javascript:void(0)" class="assignfaqs" descriptionid="'+rowData.id+'"><i class="far fa-check-square" style="font-size:15px"></i> assign</a>';
                                                }
                                                // var buttons = '<a href="javascript:void(0)" class="assignfaqs" descriptionid="'+rowData.id+'"><i class="far fa-check-square"></i> assign</a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-left')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    }
                                    

                              ]
                        });

                  }



                  // datatable_assignedManual 
                  function datatable_assignmanual(){
                        var loadtopdesc = topdesc.filter(x=>x.filetype == 2);
                        
                        $('#datatable_assignmanual').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: false,
                              autoWidth: false,
                              order: false,
                              data: loadtopdesc,
                              columns : [
                                    {"data" : "description"},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                // var loadedaf = loadedassignfaqs; 
                                                // var filterfaqs = loadedaf.filter(x=>x.descriptionid == rowData.id);

                                                // if (filterfaqs != '' && filterfaqs != null) {
                                                //       var buttons = '<a href="javascript:void(0)" class="text-success" disabled descriptionid="'+rowData.id+'"><i class="far fa-check-square"  style="font-size:15px"></i> assigned</a>';
                                                // } else {
                                                //       var buttons = '<a href="javascript:void(0)" class="assignmanualv2" descriptionid="'+rowData.id+'"><i class="far fa-check-square" style="font-size:15px"></i> assign</a>';
                                                // }

                                                var assignmanual = loadedassignmanual.filter(x=>x.descriptionid == rowData.id);
                                                var countmanual = assignmanual.length;
                                                if (countmanual) {
                                                      var buttons = '<a href="javascript:void(0)" class="assignmanualv2 text-success" descriptionid="'+rowData.id+'"><i class="far fa-check-square" style="font-size:15px"></i> assigned</a>';
                                                } else {
                                                      var buttons = '<a href="javascript:void(0)" class="assignmanualv2" descriptionid="'+rowData.id+'"><i class="far fa-check-square" style="font-size:15px"></i> assign</a>';
                                                }
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-left')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    }
                                    

                              ]
                        });

                  }



                  // modal when plus icon is click under column datatable
                  function datatable_manuals(){
                        var loadedmanual = topdesc.filter(x=>x.filetype == 2);

                        

                        $('#datatable_manuals').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: false,
                              autoWidth: false,
                              order: false,
                              data: loadedmanual,
                              columns : [
                                    {"data" : "description"},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var loadedmanual = loaduserguidedetail; 
                                                var manual_details = loadedmanual.filter(x=>x.descriptionid == rowData.id)[0];
                                                
                                                if (manual_details == null) {
                                                      var buttons = '<a href="javascript:void(0)" class="assignmanual" descriptionid="'+rowData.id+'"><i class="far fa-check-square" style="font-size:15px"></i></a>';

                                                } else {
                                                      var buttons = '<a href="javascript:void(0)" class="assignmanual" descriptionid="'+rowData.id+'"><i class="far fa-check-square text-success"  style="font-size:15px"></i></a>';
                                                }


                                                
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    }
                                    

                              ]
                        });
                  }

                  // datatable manual list table 2

                  function manual_list(){
                        var uid = $('#utypeid').val()

                        var loadedassignmanuallist = loadedassignmanual;
                        $('#datatable_manuallist').DataTable({
                              destroy: true,
                              lengthChange: false,
                              scrollX: false,
                              autoWidth: false,
                              order: false,
                              data: loadedassignmanuallist,
                              columns : [
                                    {"data" : "description"},
                                    {"data" : null},
                                    {"data" : null}
                              ], 
                              columnDefs : [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1')
                                          var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                          $(td).addClass('align-middle p-1 text-center')
                                          var text = '<a href="javascript:void(0)" style="font-size: 14px!important;" class="viewtopdescpdf" filepath="'+rowData.filepath+'"><i style="font-size: 15px" class="far fa-file-pdf"></i></a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle p-1 text-center')
                                          var text = '<a href="javascript:void(0)" style="font-size: 14px!important;" class="btn btn-danger delete_manual" descriptionid="'+rowData.descriptionid+'" userid="'+uid+'">remove</a>';
                                          $(td)[0].innerHTML =  text
                                          }
                                    }
                              ]
                        });

                        var label_text = $($('#datatable_manuallist_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button type="button" class="btn btn-primary" id="btn_assignmanual" style="font-size:15px"><i class="fas fa-plus"></i> Assign Manual</button>'
                  }
            })
      </script>

@endsection


