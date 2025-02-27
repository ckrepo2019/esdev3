@php
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

    if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
	}else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 4){
         $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 15){
         $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(Session::get('currentPortal') == 10){
        $extend = 'hr.layouts.app';
    }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(auth()->user()->type == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(auth()->user()->type == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(auth()->user()->type == 14){
        $extend = 'deanportal.layouts.app2';
    }else if(auth()->user()->type == 3){
        $extend = 'registrar.layouts.app';
    }else if(auth()->user()->type == 1){
        $extend = 'teacher.layouts.app';
    }else if(auth()->user()->type == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(auth()->user()->type == 4){
        $extend = 'finance.layouts.app';
    }else if(auth()->user()->type == 15 ){
        $extend = 'finance.layouts.app';
    }else if(auth()->user()->type == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(auth()->user()->type == 10){
        $extend = 'hr.layouts.app';
    }else{
        if(isset($check_refid->refid)){
            if($check_refid->refid == 26){
                
            }
        }
        $extend = 'general.defaultportal.layouts.app';
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
     
      </style>
@endsection


@section('content')

@php
      $schoolinfo = DB::table('schoolinfo')->first();
      $grades_table = ['grades','gradesdetail','subjects','sh_subjects','subject_plot'];
      $sections_table = ['sections','sectiondetail'];
      $attendance_table = ['studattendance','studattendance_setup','studentsubjectattendance'];
      $observed_values = ['grading_system','grading_system_detail','grading_system_grades_cv','grading_system_ratingvalue'];
      $student_information = ['studinfo'];
      $student_preregistration = ['student_pregistration'];
      $onlinepayments = ['onlinepayments','onlinepaymentdetails','studinfo'];

      $set_1 = array(
                  (object)['modulename'=>'Basic Ed. Grades','tables'=>$grades_table],
                  (object)['modulename'=>'Sections','tables'=>$sections_table],
                  (object)['modulename'=>'Student Attendance','tables'=>$attendance_table],
                  (object)['modulename'=>'Observed Values','tables'=>$observed_values],
                  (object)['modulename'=>'Student Information','tables'=>$student_information],
                  (object)['modulename'=>'Student Preregistration','tables'=>$student_preregistration],
                  );

      $set_2 = array(
            (object)['modulename'=>'Online Payments','tables'=>$student_preregistration],
      );

      $all_modules = array(
                  (object)['modulename'=>'Online Payments','tables'=>$student_preregistration],
                  (object)['modulename'=>'Basic Ed. Grades','tables'=>$grades_table],
                  (object)['modulename'=>'Sections','tables'=>$sections_table],
                  (object)['modulename'=>'Student Attendance','tables'=>$attendance_table],
                  (object)['modulename'=>'Observed Values','tables'=>$observed_values],
                  (object)['modulename'=>'Student Information','tables'=>$student_information],
                  (object)['modulename'=>'Student Preregistration','tables'=>$student_preregistration],
            );
      

      if(Session::get('currentPortal') == 3){
           $load_module = $set_1;
      }elseif(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15){
            $load_module = $set_2;
      }
      else if(auth()->user()->type == 3){
           $load_module = $set_1;
      }elseif(auth()->user()->type == 4 || auth()->user()->type == 15){
            $load_module = $set_2;
      }else if(auth()->user()->type == 17){
            $load_module = $all_modules;
      }
      else{
            $load_module = array();
      }
@endphp


<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" id="modal_1_title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:12px !important">
                        <table class="table table-sm display table-striped" id="student_list" width="100%" ">
                              <thead>
                                    <tr>
                                          <th width="100%">Student Name</th>
                                    </tr>
                              </thead>
                        </table>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Data from Online</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Data from Online</li>
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
                             <div class="card-body ">
                                   <div class="row">
                                         <div class="col-md-12">
                                                <table class="table table-sm table-striped" id="sync_module_table">
                                                      <thead>
                                                            <tr>
                                                                  <th width="55%">Module</th>
                                                                  <th width="15%">New Data</th>
                                                                  <th width="15%">Updated Data</th>
                                                                  <th width="15%">Deleted Data</th>
                                                                  {{-- <th width="15%">Last Date Sync</th> --}}
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

            var schoolinfo = @json($schoolinfo);
            var local_tables_list = []
            var all_create = []
            var all_update = []
            var local_updated = []
            var all_delete = []

            function get_created(tablelist,created,modulename){
                  if(tablelist.length == 0){
                        $('td[data-process="created"][data-id="'+modulename+'"]')[0].innerHTML = created.length
                        all_create.push({
                              module : modulename,
                              data: created
                        })
                        return false
                  }
                  var b  = tablelist[0]
                  get_last_index(b,tablelist,created,modulename)
            }

            function get_last_index(tablename,tablelist,created,modulename){
                  var check = local_tables_list.filter(x=>x.tablename == tablename)
                  if(check.length > 0 ){
                        if(check[0].create == 1){
                              $.ajax({
                                    type:'GET',
                                    url: '/monitoring/tablecount',
                                    data:{
                                          tablename: tablename
                                    },
                                    success:function(data) {
                                          lastindex = data[0].lastindex
                                          update_local_table_display(tablename,lastindex,tablelist,created,modulename)
                                    },
                                    
                              })
                        }
                  }else{
                        tablelist = tablelist.filter(x=>x != tablename)
                        get_created(tablelist,created,modulename)
                  }
            }

            function update_local_table_display(tablename,lastindex,tablelist,created,modulename){
                  
                  $.ajax({
                        type:'GET',
                        url: schoolinfo.es_cloudurl+'/monitoring/table/data',
                        data:{
                              tablename:tablename,
                              tableindex:lastindex
                        },
                        success:function(data) {
                              $.each(data,function(a,b){
                                    b.tablename = tablename
                                    created.push(b)
                              })
                              tablelist = tablelist.filter(x=>x != tablename)
                              get_created(tablelist,created,modulename)
                        },
                  })
            }

            function process_create(process_count,createdata,modulename){
                  if(createdata.length == 0){
                        return false;
                  }
                  var temp_data = createdata[0]
                  var tablename = temp_data.tablename

                  const b = Object.keys(temp_data).reduce((object, key) => {
                  if (key !== 'tablename') {
                        object[key] = temp_data[key]
                  }
                        return object
                  }, {})
            
                  $.ajax({
                        type:'GET',
                        url: '/synchornization/insert',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              process_count += 1
                              $('span[data-process="created"][data-id="'+modulename+'"]').text(process_count)
                              createdata = createdata.splice(1)
                              process_create(process_count,createdata,modulename)
                        },
                        error:function(){
                              process_count += 1
                              createdata = createdata.filter(x=>x.id != b.id)
                              process_create(process_count,createdata)
                        }
                  })
            }

            //get_updated

            function get_updated(tablelist,updated,modulename){
                  if(tablelist.length == 0){
                        $('td[data-process="updated"][data-id="'+modulename+'"]')[0].innerHTML = updated.length
                        all_update.push({
                              module : modulename,
                              data: updated
                        })
                        return false
                  }
                  var b  = tablelist[0]

                  var check = local_tables_list.filter(x=>x.tablename == b)
                  if(check.length > 0 ){
                        if(check[0].update == 1){
                              get_local_updated(b,tablelist,updated,modulename)
                        }else{
                              tablelist = tablelist.filter(x=>x != b)
                              get_updated(tablelist,updated,modulename)
                        }
                  }else{
                        tablelist = tablelist.filter(x=>x != b)
                        get_updated(tablelist,updated,modulename)
                  }
            }


            function get_updated_data(tablename,tablelist,updated,modulename,localdata){
                  var date = $('#sync_date').val()+' 00:00:00';
            
                  $.ajax({
                        type:'GET',
                        url: schoolinfo.es_cloudurl+'/monitoring/table/data/updated',
                        data:{
                                    tablename: tablename,
                                    date: date
                        },
                        success:function(data) {
                              $.each(data,function(a,b){
                                    var check = localdata.filter(x=>x.id == b.id && x.updateddatetime == b.updateddatetime)
                                    if(check.length == 0){
                                          b.tablename = tablename
                                          updated.push(b)
                                    }
                              })
                              tablelist = tablelist.filter(x=>x != tablename)
                              get_updated(tablelist,updated,modulename)
                        }
                  })
            }

            function process_update(process_count,updateddata,modulename){

                  if (updateddata.length == 0){
                        return false
                  }

                  var temp_data = updateddata[0]
                  var tablename = temp_data.tablename

                  const b = Object.keys(temp_data).reduce((object, key) => {
                  if (key !== 'tablename') {
                        object[key] = temp_data[key]
                  }
                        return object
                  }, {})
            
                  $.ajax({
                        type:'GET',
                        url: '/synchornization/update',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              process_count += 1
                              $('span[data-process="updated"][data-id="'+modulename+'"]').text(process_count)
                              updateddata = updateddata.splice(1)
                              process_update(process_count,updateddata,modulename)
                        },
                  })
            }

            //get deleted
            function get_deleted(tablelist,deleted,modulename){
                  if(tablelist.length == 0){
                        $('td[data-process="deleted"][data-id="'+modulename+'"]')[0].innerHTML = deleted.length
                        all_delete.push({
                              module : modulename,
                              data: deleted
                        })
                        return false
                  }
                  var b  = tablelist[0]
                   var check = local_tables_list.filter(x=>x.tablename == b)
                  if(check.length > 0 ){
                        if(check[0].deleted == 1){
                              get_local_deleted(b,tablelist,deleted,modulename)
                        }else{
                              tablelist = tablelist.filter(x=>x != b)
                              get_deleted(tablelist,deleted,modulename)
                        }
                  }else{
                        tablelist = tablelist.filter(x=>x != b)
                        get_deleted(tablelist,deleted,modulename)
                  }
            }


            function get_deleted_data(tablename,tablelist,deleted,modulename,localdata){
                  var date = $('#sync_date').val()+' 00:00:00';
                  $.ajax({
                        type:'GET',
                        url: schoolinfo.es_cloudurl+'/monitoring/table/data/deleted',
                        data:{
                                    tablename: tablename,
                                    date: date
                        },
                        success:function(data) {
                              $.each(data,function(a,b){
                                    var check = localdata.filter(x=>x.id == b.id && x.deleteddatetime == b.deleteddatetime)
                                    if(check.length == 0){
                                          b.tablename = tablename
                                          deleted.push(b)
                                    }
                              })
                              tablelist = tablelist.filter(x=>x != tablename)
                              get_deleted(tablelist,deleted,modulename)
                        }
                  })
            }

            function process_delete(process_count,deleteddata,modulename){

                  if (deleteddata.length == 0){
                        return false
                  }

                  var temp_data = deleteddata[0]
                  var tablename = temp_data.tablename

                  const b = Object.keys(temp_data).reduce((object, key) => {
                  if (key !== 'tablename') {
                        object[key] = temp_data[key]
                  }
                        return object
                  }, {})

                  $.ajax({
                        type:'GET',
                        url: '/synchornization/delete',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              process_count += 1
                              $('span[data-process="deleted"][data-id="'+modulename+'"]').text(process_count)
                              deleteddata = deleteddata.splice(1)
                              process_delete(process_count,deleteddata,modulename)
                        },
                  })
            }

      </script>

      <script>


            
                  function get_local_updated(tablename,tablelist,updated,modulename){          
                        var date = $('#sync_date').val()+' 00:00:00';
                        $.ajax({
                              type:'GET',
                              url: '/localclouddata/get/updated',
                              data:{
                                          tablename: tablename,
                                          date: date
                              },
                              success:function(data) {
                                    get_updated_data(tablename,tablelist,updated,modulename,data)
                              }
                        })
                  }

                  function get_local_deleted(tablename,tablelist,deleted,modulename){          
                        var date = $('#sync_date').val()+' 00:00:00';
                        $.ajax({
                              type:'GET',
                              url: '/localclouddata/get/deleted',
                              data:{
                                          tablename: tablename,
                                          date: date
                              },
                              success:function(data) {
                                    get_deleted_data(tablename,tablelist,deleted,modulename,data)
                              }
                        })
                  }

            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  function get_local_tables(){
                        $.ajax({
					type:'GET',
					url:  '/localclouddata/cloudtables',
					success:function(data) {
                                    local_tables_list = data
                                    load_data()
					}
				})
                  }


                  $(document).on('click','.reload_module',function(){
                        var modulename = $(this).attr('data-id')
                        var temp_created = all_create.filter(x=>x.module == modulename )
                        var temp_updated = all_update.filter(x=>x.module == modulename )
                        var temp_deleted = all_delete.filter(x=>x.module == modulename )

                        if(temp_created[0].data.length > 0 ){
                              $('td[data-process="created"][data-id="'+modulename+'"]')[0].innerHTML = '<span data-id="'+modulename+'" data-process="created">0</span> / ' + temp_created[0].data.length
                              process_create(0,temp_created[0].data,modulename)
                        }

                        if(temp_updated[0].data.length > 0){
                              $('td[data-process="updated"][data-id="'+modulename+'"]')[0].innerHTML = '<span data-id="'+modulename+'" data-process="updated">0</span> / ' + temp_updated[0].data.length
                              process_update(0,temp_updated[0].data,modulename)
                        }
                        
                        if(temp_deleted[0].data.length > 0){
                              $('td[data-process="deleted"][data-id="'+modulename+'"]')[0].innerHTML = '<span data-id="'+modulename+'" data-process="deleted">0</span> / ' + temp_deleted[0].data.length
                              process_delete(0,temp_deleted[0].data,modulename)
                        }
                       
                  })

                  var registrar_module = @json($load_module);
                  
                  function load_data(){
                        if(registrar_module.length > 0){
                              $.each(registrar_module,function(a,b){
                                    get_created(b.tables,[],b.modulename)
                                    get_updated(b.tables,[],b.modulename)
                                    get_deleted(b.tables,[],b.modulename)
                              })
                        }
                  }

                  $(document).on('change','#sync_date',function(){
                        load_datatable()
                  })

                  load_datatable()
                  function load_datatable(){
                        $("#sync_module_table").DataTable({
                                    destroy: true,
                                    data:registrar_module,
                                    lengthChange: false,
                                    autoWidth:false,
                                    columns: [
                                          { "data": "modulename" },
                                          { "data": null },
                                          { "data": null },
                                          { "data": null },
                                          // { "data": null },
                                    ],
                                    columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td)[0].innerHTML = '<a href="javascript:void(0)" class="reload_module" data-id="'+rowData.modulename+'">'+rowData.modulename+'</a>'
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).attr('data-tablename',rowData.tablename)
                                                      $(td).attr('data-process','created')
                                                      $(td).text('Fetching new data...') 
                                                      $(td).attr('data-type','cloudtolocal')
                                                      $(td).attr('data-id',rowData.modulename)
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).attr('data-tablename',rowData.tablename)
                                                      $(td).attr('data-process','updated')
                                                      $(td).text('Fetching new data...') 
                                                      $(td).attr('data-type','cloudtolocal')
                                                      $(td).attr('data-id',rowData.modulename)
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).attr('data-tablename',rowData.tablename)
                                                      $(td).attr('data-process','deleted')
                                                      $(td).text('Fetching new data...') 
                                                      $(td).attr('data-type','cloudtolocal')
                                                      $(td).attr('data-id',rowData.modulename)
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          // {
                                          //       'targets': 4,
                                          //       'orderable': true, 
                                          //       'createdCell':  function (td, cellData, rowData, row, col) {
                                          //             $(td).text(null)
                                          //       }
                                          // }
                                    ],
                              });

                              var label_text = $($("#sync_module_table_wrapper")[0].children[0])[0].children[0]
                              $(label_text)[0].innerHTML = '<div class="row"><div clas="col-md-3"><input type="date" class="form-control form-control-sm" id="sync_date"></div></div>'


                              
                        var date = moment().subtract(2, 'days').format('YYYY-MM-DD');
                        $('#sync_date').val(date)

                        get_local_tables()

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

               
            })
      </script>

@endsection


