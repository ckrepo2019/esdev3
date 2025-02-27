@extends('hr.layouts.app')
@section('content')
    {{-- <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        #modal_printinginformation .table td {
            border: .5px solid rgb(241, 241, 241);
        }

        #table-employees-active td {
            padding: 3px;
        }

        .select2-container .select2-selection--single {
            height: 34px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #000;
            font-size: 15px;
            font-weight: 400;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000;
            line-height: 24px;
            font-size: 15px;
            font-weight: 400;
        }

        .monthsSelectdiv>.select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            border: 1px solid #006fe6 !important;
            font-size: 13.5px;
            padding-bottom: 3px !important;
        }

        .monthsSelectdiv>.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #000000 !important;
        }

        .highlight {
            background-color: rgba(42, 166, 255, 0.313);
        }

        .picurl {}

        #table-department_wrapper>div:first-child {
            width: fit-content;
        }

        #table-info_wrapper>div:first-child {
            width: fit-content;
        }
    </style>
    @php

        session_start();
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
    @endphp
    {{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-9">
          <h4 >Employees </h4>
        </div>
        <div class="col-sm-3">
            @if ($refid != 26)
            <a href="/hr/employees/addnewemployee/index" class="btn btn-primary btn-block" style="color: white;"><i class="fa fa-plus"></i> &nbsp;Add Employee</a>
            @endif
        </div>
      </div>
    </div>
  </section>
  <section class="content-body">    
    <div class="card card-nav-header" style="background-color: white; border-radius: 10px; box-shadow:none!important; border: none!important;">
      <div class="card-header d-flex p-0" style="font-size: 15px;">
        <ul class="nav nav-pills ml-auto p-2" style="margin-left: 0px !important;">
        <li class="nav-item"><a class="nav-link active activeemp" href="#tab_1-active-employees" data-toggle="tab" style="border-radius: 25px !important;">Active Employees &nbsp;&nbsp;
        </a>
      </li>
        <li class="nav-item"><a class="nav-link inactiveemp" href="#tab_2-inactive-employees" data-toggle="tab" style="border-radius: 25px !important;">Inactive &nbsp;&nbsp;
        </a></li>
        </ul>
        <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item"><a class="nav-link emportactiveemployeepdf" href="javascript:void(0)" style="border-radius: 25px !important;">Export to PDF</a></li>
        <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=excel" style="border-radius: 25px !important;" target="_blank">Export to Excel</a></li>
        </ul>
      </div>
    </div>
    <div class="tab-content tab-header-content">
      <div class="tab-pane active" id="tab_1-active-employees">
        <div class="row d-flex ">
          <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-body">
                <table width="100%" id="table-employees-active" class="table table-hover" style="font-size: 15px; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th width="30%">EMPLOYEE</th>
                            <th width="30%" class="text-center">DESIGNATION</th>
                            <th width="20%">CONTACT DETAILS</th>
                            <th width="20%">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
          </div>
        </div>   
      </div>
      <div class="tab-pane" id="tab_2-inactive-employees">
        <div class="row d-flex">
          <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-body">
                <table width="100%" id="table-employees-inactive" class="table table-hover" style="font-size: 15px; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th width="30%">EMPLOYEE</th>
                            <th width="30%" class="text-center">DESIGNATION</th>
                            <th width="20%">CONTACT DETAILS</th>
                            <th width="20%">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
    <div id="container-profile"></div>
</section> --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h4>Employees </h4>
                </div>
                <div class="col-sm-3">
                    @if ($refid != 26)
                        <a href="/hr/employees/addnewemployee/index" class="btn btn-primary btn-block"
                            style="color: white;"><i class="fa fa-plus"></i> &nbsp;Add Employee</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="content-body">

        <div class="modal" tabindex="-1" role="dialog" id="modal_printinginformation">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Printing Employee Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="">
                                    <select class="form-control form-control-sm select2" id="filterinfo">
                                        <option value="1" selected>Department</option>
                                        <option value="2">Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="department_div">

                                <table width="100%" id="table-department" class="table table-sm"
                                    style="font-size: 16px; table-layout: fixed!important;">
                                    <thead hidden>
                                        <tr>
                                            <th class="p-0" width="100%">EMPLOYEE</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="col-md-6" style="padding-left: 10px!important;">
                                <table width="100%" id="table-info" class="table table-sm "
                                    style="font-size: 16px; table-layout: fixed;">
                                    <thead hidden>
                                        <tr>
                                            <th class="p-1 text-center" width="100%">DATA INFO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="datainfobody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="printdata">Print Data</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="modal_monthdata">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Month</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table width="100%" id="month_table" class="table table-hover"
                                    style="font-size: 15px; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th width="100%" class="text-left p-1">MONTH</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- 
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_printinginformation">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" style="display: flex">
                Printing Information
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6" id="department_div" >
                        <div class="form-group" style="">
                            <select class="form-control form-control-sm select2" id="filterinfo">
                                <option value="1" selected>Department</option>
                                <option value="2">Employee</option>
                            </select>
                        </div>
                        <table width="100%" id="table-department" class="table table-sm " style="font-size: 16px; table-layout: fixed!important;">
                            <thead hidden>
                                <tr>
                                    <th class="p-0" width="100%">EMPLOYEE</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-6 p-0">
                        <table width="100%" id="table-info" class="table table-sm no-border" style="font-size: 15px; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th class="p-1 text-center" width="100%">DATA INFO</th>
                                </tr>
                            </thead>
                            <tbody id="datainfobody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="">Save changes</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div> --}}



        <div class="card card-nav-header"
            style="background-color: white; border-radius: 10px; box-shadow:none!important; border: none!important;">
            <div class="card-header d-flex p-0" style="font-size: 15px;">
                <ul class="nav nav-pills ml-auto p-2" style="margin-left: 0px !important;">
                    {{-- <li class="nav-item"><a class="nav-link active" href="#tab_1-active-employees" data-toggle="tab" style="border-radius: 25px !important;">Active Employees &nbsp;&nbsp;
            </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#tab_2-inactive-employees" data-toggle="tab" style="border-radius: 25px !important;">Inactive &nbsp;&nbsp;
            </a>
        </li> --}}
                    <li class="nav-item"><a class="nav-link activeemp" href="#tab_1-active-employees" data-toggle="tab"
                            style="border-radius: 25px !important;">Active Employees &nbsp;&nbsp;
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link inactiveemp" href="#tab_2-inactive-employees"
                            data-toggle="tab" style="border-radius: 25px !important;">Inactive &nbsp;&nbsp;
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-pills ml-auto p-2">
                    {{-- // =========== Added by Gian =======
    $(document).on('click', '.emportactiveemployeepdf', function () {
        window.open("/hr/employees/index?action=export&exporttype=pdf", "_blank");
    });
    $(document).on('click', '.emportinactiveemployeepdf', function () {
        window.open("/hr/employees/inactiveemployeepdf?action=export&exporttype=pdf", "_blank");
    });

    $(document).on('click', '.inactiveemp', function(){
        $('.emportactiveemployeepdf').addClass('emportinactiveemployeepdf')
        $('.emportinactiveemployeepdf').removeClass('emportactiveemployeepdf')
    })

    $(document).on('click', '.activeemp', function(){
        $('.emportinactiveemployeepdf').addClass('emportactiveemployeepdf')
        $('.emportactiveemployeepdf').removeClass('emportinactiveemployeepdf')
    })
    // ================================= --}}
                    {{-- <li class="nav-item"><a class="nav-link printinginfo" style="border-radius: 25px !important;cursor: pointer;">Printing per Info</a></li>&nbsp;&nbsp; --}}
                    <li class="nav-item"><a class="nav-link emportinactiveemployeepdf"
                            style="border-radius: 25px !important; cursor: pointer;">Export to PDF</a></li>
                    <li class="nav-item"><a class="nav-link " href="/hr/employees/index?action=export&exporttype=excel"
                            style="border-radius: 25px !important;" target="_blank">Export to Excel</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=pdf" style="border-radius: 25px !important;" target="_blank">Export to PDF</a></li>
        <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=excel" style="border-radius: 25px !important;" target="_blank">Export to Excel</a></li> --}}
                </ul>
            </div>
        </div>
        <div class="tab-content tab-header-content">
            <div class="tab-pane active" id="tab_1-active-employees">
                <div class="card shadow"
                    style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-body">
                        <table width="100%" id="table-employees-active" class="table table-hover"
                            style="font-size: 15px; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th width="30%">EMPLOYEE</th>
                                    <th width="13%" class="text-left">DESIGNATION</th>
                                    <th width="18%">CONTACT DETAILS</th>
                                    <th width="9%" class="text-center">STATUS</th>
                                    <th width="30%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_2-inactive-employees">
                {{-- <div class="row d-flex align-items-stretch"> --}}
                <div class="card shadow"
                    style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-body">
                        <table width="100%" id="table-employees-inactive" class="table table-hover"
                            style="font-size: 15px; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th width="35%">EMPLOYEE</th>
                                    <th width="15%" class="text-left">DESIGNATION</th>
                                    <th width="20%">CONTACT DETAILS</th>
                                    <th width="30%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- </div> --}}
            </div>
        </div>
        <div id="container-profile"></div>
    </section>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var refid = @json($refid);
            var avatar_unknown = @json(asset('assets/images/avatars/unknown.png'));
            var avatar_female = @json(asset('avatar/T(F) 3.png'));
            var avatar_male = @json(asset('avatar/T(M) 2.png'));
            var onerror_url = @json(asset('dist/img/download.png'));
            var inactiveemployees = [];
            var department_data = [];
            var employee_data = [];
            var data_info = [];
            var checkedInfoArray = [];
            var checkedDepArray = [];
            var checkedEmpArray = [];

            getemployees();
            getinactiveemployees()
            select_departments()

            $('.hired_status').select2()

            var months = [{
                    'id': 1,
                    'description': 'January'
                },
                {
                    'id': 2,
                    'description': 'February'
                },
                {
                    'id': 3,
                    'description': 'March'
                },
                {
                    'id': 4,
                    'description': 'April'
                },
                {
                    'id': 5,
                    'description': 'May'
                },
                {
                    'id': 6,
                    'description': 'June'
                },
                {
                    'id': 7,
                    'description': 'July'
                },
                {
                    'id': 8,
                    'description': 'August'
                },
                {
                    'id': 9,
                    'description': 'September'
                },
                {
                    'id': 10,
                    'description': 'October'
                },
                {
                    'id': 11,
                    'description': 'November'
                },
                {
                    'id': 12,
                    'description': 'December'
                }
            ];

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });



            function getemployees() {
                $('#table-employees-active').DataTable({
                    destroy: true,
                    lengthChange: false,
                    scrollX: false,
                    autoWidth: false,
                    order: false,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '/hr/employees/index',
                        type: 'GET',
                        data: {
                            action: 'getactiveemployees'
                        }
                    },
                    columns: [{
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).css('vertical-align', 'middle')
                                var pic = ''
                                if (rowData.hired_status == 1) {
                                    pic = '<img class="img-circle elevation-1" src="/' + rowData
                                        .picurl +
                                        '" class="" alt="User Image" onerror="this.src=\'' +
                                        avatar_unknown +
                                        '\'" width="50px" height="50px" style="border: 5px solid #5cb85c;" />'
                                } else {
                                    pic = '<img class="img-circle elevation-1" src="/' + rowData
                                        .picurl +
                                        '" class="" alt="User Image" onerror="this.src=\'' +
                                        avatar_unknown + '\'" width="50px" height="50px"/>'
                                }

                                $(td)[0].innerHTML = ' <div class="row">' +
                                    '<div class="col-md-3">' +
                                    pic +
                                    '</div>' +
                                    '<div class="col-md-9">' +
                                    '<div class="row">' +
                                    '<div class="col-md-12"><span style="font-weight: 500">' +
                                    rowData.lastname + ', ' + rowData.firstname +
                                    '</span></div>   ' +
                                    '<div class="col-md-12">' + '<small class="text-primary">' +
                                    rowData.tid + '</small></div>   ' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'

                                $(td).find('.img-circle').css({
                                    'border': '5px solid green !important'
                                });
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.utype == null) {
                                    var html = '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>'
                                } else {
                                    var html = '<small class="text-dark">' + rowData.utype +
                                        '</small>'
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-left')
                                $(td)[0].innerHTML = html
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).css('vertical-align', 'middle')
                                $(td)[0].innerHTML =
                                    `
                            <div class="row">
                                <div class="col-md-12">
                                    <small class="text-muted" style="font-size: 13px;"><i class="fa fa-phone"></i> &nbsp;` +
                                    (rowData
                                        .contactnum == null ? 'Contact No. not set' : rowData
                                        .contactnum) +
                                    `</small>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted" style="font-size: 13px;"><i class="fa fa-address-book"></i> &nbsp;` +
                                    ((
                                            rowData.address == null || rowData.address == '' ||
                                            rowData.address == ' ') ? 'Home Address not set' :
                                        rowData.address) + `</small>
                                </div>
                            </div>`
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                var html =
                                    `<select class="form-control hired_status form-control-sm select2" id="change_hired_status" name="" data-id="` +
                                    rowData.id + `">
                                                <option value="0" ${rowData.hired_status == 1 ? '' : 'selected'} >Select Status</option>
                                                <option value="1" ${rowData.hired_status == 1 ? 'selected' : ''} >Newly hired</option>
                                            </select>`

                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-center')
                                $(td)[0].innerHTML = html
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                console.log(rowData);

                                if (refid != 26) {
                                    var html =
                                        `<a href="/hr/employees/profile/index?employeeid=${rowData.id}" type="button" class="btn btn-sm text-center btn-default p-1 text-white" data-id="` +
                                        rowData.id +
                                        `" style="font-size: 13px; width: 40%; background-color: #343a40;border: 1px solid white; border-radius: 5px;">View Profile</a>
                            <a href="javascript:void(0)" type="button" class="btn btn-sm text-center p-1 text-white deactivateuser" data-id="` +
                                        rowData.id +
                                        `" style="background-color: #dc3545c9; font-size: 13px; width: 30%;border: 1px solid white; border-radius: 5px;">Deactivate</a>`
                                } else {
                                    var html =
                                        `<a type="button" class="btn btn-sm text-center btn-default p-1 text-white btn-view-profile" data-id="` +
                                        rowData.id +
                                        `" style="font-size: 13px; width: 60%; background-color: #343a40;border: 1px solid white; border-radius: 5px;">View Profile</a>`
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-right')
                                $(td)[0].innerHTML = html
                            }
                        }
                    ]
                });

                var label_text = $($('#table-employees-active_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML =
                    `<div class="col-md-6 text-primary"><i class="fas fa-users-cog"></i>&nbsp;<a class="printinginfo" style="border-radius: 25px !important;cursor: pointer;">Printing per Info</a></div>`


                $(document).on('click', '.timelogs', function() {
                    var employeeid = $(this).attr('data-id')
                    $('#modal-timelogs').modal('show')
                    var selecteddate = $('#select-date').val()
                    $.ajax({
                        url: '/hr/attendance/gettimelogs',
                        type: "GET",
                        data: {
                            employeeid: employeeid,
                            selecteddate: selecteddate
                        },
                        success: function(data) {
                            $('#timelogsdetails').empty()
                            $('#timelogsdetails').append(data)
                        }
                    });
                })
            }


            // Added by Gian
            function getinactiveemployeestable() {
                $('#table-employees-inactive').DataTable({
                    destroy: true,
                    lengthChange: false,
                    scrollX: false,
                    autoWidth: false,
                    order: false,
                    processing: true,
                    data: inactiveemployees,
                    columns: [{
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('p-1')
                                $(td)[0].innerHTML = ' <div class="row">' +
                                    '<div class="col-md-3">' +
                                    '<img class="img-circle elevation-1" src="/' + rowData.picurl +
                                    '" class="" alt="User Image" onerror="this.src=\'' +
                                    avatar_unknown + '\'" width="50px" height="50px"/>' +
                                    '</div>' +
                                    '<div class="col-md-9">' +
                                    '<div class="row">' +
                                    '<div class="col-md-12"><span style="font-weight: 500">' +
                                    rowData.lastname + ', ' + rowData.firstname +
                                    '</span></div>   ' +
                                    '<div class="col-md-12">' + '<small class="text-primary">' +
                                    rowData.tid + '</small></div>   ' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.utype == null) {
                                    var html = '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>'
                                } else {
                                    var html = '<small class="text-dark">' + rowData.utype +
                                        '</small>'
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-left p-1')
                                $(td)[0].innerHTML = html
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('p-1')
                                $(td)[0].innerHTML =
                                    `
                        <div class="row">
                            <div class="col-md-12">
                                <small class="text-muted" style="font-size: 13px;"><i class="fa fa-phone"></i> &nbsp;` +
                                    (rowData
                                        .contactnum == null ? 'Contact No. not set' : rowData
                                        .contactnum) +
                                    `</small>
                            </div>
                            <div class="col-md-12">
                                <small class="text-muted" style="font-size: 13px;"><i class="fa fa-address-book"></i> &nbsp;` +
                                    ((rowData
                                            .address == null || rowData.address == '' || rowData
                                            .address == ' ') ? 'Home Address not set' : rowData
                                        .address) + `</small>
                            </div>
                        </div>`
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (refid != 26) {
                                    var html =
                                        `<a href="/hr/employees/profile/index?employeeid=${rowData.userid}" type="button" class="btn btn-sm text-center btn-default p-1 text-white" data-id="` +
                                        rowData.id +
                                        `" style="font-size: 13px; width: 40%; background-color: #343a40;border: 1px solid white; border-radius: 5px;">View Profile</a>
                        <a href="javascript:void(0)"  type="button" class="btn btn-sm text-center p-1 text-white btn-primary activateuser" data-id="` +
                                        rowData.id +
                                        `" style="font-size: 13px; width: 30%;border: 1px solid white; border-radius: 5px;">Activate</a>`
                                } else {
                                    var html =
                                        `<a type="button" class="btn btn-sm text-center btn-default p-1 text-white btn-view-profile" data-id="` +
                                        rowData.id +
                                        `" style="font-size: 13px; width: 60%; background-color: #343a40;border: 1px solid white; border-radius: 5px;">View Profile</a>`
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-right p-1')
                                $(td)[0].innerHTML = html
                            }
                        }
                    ]
                });
            }

            function getinactiveemployees() {
                $.ajax({
                    type: "GET",
                    url: "/hr/employees/inactiveemployees",
                    success: function(data) {
                        inactiveemployees = data;
                        getinactiveemployeestable()
                    }
                });
            }

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $("#input-filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));

                $(".eachemployee").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                        $(".card-group.card-group-filter:first-of-type").append($(this));
                        $(this).hide();
                        hiddenCards++;

                    } else {

                        $(".card-group.card-group-filter:last-of-type").prepend($(this));
                        $(this).show();
                        visibleCards++;

                        if (((visibleCards % 4) == 0)) {
                            $(".container").append($(
                                "<div class='card-group card-group-filter'></div>"));
                        }
                    }
                });
            });

            // =========== Added by Gian =======
            $(document).on('click', '.emportactiveemployeepdf', function() {
                window.open("/hr/employees/index?action=export&exporttype=pdf", "_blank");
            });
            $(document).on('click', '.emportinactiveemployeepdf', function() {
                window.open("/hr/employees/inactiveemployeepdf?action=export&exporttype=pdf", "_blank");
            });

            $(document).on('click', '.inactiveemp', function() {
                $('.emportactiveemployeepdf').addClass('emportinactiveemployeepdf')
                $('.emportinactiveemployeepdf').removeClass('emportactiveemployeepdf')
            })

            $(document).on('click', '.activeemp', function() {
                $('.emportinactiveemployeepdf').addClass('emportactiveemployeepdf')
                $('.emportactiveemployeepdf').removeClass('emportinactiveemployeepdf')
            })

            $(document).on('change', '.hired_status', function() {
                var id = $(this).attr('data-id')
                var hired_status = $(this).val()

                $.ajax({
                    type: "GET",
                    url: "/hr/employees/change_hired_status",
                    data: {
                        teacherid: id,
                        hired_status: hired_status
                    },
                    success: function(data) {
                        if (data.status == 0) {
                            Toast.fire({
                                type: 'error',
                                title: data.message
                            });
                        } else {
                            getemployees()

                            Toast.fire({
                                type: 'success',
                                title: data.message
                            });
                        }
                    }
                });
            })
            // =================================

            $(document).on('click', '.btn-view-profile', function() {

                $('#container-profile').empty()
                $('#container-profile').hide()
                var empid = $(this).attr('data-id');
                Swal.fire({
                    title: 'Fetching data...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                $.ajax({
                    type: 'GET',
                    url: '/hr/employees/getprofile',
                    data: {
                        empid: empid
                    },
                    success: function(data) {
                        $('.card-nav-header').hide()
                        $('.tab-header-content').hide()
                        $('#container-profile').append(data)
                        $('#container-profile').show()
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')

                    },
                    error: function() {

                    }
                })
            })
            $(document).on('click', '#btn-back', function() {
                $('#container-profile').hide()
                $('.card-nav-header').show()
                $('.tab-header-content').show()
            })

            $(document).on('change', '#filterinfo', function() {
                var data = $(this).val()

                if (data == 1) {
                    select_departments()

                } else {
                    select_employees()
                }
            })

            $(document).on('click', '#printdata', function() {
                var selectoption = $('#filterinfo').val();
                var filtereddata = []; // Declare the variable outside the conditionals
                var checkedInfo = []; // Declare the variable outside the conditionals
                var valid_data = true;
                var months = $('.monthsSelect').val() || []

                if (months.length === 1 && months[0] === "") {
                    months = [];
                }

                if (selectoption == 1) {
                    if (checkedDepArray.length > 0) {
                        filtereddata = checkedDepArray;
                    } else {
                        Toast.fire({
                            type: 'warning',
                            title: 'No Department Selected'
                        });
                        valid_data = false
                        return;
                    }
                } else {
                    if (checkedEmpArray.length > 0) {
                        filtereddata = checkedEmpArray;
                    } else {
                        Toast.fire({
                            type: 'warning',
                            title: 'No Employee Selected'
                        });
                        valid_data = false
                        return;
                    }
                }

                if (checkedInfoArray.length > 0) {
                    checkedInfo = checkedInfoArray;
                    console.log('naay sulod ang checkedInfoArray');
                    valid_data = true
                } else {
                    Toast.fire({
                        type: 'warning',
                        title: 'No Info Data Selected'
                    });

                    valid_data = false
                }


                if (valid_data) {

                    var url = '/payrollclerk/employees/profile/getdatainfoprintables?selectoption=' +
                        selectoption + '&filtereddata=' + filtereddata + '&checkedInfoArray=' +
                        checkedInfo + '&months=' + months;
                    window.open(url, '_blank');

                    // return false;
                    // $.ajax({
                    //     type: "GET",
                    //     url: "/payrollclerk/employees/profile/getdatainfoprintables",
                    //     data: {
                    //         selectoption : selectoption,
                    //         filtereddata : filtereddata,
                    //         checkedInfoArray : checkedInfoArray
                    //     },
                    //     success: function (data) {
                    //         console.log(data);
                    //     }
                    // });
                }
            });

            $(document).on('click', '.checkperdepartment', function() {
                var departmentid = $(this).attr('departmentid');
                if ($(this).is(':checked')) {
                    checkedDepArray.push(departmentid);
                } else {
                    var index = checkedDepArray.indexOf(departmentid);
                    if (index !== -1) {
                        checkedDepArray.splice(index, 1);
                    }
                }
            });

            $(document).on('click', '.checkperemployee', function() {
                var employeeid = $(this).attr('empid');
                if ($(this).is(':checked')) {
                    checkedEmpArray.push(employeeid);
                } else {
                    var index = checkedEmpArray.indexOf(employeeid);
                    if (index !== -1) {
                        checkedEmpArray.splice(index, 1);
                    }
                }

            });

            $(document).on('click', '.checkperinfo', function() {
                var infodata = $(this).attr('infodata');
                if ($(this).is(':checked')) {
                    checkedInfoArray.push(infodata);
                } else {
                    var index = checkedInfoArray.indexOf(infodata);
                    if (index !== -1) {
                        checkedInfoArray.splice(index, 1);
                    }
                }
            });

            $(document).on('click', '.deactivateuser', function() {
                var teacherid = $(this).attr('data-id');

                Swal.fire({
                    title: 'Are you sure you want to deactivate User?',
                    type: 'warning',
                    confirmButtonColor: '#dc3545c9',
                    confirmButtonText: 'Deactivate',
                    showCancelButton: true,
                    allowOutsideClick: true
                }).then((confirm) => {
                    if (confirm.value) {
                        $.ajax({
                            type: "GET",
                            url: "/hr/employees/profile/deactivateuser",
                            data: {
                                teacherid: teacherid
                            },
                            success: function(data) {
                                if (data[0].status == 0) {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    });
                                } else {
                                    getemployees()
                                    getinactiveemployees()
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    });
                                }
                            }
                        });
                    }
                })
            })

            $(document).on('click', '.activateuser', function() {
                var teacherid = $(this).attr('data-id');

                Swal.fire({
                    title: 'Are you sure you want to activate User?',
                    type: 'warning',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Activate',
                    showCancelButton: true,
                    allowOutsideClick: true
                }).then((confirm) => {
                    if (confirm.value) {
                        $.ajax({
                            type: "GET",
                            url: "/hr/employees/profile/activateuser",
                            data: {
                                teacherid: teacherid
                            },
                            success: function(data) {
                                if (data[0].status == 0) {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    });
                                } else {
                                    getinactiveemployees()
                                    getemployees()
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    });
                                }
                            }
                        });
                    }
                })
            })

            $(document).on('click', '#checkperinfodob', function() {
                if ($(this).is(':checked')) {
                    $('.monthsSelectdiv').attr('hidden', false);
                } else {
                    $('.monthsSelectdiv').attr('hidden', true);
                }
            })

            $(document).on('click', '#monthdata', function() {
                $('#modal_monthdata').modal('show')
                month_table()
            })

            //added by Gian
            function select_departments() {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/employees/profile/select_departments",
                    success: function(data) {
                        department_data = data
                        tabledepartment()

                        // $('#select-department').empty()
                        // $('#select-department').append('<option value="">Select Department</option>')
                        // $('#select-department').select2({
                        // 	data: data,
                        // 	allowClear : true,
                        // 	placeholder: 'Select Department'
                        // });

                    }
                });
            }

            function select_employees() {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/additionalearningdeductions/loademployees",
                    success: function(data) {
                        employee_data = data
                        console.log(employee_data);
                        tableemployees()
                    }
                });
            }


            // Added by Gian
            function tabledepartment() {
                $('#table-department').DataTable({
                    destroy: true,
                    lengthChange: false,
                    scrollX: false,
                    // autoWidth: false,
                    order: false,
                    // processing: false,
                    info: false,
                    data: department_data,
                    columns: [{
                        "data": 'text'
                    }],
                    columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var html =
                                '<input type="checkbox" class="checkperdepartment" id="checkperdepartment' +
                                rowData.id + '" departmentid="' + rowData.id +
                                '" style="width: 18px; height: 18px; position: relative; top: 3px;">&nbsp;&nbsp;<span class="text-dark">' +
                                rowData.text + '</span>'
                            $(td).css('vertical-align', 'middle')
                            $(td).addClass('text-left p-1')
                            $(td)[0].innerHTML = html
                        }
                    }]
                });
            }

            function tableemployees() {
                $('#table-department').DataTable({
                    destroy: true,
                    lengthChange: false,
                    scrollX: false,
                    order: false,
                    info: false,
                    data: employee_data,
                    columns: [{
                        "data": 'full_name'
                    }],
                    columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var html =
                                '<input type="checkbox" class="checkperemployee" id="checkperemployee' +
                                rowData.id + '" empid="' + rowData.id +
                                '" style="width: 18px; height: 18px; position: relative; top: 3px;">&nbsp;&nbsp;<span class="text-dark">' +
                                rowData.full_name + '</span>';
                            $(td).css('vertical-align', 'middle')
                            $(td).addClass('text-left p-1')
                            $(td)[0].innerHTML = html
                        }
                    }]
                });
            }
            getdatainfo()

            function getdatainfo() {

                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/employees/profile/getdatainfo",
                    success: function(data) {
                        data_info = data;
                        tableinfo()

                    }
                });
            }

            function tableinfo() {
                $('#table-info').DataTable({
                    destroy: true,
                    lengthChange: false,
                    scrollX: false,
                    order: false,
                    info: false,
                    data: data_info,
                    columns: [{
                        "data": 'description'
                    }],
                    columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // <a href="javascript:void(0)" id="monthdata" style="pointer-events: none;"><i class="fas fa-calendar-alt"></i></a>&nbsp;&nbsp;
                            if (rowData.description == "Date of Birth") {
                                var html = `<div class="row">
                                    <div class="col-12">
                                        <input type="checkbox" class="checkperinfo" id="checkperinfo${rowData.key}" infodata="${rowData.key}" style="width: 18px; height: 18px; position: relative; top: 3px;">&nbsp;&nbsp;<span class="text-dark">${rowData.description}</span>
                                    </div>
                                    <div class="col-12 text-right monthsSelectdiv" hidden>
                                        <select class="monthsSelect" multiple="multiple" style="width:100%;" ></select>
                                    </div>
                                </div>
                                `
                            } else {
                                var html =
                                    '<input type="checkbox" class="checkperinfo" id="checkperinfo' +
                                    rowData.key + '" infodata="' + rowData.key +
                                    '" style="width: 18px; height: 18px; position: relative; top: 3px;">&nbsp;&nbsp;<span class="text-dark">' +
                                    rowData.description + '</span>';
                            }


                            $(td).css('vertical-align', 'middle')
                            $(td).addClass('text-left p-1')
                            $(td)[0].innerHTML = html

                            // Initialize Select2 for each cell's select element
                            $(td).find('.monthsSelect').select2({
                                placeholder: "Select months",
                                data: months.map(function(month) {
                                    return {
                                        id: month.id,
                                        text: month.description
                                    };
                                })
                            });

                        }
                    }]
                });
            }

            $(document).on('click', '.printinginfo', function() {
                $('#modal_printinginformation').modal('show')
            })

        });
    </script>
@endsection
