@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .font-20 {
            font-size: 20px !
        }

        .display-none {
            display: none;
        }

        .gender-separator-row {
            background-color: #e0e0e0;
            /* Gray background for the separator row */
            font-weight: bold;
        }

        .gender-separator {
            display: block;
            text-align: center;
        }
    </style>
@endsection



@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $levelname = DB::table('college_year')->get();
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Class Schedule</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Class Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box shadow">
                                {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
                                <div class="info-box-content">
                                    <div class="row pb-2 d-flex">
                                        <div class="col-md-2 col-sm-1">
                                            <label for="">School Year</label>
                                            <select class="form-control form-control-sm select2" id="syid">
                                                @foreach ($sy as $item)
                                                    @if ($item->isactive == 1)
                                                        <option value="{{ $item->id }}" selected="selected">
                                                            {{ $item->sydesc }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-1">
                                            <label for="">Semester</label>
                                            <select class="form-control form-control-sm select2" id="semester">
                                                @foreach ($semester as $item)
                                                    <option {{ $item->isactive == 1 ? 'selected' : '' }}
                                                        value="{{ $item->id }}">{{ $item->semester }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-1">
                                            <label for="">Level</label>
                                            <select class="form-control form-control-sm select2" id="levelname">
                                                <option>All</option>
                                                @foreach ($levelname as $item)
                                                    <option value="{{ $item->levelid }}">{{ $item->yearDesc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-1" hidden>
                                            <label for="">Term</label>
                                            <select class="form-control form-control-sm select2" id="term">
                                                <option value="">All</option>
                                                <option value="Whole Sem">Whole Sem</option>
                                                <option value="1st Term">1st Term</option>
                                                <option value="2nd Term">2nd Term</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"></div>
                                        {{-- <div class="col-md-2 col-sm-1 ">
                                            <label for="">Search</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" id="search" class="form-control form-control-sm"
                                                    placeholder="Enter search term">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    {{-- <div class="row">
                                                            <div class="col-md-2">
                                                                  <button class="btn btn-info btn-block btn-sm" id="filter_sched"><i class="fas fa-filter"></i> Filter</button>
                                                            </div>
                                                      </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- <div class="col-md-5">
                              <div class="card shadow">
                                    <div class="card-header  bg-success">
                                          <h3 class="card-title"><i class="fas fa-clipboard-list"></i> By Day</h3>
                                          <div class="card-tools">
                                                <ul class="nav nav-pills ml-auto">
                                                      <li class="nav-item">
                                                            <select class="form-control form-control-sm" name="" id="filter_day">
                                                                  <option value="1">Monday</option>
                                                                  <option value="2">Tuesday</option>
                                                                  <option value="3">Wednesday</option>
                                                                  <option value="4">Thursday</option>
                                                                  <option value="5">Friday</option>
                                                                  <option value="6">Saturday</option>
                                                            </select>
                                                      </li>
                                                </ul>
                                          </div>
                                    </div>
                                    <div class="card-body p-0">
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <table class="table table-sm table-striped mb-0 table-bordered" style="font-size:.8rem" width="100%">
                                                            <thead>
                                                                  <tr>
                                                                        <th width="25%" class="pl-2 pr-2">Time</th>
                                                                        <th width="25%">Section</th>
                                                                        <th width="50%">Subject</th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody  id="table_1"></tbody>
                                                      </table>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div> --}}
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header  bg-primary">
                            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Class Schedule Details</h3>

                        </div>

                        <div class="card-body  p-2">
                            <div class="mt-2 p-2 row">
                                <div class="col-md-2">
                                    <button class="btn btn-success btn-sm" id="add_schedule_load" data-toggle="modal"
                                        data-target="#schedule_load">Add Schedule Load</button>
                                </div>
                                <div class="col-md-10 d-flex flex-row justify-content-end">
                                    <button class="btn btn-primary btn-sm" id="print_schedule_load"><i
                                            class="fas fa-filter"></i> Print Schedule Load</button>
                                </div>
                            </div>

                        </div>
                        <div class="row p-2">
                            <div class="col-md-12" style="font-size:.8rem">
                                <table class="table table-sm display table-bordered table-responsive-sm" id="datatable_1"
                                    width="100%">
                                    <thead>
                                        <tr class="font-20-baga">
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th class="text-center">Level</th>
                                            <th>Time Schedule</th>
                                            <th class="text-center">Day</th>
                                            <th class="text-center">Room</th>
                                            <th class="text-center">Enrolled</th>
                                            <th class="text-center">Action</th>
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
    </section>


    <div class="modal fade" id="schedule_load" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header  bg-primary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">Add Schedule Load</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-header">

                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped  table-bordered table-responsive-sm font-weight-bold"
                        style="font-size:.8rem" id="schedule_table" width="100%">
                        <thead>
                            <tr>
                                <th>Section</th>
                                <th>Course</th>
                                <th class="text-center">Level</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="section_load" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header  bg-primary">
                    <h5 class="card-title mb-0" id="exampleModalLabel">Section:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm display table-bordered table-responsive-sm font-weight-bold"
                        style="font-size:.8rem" id="schedule_table" width="100%">
                        <thead>
                            <tr>
                                <th class="align-middle" width="30%">Subject</th>
                                <th class="text-center">Lec. Units</th>
                                <th class="text-center">Lab Units</th>
                                <th width="30%" class="text-center align-middle">Schedule</th>
                                <th class="text-center align-middle">Instructor</th>
                                <th class="text-center align-middle">Room</th>
                                <th class="text-center align-middle">Capacity</th>
                                <th class="text-center align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table_2">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="conflict_modal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header  bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center text-danger font-weight-bold mb-0"><i class="fa fa-exclamation-triangle"></i>
                        Conflict of Schedules</h5>
                    <div class="mt-4 text-center"><span class="font-weight-bold conflicted_subject"
                            style="text-decoration: underline;"></span> is in conflict with other schedules</div>

                    <div style="height: 300px; overflow-y: auto;" id="conflict_modal_body">

                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_1" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h4>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" href="#">
                            <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                        </a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="container-fluid headerText p-3">
                    <div class="row py-3">
                        <!-- Teacher -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i> Teacher</span>
                                <span id="teacherName">{{ auth()->user()->name }}</span>
                                <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                            </div>
                        </div>
                        <!-- Subject -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                <span id="subjectDesc"></span>
                                <a class="text-primary" id="subjectCode"></a>
                            </div>
                        </div>
                        <!-- Level -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                <span id="collegeLevel"></span>
                            </div>
                        </div>
                        <!-- Section -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                <span id="section"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="font-size:.8rem;height: 450px; overflow-y: auto;">
                    <table class="table table-sm table-striped" width="100%">
                        <thead>
                            <tr id="enrolled_datatable_table">
                                <th width="10%">Student ID No.</th>
                                <th width="15%" class="text-center">
                                    <div class="d-flex flex-row justify-content-between">
                                        <p class="mb-0">Student Name</p>
                                        <a href="" id="sort"><i class="fa fa-sort"></i></a>
                                    </div>
                                </th>
                                <th width="10%">Academic Level</th>
                                <th width="12%">Course</th>
                                <th width="10%">Contact No.</th>
                                <th width="10%">Email Address</th>
                                <th width="auto">Address</th>
                            </tr>
                        </thead>
                        <tbody id="student_table">
                            <tr class="male_students">
                                <td colspan="7" class="font-weight-bold male_sort_arrow" style="background-color: #8ec9fd">Male <a
                                        href="" id="male_sort"><i class="fa fa-sort"></td>
                            </tr>
                            <tr class="female_students">
                                <td colspan="7" class=" font-weight-bold female_sort_arrow" style="background-color: #fd8ec9">Female <a
                                        href="" id="female_sort"><i class="fa fa-sort"></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $('#syid').select2()
        $('#semester').select2()
        $('#levelname').select2()
    </script>

    <script>
        $(document).ready(function() {

            function show_sched(schedid, section, subjectid, subject, levelname, schedotherclass, time, days, room,
                sectionID, levelid, courseDesc,daysid) {
                conflict_sched(time, daysid, room, schedid)
                student_list(schedid)
                let existingRow = $('#table_1').find(`tr[data-id="${sectionID}"][data-subjectid="${subjectid}"]`);
                if (existingRow.length > 0) {
                    let firstRow = existingRow.first();
                    let currentRowspan = parseInt(firstRow.find('td.rowspan').attr('rowspan')) || 1;

                    // Update the rowspan for the first occurrence
                    firstRow.find('td.rowspan').attr('rowspan', currentRowspan + 1);

                    // Append the new row without repeating the subject cell
                    firstRow.after(`
                        <tr class="appended_row_sched" style="font-size: 13px!important" data-id="${sectionID}" data-subjectid="${subjectid}">
                            <td class="p-2 align-middle text-center">
                                ${levelid >= 22 ? `${courseDesc} - ${levelname}` : levelname}
                            </td>
                            <td class="p-2 align-middle hasConflict" data-id="${schedid}"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${time ? time : ''}</td>
                            <td class="p-2 align-middle text-center">${days ? days: ''}</td>
                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${room ? room : ''}</td>
                            <td class="p-2 align-middle text-center"><a href="" class="enrolled_stud" data-toggle="modal" data-target="#modal_1"  data-subject="${subject}"  data-levelname="${levelname}" data-section="${section}" data-id="${schedid}"></a></td>
                            <td class="align-middle text-center"><a href="" class="view-conflict" data-toggle="modal" data-target="#conflict_modal" data-subject="${subject}" data-id="${schedid}"></a></td>
                        </tr>
                    `);
                } else {
                    // If the row does not exist, create a new one with rowspan set to 1
                    $('#table_1').append(`
                        <tr class="appended_row_sched" style="font-size: 13px!important" data-id="${sectionID}" data-subjectid="${subjectid}">
                            <td class="p-2 rowspan align-middle" rowspan="1">${section}</td>
                            <td class="p-2 rowspan align-middle" data-id="${subjectid}" rowspan="1">${subject}</td>
                            <td class="p-2 align-middle text-center">
                                ${levelid >= 22 ? `${courseDesc} - ${levelname}` : levelname}
                            </td>
                            <td class="p-2 align-middle hasConflict" data-id="${schedid}"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${time ? time : ''}</td>
                            <td class="p-2 align-middle text-center">${days ? days : ''}</td>
                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${room ? room : ''}</td>
                            <td class="p-2 align-middle text-center"><a href="" class="enrolled_stud" data-toggle="modal" data-target="#modal_1"  data-subject="${subject}"  data-levelname="${levelname}" data-section="${section}" data-id="${schedid}"></a></td>
                            <td class="align-middle text-center"><a href="" class="view-conflict" data-toggle="modal" data-target="#conflict_modal" data-subject="${subject}" data-id="${schedid}"></a></td>
                        </tr>
                    `);
                }

            }


            function show_sched_section(schedid, subjectid, subjCode, subjDesc, lec, lab, schedotherclass,
                schedtime, days, instructorname, roomname, capacity, currentuser, userid) {

                let existingRow = $('#table_2').find(`tr[data-id="${subjectid}"]`);
                if (instructorname == null) {
                    if (existingRow.length > 0) {

                        let firstRow = existingRow.first();


                        let currentRowspan = parseInt(firstRow.find('td.rowspan').attr('rowspan')) || 1;
                        firstRow.find('td.rowspan').attr('rowspan', currentRowspan + 1);

                        firstRow.after(`
                                    <tr class="appended_row" style="font-size: 13px!important" data-id="${subjectid}">
                                        <td class="p-2 align-middle"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${schedtime ? schedtime : ''} ${days ? days : ''}</td>
                                        <td class="p-2 align-middle text-center" style="white-space: nowrap">${instructorname ? instructorname : 'No Instructor Assigned'}</td>
                                        <td class="p-2 align-middle text-center" style="white-space: nowrap">${roomname ? roomname : ''}</td>
                                        <td></td>
                                        <td><a href="" class="add_to_schedule text-center" style="white-space: nowrap" data-id="${schedid}">Add To Schedule</a></td>
                                    </tr>
                                `);
                    } else {
                        // If the row does not exist, create a new one with rowspan set to 1
                        $('#table_2').append(`
                                        <tr class="appended_row" style="font-size: 13px!important" data-id="${subjectid}">
                                            <td class="p-2 rowspan align-middle"rowspan="1">${subjCode} ${subjDesc}</td>
                                            <td class="p-2 rowspan align-middle text-center" rowspan="1">${lec}</td>
                                            <td class="p-2 rowspan align-middle text-center" rowspan="1">${lab}</td>
                                            <td class="p-2 align-middle"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${schedtime ? schedtime : ''} ${days ? days : ''}</td>
                                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${instructorname ? instructorname : 'No Instructor Assigned'}</td>
                                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${roomname ? roomname : ''}</td>
                                            <td></td>
                                            <td><a href="" class="add_to_schedule text-center" style="white-space: nowrap" data-id="${schedid}">Add To Schedule</a></td>
                                        </tr>
                                `);
                    }
                } else if (currentuser == userid) {
                    if (existingRow.length > 0) {

                        let firstRow = existingRow.first();


                        let currentRowspan = parseInt(firstRow.find('td.rowspan').attr('rowspan')) || 1;
                        firstRow.find('td.rowspan').attr('rowspan', currentRowspan + 1);

                        firstRow.after(`
                                    <tr class="appended_row" style="font-size: 13px!important" data-id="${subjectid}">
                                        <td class="p-2 align-middle"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${schedtime ? schedtime : ''} ${days ? days : ''}</td>
                                        <td class="p-2 align-middle text-center" style="white-space: nowrap">${instructorname ? instructorname : 'No Instructor Assigned'}</td>
                                        <td class="p-2 align-middle text-center" style="white-space: nowrap">${roomname ? roomname : ''}</td>
                                        <td></td>
                                        <td><a href="" class="unassign_schedule text-danger text-center" style="white-space: nowrap" data-id="${schedid}">Unassign Schedule</a></td>
                                    </tr>
                                `);
                    } else {
                        // If the row does not exist, create a new one with rowspan set to 1
                        $('#table_2').append(`
                                        <tr class="appended_row" style="font-size: 13px!important" data-id="${subjectid}">
                                            <td class="p-2 rowspan align-middle"rowspan="1">${subjCode} ${subjDesc}</td>
                                            <td class="p-2 rowspan align-middle text-center" rowspan="1">${lec}</td>
                                            <td class="p-2 rowspan align-middle text-center" rowspan="1">${lab}</td>
                                            <td class="p-2 align-middle"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${schedtime ? schedtime : ''} ${days ? days : ''}</td>
                                            <td class="p-2 align-middle text-forkcenter" style="white-space: nowrap">${instructorname ? instructorname : 'No Instructor Assigned'}</td>
                                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${roomname ? roomname : ''}</td>
                                            <td></td>
                                            <td><a href="" class="unassign_schedule text-danger text-center" style="white-space: nowrap" data-id="${schedid}">Unassign Schedule</a></td>
                                        </tr>
                                `);
                    }

                } else {
                    if (existingRow.length > 0) {

                        let firstRow = existingRow.first();


                        let currentRowspan = parseInt(firstRow.find('td.rowspan').attr('rowspan')) || 1;
                        firstRow.find('td.rowspan').attr('rowspan', currentRowspan + 1);

                        firstRow.after(`
                                    <tr class="appended_row" style="font-size: 13px!important" data-id="${subjectid}">
                                        <td class="p-2 align-middle"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${schedtime ? schedtime : ''} ${days ? days : ''}</td>
                                        <td class="p-2 align-middle text-center" style="white-space: nowrap">${instructorname ? instructorname : 'No Instructor Assigned'}</td>
                                        <td class="p-2 align-middle text-center" style="white-space: nowrap">${roomname ? roomname : ''}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                `);
                    } else {
                        // If the row does not exist, create a new one with rowspan set to 1
                        $('#table_2').append(`
                                        <tr class="appended_row" style="font-size: 13px!important" data-id="${subjectid}">
                                            <td class="p-2 rowspan align-middle"rowspan="1">${subjCode} ${subjDesc}</td>
                                            <td class="p-2 rowspan align-middle text-center" rowspan="1">${lec}</td>
                                            <td class="p-2 rowspan align-middle text-center" rowspan="1">${lab}</td>
                                            <td class="p-2 align-middle"><span class="mr-2">${schedotherclass ? schedotherclass : ''}</span>${schedtime ? schedtime : ''} ${days ? days : ''}</td>
                                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${instructorname ? instructorname : 'No Instructor Assigned'}</td>
                                            <td class="p-2 align-middle text-center" style="white-space: nowrap">${roomname ? roomname : ''}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                `);
                    }
                }


            }

            function show_conflicts(subjcode, subjdesc, stime, etime, room, teacher, dayname) {
                $('#conflict_modal_body').append(`
                        <table class="table table-sm display table-bordered conflict_table " id="" width="100%">
                            <tr class="conflict_details font-weight-bold" style="font-size: 14px" >
                                    <td class="font-weight-bold" colspan="2">Conflict Details</td>
                            </tr>
                            <tr>
                                    <td width="20%">Subject:</td>
                                    <td class="font-weight-bold conflict_subject">${subjcode} - ${subjdesc}</td>
                            </tr>
                            <tr>
                                    <td>Time:</td>
                                    <td class="font-weight-bold conflict_subject">${stime} - ${etime}</td>
                            </tr>
                            <tr>
                                    <td>Room:</td>
                                    <td class="font-weight-bold conflict_subject">${room}</td>
                            </tr>
                            <tr>
                                    <td>Teacher:</td>
                                    <td class="font-weight-bold conflict_subject">${teacher}</td>
                            </tr>
                            <tr>
                                    <td>Day:</td>
                                    <td class="font-weight-bold conflict_subject">${dayname}</td>
                            </tr>
                        </table>
                `)
            }


            function show_students() {
                $('.appended_row_stud').remove(); // Remove previously appended rows
                var students = student;
                console.log(students, 'toggle');

                if (toggle == 1) {
                    // Sort students by lastname (assuming students have a 'lastname' field)
                    students.sort(function(a, b) {
                        if (a.lastname.toLowerCase() < b.lastname.toLowerCase()) {
                            return -1;
                        }
                        if (a.lastname.toLowerCase() > b.lastname.toLowerCase()) {
                            return 1;
                        }
                        return 0;
                    });

                    // Separate male and female rows before appending
                    let maleRows = '';
                    let femaleRows = '';

                    $.each(students, function(k, student) {
                        let row = `
                            <tr class="appended_row_stud ${student.gender === 'MALE' ? 'male_row' : 'female_row'}">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;

                        if (student.gender == "MALE") {
                            maleRows += row; // Accumulate male student rows
                            $(row).addClass('appended_row_stud_male');
                        } else if (student.gender == "FEMALE") {
                            femaleRows += row; // Accumulate female student rows
                            $(row).addClass('appended_row_stud_female');
                        }
                    });

                    // Insert rows after male and female sections
                    $('.male_students').after(maleRows);
                    $('.female_students').after(femaleRows);

                    $('.male_students').removeClass('display-none');
                    $('.female_students').removeClass('display-none');

                } else if (toggle == 2) {
                    // If toggle is not 1, append all students to the main table
                    let allRows = '';

                    $.each(students, function(k, student) {
                        allRows += `
                            <tr class="appended_row_stud">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;
                    });

                    $('#student_table').append(allRows);
                    $('.male_students').addClass('display-none');
                    $('.female_students').addClass('display-none');
                } else {
                    let allRows = '';

                    students.sort(function(a, b) {
                        if (a.lastname.toLowerCase() > b.lastname.toLowerCase()) {
                            return -1;
                        }
                        if (a.lastname.toLowerCase() < b.lastname.toLowerCase()) {
                            return 1;
                        }
                        return 0;
                    });

                    $.each(students, function(k, student) {
                        allRows += `
                            <tr class="appended_row_stud">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;
                    });

                    $('#student_table').append(allRows);
                    $('.male_students').addClass('display-none');
                    $('.female_students').addClass('display-none');
                }
            }



            let maleSortOrder = 'asc'; // Track sorting order for male
            let femaleSortOrder = 'asc'; // Track sorting order for female

            // Event listener for male sorting arrow click
            $('.male_sort_arrow').on('click', function(e) {
                e.preventDefault();
                maleSortOrder = maleSortOrder === 'asc' ? 'desc' : 'asc';
                sortAndDisplayMaleStudents(maleSortOrder);
            });

            // Event listener for female sorting arrow click
            $('.female_sort_arrow').on('click', function(e) {
                e.preventDefault();
                femaleSortOrder = femaleSortOrder === 'asc' ? 'desc' : 'asc';
                sortAndDisplayFemaleStudents(femaleSortOrder);
            });

            function sortAndDisplayMaleStudents(sortOrder) {
                // Filter students by gender (Male)
                let maleStudents = student.filter(s => s.gender === "MALE");

                // Sort based on the order
                maleStudents.sort(function(a, b) {
                    if (sortOrder === 'asc') {
                        return a.lastname.toLowerCase() < b.lastname.toLowerCase() ? -1 : 1;
                    } else {
                        return a.lastname.toLowerCase() > b.lastname.toLowerCase() ? -1 : 1;
                    }
                });

                // Clear only the male rows
                $('.male_row').remove();

                // Rebuild male rows after sorting
                let maleRows = '';
                $.each(maleStudents, function(k, student) {
                    maleRows += `
                        <tr class="appended_row_stud male_row">
                            <td>${student.sid}</td>
                            <td>${student.student_name}</td>
                            <td>${student.levelname}</td>
                            <td>${student.courseabrv}</td>
                            <td>${student.contactno ? student.contactno : ''}</td>
                            <td>${student.semail ? student.semail : ''}</td>
                            <td>${student.street} ${student.barangay} ${student.city}</td>
                        </tr>
                    `;
                });

                // Append sorted male rows
                $('.male_students').after(maleRows);
            }

            function sortAndDisplayFemaleStudents(sortOrder) {
                // Filter students by gender (Female)
                let femaleStudents = student.filter(s => s.gender === "FEMALE");

                // Sort based on the order
                femaleStudents.sort(function(a, b) {
                    if (sortOrder === 'asc') {
                        return a.lastname.toLowerCase() < b.lastname.toLowerCase() ? -1 : 1;
                    } else {
                        return a.lastname.toLowerCase() > b.lastname.toLowerCase() ? -1 : 1;
                    }
                });

                // Clear only the female rows
                $('.female_row').remove();

                // Rebuild female rows after sorting
                let femaleRows = '';
                $.each(femaleStudents, function(k, student) {
                    femaleRows += `
                        <tr class="appended_row_stud female_row">
                            <td>${student.sid}</td>
                            <td>${student.student_name}</td>
                            <td>${student.levelname}</td>
                            <td>${student.courseabrv}</td>
                            <td>${student.contactno ? student.contactno : ''}</td>
                            <td>${student.semail ? student.semail : ''}</td>
                            <td>${student.street} ${student.barangay} ${student.city}</td>
                        </tr>
                    `;
                });

                // Append sorted female rows
                $('.female_students').after(femaleRows);
            }





            $(document).on('click', '#sort', function(e) {
                e.preventDefault()
                toggle = toggle == 0 ? 1 : toggle == 1 ? 2 : 0;
                console.log(toggle, 'toggle')
                show_students()
            })


            $('#syid').change(function() {
                $('.appended_row_sched').remove()
                get_schedule();

            })

            $('#semester').change(function() {
                $('.appended_row_sched').remove()
                get_schedule();

            })

            $('#levelname').change(function() {
                $('.appended_row_sched').remove()
                get_schedule();

            })
            var index = 0;
            $(document).on('click', '.view-conflict', function(e) {
                var schedid = $(this).data('id')
                var subject = $(this).data('subject')
                $('.conflicted_subject').text(subject)
                const filteredArray = conflicting.filter(subArray => {
                    return subArray[subArray.length - 1] === schedid;
                });

                var count = filteredArray[0].length - 1
                for (var i = 0; i < count; i++) {
                    show_conflicts(
                        filteredArray[0][i].subjCode,
                        filteredArray[0][i].subjDesc,
                        filteredArray[0][i].stime,
                        filteredArray[0][i].etime,
                        filteredArray[0][i].roomname,
                        filteredArray[0][i].teachername,
                        filteredArray[0][i].dayname
                    )
                }
            });

            $('#conflict_modal').on('hidden.bs.modal', function() {
                $('.conflict_table').remove()
                $('.conflict_subject').text('')
                index = 0
            })
            var sched_stud = []
            var conflicting = [];

            function conflict_sched(schedtime,daysid,roomname,schedid) {
                $.ajax({
                        type:'GET',
                        url:'/college/teacher/schedule/conflict',
                        data:{
                            'syid':$('#syid').val(),
                            'semid':$('#semester').val(),
                            'time':schedtime,
                            'day':daysid,
                            'room':roomname,
                            'schedid':schedid,
                        },
                        success:function(data) {
                            if(data && data.length > 0){
                                var hasConflict = $('.hasConflict[data-id="' + schedid + '"]')
                                var viewConflict = $('.view-conflict[data-id="' + schedid + '"]')
                                hasConflict.append('<i class="fa fa-exclamation-triangle text-warning"></i>')
                                viewConflict.text('View Conflict')
                                data.push(schedid)
                                conflicting.push(data)
                            }
                            
                        },
                })

            }


            function student_list(schedid) {
                var syid = $('#syid').val();
                var semid = $('#semester').val();
                var ajaxCalls = []
                
                $.ajax({
                    type:'get',
                    url: '/college/teacher/student-list-for-all/'+syid+'/'+semid + '/' + schedid,
                    
                    success:function(enrolled){
                        var enrolled_stud = $('.enrolled_stud[data-id="' + schedid + '"]')
                        enrolled_stud.text(enrolled.studentCount)
                    }
                })
                
            }

            function get_schedule() {

                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/schedule/get',
                    data: {
                        'syid': $('#syid').val(),
                        'semid': $('#semester').val(),
                        'gradelevel': $('#levelname').val(),
                    },
                    success: function(data) {
                        console.log(data)
                        $.each(data, function(a, sched) {
                            show_sched(sched.schedid, sched.sectionDesc, sched.subjectid, sched.subject, sched.yearDesc, 
                            sched.schedotherclass, sched.schedtime, sched.days, sched.roomname, sched.sectionid, sched.levelid, sched.courseDesc,sched.daysid)
                        })
                        

                    }
                })

            }

            $('#add_schedule_load').click(function() {
                $.ajax({
                    type: 'get',
                    url: '/college/section/get',
                    data: {
                        syid: $('#syid').val(),
                        semester: $('#semester').val(),
                    },
                    success: function(data) {
                        console.log(data)
                        add_schedule_table(data)
                    }
                })
            })

            var section_sched;
            $(document).on('click', '.section_link', function() {
                section_sched = $(this).data('id')
                get_section_sched()
            })

            function get_section_sched() {

                $.ajax({
                    type: 'get',
                    url: '/college/section/get/sched',
                    data: {
                        id: section_sched
                    },
                    success: function(data) {

                        console.log(data)
                        $.each(data, function(a, sched) {
                            show_sched_section(
                                sched.schedid,
                                sched.subjectid,
                                sched.subjCode,
                                sched.subjDesc,
                                sched.lecunits,
                                sched.labunits,
                                sched.schedotherclass,
                                sched.schedtime,
                                sched.days,
                                sched.instructorname,
                                sched.roomname,
                                sched.capacity,
                                sched.currentuser,
                                sched.userid
                            )
                        })
                    }
                })
            }

            $(document).on('click', '.add_to_schedule', function(e) {
                e.preventDefault()
                var schedid = $(this).data('id')
                Swal.fire({
                    title: 'Add to Schedule?',
                    text: "Are you sure you want to add this to your schedule?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Add it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'get',
                            url: '/college/teacher/schedule/add',
                            data: {
                                schedid: schedid
                            },
                            success: function(data) {
                                $('.appended_row').remove()
                                get_section_sched()

                            }
                        })
                    }
                })
            })

            $(document).on('click', '.unassign_schedule', function(e) {
                e.preventDefault()
                var schedid = $(this).data('id')
                Swal.fire({
                    title: 'Remove from Schedule?',
                    text: "Are you sure you want to remove this from your schedule?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Remove it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'get',
                            url: '/college/teacher/schedule/delete',
                            data: {
                                schedid: schedid
                            },
                            success: function(data) {

                                $('.appended_row').remove()
                                get_section_sched()

                            }
                        })
                    }
                })
            })

            var student;

            function show_students_function(schedid) {
                var syid = $('#syid').val()
                var semid = $('#semester').val()
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student-list-for-all/' + syid + '/' + semid + '/' + schedid,
                    success: function(data) {
                        console.log(data)
                        student = data.students
                        show_students()
                    }
                })
            }


            var toggle;
            var studschedid;
            $(document).on('click', '.enrolled_stud', function(e) {
                e.preventDefault()
                toggle = 1;
                studschedid = $(this).data('id')
                var subject = $(this).attr('data-subject')
                var levelname = $(this).attr('data-levelname')
                var section = $(this).attr('data-section')
                $('#subjectDesc').text(subject)
                $('#collegeLevel').text(levelname)
                $('#section').text(section)
                $('#sectionNameHeader').text(section + ' ' + subject)
                show_students_function(studschedid)
            })

            $(document).on('click','#view_pdf', function(){
                var syid = $('#syid').val()
                var semester = $('#semester').val()
               
                

                window.open('student-list/print/' + syid + '/' + semester + '/' + studschedid,);
            })

            $('#print_schedule_load').click(function() {

                var syid = $('#syid').val()
                var semester = $('#semester').val()
                // $.ajax({
                //     type:'get',
                //     url: '/college/teacher/schedule/print/'+syid+'/'+semester,
                //     success:function(data){

                //     }

                // })
                var win = window.open('/college/teacher/schedule/print/' + syid + '/' + semester, '_blank');
                win.focus();
                setTimeout(function() {
                    win.print();
                }, 1000);
            })



            get_schedule()

            $('#filter_sched').on('click', function() {
                get_schedule()
            })



            $(document).on('change', '#term', function() {
                // datatable_1()
                byday_sched()
            })


            $(document).on('change', '#filter_day', function() {
                byday_sched()

            })

            $(document).on('change', '#filter_day', function() {
                byday_sched()
            })

            $('#section_load').on('hidden.bs.modal', function() {
                $('.appended_row').remove()

            })
            $('#schedule_load').on('hidden.bs.modal', function() {
                $('.appended_row_sched').remove()
                get_schedule()
            })

            $('#modal_1').on('hidden.bs.modal', function() {
                $('.appended_row_stud').remove()
            })

            function add_schedule_table(data) {

                $('#schedule_table').DataTable({
                    destroy: true,
                    order: false,
                    data: data,
                    columns: [{
                            data: 'sectionDesc'
                        },
                        {
                            data: 'courseDesc'
                        },
                        {
                            data: 'yearDesc'
                        },
                        {
                            data: null
                        },
                    ],
                    columnDefs: [{
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(
                                        `<p class="section_link text-muted mb-0" data-id="${rowData.subjectID}">${rowData.sectionDesc}</p>`
                                    )
                                    .addClass('align-middle')
                                    .css('vertical-align', 'middle');

                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(
                                        `<p  class="section_link text-muted mb-0" data-id="${rowData.subjectID}">${rowData.courseDesc}</p>`
                                    )
                                    .addClass('align-middle')
                                    .css('vertical-align', 'middle');

                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(
                                        `<p  class="section_link text-muted mb-0" data-id="${rowData.subjectID}">${rowData.yearDesc}</p>`
                                    )
                                    .addClass('align-middle text-center')
                                    .css('vertical-align', 'middle');

                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(
                                        `<a href="javascript:void(0)" style="white-space: nowrap" class="section_link mb-0" data-id="${rowData.id}" data-toggle="modal" data-target="#section_load">View Schedule</a>`
                                    )
                                    .addClass('align-middle text-center')
                                    .css('vertical-align', 'middle');

                            }
                        },
                    ]
                })
            }





        })
    </script>
@endsection
