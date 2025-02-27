
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
      .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
      }
      .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0;
      }
</style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->get(); 
@endphp

<div class="modal fade" id="strand_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Strand Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Strand Name</label>
                                    <input id="input_strandname" class="form-control form-control-sm">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Strand Code</label>
                                    <input id="input_strandcode" class="form-control form-control-sm">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Track</label>
                                    <select id="input_strandstrack" class="form-control form-control-sm"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="input_strandactive" >
                                          <label for="input_strandactive">Active</label>
                                    </div>
                              </div>
                        </div>
                     
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="strand_form_button">Create</button>
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
                  <h1>SHS Strand</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">SHS Strand</li>
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
                                    <div class="col-md-2 form-group mb-0" >
                                          <label for="">Semester</label>
                                          <select class="form-control select2" id="filter_semester">
                                                @foreach ($semester as $item)
                                                      <option {{$item->isactive == 1 ? 'checked' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
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
                  <div class="card shadow" style="">
                        <div class="card-body">
                             
                              <div class="row ">
                                    <div class="col-md-12"  style="font-size:14px">
                                          <table class="table-hover table table-striped table-sm table-bordered table-head-fixed  display " id="strand_datatable" width="100%" >
                                                <thead>
                                                      <tr>
                                                            <th width="11%" >Code</th>
                                                            <th width="42%" >Strand Name</th>
                                                            <th width="21%" >Track Name</th>
                                                            <th width="9%" class="text-center p-0 align-middle">Enrolled</th>
                                                            <th width="9%" class="text-center p-0 align-middle">Status</th>
                                                            <th width="4%"></th>
                                                            <th width="4%"></th>
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

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            $('.select2').select2()

            var strand_list = []
            var selected_id = null

            load_strand_datatable()
            get_strand_list()
            get_track_list()
          

            $(document).on('click','#button_to_modal_strand',function(){
                  $('#input_strandname').val("")
                  $('#input_strandcode').val("")
                  $('#input_strandstrack').val("").change()
                  $('#input_strandactive').prop('checked',false)
                  $('#strand_form_modal').modal()
                  $('#strand_form_button').removeClass('btn-success')
                  $('#strand_form_button').addClass('btn-primary')
                  $('#strand_form_button').text('Create')
                  $('#strand_form_button').attr('data-proccess','create')
            })

            $(document).on('click','.udpate_strand',function(){
                  selected_id = $(this).attr('data-id')
                  var temp_strand_info = strand_list.filter(x=>x.id == selected_id)
                  $('#input_strandname').val(temp_strand_info[0].strandname)
                  $('#input_strandcode').val(temp_strand_info[0].strandcode)
                  $('#input_strandstrack').val(temp_strand_info[0].trackid).change()

                  if(temp_strand_info[0].active == 1){
                        $('#input_strandactive').prop('checked',true)
                  }else{
                        $('#input_strandactive').prop('checked',false)
                  }

                  $('#strand_form_modal').modal()
                  $('#strand_form_button').removeClass('btn-primary')
                  $('#strand_form_button').addClass('btn-success')
                  $('#strand_form_button').text('Update')
                  $('#strand_form_button').attr('data-proccess','update')
            })

            $(document).on('click','.delete_strand',function(){
                  selected_id = $(this).attr('data-id')
                  Swal.fire({
                        title: 'Do you want to remove strand?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                  }).then((result) => {
                        if (result.value) {
                              delete_strand()
                        }
                  })
            })

            $(document).on('click','#strand_form_button',function(){
                  if($(this).attr('data-proccess') == 'update'){
                        udpate_strand()
                  }else if($(this).attr('data-proccess') == 'create'){
                        create_strand()
                  }
            })

            $(document).on('change','#filter_sy',function(){
                  get_strand_list()
            })

            $(document).on('change','#filter_semester',function(){
                  get_strand_list()
            })


            function get_track_list(){
                  $.ajax({
                        type:'GET',
                        url:'/setup/track/list',
                        success:function(data) {
                              if(data.length == 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'No Track found.'
                                    })
                              }else{
                                    $("#input_strandstrack").select2({
                                          data: data,
                                          allowClear: true,
                                          placeholder: "Select Track",
                                    })
                              }
                        }
                  })
            }

            function get_strand_list(){
                  var syid = $('#filter_sy').val()
                  var semid = $('#filter_semester').val()
                  $.ajax({
                        type:'GET',
                        url:'/setup/strand/list',
                        data:{
                              syid:syid,
                              semid:semid,
                              withEnrollmentCount:true
                        },
                        success:function(data) {
                              if(data.length == 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'No strand found.'
                                    })
                              }else{
                                    strand_list = data
                                    load_strand_datatable()
                              }
                        }
                  })
            }

            function create_strand(){

                  var strandname = $('#input_strandname').val()
                  var strandcode = $('#input_strandcode').val()
                  var trackid = $('#input_strandstrack').val()
                  var active = $('#input_strandactive').val()

                  if(strandname == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "Strand Name is empty."
                        })
                        return false
                  }

                  if(strandname == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "Strand Code is empty."
                        })
                        return false
                  }

                  if(trackid == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "Track is empty."
                        })
                        return false
                  }
                  
                  if($('#input_strandactive').prop('checked') == true){
                        active = 1
                  }else{
                        active = 0
                  }

                  $.ajax({
                        type:'GET',
                        url:'/setup/strand/create',
                        data:{
                              strandname:strandname,
                              strandcode:strandcode,
                              trackid:trackid,
                              active:active,
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].message
                                    })
                              }else{
                                    get_strand_list()
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }
                        }
                  })
            }

            function udpate_strand(){

                  var id = selected_id
                  var strandname = $('#input_strandname').val()
                  var strandcode = $('#input_strandcode').val()
                  var trackid = $('#input_strandstrack').val()

                  if(strandname == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "Strand Name is empty."
                        })
                        return false
                  }

                  if(strandname == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "Strand Code is empty."
                        })
                        return false
                  }

                  if(trackid == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "Track is empty."
                        })
                        return false
                  }
                  
                  if($('#input_strandactive').prop('checked') == true){
                        active = 1
                  }else{
                        active = 0
                  }

                  $.ajax({
                        type:'GET',
                        url:'/setup/strand/update',
                        data:{
                              id:id,
                              strandname:strandname,
                              strandcode:strandcode,
                              trackid:trackid,
                              active:active,
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].message
                                    })
                              }else{
                                    get_strand_list()
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }
                        },error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong.'
                              })
                        }
                  })
            }

            function delete_strand(){
                  var id = selected_id
                  $.ajax({
                        type:'GET',
                        url:'/setup/strand/delete',
                        data:{
                              id:id
                        },
                        success:function(data) {
                              if(data[0].status == 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].message
                                    })
                              }else{
                                    get_strand_list()
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }
                        },error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong.'
                              })
                        }
                  })
            }

            function load_strand_datatable(){

                  $("#strand_datatable").DataTable({
                        destroy: true,
                        data:strand_list,
                        lengthChange : false,
                        columns: [
                                    { "data": "strandcode" },
                                    { "data": "strandname" },
                                    { "data": "trackname" },
                                    { "data": "enrolled" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML =  rowData.trackname.replace(' Track','')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var button = '<span class="badge badge-danger">Not Active</span>'
                                          if(rowData.active == 1){
                                                button = '<span class="badge badge-success">Active</span>'
                                          }
                                          $(td)[0].innerHTML =  button
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                          
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var buttons = '<a href="javascript:void(0)" class="udpate_strand" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                          
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var disabled = '';
                                          var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_strand" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                        ]
                        
                  });


                  var label_text = $($("#strand_datatable_wrapper")[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="button_to_modal_strand">Create strand</button>'
                 
            
            }

      })
</script>

{{-- IU --}}
<script>

      $(document).ready(function(){

            var keysPressed = {};

            document.addEventListener('keydown', (event) => {
                  keysPressed[event.key] = true;
                  if (keysPressed['p'] && event.key == 'v') {
                        Toast.fire({
                                    type: 'warning',
                                    title: 'Date Version: 07/26/2021 16:34'
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

            $(document).on('input','#per',function(){
                  if($(this).val() > 100){
                        $(this).val(100)
                        Toast.fire({
                              type: 'warning',
                              title: 'Subject percentage exceeds 100!'
                        })
                  }
            })
      })
</script>

@endsection


