
@php

$check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

if(Session::get('currentPortal') == 3){
      $extend = 'registrar.layouts.app';
}else if(auth()->user()->type == 17){
      $extend = 'superadmin.layouts.app2';
}else if(Session::get('currentPortal') == 7){
      $extend = 'studentPortal.layouts.app2';
}else if(Session::get('currentPortal') == 9){
      $extend = 'parentsportal.layouts.app2';
}else if(Session::get('currentPortal') == 2){
      $extend = 'principalsportal.layouts.app2';
}else if(Session::get('currentPortal') == 1){
      $extend = 'teacher.layouts.app';
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
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.min.css')}}">

<style>
      /* select2 */
      .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
      }
      .select2-container--default .select2-selection--multiple .select2-selection__choice{

          background-color: #007bff;
          border: 1px solid #007bff;
      }
      .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{

          color: white;
      }
      .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0;
      }
      input[type=search]{
            height: calc(1.7em + 2px) !important;
      }


      /* calendar */
      #calendar td {
          cursor: pointer;
      }

      #weekList td {
          cursor: pointer;
      }

      div#new_window_modal {
            top: 85px;
        }

</style>
@endsection


@section('content')

<!-- Font Awesome -->

<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}"> 
<link rel='stylesheet' href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}" />


<!-- Create Queuing Setup Modal-->

    <div class="modal fade" id="new_que_setup" tabindex="-1" role="dialog" aria-labelledby="new_que_setupLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="new_que_setupLabel">Queuing Setup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="que_desc" class="form-label">Setup Name</label>
                        <input type="text" class="form-control" id="que_desc" autocomplete="off">
                    </div>

                    <div class="card shadow" id="department_1">
                        <div class="card-header" style="display: block">
                            <div>
                                <select id="department" class="form-control select2" style="height: auto">
                                </select>
                            </div>
                        </div>
                        
                        <div class="card-body">

                            <!-- Card Body -->
                            <!-- <form id="department_forms"> -->
                                <div id="forms">
                                    <div class="p2 text-center">Please Select Department</div>
                                </div>
                            <!-- </form> -->

                            <a type="button" class="btn link-primary add_more_values hidden" div-id="card_element_1"><i class="fas fa-plus"></i> Add Window</a>

                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <button type="button" class="btn btn-sm btn-info view_included" id="view_included">Review Included</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" id="queuing_create_cancel">Cancel</button>
                        <button type="button" class="btn btn-sm btn-success queuing_create" id="queuing_create">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Create Queuing Setup Modal-->

<!-- Create Department Modal -->

    <div class="modal fade" id="addDepartment" tabindex="-1" role="dialog" aria-labelledby="addDepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentLabel">Create Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="department_id">
                            <label for="department_desc" class="form-label">Department Name</label>
                            <input type="text" class="form-control" id="department_desc">
                        </div>
                </div>
                <div class="modal-footer">
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-success add_department" id="add_department">Add</button>
                    </div>
  
                </div>
            </div>
        </div>
    </div>

<!-- Create Department Modal -->

<!-- View Included Modal -->
    <div class="modal fade bd-example-modal-xl" id="viewIncluded" tabindex="-1" role="dialog" aria-labelledby="viewIncludedLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <input type="hidden" id="que_setup">
                    <h4 class="modal-title" id="viewIncludedLabel">Included Department</h4>
        
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    
                </div>

                <div class="modal-body p-0">
                    <div style="padding: 10px;  overflow: scroll; height: 80vh">
                        <div class="bg-warning note">
                            <p>Note: Items below shows all the included windows to their respected department. Review the list below before creating the setup.</p>
                        </div>
                        
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow" style="">
                                        <div class="card-body  p-3">
                                            <div class="row mt-2">
                                                <div class="col-md-12" style="font-size:.9rem !important">
                                                    <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="includedwindow_datatable" width="100%" >
                                                        <thead>
                                                            <tr>
                                                                <th width="50%">Window Name</th>
                                                                <th width="50%">Department</th>
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

                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- View Included Modal END-->

<!-- View Queuing Setup Modal -->
    <div class="modal fade bd-example-modal-xl" id="viewQueSetup" tabindex="-1" role="dialog" aria-labelledby="viewQueSetupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <input type="hidden" id="que_setup">
                    <div>
                        <h4 class="modal-title pl-3 pr-3" id="viewQueSetupLabel" contenteditable="true">Queuing Setup</h4>
                    </div>        
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    
                </div>

                <div class="modal-body p-0">
                    <div style="padding: 10px;  overflow: scroll; height: 80vh">

                        

                        <div class="d-flex justify-content-between">
                            
                            <div class="d-flex">
                                <button type="button" class="btn btn-danger btn-sm delete_setup mb-2 mr-2">
                                    <i class="far fa-trash-alt"></i> Delete Setup
                                </button>
        
                                <button type="button" class="btn btn-primary btn-sm edit_setup mb-2">
                                    <i class="far fa-edit"></i> Edit Setup
                                </button>
                            </div>

                            <button type="button" class="btn btn-success btn-sm new_window mb-2">
                                <i class="fas fa-plus"></i> Window
                            </button>
                        </div>

                        <div class="bg-info note">
                            <p>Note: Click the operation to edit or delete window.</p>
                        </div>

                       
                        
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow" style="">
                                        <div class="card-body  p-3">
                                            <div class="row mt-2">
                                                <div class="col-md-12" style="font-size:.9rem !important">
                                                    
                                                    <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="viewquesetup_datatable" width="100%" >
                                                        <thead>
                                                            <tr>
                                                                <th width="40%">Window Name</th>
                                                                <th width="40%">Department</th>
                                                                <th width="10%" class="align-middle"></th>
                                                                <th width="10%" class="align-middle"></th>
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

                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- View Queuing Setup Modal END-->

<!-- Add New Window Modal-->

    <div class="modal fade" id="new_window_modal" tabindex="-1" role="dialog" aria-labelledby="new_window_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="new_window_modalLabel">New Window</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                   
                    <div id="department_1">
                        <div class="mb-3">
                            <label for="department_select2">Department</label>
                            <select id="department_select2" class="form-control select2 department_select2" style="height: auto"></select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Window Label</label>
                                <input type="text" class="form-control new_window_label">
                            </div>
                            <div class="col-6"><label class="form-label ml-2">User</label>
                                <select id="new_window_user" class="form-control new_window_user" style="padding: 3px">
                                </select>
                            </div>
                        </div>

                        <br>

                    </div>

                </div>
                <div class="modal-footer ">
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-success add_window" id="add_window">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add New Window Modal-->

<!-- Edit Queuing Window Modal-->

    <div class="modal fade" id="editWindowModal" tabindex="-1" role="dialog" aria-labelledby="editWindowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWindowModalLabel">Edit Window</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="setup_id">
                    <label for="edit_department" class="form-label">Department</label>
                    <div class="mb-3">
                        <select id="edit_department" class="form-control select2" style="height: auto">
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="que_desc" class="form-label">Window Label</label>
                            <input type="hidden" id="window_id">
                            <input type="text" class="form-control" id="edit_window_label">
                        </div>
                        <div class="col-6">
                            <label class="form-label ml-2">User</label>
                            <select id="edit_window_user" class="form-control edit_window_user" style="padding: 3px">
                            </select>
                        </div>
                    </div>

                  
                </div>
                <div class="modal-footer ">

                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-success edit_window" id="edit_window">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Queuing Window Modal-->

    <div class="pt-3 px-2">
        <div class="container-fluid">

                <h1 class="page-title">Queuing Setup</h1>
                
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-success btn-sm new_setup">
                        <i class="fas fa-plus"></i> New Setup
                    </button>
                    <div class="d-flex">
                        <button type="button" class="btn btn-info btn-sm mr-3 goto_view">
                            <i class="fas fa-tv"></i> Go to View
                        </button>

                        <button type="button" class="btn btn-danger btn-sm goto_ui">
                            <i class="fas fa-tv"></i> Go to UI
                        </button>
                    </div>
                </div>
                

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card shadow" style="">
                            <div class="card-body  p-3">
                                <div class="row mt-2">
                                    <div class="col-md-12" style="font-size:.9rem !important">
                                        <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="queuingsetup_datatable" width="100%" >
                                            <thead>
                                                <tr>
                                                    <th width="80%">Setup Description</th>
                                                    <th width="20%" class="text-center">Set Active</th>
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

    </div>



<!-- BODY END-->

<script src="{{asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

<script>
    var selected_department = null;
    var selected_edit_department = null;
    var users_g = null;
    var included_list;
    var quesetup_list;
    var quingsetup_list = @json($queuingsetup);

    //ON READY 
    $(document).ready(function() {
        var counter = 1;
        load_department();
        load_edit_department();
        load_queuing_datatable();

        // MODAL & UI JQUERY

            $(document).on('click', '.new_setup', function(){
                $('#new_que_setup').modal('toggle');
                $('#que_desc').val("")
                var id = $('#department').val();

                if(id != ""){

                    load_windows(id);
                    

                }else{

                    false;
                }


                // $("#new_que_setup").load(location.href + " #new_que_setup");
            });
            
            $(document).on('click', '.add_more_values', function (event) { // ADDING MORE VALUE INPUT

                addWindow();

            });

            $(document).on('click', '.remove_input', function() { // DELETE VALUE INPUT

                var id =  $(this).val();
                removeWindow(id);

            });

            $(document).on('click', '#remove_department', function (event) { // DELETE VALUE INPUT

                var id =  $(this).val();

                if (id !== 'department_1') {

                    $("#"+id).remove();

                }else{

                    notify('error', 'Must have atleast 1 Department.')
                    return false;
                }


            });

            $(document).on('click', '#view_included', function (event) { // VIEW INCLUDED
                $('#viewIncluded').modal();
                get_included_list();
            });

            $(document).on('click', '.view_que_setup', function (event) { // VIEW INCLUDED  
                
                var setupdesc = $(this).html();
                var id = $(this).attr('data-id');
                $('#que_setup ').val(id);
                $('.delete_setup ').attr('data-id', id);
                $('#viewQueSetupLabel').text(setupdesc);
                $('#viewQueSetup').modal();

                get_quesetup_list(id);
                
                
            });

            $(document).on('change', '#department', function(event){

                counter = 1;
                var department = $(this).val();
                if(department == 'add'){

                    $('#department_desc').val(" ");
                    $('#addDepartment').modal();

                }else{
        
                    $('.add_more_values').removeClass('hidden')
                    $('.add_more_values').attr('div-id', 'element_'+department)
                    $('.add_more_values').attr('department-id', department)

                    load_users(department)
                    load_windows(department);


                }

            });

            $(document).on('change', '#department_select2', function(event){

                var department = $(this).val();
                $.ajax({
                    type:'GET',
                    url: '{{ route("get.users") }}',
                    data: {
                        usertype: department
                    },
                    success:function(data) {

                        $('#new_window_user').empty()
                        $('#new_window_user').append('<option value="">Select User</option>')
                        $.each(data, function (index, item) {

                            $("#new_window_user").append($('<option>', { 
                                value: item.id,
                                text : item.text ,
                            }));
                        });
                    
                    }
                });

            });

            $(document).on('hide.bs.modal', '#addDepartment', function (event) {
                    
                load_department();

            });

            $(document).on('click', '.open_edit_window', function (event) {

                var id = $(this).attr("data-id")
                $('#editWindowModal').modal();
                $.ajax({
                    type:'GET',
                    url: '{{ route("get.setup.windows") }}',
                    data: {
                        id: id
                    },
                    success:function(data) {

                        get_edit_window_user(data[0].userid, data[0].departmentid);
                        $('#edit_department').val(data[0].departmentid).change();
                        $('#edit_window_label').val(data[0].windowdesc);
                        $('#edit_window').val(data[0].id);
                        $('#setup_id').val(data[0].que_setupid)

                    }
                });

            });

            $(document).on('click', '.edit_setup', function (event) {

                $('#viewQueSetupLabel').focus();
            })

        // MODAL & UI JQUERY


        // AJAX JQUERY

            $(document).on('click', '#queuing_create', function(event){

                let que_desc = $('#que_desc').val();

                if(que_desc == null ||  que_desc == ""){

                    notify("error", "Setup Name is required!")

                }else{

                    $.ajax({

                    url:'{{ route("queuing.create") }}',
                    type:"GET",
                    data:{
                        que_desc: que_desc
                    },
                    success:function(response){
                        
                        notify(response[0].statusCode, response[0].message);
                        get_queuing_list();
                    }
                    });
                }

            })

            $(document).on('click', '#add_department', function(event){

                let depart_desc = $('#department_desc').val();

                $.ajax({

                    url:'{{ route("queuing.department.create") }}',
                    type:"GET",
                    data:{
                        depart_desc: depart_desc
                    },
                    success:function(response){
                        
                        notify(response[0].statusCode, response[0].message);
                        
                    }
                });

            })
            
            $(document).on('click', '#include_department', function (event) { 

                let data = $('#department_forms').serializeArray();
                let departmentid = $('#department').val();
                $.ajax({

                    url:'{{ route("department.window.create") }}',
                    type:"GET",
                    data:{
                        departmentid: departmentid,
                        formData: data
                    },
                    success:function(response){
                        
                        notify(response[0].statusCode, response[0].message);
                        
                    }
                });

            });

            $(document).on('click', '#queuing_create_cancel', function (event) {
                
                $.ajax({

                    url:'{{ route("included.window.revert") }}',
                    type:"GET",
                    success:function(data){

                    }
                });
            });

            $(document).on('keyup', '.window_label', function(event) {
                var departmentid = $('#department').val();
                var window_label = $(this).val(); 
                var id = $(this).attr('id'); 

                $.ajax({

                    url:'{{ route("edit.window.label") }}',
                    type:"GET",
                    data:{
                        departmentid: departmentid,
                        window_label: window_label,
                        id: id,
                    }
                });

            });

            $(document).on('change', '.window_user', function(event) {
                var departmentid = $('#department').val();
                var user = $(this).val();
                var id = $(this).attr('data-id');

                $.ajax({

                    url:'{{ route("assign.window.user") }}',
                    type:"GET",
                    data:{
                        departmentid: departmentid,
                        user: user,
                        id: id,
                    }
                });
            });

            $(document).on('click', '.edit_window', function (event) {

                var id = $(this).val();
                var department = $('#edit_department').val();
                var windowlabel = $('#edit_window_label').val();
                var userid = $('#edit_window_user').val();

                $.ajax({
                    url:'{{ route("edit.setup.window") }}',
                    type:"GET",
                    data:{
                        id: id,
                        department: department,
                        windowlabel: windowlabel,
                        userid: userid
                    },
                    success:function(data){

                        notify(data[0].statusCode, data[0].message);
                        var id = $('#setup_id').val();
                        get_quesetup_list(id);
                        

                    }
                });
            });

            $(document).on('click', '.delete_window', function (event) {

                var id = $(this).attr('data-id');

                $.ajax({
                    url:'{{ route("delete.setup.window") }}',
                    type:"GET",
                    data:{
                        id: id,
                    },
                    success:function(data){

                        notify(data[0].statusCode, data[0].message);
                        var id = $('#que_setup').val();
                        get_quesetup_list(id);
                    }
                });
            });

            $(document).on('click', '.delete_setup', function (event) {

                Swal.fire({
                    title: 'You want to delete this setup?',
                    type: 'info',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                })
                .then((result) => {

                    if (result.value) {
                        
                        var id = $(this).attr('data-id');

                        $.ajax({
                            url:'{{ route("delete.queuing.setup") }}',
                            type:"GET",
                            data:{
                                id: id,
                            },
                            success:function(data){

                                if(data[0].status == 400){

                                    notify(data[0].statusCode, data[0].message);
                                }else{

                                    notify(data[0].statusCode, data[0].message);
                                    get_queuing_list();
                                    $('#viewQueSetup').modal('toggle');
                                }
                                

                            }
                        });
                    }
                })
                
            })

            $(document).on('keyup', '#viewQueSetupLabel', function (event) {
                var id = $('#que_setup').val();
                var value = $(this).html();

                $.ajax({
                    url:'{{ route("edit.queuing.setup") }}',
                    type:"GET",
                    data:{
                        value: value,
                        id: id,
                    },
                    success: function () {
                        get_queuing_list();
                    }
                });
                
            })

            $(document).on('click', '.setActive', function (event) {
                
                var id = $(this).attr("data-id");
                var isSetActive = "";
                var isSetActiveStat = null;
                
                if($(this).prop('checked')){

                    isSetActive = "Active";
                    isSetActiveStat = 1;


                }else{
                    isSetActive = "Deactive";
                    isSetActiveStat = 0;

                }

                Swal.fire({
                    title: 'You want make this setup '+isSetActive+'?',
                    type: 'info',
                    text: `This process can be undone.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: isSetActive
                })
                .then((result) => {

                    if (result.value) {
                        
                        $.ajax({
                            url:'{{ route("setup.setactive") }}',
                            type:"GET",
                            data:{
                                id: id,
                                isSetActiveStat: isSetActiveStat
                            },
                            success:function(data){

                                notify(data[0].statusCode, data[0].message);
                                get_queuing_list();

                            }
                        });
                        
                    }else{

                        $(this).prop('checked', false)
                    }
                })

            })

            $(document).on('click', '.new_window', function (event) {
                
                $('#new_window_modal').modal();

            })

            $(document).on('click', '#add_window', function (event) {
                
                let windowlabel = $('.new_window_label').val();
                let departmentid = $('#department_select2').val();
                let que_setup = $('#que_setup').val();
                let user = $('#new_window_user').val();

                $.ajax({
                    url:'{{ route("add.new.window") }}',
                    type:"GET",
                    data:{

                        que_setup: que_setup,
                        windowlabel: windowlabel,
                        departmentid: departmentid,
                        user: user,
                    },
                    success:function(data){

                        notify(data[0].statusCode, data[0].message);
                        get_quesetup_list(que_setup);

                    }
                });
            })


        // AJAX JQUERY

        //Go to Windwo UI

        $(document).on('click', '.goto_ui', function(event){

            params  = 'width='+screen.width;
            params += ', height='+screen.height;
            params += ', top=0, left=0'
            params += ', fullscreen=yes';

            window.open('http://queuing.ck/queuingui', '_blank', params);
            return false;
        })

         $(document).on('click', '.goto_view', function(event){

            params  = 'width='+screen.width;
            params += ', height='+screen.height;
            params += ', top=0, left=0'
            params += ', fullscreen=yes';

            window.open('http://queuing.ck/', '_blank', params);
            return false;
        })

    });

    //ADD/DELETE WINDOW

        function addWindow(){

            let departmentid = $('#department').val();
            $.ajax({

                url:'{{ route("department.window.create") }}',
                type:"GET",
                data:{
                    departmentid: departmentid,
                },
                success:function(response){
                    
                    notify(response[0].statusCode, response[0].message);
                    load_windows(departmentid)
                    load_users(departmentid);

                }
            });     

        }

        function removeWindow(id){
            
            let departmentid = $('#department').val();
            $.ajax({

                url:'{{ route("delete.windows") }}',
                type:"GET",
                data:{
                    id: id,
                    departmentid: departmentid,
                },
                success:function(response){
                    
                    notify(response[0].statusCode, response[0].message);
                    load_windows(departmentid);
                    load_users(departmentid);

                    
                }
            });     

        }

    //ADD/DELETE WINDOW

    //GETTING LIST

        function get_included_list(){

            $.ajax({
                    type:'GET',
                    url:'{{ route("get.included.window") }}',
                    success:function(data) {
                        
                        if(data.length == 0){

                            notify('warning', 'No included department found.');
                            included_list = data;
                            load_included_datatable();
                        
                        }else{
                            included_list = data;
                            load_included_datatable();
                        }
                    }
            })
        }

        function get_quesetup_list(id){

            $.ajax({
                type:'GET',
                url:'{{ route("get.queuingsetup.data") }}',
                data:{
                    id: id,
                },
                success:function(data) {
                    
                    quesetup_list = data;
                    load_quesetup_datatable();
                }
            })

        }

        function get_queuing_list(){

            $.ajax({
                    type:'GET',
                    url:'{{ route("get.queuingsetup") }}',
                    success:function(data) {

                        if(data.length == 0){

                            notify('warning', 'No included setup found.');
                        
                        }else{
                            quingsetup_list = data;
                            load_queuing_datatable();
                        }
                    }
            })
        }

        function get_edit_window_user(user, department){

            $.ajax({
                    type:'GET',
                    url:'{{ route("get.users") }}',
                    data: {
                        usertype: department,
                    },
                    success:function(data) {

                        $('#edit_window_user').empty()
                        $('#edit_window_user').append('<option value="">Select User</option>')
                        $('#edit_window_user').select2({
                            data: data,
                            placeholder: "Select User",
                        })

                        $('#edit_window_user').val(user).change();
                    }
            })
        }

    //GETTING LIST


    //LOADING DATAS
                    
        function load_queuing_datatable(){

            $("#queuingsetup_datatable").DataTable({
                    destroy: true,
                    data: quingsetup_list,
                    lengthChange : false,
                    columns: [
                                { "data": "quedesc" },
                                { "data": null },
                        ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a style="cursor: pointer"  class="view_que_setup text-primary" data-id="'+rowData.id+'">'+rowData.quedesc+'</a>';
                                $(td)[0].innerHTML =  buttons
                            }
                        },

                        {
                            'targets': 1,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {

                                var isActive = '';

                                if(rowData.isActivated == 1){
                                    isActive = 'checked'
                                }else{
                                    isActive = ''
                                }

                                var buttons = '<div class="custom-control custom-switch">'
                                    +'<input type="checkbox" class="custom-control-input setActive" id="switch'+rowData.id+'" data-id="'+rowData.id+'" '+isActive+'>'
                                    +'<label class="custom-control-label" for="switch'+rowData.id+'"></label>'
                                    +'</div>';
                                $(td)[0].innerHTML =  buttons;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                            }
                        },
                    ]
                    
            });
        }
        
        function load_included_datatable(){

            $("#includedwindow_datatable").DataTable({
                    destroy: true,
                    data: included_list,
                    lengthChange : false,
                    columns: [
                                { "data": "windowdesc" },
                                { "data": "utype" },
                        ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a style="cursor: pointer"  class="view_invluded text-primary" data-id="'+rowData.id+'">'+rowData.windowdesc+'</a>';
                                $(td)[0].innerHTML =  buttons
                            }
                        },
                    ]
                    
            });
        }

        function load_quesetup_datatable(){
            
            $("#viewquesetup_datatable").DataTable({
                    destroy: true,
                    data: quesetup_list,
                    lengthChange : false,
                    columns: [
                                { "data": "windowdesc" },
                                { "data": "utype" },
                                { "data": null },
                                { "data": null },
                        ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a style="cursor: pointer"  class="view_invluded text-primary" data-id="'+rowData.id+'">'+rowData.windowdesc+'</a>';
                                $(td)[0].innerHTML =  buttons
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var buttons = '<a href="javascript:void(0)" class="open_edit_window" data-id="'+rowData.id+'"><i class="far fa-edit text-primary"></i></a>';
                                    $(td)[0].innerHTML =  buttons
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var buttons = '<a href="javascript:void(0)" class="delete_window" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                    $(td)[0].innerHTML =  buttons
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                            }
                        },
                    ]
                    
            });
        }

        function load_department(){ 

            $.ajax({
                type:'GET',
                url: '{{ route("get.select2.department") }}',
                success:function(data) {

                    $('#department').empty()
                    $('#department').append('<option value="">Select Department</option>')
                    $('#department').select2({
                        data: data,
                        placeholder: "Select Department",
                    })
                    if(selected_department != null){
                        $('#department').val(selected_department).change()
                    }

                    $('.department_select2').empty()
                    $('.department_select2').append('<option value="">Select Department</option>')
                    $('.department_select2').select2({
                        data: data,
                        placeholder: "Select Department",
                    })
                    if(selected_department != null){
                        $('.department_select2').val(selected_department).change()
                    }

    
                }
            });
        }

        function load_edit_department(){ 

            $.ajax({
                type:'GET',
                url: '{{ route("get.select2.department") }}',
                success:function(data) {

                    $('#edit_department').empty()
                    $('#edit_department').append('<option value="">Select Department</option>')
                    // $('#edit_department').append('<option value="add">Add Department</option>')
                    $('#edit_department').select2({
                        data: data,
                        placeholder: "Select Department",
                    })
                    if(selected_edit_department != null){
                        $('#edit_department').val(selected_edit_department).change()
                    }
                }
            });
        }

        function load_windows(id){

            $.ajax({
                type:'GET',
                url: '{{ route("get.windows") }}',
                data: {
                    id: id
                },
                success:function(data) {
                    
                    var template = '';

                    for (let i = 0; i < data.length; i++) {
                        
                        $('.window_user').val(data[i].userid).change()

                        template += '<div class="row mb-3">'
                            +'<div class="col-6" id="element_'+id+'">'
                                +'<button class="remove_input" value="'+data[i].id+'">'
                                    +'×'
                                +'</button>'
                                +'<label class="form-label">Window Label</label>'
                                
                                +'<input type="text" class="form-control window_label" id="'+data[i].id+'" value="'+data[i].windowdesc+'">'
                            +'</div>'
                            +'<div class="col-6" id="element_'+id+'">'
                        
                                +'<label class="form-label ml-2">User</label>'
                                +'<select data-id="'+data[i].id+'" id="select_user_'+data[i].id+'" class="form-control window_user" style="height: auto; padding: 3px">'
                                    +'<option value="0">Select User</option>'
                                +'</select>'
                            +'</div>'
                        +'</div>';

                    }

                    $('#forms').html(template);

                    for (let i = 0; i < data.length; i++) {

                        
                        $.each(users_g, function (index, item) {

                            $("#select_user_"+data[i].id).append($('<option>', { 
                                value: item.id,
                                text : item.text ,
                            }));
                        });

                        if($("#select_user_"+data[i].id).val() == 0){

                            $("#select_user_"+data[i].id).val(0)
                        }

                        $("#select_user_"+data[i].id).val(data[i].userid).change();
                    }

                }
            });

        }

        function load_users(usertype){

            $.ajax({
                type:'GET',
                url: '{{ route("get.users") }}',
                data: {

                    usertype: usertype
                },
                success:function(data) {

                    users_g = data
                    // $('.window_user').empty()
                    // $('.window_user').append('<option value="">Select Users</option>')
                    // $('.window_user').select2({
                    //     data: data,
                    //     placeholder: "Select Users",
                    // })
                
                }
            });
        }

    //LOADING DATAS

    /////////////SWEET ALERT///////////////
    function notify(code, message){
        Swal.fire({
            type: code,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });

    }


</script>
@endsection
