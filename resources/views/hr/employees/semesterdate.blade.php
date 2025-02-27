@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
  
    html {
      scroll-behavior: smooth!important;
    }
    /* Add this style to your stylesheet or in a style tag in the head of your HTML */
    .date-table {
        width: 100%;
        border-collapse: collapse;
    }
  
    .date-table th {
        white-space: nowrap; /* Prevent text wrapping in headers */
        min-width: 100px; /* Set a minimum width for each header cell */
        text-align: left; /* Adjust text alignment as needed */
    }
  
    .overloadcontainer {
        overflow-x: auto; /* Enable horizontal scrolling */
        max-width: 100%; /* Ensure the container does not exceed the viewport width */
    }
    #switchlabel{
      top: -5px;
      position: relative;
    }
    input[type="radio"] {
        width: 20px;
        height: 20px;
      }
    /* input:disabled {
      background-color: #fff!important;
      opacity: 1;
      border: 1px solid #a1a1a1!important;
    }
    .form-control:disabled, .form-control[readonly] {
      background-color: #fff!important;
      opacity: 1;
    } */
    .modal-xl {
      max-width: 80%!important;
    }
    
    input[type="checkbox"] {
      margin-right: 0em !important;
    }
    table.dataTable.table-sm > thead > tr > th {
      padding-right: 0px !important;
    }
    .floatinginput {
      position: relative;
      width: 100%;
    } 
  
    .floatinginput input {
      z-index: 1;
      width: 100%;
      padding-top: 4px;
      padding-bottom: 4px;
      padding-left: 17px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      background: rgb(255, 255, 255);
      outline: none;
      color: rgb(66, 66, 66);
      font-size: 15px;
    }
  
    .floatinginput span {
      font-weight: bold;
      position: absolute;
      left: 0;
      padding: 5px;
      padding-left: 8px;
      font-size: 13px;
      pointer-events: none;
      color: #495057;
      text-transform: capitalize;
      transition: 0.2s;
    }
  
    .floatinginput input:valid ~ span, 
    .floatinginput input:focus ~ span {
      color: #007bff;
      transform: translateX(9px) translateY(-7px);
      font-size: 11px;
      padding: 0 10px;
      background: #fff;
    }
    .disabled {
      pointer-events: none; /* Disable mouse events */
      /* opacity: 0.5; */
    }
    .opacityy {
      opacity: 0.5;
    }
    /* .interestselect2 , .select2-container{
      text-align: left!important;
      width: 150px!important;
    } */
    .highlighted {
      background-color: rgba(30, 200, 245, 0.07);
    }
    .custom-checkbox {
      pointer-events: none!important;
    }
    .red-border {
      border: 2px solid red;
    }
    .g-3, .gy-3 {
        --bs-gutter-y: 1rem;
    }
    .g-3, .gx-3 {
        --bs-gutter-x: 1rem;
    }
    .w220 {
        width: 220px;
    }
    @media (min-width: 768px)
    {
    .pe-md-2 {
        padding-right: 0.5rem !important;
    }
    }
    @media (min-width: 576px)
    {
      
    .pe-sm-4 {
        padding-right: 1.5rem !important;
    }
    .text-center {
        text-align: center !important;
    }
    .pe-4 {
        padding-right: 1.5rem !important;
    }
    }
    *, *::before, *::after {
        box-sizing: border-box;
    }
    #table-deductions-standard th, #table-deductions-standard td 
    {
      padding: 3px;
    }
    .table-personal-information td, .table-personal-information th{
      border: 1px solid #ddd;
    }
    #table-personal-accounts td, #table-personal-accounts th{
      padding: 2px;
    }
    .card {
      border: none!important;
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px !important;
    }
  
    .medium {
      font-size: 15px!important;
    }
  
    .select2-container .select2-selection--single {
      height: 37px !important;
    }
  
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      margin-top: -4px!important;
    }
    #sc_amount {
      position: relative;
      top: 20%;
    }
  
    input[type=checkbox]{
      -ms-transform: scale(1.3);
      -moz-transform: scale(1.3);
      -webkit-transform: scale(1.3);
      -o-transform: scale(1.3);
      transform: scale(1.3);
      padding: 20px;  
      }
  
    input[type="checkbox"]{
      content: "\2713";
      display: inline-block;
      font-size: 18px;
      color: #28a745!important; /* Replace with your desired success hex color */
      width: .9em;
      height: .9em;
      vertical-align: middle;
      margin-right: 0.5em;
    }
  
    input[type="checkbox"]:checked + label:before {
      font-weight: bold;
    }
    .non-clickable-checkbox {
      pointer-events: none !important; /* Disable click events */
      opacity: 1 !important; /* Maintain full opacity */
    }
  
    .sticky-header {
      position: sticky;
      top: 60px;
      z-index: 1000;
    }
  
    .card-nav-header {
      border-radius: 10px;
      box-shadow: 0 0 1px rgba(0, 0, 0, 0.13), 0 1px 3px rgba(0, 0, 0, 0.2) !important;
    }
</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>SEMESTER DATE</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">SEMESTER DATE</li>
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
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group" style="">
                        <label for="inputState">Select School Year</label>
                        <select class="form-control form-control-sm select2" id="profileselect-sy"></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="semesterdate_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="20%">Semester</th>
                                    <th class="text-center" width="30%">Start Date</th>
                                    <th class="text-center" width="30%">End Date</th>
                                    <th class="text-center" width="10%">Activate</th>
                                    <th class="text-center" width="10%"></th>
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
       var semesterdate = [];
       var semesterdata = [];
    
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
        loadsy()
        load_semester()
       
        
        // ==============================================================================================================================================
        
        // ============================================================= Modal Close ====================================================================
    
        // ========================================================= click event sections ===============================================================
        $(document).on('click', '.semaddsched', function(){
            var semid = $(this).attr('dataid')
            var syid = $('#profileselect-sy').val()

            var sdate = $('#startdate'+semid).val()
            var edate = $('#enddate'+semid).val()

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/addsemesterdate",
                data: {
                    semid : semid,
                    syid : syid,
                    sdate : sdate,
                    edate : edate
                },
                success: function (data) {
                    
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                        })
                    }else{
                        loadsy()
                        load_semester()
                        Toast.fire({
                        type: 'success',
                        title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('click', '.semdeletesched', function(){
            var semid = $(this).attr('dataid')
            var syid = $('#profileselect-sy').val()

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/deletesemesterdate",
                data: {
                    semid : semid,
                    syid : syid
                },
                success: function (data) {
                    
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                        })
                    }else{
                        loadsy()
                        load_semester()
                        Toast.fire({
                        type: 'success',
                        title: data[0].message
                        })
                    }
                }
            });
        })


        $(document).on('change', '#profileselect-sy', function(){
            var activesyid = $('#profileselect-sy').val()

            load_semester_date(activesyid)
        })

        $(document).on('change', '.activatesemdate , .inactivatesemdate', function(){
            var semid = $(this).attr('semid')
            var activesyid = $('#profileselect-sy').val()
            

            if ($(this).hasClass('inactivatesemdate')) {
                var status = 0;
            } else if ($(this).hasClass('activatesemdate')) {
                var status = 1;
            }
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/activatesemesterdate",
                data: {
                    semid : semid,
                    syid : activesyid,
                    status : status
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                        })
                    }else{
                        loadsy()
                        // load_semester()
                        Toast.fire({
                        type: 'success',
                        title: data[0].message
                        })
                    }
                }
            });
        })
        
        // ========================================================== Functions =========================================================================
        function load_semester(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/loadsemester",
                success: function (data) {
                    semesterdata = data
                    load_semester_table()
                }
            });
        }
        
        function load_semester_date(activesyid){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/loadsemesterdate",
                data: {
                    syid : activesyid
                },
                success: function (data) {
                    semesterdate = data
                    console.log(semesterdate);
                    load_semester_table()
                }
            });
        }

        function load_semester_table(){
            $('#semesterdate_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                searching: false,
                data: semesterdata,
                columns : [
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
                            var text = '<span>'+rowData.semester+'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var sdate = semesterdate.filter(x=>x.semester == rowData.id)
                            if (sdate.length > 0 && sdate[0].sdate) {
                                var date = new Date(sdate[0].sdate);
                                var formattedDate = date.toLocaleDateString('en-CA'); // Adjust the locale as needed
                                var text = '<input type="date" class="form-control" id="startdate' + rowData.id + '" name="startdate" value="' + formattedDate + '" />';
                            } else {
                                var text = '<input type="date" class="form-control" id="startdate' + rowData.id + '" name="startdate" />';
                            }
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var edate = semesterdate.filter(x=>x.semester == rowData.id)
                            if (edate.length > 0 && edate[0].edate) {
                                var date = new Date(edate[0].edate);
                                var formattedDate = date.toLocaleDateString('en-CA'); // Adjust the locale as needed
                                var text = '<input type="date" class="form-control" id="enddate' + rowData.id + '" name="enddate" value="' + formattedDate + '" />';
                            } else {
                                var text = '<input type="date" class="form-control" id="enddate' + rowData.id + '" name="enddate" />';
                            }
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }

                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var active = semesterdate.filter(x=>x.active == 1 && x.semester == rowData.id)
                            console.log(rowData.id);
                            if (active.length > 0) {
                                var text = '<input type="checkbox" class="form-control text-center inactivatesemdate" id="inactivatesemdate'+rowData.id+'" semid="'+rowData.id+'" checked />';
                            } else {
                                var text = '<input type="checkbox" class="form-control text-center activatesemdate" id="activatesemdate'+rowData.id+'" semid="'+rowData.id+'" />';
                            }
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }

                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var exist = semesterdate.filter(x=>x.semester == rowData.id)
                            if (exist.length > 0) {
                                var text = '<a href="javascript:void(0)" class="text-primary semaddsched" id="semaddsched'+rowData.id+'" dataid="'+rowData.id+'"><i class="fas fa-plus"></i></a>' +
                                           '&nbsp;&nbsp;<a href="javascript:void(0)" class="text-primary semdeletesched" id="semdeletesched'+rowData.id+'" dataid="'+rowData.id+'"><i class="fas fa-trash-alt"></i></a>';
                            } else {
                                var text = '<a href="javascript:void(0)" class="text-primary semaddsched" id="semaddsched'+rowData.id+'" dataid="'+rowData.id+'"><i class="fas fa-plus"></i></a>';
                            }
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }

                    }
                   
                ]
            })
        }
        function loadsy(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/profileloadsy",
                success: function (data) {
                sy = data;
                var activesy = sy.filter(x=>x.isactive == 1)[0]
                var activesyid = activesy.id
                load_semester_date(activesyid)

                $('#profileselect-sy').empty();
                $('#profileselect-sy').append('<option value="">Select School Year</option>')
                $('#profileselect-sy').select2({
                    data: sy,
                    allowClear : true,
                    placeholder: 'Select School Year'
                });
                $('#profileselect-sy').val(activesy.id).change();
                }
            });
        }

        // ==============================================================================================================================================

    })
</script>
@endsection


