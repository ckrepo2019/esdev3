@php

    $refid = DB::table('usertype')
        ->where('id', auth()->user()->type)
        ->where('deleted', 0)
        ->select('refid')
        ->first();

    $teacherid = DB::table('teacher')
        ->where('userid', auth()->user()->id)
        ->select('id')
        ->first()->id;

    if (Session::get('currentPortal') == 3) {
        $xtend = 'registrar.layouts.app';
        $acadprogid = DB::table('teacheracadprog')
            ->where('teacherid', $teacherid)
            ->select('acadprogid')
            ->where('deleted', 0)
            ->orderBy('acadprogid')
            ->distinct('acadprogid')
            ->get();
    } elseif (Session::get('currentPortal') == 2) {
        $acadprogid = DB::table('teacheracadprog')
            ->where('teacherid', $teacherid)
            ->where('acadprogutype', 2)
            ->select('acadprogid')
            ->where('deleted', 0)
            ->orderBy('acadprogid')
            ->distinct('acadprogid')
            ->get();

        $xtend = 'principalsportal.layouts.app2';
    } elseif (auth()->user()->type == 2) {
        $acadprogid = DB::table('academicprogram')->where('principalid', $teacherid)->select('id as acadprogid')->get();

        $xtend = 'principalsportal.layouts.app2';
    } elseif (auth()->user()->type == 3) {
        $acadprogid = DB::table('academicprogram')->select('id as acadprogid')->get();

        $xtend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 1) {
        $acadprogid = DB::table('academicprogram')->select('id as acadprogid')->get();
        $syid = DB::table('sy')->where('isactive', 1)->select('id')->first()->id;
        $advisory_levelid = array();

        if ($teacherid) {
            $advisory = DB::table('sections')
                ->where('sections.teacherid', $teacherid)
                ->leftJoin('sectiondetail', function ($join) use ($syid) {
                    $join->on('sections.id', '=', 'sectiondetail.sectionid');
                    $join->where('sectiondetail.deleted', '0');
                    $join->where('sectiondetail.syid', $syid);
                })->get();


            $advisory_levelid = collect($advisory)->pluck('levelid')->unique();
        }

        $xtend = 'teacher.layouts.app';
    } else {
        if ($refid->refid == 20) {
            $xtend = 'principalassistant.layouts.app2';
        } elseif ($refid->refid == 22) {
            $xtend = 'principalcoor.layouts.app2';
        }

        $syid = DB::table('sy')->where('isactive', 1)->select('id')->first()->id;

        $acadprogid = DB::table('teacheracadprog')
            ->where('teacherid', $teacherid)
            ->where('syid', $syid)
            ->select('acadprogid')
            ->where('deleted', 0)
            ->distinct('acadprogid')
            ->get();
    }

    $all_acad = [];

    foreach ($acadprogid as $item) {
        if ($item->acadprogid != 6) {
            array_push($all_acad, $item->acadprogid);
        }
    }
@endphp

@extends($xtend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
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
    <div class="modal fade" id="award_setup_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Award Setup Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Award</label>
                            <input id="input_award" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Range (From)</label>
                            <input id="input_gfrom" class="form-control form-control-sm"
                                oninput="this.value=this.value.replace(/[^0-9\.]/g,'');">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Range (To)</label>
                            <input id="input_gto" class="form-control form-control-sm"
                                oninput="this.value=this.value.replace(/[^0-9\.]/g,'');">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="award_setup_form_button">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Ranking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Ranking</li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" class="mb-1">Grade Level</label>

                                        <select class="form-control select2" id="gradelevel">
                                            <option selected value="">Select Grade Level</option>
                                            @foreach ($all_acad as $item)
                                                @php
                                                    if (auth()->user()->type == 1) {
                                                        if(count($advisory) > 0){
                                                            $gradelevel = DB::table('gradelevel')
                                                                ->where('acadprogid', $item)
                                                                ->whereIn('id', $advisory_levelid)
                                                                ->orderBy('sortid')
                                                                ->where('deleted', 0)
                                                                ->select('id', 'levelname')
                                                                ->get();
                                                        }
                                                    } else {
                                                        $gradelevel = DB::table('gradelevel')
                                                            ->where('acadprogid', $item)
                                                            ->orderBy('sortid')
                                                            ->where('deleted', 0)
                                                            ->select('id', 'levelname')
                                                            ->get();
                                                    }
                                                @endphp
                                                @foreach ($gradelevel as $levelitem)
                                                    <option value="{{ $levelitem->id }}">{{ $levelitem->levelname }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">Section</label>
                                        <select name="section" id="section" class="form-control select2">
                                            {{-- <option selected value="" >Select Section</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group strand_holder" hidden id="starnd_holder">
                                        <label class="mb-1">Strand</label>
                                        <select name="strand" id="strand" class="form-control select2">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-2">
                                    <label class="mb-1">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2">
                                        @foreach (DB::table('sy')->select('id', 'sydesc', 'isactive')->orderBy('sydesc')->get() as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">{{ $item->sydesc }}
                                                </option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="mb-1">Semester</label>
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
                            </div>
                            <div class="row ">
                                <div class="col-md-2 form-group">
                                    <label class="mb-1">Quarter</label>

                                    <select name="quarter" id="quarter" class="form-control select2">
                                        <option value="">Select Quarter</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="mb-1">Certification Date</label>

                                    <input type="date" class="form-control form-control-sm" id="filter_date"
                                        style="height: calc(1.65rem + 2px) !important">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12" style="font-size:.8rem">
                                    <table class="table table-bordered table-sm display nowrap" id="student_list"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th class="text-center strand_holder_header">Section</th>
                                                <th class="text-center">Gen. Ave (Rounded)</th>
                                                <th class="text-center">Gen. Ave (Decimal)</th>
                                                <th class="text-center">Award</th>
                                                <th class="text-center">Lowest</th>
                                                <th class="text-center">Rank</th>
                                                <th class="text-center">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center">PLEASE SELECT FILTER</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 group-group">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="">Minimum grade requirement by subject </label>
                                            <input class="form-control form-control-sm" id="input_lowest_grade">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="">Base Grade</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="base_rounded" name="base_grade"
                                                    class="base_grade" value="1">
                                                <label for="base_rounded">
                                                    Rounded
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="base_decimal" name="base_grade"
                                                    class="base_grade" value="2">
                                                <label for="base_decimal">
                                                    Decimal
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary btn-sm" id="update_button_1">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mt-2">
                                        <div class="col-md-12" style="font-size:.8rem">
                                            <table class="table table-bordered table-sm" id="award_setup">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">Award</th>
                                                        <th width="15%" class="text-center">From</th>
                                                        <th width="15%" class="text-center">To</th>
                                                        <th width="5%" class="text-center"></th>
                                                        <th width="5%" class="text-center"></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
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
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    @include('principalsportal.pages.awards.awardsjs')

    <script>
        $(document).ready(function() {

            $(document).on('click', '#print_student_ranking', function() {


                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter = $('#quarter').val();
                var syid = $('#syid').val();
                var semid = $('#semester').val();
                var valid_filter = true

                if (gradelevel == '') {
                    Swal.fire({
                        type: 'info',
                        text: 'Please select a gradelevel!',
                        timer: 1500
                    });
                    return false;
                }

                var excluded = []

                $('.subj_list').each(function(a, b) {
                    if ($(b).prop('checked') == false) {
                        excluded.push($(b).attr('data-id'));
                    }
                })

                if (section == null) {
                    Swal.fire({
                        type: 'info',
                        title: 'Something went wrong!',
                        text: 'Please reload the page',
                        timer: 1500
                    });
                } else {
                    window.open("/grades/report/studentawards?gradelevel=" + gradelevel + "&section=" +
                        section + "&quarter=" + quarter + "&sy=" + syid + "&strand=" + $("#strand")
                        .val() + "&semid=" + semid + '&exclude=' + excluded, '_blank');
                }
            })

            //award setup
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })


            var all_setup = []
            var selected_id = []
            load_award_setup()
            get_list_award_setup()

            $(document).on('click', '#update_button_1', function() {
                update_award_setup_lowest()
            })

            $(document).on('change', '#syid', function() {
                get_list_award_setup()
            })

            $(document).on('click', '#to_award_setup_form_modal', function() {
                selected_id = null
                $('#input_award').val("")
                $('#input_gto').val("")
                $('#input_gfrom').val("")
                $('#award_setup_form_button').text('Create')
                $('#award_setup_form_button').removeClass('btn-success')
                $('#award_setup_form_button').addClass('btn-primary')
                $('#award_setup_form_modal').modal();
            })

            $(document).on('click', '.update_award_setup', function() {
                selected_id = $(this).attr('data-id')
                var temp_setup = all_setup.filter(x => x.id == selected_id)
                $('#input_award').val(temp_setup[0].award)
                $('#input_gto').val(temp_setup[0].gto)
                $('#input_gfrom').val(temp_setup[0].gfrom)
                $('#award_setup_form_button').text('Update')
                $('#award_setup_form_button').removeClass('btn-primary')
                $('#award_setup_form_button').addClass('btn-success')
                $('#award_setup_form_modal').modal();
            })

            $(document).on('click', '.delete_award_setup', function() {
                selected_id = $(this).attr('data-id')
                delete_award_setup()
            })

            $(document).on('click', '#award_setup_form_button', function() {
                (selected_id == null) ? create_award_setup(): update_award_setup();
            })

            function showToast(status, message) {
                Toast.fire({
                    type: (status == 1) ? 'success' : 'error',
                    title: message
                });
            }

            function update_award_setup_lowest() {
                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/update/lowest',
                    data: {
                        syid: $('#syid').val(),
                        gto: $('#input_lowest_grade').val(),
                        basegrade: $('input[name="base_grade"]:checked').val()
                    },
                    success: function(data) {
                        showToast(data[0].status, data[0].message);
                    },
                    error: () => showToast(1, 'Something went wrong.')
                })
            }


            function create_award_setup() {
                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/create',
                    data: {
                        syid: $('#syid').val(),
                        award: $('#input_award').val(),
                        gfrom: $('#input_gfrom').val(),
                        gto: $('#input_gto').val(),
                    },
                    success: data => {
                        (data[0].status == 1) && get_list_award_setup();
                        showToast(data[0].status, data[0].message);
                    },
                    error: () => showToast(1, 'Something went wrong.')
                })
            }

            function update_award_setup() {
                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/update',
                    data: {
                        id: selected_id,
                        syid: $('#syid').val(),
                        award: $('#input_award').val(),
                        gfrom: $('#input_gfrom').val(),
                        gto: $('#input_gto').val(),
                    },
                    success: data => {
                        (data[0].status == 1) && get_list_award_setup();
                        showToast(data[0].status, data[0].message);
                    },
                    error: () => showToast(1, 'Something went wrong.')
                })
            }

            function delete_award_setup() {
                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/delete',
                    data: {
                        id: selected_id,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            all_setup = all_setup.filter(x => x.id != selected_id)
                            load_award_setup()
                        }
                        showToast(data[0].status, data[0].message);
                    },
                    error: () => showToast(1, 'Something went wrong.')
                })
            }

            function get_list_award_setup() {

                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/list',
                    data: {
                        syid: $('#syid').val(),
                    },
                    success: function(data) {
                        all_setup = data.filter(x => x.award != 'lowest grade')
                        all_setup = all_setup.filter(x => x.award != 'base grade')
                        load_award_setup()

                        var lowest = data.filter(x => x.award == 'lowest grade')
                        if (lowest.length > 0) {
                            $('#input_lowest_grade').val(lowest[0].gto)
                        }

                        var base_setup = data.filter(x => x.award == 'base grade')
                        if (base_setup.length > 0) {
                            $('#base_decimal').prop('checked', base_setup[0].gto == 1);
                            $('#base_rounded').prop('checked', base_setup[0].gto !== 1);
                        } else {
                            $('#base_decimal').prop('checked', false);
                            $('#base_rounded').prop('checked', true);
                        }
                    }
                })

            }

            function load_award_setup() {

                $("#award_setup").DataTable({
                    destroy: true,
                    bInfo: false,
                    bLengthChange: false,
                    bPaginate: false,
                    data: all_setup,
                    order: [
                        [1, "asc"]
                    ],
                    "columns": [{
                            "data": "award"
                        },
                        {
                            "data": "gfrom"
                        },
                        {
                            "data": "gto"
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }

                    ],
                    columnDefs: [{
                            'targets': [1, 2],
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('text-center')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="update_award_setup" data-id="' +
                                    rowData.id + '"><i class="far fa-edit"></i></a>';
                                $(td)[0].innerHTML = buttons
                                $(td).addClass('text-center')
                                $(td).addClass('align-middle')

                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a href="javascript:void(0)" ' + disabled +
                                    ' class="delete_award_setup" data-id="' + rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons
                                $(td).addClass('text-center')
                                $(td).addClass('align-middle')
                            }
                        },
                    ]
                });

                var label_text = $($('#award_setup_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML =
                    '<button style="font-size: .8rem !important" class="btn btn-sm btn-primary" id="to_award_setup_form_modal"><i class="fas fa-plus"></i> Add Setup</button>'

            }


        })
    </script>
@endsection
