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
      <style>
            /* .select2-selection--single{
                height: calc(2.25rem + 2px) !important;
            } */
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .has-error .select2-selection {
                  /*border: 1px solid #a94442;
                  border-radius: 4px;*/
                  border-color:#bd2130 !important;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->get(); 
      $semester = DB::table('semester')->get(); 
      $active_sy = DB::table('sy')->where('isactive',1)->first()->id;

      if(auth()->user()->type == 17){
            $teacher_acadprog = DB::table('academicprogram')
                                    ->select('id as acadprogid')
                                    ->get();
      }else{
            $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                              ->select('id')
                              ->first()
                              ->id;

            $teacher_acadprog = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid)
                              ->where('teacherid',$teacherid)
                              ->where('syid',$active_sy)
                              ->whereIn('acadprogutype',[3,8])
                              ->distinct('acadprogid')
                              ->where('deleted',0)
                              ->get();
      }

      $acadprog = array();

      foreach($teacher_acadprog as $item){
            array_push($acadprog,$item->acadprogid);
      }

      $gradelevel = DB::table('gradelevel')
                        ->where('deleted',0)
                        ->orderBy('sortid')
                        ->whereIn('acadprogid',$acadprog)
                        ->select('id','levelname','levelname as text')
                        ->get();
@endphp


<div class="modal fade" id="modal_document" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group docdesc-form">
                                    <label for="">Document Description
                                          <a href="javascript:void(0)" hidden class="pl-2" id="edit_docdesc"><i class="far fa-edit"></i></a>
                                          <a href="javascript:void(0)" hidden class="pl-2" id="delete_docdesc"><i class="far fa-trash-alt text-danger"></i></a>
                                    </label>
                                    <select name="" id="input_description" class=" form-control select2"></select>
                                    <div id="invalidDocDesc" class="invalid-feedback">Please make a selection.</div>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Document Sort</label>
                                    <input id="input_sequence" class="form-control" placeholder="Document Sequence" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                                    <div id="invalidSeq" class="invalid-feedback">Please enter a sort value</div>
                                    <div class="valid-feedback">
                                          Document sort looks good!
                                    </div>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Student Type</label>
                                    <select name="stud_type" id="stud_type" class="form-control select2">
                                          <option value="" >All</option>
                                          <option value="New">New Student</option>
                                          <option value="Transferee">Transferee Student</option>
                                    </select>
                              </div>
                              <div class="col-md-12">
                                    <div class="form-group clearfix">
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="input_isrequired">
                                                <label for="input_isrequired">
                                                      Required
                                                </label>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12">
                                    <div class="form-group clearfix">
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="input_isactive" checked>
                                                <label for="input_isactive">
                                                      Active
                                                </label>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="create_document">Create</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>


<div class="modal fade" id="document_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Add New Document Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                  <label for="">Document Description
                                  </label>
                                  <input class="form-control form-control-sm" id="input_document" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                                  <div id="invalidDocu" class="invalid-feedback"></div>
                                  <div class="valid-feedback">Document description looks good!</div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="document-f-btn">Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="copy_document" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="" id="copy_description"></label>
                              </div>
                              <div class="col-md-12">
                                    <div class="form-group clearfix">
                                          <div class="icheck-primary d-inline ">
                                          <input type="checkbox" id="apply_to_all">
                                          <label for="apply_to_all">ALL GRADE LEVEL
                                          </label>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Grade Level</label>
                                    <select name="to_gradelevel" id="to_gradelevel" class="form-control select2" multiple>

                                    </select>
                              </div>
                              
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="button_to_copy">Copy</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>    

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Document Requirement</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Document Requirement</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<section class="content pt-0">

      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-1">
                                   <p class="mb-0">Note: The document requirement would let the student know what requirements needed to bring the enrollment.</p>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-3">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label for="">Grade Level</label>
                                          <select class="form-control select2" id="filter_gradelevel">
                                                @foreach ($gradelevel as $item)
                                                      <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              {{-- <div class="row mt-3">
                                    <div class="col-md-12">
                                          <button class="btn btn-primary btn-sm" id="filter_button">Filter</button>
                                    </div>
                              </div> --}}
                          </div>
                        </div>
                  </div>
                  <div class="col-md-5">
                  </div>
                  <div class="col-md-2">
                        <div class="info-box shadow-lg">
                          <span class="info-box-icon bg-success"><i class="fas fa-calendar-alt"></i></span>
            
                          <div class="info-box-content">
                            <span class="info-box-text">Required</span>
                            <span class="info-box-number" id="total_required">0</span>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-2">
                        <div class="info-box shadow-lg">
                          <span class="info-box-icon bg-danger"><i class="fas fa-calendar-alt"></i></span>
            
                          <div class="info-box-content">
                            <span class="info-box-text">Active</span>
                            <span class="info-box-number" id="total_active">0</span>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12 text-right">
                      
                  </div>
            </div>
            <div class="row mt-3">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                   
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="document_setup" width="100%">
                                                      <thead>
                                                            <tr> 
                                                                  <th width="5%">Sort</th>
                                                                  <th width="40%">Description</th>
                                                                  <th width="15%">Student Type</th>
                                                                  <th width="5%">Active</th>
                                                                  <th width="10%">Required</th>
                                                                  <th width="5%"></th>
                                                                  <th width="5%"></th>
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
</section>

@endsection

@section('footerjavascript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      

      <script>
            $(document).ready(function(){

                  var all_document = []
                  var all_docdesc = []
                  var selected_docdescid = null
                  var selected_docdesctext = null
                  var selected_document
                  var process = 'create'
                  var gradelevel = @json($gradelevel)

                  $("#filter_gradelevel").empty()
                  $("#filter_gradelevel").append('<option value="">Select Grade Level</option>')
                  $("#filter_gradelevel").select2({
                        data: gradelevel,
                        allowClear: true,
                        placeholder: "Select Grade Level",
                  })

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  loaddatatable()
                  list_all_docdesc()

                  $(document).on('change','#filter_gradelevel',function(){
                        if($(this).val() == ""){

                              $('#copy_all').attr('disabled','disabled')
                              $('#button_document').attr('disabled','disabled')

                              all_document = []
                              loaddatatable()

                              return false;
                        }

                        $('#copy_all').removeAttr('disabled','disabled')
                        $('#button_document').removeAttr('disabled','disabled')

                        get_document()
                  })

                  $(document).on('click','#create_document',function(){
                        if(process == 'create'){
                              create_document()
                        }else if(process == 'edit'){
                              update_document()  
                        }
                  })

                  $(document).on('click','.delete_document',function(){
                        selected_document = $(this).attr('data-id')
                        delete_document()
                  })

                  $(document).on('click','.edit_document',function(){
                        selected_document = $(this).attr('data-id')
                        process = 'edit'

                        var temp_document_id = all_document.filter(x=>x.id == selected_document)
                        var temp_docdesc = all_docdesc.filter(x=>x.text == temp_document_id[0].description)

                        if (temp_docdesc.length != 0) {
                              $('#input_description').val(temp_docdesc[0].id).trigger("change");
                        } else {
                              list_all_docdesc()
                        }

                        $('#input_description').prop("disabled", true);
                        $('#edit_docdesc').attr('hidden','hidden')
                        $('#delete_docdesc').attr('hidden','hidden')
                        $('#input_sequence').val(temp_document_id[0].docsort)
                        $('#input_acadprog').val(temp_document_id[0].acadprogid).change()
                        $('#stud_type').val(temp_document_id[0].doc_studtype).change()

                        
                        if(temp_document_id[0].isActive == 1){
                              $('#input_isactive').prop('checked',true)
                        }else{
                              $('#input_isactive').prop('checked',false)
                        }

                        if(temp_document_id[0].isRequired == 1){
                              $('#input_isrequired').prop('checked',true)
                        }else{
                              $('#input_isrequired').prop('checked',false)
                        }


                        $('#modal_document').modal()   
                        $('#create_document').text('Update')           
                  })

                  function get_document(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/document/list',
                              data:{
                                    levelid:$('#filter_gradelevel').val()
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: "Document setup is empty."
                                          })
                                          all_document = []
                                          loaddatatable()
                                    }else{
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length+" document(s) found."
                                          })
                                          all_document = data
                                          loaddatatable()
                                    }
                              }
                        })
                  }

                  function create_document(){

                        // console.log(selected_docdescid, selected_docdesctext)

                        var isactive = 0;
                        var isrequied = 0;
                        var isvalid = true;

                        if($('#input_isrequired').prop('checked') == true){
                              isrequied = 1
                        }
                        if($('#input_isactive').prop('checked') == true){
                              isactive = 1
                        }

                        if($('#input_description').val() == ""){

                              select2_docdesc_error(
                                    '.docdesc-form',
                                    '#invalidDocDesc',
                                    'Please select a document description'
                              )

                              Toast.fire({
                                    type: 'warning',
                                    title: 'Document description is empty!'
                              })

                              isvalid = false

                        }
                        // else if($('#input_sequence').val() == ""){

                        //       // select2_docdesc_error(
                        //       //       '.docdesc-form',
                        //       //       '#invalidDocDesc',
                        //       //       'Document sequence is empty!'
                        //       // )
                        //       $('#input_sequence').addClass('is-invalid')
                        //       $('#invalidSeq').text('Document sequence is empty!')

                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Document sequence is empty!'
                        //       })
                              
                        //       isvalid = false
                        // }

                        if(isvalid){
                              $.ajax({
                                    type:'GET',
                                    url: '/superadmin/setup/document/create',
                                    data:{
                                          description: selected_docdesctext,
                                          sequence:$('#input_sequence').val(),
                                          isactive:isactive,
                                          isrequired:isrequied,
                                          levelid:$('#filter_gradelevel').val(),
                                          studtype:$('#stud_type').val(),
                                          headerid: selected_docdescid
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){

                                                $('#modal_document').modal('hide')
                                                all_document = data[0].info
                                                loaddatatable()
                                                list_all_docdesc()

                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].data
                                                })
                                          }
                                          else if(data[0].status == 2){

                                                // select2_docdesc_error(data[0].data)
                                                select2_docdesc_error(
                                                      '.docdesc-form',
                                                      '#invalidDocDesc',
                                                      data[0].data
                                                )

                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].data
                                                }) 
                                          }
                                          else{

                                                // select2_docdesc_error(data[0].data)
                                                select2_docdesc_error(
                                                      '.docdesc-form',
                                                      '#invalidDocDesc',
                                                      data[0].data
                                                )

                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].data
                                                })
                                          }
                                    }
                              })
                        }
                  
                  }

                  function update_document(){

                        var isactive = 0;
                        var isrequied = 0;
                        var isvalid = true;

                        if($('#input_isrequired').prop('checked') == true){
                              isrequied = 1
                        }
                        if($('#input_isactive').prop('checked') == true){
                              isactive = 1
                        }

                        // var temp_document_id = all_document.filter(x=>x.id == selected_document && x.headerid == selected_docdescid)

                        // console.log(temp_document_id, selected_document, selected_docdescid, all_document)


                        // if(temp_document_id.length > 0){

                        //       // select2_docdesc_error('Document requirement already exist')
                        //       select2_docdesc_error(
                        //             '.docdesc-form',
                        //             '#invalidDocDesc',
                        //             'Document requirement already exist'
                        //       )

                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Document requirement already exist'
                        //       })

                        //       isvalid = false
                        // }
                        // else if($('#input_description').val() == ""){

                        //       // select2_docdesc_error('Document description is empty!')
                        //       select2_docdesc_error(
                        //             '.docdesc-form',
                        //             '#invalidDocDesc',
                        //             'Document description is empty!'
                        //       )
                              
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Document description is empty!'
                        //       })

                        //       isvalid = false
                        // }
                        // else if($('#input_sequence').val() == ""){

                        //       $('#input_sequence').addClass('is-invalid')
                        //       $('#invalidSeq').text('Document sequence is empty!')

                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Document sequence is empty!'
                        //       })
                        //       isvalid = false
                        // }
                        
                        if(isvalid){
                              $.ajax({
                                    type:'GET',
                                    url: '/superadmin/setup/document/update',
                                    data:{
                                          description:selected_docdesctext,
                                          sequence:$('#input_sequence').val(),
                                          isactive:isactive,
                                          isrequired:isrequied,
                                          documentid:selected_document,
                                          levelid:$('#filter_gradelevel').val(),
                                          studtype:$('#stud_type').val()
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].data
                                                })
                                                all_document = data[0].info

                                          
                                                $('#modal_document').modal('hide')
                                                loaddatatable()
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].data
                                                })
                                          }
                                    }
                              })
                        }
                  }

                  function delete_document(){
                        var temp_doc = all_document.filter(x=>x.id == selected_document)

                        Swal.fire({
                              title: `Do you want to remove ${temp_doc[0].description}?`,
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/setup/document/delete',
                                          data:{
                                                documentid:selected_document
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                      all_document = data[0].info
                                                      loaddatatable()
                                                      list_all_docdesc()
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].data
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  }

                  // docdesc
                  $(document).on('click','#button_document',function(){
                        process = 'create'

                        select2_docdesc_reset()

                        $('#input_description').prop("disabled", false);
                        $('#input_isrequired').prop('checked',false)
                        $('#input_isactive').prop('checked',true)
                        $('#edit_docdesc').attr('hidden','hidden')
                        $('#delete_docdesc').attr('hidden','hidden')

                        list_all_docdesc()

                        selected_docdescid = null
                        selected_docdesctext = null

                        // validateSelector('#input_sequence', (result) => {
                        //       if(!result) {
                        //             docdesc_isvalid = result
                        //             $('#invalidSeq').text('Document sequence is empty!')
                        //       }
                              
                        // })

                        $('#input_sequence').val("")
                        $('#input_acadprog').val("").change()
                        $('#create_document').text('Create')  
                        $('#modal_document').modal()    
                  })

                  $(document).on('click','#document-f-btn',function(){
                        // if($('#input_document').val() == ""){
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Document Description is empty'
                        //       })
                        //       return false;
                        // }

                        if(selected_docdescid == null) {
                              create_docdesc()
                        } else {
                              update_docdesc()
                        }
                  })

                  $(document).on('click','#edit_docdesc',function(){
                        // process = 'edit'
                        $('#input_document').val(selected_docdesctext)

                        $('#document-f-btn').removeClass('btn-primary')
                        $('#document-f-btn').addClass('btn-success')

                        $('#document-f-btn').text('Update')
                        $('#document_form_modal').modal()   
                        
                  })

                  $(document).on('click','#delete_docdesc',function(){
                        Swal.fire({
                              text: `Are you sure you want to remove ${selected_docdesctext}?`,
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#d33', // #3085d6
                              cancelButtonColor: '#808080', // #d33
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    delete_docdesc()
                              }
                        })
                  })

                  $(document).on('select2:clear','#input_description',function(){
                        $('#edit_docdesc').attr('hidden','hidden')
                        $('#delete_docdesc').attr('hidden','hidden')
                        selected_docdescid = null
                        selected_docdesctext = null
                  })

                  $(document).on('change','#input_description',function(){

                        select2_docdesc_reset()

                        if($(this).val() == "add"){

                              $('#edit_docdesc').attr('hidden','hidden')
                              $('#delete_docdesc').attr('hidden','hidden')

                              $('#input_document').removeClass('is-valid')
                              $('#input_document').removeClass('is-invalid')

                              $('#document-f-btn').text('Create')
                              $('#document-f-btn').removeClass('btn-success')
                              $('#document-f-btn').addClass('btn-primary')
                              $('#input_document').val("").change()
                              $('#document_form_modal').modal()
                              $('#input_description').val("").change()

                              selected_docdescid = null
                              selected_docdesctext = null
                        
                        }

                        else if($(this).val() != "") {

                              selected_docdescid = $(this).val()
                              selected_docdesctext =  all_docdesc.filter(x=>x.id == selected_docdescid)

                              if (selected_docdesctext.length != 0) {
                                    selected_docdesctext = selected_docdesctext[0].text
                              }
                              
                              // if (!selected_docdescid && !selected_docdesctext) {
                              //       selected_docdescid = $(this).val()
                              //       selected_docdesctext =  all_docdesc.filter(x=>x.id == selected_docdescid)[0].text
                              // }

                              $('#edit_docdesc').removeAttr('hidden')
                              $('#delete_docdesc').removeAttr('hidden')

                        }
                        // else {
                        //       $('#edit_docdesc').attr('hidden','hidden')
                        //       $('#delete_docdesc').attr('hidden','hidden')
                        // }

                  })

                  $(document).on('shown.bs.modal','#document_form_modal',function(){
                        docdesc_isvalid = true

                        validateSelector('#input_document', (result) => {
                              if(!result) {
                                    docdesc_isvalid = result
                                    $('#invalidDocu').text('Please provide a valid document description')
                              }
                              
                        })

                        if(!docdesc_isvalid) {

                              Toast.fire({
                                    type: 'warning',
                                    title: 'Document description is empty!'
                              })
                        }
                  })

                  function create_docdesc(){

                        isvalid = true

                        if($('#input_document').val() == ""){

                              $('#input_document').removeClass('is-valid')
                              $('#input_document').addClass('is-invalid')
                              $('#invalidDocu').text('Please provide a valid document description')

                              Toast.fire({
                                    type: 'warning',
                                    title: 'Document description is empty!'
                              })

                              isvalid = false

                        }

                        if(isvalid) {
                              $.ajax({
                                    type:'GET',
                                    url: '/superadmin/setup/docdesc/create',
                                    data:{
                                          description:$('#input_document').val()
                                    },
                                    success:function(data) {
                                          if(data[0].status == 2){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].message
                                                })
                                          }else if(data[0].status == 1){

                                                list_all_docdesc()

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
                              })
                        }

                  }

                  function update_docdesc(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/docdesc/update',
                              data:{
                                    docdescid: selected_docdescid,
                                    description: $('#input_document').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 2) {

                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })

                                    } else if(data[0].status == 1) {

                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          list_all_docdesc()

                                    } else {

                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })
                  }

                  function delete_docdesc(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/docdesc/delete',
                              data:{
                                    docdescid: selected_docdescid
                              },
                              success:function(data) {
                                    if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          list_all_docdesc().then(() => {
                                                $('#edit_docdesc').attr('hidden','hidden')
                                                $('#delete_docdesc').attr('hidden','hidden')
                                          })

                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })
                  }

                  function list_all_docdesc(prompt=false){
                        
                        return $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/docdesc/list',
                              success:function(data) {
                                    all_docdesc = data

                                    $('#input_description').empty()
                                    $('#input_description').append('<option value="">Select document</option>')
                                    $('#input_description').append('<option value="add">Add document</option>')
                                    $("#input_description").select2({
                                          data: data,
                                          allowClear: true,
                                          placeholder: "Select document",
                                    })

                                    if(prompt){
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length+' document description(s) found.'
                                          })
                                    }
                              }
                        })
                  }

                  function select2_docdesc_error(formSel, invalidSel, message) {
                        // error validation styling for #input_description
                        $(`${formSel}`).addClass('has-error')
                        $(`${invalidSel}`).addClass('d-block')
                        $(`${invalidSel}`).text(message)
                  }

                  function select2_docdesc_reset() {
                        // reset validation styling for #input_description
                        $('.docdesc-form').removeClass('has-error')
                        $('#invalidDocDesc').removeClass('d-block')
                        $('#invalidDocDesc').text('')
                  }

                  function validateSelector(selector, callback) {

                        function validateInput(input) {
                              if (!input.val().trim()) {
                                    // $("#building_create_button").prop("disabled", true);
                                    input.removeClass("is-valid").addClass("is-invalid");
                                    return false;
                              } else {
                                    // $("#building_create_button").prop("disabled", false);
                                    input.removeClass("is-invalid").addClass("is-valid");
                                    return true;
                              }
                        }

                        $(selector).on("input", () => {
                              var isValid = validateInput($(selector));
                              callback(isValid);
                        });
                  }

                  $(document).on('click','.copy_document',function(){

                        $("#to_gradelevel").val([]).change();
                        $('#apply_to_all').prop('checked',false)

                        selected_document = $(this).attr('data-id')
                        $('#copy_document').modal()
                        var current_gradelevel = $('#filter_gradelevel').val()
                        var temp_gradelevel = gradelevel.filter(x=>x.id != current_gradelevel)
                        var temp_document = all_document.filter(x=>x.id == selected_document)
                        $('#copy_description')[0].innerHTML = 'Copying <i class="text-success">'+temp_document[0].description+'</i>'
                        $("#to_gradelevel").select2({
                              data: temp_gradelevel,
                              placeholder: "Select gradelevel",
                              theme: 'bootstrap4'
                        })


                  })

                  $(document).on('click','#copy_all',function(){

                        $("#to_gradelevel").val([]).change();
                        $('#apply_to_all').prop('checked',false)

                        selected_document = null
                        $('#copy_document').modal()
                        var current_gradelevel = $('#filter_gradelevel').val()
                        var temp_gradelevel = gradelevel.filter(x=>x.id != current_gradelevel)
                        $("#to_gradelevel").select2({
                              data: temp_gradelevel,
                              placeholder: "Select gradelevel",
                              theme: 'bootstrap4'
                        })

                        var temp_gradelevel = gradelevel.filter(x=>x.id == current_gradelevel)
                        $('#copy_description')[0].innerHTML = 'Copying all requirements from <br><i class="text-success">'+temp_gradelevel[0].text+'</i>'
                  })

                  $(document).on('click','#button_to_copy',function(){
                        copy_document()
                  })
                  
                  $(document).on('click','#apply_to_all',function(){
                        var temp_gradelevel = []
                        var current_gradelevel = $('#filter_gradelevel').val()
                        if($(this).prop('checked') == true){
                              var acad_level = gradelevel.filter(x=>x.id != current_gradelevel)
                              $.each(acad_level,function(a,b){
                                    temp_gradelevel.push(b.id)
                              })
                              $("#to_gradelevel").val(temp_gradelevel).change()
                        }else{
                              $("#to_gradelevel").val(temp_gradelevel).change()
                        }
                  })

                  function copy_document(){
                        Swal.fire({
                              title: 'Do you want to copy document requirement?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Copy'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/setup/document/copy',
                                          data:{
                                                documentid:selected_document,
                                                gradelevel_from:$('#filter_gradelevel').val(),
                                                gradelevel_to:$('#to_gradelevel').val()
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].data
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  }

                  function loaddatatable(){
                        
                        $('#total_required').text(all_document.filter(x=>x.isRequired == 1).length)
                        $('#total_active').text(all_document.filter(x=>x.isActive == 1).length)

                        $("#document_setup").DataTable({
                                    destroy: true,
                                    autoWidth: false,
                                    pageLength: 50,
                                    paging: false,
                                    bInfo: false,
                                    data:all_document,
                                    
                                    columns: [
                                          { "data": "docsort"},
                                          { "data": "description" },
                                          { "data": "doc_studtype" },
                                          { "data": "isActive" },
                                          { "data": "isRequired" },
                                          { "data": null },
                                          { "data": null },
                                          { "data": null },
                                          
                                    ],

                                    columnDefs: [
                                                      {
                                                            'targets': 0,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  $(td).addClass('text-center')
                                                            }
                                                      },
                                                      {
                                                            'targets': 2,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  var doc_studtype = rowData.doc_studtype
                                                                  if(rowData.doc_studtype == null){
                                                                        doc_studtype = 'All'
                                                                  }
                                                                  $(td).text(doc_studtype)
                                                            }
                                                      },
                                                      {
                                                            'targets': 3,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.isActive == 1){
                                                                        $(td)[0]. innerHTML = '<i class="fas fa-check-square text-success"></i>'
                                                                  }else{
                                                                        $(td)[0]. innerHTML = '<i class="fas fa-times-circle text-danger"></i>'
                                                                  }
                                                                  $(td).addClass('text-center')
                                                            }
                                                      },
                                                      {
                                                            'targets': 4,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.isRequired == 1){
                                                                        $(td)[0]. innerHTML = '<i class="fas fa-check-square text-success"></i>'
                                                                  }else{
                                                                        $(td)[0]. innerHTML = '<i class="fas fa-times-circle  text-danger"></i>'
                                                                  }
                                                                  $(td).addClass('text-center')
                                                            }
                                                      },
                                                      {
                                                            'targets': 5,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  var buttons = '<a href="#" class="copy_document" data-id="'+rowData.id+'"><i class="fas fa-copy text-primary"></i></a>';
                                                                  $(td)[0].innerHTML =  buttons
                                                                  $(td).addClass('text-center')
                                                            }
                                                      },
                                                      
                                                      {
                                                            'targets': 6,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  var buttons = '<a href="#" class="edit_document" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                                  $(td)[0].innerHTML =  buttons
                                                                  $(td).addClass('text-center')
                                                                  
                                                            }
                                                      },
                                                      {
                                                            'targets': 7,
                                                            'orderable': true, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  var buttons = '<a href="#" class="delete_document" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                                  $(td)[0].innerHTML =  buttons
                                                                  $(td).addClass('text-center')
                                                            }
                                                      },
                                                ]
                        });

                        var label_text = $($('#document_setup_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '  <button class="btn btn-primary btn-sm mr-2" id="copy_all" disabled><i class="fas fa-copy" ></i> Copy</button><button class="btn btn-primary  btn-sm" id="button_document" disabled><i class="fas fa-plus"></i> Add Document Requirement</button>'

                        if($('#filter_gradelevel').val() != ""){
                              $('#copy_all').removeAttr('disabled','disabled')
                              $('#button_document').removeAttr('disabled','disabled')
                        }

                       
                  
                  }

                  var keysPressed = {};

                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/21/2021 03:53'
                                    })
                        }
                  });

                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });
            
            })
      </script>

@endsection


