
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
      <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
      <style>
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->get(); 

      // if(auth()->user()->type == 2){
      //       $acad = array();
            
      //       $teacherid = DB::table('teacher')
      //                         ->where('deleted',0)
      //                         ->where('tid',auth()->user()->email)
      //                         ->first();

      //       $temp_acad = DB::table('academicprogram')
      //                   ->where('principalid',$teacherid->id)
      //                   ->select('id')
      //                   ->get();

      //       foreach($temp_acad as $item){
      //             array_push($acad,$item->id);
      //       }

      //       $gradelevel = DB::table('gradelevel')
      //                   ->where('deleted',0)
      //                   ->whereIn('acadprogid',$acad)
      //                   ->where('gradelevel.acadprogid','!=',6)
      //                   ->orderBy('sortid')
      //                   ->select(
      //                         'gradelevel.levelname as text',
      //                         'gradelevel.levelname',
      //                         'gradelevel.id',
      //                         'acadprogid'
      //                   )
      //                   ->get(); 

      // }else{

            
      //       $teacherid = DB::table('teacher')
      //                               ->where('tid',auth()->user()->email)
      //                               ->select('id')
      //                               ->first()
      //                               ->id;

            
      //       $acadprog = DB::table('teacheracadprog')
      //                               ->where('teacherid',$teacherid)
      //                               ->where('acadprogutype',Session::get('currentPortal'))
      //                               ->where('deleted',0)
      //                               ->select('acadprogid as id')
      //                               ->distinct('acadprogid')
      //                               ->get();

      //       $gradelevel = DB::table('gradelevel')
      //                   ->where('deleted',0)
      //                   ->where('gradelevel.acadprogid','!=',6)
      //                   ->whereIn('gradelevel.acadprogid',collect($acadprog)->pluck('id'))
      //                   ->orderBy('sortid')
      //                   ->select(
      //                         'gradelevel.levelname as text',
      //                         'gradelevel.levelname',
      //                         'gradelevel.id',
      //                         'acadprogid'
      //                   )
      //                   ->get(); 
      // }
//    $gradelevel = DB::table('gradelevel')->where('deleted',0)
//                   ->orderBy('sortid')
//                   ->where('acadprogid','!=',6)
//                   ->select('id','levelname as text','levelname','acadprogid')
//                   ->get(); 
@endphp

<div class="modal fade" id="ratingvalue_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Rating</label>
                                    <input type="text" id="rv_input_value" class="form-control" autocomplete="off">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Non-Numerical Rating</label>
                                    <textarea type="text" id="rv_input_description" class="form-control" autocomplete="off"></textarea>
                              </div>
                        </div>
                      
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input type="text" id="rv_input_sort" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="rv_create_button">Create</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="create_observedvalues_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Core Values</label>
                                    <input type="text" id="input_group" class="form-control" autocomplete="off">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Available group</label>
                                    <div id="group_list"></div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Behaviour Statement</label>
                                    <textarea type="text" id="input_description" class="form-control" autocomplete="off" rows="4"></textarea>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input type="text" id="input_sort" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <label class="text-danger">Note: Please don't forget to also add the rating value to complete the setup.</label>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="create_button">Create</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="ob_copy_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="copy_to_gradelevel" >
                                        <label for="copy_to_gradelevel">Grade Level
                                        </label>
                                    </div>
                              </div>
                              <div class="col-md-6 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="copy_to_schoolyear" >
                                        <label for="copy_to_schoolyear">School Year
                                        </label>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Copy to school year</label>
                                    <select class="form-control select2" id="input_sy" disabled>
                                          <option value="" selected="selected">Select School Year</option>
                                          @foreach ($sy as $item)
                                                <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Copy to grade level</label>
                                    <select class="form-control select2" id="input_gradelevel" disabled>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm btn-block" id="miss_com_to_add" hidden>Add Missing Components</button>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="copy_to">COPY</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Observed Values</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Observed Values</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-4">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-5 mb-2">
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
                                                      <div class="col-md-7">
                                                            <label for="">Grade Level</label>
                                                            <select class="form-control select2 form-control-sm" id="filter_gradelevel">
                                                                  <option value="">Grade level</option>
                                                            </select>
                                                      </div>
                                                </div>
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
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h5 class="mb-0">Details 
                                                      <span class="float-right"> 
                                                            <button class="btn btn-primary btn-sm btn-warning " id="ob_copy_to" disabled="disabled"><i class="fas fa-copy"></i></button>
                                                            <button class="btn btn-success btn btn-sm" id="button_to_modal" disabled="disabled"><i class="fas fa-plus"></i></button>
                                                      </span>
                                                </h5>
                                               
                                          </div>
                                    
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display" id="observedvalues_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%">Sort</th>
                                                                  <th width="10%">Core Values</th>
                                                                  <th width="70%" class="pl-3">Behaviour Statement</th>
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
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" style="">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h5 class="mb-0">Rating Value
                                                      <span class="float-right"> 
                                                            <button class="btn btn-success btn btn-sm" id="rating_value" disabled="disabled"><i class="fas fa-plus"></i></button>
                                                      </span>

                                                </h5>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display" id="ratingvalue_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%">Sort</th>
                                                                  <th width="65%" class="pl-3">Rating</th>
                                                                  <th width="20%">Non-Numerical Rating</th>
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
      <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
      <script>

            $(document).ready(function(){

                  $('.select2').select2()

                  // $("#filter_gradelevel").select2({
                  //       data: gradelevel,
                  //       allowClear: true,
                  //       placeholder: "Grade Level",
                  // })
                  

                  $(document).on('click','#ob_copy_to',function() {
                        $('#input_sy').attr('disabled','disabled')
                        $('#input_gradelevel').attr('disabled','disabled')
                        
                        $('#copy_to_gradelevel').prop('checked',false)
                        $('#copy_to_schoolyear').prop('checked',false)
                       
                        $('#input_gradelevel').val("").change()
                        $('#input_sy').val("").change()

                        $('#ob_copy_modal').modal()
                  })

                  $(document).on('click','#copy_to_gradelevel',function(){
                        if($(this).prop('checked') == true){
                              $('#input_gradelevel').removeAttr('disabled','disabled')
                              $('#copy_to_schoolyear').prop('checked',false)
                              $('#input_sy').attr('disabled','disabled')
                              $('#input_sy').val("").change()
                        }
                       
                  })

                  $(document).on('click','#copy_to_schoolyear',function(){
                        if($(this).prop('checked') == true){
                              $('#input_sy').removeAttr('disabled','disabled')
                              $('#copy_to_gradelevel').prop('checked',false)
                              $('#input_gradelevel').attr('disabled','disabled')
                              $('#input_gradelevel').val("").change()
                        }
                  })

                  $(document).on('click','.select_group',function(){
                        $('#input_group').val($(this).attr('data-text'))
                  })

                  $(document).on('change','#filter_gradelevel',function(){

                        $('#button_to_modal').attr('disabled','disabled')
                        $('#ob_copy_to').attr('disabled','disabled')
                        $('#rating_value').attr('disabled','disabled')
                        var all_observedvalues = []
                        var all_ratingvalue = []
                        
                        var rv = $("#ratingvalue_table").DataTable({data:all_ratingvalue,destroy: true});
                        rv.draw()
                        rv.state.clear()
                        rv.destroy()

                        var ov = $("#observedvalues_table").DataTable({data:all_observedvalues,destroy: true})
                        ov.draw()
                        ov.state.clear();
                        ov.destroy();
                  })

            })

      </script>

           

      <script>

            var gradelevel = []

            $(document).ready(function(){

                 

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  toastr.options = {
                        "timeOut": "1000",
                  }

                  var all_observedvalues = []
                  process = 'create'
         
                  // observedvalues_datatable()

                  var selected = null
                  var action = ''

                  $(document).on('click','#button_to_modal',function(){

                        $('#input_sort').val(""),
                        $('#input_group').val(""),
                        $('#input_description').val(""),

                        action = 'create'
                        $('#create_button').text('Create')
                        $('#create_observedvalues_modal').modal()
                  })

                  $(document).on('click','#copy_to',function(){

                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/observed/values/copy',
                              data:{
                                    syid_to:$('#input_sy').val(),
                                    syid_from:$('#filter_sy').val(),
                                    gradelevel_to:$('#input_gradelevel').val(),
                                    gradelevel_from:$('#filter_gradelevel').val(),
                              },
                              success:function(data) {
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].data
                                    })
                              }
                        })

                  })

                  $(document).on('click','.edit',function(){
                        selected = $(this).attr('data-id')
                        var temp_data = all_observedvalues.filter(x=>x.id == selected)
                        $('#input_sort').val(temp_data[0].sort)
                        $('#input_group').val(temp_data[0].group)
                        $('#input_description').val(temp_data[0].description)
                        $('#create_observedvalues_modal').modal()
                        $('#create_button').text('Update')
                        action = 'update'
                  })

                  $(document).on('change','#filter_sy',function(){
                        all_observedvalues = []
                        observedvalues_datatable()
                  })

                  function update_observedvalues() {
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/observed/values/update',
                              data:{
                                    id:selected,
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val(),
                                    sort:$('#input_sort').val(),
                                    group:$('#input_group').val(),
                                    description:$('#input_description').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          all_observedvalues = data[0].info
                                          observedvalues_datatable()
                                          toastr.success('Updated')
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }


                  $(document).on('click','.delete',function(){
                        selected = $(this).attr('data-id')
                        Swal.fire({
                              title: 'Do you want to remove observed values?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    delete_observedvalues()
                              }
                        })
                  })

                  function delete_observedvalues(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/observed/values/delete',
                              data:{
                                    id:selected,
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val(),
                                    sort:$('#input_sort').val(),
                                    group:$('#input_group').val(),
                                    description:$('#input_description').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          all_observedvalues = data[0].info
                                          observedvalues_datatable()
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
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  $(document).on('click','#create_button',function(){
                        var valid = true;
                        if($('#input_description').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Description is required!'
                              })
                              valid = false;
                        }else if($('#input_group').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Group is required!'
                              })
                              valid = false;
                        }
                        else if($('#input_sort').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Sort is required!'
                              })
                              valid = false;
                        }

                        if(valid){
                              if(action == 'update'){
                                    update_observedvalues()
                              }else{
                                    create_observedvalues()
                              }
                             
                        }
                       
                  })

                  function create_observedvalues(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/observed/values/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val(),
                                    sort:$('#input_sort').val(),
                                    group:$('#input_group').val(),
                                    description:$('#input_description').val(),
                              },
					success:function(data) {
                                    if(data[0].status == 1){

                                          $('#input_group').val("")
                                          $('#input_description').val("")
                                          $('#input_sort').val("")

                                          all_observedvalues = data[0].info
                                          observedvalues_datatable()
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
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  }


                  
              

                  $(document).on('change','#filter_gradelevel',function(){

                        var valid = true

                        if($('#filter_gradelevel').val() == ""){
                              valid = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please select grade level'
                              })
                        }

                        if(valid){
                              $('#button_to_modal').removeAttr('disabled')
                              $('#ob_copy_to').removeAttr('disabled')
                              $('#rating_value').removeAttr('disabled')
                              get_observedvalues()
                        }else{
                              $('#button_to_modal').attr('disabled','disabled')
                              $('#ob_copy_to').attr('disabled','disabled')
                              $('#rating_value').attr('disabled','disabled')
                              all_observedvalues = []
                              observedvalues_datatable()
                        }

                  })

                  all_observedvalues = []
                  var table = observedvalues_datatable()
                  table.draw()
                  table.state.clear();

                  function get_observedvalues(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/observed/values/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val()
                              },
					success:function(data) {
                                    if(data.length > 0){
                                          toastr.info(data.length+' observed value(s) found!')
                                          
                                    }else{
                                          toastr.info('No observed value(s) found!')
                                    }

                                  
						all_observedvalues = data
                                    observedvalues_datatable()
					},
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
				})
                  }

                  function observedvalues_datatable(){

                        var temp_groups = []

                        if(all_observedvalues.length > 0){
                              $('#input_sort').attr('placeholder','Last sort '+all_observedvalues[all_observedvalues.length-1].sort)
                        }

                        $.each(all_observedvalues,function(a,b) {
                              temp_groups.push(b.group)    
                        })

                        var text = ''

                        $.each($.unique(temp_groups),function(a,b) {
                              text += '<button class="btn btn-sm btn-primary select_group mr-2 mt-2" data-text="'+b+'">'+b+'</button>'
                        })

                        $('#group_list')[0].innerHTML = text

                        var temp_table = $("#observedvalues_table").DataTable({
                              destroy: true,
                              data:all_observedvalues,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              stateSave: true,
                              columns: [
                                    { "data": "sort" },
                                    { "data": "group" },
                                    { "data": "description" },
                                    { "data": null },
                                    { "data": null }
                              ], 
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="edit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        });

                        return temp_table

                  }
                        
                  $(document).on('click','.button_to_gradesetup_modal',function(){
                        subjid = $(this).attr('data-id')
                        var subj_info = all_subject.filter(x=>x.id == subjid)
                     
                        $('#subject_holder').text(subj_info[0].text)
                  })

            })
      </script>


      <script>
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  toastr.options = {
                        "timeOut": "1000",
                  }

                  var all_ratingvalue = []
                  // ratingvalue_datatable()
                  


                  var selected = null
                  var action = ''

                  $(document).on('click','#rating_value',function(){
                        action = 'create'
                        $('#create_button').text('Create')
                        $('#ratingvalue_modal').modal()
                  })
            
                  $(document).on('click','.rv_edit',function(){
                        selected = $(this).attr('data-id')
                        var temp_data = all_ratingvalue.filter(x=>x.id == selected)
                        $('#rv_input_sort').val(temp_data[0].sort)
                        $('#rv_input_value').val(temp_data[0].value)
                        $('#rv_input_description').val(temp_data[0].description)
                        $('#ratingvalue_modal').modal()
                        $('#rv_create_button').text('Update')
                        action = 'update'
                  })

                  $(document).on('change','#filter_sy',function(){
                        all_ratingvalue = []
                        ratingvalue_datatable()
                  })
                 

                  function update_ratingvalue() {
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/ratingvalue/update',
                              data:{
                                    id:selected,
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val(),
                                    sort:$('#rv_input_sort').val(),
                                    value:$('#rv_input_value').val(),
                                    description:$('#rv_input_description').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          all_ratingvalue = data[0].info
                                          ratingvalue_datatable()
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
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }


                  $(document).on('click','.rv_delete',function(){
                        selected = $(this).attr('data-id')
                        Swal.fire({
                              title: 'Do you want to remove rating value?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    delete_ratingvalue()
                              }
                        })
                  })

                  function delete_ratingvalue(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/ratingvalue/delete',
                              data:{
                                    id:selected,
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val(),
                                    sort:$('#rv_input_sort').val(),
                                    value:$('#rv_input_value').val(),
                                    description:$('#rv_input_description').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          all_ratingvalue = data[0].info
                                          ratingvalue_datatable()
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
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  $(document).on('click','#rv_create_button',function(){
                        var valid = true;
                        if($('#rv_input_description').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Description is required!'
                              })
                              valid = false;
                        }else if($('#rv_input_value').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Value is required!'
                              })
                              valid = false;
                        }
                        else if($('#rv_input_sort').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Sort is required!'
                              })
                              valid = false;
                        }

                        if(valid){
                              if(action == 'update'){
                                    update_ratingvalue()
                              }else{
                                    create_ratingvalue()
                              }
                        
                        }
                  
                  })

                  function create_ratingvalue(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/ratingvalue/create',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val(),
                                    sort:$('#rv_input_sort').val(),
                                    value:$('#rv_input_value').val(),
                                    description:$('#rv_input_description').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          all_ratingvalue = data[0].info
                                          ratingvalue_datatable()
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
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }


                  
            

                  $(document).on('change','#filter_gradelevel',function(){

                        var valid = true

                        if($('#filter_gradelevel').val() == ""){
                              valid = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please select grade level'
                              })
                        }

                        if(valid){
                              $('#button_to_modal').removeAttr('disabled')
                              $('#ob_copy_to').removeAttr('disabled')
                              $('#rating_value').removeAttr('disabled')
                              get_ratingvalue()
                        }else{
                              $('#button_to_modal').attr('disabled','disabled')
                              $('#ob_copy_to').attr('disabled','disabled')
                              $('#rating_value').attr('disabled','disabled')
                              all_ratingvalue = []
                              ratingvalue_datatable()
                        }

                  })

                  all_ratingvalue = []
                  ratingvalue_datatable()

                  function get_ratingvalue(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/ratingvalue/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#filter_gradelevel').val()
                              },
                              success:function(data) {
                                    if(data.length > 0){
                                          toastr.info( data.length+' rating value(s) found!')
                                    }else{
                                          toastr.info( 'No rating value found!')
                                    }
                                    all_ratingvalue = data
                                    ratingvalue_datatable()
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function ratingvalue_datatable(){

                        var temp_table = $("#ratingvalue_table").DataTable({
                              destroy: true,
                              data:all_ratingvalue,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              stateSave: true,
                              columns: [
                                    { "data": "sort" },
                                    { "data": "value" },
                                    { "data": "description" },
                                    { "data": null },
                                    { "data": null }
                              ], 
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="rv_edit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="rv_delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        });

                        return temp_table

                  }
                        
                  $(document).on('click','.button_to_gradesetup_modal',function(){
                        subjid = $(this).attr('data-id')
                        var subj_info = all_subject.filter(x=>x.id == subjid)
                  
                        $('#subject_holder').text(subj_info[0].text)
                  })

                  var keysPressed = {};

                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/31/2021 14:22'
                                    })
                        }
                  });

                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });

            })
      </script>
      
      <script>
            $(document).on('change','#filter_sy',function(){
                  $('#filter_gradelevel').empty()
                  get_gradelevel()
            })

            get_gradelevel()

            function get_gradelevel(){

                  $.ajax({
                        type:'GET',
                        url: '/superadmin/setup/subject/plot/getgradelevel',
                        data:{
                              syid:$('#filter_sy').val(),
                        },
                        success:function(data) {

                              gradelevel = data
                              $('#filter_gradelevel').empty()
                              $("#filter_gradelevel").append('<option value="">Select Grade Level</option>');
                              $("#filter_gradelevel").select2({
                                    data: gradelevel,
                                    placeholder: "Select Grade Level",
                                    allowClear:true
                              })

                              $('#input_gradelevel').empty()
                              $("#input_gradelevel").append('<option value="">Select Grade Level</option>');
                              $("#input_gradelevel").select2({
                                    data: gradelevel,
                                    placeholder: "Select Grade Level",
                                    allowClear:true
                              })

                              
                        }
                  })

            }
      </script>


@endsection


