
@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 6 || Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }
@endphp


@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
            /* input[type=search]{
                  height: calc(1.7em + 2px) !important;
            } */
      </style>
@endsection


@section('content')

@php
      $schoolinfo = DB::table('schoolinfo')->first();
@endphp

<div class="modal fade" id="sy_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">School Year Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Start Date</label>
                                    <input id="sdate" class="form-control form-control-sm" type="date">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">End Date</label>
                                    <input id="edate" class="form-control form-control-sm" type="date">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="sy_form_button">Create</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="sy_info_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">School Year Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-4">
                                    <strong><i class="fas fa-book mr-1"></i>School Year</strong>
                                    <p class="text-muted" id="label_sydesc"></p>
                              </div>
                              <div class="col-md-4">
                                    <strong><i class="fas fa-book mr-1"></i>Start Date</strong>
                                    <p class="text-muted" id="label_sdate"></p>
                              </div>
                              <div class="col-md-4">
                                    <strong><i class="fas fa-book mr-1"></i>End Date</strong>
                                    <p class="text-muted" id="label_edate"></p>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="enrollment_info_datatable" width="100%" style="font-size:.8rem">
                                          <thead>
                                                <tr>
                                                      <th width="45%"  class="align-middle">Academic Program</th>
                                                      <th width="20%"  class="text-center">Enrolled</th>
                                                      <th width="35%"  class="text-center">Processed Promotion</th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-5">
                                    <button class="btn btn-sm btn-primary" style="font-size:.8rem !important" id="activate_sy" hidden>Activate School Year</button>
                              </div>
                              <div class="col-md-7"  id="sem_holder" hidden>
                                    <table class="table-hover table table-striped table-sm table-bordered" width="100%" style="font-size:.8rem">
                                          <tbody id="semester_list">

                                          </tbody>
                                         
                                    </table>
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
                        <h1>School Year</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Year</li>
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
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered" id="sy_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="1%"></th>
                                                                  <th width="11%">S.Y.</th>
                                                                  <th width="31%">Start Date</th>
                                                                  <th width="31%">End Date</th>
                                                                  <th width="6%" class="text-center">Enrolled</th>
                                                                  <th width="15%"></th>
                                                                  <th width="5%"></th>
                                                                  {{-- <th width="5%"></th> --}}
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <span class="badge badge-success mr-1">Active</span><span class="badge badge-danger mr-1">No Activated</span><span class="badge badge-warning">Ended</span>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                Note: 
                                                <ul>
                                                      <li>
                                                            Click the <button class="btn btn-sm btn-danger" style="font-size:.6rem !important">End</button> button to end a specific school year. Ending the school year will retrict some functionalities. School year will be automatically end if a new school year is set to active. <button class="btn btn-sm btn-danger" style="font-size:.6rem !important">End</button> button will only appear if the previous active school year is not marked as ended.
                                                      </li>
                                                      <li>
                                                            Only the Start Date and end date will be edited if a school year is active.
                                                      </li>
                                                </ul>
                                                
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
                  if(createdata.length == 0){
                        return false;
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

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var schoolinfo = @json($schoolinfo);

                  if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                        get_last_index('sy')
                  }


                  $('.select2').select2()

                  var sy_list = []
                  var all_enrollment_Info = []
                  var selected_id = null
                  var selected_sem = null

                  load_sy_datatable()
                  get_sy_list()

                  $(document).on('click','#sy_button_to_create_form',function(){
                        $('#sdate').val("")
                        $('#edate').val("")
                        $('#sy_form_modal').modal()
                        $('#sy_form_button').attr('data-p','create')
                        $('#sy_form_button').removeClass('btn-success')
                        $('#sy_form_button').addClass('btn-primary')
                        $('#sy_form_button').text('Create')
                  })

                  $(document).on('click','#sy_form_button',function(){
                        if($(this).attr('data-p') == 'create'){
                              sy_create()
                        }else if($(this).attr('data-p') == 'update'){
                              sy_update()
                        }
                  })

                  $(document).on('click','.update_sy',function(){
                        var temp_id = $(this).attr('data-id')
                        var sy_info = sy_list.filter(x=>x.id == temp_id)
                        selected_id = temp_id
                        $('#sdate').val(sy_info[0].sdateorig)
                        $('#edate').val(sy_info[0].edateorig)
                        $('#sy_form_modal').modal()
                        $('#sy_form_button').attr('data-p','update')
                        $('#sy_form_button').removeClass('btn-primary')
                        $('#sy_form_button').addClass('btn-success')
                        $('#sy_form_button').text('Update')
                  })

                 

                  $(document).on('click','#activate_sy',function(){

                        var sy_info = sy_list.filter(x=>x.id == selected_id)
                        var active_sy = sy_list.filter(x=>x.isactive == 1)

                        if(sy_info[0].sort + 1 == active_sy[0].sort  ){
                              Swal.fire({
                                    text: 'Are you sure you want to activate S.Y. '+sy_info[0].sydesc,
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Activate'
                              }).then((result) => {
                                    if (result.value) {
                                          activate_sy()
                                          
                                    }
                              })
                        }else{
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Unable to update!'
                              }) 
                        }
                        
                  })

                  $(document).on('click','.end_sy',function(){

                        var selected_id = $(this).attr('data-id')
                        var sy_info = sy_list.filter(x=>x.id == selected_id)
                        var active_sy = sy_list.filter(x=>x.isactive == 1)

                        if(sy_info[0].sort > active_sy[0].sort){
                              Swal.fire({
                                    text: 'Are you sure you want to end S.Y. '+sy_info[0].sydesc,
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'End S.Y.'
                              }).then((result) => {
                                    if (result.value) {
                                          $.ajax({
                                                type:'GET',
                                                url:'/setup/schoolyear/endsy',
                                                data:{
                                                      syid:selected_id
                                                },
                                                success:function(data) {
                                                      if(data[0].status == 0){
                                                            Toast.fire({
                                                                  type: 'warning',
                                                                  title: data[0].message
                                                            })
                                                      }else{
                                                            Toast.fire({
                                                                  type: 'success',
                                                                  title: data[0].message
                                                            })
                                                            get_sy_list()
                                                      }
                                                }
                                          })
                                    }
                              })
                        }else{
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Unable to process!'
                              }) 
                        }

                  })

                  $(document).on('click','#activate_sem',function(){
                        selected_sem = $(this).attr('data-id')
                        activate_sem()
                  })

                  $(document).on('click','.view_sy_info',function(){
                        var temp_id = $(this).attr('data-id')
                        var sy_info = sy_list.filter(x=>x.id == temp_id)
                        var active_sy = sy_list.filter(x=>x.isactive == 1)

                        if(sy_info[0].sort + 1 == active_sy[0].sort){
                              $('#activate_sy').removeAttr('hidden')
                        }else{
                              $('#activate_sy').attr('hidden','hidden')
                        }

                        if(sy_info[0].isactive == 1){
                              $('#sem_holder').removeAttr('hidden')
                        }else{
                              $('#sem_holder').attr('hidden','hidden')
                        }

                        selected_id = temp_id
                        get_sy_enrollment_info()
                        get_sem_list()
                        $('#semester_list').empty()
                        $('#label_sydesc').text(sy_info[0].sydesc)
                        $('#label_sdate').text(sy_info[0].sdate)
                        $('#label_edate').text(sy_info[0].edate)
                        $('#sy_info_modal').modal()
                  })

                  function activate_sy(){
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/activatesy',
                              data:{
                                    syid:selected_id
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                                get_updated('early_enrollment_setup')
                                                get_updated('sy')
                                                $.ajax({
                                                      type:'GET',
                                                      url:school_setup.es_cloudurl+'/setup/schoolyear/activatesem',
                                                      data:{
                                                            semid:1
                                                      },
                                                      success:function(data) {
                                                            if(data[0].status == 0){
                                                                  Toast.fire({
                                                                        type: 'warning',
                                                                        title: data[0].message
                                                                  })
                                                            }else{
                                                                  Toast.fire({
                                                                        type: 'success',
                                                                        title: data[0].message
                                                                  })
                                                                  get_sem_list()
                                                            }
                                                      },error:function(){
                                                            Toast.fire({
                                                                  type: 'warning',
                                                                  title: 'Something went wrong!'
                                                            })
                                                      }
                                                })
                                          }

                                         
                                          get_sem_list()
                                          get_sy_list()
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function activate_sem(){
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/activatesem',
                              data:{
                                    semid:selected_sem
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          get_sem_list()
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }
                  
                  function get_sy_enrollment_info(){
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/enrollment',
                              data:{
                                    syid:selected_id
                              },
                              success:function(data) {
                                    all_enrollment_Info = data
                                    load_sy_enrollment_info_datatable()
                              }
                        })
                  }

                  function get_sy_list(){
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/list',
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No S.Y. found.'
                                          })
                                    }else{
                                          sy_list = data

                                          if(selected_id != null){
                                                var sy_info = sy_list.filter(x=>x.id == selected_id)
                                                var active_sy = sy_list.filter(x=>x.isactive == 1)
                                                if(sy_info[0].sort + 1 == active_sy[0].sort){
                                                      $('#activate_sy').removeAttr('hidden')
                                                }else{
                                                      $('#activate_sy').attr('hidden','hidden')
                                                }
                                          }

                                          if(sy_list.length > 0){
                                                // Toast.fire({
                                                //       type: 'warning',
                                                //       title: 'School year found.'
                                                // }) 
                                          }else{
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: 'No school year found. Please create school year.'
                                                })
                                          }
                                         

                                          load_sy_datatable()
                                    }
                              }
                        })
                  }

                  function get_sem_list(){
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/semester',
                              success:function(data) {
                                    $('#semester_list').empty()
                                    var sy_info = sy_list.filter(x=>x.id == selected_id)
                                    $.each(data,function(a,b){
                                          var button = ''
                                          var checked = '<i class="fas fa-times text-danger"></i>'
                                          if(b.isactive == 0){
                                                if(sy_info[0].isactive == 1){
                                                      button =  '<button class="btn btn-primary btn-sm" style="font-size:.6rem !important" data-id="'+b.id+'" id="activate_sem">Activate Semester</button>'
                                                }
                                          }else{
                                                checked = '<i class="fas fa-check text-success"></i>'
                                          }
                                          if(sy_info[0].isactive == 1){
                                                $('#semester_list').append( '<tr><td width="5%" class="align-middle text-center">'+checked+'</td><td width="45%" class="align-middle">'+b.semester+'</td><td width="50%" class="text-center">'+button+'</td></tr>')
                                          }else{
                                                $('#semester_list').append( '<tr><td width="5%" class="align-middle text-center">'+checked+'</td><td width="95%" class="align-middle">'+b.semester+'</td></tr>')
                                          }
                                    })
                                    
                                   
                              }
                        })
                  }

                  function sy_update(){
                      
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/update',
                              data:{
                                    id:selected_id,
                                    sdate:$('#sdate').val(),
                                    edate:$('#edate').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                                get_updated('sy')
                                          }
                                          get_sy_list()
                                    }
                              }
                        })
                  }

                  function sy_create(){
               
                        $.ajax({
                              type:'GET',
                              url:'/setup/schoolyear/create',
                              data:{
                                    sdate:$('#sdate').val(),
                                    edate:$('#edate').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                                get_last_index('sy')
                                          }
                                          get_sy_list()
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function load_sy_enrollment_info_datatable(){

                   

                        $("#enrollment_info_datatable").DataTable({
                              destroy: true,
                              data:all_enrollment_Info,
                              lengthChange : false,
                              searching: false,
                              bPaginate: false,
                              stateSave: true,
                              autoWidth: false,
                              bInfo: false,
                              columns: [
                                          { "data": 'sort' },
                                          { "data": "enrolled" },
                                          { "data": "promoted" },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).text(rowData.progname)
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    }
                              ]
                        });

                  }

                  var withNotEnedSy = false

                  function load_sy_datatable(){

                        withNotEnedSy = false

                        $("#sy_datatable").DataTable({
                              destroy: true,
                              data:sy_list,
                              lengthChange : false,
                              stateSave: true,
                              autoWidth: false,
                              order: [
                                          [ 1, "desc" ]
                                    ],
                              columns: [
                                          { "data": null },
                                          { "data": "sydesc" },
                                          { "data": "sdate" },
                                          { "data": "edate" },
                                          { "data": "enrolled" },
                                          { "data": null },
                                          { "data": null },
                                          // { "data": null },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                if(rowData.ended == 1){
                                                      $(td).addClass('bg-warning')
                                                }else{
                                                      if(rowData.isactive == 1){
                                                            $(td).addClass('bg-success')
                                                      }else{
                                                            $(td).addClass('bg-danger')
                                                      }
                                                }
                                               
                                               $(td).text(null)
                                          }
                                    },
                                   
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<button class="btn btn-primary btn-sm view_sy_info" style="font-size:.8rem !important" data-id="'+rowData.id+'">View Information</button>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                
                                                if( rowData.ended == 1){
                                                      var buttons = null
                                                }else{
                                                      var tempActiveSY = sy_list.filter(x=>x.isactive == 1)[0]
                                                      if( ( rowData.sort > tempActiveSY.sort  ) && rowData.ended == 0){
                                                            withNotEnedSy = true
                                                            var buttons = '<button class="btn btn-sm btn-danger end_sy" data-id="'+rowData.id+'">End</a>';
                                                      }else{
                                                            var buttons = '<a href="javascript:void(0)" class="update_sy" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                      }
                                                     
                                                }
                                             
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                              ]
                        });

                        var label_text = $($('#sy_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="sy_button_to_create_form"><i class="fa fa-plus"></i> Create School Year</button>'
                  
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


