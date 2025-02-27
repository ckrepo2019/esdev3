@php

    $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();

    if (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (auth()->user()->type == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 7) {
        $extend = 'studentPortal.layouts.app2';
    } elseif (Session::get('currentPortal') == 9) {
        $extend = 'parentsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            }
        } else {
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
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">

    <style>
        /* select2 */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {

            background-color: #007bff;
            border: 1px solid #007bff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {

            color: white;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }

        input[type=search] {
            height: calc(1.7em + 2px) !important;
        }


        /* calendar */
        #calendar td {
            cursor: pointer;
        }


        .attendance-table td .student_name {

            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }



        .tabledata {

            font-size: 15px;
            padding-top: 2px;
            padding-bottom: 2px;
            max-width: 100px;
            min-width: 100px;
            border: 1px solid rgb(228, 227, 227);
            user-select: none;
        }

        .tablenumber {

            width: 25px;
            font-size: 12px;
            padding-top: 2px;
            padding-bottom: 2px;
            max-width: 25px;
            min-width: 25px;
            border: 1px solid rgb(228, 227, 227);
            user-select: none;
            z-index: 10;
            background: white;
        }


        .attendance-table th {

            font-size: 15px;
            padding-top: 2px;
            padding-bottom: 2px;
            border: 1px solid white;
            background: #383b3d;
            color: white;
        }

        .attendance-table tr {

            background: white;
        }

        .attendance-table tr:hover {

            background: #fff5d4;
        }

        .badge {

            width: 15px;
        }

        .spanholder {
            cursor: pointer;
            transition: all 0.3s;
        }

        .spanholder:hover {
            padding-right: 6px;
        }

        .tooltip {
            position: absolute;
            width: 100px;
            height: 50px;
            transition: 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 1;
            transform: scale(0);
        }

        .tooltip-active {
            transform: scale(1);
        }

        .tooltip-holder {
            position: absolute;
            border-radius: 5px;
        }

        .card>.list-group:first-child .list-group-item:first-child {
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
        }

        i.fas.fa-caret-down {
            width: 100%;
            height: 27px;
            line-height: 2;
        }

        i.fas.fa-caret-down:hover {

            background: #6183a7;
        }
    </style>
@endsection

@section('content')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}">
    <link rel='stylesheet' href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}" />

    <!-- Export Modal -->
    <div class="modal fade" id="exportmodal" tabindex="-1" aria-labelledby="exportmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportmodalLabel">Export PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- <div class="form-group mb-3">
                    <div class="export_select">
                        <label for="export_coverage">Export Coverage</label>
                        <select id="export_coverage" name="export_coverage" class=" form-control select2"></select>
                    </div>

                </div> --}}

                    <div class="form-group mb-3" id="exportsy">
                        <div class="export_select">
                            <label for="export_sy">School Year</label>
                            <select id="export_sy" name="export_sy" class=" form-control select2"></select>
                        </div>
                    </div>

                    <div class="form-group mb-3 hidden" id="byStudent">
                        <div class="export_select">
                            <label for="export_student">Student</label>
                            <select id="export_student" name="export_student" class=" form-control select2"></select>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="byMonth">
                        <div class="export_select">
                            <label for="export_month">By Monthy</label>
                            <select id="export_month" name="export_month" class=" form-control select2"></select>
                        </div>
                    </div>





                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success export" id="export">Export</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Export Modal END-->




    <!-- BODY -->

    <div class="pt-3 px-2">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-9">

                    <div class="card">
                        <div class="card-body p-0">

                            <div style="padding: 12px 12px 20px 12px">

                                <form id="selection_form">

                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="base-rating-title"><i class="fa fa-filter"></i>Attendance</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-warning btn-sm float-right"
                                                id="btn_generate" style="font-size: 1rem">
                                                <i class="fas fa-sync-alt pr-1"></i> Generate
                                            </button>
                                        </div>
                                    </div>

                                    <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;">

                                    <div class="d-flex justify-content-between" style="font-size: 0.9rem">

                                        <div class="row" style="flex-basis: 40%">

                                            <div class="col-md-6  form-group mb-0">
                                                <div class="select_container_attendance">
                                                    <label for="syid">School Year</label>
                                                    <select id="syid" name="syid" class=" form-control select2">

                                                    </select>
                                                </div>

                                            </div>

                                            <div class="col-md-6  form-group mb-0">
                                                <div class="select_container_attendance">
                                                    <label for="semid">Semester</label>
                                                    <select id="semid" name="semid"
                                                        class=" form-control select2"></select>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row" style="flex-basis: 60%">

                                            <div class="col-md-6 form-group mb-0">
                                                <div class="select_container_attendance">
                                                    <label for="subjectid">Subject</label>
                                                    <select id="subjectid" name="subjectid"
                                                        class=" form-control select2"></select>
                                                </div>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="select_container">
                                                    <label for="sectionid">Section</label>
                                                    <select id="sectionid" name="sectionid"
                                                        class=" form-control select2"></select>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="border-radius: 0px">
                        <div class="card-body p-0">

                            <div style="padding: 12px 12px 20px 12px">

                                <div id="attendance_table" class="text-center" style="height: 57vh; overflow-x: auto;">

                                    <div style="padding: 100px">
                                        <h1><i class="fas fa-exclamation-circle text-warning"></i></h1>
                                        <h4>Please Generate Attendance.</h4>
                                    </div>
                                </div>

                                <style>
                                    .attendance-table-header {
                                        position: sticky;
                                        top: 0px;
                                        z-index: 1000;
                                    }

                                    .attendance-table td:nth-child(1),
                                    .attendance-table th:nth-child(1) {
                                        position: sticky;
                                        left: 0px;
                                    }

                                    .attendance-table td:nth-child(2),
                                    .attendance-table th:nth-child(2) {
                                        position: sticky;
                                        left: 29px;
                                    }
                                </style>

                            </div>
                        </div>
                    </div>

                    {{-- <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="attendance_datatable" width="100%" >
                <thead>
                    <tr>
                        <th width="80%" class="pl-3">Student Name</th>
                        <th width="20%" class="text-center">Operation</th>
                    </tr>
                </thead>
            </table> --}}

                </div>
                <div class="col-md-3">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card shadow" style="height: 100%">
                                {{-- <div class="card-header">
                            <p class="m-0">
                                <i class="fas fa-exclamation-circle text-primary"></i> Calendar
                            </p>
                        </div> --}}
                                <div class="card-body pl-2 pr-2 pt-2 pb-0">
                                    <div class="fc fc-ltr fc-bootstrap" style="font-size: 15px;" id="calendar"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{--
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="d-flex justify-content-center">
                                            <label id="currentSY" class="mb-0 text-info">SY 2022-2023</label>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                            <div class="d-flex justify-content-center">
                                            <label id="currentSemester" class="mb-0 text-info">1st</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex">
                                            <label class="mr-3 mb-0"><i class="fas fa-user pl-1 pr-1"></i></label>
                                            <label class="mb-0 text-info">{{ $teacher->teachername }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="d-flex">
                                            <label class="mr-3 mb-0"><i class="fas fa-clock pl-1 pr-1"></i></label>
                                            <label class="mb-0 text-info">[M] 07:30 am - 08:30 am</label>
                                        </div>
                                    </div>
                                </div>

                    </div>
                </div>
                </div>
            </div> --}}

                    <button type="button" class="btn btn-danger btn-lg btn-block" id="pdf_exportbtn"><i
                            class="fas fa-file-pdf mr-2 text-light"></i> Export PDF </button>

                    <button type="button" class="btn btn-success btn-lg btn-block" id="xlsx_exportbtn"><i
                            class="fas fa-file-excel mr-2 text-light"></i>Export XLSX</button>


                </div>
            </div>

        </div>
    </div>


    <div class="tooltip-holder card shadow">
        <div class="tooltip">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action menu bg-dark" id="present">Present</a>
                <a href="#" class="list-group-item list-group-item-action menu bg-dark" id="absent">Absent</a>
                <a href="#" class="list-group-item list-group-item-action menu bg-dark" id="late">Late</a>
                <a href="#" class="list-group-item list-group-item-action menu bg-dark" id="excuse">Excuse</a>
                <a href="#" class="list-group-item list-group-item-action menu bg-dark" id="reset">Reset</a>
            </div>
        </div>
    </div>



    <!-- BODY END-->


    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

    <script>
        var syid = '<?php echo DB::table('sy')->where('isactive', 1)->first()->id; ?>';
        var sydesc = '<?php echo DB::table('sy')->where('isactive', 1)->first()->sydesc; ?>';

        var semid = '<?php echo DB::table('semester')->where('isactive', 1)->first()->id; ?>';
        var semdesc = '<?php echo DB::table('semester')->where('isactive', 1)->first()->semester; ?>';

        var schoolyears = @json($sy);
        var sections_g = null;
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
            "November", "December"
        ];
        var dataArray = [];
        var isSafeToClick = false;
        var currentMonthid = 0;
        var currentYearid = 0;
        var currentMousePos = {
            x: -1,
            y: -1
        };
        var studentAttendance = null;

        $('#attendance_table').scroll(function() { // If mu scroll ka mawala ang menu
            $('.tooltip').removeClass('tooltip-active');
            $('.spanholder').attr('click-count', 0);
        });


        $(document).ready(function() {

            $('#currentSY').text('SY: ' + sydesc);
            $('#currentSemester').text(semdesc);
            renderCalendar();
            getCurrentDate();
            get_semester();
            get_section();
            get_subject();
            get_coverage();
            get_months();
            get_schoolyear();



            $(document).on('click', '.date_cell', function(
            event) { //Event function responsible pag click sa calendar para makuha ang array of dates

                var id = $(this).attr('data-id');
                var colname = $(this).attr('col-name');
                var click = parseInt($(this).attr('clicked')) + 1;
                var monthid = $(this).attr('monthid');
                var yearid = $(this).attr('yearid');

                $('.tooltip').removeClass('tooltip-active');
                $('.spanholder').attr('click-count', 0);

                $(this).attr('clicked', parseInt(click));

                if (click == 1) {
                    $(this).addClass('bg-success');
                    $(this).removeClass('bg-danger');
                    $(this).removeClass('bg-warning');
                    $(this).removeClass('bg-info');
                    $(this).text('Present');
                    setAttendanceStatus(1, colname, id, monthid, yearid);

                } else if (click == 2) {
                    $(this).addClass('bg-danger');
                    $(this).removeClass('bg-success');
                    $(this).removeClass('bg-warning');
                    $(this).removeClass('bg-info');
                    $(this).text('Absent');
                    setAttendanceStatus(2, colname, id, monthid, yearid);

                } else if (click == 3) {
                    $(this).addClass('bg-warning');
                    $(this).removeClass('bg-danger');
                    $(this).removeClass('bg-success');
                    $(this).removeClass('bg-info');
                    $(this).text('Late');
                    setAttendanceStatus(3, colname, id, monthid, yearid);

                } else if (click == 4) {
                    $(this).addClass('bg-info');
                    $(this).removeClass('bg-warning');
                    $(this).removeClass('bg-danger');
                    $(this).removeClass('bg-success');
                    $(this).text('Excuse');
                    setAttendanceStatus(4, colname, id, monthid, yearid);

                } else if (click > 4) {

                    $(this).removeClass('bg-success');
                    $(this).removeClass('bg-danger');
                    $(this).removeClass('bg-warning');
                    $(this).removeClass('bg-info');
                    $(this).text(' ');
                    setAttendanceStatus(0, colname, id, monthid, yearid);

                    $(this).attr('clicked', 0);
                }


            });

            $(document).on('click', '.spanholder', function(event) { // Event function responsible for clicking menu

                var postion = $(this).offset();
                var clickCount = parseInt($(this).attr('click-count')) + 1;
                var monthid = $(this).attr('monthid');
                var yearid = $(this).attr('yearid');

                $('.spanholder').attr('click-count', 0);
                $(this).attr('click-count', parseInt(clickCount));

                if (clickCount == 1) {

                    $('.tooltip-holder').css('top', postion.top - 225);
                    $('.tooltip-holder').css('left', postion.left - 105);
                    $('.tooltip').addClass('tooltip-active');
                    // isClicked = true;

                    if ($(this).attr('menu-type') == 'row') {
                        $('.menu').removeAttr('colid');
                        $('.menu').attr('rowid', $(this).attr('data-id'));
                        $('.menu').attr('menu-type', 'row');
                        $('.menu').attr('monthid', monthid);
                        $('.menu').attr('yearid', yearid);

                    } else {
                        $('.menu').removeAttr('rowid');
                        $('.menu').attr('colid', $(this).attr('col-name'));
                        $('.menu').attr('menu-type', 'column');
                        $('.menu').attr('monthid', monthid);
                        $('.menu').attr('yearid', yearid);

                    }

                } else if (clickCount == 2) {

                    $('.tooltip').removeClass('tooltip-active');
                    $(this).attr('click-count', 0)

                } else {

                    console.log('Something is wrong');
                }


            });

            $(document).on('click', '#present', function(event) {

                if ($(this).attr('menu-type') == 'row') {

                    var id = $(this).attr('rowid');
                    bulkRowSetAttendanceSatatus(1, id);


                } else if ($(this).attr('menu-type') == 'column') {

                    var colname = $(this).attr('colid');
                    var monthid = $(this).attr('monthid');
                    var yearid = $(this).attr('yearid');

                    bulkColSetAttendanceSatatus(1, colname, monthid, yearid);
                }
            });

            $(document).on('click', '#absent', function(event) {

                if ($(this).attr('menu-type') == 'row') {

                    var id = $(this).attr('rowid');
                    bulkRowSetAttendanceSatatus(2, id);

                } else if ($(this).attr('menu-type') == 'column') {

                    var colname = $(this).attr('colid');
                    var monthid = $(this).attr('monthid');
                    var yearid = $(this).attr('yearid');

                    bulkColSetAttendanceSatatus(2, colname, monthid, yearid);
                }
            });

            $(document).on('click', '#late', function(event) {

                if ($(this).attr('menu-type') == 'row') {

                    var id = $(this).attr('rowid');
                    bulkRowSetAttendanceSatatus(3, id);

                } else if ($(this).attr('menu-type') == 'column') {

                    var colname = $(this).attr('colid');
                    var monthid = $(this).attr('monthid');
                    var yearid = $(this).attr('yearid');

                    bulkColSetAttendanceSatatus(3, colname, monthid, yearid);
                }
            });

            $(document).on('click', '#excuse', function(event) {

                if ($(this).attr('menu-type') == 'row') {

                    var id = $(this).attr('rowid');
                    bulkRowSetAttendanceSatatus(4, id);

                } else if ($(this).attr('menu-type') == 'column') {

                    var colname = $(this).attr('colid');
                    var monthid = $(this).attr('monthid');
                    var yearid = $(this).attr('yearid');

                    bulkColSetAttendanceSatatus(4, colname, monthid, yearid);
                }
            });

            $(document).on('click', '#reset', function(event) {

                if ($(this).attr('menu-type') == 'row') {

                    var id = $(this).attr('rowid');
                    bulkRowSetAttendanceSatatus(0, id);

                } else if ($(this).attr('menu-type') == 'column') {

                    var colname = $(this).attr('colid');
                    var monthid = $(this).attr('monthid');
                    var yearid = $(this).attr('yearid');
                    bulkColSetAttendanceSatatus(0, colname, monthid, yearid);
                }
            });




            $(document).on('click', '#pdf_exportbtn', function(event) {

                if (isSafeToClick) {

                    $('#exportmodal').modal();
                    $('#exportmodalLabel').text("Export PDF");
                    $('#export').removeClass("btn-success");
                    $('#export').addClass("btn-danger");
                    $('#export').attr("export-type", "pdf");

                } else {

                    notify('warning', "Please genereate first!")
                }

            });

            $(document).on('click', '#xlsx_exportbtn', function(event) {

                if (isSafeToClick) {

                    $('#exportmodal').modal();
                    $('#exportmodalLabel').text("Export XLSX");
                    $('#export').removeClass("btn-danger");
                    $('#export').addClass("btn-success");
                    $('#export').attr("export-type", "xlsx");

                } else {

                    notify('warning', "Please genereate first!")
                }

            });

            // $(document).on('change', '#export_coverage',function (event) {

            //     if($(this).val() == 1){

            //         $('#byStudent').addClass('hidden');
            //         $('#byMonth').addClass('hidden');


            //     }else{

            //         $('#byStudent').removeClass('hidden');
            //         $('#byMonth').removeClass('hidden');
            //     }

            // });

            $(document).on('change', '#subjectid', function(event) {
                load_sections($(this).val());

                if (isSafeToClick) {

                    getStatusByMonth();
                }

            });

            $(document).on('change', '#sectionid', function(event) {

                if (isSafeToClick) {

                    dataArray = [];

                    $('.fc-daygrid-day').attr("click_count", 0);
                    $('.fc-daygrid-day').css('background', 'white');
                    getCurrentDate();
                    setGenerateAttendance();
                }

            });


            $(document).on('click', '#export', function(event) {
                var type = $(this).attr('export-type');

                let sectionid = $('#sectionid').val();
                let semid = $('#semid').val();
                let subjectid = $('#subjectid').val();
                let syid = $('#export_sy').val();
                let bymonthArray = $('#export_month').val();


                var exportCoverage = $('#export_coverage').val();

                if (type == "pdf") {

                    // let array = [];

                    // for (let i = 0; i < dataArray.length; i++) {

                    //     array.push(dataArray[i].monthid);
                    // }

                    // let arraymonth = dataArray.filter((element, index) => {
                    //     return array.indexOf(element.monthid) === index;
                    // });

                    // arraymonth = JSON.stringify(arraymonth);

                    let arraymonth = JSON.stringify(bymonthArray);

                    window.open('/college/attendance/generate-pdf' + '/' + syid + '/' + subjectid + '/' +
                        sectionid + '/' + semid + '/' + arraymonth, '_blank');


                } else {

                    let arraymonth = JSON.stringify(bymonthArray);
                    window.open('/college/attendance/generate-excel' + '/' + syid + '/' + subjectid + '/' +
                        sectionid + '/' + semid + '/' + arraymonth);
                }



            });


        });

        function toFindDuplicates(arry) {
            const uniqueElements = new Set(arry);
            const filteredElements = arry.filter(item => {
                if (uniqueElements.has(item)) {
                    uniqueElements.delete(item);
                } else {
                    return item;
                }
            });

            return [...new Set(uniqueElements)]
        }

        function renderCalendar() { //Rendering the calendar para makita sa page and some configurations

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, { // Configuration sa Full Calendar

                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                selectable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                aspectRatio: 0.9,
                dayCellClassNames: 'calendardays',
                dayCellContent: function(info, create) {
                    const element = create('span', {
                        id: "fc-day-span-"
                    }, info.dayNumberText);
                    return element;
                },
                dayCellContent: function(info, create) {
                    const element = create('div', {
                        id: "fc-day-span-"
                    }, info.dayNumberText);
                    return element;
                },

                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'currentday',

                },
                customButtons: {
                    currentday: {
                        text: 'Today',
                        click: function() {
                            if (isSafeToClick) {

                                calendar.today();

                                dataArray.length = new Array();

                                $('.fc-daygrid-day').attr("click_count", 0);
                                $('.fc-daygrid-day').css('background', 'white');

                                getCurrentDate();

                                // const date = new Date();

                                // let day = date.getDate();
                                // let month = date.getMonth() + 1;
                                // let year = date.getFullYear();
                                // var currentDate = `${year}-${month}-${day}`;
                                // var today = monthNames[date.getMonth()]+" "+date.getDate();
                                // var value = monthNames[date.getMonth()]+" "+date.getDate();

                                // var obj = {date: value, col_name: 'day'+date.getDate(), monthid: date.getMonth()+1, yearid: date.getFullYear(), datadate: currentDate};
                                // dataArray.push(obj);

                                // $('td[data-date='+currentDate+']').attr('click_count', 1);
                                // $('td[data-date='+currentDate+']').css('background', '#007bff');
                                setGenerateAttendance();

                            } else {
                                notify('warning', "Please genereate first!")
                            }
                        }
                    },

                },
                businessHours: {
                    startTime: '06:00',
                    endTime: '19:00',
                },
                select: function(info) {

                    var date = info.startStr;
                    var end = info.endStr;
                    var formatDate = calendar.formatDate(date, {
                        month: 'short',
                        year: 'numeric',
                        day: 'numeric',
                    });

                },

            });

            calendar.render();

            $('.fc-currentday-button').addClass('btn-sm p-1')
            $('.fc-prev-button').addClass('btn-sm p-1')
            $('.fc-next-button').addClass('btn-sm p-1')

            $('.fc-prev-button').css('font-size', '8px')
            $('.fc-next-button').css('font-size', '8px')
            $('.fc-toolbar-title').css('font-size', '20px')

            $('.fc-currentday-button').css('font-size', '12px')
            $('.fc-generate-button').addClass('btn-sm mt-3 bg-danger border-danger generate')

            $('.fc-toolbar').css('margin', '0')
            $('.fc-toolbar').css('padding-top', '0')
            $('.fc-toolbar').css('font-size', '12px')
            $('.fc-day-today').css("background", "#007bff");
            $('.calendardays').attr("click_count", 0);

            $(document).on('change', '#syid', function(event) {

                if (!$(this).val()) {
                    return
                }

                console.log('this is syid...');
                console.log($(this).val());

                $.ajax({
                    type: "GET",
                    url: "{{ route('getFilterSubject') }}",
                    data: {
                        syid: $(this).val(),
                        semid: $('#semid').val(),
                    },
                    success: function(data) {
                        console.log(data);
                        if (data !== null) {
                            $('#subjectid').empty()
                            $('#subjectid').append('<option value="">Select Subject</option>')
                            $("#subjectid").select2({
                                data: data,
                                // allowClear: true,
                                placeholder: "Select Subject",
                            })
                        }
                    }
                })
            })



            $(document).on('click', '#btn_generate', function(event) {
                // setGenerateAttendance();
                nexprevGenerate();

            });

            $(document).on('click', '.fc-prev-button', function(event) { //Calendar preview button
                if (isSafeToClick) {
                    nexprevGenerate();
                } else {
                    notify('warning', 'Please generate first!');
                }
            });

            $(document).on('click', '.fc-next-button', function(event) { //Calendar next button
                if (isSafeToClick) {

                    nexprevGenerate();

                } else {
                    notify('warning', 'Please generate first!');
                }

            });

            $(document).on('click', 'td .fc-daygrid-day', function(
            event) { //Clicking sa date para mu highlight daw assignan ug value ang checkbox

                if (isSafeToClick) {

                    var checkbox = $(this);
                    var currentclick = checkbox.attr('click_count');
                    checkbox.attr('click_count', parseInt(currentclick) + 1);

                    // checkbox.prop("checked", !checkbox.prop("checked"));
                    var monthidArray = $(this).attr('data-date').split("-");
                    const date = new Date($(this).attr('data-date'));

                    if (checkbox.attr('click_count') == 1) {

                        checkbox.attr('val', monthNames[date.getMonth()] + " " + date.getDate())
                        checkbox.attr('col_name', 'day' + date.getDate());
                        checkbox.attr('monthid', parseInt(monthidArray[1]));
                        checkbox.attr('yearid', parseInt(monthidArray[0]));

                        checkbox.css('background', '#007bff');


                        $("td[click_count='1']").each(function() {

                            var obj = {
                                date: $(this).attr('val'),
                                col_name: $(this).attr('col_name'),
                                monthid: $(this).attr('monthid'),
                                yearid: $(this).attr('yearid'),
                                datadate: $(this).attr('data-date')
                            };

                            if (!dataArray.find(({
                                    datadate
                                }) => datadate === obj.datadate)) {
                                dataArray.push(obj);
                            }

                        });


                        if (dataArray.length == 0) {
                            notify('error', 'Please select day to generate.');

                        } else {

                            setGenerateAttendance();
                            console.log(dataArray);

                        }

                    } else {

                        checkbox.attr('click_count', 0);

                        $(this).css('background', 'white');

                        var col_name = checkbox.attr('col_name');

                        const indexOfObject = dataArray.findIndex(object => {
                            return object.col_name === col_name;
                        });


                        dataArray.splice(indexOfObject, 1);
                        console.log(dataArray);
                        setGenerateAttendance();
                    }


                    $('.tooltip').removeClass('tooltip-active');
                    $('.spanholder').attr('click-count', 0);


                } else {

                    notify('warning', "Please genereate first!")
                }

            })

            function nexprevGenerate() {

                var date = calendar.getDate();
                var monthid = date.getMonth() + 1;
                var yearid = calendar.formatDate(date, {
                    year: 'numeric',
                });
                var syid = $('#syid').val();
                var semid = $('#semid').val();
                var subjectid = $('#subjectid').val();
                var sectionid = $('#sectionid').val();

                $.ajax({
                    url: '{{ route('clgattndcGetColumns') }}',
                    method: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        subjectid: subjectid,
                        sectionid: sectionid,
                        monthid: monthid,
                        yearid: yearid,
                    },
                    success: function(data) {

                        if (data[0]['status'] == 400) {

                            $('.tooltip').removeClass('tooltip-active');
                            $('.spanholder').attr('click-count', 0);

                            Swal.fire({
                                title: months[data[0]['monthid'] - 1] + ' not yet generated?',
                                text: "Do you want to generate " + months[data[0]['monthid'] - 1] +
                                    " " + data[0]['yearid'] + "?",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, generate!'
                            }).then((result) => {

                                if (result.value) {

                                    dataArray = [];
                                    dataArray = data[0]['data'];
                                    generatingSpecificMonth(data[0]['monthid'], data[0]['yearid'])

                                    $('.calendardays').attr("click_count", 0);
                                    $('.calendardays').css('background', 'white');
                                    $("td[click_count='0']").each(function() {

                                        for (let i = 0; i < dataArray.length; i++) {

                                            if ($(this).attr('data-date') == dataArray[i]
                                                .datadate) {

                                                $(this).css('background', '#007bff');
                                                $(this).attr("click_count", 1);
                                            }

                                        }
                                    });

                                } else {

                                    var popped = dataArray.pop();
                                    $('td[data-date=' + popped['datadate'] + ']').attr('click_count',
                                    0);
                                    $('td[data-date=' + popped['datadate'] + ']').css('background',
                                        'white');

                                }
                            })

                        } else if (data[0]['status'] == 404) {

                            dataArray = [];
                            dataArray = data[0]['data'];
                            console.log(dataArray);
                            setGenerateAttendance();

                            $('.calendardays').attr("click_count", 0);
                            $('.calendardays').css('background', 'white');
                            $("td[click_count='0']").each(function() {

                                for (let i = 0; i < dataArray.length; i++) {

                                    if ($(this).attr('data-date') == dataArray[i].datadate) {

                                        $(this).css('background', '#007bff');
                                        $(this).attr("click_count", 1);
                                    }

                                }
                            });

                        } else {

                            dataArray = [];
                            dataArray = data[0]['data'];
                            console.log(dataArray);
                            setGenerateAttendance();

                            $('.calendardays').attr("click_count", 0);
                            $('.calendardays').css('background', 'white');
                            $("td[click_count='0']").each(function() {

                                for (let i = 0; i < dataArray.length; i++) {

                                    if ($(this).attr('data-date') == dataArray[i].datadate) {

                                        $(this).css('background', '#007bff');
                                        $(this).attr("click_count", 1);
                                    }

                                }
                            });
                        }

                    }

                });
            }


        }

        // GET
        function getCurrentDate() { // Para makuha always makuha ang current date

            dataArray.length = new Array();

            const date = new Date();

            let day = date.getDate();
            let month = date.getMonth() + 1;

            if (day < 10) {

                day = "0" + day;
            }

            if (month < 10) {

                month = "0" + month;
            }

            let year = date.getFullYear();
            var currentDate = `${year}-${month}-${day}`;
            var today = monthNames[date.getMonth()] + " " + date.getDate();
            var value = monthNames[date.getMonth()] + " " + date.getDate();

            // $('td[data-date='+currentDate+']').children('input').prop("checked", true);
            $('td[data-date=' + currentDate + ']').attr('click_count', 1);
            $('td[data-date=' + currentDate + ']').attr('val', value);
            $('td[data-date=' + currentDate + ']').attr('col_name', 'day' + date.getDate());
            $('td[data-date=' + currentDate + ']').attr('monthid', date.getMonth() + 1);
            $('td[data-date=' + currentDate + ']').attr('yearid', date.getFullYear());

            $("td[click_count='1']").each(function() {

                if ($(this).attr('val') == value) {

                    var obj = {
                        date: $(this).attr('val'),
                        col_name: $(this).attr('col_name'),
                        monthid: $(this).attr('monthid'),
                        yearid: $(this).attr('yearid'),
                        datadate: currentDate
                    };
                    dataArray.push(obj);
                    $(this).css('background', '#007bff');
                    currentMonthid = $(this).attr('monthid');
                    currentYearid = $(this).attr('yearid');

                } else {

                    $(this).prop("checked", false);
                    $(this).parent().css('background', 'white');
                }

            });

            console.log(currentDate);



            // dataArray.push(currentDate);


        }

        function get_section() { // Para ma display ang mga sections sa select2

            $('#sectionid').empty()
            $('#sectionid').append('<option value="">Select Sections</option>')
            $("#sectionid").select2({
                data: sections_g,
                // allowClear: true,
                placeholder: "Select Section",
            })

        }

        function load_sections(subjectid) {
            $.ajax({
                url: '{{ route('clgattndcGetSelect') }}',
                method: 'GET',
                data: {
                    subjectid: subjectid,
                },
                success: function(data) {
                    sections_g = data[0]['section']
                    get_section();
                }
            });
        }

        function get_semester() { // Para ma display ang mga semester sa select2

            var semester = [

                {
                    "id": 1,
                    "text": "1st Semester"
                },
                {
                    "id": 2,
                    "text": "2nd Semester"
                },
                {
                    "id": 3,
                    "text": "Summer"
                },
            ];

            $('#semid').empty()
            $('#semid').append('<option value="">Select Sections</option>')
            $("#semid").select2({
                data: semester,
                // allowClear: true,
                placeholder: "Select Sections",
            });

            if (semid != null) {

                $('#semid').val(semid).change()
            }
        }

        function get_subject() { // Para ma display ang mga subject sa select2

            var subject = @json($subject);

            console.log('this is subjects...');
            console.log(subject);

            $('#subjectid').empty()
            $('#subjectid').append('<option value="">Select Subject</option>')
            $("#subjectid").select2({
                data: subject,
                // allowClear: true,
                placeholder: "Select Subject",
            })
        }

        function get_students() { // Para ma display ang mga students sa select2 export

            let sectionid = $('#sectionid').val();
            let semid = $('#semid').val();
            let subjectid = $('#subjectid').val();

            $.ajax({
                url: '{{ route('clgattndcGetSelect') }}',
                method: 'GET',
                data: {
                    sectionid: sectionid,
                    semid: semid,
                    subjectid: subjectid,
                    syid: syid,
                },

                success: function(data) {

                    $('#export_student').empty()
                    $('#export_student').append('<option value="">Select Student</option>')
                    $("#export_student").select2({
                        data: data[0]['enrollstudents'],
                        allowClear: true,
                        placeholder: "Select Students",
                    })

                    get_sy(data[0]['sy']);

                }

            });

        }

        function get_months() { // Para ma display ang mga months sa select2

            var months = [{
                    id: 1,
                    text: "January"
                },
                {
                    id: 2,
                    text: "Febuary"
                },
                {
                    id: 3,
                    text: "March"
                },
                {
                    id: 4,
                    text: "April"
                },
                {
                    id: 5,
                    text: "May"
                },
                {
                    id: 6,
                    text: "June"
                },
                {
                    id: 7,
                    text: "July"
                },
                {
                    id: 8,
                    text: "August"
                },
                {
                    id: 9,
                    text: "September"
                },
                {
                    id: 10,
                    text: "October"
                },
                {
                    id: 11,
                    text: "November"
                },
                {
                    id: 12,
                    text: "December"
                },
                {
                    id: 13,
                    text: "All"
                },
            ];

            $('#export_month').empty()
            $("#export_month").select2({
                data: months,
                allowClear: true,
                placeholder: "Select Months",
                multiple: true,
            })
        }

        function get_coverage() { // Para ma display ang mga coverage sa select2

            var coverage = [
                // {id: 1, text: "Current Table"},
                // {id: 2, text: "Specific Student"},
                {
                    id: 1,
                    text: "Monthly"
                },
            ];

            $('#export_coverage').empty()
            $('#export_coverage').append('<option value="">Select Coverage</option>')
            $("#export_coverage").select2({
                data: coverage,
                allowClear: true,
                placeholder: "Select Coverage",
            })
        }

        function get_sy(sy) {

            $('#export_sy').empty()
            $('#export_sy').append('<option value="">Select School Year</option>')
            $("#export_sy").select2({
                data: sy,
                // allowClear: true,
                placeholder: "Select School Year",
            })

            if (syid != null) {

                $('#export_sy').val(syid).change()
            }

        }

        function get_schoolyear() {

            $('#syid').empty()
            $('#syid').append('<option value="">Select SY</option>')
            $("#syid").select2({
                data: schoolyears,
                allowClear: true,
                placeholder: "Select SY",
            })

            if (syid != null) {

                $('#syid').val(syid).change()
            }

        }

        function getGenerateAttendance(sectionid, subjectid, semid, syid, monthid,
        yearid) { // The actual function sa pag generate sa attendance
            console.log(dataArray);
            $.ajax({
                url: '{{ route('clgattndcGenerateAttendance') }}',
                method: 'GET',
                data: {
                    sectionid: sectionid,
                    semid: semid,
                    subjectid: subjectid,
                    syid: syid,
                    monthid: monthid,
                    yearid: yearid,
                    dataArray: dataArray,
                },
                success: function(data) {

                    if (data[0]['status'] == 400) {

                        $('.tooltip').removeClass('tooltip-active');
                        $('.spanholder').attr('click-count', 0);

                        Swal.fire({
                            title: months[data[0]['monthid'] - 1] + ' not yet generated?',
                            text: "Do you want to generate " + months[data[0]['monthid'] - 1] + " " +
                                data[0]['yearid'] + "?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, generate!'
                        }).then((result) => {

                            if (result.value) {

                                generatingSpecificMonth(data[0]['monthid'], data[0]['yearid'])

                            } else {

                                var popped = dataArray.pop();
                                $('td[data-date=' + popped['datadate'] + ']').attr('click_count', 0);
                                $('td[data-date=' + popped['datadate'] + ']').css('background',
                                'white');


                            }
                        })

                    } else {

                        $('#attendance_table').html(data[0]['output']);
                        studentAttendance = data[0]['enrollstudents'];
                        get_students();

                    }


                }
            });

        }

        function getStatusByMonth() {

            dataArray = [];

            $('.fc-daygrid-day').attr("click_count", 0);
            $('.fc-daygrid-day').css('background', 'white');
            getCurrentDate();
            setGenerateAttendance();
        }



        // SET


        function setGenerateAttendance() { // Function responsible for validating and generating the attendance
            let sy = $('#syid').val();
            let semid = $('#semid').val();

            let sectionid = $('#sectionid').val();
            let subjectid = $('#subjectid').val();

            if (semid == "" && sectionid == "") {

                notify('error', 'Please select Section and Semester.');

            } else if (semid == "") {

                notify('error', 'Please select Semester.');

            } else if (sectionid == "") {

                notify('error', 'Please select Section.');

            } else {

                getGenerateAttendance(sectionid, subjectid, semid, sy, currentMonthid, currentYearid);
                isSafeToClick = true;
            }
        }

        function setAttendanceStatus(status, colname, id, monthid,
        yearid) { // Function responsible for setting the attendance status

            let sectionid = $('#sectionid').val();
            let semid = $('#semid').val();
            let subjectid = $('#subjectid').val();

            $.ajax({
                url: '{{ route('clgattndcSetStatus') }}',
                method: 'GET',
                data: {
                    studid: id,
                    colname: colname,
                    status: status,
                    monthid: monthid,
                    yearid: yearid,
                    sectionid: sectionid,
                    semid: semid,
                    subjectid: subjectid,
                    syid: syid,
                },
                success: function(data) {


                }
            });
        }

        function bulkRowSetAttendanceSatatus(status, id) { //Setting student's attendance status in bulk by row
            let sectionid = $('#sectionid').val();
            let semid = $('#semid').val();
            let subjectid = $('#subjectid').val();

            $.ajax({
                url: '{{ route('clgattndcBulkSetRowStatus') }}',
                method: 'GET',
                data: {
                    id: id,
                    colname: dataArray,
                    status: status,
                    sectionid: sectionid,
                    subjectid: subjectid,
                    semid: semid,
                    syid: syid,
                },
                success: function(data) {

                    $('.tooltip').removeClass('tooltip-active');
                    setGenerateAttendance();
                }
            });
        }

        function bulkColSetAttendanceSatatus(status, colname, monthid,
        yearid) { //Setting student's attendance status in bulk by column

            let sectionid = $('#sectionid').val();
            let semid = $('#semid').val();
            let subjectid = $('#subjectid').val();

            $.ajax({
                url: '{{ route('clgattndcBulkSetColStatus') }}',
                method: 'GET',
                data: {
                    colname: colname,
                    status: status,
                    sectionid: sectionid,
                    semid: semid,
                    subjectid: subjectid,
                    syid: syid,
                    monthid: monthid,
                    yearid: yearid,
                    students: studentAttendance,

                },
                success: function(data) {

                    $('.tooltip').removeClass('tooltip-active');
                    setGenerateAttendance();
                }
            });
        }

        function generatingSpecificMonth(monthid, yearid) {

            let sectionid = $('#sectionid').val();
            let semid = $('#semid').val();
            let subjectid = $('#subjectid').val();

            $.ajax({
                url: '{{ route('clgattndcGeneratePerMonth') }}',
                method: 'GET',
                data: {
                    sectionid: sectionid,
                    semid: semid,
                    subjectid: subjectid,
                    syid: syid,
                    monthid: monthid,
                    yearid: yearid,
                    students: studentAttendance,

                },
                success: function(data) {

                    setGenerateAttendance()

                }

            });

        }


        /////////////SWEET ALERT///////////////
        function notify(code, message) {
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
