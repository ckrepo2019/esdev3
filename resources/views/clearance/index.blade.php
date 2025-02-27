@php
    $sy = DB::table('sy')->orderBy('sydesc','desc')->get();

    $getactivesy = DB::table('sy')->where('isactive','1')->get();

    $acadprog = DB::table('academicprogram')->orderBy('id', 'asc')->get();
    
    $clearance_acadterm = DB::table('clearance_acadterm')->orderBy('id', 'asc')->where('deleted',0)->where('isactive',0)->get();
@endphp
@extends($extends)
@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

@section('content')
<style>
    .required:after {
        content:" *";
        color: red;
    }
    .form-check-inline .form-check-input {
    width: 1em;
    height: 1em;
    }
    .form-check-inline .form-check-label {
        margin-left: 0.5em;
    }
    .is-valid + .select2-container .select2-selection {
        border-color: #4CAF50;
    }

    .is-invalid + .select2-container .select2-selection {
        border-color: #F44336;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2.select2-container .select2-selection--multiple {
    height: auto;
    min-height: 18px;
    }

    .select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
    margin-top: 0;
    height: 18px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__rendered {
    display: block;
    padding: 0 4px;
    line-height: 29px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice {
    background-color: #6c757d;
    border: 1px solid #343a40;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin: 4px 4px 0 0;
    padding: 0 6px 0 22px;
    height: 24px;
    line-height: 24px;
    font-size: 12px;
    position: relative;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
    position: absolute;
    top: 0;
    left: 0;
    height: 22px;
    width: 22px;
    margin: 0;
    text-align: center;
    color: #f8f9fa;
    font-weight: bold;
    font-size: 14px;
    }

    @media (max-width: 576px) {
        .table-responsive {
            overflow-x: auto;
        }
    }

    @media screen and (max-width: 767px) {
        .table-responsive table td:nth-child(3), table th:nth-child(3),
        .table-responsive table td:nth-child(4), table th:nth-child(4),
        .table-responsive table td:nth-child(5), table th:nth-child(5) {
            display: none;
        }
    }

    /* For screens smaller than 768px (e.g., mobile devices) */
    @media (max-width: 767.98px) {
        .btn_addsignatory {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
        }

        #table_signatories_filter, #table_signatories_info, #table_signatories_paginate{
            display: flex;
            justify-content: flex-end;
        }

        .hide-border {
            border-right: none !important;
            border-left: none !important;
        }
    }

    .checkbox-container {
        position: relative;
    }

    .align-end {
        position: absolute;
        top: 0;
        right: 0;
    }

    #select_sy + .select2-container .select2-selection__arrow {
        display: none;
    }

</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Signatories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Clearance</li>
                    <li class="breadcrumb-item active">Signatories</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row mb-2">
                    <label><i class="fa fa-filter"></i>Filter</label>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>School Year</label>
                        <select class="form-control select2 form-control-sm" id="filter_sy">
                            @foreach(DB::table('sy')->get() as $eachsy)
                                <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Academic Prog.</label>
                        <select class="form-control select2 form-control-sm filter_acadprogid" id="filter_acadprogid">
                            <option value=""></option>
                            @foreach(DB::table('academicprogram')->get() as $eachacadprog)
                                <option value="{{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Academic Term</label>
                        <select class="form-control select2 form-control-sm filter_acadterm" id="filter_acadterm"></select>
                    </div>
                </div>
                {{-- <div class="row"> // For further discussion and planning 
                    <div class="col-md-4">
                        <label class="required">Department Selection</label>
                        <select class="form-control select2 form-control-sm" id="select-formid">
                            <option value="0" selected>All</option>
                            <option value="1">School Registrar</option>
                            <option value="2">Finance Office</option>
                            <option value="3">School Principal</option>
                        </select>
                    </div>
                </div> --}}
                <div class="row mt-3">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-getsignatories"><i class="fa fa-sync"></i> Get Signatories</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow" id="signatory_view" hidden>
            <div class="card-header bg-secondary">
                <div class="row">
                    <div class="col-md-6">
                        <label class="card-title" style="font-size: 1.5rem !important;">Signatories</label>
                    </div>
                    <div class="col-md-6 text-right"> 
                        <button type="button" class="btn btn-primary btn-sm set_clearance btn_disabled" hidden id="set_clearance"><i class="far fa-check-circle"></i> Teacher Signatory</button>
                    </div> 
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-sm table-bordered table-hover p-0" id="table_signatories" width="100%" style="font-size:14px !important">
                                <thead>
                                    <tr style="font-size: 1rem !important;">
                                        <th width="15%">Title</th>
                                        <th width="20%">Name</th>
                                        <th width="15%">Department</th>
                                        <th width="20%">Academic Program</th>
                                        <th width="15%">Academic Term</th>
                                        <th width="5%"></th>
                                        <th width="5%"></th>
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
{{-- Modal add signatory --}}
<div class="modal fade" id="modal_addsignatories" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <label class="modal-title">Add Signatory</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="" class="required">Title</label>
                        <input id="input_title" class="form-control form-control-sm" placeholder="E.g School Principal" oninput="this.value = this.value.replace(/[^a-zA-Z0-9-_. ]/g, '')" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                        <span class="invalid-feedback" id="span_input_title" role="alert">
                            <strong>Title is required. </strong>
                        </span>
                        <ul id="same_term" class="mb-0" ></ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="" class="required">Name</label>
                        <select class="form-control select2 form-control-sm select_name" id="select_name">
                            <option value="" selected disabled>Select Teacher</option>
                            @foreach(DB::table('teacher')->orderBy('lastname', 'asc')->where('deleted',0)->get() as $eachteacher)
                                <option value="{{$eachteacher->id}}">{{$eachteacher->firstname}} {{$eachteacher->lastname}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" id="span_select_name" role="alert">
                            <strong>Name is required. </strong>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="" class="required">Department</label>
                        <input id="input_department" class="form-control form-control-sm input_department" placeholder="E.g Principal Office" oninput="this.value = this.value.replace(/[^a-zA-Z0-9-_. ]/g, '')" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                        <span class="invalid-feedback" id="span_input_department" role="alert">
                            <strong> Department is required. </strong>
                        </span>
                        <ul id="same_term" class="mb-0" ></ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="" class="required">Academic Term</label>
                        <select class="form-control select2 form-control-sm select_acadterm" id="select_acadterm">
                            <option value="" selected disabled>Select Academic Term</option>
                            @foreach($clearance_acadterm as $eachclearance_acadterm)
                                <option value="{{$eachclearance_acadterm->id}}">{{$eachclearance_acadterm->term}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" id="span_select_acadterm" role="alert">
                            <strong>Academic Term is required. </strong>
                        </span> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div>
                            <label for="" class="required">Academic Program</label>
                            <label class="align-end mr-2" > <input type="checkbox" id="selectAllCheckbox"> All </label>
                        </div>
                        <select class="form-control select2 form-control-sm multiple-select" multiple="multiple" id="select_acadprog"></select>
                        <span class="invalid-feedback" id="span_select_acadprog" role="alert">
                            <strong> Academic Program is required. </strong>
                        </span> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span id="btn_addsingatory_modal"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Modal Set-Up Clearance--}}
<div class="modal fade" id="modal_clearance_setup" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title">Set-Up Teacher Signatory</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                <div class="row">
                    {{-- <div class="col-md-6 border-right hide-border">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>School Year</label>
                                <select class="form-control select2 form-control-sm" id="select_sy" disabled>
                                    @foreach($getactivesy as $eachsy)
                                        <option value="{{$eachsy->id}}" selected>{{$eachsy->sydesc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <table class="table table-striped table-sm table-bordered table-hover" id="table_acadterm" width="100%" style="font-size:14px !important">
                                    <thead>
                                        <tr>
                                            <th width="75%">Academic Term</th>
                                            <th width="25%"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div> 
                        </div>
                    </div> --}}
                    <div class="col-md-12">
                        <div class="row pl-2">
                            {{-- <div class="col-md-6 form-group">
                                    <label>Academic Prog.</label>
                                    <select class="form-control select2 form-control-sm" id="filter_enable_acadprogid">
                                        @foreach(DB::table('academicprogram')->get() as $eachacadprog)
                                            <option value="{{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</option>
                                        @endforeach
                                    </select>
                            </div> --}}
                            <div class="col-md-12  form-group">
                                <label>Academic Term</label>
                                <select class="form-control select2 form-control-sm filter_enable_acadterm" id="filter_enable_acadterm"></select>
                            </div>
                        </div>
                        {{-- <div class="row pl-2">
                            <div class="col-md-6 form-group">
                                <label id="label_enable_teacher">Subject Teacher</label>
                                <span id="btn_enable_teacher"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label id="label_enable_adviser" hidden>Class Adviser</label>
                                <span id="btn_enable_adviser"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm enable_teacher" id="get_enable_status" disabled><i class="fa fa-sync"></i> Get Status</button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table class="table table-striped table-sm table-bordered table-hover p-0" id="table_teacher_enabled" width="100%" style="font-size:14px !important">
                            <thead>
                                <tr style="font-size: 1rem !important;">
                                    <th width="60%">Academic Program</th>
                                    <th class="text-center" width="20%">Subject Teacher</th>
                                    <th class="text-center" width="20%">Class Adviser</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div> --}}
                    {{-- <div class="col-md-8 text-right"> --}}
                        {{-- <button type="button" class="btn btn-primary btn-sm enable_teacher" id="get_enable_status"><i class="fa fa-sync"></i> Get Status</button> --}}
                        {{-- <button type="button" class="btn btn-primary btn-sm set_clearance" hidden id="set_clearance"><i class="fas fa-user-check"></i> Set-Up Clearance</button> --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
{{-- Modal Set-Up Clearance -close --}} 
@endsection

@section('footerjavascript')

<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    var selectedid = null;
    var status = null;
    var clearance_type = null;
    var sy = @json($sy);
    var academicprog = null;
    var acadprogid = null;
    var selectedstatus = null;
    var cler_acadprogid = null;

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })

    $(document).ready(function(){
        $('#input_inputperiod').daterangepicker()

        $('.select2').select2()

        $('#filter_acadprogid').select2({
            placeholder: 'All',
            allowClear: true
        })

        $('#filter_acadterm').select2({
            allowClear: true,
            placeholder: 'All'
        })

        $('#selectAllCheckbox').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('#select_acadprog').find('option').prop('selected', isChecked);
            $('#select_acadprog').trigger('change');
        });

        academicprog_list()

        $(document).on('click','#btn-getsignatories', function(){
            signatory_list()
        })

        $(document).on('change','#filter_sy', function(){
            $('#signatory_view').attr('hidden',true)
            getacadterm()
        })

        $(document).on('change','#filter_acadprogid', function(){
            getacadterm()
            $('#filter_acadterm').removeClass('is-invalid').removeClass('is-valid')
        })

        // $(document).on('click', '#get_enable_status', function(){
        //     if($('#filter_enable_acadterm').val() == null){
        //         $('#filter_enable_acadterm').addClass('is-invalid')
        //         $('#get_enable_status').attr('disabled', true)
        //     }else{
        //         $('#filter_enable_acadterm').removeClass('is-invalid')
        //         get_adviserstatus()
        //         get_subteacherstatus()
        //     }
        // })

        // $(document).on('change', '#filter_enable_acadprogid', function(){
        //     $('#filter_enable_acadterm').removeClass('is-invalid')
        //     $('#get_enable_status').attr('disabled', false)
        //     getsetupacadterm()
        //     get_adviserstatus()
        //     get_subteacherstatus()
        // })

        $(document).on('change', '#filter_enable_acadterm', function(){
            getenable()
        })

        $(document).on('change', '#select_acadterm', function(){
            getacadprog();
            // $('#select_acadprog').removeClass('is-valid').removeClass('is-invalid')
            $("#selectAllCheckbox").prop("checked", false);
        })

        function systatus(){
            $.ajax({
                type:'GET',
                url: '/setup/student/clearance/systatus',
                data:{
                    syid:$('#filter_sy').val(),
                },
                success:function(data) {
                    getsy = data
                    if(getsy[0].ended == 1){
                        $(".btn_disabled").prop("hidden", true);
                        $(".syprompt").prop("hidden", false);
                    }else{
                        $(".btn_disabled").prop("hidden", false);
                        $(".syprompt").prop("hidden", true);
                    }
                }
            })
        }

        function signatory_list(){
            systatus()
            $.ajax({
                type:'GET',
                url: '/setup/student/clearance/signatory/list',
                data:{
                    syid:$('#filter_sy').val(),
                    acadterm:$('#filter_acadterm').val(),
                    acadprogid:$('#filter_acadprogid').val()
                },
                success:function(data) {
                    signatories = data
                    signatoriestable()
                    $('#filter_acadterm').removeClass('is-invalid').removeClass('is-valid')
                    $('#signatory_view').attr('hidden',false)
                }
            })
        }

        function academicprog_list(){
            $.ajax({
                type:'GET',
                url: '/setup/student/clearance/signatory/acadprog',
                data:{
                    syid:$('#filter_sy').val(),
                },
                success:function(data) {
                    academicprog = data
                    signatory_list()
                }
            })
        }

        function signatoriestable(){
            var selectedsy = $('#filter_sy').val()
            var selectedinfo = sy.filter(x=>x.id == selectedsy)[0]

            $("#table_signatories").DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                destroy: true,
                data:signatories,
                lengthChange: false,
                stateSave: true,
                sort:false,
                columns: [
                    { "data": "title" },
                    { "data": "firstname" },
                    { "data": "lastname" },
                    { "data": "departmentid" },
                    { "data": "term" },
                    { "data": null },
                    { "data": null },
                ],
                columnDefs:[
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = rowData.title

                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var firstLetter = rowData.middlename != null ? rowData.middlename.charAt(0) + '.' : ' '
                            var text = rowData.firstname +' '+ firstLetter +' '+ rowData.lastname

                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = rowData.departmentid

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var acadprogs = rowData.acadprogid
                            console.log(acadprogs)
                            var selectedacadprogid = acadprogs.split(',').map(item => item.trim());
                            console.log(selectedacadprogid)
                            var text = "" 

                            selectedacadprogid.forEach(function(item) {
                                var cleanstring = item.replace(/[\[\],"']+/g,'');
                                console.log(cleanstring)
                                var acad_info = academicprog.find(x => x.id == cleanstring);
                                console.log(acad_info)
                                if(acad_info) {
                                    var progname = acad_info.progname
                                    text += '<span class="badge badge-pill badge-info ml-1 mb-1 font-weight-normal" style="font-size: .9rem;">'+progname+'</span> '
                                }
                            })

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                            // var acadprogs = rowData.acadprogid
                            // console.log(acadprogs)
                            // var selectedacadprogid = acadprogs.split(',')
                            // console.log(selectedacadprogid)
                            // var text = "" 

                            // selectedacadprogid.forEach(function(item) {
                            //     var cleanstring = item.replace(/[\[\],"']+/g,'')
                            //     console.log(cleanstring)
                            //     var acad_info = academicprog.filter(x=>x.id == cleanstring)[0]
                            //     console.log(acad_info)
                            //     var progname = acad_info.progname
                            //     text += '<span class="badge badge-pill badge-info ml-1 mb-1 font-weight-normal" style="font-size: .9rem;">'+progname+'</span> '
                            // })

                            // $(td)[0].innerHTML = text
                            // $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = rowData.term

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if(selectedinfo.ended != 1){
                                var buttons = '<a href="#" class="edit_signatory" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                            }else{
                                var buttons = ''
                            }

                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if(selectedinfo.ended != 1){
                                var buttons = '<a href="#" class="remove_signatory" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                            }else{
                                var buttons = ''
                            }
                            
                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                ],
            });
            var label_text = ''
            var label_text = $($("#table_signatories_wrapper")[0].children[0])[0].children[0]

            if(selectedinfo.ended != 1){
                var button = `<div class="row" >
                            <div class="col-md-12 btn_addsignatory" >
                                <button type="button" class="btn btn-sm btn-primary add-signatory" id="add-signatory" style="font-size: 1rem !important">
                                    <i class="fa fa-plus"></i> Signatory
                                </button>
                            </div>
                        </div>`
            }else{
                var button = `<div class="col-md-6">
                                <span class="syprompt text-danger" >Note: School Year has ended</span>
                            </div>`
            }

            $(label_text)[0].innerHTML = button

        }

        $(document).on('click','#add-signatory', function(){
            $('#btn_addsingatory_modal').empty()
            $('#btn_addsingatory_modal').append('<button class="btn btn-primary btn-sm" id="save_signatory"><i class="fas fa-save"></i>&nbspAdd</button>')
            request = 'SIGNATORY' // use to trigger the append options to select_acadprog which is in the modal below
            $('#save_signatory').removeAttr('disabled')
            $(':input').removeClass('is-valid').removeClass('is-invalid')
            $('#input_title').val("")
            $('#select_name').val("").change().removeClass('is-valid').removeClass('is-invalid')
            $('#input_department').val("")
            $('#select_acadterm').val("").change().removeClass('is-valid').removeClass('is-invalid')
            $('#select_acadprog').val("").change().removeClass('is-valid').removeClass('is-invalid')
            $('#selectAllCheckbox').prop('checked', false)
            $('#modal_addsignatories').modal()
        })

        dynamic_validate('#input_title', '#save_signatory', function(isValid) {
            return isValid
        })
        dynamic_validate('#select_name', '#save_signatory', function(isValid) {
            return isValid
        })
        dynamic_validate('#input_department', '#save_signatory', function(isValid) {
            return isValid
        })
        dynamic_validate('#select_acadterm', '#save_signatory', function(isValid) {
            return isValid
        })
        dynamic_validate('#select_acadprog', '#save_signatory', function(isValid) {
            return isValid
        })

        // //Clearance Set-up
        // dynamic_validate('#select_setup_acadterm', '#save_clearance_setup', function(isValid) {
        //     return isValid
        // })
        // dynamic_validate('#select_setup_acadprog', '#save_clearance_setup', function(isValid) {
        //     return isValid
        // })

        //dynamic validation
        function dynamic_validate(inputSel, btnSel, callback) {

            function validateInput(input, select) {
                if(input.val() == "") {
                    // disable add button when error input
                    $(btnSel).prop("disabled", true);
                    input.removeClass("is-valid").addClass("is-invalid");
                }else{
                    $(btnSel).prop("disabled", false);
                    input.removeClass("is-invalid").addClass("is-valid");
                }
            }

            // need to finish typing before validating
            $(inputSel).on("input", (e) => {
                var isValid = validateInput($(inputSel));
                // return the true if valid false if not to callback
                callback(isValid);
            });

            // need to finish typing before validating
            $(inputSel).on("change", (e) => {
                var isValid = validateInput($(inputSel));
                // return the true if valid false if not to callback
                callback(isValid);
            });
        }

        function dynamic_validate_reset(selectors) {
            selectors.forEach(function(selector) {
                $(selector).removeClass('is-valid')
                $(selector).removeClass('is-invalid');
            });
        }

        $(document).on('click','#save_signatory', function(){
            $('#save_signatory').attr('disabled', 'disabled');
            var valid_input = true
            var title = $('#input_title').val();
            var name = $('#select_name').val();
            var department = $('#input_department').val();
            var acadprog = $('#select_acadprog').val();
            var acadterm = $('#select_acadterm').val();

            var signatory_info = signatories.filter(x => x.teacherid == name && x.id != selectedid)[0];
            if(signatory_info == null){
                var checktitle = " "
                var checkname = " "
                var checkacadterm = " "
            }else{
                var checktitle = signatory_info.title
                var checkname = signatory_info.teacherid
                var checkacadterm = signatory_info.termid
            }

            if(title == "" ){
                $("#span_input_title").empty()
                $('#input_title').removeClass('is-valid').addClass('is-invalid')
                $("#span_input_title").append("<strong>Title is required. </strong>")
                valid_input = false
            }
            // else{
            //     $('#input_title').removeClass('is-invalid').addClass('is-valid')
            //     $('#save_signatory').removeAttr('disabled')
            // }

            if(name == null ){
                $("#span_select_name").empty()
                $('#select_name').removeClass('is-valid').addClass('is-invalid')
                $("#span_select_name").append("<strong>Name is required. </strong>")
                valid_input = false
            }
            // else{
            //     $('#select_name').removeClass('is-invalid').addClass('is-valid')
            //     $('#save_signatory').removeAttr('disabled')
            // }

            if(department == "" ){
                $('#input_department').removeClass('is-valid').addClass('is-invalid');
                valid_input = false
            }
            // else{
            //     $('#input_department').removeClass('is-invalid').addClass('is-valid')
            //     $('#save_signatory').removeAttr('disabled')
            // }

            if(acadprog == "" ){
                $('#select_acadprog').removeClass('is-valid').addClass('is-invalid');
                valid_input = false
            }
            // else{
            //     $('#select_acadprog').removeClass('is-invalid').addClass('is-valid')
            //     $('#save_signatory').removeAttr('disabled')
            // }

            if(acadterm == null ){
                $("#span_select_acadterm").empty()
                $('#select_acadterm').removeClass('is-valid').addClass('is-invalid');
                $("#span_select_acadterm").append("<strong>Academic Term is required. </strong>")
                valid_input = false
            }
            // else{
            //     $('#select_acadterm').removeClass('is-invalid').addClass('is-valid')
            //     $('#save_signatory').removeAttr('disabled')
            // }

            if(title == checktitle && name == checkname && acadterm == checkacadterm){
                $("#span_input_title").empty()
                $("#span_select_name").empty()
                $("#span_select_acadterm").empty()

                $('#input_title').removeClass('is-valid').addClass('is-invalid')
                $("#span_input_title").append("<strong>Title, Name, & Academic Term is already registered. Please update! </strong>")

                $('#select_name').removeClass('is-valid').addClass('is-invalid')
                $("#span_select_name").append("<strong>Title, Name, & Academic Term is already registered. Please update! </strong>")

                $('#select_acadterm').removeClass('is-valid').addClass('is-invalid');
                $("#span_select_acadterm").append("<strong>Title, Name, & Academic Term is already registered. Please update! </strong>")
                valid_input = false
            }

            var url = '/setup/student/clearance/save'

            if(selectedid == null){
                $('#save_signatory').attr('disabled', 'disabled')
                if(valid_input){
                    $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                            title: title,
                            name: name,
                            department: department,
                            acadprog: acadprog,
                            acadterm: acadterm,
                            syid: $('#filter_sy').val(),
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Added Successfully!'
                                })
                                $('#modal_addsignatories').modal('hide')
                                signatory_list()
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].data
                                })
                            }
                        },
                        error:function(){
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                }
            }else{
                $('#save_signatory').attr('disabled', 'disabled')
                if(valid_input){
                    $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                            selectedid: selectedid,
                            title: title,
                            name: name,
                            department: department,
                            acadprog: acadprog,
                            acadterm: acadterm,
                            syid: $('#filter_sy').val(),
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated Successfully!'
                                })
                                $('#modal_addsignatories').modal('hide')
                                selectedid = ""
                                signatory_list()
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].data
                                })
                            }
                        },
                        error:function(){
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                }
            }
        })

        $(document).on('click','.remove_signatory',function(){
            var id = $(this).attr('data-id')
            selectedid = id

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, deleted it!'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:'GET',
                        url: '/setup/student/clearance/delete',
                        data:{
                            selectedid: selectedid
                        },
                        success:function(data) {
                            Toast.fire({
                            type: 'success',
                            title: 'Signatory Deleted Successfully'
                            })
                            selectedid = ""
                            signatory_list()
                        },
                        error:function(){
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                }
            })
        })

        $(document).on('click','.edit_signatory',function(){
            $('#btn_addsingatory_modal').empty()
            $('#btn_addsingatory_modal').append('<button class="btn btn-primary btn-sm" id="save_signatory"><i class="fas fa-save"></i>&nbspUpdate</button>')
            var id = $(this).attr('data-id')
            selectedid = id
            edit_signatory()
            $('#modal_addsignatories').modal()
        })

        function edit_signatory(){
            var signatory_info = signatories.filter(x => x.id == selectedid)[0];
            $('#input_title').val(signatory_info.title)
            $('#select_name').val(signatory_info.teacherid).change()
            $('#input_department').val(signatory_info.departmentid)
            $('#select_acadterm').val(signatory_info.termid).change()

            // Convert the selected academic program IDs from string to array
            var selectedacadprogid = signatory_info.acadprogid
            var cleanstring = signatory_info.acadprogid.replace(/[\[\]"']+/g,'')
            var acadprogids = cleanstring.split(',')
            // Set the selected academic program IDs in the select2 input field
            acadprogid = acadprogids

            $(':input').removeClass('is-invalid').removeClass('is-valid')
        }

        $(document).on('click','#set_clearance',function(){
            getsetupacadterm()
        })

        // $(document).on('click','#save_clearance_setup', function(){
        //     $(this).attr('disabled', 'disabled');
        //     var valid_input = true
        //     var activesy = $('#select_sy').val();
        //     var acadprog = $('#select_setup_acadprog').val();
        //     var acadterm = $('#select_setup_acadterm').val();

        //     if(acadprog == null ){
        //         $('#select_setup_acadprog').removeClass('is-valid').addClass('is-invalid');
        //         valid_input = false
        //     }else{
        //         $('#select_setup_acadprog').removeClass('is-invalid').addClass('is-valid')
        //         $('#save_clearance_setup').removeAttr('disabled')
        //     }

        //     if(acadterm == null ){
        //         $('#select_setup_acadterm').removeClass('is-valid').addClass('is-invalid');
        //         valid_input = false
        //     }else{
        //         $('#select_setup_acadterm').removeClass('is-invalid').addClass('is-valid')
        //         $('#save_clearance_setup').removeAttr('disabled')
        //     }

        //     var url = '/setup/student/clearance/activate'

        //     if(valid_input){
        //         $.ajax({
        //             type:'GET',
        //             url: url,
        //             data:{
        //                 activesy: activesy,
        //                 acadprog: acadprog,
        //                 acadterm: acadterm,
        //             },
        //             success:function(data) {
        //                 if(data[0].status == 1){
        //                     Toast.fire({
        //                         type: 'success',
        //                         title: 'Clearance Activated Successfully!'
        //                     })
        //                     $('#save_signatory').attr('disabled', 'disabled')
        //                     $('#modal_clearance_setup').modal('hide')
        //                     $('#select_setup_acadprog').val("").change()
        //                     $('#select_setup_acadterm').val("").change()
        //                 }else{
        //                     Toast.fire({
        //                         type: 'error',
        //                         title: data[0].data
        //                     })
        //                 }
        //             },
        //             error:function(){
        //                 Toast.fire({
        //                     type: 'error',
        //                     title: 'Something went wrong!'
        //                 })
        //             }
        //         })
        //     }
        // })

        // $('#modal_clearance_setup').on('hidden.bs.modal', function() {
        //     $('#save_clearance_setup').removeAttr('disabled')
        //     $(':input').removeClass('is-valid').removeClass('is-invalid')
        // })

        // Teacher Enable Status **
        $(document).on('click','.subteacherstatus', function(){
            selectedstatus = $(this).attr('data-status')
            cler_acadprogid = $(this).attr('data-id')

            if(selectedstatus == 1){
                Swal.fire({
                title: 'Disable Subject Teacher Clearance?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Disable it!',
                }).then((result) => {
                    if (result.value) {
                        clearance_type = 'subteacher'
                        save_status()
                        $('#filter_enable_acadterm').removeClass('is-valid').removeClass('is-invalid')  
                    }
                }) 
            }else{
                Swal.fire({
                title: 'Enable Subject Teacher Clearance?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Enable it!',
                }).then((result) => {
                    if (result.value) {
                        clearance_type = 'subteacher'
                        save_status()
                        $('#filter_enable_acadterm').removeClass('is-valid').removeClass('is-invalid')   
                    }
                })
            }  
        })

        $(document).on('click','.adviserstatus', function(){
            selectedstatus = $(this).attr('data-status')
            cler_acadprogid = $(this).attr('data-id')

            if(selectedstatus == 1){
                Swal.fire({
                title: 'Disable Class Adviser Clearance?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Disable it!',
                }).then((result) => {
                if (result.value) {
                    clearance_type = 'adviser'
                    save_status()
                }
                })
            }else{
                Swal.fire({
                title: 'Enable Class Adviser Clearance?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Enable it!',
                }).then((result) => {
                if (result.value) {
                    clearance_type = 'adviser'
                    save_status()
                }
                })
            }        
        })

        function save_status(){
            if(clearance_type == 'subteacher'){
                var clearancefor = 'SUBJECT TEACHER'
            }else{
                var clearancefor = 'CLASS ADVISER'
            }
            var url = '/setup/student/clearance/activation'

            $.ajax({
                type:'GET',
                url: url,
                data:{
                    isactive: selectedstatus,
                    clearancefor: clearancefor,
                    cler_acadprogid: cler_acadprogid,
                },
                success:function(data) {
                    Toast.fire({
                        type: 'success',
                        title: 'Status Updated Successfully!'
                    })
                    selectedstatus = null;
                    cler_acadprogid = null;
                    getenable()
                },
                error:function(){
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        }

        // function get_subteacherstatus(){
        //     $.ajax({
        //         type:'GET',
        //         url: '/setup/student/clearance/get/activation/subteacher',
        //         data:{
        //             syid:$('#filter_sy').val(),
        //             acadterm: $('#filter_enable_acadterm').val(),
        //             acadprogid: $('#filter_enable_acadprogid').val(),
        //         },
        //         success:function(data) {
        //             subteacherstatus = data[0]
        //             $('#btn_enable_teacher').empty()
        //             if(subteacherstatus.isactive == 0){
        //                 $('#btn_enable_teacher').append(
        //                     `<br><button type="button" class="btn btn-sm btn-success subteacherstatus mr-1" style="font-size:.8rem !important" data-status="1"><i class="fa fa-check mr-1" ></i>Subject Teacher Enabled</button>`
        //                 )
        //             }
        //             if(subteacherstatus.isactive == 1){
        //                 $('#btn_enable_teacher').append(
        //                     `<br><button type="button" class="btn btn-sm btn-danger subteacherstatus mr-1" style="font-size:.8rem !important" data-status="0" ><i class="fa fa-ban mr-1" ></i>Subject Teacher  Disabled</button>`
        //                 )
        //             }
        //         }
        //     })
        // }

        // function get_adviserstatus(){
        //     $.ajax({
        //         type:'GET',
        //         url: '/setup/student/clearance/get/activation/adviser',
        //         data:{
        //             syid:$('#filter_sy').val(),
        //             acadterm: $('#filter_enable_acadterm').val(),
        //             acadprogid: $('#filter_enable_acadprogid').val(),
        //         },
        //         success:function(data) {
        //             adviserstatus = data[0]
        //             var acadid =$('#filter_enable_acadprogid').val()
        //             if(acadid != ""){
        //                 $('#label_enable_adviser').attr('hidden', false)
        //                 $('#btn_enable_adviser').empty()
        //                 var acad_info = academicprog.filter(x=>x.id == acadid)
        //                 var progname = acad_info[0].acadprogcode
        //                 if(progname == "PRE-SCHOOL" || progname == "ELEM" || progname == "HS" || progname == "SHS"){
        //                     if(adviserstatus.isactive == 0){
        //                         $('#btn_enable_adviser').append(
        //                             `<br><button type="button" class="btn btn-sm btn-success adviserstatus" style="font-size:.8rem !important" data-status="1" ><i class="fa fa-check mr-1" ></i>Class Adviser Enabled</button>`
        //                         )
        //                     }
        //                     if(adviserstatus.isactive == 1){
        //                         $('#btn_enable_adviser').append(
        //                             `<br><button type="button" class="btn btn-sm btn-danger adviserstatus" style="font-size:.8rem !important" data-status="0" ><i class="fa fa-ban mr-1" ></i>Class Adviser Disabled</button>`    
        //                         )
        //                     }
        //                 }else{
        //                     $('#label_enable_adviser').attr('hidden', true)
        //                     $('#btn_enable_adviser').empty()
        //                 }
        //             }
        //         },
        //         error:function(){
        //             Toast.fire({
        //                 type: 'error',
        //                 title: "Error: Subject Teacher Status!"
        //             })
        //         }
        //     })
        // }

        function getsetupacadterm(){
            var syid = $('#filter_sy').val();

            $.ajax({
                url: '/setup/students/clearance/getacadterm',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                },
                success:function(data){
                    terms = data
                    $("#filter_enable_acadterm").empty();
                    if(terms.length > 0 )
                    {
                        $("#filter_enable_acadterm").select2({
                            data:terms,
                            placeholder: "Select Term",
                        })
                        $("#get_enable_status").attr('disabled', false)
                    }else{
                        $("#filter_enable_acadterm").select2({
                            placeholder: "No Active Term!",
                        })
                        $("#get_enable_status").attr('disabled', true)
                    }
                    $('#modal_clearance_setup').modal()
                    getenable()
                }
            })
        }
        // Teacher Enable Status -close

        function getacadterm(){
            var syid = $('#filter_sy').val();
            var acadprog = $('#filter_acadprogid').val();

            $.ajax({
                url: '/students/clearance/getacadterm',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                    acadprog:acadprog,
                },
                success:function(data){
                    term = data 
                    $('#filter_acadterm').empty();
                    if(data.length > 0)
                    {
                        $('#filter_acadterm').append('<option value=""> All </option>')
                        $('#filter_acadterm').select2({
                            data: term,
                            allowClear: true,
                            placeholder: 'All'
                        })
                    }else{
                        $("#filter_acadterm").select2({
                            placeholder: "No Acad Term Setup",
                        })
                    }
                }
            })
        }

        function getacadprog(){
            $.ajax({
                type: 'GET',
                url: '/setup/student/clearance/getacadprog',
                data:{
                    syid:$('#filter_sy').val(),
                    acadterm: $('#select_acadterm').val()
                },
                success:function(data){
                    $('#select_acadprog').empty();
                    acadprog = data
                    if(acadprog.length > 0){
                        $('#select_acadprog').select2({
                            data:acadprog,
                            placeholder: "Select Academic Program",
                        })
                        $('#select_acadprog').val(acadprogid).trigger('change');
                        acadprogid = '';
                        $('#select_acadprog').removeClass('is-invalid')
                    }
                }
            })
        }
        
        // function acadisactive(){
        //     var syid = $('#filter_sy').val();

        //     $.ajax({
        //         url: '/setup/students/clearance/getacadterm',
        //         type: 'GET',
        //         dataType: 'json',
        //         data: {
        //             syid:syid,
        //         },
        //         success:function(data){
        //             academicterm = data
        //         }
        //     })
        // }

        function getenable(){
            var syid = $('#filter_sy').val();
            var acadterm = $('#filter_enable_acadterm').val();

            $.ajax({
                url: '/setup/students/clearance/getenablestatus',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                    acadterm:acadterm,
                },
                success:function(data){
                    enablestatus = data
                    console.log(data)
                    teacherclearenable()
                },
                error:function(){
                    Toast.fire({
                        type: 'error',
                        title: 'No Clearance Type!'
                    })
                }
            })
        }

        function teacherclearenable(){
            $("#table_teacher_enabled").DataTable({
                searching: true,
                paging: true,
                pageLength: 4,
                ordering: false,
                info: false,
                responsive: true,
                destroy: true,
                data:enablestatus,
                lengthChange: false,
                sort:false,
                columns: [
                    { "data": 'progname' },
                    { "data": null },
                    { "data": null },
                ],
                columnDefs:[
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML =  rowData.progname;
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var status = rowData.subjteacher;

                            if(status === 0){
                                button = `<button type="button" class="btn btn-sm btn-success subteacherstatus mr-1" style="font-size:.9rem !important" data-id=`+rowData.id+` data-status="1">Enabled</button>`
                            }else{
                                button = `<button type="button" class="btn btn-sm btn-danger subteacherstatus mr-1" style="font-size:.9rem !important" data-id=`+rowData.id+` data-status="0" >Disabled</button>`
                            }
                            
                            $(td)[0].innerHTML =  button
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var status = rowData.classadviser;

                            if(status === 0){
                                button = `<button type="button" class="btn btn-sm btn-success adviserstatus" style="font-size:.9rem !important" data-id=`+rowData.id+` data-status="1" >Enabled</button>`
                            }else{
                                button = `<button type="button" class="btn btn-sm btn-danger adviserstatus" style="font-size:.9rem !important" data-id=`+rowData.id+` data-status="0" >Disabled</button>`
                            }

                            $(td)[0].innerHTML =  button
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                ],
                rowCallback: function(row, data) {
                    $(row).attr('data-id', data.termid);
                },
                dom: '<"d-flex justify-content-center"lf>rtrip',
                language: {
                    search: '',
                    searchPlaceholder: 'Search...'
                }
            });
        }

        // $(document).on('click', '.activateterm', function(){
        //     // $('#modal_clearance_period').modal() //not implemented yet!!
        //     var acadtermstatus = $(this).attr('data-status');
        //     var termselectedid =  $(this).closest('tr').attr('data-id');
        //     var syid = $('#select_sy').val();

        //     var selected_acadterm = academicterm.filter(x=>x.id == termselectedid)

        //     if(acadtermstatus == 0){
        //         var title = 'Activate?'
        //     }else{
        //         var title = 'Deactivate?'
        //     }

        //     Swal.fire({
        //         title: title,
        //         text: selected_acadterm[0].term  + ' Clearance!',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, activate it!'
        //         }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: '/setup/students/clearance/activate/acadterm',
        //                 type: 'GET',
        //                 dataType: 'json',
        //                 data: {
        //                     termselectedid: termselectedid,
        //                     acadtermstatus: acadtermstatus,
        //                     syid: syid,
        //                 },
        //                 success:function(data){
        //                     Toast.fire({
        //                         type: 'success',
        //                         title: 'Academic Term Status Updated!'
        //                     })
        //                     acadisactive()
        //                 },
        //                 error:function(){
        //                     Toast.fire({
        //                         type: 'error',
        //                         title: 'Something went wrong!'
        //                     })
        //                 }
        //             })
        //         }
        //     })
        // })
    })
</script>
@endsection