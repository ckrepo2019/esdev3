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
        if ($item->acadprogid != 6 && $item->acadprogid != 8) {
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
                            <label for="">Select Level</label>
                            <select name="" class="form-control select2" id="selectLevelSetupAward"
                                style="width:100%;">
                                <option value="">Select Level </option>
                                @foreach (DB::table('gradelevel')->orderBy('sortid')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}"> {{ $item->levelname }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Award</label>
                            <input id="input_award" class="form-control form-control-sm" placeholder="Award Description">
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

    <div class="modal fade" id="basegrade_setup_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Minimum & Base Grade Setup Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Select Level</label>
                            <select name="" class="form-control select2" id="selectLevelSetupEdit"
                                style="width:100%;">
                                <option value="">Select Level </option>
                                @foreach (DB::table('gradelevel')->orderBy('sortid')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}"> {{ $item->levelname }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Minimum Grade</label>
                            <input id="input_lowest_gradeedit" type="number" class="form-control form-control-sm"
                                placeholder="Minimum Grade">
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
                                <input type="radio" id="base_rounded2" name="base_gradeedit" class="base_grade"
                                    value="1">
                                <label for="base_rounded2">
                                    Rounded
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="base_decimal2" name="base_gradeedit" class="base_grade"
                                    value="2">
                                <label for="base_decimal2">
                                    Decimal
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" id="update_button_1" hidden>Update</button>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="basegrade_setup_form_button">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="basegrade_edit_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Edit Minimum & Base Grade</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Select Level</label>
                            <select name="" class="form-control select2" id="selectLevelSetupEdit2"
                                style="width:100%;">
                                <option value="">Select Level</option>
                                @foreach (DB::table('gradelevel')->orderBy('sortid')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}"> {{ $item->levelname }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Minimum Grade</label>
                            <input id="input_lowest_grade_edit" type="number" class="form-control form-control-sm"
                                placeholder="Minimum Grade">
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
                                <input type="radio" id="base_rounded_edit" name="base_grade_edit" class="base_grade"
                                    value="1">
                                <label for="base_rounded_edit">
                                    Rounded
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="base_decimal_edit" name="base_grade_edit" class="base_grade"
                                    value="2">
                                <label for="base_decimal_edit">
                                    Decimal
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="basegrade_edit_form_button">Update</button>
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
            {{-- {{ dd(session()->all()) }} --}}

            <div class="row ">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" class="mb-1">Grade Level</label>

                                        <select class="form-control select2" id="gradelevel" style="width:100%;">
                                            <option selected value="">Select Grade Level</option>
                                            @foreach ($all_acad as $item)
                                                @php
                                                    $gradelevel = DB::table('gradelevel')
                                                        ->where('acadprogid', $item)
                                                        ->orderBy('sortid')
                                                        ->where('deleted', 0)
                                                        ->select('id', 'levelname')
                                                        ->get();
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
                                        <select name="section" id="section" class="form-control select2"
                                            style="width:100%;">
                                            {{-- <option selected value="" >Select Section</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group strand_holder" hidden id="starnd_holder">
                                        <label class="mb-1">Strand</label>
                                        <select name="strand" id="strand" class="form-control select2"
                                            style="width:100%;">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-2">
                                    <label class="mb-1">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2"
                                        style="width:100%;">
                                        @foreach (DB::table('sy')->select('id', 'sydesc', 'isactive')->orderBy('sydesc')->get() as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->sydesc }}
                                                </option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="mb-1">Semester</label>
                                    <select name="semester" id="semester" class="form-control select2"
                                        style="width:100%;">
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

                                    <select name="quarter" id="quarter" class="form-control select2"
                                        style="width:100%;">
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
                                    <div class="table-responsive">
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
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <strong> Minimum Grade & Award Setup</strong>
                        </div>
                        <div class="card-body">
                            {{-- <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="">Select Level</label>
                                    <select name="" class="form-control select2" id="selectLevelSetup"
                                        style="width:100%;">
                                        <option value="">Select Setup </option>
                                        @foreach (DB::table('gradelevel')->orderBy('sortid')->where('deleted', 0)->get() as $item)
                                            <option value="{{ $item->id }}"> {{ $item->levelname }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-4 group-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button class="btn btn-sm btn-primary btn_add_mg_setup"
                                                style="font-size: .8rem !important; white-space: nowrap;">
                                                + Add MG & BG Setup
                                            </button>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <a href="#">
                                                <i class="fas fa-edit text-primary edit_mgsetup" hidden></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="">Minimum grade requirement by subject </label>
                                            <input class="form-control form-control-sm" id="input_lowest_grade" disabled>
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
                                                    class="base_grade" value="1" disabled>
                                                <label for="base_rounded">
                                                    Rounded
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="base_decimal" name="base_grade"
                                                    class="base_grade" value="2" disabled>
                                                <label for="base_decimal">
                                                    Decimal
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary btn-sm" id="update_button_1"
                                                hidden>Update</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mt-2">
                                        <div class="col-md-12" style="font-size:.8rem">
                                            <div class="table-responsive">
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
        </div>
    </section>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    @include('principalsportal.pages.awards.awardsjs')

    @if (session('error'))
        <script>
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
                stopKeydownPropagation: false
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {

            $('#basegrade_edit_form_button').on('click', function() {
                // AJAX request to update the lowest grade
                let basegrade = $('input[name="base_grade_edit"]:checked').val();
                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/update/lowest',
                    data: {
                        syid: $('#syid').val(),
                        levelid: $('#gradelevel').val(),
                        gto: $('#input_lowest_grade_edit').val(),
                        basegrade: basegrade
                    },
                    success: function(data) {
                        console.log(data);
                        $('#input_lowest_grade').val(data[0].setup[0].gto)

                        var gfrom = data[0].setup[1].gfrom
                        var gto = data[0].setup[1].gto


                        if (gfrom) {
                            $('#base_rounded').prop('checked', true);
                        } else {
                            $('#base_decimal').prop('checked', true);
                        }
                        // Show a success or error toast based on the response
                        showToast(data[0].status, data[0].message);
                    },
                    error: function() {
                        showToast('error', 'Something went wrong.');
                    }
                });
            })

            $('.edit_mgsetup').on('click', function() {

                var lowestgrade = $('#input_lowest_grade').val()
                let basegrade = $('input[name="base_grade"]:checked').val();

                $('#input_lowest_grade_edit').val(lowestgrade)
                if (basegrade == 1) {
                    $('#base_rounded_edit').prop('checked', true);
                } else if (basegrade == 2) {
                    $('#base_decimal_edit').prop('checked', true);
                }


                $('#basegrade_edit_form_modal').modal()


            })

            $('.btn_add_mg_setup').on('click', function() {
                $('#basegrade_setup_form_modal').modal();
            })

            $('#basegrade_setup_form_button').on('click', function() {

                // Validate the level setup selection
                if (!$('#selectLevelSetupEdit').val()) {
                    showToast('error', 'Level is required');
                    $('#selectLevelSetupEdit').addClass('is-invalid');
                    return;
                } else {
                    $('#selectLevelSetupEdit').removeClass('is-invalid');
                }
                // Validate the minimum grade input
                if (!$('#input_lowest_gradeedit').val()) {
                    showToast('error', 'Minimum grade is required');
                    $('#input_lowest_gradeedit').addClass('is-invalid'); // Use Bootstrap's is-invalid class
                    return;
                } else {
                    $('#input_lowest_gradeedit').removeClass(
                        'is-invalid'); // Remove the invalid class if valid
                }


                // Get the selected base grade radio button (if any)
                let basegrade = $('input[name="base_gradeedit"]:checked').val();
                if (!basegrade) {
                    showToast('error', 'Base grade is required');
                    return;
                }

                // AJAX request to update the lowest grade
                $.ajax({
                    type: 'GET',
                    url: '/awarsetup/update/lowest',
                    data: {
                        syid: $('#syid').val(),
                        levelid: $('#selectLevelSetupEdit').val(),
                        gto: $('#input_lowest_gradeedit').val(),
                        basegrade: basegrade
                    },
                    success: function(data) {
                        // Show a success or error toast based on the response
                        showToast(data[0].status, data[0].message);
                    },
                    error: function() {
                        showToast('error', 'Something went wrong.');
                    }
                });
            });


            // $('.base_grade').on('change', function() {
            //     console.log('slkdjfksdjf...');

            //     var base_grade = $('input[name=base_grade]:checked').val();
            //     if (base_grade == 1) {
            //         $('#input_gfrom').removeAttr('placeholder');
            //         $('#input_gto').removeAttr('placeholder');
            //     } else {
            //         $('#input_gfrom').attr('placeholder', '0.00');
            //         $('#input_gto').attr('placeholder', '0.00');
            //     }
            // });

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
                check_decimal()

            })

            $('#gradelevel').on('change', function() {
                get_list_award_setup();
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

            function check_decimal() {
                if ($('input[name="base_grade"]:checked').val()) {
                    if ($('input[name="base_grade"]:checked').val() == 2) {
                        if ($('#input_gfrom').val().indexOf('.') === -1 || $('#input_gto').val().indexOf('.') === -
                            1) {
                            $('#input_gfrom').css('border', '1px solid red')
                            $('#input_gto').css('border', '1px solid red')
                            Toast.fire({
                                type: 'error',
                                title: 'Please enter a decimal number!'
                            })
                        } else {
                            (selected_id == null) ? create_award_setup(): update_award_setup();
                            $('#input_gfrom').removeAttr('style')
                            $('#input_gto').removeAttr('style')
                        }
                    } else {
                        (selected_id == null) ? create_award_setup(): update_award_setup();
                    }
                } else {
                    Swal.fire({
                        type: 'info',
                        text: 'Please add Base Grade first!',
                        timer: 1500
                    });
                }
            }

            function create_award_setup() {
                if ($('#input_award').val() == "" || $('#input_gfrom').val() == "" || $('#input_gto').val() == "" ||
                    $('#selectLevelSetupAward').val() == "") {
                    $('#input_gfrom').css('border', '1px solid red')
                    $('#input_gto').css('border', '1px solid red')
                    $('#input_award').css('border', '1px solid red')
                    $('#selectLevelSetupAward').css('border', '1px solid red')
                    Toast.fire({
                        type: 'error',
                        title: 'Please fill in all required fields!'
                    })
                } else {
                    $('#input_gfrom').removeAttr('style')
                    $('#input_gto').removeAttr('style')
                    $('#input_award').removeAttr('style')
                    $('#selectLevelSetupAward').removeAttr('style')
                    $.ajax({
                        type: 'GET',
                        url: '/awarsetup/create',
                        data: {
                            syid: $('#syid').val(),
                            award: $('#input_award').val(),
                            gfrom: $('#input_gfrom').val(),
                            gto: $('#input_gto').val(),
                            levelid: $('#selectLevelSetupAward').val(),
                        },
                        success: data => {
                            if (data[0].status == 2) {
                                showToast('error', data[0].message);
                                return
                            }
                            (data[0].status == 1) && get_list_award_setup();
                            showToast(data[0].status, data[0].message);
                        },
                        error: () => showToast(1, 'Something went wrong.')
                    })
                }


            }

            function update_award_setup() {
                if ($('#input_award').val() == "" || $('#input_gfrom').val() == "" || $('#input_gto').val() == "") {
                    $('#input_gfrom').css('border', '1px solid red')
                    $('#input_gto').css('border', '1px solid red')
                    $('#input_award').css('border', '1px solid red')
                    Toast.fire({
                        type: 'error',
                        title: 'Please fill in all required fields!'
                    })
                } else {
                    $('#input_gfrom').removeAttr('style')
                    $('#input_gto').removeAttr('style')
                    $('#input_award').removeAttr('style')
                    $.ajax({
                        type: 'GET',
                        url: '/awarsetup/update',
                        data: {
                            id: selected_id,
                            syid: $('#syid').val(),
                            award: $('#input_award').val(),
                            gfrom: $('#input_gfrom').val(),
                            gto: $('#input_gto').val(),
                            levelid: $('#selectLevelSetupAward').val(),
                        },
                        success: data => {
                            (data[0].status == 1) && get_list_award_setup();
                            showToast(data[0].status, data[0].message);
                        },
                        error: () => showToast(1, 'Something went wrong.')
                    })
                }
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
                        levelid: $('#gradelevel').val()
                    },
                    success: function(data) {
                        console.log('AWARD SETUP', data);

                        if (data.length <= 0) {
                            Toast.fire({
                                type: 'warning',
                                title: 'No Available Setup yet!'
                            });
                        }

                        all_setup = data.filter(x => x.award != 'lowest grade')
                        all_setup = all_setup.filter(x => x.award != 'base grade')
                        load_award_setup()

                        var lowest = data.filter(x => x.award == 'lowest grade')
                        if (lowest.length > 0) {
                            $('#input_lowest_grade').val(lowest[0].gto)
                            $('.edit_mgsetup').prop('hidden', false)
                        } else {
                            $('#input_lowest_grade').val('')
                        }

                        var base_setup = data.filter(x => x.award == 'base grade')
                        if (base_setup.length > 0) {
                            // console.log('has rounded');
                            $('.edit_mgsetup').prop('hidden', false)
                            $('#base_decimal').prop('checked', base_setup[0].gto == 1).trigger(
                                'change');
                            $('#base_rounded').prop('checked', base_setup[0].gto !== 1).trigger(
                                'change');
                        } else {
                            $('#base_decimal').prop('checked', false);
                            $('#base_rounded').prop('checked', false);
                        }
                    }
                })

            }

            $('#award_setup_form_modal').on('hidden.bs.modal', function() {
                $('#input_award').val('')
                $('#input_gfrom').val('')
                $('#input_gto').val('')
                $('#input_award').removeAttr('style')
                $('#input_gfrom').removeAttr('style')
                $('#input_gto').removeAttr('style')
            })

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
                    '<button style="font-size: .8rem !important" class="btn btn-sm btn-primary" id="to_award_setup_form_modal"><i class="fas fa-plus"></i> Add Award Setup</button>'

            }


        })
    </script>
@endsection
