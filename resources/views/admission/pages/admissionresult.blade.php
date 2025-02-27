@extends('admission.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <style>
        .align-middle td {
            vertical-align: middle;
        }

        .btn_custom_group {
            padding: 3px 8px !important;
        }

        .select2-container--bootstrap4 .select2-selection {
            /* border: 1px solid #414242; */
            border-radius: 6px;

        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: unset !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -6px;
        }
    </style>
@endsection

@section('content')
    <div class="modal fade" id="modalViewResult">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-danger text-md resultStatus">Passed</div>
                </div>
                {{-- <div class="modal-header">
                    <h5 class="modal-title">Detail
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div> --}}
                <div class="modal-body">
                    <div class="row accepted_container" hidden>
                        <div class="col-md-12 form-group">
                            <label class="ml-1" for="stud_final_course"><i class="fas fa-user-check mr-1"></i> <span
                                    class="badge badge-success p-1"> Accepted </span>
                            </label>
                            <input type="text" class="form-control" id="stud_final_course" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1"> Applicant Name </label>
                                <input type="text" class="form-control" id="applicant_name" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1"> Exam Setup </label>
                                <input type="number" id="examsetup_id" hidden>
                                <input type="text" class="form-control" id="examsetupname" disabled>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <button class="btn btn-outline-primary btn-sm btn_answers">View Answers</button>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle table-sm" id="table_test_categories"
                                    style="width: 100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Subjects</th>
                                            <th>Score%</th>
                                            <th>Time</th>
                                            <th>Items</th>
                                            <th>Required</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_categories">
                                        <!-- Your table rows will go here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="2"><strong>Average:</strong>
                                            </td>
                                            <!-- Label for Average -->
                                            <td> <span id="average_passing_rate" class="font-weight-bold">0%</span>
                                            </td>

                                            <td colspan="3"></td>
                                            <!-- Empty cells for Time and Items columns -->
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                    <hr>
                    {{-- <button class="btn btn-success btn-sm btn_accept_stud mb-2">Accept Student</button> --}}
                    <button class="btn btn-outline-danger btn-sm btn_unassign mb-2">Unassigned</button>
                    <div class="row">
                        <div class="col-md-8">
                            <label class="mb-1 col-md-12 recomWrapper"> Recommended Course </label>
                            <ul class="col-12 listofcourses">

                            </ul>
                        </div>
                        <div class="col-md-4 text-right">
                            <button class="btn btn-warning btn-sm btn_criteria">Input Criteria</button>
                        </div>
                    </div>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="answerHistoryModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Answer History</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1"> <i class="fas fa-filter"></i> Subject </label>
                                <select name="" class="form-control form-control-sm select2"
                                    id="filter_answer_history" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table id="tb_answers" class="table table-bordered table-sm table-striped table-valign-middle"
                                style="width: 100%;font-size: 14px;">
                                <thead>
                                    <th>Question</th>
                                    <th>Status</th>
                                    <th>Chosen</th>
                                    <th>Answer</th>
                                    <th>Points</th>
                                </thead>
                                <tbody id="table_answer_history"></tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-right"><strong>Score:</strong>
                                        <td class="text-center font-weight-bold"><span class="badge badge-success py-1 px-2"
                                                id="total_score">0</span>
                                        <td colspan="2" class="text-right"><strong>Total:</strong>
                                        </td>
                                        <td id="total_points" class="text-center font-weight-bold">0 pts</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCriteria" style="font-size: 14px;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                {{-- <div class="modal-header">
                    <h4 class="modal-title">Admission Criteria</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div> --}}

                <div class="modal-body">
                    <table class="table table-sm table-bordered table-striped table-valign-middle" style="width: 100%;">
                        <thead class="thead-light">
                            <tr>
                                <th>Criteria</th>
                                <th>Ratio</th>
                                <th class="text-center">Value</th>
                            </tr>
                        </thead>
                        <tbody id="tb_criteria"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right" style="vertical-align: middle;">Total Average:
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <h5 id="total_average" class="mb-0">0%</h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- <button class="btn btn-primary btn-sm btn_accept_stud">Accept Student</button> --}}

                    <div class="row finalCourseWrapper">
                        <div class="col-md-12">
                            <label class="mb-1" id="finalCourseLabel">Final Assigned Course</label>
                            <select class="form-control select2 form-control-sm" id="select-course" style="width: 100%;">
                                <option value="">Select Course</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary ml-auto" id="btn_save_criteria">Accept Student</button>
                </div>

            </div>
        </div>
    </div>



    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">ADMISSION RESULT</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item active"> Admission Result </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-filter"></i> Filter
                    </h5>
                    <div class="card-tools">
                        Active SY : {{ DB::table('sy')->where('isactive', 1)->value('sydesc') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-3">
                            <label for="" class="mb-1">Admission Status</label>
                            <select name="" id="admStatus" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">Select All</option>
                                <option value="1">Pending</option>
                                <option value="2">Approved</option>
                                <option value="3">Decline</option>

                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="" class="mb-1">Academic Program</label>
                            <select name="" id="acadprog" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">Select Program</option>
                                @foreach (DB::table('academicprogram')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="" class="mb-1">Grade Levels</label>
                            <select name="" id="gradelevels" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">Select Level</option>
                                @foreach (DB::table('gradelevel')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="college_course_wrapper" hidden>
                            <label for="" class="mb-1">College Courses</label>
                            <select name="" id="college_courses" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">Select Course</option>
                                @foreach (DB::table('college_courses')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="sh_strand_wrapper" hidden>
                            <label for="" class="mb-1">Strand</label>
                            <select name="" id="sh_strand" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">Select Course</option>
                                @foreach (DB::table('sh_strand')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->strandcode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <button class="btn btn-warning btn-sm shadow" id="btn_print_report">
                                <i class="fas fa-print"></i> Generate Waitlist Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow">
                <div class="card-header p-0 border-bottom-0">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-scroll mr-1"></i>
                            WaitList
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-valign-middle" id="tbl_results"
                            style="width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Exam Date</th>
                                    <th>Applicant Name</th>
                                    <th>Score</th>
                                    <th>Desired Course</th>
                                    <th>Fitted Course</th>
                                    <th>Final Course</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('body').addClass('sidebar-collapse');
        var currentUserId = 0;
        var TotalAverage = 0;
        var stud_acadprog = 0
        var stud_status = 0;
        var courses = {!! json_encode($courses) !!}
        var gradelevels = {!! json_encode($gradelevels) !!}
        var levelArr = []
        $(document).ready(function() {
            admissionGetAllResults()
            console.log(courses);
            console.log(gradelevels);

            $('.select2').select2({
                alllowClear: true,
            })

            $('#btn_print_report').on('click', function() {
                $('#admStatus').val(1).change()

                window.open("/admission/admission-waitlist-report_pdf", "_blank")
            })

            $('#admStatus, #acadprog, #college_courses, #sh_strand').on('change', function() {
                admissionGetAllResults()

                if ($('#acadprog').val() == 6) {
                    $('#college_course_wrapper').prop('hidden', false)
                    $('#sh_strand_wrapper').prop('hidden', true)
                    $('#sh_strand').val('')

                } else if ($('#acadprog').val() == 5) {
                    $('#sh_strand_wrapper').prop('hidden', false)
                    $('#college_course_wrapper').prop('hidden', true)
                    $('#college_courses').val('')
                } else {
                    $('#college_course_wrapper').prop('hidden', true)
                    $('#sh_strand_wrapper').prop('hidden', true)
                }

                $('#gradelevels').empty()
                $('#gradelevels').append('<option value="">Select Level</option>')
                levelArr = gradelevels
                levelArr.filter(x => x.acadprogid == $('#acadprog').val()).map(x => {
                    $('#gradelevels').append(`<option value="${x.id}">${x.levelname}</option>`)
                })
            })

            $('#gradelevels').on('change', function() {
                admissionGetAllResults()
            })


            $('#btn_save_criteria').on('click', function() {
                var isvalid = true;
                var criteria_array = [];

                // stud_status = $(this).data('status');

                if (stud_acadprog == 6 || stud_acadprog == 5) {
                    if ($('#select-course').val() == '' || $('#select-course').val() == null) {
                        isvalid = false;
                        $('#select-course').addClass('is-invalid');
                        notify('error', 'Please select a course!');
                    } else {
                        $('#select-course').removeClass('is-invalid');

                    }
                }

                $('#tb_criteria tr').find('.input_value, .sub_input_value').each(function() {
                    var input_value = $(this).val();
                    var criteria_id = $(this).data('id');
                    var sub_criteria_id = $(this).data('sub_criteria_id');
                    var serverId = $(this).data('serverid');

                    if (input_value && criteria_id) {
                        var criteria_obj = {
                            value: input_value,
                            criteria_id: criteria_id,
                            sub_criteria_id: sub_criteria_id ?? null,
                            serverId: serverId,
                        };

                        criteria_array.push(criteria_obj);
                    }
                });

                console.log(criteria_array);
                // return

                var data = {
                    course_id: $('#select-course').val(),
                    studid: currentUserId,
                    criteria_array: JSON.stringify(criteria_array)
                };

                if (!isvalid) {
                    return;
                }


                $.ajax({
                    type: "POST",
                    url: "{{ route('store.student.criteria') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            notify(response.status, response.message);
                            if (stud_acadprog == 6 || stud_acadprog == 5) {
                                if ($('#btn_save_criteria').text() == 'Unassign') {
                                    unassign_student(currentUserId);
                                } else {
                                    accept_student(currentUserId, $('#select-course').val());
                                }
                            } else {
                                if ($('#btn_save_criteria').text() == 'Unassign') {
                                    unassign_student(currentUserId);
                                } else {
                                    accept_student(currentUserId, null);
                                }
                            }

                            $('#modalCriteria').modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 419) {
                            alert(
                                'The action has been expired. Please refresh the page and try again.'
                            );
                        }
                    }
                });
            });


            $('.btn_answers').on('click', function() {
                $('#answerHistoryModal').modal();
            })

            $('.btn_criteria').on('click', function() {
                getAllCriteria()
            })

            $(document).on('click', '.btn_view', function() {
                var id = $(this).data('id');
                currentUserId = id
                view_result(id)
            })

            $(document).on('click', '.btn_retake', function() {
                var id = $(this).data('id');
                retake_test(id)
            })

            $(document).on('click', '.btn_accept', function() {
                var id = $(this).data('id');
                var courseid = $(this).data('courseid');
                // var probation = $(this).data('probation');
                accept_student(id, courseid);
            })

            $(document).on('click', '.btn_decline', function() {
                var id = $(this).data('id');
                decline_student(id);
            })
            $('#filter_answer_history').on('change', function() {
                var id = $(this).val();
                console.log(id);
                showAnswerHistory(currentUserId)
            })

            // $('.btn_accept_stud').on('click', function() {
            //     accept_student(currentUserId, null);
            // })

            $('.btn_unassign').on('click', function() {
                unassign_student(currentUserId);
            })
        });

        function unassign_student(id) {
            Swal.fire({
                type: 'warning',
                title: 'Unassign Student?',
                text: `Student Status will be set to UNASSIGNED! `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/admission/accept-student",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            console.log(response)
                            notify(response.status, response.message)
                            if (response.status == 'success') {
                                // $('.btn_accept_stud').prop('hidden', false);
                                $('.btn_unassign').prop('hidden', true);
                                $('#btn_save_criteria').attr('data-status', 1);
                                $('#btn_save_criteria').text('Accept Student');
                                $('.accepted_container').prop('hidden', true)
                                // $('#stud_final_course').val('Not Specified')

                                $('#modalCriteria').modal('hide');
                                // $('#modalViewResult').modal('hide');
                            }
                            admissionGetAllResults()

                        },
                        error: function(xhr, status, error) {
                            // Handle error response from the server if needed
                            console.error('Error updating server:', error);
                        }
                    })
                }
            });

        }


        function getAllSubject(id) {
            $.ajax({
                type: 'GET',
                data: {
                    id: id
                },
                url: '{{ route('getAllSubject') }}',
                success: function(respo) {
                    console.log(respo);
                    $('#filter_answer_history').empty();
                    $('#filter_answer_history').append('<option value="">All</option>');
                    $('#filter_answer_history').select2({
                        data: respo
                    })

                }
            })
        }

        function getAllCriteria() {
            $.ajax({
                type: 'GET',
                data: {
                    studid: currentUserId
                },
                url: '{{ route('criteria.all') }}',
                success: function(respo) {
                    console.log(respo);

                    var tbody = $('#tb_criteria');
                    tbody.empty(); // Clear any existing rows

                    respo.forEach(function(item) {

                        var tb_sub = `<table class="table table-sm table-bordered" style="font-size: 12px; margin-top: 3px;">
                                            <thead>
                                                <th>Subcriteria</th>
                                                <th>%</th>
                                                <th></th>
                                            </thead>
                                            <tbody>${item.subcriteria.map(sub => `<tr><td>${sub.name}</td><td>${sub.percentage}%</td><td> <input data-serverid="${sub.studsubcriteria ? sub.studsubcriteria.id : 0}" type="number" value="${sub.studsubcriteria ? sub.studsubcriteria.value : 0}" min="0" max="100" class="form-control form-control-sm-form text-center sub_input_value" data-id="${item.id}" data-sub_criteria_id="${sub.id}" data-percentage="${sub.percentage}"> </td></tr>`).join('')}</tbody>
                                        </table>`


                        var row = $('<tr></tr>');
                        row.append('<td>' + item.criteria_name + '</td>');
                        row.append('<td><span class="text-success" style="font-weight:600;">' + item
                            .criteria_percentage + '%' + '</span></td>');
                        row.append(
                            `<td width="300px">
                                <input type="number" min="0" max="100" class="form-control form-control-sm-form text-center input_value" data-percentage="${item.criteria_percentage}" data-id="${item.id}" data-serverid="${item.studcriteria ? item.studcriteria.id : 0}"
                                value="${item.primary ? TotalAverage : item.studcriteria ? item.studcriteria.value : 0}" ${item.primary ? 'disabled' : ''} step="0.01" min="0">
                                <div>
                                     ${ item.subcriteria.length > 0 ? tb_sub : ''}           
                                </div>
                            </td>`
                        );
                        tbody.append(row);
                    });

                    $(document).on('input', '.sub_input_value', function(e) {
                        var $table = $(this).closest('table');
                        var $row = $table.closest('tr');
                        var totalValue = 0;

                        var max = parseFloat($(this).attr('max'));
                        var min = parseFloat($(this).attr('min'));
                        var value = parseFloat($(this).val()) || min;

                        if (value > max) {
                            $(this).val(max);
                        } else if (value < min) {
                            $(this).val(min);
                        }

                        $table.find('.sub_input_value').each(function() {
                            var value = parseFloat($(this).val()) || min;
                            var percentage = parseFloat($(this).data('percentage'));
                            totalValue += value * (percentage / 100);
                        });

                        var average = totalValue.toFixed(2)
                        $row.find('.input_value').val(average);
                        calculateTotalAverage();
                    }).on('wheel', function(e) {
                        e.preventDefault();

                        var max = parseFloat($(this).attr('max'));
                        var min = parseFloat($(this).attr('min'));
                        var value = parseFloat($(this).val()) || min;

                        if (e.originalEvent.deltaY < 0) {
                            if (value < max) {
                                $(this).val(value + 1);
                            }
                        } else if (e.originalEvent.deltaY > 0) {
                            if (value > min) {
                                $(this).val(value - 1);
                            }
                        }
                        calculateTotalAverage();
                    });

                    // Attach input event listener to dynamically calculate total average
                    $(document).on('input', '.input_value', function(e) {
                        var max = parseFloat($(this).attr('max'));
                        var value = parseFloat($(this).val());
                        if (value > max) {
                            $(this).val(max);
                        } else if (value < 0) {
                            $(this).val(0);
                        }
                        calculateTotalAverage();
                    }).on('wheel', function(e) {
                        e.preventDefault();
                        var max = parseFloat($(this).attr('max'));
                        var value = parseFloat($(this).val());
                        if (e.originalEvent.deltaY < 0) {
                            if (value < max) {
                                $(this).val(value + 1);
                            }
                        } else if (e.originalEvent.deltaY > 0) {
                            if (value > 0) {
                                $(this).val(value - 1);
                            }
                        }
                        calculateTotalAverage();
                    });


                    calculateTotalAverage();
                    $('#modalCriteria').modal();
                }
            });
        }

        function calculateTotalAverage() {
            var total = 0;
            var count = 0;

            $('.input_value').each(function() {
                var value = parseFloat($(this).val());
                var percentage = parseFloat($(this).data('percentage'));
                // console.log(value, percentage);

                if (!isNaN(value) && !isNaN(percentage)) {
                    // Convert percentage to decimal and multiply by the value
                    total += (percentage / 100) * value;
                    count++; // Increment count for each valid input
                }
            });

            // Calculate the total average only if there are valid inputs
            // var average = count > 0 ? (total / count).toFixed(2) : 0;
            var average = total.toFixed(2);
            $('#total_average').text(average + '%');
        }


        function admissionGetAllResults() {
            $.ajax({
                type: 'GET',
                data: {
                    admstatus: $('#admStatus').val(),
                    acadprog: $('#acadprog').val(),
                    gradelevel: $('#gradelevels').val(),
                    course: $('#college_courses').val(),
                    strand: $('#sh_strand').val()
                },
                url: '/admission/getallresults',
                success: function(respo) {
                    console.log(respo);
                    load_results(respo.result)
                },
                error: function(xhr, status, error) {
                    // Handle error response from the server if needed
                    console.error('Error updating server:', error);
                }
            })
        }

        function view_result(id) {
            $.ajax({
                type: "GET",
                url: "/admission/viewresult",
                data: {
                    id: id
                },
                success: function(response) {
                    var result = response.result[0]
                    console.log(result)

                    stud_status = result.status

                    stud_acadprog = result.acadprog_id

                    if (result.status == 2) {
                        // $('.btn_accept_stud').prop('hidden', true)
                        $('.btn_unassign').prop('hidden', false)
                        $('#btn_save_criteria').attr('data-status', result.status)
                        $('#btn_save_criteria').text('Unassign')
                    } else {
                        // $('.btn_accept_stud').prop('hidden', false)
                        $('.btn_unassign').prop('hidden', true)
                        $('#btn_save_criteria').attr('data-status', 1)
                        $('#btn_save_criteria').text('Accept Student')

                    }

                    if ((result.status == 2 && result.final_assign_course) && (result
                            .acadprog_id == 6 || result
                            .acadprog_id == 5)) {

                        if (result.acadprog_id == 6) {
                            $('#stud_final_course').val(
                                `${result.final_courseabrv} - ${result.final_courseDesc}`)

                        } else {
                            $('#stud_final_course').val(
                                `${result.final_strandcode} - ${result.final_strandname}`)

                        }
                        $('.accepted_container').prop('hidden', false)
                        $('.finalCourseWrapper').prop('hidden', false)


                    } else if (result.status == 1 && result
                        .acadprog_id == 6 || result
                        .acadprog_id == 5) {
                        $('.finalCourseWrapper').prop('hidden', false)
                        $('.accepted_container').prop('hidden', true)
                    } else if (result.status == 2 && (result.acadprog_id <=
                            4 && result.acadprog_id >= 2)) {
                        $('.accepted_container').prop('hidden', false)
                        $('#stud_final_course').val('Not Specified')
                        $('.finalCourseWrapper').prop('hidden', true)
                        $('#select-course').empty()

                    } else {
                        $('.accepted_container').prop('hidden', true)
                        $('#stud_final_course').val('Not Specified')
                        $('.finalCourseWrapper').prop('hidden', true)
                        $('#select-course').empty()

                    }
                    var fullname = `${result.fname} ${result.lname}`
                    var subjects = result.subjects
                    var fittedCourses = result.recommendedcourse.filter(course => course != null && course !=
                        undefined);
                    TotalAverage = result.totalScore;
                    var genAverage = result.totalScore + '%'
                    var finalAssignCourse = result.final_assign_course
                    // const selectedCourse = courses.find(course => course.id === result.fitted_course_id ||
                    //     course.id === result.course_id || course.id === result.final_assign_course
                    // );

                    if (fittedCourses.length == 0 && result.acadprog_id == 6) {
                        $('.recomWrapper').text('No Recommended Course')
                    } else if (fittedCourses.length == 0 && result.acadprog_id == 5) {
                        $('.recomWrapper').text('No Recommended Strand')
                    } else if (fittedCourses.length == 0 && result.acadprog_id >= 2 && result.acadprog_id <=
                        4) {
                        $('.recomWrapper').text('No Recommendations')
                    } else if (fittedCourses.length > 0 && result.acadprog_id == 5) {
                        $('.recomWrapper').text('Recommended Strand')
                    }


                    var data = fittedCourses.length > 0 ? result.alternateCourse.concat(fittedCourses) :
                        result
                        .alternateCourse;
                    var uniqueData = Array.from(new Set(data.map(JSON.stringify))).map(JSON.parse);
                    let courseData = uniqueData.filter(course => course).map(course => ({
                        id: course.id,
                        text: result.acadprog_id == 6 ? course.courseDesc : course.strandname
                    }));

                    if (courseData.length > 0) {
                        $('#select-course').empty()
                        $('#select-course').select2({
                            data: courseData,
                            placeholder: result.acadprog_id == 6 ? 'Select Course' : 'Select Strand',

                        });
                    }
                    if (result.final_assign_course) {
                        $('#select-course').val(finalAssignCourse).change()
                    }
                    // $('#select-course').val(finalAssignCourse).change()
                    $('#examsetupname').val(result.examSetup)
                    $('#applicant_name').val(fullname)
                    $('#average_passing_rate').text(genAverage)
                    if (result.passedOverall) {
                        $('#average_passing_rate').addClass('text-success')
                    } else {
                        $('#average_passing_rate').addClass('text-danger')
                    }

                    $("#table_test_categories").DataTable({
                        autowidth: false,
                        destroy: true,
                        data: subjects,
                        lengthChange: false,
                        columns: [{
                                data: "index",
                                className: 'text-center',
                                render: function(type, data, row) {
                                    return `<span> ${row.index}.</span>`
                                }
                            },
                            {
                                data: "category_name",
                                render: function(type, data, row) {
                                    return `<span> <strong> ${row.category_name}</strong></span>`
                                }
                            },
                            {
                                data: "scoreInPercent",
                                render: function(type, data, row) {
                                    return `<span class="${row.passed ? 'text-success' : 'text-danger' }"> <strong> ${row.scoreInPercent ? row.scoreInPercent : 'No Record'}% </strong></span>`
                                }
                            },
                            {
                                data: null,
                                render: function(type, data, row) {
                                    return `<span> ${row.category_timelimit_hrs}hrs ${row.category_timelimit_min}min</span>`
                                }
                            },
                            {
                                data: 'category_totalitems',
                                render: function(type, data, row) {
                                    return `<span> ${row.category_totalitems} Items </span>`
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: function(type, data, row) {

                                    var renderHtml =
                                        `${row.required ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' }`

                                    return renderHtml
                                }
                            }

                        ],
                    });

                    $('.listofcourses').empty()
                    if (fittedCourses && fittedCourses.length > 0) {

                        fittedCourses.forEach(element => {
                            if (element && Object.keys(element).length) {
                                var htmlToAppend = result.acadprog_id == 6 ?
                                    `
                                    <li>
                                        <i class="far fa-check-square text-success"></i> <strong>${element.courseabrv}</strong>-${element.courseDesc} 
                                    </li>` :

                                    `<li>
                                        <i class="far fa-check-square text-success"></i> <strong>${element.strandcode}</strong>-${element.strandname} 
                                    </li>`

                                $('.listofcourses').append(htmlToAppend);
                            }
                        });
                    }

                    $('#examsetup_id').val(result.exam_setup_id)
                    getAllSubject(result.exam_setup_id)
                    showAnswerHistory(id)

                    let ResultStatus = result.passedOverall ? 'Passed' : 'Failed'
                    $('.resultStatus').text(ResultStatus)
                    if (!result.passedOverall) {
                        $('.resultStatus').removeClass('bg-success')
                        $('.resultStatus').addClass('bg-danger')
                    } else {
                        $('.resultStatus').removeClass('bg-danger')
                        $('.resultStatus').addClass('bg-success')
                    }

                    // if ((result.acadprog_id <= 4 && result.acadprog_id >= 2) && result.status == 2) {

                    // } else {
                    //     $('.btn_accept_stud').prop('hidden', true)
                    //     $('.finalCourseWrapper').prop('hidden', false)
                    // }

                    if (result.acadprog_id == 6) {
                        $('#finalCourseLabel').text('Final Assigned Course')
                    } else if (result.acadprog_id == 5) {
                        $('#finalCourseLabel').text('Final Assigned Strand')
                    }


                    $('#modalViewResult').modal()
                },
                error: function(xhr, status, error) {
                    // Handle error response from the server if needed
                    console.error('Error updating server:', error);
                }
            })
        }

        function retake_test(id) {
            Swal.fire({
                type: 'info',
                title: 'Are you sure you want to reset this test ?',
                text: `Student will be able to retake the test after this! `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/admission/retaketest",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response)
                            notify(response.status, response.message)

                            admissionGetAllResults()
                        },
                        error: function(xhr, status, error) {
                            // Handle error response from the server if needed
                            console.error('Error updating server:', error);
                        }
                    })
                }
            });

        }

        function accept_student(id, courseid) {
            Swal.fire({
                type: 'info',
                title: 'Accept Student?',
                text: `Student will be accepted with their fitted course! `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Accept'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/admission/accept-student",
                        data: {
                            id: id,
                            courseid: courseid
                        },
                        success: function(response) {
                            console.log(response)
                            notify(response.status, response.message)
                            if (response.status == 'success') {
                                // $('.btn_accept_stud').prop('hidden', true);
                                $('.btn_unassign').prop('hidden', false)
                                $('#btn_save_criteria').data('status', 2);
                                $('#btn_save_criteria').attr('data-status', 2);
                                $('#btn_save_criteria').text('Unassign');
                                $('.accepted_container').prop('hidden', false)
                                $('#stud_final_course').val('Not Specified')

                                $('#modalCriteria').modal('hide');
                                // $('#modalViewResult').modal('hide');
                                admissionGetAllResults()
                            }

                        },
                        error: function(xhr, status, error) {
                            // Handle error response from the server if needed
                            console.error('Error updating server:', error);
                        }
                    })
                }
            });

        }

        function decline_student(id) {
            Swal.fire({
                type: 'info',
                title: 'Decline Student?',
                text: `Student will be rejected and will not be eligible! `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/admission/decline-student",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            console.log(response)
                            notify(response.status, response.message)
                            admissionGetAllResults()
                        },
                        error: function(xhr, status, error) {
                            // Handle error response from the server if needed
                            console.error('Error updating server:', error);
                        }
                    })
                }
            });

        }

        function load_results(data) {
            $('#tbl_results').DataTable({
                destroy: true,
                data: data,
                columns: [{
                        data: "formatted_examdate"
                    },
                    {
                        data: "studname",
                        render: (type, data, row) =>
                            `<span style="font-weight: 600;"> ${ row.studname || 'Not Specified'} </span>`
                    },
                    {
                        data: 'totalScore',
                        render: (type, data, row) =>
                            `<span class="${row.passedOverall ? "text-success" : "text-danger"}" style="font-weight: 600;">${row.totalScore}% (${row.passedOverall ? "Passed" : "Failed"})</span>`
                    },
                    {
                        data: null,
                        render: (data, type, row) =>
                            row.acadprog_id == 7 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> Technical Vocational </span>` : row
                            .acadprog_id == 6 ?
                            `<span> ${row.courseabrv}</span>
                             <span class="badge badge-primary"> College </span>` : row
                            .acadprog_id == 5 ?
                            `<span> <strong> ${row.strandcode} </strong> - ${row.strandname} </span>  
                            <span class="badge badge-danger" > SHS Strand </span>` : row.acadprog_id == 4 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> High School </span>` : row.acadprog_id == 3 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> Elementary </span>` : row.acadprog_id == 2 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> Pre-School </span>` :
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>`


                    },
                    {
                        data: null,
                        render: (data, type, row) =>
                            row.fitted_course_id ?
                            `<span>  ${row.fitted_courseAbrv} </span>` :
                            `<span class="text-muted"> Not Specified </span>`
                    },
                    {
                        data: 'final_courseabrv',
                        render: (data, type, row) =>
                            row.final_courseabrv && row.acadprog_id == 6 ?
                            `<button type="button" class="btn btn-outline-success btn-sm btn_view" data-id="${row.id}" title="${row.final_courseDesc}">${row.final_courseabrv}</button>` :
                            row.final_strandcode && row.acadprog_id == 5 ?
                            `<button type="button" class="btn btn-outline-success btn-sm btn_view" data-id="${row.id}" title="${row.final_strandname}">${row.final_strandcode}</button>` :
                            '<button type="button" class="btn btn-outline-secondary btn-sm btn_view" data-id="${row.id}" title="Not Specified">Not Specified</button>'
                    },
                    {
                        data: 'status',
                        render: (data, type, row) => {
                            const status = {
                                2: '<span class="badge bg-success">Accepted</span>',
                                3: '<span class="badge bg-danger">Rejected</span>',
                                default: '<span class="badge bg-warning">Pending</span>'
                            };
                            return status[row.status] || status.default;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: (data, type, row) => {
                            if (type !== 'display' || !Array.isArray(row.recommendedcourse)) return '';

                            const recommendedCourses = row.recommendedcourse.map(
                                (element) => element !== null && element !== '' ?
                                `<a class="dropdown-item btn_accept" href="#" data-id="${row.id}" data-courseid="${element.id}">
                                <strong>${row.acadprog_id == 6 ? element.courseabrv : row.acadprog_id == 5 ? element.strandcode : ''}</strong> - 
                                ${row.acadprog_id == 6 ? element.courseDesc : row.acadprog_id == 5 ? element.strandname : ''}</a>` :
                                'None'
                            ).join('');

                            return `
                                <div class="btn-group">
                                    <div class="input-group-append" hidden>
                                        <button type="button" class="btn btn-default dropdown-toggle btn_custom_group" data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" title="Accept">
                                            <i class="far fa-hand-pointer"></i>
                                        </button>
                                        <div class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                            <a class="dropdown-item" href="#"><strong>Recommended Courses</strong></a>
                                            <div class="dropdown-divider"></div>
                                            ${recommendedCourses}
                                        </div>
                                    </div>
                                    <a data-toggle="tooltip" title="View Result" data-id="${row.id}" type="button" href="javascript:void(0)" class="btn btn-default btn_view btn_custom_group"> <i class="far fa-eye text-info"></i> </a>
                                    <a data-toggle="tooltip" title="Retake" data-id="${row.id}" type="button" href="javascript:void(0)" class="btn btn-default btn_retake btn_custom_group"> <i class="fas fa-sync text-primary"></i> </a>     
                                    <a data-toggle="tooltip" data-id="${row.id}" title="Decline" type="button" href="javascript:void(0)" class="btn btn-default btn_decline btn_custom_group"> <i class="fas fa-times text-danger"></i> </a>
                                </div>`;
                        }
                    }

                ]
            })
        }

        function showAnswerHistory00(studid) {
            $.ajax({
                url: '{{ route('answer.history') }}',
                type: 'GET',
                data: {
                    studid: studid
                },
                success: function(response) {
                    console.log(response);
                    $('#tb_answers').DataTable({
                        autowidth: false,
                        destroy: true,
                        data: response,
                        lengthChange: true,
                        columns: [{
                                data: "testquestion",
                            },
                            {
                                data: "status",
                                className: 'text-center',
                                render: function(data, type, row) {
                                    return `<span style="font-weight: 600;">  
                                        ${ row.status  == 'correct' ? 
                                        '<i class="fa fa-check text-success" aria-hidden="true"></i> ' 
                                        :'<i class="fa fa-times text-danger" aria-hidden="true"></i>'
                                    } </span> `
                                }
                            },

                            {
                                data: 'answer',
                                className: 'text-center',
                            },
                            {
                                data: 'testanswer',
                                className: 'text-center',
                            },
                            {
                                data: 'points',
                                className: 'text-center',
                                render: function(data, type, row) {
                                    return `<span style="font-weight: 600;"> ${row.points} Pts</span> `

                                }

                            },

                        ],
                        columnDefs: [{
                                width: '200px',
                                targets: 0
                            }, // Set the width for the first column
                        ],
                    })
                },
                error: function(xhr, status, error) {
                    alert('Error fetching data: ' + error);
                }
            });
        }

        function showAnswerHistory(studid) {
            $.ajax({
                url: '{{ route('answer.history') }}',
                type: 'GET',
                data: {
                    studid: studid,
                    examid: $('#filter_answer_history').val()
                },
                success: function(response) {
                    console.log(response);

                    // Clear the table body before appending new data
                    $('#table_answer_history').empty();

                    // Loop through the response data and append rows to the table
                    $.each(response.answers, function(index, row) {
                        let statusIcon = row.status == 'correct' ?
                            '<i class="fa fa-check text-success" aria-hidden="true"></i>' :
                            '<i class="fa fa-times text-danger" aria-hidden="true"></i>';

                        $('#table_answer_history').append(`
                            <tr>
                                <td>${row.testquestion}</td>
                                <td class="text-center"><span style="font-weight: 600;">${statusIcon}</span></td>
                                <td class="text-center">${row.answer}</td>
                                <td class="text-center">${row.testanswer}</td>
                                <td class="text-center"><span style="font-weight: 600;">${row.points} Pts</span></td>
                            </tr>
                        `);
                    });

                    $('#total_points').text(`${response.total} Pts`);
                    $('#total_score').text(response.score);
                },
                error: function(xhr, status, error) {
                    alert('Error fetching data: ' + error);
                }
            });
        }
    </script>
@endsection
