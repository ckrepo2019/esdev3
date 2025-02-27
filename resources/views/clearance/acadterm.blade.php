@php
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

    if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(auth()->user()->type == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else{
        if(isset($check_refid->refid)){
                if($check_refid->refid == 27){
                    $extend = 'academiccoor.layouts.app2';
                }
        }else{
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
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    @media (max-width: 576px) {
        .table-responsive {
            overflow-x: auto;
        }
    }

    @media screen and (max-width: 767px) {
        .responsive-table td:nth-child(2),
        .responsive-table th:nth-child(2) {
            display: none;
        }
    }

    /* For screens smaller than 768px (e.g., mobile devices) */
    @media (max-width: 767.98px) {
    /* Hide the text inside the button */
    .add_term {
        margin-bottom: 5px;
    }

    #table_addterm, #section_setup_filter, #section_setup_paginate{
        display: flex;
        justify-content: flex-end;
    }
    }

    .required:after {
        content:" *";
        color: red;
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
        line-height: 34px;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #6c757d;
        border: 1px solid #343a40;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: 4px 4px 0 0;
        padding: 0 6px 0 22px;
        height: 28px;
        line-height: 28px;
        font-size: 12px;
        position: relative;
    }

    .select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
        position: absolute;
        top: 0;
        left: 0;
        height: 28px;
        width: 28px;
        margin: 0;
        text-align: center;
        color: #f8f9fa;
        font-weight: bold;
        font-size: 14px;
    }

    .checkbox-container {
        position: relative;
    }

    .align-end {
        position: absolute;
        top: 0;
        right: 0;
    }

    .is-valid + .select2-container .select2-selection {
        border-color: #4CAF50;
    }
    .is-invalid + .select2-container .select2-selection {
        border-color: #F44336;
    }

</style>
@endsection

@section('content')

@php
    $sy = DB::table('sy')->orderBy('sydesc','desc')->get();
    $activesy = DB::table('sy')->where('isactive',1)->select('sydesc')->value('sydesc');

    $acadprog = DB::table('academicprogram')->get();
    
    $schoolinfo = DB::table('schoolinfo')->first();
@endphp

{{-- Modal add term --}}
<div class="modal fade" id="term_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h3 class="modal-title"><label>Student Clearance Form</label></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="" class="required">Student Clearance</label>
                        <input id="input_term" class="form-control form-control" placeholder="E.g 4TH QUARTER" oninput="this.value = this.value.replace(/[^a-zA-Z0-9-_. ]/g, '')" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                        <span class="invalid-feedback" id="span_input_term" role="alert"></span>
                        <ul id="same_term" class="mb-0" ></ul>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="" class="required">School Year</label>
                        <input id="input_sy" class="form-control form-control" value="{{$activesy}}" disabled>
                    </div>
                    <div class="col-md-12 form-group">
                        <div>
                            <label for="" class="required">Academic Program</label>
                            <label class="align-end mr-2" > <input type="checkbox" id="selectAllCheckbox"> All </label>
                        </div>
                        <select class="form-control select2 form-control multiple-select" multiple="multiple" id="input_acadprog">
                            @foreach ($acadprog as $item)
                                <option value="{{$item->id}}" >{{$item->progname}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" id="span_input_acadprog" role="alert">
                            <strong> Academic Program is required. </strong>
                        </span> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span id="btn-section-form"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>   

{{-- Page --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Clearance</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Student Clearance</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content pt-0">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="info-box shadow-lg">
                <div class="info-box-content">
                    <div class="row">
                        <div class="col-md-12">
                            <i class="fa fa-filter"></i><label>Filter</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">School Year</label>
                            <select class="form-control select2 form-control-sm" id="filter_sy">
                                @foreach ($sy as $item)
                                    @if($item->isactive == 1)
                                        <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                    @else
                                        <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Academic Program</label>
                            <select class="form-control select2 form-control-sm" id="filter_acadprog">
                                <option value="" selected>All</option>
                                @foreach ($acadprog as $item)
                                    <option value="{{$item->id}}" >{{$item->progname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow" style="">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <table class="table table-striped table-sm table-hover" id="section_setup" width="100%" >
                                <thead>
                                    <tr>
                                        <th class="no-border" width="30%">Student Clearance</th>
                                        <th width="55%">Academic Program</th>
                                        <th width="5%">Status</th>
                                        <th class="text-center" width="5%"></th>
                                        <th class="text-center" width="5%"></th>
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
</section>
{{-- Page --}}
@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>

<script>
    $("#section_setup").addClass("responsive-table");
    var acadterm = null;

    const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        })

    $(document).ready(function(){
        $('.select2').select2()
        $('[data-toggle="tooltip"]').tooltip();

        $('#input_acadprog').select2({
            placeholder: "Select Academic Program",
        })

        $('#selectAllCheckbox').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('#input_acadprog').find('option').prop('selected', isChecked);
            $('#input_acadprog').trigger('change');
        });

        var school_setup = @json($schoolinfo);
        var schoolinfo = @json($schoolinfo);

        $(document).on('click','#add_term',function(){
            $('#btn-section-form').empty()
            var button = '<button class="btn btn-primary" id="save_term"><i class="fas fa-save"> </i> <b>Save</b></button>'
            $('#btn-section-form').append(button)
            $('#term_modal').modal()
        })

        $(document).on('input','#input_term',function(){
            var text = $(this).val()
            var check_dup = uniqueTermArr.filter(x=>x.term.includes(text.toUpperCase()) && x.term == text.toUpperCase() && x.term !== termselected).slice(0,3)
            $('#same_term').empty()

            if(text.length == '0'){
                $('#errinput_term').remove();
                $('#input_term').removeClass('is-valid').addClass('is-invalid')
                $('#span_input_term').append("<strong id='errinput_term'>Student Clearance is required. </strong>")
            }else if(check_dup.length > 0 && $(this).val() != ""){
                var duplicate = ''
                $.each(check_dup,function(a,b){
                        duplicate += '<li>'+b.term+'</li>'
                })
                $('#errinput_term').remove();
                $('#span_input_term').append("<strong id='errinput_term'>Student Clearance is already registerd. </strong>")
                $('#same_term')[0].innerHTML = duplicate
                $('#input_term').removeClass('is-valid').addClass('is-invalid')
            }else{
                $('#errinput_term').remove();
                $('#input_term').removeClass('is-invalid').addClass('is-valid')
                $('#save_term').removeAttr('disabled')
            }
        })

        $(document).on('change','#input_acadprog',function(){

            if($('#input_acadprog').val().length == '0'){
                $('#input_acadprog').removeClass('is-valid').addClass('is-invalid')
            }else{
                $('#input_acadprog').removeClass('is-invalid').addClass('is-valid')
                $('#save_term').removeAttr('disabled')
            }
        })

        $('#term_modal').on('hidden.bs.modal', function () {
            $(':input').removeClass('is-valid').removeClass('is-invalid')
            termselected = ""
        })

        $(document).on('click','#save_term',function(){
            $(this).attr('disabled', 'disabled');
            var valid_input = true
            if($('#input_term').val().length == '0'){
                Toast.fire({
                    type: 'warning',
                    title: 'Acadmic term is required!'
                })
                $('#errinput_term').remove();
                $('#input_term').removeClass('is-valid').addClass('is-invalid')
                $('#span_input_term').append("<strong id='errinput_term'>Student Clearance is required. </strong>")
                valid_input = false
            }else if($('#input_term').hasClass('is-invalid')){
                Toast.fire({
                    type: 'warning',
                    title: 'Acadmic term is already registerd!'
                })
                $('#errinput_term').remove();
                $('#span_input_term').append("<strong id='errinput_term'>Student Clearance is already registerd. </strong>")
                $('#input_term').removeClass('is-valid').addClass('is-invalid')
                valid_input = false
            }else{
                $('#input_term').removeClass('is-invalid').addClass('is-valid')
            }

            if($('#input_acadprog').val().length == '0' || $('#input_acadprog').hasClass('is-invalid')){
                Toast.fire({
                    type: 'warning',
                    title: 'Academic program is required!'
                })
                $('#input_acadprog').removeClass('is-valid').addClass('is-invalid')
                valid_input = false
            }else{
                $('#input_acadprog').removeClass('is-invalid').addClass('is-valid')
            }

            var url = '/acadterm/save'

            if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                url = schoolinfo.es_cloudurl+'/acadterm/save'
            }

            if(selectedid == null){
                if(valid_input){
                    $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                            term:$('#input_term').val(),
                            acadprog:$('#input_acadprog').val(),
                            syid: $('#filter_sy').val()
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Save Successfully!'
                                })
                                $('#term_modal').modal('hide')
                                acadterm_info_list()
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
                if(valid_input){
                    $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                            selectedid:selectedid,
                            term:$('#input_term').val(),
                            acadprog:$('#input_acadprog').val(),
                            syid: $('#filter_sy').val()
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated Successfully!'
                                })
                                $('#term_modal').modal('hide')
                                acadterm_info_list()
                                selectedid = ""
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

        $('#term_modal').on('hidden.bs.modal', function() {
            selectedid = null;
            $('#save_signatory').removeAttr('disabled')
            $(':input').removeClass('is-valid').removeClass('is-invalid')
            $('#input_term').val("")
            $('#input_acadprog').val("").change().removeClass('is-valid').removeClass('is-invalid')
            $('#selectAllCheckbox').prop('checked', false)
        })

        $(document).on('click','.edit_term',function(){
            var id = $(this).attr('data-id')
            selectedid = id
            edit_term()
            $('#term_modal').modal()
        })

        $(document).on('click','.remove_term',function(){
            var id = $(this).attr('data-id')
            selectedid = id
            var selected_acadterm = acadterm.filter(x=>x.id == selectedid)[0]

            Swal.fire({
                title: 'Delete? ' + selected_acadterm.term,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete it!'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:'GET',
                        url: '/acadterm/delete',
                        data:{
                            selectedid: selectedid
                        },
                        success:function(data) {
                            Toast.fire({
                            type: 'success',
                            title: 'Deleted Successfully!'
                            })
                            selectedid = null
                            acadterm_info_list()
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

        acadterm_info_list()

        $(document).on('change','#filter_sy',function(){
            acadterm_info_list() 
        })

        $(document).on('change','#filter_acadprog',function(){
            acadterm_info_list() 
        })

    });
///////////
    var sy = @json($sy);
    var acadterm = [];
    var selectedsy = $('#filter_sy').val()
    var uniqueTermArr = [];
    var selectedid = null
    var termselected = null;
//////////    
    function acadterm_info_list(){
        $.ajax({
            type:'GET',
            url: '/acadterm/list',
            data:{
                syid:$('#filter_sy').val(),
                acadprogid:$('#filter_acadprog').val()
            },
            success:function(data) {
                acadterm = data
                acadtermtable()
            }
        })
    }

    function acadtermtable(){
        uniqueTermArr = acadterm.filter((obj, index, self) =>
            index === self.findIndex((o) => o.term === obj.term)
        );
        
        var selectedsy = $('#filter_sy').val()
        var selectedinfo = sy.filter(x=>x.id == selectedsy)[0]
    
        $("#section_setup").DataTable({
            destroy: true,
            data:uniqueTermArr,
            lengthChange: false,
            stateSave: true,
            sort:false,
            columns: [
                { "data": "term" },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
            ],
            columnDefs:[
                    {
                        'targets': 0,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            // var text = '<label>' + rowData.term + '</label>'
                            var status = rowData.isactive;                            
                            if(status == 0){
                                var isactive = 'Activated';
                            }else{
                                var isactive = 'Deactivated';
                            }

                            var switchInput = '<div class="custom-control custom-switch" data-toggle="tooltip" data-placement="top" title="'+isactive+'"><input type="checkbox" class="custom-control-input activateterm" id="switch-' + rowData.id + '" data-id="' + rowData.id + '" data-status="' + (status === 0 ? 1 : 0) + '"' + (status === 0 ? ' checked' : '') + '><label class="custom-control-label" for="switch-' + rowData.id + '"></label>'+rowData.term+'</div>';

                            $(td)[0].innerHTML = rowData.term
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = ''
                            var filter_acadprog = acadterm.filter(x=>x.id == rowData.id && x.term == rowData.term && x.syid == selectedsy)

                            $.each(filter_acadprog,function(a,b){
                                text += '<span class="badge badge-pill badge-info ml-1 mb-1 font-weight-normal" style="font-size: .9rem;">'+b.progname+'</span>'
                            })

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col,) {
                            // var status = rowData.isactive
                            // var checkbox1 = '<button type="button" class="btn btn-outline-success btn-xs font-weight-bold activateterm btn-outline-custom" data-id="'+rowData.id+'" data-status="1">ACTIVE</button>'
                            // var checkbox2 = '<button type="button" class="btn btn-outline-danger btn-xs font-weight-bold activateterm btn-outline-custom" data-id="'+rowData.id+'" data-status="0">INACTIVE</button>'
                            
                            var status = rowData.isactive;                            
                            if(status == 0){
                                var isactive = 'Activated';
                            }else{
                                var isactive = 'Deactivated';
                            }

                            var switchInput = '<div class="custom-control custom-switch" data-toggle="tooltip" data-placement="top" title="'+isactive+'"><input type="checkbox" class="custom-control-input activateterm" id="switch-' + rowData.id + '" data-id="' + rowData.id + '" data-status="' + (status === 0 ? 1 : 0) + '"' + (status === 0 ? ' checked' : '') + '><label class="custom-control-label" for="switch-' + rowData.id + '"></label></div>';

                            $(td)[0].innerHTML =  switchInput
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if(selectedinfo.ended != 1){
                                var buttons = '<a href="#" class="edit_term" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="far fa-edit"></i></a>';
                            }else{
                                var buttons = ''
                            }
                            
                        $(td)[0].innerHTML =  buttons
                        $(td).addClass('align-middle')
                        $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if(selectedinfo.ended != 1){
                                var buttons = '<a href="#" class="remove_term" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="far fa-trash-alt text-danger"></i></a>';
                            }else{
                                var buttons = ''
                            }
                            
                        $(td)[0].innerHTML =  buttons
                        $(td).addClass('align-middle')
                        $(td).addClass('text-center')
                        }
                    },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Student Clearance...'
            },
            drawCallback: function () {
                $('#section_setup tbody th').css('border-left', 'none');
                $('#section_setup tbody th').css('border-right', 'none');
                $('#section_setup tbody td').css('border-left', 'none');
                $('#section_setup tbody td').css('border-right', 'none');
            }
        });
        
        var label_text = ''
        var label_text = $($("#section_setup_wrapper")[0].children[0])[0].children[0]

        if(selectedinfo.ended != 1){
            var button = `<div class="row" >
                    <div class="col-md-12"  id="table_addterm">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-sm add_term btn_disabled"  id="add_term" style="font-size: 1rem !important"><i class="fas fa-plus"></i>&nbspStudent Clearance</button>
                            </div>
                        </div>
                    </div>
                </div>`
        }else{
            var button = `<div class="col-md-6">
                            <span class="syprompt text-danger" >Note: School Year has ended</span>
                        </div>`
        }

        $(label_text)[0].innerHTML = button
    }

    function edit_term(){
        $('#btn-section-form').empty()
        var button = '<button class="btn btn-primary" id="save_term"><i class="fas fa-save"> </i> <b>Update</b></button>'
        $('#btn-section-form').append(button)

        var term_info = acadterm.filter(x=>x.id == selectedid )
        $('#input_term').val(term_info[0].term)
        
        const filteredIds = term_info.map(item => item.acadprogid)
        $('#input_acadprog').val(filteredIds).trigger('change');
        termselected = $('#input_term').val()
        $('#input_acadprog').removeClass('is-valid')
    }

    $(document).on('click', '.activateterm', function(){
        var status
        var type
        var acadtermstatus = $(this).attr('data-status');
        var termselectedid =  $(this).attr('data-id');
        var selected_acadterm = acadterm.filter(x=>x.id == termselectedid)[0]
        console.log(acadtermstatus)
        if(acadtermstatus == 0){
            status = "Activated";
        }else{
            status = "Deactivated";
        }

        $.ajax({
            url: '/setup/students/clearance/activate/acadterm',
            type: 'GET',
            dataType: 'json',
            data: {
                termselectedid: termselectedid,
                acadtermstatus: acadtermstatus,
            },
            success:function(data){
                Swal.fire({
                    type: 'success',
                    title: status,
                    showConfirmButton: true,
                    timer: 1000
                    })
                acadterm_info_list()
            },
            error:function(){
                Toast.fire({
                    type: 'error',
                    title: 'Something went wrong!'
                })
            }
        })

        // Swal.fire({
        //     title: title,
        //     text: selected_acadterm.term  + ' Clearance!',
        //     type: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, activate it!'
        //     }).then((result) => {
        //     if (result.value) {
        //         $.ajax({
        //             url: '/setup/students/clearance/activate/acadterm',
        //             type: 'GET',
        //             dataType: 'json',
        //             data: {
        //                 termselectedid: termselectedid,
        //                 acadtermstatus: acadtermstatus,
        //             },
        //             success:function(data){
        //                 Toast.fire({
        //                     type: 'success',
        //                     title: 'Status Updated!'
        //                 })
        //                 acadterm_info_list()
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

    })

</script>
@endsection


