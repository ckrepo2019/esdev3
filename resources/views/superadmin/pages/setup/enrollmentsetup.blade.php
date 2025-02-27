@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if( Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
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
      $sy = DB::table('sy')
                  ->select(
                        'id',
                        'sydesc as text'
                  )
                  ->orderBy('sydesc','desc')
                  ->get(); 
      $semester = DB::table('semester')
                  ->select(
                        'id',
                        'semester as text'
                  )
                  ->get(); 
      $acadprog = DB::table('academicprogram')
                        ->select(
                              'id',
                              'progname as text'
                        )
                  ->get(); 
      $enrollmenttype = DB::table('early_enrollment_setup_type')
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'description as text',
                                    'description'
                              )
                              ->get();

     
      $schoolinfo = DB::table('schoolinfo')->first();
@endphp

<div class="modal fade" id="modal_enrollmentsetup" data-backdrop="static" data-keyboard="false" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">School Year</label>
                                    <select class="form-control select2" id="input_syid">
                                         
                                    </select>
                                </div>
                              <div class="col-md-12 form-group">
                                    <strong>Admission Type</strong>
                                    {{-- <p class="text-muted mb-0" id="addtype_label">2</p> --}}
                                    <select name="input_addtype" id="input_addtype" class="select2 form-control">
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <strong>Academic Program</strong>
                                    {{-- <p class="text-muted mb-0" id="acadprog_label">2</p> --}}
                                    <select name="input_acadprogid" id="input_acadprogid" class="select2 form-control">
                                    </select>
                              </div>
                              {{-- <div class="col-md-12 form-group" hidden id="holder_college">
                                    <label for="">Enrollment Type</label>
                                    <div class="row">
                                          <div class="col-md-12 pt-1">
                                                <div class="icheck-success d-inline">
                                                    <input class="form-control" type="radio" id="enrollment_regular" name="enrollmenttype" value="1" required checked>
                                                    <label for="enrollment_regular">Regular Enrollment
                                                    </label>
                                                </div>
                                          </div>
                                          <div class="col-md-12 pt-1">
                                                <div class="icheck-success d-inline">
                                                      <input class="form-control" type="radio" id="enrollment_addingdropping" name="enrollmenttype" value="2" required>
                                                      <label for="enrollment_addingdropping">Adding Dropping
                                                      </label>
                                                </div>
                                          </div>
                                    </div>
                              </div> --}}

                              <div class="col-md-12 form-group" hidden id="holder_semid">
                                    <label for="">Enrollment Type</label>
                                    <select name="" id="input_semid" class="form-control form-control-sm">
                                          {{-- @foreach ($semester as $item)
                                              <option value="{{$item->id}}">{{$item->semester}}</option>
                                          @endforeach --}}
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Student Type</label>
                                    <select name="input_studtype" id="input_studtype" class="select2 form-control">
                                          <option value="0" selected=>All</option>
                                          <option value="1">Old</option>
                                          <option value="2">New</option>
                                    </select>
                              </div>
                           
                              <div class="col-md-12 form-group">
                                    <label for="">Date Start</label>
                                    <input id="input_datestart" class="form-control form-control-sm" type="date">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Date End</label>
                                    <input id="input_dateend" class="form-control form-control-sm" type="date">
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6" id="create_enrollmentsetup_holder">
                              <button class="btn btn-primary btn-sm" id="create_enrollmentsetup">Create</button>
                        </div>
                        <div class="col-md-6" id="update_enrollmentsetup_holder" hidden>
                              <button class="btn btn-success btn-sm" id="update_enrollmentsetup">Update</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>    


<div class="modal fade" id="admission_type_list_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h5 class="modal-title">Admission Type List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <table class="table table-sm table-striped" id="admission_type_table" width="100%">
                              <thead>
                                    <tr> 
                                          <th width="90%">Description</th>
                                          <th width="5%"></th>
                                          <th width="5%"></th>
                                    </tr>
                              </thead>
                        </table>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="admission_type_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h5 class="modal-title">Admission Type Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Description</label>
                                    <input id="admission_type_description" class="form-control form-control-sm" autocomplete="off">
                              </div>
                            
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm admission_type_create_button"><i class="fas fa-plus"></i> Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="admission_type_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h5 class="modal-title">Admission Type Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Admission type Description</label>
                                    <input id="admission_type_description" class="form-control form-control-sm" autocomplete="off">
                              </div>
                            
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm admission_type_create_button"><i class="fas fa-plus"></i> Create</button>
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
                        <h1 class="m-0 text-dark">Admission Date Setup</h1>
                  </div>
                  <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">Admission Date Setup</li>
                        </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row" id="no_acad_holder" hidden>
                  <div class="col-md-12">
                        <div class="card shadow bg-danger">
                              <div class="card-body p-1">
                                    No academic program assigned.
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-1">
                                   <p class="mb-0">Note: In order to start the enrollment, you have to setup the Admission Date of the enrollment to proceed for this school's enrollment.</p>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="attendance_setup" width="100%" style="font-size: 15px !important;">
                                                      <thead>
                                                            <tr> 
                                                                  <th width="5%"></th>
                                                                  <th width="8%">S.Y.</th>
                                                                  <th width="29%">Admission Type</th>
                                                                  <th width="15%">Academic Program</th>
                                                                  <th width="10%">Stud. Type</th>
                                                                  <th width="10%">Date Start</th>
                                                                  <th width="10%">Date End</th>
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
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    {{-- <div class="row">
                                          <div class="col-md-12 mb-1">
                                                <h5 for="">Ended Admission Setup</h5>
                                          </div>
                                    </div> --}}
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="attendance_setup_ended" width="100%" style="font-size: 15px !important">
                                                      <thead>
                                                            <tr> 
                                                                  <th width="10%">S.Y.</th>
                                                                  <th width="30%">Admission Type</th>
                                                                  <th width="15%">Academic Program</th>
                                                                  <th width="10%">Stud. Type</th>
                                                                  <th width="15%">Date Start</th>
                                                                  <th width="15%">Date End</th>
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

            var school_setup = @json($schoolinfo);

            function get_last_index(tablename){
                  $.ajax({
                        type:'GET',
                        url: school_setup.es_cloudurl+'/monitoring/tablecount',
                        data:{
                              tablename: tablename
                        },
                        success:function(data) {
                              lastindex = data[0].lastindex
                              update_local_table_display(tablename,lastindex)
                        },
                  })
            }

            function update_local_table_display(tablename,lastindex){
                  $.ajax({
                        type:'GET',
                        url: '/monitoring/table/data',
                        data:{
                              tablename:tablename,
                              tableindex:lastindex
                        },
                        success:function(data) {
                              if(data.length > 0){
                                    process_create(tablename,0,data)
                              }
                        },
                        error:function(){
                              $('td[data-tablename="'+tablename+'"]')[0].innerHTML = 'Error!'
                        }
                  })
            }

            function process_create(tablename,process_count,createdata){
                  if (createdata.length == 0){
                        return false
                  }
                  var b = createdata[0]
                  $.ajax({
                        type:'GET',
                        url: school_setup.es_cloudurl+'/synchornization/insert',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              process_count += 1
                              createdata = createdata.filter(x=>x.id != b.id)
                              process_create(tablename,process_count,createdata)
                        },
                        error:function(){
                              process_count += 1
                              createdata = createdata.filter(x=>x.id != b.id)
                              process_create(tablename,process_count,createdata)
                        }
                  })
            }

            //get_updated
            function get_updated(tablename){
                  var date = moment().subtract(1, 'minute').format('YYYY-MM-DD HH:mm:ss');
                  $.ajax({
                        type:'GET',
                        url: '/monitoring/table/data/updated',
                        data:{
                              tablename: tablename,
                              date: date
                        },
                        success:function(data) {
                              process_update(tablename,data)
                        }
                  })
            }


            function process_update(tablename , updated_data){
                  if (updated_data.length == 0){
                        return false
                  }

                  var b = updated_data[0]

                  $.ajax({
                        type:'GET',
                        url:  school_setup.es_cloudurl+'/synchornization/update',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              updated_data = updated_data.filter(x=>x.id != b.id)
                              process_update(tablename,updated_data)
                        },
                  })
            }

            //get_updated
      </script>

      <script>
            $(document).ready(function(){

                  var all_enrollmentsetup = []
                  var all_active_enrollmentsetup = []
                  var selected_enrollmentsetup
                  var entype = @json($enrollmenttype);
                  var sy = @json($sy);
                  var semester = @json($semester);
                  // var acadprog = @json($acadprog);
                  var process = 'create'

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var schoolinfo = @json($schoolinfo);


                  $('#input_studtype').select2()

                  $('#input_semid').empty()
                  $('#input_semid').append('<option value="">Select Semester</option>')
                  $('#input_semid').select2({
                        allowClear:true,
                        data:semester,
                        placeholder: "Select Semester",
                  })

                  $('#input_syid').empty()
                  $('#input_syid').append('<option value="">Select School Year</option>')
                  $('#input_syid').select2({
                        allowClear:true,
                        data:sy,
                        placeholder: "Select School Year",
                  })

                  
                  $(document).on('click','.create_admission_type',function(){
                        $('.admission_type_update_button').addClass('admission_type_create_button')
                        $('.admission_type_create_button').text('Create')
                        $('.admission_type_create_button').removeClass('admission_type_update_button')
                        $('#admission_type_modal').modal()    
                  })

                  $(document).on('click','.edit_addmission_type',function(){

                        selected_admission_type = $(this).attr('data-id')

                        var temp_add_type_selected_info = entype.filter(x=>x.id == selected_admission_type)
                        $('#admission_type_description').val(temp_add_type_selected_info[0].text)
                        $('#admission_type_modal').modal()   

                        $('.admission_type_create_button').addClass('admission_type_update_button')
                        $('.admission_type_create_button').text('Update')
                        $('.admission_type_create_button').removeClass('admission_type_create_button')
                        $('#admission_type_modal').modal()    
                        
                  })

              

                  $("#filter_semid").select2({
                        allowClear: true,
                        placeholder: "Select Semester",
                  })


                 

                  get_enrollmentsetup()
                  // loaddatatable()

                  var selected_admission_type = null

                  if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' ) ){
                        get_last_index('early_enrollment_setup_type')
                        get_last_index('early_enrollment_setup')
                        get_updated('early_enrollment_setup_type')
                        get_updated('early_enrollment_setup')
                  }

                  get_acad()

                  function get_acad(){
                        $.ajax({
					type:'GET',
					url: '/enrollmentsetup/getacad',
					success:function(data) {

                                    if(data.length > 0 ){
                                          var acadprog = data
                                          $("#input_acadprogid").empty();
                                          $("#input_acadprogid").append('<option values=""></option>')

                                          $("#input_acadprogid").select2({
                                                allowClear: true,
                                                data:acadprog,
                                                placeholder: "Select Acadmic Program",
                                          })
                                          $('.admission_type_to_modal').removeAttr('hidden')
                                          $('.admission_date_to_modal').removeAttr('hidden')
                                          $('#no_acad_holder').attr('hidden')
                                    }else{
                                          $('#no_acad_holder').removeAttr('hidden')
                                          $('.admission_type_to_modal').attr('hidden','hidden')
                                          $('.admission_date_to_modal').attr('hidden','hidden')
                                    }
                                    
					}
				})
                  }
                  
              

                  $(document).on('change','#input_acadprogid',function(){
                       if($(this).val() == 5 || $(this).val() == 6 || $(this).val() == 8){
                             $('#holder_semid').removeAttr('hidden')
                       }else{
                              $('#holder_semid').attr('hidden','hidden')
                       }

                        if($(this).val() == 6){
                              $('#holder_college').removeAttr('hidden')
                        }else{
                              $('#holder_college').attr('hidden','hidden')
                        }
                  })

                  $(document).on('click','.admission_type_create_button',function(){

                        if($('#admission_type_description').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title:  "Description is empty"
                              })
                              return false
                        }

                        $.ajax({
					type:'GET',
					url: '/enrollmentsetup/type/create',
                              data:{
                                    description:$('#admission_type_description').val(),
                              },
					success:function(data) {
						if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          entype = data[0].type_list
                                          admission_type_table()

                                          if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                                get_last_index('early_enrollment_setup_type')
                                                // get_last_index('early_enrollment_setup')
                                          }

                                    }else if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }else if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'danger',
                                                title: data[0].data
                                          })
                                    }
					}
				})

                  })

                  $(document).on('click','.admission_type_update_button',function(){

                        if($('#admission_type_description').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title:  "Description is empty"
                              })
                              return false
                        }

                        $.ajax({
                              type:'GET',
                              url: '/enrollmentsetup/type/update',
                              data:{
                                    description:$('#admission_type_description').val(),
                                    id:selected_admission_type
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          entype = data[0].type_list
                                          admission_type_table()
                                          if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                                get_updated('early_enrollment_setup_type')
                                          }
                                    }else if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }else if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'danger',
                                                title: data[0].data
                                          })
                                    }
                              }
                        })
                  })


                  $(document).on('click','.delete_addmission_type',function(){

                        var temp_id = $(this).attr('data-id')

                        Swal.fire({
                              title: 'Do you want to remove admission type?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/enrollmentsetup/type/delete',
                                          data:{
                                                id:temp_id
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                      entype = data[0].type_list
                                                      admission_type_table()
                                                }else if(data[0].status == 2){
                                                      Toast.fire({
                                                            type: 'warning',
                                                            title: data[0].data
                                                      })
                                                }else if(data[0].status == 0){
                                                      Toast.fire({
                                                            type: 'danger',
                                                            title: data[0].data
                                                      })
                                                }
                                          }
                                    })

                              }
                        })
                  })


                  $(document).on('click','#button_enrollmentsetup',function(){
                        $('#modal_enrollmentsetup').modal()    
                  })

                  $(document).on('click','.admission_date_to_modal',function(){
                        $('#update_enrollmentsetup_holder').attr('hidden','hidden')
                        $('#create_enrollmentsetup_holder').removeAttr('hidden')
                        $('#input_acadprogid').val("").change();
                        $('#input_addtype').val("").change();
                        $('#input_datestart').val("")
                        $('#input_dateend').val("")
                        $('#input_semid').val("").change()
                        $('#input_syid').val("").change()
                        $('#input_studtype').val(0).change()
                        $('#modal_enrollmentsetup').modal()
                  })
                 
                  $(document).on('click','.edit_enrollmentsetup',function(){
                        selected_enrollmentsetup = $(this).attr('data-id')
                        process = 'edit'
                        $('#create_enrollmentsetup_holder').attr('hidden','hidden')
                        $('#update_enrollmentsetup_holder').removeAttr('hidden')
                        var temp_attendance_id = all_enrollmentsetup.filter(x=>x.id == selected_enrollmentsetup)
                        $('#input_syid').val(temp_attendance_id[0].syid).change()
                        $('#input_addtype').val(temp_attendance_id[0].type).change()
                        $('#input_acadprogid').val(temp_attendance_id[0].acadprogid).change()
                        $('#input_semid').val(temp_attendance_id[0].semid).change()
                        $('#input_studtype').val(temp_attendance_id[0].admission_studtype).change()
                        $('#input_datestart').val(temp_attendance_id[0].enrollmentstart_format1)
                        $('#input_dateend').val(temp_attendance_id[0].enrollmentend_format1)

                        if(temp_attendance_id[0].collegeentype == 1){
                              $('#enrollment_regular').prop('checked',true)
                        }else{
                              $('#enrollment_addingdropping').prop('checked',true)
                        }

                        $('#modal_enrollmentsetup').modal()   
                  })

                  function get_enrollmentsetup(){

                        $.ajax({
					type:'GET',
					url: '/enrollmentsetup/list',
					success:function(data) {
						all_enrollmentsetup = data
                                    if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                          get_last_index('early_enrollment_setup')
                                          get_last_index('early_enrollment_setup_type')
                                    }
                                    loaddatatable(true)
					}
				})
                  }

                 

                  $(document).on('click','#create_enrollmentsetup',function(){

                        if($('#input_syid').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No School Year selected'
                              })
                              return false
                        }

                        if($('#input_addtype').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Admission Type selected'
                              })
                              return false
                        }

                        if($('#input_acadprogid').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Academic Program selected'
                              })
                              return false
                        }

                        if($('#input_datestart').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Date Start selected'
                              })
                              return false
                        }

                        if($('#input_dateend').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Date End selected'
                              })
                              return false
                        }

                        var enrollmenttype = 1

                        if($('#enrollment_addingdropping').prop('checked') == true){
                              enrollmenttype = 2
                        }

                        $.ajax({
					type:'GET',
					url: '/enrollmentsetup/create',
					data:{
                                    syid:$('#input_syid').val(),
                                    enrollmentstart:$('#input_datestart').val(),
                                    enrollmentend:$('#input_dateend').val(),
                                    studtype:$('#input_studtype').val(),
                                    addtype:$('#input_addtype').val(),
                                    semid:$('#input_semid').val(),
                                    acadprogid:$('#input_acadprogid').val(),
                                    enrollmenttype:enrollmenttype

					},
					success:function(data) {
						if(data[0].status == 1){
							Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                                get_last_index('early_enrollment_setup')
                                          }
                                          all_enrollmentsetup = data[0].info
                                          loaddatatable()
						}else{
							Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
						}
					}
				})
                  })

                  $(document).on('click','.delete_enrollmentsetup',function(){

                        var temp_id = $(this).attr('data-id')

                        Swal.fire({
                              title: 'Do you want to remove enrollment setup?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/enrollmentsetup/delete',
                                          data:{
                                                id:temp_id
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      all_enrollmentsetup = data[0].info
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
                        })
                       
                  })


                  $(document).on('click','#update_enrollmentsetup',function(){

                        if($('#input_syid').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No School Year selected'
                              })
                              return false
                        }

                        if($('#input_addtype').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Admission Type selected'
                              })
                              return false
                        }

                        if($('#input_acadprogid').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Academic Program selected'
                              })
                              return false
                        }

                        if($('#input_datestart').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Date Start selected'
                              })
                              return false
                        }

                        if($('#input_dateend').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No Date End selected'
                              })
                              return false
                        }
                        
                        $.ajax({
					type:'GET',
					url: '/enrollmentsetup/update',
					data:{
                                    syid:$('#input_syid').val(),
                                    enrollmentstart:$('#input_datestart').val(),
                                    enrollmentend:$('#input_dateend').val(),
                                    studtype:$('#input_studtype').val(),
                                    addtype:$('#input_addtype').val(),
                                    semid:$('#input_semid').val(),
                                    acadprogid:$('#input_acadprogid').val(),
                                    id:selected_enrollmentsetup
					},
					success:function(data) {
						if(data[0].status == 1){
							Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          all_enrollmentsetup = data[0].info
                                          loaddatatable()
                                          if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                                get_updated('early_enrollment_setup')
                                          }
						}else{
							Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
						}
					}
				})
                  })


                  $(document).on('click','.update_active',function(){
                        var temp_id = $(this).attr('data-id')

                        var temp_info = all_enrollmentsetup.filter(x=>x.id == temp_id)
                
                        var check = all_enrollmentsetup.filter(x=>x.acadprogid == temp_info[0].acadprogid && x.isactive == 1 && x.syid == temp_info[0].syid)

                        if(check.length > 0){
                              Swal.fire({
                                    title: 'Active enrollment setup already exist for '+check[0].progname+' Department.',
                                    text: 'Please end the current active enrollment setup.',
                                    type: 'warning',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Close'
                              }).then((result) => {

                              })

                              return false
                        }
                        
                        Swal.fire({
                              title: 'Do you want to start enrollment setup?',
                              text: 'You will not be able update enrollment setup',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Start'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/enrollmentsetup/update/active',
                                          data:{
                                                id:temp_id
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                      all_enrollmentsetup = data[0].info
                                                      loaddatatable()
                                                      if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                                            get_updated('early_enrollment_setup')
                                                      }
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
                  })

                  $(document).on('click','.update_end',function(){
                        var temp_id = $(this).attr('data-id')

                        


                        Swal.fire({
                              title: 'Do you want to end enrollment setup?',
                             // text: 'You will not be able update enrollment setup',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'End'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/enrollmentsetup/update/end',
                                          data:{
                                                id:temp_id
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].data
                                                      })
                                                      all_enrollmentsetup = data[0].info
                                                      loaddatatable()
                                                      if(schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' )){
                                                            get_updated('early_enrollment_setup')
                                                      }
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
                        
                  })

                  $(document).on('click','.admission_type_to_modal',function(){
                        $('#admission_type_list_modal').modal()
                  })

                 
                  

                  

                  function loaddatatable(prompt = false){

                        var temp_all_enrollmentsetup = all_enrollmentsetup.filter(x => x.admission_ended == 0)

                        if(prompt){
                              if(temp_all_enrollmentsetup.length > 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: "Admission dates found."
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'warning',
                                          title: "Please create admission Date."
                                    })
                              }
                        }
                        
                        $("#attendance_setup").DataTable({
                                    destroy: true,
                                    pageLength: 50,
                                    paging: false,
                                    bInfo: false,
                                    data:temp_all_enrollmentsetup,
                                    columns: [
                                          { "data": "acadprogid"},
                                          { "data": "sydesc" },
                                          { "data": "description" },
                                          { "data": "progname" },
                                          { "data": "progname" },
                                          { "data": "enrollmentstart" },
                                          { "data": "enrollmentend" },
                                          { "data": null },
                                          { "data": null },
                                    ],

                                    columnDefs: [
                                                      {
                                                            'targets': 0,
                                                            'orderable': false, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if( (schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' ) ) || schoolinfo.processsetup == 'all' ){
                                                                        if(rowData.isactive == 1){
                                                                              $(td)[0].innerHTML = '<button class="btn btn-sm btn-danger btn-block update_end" style="font-size:15px !important"  data-id="'+rowData.id+'">END</button>'
                                                                        }else{
                                                                              // $(td)[0].innerHTML = '<input type="checkbox" class="update_active" data-id="'+rowData.id+'" >'
                                                                              // $(td).addClass('text-center')
                                                                              $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block update_active" style="font-size:15px !important" data-id="'+rowData.id+'">START</button>'
                                                                        }
                                                                  }else{
                                                                        $(td).text(null)
                                                                  }
                                                            }
                                                      },
                                                      {
                                                            'targets': 2,
                                                            'orderable': false, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.acadprogid == 5){
                                                                        var displaySem = rowData.semester != null ? rowData.semester : '';
                                                                        $(td)[0].innerHTML = rowData.description +' : <span class="text-success"><i>'+displaySem+'</span><i>'
                                                                  }else if( rowData.acadprogid == 6){
                                                                        var collegeentype = 'Regular'
                                                                        if(rowData.collegeentype == 2){
                                                                              collegeentype = 'Adding/Dropping'
                                                                        }
                                                                        var displaySem = rowData.semester != null ? rowData.semester.substr(0,7) : '';
                                                                        $(td)[0].innerHTML = rowData.description +' : <span class="text-success"><i>'+displaySem+' : <span class="text-danger">'+collegeentype+'<span></span><i>'
                                                                  }
                                                            }
                                                      },
                                                      {
                                                            'targets': 4,
                                                            'orderable': false, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.admission_studtype == 0){
                                                                        $(td).text('All')
                                                                  }else if(rowData.admission_studtype == 1){
                                                                        $(td).text('Old')
                                                                  }else{
                                                                        $(td).text('New')
                                                                  }
                                                                  
                                                            }
                                                      },
                                                      {
										'targets': 7,
										'orderable': false, 
										'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.isactive == 1){
                                                                        var buttons = ''
                                                                  }else{
                                                                        var buttons = '<a href="#" class="edit_enrollmentsetup" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                                  }
                                                                 
											$(td)[0].innerHTML =  buttons
											$(td).addClass('text-center')
                                                              
										}
                                    			},
                                                      {
										'targets': 8,
										'orderable': false, 
										'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.isactive == 1){
                                                                        var buttons = ''
                                                                  }else{
                                                                        var buttons = '<a href="#" class="delete_enrollmentsetup" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                                  }
                                                                 
											$(td)[0].innerHTML =  buttons
											$(td).addClass('text-center')
                                                              
										}
                                    			},
								]
                        });


                        if( (schoolinfo.projectsetup == 'offline' &&  ( schoolinfo.processsetup == 'hybrid1' || schoolinfo.processsetup == 'hybrid2' ) ) || schoolinfo.processsetup == 'all' ){
                              var label_text = $($("#attendance_setup_wrapper")[0].children[0])[0].children[0]
                              $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm admission_type_to_modal mr-1" style="font-size: 0.938rem !important" >Admission Type</button><button class="btn btn-primary btn-sm admission_date_to_modal" style="font-size: 0.938rem !important" ><i class="fas fa-plus"></i> Create Admission Date</button>'
                        }

                      


                        var temp_all_enrollmentsetup = all_enrollmentsetup.filter(x => x.admission_ended == 1)

                        $("#attendance_setup_ended").DataTable({
                                    destroy: true,
                                    pageLength: 50,
                                    bInfo: false,
                                    data:temp_all_enrollmentsetup,
                                    columns: [
                                          { "data": "sydesc" },
                                          { "data": "description" },
                                          { "data": "progname" },
                                          { "data": "progname" },
                                          { "data": "enrollmentstart" },
                                          { "data": "enrollmentend" }
                                    ],

                                    columnDefs: [
                                                      {
                                                            'targets': 1,
                                                            'orderable': false, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.acadprogid == 5 || rowData.acadprogid == 6){
                                                                        var displaySem = rowData.semester != null ? rowData.semester : '';

                                                                        $(td)[0].innerHTML = rowData.description +' : <span class="text-success"><i>'+displaySem+'</span><i>'
                                                                  }
                                                            }
                                                      },
                                                      {
                                                            'targets': 3,
                                                            'orderable': false, 
                                                            'createdCell':  function (td, cellData, rowData, row, col) {
                                                                  if(rowData.admission_studtype == 0){
                                                                        $(td).text('All')
                                                                  }else if(rowData.admission_studtype == 1){
                                                                        $(td).text('Old')
                                                                  }else{
                                                                        $(td).text('New')
                                                                  }
                                                                  
                                                            }
                                                      }
								]
                        });


                      

                        var label_text = $($("#attendance_setup_ended_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<h5 class="mb-0">Ended Admission Setup</h5>'

                  
                  }

                  admission_type_table()
             
                  function admission_type_table(){

                        $("#input_addtype").empty();
                        $("#input_addtype").append('<option value=""></option>');
                        $("#input_addtype").select2({
                              data:entype,
                              allowClear: true,
                              placeholder: "Select Admission Type",
                        })

                        $("#admission_type_table").DataTable({
                                    destroy: true,
                                    paging: false,
                                    bInfo: false,
                                    autoWidth:false,
                                    data:entype,
                                    columns: [
                                          { "data": "description" },
                                          { "data": null },
                                          { "data": null }
                                    ],

                                    columnDefs: [
                                          {
                                                      'targets': 1,
                                                      'orderable': true, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            var buttons = '<a href="#" class="edit_addmission_type" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                            
                                                      }
                                                },
                                                {
                                                      'targets': 2,
                                                      'orderable': true, 
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            var buttons = '<a href="#" class="delete_addmission_type" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                      }
                                                },
                                                      
                                                ]
                        });


                        var label_text = $($("#admission_type_table_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm create_admission_type" style="font-size: 0.938rem !important"><i class="fas fa-plus"></i> Create Admission Type</button>'

                  }



            })
      </script>


@endsection


