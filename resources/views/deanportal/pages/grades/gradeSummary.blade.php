@php
    $refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->where('deleted', 0)
        ->select('refid')
        ->first();
    $teacherid = DB::table('teacher')
        ->where('userid', auth()->user()->id)
        ->select('id')
        ->first()->id;

    // if (Session::get('currentPortal') == 3) {
    //     $xtend = 'registrar.layouts.app';
    //     $acadprogid = DB::table('teacheracadprog')
    //         ->where('teacherid', $teacherid)
    //         ->where('acadprogutype', 3)
    //         ->select('acadprogid', 'syid')
    //         ->where('deleted', 0)
    //         ->orderBy('acadprogid')
    //         ->get();
    // } elseif (Session::get('currentPortal') == 2) {
    //     $acadprogid = DB::table('teacheracadprog')
    //         ->where('teacherid', $teacherid)
    //         ->where('acadprogutype', 2)
    //         ->select('acadprogid', 'syid')
    //         ->where('deleted', 0)
    //         ->orderBy('acadprogid')
    //         ->get();

    //     $xtend = 'principalsportal.layouts.app2';
    // } else {
    //     if ($refid->refid == 20) {
    //         $xtend = 'principalassistant.layouts.app2';
    //     } elseif ($refid->refid == 22) {
    //         $xtend = 'principalcoor.layouts.app2';
    //     } elseif (Session::get('currentPortal') == 3) {
    //         $xtend = 'registrar.layouts.app';
    //     } else {
    //         if (isset($refid->refid)) {
    //             if ($refid->refid == 27) {
    //                 $xtend = 'academiccoor.layouts.app2';
    //             }
    //         } else {
    //             $xtend = 'general.defaultportal.layouts.app';
    //         }
    //     }

    $acadprogid = DB::table('teacheracadprog')
        ->where('teacherid', $teacherid)
        ->where('acadprogutype', Session::get('currentPortal'))
        ->select('acadprogid', 'syid')
        ->where('deleted', 0)
        ->orderBy('acadprogid')
        ->get();
    // }
    if (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (auth()->user()->type == 3) {
        $extend = 'registrar.layouts.app';
    }

    $all_acad = [];

    foreach ($acadprogid as $item) {
        if ($item->acadprogid != 6) {
            array_push($all_acad, $item->acadprogid);
        }
    }

    $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
    $semester = DB::table('semester')->get();
    $user = auth()->user()->id;
    $levelname = DB::table('college_year')->get();

    $type = auth()->user()->type;
    if ($type != 3) {
        $collegeid = DB::table('teacher')
            ->join('teacherdean', 'teacherdean.teacherid', '=', 'teacher.id')
            ->where('teacher.userid', $user)
            ->where('teacher.deleted', 0)
            ->where('teacherdean.deleted', 0)
            ->pluck('teacherdean.collegeid')
            ->toArray();
        $course = DB::table('college_courses')->where('deleted', 0)->whereIn('collegeid', $collegeid)->get();
    } else {
        $course = DB::table('college_courses')->where('deleted', 0)->get();
    }

    // $gradelevel = DB::table('gradelevel')
    //     ->where('deleted', 0)
    //     ->whereIn('acadprogid', $all_acad)
    //     ->orderBy('sortid')
    //     ->select('id', 'levelname', 'levelname as text', 'acadprogid')
    //     ->get();

    // $gradelevel1 = DB::table('gradelevel')
    //     ->where('acadprogid', 6)
    //     ->where('deleted', 0)
    //     ->select('id', 'levelname as text')
    //     ->get();

    // $gradelevel2 = DB::table('gradelevel')
    //     ->where('acadprogid', 8)
    //     ->where('deleted', 0)
    //     ->select('id', 'levelname as text')
    //     ->get();

    $gradelevel = DB::table('gradelevel')
        ->where('deleted', 0)
        ->whereIn('acadprogid', [6, 8])
        ->orderBy('sortid')
        ->get();

    $allsections = DB::table('sectiondetail')
        ->join('sections', function ($join) use ($gradelevel) {
            $join->on('sectiondetail.sectionid', '=', 'sections.id');
            $join->where('sections.deleted', 0);
            $join->whereIn('sections.levelid', collect($gradelevel)->pluck('id'));
        })
        ->where('sectiondetail.deleted', 0)
        ->orderBy('sectionname')
        ->select('sections.sectionname as text', 'syid', 'sections.id', 'sections.sectionname', 'sections.levelid')
        ->get();

    $schoolinfo = DB::table('schoolinfo')->first();

@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <style>
        .subj_tr {
            vertical-align: middle !important;
            cursor: pointer;
        }

        .stud_subj_tr {
            vertical-align: middle !important;
            cursor: pointer;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>
@endsection

@section('content')
    @php
        $subj_strand = DB::table('sh_sectionblockassignment')
            ->join('sh_block', function ($join) {
                $join->on('sh_sectionblockassignment.blockid', '=', 'sh_block.id');
                $join->where('sh_block.deleted', 0);
            })
            ->join('sh_strand', function ($join) {
                $join->on('sh_block.strandid', '=', 'sh_strand.id');
                $join->where('sh_strand.deleted', 0);
            })
            ->where('sh_sectionblockassignment.deleted', 0)
            ->select('syid', 'sectionid', 'strandid', 'strandcode')
            ->get();
    @endphp




    <section class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i>Grade Summary</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Grade Summary</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card shadow">
                        {{-- <div class="card-header bg-primary p-1">
                            <div class="row">
                            </div>
                        </div> --}}
                        <div class="card-body" style="background-color: #c1c1c1de">
                            <div style="font-size: 1.5rem"><i class="fa fa-filter"></i> Filter</div>
                            <br>
                            <div class="row">

                                <div class="col-md-2">
                                    <label for="">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2">
                                        @foreach (DB::table('sy')->select('id', 'sydesc', 'isactive')->orderBy('sydesc', 'desc')->get() as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->sydesc }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Semester</label>
                                    <select name="semester" id="semester" class="form-control select2">
                                        @foreach (DB::table('semester')->select('id', 'semester', 'isactive')->get() as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->semester }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->semester }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="text-sm">Course</label>

                                    <select class="form-control select2 " id="course">
                                        {{-- <option value="">All</option> --}}
                                        @foreach ($course as $item)
                                            @if ($item->deleted == 0)
                                                <option value="{{ $item->id }}"
                                                    style="word-wrap: break-word!important">
                                                    {{ $item->courseDesc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-2">
                                    <label for="" class="text-sm">Academic Level</label>
                                    <select class="form-control form-control-sm select2 academic" id="academic"
                                        style="width: 100%;">
                                        <option value="">Select Level</option>

                                    </select>
                                </div> --}}

                                <div class="col-md-2">
                                    <label for="">Academic Level</label>
                                    <select name="academic" id="academic" class="form-control select2">
                                        {{-- <option value="">All</option> --}}
                                        @foreach ($gradelevel as $item)
                                            <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Section/Block </label>
                                        <select name="section" id="section" class="form-control select2 section">
                                            {{-- <option value="">Select Section</option> --}}
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-2">
                                    <div class="form-group" id="strand_holder" hidden>
                                        <label for="">Strand</label>
                                        <select name="strand" id="strand" class="form-control select2">

                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-1">

                                </div>

                            </div>
                            {{-- <hr class="mt-0"> --}}




                            <div class="row grading_sheet_option" hidden="hidden">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block btn-sm" id="grading_sheet_print"> <i
                                            class="fas fa-print"></i></i> PRINT</button>
                                </div>
                            </div>



                        </div>
                    </div>
                    <div class="card shadow">
                        {{-- <div class="card-header p-1 bg-primary">
                        </div> --}}
                        <div class="card-body" style="background-color: #c1c1c1de">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-0 pt-1">Grade Summary</h5>

                                </div>

                            </div>
                            <hr>

                            <div class="table-responsive">
                                {{-- <div class="d-flex justify-content-between mb-2"> --}}
                                <div>

                                    <div>
                                        <input type="text" id="datatable_2_search" class="form-control"
                                            style="float: right;display: none" placeholder="Search...">
                                    </div>
                                </div>
                                {{-- <div class="d-flex" id="datatable_2_length"> --}}
                                <label class="mr-2" id="label-show" style="display:none;">Show</label>
                                <select name="datatable_2_length" aria-controls="datatable_2_table"
                                    class="custom-select custom-select-sm form-control form-control-sm"
                                    id="datatable_2_length_select" style="width: 60px;display:none;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="ml-2" id="label-entries" style="display:none;">entries</label>
                                {{-- </div> --}}
                                <table class="table table-bordered" width="100%" id="datatable_2_table">
                                    <thead id="datatable_2_head" style="background-color: gray;color:white;">
                                    </thead>
                                    <tbody id="datatable_2" style="background-color: white">
                                    </tbody>
                                </table>
                                <h6 id="instruction" class="text-muted text-center">Please choose filters to display the
                                    grade summary
                                </h6>
                            </div>

                            <div class=" master_sheet_option mt-4">
                                <span>Legend:</span>
                                <span class="ml-3">
                                    <i class="fas fa-square mr-1" style="color: green"></i>Submitted
                                </span>
                                <span class="ml-3">
                                    <i class="fas fa-square mr-1" style="color: blue"></i>Approved
                                </span>
                                <span class="ml-3">
                                    <i class="fas fa-square mr-1 text-info"></i>Posted
                                </span>
                                <span class="ml-3">
                                    <i class="fas fa-square mr-1 text-warning"></i>Pending
                                </span>

                            </div>

                            {{-- 
                            <div class="row mt-2">
                                <div class="col-md-12 text-right"></div>
                            </div> --}}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#syid').select2();
            $('#semester').select2();
            $('#course').select2();
            $('#academic').select2();
            $('#section').select2();


            var sy = $('#syid').val();
            var semester = $('#semester').val();
            var course = $('#course').val();
            var academic = $('#academic').val();
            var section = $('#section').val();

            console.log('School Year:', sy);
            console.log('Semester:', semester);
            console.log('Course:', course);
            console.log('Academic:', academic);
            console.log('Section:', section);



            $('#course').on('change', function() {
                var syid = $('#syid').val();
                var semester = $('#semester').val();
                var academic = $('#academic').val();
                var value = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '/college/section/gradesummary/get',
                    data: {
                        syid: syid,
                        semester: semester,
                        academic: academic,
                        course: value
                    },
                    success: function(data) {
                        $('#section').empty();
                        $.each(data, function(index, item) {
                            $('#section').append(`
                                <option value="${item.id}">${item.sectionDesc}</option>
                            `);
                        });
                    }
                });
            });


            $('#course_modal').select2()
            $('#academic_modal').select2()

            $(document).on('change', '#syid , #semester, #course, #academic', function(data) {


                var syid = $('#syid').val();
                var semester = $('#semester').val();
                var course = $('#course').val();
                var value = $('#academic').val();
                $.ajax({
                    type: 'GET',
                    url: '/college/section/gradesummary/get',
                    data: {
                        syid: syid,
                        semester: semester,
                        academic: value,
                        course: course
                    },
                    success: function(data) {
                        $('#section').empty();
                        $('#section').append(`
                            
                            `);
                        $.each(data, function(index, item) {
                            $('#section').append(`
                               
                                <option value="${item.id}">${item.sectionDesc}</option>
                            `);
                        });

                        get_student(data)
                        datatable_2_head(data)
                        $('#instruction').hide();
                        $('#datatable_2_length_select').show();
                        $('#label-show').show();
                        $('#label-entries').show();
                    }
                });
            })


            // get_student(data)

            function get_student(data) {
                const sy = $('#syid').val();
                const semester = $('#semester').val();
                const course = $('#course').val();
                const academic = $('#academic').val();
                const section = $('#section').val();

                $.ajax({
                    type: 'GET',
                    url: `/college/teacher/student-list-for-all-grade-summary/${sy}/${semester}/${course}/${academic}/${section}`,
                    success: function(data) {
                        console.log(data);
                        // datatable_2(data);
                        datatable_2_head(data);
                        // plot_subject_grades(data)
                    }
                });

                $('#datatable_2_search').css({
                    'width': '30%',
                    'float': 'right',
                    'margin-bottom': '15px',
                    'display': 'block'
                }).on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $("#datatable_2 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            }







            function datatable_2_head(data) {
                const students = data.students || [];
                const groupedSubjects = data.groupedSubjects || [];
                const studentGrades = data.studentsgrades || [];




                $("#datatable_2_head").empty();

                let headerRow = `
                        <tr style="font-size: 11.2px !important;">
                        <th width="10%">Student ID</th>
                        <th width="15%">Student Name</th>
                        <th width="10%">Academic Level</th>
                        <th width="12%">Course</th>
                        `;
                groupedSubjects.forEach(subject => {
                    headerRow += `<th style="width: 8.5vw; text-align: center;">${subject.subjCode}</th>`;
                    // headerRow += `<th>${subject.subjCode}</th>`;
                });
                headerRow += `</tr>`;
                $("#datatable_2_head").append(headerRow);

                const groupedByGender = students.reduce((acc, student) => {
                    const gender = student.gender || 'Unspecified';
                    acc[gender] = acc[gender] || [];
                    acc[gender].push(student);
                    return acc;
                }, {});

                $("#datatable_2").empty();

                const genderKeys = Object.keys(groupedByGender);
                genderKeys.sort((a, b) => {
                    if (a.toLowerCase() === 'male') return -1;
                    if (b.toLowerCase() === 'male') return 1;
                    return 0;
                });

                genderKeys.forEach(gender => {
                    // let backgroundColor = gender.toLowerCase() === 'male' ? '#8ec9fd' : '#fd8ec9';
                    let backgroundColor = gender.toLowerCase() === 'male' ? '' : '';
                    $("#datatable_2").append(`
                    <tr style="background-color: ${backgroundColor}; font-size: 13.5px;">
                            <td colspan="100%"><strong>${gender.toUpperCase()}</strong></td>
                        </tr>
                    `);

                    groupedByGender[gender].forEach(student => {
                        let row = `
                            <tr style="font-size: 11.5px;">
                                <td>${student.sid || ''}</td>
                                <td>${student.lastname || ''}, ${student.firstname || ''} ${student.middlename || ''} ${student.suffix || ''}</td>
                                <td>${student.levelname || ''}</td>
                                <td>${student.courseabrv || ''}</td>
                            `;
                        groupedSubjects.forEach(subject => {
                            const grade = studentGrades.find(grade => grade.prospectusID ===
                                subject.subjectID && grade.id === student.id);
                            // const gradeValue = grade ? grade.fg : '';
                            const gradeValue = grade ? (grade.fg === null ? '' : grade.fg) :
                                '';
                            const statusClassMap = {
                                1: 'bg-success',
                                2: 'bg-primary',
                                3: 'bg-warning',
                                4: 'bg-info',
                                'default': 'bg-transparent'
                            };
                            const statusClass = grade ? (statusClassMap[grade
                                    .finalstatus] || statusClassMap.default) :
                                statusClassMap.default;
                            row +=
                                `<td class="${statusClass}" style="text-align: center;" data-student-id="${student.sid}" data-subject-id="${subject.subjectID}">${gradeValue}</td>`;
                            if (grade) {
                                console.log(
                                    `Grade final status for student ${student.sid} in subject ${subject.subjectID}: with a grade value ${gradeValue} is ${grade.finalstatus}`
                                );
                            }
                        });
                        row += `</tr>`;
                        $("#datatable_2").append(row);
                    });
                });
            }

            // var table = $('#datatable_2_table').DataTable({
            //     "lengthChange": false,
            //     "pageLength": 10 // default value
            // });

            // $('#datatable_2_length_select').on('change', function() {
            //     var length = $(this).val();
            //     table.page.len(length).draw();
            // });


        })
    </script>
@endsection
