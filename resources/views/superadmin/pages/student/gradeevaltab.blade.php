
@php
    $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
    $semester = DB::table('semester')->orderBy('id', 'asc')->get();
    $gradelevel1 = DB::table('gradelevel')->whereBetween('id', [17, 21])->orderBy('id', 'asc')->get();
    $gradelevel2 = DB::table('gradelevel')->whereBetween('id',[22, 25])->orderBy('id', 'asc')->get();
@endphp

    

<div class="d-flex justify-content-between align-items-center m-3">
    <h5>Student Grade Details</h5>
    <div>
        <button class="btn btn-success btn-sm" id="credSubjects">Credited
            Subjects</button>
        <button class="btn btn-primary btn-sm" id="gradeEval" data-toggle="modal" data-target="#grade_evaluation_modal">Grade Evaluation</button>
    </div>
</div>
<div class="appended_grade_eval"></div>
@foreach($sy as $year)
    @foreach($semester as $sem)
        <div class="card d-none div-{{$year->id}}-{{$sem->id}} hide_div_grade_eval" >
            <div class="card-body">
                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                    <div style="font-size: 14px; font-weight: bold">{{ $year->sydesc }} - {{ $sem->semester }}</div>
                    <table width="100%" class="table-bordered table-striped" id="table-gradeeval-{{$year->id}}-{{$sem->id}}" style="table-layout: fixed!important">
                        <thead>
                            <tr style="font-size: 12.5px">
                                <th width="10%" class="p-1">Subject Code</th>
                                <th width="45%" class="p-1">Subject</th>
                                <th width="7.5%" class="text-center p-1">Prelim</th>
                                <th width="7.5%" class="text-center p-1">Midterm</th>
                                <th width="7.5%" class="text-center p-1">Semi-Final</th>
                                <th width="7.5%" class="text-center p-1">Final</th>
                                <th width="7.5%" class="text-center p-1">General Average</th>
                                <th width="7.5%" class="text-center p-1">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="gradesTableBody" style="font-size: 11px;">
                            <!-- Table rows will be populated dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right">GPA</td>
                                <td class=""></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<script>
    const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });
    var schoolyear = @json($sy);
    var semester = @json($semester);
    var gradelevel1 = @json($gradelevel1);
    var gradelevel2 = @json($gradelevel2);
    
    $(document).on('click', '#gradeEval', function() {
        $('#student_name_eval').text(' ' + eval_name + ' ')
        $('#course_eval').text(' ' + eval_course + ' ')
        $('#yearlevel_eval').text(' ' + eval_gradelevel + ' ')
        $('#curriculum_eval').text(' ' + eval_curriculum + ' ')
        $('#section_eval').text(' ' + eval_section + ' ')
        append_table()
        get_grades_all()
        
    })


    function append_table(){
        if(levelid >= 22 && levelid <= 25){
            $('#grade_eval_table_container').append(`
                @foreach($gradelevel2 as $grade)
                    @foreach($semester as $sem)
                        <div class="card mt-2 div_modal-{{$grade->id}}-{{$sem->id}} eval_modal_table">
                            <div class="card-body">
                                <div>
                                    <div style="font-size: 12px; font-weight: bold">{{ $grade->levelname }} - {{ $sem->semester }}</div>
                                    <table width="100%" class="table table-sm table-bordered table-striped" id="table_modal-{{$grade->id}}-{{$sem->id}}">
                                        <thead>
                                            <tr style="font-size: 11px">
                                                <th width="10%">Code</th>
                                                <th width="25%">Subject Description</th>
                                                <th width="25%">Pre-Requisite</th>
                                                <th width="7%" class="text-center">Lecture</th>
                                                <th width="7%" class="text-center">Laboratory</th>
                                                <th width="7%" class="text-center">Credited Units</th>
                                                <th width="7%" class="text-center">GPA</th>
                                                <th width="12%" class="text-center">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            `)
        }else{
            $('#grade_eval_table_container').append(`
                @foreach($gradelevel1 as $grade)
                    @foreach($semester as $sem)
                        <div class="card mt-2 div_modal-{{$grade->id}}-{{$sem->id}} eval_modal_table">
                            <div class="card-body">
                                <div>
                                    <div style="font-size: 12px; font-weight: bold">{{ $grade->levelname }} - {{ $sem->semester }}</div>
                                    <table width="100%" class="table table-sm table-bordered table-striped" id="table_modal-{{$grade->id}}-{{$sem->id}}">
                                        <thead>
                                            <tr style="font-size: 11px">
                                                <th width="10%">Code</th>
                                                <th width="25%">Subject Description</th>
                                                <th width="25%">Pre-Requisite</th>
                                                <th width="7%" class="text-center">Lecture</th>
                                                <th width="7%" class="text-center">Laboratory</th>
                                                <th width="7%" class="text-center">Credited Units</th>
                                                <th width="7%" class="text-center">GPA</th>
                                                <th width="12%" class="text-center">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            `)
        }
    }

    function append_credited_table(){
        if(levelid >= 22 && levelid <= 25){
            $('#creditedSubjectListHolder').append(`
                @foreach($gradelevel2 as $grade)
                    @foreach($semester as $sem)
                        <div class="card mt-2 div_credit-{{$grade->id}}-{{$sem->id}} credit_modal_table">
                            <div class="card-body">
                                <div>
                                    <div class="d-flex flex-row justify-content-between mb-1">
                                        <div style="font-size: 12px; font-weight: bold">{{ $grade->levelname }} - {{ $sem->semester }}</div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-success text-sm button_credit" data-id="" data-level="{{$grade->id}}" data-semester="{{$sem->id}}">Credit Subject</button>
                                            <button type="button" class="btn btn-sm btn-primary text-sm button_update_credit d-none" data-id="" data-level="{{$grade->id}}" data-semester="{{$sem->id}}">Update</button>
                                        </div>
                                    </div>
                                    <table width="100%" class="table table-sm table-bordered table-striped" id="table_credit-{{$grade->id}}-{{$sem->id}}">
                                        <thead>
                                            <tr style="font-size: 11px">
                                                <th width="10%">Code</th>
                                                <th width="25%">Subject Description</th>
                                                <th width="25%">Pre-Requisite</th>
                                                <th width="7%" class="text-center">Lecture</th>
                                                <th width="7%" class="text-center">Laboratory</th>
                                                <th width="7%" class="text-center">Credited Units</th>
                                                <th width="7%" class="text-center">GPA</th>
                                                <th width="12%" class="text-center">Credit Subject</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            `)
        }else{
            $('#creditedSubjectListHolder').append(`
                @foreach($gradelevel1 as $grade)
                    @foreach($semester as $sem)
                        <div class="card mt-2 div_credit-{{$grade->id}}-{{$sem->id}} credit_modal_table">
                            <div class="card-body">
                                <div>
                                    <div class="d-flex flex-row justify-content-between mb-1">
                                        <div style="font-size: 12px; font-weight: bold">{{ $grade->levelname }} - {{ $sem->semester }}</div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-success text-sm button_credit" data-id="" data-level="{{$grade->id}}" data-semester="{{$sem->id}}">Credit Subject</button>
                                            <button type="button" class="btn btn-sm btn-primary text-sm button_update_credit d-none" data-id="" data-level="{{$grade->id}}" data-semester="{{$sem->id}}">Update</button>
                                        </div>
                                    </div>
                                    <table width="100%" class="table table-sm table-bordered table-striped" id="table_credit-{{$grade->id}}-{{$sem->id}}">
                                        <thead>
                                            <tr style="font-size: 11px">
                                                <th width="10%">Code</th>
                                                <th width="25%">Subject Description</th>
                                                <th width="25%">Pre-Requisite</th>
                                                <th width="7%" class="text-center">Lecture</th>
                                                <th width="7%" class="text-center">Laboratory</th>
                                                <th width="7%" class="text-center">Credited Units</th>
                                                <th width="7%" class="text-center">GPA</th>
                                                <th width="12%" class="text-center">Credit Subject</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            `)
        }
    }


    

    function get_grades_all(){
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/get/prospectus',
            data: {
                studid: studID,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val(),
                curr: curr_id
            },
            success: function(data) {
                student_grade_all(data)
                
            }
        })
    }

    function get_grades_all_credit(){
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/get/prospectus',
            data: {
                studid: studID,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val(),
                curr: curr_id
            },
            success: function(data) {
                student_grade_all_credit(data)
                
            }
        })
    }
    function get_student_grades(studentId){
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/get',
            data: {
                studid: studentId,
            },
            success: function(data) {
                student_grade_details(data)

            }
        })
    }

    function student_grade_all(grades_eval){
            if(levelid >= 22 && levelid <= 25){
                var gradelevel = gradelevel2
            }else if(levelid >= 17 && levelid <= 21){
                var gradelevel = gradelevel1
            }
            $.each(gradelevel, function(index, level) {
                $.each(semester, function(index, sem) {
                    let new_grades = grades_eval.filter(grade => grade.yearID == level.id && grade.semesterID == sem.id)
                    $('#table_modal-' + level.id + '-' + sem.id).DataTable({
                        destroy: true,
                        order: false,
                        data: new_grades,
                        lengthChange: false,
                        info: false,
                        paging: false,
                        searching: false,
                        columns: [
                            {data: 'subjCode'},
                            {data: 'subjDesc'},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                        ],
                        columnDefs: [
                            {
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="p-1 mb-0">${rowData.subjCode}</p>`)
                                }
                            },
                            {
                                targets: 2,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="p-1 mb-0">${rowData.prereq.length > 0 ? rowData.prereq[0].subjDesc : ''}</p>`)
                                    .addClass('text-danger')
                                }
                            },
                            {
                                targets: 3,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="p-1 mb-0">${rowData.lecunits ? rowData.lecunits : ''}</p>`)
                                    .addClass('text-center')
                                }
                            },
                            {
                                targets: 4,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="p-1 mb-0">${rowData.labunits ? rowData.labunits : ''}</p>`)
                                    .addClass('text-center')
                                }
                            },
                            {
                                targets: 5,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="p-1 mb-0">${rowData.credunits ? rowData.credunits : ''}</p>`)
                                    .addClass('text-center')
                                }
                            },
                            {
                                targets: 6,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    if (rowData.prelemstatus != 2 && rowData.prelemstatus != 4 && rowData.midtermstatus != 2 && rowData.midtermstatus != 4 && rowData.semifinalstatus != 2 && rowData.semifinalstatus != 4 && rowData.finalstatus != 2 && rowData.finalstatus != 4) {
                                        $(td).html('')
                                    } else {
                                        $(td).html(`<p  class="p-1 mb-0">${rowData.fg ? rowData.fg : ''}</p>`)
                                        .addClass('text-center')
                                    }
                                }
                            },
                            {
                                targets: 7,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="p-1 mb-0">${rowData.remarks ? rowData.remarks : ''}</p>`)
                                    .addClass('text-center')
                                }
                            },
                        ]
                    })
                })
            })
        }

        function student_grade_all_credit(grades_eval){
            if(levelid >= 22 && levelid <= 25){
                var gradelevel = gradelevel2
            }else if(levelid >= 17 && levelid <= 21){
                var gradelevel = gradelevel1
            }
            $.each(gradelevel, function(index, level) {
                $.each(semester, function(index, sem) {
                    let new_grades = grades_eval.filter(grade => grade.yearID == level.id && grade.semesterID == sem.id)
                    $('#table_credit-' + level.id + '-' + sem.id).DataTable({
                        destroy: true,
                        order: false,
                        data: new_grades,
                        lengthChange: false,
                        info: false,
                        paging: false,
                        searching: false,
                        columns: [
                            {data: 'subjCode'},
                            {data: 'subjDesc'},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                        ],
                        columnDefs: [
                            {
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="">${rowData.subjCode}</p>`)
                                    .addClass('align-middle')
                                }
                            },
                            {
                                targets: 2,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="">${rowData.prereq.length > 0 ? rowData.prereq[0].subjDesc : ''}</p>`)
                                    .addClass('text-danger align-middle')
                                }
                            },
                            {
                                targets: 3,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="">${rowData.lecunits ? rowData.lecunits : ''}</p>`)
                                    .addClass('text-center align-middle')
                                }
                            },
                            {
                                targets: 4,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="">${rowData.labunits ? rowData.labunits : ''}</p>`)
                                    .addClass('text-center align-middle')
                                }
                            },
                            {
                                targets: 5,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="">${rowData.credunits ? rowData.credunits : ''}</p>`)
                                    .addClass('text-center align-middle')
                                }
                            },
                            {
                                targets: 6,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html('')
                                    .addClass('text-center align-middle gpa_credit')
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 7,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class=""><input type="checkbox" class="check_button_credit" data-level="${level.id}" data-semester="${sem.id}" value="${rowData.id}"></p>`)
                                    .addClass('text-center align-middle')
                                }
                            },
                        ]
                    })
                })
            })
        }

    $(document).on('change', '.check_button_credit', function() {
        var val = $(this).val()
        if ($(this).is(':checked')) {
            $('.gpa_credit[data-id="' + val + '"]').attr('contenteditable', true).addClass('bg-info');
            
        } else {
            $('.gpa_credit[data-id="' + val + '"]').removeAttr('contenteditable').removeClass('bg-info');
        }
    });

    function student_grade_details(grades){
        $.each(schoolyear, function(index, sy) {
            $.each(semester, function(index, sem) {
                // $('.div-' + sy.id + '-' + sem.id).removeClass('d-none')
                $.each(grades, function(index, grade) {
                    $('.div-' + grade.yearID + '-' + grade.semID).removeClass('d-none')
                })
                
                let new_grades = grades.filter(grade => grade.yearID == sy.id && grade.semID == sem.id)

                
                $('#table-gradeeval-' + sy.id + '-' + sem.id).DataTable({
                    destroy: true,
                    order: false,
                    data: new_grades,
                    lengthChange: false,
                    info: false,
                    paging: false,
                    searching: false,
                    columns: [
                        {data: 'subjCode'},
                        {data: 'subjDesc'},
                        {data: 'prelemgrade'},
                        {data: 'midtermgrade'},
                        {data: 'semifinal'},
                        {data: 'finalgrade'},
                        {data: 'fg'},
                        {data: 'remarks'},
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<p  class="mb-0 ">${rowData.subjCode}</p>`)
                                    .attr('style', 'width: 10%!important')
                                    .addClass('py-2 p-1 align-middle')

                            }
                        },

                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<p  class="mb-0 ">${rowData.subjDesc}</p>`)
                                    .attr('style', 'width: 45%!important')
                                    .addClass('py-2 p-1 align-middle')

                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (rowData.prelemstatus == 2 || rowData.prelemstatus == 4) {
                                    $(td).html(`<p  class="mb-0">${rowData.prelemgrade ? rowData.prelemgrade : ''}</p>`)
                                    .attr('style', 'width: 7.5%!important')
                                    .addClass('text-center align-middle')
                                    .addClass('py-2 p-1')

                                } else {
                                    $(td).html('')
                                }
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (rowData.midtermstatus == 2 || rowData.midtermstatus == 4) {
                                    $(td).html(`<p  class="mb-0">${rowData.midtermgrade ? rowData.midtermgrade : ''}</p>`)
                                    .attr('style', 'width: 7.5%!important')
                                    .addClass('text-center align-middle')
                                    .addClass('py-2 p-1 ')

                                } else {
                                    $(td).html('')
                                }
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (rowData.prefistatus == 2 || rowData.prefistatus == 4) {
                                    $(td).html(`<p  class="mb-0">${rowData.prefigrade ? rowData.prefigrade : ''}</p>`)
                                    .attr('style', 'width: 7.5%!important')
                                    .addClass('text-center align-middle')
                                    .addClass('py-2 p-1')

                                } else {
                                    $(td).html('')
                                }
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (rowData.finalstatus == 2 || rowData.finalstatus == 4) {
                                    $(td).html(`<p  class="mb-0">${rowData.finalgrade ? rowData.finalgrade : ''}</p>`)
                                    .attr('style', 'width: 7.5%!important')
                                    .addClass('text-center align-middle')
                                    .addClass('py-2 p-1')

                                } else {
                                    $(td).html('')
                                }
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (rowData.midtermstatus != 2 && rowData.midtermstatus != 4 && rowData.semifinalstatus != 2 && rowData.semifinalstatus != 4 && rowData.finalstatus != 2 && rowData.finalstatus != 4) {
                                    $(td).html('')
                                } else {
                                    $(td).html(`<p  class="mb-0">${rowData.fg ? rowData.fg : ''}</p>`)
                                    .addClass('text-center align-middle')
                                    .addClass('py-2 p-1')

                                }
                            }
                        },
                        {
                            targets: 7,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (rowData.prelemstatus != 2 && rowData.prelemstatus != 4 && rowData.midtermstatus != 2 && rowData.midtermstatus != 4 && rowData.semifinalstatus != 2 && rowData.semifinalstatus != 4 && rowData.finalstatus != 2 && rowData.finalstatus != 4) {
                                    $(td).html('')
                                } else {
                                    $(td).html(`<p  class="mb-0">${rowData.remarks ? rowData.remarks : ''}</p>`)
                                    .addClass('text-center align-middle')
                                    .addClass('py-2 p-1')

                                }
                            }
                        },
                    ]
                })
            })
        })
    }

    $(document).on('click', '.button_credit', function(){
        var level = $(this).data('level');
        var sem = $(this).data('semester');
        var schoolname = $('#school_name_credit').val();
        var schooladdress = $('#school_address_credit').val();
        var schoolyear = $('#school_year_credit').val();
        var credsubj = [];
        var checked;
        var id = $(this).data('id');
        
        
        $('#table_credit-' + level + '-' + sem + ' tbody tr').each(function(index, tr) {
            if ($(this).find('.check_button_credit').is(':checked')) {
                checked = {
                    prospectusid: $(this).find('.check_button_credit').val(),
                    gpa: $(this).find('.gpa_credit').text()
                }
                credsubj.push(checked)
                checked = []
            }
        });
        if(credsubj.length == 0){
            Toast.fire({
                type: 'warning',
                title: 'No subjects to credit!'
            })
            return
        }else{
            $.ajax({
                type: 'GET',
                url: '/superadmin/student/grade/evaluation/add/credit',
                data: {
                    level: level,
                    sem: sem,
                    studid: studID,
                    credsubj: credsubj,
                    schoolname: schoolname,
                    schooladdress: schooladdress,
                    schoolyear: schoolyear,
                    headerid: id
                    
                }, success: function(data) {
                    get_schoolcred_subjcred()
                    $('.button_credit').attr('data-id', data);
                    Toast.fire({
                        type: 'success',
                        title: 'Subjects Credited!'
                    })
                }
            })
        }
        
    })

    $(document).on('click', '#print_grade_eval', function() {
        var syid = $('#filter_sy').val();
        var semid = $('#filter_semester').val();
        
        var win = window.open('/superadmin/student/grade/evaluation/get/prospectus/print?syid=' + syid + '&semid=' + semid + '&curr=' + curr_id + '&studid=' + studID);
        win.focus();
        setTimeout(function() {
            win.print();
        }, 1000);

        // $.ajax({
        //     type: 'GET',
        //     url: '/superadmin/student/grade/evaluation/get/prospectus/print?syid=' + syid + '&semid=' + semid + '&curr=' + curr_id + '&studid=' + studID,
        //     data: {
        //         studid: studID,
        //         syid: $('#filter_sy').val(),
        //         semid: $('#filter_semester').val(),
        //         curr: curr_id
        //     },
        //     success: function(data) {
                
        //     }
        // })
    })

    $(document).on('click', '#credSubjects', function() {
        get_schoolcred_subjcred()
    })

    function get_schoolcred_subjcred(){
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/get/credit',
            data: {
                studid: studID
            }, success: function(data) {
                $('.school_credit_modal_table').remove()
                append_credited_schools(data);
                insert_data_to_credited_table(data)
            }
            
        })
    }

    function append_credited_schools(data){ 
        if(data.length == 0){
            $('.no_credit').append(`
                 <p class="mb-0 text-sm font-weight-bold">NO CREDITED UNITS RECORDED</p>
                <span  class="text-sm">(<i class="text-info">Click the 'Add Credited Subject' button to credit subject/s</i>)</span>
            `)
        }else{
            $('.no_credit').empty()
            $.each(data, function(key, school) {
                $('#credited_table').append(`
                    <div style="font-size: 15px;" class=" ml-1 font-weight-bold school_credit_modal_table">${school.schoolname}
                        <button class="btn btn-sm edit_school_credit" data-id="${school.headerid}"><i class="fas fa-edit text-sm text-info ml-2"></i></button>
                        <button class="btn btn-sm delete_school_credit" data-id="${school.headerid}"><i class="fas fa-trash text-sm text-danger ml-2"></i></button>
                    </div>
                `)
                $('#credited_table').append(`
                        @foreach($semester as $sem)
                        <div class="card mt-2 d-none school_div_credit-${school.headerid}-{{$sem->id}} school_credit_modal_table">
                            <div class="card-body">
                                <div>
                                    <div class="d-flex flex-row justify-content-between mb-1">
                                        <div style="font-size: 12px; font-weight: bold">${school.sydesc}-{{ $sem->semester }}</div>
                                    </div>
                                    <table width="100%" class="table table-sm table-bordered table-striped" id="school_credit-${school.headerid}-{{$sem->id}}">
                                        <thead>
                                            <tr style="font-size: 11px">
                                                <th width="7%">Code</th>
                                                <th width="25%">Subject Description</th>
                                                <th width="25%">Pre-Requisite</th>
                                                <th width="7%" class="text-center">Lecture</th>
                                                <th width="7%" class="text-center">Laboratory</th>
                                                <th width="7%" class="text-center">Credited Units</th>
                                                <th width="7%" class="text-center">GPA</th>
                                                <th width="7%" class="text-center">Credited</th>
                                                <th width="8%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td contenteditable="true" class="credit_subjCode bg-info" data-school="${school.headerid}" data-sem="{{ $sem->id }}"></td>
                                                <td contenteditable="true" class="credit_subjDesc bg-info" data-school="${school.headerid}" data-sem="{{ $sem->id }}"></td>
                                                <td class="credit_subjPrereq"></td>
                                                <td contenteditable="true" class="credit_subjLec bg-info text-center" data-school="${school.headerid}" data-sem="{{ $sem->id }}"></td>
                                                <td contenteditable="true" class="credit_subjLab bg-info text-center" data-school="${school.headerid}" data-sem="{{ $sem->id }}"></td>
                                                <td contenteditable="true" class="credit_subjCred bg-info text-center" data-school="${school.headerid}" data-sem="{{ $sem->id }}"></td>
                                                <td contenteditable="true" class="credit_subjGPA text-center bg-info" data-school="${school.headerid}" data-sem="{{ $sem->id }}"></td>
                                                <td class=""></td>
                                                <td class="text-center"><button class="btn btn-sm btn-success credit_to_prospectus_subject" data-school="${school.headerid}" data-sem="{{ $sem->id }}" data-id="${school.credsubj[0].levelID}" ><i class="fa fa-save"></i></button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="d-flex flex-row mb-1">
                                        <div style="font-size: 12px;color: red"><i>Please Input Non-Credited Subjects To be Displayed on TOR</i></div>
                                        <div class="d-flex flex-row text-success ml-auto align-middle"><i class="fa fa-check text-lg  ml-auto"></i> - Credited</div>
                                        <div class="d-flex flex-row text-danger ml-2 align-middle"><i class="fa fa-times text-lg  ml-auto"></i> - Not-Credited</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                `)
            })
        }
        
    }

    function insert_data_to_credited_table(data){
        $.each(data, function(key, school) {
            $.each(semester, function(key, sem) {
                $.each(school.credsubj, function(key, cred) {
                    $('.school_div_credit-' + school.headerid + '-' + cred.semID).removeClass('d-none')
                })
                let credited_subj = school.credsubj.filter(cred => cred.schoolID == school.headerid && cred.semID == sem.id)
                $('#school_credit-' + school.headerid + '-' + sem.id).DataTable({
                    destroy: true,
                    order: false,
                    data: credited_subj,
                    lengthChange: false,
                    info: false,
                    paging: false,
                    searching: false,
                    columns: [
                        {data: 'subjCode'},
                        {data: 'subjDesc'},
                        {data: null},
                        {data: null},
                        {data: null},
                        {data: null},
                        {data: null},
                        {data: null},
                        {data: null},
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                $(td).html(`${rowData.subjCode}`)
                                .addClass('mb-0 align-middle credited_subjCode')
                                .attr('data-school', rowData.schoolID)
                                .attr('data-sem', rowData.semID)
                                .attr('data-id', rowData.id)
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                $(td).html(`${rowData.subjDesc}`)
                                .addClass('mb-0 align-middle credited_subjDesc')
                                .attr('data-school', rowData.schoolID)
                                .attr('data-sem', rowData.semID)
                                .attr('data-id', rowData.id)
                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                $(td).html(`<p class="mb-0">${credpreq}</p>`)
                                .addClass('mb-0 ')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<p class="mb-0">${rowData.lecunits ? rowData.lecunits : ''}</p>`)
                                .addClass('text-center  align-middle credited_lecunits')
                                .attr('data-school', rowData.schoolID)
                                .attr('data-sem', rowData.semID)
                                .attr('data-id', rowData.id)
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<p  class="mb-0">${rowData.labunits ? rowData.labunits : ''}</p>`)
                                .addClass('text-center m-0 mb-0  align-middle credited_labunits')
                                .attr('data-school', rowData.schoolID)
                                .attr('data-sem', rowData.semID)
                                .attr('data-id', rowData.id)
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<p  class="mb-0">${rowData.credunits ? rowData.credunits : ''}</p>`)
                                .addClass('text-center p-0 mb-0  align-middle credited_credunits')
                                .attr('data-school', rowData.schoolID)
                                .attr('data-sem', rowData.semID)
                                .attr('data-id', rowData.id)
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<p  class="mb-0">${rowData.gpa != null ? rowData.gpa : ''}</p>`)
                                .addClass('text-center p-0 mb-0  align-middle credited_gpa')
                                .attr('data-school', rowData.schoolID)
                                .attr('data-sem', rowData.semID)
                                .attr('data-id', rowData.id)
                            }
                        },
                        {
                            targets: 7,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if(rowData.status == 0){
                                    $(td).html(`<p  class="mb-0"><i class="fa fa-check text-success"></i></p>`)
                                }else{
                                    $(td).html(`<p  class="mb-0"><i class="fa fa-times text-danger"></i></p>`)
                                }
                                $(td).addClass('text-center p-0 mb-0  align-middle')
                            }
                        },
                        {
                            targets: 8,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if(rowData.status == 1){
                                    $(td).html(`<p  class="mb-0">
                                        <button class="btn btn-sm save_edit_additional_credit d-none" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-save text-success"></i></button>
                                        <button class="btn btn-sm close_edit_additional_credit d-none" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-window-close text-danger"></i></button>
                                        <button class="btn btn-sm edit_additional_credit" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-edit text-primary"></i></button>
                                        <button class="btn btn-sm delete_additional_credit" data-id="${rowData.id}"><i class="fa fa-trash text-danger"></i></button>
                                        </p>`)
                                    .addClass('text-center mb-0  align-middle ')

                                }else{
                                    $(td).html(`<p  class="mb-0"></p>`)
                                    .addClass('text-center mb-0  align-middle ')

                                }
                            }
                        },
                    ]
                })
            })
        })
    }
    var update;
    var new_cred_data;
    $(document).on('click', '.credit_to_prospectus_subject', function(event) {
        var headerid = $(this).data('school');
        var semid = $(this).data('sem');
        var subjCode = $(this).closest('tr').find('.credit_subjCode').text();
        var subjDesc = $(this).closest('tr').find('.credit_subjDesc').text();
        var subjPrereq = $(this).closest('tr').find('.credit_subjPrereq').text();
        var lecunits = $(this).closest('tr').find('.credit_subjLec').text();
        var labunits = $(this).closest('tr').find('.credit_subjLab').text();
        var credunits = $(this).closest('tr').find('.credit_subjCred').text();
        var gpa = $(this).closest('tr').find('.credit_subjGPA').text();
        new_cred_data = {
            headerid: headerid,
            semid: semid,
            subjCode: subjCode,
            subjDesc: subjDesc,
            subjPrereq: subjPrereq,
            lecunits: lecunits,
            labunits: labunits,
            credunits: credunits,
            gpa: gpa,
            studid: studID,
            levelid: $(this).data('id')
        }
        update = 0;
        show_credit_prospectus(new_cred_data)
    })
    function show_credit_prospectus(cred_data) {
        console.log(cred_data)

        if(!cred_data.subjCode){
            $('.credit_subjCode[data-school="'+cred_data.headerid+'"][data-sem="'+cred_data.semid+'"]')
                .addClass('bg-info')
                .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
        }else{
            $('.save_added_cred_subj').closest('tr').find('.credit_subjCode').addClass('bg-info')
        }
        if(!cred_data.subjDesc){
            $('.credit_subjDesc[data-school="'+cred_data.headerid+'"][data-sem="'+cred_data.semid+'"]')
                .addClass('bg-info')
                .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
        }else{
            $('.save_added_cred_subj').closest('tr').find('.credit_subjDesc').addClass('bg-info')
        }
        if(!cred_data.lecunits){
            $('.credit_subjLec[data-school="'+cred_data.headerid+'"][data-sem="'+cred_data.semid+'"]')
                .addClass('bg-info')
                .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
        }else{
            $('.save_added_cred_subj').closest('tr').find('.credit_subjLec').addClass('bg-info')
        }
        if(!cred_data.labunits){
            $('.credit_subjLab[data-school="'+cred_data.headerid+'"][data-sem="'+cred_data.semid+'"]')
                .addClass('bg-info')
                .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
        }else{
            $('.save_added_cred_subj').closest('tr').find('.credit_subjLab').addClass('bg-info')
        }
        if(!cred_data.credunits){
            $('.credit_subjCred[data-school="'+cred_data.headerid+'"][data-sem="'+cred_data.semid+'"]')
                .addClass('bg-info')
                .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
        }else{
            $('.save_added_cred_subj').closest('tr').find('.credit_subjCred').addClass('bg-info')
        }
        if(!cred_data.gpa){
            $('.credit_subjGPA[data-school="'+cred_data.headerid+'"][data-sem="'+cred_data.semid+'"]')
                .addClass('bg-info')
                .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
        }else{
            $('.save_added_cred_subj').closest('tr').find('.credit_subjGPA').addClass('bg-info')
        }
        if(!cred_data.subjCode || !cred_data.subjDesc || !cred_data.gpa || !cred_data.lecunits || !cred_data.labunits || !cred_data.credunits){
            Toast.fire({
                type: 'warning',
                title: 'Please fill in required fields!'
            })
        }
        if(cred_data.subjCode && cred_data.subjDesc && cred_data.gpa && cred_data.lecunits && cred_data.labunits && cred_data.credunits){

            // $('#credit_prospectus').modal('show')
            // $.ajax({
            //     type: 'GET',
            //     url: '/superadmin/student/grade/evaluation/get/prospectus',
            //     data: {
            //         studid: studID,
            //         syid: $('#filter_sy').val(),
            //         semid: $('#filter_semester').val(),
            //         curr: curr_id
            //     },
            //     success: function(data) {
            //         $('#credit_prospectus_subject').select2()
            //         $('#credit_prospectus_subject').empty()
            //         $('#credit_prospectus_subject').append(`<option value="">Select Subject</option>`);
            //         $.each(data, function(key, subj) {
            //             $('#credit_prospectus_subject').append(`<option value="${subj.id}" data-levelid="${subj.yearID}">${subj.subjCode} - ${subj.subjDesc}</option>`)
            //         })
                    
            //     }
            // })

            if(update == 1){
                // edit_cred_data.prospectusID = prospectus_id;
                update_additional_credit()
            }else{
                cred_data.prospectusID = 0;
                save_additional_credit()
            }
            
        }
      
}

    // $(document).on('click', '.credit_to_prospectus_subject', function(event) {
    //     var prospectus_id = $('#credit_prospectus_subject').val();
    //     if(!prospectus_id){
    //         Toast.fire({
    //             type: 'warning',
    //             title: 'Please select a subject!'
    //         })
    //     }else{
            
            
            
    //     }
        
    // })

    function save_additional_credit(){
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/add/additional_credit',
            data: new_cred_data,
            success: function(data) {
                $('#credit_prospectus').modal('hide')
                get_schoolcred_subjcred()
                Toast.fire({
                    type: 'success',
                    title: 'Successfully added!'
                })
            }
        })
    }


    $(document).on('click', '.delete_additional_credit', function(event) {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/grade/evaluation/delete/additional_credit',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        get_schoolcred_subjcred()
                        Swal.fire(
                            'Deleted!',
                            'Your credited subject has been deleted.',
                            'success'
                        )
                    }
                })
            }
        })
    })

    $(document).on('click', '.edit_additional_credit', function(event) {
        var id = $(this).data('id');
        var headerid = $(this).data('school');
        var semid = $(this).data('sem');
        $('.credited_subjCode[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').attr('contenteditable', 'true')
            .addClass('bg-info')
            .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        $('.credited_subjDesc[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').attr('contenteditable', 'true')
            .addClass('bg-info')
            .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        $('.credited_gpa[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').attr('contenteditable', 'true')
            .addClass('bg-info')
            .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        $('.credited_lecunits[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').attr('contenteditable', 'true')
            .addClass('bg-info')
            .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        $('.credited_labunits[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').attr('contenteditable', 'true')
            .addClass('bg-info')
            .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        $('.credited_credunits[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').attr('contenteditable', 'true')
            .addClass('bg-info')
            .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
        $('.edit_additional_credit[data-id="'+id+'"]').addClass('d-none');
        $('.delete_additional_credit[data-id="'+id+'"]').addClass('d-none');
        $('.save_edit_additional_credit[data-id="'+id+'"]').removeClass('d-none');
        $('.close_edit_additional_credit[data-id="'+id+'"]').removeClass('d-none');
    })

    var edit_cred_data;
    var edit_id;
    var edit_headerid
    var edit_semid
    $(document).on('click', '.save_edit_additional_credit', function(event) {
        edit_id = $(this).data('id');
        edit_headerid = $(this).data('school');
        edit_semid = $(this).data('sem');
        edit_cred_data = {
            id: edit_id,
            headerid: edit_headerid,
            semid: edit_semid,
            subjCode: $('.credited_subjCode[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').text(),
            subjDesc: $('.credited_subjDesc[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').text(),
            lecunits: $('.credited_lecunits[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').text(),
            labunits: $('.credited_labunits[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').text(),
            credunits: $('.credited_credunits[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').text(),
            gpa: $('.credited_gpa[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').text(),
        }
        update = 1;
        update_additional_credit()
        
    })

    function update_additional_credit() {
        console.log(edit_cred_data);
        
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/update/additional_credit',
            data: edit_cred_data,
            success: function(data) {
                $('#credit_prospectus').modal('hide')
                Toast.fire({
                    type: 'success',
                    title: 'Changes have been saved successfully!'
                });
                $('.credited_subjCode[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').removeAttr('contenteditable', 'true')
                    .removeClass('bg-info')
                $('.credited_subjDesc[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').removeAttr('contenteditable', 'true')
                    .removeClass('bg-info')
                $('.credited_gpa[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').removeAttr('contenteditable', 'true')
                    .removeClass('bg-info')
                $('.credited_lecunits[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').removeAttr('contenteditable', 'true')
                .removeClass('bg-info')
                $('.credited_labunits[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').removeAttr('contenteditable', 'true')
                .removeClass('bg-info')
                $('.credited_credunits[data-id="'+edit_id+'"][data-school="'+edit_headerid+'"][data-sem="'+edit_semid+'"]').removeAttr('contenteditable', 'true')
                .removeClass('bg-info')
                $('.edit_additional_credit[data-id="'+edit_id+'"]').removeClass('d-none');
                $('.delete_additional_credit[data-id="'+edit_id+'"]').removeClass('d-none');
                $('.save_edit_additional_credit[data-id="'+edit_id+'"]').addClass('d-none');
                $('.close_edit_additional_credit[data-id="'+edit_id+'"]').addClass('d-none');
            }
        })
    }

    $(document).on('click', '.close_edit_additional_credit', function(event) {
        var id = $(this).data('id');
        var headerid = $(this).data('school');
        var semid = $(this).data('sem');
        $('.credited_subjCode[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').removeAttr('contenteditable', 'true')
            .removeClass('bg-info')
        $('.credited_subjDesc[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').removeAttr('contenteditable', 'true')
            .removeClass('bg-info')
        $('.credited_gpa[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').removeAttr('contenteditable', 'true')
            .removeClass('bg-info')
        $('.credited_lecunits[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').removeAttr('contenteditable', 'true')
        .removeClass('bg-info')
        $('.credited_labunits[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').removeAttr('contenteditable', 'true')
        .removeClass('bg-info')
        $('.credited_credunits[data-id="'+id+'"][data-school="'+headerid+'"][data-sem="'+semid+'"]').removeAttr('contenteditable', 'true')
        .removeClass('bg-info')
        $('.edit_additional_credit[data-id="'+id+'"]').removeClass('d-none');
        $('.delete_additional_credit[data-id="'+id+'"]').removeClass('d-none');
        $('.save_edit_additional_credit[data-id="'+id+'"]').addClass('d-none');
        $('.close_edit_additional_credit[data-id="'+id+'"]').addClass('d-none');
    })

    $(document).on('click', '.edit_school_credit', function(event) {
        var id = $(this).data('id');
        $('#creditedSubjectList').modal('show')
        append_credited_table();
        get_grades_all_credit();
        $('.button_credit').addClass('d-none')
        $('.button_update_credit').removeClass('d-none')
        $('.button_update_credit').attr('data-id', id)
        $('#updateCreditedSchool').removeClass('d-none')
        $('#updateCreditedSchool').attr('data-id', id)
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/get/specific_credit',
            data: {
                id: id
            }, success: function(data) {
                $('#school_name_credit').val(data[0].schoolname)
                $('#school_year_credit').val(data[0].sydesc)
                $('#school_address_credit').val(data[0].schooladdress)
                setTimeout(function(){
                    $.each(data[0].credsubj, function(key, cred) {
                        $('.check_button_credit[value="'+cred.prospectusID+'"]').prop('checked', true).trigger('change')
                        $('.gpa_credit[data-id="'+cred.prospectusID+'"]').text(cred.gpa)
                    })
                }, 100)
            }
            
        })  
    })
    
    $(document).on('click', '#updateCreditedSchool', function(event) {
        var id = $(this).data('id');
        var schoolname = $('#school_name_credit').val();
        var schoolyear = $('#school_year_credit').val();
        var schooladdress = $('#school_address_credit').val();
        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/update/school_credit',
            data: {
                id: id,
                schoolname: schoolname,
                schoolyear: schoolyear,
                schooladdress: schooladdress
            },
            success: function(data) {
                Toast.fire({
                    type: 'success',
                    title: 'Changes have been saved successfully!'
                })
            },
        })
    })

    $(document).on('click', '.button_update_credit', function(){
        var levelid = $(this).data('level');
        var sem = $(this).data('semester');
        var id = $(this).data('id');
        var credsubj = [];
        var checked;

        $.each($('#table_credit-' + levelid + '-' + sem + ' tbody tr'), function(index, tr) {
            if ($(this).find('.check_button_credit').is(':checked')) {
                checked = {
                    prospectusid: $(this).find('.check_button_credit').val(),
                    gpa: $(this).find('.gpa_credit').text()
                }
                credsubj.push(checked)
                checked = []
            }
        })

        $.ajax({
            type: 'GET',
            url: '/superadmin/student/grade/evaluation/update/credit',
            data: {
                id: id,
                credsubj: credsubj,
                levelid: levelid,
                semid: sem,
                studid: studID
            },
            success: function(data) {
                Toast.fire({
                    type: 'success',
                    title: 'Changes have been saved successfully!'
                })
                get_schoolcred_subjcred()
            },  
        })
        
    })

    $(document).on('click', '.delete_school_credit', function(event) {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/grade/evaluation/delete/school_credit',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Credited Subjects has been deleted successfully!'
                        })
                        get_schoolcred_subjcred()
                    },
                })
            }
        })
        
        
    })

    $('#studentLoadingModal').on('hidden.bs.modal', function(event) {
        $('.hide_div_grade_eval').addClass('d-none');
    })

    $('#credSubjects').on('click', function(event) {
        $('#creditedSubjects').modal('show')
    })


    
    $(document).on('hidden.bs.modal', '#creditedSubjects', function() {
        $('.no_credit').empty()
    })

    $(document).on('click', '#addCreditedSubject', function(event) {
        $('#creditedSubjectList').modal('show')
        append_credited_table();
        get_grades_all_credit();
    })

    $(document).on('hidden.bs.modal', '#creditedSubjectList', function() {
        $('.credit_modal_table').remove()
        $('.button_credit').attr('data-id', '');
        $('#updateCreditedSchool').addClass('d-none')
        $('#school_name_credit').val('')
        $('#school_year_credit').val('')
        $('#school_address_credit').val('')
    })
    

    $(document).on('hidden.bs.modal', '#creditedSubjects', function() {
        $('.school_credit_modal_table').remove()
    })


</script>