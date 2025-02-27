@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 2){
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
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
      </style>
@endsection


@section('content')

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="header">Header Group</label>
                                    <textarea id="headergroup" readonly="readonly" class="form-control form-control-sm"></textarea>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="header" >
                                        <label for="header">Header
                                        </label>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control form-control-sm" id="description" rows="3"></textarea>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <button class="btn btn-primary btn-sm" id="create_button_1"><i class="fas fa-copy"></i> CREATE</button>
                        <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="studinfo_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12  form-group">
                                    <label for="">Info</label>
                                    <select class="form-control select2" name="" id="input_studinfo">
                                          <option value="Student Name">Student Name</option>
                                          <option value="Date of Birth">Date of Birth</option>
                                          <option value="Gender">Gender</option>
                                          <option value="School Year">School Year</option>
                                          <option value="Birth Order">Birth Order</option>
                                          <option value="Child’s Number of Siblings">Child’s Number of Siblings</option>

                                          <option value="Child’s Handedness (Right)">Child’s Handedness (Right)</option>
                                          <option value="Child’s Handedness (Left)">Child’s Handedness (Left)</option>
                                          <option value="Child’s Handedness (Bot">Child’s Handedness (Both)</option>
                                          <option value="Child’s Handedness (not yet established)">Child’s Handedness (not yet established)</option>

                                          <option value="Father’s Name">Father’s Name</option>
                                          <option value="Father’s Age">Father’s Age</option>
                                          <option value="Father’s Occupation">Father’s Occupation</option>
                                          <option value="Father’s Educational Attainment">Father’s Educational Attainment</option>

                                          <option value="Mother’s Na">Mother’s Name</option>
                                          <option value="Mother’s Age">Mother’s Age</option>
                                          <option value="Mother’s Occupation">Mother’s Occupation</option>
                                          <option value="Mother’s Educational Attainment">Mother’s Educational Attainment</option>


                                          <option value="Adviser">Adviser</option>
                                          <option value="Principal">Principal</option>
                                          <option value="Address">Address</option>
                                    </select>
                              </div>
                             
                              <div class="col-md-12">
                                  <button class="btn btn-primary" id="create_button_2">Add</button>
                              </div>
                        </div>
                  </div>
               
            </div>
      </div>
</div>   




<div class="modal fade" id="rating_value_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Rating Value</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="ratingvalue_datatable" width="100%" >
                                          <thead>
                                                <tr>
                                                      <th width="10%">Sort</th>
                                                      <th width="44%">Description</th>
                                                      <th width="34%">Value</th>
                                                      <th width="6%"></th>
                                                      <th width="6%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                       </div>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="add_setup_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Description</label>
                                    <input type="text" class="form-control" id="input_setup_description">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Type</label>
                                   <select name="" id="input_type" class="form-control select2">
                                          <option value="">Select Type</option>
                                          <option value="1">3 Term/Quarter Checklist</option>
                                          <option value="2">4 Term/Quarter Checklist</option>
                                          <option value="3">3 Term/Quarter Rating</option>
                                          <option value="4">4 Term/Quarter Rating</option>
                                          <option value="5">Student Information</option>
                                          <option value="7">Word Template</option>
                                   </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary" id="process_grading_system">Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="upload_ratingvalue_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-body">
                        <form 
                              action="/grade/preschool/upload/detail" 
                              id="upload_kinderdetail" 
                              method="POST" 
                              enctype="multipart/form-data"
                              >
                              @csrf
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Uplod Description</label>
                                          <input type="text" class="form-control" id="upload_setup_description" readonly>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">File</label>
                                          <input type="file" class="form-control" id="input_fileupload"  name="input_fileupload">
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <button class="btn btn-primary" id="detail_upload">Upload</button>
                                    </div>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="ratingvalue_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Rating Value Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input type="text" class="form-control" id="input_rv_sort">
                              </div>
                       </div>
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Description</label>
                                    <input type="text" class="form-control" id="input_rv_description">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Value</label>
                                    <input type="text" class="form-control" id="input_rv_value">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary" id="create_ratingvalue">Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="uploadtemplate_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Upload Template Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <form 
                              action="/grade/preschool/upload/template" 
                              id="upload_template" 
                              method="POST" 
                              enctype="multipart/form-data"
                              >
                              @csrf
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">File</label>
                                          <input type="file" class="form-control" id="input_fileupload_template"  name="input_fileupload_template">
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <button class="btn btn-primary">Upload</button>
                                    </div>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Pre-School Grading</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Pre School Grading</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
@php
    $sy = DB::table('sy')->get();
    $gradelevel = DB::table('gradelevel')
                  ->where('deleted',0)
                  ->where('acadprogid',2)
                  ->get();
                  

@endphp
    
<section class="content pt-0">
      <div class="container-fluid">
           <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" >
                              <div class="card-body">
                                   <div class="row">
                                        <div class="col-md-2  form-group  mb-0">
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
                                        <div class="col-md-2  form-group  mb-0">
                                              <label for="" class="mb-1">Grade Level</label>
                                              <select class="form-control select2 form-control-sm" id="filter_gradelevel">
                                                    @foreach ($gradelevel as $item)
                                                        <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                    @endforeach
                                              </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" >
                              <div class="card-body">
                                  <div class="row">
                                          <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm" id="add_setup">Add Setup</button>
                                                {{-- <button class="btn btn-primary btn-sm" id="upload_setup">Upload Template</button> --}}
                                          </div>
                                    </div>
                                      {{-- <div class="row mt-3">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%" class="align-middle text-center">Sort</th>
                                                                  <th width="77%" class="align-middle">Description  </th>
                                                                  <th width="4%"></th>
                                                                  <th width="4%"></th>
                                                                  <th width="10%" class="text-center"><button class="btn btn-primary btn-sm" id="button_to_modal_1"><i class="fas fa-plus"></i> Add Item</button></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="data">

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div> --}}
                                    <div class="row mt-3" id="setup_holder">
                                         
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
            var all_ratingvalue = []
            var rv_process = null;
            var selected_rv_header = null
            var selected_rv_id = null

            $(document).on('click','#upload_setup',function(){
                  $('#uploadtemplate_form_modal').modal()
            })

            $( '#upload_template' )
              .submit( function( e ) {

                  if($('#input_fileupload_template').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title: 'No File'
                        })
                        return false;
                  }

                 

                  var inputs = new FormData(this)
                  inputs.append('headerid',dataheaderid)
                  inputs.append('syid',$('#filter_sy').val())
                  inputs.append('levelid',$('#filter_gradelevel').val())
                  
                  $.ajax({
                        xhr: function() {
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                              if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.progress-bar').width(percentComplete+'%');
                                    $('.progress-bar').html(percentComplete+'%');
                                    console.log(percentComplete)
                                    }
                              }, false);
                              return xhr;
                        },
                        url: '/grade/preschool/upload/template',
                        type: 'POST',
                        data: inputs,
                        processData: false,
                        contentType: false,
                        headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })

                                  

                                    get_preschool_setup(dataheaderid)
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                        }
                      
                        ,error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                              })
                        }
                  })
                  e.preventDefault();
            })

            
            $( '#upload_kinderdetail' )
              .submit( function( e ) {

                  if($('#input_sinput_fileuploadf1').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title: 'No File'
                        })
                        return false;
                  }

                  $('#detail_upload').attr('disabled','disabled')
                  $('#detail_upload').text('Uploading...')
                  $('#input_fileupload').attr('readonly','readonly')

                  var inputs = new FormData(this)
                  inputs.append('headerid',dataheaderid)
                  
                  $.ajax({
                        xhr: function() {
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                              if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.progress-bar').width(percentComplete+'%');
                                    $('.progress-bar').html(percentComplete+'%');
                                    }
                              }, false);
                              return xhr;
                        },
                        url: '/grade/preschool/upload/detail',
                        type: 'POST',
                        data: inputs,
                        processData: false,
                        contentType: false,
                        headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })

                                    $('#detail_upload').removeAttr('disabled')
                                    $('#input_fileupload').removeAttr('readonly')
                                    $('#detail_upload').text('Upload')
                                    
                                    get_preschool_setup(dataheaderid)
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                        }
                      
                        ,error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                              })
                        }
                  })
                  e.preventDefault();
            })

            function ratingvalue_list(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/ratingvalue/list',
                        data:{
                              headerid:selected_rv_header
                        },
                        success:function(data) {
                              all_ratingvalue = data
                              ratingvalue_datatable(data)
                        }
                  })
            }

            function ratingvalue_create(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/ratingvalue/create',
                        data:{
                              sort:$('#input_rv_sort').val(),
                              description:$('#input_rv_description').val(),
                              value:$('#input_rv_value').val(),
                              headerid:selected_rv_header,
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    ratingvalue_list()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                              $('#create_ratingvalue').removeAttr('disabled')
                        }
                  })
            }

            function ratingvalue_update(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/ratingvalue/update',
                        data:{
                              sort:$('#input_rv_sort').val(),
                              description:$('#input_rv_description').val(),
                              value:$('#input_rv_value').val(),
                              headerid:selected_rv_header,
                              id:selected_rv_id
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    ratingvalue_list()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                              $('#create_ratingvalue').removeAttr('disabled')
                        }
                  })
            }

            function ratingvalue_delete(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/ratingvalue/delete',
                        data:{
                              id:selected_rv_id,
                              headerid:selected_rv_header,
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    ratingvalue_list()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                             
                        }
                  })
            }

            function ratingvalue_datatable(data){

                  $("#ratingvalue_datatable").DataTable({
                        destroy: true,
                        autoWidth: false,
                        data:data,
                        columns: [
                                    { "data": 'sort' },
                                    { "data": "description" },
                                    { "data": "value" },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                          var buttons = '<a href="#" class="update_ratingvalue" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                          var buttons = '<a href="#" class="delete_ratingvalue" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                    }
                              }
                        ]
                  });

                  var label_text = $($('#ratingvalue_datatable_wrapper')[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<button id="create_ratingvalue_tomodal" class="btn btn-sm btn-primary">Create Rating Value</button>'
            }

            $(document).on('click','.rating_value_to_modal',function(){
                  $('#rating_value_modal').modal()
                  selected_rv_header = $(this).attr('data-id')
                  ratingvalue_list()
            })

            $(document).on('click','.update_ratingvalue',function(){
                  rv_process = 'update'
                  selected_rv_id = $(this).attr('data-id')
                  rv_detail = all_ratingvalue.filter(x=>x.id == selected_rv_id)

                  $('#input_rv_sort').val(rv_detail[0].sort)
                  $('#input_rv_description').val(rv_detail[0].description)
                  $('#input_rv_value').val(rv_detail[0].value)
                  $('#create_ratingvalue').text('Update')
                  $('#create_ratingvalue').addClass('btn-success')
                  $('#create_ratingvalue').removeClass('btn-primary')
                  $('#ratingvalue_form_modal').modal()
            })

            $(document).on('click','.delete_ratingvalue',function(){
                  selected_rv_id = $(this).attr('data-id')
                  Swal.fire({
                        title: 'Do you want to remove rating value?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                  }).then((result) => {
                        if (result.value) {
                              ratingvalue_delete()
                        }
                  })
            })

            $(document).on('click','#create_ratingvalue',function(){
                  $('#create_ratingvalue').attr('disabled','disabled')
                  rv_process == 'create' ? ratingvalue_create() : ratingvalue_update()
            })

            $(document).on('click','#create_ratingvalue_tomodal',function(){
                  $('#ratingvalue_form_modal').modal()
                  rv_process = 'create'
                  $('#create_ratingvalue').text('Create')
                  $('#create_ratingvalue').removeClass('btn-success')
                  $('#create_ratingvalue').addClass('btn-primary')
                  $('#input_rv_sort').val("")
                  $('#input_rv_description').val("")
                  $('#input_rv_value').val("")
            })

            $(document).on('click','#upload_ratingvalue_tomodal',function(){

                  dataid = $(this).attr('data-id')
                  dataheaderid = dataid
                  var tempsetup = lvl_gradesetup_list.filter(x=>x.id == dataid)
                  $('#upload_setup_description').val(tempsetup[0].description) 

                  $('#upload_ratingvalue_modal').modal()
                
            })

      </script>
      
      <script>

            var grading_setup_process = null
            var setupid = null
            var lvl_gradesetup_list = []

            $('.select2').select2()

            $(document).on('click','#add_setup',function () {
                  grading_setup_process = 'create'
                  setupid = null

                  $('#input_setup_description').val("")
                  $('#input_type').val("").change()

                  $('process_grading_system').text('Create')
                  $('process_grading_system').removeClass('btn-success')
                  $('process_grading_system').addClass('btn-primary')
                  $('#add_setup_modal').modal()
            })

            $(document).on('click','.edit_setup',function () {
                  grading_setup_process = 'update'

                  setupid = $(this).attr('data-id')
                  var tempinfo = lvl_gradesetup_list.filter(x=>x.id == setupid)

                  $('#input_setup_description').val(tempinfo[0].description)
                  $('#input_type').val(tempinfo[0].type).change()

                  $('#process_grading_system').text('Update')
                  $('#process_grading_system').addClass('btn-success')
                  $('#process_grading_system').removeClass('btn-primary')
                  $('#add_setup_modal').modal()
            })

            $(document).on('click','.delete_setup',function () {
                  setupid = $(this).attr('data-id')
                  grading_setup_delete()
            })

            $(document).on('click','#process_grading_system',function(){
                  if(grading_setup_process == 'create'){
                        grading_setup_create()
                  }else if(grading_setup_process == 'update'){
                        grading_setup_edit()
                  }
            })

            
            var grading_system_detail = [];

            $(document).on('click','.download_detail',function(){
                  headerid = $(this).attr('data-id')
                  var syid =$('#filter_sy').val()
                  var levelid = $('#filter_gradelevel').val()
                  window.open("/grade/preschool/download/detail?headerid="+headerid+'&syid='+syid+'&levelid='+levelid);
            })

            function grading_setup_list(){
                  grading_system_detail = []
                  $.ajax({
                        type:'GET',
                        url: '/preschool/gradingsystem/list',
                        data:{
                              syid:$('#filter_sy').val(),
                              levelid:$('#filter_gradelevel').val()
                        },
                        success:function(data) {

                              $('#setup_holder').empty()
                              lvl_gradesetup_list = data

                              $.each(data,function(a,b){

                                    var type = ''
                                    var uploadbutton = '<button  data-id="'+b.id+'" class="download_detail btn btn-sm btn-primary float-right  ml-2" style="font-size:.7rem !important">Download Detail</button><button  data-id="'+b.id+'" id="upload_ratingvalue_tomodal" class="btn btn-sm btn-primary float-right  ml-2" style="font-size:.7rem !important">Upload Detail</button>'

                                    var actionButton = '<th class="text-center" colspan="2"><a hidden href="javascript:void(0)" class="edit_setup mr-3" data-id="'+b.id+'"><i class="fas fa-edit text-primary"></i></a><a  href="javascript:void(0)" class="delete_setup text-danger" data-id="'+b.id+'"><i class="far fa-trash-alt"></i></a></th>'

                                    // var actionButton = '<th></th><th></th>'

                                    if(b.type == 5){
                                          var headerrow = `<tr>
                                                            <th width="5%" class="align-middle text-center">Sort</th>
                                                            <th width="61%" class="align-middle" colspan="5">
                                                                  Description 
                                                            </th>
                                                            <th width="4%"></th>
                                                            <th width="4%"></th>
                                                      </tr>`

                                         
                                    }else{
                                          var headerrow = `<tr>
                                                            <th width="5%" class="align-middle text-center">Sort</th>
                                                            <th width="61%" class="align-middle" colspan="5">
                                                                  Description
                                                            </th>
                                                            <th width="4%"></th>
                                                            <th width="4%"></th>
                                                      </tr>`
                                    }

                                    

                                    if(b.type == 1){
                                          type = '( 3 Term/Quarter checklist )'+uploadbutton
                                    }else if(b.type == 2){
                                          type = '( 4 Term/Quarter checklist )'+uploadbutton
                                    }else if(b.type == 3){
                                          type = '( 3 Term/Quarter Rating )'+uploadbutton+'<button class="btn-sm btn btn-primary float-right rating_value_to_modal" data-id="'+b.id+'" style="font-size:.7rem !important">Rating Value</button>'
                                    }else if(b.type == 4){
                                          type = '( 4 Term/Quarter Rating ) '+uploadbutton+'<button class="btn-sm btn btn-primary float-right rating_value_to_modal"  data-id="'+b.id+'" style="font-size:.7rem !important">Rating Value</button>'
                                    }else if(b.type == 7){
                                          type = '<button class="btn btn-primary btn-sm float-right" id="upload_setup" style="font-size:.7rem !important">Upload Template</button>'
                                    }else if(b.type == 5){
                                          type = '( Student Information ) <button class="btn btn-primary btn-sm float-right" id="button_to_modal_1" data-headerid="'+b.id+'" style="font-size:.7rem !important" ><i class="fas fa-plus"></i> Add Item</button>'
                                    }


                                    if(b.type == 7){
                                          actionButton = '<th></th><th></th>';
                                          headerrow = '';
                                    }
                                    

                                    var text = `<div class="col-md-12  table-responsive mb-4" style="height: 422px;" data-id="`+b.id+`">
                                                      <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%"  >
                                                            <thead>
                                                                  <tr>
                                                                        <th colspan="6" class="align-middle">`+b.description+` `+type +`</th>
                                                                       `+actionButton+`
                                                                  </tr>`+
                                                                  headerrow+`
                                                            </thead>
                                                            <tbody id="data" data-id="`+b.id+`">

                                                            </tbody>
                                                      </table>
                                                </div>`

                                    $('#setup_holder').append(text)
                                    get_preschool_setup(b.id)
                              })

                              
                        }
                  })
            }

            // grading setup
            function grading_setup_create(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/gradingsystem/create',
                        data:{
                              syid:$('#filter_sy').val(),
                              levelid:$('#filter_gradelevel').val(),
                              type:$('#input_type').val(),
                              description:$('#input_setup_description').val()
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    grading_setup_list()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                        }
                  })
            }

            function grading_setup_edit(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/gradingsystem/update',
                        data:{
                              syid:$('#filter_sy').val(),
                              levelid:$('#filter_gradelevel').val(),
                              type:$('#input_type').val(),
                              description:$('#input_setup_description').val(),
                              id:setupid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })

                                    grading_setup_list()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title:data[0].message
                                    })
                              }
                        }
                  })
            }

            function grading_setup_delete(){
                  $.ajax({
                        type:'GET',
                        url: '/preschool/gradingsystem/delete',
                        data:{
                              id:setupid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                                    grading_setup_list()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }
                        }
                  })
            }
      </script>

      <script>

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

     

                  

                  $('.select2').select2()
                  var dataid = null
                  var actoion = null
                  var dataheaderid = null;

                  grading_setup_list()

                 
                  $(document).on('click','#button_to_modal_1',function () {
                        dataid = ""
                        action = "create"
                        dataheaderid = $(this).attr('data-headerid')

                        var tempinfo = lvl_gradesetup_list.filter(x=>x.id == dataheaderid)

                        $('#headergroup').val(tempinfo[0].description  ) 
                        $('#create_button_1')[0].innerHTML = '<i class="fas fa-plus"></i> Create'
                        $('#create_button_1').addClass('btn-primary')
                        $('#create_button_1').removeClass('btn-success')
                        $('#description').val("")
                        $('#header').prop('checked',false)

                        if(tempinfo[0].type == 5){
                              $('#studinfo_modal').modal('show')
                        }else{
                              $('#modal_1').modal('show')
                        }

                        
                        
                  })

                  $(document).on('click','.create_item_1',function () {
                        action = "create"
                        action = "create"
                        $('#create_button_1')[0].innerHTML = '<i class="fas fa-plus"></i> Create'
                        $('#create_button_1').addClass('btn-primary')
                        $('#create_button_1').removeClass('btn-success')
                        $('#description').val("")
                        $('#header').prop('checked',false)
                        dataid = $(this).attr('data-id')
                        dataheaderid = $(this).attr('data-headerid')
                        var tempsetup = grading_system_detail.filter(x=>x.id == dataid)
                        $('#headergroup').val(tempsetup[0].description) 

                        $('#modal_1').modal('show')
                  })

                  $(document).on('click','#create_button_1 , #create_button_2',function () {
                        action == "create" ? create_1() : udpate_1()
                  })

                  function udpate_1(){
                        type = $('#header').prop('checked') ? "header" : ""
                        $.ajax({
                              type:'GET',
                              url: '/grade/preschool/setup/update',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    dataid:dataid,
                                    dataheaderid:dataheaderid,
                                    description:$('#description').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })
                                          get_preschool_setup(dataheaderid)
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              }
                        })
                  }

                  $(document).on('click','.group_option',function () {
                        $('#group').val($(this).text())
                  })


                  $(document).on('click','.edit',function () {
                        action = "update"
                        $('#create_button_1')[0].innerHTML = '<i class="fas fa-edit"></i> Update'
                        $('#create_button_1').removeClass('btn-primary')
                        $('#create_button_1').addClass('btn-success')

                        dataid = $(this).attr('data-id')
                        var temp_data = grading_system_detail.filter(x=>x.id == dataid)
                        if(temp_data[0].value == 0){
                              $('#header').prop('checked','checked')
                        }else{
                              $('#header').prop('checked',false)
                        }

                        var tempsetup = grading_system_detail.filter(x=>x.sort == temp_data[0].group)
                        
                        if(tempsetup.length > 0){
                              $('#headergroup').val(tempsetup[0].description) 
                        }else{
                              $('#headergroup').val("") 
                        }
                      
                        dataheaderid = temp_data[0].headerid
                        $('#description').val(temp_data[0].description)
                        $('#modal_1').modal('show')
                  })


                  $(document).on('click','.delete',function () {
                        var temp_dataid = $(this).attr('data-id')
                        var temp_data = grading_system_detail.filter(x=>x.id == temp_dataid)
                        dataheaderid = temp_data[0].headerid
                        $.ajax({
					type:'GET',
					url: '/grade/preschool/setup/delete',
                              data:{
                                    dataid:temp_dataid,
                                    dataheaderid:dataheaderid,
                                    syid:$('#filter_sy').val(),
                                    levelid:$('#filter_gradelevel').val(),
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Deleted Successfully!'
                                          })
                                          get_preschool_setup(dataheaderid)
                                    }
                                    else if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'info',
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

                  })

                 
                  
                   $(document).on('change','#filter_sy',function () {
                        // get_preschool_setup()
                        grading_setup_list()
                   })
                   
                    $(document).on('change','#filter_gradelevel',function () {
                        // get_preschool_setup()
                        grading_setup_list()
                    })
                   
                   
                  var all_setup = []


                  function get_preschool_setup(headerid){
                        $.ajax({
        					type:'GET',
        					url: '/grade/preschool/setup/list',
                                      data:{
                                            syid:$('#filter_sy').val(),
                                            levelid:$('#filter_gradelevel').val(),
                                            dataheaderid:headerid
                                      },
        					success:function(data) {
                                            plot_setup(data[0].detail,headerid, data[0].cellvalue)
        					}
        				})
                  }

                  function plot_setup(data,headerid,cellvalue) {
                        all_setup = data
                        $('#data[data-id="'+headerid+'"]').empty()
                        var tempheaderinfo = lvl_gradesetup_list.filter(x=>x.id == headerid)
                        $.each(data,function(a,b){
                              grading_system_detail.push(b)
                              var padding = ""
                              var header = ""
                              var button = ""
                              if(b.value == 0){
                                    header = 'font-weight: bold;'
                                   
                                    button = '<button class="create_item_1 btn btn-sm btn-primary float-right" data-id="'+b.id+'" data-headerid="'+headerid+'" style="font-size:.7rem !important"><i class="fas fa-plus"></i> Add Item</button>'

                                    button = ''

                                    if(b.sort.length > 1){
                                          padding = "padding-left:"+(b.group.length*2)+"rem;"
                                    }
                                    
                              }else{
                                    padding = "padding-left:"+(b.group.length*2)+"rem;"
                              }

                              var checkcellvalue = cellvalue.filter(x=>x.gsdid == b.id)
                              var celltd = `<td colspan="4" width=" class="text-center align-middle">`+button+`</td>`
                              if(checkcellvalue.length > 0){
                                    if(tempheaderinfo[0].type == 1 || tempheaderinfo[0].type == 3){
                                          celltd = `<td width="4%" class="text-center align-middle">`+checkcellvalue[0].q1cellval +`</td>
                                                <td width="4%" class="text-center align-middle">`+checkcellvalue[0].q2cellval+`</td>
                                                <td width="4%" class="text-center align-middle">`+checkcellvalue[0].q3cellval +`</td>
                                                <td width="4%" class="text-center align-middle"></td>`
                                    }else if(tempheaderinfo[0].type == 2 || tempheaderinfo[0].type == 4){
                                          celltd = `<td width="4%" class="text-center align-middle">`+checkcellvalue[0].q1cellval +`</td>
                                                <td width="4%" class="text-center align-middle">`+checkcellvalue[0].q2cellval+`</td>
                                                <td width="4%" class="text-center align-middle">`+checkcellvalue[0].q3cellval +`</td>
                                                <td width="4%" class="text-center align-middle">`+checkcellvalue[0].q4cellval +`</td>`
                                    }else if(tempheaderinfo[0].type == 5){
                                          celltd = `<td width="4%" class="text-center align-middle">`+checkcellvalue[0].q1cellval +`</td>
                                                <td width="4%" class="text-center align-middle"></td>
                                                <td width="4%" class="text-center align-middle"></td>
                                                <td width="4%" class="text-center align-middle"></td>`
                                    }
                              }

                          
                              if(tempheaderinfo[0].type == 7){
                                    $('#data[data-id="'+headerid+'"]').append(`<tr style="`+header+`"><td  colspan="8">`+b.description+`</td></tr>`)
                              }else{
                                    $('#data[data-id="'+headerid+'"]').append(`<tr style="`+header+`"><td class="align-middle text-center">`+b.sort+`</td>
                                    <td class="align-middle" style="`+padding+header+`">`+b.description+`</td>
                                    `+celltd+`
                                    <td class="align-middle text-center"><a hidden href="javascript:void(0)" class="edit" data-id="`+b.id+`"><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"><a   href="javascript:void(0)" class="delete text-danger" data-id="`+b.id+`"><i class="far fa-trash-alt"></i></a></td></tr><`)
                              }
                             
                        })

                        if(tempheaderinfo[0].type == 7){
                              if(data.length == 0){
                                    $('#data[data-id="'+headerid+'"]').append(`<tr style="`+header+`"><td  colspan="8">No template uploaded.</td></tr>`)
                              }
                        }

                        if(tempheaderinfo[0].type == 7){
                              $('.table-responsive[data-id="'+headerid+'"]').removeAttr('style')
                              $('.table-responsive[data-id="'+headerid+'"]').removeClass('table-responsive')
                   
                        }
                  }


                  function create_1(){
                        type = $('#header').prop('checked') ? "header" : ""

                        var tempinfo = lvl_gradesetup_list.filter(x=>x.id == dataheaderid)

                        if(tempinfo[0].type == 5){
                              var decription = $('#input_studinfo').val()
                        }else{
                              var decription = $('#description').val()
                        }

                        $.ajax({
					type:'GET',
					url: '/grade/preschool/setup/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    type:type,
                                    dataid:dataid,
                                    decription:decription,
                                    value:$('#value').val(),
                                    sort:$('#sort').val(),
                                    dataheaderid:dataheaderid
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Created Successfully!'
                                          })
                                          get_preschool_setup(dataheaderid)
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
					}
				})
                  }
                


      </script>

      <script>
            $(document).ready(function(){
                  var keysPressed = {};
                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/28/2021 14:34'
                                    })
                        }
                  });
                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });
            })
      </script>


@endsection


