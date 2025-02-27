@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')


    @php

    $buildings = DB::table('building')->where('deleted', 0)->get();

    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Batches</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Batches</li>
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
                                        <div class="col-md-2 col-sm-12 ml-3">
                                            <label for="" class="mb-0">Courses</label>
                                            <select class="form-control form-control-sm select2 course" id="course_filter" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label for="" class="mb-0">Courses Type</label>
                                            <select class="form-control form-control-sm select2 " id="course_type_filter" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label for="" class="mb-0">Course Duration</label>
                                            <select class="form-control form-control-sm select2 " id="course_duration_filter" style="width: 100%;">
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
                            <h3 class="card-title fw-1000">Batches</h3>
    
                        </div>
    
                        <div class="card-body p-2 ">
                            <div class="mt-2 p-2 d-flex flex-row">
                                <div class="">
                                    <button class="btn btn-success btn-sm" id="add_batches" data-toggle="modal">Add Batches</button>
                                </div>
                            </div>
                            <div class="row px-2">
                                <div class="col-md-12" style="font-size:.8rem">
                                    <table class="table table-sm table-striped display table-bordered table-responsive-sm"
                                        id="batch_table" width="100%">
                                        <thead>
                                            <tr class="font-20">
                                                <th>Batch Description</th>
                                                <th>Course Duration</th>
                                                <th class="">Specialization</th>
                                                <th class="">Enrolled</th>
                                                <th class="">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">
    
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

    <div class="modal fade" id="new_batches" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">New Batches</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="mb-0">Batch Description</label>
                            <input type="text" class="form-control form-control-sm" id="batch_name" placeholder="Enter Batch Name">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-7">
                            <label for="" class="mb-0">Specialization/Course</label>
                            <select class="form-control form-control-sm select2" id="batch_specialization">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-5" id="batch_course_series">
                            <label for="" class="mb-0">Course Series Competency</label>
                            <select class="form-control form-control-sm select2" id="batch_series" >
                            </select>
                        </div>
                        
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-7">
                            <label for="" class="mb-0">Course Duration</label>
                            <input type="text" class="form-control form-control-sm" id="batch_duration">

                        </div>
                        <div class="col-md-5">
                            <label for="" class="mb-0">Capacity</label>
                            <input type="number" min="0" class="form-control form-control-sm" id="batch_capacity" placeholder="40">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-7">
                            <label for="" class="mb-0">Building</label>
                            <select class="form-control form-control-sm select2" id="buildingadd">
                                <option value=""></option>
                                <option value="add1">Add Building</option>
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="" class="mb-0">Room</label>
                            <select class="form-control form-control-sm select2" id="room" >
                                <option value=""></option>
                                <option value="add"><i class="fa fa-plus"></i> Add Room</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <label for="" class="mb-0">Grade Template</label>
                            <select class="form-control form-control-sm select2" id="grade_template" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-5 d-flex flex-row">
                            <input type="checkbox" id="batch_active" style="margin-right: 12px;cursor:pointer;">
                            <label class="my-0" style="font-size: 14px;margin-top:15px;"> Active </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success" style="width:20% !important" id="save_batches">Save</button>
                    <button type="button" class="btn btn-sm btn-primary d-none" style="width:20% !important" id="save_edit_batches">Update</button>
                </div>
            </div>
        </div>
    </div>

    

    

    <div class="modal fade" id="batch_schedule" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl " role="document">
            <div class="modal-content" style="height: 700px!important">
                <div class="card-header  bg-secondary">
                    <h5 class="card-title mb-0" id="batch_modal_title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" style="font-size: 12px!important">
                        <div class="col-md-3">
                            <label for="" class="mb-0">Course Duration</label>
                            <input type="text" class="form-control form-control-sm" id="batch_duration_modal">
                        </div>
                        <div class="col-md-3">
                            <label for="" class="mb-0">Building</label>
                            <select class="form-control form-control-sm select2" id="buildingadd_modal">
                                <option value=""></option>
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="mb-0">Room</label>
                            <select class="form-control form-control-sm select2" id="room_modal" >
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-5">
                                <label for="" class="mb-0">Capacity</label>
                                <input type="number" min="0" class="form-control form-control-sm" id="batch_capacity_modal" placeholder="40">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2" style="max-height: 500px!important">
                        <table class="table table-sm display table-bordered" id="section_table" width="100%" >
                            <thead>
                                <tr class="" style="font-size: 12px!important; background-color: rgb(244, 244, 244)">
                                    <th width="40%">Competency Description</th>
                                    <th width="10%">Hrs</th>
                                    <th width="20%">Schedule</th>
                                    <th width="10%">Trainer</th>
                                    <th width="10%">Room</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="batch_sched_table">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="font-weight-bold text-right">Total Hours</td>
                                    <td class="font-weight-bold" id="batch_schedule_total_hours"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new_schedule" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header bg-secondary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">Add Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="competency_label"></div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="" class="mb-0">Date</label>
                            <input type="text" class="form-control form-control-sm" id="sched_duration">
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="" class="mb-0 text-sm">Start Time</label>
                                <input type="time" class="form-control form-control-sm" value="07:00"
                                    id="stime">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label for="etime" class="mb-0 text-sm">End Time</label>
                                <input type="time" class="form-control form-control-sm" min="07:00:00"
                                 id="etime">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="" class="mb-0">Building</label>
                            <select class="form-control form-control-sm select2" id="buildingadd_sched">
                                <option value=""></option>
                                <option value="add1">Add Building</option>
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="mb-0">Room</label>
                            <select class="form-control form-control-sm select2" id="room_sched" >
                                <option value=""></option>
                                <option value="add"><i class="fa fa-plus"></i> Add Room</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="mb-0">Trainer</label>
                            <select class="form-control form-control-sm select2" id="trainer_sched" >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success" style="width:20% !important" id="save_schedule">Save</button>
                    <button type="button" class="btn btn-sm btn-primary d-none" style="width:20% !important" id="save_edit_schedule">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addbuilding" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class=" modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-secondary">
                    <h6 class="modal-title">
                        <span class="mt-1" id="">Create Building</span>
                    </h6>

                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Add Building</label>
                        <input type="text" id="add_building" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="">Capacity</label>
                        <input type="number" min="0" id="building_capacity" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success" id="add_building_button">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addroom" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class=" modal-dialog ">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-secondary">
                    <h6 class="modal-title">
                        <span class="mt-1" id="">Create Room</span>
                    </h6>

                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Select Building</label>
                        <select name="" id="select_building" class=" form-control form-control-sm select2">
                            @foreach ($buildings as $item)
                                <option value="{{ $item->id }}">{{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Add Room</label>
                        <input type="text" id="add_room" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="">Capacity</label>
                        <input type="number" min="0" id="room_capacity" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success" id="add_room_button">Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            //Select 2 Initialization ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $('#course_filter').select2({
                placeholder: 'Select Course',
                allowClear: true
            })
            $('#course_duration_filter').select2({
                placeholder: 'Select Course Duration',
                allowClear: true
            })
            $('#course_type_filter').select2({
                placeholder: 'Select Course Type',
                allowClear: true
            })
            $('#grade_template').select2({
                placeholder: 'Select Grade Template',
                allowClear: true,
                ajax: {
                    url: '/tesda/batch_setup/get/ecr_template',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.map(item => {
                                return { id: item.id, text: item.gradDesc }
                            })
                        };
                    }
                }
            })
            
            $('#trainer_sched').select2({
                placeholder: 'Select Trainer',
                allowClear: true
            })
            $('#room_modal').select2({
                placeholder: 'Select Room',
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
            $('#room_sched').select2({
                placeholder: 'Select Room',
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
            $('#buildingadd').select2({
                placeholder: 'Select Building',
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) {
                        return data.text; // Handles the placeholder
                    }

                    // Check if the option is "Add Building"
                    if (data.id === 'add1') {
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
            $('#buildingadd_modal').select2({
                placeholder: 'Select Building',
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) {
                        return data.text; // Handles the placeholder
                    }

                    // Check if the option is "Add Building"
                    if (data.id === 'add1') {
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
            $('#buildingadd_sched').select2({
                placeholder: 'Select Building',
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) {
                        return data.text; // Handles the placeholder
                    }

                    // Check if the option is "Add Building"
                    if (data.id === 'add1') {
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
            $('#specialization').select2({
                placeholder: 'Select Room',
                allowClear: true
            })
            $('#batch_series').select2({
                placeholder: 'Select Course Series',
                allowClear: true
            })
            $('#batch_specialization').select2({
                placeholder: 'Select Specialization/Course',
                allowClear: true
            })

            $('#batch_duration').daterangepicker({
                locale: {
                    format: 'MM/DD/YYYY',
                    cancelLabel: 'Clear',
                },
                autoUpdateInput: false,
                opens: 'right',
            });

            $('#batch_duration').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('#batch_duration').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            

            $('#batch_duration_modal').daterangepicker({
                locale: {
                    format: 'MM/DD/YYYY',
                    cancelLabel: 'Clear',
                },
                autoUpdateInput: false,
                opens: 'right',
            });

            $('#batch_duration_modal').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'))
                .trigger('change');
            });

            $('#batch_duration_modal').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            $('#sched_duration').daterangepicker({
                locale: {
                    format: 'MM/DD/YYYY',
                    cancelLabel: 'Clear',
                },
                autoUpdateInput: false,
                opens: 'right',
            });

            $('#sched_duration').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'))
                .trigger('change');
            });

            $('#sched_duration').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            //Select 2 Initialization ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            
            //Batch Filter --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $(document).on('change', '#course_filter', function() {
                get_batches()
            })

            $(document).on('change', '#course_type_filter', function() {
                get_batches()
            })
            $(document).on('change', '#course_duration_filter', function() {
                get_batches()
            })

            get_batches()
            function get_batches() {
                
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batches',
                    data: {
                        course_id: $('#course_filter').val(),
                        course_type : $('#course_type_filter').val(),
                        course_duration : $('#course_duration_filter').val()
                    },
                    success: function(data) {
                        display_batches(data)
                    }
                })
            }
            get_batches_duration()
            function get_batches_duration(){
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batches/duration',
                    success: function(data) {
                        $('#course_duration_filter').empty()
                        $('#course_duration_filter').val('').trigger('change')
                        $('#course_duration_filter').append('<option value=""></option>');
                        $.each(data, function(index, batches){
                            $('#course_duration_filter').append(`
                                <option value="${batches.date_range}">${batches.date_range}</option>
                            `)
                        })
                    }
                })
            }

            get_courses()
            function get_courses(){
                $.ajax({
                    type:'get',
                    url: '/tesda/course_setup/get/courses',
                    data: {

                    },
                    success: function(data) {
                        $('#course_filter').empty();
                        $('#course_filter').append('<option value=""></option>');
                        $.each(data, function(index, course) {
                            $('#course_filter').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                        });
                    }
                })
            }

            get_course_type()
            function get_course_type(){
                $.ajax({
                    type:'get',
                    url: '/tesda/course_setup/get/course_type',
                    success: function(data) {
                        $('#course_type_filter').empty()
                        $('#course_type_filter').append(` 
                            <option value=""></option>
                        `)
                        $.each(data, function(key, courseType) {
                            $('#course_type_filter').append(`
                                <option value="${courseType.id}">${courseType.description}</option>
                            `);
                        })
                    }
                })
            }
            //Batch Filter --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            
            
            //New Batch Modal Dropdown Population ---------------------------------------------------------------------------------------------------------------------------------------------------------------
            var building_val;
            $('#buildingadd').change(function() {
                var val = $(this).find(':selected').val()
                building_val = val
                if(val == 'add1'){
                    $('#addbuilding').modal('show')
                    $(this).val('').trigger('change');
                }else{
                    get_rooms()
                }
            })

            $('#room').change(function() {
                var value = $('#room').find(':selected').data('id')
                var val = $('#room').find(':selected').val()
                if(val == 'add'){
                    $('#addroom').modal('show')
                    $('#room').val('').trigger('change');
                }else{
                    if($('#batch_capacity').val() == ''){
                        $('#batch_capacity').val(value)
                    }
                }
            })
            
            $('#buildingadd_sched').change(function() {
                var val = $(this).find(':selected').val()
                building_val = val
                if(val == 'add1'){
                    $('#addbuilding').modal('show')
                    $(this).val('').trigger('change');
                }else{
                    get_room3()
                }
            })

            $('#room_sched').change(function() {
                var value = $('#room_sched').find(':selected').data('id')
                var val = $('#room_sched').find(':selected').val()
                if(val == 'add'){
                    $('#addroom').modal('show')
                    $('#room_sched').val('').trigger('change');
                }
            })

            $(document).on('click', '#add_building_button', function() {
                var building = $('#add_building').val()
                var capacity = $('#building_capacity').val()
                $.ajax({
                    url: "/college/section/add/building",
                    type: "GET",
                    data: {
                        building: building,
                        capacity: capacity,
                    },
                    success: function(data) {
                        $('#add_building').val('')
                        $('#building_capacity').val('')
                        $('#buildingadd').append(`<option value="${data.id}">${data.description}</option>`)
                        $('#buildingadd').trigger('change')
                        $('#buildingadd_sched').append(`<option value="${data.id}">${data.description}</option>`)
                        $('#buildingadd_sched').trigger('change')
                        $('#select_building').append(`<option value="${data.id}" data-id="${data.capacity}">${data.description}</option>`)
                        $('#select_building').trigger('change')

                        Toast.fire({
                            type: 'success',
                            title: 'Building Added!'
                        })
                    }
                })
            })

            $(document).on('click', '#add_room_button', function() {
                var room = $('#add_room').val()
                var capacity = $('#room_capacity').val()
                var buildingid = $('#select_building').val()
                $.ajax({
                    url: "/college/section/add/room",
                    type: "GET",
                    data: {
                        room: room,
                        capacity: capacity,
                        buildingid: buildingid
                    },
                    success: function(data) {
                        get_rooms()
                        Toast.fire({
                            type: 'success',
                            title: 'Room Added!'
                        })
                        $('#add_room').modal('hide')
                        $('#buildingadd_sched').trigger('change')
                    }
                })
            })

            function get_rooms(){
                var buildingid = $('#buildingadd').val()
                $.ajax({
                    type: 'get',
                    url: '/college/section/get/rooms',
                    data: {
                        buildingid: buildingid
                    },
                    success: function(data) {
                        $('.room_values').remove()
                        $.each(data, function(index, room) {
                            $('#room').append(`<option class="room_values" value="${room.id}" data-id="${room.capacity}">${room.roomname}</option>`)
                        })
                        $('#room').trigger('change')
                    }
                })
            }

            function get_room2(){
                var buildingid = $('#buildingadd_modal').val()
                $.ajax({
                    type: 'get',
                    url: '/college/section/get/rooms',
                    data: {
                        buildingid: buildingid
                    },
                    success: function(data) {
                        $('.room_values').remove()
                        $.each(data, function(index, room) {
                            $('#room_modal').append(`<option class="room_values" value="${room.id}" data-id="${room.capacity}">${room.roomname}</option>`)
                        })
                    }
                })
            }
            function get_room3(){
                var buildingid = $('#buildingadd_sched').val()
                $.ajax({
                    type: 'get',
                    url: '/college/section/get/rooms',
                    data: {
                        buildingid: buildingid
                    },
                    success: function(data) {
                        $('.room_values').remove()
                        $.each(data, function(index, room) {
                            $('#room_sched').append(`<option class="room_values" value="${room.id}" data-id="${room.capacity}">${room.roomname}</option>`)
                        })
                    }
                })
            }
            $(document).on('change', '#batch_specialization', function() {
                var courseid = $(this).val()
                $.ajax({
                    type: 'get',
                    url: '/tesda/course_setup/get/series',
                    data: {
                        course_id: courseid
                    },
                    success: function(data) {
                        $('#batch_series').empty()
                        $('#batch_series').append('<option value=""></option>')
                        $.each(data, function(index, series) {
                            $('#batch_series').append('<option value="' + series.id + '">' + series.description + '</option>')
                        })
                    }
                })
            })
            //New Batch Modal Dropdown Population ---------------------------------------------------------------------------------------------------------------------------------------------------------------
            
            
            //Batch CRUD ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
           

            $(document).on('click', '#save_batches', function() {
                var batch_name = $('#batch_name').val()
                var batch_course = $('#batch_specialization').val()
                var batch_capacity = $('#batch_capacity').val()
                var batch_duration = $('#batch_duration').val()
                var batch_building = $('#buildingadd').val()
                var batch_room = $('#room').val()
                var is_active = $('#batch_active').prop('checked') ? 1 : 0
                var batch_series = $('#batch_series').val()
                var grade_template = $('#grade_template').val()
                console.log(batch_course);
                
                if(batch_name == ''){
                    $('#batch_name').addClass('is-invalid')
                }else{
                    $('#batch_name').removeClass('is-invalid')
                }
                if (!batch_course || batch_course == '') {
                    $('#batch_specialization').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                    $('#batch_specialization').addClass('is-invalid')
                        .next('.select2-container')
                        .after('<div class="invalid-feedback">This field is required.</div>');
                } else {
                    $('#batch_specialization').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                }
                if(batch_duration == ''){
                    $('#batch_duration').addClass('is-invalid')
                }else{
                    $('#batch_duration').removeClass('is-invalid')
                }

                if (!batch_series || batch_series == '') {
                    $('#batch_series').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                    $('#batch_series').addClass('is-invalid')
                        .next('.select2-container')
                        .after('<div class="invalid-feedback">This field is required.</div>');
                } else {
                    $('#batch_series').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                }

                if(batch_building == null || batch_building == ''){
                    $('#buildingadd').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                    $('#buildingadd').addClass('is-invalid')
                        .next('.select2-container')
                        .after('<div class="invalid-feedback">This field is required.</div>');
                } else {
                    $('#buildingadd').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                }

                if(batch_room == null || batch_room == ''){
                    $('#room').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                    $('#room').addClass('is-invalid')
                        .next('.select2-container')
                        .after('<div class="invalid-feedback">This field is required.</div>');
                } else {
                    $('#room').removeClass('is-invalid')
                        .next('.select2-container').next('.invalid-feedback').remove();
                }


                $.ajax({
                    url: "/tesda/batch_setup/add/batch",
                    type: "GET",
                    data: {
                        batch_name: batch_name,
                        batch_course: batch_course,
                        batch_capacity: batch_capacity,
                        batch_duration: batch_duration,
                        batch_building: batch_building,
                        batch_room: batch_room,
                        is_active: is_active,
                        batch_series: batch_series,
                        grade_template: grade_template
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Batch Added!'
                        })
                        $('#new_batches').modal('hide')
                        get_batches()
                        get_batches_duration()
                    },error: function(xhr) {
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

            $(document).on('click', '.edit_batches', function() {
                $('#new_batches').modal('show')
                $('#save_batches').addClass('d-none')
                $('#save_edit_batches').removeClass('d-none')
                var batch_id = $(this).data('id')
                $('#save_edit_batches').attr('data-id', batch_id)
                $('#batch_series').attr('disabled', true)
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batch',
                    data: {
                        batch_id: batch_id
                    },
                    success: function(data) {
                        $('#batch_name').val(data.batch_desc)
                        $('#batch_capacity').val(data.batch_capacity)
                        $('#batch_duration').val(data.date_range)
                        setTimeout(() => {
                            $('#buildingadd').val(data.buildingID).trigger('change')
                            $('#batch_specialization').val(data.course_id).trigger('change')
                        }, 200);
                        setTimeout(() => {
                            $('#room').val(data.roomID).trigger('change')
                        }, 800);
                        $('#batch_active').prop('checked', data.isactive == 1 ? true : false)
                        
                    }
                })
            })

            $(document).on('click', '#save_edit_batches', function() {
                var batch_name = $('#batch_name').val()
                var batch_course = $('#batch_specialization').val()
                var batch_capacity = $('#batch_capacity').val()
                var batch_duration = $('#batch_duration').val()
                var batch_building = $('#buildingadd').val()
                var batch_room = $('#room').val()
                var is_active = $('#batch_active').prop('checked') ? 1 : 0
                var id = $(this).data('id')
                var grade_template = $('#grade_template').val()
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/edit/batch',
                    data: {
                        id: id,
                        batch_name: batch_name,
                        batch_course: batch_course,
                        batch_capacity: batch_capacity,
                        batch_duration: batch_duration,
                        batch_building: batch_building,
                        batch_room: batch_room,
                        is_active: is_active,
                        grade_template: grade_template
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Batch Updated!'
                        })
                        $('#new_batches').modal('hide')
                        get_batches_duration()
                        get_batches()
                    }
                })
            })

            $(document).on('click', '.delete_batches', function() {
                var id = $(this).data('id')
                swal.fire({
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
                            type: 'get',
                            url: '/tesda/batch_setup/delete/batch',
                            data: {
                                id: id
                            },
                            success: function(data) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Batch Deleted!'
                                })
                                get_batches()
                            }
                        })
                    }
                })
                
            })
            //Batch CRUD ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            //Batch Schedule CRUD -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            function show_batch_sched(schedid, compid, compdesc, hours, scheddetailsid, stime, etime, datefrom, dateto, building, room,exist_details,  trainer = null) {
                // get_student(schedid, capacity)
                datefromnew = datefrom ? new Date(datefrom).toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '';
                datetonew = dateto ? new Date(dateto).toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '';
                
                if(datefromnew === datetonew){
                    var date = datefromnew
                } else {
                    var date = datefromnew + ' - ' + datetonew
                }
                
                let existingRow = $('#batch_sched_table').find(`tr[data-id="${compid}"]`);
                if (existingRow.length > 0) {
                    let firstRow = existingRow.first();

                    let currentRowspan = parseInt(firstRow.find('td.rowspan').attr('rowspan')) || 1;
                    firstRow.find('td.rowspan').attr('rowspan', currentRowspan + 1);

                    firstRow.after(`
                            <tr class="appended_row" style="font-size: 12px!important" data-id="${compid}">
                                <td class="align-middle p-2">${date ? date : 'N/A'} <br>${stime ? stime : ''} - ${etime ? etime : ''}</td>
                                <td class="p-2 align-middle">${trainer ? trainer : 'No Assigned Instructor'}</td>
                                <td class="p-2 align-middle" style="white-space: nowrap">${room ? room : 'No Assigned Room'}<br><span style="font-size: 10px"><i>${building ? building : ''}</td>
                                <td class="text-center p-2" style="white-space: nowrap">
                                <button class="btn btn-sm  edit_sched" data-comp="${compdesc}" data-id="${scheddetailsid}" ><i class="fa fa-edit text-primary"></i></button>
                                <button class="btn btn-sm delete_sched" data-subject-id="${compid}"  data-id="${scheddetailsid}"><i class="fa fa-trash text-danger"></i></button>
                                </td>
                            </tr>
                    `);
                } else {       
                        if(exist_details == 0){
                            $('#batch_sched_table').append(`
                                <tr class="appended_row" style="font-size: 12px!important" data-id="${compid}">
                                    <td class="p-2 rowspan align-middle" rowspan="1"><div class="d-flex flex-row justify-content-between">${compdesc} <a href="javascript:void(0)" class="add_comp_sched" data-comp="${compdesc} ( ${hours} hrs. )" data-id="${schedid}"><i class="fas fa-calendar" title="Add Schedule"></i></a></div></td>
                                    <td class=" align-middle p-2 rowspan" rowspan="1">${hours}</td>
                                    <td class="align-middle p-2"></td>
                                    <td class="p-2 align-middle"></td>
                                    <td class="p-2 align-middle" style="white-space: nowrap"></td>
                                    <td class="text-center p-2" style="white-space: nowrap">
                                    </td>
                                </tr>
                            `);
                        }else{
                            $('#batch_sched_table').append(`
                                <tr class="appended_row" style="font-size: 12px!important" data-id="${compid}">
                                    <td class="p-2 rowspan align-middle" rowspan="1"><div class="d-flex flex-row justify-content-between">${compdesc} <a href="javascript:void(0)" class="add_comp_sched" data-comp="${compdesc} ( ${hours} hrs. )" data-id="${schedid}"><i class="fas fa-calendar" title="Add Schedule"></i></a></div></td>
                                    <td class=" align-middle p-2 rowspan" rowspan="1">${hours}</td>
                                    <td class="align-middle p-2">${date ? date : 'N/A'} <br>${stime ? stime : ''} - ${etime ? etime : ''}</td>
                                    <td class="p-2 align-middle">${trainer ? trainer : 'No Assigned Instructor'}</td>
                                    <td class="p-2 align-middle" style="white-space: nowrap">${room ? room : 'No Assigned Room'}<br><span style="font-size: 10px"><i>${building ? building : ''}</td>
                                    <td class="text-center p-2" style="white-space: nowrap">
                                    <button class="btn btn-sm  edit_sched" data-comp="${compdesc}" data-id="${scheddetailsid}"><i class="fa fa-edit text-primary"></i></button>
                                    <button class="btn btn-sm delete_sched" data-subject-id="${compid}"  data-id="${scheddetailsid}"><i class="fa fa-trash text-danger"></i></button>
                                    </td>
                                </tr>
                            `);
                        }                           
                        
                    
                }

            }



            var building_instance;
            var room_instance;
            var batch_id_modal;
            $(document).on('click', '#batch_schedule_details', function() {
                batch_id_modal = $(this).data('id')
                $('#batch_schedule').modal('show')
                
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batch',
                    data: {
                        batch_id: batch_id_modal
                    },
                    success: function(data) {
                        $('#batch_modal_title').text(data.batch_desc + ' ( ' + data.course_name + ' )')
                        $('#batch_duration_modal').val(data.date_range)
                        setTimeout(() => {
                            $('#buildingadd_modal').val(data.buildingID).trigger('change')
                            get_room2();

                        }, 300);
                        setTimeout(() => {
                            $('#room_modal').val(data.roomID).trigger('change')
                        }, 900);
                        $('#batch_capacity_modal').val(data.batch_capacity)
                        building_instance = 0
                        room_instance = 0

                    }
                })
                check_add_competency_sched()
                get_batch_schedule()
            })

            function check_add_competency_sched(){
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/add/batch/schedule/' + batch_id_modal,
                    success: function(data) {
                    }
                })
            }

            function get_batch_schedule(){
                var all_hours;
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batch/schedule',
                    data: {
                        batch_id: batch_id_modal
                    },
                    success: function(data) {
                        $('.appended_row').remove()
                        $.each(data, function(index, competency) {
                            if (competency.scheddetails && competency.scheddetails.length > 0) {
                                $.each(competency.scheddetails, function (index, schedule) {
                                    all_hours += parseFloat(competency.hours)
                                    
                                    show_batch_sched(
                                        competency.id,
                                        competency.competency_id,
                                        competency.competency_desc,
                                        competency.hours,
                                        schedule.id,
                                        schedule.stime,
                                        schedule.etime,
                                        schedule.date_from,
                                        schedule.date_to,
                                        schedule.description,
                                        schedule.roomname,
                                        1,
                                        schedule.trainer_name

                                    );
                                });
                            } else {
                                // Handle the case when there are no scheddetails
                                show_batch_sched(
                                    competency.id,
                                    competency.competency_id,
                                    competency.competency_desc,
                                    competency.hours,
                                    null, // No scheddetails ID
                                    null, // No stime
                                    null, // No etime
                                    null, // No date_from
                                    null, // No date_to
                                    null, // No description
                                    null, // No roomname
                                    0,
                                    null
                                );
                            }
                                   
                        })
                    
                        let totalHours = 0;
                        $('#batch_sched_table tr').each(function() {
                            const hours = parseFloat($(this).find('td:nth-child(2)').text());
                            if (!isNaN(hours)) {
                                totalHours += hours;
                            }
                        });
                        $('#batch_schedule_total_hours').text(totalHours);
                            
                    }
                })
            }

            var comp_schedid;
            $(document).on('click', '.add_comp_sched', function(){
                $('#new_schedule').modal('show')
                conp_schedid = $(this).data('id')
                var comp_label = $(this).data('comp')
                $('#competency_label').html(comp_label)
                get_trainers()
            })

            function get_trainers(){
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/trainers',
                    success: function(data){
                        $('#trainer_sched').empty()
                        $('#trainer_sched').append(`<option value=""></option>`)
                        $.each(data, function(index, trainer){
                            
                            $('#trainer_sched').append(`<option value="${trainer.id}">${trainer.name}</option>`)
                        })
                        
                    }
                })
            }

            $(document).on('click', '#save_schedule', function(){
                var duration = $('#sched_duration').val()
                var stime = $('#stime').val()
                var etime = $('#etime').val()
                var buildingid = $('#buildingadd_sched').val()
                var roomid = $('#room_sched').val()
                var trainer_id = $('#trainer_sched').val();
                
                $.ajax({
                    type: 'GET',
                    url: '/tesda/batch_setup/add/batch/scheduledetail',
                    data:{
                        comp_schedid: conp_schedid,
                        duration: duration,
                        stime: stime,
                        etime: etime,
                        buildingid: buildingid,
                        roomid: roomid,
                        trainer: trainer_id,

                    },success: function(data){
                        $('#new_schedule').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Schedule Added!'
                        })
                        get_batch_schedule()
                    }
                })
            })

            $(document).on('click', '.edit_sched', function(){
                var comp_label = $(this).data('comp')
                var schedid = $(this).data('id')
                console.log(schedid,'schedid');
                
                $('#new_schedule').modal('show')
                $('#save_schedule').addClass("d-none")
                $('#save_edit_schedule').removeClass("d-none")
                $('#competency_label').html(comp_label)
                get_trainers()

                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batch/scheduledetail',
                    data: {
                        schedid: schedid
                    },
                    success: function(data) {
                        $('#sched_duration').val(data.date_range)
                        $('#stime').val(data.stime)
                        $('#etime').val(data.etime)
                        setTimeout(() => {
                            $('#buildingadd_sched').val(data.buildingID).trigger('change')
                            $('#trainer_sched').val(data.trainer_id).trigger('change');
                        }, 200);
                        setTimeout(() => {
                            $('#room_sched').val(data.roomID).trigger('change')
                        }, 700);
                        $('#save_edit_schedule').attr('data-id', schedid)

                    }
                })
            })

            $(document).on('click', '#save_edit_schedule', function(){
                var sched_id = $('#save_edit_schedule').attr('data-id')
                var duration = $('#sched_duration').val()
                var stime = $('#stime').val()
                var etime = $('#etime').val()
                var buildingid = $('#buildingadd_sched').val()
                var trainer_id = $('#trainer_sched').val();
                var roomid = $('#room_sched').val()
                console.log(sched_id,'sched_id');
                
                $.ajax({
                    type: 'GET',
                    url: '/tesda/batch_setup/update/batch/scheduledetail',
                    data:{
                        schedid: sched_id,
                        duration: duration,
                        stime: stime,
                        etime: etime,
                        buildingid: buildingid,
                        trainer: trainer_id,
                        roomid: roomid,
                    },success: function(data){
                        $('#new_schedule').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Schedule Updated!'
                        })
                        get_batch_schedule()
                    }
                })
            })

            $(document).on('click', '.delete_sched', function(){
                var schedid = $(this).data('id')
                var compid = $(this).data('subject-id')
                var row = $(this).closest('tr');
                swal.fire({
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
                            url: "/tesda/batch_setup/delete/batch/scheduledetail",
                            type: "GET",
                            data: {
                                schedid: schedid
                            },
                            success: function(data) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Schedule Deleted!'
                                })
                                updateRowAndRowspan(row, compid)
                            }
                        })
                    }
                })
            })
            
            function updateRowAndRowspan(row, compid) {
                var rowspanCell = row.find('td.rowspan');

                if (rowspanCell.length > 0) {
                    let currentRowspan = parseInt(rowspanCell.attr('rowspan')) || 1;

                    // Find the next row with the same subject ID
                    var nextRow = row.nextAll(`tr[data-id="${compid}"]`).first();

                    if (nextRow.length > 0) {
                        if (!nextRow.find('td.rowspan').length) {
                            nextRow.prepend(rowspanCell); // Move the rowspan cell to the next row
                            rowspanCell.attr('rowspan', currentRowspan - 1); // Decrease the rowspan count
                        }
                    } else {
                        rowspanCell.attr('rowspan', 1); // Reset rowspan to 1
                        row.find('td:gt(1)').empty(); // Clear schedule-related columns
                    }
                } else {
                    // If this row doesn't have a rowspan, find the closest previous row with a rowspan
                    var rowspanRow = row.prevAll('tr').has('td.rowspan').first();
                    if (rowspanRow.length > 0) {
                        var rowspanCell = rowspanRow.find('td.rowspan');
                        let currentRowspan = parseInt(rowspanCell.attr('rowspan')) || 1;

                        // Only decrease if the current rowspan is greater than 1
                        if (currentRowspan > 1) {
                            rowspanCell.attr('rowspan', currentRowspan - 1);
                        }
                    }
                }

                // Remove the current row
                if (!row.find('td.rowspan').length) {
                    row.remove();
                }
            }

            //Batch Schedule CRUD -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            //Batch Schedule Dropdown & Input -------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $(document).on('change', '#buildingadd_modal', function() {
                var buildingid = $(this).val()
                if(building_instance == 1){
                    $.ajax({
                        type: 'get',
                        url: '/tesda/batch_setup/update/batch/building',
                        data: {
                            batch_id: batch_id_modal,
                            buildingid: buildingid
                        },
                        success: function(data) {
                            Toast.fire({
                                type: 'success',
                                title: 'Building Updated!'
                            })
                            get_batches();
                            get_room2()
                        }
                    })
                }else{
                    building_instance = 1
                }
            })

            $(document).on('change', '#room_modal', function() {
                var roomid = $(this).val()
                if(room_instance == 1){
                    $.ajax({
                        type: 'get',
                        url: '/tesda/batch_setup/update/batch/room',
                        data: {
                            batch_id: batch_id_modal,
                            roomid: roomid
                        },
                        success: function(data) {
                            Toast.fire({
                                type: 'success',
                                title: 'Room Updated!'
                            })
                            get_batches();
                    }
                    })
                }else{
                    room_instance = 1
                }
            })

            $(document).on('change', '#batch_duration_modal', function() {
                var duration = $(this).val()
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/update/batch/duration',
                    data: {
                        batch_id: batch_id_modal,
                        duration: duration
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Duration Updated!'
                        })
                        get_batches();
                    }
                })
            })
            $(document).on('input', '#batch_capacity_modal', function() {
                var capacity = $(this).val()
                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/update/batch/capacity',
                    data: {
                        batch_id: batch_id_modal,
                        capacity: capacity
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Capacity Updated!'
                        })
                        get_batches();
                    }
                })
            })
            //Batch Schedule Dropdown & Input -------------------------------------------------------------------------------------------------------------------------------------------------------------------

            //Datatables ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            function display_batches(data) {
                $('#batch_table').DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        { data: 'batch_desc' },
                        { data: 'date_range' },
                        { data: 'course_name' },
                        { data: null },
                        { data: 'isactive' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#" id="batch_schedule_details" data-id="${rowData.id}">${rowData.batch_desc}</a>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
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
                                $(td).html(`<p class="mb-0">${rowData.enrolled || 0}/${rowData.batch_capacity || 0}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if(rowData.isactive == 1){
                                    $(td).html(`<p class="mb-0 text-success">Active</p>`)
                                }else{
                                    $(td).html(`<p class="mb-0 text-danger">Inactive</p>`)
                                }
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(
                                    `
                                    <button class="btn btn-sm px-0 edit_batches" id="" data-toggle="modal"data-id="${rowData.id}"><i class="fas fa-edit text-primary"></i></button>
                                    <button class="btn btn-sm px-0 delete_batches" id=""  data-id="${rowData.id}"><i class="fas fa-trash text-danger" style="font"></i></button>
                                    `
                                ).addClass('text-center align-middle')
                            }
                        },
                    ]
                })
            }
            //Datatables ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            //Modals Open or Close Functions --------------------------------------------------------------------------------------------------------------------------------------------------------------------

            $('#new_batches').on('hidden.bs.modal', function (e) {
                $('#batch_name').val('')
                $('#batch_specialization').val('').trigger('change')
                $('#batch_capacity').val('')
                $('#batch_duration').val('')
                $('#buildingadd').val('').trigger('change')
                $('#room').val('').trigger('change')
                $('#batch_active').prop('checked', false)
            })
            $('#addroom').on('hidden.bs.modal', function (e) {
                $('#room').val('').trigger('change');
                $('#room_capacity').val('')
            })

            $('#addroom').on('show.bs.modal', function (e) {
                $('#room').val('').trigger('change');
                $('#room_capacity').val('')
                $('#select_building').val(building_val).trigger('change');
            })

            $('#new_batches').on('show.bs.modal', function(e) {
                $.ajax({
                    type:'get',
                    url: '/tesda/course_setup/get/courses',
                    data: {

                    },
                    success: function(data) {
                        $('#batch_specialization').empty();
                        $('#batch_specialization').append('<option value=""></option>');
                        $.each(data, function(index, course) {
                            $('#batch_specialization').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                        });
                    }
                })
            })

            $('#batch_schedule').on('hidden.bs.modal', function (e) {
                building_instance = 0
                room_instance = 0
                $('#room_modal').val('').trigger('change');
                $('#buildingadd_modal').val('').trigger('change');
                $('#batch_duration_modal').val('')
                $('#batch_capacity_modal').val('')
            })

            $('#add_batches').click(function() {
                $('#new_batches').modal('show')
                $('#save_batches').removeClass('d-none')
                $('#save_edit_batches').addClass('d-none')
                $('#batch_course_series').removeClass('d-none')
                $('#batch_series').removeAttr('disabled')

            })
            $('#batch_schedule').on('hidden.bs.modal', function (e) {
                $('.appended_row').remove();
            })
            $('#new_schedule').on('hidden.bs.modal', function (e) {
                $('#sched_duration').val('')
                $('#stime').val('')
                $('#etime').val('')
                $('#buildingadd_sched').val('').trigger('change')
                $('#room_sched').val('').trigger('change')
            })

            //Modals Open or Close Functions --------------------------------------------------------------------------------------------------------------------------------------------------------------------

        })
    </script>
@endsection
