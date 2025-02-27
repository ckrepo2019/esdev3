
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
    </style>
@endsection

@section('content')



    <div class="modal fade" id="url_setup_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">URL Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">User Type</label>
                            <input type="text" id="input_setup_usertype" class="form-control" disabled>
                        </div>
                        <div class="col-md-12 form-group">
                             <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="input_setup_type" >
                                    <label for="input_setup_type">Header</label>
                            </div> 
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">URL</label>
                            <select name="" id="input_setup_url" class="select2 form-control">
                                <option value="">URL</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Header Description</label>
                            <input type="text" id="input_setup_header_desc" class="form-control" disabled>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Sort</label>
                            <input type="text" id="input_setup_sort" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Group</label>
                            <select name="" id="input_setup_group" class="select2 form-control">
                                <option value="">Group</option>
                            </select>
                        </div>
                          <div class="col-md-12 form-group">
                             <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="input_setup_active" >
                                    <label for="input_setup_active">Active</label>
                            </div> 
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary url_setup_form_button">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   


    <div class="modal fade" id="url_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">URL Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Description</label>
                            <input type="text" id="input_desc" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">URL</label>
                            <input type="text" id="input_url" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="icheck-primary d-inline pt-2">
                                   <input type="checkbox" id="input_active" >
                                   <label for="input_active">Active</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary url_form_button">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <div class="modal fade" id="sidenav_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">URL View</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body " >
                    
                  
                   
                            <nav class="mt-2" >
                                <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false" id="nav_holder">
                                </ul>
                            </nav>
                   
                   
                   
                </div>
            </div>
        </div>
    </div>   

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>URL</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">URL</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-container"></div>
                </div>
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="">User Type</label>
                                    <select name="" class="select2 form-control" id="filter_usertype">
                                        <option value="">User Type</option>
                                    </select>
                                </div>
                                <div class="col-md-8 text-right">
                                    <button class="btn btn-primary " id="url_setup_create_to_modal" disabled>Add Setup</button>
                                    <button class="btn btn-primary " id="view_sidenav" disabled>View Side Nav</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" table-responsive tableFixHead mt-1 " style="height: 400px; " >
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="8%">Sort</th>
                                                    <th width="32%">Description</th>
                                                    <th width="37%">URL</th>
                                                    <th width="8%" class="text-center">Active</th>
                                                    <th width="8%"></th>
                                                    <th width="8%"></th>
                                                </tr>
                                            </thead>
                                            <tbody  id="url_setup_datatable">
                                                <tr>
                                                    <td colspan="6" class="text-center">Select User Type</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered" id="url_datatable">
                                        <thead>
                                            <tr>
                                                <th  width="25%">URL</th>
                                                <th  width="25%">Description</th>
                                                <th  width="25%">Portal</th>
                                                <th width="8%">Active</th>
                                                <th width="8%"></th>
                                                <th width="8%"></th>
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

    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
   

    <script>
        var current_process = null
        var selected_id = null
        var all_url = []
        $(document).on('click','#url_create_to_modal',function(){

            var messageContainer = $('.error-container');
                messageContainer.empty();
            $('#url_modal').modal()
            $('.url_form_button').text('Create')
            $('.url_form_button').addClass('btn-primary')
            $('.url_form_button').removeClass('btn-success')
            current_process = 'create'
            selected_id = null
            $('#input_url').val("")
            $('#input_desc').val("")
            $('#input_active').prop('checked',false)
        })

        $(document).on('click','.url_edit_to_modal',function(){
            var messageContainer = $('.error-container');
                messageContainer.empty();
            $('#url_modal').modal()
            $('.url_form_button').text('Update')
            $('.url_form_button').addClass('btn-success')
            $('.url_form_button').removeClass('btn-primary')
            current_process = 'update'
            selected_id = $(this).attr('data-id')

            var tempinfo = all_url.filter(x=>x.id == selected_id)
            $('#input_url').val(tempinfo[0].url)
            $('#input_desc').val(tempinfo[0].desc)
          
            if(tempinfo[0].url_active == 1){
                $('#input_active').prop('checked',true)
            }else{
                $('#input_active').prop('checked',false)
            }
        })

        $(document).on('click','.url_form_button',function(){
            if(current_process == 'create'){
                url_create()
            }else{
                url_update()
            }
        })

        $(document).on('click','.url_delete',function(){
            selected_id = $(this).attr('data-id')
            url_delete()
        })

        $('.modal').on('hidden.bs.modal', function () {
            // Remove the desired div when the modal is closed
            $('.alert').alert('close')
        });

    </script>

    <script>

            url_list()

            function url_list(){
                $("#url_datatable").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    serverSide: true,
                    processing: true,
                    ajax:{
                            url: '/url/list',
                            type: 'GET',
                            dataSrc: function ( json ) {
                                all_url = json.data
                                return json.data;
                            }
                    },
                    columns: [
                            { "data": "url" },
                            { "data": "desc" },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                            
                    ],
                    columnDefs: [
                                    {
                                        'targets': 2,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  null
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {

                                                var checked = 'checked="checked"'
                                                if(rowData.url_active == 0){
                                                    checked = ''
                                                }

                                                var checkbox = `<div class="icheck-primary d-inline pt-2">
                                                                    <input disabled type="checkbox" id="status_`+rowData.id+`" data-id="`+rowData.id+`" `+checked+`>
                                                                    <label for="status_`+rowData.id+`"></label>
                                                                </div> `

                                                $(td)[0].innerHTML =  checkbox
                                                                
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" class="url_edit_to_modal" data-id="'+rowData.id+'"><i class="far fa-edit text-primary"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 5,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" class="url_delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                            ]
                })

                var label_text = $($('#url_datatable_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm" id="url_create_to_modal"><i class="fa fa-plus"></i> Add Url</button>'

            }

            function url_create(){

                var active = 1

                if(!$('#input_active').prop('checked')){
                    active = 0
                }
                
                $.ajax({
                        type:'GET',
                        url: '/url/create',
                        data:{
                            url: $('#input_url').val(),
                            desc: $('#input_desc').val(),
                            active:active
                        },
                        success:function(response) {
                            if (response.success) {
                                displayMessage('success', response.message);
                                url_list()
                                url_for_setup_list()
                            }else {
                                displayMessage('error', response.message);
                            }
                        },
                        error:function(response){
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                displayErrors(errors);
                            }else if(response.status === 404){
                                displayMessage('error', 'Page not found');
                            }else{
                                displayMessage('error', response.responseJSON.message);
                            }
                        }
                })
            }

            function url_update(){

                var active = 1

                if(!$('#input_active').prop('checked')){
                    active = 0
                }

                $.ajax({
                        type:'GET',
                        url: '/url/update',
                        data:{
                            url: $('#input_url').val(),
                            description: $('#input_desc').val(),
                            id:selected_id,
                            active:active
                        },
                        success:function(response) {
                            if (response.success) {
                                displayMessage('success', response.message);
                                url_list()
                                url_for_setup_list()
                            }else {
                                displayMessage('error', response.message);
                            }
                        },
                        error:function(response){
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                displayErrors(errors);
                            }else if(response.status === 404){
                                displayMessage('error', 'Page not found');
                            }else{
                                displayMessage('error', response.responseJSON.message);
                            }
                        }
                })
            }

            function url_delete(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove your URL.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    $.ajax({
                            type:'GET',
                            url: '/url/delete',
                            data:{
                                id:selected_id
                            },
                            success:function(response) {
                                if (response.success) {
                                    displayMessage('success', response.message);
                                    url_list()
                                    url_for_setup_list()
                                }else {
                                    displayMessage('error', response.message);
                                }
                            },
                            error:function(response){
                                if (response.status === 422) {
                                    var errors = response.responseJSON.errors;
                                    displayErrors(errors);
                                }else if(response.status === 404){
                                    displayMessage('error', 'Page not found');
                                }else{
                                    displayMessage('error', response.responseJSON.message);
                                }
                            }
                    })
                })
            }

            function displayMessage(type, message) {
                var messageContainer = $('.error-container');
                messageContainer.empty();

                if (type === 'success') {
                   var alert = 'alert-success'
                } else if (type === 'error') {
                    var alert = 'alert-danger'
                }

                $('.error-container').append(`<div class="alert `+alert+` alert-dismissible fade show" role="alert">
                            `+message+`
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>`);
            }

            function displayErrors(errors) {
                // Clear previous errors
                $('.error-container').empty();

                // Display new errors
                $.each(errors, function(key, value) {
                    $('.error-container').append(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            `+value+`
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>`);
                });
            }

           

        

    </script>


    <script>

        var all_usertype = []
        var url_setup_proccess = null
        var all_url_setup = []
        var url_for_setup = []
        get_usertype()
        url_for_setup_list()

       
        $(document).on('click','#url_setup_create_to_modal',function(){

            $('#input_setup_usertype').val($('#filter_usertype option:selected').text())

            $('#input_setup_url').select2({
                'placeholder':'URL',
                'data':url_for_setup
            })

            var groups = all_url_setup.filter(x=>x.header_desc != null)
            $.each(groups,function(a,b){
                b.text = b.header_desc
            })
            $('#input_setup_group').select2({
                placeholder:'Group',
                data:groups
            })

            $('.url_setup_form_button').text('Create')
            $('.url_setup_form_button').addClass('btn-primary')
            $('.url_setup_form_button').removeClass('btn-success')
       
            $('#input_setup_type').prop('checked',false)
            $('#input_setup_active').prop('checked',true)
            $('#input_setup_url').val("").change()
            $('#input_setup_header_desc').val("")
            $('#input_setup_sort').val("")
            $('#input_setup_group').val("").change()
            $('#input_setup_header_desc').attr('disabled','disabled')
            $('#input_setup_url').removeAttr('disabled')

            $('#url_setup_modal').modal();
            $('.error-container').empty()
            url_setup_proccess = 'create'
        })

         $(document).on('click','#view_sidenav',function(){
            $('#sidenav_modal').modal()
        })

        $(document).on('click','.url_setup_form_button',function(){
            if(url_setup_proccess == 'create'){
                url_setup_create()
            }else{
                url_setup_update()
            }
        })

        $(document).on('click','.url_setup_delete',function(){
            selected_id = $(this).attr('data-id')
            url_setup_delete()
        })

        $(document).on('click','.url_setup_edit_to_modal',function(){

            $('#input_setup_usertype').val($('#filter_usertype option:selected').text())

            $('#input_setup_url').select2({
                'placeholder':'URL',
                'data':url_for_setup
            })

            var groups = all_url_setup.filter(x=>x.header_desc != null)
            $.each(groups,function(a,b){
                b.text = b.header_desc
            })
            $('#input_setup_group').select2({
                placeholder:'Group',
                data:groups
            })

            selected_id = $(this).attr('data-id')

            var tempdata = all_url_setup.filter(x=>x.id == selected_id)

            if(tempdata[0].header_desc != null){
                $('#input_setup_type').prop('checked',true)
                $('#input_setup_header_desc').removeAttr('disabled','disabled')
                $('#input_setup_url').attr('disabled','disabled')
            }else{
                $('#input_setup_type').prop('checked',false)
                $('#input_setup_url').removeAttr('disabled','disabled')
                $('#input_setup_header_desc').attr('disabled','disabled')
            }

            if(tempdata[0].active == 1){
                $('#input_setup_active').prop('checked',true)
            }else{
                $('#input_setup_active').prop('checked',false)
            }


            $('#input_setup_url').val(tempdata[0].url_id).change()
            $('#input_setup_sort').val(tempdata[0].sort).change()
            $('#input_setup_group').val(tempdata[0].url_group).change()
            $('#input_setup_header_desc').val(tempdata[0].header_desc).change()




            $('.url_setup_form_button').text('Update')
            $('.url_setup_form_button').addClass('btn-success')
            $('.url_setup_form_button').removeClass('btn-primary')

            url_setup_proccess = 'update'

            $('.error-container').empty()
            $('#url_setup_modal').modal();
        })

        $(document).on('change','#filter_usertype',function(){
            $('#url_setup_create_to_modal').removeAttr('disabled','disabled')
            $('#view_sidenav').removeAttr('disabled','disabled')
            get_url_setup()
        })

        $(document).on('change','#input_setup_type',function(){
            if($(this).prop('checked')){
                $('#input_setup_header_desc').removeAttr('disabled')
                $('#input_setup_url').attr('disabled','disabled')
                $('#input_setup_url').val("").change()
            }else{
                $('#input_setup_header_desc').attr('disabled','disabled')
                $('#input_setup_url').removeAttr('disabled')
                $('#input_setup_header_desc').val("")
            }
        })

        function url_for_setup_list(){
            $.ajax({
                    type:'GET',
                    url: '/url/list',
                    success:function(response) {
                        
                        url_for_setup =  JSON.parse( response).data
                        console.log(url_for_setup)
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })

        }

        function url_setup_delete(){

            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove your URL.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                $.ajax({
                        type:'GET',
                        url: '/url/setup/delete',
                        data:{
                            id:selected_id
                        },
                        success:function(response) {
                            if (response.success) {
                                displayMessage('success', response.message);
                                get_url_setup()
                            }else {
                                displayMessage('error', response.message);
                            }
                        },
                        error:function(response){
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                displayErrors(errors);
                            }else if(response.status === 404){
                                displayMessage('error', 'Page not found');
                            }else{
                                displayMessage('error', response.responseJSON.message);
                            }
                        }
                })
            })
        }

        function url_setup_create(){

            var header = 'not checked'
            var active = 1

            if($('#input_setup_type').prop('checked')){
                header = 'checked'
            }

            if(!$('#input_setup_active').prop('checked')){
                active = 0
            }

            $.ajax({
                    type:'GET',
                    url: '/url/setup/create',
                    data:{
                        usertype:$('#filter_usertype').val(),
                        header:header,
                        header_description: $('#input_setup_header_desc').val(),
                        sort:$('#input_setup_sort').val(),
                        url_id:$('#input_setup_url').val(),
                        group:$('#input_setup_group').val(),
                        active:active
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            get_url_setup()
                        }else {
                            displayMessage('error', response.message);
                        }
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }

        function url_setup_update(){

            var header = 'not checked'
            var active = 1

            if($('#input_setup_type').prop('checked')){
                header = 'checked'
            }

            if(!$('#input_setup_active').prop('checked')){
                active = 0
            }


            $.ajax({
                    type:'GET',
                    url: '/url/setup/update',
                    data:{
                        id:selected_id,
                        usertype:$('#filter_usertype').val(),
                        header:header,
                        header_description: $('#input_setup_header_desc').val(),
                        sort:$('#input_setup_sort').val(),
                        url_id:$('#input_setup_url').val(),
                        group:$('#input_setup_group').val(),
                        active:active
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            get_url_setup()
                        }else {
                            displayMessage('error', response.message);
                        }
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }

        function get_url_setup(){
            $.ajax({
                    type:'GET',
                    url: '/url/setup/list',
                    data:{
                        usertype:$('#filter_usertype').val()
                    },
                    success:function(response) {

                        all_url_setup = response

                        $('#url_setup_datatable').empty();
                        $.each(response,function(a,b){
                            
                            var desc = b.desc
                            var url = b.url
                            var padding = ''

                            if(b.url_id == null){
                                desc = b.header_desc
                                url = ''
                            }

                            if(b.level == 1){
                                padding='pl-4'
                            }else if(b.level == 2){
                                padding='pl-5'
                            }

                            var checked = 'checked="checked"'
                            if(b.active == 0){
                                checked = ''
                            }



                            var checkbox = `<div class="icheck-primary d-inline pt-2">
                                                <input disabled type="checkbox" id="status_`+b.id+`" data-id="`+b.id+`" `+checked+`>
                                                <label for="status_`+b.id+`"></label>
                                            </div> `
                            
                            $('#url_setup_datatable').append(
                                `<tr>
                                    <td class="text-center">`+b.sort+`</td>
                                    <td class="`+padding+`">`+desc+`</td>
                                    <td>`+url+`</td>
                                    <td class="text-center">`+checkbox+`</td>
                                    <td class="text-center"><a href="javascript:void(0)" class="url_setup_edit_to_modal" data-id="`+b.id+`"><i class="far fa-edit text-primary"></i></a></td>   
                                    <td class="text-center"><a href="javascript:void(0)" class="url_setup_delete" data-id="`+b.id+`"><i class="far fa-trash-alt text-danger"></i></a></td>    
                                </tr>`
                            )
                        })

                        if(all_url_setup.length == 0){
                            $('#url_setup_datatable').append(`<tr><td colspan="6" class="text-center">No Setup Found</td></tr>`)
                        }

                        //setup sample
                        var dis_html = ''
                        var dis_group = all_url_setup.filter(x=>x.url_group == null)
                        $.each(dis_group,function(a,b){
                            if(b.url_id == null){
                                var check = all_url_setup.filter(x=>x.url_group == b.id)
                                if(check.length > 0){
                                    dis_html += `<li class="nav-item has-treeview ">
                                                        <a href="#" class="nav-link "  >
                                                            <i class="nav-icon fas fa-layer-group"></i>
                                                            <p>
                                                                `+b.header_desc+`
                                                                <i class="fas fa-angle-left right"></i>
                                                            </p>
                                                        </a>
                                                        <ul class="nav nav-treeview ">`

                                    $.each(check,function(c,d){

                                        var check_2 = all_url_setup.filter(x=>x.url_group == d.id)
                                        console.log(check_2)
                                        if(check_2.length > 0){
                                            
                                            dis_html += `<li class="nav-item has-treeview ">
                                                        <a href="#" class="nav-link "  >
                                                            <i class="nav-icon fas fa-layer-group"></i>
                                                            <p>
                                                                `+d.header_desc+`
                                                                <i class="fas fa-angle-left right"></i>
                                                            </p>
                                                        </a>
                                                        <ul class="nav nav-treeview ">`

                                            $.each(check_2,function(e,f){
                                                dis_html += `   <li class="nav-item">
                                                            <a  class="nav-link" href="#">
                                                                <i class="nav-icon far fa-circle"></i>
                                                                <p>
                                                                    `+f.desc+`
                                                                </p>
                                                            </a>
                                                        </li>`

                                            })

                                            dis_html += `       </ul>
                                                    </li>`


                                        }else{
                                            dis_html += `   <li class="nav-item">
                                                            <a  class="nav-link" href="#">
                                                                <i class="nav-icon far fa-circle"></i>
                                                                <p>
                                                                    `+d.desc+`
                                                                </p>
                                                            </a>
                                                        </li>`
                                        }

                                        
                                    })            

                                    dis_html += `       </ul>
                                                    </li>`


                                }else{
                                    dis_html += `<li class="nav-header">`+b.header_desc+`</li>`
                                }

                            }else{
                                dis_html += `<li class="nav-item">
                                    <a  class="nav-link" href="#">
                                        <i class="nav-icon fas fa-layer-group"></i>
                                        <p>
                                            `+b.desc+`
                                        </p>
                                    </a>
                                </li>`
                            }
                        })

                        $('#nav_holder')[0].innerHTML = dis_html
                    },
            })
        }

        function get_usertype(){
            $.ajax({
                    type:'GET',
                    url: '/setup/usertype/list',
                    success:function(response) {
                        all_usertype = JSON.parse(response).data
                        $.each(all_usertype,function(a,b){
                            b.text = b.utype
                        })
                        $('#filter_usertype').select2({
                            'placeholder':'User type',
                            'data':all_usertype
                        })
                    },
            })
        }


    </script>
    
@endsection

