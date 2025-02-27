@php

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
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
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
       .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .nav-link.active {
            background-color: rgb(211, 210, 210) !important;
            color: black
        }

        .inactive{
            background-color: #ffffff!important;
            cursor: pointer;
        }

        .nav-tabs{
            border-bottom: 3px solid gray!important;
        }

</style>
@endsection

@section('content')

<section class="content-header ">
    <div class="container-fluid">
        <div class="row mb-2 pt-3">
            <div class="col-sm-6">
                <h1>Conduct</h1>
            </div>
            <div class="col-sm-6 pt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Character Grade</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<div class="m-3">
    <ul class="nav nav-tabs row" id="studentInfoTabs" role="tablist">
        <li class="nav-item" style="width: 10%;" role="presentation">
            <a href="/setup/character/grade" style="border-bottom: 1px solid gray" class="nav-link active fw-600 text-center" id="conduct-tab" data-bs-toggle="tab"
                data-bs-target="#conduct" role="tab" aria-controls="conduct">Conduct</a>
        </li>
        <li class="nav-item" style="width: 10%; " role="presentation">
            <a href="/setup/character/grade/homeroom" class="nav-link inactive fw-600 text-center " style="border-bottom: 1px solid gray;color:black!important" id="homeroom-tab" data-id=""
                data-bs-toggle="tab" data-bs-target="#homeroom" role="tab"
                aria-controls="homeroom" aria-selected="true" >Homeroom</a>
        </li>
    </ul>
</div>
<hr class="mx-2">

<section class="content">   
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mx-2 my-3">
                            <table width=100% class="table table-striped table-bordered table-sm"  id="conduct_table">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">Sort</th>
                                        <th width="20%">Conduct</th>
                                        <th width="55%">Description</th>
                                        <th width="5%" class="text-center">%</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mx-2 my-3">
                            <table width=100% class="table table-striped table-bordered table-sm p-0"  id="conduct_values_table">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">Sort</th>
                                        <th width="30%">Descriptive Grades</th>
                                        <th width="15%">Non-Numeric Equivalence</th>
                                        <th width="15%" class="">Numeric Equivalence</th>
                                        <th width="15%" class="text-center">Remarks</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="conduct_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Conduct</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body ">
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Sort</label>
                        <input type="number" class="form-control form-control-sm conduct_form" id="conduct_sort">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Conduct</label>
                        <input  class="form-control form-control-sm conduct_form" id="conduct_name">
                    </div>
                </div>
                <div class="row  ">
                    <div class="col-md-12 form-group">
                        <label for="">Description</label>
                        <textarea class="form-control form-control-sm conduct_form" id="conduct_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6 form-group">
                        <div class=" d-inline pt-2">
                            <input type="checkbox" id="conduct_ispercentage" >
                            <label for="ispercentage" style="font-size: 14px">With Percentage
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="number" class="form-control form-control-sm conduct_form" id="conduct_percentage" placeholder="100%" hidden>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary w-100" id="conduct_create_button"><i class="fas fa-save"></i> Create</button>
                        <button class="btn btn-sm btn-success w-100" id="conduct_save_button" hidden><i class="fas fa-save" ></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="conduct_grade_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Values Grade & Equivalence</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body ">
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Sort</label>
                        <input type="number" class="form-control form-control-sm conduct_form" id="conduct_grade_sort">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Descriptive Grades</label>
                        <input  class="form-control form-control-sm conduct_form" id="conduct_grade_description">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Non-Numeric Equivalence</label>
                        <input  class="form-control form-control-sm conduct_form" id="conduct_nonnumeric">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Numeric Equivalence</label>
                        <input  class="form-control form-control-sm conduct_form" id="conduct_numeric">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Remarks</label>
                        <input  class="form-control form-control-sm conduct_form text-center" id="conduct_remarks">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary w-100" id="conduct_grade_create_button"><i class="fas fa-save"></i> Create</button>
                        <button class="btn btn-sm btn-success w-100" id="conduct_grade_save_button" hidden><i class="fas fa-save" ></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            get_conducts()
            get_conduct_grades()
            

            function load_conduct_values_table(){
                $('#conduct_values_table').DataTable({
                    destroy: true,
                    searching: false,
                    paging: false,
                    info: false,
                    stateSave: true,
                })
            }

            //save & create conduct
            $(document).on('click', '#conduct_create_button', function() {
                var conduct_name = $('#conduct_name').val()
                var conduct_description = $('#conduct_description').val()
                var conduct_ispercentage = $('#conduct_ispercentage').is(':checked') ? 1 : 0
                var conduct_percentage = $('#conduct_percentage').val()
                var sort = $('#conduct_sort').val()
                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/create',
                    data:{
                        conduct_name: conduct_name,
                        conduct_description: conduct_description,
                        conduct_ispercentage: conduct_ispercentage,
                        conduct_percentage: conduct_percentage,
                        sort: sort
                    },
                    success: function(data) {
                        get_conducts()
                    }
                })
            })

            $(document).on('click', '#conduct_save_button', function() {
                var conduct_name = $('#conduct_name').val()
                var conduct_description = $('#conduct_description').val()
                var conduct_ispercentage = $('#conduct_ispercentage').is(':checked') ? 1 : 0
                var conduct_percentage = $('#conduct_percentage').val()
                var sort = $('#conduct_sort').val()
                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/edit',
                    data:{
                        conduct_name: conduct_name,
                        conduct_description: conduct_description,
                        conduct_ispercentage: conduct_ispercentage,
                        conduct_percentage: conduct_percentage,
                        sort: sort,
                        id: conduct_id

                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Conduct Saved'
                        })
                        get_conducts()
                    }
                })
            })

            //get & get edit conduct
            function get_conducts(){
                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/get',
                    success: function(data) {
                        load_conduct_table(data)
                    }
                })
            }
            var conduct_id
            $(document).on('click', '#edit_conduct', function() {
                conduct_id = $(this).data('id')
                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/select',
                    data: {
                        id: conduct_id
                    },
                    success: function(data) {
                        
                        $('#conduct_name').val(data.conductname)
                        $('#conduct_description').val(data.description)
                        $('#conduct_ispercentage').prop('checked', data.ispercentage == 1 ? true : false).trigger('change')
                        if(data.ispercentage == 1){
                            $('#conduct_percentage').val(data.percentage)
                        }
                        $('#conduct_sort').val(data.sortid)
                    }
                })
            })


            //delete conduct
            $(document).on('click', '#delete_conduct', function() {
                var id = $(this).data('id')
                Swal.fire({
                        title: 'Delete Conduct',
                        text: "Are you sure you want to delete this conduct?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: 'GET',
                                url: '/setup/character/grade/conduct/delete',
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    get_conducts();
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Succesfully Deleted',
                                    })
                                },error:function(){
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Section has Enrolled Students!'
                                    })
                                }
                            })
                        }
                    })
            })

            //save create conduct grade
            
            $(document).on('click', '#conduct_grade_create_button', function() {
                var description = $('#conduct_grade_description').val()
                var nonnumeric = $('#conduct_nonnumeric').val()
                var numeric = $('#conduct_numeric').val()
                var remarks = $('#conduct_remarks').val()
                var sortid = $('#conduct_grade_sort').val()

                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/grade/create',
                    data: {
                        description: description,
                        nonnumeric: nonnumeric,
                        numeric: numeric,
                        remarks: remarks,
                        sortid: sortid
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Conduct Grade Created'
                        })
                        get_conduct_grades()
                    }
                })
            })

            $(document).on('click', '#conduct_grade_save_button', function() {
                var description = $('#conduct_grade_description').val()
                var nonnumeric = $('#conduct_nonnumeric').val()
                var numeric = $('#conduct_numeric').val()
                var remarks = $('#conduct_remarks').val()
                var sortid = $('#conduct_grade_sort').val()

                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/grade/edit',
                    data: {
                        description: description,
                        nonnumeric: nonnumeric,
                        numeric: numeric,
                        remarks: remarks,
                        sortid: sortid,
                        id: conduct_grade_id
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Conduct Grade Saved'
                        })
                        get_conduct_grades()
                    }
                })
            })

            //get conduct grades & get edit

            function get_conduct_grades(){
                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/grade/get',
                    success: function(data) {
                        load_conduct_grade_table(data)
                    }
                })
            }
            var conduct_grade_id;
            $(document).on('click', '#edit_grade_conduct', function() {
                conduct_grade_id = $(this).data('id')
                $.ajax({
                    type: 'GET',
                    url: '/setup/character/grade/conduct/grade/select',
                    data: {
                        id: conduct_grade_id
                    },
                    success: function(data) {
                        $('#conduct_grade_description').val(data.description)
                        $('#conduct_nonnumeric').val(data.nonnumeric)
                        $('#conduct_numeric').val(data.numeric)
                        $('#conduct_remarks').val(data.remarks)
                        $('#conduct_grade_sort').val(data.sortid)
                    }
                })
            })

            //delete conduct grade
            $(document).on('click', '#delete_grade_conduct', function() {
                var id = $(this).data('id')
                Swal.fire({
                        title: 'Delete Conduct Grade',
                        text: "Are you sure you want to delete this conduct grade?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: 'GET',
                                url: '/setup/character/grade/conduct/grade/delete',
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Succesfully Deleted',
                                    })
                                    get_conduct_grades()
                                 }}
                    )}
                })
            })
            function load_conduct_grade_table(data){
                $('#conduct_values_table').DataTable({
                    destroy: true,
                    searching: true,
                    data: data,
                    paging: false,
                    info: false,
                    stateSave: true,
                    columns: [
                        { data: 'sortid' },
                        { data: 'description' },
                        { data: 'nonnumeric' },
                        { data: 'numeric' },
                        { data: 'remarks' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 m-0">${rowData.sortid}</p>`)
                                $(td).addClass('text-center align-middle')

                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 m-0">${rowData.description}</p>`)
                                $(td).addClass(' align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0  m-0">${rowData.nonnumeric}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 m-0">${rowData.numeric}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 m-0">${rowData.remarks}</p>`)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(
                                    `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                    <button class="btn btn-sm btn-primary" id="edit_grade_conduct" data-toggle="modal" data-target="#conduct_grade_modal" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="delete_grade_conduct"  data-id="${rowData.id}"><i class="fas fa-trash"></i></button></a>`
                                ).addClass('text-center align-middle')
                            }
                        },
                    ]
                })
            }

            function load_conduct_table(conduct){
                $('#conduct_table').DataTable({
                    destroy: true,
                    searching: true,
                    data: conduct,
                    paging: false,
                    info: false,
                    stateSave: true,
                    columns: [
                        { data: 'sortid' },
                        { data: 'conductname' },
                        { data: 'description' },
                        { data: 'percentage' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.sortid}</p>`)
                                $(td).addClass('text-center align-middle')

                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.conductname}</p>`)
                                $(td).addClass(' align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.description}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.percentage ? rowData.percentage + '%' : ''}</p>`)
                                $(td).addClass('text-center align-middle')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(
                                    `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                    <button class="btn btn-sm btn-primary" id="edit_conduct" data-toggle="modal" data-target="#conduct_modal" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="delete_conduct"  data-id="${rowData.id}"><i class="fas fa-trash"></i></button></a>`
                                ).addClass('text-center align-middle')
                            }
                        },
                    ]
                })
                var label_text = $($("#conduct_table_wrapper")[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML =
                    '<div class="mb-2">' +
                    '   <button class="btn btn-primary btn-sm mt-1" id="conduct_button" data-toggle="modal" data-target="#conduct_modal"><i class="fas fa-plus"></i> Conduct</button>' +
                    '   <button class="btn btn-success btn-sm mt-1 ml-2" id="conduct_grade_button" data-toggle="modal" data-target="#conduct_grade_modal"><i class="fas fa-plus"></i> Values Grade & Equivalence</button>' +
                    '</div>'
            }

            $('#conduct_ispercentage').change(function() {
                if ($(this).is(':checked')) {
                    $('#conduct_percentage').prop('hidden', false);
                } else {
                    $('#conduct_percentage').prop('hidden', true);
                    $('#conduct_percentage').val();
                }
            });
            $('#conduct_modal').on('hidden.bs.modal', function() {
                $('.conduct_form').val('')
                $('#conduct_ispercentage').prop('checked', false).trigger('change')
            })
            $('#conduct_grade_modal').on('hidden.bs.modal', function() {
                $('.conduct_form').val('')
                $('#conduct_ispercentage').prop('checked', false).trigger('change')
            })
            $(document).on('click', '#conduct_button', function() {
                $('#conduct_save_button').prop('hidden', true)
                $('#conduct_create_button').prop('hidden', false)
            })
            $(document).on('click', '#edit_conduct', function() {
                $('#conduct_save_button').prop('hidden', false)
                $('#conduct_create_button').prop('hidden', true)
            })

            $(document).on('click', '#edit_grade_conduct', function() {
                $('#conduct_grade_create_button').prop('hidden', true)
                $('#conduct_grade_save_button').prop('hidden', false)
            })
        })
    </script>
@endsection