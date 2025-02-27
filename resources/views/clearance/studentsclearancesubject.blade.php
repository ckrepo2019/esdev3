@extends('teacher.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
    #approveall {
        overflow: hidden;
        white-space: nowrap;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 5px;
    }

    .btn_allapprove {
        display: flex;
        justify-content: flex-end;
    }

    #table_students_filter, #table_students_paginate{
        display: flex;
        justify-content: flex-end;
    }
    }

    .select2-container {
        width: 100% !important;
    }

    .is-valid + .select2-container .select2-selection {
        border-color: #4CAF50;
    }

    .is-invalid + .select2-container .select2-selection {
        border-color: #F44336;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                    <h1>Clearance Approval By Subject</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="active breadcrumb-item">Students</li>
                <li class="active breadcrumb-item" aria-current="page">Clearance</li>
                <li class="active breadcrumb-item" aria-current="page">By Subject</li>
            </ol>
            </div>
        </div>
    </div>
</section>
@php   
$sy = DB::table('sy')->orderBy('sydesc','desc')->get();    
@endphp
{{-- Modal --}}
<div class="modal fade remarks_modal" id="remarks_modal" style="display:none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0" id="header_modalremarks">
                <span class="modal-title" id="remarks_title" role="display_name"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span></button>
                </button>
            </div>
            <div class="modal-body mt-2 pt-0">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="student_name">Name</label>
                        <span class="form-control form-control-sm" id="stud_name" role="display_name"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="clearance_remarks">Remarks</label><span style="color:red; font-size: 11px;"> (Maximun of 200 characters)</span>
                        <textarea class="form-control form-control-sm" id="clearance_remarks" rows="5" maxlength="200"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success btn-sm" id="save_remarks"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Modal --}}
<div class="card" style="border: none;">
    <div class="row">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>School Year</label>
                    <select class="form-control select2" id="filter_syid">
                        @foreach(collect(DB::table('sy')->get())->sortByDesc('sydesc')->values() as $eachsy)
                            <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-control select2" id="filter_semid">
                        @foreach(DB::table('semester')->get() as $eachsemester)
                            <option value="{{$eachsemester->id}}" @if($eachsemester->isactive == 1) selected @endif>{{$eachsemester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" style="vertical-align: bottom;">
                    <label>Grade Level</label>
                    <select class="form-control select2" id="filter_levelid">
                    </select>
                </div>
                <div class="col-md-3" style="vertical-align: bottom;">
                    <label>Section</label>
                    <select class="form-control select2" id="filter_sectionid">
                    </select>
                </div>          
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Subject</label>
                    <select class="form-control select2" id="filter_subjectid">
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Academic Term</label>
                    <select class="form-control select2" id="filter_acadterm">
                    </select>
                </div>
                {{-- <div class="col-md-3 text-right align-self-end mt-2">
                    <button type="button" class="btn btn-primary btn-sm" disabled id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div> --}}
            </div>
        </div>
    </div>
</div>
<div class="card" style="border: none;" id="clearance_view" hidden>
    <div class="card-body">
        <div class="table-responsive">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-sm table-bordered table-hover p-0" id="table_students" width="100%" style="font-size:12px !important">
                                <thead>
                                    <tr style="font-size:1rem !important">
                                        <th width="25%">Name</th>
                                        <th width="20%">Section</th>
                                        <th width="20%">Clearance Status</th>
                                        <th width="25%">Remarks</th>
                                        <th width="10%"></th>
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
<div id="results-container"></div>
@endsection
@section('footerscripts')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>
    var selectedid = null
    var status = null
    var action = null
    var name = null
    var selected_clearanceid = null
    var sy = @json($sy);

    $("#table_students").addClass("responsive-table");

    $(document).ready(function(){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        })

        $('.select2').select2()

        // $(document).on('click','#btn-generate', function(){
        //     action = 'SUBJECT TEACHER'
        //     getstudents()
        // })

        $(document).on('change','#filter_syid', function(){
            $('#clearance_view').attr('hidden',true)
        })

        $(document).on('change','#filter_levelid', function(){
            getsections()
        })

        $(document).on('change','#filter_sectionid', function(){
            getsubjects()
        })

        $(document).on('change','#filter_subjectid', function(){
            getacadterm()
        })

        $(document).on('change','#filter_acadterm', function(){
            getstudents()
        })

        $(document).on('click','.approveclearance', function(){
            selected_clearanceid = $(this).closest('tr').data('id');
            selectedid = $(this).closest('tr').data('studid');
            status = $(this).attr('data-status');

            var studinfo = students[0].filter(x=>x.id == selected_clearanceid)
            var name = '<label>' + studinfo[0].lastname + ',</label> ' + studinfo[0].firstname

            if($('#filter_acadterm').val() == ""){
                Toast.fire({
                    type: 'warning',
                    title: 'Please! select academic term'
                })
                $('#filter_acadterm').removeClass('is-valid').addClass('is-invalid')
            }else{
                if(status == 0){
                    var remarks_title = '<label class="modal-title">Approve Clearance</label>'
                    $('#header_modalremarks').removeClass('bg-danger').removeClass('bg-warning').addClass('bg-success')
                    $('#remarks_title')[0].innerHTML = remarks_title
                }else if(status == 2){
                    var remarks_title = '<label class="modal-title">Set clearance to pending</label>'
                    $('#header_modalremarks').removeClass('bg-success').removeClass('bg-danger').addClass('bg-warning')
                    $('#remarks_title')[0].innerHTML = remarks_title
                }else{
                    var remarks_title = '<label class="modal-title">Unapproved Clearance</label>'
                    $('#header_modalremarks').removeClass('bg-success').removeClass('bg-warning').addClass('bg-danger')
                    $('#remarks_title')[0].innerHTML = remarks_title
                }
                $('#stud_name')[0].innerHTML = name
                $('#remarks_modal').modal()
            }
        })

        $(document).on('click','#save_remarks', function(){
            approve_clearance()
        })

        $('#remarks_modal').on('hidden.bs.modal', function () {
            $('#stud_name').empty()
            $('#remarks_title').empty()
            $('#clearance_remarks').val("")
        })

        $(document).on('click','#approveall', function(){
            var remarks = $('#clearance_remarks').val()
            var syid = $('#filter_syid').val();
            var acadterm = $('#filter_acadterm').val();
            var section = $('#filter_sectionid').val();
            var subjectid = $('#filter_subjectid').val();
            var levelid =$('#filter_levelid').val();
            var status = $(this).attr('data-status');
            action = 'SUBJECT TEACHER'

            Swal.fire({
                title: 'Approve All Student?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve all!'
            }).then((result) => {
                if (result.value) {
                        $.ajax({
                    url: '/students/clearance/approveall',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        remarks: remarks,
                        syid:syid,
                        clearance_type: 0, // 0 - sbuject teacher 1 - advicer id - for signatory(principal/finance/registrar)
                        section: section,
                        subjectid: subjectid,
                        levelid: levelid,
                        status:status,
                    },
                    success:function(data){
                        Toast.fire({
                            type: 'success',
                            title: 'Student Clearance Updated!'
                        })
                        $('#filter_acadterm').removeClass('is-valid').removeClass('is-invalid')
                        $('#remarks_modal').modal('hide')
                        getstudents()
                        action = ""
                        selectedid = ""
                        status = ""
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

        getlevels()

        function getlevels(){
            var syid = $('#filter_syid').val();
            var semid = $('#filter_semid').val();
            var acadterm = $('#filter_acadterm').val();

            $.ajax({
                url: '/students/bysubject',
                type: 'GET',
                dataType: 'json',
                data: {
                    acadterm : acadterm,
                    action  : 'SUBJECT TEACHER',
                    syid    : syid,
                    semid   : semid
                },
                success:function(data){
                    $('#filter_levelid').empty();
                    var levelarray = []
                        data.forEach(function(obj){
                            var modifyObj = {
                                id: obj.id,
                                text: obj.levelname,
                                sortid: obj.sortid
                            };
                            levelarray.push(modifyObj);
                        })
                    if(levelarray.length > 0 ){
                        $("#filter_levelid").select2({
                            data:levelarray,
                            placeholder: "Select Grade Level",
                        })
                    }else{
                        $("#filter_levelid").select2({
                            placeholder: "No Grade Level!",
                        })
                    }
                    getsections()
                }
            })
        }

        function getsections(){
            var syid = $('#filter_syid').val();
            var semid = $('#filter_semid').val();
            var levelid = $('#filter_levelid').val();
            $.ajax({
                url: '/students/clearance/getsection',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                    levelid:levelid
                },
                success:function(data){
                    $('#filter_sectionid').empty();
                    section = data
                    if(section.length > 0){
                        $("#filter_sectionid").select2({
                            data:section,
                            placeholder: "Select Grade Level",
                        })
                        getsubjects()
                    }else{
                        $("#filter_sectionid").select2({
                            placeholder: "No Section!",
                        })
                        // $("#btn-generate").attr('disabled', true)
                    }
                }
            })
        }

        function getsubjects(){
            var syid = $('#filter_syid').val();
            var semid = $('#filter_semid').val();
            var levelid = $('#filter_levelid').val();
            var sectionid = $('#filter_sectionid').val();
            
            if(sectionid != null){
                $.ajax({
                    url: '/students/bysubjectgetsubjects',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        syid            : syid,
                        semid           : semid,
                        gradelevelid    : levelid,
                        sectionid       : sectionid
                    },
                    success:function(data){
                        var subjarray = []
                        data.forEach(function(obj){
                            var modifyObj = {
                                id: obj.id,
                                text: obj.subjdesc,
                            };
                            subjarray.push(modifyObj);
                        })
                        $('#filter_subjectid').empty();
                        if(subjarray.length > 0){
                            $("#filter_subjectid").select2({
                                data:subjarray,
                                placeholder: "Select Subject",
                            })
                        }else{
                            $("#filter_subjectid").select2({
                                placeholder: "No Subject!",
                            })
                        }
                        getacadterm()
                    },
                    error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }else{
                $("#filter_subjectid").select2({
                    placeholder: "No Subject!",
                })
            }
        }

        function getacadterm(){
            var syid = $('#filter_syid').val();
            var levelid = $('#filter_levelid').val();
            var subid = $('#filter_subjectid').val();
            $("#filter_acadterm").empty();
            var table = $('#table_students').DataTable();
            table.clear().draw();

            if(subid != null){
                $.ajax({
                    url: '/students/clearance/getacadterm',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        syid:syid,
                        levelid:levelid,
                    },
                    success:function(data){
                        terms = data
                        if(terms.length > 0 )
                        {
                            $("#filter_acadterm").select2({
                                data:terms,
                                placeholder: "Select Term",
                            })
                            // $("#btn-generate").attr('disabled', false)
                        }else{
                            $("#filter_acadterm").select2({
                                placeholder: "No Active Term!",
                            })
                            // $("#btn-generate").prop('disabled', true)
                        }
                        getstudents()
                    },
                    error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }else{
                $("#filter_acadterm").select2({
                    placeholder: "No Term!",
                })
                // $("#btn-generate").prop('disabled', true)
            }
        }

        function getstudents(){
            var syid = $('#filter_syid').val();
            var acadterm = $('#filter_acadterm').val();
            var levelid = $('#filter_levelid').val();
            var sectionid = $('#filter_sectionid').val();
            var subjectid = $('#filter_subjectid').val();
            var loadingPopup

            // if(action == 'SUBJECT TEACHER'){
            //     loadingPopup = Swal.fire({
            //         title: 'Fetching data...',
            //         allowOutsideClick: false,
            //         onBeforeOpen: () => {
            //             Swal.showLoading();
            //         }
            //     });
            // }

            $.ajax({
                url: '/students/clearance/getstudents',
                type: 'GET',
                dataType: 'json',
                data: {
                    action:'SUBJECT TEACHER',
                    syid:syid,
                    acadterm:acadterm,
                    sectionid:sectionid,
                    levelid:levelid,
                    subjectid:subjectid,
                    clearance_type: 0,
                },
                success:function(data){
                    $('#clearance_view').attr('hidden', false)
                    students = data
                    studtable()
                    action = ""
                    // loadingPopup.close();
                },
                error:function(){
                    action = ""
                    // loadingPopup.close();
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        }

        function studtable(){
        var selectedsy = $('#filter_syid').val()
        var selectedinfo = sy.filter(x=>x.id == selectedsy)[0]

            $("#table_students").DataTable({ 
                destroy: true,
                data: students[0],
                lengthChange: false,
                stateSave: true,
                sort: false,
                responsive: true,
                columns: [
                    {"data": "lastname"},
                    {"data": "firstname"},
                    {"data": "middlename"},
                    {"data": null},
                    {"data": null},
                ],
                columnDefs:[
                    {
                        'targets': 0,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var middlename = rowData.middlename != null ? rowData.middlename : '';
                            var firstLetter = middlename != null ? middlename.charAt(0) + '.' : ' '

                            var text = '<b>' + rowData.lastname + '</b>, ' + rowData.firstname +" "+ firstLetter

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var text = rowData.levelname +" "+ rowData.sectname

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var status = rowData.status
                            var active = rowData.isactive
                            var date = ""
                            var hint = ""
                            var approval = ""
                            var formattedDatetime = ""

                            if(students[1] == true){
                                if(status == 1){
                                    date = rowData.updateddatetime
                                    approval = '<span class="badge badge-danger">Uncleared</span>'
                                }else if(status == 0){
                                    date = rowData.approveddatetime
                                    approval = '<span class="badge badge-success">Cleared</span>'
                                }else if(status == 2){
                                    date = rowData.updateddatetime
                                    approval = '<span class="badge badge-warning">Pending</span>'
                                }else{
                                    date = null
                                    approval = ''
                                }
                            }else{
                                date = null
                                approval = ''
                            }


                            if(date != null){
                                let datetime = new Date(date);
                                let options = {
                                    year: 'numeric',
                                    month: '2-digit',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: true
                                };
                                formattedDatetime = datetime.toLocaleString('en-US', options);
                            }else{
                                formattedDatetime = "..."
                            }

                            if(approval != ''){
                                var text = '<span style="font-size:.9rem !important">' + approval + '</span><br>'
                                text += '<span style="font-size:.7rem !important">' + formattedDatetime + '</span>' 
                            }else{
                                var text = '<span> <hr class="my-1 bg-dark" style="width: 5%;"> </span>';
                            }

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var text = null;
                            var centertext = null;

                            if(rowData.remarks != null){
                                text = rowData.remarks;
                                centertext = '';
                            }else{
                                text = '<span> <hr class="my-1 bg-dark" style="width: 5%;"> </span>';
                                centertext = "text-center";
                            }

                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle');
                            $(td).addClass(centertext);
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var checkbox1 = '<button type="button" class="btn btn-sm btn-outline-success approveclearance" data-status="0" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Approve"><i class="fas fa-check-circle"></i></button>'
                            var checkbox2 = '<button type="button" class="btn btn-sm btn-outline-danger approveclearance" data-status="1" data-id="'+rowData.id+'" data-placement="top" title="Reject"><i class="fas fa-times-circle"></i></button>'
                            var checkbox3 = '<button type="button" class="btn btn-sm btn-outline-dark approveclearance m-1" data-status="2" data-id="'+rowData.id+'" data-placement="top" title="Pending"><i class="fas fa-hourglass-half"></i></button>'
                            if(students[1] == true){
                                if(selectedinfo.ended == 0){
                                    if(rowData.status != null){
                                        if (rowData.status == 0) { // note final condition
                                            checkbox2 += checkbox3
                                            $(td)[0].innerHTML = checkbox2
                                        } else {
                                            checkbox1 += checkbox3
                                            $(td)[0].innerHTML = checkbox1;
                                        }
                                    }else{
                                        checkbox = '<span  style="font-size:.7rem !important" class="badge badge-warning"><i class="fas fa-ban"></i> No Clearance!</span>'
                                        $(td)[0].innerHTML = checkbox;
                                    }
                                }else{  
                                    checkbox = '<span  style="font-size:.7rem !important" class="badge badge-warning"><i class="fas fa-ban"></i> School Year Ended!</span>'
                                    $(td)[0].innerHTML = checkbox;
                                }
                            }else{
                                checkbox = '<span  style="font-size:.7rem !important" class="badge badge-danger"><i class="fas fa-ban"></i> Clearance Disabled!</span>'
                                    $(td)[0].innerHTML = checkbox;
                            }


                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                ],
                rowCallback: function(row, data) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-studid', data.clearance_studid);
                }
            });

            var label_text = ''
            var label_text = $($("#table_students_wrapper")[0].children[0])[0].children[0]
            var isclearance_enabled = students[1]

            if(selectedinfo.ended != 1){
                if(isclearance_enabled){
                    var button = `<div class="row">
                            <div class="col-md-12 btn_allapprove" >
                                <button class="btn btn-success mr-1 btn-sm" id="approveall" data-status="0"><i class="fas fa-user-check"></i> Approve All</button>
                                <button class="btn btn-warning btn-sm" id="approveall" data-status="2"><i class="fas fa-hourglass-half"></i> Pending All</button>
                            </div>
                        </div>`
                }else{
                    var button = `<div class="row">
                            <div class="col-md-12 btn_allapprove" >
                                <span class="text-danger font-weight-bold" >Teacher clearance disabled. Please contact Registrar Office if you believe this is incorrect. </span>
                            </div>
                        </div>`
                }
            }else{
                var button = `<div class="col-md-6">
                                <span class="syprompt text-danger font-weight-bold" >Note: School Year has ended!</span>
                            </div>`
            }

            $(label_text)[0].innerHTML = button
        }

        function approve_clearance(){
            var clear_stat_id = selected_clearanceid
            var clear_studid = selectedid
            var clearance_status = status
            var remarks = $('#clearance_remarks').val()
            var syid = $('#filter_syid').val();
            var acadterm = $('#filter_acadterm').val();
            var subjectid = $('#filter_subjectid').val();
            action = 'SUBJECT TEACHER'

            $.ajax({
                url: '/students/clearance/approve',
                type: 'GET',
                dataType: 'json',
                data: {
                    clear_stat_id:clear_stat_id,
                    clear_studid: clear_studid,
                    status: clearance_status,
                    remarks: remarks,
                    syid:syid,
                    acadterm:acadterm,
                    subjectid:subjectid,
                    clearance_type: 0, // 0 - sbuject teacher 1 - advicer id - for signatory(principal/finance/registrar)
                },
                success:function(data){
                    Toast.fire({
                        type: 'success',
                        title: 'Student Clearance Updated!'
                    })
                    $('#filter_acadterm').removeClass('is-valid').removeClass('is-invalid')
                    $('#remarks_modal').modal('hide')
                    getstudents()
                    selectedid = ""
                    status = ""
                    action = ""
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
</script>
@endsection
