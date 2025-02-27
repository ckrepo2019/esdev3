
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff;
            border-color: #006fe6;
            color: #fff;
            padding: 0 10px;
            margin-top: 0.31rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,.7);
            float: right;
            margin-left: 5px;
            margin-right: -2px;
        }
        .shadow {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                border: 0;
        }
    </style>
@endsection

@section('modalSection')
  
@endsection

@section('content')

@php
    $queryTables = DB::select(DB::raw('SHOW TABLES'));
    $temp_list = array();
    $tableKey = collect($queryTables[0])->keys()[0];
    foreach($queryTables as $key=>$item){
         array_push($temp_list , (object)[
             'text'=>$item->$tableKey,
             'id'=>$item->$tableKey
         ]);
    }
    $queryTables = $temp_list;
@endphp

<div class="modal fade" id="module_form_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title">Module Name</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                      <div class="row">
                            <div class="col-md-12 form-group">
                                  <label for="">Module Name</label>
                                  <input id="module_name" class="form-control form-control-sm">
                            </div>
                      </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="startup">
                                    <label for="startup">START UP
                                    </label>
                                </div>
                            </div>
                        </div>
                      <div class="row">
                            <div class="col-md-12">
                                  <button class="btn btn-sm btn-primary" id="module_form_button">Create</button>
                            </div>
                      </div>
                </div>
          </div>
    </div>
</div>   

<div class="modal fade" id="module_form_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title">Module Name</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                      <div class="row">
                            <div class="col-md-12 form-group">
                                  <label for="">Module Name</label>
                                  <input id="module_name" class="form-control form-control-sm">
                            </div>
                      </div>
                      <div class="row">
                            <div class="col-md-12">
                                  <button class="btn btn-sm btn-primary" id="module_form_button">Create</button>
                            </div>
                      </div>
                </div>
          </div>
    </div>
</div>   

<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            Clear Data Setup
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Clear Data Setup</li>
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
                                        <div class="col-md-4">
                                             <h5><i class="fa fa-filter"></i> Filter</h5> 
                                        </div>
                                        <div class="col-md-8">
                                              
                                        </div>
                                  </div>
                                  <div class="row">
                                        <div class="col-md-2 form-group mb-0" >
                                              <label for="">Filter</label>
                                              <select class="form-control select2" id="filter_type">
                                                   <option value="">All</option>
                                                   <option value="1">Module</option>
                                                   <option value="2">Implementation</option>
                                              </select>
                                        </div>
                                  </div>
                                  
                            </div>
                      </div>
                </div>
          </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12"  style="font-size:.8rem">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="module_datatable" width="100%" >
                                        <thead>
                                            <tr>
                                                    <th width="15%">Module</th>
                                                    <th width="6%" class="text-center p-0 align-middle">Status</th>
                                                    <th width="79%" class="text-center"></th>
                                                    {{-- <th width="42%" class="text-center">Tables</th> --}}
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

            var all_modules = []
            var selected_id = null
            var table = @json($queryTables);
            load_modules_datatable()
            get_modules()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $('#filter_type').select2()

            var first_load = true;

            function get_modules(){
                $.ajax({
                    type:'GET',
                    url: '/truncanator/module/list',
                    success:function(data) {
                        all_modules = data
                        load_modules_datatable()
                    },error:function(){
                        
                    }
                })
            }

            $(document).on('click','#button_to_module_form_modal',function(){
                $('#module_name').val('')
                $('#module_form_button').attr('data-p','create')
                $('#module_form_button').removeClass('btn-success')
                $('#module_form_button').addClass('btn-primary')
                $('#module_form_button').text('Create')
                $('#module_form_modal').modal()
                selected_id = null
            })

            $(document).on('click','.update_module',function(){
                var temp_id = $(this).attr('data-id')
                selected_id = temp_id
                var temp_info = all_modules.filter(x=>x.id==temp_id)
                $('#module_name').val(temp_info[0].module_name)
                $('#module_form_button').addClass('btn-success')
                $('#module_form_button').removeClass('btn-primary')
                $('#module_form_button').text('Update')
                $('#module_form_button').attr('data-p','update')
                $('#module_form_modal').modal()
            })

            $(document).on('click','#module_form_button',function(){
                if($(this).attr('data-p') == 'create'){
                    create_module()
                }else{
                    update_module(selected_id)
                }
              
            })

            $(document).on('change','.tables_modules',function(){
                if(!first_load){
                    var temp_moduleid = $(this).attr('data-id')
                    var temp_table = $(this).val()
                    add_table_to_module(temp_table,temp_moduleid)
                }
            })

            $(document).on('change','.inc_modules',function(){
                if(!first_load){
                    var temp_moduleid = $(this).attr('data-id')
                    var temp_module = $(this).val()
                    add_module_to_group(temp_module,temp_moduleid)
                }
            })

            function update_module(id){
                var modulename = $('#module_name').val()
                var temp_startup = 0
                if($('#startup').prop('checked') == true){
                    temp_startup = 1
                }
                $.ajax({
                    type:'GET',
                    url: '/truncanator/module/update',
                    data:{
                        id:id,
                        modulename:modulename,
                        startup:temp_startup
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            $('.update_module[data-id="'+selected_id+'"]').text(modulename)
                            var module_index = all_modules.findIndex(x=>x.id == id)
                            all_modules[module_index].module_name = modulename
                            all_modules[module_index].text = modulename
                            update_include_module_options()
                            
                        }else if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }
            

            function create_module(){
                
                var temp_startup = 0
                if($('#startup').prop('checked') == true){
                    temp_startup = 1
                }

                $.ajax({
                    type:'GET',
                    url: '/truncanator/module/create',
                    data:{
                        modulename:$('#module_name').val(),
                        startup:temp_startup
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            get_modules()
                        }else if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

            function add_table_to_module(temp_table,temp_moduleid){
                $.ajax({
                    type:'GET',
                    url: '/truncanator/module/table/create',
                    data:{
                        tables:temp_table,
                        moduleid:temp_moduleid
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            // get_modules()
                        }else if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

            
            function add_module_to_group(temp_module,temp_moduleid){
                $.ajax({
                    type:'GET',
                    url: '/truncanator/module/table/inserttogroup',
                    data:{
                        modules:temp_module,
                        moduleid:temp_moduleid
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })

                        }else if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

            $(document).on('change','#filter_type',function(){
                load_modules_datatable()
            })

            function load_modules_datatable(){

                first_load = true

                var filter_type = $('#filter_type').val()
                var temp_module = all_modules
                if(filter_type == 1){
                    temp_module = all_modules.filter(x=>x.startup == 0)
                }else if(filter_type == 2){
                    temp_module = all_modules.filter(x=>x.startup == 1)
                }else{
                    temp_module = all_modules
                }

                $("#module_datatable").DataTable({
                    destroy: true,
                    data:temp_module,
                    lengthChange : false,
                    paging: false,
                    columns: [
                                { "data": "module_name" },
                                { "data": null },
                                { "data": null },
                                // { "data": null }
                            ],
                    columnDefs: [
                             {
                                'targets': 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td)[0].innerHTML = '<a href="javascript:void(0)" class="update_module" data-id="'+rowData.id+'">'+rowData.module_name+'</a> '
                                        $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                        $(td).addClass('status_column')
                                        $(td).attr('data-id',rowData.id)
                                        $(td).text(null)
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    var group = '<div class="row mb-2"><div class="col-md-12 mb-2" ><label class="mb-0">Included Modules</label>'
                                    group +='<select class="select2 inc_modules" multiple="multiple" data-placeholder="Select a module" style="width: 100%;" data-id="'+rowData.id+'"></select>'
                                    group += '</div>'
                                    group += '<div class="col-md-12"><label class="mb-0">Tables</label>'
                                    group += '<select class="select2 tables_modules" multiple="multiple" data-placeholder="Select a table" style="width: 100%;" data-id="'+rowData.id+'"></select>'
                                    group += '</div></div>'
                                    $(td)[0].innerHTML =  group
                                }
                            },
                    ]
                    
                });

                var label_text = $($("#module_datatable_wrapper")[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="button_to_module_form_modal">Add Module</button><button class="btn btn-primary btn-sm ml-2" id="not_cleared_tables">Not Cleared Tables</button>'

                update_options()
                update_include_module_options()
                get_data()

            }

            function update_include_module_options(){
                first_load= true
               
                $.each(all_modules,function(a,b){
                    var module_array = []
                    $.each(b.group,function(c,d){
                        module_array.push(d.module)
                    })
                    $('.inc_modules[data-id="'+b.id+'"]').empty()
                    $('.inc_modules[data-id="'+b.id+'"]').select2({
                        data:all_modules
                    })
                    $('.inc_modules[data-id="'+b.id+'"]').val(module_array).change()
                })
                first_load= false
            }

            function update_options(){
                first_load= true
                $.each(all_modules,function(a,b){
                    $('.tables_modules[data-id="'+b.id+'"]').empty()
                    $('.tables_modules[data-id="'+b.id+'"]').select2({
                        data:table
                    })
                    var table_array = []
                    $.each(b.tables,function(e,f){
                        table_array.push(f.moduletable)
                    })
                    $('.tables_modules[data-id="'+b.id+'"]').val(table_array).change()
                })
                first_load= false
            }

            function get_data(){
                $.each(all_modules,function(a,b){
                    var temp_id = b.id
                    // get_data_info(temp_id)
                   
                })
            }

            function get_data_info(temp_id){
                $.ajax({
                    type:'GET',
                    url: '/truncanator/v2/check',
                    data:{
                        id:temp_id,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            $('.status_column[data-id="'+temp_id+'"]').text(data[0].message)
                        }else{
                            $('.status_column[data-id="'+temp_id+'"]').text(data[0].message)
                        }
                    },error:function(){
                        $('.status_column[data-id="'+temp_id+'"]').text('Something went wrong')
                    }
                })
            }

            function clear_data(id){

                $.ajax({
                    type:'GET',
                    url: '/truncanator/v2/clear',
                    data:{
                        id:id,
                    },
                    success:function(data) {
                       
                    }
                })
            }

        })
    </script>
   
    
@endsection

