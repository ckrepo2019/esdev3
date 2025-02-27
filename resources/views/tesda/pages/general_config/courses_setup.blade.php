@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .nav-tabs .nav-link.active {
            background-color: #dee2e6; /* Very light gray */
            border-color: #dee2e6 #dee2e6 #dee2e6; /* Adjust border for a seamless look */
        }
        .nav-tabs{
            border-bottom : 5px solid #dee2e6!important;
        }

        .nav-tabs .nav-link {
            border: 1px solid #dee2e6; /* Add border to all tabs */
            border-bottom: none; /* Remove bottom border for consistency */
            background-color: #ffffff; /* Default background for inactive tabs */
        }
    </style>
@endsection

@section('content')


    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Courses Setup</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Course Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box shadow">
                                <div class="info-box-content">
                                    <div><i class="fa fa-filter"></i> Filter</div>
                                    <div class="row pb-2 d-flex">
                                        <div class="col-md-2 col-sm-12">
                                            <label for="" class="mb-0">Course Type</label>
                                            <select class="form-control form-control-sm select2 academic" id="course_type_filter" style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title fw-1000">Courses Setup</h3>
    
                        </div>
    
                        <div class="card-body p-2 ">
                            <div class="mt-2 p-2 d-flex flex-row">
                                <div class="">
                                    <button class="btn btn-success btn-sm" id="course_button">Add Course</button>
                                </div>
                            </div>
                            <div class="row px-2">
                                <div class="col-md-12" style="font-size:.8rem">
                                    <table class="table table-sm table-striped display table-bordered table-responsive-sm"
                                        id="course_table" width="100%">
                                        <thead>
                                            <tr class="font-20">
                                                <th width="10%">Course Code</th>
                                                <th width="50%">Course Name</th>
                                                <th width="10%">Course Type</th>
                                                <th width="20%">Course Duration</th>
                                                <th width="10%" class="text-center px-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_1">
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new_course" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="new_course_title">New Course</h5>
                    <h5 class="card-title mb-0 d-none" id="update_course_title">Update Course</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="" class="mb-0">Course Code</label>
                            <input type="text" class="form-control form-control-sm" id="course_code" placeholder="Enter Course Code" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="" class="mb-0">Course Name</label>
                            <input type="text" class="form-control form-control-sm" id="course_name" placeholder="Enter Course Name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="mb-0">Course Type</label>
                            <select class="form-control form-control-sm select2 " id="course_type" style="width: 100%;" required>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="" class="mb-0">Course Duration</label>
                            <input type="text" class="form-control form-control-sm" id="course_duration" placeholder="Enter Course Duration e.g.(754 hrs.)" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success" id="save_course" style="width:20% !important">Save</button>
                    <button type="button" class="btn btn-sm btn-primary d-none" id="save_edit_course" style="width:20% !important">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new_course_type" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">New Course Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="mb-0">Description</label>
                            <input type="text" class="form-control form-control-sm" id="course_type_add" placeholder="Enter Course Type">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success" id="save_new_course_type" style="width:20% !important">Save</button>
                </div>
            </div>
        </div>
    </div>

    

    <div class="modal fade" id="course_units_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content" style="height: 700px">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="course_title_modal"></h5>
                    <button type="button" class="close" id="close_course_units"  aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-11" >
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation" id="course_series_tab_add">
                                    <a class="nav-link" data-toggle="tab" id="course_series_add" href="#" role="tab"><i class="fa fa-plus-square text-success"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-1 text-center">
                            <button class="btn btn-sm mt-2" id="edit_series"><i class="fa fa-cog text-lg"></i></button>
                        </div>
                    </div>
                    <div class="text-center" id="series_info"><i class="text-info">Please Add Course Series Using the " <i class="fa fa-plus-square text-success"></i> " above</i></div>
                    <div class="mt-3 d-flex flex-row justify-content-between">
                        
                        <button class="btn btn-sm btn-success d-none" id="add_compentency" style="width:20% !important">Add Competency</button>
                        <div class="d-flex flex-row"  id="active_button">
                            <input type="checkbox" class="d-none" id="series_active_state" style="margin-right: 12px;cursor:pointer;">
                            <label class="my-0 d-none" id="series_active_label" style="font-size: 14px;margin-top:4px!important;"> Active </label>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3" style="height:350px;overflow: auto">
                        <table width="100%" class="table table-fixd table-sm table-bordered table-striped d-none" id="course_units_table">
                            <thead>
                                <tr style="font-size: 12px!important">
                                    <th>Unit Code</th>
                                    <th>Competency Description</th>
                                    <th>No. of Hours</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="course_units_body"></tbody>
                            <tfoot style="font-size: 12px!important">
                                <tr>
                                    <td colspan="2" class="font-weight-bold text-right">Total Hours</td>
                                    <td colspan="2" class="font-weight-bold" id="competency_total_hours"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="border border-rounded m-2 rounded p-1 d-none" id="signatories_div" style="font-size: 13px!important">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <div>
                            Update Signature
                            <button class="btn btn-sm ml-0" id="update_signature"><i class="fa fa-edit text-info"></i></button>
                            <button class="btn btn-sm btn-success d-none ml-2" id="save_update_signature">Save</button>
                        </div>
                        <div>
                            <button class="btn btn-sm text-success d-none" id="add_signatories" style="font-size: 11px!important" >Add Signatory<i class="fa fa-plus ml-1"></i></button>
                        </div>
                    </div>
                    <div class="row p-1" id="course_signatories">
                        <div class="col-md-2 align-middle" id="">
                            <div class="" style="margin-top: 3.65rem!important">
                                <p class="m-0" style="border-bottom: 1px solid black"></p>
                                <p class="mb-0 mt-1" style="font-size: 9px!important">Name & Signature Over Printed Name</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="course_competency_series" data-backdrop="static" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">New Series of Course Competency</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="mb-0">Series of Course Competency</label>
                            <input type="text" class="form-control form-control-sm" id="course_series" placeholder="">
                        </div>
                        <div class="col-md-5 d-flex flex-row mt-2">
                            <input type="checkbox" id="series_active" style="margin-right: 12px;cursor:pointer;">
                            <label class="my-0" style="font-size: 14px;margin-top:15px;"> Active </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success" id="save_course_series" style="width:20% !important">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_competency_modal" data-backdrop="static" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">New Competency</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="mb-0">Competency Type</label>
                            <input type="text" class="form-control form-control-sm" id="competency_type" placeholder="Enter Competency Type">
                            <div class="my-1" id="competencies_available"></div> 
                        </div>
                        <div class="col-md-6">
                            <label for="" class="mb-0">Competency Code</label>
                            <input type="text" class="form-control form-control-sm" id="competency_code" placeholder="Enter Compentency Code">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="" class="mb-0">Competency Description</label>
                            <input type="text" class="form-control form-control-sm" id="competency_description" placeholder="Enter Compentency ">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="mb-0">No. of Hours</label>
                            <input type="number" min="0" class="form-control form-control-sm" id="competency_hours" placeholder="Enter No. of Hours ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success" id="save_competency" style="width:20% !important">Save</button>
                    <button type="button" class="btn btn-sm btn-primary d-none" id="save_edit_competency" style="width:20% !important">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="open_series_modal" data-backdrop="static" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">Available Series for this Course</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div>
                        <table width="100%" class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Series Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="series_table_list">

                            </tbody>
                        </table>
                   </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            $('.select2').select2()

            $('#course_type_filter').select2({
                placeholder: 'Select Course Type',
                allowClear: true
            })

            $('#course_type').select2({
                placeholder: 'Select Course Type',
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) {
                        return data.text; // Handles the placeholder
                    }

                    // Check if the option is "Add Building"
                    if (data.id === 'add') {
                        return $(
                            '<span><i class="fas fa-plus"></i> ' +
                            '<span class="font-weight-bold ">' + data.text + '</span></span>'
                        );
                    }

                    return data.text;
                },
                templateSelection: function (data) {
                    return data.text; // Ensures selected option displays correctly
                }
            })
            
            // $('#course_duration').daterangepicker({
            //     autoUpdateInput: false,
            //     locale: {
            //         cancelLabel: 'Clear',
            //         format: 'MM/DD/YYYY'
            //     }
            // })

            // Course Type
            generate_course_type()

            function generate_course_type() {
                $.ajax({
                    type:'get',
                    url: '/tesda/course_setup/get/course_type',
                    success: function(data) {
                        $('#course_type').empty()
                        $('#course_type_filter').empty()
                        $('#course_type_filter').append(` 
                            <option value=""></option>
                        `)
                        $('#course_type').append(` 
                            <option value=""></option>
                            <option value="add"><i class="fa fa-times"></i> Add Course Type</option>
                        `)
                        $.each(data, function(key, courseType) {
                            $('#course_type').append(`
                                <option value="${courseType.id}">${courseType.description}</option>
                            `);
                            $('#course_type_filter').append(`
                                <option value="${courseType.id}">${courseType.description}</option>
                            `);
                        })
                    }
                })
            }
            $('#course_button').on('click', function() {
                $('#new_course').modal('show')
                generate_course_type()
                $('#save_course').removeClass('d-none')
                $('#save_edit_course').addClass('d-none')
                $('#new_course_title').removeClass('d-none')
                $('#update_course_title').addClass('d-none')

            })

            
            $('#course_type').on('change', function() {
                var val = $(this).find(':selected').val()
                if(val == 'add'){
                    $('#new_course_type').modal('show')
                    $(this).val('').trigger('change');
                }
            })

            $(document).on('click', '#save_new_course_type', function() {
                var course_type = $('#course_type_add').val()
                $.ajax({
                    url: "/tesda/course_setup/add/course_type",
                    type: "GET",
                    data: {
                        course_type: course_type,
                    },
                    success: function(data) {
                        $('#new_course_type').modal('hide')
                        generate_course_type()
                        Toast.fire({
                            type: 'success',
                            title: 'Course Type Added!'
                        })
                    }
                })
            })
            // Course Type 

            // Course

            generate_courses()

            $(document).on('change', '#course_type_filter', function() {
                generate_courses()
            })

            function generate_courses(){
                $.ajax({
                    type:'get',
                    url: '/tesda/course_setup/get/courses',
                    data: {
                        course_type: $('#course_type_filter').val()
                    },
                    success: function(data) {
                        generate_courses_datatable(data)
                    }
                })
            }

            $(document).on('click', '#save_course', function() {
                var course_code = $('#course_code').val()
                var course_name = $('#course_name').val()
                var course_type = $('#course_type').val()
                var course_duration = $('#course_duration').val()

                if(course_code == ''){
                    $('#course_code').addClass('is-invalid')
                }else{
                    $('#course_code').removeClass('is-invalid')
                }
                if(course_name == ''){
                    $('#course_name').addClass('is-invalid')
                }else{
                    $('#course_name').removeClass('is-invalid')
                }
                if(course_type == null || course_type == 0){
                    $('#course_type').addClass('is-invalid')
                }else{
                    $('#course_type').removeClass('is-invalid')
                }
                if(course_duration == ''){
                    $('#course_duration').addClass('is-invalid')
                }else{
                    $('#course_duration').removeClass('is-invalid')
                }

                $.ajax({
                    url: "/tesda/course_setup/add/course",
                    type: "GET",
                    data: {
                        course_code: course_code,
                        course_name: course_name,
                        course_type: course_type,
                        course_duration: course_duration,
                    },
                    success: function(data) {
                        $('#new_course').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Course Added!'
                        })
                        generate_courses()
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                Toast.fire({
                                    type: 'error',
                                    title: value[0]
                                });
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong!',
                                text: 'Please try again later.'
                            });
                        }
                    }
                })
            })
            var course_id;
            $(document).on('click', '.edit_course', function() {
                course_id = $(this).data('id')
                $('#new_course').modal('show')
                $('#save_course').addClass('d-none')
                $('#save_edit_course').removeClass('d-none')
                $('#new_course_title').addClass('d-none')
                $('#update_course_title').removeClass('d-none')

                $.ajax({
                    url: "/tesda/course_setup/get/course",
                    type: "GET",
                    data: {
                        course_id: course_id
                    },
                    success: function(data) {
                        $('#course_code').val(data.course_code)
                        $('#course_name').val(data.course_name)
                        $('#course_duration').val(data.course_duration)
                        setTimeout(() => {
                            $('#course_type').val(data.course_type).trigger('change')
                        }, 500);
                    }
                })
            })

            $(document).on('click', '.delete_course', function() {
                var course_id = $(this).data('id')
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "/tesda/course_setup/delete/course",
                            type: "GET",
                            data: {
                                course_id: course_id
                            },
                            success: function(data) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Course Deleted!'
                                })
                                generate_courses()
                            }
                        })
                    }
                })
            })

            $(document).on('click', '#save_edit_course', function() {
                var course_code = $('#course_code').val()
                var course_name = $('#course_name').val()
                var course_type = $('#course_type').val()
                var course_duration = $('#course_duration').val()
                $.ajax({
                    url: "/tesda/course_setup/update/course",
                    type: "GET",
                    data: {
                        course_id: course_id,
                        course_code: course_code,
                        course_name: course_name,
                        course_type: course_type,
                        course_duration: course_duration,
                    },
                    success: function(data) {
                        $('#new_course').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Course Updated!'
                        })
                        generate_courses()
                    }
                })
            })
            var duration_hours;
            $(document).on('click', '#show_course_details', function() {
                course_id = $(this).data('id')
                $('#course_units_modal').modal('show')

                $.ajax({
                    url: "/tesda/course_setup/get/course",
                    type: "GET",
                    data: {
                        course_id: course_id
                    },
                    success: function(data) {
                        $('#course_title_modal').text(data.course_code + ' - ' + data.course_name + ' ( ' + data.course_duration + ' )')
                        get_course_series()
                        get_signatories()
                        duration_hours = parseFloat(data.course_duration.replace(/\D/g, ''))
                    }
                })
            })

            $('#course_series_add').on('click', function() {
                $('#course_competency_series').modal('show')
            })

            function get_course_series(){
                $.ajax({
                    url: "/tesda/course_setup/get/series",
                    type: "GET",
                    data: {
                        course_id: course_id
                    },
                    success: function(data) {
                        if(data.length != 0){
                            $('#course_units_table').removeClass('d-none')
                            $('#series_active_label').removeClass('d-none')
                            $('#series_active_state').removeClass('d-none')
                            $('#add_compentency').removeClass('d-none')
                            $('#signatories_div').removeClass('d-none')
                            $('#series_info').addClass('d-none')
                            $('.series_tab_li').remove() 
                            $.each(data, function(index, series) {
                                if(series.active == 0){
                                    $('#course_series_tab_add').before(`
                                        <li class="nav-item series_tab_li mr-1" role="presentation " id="" data-id="${series.id}">
                                            <a class="nav-link series_tab text-secondary" data-toggle="tab" id="" href="#" role="tab" data-id="${series.id}">${series.description}</a>
                                        </li>
                                    `)
                                }else{
                                    $('#course_series_tab_add').before(`
                                        <li class="nav-item series_tab_li mr-1" role="presentation " id="" data-id="${series.id}">
                                            <a class="nav-link series_tab text-secondary" data-toggle="tab" id="" href="#" role="tab" data-active="1" data-id="${series.id}">${series.description} <span class="badge badge-pill badge-success" style="font-size: 10px;">Active</span> </a>
                                        </li>
                                    `)
                                }
                                
                            })
                            
                            setTimeout(() => {
                                let lastTab = $('.series_tab_li a.series_tab[data-active="1"]');                            
                                lastTab.trigger('click'); 
                            }, 500);
                        }
                    }
                })
                
            }

            $(document).on('click', '#save_course_series', function() {
                var course_series = $('#course_series').val()
                var series_active = $('#series_active').prop('checked') ? 1 : 0
                $.ajax({
                    url: "/tesda/course_setup/add/series",
                    type: "GET",
                    data: {
                        course_id: course_id,
                        course_series: course_series,
                        series_active: series_active,
                    },
                    success: function(data) {
                        $('#course_competency_series').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Course Series Added!'
                        })
                        get_course_series()

                    }
                })
                
            })

            $(document).on('click', '#add_compentency', function() {
                if(series_id == undefined){
                    Toast.fire({
                        type: 'error',
                        title: 'Please select a course series!'
                    })
                    
                }else{
                    $('#add_competency_modal').modal('show')
                    $('#save_edit_competency').addClass('d-none')
                    $('#save_competency').removeClass('d-none')
                    get_competency_type()
                }
            })

            var series_id;
            $(document).on('click', '.series_tab', function() {
                series_id = $(this).data('id')
                var active = $(this).data('active')
                if(active == 1){
                    $('#series_active_state').prop('checked', true)
                }else{
                    $('#series_active_state').prop('checked', false)
                }
                get_competencies()         
            })

            $(document).on('click', '#edit_series', function() {
                
                $('#open_series_modal').modal('show')
                $.ajax({
                    url: "/tesda/course_setup/get/series",
                    type: "GET",
                    data: {
                        course_id: course_id,
                    },
                    success: function(data) {
                        $('#series_table_list').empty()
                        $.each(data, function(index, series) {
                            $('#series_table_list').append(`
                                <tr>
                                    <td class="series_desc" data-id="${series.id}">${series.description}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit_series_here" data-id="${series.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger delete_series" data-id="${series.id}"><i class="fas fa-trash"></i></button>
                                        <button class="btn btn-sm btn-success save_edit_series_here d-none" data-id="${series.id}"><i class="fas fa-save"></i></button>
                                        <button class="btn btn-sm btn-danger close_edit_series d-none" data-id="${series.id}"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            `)
                        })
                    }
                })
            })

            var unsave_series_desc;
            $(document).on('click', '.edit_series_here', function() {
                var series_id = $(this).data('id')
                $('.save_edit_series_here[data-id='+series_id+']').removeClass('d-none')
                $('.close_edit_series[data-id='+series_id+']').removeClass('d-none')
                $('.delete_series[data-id='+series_id+']').addClass('d-none')
                $('.edit_series_here[data-id='+series_id+']').addClass('d-none')
                $('.series_desc[data-id='+series_id+']').attr('contenteditable', true).addClass('bg-info')

                unsave_series_desc = 1
            })

            $(document).on('click', '.close_edit_series', function() {
                var series_id = $(this).data('id')
                if(unsave_series_desc == 1){
                    Toast.fire({
                        type: 'error',
                        title: 'Unsaved changes!'
                    })
                }else{
                    $('.save_edit_series_here[data-id='+series_id+']').addClass('d-none')
                    $('.close_edit_series[data-id='+series_id+']').addClass('d-none')
                    $('.delete_series[data-id='+series_id+']').removeClass('d-none')
                    $('.edit_series_here[data-id='+series_id+']').removeClass('d-none')
                    $('.series_desc[data-id='+series_id+']').removeAttr('contenteditable')
                }
            })

            $(document).on('click', '.save_edit_series_here', function() {
                var series_id = $(this).data('id')
                $.ajax({
                    url: "/tesda/course_setup/update/series",
                    type: "GET",
                    data: {
                        series_id: series_id,
                        series_desc: $('.series_desc[data-id='+series_id+']').text(),
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Course Series Updated!'
                        })
                        unsave_series_desc = 0
                        $('.save_edit_series_here[data-id='+series_id+']').addClass('d-none')
                        $('.close_edit_series[data-id='+series_id+']').addClass('d-none')
                        $('.delete_series[data-id='+series_id+']').removeClass('d-none')
                        $('.edit_series_here[data-id='+series_id+']').removeClass('d-none')
                        $('.series_desc[data-id='+series_id+']').removeAttr('contenteditable')
                        $('.series_desc[data-id='+series_id+']').removeClass('bg-info')
                        get_course_series()
                    }
                })
            })

            $(document).on('click', '#series_active_state', function() {
                var series_active = $('#series_active_state').prop('checked') ? 1 : 0
                $.ajax({
                    url: "/tesda/course_setup/update/series/active",
                    type: "GET",
                    data: {
                        course_id: course_id,
                        series_id: series_id,
                        series_active: series_active,
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Course Series Updated!'
                        })
                        get_course_series()
                    }
                })
            })

            $(document).on('click', '#save_competency', function() {
                var competency_type = $('#competency_type').val()
                var competency_code = $('#competency_code').val()
                var competency_hours = $('#competency_hours').val()
                var competency_description = $('#competency_description').val()
                $.ajax({
                    url: "/tesda/course_setup/add/competency",
                    type: "GET",
                    data: {
                        series_id: series_id,
                        competency_type: competency_type,
                        competency_code: competency_code,
                        competency_hours: competency_hours,
                        competency_description: competency_description,
                    },
                    success: function(data) {
                        $('#competency_type').val('')
                        $('#competency_code').val('')
                        $('#competency_hours').val('')
                        $('#competency_description').val('')
                        Toast.fire({
                            type: 'success',
                            title: 'Competency Added!'
                        })
                        get_competency_type()
                        get_competencies()
                        
                    }
                })

            })

            function get_competency_type(){
                $.ajax({
                    url: "/tesda/course_setup/get/competency_type",
                    type: "GET",
                    success: function(data) {
                        $('#competencies_available').empty('')
                        $.each(data, function(index, type) {
                            $('#competencies_available').append(`<button class="btn btn-sm btn-primary mr-1 my-1 competency_button">${type.competency_type}</button>`)
                        })
                    }
                })
            }

            function get_competencies(){
                $.ajax({
                    url: "/tesda/course_setup/get/competencies",
                    type: "GET",
                    data: {
                        series_id: series_id
                    },
                    success: function(data) {
                        generate_competencies_datatable(data)
                    }
                })
            }

            $(document).on('click', '.competency_button', function() {
                var val = $(this).text()
                $('#competency_type').val(val)
            })
            
            var competency_id;
            $(document).on('click', '.edit_competency', function() {
                $('#save_edit_competency').removeClass('d-none')
                $('#save_competency').addClass('d-none')
                get_competency_type()
                competency_id = $(this).data('id')
                
                $.ajax({
                    url: "/tesda/course_setup/get/competency",
                    type: "GET",
                    data: {
                        competency_id: competency_id
                    },
                    success: function(data) {
                        $('#competency_type').val(data.competency_type)
                        $('#competency_code').val(data.competency_code)
                        $('#competency_hours').val(data.hours)
                        $('#competency_description').val(data.competency_desc)
                    }
                })
            })
            $(document).on('click', '.delete_competency', function() {
               var competency_id = $(this).data('id')
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                            $.ajax({
                            url: "/tesda/course_setup/delete/competency",
                            type: "GET",
                            data: {
                                competency_id: competency_id
                            },
                            success: function(data) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Competency Deleted!'
                                })
                                get_competencies()
                            }
                        })
                    }
                })
                
            })
            
            $(document).on('click', '#save_edit_competency', function() {
                var competency_type = $('#competency_type').val()
                var competency_code = $('#competency_code').val()
                var competency_hours = $('#competency_hours').val()
                var competency_description = $('#competency_description').val()
                console.log(competency_id);
                
                $.ajax({
                    url: "/tesda/course_setup/update/competency",
                    type: "GET",
                    data: {
                        competency_id: competency_id,
                        competency_type: competency_type,
                        competency_code: competency_code,
                        competency_hours: competency_hours,
                        competency_description: competency_description,
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Competency Updated!'
                        })
                        get_competencies()
                    }
                })
            })

            var unsaved = 0;
            $(document).on('click', '#update_signature', function() {
                unsaved = 1;
                $('#update_signature').addClass('d-none')
                $('#save_update_signature').removeClass('d-none')
                $('#add_signatories').removeClass('d-none')
                $('.signatory_column_2').remove()
                $.ajax({
                    url: "/tesda/course_setup/get/signatories",
                    type: "GET",
                    data: {
                        course_id: course_id
                    },
                    success: function(data) {
                        $.each(data, function(index, signatory) {
                            $('#course_signatories').append(`
                                <div class="col-md-2 signatory_column" id="">
                                    <div class="signatories_created">
                                        <div class="text-right"><button class="btn btn-sm remove_signatory text-danger" data-id="${signatory.id}" style="font-size: 9px!important">Delete <i class="fa fa-trash "></i></button></div>
                                        <input class="form-control form-control-sm signatory_name text-center" data-id="${signatory.id}" value="${signatory.signatory_name}" type="text"></input>
                                        <p class="mx-0 my-1" style="border-bottom: 1px solid black"></p>
                                        <input class="form-control form-control-sm signatory_title text-center" value="${signatory.signatory_title}" type="text"></input>
                                    </div>
                                </div>
                            `)
                        })
                    }
                })
            })

            $(document).on('click', '#save_update_signature', function() {
                $('#update_signature').removeClass('d-none')
                $('#save_update_signature').addClass('d-none')
                $('#add_signatories').addClass('d-none')
                
                var signatories = []
                $.each($('.signatories_created'), function(index, signatory) {
                    var array = []
                    var name = $(signatory).find('.signatory_name').val() || ''
                    var title = $(signatory).find('.signatory_title').val() || ''
                    var id = $(signatory).find('.signatory_name').data('id') || 0
                    if(name != '' && title != ''){
                        array.push(id)
                        array.push(name)
                        array.push(title)
                        signatories.push(array)
                    }
                })

                console.log(signatories);
                
                $.ajax({
                    url: "/tesda/course_setup/update/signatories",
                    type: "GET",
                    data: {
                        course_id: course_id,
                        signatories: signatories
                    },
                    success: function(data) {
                        $('.signatory_column').remove()
                        get_signatories()
                        unsaved = 0
                    }
                })
                
            })

            function get_signatories(){
                $.ajax({
                    url: "/tesda/course_setup/get/signatories",
                    type: "GET",
                    data: {
                        course_id: course_id
                    },
                    success: function(data) {
                        $.each(data, function(index, signatory) {
                            $('#course_signatories').append(`
                                <div class="col-md-2 signatory_column_2" id="">
                                    <div class="signatories_created" style="margin-top: 2.18rem!important">
                                        <p class="m-0 text-center" >${signatory.signatory_name}</p>
                                        <p class="mx-0 my-1" style="border-bottom: 1px solid black"></p>
                                        <p class="m-0 text-center">${signatory.signatory_title}</p>
                                    </div>
                                </div>
                            `)
                        })
                    }
                })
            }

            $(document).on('click', '#add_signatories', function() {
                $('#course_signatories').append(`
                    <div class="col-md-2 signatory_column" id="">
                        <div class="signatories_created">
                            <div class="text-right"><button class="btn btn-sm remove_signatory text-danger" style="font-size: 9px!important">Delete <i class="fa fa-trash "></i></button></div>
                            <input class="form-control form-control-sm signatory_name text-center" type="text"></input>
                            <p class="mx-0 my-1" style="border-bottom: 1px solid black"></p>
                            <input class="form-control form-control-sm signatory_title text-center" type="text"></input>
                        </div>
                    </div>
                `)
            })

            $(document).on('click', '.remove_signatory', function() {
                var signatory_id = $(this).data('id')
                var button = $(this)
                $.ajax({
                    url: "/tesda/course_setup/remove/signatory",
                    type: "GET",
                    data: {
                        signatory_id: signatory_id
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Signatory removed successfully'
                        })
                        button.closest('.col-md-2').remove()
                    }
                })
            })

            $(document).on('click', '#close_course_units', function() {
                if(unsaved == 0){
                    if(totalHours === duration_hours){
                        $('#course_units_modal').modal('hide')
                    }else{
                        if ($('.series_tab_li').length > 0){
                            Swal.fire({
                            title: 'Are you sure?',
                            text: "Competency Total Hours is Not Equal to Duration Hours",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, close it!'
                            }).then((result) => {
                                if (result.value) {
                                    $('#course_units_modal').modal('hide')
                                }
                            })
                        }else{
                            $('#course_units_modal').modal('hide')
                        }
                        
                    }
                }else{
                    Swal.fire({
                        type: 'warning',
                        title: 'Are you sure?',
                        text: 'You have unsaved changes on Course Signatories',
                        confirmButtonText: 'Okay',
                    })
                }


                
                
            })

            var totalHours;
            function generate_courses_datatable(data){
                $('#course_table').DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        { data: 'course_code' },
                        { data: 'course_name' },
                        { data: 'description' },
                        { data: 'course_duration' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle')
                            }
                        },
                        
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#" id="show_course_details" data-id="${rowData.id}">${rowData.course_name}</a>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(
                                    `
                                    <button class="btn btn-sm px-0 edit_course" id="" data-toggle="modal"data-id="${rowData.id}"><i class="fas fa-edit text-primary"></i></button>
                                    <button class="btn btn-sm px-0 delete_course" id=""  data-id="${rowData.id}"><i class="fas fa-trash text-danger" style="font"></i></button>
                                    `
                                ).addClass('text-center align-middle')
                            }
                        },
                    ]
                })

            }

            function generate_competencies_datatable(competencies){
                $('#course_units_body').empty('')
                $.each(competencies, function(index, competency_type) {
                    $('#course_units_body').append(`
                        <tr style="font-size: 12px">
                            <td></td>
                            <td class="font-weight-bold">${competency_type.competency_type}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    `)
                    $.each(competency_type.other_data, function(index, competency) {
                        $('#course_units_body').append(`
                        <tr style="font-size: 12px">
                            <td>${competency.competency_code}</td>
                            <td>${competency.competency_desc}</td>
                            <td>${competency.hours}</td>
                            <td class="text-center">
                                <button class="btn btn-sm px-0 edit_competency" id="" data-toggle="modal" data-target="#add_competency_modal" data-id="${competency.id}"><i class="fas fa-edit text-primary"></i></button>
                                <button class="btn btn-sm px-0 delete_competency" id="" data-id="${competency.id}"><i class="fas fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                    `)
                    })
                })
                totalHours = 0;
                $('#course_units_body tr').each(function() {
                    const hours = parseFloat($(this).find('td:nth-child(3)').text());
                    if (!isNaN(hours)) {
                        totalHours += hours;
                    }
                });

                $('#competency_total_hours').text(totalHours);

                $('#course_units_body').append(`
                    
                `)
            }

            $('#new_course').on('hidden.bs.modal', function (e) {
                $('#course_code').val('')
                $('#course_name').val('')
                $('#course_duration').val('')
                $('#course_type').val('').trigger('change')
            })

            $('#add_competency_modal').on('hidden.bs.modal', function (e) {
                $('#competency_type').val('')
                $('#competency_code').val('')
                $('#competency_hours').val('')
                $('#competency_description').val('')
            })

            $('#course_competency_series').on('hidden.bs.modal', function (e) {
                $('#course_series').val('')
                $('#course_series_add').removeClass('active')
                let lastTab = $('.series_tab_li a.series_tab[data-active="1"]');                           
                lastTab.trigger('click'); 
            })

            $('#course_units_modal').on('hidden.bs.modal', function (e) {
                $('#course_units_body').empty('')
                $('.signatory_column').remove('')
                $('.signatory_column_2').remove('')
                $('.series_tab_li').remove('')
                series_id = undefined
                $('#competency_total_hours').text(0)
                $('#series_info').removeClass('d-none')
                $('#signatories_div').addClass('d-none')
                $('#add_compentency').addClass('d-none')
                $('#series_active_state').addClass('d-none')
                $('#series_active_label').addClass('d-none')
                $('#course_units_table').addClass('d-none')
            })
            // Course

        })
    </script>
@endsection
