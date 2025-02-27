
@extends('superadmin.layouts.app2')

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

@section('modalSection')
  
@endsection

@section('content')

@php
    $modules = DB::table('modules')->where('deleted',0)->get();
    foreach($modules as $item){
        $module_item = DB::table('modules_group')
                        ->where('moduleheader',$item->id)
                        ->where('deleted',0)
                        ->get();
        $item->group = $module_item;
    }
@endphp

<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
       
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Data Remover</li>
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
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12"  style="font-size:.8rem">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="module_datatable" width="100%" >
                                        <thead>
                                            <tr>
                                                    <th width="40%">Module</th>
                                                    <th width="10%" class="text-center">Status</th>
                                                    <th width="30%" class="text-center">Included Module</th>
                                                    <th width="10%"></th>
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

            var all_modules = @json($modules);
            load_college_datatable()

            $(document).on('click','.clear_data',function(){
                var id = $(this).attr('data-id')
                clear_data(id)
            })

            $('#filter_type').select2()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            
            
            $(document).on('change','#filter_type',function(){
                load_college_datatable()
            })

            

            function load_college_datatable(){

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
                                { "data": null },
                            ],
                    columnDefs: [
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
                                        $(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                        var group = ''
                                        length = rowData.group.length
                                        $.each(rowData.group,function(a,b){
                                            var temp_module_name = all_modules.filter(x=>x.id == b.module)
                                            group += temp_module_name[0].module_name
                                            if(length > a+1){
                                                group += ' / '
                                            }
                                        })
                                        $(td)[0].innerHTML =  group
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var buttons = '<button class="btn btn-sm btn-primary btn-block clear_data" hidden="hidden" data-id="'+rowData.id+'" style="font-size:.7rem !important">Clear Data</button>';
                                        $(td)[0].innerHTML =  buttons
                                        $(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                         
                                }
                            },
                            
                    ]
                    
                });

                get_data(temp_module)

            }

            function get_data(select_modules){
              
                $.each(select_modules,function(a,b){
                    var temp_id = b.id
                    $.ajax({
                        type:'GET',
                        url: '/truncanator/v2/check',
                        data:{
                            id:temp_id,
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                var text = ''
                                if(data[0].message == 'Empty'){
                                    text = '<span class="badge badge-success">Empty</span>'
                                }else if(data[0].message == 'With Data'){
                                    $('.clear_data[data-id="'+temp_id+'"]').removeAttr('hidden')
                                    text = '<span class="badge badge-primary">With Data</span>'
                                }
                                if( $('.status_column[data-id="'+temp_id+'"]').length > 0 ){
                                    $('.status_column[data-id="'+temp_id+'"]')[0].innerHTML = text
                                }
                            }else{
                                if(data[0].message == 'No Table'){
                                    var text = '<span class="badge badge-danger">No Table</span>'
                                }else{
                                    var text = '<span class="badge badge-danger">'+data[0].message+'</span>'
                                }
                                $('.status_column[data-id="'+temp_id+'"]')[0].innerHTML = text
                            }
                        },error:function(){
                            $('.status_column[data-id="'+temp_id+'"]').text('Something went wrong')
                        }
                    })
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
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            $('.clear_data[data-id="'+id+'"]').attr('hidden','hidden')
                            $('.status_column[data-id="'+id+'"]').empty()
                            text = '<span class="badge badge-success">Empty</span>'
                            $('.status_column[data-id="'+id+'"]')[0].innerHTML = text
                            // get_data()
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

        })
    </script>
    
@endsection

