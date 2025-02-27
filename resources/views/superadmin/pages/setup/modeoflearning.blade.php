
@php
      if(!Auth::check()){
            header("Location: " . URL::to('/'), true, 302);
            Session::flush();
            exit();
      }

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      $refid = $check_refid->refid;

      $extend = '';
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
	  }else if(auth()->user()->type == 3){
        $extend = 'registrar.layouts.app';
      }
      if($extend == ''){
            header("Location: " . URL::to('/'), true, 302);
            exit();
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
      $schoolinfo = DB::table('schoolinfo')->first(); 
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $active_sy = DB::table('sy')->where('isactive',1)->first()->id;

      if(auth()->user()->type == 17 || auth()->user()->type == 4 || auth()->user()->type == 15 || Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 6 || Session::get('currentPortal') == 6 || $refid == 28){

            $acadprog = DB::table('academicprogram')
                                    ->select(
                                          'academicprogram.*',
                                          'progname as text'
                                    )
                                    ->get();

      }elseif(auth()->user()->type == 14 || Session::get('currentPortal') == 14){
      $acadprog = DB::table('academicprogram')
                        ->where('id',6)
                        ->select(
                                    'academicprogram.*',
                                    'progname as text'
                              )
                        ->get();
      }
      else{

            $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                              ->select('id')
                              ->first()
                              ->id;

            if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

                  $acadprog = DB::table('academicprogram')
                                    ->where('principalid',$teacherid)
                                    ->select(
                                          'academicprogram.*',
                                          'progname as text'
                                    )
                                    ->get();

            }else{

                  $acadprog = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid)
                              ->where('syid',$active_sy)
                              ->whereIn('acadprogutype',[3,8])
                              ->join('academicprogram',function($join){
                                    $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                              })
                              ->where('deleted',0)
                              ->select(
                                    'acadprogid as id',
                                    'progname as text',
                                    'academicprogram.acadprogcode'
                              )
                              ->distinct('acadprogid')
                              ->get();
            }
      }


      $acadprog_list = array();
            foreach($acadprog as $item){
            array_push($acadprog_list,$item->id);
      }

      $gradelevel = DB::table('gradelevel')
                  ->where('deleted',0)
                  ->whereIn('acadprogid',$acadprog_list)
                  ->orderBy('sortid')
                  ->select(
                        'id',
                        'levelname',
                        'levelname as text',
                        'acadprogid'
                  )
                  ->get(); 

@endphp




<div class="modal fade" id="modeoflearning_form" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Mode of Learning Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row form-group">
                              <div class="col-md-12">
                                    <label for="">Mode Of Learning Description</label>
                                    <input name="" id="input_mol_description" class="form-control form-control-sm">
                              </div>
                        </div>
                        <div class="row form-group" >
                              <div class="col-md-12">
                                    <label for="">Grade Level</label>
                                    <select id="input_gradelevel" class="form-control form-control-sm select2" multiple></select>
                                    <p style="font-size:.9rem !important" class="mb-0 text-danger">*Leave grade level empty if mode of learning is applicable to all grade level.</p>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isActive" checked>
                                        <label for="isActive">Active
                                        </label>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="create_modeoflearning">Create</button>
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
                        <h1>Mode of Learning</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Mode of Learning</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-2">
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
                                                      <div class="col-md-8 ">
                                                      </div>
                                                      <div class="col-md-2 text-center">
                                                            <label for="">Status</label>
                                                            <br>
                                                            <input id="mol_status" type="checkbox" name="my-checkbox" checked data-bootstrap-switch>
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
                       <div class="card shadow">
                             <div class="card-body">
                                   <div class="row">
                                         <div class="col-md-12">
                                               <table class="table table-sm  table-striped" id="modeoflearning_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="29%" >Mode of Learning Description</th>
                                                                  <th width="10%">Registered</th>
                                                                  <th width="40%">Grade Level</th>
                                                                  <th width="10%">Status</th>
                                                                  <th width="3%"></th>
                                                                  <th width="3%"></th>
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
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
      <script>
            var school_setup = @json($schoolinfo);
            var userid = @json(auth()->user()->id)
    
            function get_last_index(tablename){
                $.ajax({
                        type:'GET',
                        url: '/monitoring/tablecount',
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
                        url: school_setup.es_cloudurl+'/monitoring/table/data',
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
                        if(tablename == 'modeoflearning'){
                              Toast.fire({
                                    type: 'success',
                                    title: 'Mode of Learning Created!'
                              })
                        }
                        get_modeoflearning()
                        return false;
                }
                var b = createdata[0]
                $.ajax({
                        type:'GET',
                        url: '/synchornization/insert',
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
                var date = moment().subtract(2, 'minute').format('YYYY-MM-DD HH:mm:ss');
                $.ajax({
                    type:'GET',
                    url: school_setup.es_cloudurl+'/monitoring/table/data/updated',
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
                        if(tablename == 'modeoflearning'){
                              Toast.fire({
                                    type: 'success',
                                    title: 'Mode of Learning Updated!'
                              })
                        }
                       
                        get_modeoflearning()
                        return false
                  }
    
                var b = updated_data[0]
    
                $.ajax({
                    type:'GET',
                    url: '/synchornization/update',
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
    
            //get deleted
            function get_deleted(tablename){
                var date = moment().subtract(1, 'minute').format('YYYY-MM-DD HH:mm:ss');
                $.ajax({
                    type:'GET',
                    url: school_setup.es_cloudurl+'/monitoring/table/data/deleted',
                    data:{
                            tablename: tablename,
                            date: date
                    },
                    success:function(data) {
                        process_deleted(tablename,data)
                    }
                })
            }
    
            function process_deleted(tablename , deleted_data){
                if (deleted_data.length == 0){
                        if(tablename == 'modeoflearning'){
                              Toast.fire({
                                    type: 'success',
                                    title: 'Mode of Learning Deleted!'
                              })
                        }
                        get_modeoflearning()
                        return false
                }
                var b = deleted_data[0]
                $.ajax({
                    type:'GET',
                    url: '/synchornization/delete',
                    data:{
                        tablename: tablename,
                        data:b
                    },
                    success:function(data) {
                        deleted_data = deleted_data.filter(x=>x.id != b.id)
                        process_deleted(tablename,deleted_data)
                    },
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

            var all_modeoflearning = []
            var userid = @json(auth()->user()->id);

            get_modeoflearning(true)          
            function get_modeoflearning(prompt=true){
                  $.ajax({
                        type:'GET',
                        url: '/setup/modeoflearning/list',
                        data:{
                              syid:$('#filter_sy').val(),
                              withrecord:true
                        },
                        success:function(data) {
                              if(school_setup.withMOL == 1){
                                    if(prompt){
                                          if(data.length > 0 ){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: 'Mode of learning found!'
                                                })
                                          }else{
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: 'No mode of learning found, Create mode of learning.'
                                                })
                                          }
                                    }
                              }
                              all_modeoflearning = data
                              load_modeoflearning()
                        }
                  })
            }

            function load_modeoflearning(){

                  $("#modeoflearning_table").DataTable({
                        destroy: true,
                        data:all_modeoflearning,
                        lengthChange: false,
                        autoWidth:false,
                        columns: [
                              { "data": "description" },
                              { "data": "registered"  },
                              { "data": null  },
                              { "data": null  },
                              { "data": null },
                              { "data": null },
                        ],
                        columnDefs: [
                                         
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
                                                      if(rowData.all){
                                                            var text = 'All Grade Level'
                                                      }else{
                                                            var text = ''
                                                            $.each(rowData.gradelevel,function(a,b){
                                                                  text+='<span class="badge badge-primary mr-2">'+b.levelname+'</span>'
                                                            })
                                                      }
                                                     
                                                      
                                                      $(td)[0].innerHTML = text
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.isactive == 1){
                                                            $(td)[0].innerHTML = '<span class="badge badge-success">Active</span>'
                                                      }else{
                                                            $(td)[0].innerHTML = '<span class="badge badge-danger">Not Active</span>'
                                                      }
                                                   
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 4,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var buttons = '<a href="#" class="edit_modeoflearning" data-id="'+rowData.id+'"><i class="far fa-edit text-primary"></i></a>';
                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 5,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var buttons = '<a href="#" class="delete_modeoflearning" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          
                                    ],
                                    initComplete:function( settings, json){
                                          $('.edit_modeoflearning').attr('data-toggle', 'popover').attr('data-html', 'true');
                                          $('.edit_modeoflearning').popover({
                                                trigger: 'hover',
                                                offset: '0 5',
                                                content: `<span>Update mode of learning</span>`,
                                          });

                                          $('.delete_modeoflearning').attr('data-toggle', 'popover').attr('data-html', 'true');
                                          $('.delete_modeoflearning').popover({
                                                trigger: 'hover',
                                                offset: '0 5',
                                                content: `<span>Delete mode of learning</span>`,
                                          });
                                    }
                  });

                  var label_text = $($("#modeoflearning_table_wrapper")[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="create_modeoflearning_to_modal" style="font-size: .8rem !important"><i class="fas fa-plus"></i> Create Mode of Learning</button>'

            }

      </script>
      <script>
            $(document).ready(function(){


                  $('.select2').select2()
                  $("input[data-bootstrap-switch]").each(function(){
                        $(this).bootstrapSwitch('state', $(this).prop('checked'));
                  });

             
                  if(school_setup.withMOL == 1){
                        $('#mol_status').bootstrapSwitch('state', true);
                  }else{
                        $('#mol_status').bootstrapSwitch('state', false);
                        Toast.fire({
                              type: 'warning',
                              title: "Mode of learning is disabled, please turn on status!"
                        })
                  }

                  var all_gradelevel = @json($gradelevel)

                  $("#input_gradelevel").select2({
                        data: all_gradelevel,
                        // allowClear: true,
                        theme: 'bootstrap4',
                        placeholder: "Grade Level",
                  })

                  $(document).on('switchChange.bootstrapSwitch','#mol_status',function(){
                        var status = 0;
                        if($(this).prop('checked')){
                              status = 1
                        }
                        $.ajax({
                              url: '/setup/modeoflearning/schoolinfo/activate',
                              type: 'GET',
                              data:{
                                    status:status
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
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

                        if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                              $.ajax({
                                    url: school_setup.es_cloudurl+'/setup/modeoflearning/schoolinfo/activate',
                                    type: 'GET',
                                    data:{
                                          status:status
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].message
                                                })
                                          }
                                    }
                              })
                        }
                  })



                  $(document).on('change','#filter_sy',function(){
                        get_modeoflearning(true)          
                  })

                  $(document).on('click','#create_modeoflearning_to_modal',function(){
                        if($('#update_modeoflearning').length > 0){
                              $('#update_paymentoption').text('Create')
                              $('#update_paymentoption').removeClass('btn-success')
                              $('#update_paymentoption').addClass('btn-primary')
                              $('#update_paymentoption').attr('id','create_paymentoption')
                        }
                        $('#input_mol_description').val("")
                        $('#input_gradelevel').val("").change()
                        $('#modeoflearning_form').modal()
                  })

                  var selected_modeoflearning = null
                  $(document).on('click','.edit_modeoflearning',function(){
                        selected_modeoflearning = $(this).attr('data-id')
                        var temp_modeoflearning = all_modeoflearning.filter(x=>x.id == selected_modeoflearning)

                        var temp_lvl = []
                        $.each(temp_modeoflearning[0].gradelevel,function(a,b){
                              temp_lvl.push(b.levelid)
                        })

                        if(!temp_modeoflearning[0].all){
                              $('#input_gradelevel').val(temp_lvl).change()
                        }else{
                              $('#input_gradelevel').val("").change()
                        }

                        if(temp_modeoflearning[0].isactive == 1){
                              $('#isActive').prop('checked',true)
                        }else{
                              $('#isActive').prop('checked',false)
                        }
                  
                        if(temp_modeoflearning[0].registered == 0){
                              $('#input_mol_description').removeAttr('disabled')
                        }else{
                              $('#input_mol_description').attr('disabled','disabled')
                        }

                        $('#input_mol_description').val(temp_modeoflearning[0].description)
                        $('#create_modeoflearning').text('Update')
                        $('#create_modeoflearning').removeClass('btn-primary')
                        $('#create_modeoflearning').addClass('btn-success')
                        $('#create_modeoflearning').attr('id','update_modeoflearning')
                        $('#modeoflearning_form').modal()
                  })

                  $(document).on('click','.delete_modeoflearning',function(){
                        selected_modeoflearning = $(this).attr('data-id')
                        Swal.fire({
                              title: 'Do you want to remove mode of learning?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    var url = '/setup/modeoflearning/delete'
                                    if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                          url = school_setup.es_cloudurl+'/setup/modeoflearning/delete'
                                    }
                                    $.ajax({
                                          url: url,
                                          type: 'GET',
                                          data:{
                                                id:selected_modeoflearning,
                                                userid:userid
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                                            get_deleted('modeoflearning')
                                                            get_deleted('modeoflearning_lvl')
                                                      }else{
                                                            Toast.fire({
                                                                  type: 'success',
                                                                  title: 'Mode of Learning Deleted!'
                                                            })
                                                            get_modeoflearning(false)
                                                      }
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  })

                  $(document).on('click','#update_modeoflearning',function(){

                        if($('#input_mol_description').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Description is empty!'
                              })
                              return false
                        }

                        var url = '/setup/modeoflearning/update'
                        var isActive = 1

                        if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                              url = school_setup.es_cloudurl+'/setup/modeoflearning/update'
                        }

                        if($('#isActive').prop('checked')==false){
                              isActive = 0
                        }

                        $.ajax({
                              type:'GET',
                              url: url,
                              data:{
                                    id:selected_modeoflearning,
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#input_gradelevel').val(),
                                    description:$('#input_mol_description').val(),
                                    active:isActive,
                                    userid:userid
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                                get_updated('modeoflearning')
                                                get_last_index('modeoflearning_lvl')
                                                get_deleted('modeoflearning_lvl')
                                          }else{
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Mode of Learning Updated!'
                                                })
                                                get_modeoflearning(false)
                                          }
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })

                  })
                  
                  $(document).on('click','#create_modeoflearning',function(){

                        if($('#input_mol_description').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Description is empty!'
                              })
                              return false
                        }

                        var isActive = 1
                        var url = '/setup/modeoflearning/create'

                        if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                              url = school_setup.es_cloudurl+'/setup/modeoflearning/create'
                        }
                        if($('#isActive').prop('checked')==false){
                              isActive = 0
                        }

                        $.ajax({
                              type:'GET',
                              url: url,
                              data:{
                                    syid:$('#filter_sy').val(),
                                    gradelevel:$('#input_gradelevel').val(),
                                    description:$('#input_mol_description').val(),
                                    active:isActive,
                                    userid:userid
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                                get_last_index('modeoflearning')
                                                get_last_index('modeoflearning_lvl')
                                          }else{
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Mode of Learning Created!'
                                                })
                                                get_modeoflearning(false)
                                          }
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })
                  })

            })

      </script>
@endsection


