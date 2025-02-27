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
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $levelname = DB::table('college_year')->get();

    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Grades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">System Grading</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                        <li class="nav-item col-md-2 ">
                                            <a class="nav-link active" href="{{ url('college/teacher/student/collegesystemgrading') }}">
                                                System Grading
                                            </a>
                                        </li>
                                        <li class="nav-item col-md-2 ">
                                            <a class="nav-link" href="{{ url('college/teacher/student/excelupload') }}">
                                                Excel Upload
                                            </a>
                                        </li>
                                        <li class="nav-item col-md-2 ">
                                            <a class="nav-link " href="{{ url('college/teacher/student/grades') }}">
                                                Final Grading
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                {{-- <div class="col-md-3"></div> --}}
                            </div>
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
                                            <select class="form-control form-control-sm select2" id="levelid">
                                                <option value="0">ALL</option>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header  bg-primary">
                            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Class Schedule Details</h3>
                        </div>
                        <div class="card-body  p-2">
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12" style="font-size:.8rem">
                                <table class="table table-sm display table-bordered table-responsive-sm" id="systemgrading"
                                    width="100%">
                                    <thead>
                                        <tr class="">
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th class="text-center">Level</th>
                                            <th>Time Schedule</th>
                                            <th class="text-center">Day</th>
                                            <th class="text-center">Room</th>
                                            <th class="text-center">Grading Template</th>
                                            <th class="text-center">Enrolled</th>
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
        $(document).ready(function () {
            

            var syid = $('#syid').val();
            var semid = $('#semester').val();
            var levelid = $('#levelid').val();

            get_schedule_details();

            $(document).on('change','#syid', function () {
                syid = $(this).val();
                get_schedule_details()
            })

            $(document).on('change','#semester', function () {
                semid = $(this).val();
                get_schedule_details()
            })

            $(document).on('change','#levelid', function () {
                levelid = $(this).val();
                get_schedule_details()
            })


            function get_schedule_details(){
                $.ajax({
                    url: '/college/teacher/schedule/get',
                    type: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        gradelevel: levelid
                    },
                    success: function (response) {
                        schedule_datatable(response)
                    }
                })
            }

            function schedule_datatable(data){
                $('#systemgrading').DataTable({
                    destroy: true,
                    order: false,
                    data: data,
                    lengthChange: false,
                    info: false,
                    paging: false,
                    columns: [
                        {data: 'sectionDesc'},
                        {data: 'subjDesc'},
                        {data: 'yearDesc'},
                        {data: 'schedtime'},
                        {data: 'days'},
                        {data: 'roomname'},
                        {data: null},
                        {data: null},
                        {data: null},
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
                                $(td).addClass('align-middle')
                            }
                        },
                        
                        {
                        targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle text-center')
                            }
                        },

                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div  class="" >${rowData.ecrDesc}</div>`)

                                $(td).addClass('align-middle text-center')
                            }
                        },

                        {
                            targets: 7,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#" class="" >${rowData.enrolled}</a>`)

                                $(td).addClass('align-middle text-center')
                            }
                        },

                        {
                            targets: 8,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#"  data-schedid="${rowData.schedid}" class="goto_grading" style="text-decoration: underline;">Grading</a>`)

                                $(td).addClass('align-middle text-center')
                            }
                        },
                    ]
                })
            }

            $(document).on('click','.goto_grading', function(e){
                var schedid = $(this).data('schedid');
                $.ajax({
                    url: '/college/teacher/student/systemgrading/' + schedid + '/' + syid + '/'  + semid,
                    type: 'GET',
                    success: function (response) {
                        if(response === 'No Grading Template Selected'){
                            Swal.fire({
                                type: 'warning',
                                title: 'No Grading Template Selected',
                                text: 'Please contact your Dean for Grading Template'
                            })
                        }else{
                            window.location.href = '/college/teacher/student/systemgrading/' + schedid + '/' + syid + '/'  + semid;
                        }
                        
                    }

                })
            })
        })
    </script>
@endsection
