@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.0/css/rowGroup.dataTables.min.css">
    <style>
        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            outline: 2px solid #dee2e6;
            outline-offset: -1px;


        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .grade_td {
            cursor: pointer;
            vertical-align: middle !important;
        }

        #dropdown-item {
            background-color: green;
            color: white;
            cursor: pointer;
            border-radius: 5%;
            margin-bottom: 2px;
            width: 120px;
            font-size: 13px;
            padding: 6px;
            text-align: center;
        }

        .sort-icon {
            font-size: 14px;
            color: black;
            /* Example color */
        }

        .sort-icon:hover {
            color: blue;
            /* Example hover color */
        }

        #grade_submissions {
            cursor: pointer;
            background-color: rgb(60, 114, 181);
            color: white;
        }

        #grade_submissions:hover {
            background-color: rgba(29, 62, 103, 0.859);
        }
    </style>
@endsection

@section('content')
    @php
        $levelname = DB::table('college_year')->get();

    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grade Input</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/college/teacher/student/collegesystemgrading">System
                                Grading</a></li>
                        <li class="breadcrumb-item active">Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="d-flex flex-row justify-content-end" id="term_container">
            {{-- <div>
                <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="Prelim">Prelim</button>
            </div>
            <div>
                <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="Midterm">Midterm</button>
            </div>
            <div>
                <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="Pre-Final">Pre-Final</button>
            </div>
            <div>
                <button class="btn btn-sm  btn-success ml-2 mr-2 term_buttons" data-term="Final">Final</button>
            </div> --}}
        </div>
        <div class="info-box shadow">
            {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
            <div class="info-box-content">
                <div class="row" style="font-size:.7rem">
                    <div class="col-md-2">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Teacher</label>
                        <p class="mb-0" id="teacherName"></p>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Subject</label>
                        <p class="mb-0" id="subjectDesc"></p>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Level</label>
                        <p class="mb-0" id="levelName"></p>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Section</label>
                        <p class="mb-0" id="sectionName"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="info-box shadow mt-2 row mx-1" id="ecr_table_container" style="height: 350px!important;amx-height: 350px!important" >
            
        </div>
        <div width="100%" class="info-box shadow mt-2 row mx-1 py-4 d-flex flex-row justify-content-end">
            <button class="btn btn-success btn-sm d-none ml-auto" id="save_grades">Save Grades</button>
            <button class="btn btn-primary btn-sm ml-2 d-none" id="submit_grades">Submit Grades</button>
        </div>
    </section>
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    {{-- <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script> --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/rowgroup/1.1.0/js/dataTables.rowGroup.min.js"></script> --}}


    <script>
        $(document).ready(function() {
            var schedid = @json($schedid);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            get_scheddetails();

            function get_scheddetails() {

                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/getsched',
                    data: {
                        schedid: schedid
                    },
                    success: function(response) {
                        $('#teacherName').text(response.lastname + ', ' + response.firstname);
                        $('#subjectDesc').text(response.subjCode + ' - ' + response.subjDesc);
                        $('#levelName').text(response.levelname);
                        $('#sectionName').text(response.sectionDesc);
                        get_ecr_template()
                    }
                })
            }

            function disable_ecr() {
                $('.score').removeAttr('contenteditable');
                $('.highest_score').removeAttr('contenteditable');
                $('.date_time').removeAttr('contenteditable');

            }

            var syid = @json($syid);
            var semid = @json($semid);

            function get_ecr_template() {
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/getecr',
                    data: {
                        schedid: schedid,
                        syid: syid,
                        semid: semid

                    },
                    success: function(response) {
                        $('#ecr_table_container').html(response);
                        disable_ecr()
                    }
                })
            }
            terms_applied()

            function terms_applied() {
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/getterms',
                    data: {
                        schedid: schedid
                    },
                    success(response) {
                        $.each(response, function(a, term) {
                            $('#term_container').append(
                                `
                                    <div>
                                        <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="${term.quarter}">${term.description}</button>
                                    </div>
                                `
                            )
                        })
                    }
                })
            }

            var term;
            $(document).on('click', '.term_buttons', function() {
                if (changes == 1) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Please save your changes before changing term'
                    })
                } else {
                    $('.gender_checkbox').prop('checked', true).trigger('change');
                    $('.scores').removeClass('bg-success');
                    $('.total_average').removeClass('bg-success');
                    $('.total_score').removeClass('bg-success');
                    $('.average_score').removeClass('bg-success');
                    $('.average_score').removeClass('bg-success');
                    $('.gen_average').removeClass('bg-success');
                    $('#gradeRibbon').remove();
                    $('.checkbox').removeAttr('disabled')
                    term = $(this).attr('data-term');
                    $('.term_buttons').removeClass('btn-outline-success').addClass('btn-success');
                    $('.term_buttons[data-term=' + term + ']').removeClass('btn-success').addClass(
                        'btn-outline-success');
                    $('.date_time').text('').trigger('change')
                    $('.highest_score').text(0)
                    $('.score').trigger('input')
                    $('.score').attr('contenteditable', 'true');
                    $('.highest_score').attr('contenteditable', 'true');
                    $('.date_time').attr('contenteditable', 'true');
                    $('#save_grades').removeClass('d-none');
                    $('#submit_grades').removeClass('d-none');
                    display_term_grading()
                }

            })

            function display_term_grading() {
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/get_grades',
                    data: {
                        schedid: schedid,
                        term: term
                    },
                    success: function(grades) {


                        $.each(grades.highest_scores, function(a, high_score) {

                            if (high_score.subcomponent_id != 0) {
                                $('.highest_score[data-sort-id=' + high_score.column_number +
                                        '][data-comp-id=' + high_score.component_id +
                                        '][data-sub-id=' + high_score.subcomponent_id + ']')
                                    .text(high_score.score);
                                $('.date_time[data-sort-id=' + high_score.column_number +
                                        '][data-comp-id=' + high_score.component_id +
                                        '][data-sub-id=' + high_score.subcomponent_id + ']')
                                    .text(high_score.date).trigger('change');
                            } else {
                                $('.highest_score[data-sort-id=' + high_score.column_number +
                                    '][data-comp-id=' + high_score.component_id + ']').text(
                                    high_score.score);
                                $('.date_time[data-sort-id=' + high_score.column_number +
                                    '][data-comp-id=' + high_score.component_id + ']').text(
                                    high_score.date).trigger('change');
                            }
                        })
                        $.each(grades.grade_scores, function(a, grade) {
                            if (grade.subcomponent_id != 0) {
                                if (grade.score === 'INC') {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                            '][data-comp-id=' + grade.componentid +
                                            '][data-sub-id=' + grade.subcomponent_id +
                                            '][data-stud-id=' + grade.studid + ']').text('I')
                                        .trigger('input')
                                } else {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                        '][data-comp-id=' + grade.componentid +
                                        '][data-sub-id=' + grade.subcomponent_id +
                                        '][data-stud-id=' + grade.studid + ']').text(grade
                                        .score).trigger('input')
                                }
                            } else {
                                if (grade.score === 'INC') {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                            '][data-comp-id=' + grade.componentid +
                                            '][data-stud-id=' + grade.studid + ']').text('I')
                                        .trigger('input')
                                } else {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                        '][data-comp-id=' + grade.componentid +
                                        '][data-stud-id=' + grade.studid + ']').text(grade
                                        .score).trigger('input')
                                }
                            }

                        })
                        var studCount = $('.student').length;
                        let status = grades.grade_status.every(status => status.status_flag > 0 && status.status_flag != 6);
                        if(status && grades.grade_status.length > 0 && grades.grade_status.length == studCount) {
                            $('#ecr_table_container').append('<div class="ribbon-wrapper ribbon-lg" id="gradeRibbon" style="z-index: 101"><div class="ribbon bg-success" id="gradeRibbonMessage">SUBMITTED</div></div>')
                            $('.highest_score').removeAttr('contenteditable');
                            $('#save_grades').addClass('d-none');
                            $('#submit_grades').addClass('d-none');
                        } else {
                            $('#gradeRibbon').remove();
                            $('.scores').attr('contenteditable', 'true');
                            $('.highest_score').attr('contenteditable', 'true');
                            $('.date_time').attr('contenteditable', 'true');
                        }

                        $.each(grades.grade_status, function(a, status) {
                            if (status.status_flag > 0 && status.status_flag != 6) {
                                $('[data-stud-id=' + status.studid + ']').removeClass(
                                    'bg-warning')
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-success')
                                    .removeAttr('contenteditable');
                                $('.checkbox[data-stud-id=' + status.studid + ']').attr(
                                    'disabled', true).prop('checked', false);
                            }
                            if (status.status_flag == 6) {
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-warning')
                            }
                        })
                    }
                })
            }



            var total_score = 0;
            var total_highest_score = 0;
            var changes = 0;
            $(document).on('input', '.score', function() {
                if ($(this).text() === 'i' || $(this).text() === 'I') {
                    $(this).text('INC')
                } else {
                    let text = $(this).text().replace(/[^0-9]/g, "");
                    $(this).text(text);
                }


                let range = document.createRange();
                let sel = window.getSelection();
                range.selectNodeContents(this);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);


                var studid = $(this).attr('data-stud-id');
                var compid = $(this).attr('data-comp-id');
                var subid = $(this).attr('data-sub-id');
                var sort = $(this).attr('data-sort-id');



                if ($(this).text().trim() === 'INC') {
                    $('.score[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $(this).addClass('bg-warning').attr('data-inc', '1')

                    $('.total_average[data-stud-id=' + studid + ']').html('INC').addClass('bg-warning');

                } else if (subid) {
                    $(this).removeClass('bg-warning')
                    $('.total_average[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.total_score[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.average_score[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.gen_average[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.scores[data-stud-id=' + studid + ']').not('[data-inc]').removeClass('bg-warning')

                    var high_sort = parseInt($('.highest_score[data-sort-id=' + sort + '][data-sub-id=' +
                        subid + ']').text());
                    if (parseInt($(this).text()) > high_sort) {
                        $(this).text(high_sort)
                    }

                    total_score = 0
                    $('.scores[data-stud-id=' + studid + '][data-sub-id=' + subid + ']').each(function() {
                        total_score += parseInt($(this).text()) || 0;
                    });
                    $('.total_score[data-stud-id=' + studid + '][data-sub-id=' + subid + ']').html(
                        total_score);
                    total_highest_score = 0
                    $('.highest_score[data-sub-id=' + subid + ']').each(function() {
                        total_highest_score += parseInt($(this).text()) || 0;
                    });
                    var average = isNaN(parseFloat((total_score / total_highest_score) * 100)) ? 0 :
                        parseFloat((total_score / total_highest_score) * 100).toFixed(2)

                    $('.average_score[data-stud-id=' + studid + '][data-sub-id=' + subid + ']').html(
                        average, '%')
                    var gen_average = 0;

                    $('.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').each(
                        function() {
                            var score = parseFloat($(this).text()) || 0;
                            var percentage = parseFloat($(this).attr('data-percentage')) || 0;

                            gen_average += (score * (percentage / 100));
                        });

                    $('.gen_average[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        gen_average.toFixed(2));


                } else {
                    var high_sort = parseInt($('.highest_score[data-sort-id=' + sort + '][data-comp-id=' +
                        compid + ']').text());
                    if (parseInt($(this).text()) > high_sort) {
                        $(this).text(high_sort)
                    }

                    total_score = 0
                    $('.scores[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').each(function() {
                        total_score += parseInt($(this).text()) || 0;
                    });
                    $('.total_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        total_score);
                    total_highest_score = 0
                    $('.highest_score[data-comp-id=' + compid + ']').each(function() {
                        total_highest_score += parseInt($(this).text()) || 0;
                    });
                    var average = isNaN(total_score / total_highest_score) ? 0 : parseFloat((total_score /
                        total_highest_score) * 100).toFixed(2);

                    $('.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        average, '%')
                    var gen_average = 0
                    $('.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').each(
                        function() {
                            gen_average += parseFloat($(this).text()) || 0;
                        })
                    gen_average = isNaN(gen_average) ? 0 : parseFloat(gen_average / $(
                            '.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']')
                        .length).toFixed(2)
                    $('.gen_average[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        gen_average);
                }

                var hasINC = false;
                $('.scores[data-stud-id=' + studid + ']').each(function() {
                    if ($(this).text().trim() === 'INC') {
                        hasINC = true;
                        return false; // break the loop
                    }
                });


                if (hasINC) {
                    $('.total_average[data-stud-id=' + studid + ']').html('INC').addClass('bg-warning');
                } else {
                    var total_average = 0;
                    $('.gen_average[data-stud-id=' + studid + ']').each(function() {
                        var gen_ave = $(this).text();
                        var percentage = parseFloat($(this).attr('data-percentage')) || 0;

                        total_average += (gen_ave * (percentage / 100));
                    });
                    $('.total_average[data-stud-id=' + studid + ']').html(total_average.toFixed(2));
                }

            })


            $(document).on('click', '#save_grades', function() {
                if (!term) {
                    Toast.fire({
                        type: 'error',
                        title: 'Please select a term'
                    })
                } else {
                    var highest_scores = []
                    var scores = []
                    var term_averages = []

                    $.each($('.highest_score'), function() {
                        var sort = $(this).data('sort-id')
                        var component_id = $(this).data('comp-id')
                        var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(this).data(
                            'sub-id') : 0;
                        var highest_score_text = $(this).text()
                        if (subid == 0) {
                            var date = $('.date_time[data-sort-id=' + sort + '][data-comp-id=' +
                                component_id + ']').text();
                            if (date == 'MM/DD/YYYY') {
                                date = ''
                            }

                        } else {
                            var date = $('.date_time[data-sort-id=' + sort + '][data-sub-id=' +
                                subid + ']').text();
                            if (date == 'MM/DD/YYYY') {
                                date = ''
                            }
                        }

                        var highest_score = {
                            schedid: schedid,
                            component_id: component_id,
                            subid: subid,
                            highest_score: highest_score_text,
                            term: term,
                            date: date,
                            sort: sort
                        }
                        highest_scores.push(highest_score)
                    })
                    $.each($('.checkbox:checked'), function() {
                        var studid = $(this).data('stud-id')
                        $.each($('.total_average[data-stud-id=' + studid + ']'), function() {
                            var term_average = $(this).text()
                            var averages = {
                                term_average: term_average,
                                studid: studid,
                                schedid: schedid,
                                term: term
                            }

                            term_averages.push(averages)
                        })
                        $.each($('.scores[data-stud-id=' + studid + ']'), function() {
                            var sort = $(this).data('sort-id')
                            var component_id = $(this).data('comp-id')
                            var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(
                                this).data('sub-id') : 0;
                            var score_text = $(this).text()
                            var studid = $(this).data('stud-id')

                            var score = {
                                schedid: schedid,
                                studid: studid,
                                component_id: component_id,
                                subid: subid,
                                score: score_text,
                                term: term,
                                sort: sort
                            }
                            scores.push(score)
                        })

                    })

                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/systemgrades/savegrades',
                        data: {
                            highest_scores: highest_scores,
                            scores: scores,
                            term_averages: term_averages
                        },
                        success: function(response) {
                            Toast.fire({
                                type: 'success',
                                title: 'Grades successfully saved'
                            })
                            changes = 0
                        }
                    })
                }

            })

            $(document).on('click', '#submit_grades', function() {
                if (changes != 0) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Please save your changes before submitting'
                    })
                } else {
                    var scores = []
                    var students = []
                    $.each($('.checkbox:checked'), function() {
                        var studid = $(this).data('stud-id')
                        $.each($('.total_average[data-stud-id=' + studid + ']'), function() {
                            var term_average = $(this).text()
                            // console.log(term_average,'asd');

                            var student = {
                                studid: studid,
                                schedid: schedid,
                                term: term,
                                term_average: term_average
                            }

                            students.push(student)
                        })
                        $.each($('.scores[data-stud-id=' + studid + ']'), function() {
                            var sort = $(this).data('sort-id')
                            var component_id = $(this).data('comp-id')
                            var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(
                                this).data('sub-id') : 0;
                            var score_text = $(this).text()
                            var studid = $(this).data('stud-id')

                            var score = {
                                schedid: schedid,
                                studid: studid,
                                component_id: component_id,
                                subid: subid,
                                score: score_text,
                                term: term,
                                sort: sort,
                            }
                            scores.push(score)
                        })

                    })

                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/systemgrades/submit_grades',
                        data: {
                            grades: scores,
                            students: students
                        },
                        success: function(response) {
                            Toast.fire({
                                type: 'success',
                                title: 'Grades Submitted Succesfully'
                            })
                            display_term_grading()
                        }
                    })
                }
            })

            $(document).on('change', '.date_time', function() {
                if ($(this).text() == '') {
                    $(this).text('MM/DD/YYYY')
                }
            })
            $(document).on('input', '.date_time', function(e) {
                var val = $(this).text().replace(/\D/g, ''); 
                if (val.length > 8) val = val.slice(0, 8);
                
                var formatted = val.replace(/^(\d{2})(\d{2})(\d{0,4})$/, '$1/$2/$3');
                
                $(this).text(formatted);

                var range = document.createRange();
                var sel = window.getSelection();
                range.selectNodeContents(this);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            
            })

            $(document).on('change', '#male_checkbox', function(){

                if ($(this).is(':checked')) {
                    $('.male_checkbox').prop('checked', true)
                } else {
                    $('.male_checkbox').prop('checked', false)
                }
            })

            $(document).on('change', '#female_checkbox', function() {

                if ($(this).is(':checked')) {
                    $('.female_checkbox').prop('checked', true)
                } else {
                    $('.female_checkbox').prop('checked', false)
                }
            })

            $(document).on('change', '.female_checkbox', function() {
                $('.female_checkbox').each(function() {
                    var count = $('.female_checkbox:checked').length
                    if (count == 0) {
                        $('#female_checkbox').prop('checked', false)
                    }
                })
            })
            $(document).on('change', '.male_checkbox', function() {
                $('.male_checkbox').each(function() {
                    var count = $('.male_checkbox:checked').length
                    if (count == 0) {
                        $('#male_checkbox').prop('checked', false)
                    }
                })
            })

            $(document).on('focus', '.score, .highest_score, .date_time', function(){
                if(!term){
                    Toast.fire({
                        type: 'error',
                        title: 'Please select a term'
                    })
                }else{
                    var text = $(this).text()
                    if (!$('#gradeRibbon').length && !$(this).hasClass('bg-success')) {

                        $('.score, .highest_score').each(function(){
                        if($(this).text() == 0 || $(this).text() == ''){
                            $(this).text(0)
                        }
                        })

                        $('.date_time').each(function() {
                            if ($(this).text() == 'MM/DD/YYYY' || $(this).text() == '') {
                                $(this).text('MM/DD/YYYY')
                            }
                        })

                    if($(this).text() == 0 || $(this).text() == '' || $(this).text() == 'MM/DD/YYYY'){
                        $(this).text('')
                    }else{
                        $(this).text(text)
                    }
                       
                    }
                    
                }         
            })


            $(document).on('keydown', '.score, .highest_score, .date_time', function(e) {
                
                if ($(this).text() != 0 && (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                    changes = 1
                }
            })

            

            $(document).on('keydown', '.score', function(e) {
                var td = $(this).closest('td'); // Get current cell

                if (e.keyCode == 37) { // Left Arrow
                    e.preventDefault();

                    var prevTd = td.prevAll('.score').first(); // Get previous <td> with .score

                    if (prevTd.length) {
                        prevTd.focus(); // Focus directly since <td> is .score
                    } 
                } else if (e.keyCode == 39) { // Right Arrow
                    e.preventDefault();

                    var nextTd = td.nextAll('.score').first(); // Get next <td> with .score

                    if (nextTd.length) {
                        nextTd.focus();
                    } 
                } else if (e.keyCode == 38) { // Up Arrow
                    e.preventDefault();

                    var idx = td.index(); // Get column index
                    var prevRow = td.closest('tr').prev(); // Get previous row
                    var prevTd = prevRow.find('td').eq(idx); // Get the same column in previous row

                    if (prevTd.hasClass('score')) {
                        prevTd.focus();
                    }
                } else if (e.keyCode == 40) { // Down Arrow
                    e.preventDefault();

                    var idx = td.index();
                    var nextRow = td.closest('tr').next();
                    var nextTd = nextRow.find('td').eq(idx);

                    if (nextTd.hasClass('score')) {
                        nextTd.focus();
                    }
                }
            });

            $(document).on('keydown', '.highest_score', function(e) {
                var th = $(this).closest('th'); // Get current cell

                if (e.keyCode == 37) { // Left Arrow
                    e.preventDefault();

                    var prevTd = th.prevAll('.highest_score').first(); // Get previous <td> with .highest_score

                    if (prevTd.length) {
                        prevTd.focus(); // Focus directly since <td> is .highest_score
                    }else{
                        console.log('test');
                    }
                } else if (e.keyCode == 39) { // Right Arrow
                    e.preventDefault();

                    var nextTd = th.nextAll('.highest_score').first(); // Get next <td> with .highest_score

                    if (nextTd.length) {
                        nextTd.focus();
                    }
                } else if (e.keyCode == 38) { // Up Arrow
                    e.preventDefault();

                    var idx = th.index(); // Get column index
                    var prevRow = th.closest('tr').prev(); // Get previous row
                    var prevTd = prevRow.find('th').eq(idx); // Get the same column in previous row

                    if (prevTd.hasClass('highest_score')) {
                        prevTd.focus();
                    }
                } else if (e.keyCode == 40) { // Down Arrow
                    e.preventDefault();

                    var idx = th.index();
                    var nextRow = th.closest('tr').next();
                    var nextTd = nextRow.find('th').eq(idx);

                    if (nextTd.hasClass('highest_score')) {
                        nextTd.focus();
                    }
                }
            });

            $(document).on('keydown', '.date_time', function(e) {
                var th = $(this).closest('th'); // Get current cell

                if (e.keyCode == 37) { // Left Arrow
                    e.preventDefault();

                    var prevTd = th.prevAll('.date_time').first(); // Get previous <td> with .highest_score

                    if (prevTd.length) {
                        prevTd.focus(); // Focus directly since <td> is .highest_score
                    }else{
                        console.log('test');
                    }
                } else if (e.keyCode == 39) { // Right Arrow
                    e.preventDefault();

                    var nextTd = th.nextAll('.date_time').first(); // Get next <td> with .highest_score

                    if (nextTd.length) {
                        nextTd.focus();
                    }
                } else if (e.keyCode == 38) { // Up Arrow
                    e.preventDefault();

                    var idx = th.index(); // Get column index
                    var prevRow = th.closest('tr').prev(); // Get previous row
                    var prevTd = prevRow.find('th').eq(idx); // Get the same column in previous row

                    if (prevTd.hasClass('date_time')) {
                        prevTd.focus();
                    }
                } else if (e.keyCode == 40) { // Down Arrow
                    e.preventDefault();

                    var idx = th.index();
                    var nextRow = th.closest('tr').next();
                    var nextTd = nextRow.find('th').eq(idx);

                    if (nextTd.hasClass('date_time')) {
                        nextTd.focus();
                    }
                }
            });


            


        })
    </script>
@endsection
