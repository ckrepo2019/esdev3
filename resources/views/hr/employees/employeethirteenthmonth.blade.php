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
                    <h1>13<sup>th</sup> Month</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">13<sup>th</sup> Month</li>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal-generate" style="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">GENERATE 13th MONTH PAY</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-4">
                                <label for="">Select Year</label>
                                <select class="form-control form-control-sm select2" id="year-select"></select>
                            </div>
                            <div class="col-4">
                                <label for="">Select Range</label>
                                <select class="form-control form-control-sm select2" id="range-select"></select>
                            </div>
                            <div class="col-4">
                                <label for="">Select Department</label>
                                <select class="form-control form-control-sm select2" id="department-select"></select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-12 mt-3">
                        <table width="100%" class="table table-sm table-bordered table-head-fixed " id="modal_employee_datatables"  style="font-size: 14px">
                            <thead>
                                    <tr>
                                        <th width="10%">&nbsp;&nbsp;No.</th>
                                        <th width="80%">Employee</th>
                                        <th class="text-center pl-0 pr-0" width="10%">Action</th>
                                    </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary">Export Summary</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- ======================================================================================================================================= --}}


    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 14px">
                        <thead>
                                <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="30%">Employee</th>
                                    <th class="text-left" width="19%">Designation</th>
                                    <th class="text-left" width="14%">Department</th>
                                    <th class="text-center pl-0 pr-0" width="14%">Basic Salary</th>
                                    <th class="text-center pl-0 pr-0" width="11%">13th Mo. Pay</th>
                                    <th class="text-center pl-0 pr-0" width="10%">Action</th>
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
        // INITIALIZE
        $('#year-select').select2()
        $('#range-select').select2()
        $('#department-select').select2()
        
        $('#daterange').daterangepicker({
            opens: 'right',
            autoUpdateInput: false,
            locale: {
                format: 'MM/DD/YYYY'
                
            }
        });

        // VARIABLE CALLS
        var employees = @json($employees);
        var ranges = []
        var departments = []


        // EVENTS
        $(document).on('click', '#generate13thmonthpay', function(){
            $('#modal-generate').modal('show')
        })

        $(document).on('change', '#department-select', function(){
            var depid = $(this).val()
            var employeefiltered = employees.filter(x=>x.departmentid == depid)

            load_employeemodaldatatable(employeefiltered)
        })

        // FUNCTION CALLS
        loadyears()
        loadranges()
        loaddepartments()
        load_employeedatatable()
        load_employeemodaldatatable(employees)
        function loadyears(){
            const currentYear = new Date().getFullYear();
            const numYears = 100;
            const years = [];

            for (let i = 0; i <= numYears; i++) {
                const year = currentYear - i;
                years.push({ id: year, text: year.toString() });
            }

            $('#year-select').empty();
            $('#year-select').append('<option value="">Select Year</option>')
            $('#year-select').select2({
                data: years,
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Select Years',
                }
            });
        }

        function loadranges(){
            $.ajax({
                type: "GET",
                url: "/hr/payrollv3/thirteenthmonth/loadrange",
                success: function (data) {
                    ranges = data
                    console.log(ranges);
                    $('#range-select').empty();
                    $('#range-select').append('<option value="">Select Range</option>')
                    $('#range-select').select2({
                        data: ranges,
                        allowClear: true,
                        placeholder: {
                            id: '',
                            text: 'Select Range',
                            // template: function (data) {
                            //     return '<span style="font-size: 9px; font-weight: normal;">' + data.text + '</span>';
                            // }
                        }
                    });
                    
                }
            });
        }

        function loaddepartments(){
            $.ajax({
                type: "GET",
                url: "/hr/payrollv3/thirteenthmonth/loaddepartments",
                success: function (data) {
                    departments = data
                    $('#department-select').empty();
                    $('#department-select').append('<option value="">Select Department</option>')
                    $('#department-select').select2({
                        data: departments,
                        allowClear: true,
                        placeholder: {
                            id: '',
                            text: 'Select Departments',
                        }
                    });
                    
                }
            });
        }
        

        function load_employeedatatable(){
            $('#employee_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: false,
                order: false,
                data: employees,
                columns : [
                    {"data" : 'full_name'},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
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
                            var text = '<a class="mb-0">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span>' + rowData.designation + '</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span>' + rowData.department + '</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span>' + rowData.amount + '</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span></span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = `<div class="btn-group">
                                <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="tooltip"
                                        title="view" id="edit_leave_btn" data-id="${rowData.id}" data-name="${rowData.leave_type}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>`
                            $(td)[0].innerHTML = text
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })

            var label_text = $($('#employee_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip"
                                            title="generate report" id="generate13thmonthpay"><i class="fas fa-file"></i> Generate 13th Month Pay
                                        </button>`
        }

        function load_employeemodaldatatable(employeefiltered){
            $('#modal_employee_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                data: employeefiltered,
                columns : [
                    {"data" : 'full_name'},
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
                            var text = '<a class="mb-0">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = `<input class="selectionstatus" type="checkbox" id="peremployee" value="1">`
                            $(td)[0].innerHTML = text
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
        }
    })
</script>
@endsection


