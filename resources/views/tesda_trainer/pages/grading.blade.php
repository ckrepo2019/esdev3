@extends('tesda_trainer.layouts.app2')

@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
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
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Class Schedule Details</h3>
                        </div>
                        <div class="card-body  p-2">
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12" style="font-size:.8rem">
                                <table class="table table-sm display table-bordered table-responsive-sm" id="systemgrading"
                                    width="100%">
                                    <thead>
                                        <tr class="font-20-baga">
                                            <th>Batch</th>
                                            <th>Specialization</th>
                                            <th>Competency Description</th>
                                            <th class="">Hours</th>
                                            <th>Schedule</th>
                                            <th class="">Room</th>
                                            <th class="">Enrolled</th>
                                            <th class="text-center" style="padding-right: 5px!important">Action</th>
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

@section('footerjavascript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
         $(document).ready(function() {

            get_trainer_schedule()
            function get_trainer_schedule(){
                var courseid = $('#course_filter').val()

                $.ajax({
                    type: 'get',
                    url: '/tesda/trainer/schedule/get',
                    data: {
                        courseid: courseid
                    },
                    success: function(data) {
                        
                        display_schedule(data)
                    }
                })
            }


            function display_schedule(schedule){
                $('#systemgrading').DataTable({
                    destroy: true,
                    data: schedule,
                    columns: [
                        { data: 'batch_desc' },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0" >${rowData.batch_desc}</div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.course_name}<div class="mb-0 text-success" style="font-size:.5rem!important">${rowData.course_code}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle" ><div class="mb-0" style="font-size:.5rem!important">${rowData.competency_type}</div>${rowData.competency_desc}<div class="mb-0 text-success" style="font-size:.5rem!important">${rowData.competency_code}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.hours}</div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.date_range || '--'}<div>${rowData.time_range || '--'}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.roomname || '--'}<div class="mb-0 text-success" style="font-size:.5rem!important">${rowData.description}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle text-primary">${rowData.enrolled || '--'}</div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 7,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#" class="mb-0 align-middle text-primary goto_grading" data-schedid="${rowData.schedid}">Grading</a>`)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                    ]
                })
            }

         })

         $(document).on('click','.goto_grading', function(e){
            var schedid = $(this).data('schedid');
            console.log(schedid,'schedid');
            
            $.ajax({
                url: '/tesda/trainer/systemgrading/'+ schedid,
                type: 'GET',
                success: function (response) {
                    window.location.href = '/tesda/trainer/systemgrading/' + schedid;
                }

            })
        })
        
        
    </script>
@endsection
