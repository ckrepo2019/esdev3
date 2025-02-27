@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    .daterangepicker {
        z-index: 9999 !important;
    }

    .daterangepicker.opensleft {
        left: auto !important;
        right: 20px !important;
    }
    .modal-body {
        max-height: calc(100vh - 210px); /* Adjust the value according to your modal's header and footer height */
        overflow-y: auto;
    }
    .tooltip {
      position: relative;
      display: inline-block;
      cursor: pointer;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: rgb(6, 6, 6);
        line-height: 18px;
    }
    /* Style for tooltip text */
    .tooltiptext {
      visibility: hidden;
      width: 120px;
      background-color: #333;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 5px;
      position: absolute;
      z-index: 1;
      bottom: 125%;
      left: 50%;
      margin-left: -60px;
      opacity: 0;
      transition: opacity 0.3s;
    }

    /* Show the tooltip text when hovering over the container */
    .tooltip:hover .tooltiptext {
      visibility: visible;
      opacity: 1;
    }
    .hover-color {
        cursor: pointer!important;
    }

    .hover-color:hover {
        background-color: #00e7ff26;
        /* color: #fff; */
    }
 
</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>PART TIME</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">PART TIME</li>
                </ol>
                </div>
        </div>
    </div>
</section>

@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp



<section class="content">
    {{-- ============================================================= MODAL =================================================================== --}}

    <div class="modal fade" tabindex="-1" role="dialog" id="teachercollegesched" style="">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Teacher Schedule &nbsp;&nbsp;<input type="hidden" id="teacherid"><input type="hidden" id="daterangeactive"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="select2 form-control form-control-sm text-uppercase" name="period" placeholder="" id="assigning_type">
                                <option value="1">Per Subject</option>
                                <option value="2">Per Schedule</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-12">
                            <table width="100%" class="table table-sm table-bordered table-head-fixed " id="teacherschedule_datatable"  style="font-size: 16px">
                                <thead>
                                    <tr>
                                        <th width="35%" class="align-middle">Subject Name</th>
                                        <th width="10%" class="text-center align-middle"># Units <br> Lec</th>
                                        <th width="10%" class="text-center align-middle"># Units <br> Lab</th>
                                        <th width="10%" class="text-center align-middle">Hourly <br> Rate</th>
                                        <th width="15%" class="text-center align-middle">Type</th>
                                        <th width="10%" class="text-center align-middle">Total</th>
                                        <th width="10%" class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div> --}}
                        <div class="col-md-12 teacherschedule_table"></div>
                        <div class="col-md-12 teacherschedule_tableperschedule"></div>
                        <div class="col-md-8 datepick" style="height: 300px;">
                            <label for="">Select Range</label> <br>
                            {{-- <input type="text" id="daterange" name="daterange" /> --}}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right input-date" id="daterange" readonly>
                            </div>

                            <div class="attendance_tardy">

                            </div>

                        </div>
                        <div class="col-md-4 approvetardy" style="height: 300px;">
                            
                            <label for="">Total Tardiness</label>
                            <table width="100%" class="table table-sm table-bordered" id="tardy_datatable"  style="font-size: 14.5px">
                                <thead>
                                    <tr>
                                        <th width="50%" class="p-1 align-middle">Type</th>
                                        <th width="20%" class="p-1 text-center align-middle">Mins</th>
                                        <th width="30%" class="p-1 text-center align-middle"><input type="checkbox" class="approvetardyall" id="approvetardyall"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>


                            <label for="">Approved</label>
                            <table width="100%" class="table table-sm table-bordered" id="approvedtardy_datatable"  style="font-size: 14.5px">
                                <thead>
                                    <tr>
                                        <th width="50%" class="p-1 align-middle">Type</th>
                                        <th width="20%" class="p-1 text-center align-middle">Mins</th>
                                        <th width="30%" class="p-1 text-center align-middle">{{-- <input type="checkbox" class="disapprovetardyall" id="disapprovetardyall"> --}}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="subject_container"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

{{-- ======================================================================================================================================= --}}


    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-sm-12">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input class="selectionstatus" type="checkbox" id="persubject" value="1">
                                <label for="persubject">
                                    PER SUBJECT
                                </label>
                            </div>
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            <div class="icheck-primary d-inline">
                                <input class="selectionstatus" type="checkbox" id="persched" value="2">
                                <label for="persched">
                                    PER SCHEDULE
                                </label>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-left">
                    {{-- <div class="col-sm-12 text-right">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fas fa-sync"></i> Sync Schedules</button>
                    </div> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 16px">
                        <thead>
                                <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="53%">Employee</th>
                                    <th class="text-center" width="15%">Subjects</th>
                                    {{-- <th class="text-center" width="15%">Salary /hr</th> --}}
                                    <th class="text-center" width="10%">Action</th>
                                </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
  
@endsection
@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('#assigning_type').select2()
        
        $('#daterange').daterangepicker({
            opens: 'right',
            autoUpdateInput: false,
            locale: {
                format: 'MM/DD/YYYY'
                
            }
        });

        // variable calls
        var syid = @json($sy->id);
        var semid = @json($semester->id);
        
        var all_sched = [];
        var employee_list = [];
        var teacherloads = [];
        var teacherloads2 = [];
        var attendancetardy = [];
        var tardy = [];
        var approved_tardy = [];
        var perdaysubjrl = [];
        var perdaysubjol = [];
        var perdaysubjpt = [];
        var regularloaddata = [];
        var overloaddata = [];
        var parttimeloaddata = [];
        // ============================================================ Modal Close =====================================================================

        $('#teachercollegesched').on('hidden.bs.modal', function (e) {
           $('#daterange').val('')
           $('#assigning_type').val(1).change();
           attendancetardy = []
           filtertardy = []
           subjecttardy_table(attendancetardy)
        //    subjecttardy_datatable(attendancetardy)
           total_datatable(filtertardy)
        });

        // ==============================================================================================================================================

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
        
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // ============================================================= function calls =================================================================
        // allsched()
        load_activatedrange()
        // ==============================================================================================================================================
        
        // ============================================================= Modal Close ====================================================================
    
        // ========================================================= click event sections ===============================================================
        $(document).on('click', '.viewsched', function(){
            var teacherid = $(this).attr('data-id');
            $('#teacherid').val(teacherid);
            
            if ($('.selectionstatus').is(':checked')) {
                var dataid = $('.selectionstatus:checked').val();
                $('#assigning_type').val(dataid).change()
            } else {
               var dataid = 0;
            }
            
            

            var employeesched = all_sched.filter(x=>x.teacherid == teacherid);
            
            if (activerange) {
                var startDate = moment(activerange.datefrom, 'YYYY-MM-DD').format('MM/DD/YYYY');
                var endDate = moment(activerange.dateto, 'YYYY-MM-DD').format('MM/DD/YYYY');
                var defaultDateRange = startDate + ' - ' + endDate;

                if (activerange.datefrom == null || activerange.datefrom == 'undefined') {
                    $('#daterange').attr('disabled', true);
                } else {
                    $('#daterange').val(defaultDateRange);
                }
                
                
                loadteacherssched(teacherid, startDate, endDate, dataid)
            }
            // teacherschedule_datatable(employeesched)
            loadteachersschedule(teacherid)
            load_totaltardines(teacherid,dataid)

            // $('.teacherschedule_tableperschedule').attr('hidden', true)
            $('#teachercollegesched').modal('show')
        })

        // $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        //     var startDate = picker.startDate.format('YYYY-MM-DD');
        //     var endDate = picker.endDate.format('YYYY-MM-DD');
        //     var teacherid = $('#teacherid').val()
        //     // Log the selected date range
        //     console.log('Selected Date Range: ' + startDate + ' - ' + endDate);

        //     $(this).val(startDate + ' - ' + endDate);

        //     loadteacherssched(teacherid, startDate, endDate)

        // });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            var daterange = $('#daterange').val();
            var teacherid = $('#teacherid').val()

            var dates = daterange.split(' - ');
            // Use new variable names to avoid confusion
            var startDate = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            var endDate   = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            loadteacherssched(teacherid, startDate, endDate)

        });


        $(document).on('change', '#selectsubjecttype', function(){
            var optionstatus = $(this).val()
            var subjectid = $(this).attr('subjectid')
            var teacherid = $('#teacherid').val()

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loading/changesubjtype",
                data: {
                    optionstatus : optionstatus,
                    subjectid : subjectid,
                    teacherid : teacherid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('change', '#selectsubjecttypepersubj', function(){
            var optionstatus = $(this).val()
            var subjectid = $(this).attr('subjectid')
            var day = $(this).attr('day')
            var code = $(this).attr('code')
            var stime = $(this).attr('stime')
            var etime = $(this).attr('etime')
            var teacherid = $('#teacherid').val()
        
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loading/changesubjtypepersubj",
                data: {
                    optionstatus : optionstatus,
                    subjectid : subjectid,
                    teacherid : teacherid,
                    code : code,
                    day : day,
                    stime : stime,
                    etime : etime
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })

        

        $(document).on('click', '#btn-payroll-dates-submit', function(){
            var valid_data = true;
            var daterange = $('#reservationnew').val();
   
            var dates = daterange.split(' - ');

            // Use new variable names to avoid confusion
            var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            var dateto   = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            if (daterange == '' || daterange == null) {
                Toast.fire({
                    type: 'error',
                    title: 'Select Date Range'
                })
                valid_data = false;
            }

            if (valid_data) {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/parttime/adddaterange",
                    data: {
                        datefrom : datefrom,
                        dateto : dateto
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                            }else{
                            load_activatedrange()
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            }
        })

        $(document).on('click', '#close_range', function(){
            var status = 1
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/closedaterange",
                data: {
                    status: status
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        load_activatedrange()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('click', '.approvetardyall', function(){
            var teacherid = $('#teacherid').val()
            var dataid = $('#assigning_type').val()

            if ($(this).is(':checked')) {
                $('.approvetardy').prop('checked', true);

                var checkedData = [];

                $('.approvetardy:checked').each(function() {
                    var type = $(this).data('type');
                    var minutes = $(this).attr('minutes');

                    // Push the data to the array
                    checkedData.push({ type: type, minutes: minutes });
                });

                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/parttime/savetardy",
                    data: {
                        checkedData : checkedData,
                        teacherid : teacherid,
                        dataid : dataid
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        $('.approvetardyall').prop('checked', false)
                        $('.approvetardy').prop('checked', false)
                        load_totaltardines(teacherid,dataid)
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                    }
                });

            } else {
                $('.approvetardy').prop('checked', false)
            }
        })

        $(document).on('click', '#approvetardy', function(){
            var teacherid = $('#teacherid').val()
            var type = $(this).data('type');
            var minutes = $(this).attr('minutes');
            var dataid = $('#assigning_type').val()
            var daterangeactive = $('#daterangeactive').val()
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/approvepertype",
                data: {
                    teacherid : teacherid,
                    type : type,
                    minutes : minutes,
                    daterangeactive: daterangeactive,
                    dataid: dataid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                    })
                    }else{
                        $('.approvetardy').prop('checked', false)
                        load_totaltardines(teacherid,dataid)
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })


        $(document).on('click', '#disapprovetardy', function(){
            var teacherid = $('#teacherid').val()
            var rid = $(this).attr('data-id');
            var type = $(this).data('type');
            var minutes = $(this).attr('minutes');
            var dataid = $('#assigning_type').val()

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/disapprovepertype",
                data: {
                    teacherid : teacherid,
                    type : type,
                    minutes : minutes,
                    rid: rid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                    })
                        }else{
                        load_totaltardines(teacherid,dataid)
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('mouseover', '.regularsubj', function(){
            var day = $(this).attr('data-day');
            regularsubjects_table(regularloaddata, day)
        })

        $(document).on('mouseout', '.regularsubj', function(){
            var day = $(this).attr('data-day');

            $('.subject_container').empty();
        })

        $(document).on('mouseover', '.overloadsubj', function(){
            var day = $(this).attr('data-day');

            regularsubjects_table(overloaddata, day)
        })

        $(document).on('mouseout', '.overloadsubj', function(){
            var day = $(this).attr('data-day');

            $('.subject_container').empty();
        })

        $(document).on('mouseover', '.parttimesubj', function(){
            var day = $(this).attr('data-day');

            regularsubjects_table(parttimeloaddata, day)
        })

        $(document).on('mouseout', '.parttimesubj', function(){
            var day = $(this).attr('data-day');
            $('.subject_container').empty();
        })
      
        $(document).on('change', '#assigning_type', function(){
            var dataid = $(this).val()
            var teacherid = $('#teacherid').val()
            var daterange = $('#reservationnew').val();
            var dates = daterange.split(' - ');
            // Use new variable names to avoid confusion
            var startDate = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            var endDate   = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            loadteacherssched(teacherid, startDate , endDate, dataid)
            select_assigntype(dataid)
            load_totaltardines(teacherid,dataid)
            // if (dataid == 1) {
            //     $('.teacherschedule_table').attr('hidden', false)
            //     $('.teacherschedule_tableperschedule').attr('hidden', true)
                
            //     $('.datepick').attr('hidden', false)
            //     $('.approvetardy').attr('hidden', false)
            // } else {
            //     $('.teacherschedule_table').attr('hidden', true)
            //     $('.teacherschedule_tableperschedule').attr('hidden', false)

            //     $('.datepick').attr('hidden', true)
            //     $('.approvetardy').attr('hidden', true)
            // }
        })

        // mao ning magpili ka kong unsa nga setup if per subject ba or per schedule
        $(document).on('click', '#persubject', function(){
            $('#persubject').prop('checked', true)
            $('#persched').prop('checked', false)
            var status = 1;
            changestatus(status)
        })
        $(document).on('click', '#persched', function(){
            $('#persched').prop('checked', true)
            $('#persubject').prop('checked', false)
            var status = 2;
            changestatus(status)
        })
        // ========================================================== Functions =========================================================================
        function load_employees(){
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loademployees",
                success: function (data) {
                    employee_list = data
                    load_employeedatatable()
                    $(".swal2-container").remove();
                }
            });
        }
        function select_assigntype(dataid){
        
            if (dataid == 1) {
                $('.teacherschedule_table').attr('hidden', false)
                $('.teacherschedule_tableperschedule').attr('hidden', true)
                
                $('.datepick').attr('hidden', false)
                $('.approvetardy').attr('hidden', false)
            } else {
                
                $('.teacherschedule_table').attr('hidden', true)
                $('.teacherschedule_tableperschedule').attr('hidden', false)

                $('.datepick').attr('hidden', false)
                $('.approvetardy').attr('hidden', false)
            }
        }

        // this function is for the employee list datatable
        function load_employeedatatable(){

            $('#employee_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                data: employee_list,
                columns : [
                    {"data" : 'full_name'},
                    {"data" : null},
                    // {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var index = row + 1; // Start indexing from 1
                            var text = '<span>&nbsp;&nbsp;' + index + '</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var employeesched = all_sched.filter(x=>x.teacherid == rowData.id);
                            var subjcount = '<span class="text-danger">-</span>'
                            if (employeesched.length > 0) {
                                subjcount = '<span class="text-primary">'+employeesched[0].teacherdata.length+'</span>'
                            }
                            var text = '<a href="javascript:void(0)" class="text-primary" id="subject_loads" teacherid="'+rowData.id+'">'+subjcount+'</a>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }

                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            
                            var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                                '<a href="javascript:void(0)" id="viewsched'+rowData.id+'" class="mb-0 viewsched" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-user-cog text-primary"></i></a>' +
                                '<input type="hidden" class="" style="width: 18px; height: 18px;">' +
                                '</div>';
                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
            
            var label_text = $($('#employee_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<div class="row">
                                    <div class="col-md-8 text-right">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm float-right input-range" id="reservationnew" readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-sm btn-success btn-payroll-dates-submit" id="btn-payroll-dates-submit" data-action="new">
                                                    <i class="fa fa-share"></i> Activate Range
                                                </button>
                                            </div><br>
                                        </div>
                                        <span><a style="cursor: pointer;" href="javascript:void(0)" id="close_range"><u>Close this Range</u></a></span>
                                    </div>
                            </div>
                            `

            if (activerange && activerange.datefrom && activerange.dateto) {
                var startDate = moment(activerange.datefrom, 'YYYY-MM-DD').format('MM/DD/YYYY');
                var endDate = moment(activerange.dateto, 'YYYY-MM-DD').format('MM/DD/YYYY');
                var defaultDateRange = startDate + ' - ' + endDate;
                $('#reservationnew').val(defaultDateRange);
            }

            $('#reservationnew').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
                locale: {
                    format: 'MM/DD/YYYY'
                }
            }, function(start, end, label) {
                $('#reservationnew').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            });

            // This event is triggered when the date range is changed
            $('#reservationnew').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });
        }

        function teacherschedule_datatable(teacherloads,teacherloads2) {
            $('.teacherschedule_table').empty();
            // var subjectdata = employeesched[0].teacherdata;
         
            var teacherschedtable = $('.teacherschedule_table');

            var table = $('<table width="100%" class="table table-bordered" style="font-size: 15px;">');

            table.append(`<thead>
                                <tr style="font-size: 14px;">
                                <th width="2%"></th>
                                <th class="text-left align-middle" width="48%">SUBJECTS</th>
                                <th class="text-center align-middle" width="13%"># OF HOURS <br> LECTURE</th>
                                <th class="text-center align-middle" width="13%"># OF HOURS <br> LAB</th>
                                <th class="text-center align-middle" width="10%">HOURLY <br> RATE</th>
                                <th class="text-center align-middle" width="14%">TYPE</th>
                                </tr>
                            </thead>`);

            var body = $('<tbody>');

            $.each(teacherloads2, function (index, item) {

                // $.each(teacherloads2, function (index, item2) {
                //     console.log(item2);
                // })
                
                var option = '0'; // Default to empty
                if (item.subjtype == 1) {
                    option = '1'; // Set to Regular
                } else if (item.subjtype == 2) {
                    option = '2'; // Set to Overload
                } else if (item.subjtype == 3) {
                    option = '3'; // Set to Parttime
                }

                var rowData = `<tr>
                                <td class="text-center align-middle p-0">${index + 1}</td>
                                <td class="align-middle p-1" style="padding-left: 5px!important; font-weight: 500; font-size: 14px!important">${item.subjectdesc}</td>
                                <td class="text-center align-middle p-0">${item.lecunits}</td>
                                <td class="text-center align-middle p-0">${item.labunits}</td>
                                <td class="text-center align-middle p-0"></td>
                                <td class="text-center align-middle p-0">
                                    <div class="form-group" style="margin: 0 !important; padding-left: 10px; padding-right: 10px;">
                                        <select class="form-control form-control-sm" id="selectsubjecttype" subjectid="${item.subjid}" style=" border: none!important;">
                                            <option value="0" ${option === '0' ? 'selected' : ''}></option>
                                            <option value="1" ${option === '1' ? 'selected' : ''}>Regular Load</option>
                                            <option value="2" ${option === '2' ? 'selected' : ''}>Overload</option>
                                            <option value="3" ${option === '3' ? 'selected' : ''}>Emergency Load</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="text-center align-middle p-0">
                                    
                                </td>
                            </tr>`;
                body.append(rowData);
            });

            table.append(body);
            $(teacherschedtable).append(table);
        }

        // mao ning per schedule 
        function teacherschedule_datatableperschedule(teacherloads,teacherloads2) {
            $('.teacherschedule_tableperschedule').empty();
            var teacherschedtable = $('.teacherschedule_tableperschedule');
            var table = $('<table width="100%" class="table table-bordered" style="font-size: 15px;">');

            table.append(`<thead>
                                <tr style="font-size: 14px;">
                                <th width="2%"></th>
                                <th class="text-left align-middle" width="38%">SUBJECTS</th>
                                <th class="text-center align-middle" width="10%"># OF HOURS <br> LECTURE</th>
                                <th class="text-center align-middle" width="10%"># OF HOURS <br> LAB</th>
                                <th class="text-center align-middle" width="8%">HOURLY <br> RATE</th>
                                <th colspan="3" class="text-center align-middle" width="31%">Sched | Time | TYPE</th>
                                </tr>
                            </thead>`);

            var body = $('<tbody>');

            $.each(teacherloads2, function (index, item) {
                var scheddata = '';  // Declare scheddata here
                // console.log('item.schedule');
                // console.log(item.schedule);
                // console.log('item.schedule');

                


                $.each(item.schedule, function (index, item2) {
                    var option = '0'; // Default to empty
                    if (item2.subjtype == 1) {
                        option = '1'; // Set to Regular
                    } else if (item2.subjtype == 2) {
                        option = '2'; // Set to Overload
                    } else if (item2.subjtype == 3) {
                        option = '3'; // Set to Parttime
                    }
                    // var subjcode = item2.schedules.code;
                    scheddata += `
                        <tr>
                            <td class="p-0 text-right">
                                <table width="100%" class="p-0 table" style="font-size: 13px; margin: 0!important">
                                    <tr>
                                        <td width="20%" class="p-0" style="padding-right: 10px!important;vertical-align: middle;"><span>${item2.dayname.substring(0, 3)} </span></td>
                                        <td width="40%" class="p-0" style="padding-right: 10px!important;vertical-align: middle;"><span>${item2.starttime} - ${item2.endtime}</span></td>
                                        <td width="40%" class="p-0">
                                            <div class="form-group" style="margin: 0 !important; margin-left: auto;">
                                                <select class="form-control form-control-sm" id="selectsubjecttypepersubj" day="${item2.day}" code="${item2.schedules.code}" stime="${item2.starttime}" etime="${item2.endtime}" subjectid="${item.subjid}" style="border: none!important; background-color: inherit">
                                                    <option value="0" ${option === '0' ? 'selected' : ''}></option>
                                                    <option value="1" ${option === '1' ? 'selected' : ''}>Regular Load</option>
                                                    <option value="2" ${option === '2' ? 'selected' : ''}>Overload</option>
                                                    <option value="3" ${option === '3' ? 'selected' : ''}>Emergency Load</option>
                                                </select>
                                            </div>    
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    `;
                });
                

                // $.each(teacherloads2, function (index, item2) {
                //     console.log(item2);
                // })
                
                // <div class="bg-primary"style="display: flex;align-items: center">
                //     <div class="form-group" style="margin: 0 !important;">
                //         <small>${item2.dayname.substring(0, 3)} </small>|| <small >${item2.starttime} - ${item2.endtime}</small>
                //     </div>
                //     <div class="form-group" style="margin: 0 !important; margin-left: auto;">
                //         <select class="form-control form-control-sm" id="selectsubjecttype" subjectid="${item.subjid}" style=" border: none!important;">
                //             <option value="0" ${option === '0' ? 'selected' : ''}></option>
                //             <option value="1" ${option === '1' ? 'selected' : ''}>Regular</option>
                //             <option value="2" ${option === '2' ? 'selected' : ''}>Overload</option>
                //             <option value="3" ${option === '3' ? 'selected' : ''}>Parttime</option>
                //         </select>
                //     </div>    
                // </div>

                // var rowData = `<tr>
                //                 <td class="text-center align-middle p-0">${index + 1}</td>
                //                 <td class="align-middle p-1" style="padding-left: 5px!important; font-weight: 500; font-size: 14px!important">${item.subjectdesc}</td>
                //                 <td class="text-center align-middle p-0">${item.lecunits}</td>
                //                 <td class="text-center align-middle p-0">${item.labunits}</td>
                //                 <td class="text-center align-middle p-0"></td>
                //                 <td colspan="3"  class="text-center align-middle p-0">
                //                     <table width="100%">
                //                         ${scheddata}
                //                     </table>
                //                 </td>
                //                 <td class="text-center align-middle p-0">
                                    
                //                 </td>
                //             </tr>`;
                var rowData = `<tr ${index % 2 === 1 ? 'style="background-color: #005dff0a;"' : ''}>
                                    <td class="text-center align-middle p-0">${index + 1}</td>
                                    <td class="align-middle p-1" style="padding-left: 5px!important; font-weight: 500; font-size: 14px!important">${item.subjectdesc}</td>
                                    <td class="text-center align-middle p-0">${item.lecunits}</td>
                                    <td class="text-center align-middle p-0">${item.labunits}</td>
                                    <td class="text-center align-middle p-0"></td>
                                    <td colspan="3"  class="text-center align-middle p-0">
                                        <table width="100%">
                                            ${scheddata}
                                        </table>
                                    </td>
                                    <td class="text-center align-middle p-0">
                                    
                                    </td>
                                </tr>`;

                body.append(rowData);
            });

            table.append(body);
            $(teacherschedtable).append(table);
        }

        function subjecttardy_table(attendancetardy){
            $('.attendance_tardy').empty();
         
            var attendancedtable = $('.attendance_tardy');

            var table = $('<table width="100%" class="table" style="font-size: 13px; ">');

            table.append(`<thead>
                                <tr style="font-size: 14px;">
                                    <th class="p-1 text-left align-middle" width="25%">Dates</th>
                                    <th class="p-1 text-center align-middle" width="37.5%">Late</th>
                                    <th class="p-1 text-center align-middle" width="37.5%">Undertime</th>
                                </tr>
                            </thead>`);

            var body = $('<tbody>');

            $.each(attendancetardy, function (index, item) {
                console.log('item');
                console.log(item);
                console.log('item');
                if (item.timeinam == "" && item.timeoutpm == "") {
                    var time = '<br><small class="text-danger">No Tap</small>';
                } else if(item.timeinam == "" && item.timeoutpm != ""){
                    var convertedTimeInAM = 'No Tap';
                    var convertedTimeOutPM = moment(item.timeoutpm, 'HH:mm:ss').format('hh:mm A');
                    var time = '<br><small><span class="text-danger">'+convertedTimeInAM+'</span> - <span class="text-info">'+convertedTimeOutPM+'</span></small>';
                } else if(item.timeinam != "" && item.timeoutpm == ""){
                    var convertedTimeInAM = moment(item.timeinam, 'HH:mm:ss').format('hh:mm A');
                    // var convertedTimeOutPM = 'No Tap';
                    var convertedTimeOutPM = moment(item.timeoutam, 'HH:mm:ss').format('hh:mm A');
                    var time = '<br><small class="text-info"><span>'+convertedTimeInAM+'</span> - <span class="text-info">'+convertedTimeOutPM+'</span></small>';
                }else {
                    var convertedTimeInAM = moment(item.timeinam, 'HH:mm:ss').format('hh:mm A');
                    var convertedTimeOutPM = moment(item.timeoutpm, 'HH:mm:ss').format('hh:mm A');
                    var time = '<br><small class="text-info">'+convertedTimeInAM+' - '+convertedTimeOutPM+'</small>';
                }
                console.log(time);

                if (item.regularlate > 0) {
                    var rl = '<span class="badge badge-light regularsubj hover-color" data-day="'+item.day+'">RL = '+item.regularlate+' m</span>'
                } else {
                    var rl = ''
                }

                // for Overload Load
                if (item.overloadlate > 0) {
                    var ol = '<span class="badge badge-light overloadsubj hover-color" data-day="'+item.day+'">OL = '+item.overloadlate+' m</span>'
                } else {
                    var ol = ''
                }

                // for Part Time Load
                if (item.parttimelate > 0) {
                    var pt = '<span class="badge badge-light parttimesubj hover-color" data-day="'+item.day+'">EL = '+item.parttimelate+' m</span>'
                } else {
                    var pt = ''
                }

                if (item.regularundertime > 0) {
                    var ru = '<span class="badge badge-light regularsubj hover-color" data-day="'+item.day+'">RU = '+item.regularundertime+' m</span>'
                } else {
                    var ru = ''
                }

                // for Overload Load
                if (item.overloadundertime > 0) {
                    var ou = '<span class="badge badge-light overloadsubj hover-color" data-day="'+item.day+'">OU = '+item.overloadundertime+' m</span>'
                } else {
                    var ou = ''
                }

                // for Part Time Load
                if (item.parttimeundertime > 0) {
                    var pu = '<span class="badge badge-light parttimesubj hover-color" data-day="'+item.day+'">EU = '+item.parttimeundertime+' m</span>'
                } else {
                    var pu = ''
                }

                if (item.regularabsent > 0) {
                    var ra = '<span class="badge badge-light regularsubj hover-color text-danger" data-day="'+item.day+'">RA = '+item.regularabsent+' m</span>'
                } else {
                    var ra = ''
                }
                if (item.overloadabsent > 0) {
                    var oa = '<span class="badge badge-light overloadsubj hover-color text-danger" data-day="'+item.day+'">OA = '+item.overloadabsent+' m</span>'
                } else {
                    var oa = ''
                }
                if (item.parttimeabsent > 0) {
                    var pa = '<span class="badge badge-light parttimesubj hover-color text-danger" data-day="'+item.day+'">EA = '+item.parttimeabsent+' m</span>'
                } else {
                    var pa = ''
                }

                if (item.holiday == 1) {
                    var rowData1 = `<tr style="background-color: #fff;">
                                    <td class="align-middle p-1 text-left" style="padding-left: 5px!important; font-size: 13px!important"><span>${item.date}</span> <small style="font-size: 14px; font-weight: 500">${item.day}</small>${time}</td>
                                    <td colspan="2" class="align-middle p-1 text-center" style="padding-left: 5px!important; font-size: 15px!important"><span class="badge badge-info">${item.holidayname}</span></td>
                                </tr>`;
                }else if (item.appliedleave == 1) {
                    var rowData1 = `<tr style="background-color: #fff;">
                                    <td class="align-middle p-1 text-left" style="padding-left: 5px!important; font-size: 13px!important"><span>${item.date}</span> <small style="font-size: 14px; font-weight: 500">${item.day}</small>${time}</td>
                                    <td colspan="2" class="align-middle p-1 text-center" style="padding-left: 5px!important;">
                                        <table width="100%" style=" border-top: none !important;"">
                                            <tr>
                                                <td style="border-top: none !important;""><span class="badge badge-info">${item.leavetype}</span> <br> <span style="font-size: 13px!important;">${item.daycoverd}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle p-1 text-center" style="padding-left: 5px!important; font-size: 15px!important; border-top: none !important;"><span>${rl}  ${ol} ${pt} ${ru}  ${ou} ${pu} ${ra}  ${oa} ${pa}</span></td>
                                            </tr>
                                        </table>    
                                    </td>
                                </tr>
                                `;
                } else if (item.status == 2 && item.appliedleave != 1) {
                    var rowData1 = `<tr style="background-color: #fff;">
                                    <td class="align-middle p-1 text-left" style="padding-left: 5px!important; font-size: 13px!important"><span>${item.date}</span> <small style="font-size: 14px; font-weight: 500">${item.day}</small>${time}</td>
                                    <td colspan="2" class="align-middle p-1 text-center" style="padding-left: 5px!important;"><span>${ra}  ${oa} ${pa}</span></td>
                                </tr>`;
                }
                else {
                    var rowData1 = `<tr style="background-color: #fff;">
                                    <td class="align-middle p-1 text-left" style="padding-left: 5px!important; font-size: 13px!important"><span>${item.date}</span> <small style="font-size: 14px; font-weight: 500">${item.day}</small>${time}</td>
                                    <td class="align-middle p-1 text-center" style="padding-left: 5px!important; font-size: 15px!important"><span>${rl}  ${ol} ${pt}</span></td>
                                    <td class="align-middle p-1 text-center" style="padding-left: 5px!important; font-size: 15px!important"><span>${ru}  ${ou} ${pu}</span></td>
                                </tr>`;

                }

                
                body.append(rowData1);
            });

            // $.each(collegesubj, function (index, item) {

            //     var convertedtimestart = moment(item.timestart, 'HH:mm:ss').format('hh:mm A');
            //     var convertedtimeend = moment(item.timeend, 'HH:mm:ss').format('hh:mm A');
            //     var rowData2 = `<tr>
            //                         <td class="align-middle p-1" style="padding-left: 5px!important; font-size: 13px!important;">${item.subjDesc}</td>
            //                         <td class="align-middle p-1" style="padding-left: 5px!important; font-size: 13px!important">${convertedtimestart} - ${convertedtimeend}</td>
            //                     </tr>`;
            //     body.append(rowData2);
            // });

            table.append(body);
            $(attendancedtable).append(table);
        }


        // function subjecttardy_datatable(attendancetardy){

        //     $('#tardiness_datatable').DataTable({
        //         destroy: true,
        //         lengthMenu: [16],
        //         lengthChange: false,
        //         scrollX: false,
        //         autoWidth: false,
        //         order: false,
        //         searching: false,
        //         data: attendancetardy,
        //         columns : [
        //             {"data" : null},
        //             {"data" : null},
        //             {"data" : null}
        //         ], 
        //         columnDefs: [
        //             {
        //                 'targets': 0,
        //                 'orderable': false, 
        //                 'createdCell': function (td, cellData, rowData, row, col) {
        //                     if (rowData.amin == null && rowData.amout == null) {
        //                         var time = '';
        //                     } else {
        //                         var convertedTimeInAM = moment(rowData.timeinam, 'HH:mm:ss').format('hh:mm A');
        //                         var convertedTimeOutPM = moment(rowData.timeoutpm, 'HH:mm:ss').format('hh:mm A');
        //                         var time = '<br><small class="text-info">'+convertedTimeInAM+' - '+convertedTimeOutPM+'</small>';
        //                     }
        //                     var text = '<span>'+rowData.date+'</span> <small style="font-size: 14px; font-weight: 500">'+rowData.day+'</small>'+time+'';
        //                     $(td)[0].innerHTML = text;
        //                     $(td).addClass('align-middle text-left');
        //                 }
        //             },
        //             {
        //                 'targets': 1,
        //                 'orderable': false, 
        //                 'createdCell':  function (td, cellData, rowData, row, col) {
        //                     // for Regular Load
        //                     if (rowData.regularlate > 0) {
        //                         var rl = '<span class="badge badge-light regularsubj hover-color" data-day="'+rowData.day+'">RL = '+rowData.regularlate+' m</span>'
        //                     } else {
        //                         var rl = ''
        //                     }

        //                     // for Overload Load
        //                     if (rowData.overloadlate > 0) {
        //                         var ol = '<span class="badge badge-light overloadsubj hover-color" data-day="'+rowData.day+'">OL = '+rowData.overloadlate+' m</span>'
        //                     } else {
        //                         var ol = ''
        //                     }

        //                     // for Part Time Load
        //                     if (rowData.parttimelate > 0) {
        //                         var pt = '<span class="badge badge-light parttimesubj hover-color" data-day="'+rowData.day+'">PT = '+rowData.parttimelate+' m</span>'
        //                     } else {
        //                         var pt = ''
        //                     }


        //                     // var text = '<div><span class="" style="font-size: 16px!important;">'+rl+'</span> <span style="font-size: 16px!important;">'+ol+'</span> <span style="font-size: 16px!important;">'+pt+'</span></div>';
        //                     var text = '<div><span style="font-size: 16px!important; cursor: pointer;">'+rl+' '+ol+' '+pt+'</span></div>';
        //                     $(td)[0].innerHTML =  text
        //                     $(td).addClass('align-middle  text-left')
        //                 }
        //             },
        //             {
        //                 'targets': 2,
        //                 'orderable': false, 
        //                 'createdCell':  function (td, cellData, rowData, row, col) {
        //                     // for Regular Load
        //                     if (rowData.regularundertime > 0) {
        //                         var rl = '<span class="badge badge-light regularsubj hover-color" data-day="'+rowData.day+'">RL = '+rowData.regularundertime+' m</span>'
        //                     } else {
        //                         var rl = ''
        //                     }

        //                     // for Overload Load
        //                     if (rowData.overloadundertime > 0) {
        //                         var ol = '<span class="badge badge-light overloadsubj hover-color" data-day="'+rowData.day+'">OL = '+rowData.overloadundertime+' m</span>'
        //                     } else {
        //                         var ol = ''
        //                     }

        //                     // for Part Time Load
        //                     if (rowData.parttimeundertime > 0) {
        //                         var pt = '<span class="badge badge-light parttimesubj hover-color" data-day="'+rowData.day+'">PT = '+rowData.parttimeundertime+' m</span>'
        //                     } else {
        //                         var pt = ''
        //                     }
        //                     // var text = '<div><span>'+rl+'</span><br><span>'+ol+'</span><br><span>'+pt+'</span></div>';
        //                     var text = '<div><span style="font-size: 16px!important;cursor: pointer;">'+rl+' '+ol+' '+pt+'</span></div>';
        //                     $(td)[0].innerHTML =  text
        //                     $(td).addClass('align-middle  text-left')
        //                 }
        //             }
        //         ]
        //     })
        // }
        
        function total_datatable(filtertardy){
            $('#tardy_datatable').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                searching: false,
                paging: false,
                info: false,
                data: filtertardy,
                columns : [
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span>'+rowData.type+'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span>'+rowData.minutes+'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = `<div class="">
                                        <input type="checkbox" class="approvetardy" id="approvetardy" data-type="${rowData.type}"  minutes="${rowData.minutes}">
                                    </div>`;
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    }
                ]
            })
        }

        function approvedtardy_datatable(tardy){
            $('#approvedtardy_datatable').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                searching: false,
                paging: false,
                info: false,
                data: approved_tardy,
                columns : [
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span>'+rowData.type+'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span>'+rowData.totalminutes+'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = `<div class="">
                                        <input type="checkbox" class="disapprovetardy"  id="disapprovetardy" data-type="${rowData.type}" data-id="${rowData.id}" data-type="${rowData.type}"  minutes="${rowData.totalminutes}">
                                    </div>`;
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    }
                ]
            })
        }

        function regularsubjects_table(regularloaddata, day){
            
            var perdaydata = regularloaddata.filter(x=>x.day == day)
            var collegesubj = perdaydata[0].schedules
           
            // console.log('perdaydata');
            $('.subject_container').empty();
            // var subjectdata = employeesched[0].teacherdata;
         
            var teacherschedtable = $('.subject_container');

            var table = $('<table width="100%" class="table table-bordered" style="font-size: 13px; background-color: #00e7ff26;">');

            table.append(`<thead>
                                <tr style="font-size: 14px;">
                                    <th class="p-1 text-left align-middle" width="70%">SUBJECTS</th>
                                    <th class="p-1 text-center align-middle" width="30%">Time</th>
                                </tr>
                            </thead>`);

            var body = $('<tbody>');

            $.each(perdaydata, function (index, item) {
                
                var convertedendtime = moment(item.endtime, 'HH:mm:ss').format('hh:mm A');
                var convertedstarttime = moment(item.starttime, 'HH:mm:ss').format('hh:mm A');
                var rowData1 = `<tr style="background-color: #fff;">
                                    <td colspan="2" class="align-middle p-1 text-center" style="padding-left: 5px!important; font-size: 13px!important">Start time : ${convertedstarttime} - End time : ${convertedendtime}</td>
                                </tr>`;
                body.append(rowData1);
            });

            $.each(collegesubj, function (index, item) {

                var convertedtimestart = moment(item.timestart, 'HH:mm:ss').format('hh:mm A');
                var convertedtimeend = moment(item.timeend, 'HH:mm:ss').format('hh:mm A');
                var rowData2 = `<tr>
                                    <td class="align-middle p-1" style="padding-left: 5px!important; font-size: 13px!important;">${item.subjDesc}</td>
                                    <td class="align-middle p-1" style="padding-left: 5px!important; font-size: 13px!important">${convertedtimestart} - ${convertedtimeend}</td>
                                </tr>`;
                body.append(rowData2);
            });

            table.append(body);
            $(teacherschedtable).append(table);
        }

        function allsched(){
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loading/allsched",
                data: {
                    syid: syid,
                    semid: semid,
                },
                success: function (data) {
                    all_sched = data;
                    // console.log(all_sched);
                    load_employees()
                    
                }
            });
        }

        function loadteachersschedule(teacherid){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loading/loadteachersschedule",
                data: {
                    teacherid : teacherid, 
                    syid: syid,
                    semid: semid
                },
                success: function (data) {
                    teacherloads = data.load
                    teacherloads2 = data.teachingloads

                    teacherschedule_datatable(teacherloads,teacherloads2)
                    teacherschedule_datatableperschedule(teacherloads,teacherloads2)
                }
            });
        }

        function loadteacherssched(teacherid, startDate , endDate, dataid){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loading/allschedteacher",
                data: {
                    syid: syid,
                    semid: semid,
                    teacherid : teacherid,
                    startdate : startDate,
                    enddate : endDate,
                    dataid : dataid
                },
                success: function (data) {
                    attendancetardy = data.attendance
                    tardy = data.tardy

                    filtertardy = tardy.filter(x=>x.minutes > 0);

                    if (data.regularload[0]) {
                        regularloaddata = data.regularload[0].schedule;
                    }
                    if (data.overload[0]) {
                        overloaddata = data.overload[0].schedule;
                    }
                    if (data.parttime[0]) {
                        parttimeloaddata = data.parttime[0].schedule;
                    }

                    subjecttardy_table(attendancetardy)
                    // subjecttardy_datatable(attendancetardy)
                    total_datatable(filtertardy)
                }
            });
        }
        
        function load_activatedrange(){
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loadactivedaterange",
                success: function (data) {
                    
                    activerange = data;
                    $('#daterangeactive').val(activerange.id)
                    daterangeactive
                    var status = activerange.setupstatus;
                    if (status == 1) {
                        $('#persubject').prop('checked', true)
                    } else if(status == 2){
                        $('#persched').prop('checked', true)
                    }
                    allsched()
                    
                }
            });
        }

        function load_totaltardines(teacherid,dataid){
        
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/load_totaltardines",
                data: {
                    teacherid : teacherid,
                    dataid : dataid
                },
                success: function (data) {
                    approved_tardy = data
                    console.log(approved_tardy);
                    if (approved_tardy.length == 0) {
                        $('#disapprovetardyall').prop('checked', false)
                    } else {
                        $('#disapprovetardyall').prop('checked', true)
                    }
                    approvedtardy_datatable(approved_tardy)
                    $('.disapprovetardy').prop('checked', true)
                }
            });
        }

        // mao ning function nga tawagon if mag pili si user kong per subject or per schedule nga setup iya gusto
        function changestatus(status){
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/changestatus",
                data: {
                    status : status
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        // ==============================================================================================================================================

    })
</script>
@endsection


