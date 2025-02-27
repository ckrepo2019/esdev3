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

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            border: 0 !important;
        }

        .shadow-none {
            box-shadow: none !important;
            border: 1px solid rgb(224, 222, 222) !important;
        }
    </style>
    @php

        session_start();
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        $job_status = DB::table('hr_empstatus')
            ->select('description as text', 'id')
            ->where('deleted', '0')
            ->orderBy('description', 'ASC')
            ->get();

        use App\Models\HR\HREmployeeAttendance;
        // use Illuminate\Support\Facades\DB;

        $employee = DB::table('teacher')->first();

        $currentmonthworkdays = [];
        $beginmonth = new DateTime(date('Y-m-01'));
        $endmonth = new DateTime(date('Y-m-t'));
        $endmonth->modify('+1 day');
        $intervalmonth = new DateInterval('P1D');
        $daterangemonth = new DatePeriod($beginmonth, $intervalmonth, $endmonth);

        foreach ($daterangemonth as $datemonth) {
            $currentmonthworkdays[] = $datemonth->format('Y-m-d');
        }

        $employeeattendance = [];
        foreach ($currentmonthworkdays as $currentmonthworkday) {
            $att = HREmployeeAttendance::getattendance($currentmonthworkday, $employee);

            $timerecord = (object) [
                'amin' => $att->amin === '00:00:00' ? '' : $att->amin,
                'amout' => $att->amout === '00:00:00' ? '' : $att->amout,
                'pmin' => $att->pmin === '00:00:00' ? '' : $att->pmin,
                'pmout' => $att->pmout === '00:00:00' ? '' : $att->pmout,
            ];

            $employeeattendance[] = (object) [
                'date' => date('M d Y', strtotime($currentmonthworkday)),
                'day' => date('l', strtotime($currentmonthworkday)),
                'timerecord' => $timerecord,
            ];
        }

        $currentmonthfirstday = date('m-01-Y');
        $currentmonthlastday = date('m-t-Y');
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
                    <div class="d-flex align-items-center">

                        <h4><i class="fas fa-address-book mr-2"></i> Employee Profile </h4>
                    </div>
                </div>
                <div class="col-sm-3">
                    @if ($refid != 26)
                        {{-- <a href="/hr/employees/addnewemployee/index" class="btn btn-primary btn-block" style="color: white;"
                            id="addnewemployee"><i class="fa fa-plus"></i> &nbsp;Add Employee</a> --}}
                        <a class="btn btn-primary btn-block" style="color: white;" id="addnewemployee"><i
                                class="fa fa-plus"></i> &nbsp;Add Employee</a>
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



        {{-- <div class="card card-nav-header"
            style="background-color: white; border-radius: 10px; box-shadow:none!important; border: none!important;">
            <div class="card-header d-flex p-0" style="font-size: 15px;">
                <ul class="nav nav-pills ml-auto p-2" style="margin-left: 0px !important;"> --}}
        {{-- <li class="nav-item"><a class="nav-link active" href="#tab_1-active-employees" data-toggle="tab" style="border-radius: 25px !important;">Active Employees &nbsp;&nbsp;
            </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#tab_2-inactive-employees" data-toggle="tab" style="border-radius: 25px !important;">Inactive &nbsp;&nbsp;
            </a>
        </li> --}}
        {{-- <li class="nav-item"><a class="nav-link activeemp" href="#tab_1-active-employees" data-toggle="tab"
                            style="border-radius: 25px !important;">Active Employees &nbsp;&nbsp;
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link inactiveemp" href="#tab_2-inactive-employees"
                            data-toggle="tab" style="border-radius: 25px !important;">Inactive &nbsp;&nbsp;
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-pills ml-auto p-2"> --}}
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
        {{-- <li class="nav-item"><a class="nav-link emportinactiveemployeepdf"
                            style="border-radius: 25px !important; cursor: pointer;">Export to PDF</a></li>
                    <li class="nav-item"><a class="nav-link " href="/hr/employees/index?action=export&exporttype=excel"
                            style="border-radius: 25px !important;" target="_blank">Export to Excel</a></li> --}}
        {{-- <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=pdf" style="border-radius: 25px !important;" target="_blank">Export to PDF</a></li>
        <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=excel" style="border-radius: 25px !important;" target="_blank">Export to Excel</a></li> --}}
        {{-- </ul>
            </div>
        </div> --}}
        <div class="modal fade" id="selectedEmployeeEditModal" tabindex="-1" aria-labelledby="selectedEmployeeEditModal"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectedEmployeeEditModal"><strong>Edit Employee Profile</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Content -->
                        <div class="row  d-flex">
                            <div class="col-md-4">
                                <div class="card profile-card">
                                    {{-- <img src="placeholder-image.jpg" alt="Profile Picture" class="profile-image img-fluid mx-auto d-block"> --}}

                                    <img src="{{ asset('avatar/S(F) 1.png') }}" alt="Profile Picture"
                                        class="profile-image" id="edit_profile_picture" width="200" height="210"
                                        style="display: block; margin: auto;">

                                    <div class="card-body">
                                        <div class="d-flex justify-content-between form-group">
                                            <label>Employee ID</label>
                                            <p class="mb-0">Not Assigned</p>
                                        </div>
                                        <hr />

                                        <div class="d-flex justify-content-between form-group">
                                            <label>Designation</label>
                                            <p class="mb-0">Not Assigned</p>
                                        </div>
                                        <hr />

                                        <br>
                                        <label class="btn btn-success btn-block" for="edit_employee_picture"
                                            style="cursor: pointer;">Upload
                                            Profile Picture</label>
                                        <input type="file" id="edit_employee_picture" style="display: none;" />

                                        {{-- <button type="button" class="btn btn-success btn-block">Update Profile Picture</button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 bg-white p-4">

                                <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">



                                    <li class="nav-item mr-3" role="presentation" style="cursor:pointer;">
                                        <a class="nav-link active employee_information_nav">Employee
                                            Information</a>
                                    </li>

                                    <li class="nav-item mr-3" role="presentation" style="cursor:pointer;"
                                        id="employment_details_nav">
                                        <a class="nav-link mployment_details_nav">Employment
                                            Details</a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link ">Ledger of Leave Credits</a>
                                    </li>
                                </ul>

                                <hr class="mt-4" />

                                <div class="employee_information">

                                    <div class="container">
                                        <h6>Employee Information</h1>
                                            <br>
                                            <form>
                                                <input type="text" class="form-control ml-5" id="edit_employeeid"
                                                    style="width:30%;" hidden>

                                                <div class="d-flex mb-3">
                                                    <label for="rfid" class="form-label me-2">RFID Assignment</label>
                                                    <input type="text" class="form-control ml-5" id="edit_rfid"
                                                        style="width:30%;">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="edit_firstName"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Middle Name</label>
                                                        <input type="text" class="form-control" id="edit_middleName"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="lastName" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" id="edit_lastName"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="suffix" class="form-label">Suffix</label>
                                                        <input type="text" class="form-control" id="edit_suffix"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="sex" class="form-label">Sex</label>
                                                        <select class="form-control" id="edit_sex">
                                                            <option value="male" selected>Male</option>
                                                            <option value="female">Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="civilStatus" class="form-label">Civil Status</label>
                                                        <input type="text" class="form-control" id="edit_civilStatus"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="birthdate" class="form-label">Birthdate</label>
                                                        <input type="date" class="form-control" id="edit_birthdate"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="cellphone" class="form-label">Cellphone No.</label>
                                                    <input type="tel" class="form-control" id="edit_cellphone"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="edit_email" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Resident Address</label>
                                                    <input type="text" class="form-control" id="edit_address"
                                                        required>
                                                </div>
                                            </form>
                                    </div>

                                    <hr class="mt-4" />

                                    <div class="container">
                                        <h6>Person to Notify in Case of Emergency</h1>
                                            <br>
                                            <form>


                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="contactFirstname" class="form-label">First
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_contactFirstname"required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="contactMiddlename" class="form-label">Middle
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_contactMiddlename" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="contactLastname" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_contactLastname" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="contactSuffix" class="form-label">Suffix</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_contactSuffix" required>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="Relationship" class="form-label">Relationship</label>
                                                        <input type="text" class="form-control" id="edit_Relationship"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Telephone" class="form-label">Telephone</label>
                                                        <input type="text" class="form-control" id="edit_Telephone"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Cellphone" class="form-label">Cellphone</label>
                                                        <input type="text" class="form-control" id="edit_Cellphone"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Email" class="form-label">Email Address</label>
                                                        <input type="text" class="form-control" id="edit_Email"
                                                            required>
                                                    </div>
                                                </div>



                                            </form>
                                    </div>


                                    <hr class="mt-4" />

                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Highest Educational Atttainment <span class="badge badge-success ml-4"
                                                    style="cursor: pointer;" id="editaddhighest_education"><i
                                                        class="fas fa-plus fa-sm"></i></span></h3>
                                        </div>
                                        <br>

                                        <div class="edithighest_education_section" id="edithighest_education_section">

                                            {{-- <div class="append_highest_education_row" hidden>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="Company" class="form-label">School Name</label>
                                                        <input type="text" class="form-control" id="school_name"
                                                            placeholder="St. Michael's college" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="year_graduated" class="form-label">Year
                                                            Graduated</label>
                                                        <input type="text" class="form-control" id="year_graduated"
                                                            placeholder="2018-2019" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Datefrom" class="form-label">Course</label>
                                                        <input type="text" class="form-control" id="course"
                                                            placeholder="AB Secondary Education" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Dateto" class="form-label">Award</label>
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" class="form-control" id="award"
                                                                placeholder="Cum Laude" required>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove_highest_education ml-1"
                                                                hidden><i class="fas fa-times"></i></button>
                                                        </div>

                                                    </div>


                                                </div>
                                            </div> --}}

                                        </div>


                                    </div>



                                    <hr class="mt-4" />

                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Work Experience <span class="badge badge-success ml-4"
                                                    style="cursor: pointer;" id="editaddwork_expereiences"><i
                                                        class="fas fa-plus fa-sm"></i></span></h3>
                                        </div>
                                        <br>

                                        <div class="editwork-experience-section" id="editwork-experience-section">

                                        </div>


                                    </div>

                                    <hr class="mt-4" />





                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Account Information</h6>
                                                <br>
                                                <div class="form-group">
                                                    <label for="sss">SSS No.</label>
                                                    <input type="text" class="form-control" id="edit_sss">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pagibig">Pag-Ibig No.</label>
                                                    <input type="text" class="form-control" id="edit_pagibig">
                                                </div>
                                                <div class="form-group">
                                                    <label for="philhealth">Philhealth No.</label>
                                                    <input type="text" class="form-control" id="edit_philhealth">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tin">TIN No.</label>
                                                    <input type="text" class="form-control" id="edit_tin">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Bank Account <span class="badge badge-success ml-4"
                                                        style="cursor: pointer;" id="editaddbank_account"><i
                                                            class="fas fa-plus fa-sm"></i></span></h6>
                                                <br>
                                                <div class="editaddbank_account_section mb-1"
                                                    id="editaddbank_account_section">
                                                    {{--                                                     
                                                    <div class="addbank_account_row mb-3" style="margin-top: -13px;">
                                                        <input type="text" class="form-control bank_name"
                                                            id="edit_append_bank_name" placeholder="Input Bank Name"
                                                            style="background-color: transparent;" hidden>
                                                        <div class="d-flex align-items-center mt-1">
                                                            <input type="text" class="form-control bank_number"
                                                                id="edit_append_bank_number" placeholder="203211214"
                                                                hidden>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove_bank_account ml-1"
                                                                hidden><i class="fas fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                     --}}
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="submit-section text-center mt-4">
                                        <button type="submit" id="update_employee_btn"
                                            class="btn btn-success submit-btn" id="btn-save">Save</button>
                                    </div>


                                </div>



                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                                <div class="employment_details" style="display: none;">
                                    <div class="container">
                                        <h6>Employment Details</h1>
                                            <br>
                                            <form>

                                                <input type="text" name="selected_employee_id"
                                                    id="selected_employee_id" hidden>

                                                <div class="d-flex mb-3">
                                                    <label for="edit_empid" class="form-label me-2">Employee ID</label>
                                                    <input type="text" class="form-control ml-5" id="edit_empid"
                                                        style="width:30%;">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="edit_select_designation"
                                                            class="form-label">Designation</label>
                                                        <select class="form-control" id="edit_select_designation">
                                                        </select>
                                                    </div>
                                                    {{-- <div class="col-md-4">
                                                        <label for="edit_select_department"
                                                            class="form-label">Department</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_select_department" placeholder="Enter Department">
                                                    </div> --}}

                                                    <div class="col-md-4">
                                                        <label for="edit_select_department"
                                                            class="form-label">Department</label>
                                                        <select class="form-control" id="edit_select_department">

                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="edit_select_office" class="form-label">Office</label>
                                                        <select class="form-control" id="edit_select_office">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">Date Hired</label>
                                                        <input type="date" class="form-control" id="edit_date_hired">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Accumulated
                                                            Y.0.S</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_accumulated_years_service">
                                                    </div>

                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">Job Status</label>
                                                        <select class="form-control" id="edit_select_jobstatus">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Probationary Start
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="edit_probationary_start_date">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="lastName" class="form-label">Probationary End
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="edit_probationary_end_date">
                                                    </div>
                                                </div>

                                            </form>
                                    </div>



                                    <hr class="mt-4" />


                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Workday</h6>
                                        </div>

                                        <div class="col-md-5 d-flex">
                                            <select class="form-control" id="edit-select-generalworkdays">

                                            </select>
                                            {{-- <span class="badge badge-primary mt-0 ml-4"
                                                style="cursor: pointer;width:26px;height: 25px;" id="btn_addworkday"><i
                                                    class="fas fa-th-large mt-1"></i></span> --}}

                                        </div>


                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table width="100%" class="table table-bordered text-center"
                                                style="table-layout: fixed;">
                                                <thead>
                                                    <tr>
                                                        <th width="12%">Days</th>
                                                        <th width="20%">Status</th>
                                                        <th width="18%">AM In</th>
                                                        <th width="18%">AM Out</th>
                                                        <th width="18%">PM In</th>
                                                        <th width="18%">PM Out</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="edit_generalworkdaysTable"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="submit-section text-center mt-4">
                                        <button type="submit" id="update_employeebtn__employmentdetails"
                                            class="btn btn-success submit-btn" id="btn-save">Save</button>
                                    </div>

                                </div>


                                {{-- <div class="submit-section text-center mt-4">
                                    <button type="submit" id="save_employeebtn" class="btn btn-success submit-btn"
                                        id="btn-save">Save</button>
                                </div> --}}



                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer with Save Button -->
                    {{-- <div class="modal-footer">
                 <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                     class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
             </div> --}}
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="updateGradeEquivalencyBtn"> UPDATE</button>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="modal fade" id="newEmployeeModal" tabindex="-1" aria-labelledby="newEmployeeModal"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newEmployeeModal"><strong>Add Employee Profile</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="employeecloseModalBtn">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Content -->
                        <div class="row  d-flex">
                            <div class="col-md-4">
                                <div class="card profile-card">
                                    {{-- <img src="placeholder-image.jpg" alt="Profile Picture" class="profile-image img-fluid mx-auto d-block"> --}}

                                    <img src="{{ asset('avatar/S(F) 1.png') }}" alt="Profile Picture"
                                        class="profile-image" id="profile_picture" width="200" height="210"
                                        style="display: block; margin: auto;">

                                    <div class="card-body">
                                        <div class="d-flex justify-content-between form-group">
                                            <label>Employee ID</label>
                                            <p class="mb-0">Not Assigned</p>
                                        </div>
                                        <hr />

                                        <div class="d-flex justify-content-between form-group">
                                            <label>Designation</label>
                                            <p class="mb-0">Not Assigned</p>
                                        </div>
                                        <hr />

                                        <br>
                                        <label class="btn btn-success btn-block" for="employee_picture"
                                            style="cursor: pointer;">Upload
                                            Profile Picture</label>
                                        <input type="file" id="employee_picture" style="display: none;" />

                                        {{-- <button type="button" class="btn btn-success btn-block">Update Profile Picture</button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 bg-white p-4">

                                <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">

                                    {{-- <li class="nav-item mr-3" role="presentation">
                                        <a class="nav-link" href="{{ url('setup/gradingsetup') }}">Employee Information</a>
                                    </li> --}}

                                    {{-- <li class="nav-item mr-3" role="presentation">
                                        <a class="nav-link active" href="{{ url('hr/employees/addnewemployee/index') }}">Employee
                                            Information</a>
                                    </li>
                
                                    <li class="nav-item mr-3" role="presentation">
                                        <a class="nav-link " href="{{ url('hr/employees/newemployee_employmentDetails') }}">Employment
                                            Details</a>
                                    </li> --}}

                                    <li class="nav-item mr-3" role="presentation" style="cursor:pointer;">
                                        <a class="nav-link active employee_information_nav">Employee
                                            Information</a>
                                    </li>

                                    <li class="nav-item mr-3" role="presentation" style="cursor:pointer;"
                                        id="employment_details_nav">
                                        <a class="nav-link mployment_details_nav">Employment
                                            Details</a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link ">Ledger of Leave Credits</a>
                                    </li>
                                </ul>

                                <hr class="mt-4" />

                                <div class="employee_information">

                                    <div class="container">
                                        <h6>Employee Information</h1>
                                            <br>
                                            <form>
                                                <div class="d-flex mb-3">
                                                    <label for="rfid" class="form-label me-2">RFID Assignment</label>
                                                    <input type="text" class="form-control ml-5" id="rfid"
                                                        style="width:30%;">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="firstName"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Middle Name</label>
                                                        <input type="text" class="form-control" id="middleName"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="lastName" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" id="lastName"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="suffix" class="form-label">Suffix</label>
                                                        <input type="text" class="form-control" id="suffix"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="sex" class="form-label">Sex</label>
                                                        <select class="form-control" id="sex">
                                                            <option value="Male" selected>Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="civilStatus" class="form-label">Civil Status</label>
                                                        <input type="text" class="form-control" id="civilStatus"
                                                            placeholder="Single" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="birthdate" class="form-label">Birthdate</label>
                                                        <input type="date" class="form-control" id="birthdate"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="cellphone" class="form-label">Cellphone No.</label>
                                                    <input type="tel" class="form-control" id="cellphone" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="email" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Resident Address</label>
                                                    <input type="text" class="form-control" id="address" required>
                                                </div>
                                            </form>
                                    </div>

                                    <hr class="mt-4" />

                                    <div class="container">
                                        <h6>Person to Notify in Case of Emergency</h1>
                                            <br>
                                            <form>


                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="contactFirstname" class="form-label">First
                                                            Name</label>
                                                        <input type="text" class="form-control" id="contactFirstname"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="contactMiddlename" class="form-label">Middle
                                                            Name</label>
                                                        <input type="text" class="form-control" id="contactMiddlename"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="contactLastname" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" id="contactLastname"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="contactSuffix" class="form-label">Suffix</label>
                                                        <input type="text" class="form-control" id="contactSuffix"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label for="Relationship" class="form-label">Relationship</label>
                                                        <input type="text" class="form-control" id="Relationship"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Telephone" class="form-label">Telephone</label>
                                                        <input type="text" class="form-control" id="Telephone"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Cellphone" class="form-label">Cellphone</label>
                                                        <input type="text" class="form-control" id="Cellphone"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Email" class="form-label">Email Address</label>
                                                        <input type="text" class="form-control" id="Email"
                                                            required>
                                                    </div>
                                                </div>



                                            </form>
                                    </div>


                                    <hr class="mt-4" />

                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Highest Eduactional Atttainment <span class="badge badge-success ml-4"
                                                    style="cursor: pointer;" id="addhighest_education"><i
                                                        class="fas fa-plus fa-sm"></i></span></h3>
                                        </div>
                                        <br>
                                        <form>
                                            <div class="highest_education_section">
                                                <div class="highest_education_row">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <label for="Company" class="form-label">School Name</label>
                                                            <input type="text" class="form-control" id="school_name"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="year_graduated" class="form-label">Year
                                                                Graduated</label>
                                                            <input type="text" class="form-control"
                                                                id="year_graduated" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="Datefrom" class="form-label">Course</label>
                                                            <input type="text" class="form-control" id="course"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="Dateto" class="form-label">Award</label>
                                                            <div class="d-flex align-items-center">
                                                                <input type="text" class="form-control" id="award"
                                                                    required>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger remove_highest_education ml-1"
                                                                    hidden><i class="fas fa-times"></i></button>
                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>



                                    <hr class="mt-4" />

                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Work Experience <span class="badge badge-success ml-4"
                                                    style="cursor: pointer;" id="addwork_expereiences"><i
                                                        class="fas fa-plus fa-sm"></i></span></h3>
                                        </div>
                                        <br>
                                        <form>
                                            <div class="work_experience_section">
                                                <div class="work_experience_row">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <label for="Company" class="form-label">Company Name</label>
                                                            <input type="text" class="form-control" id="Company"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="Designation"
                                                                class="form-label">Designation</label>
                                                            <input type="text" class="form-control" id="Designation"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="Datefrom" class="form-label">Date From</label>
                                                            <input type="date" class="form-control" id="Datefrom"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="Dateto" class="form-label">Date To</label>
                                                            <div class="d-flex align-items-center">
                                                                <input type="date" class="form-control" id="Dateto"
                                                                    required>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger remove_work_experience ml-1"
                                                                    hidden><i class="fas fa-times"></i></button>
                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    <hr class="mt-4" />





                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Account Information</h6>
                                                <br>
                                                <div class="form-group">
                                                    <label for="sss">SSS No.</label>
                                                    <input type="text" class="form-control" id="sss">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pagibig">Pag-Ibig No.</label>
                                                    <input type="text" class="form-control" id="pagibig">
                                                </div>
                                                <div class="form-group">
                                                    <label for="philhealth">Philhealth No.</label>
                                                    <input type="text" class="form-control" id="philhealth">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tin">TIN No.</label>
                                                    <input type="text" class="form-control" id="tin">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Bank Account <span class="badge badge-success ml-4"
                                                        style="cursor: pointer;" id="addbank_account"><i
                                                            class="fas fa-plus fa-sm"></i></span></h6>
                                                <br>
                                                <div class="addbank_account_section mb-1">
                                                    <div class="addbank_account_row mb-3" style="margin-top: -13px;">
                                                        <input type="text" class="form-control bank_name"
                                                            id="append_bank_name" placeholder="Input Bank Name"
                                                            style="background-color: transparent;" hidden>
                                                        <div class="d-flex align-items-center mt-1">
                                                            <input type="text" class="form-control bank_number"
                                                                id="append_bank_number" placeholder="203211214" hidden>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove_bank_account ml-1"
                                                                hidden><i class="fas fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-6">
                                            <h6>Bank Account <span class="badge badge-success ml-4" style="cursor: pointer;"
                                                    id="addbank_account"><i class="fas fa-plus fa-sm"></i></span></h3>
                                                <div class="addbank_account_section">
                                                    <div class="addbank_account_row_static">
                                                        <label for="bpi" id="bpi_name">BPI</label>
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" class="form-control" id="bpi"
                                                                placeholder="203211214">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove_bank_account_static ml-1"><i
                                                                    class="fas fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="addbank_account_row">
                                                        <div class="form-group">
                                                            <label for="bpi" class="bank_name" id="append_bank_name"
                                                                contenteditable="true" hidden>Bank
                                                                name</label>
                                                            <div class="d-flex align-items-center">
                                                                <input type="text" class="form-control bank_number"
                                                                    id="append_bank_number" placeholder="203211214" hidden>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger remove_bank_account ml-1" hidden><i
                                                                        class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div> --}}
                                        </div>
                                    </div>
                                    <div class="submit-section text-center mt-4">
                                        <button type="submit" id="save_employeebtn__employeeInformation"
                                            class="btn btn-success submit-btn" id="btn-save">Save</button>
                                    </div>


                                </div>



                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                                <div class="employment_details" style="display: none;">
                                    <div class="container">
                                        <h6>Employment Details</h1>
                                            <br>
                                            <form>

                                                <input type="text" name="selected_employee_id"
                                                    id="selected_employee_id" hidden>

                                                <div class="d-flex mb-3">
                                                    <label for="empid" class="form-label me-2">Employee ID</label>
                                                    <input type="text" class="form-control ml-5" id="empid"
                                                        style="width:30%;">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">Designation</label>
                                                        <select class="form-control" id="select-designation">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Department</label>
                                                        <select class="form-control" id="select-department">

                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="lastName" class="form-label">Office</label>
                                                        <select class="form-control" id="select-office">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">Date Hired</label>
                                                        <input type="date" class="form-control" id="date_hired">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Accumulated
                                                            Y.0.S</label>
                                                        <input type="text" class="form-control"
                                                            id="accumulated_years_service">
                                                    </div>

                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">Job Status</label>
                                                        <select class="form-control" id="select-jobstatus">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Probationary Start
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="probationary_start_date">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="lastName" class="form-label">Probationary End
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="probationary_end_date">
                                                    </div>
                                                </div>

                                            </form>
                                    </div>


                                    {{-- <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Approval Sequence <span class="badge badge-success ml-4" style="cursor: pointer;"
                                                    id="addapproval"><i class="fas fa-plus fa-sm"></i></span></h3>
                                        </div>
                                        <br>
                                        <form>
                                            <div class="approval_section">
                                                <div class="approval_row">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">1st Approval</label>
                
                                                        </div>
                                                        <div class="col-md-5 ml-5 d-flex">
                                                            <select class="form-control" id="select-approval">
                                                                <option value="">Select Employee</option>
                                                                @foreach ($employees as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->full_name }}
                                                                    </option>
                                                                @endforeach
                
                                                            </select>
                
                                                            <button type="button" class="btn btn-sm btn-danger remove_approval mt-2 ml-1"
                                                                style="cursor: pointer;width:26px;height: 25px;" hidden><i
                                                                    class="fas fa-times"></i></button>
                                                        </div>
                
                                                    </div>
                                                    <br>
                                                </div>
                
                                            </div>
                
                
                
                
                
                                        </form>
                                    </div> --}}

                                    <hr class="mt-4" />


                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Workday</h6>
                                        </div>

                                        <div class="col-md-5 d-flex">
                                            <select class="form-control" id="select-generalworkdays">

                                            </select>
                                            <span class="badge badge-primary mt-0 ml-4"
                                                style="cursor: pointer;width:26px;height: 25px;" id="btn_addworkday"><i
                                                    class="fas fa-th-large mt-1"></i></span>

                                        </div>


                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table width="100%" class="table table-bordered text-center"
                                                style="table-layout: fixed;">
                                                <thead>
                                                    <tr>
                                                        <th width="12%">Days</th>
                                                        <th width="20%">Status</th>
                                                        <th width="18%">AM In</th>
                                                        <th width="18%">AM Out</th>
                                                        <th width="18%">PM In</th>
                                                        <th width="18%">PM Out</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="generalworkdaysTable"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>

                                    {{-- <div class="container">
                                        <div class="d-flex align-items-center">
                                            <h6>Daily Time Record</h6>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="middleName" class="form-label">Period</label>
                                                <input type="text" name="dtrchangeperiod" class="form-control"
                                                    id="dtrdaterange"
                                                    value="{{ $currentmonthfirstday }} - {{ $currentmonthlastday }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="lastName" class="form-label">Office Hours</label>
                                                <input type="time" class="form-control" id="office_hours">
                                            </div>

                                        </div>

                                        <div class="card-body">
                                        
                                            <div class="row" style="overflow-x: auto;">
                                                <table class="table table-bordered">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th rowspan="2" style="width: 25%;">Date</th>
                                                            <th colspan="2">AM</th>
                                                            <th colspan="2">PM</th>
                                                            <th colspan="2">TOTAL</th>
                                                         
                                                        </tr>
                                                        <tr>
                                                            <th>IN</th>
                                                            <th>OUT</th>
                                                            <th>IN</th>
                                                            <th>OUT</th>
                                                            <th>Hours</th>
                                                            <th>Minutes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="timerecord">
                                                        @foreach ($employeeattendance as $empattendance)
                                                            <tr>
                                                                <td>
                                                                    {{ $empattendance->date }}
                                                                    @if (strtolower($empattendance->day) == 'saturday' || strtolower($empattendance->day) == 'sunday')
                                                                        <span
                                                                            class="right badge badge-secondary">{{ $empattendance->day }}</span>
                                                                    @else
                                                                        <span
                                                                            class="right badge badge-default">{{ $empattendance->day }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $empattendance->timerecord->amin }}</td>
                                                                <td class="text-center">
                                                                    {{ $empattendance->timerecord->amout }}</td>
                                                                <td class="text-center">
                                                                    {{ $empattendance->timerecord->pmin }}</td>
                                                                <td class="text-center">
                                                                    {{ $empattendance->timerecord->pmout }}</td>
                                                                <td class="text-center">
                                                                    {{ $empattendance->timerecord->pmin }}</td>
                                                                <td class="text-center">
                                                                    {{ $empattendance->timerecord->pmout }}</td>

                                                            
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                    </div> --}}
                                    <br>
                                    <br>
                                    <div class="submit-section text-center mt-4">
                                        <button type="submit" id="save_employeebtn__employmentdetails"
                                            class="btn btn-success submit-btn" id="btn-save">Save</button>
                                    </div>

                                </div>


                                {{-- <div class="submit-section text-center mt-4">
                                    <button type="submit" id="save_employeebtn" class="btn btn-success submit-btn"
                                        id="btn-save">Save</button>
                                </div> --}}



                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer with Save Button -->
                    {{-- <div class="modal-footer">
             <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                 class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
         </div> --}}
                    {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="updateGradeEquivalencyBtn"> UPDATE</button>
                </div> --}}
                </div>
            </div>
        </div>


        {{-- //////Workdays////// --}}
        <div class="modal fade modal_addworkday" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="modal_desc">Cuztomized Workday</span> <input type="text"
                                id="workdayid" hidden></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="display: none;">
                                <div class="form-group">
                                    <label for="workday_description">Workday Description</label>
                                    <input type="text" class="form-control" id="workday_description"
                                        aria-describedby="descriptionHelp" placeholder="Enter Description"
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table width="100%" class="table table-bordered text-center"
                                        style="table-layout: fixed;">
                                        <thead>
                                            <tr>
                                                <th width="12%">Workdays</th>
                                                <th width="20%">Status</th>
                                                <th width="18%">AM In</th>
                                                <th width="18%">AM Out</th>
                                                <th width="18%">PM In</th>
                                                <th width="18%">PM Out</th>
                                            </tr>
                                        </thead>
                                        <tbody id="scheduleTable"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="block-header bg-primary-dark">
                          
                            <h5 class="block-title">Overtime & Undertime</h5>
                        </div>

                        <div class="d-flex align-items-center" style="margin-left: 2rem; margin-top:2rem">
                            <h5>Full Day</h5>
                          

                            <div class="toggle-container ml-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="custom-control-input" id="fullday_status"
                                        name="fullday_status" checked="">
                                    <span class="slider">
                                        <span class="label-on">On</span>
                                        <span class="label-off">Off</span>
                                    </span>
                                </label>
                            </div>

                        </div>
                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-info" style="font-size: 24px;"></i>
                                <div>Mark shortage as Overtime, when work duration is more than</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="fullday_overtime" style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="fullday_overtime"
                                        name="fullday_overtime" placeholder="" style="width: 100%;">
                                </div>
                            </div>
                        </div>

                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-danger" style="font-size: 24px;"></i>
                                <div>Mark shortage as Undertime, when work duration is less than</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="fullday_undertime" style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="fullday_undertime"
                                        name="fullday_undertime" placeholder="" style="width: 100%;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center" style="margin-left: 2rem; margin-top:2rem">
                            <h5>Half Day</h5>
                          

                            <div class="toggle-container ml-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="custom-control-input" id="halfday_status"
                                        name="halfday_status" checked="">
                                    <span class="slider">
                                        <span class="label-on">On</span>
                                        <span class="label-off">Off</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-info" style="font-size: 24px;"></i>
                                <div>Mark shortage as Overtime, when work duration is more than</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="fullday_undertime" style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="halfday_overtime"
                                        name="fullday_undertime" placeholder="" style="width: 100%;" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-danger" style="font-size: 24px;"></i>
                                <div>Mark shortage as Undertime, when work duration is less than</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="fullday_undertime" style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="halfday_undertime"
                                        name="fullday_undertime" placeholder="" style="width: 100%;" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center" style="margin-left: 2rem; margin-top:2rem">
                            <h5>Dayoff</h5>
                          
                            <div class="toggle-container ml-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="custom-control-input" id="dayoff_status"
                                        name="dayoff_status" checked="">
                                    <span class="slider">
                                        <span class="label-on">On</span>
                                        <span class="label-off">Off</span>
                                    </span>
                                </label>
                            </div>

                        </div>
                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-info" style="font-size: 24px;"></i>
                                <div>Mark shortage as Overtime, when work duration is more than</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="fullday_undertime" style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="total_dayoffminutes"
                                        name="total_dayoffminutes" placeholder="" style="width: 100%;" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center" style="margin-left: 2rem; margin-top:2rem">
                            <h5>Holiday</h5>
                         

                            <div class="toggle-container ml-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="custom-control-input" id="holiday_status"
                                        name="holiday_status" checked="">
                                    <span class="slider">
                                        <span class="label-on">On</span>
                                        <span class="label-off">Off</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-info" style="font-size: 24px;"></i>
                                <div>Mark shortage as Overtime, when work duration is more than</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="fullday_undertime" style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="total_holidayminutes"
                                        name="fullday_undertime" placeholder="" style="width: 100%;" value="0">
                                </div>
                            </div>
                        </div>


                        <div class="d-flex align-items-center" style="margin-left: 2rem; margin-top:2rem">
                            <h5>Tardiness</h5>
                         
                            <div class="toggle-container ml-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="custom-control-input" id="tarday_status"
                                        name="tarday_status" checked="">
                                    <span class="slider">
                                        <span class="label-on">On</span>
                                        <span class="label-off">Off</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="x-display-grid-2-columns-custom ml-4"
                            style="display: grid; grid-template-columns: auto 120px; align-items: center; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-clock text-danger" style="font-size: 24px;"></i>
                                <div>Mark as TARDY, when first 'TIME IN' is later than the required AM IN</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="total_tardayminutes"
                                        style="font-size: 12px; color: #555;">Minute</label>
                                    <input type="text" class="form-control" id="total_tardayminutes"
                                        name="total_tardayminutes" placeholder="" style="width: 100%;"
                                        value="0">
                                </div>
                            </div>
                            <div>

                                <div class="container mt-4 tardiness_main_section">
                                    <div class="d-flex align-items-center">
                                        <h6>Tardy Deduction</h6>
                                        <span class="badge badge-success ml-1" style="cursor: pointer;"
                                            id="add_tardiness">
                                            <i class="fas fa-plus fa-sm"></i>
                                        </span>

                                        <div class="tardiness_section ml-5">
                                            <div class="tardiness_row">
                                                <div class="row g-3">
                                                    <div class="col-md-6 tardiness_label">
                                                        <div class="d-flex align-items-center">
                                                            <label for="tardy_time"
                                                                style="font-size: 12px; color: #555;">Tardy
                                                                Time</label>
                                                            <input type="text" class="form-control ml-2"
                                                                id="tardy_time">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 tardiness_input">
                                                        <div class="d-flex align-items-center">
                                                            <label for="tardy_amount"
                                                                style="font-size: 12px; color: #555;">Amount</label>
                                                            <input type="text" class="form-control ml-2"
                                                                id="tardy_amount">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove_tardiness ml-1"
                                                                hidden>
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div> --}}

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" id="btn_saveworkday">
                            Save</button>
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>


        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <div class="row" style="font-size:.9rem !important">
                                <div class="col-md-2">
                                    <label for="" class="mb-1">Department</label>
                                    <select class="form-control" id="filter-select-department">

                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="mb-1">Office</label>
                                    <select class="form-control" id="filter-select-office">

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Job Status</label>

                                    <select class="form-control select2" id="filter-select-jobstatus">
                                        <option value="">Select Job Status</option>
                                        @foreach ($job_status as $job)
                                            <option value="{{ $job->id }}">{{ $job->text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Designation</label>

                                    <select class="form-control" id="filter-select-designation">
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
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
                                    <th width="19%" class="text-center">Employee Name</th>
                                    <th>Employee ID</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Date Hired</th>
                                    <th>Job Status</th>
                                    <th width="10%" colspan="2" style="text-align: center;">Action</th>
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

            getemployees()
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

            // $('input.form-control').on('input', function() {
            //     if ($(this).val().replace(/^\s+|\s+$/g, "").length == 0) {
            //         $(this).removeClass('is-valid')
            //     } else {
            //         $(this).addClass('is-valid')
            //     }
            // })



            function getemployees() {
                $('#table-employees-active').DataTable({
                    destroy: true,
                    lengthChange: true,
                    scrollX: false,
                    autoWidth: false,
                    order: false,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '/hr/employees/V4fetch',
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
                                if (rowData.tid == null) {
                                    var html = '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>'
                                } else {
                                    var html = '<small class="text-dark">' + rowData.tid +
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
                                var html = '';

                                // Check if main_usertype exists
                                if (rowData.main_usertype == null) {
                                    html += '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>';
                                } else {
                                    html += '<div class="col-md-12">' +
                                        '<small class="text-dark font-weight-bold">' + rowData
                                        .main_usertype + '</small>';
                                }

                                // Check if sub_usertype exists and format accordingly
                                if (rowData.sub_usertype != null && rowData.sub_usertype.trim() !==
                                    '') {
                                    var subUserTypes = rowData.sub_usertype.split(', ');

                                    html +=
                                        '<div class="mt-1 d-flex flex-wrap gap-1" style="font-size: .6rem;">'; // Added flex-wrap for responsive spacing and reduced font size

                                    subUserTypes.forEach(function(subType) {
                                        html +=
                                            '<span class="badge badge-pill badge-info text-wrap px-2 py-1" style="margin-left:1px;margin-bottom:1px;">' +
                                            subType + '</span>';
                                    });

                                    html += '</div>';
                                }

                                html += '</div>';

                                $(td).css({
                                    'vertical-align': 'middle',
                                    'white-space': 'normal', // Allows text to wrap within the column
                                    'max-width': '200px' // Prevents the column from expanding too much
                                });
                                $(td).addClass('text-left');
                                $(td)[0].innerHTML = html;
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.department == null) {
                                    var html = '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>'
                                } else {
                                    var html = '<small class="text-dark">' + rowData.department +
                                        '</small>'
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-left')
                                $(td)[0].innerHTML = html
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.date_joined == null) {
                                    var html = '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>'
                                } else {
                                    var html = '<small class="text-dark">' + rowData.date_joined +
                                        '</small>'
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-left')
                                $(td)[0].innerHTML = html
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                if (rowData.employmentstatus == null) {
                                    var html = '<div class="col-md-12">' +
                                        '<small class="text-dark"><span><i class="text-danger fas fa-minus"></i></span></small></div>'
                                } else {
                                    var html = '<small class="text-dark">' + rowData
                                        .employmentstatus +
                                        '</small>'
                                }
                                $(td).css('vertical-align', 'middle')
                                $(td).addClass('text-left')
                                $(td)[0].innerHTML = html
                            }
                        },

                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_selected_employee" id="edit_selected_employee" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-edit text-primary"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');

                            }
                        },
                        {
                            'targets': 7,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="delete_selected_employee" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');

                            }
                        }
                    ]
                });

                var label_text = $($('#table-employees-active_wrapper')[0].children[0])[0].children[0]
                // $(label_text)[0].innerHTML =
                //     `<div class="col-md-6 text-primary"><i class="fas fa-users-cog"></i>&nbsp;<a class="printinginfo" style="border-radius: 25px !important;cursor: pointer;">Printing per Info</a></div>`


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

            select_designations_filter()


            function select_designations_filter() {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/employees/profile/select_designations",
                    success: function(data) {
                        $('#filter-select-designation').empty()
                        $('#filter-select-designation').append(
                            '<option value="">Select Designation</option>')
                        $('#filter-select-designation').select2({
                            data: data,
                            allowClear: true,
                            placeholder: 'Select Designation',
                            dropdownCssClass: 'custom-dropdown'
                        });
                    }
                });
            }


            select_departments_filter()

            function select_departments_filter() {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/employees/profile/select_departments",
                    success: function(data) {
                        console.log(data);
                        $('#filter-select-department').empty()
                        $('#filter-select-department').append(
                            '<option value="">Select Department</option>')
                        $('#filter-select-department').select2({
                            data: data,
                            allowClear: true,
                            placeholder: 'Select Department'
                        });

                    }
                });
            }

            select_offices_filter()

            function select_offices_filter() {
                $.ajax({
                    type: "GET",
                    url: "/newempoloyee/select_offices",
                    success: function(data) {
                        $('#filter-select-office').empty()
                        $('#filter-select-office').append('<option value="">Select Office</option>')
                        $('#filter-select-office').select2({
                            data: data,
                            allowClear: true,
                            placeholder: 'Select Office',
                            dropdownCssClass: 'custom-dropdown'
                        });
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

            $(document).on('click', '.delete_selected_employee', function() {
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




            $(document).on('click', '.edit_selected_employee', function() {
                $('#selectedEmployeeEditModal').modal('show')
                $('.employee_information_nav').click();
                var edit_selected_employee_id = $(this).attr('data-id')
                fetchEditEmployeeData(edit_selected_employee_id);

            });


            // $('#edit_select_department').select2()

            function fetchEditEmployeeData(edit_selected_employee_id) {
                $.ajax({
                    type: 'GET',
                    url: '/edit/selcted_employee/fetch',
                    data: {
                        employee_id: edit_selected_employee_id
                    },
                    success: function(response) {


                        // var grade_point_scale = response.grade_point_scale;
                        var employee_info = response.employee;
                        var education_info = response.education_info;
                        var work_experiences = response.work_experiences;
                        var bankAccounts = response.bankAccounts;
                        var selectedDepartmentid = employee_info[0].departmentid;
                        var selectedDepartment = employee_info[0].department;
                        var departments = response.departments;
                        var selectedDesignationid = employee_info[0].designationid;
                        var selectedDesignation = employee_info[0].designation;
                        var designations = response.designations;
                        var selectedOfficeid = employee_info[0].officeid;
                        var selectedOffice = employee_info[0].officename;
                        var offices = response.offices;
                        var selectedJobStatusId = employee_info[0].employmentstatus;
                        var selectedJobStatus = employee_info[0].jobstatus;
                        var job_status = response.job_status;

                        var selectedGeneralWorkdaysId = employee_info[0].general_workdaysid;
                        var selectedGeneralWorkdays = employee_info[0].general_workdays;
                        var general_workdays = response.general_workdays;

                        var employeeworkday_details = response.employeeworkday_details;



                        $("#selected_employee_id").val(edit_selected_employee_id);
                        $("#edit_employeeid").val(edit_selected_employee_id);

                        $("#edit_firstName").val(employee_info[0]
                            .firstname);
                        $("#edit_lastName").val(employee_info[0]
                            .lastname);
                        $("#edit_middleName").val(employee_info[0]
                            .middlename);
                        $("#edit_suffix").val(employee_info[0]
                            .suffix);
                        $("#edit_cellphone").val(employee_info[0]
                            .phonenumber);
                        $("#edit_email").val(employee_info[0]
                            .email);
                        $("#edit_address").val(employee_info[0]
                            .residentaddr);

                        $("#edit_rfid").val(employee_info[0]
                            .rfid);

                        // $('#edit_sex').empty().append(
                        //     '<option value="">Select Gender</option>'
                        // );
                        $('#edit_sex').empty();

                        let genders = ['Male', 'Female']; // Add any other genders if needed
                        genders.forEach(gender => {
                            if (employee_info[0].gender !== gender) {
                                $('#edit_sex').append(
                                    `<option value="${gender}">${gender}</option>`
                                );
                            }
                        });

                        if (employee_info[0].gender) {
                            $('#edit_sex').append(
                                `<option selected value="${employee_info[0].gender}">${employee_info[0].gender}</option>`
                            );
                        }

                        $('#edit_sex').trigger('change');

                        console.log(employee_info[0].department)


                        // $('#edit_select_designation').empty().append(
                        //     '<option value="">Select Designation</option>'
                        // );

                        // $('#edit_select_designation').empty().append(
                        //     '<option value="">Select Designation</option>'
                        // );

                        // designations.forEach(designation => {
                        //     if (!selectedDesignation || designation.text !==
                        //         selectedDesignation) {
                        //         $('#edit_select_designation').append(
                        //             `<option value="${designation.id}">${designation.text}</option>`
                        //         );
                        //     }
                        // });

                        // if (selectedDesignation) {
                        //     $('#edit_select_designation').append(
                        //         `<option selected value="${selectedDesignationid}">${selectedDesignation}</option>`
                        //     );
                        // }

                        $('#edit_select_designation').empty().append(
                            '<option value="">Select Designation</option>'
                        );

                        designations.forEach(designation => {
                            let isSelected = selectedDesignation && designation.text ===
                                selectedDesignation;
                            $('#edit_select_designation').append(
                                `<option value="${designation.id}" ${isSelected ? 'selected' : ''}>${designation.text}</option>`
                            );
                        });



                        // $('#edit_select_department').empty().append(
                        //     '<option value="">Select Department</option>'
                        // );

                        // departments.forEach(dept => {
                        //     if (!selectedDepartment || dept.text !== selectedDepartment) {
                        //         $('#edit_select_department').append(
                        //             `<option value="${dept.id}">${dept.text}</option>`
                        //         );
                        //     }
                        // });

                        // if (selectedDepartment) {
                        //     $('#edit_select_department').append(
                        //         `<option selected value="${selectedDepartmentid}">${selectedDepartment}</option>`
                        //     );
                        // }

                        $('#edit_select_department').empty().append(
                            '<option value="">Select Department</option>'
                        );

                        departments.forEach(dept => {
                            let isSelected = selectedDepartment && dept.text ===
                                selectedDepartment;
                            $('#edit_select_department').append(
                                `<option value="${dept.id}" ${isSelected ? 'selected' : ''}>${dept.text}</option>`
                            );
                        });


                        // $('#edit_select_office').empty().append(
                        //     '<option value="">Select Office</option>'
                        // );

                        // offices.forEach(office => {
                        //     if (!selectedOffice || office.text !== selectedOffice) {
                        //         $('#edit_select_office').append(
                        //             `<option value="${office.id}">${office.text}</option>`
                        //         );
                        //     }
                        // });

                        // if (selectedOffice) {
                        //     $('#edit_select_office').append(
                        //         `<option selected value="${selectedOfficeid}">${selectedOffice}</option>`
                        //     );
                        // }


                        $('#edit_select_office').empty().append(
                            '<option value="">Select Office</option>'
                        );

                        offices.forEach(office => {
                            let isSelected = selectedOffice && office.text === selectedOffice;
                            $('#edit_select_office').append(
                                `<option value="${office.id}" ${isSelected ? 'selected' : ''}>${office.text}</option>`
                            );
                        });


                        // $('#edit_select_jobstatus').empty().append(
                        //     '<option value="">Select Job Status</option>'
                        // );

                        // job_status.forEach(job_status => {
                        //     if (!selectedJobStatus || job_status.text !== selectedJobStatus) {
                        //         $('#edit_select_jobstatus').append(
                        //             `<option value="${job_status.id}">${job_status.text}</option>`
                        //         );
                        //     }
                        // });

                        // if (selectedJobStatus) {
                        //     $('#edit_select_jobstatus').append(
                        //         `<option selected value="${selectedJobStatusId}">${selectedJobStatus}</option>`
                        //     );
                        // }

                        $('#edit_select_jobstatus').empty().append(
                            '<option value="">Select Job Status</option>'
                        );

                        job_status.forEach(job_status => {
                            let isSelected = selectedJobStatus && job_status.text ===
                                selectedJobStatus;
                            $('#edit_select_jobstatus').append(
                                `<option value="${job_status.id}" ${isSelected ? 'selected' : ''}>${job_status.text}</option>`
                            );
                        });



                        $('#edit-select-generalworkdays').empty().append(
                            '<option value="0">Select General Workdays</option>'
                        );

                        general_workdays.forEach(general_work_day => {
                            if (!selectedGeneralWorkdays || general_work_day.text !==
                                selectedGeneralWorkdays) {
                                $('#edit-select-generalworkdays').append(
                                    `<option value="${general_work_day.id}">${general_work_day.text}</option>`
                                );
                            }
                        });

                        if (selectedGeneralWorkdaysId === 1) {
                            $('#edit-select-generalworkdays').append(
                                `<option selected value="${selectedGeneralWorkdaysId}">Cuztomized Employee Workday</option>`
                            );
                        } else if (selectedGeneralWorkdays) {
                            $('#edit-select-generalworkdays').append(
                                `<option selected value="${selectedGeneralWorkdaysId}">${selectedGeneralWorkdays}</option>`
                            );
                        }

                        if (employeeworkday_details !== null) {
                            const scheduleTable = document.getElementById('edit_generalworkdaysTable');
                            scheduleTable.innerHTML = ''; // Clear existing rows

                            // Days of the week
                            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday',
                                'saturday', 'sunday'
                            ];

                            // Generate rows dynamically
                            days.forEach(day => {
                                scheduleTable.innerHTML += `
                                    <tr style="font-size: 13px;">
                                        <td>${day.charAt(0).toUpperCase()}${day.charAt(1)}${day.charAt(2)}</td>
                                        <td>
                                            <option id="${day.toLowerCase()}_select_fetch" name="${day.toLowerCase()}_select" value="${employeeworkday_details[day] || 0}">
                                                ${employeeworkday_details[day] === 1 ? 'Full Day' : employeeworkday_details[day] === 2 ? 'Half AM' : employeeworkday_details[day] === 3 ? 'Half PM' : 'Day Off'}
                                            </option>
                                        </td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_amin_time_fetch" name="${day}_amin_time_fetch" value="${employeeworkday_details[`${day}_amin`] || ''}" ></td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_amout_time_fetch" name="${day}_amout_time_fetch" value="${employeeworkday_details[`${day}_amout`] || ''}" ></td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_pmin_time_fetch" name="${day}_pmin_time_fetch" value="${employeeworkday_details[`${day}_pmin`] || ''}" ></td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_pmout_time_fetch" name="${day}_pmout_time_fetch" value="${employeeworkday_details[`${day}_pmout`] || ''}" ></td>
                                    </tr>
                                `;
                            });
                        } else {
                            const scheduleTable = document.getElementById('edit_generalworkdaysTable');
                            scheduleTable.innerHTML = ''; // Clear existing rows
                        }

                        $('#edit-select-generalworkdays').on('change', function() {
                            const dataId = $(this).val();
                            console.log(dataId);

                            if (dataId == 1) {
                                cuztomized_workdays();

                            } else if (dataId == 0) {
                                const scheduleTable = document.getElementById(
                                    'edit_generalworkdaysTable');
                                scheduleTable.innerHTML = ''; // Clear existing rows
                            } else {
                                // Fetch and populate workday details
                                $.ajax({
                                    url: '/hr/setup/edit_workday',
                                    method: 'GET',
                                    data: {
                                        id: dataId
                                    },
                                    success: function(data) {
                                        if (data) {
                                            // Populate the workday details
                                            populateWorkdayDetails(data);
                                        } else {
                                            Toast.fire({
                                                icon: 'error',
                                                title: 'No data found!'
                                            });
                                        }
                                    },
                                    error: function() {
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Failed to load data!'
                                        });
                                    }
                                });
                            }

                        });

                        function cuztomized_workdays() {
                            const scheduleTable = document.getElementById(
                                'edit_generalworkdaysTable');
                            scheduleTable.innerHTML = ''; // Clear existing rows

                            // Days of the week
                            const days = ['monday', 'tuesday', 'wednesday', 'thursday',
                                'friday', 'saturday', 'sunday'
                            ];

                            // Generate rows dynamically
                            days.forEach(day => {
                                scheduleTable.innerHTML += `
                                        <tr style="font-size: 13px;">
                                            <td>${day.charAt(0).toUpperCase()}${day.charAt(1)}${day.charAt(2)}</td>
                                            <td>
                                                <option id="${day.toLowerCase()}_select_fetch" name="${day.toLowerCase()}_select" value="${employeeworkday_details[day] || 0}">
                                                    ${employeeworkday_details[day] === 1 ? 'Full Day' : employeeworkday_details[day] === 2 ? 'Half AM' : employeeworkday_details[day] === 3 ? 'Half PM' : 'Day Off'}
                                                </option>
                                            </td>
                                            <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_amin_time_fetch" name="${day}_amin_time_fetch" value="${employeeworkday_details[`${day}_amin`] || ''}"></td>
                                            <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_amout_time_fetch" name="${day}_amout_time_fetch" value="${employeeworkday_details[`${day}_amout`] || ''}"></td>
                                            <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_pmin_time_fetch" name="${day}_pmin_time_fetch" value="${employeeworkday_details[`${day}_pmin`] || ''}"></td>
                                            <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm"  style="font-size: 10.7px;" id="${day}_pmout_time_fetch" name="${day}_pmout_time_fetch" value="${employeeworkday_details[`${day}_pmout`] || ''}"></td>
                                        </tr>
                                    `;
                            })

                        }

                        // Function to populate workday details
                        function populateWorkdayDetails(data) {
                            // Clear and repopulate the table
                            const scheduleTable = document.getElementById('edit_generalworkdaysTable');
                            scheduleTable.innerHTML = ''; // Clear existing rows

                            // Days of the week
                            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday',
                                'saturday', 'sunday'
                            ];

                            // Generate rows dynamically
                            days.forEach(day => {
                                scheduleTable.innerHTML += `
                                    <tr style="font-size: 13px;">
                                        <td>${day.charAt(0).toUpperCase()}${day.charAt(1)}${day.charAt(2)}</td>
                                        <td>
                                            <option id="${day.toLowerCase()}_select_fetch" name="${day.toLowerCase()}_select" value="${data[day]}">${data[day] == 1 ? 'Full Day' : data[day] == 2 ? 'Half AM' : data[day] == 3 ? 'Half PM' : 'Day Off'}</option>
                                        </td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_amin_time_fetch" name="${day}_amin_time_fetch" value="${data[`${day}_amin`]}"></td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_amout_time_fetch" name="${day}_amout_time_fetch" value="${data[`${day}_amout`]}"></td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_pmin_time_fetch" name="${day}_pmin_time_fetch" value="${data[`${day}_pmin`]}"></td>
                                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_pmout_time_fetch" name="${day}_pmout_time_fetch" value="${data[`${day}_pmout`]}"></td>
                                    </tr>
                                `;
                                // <input type="text" class="form-control days form-control-sm" id="${day}_select" name="${day}_select" value="${data[day]}">
                            });
                        }

                        $("#edit_empid").val(employee_info[0]
                            .tid);
                        $("#edit_accumulated_years_service").val(employee_info[0]
                            .yos);
                        $("#edit_date_hired").val(employee_info[0]
                            .date_joined);
                        $("#edit_probationary_start_date").val(employee_info[0]
                            .probation_start);
                        $("#edit_probationary_end_date").val(employee_info[0]
                            .probation_end);





                        var picurl = employee_info[0].picurl ? '/' + employee_info[0]
                            .picurl : '/avatar/S(F) 1.png';

                        $("#edit_profile_picture").attr('src', picurl).attr('width', '200').attr(
                            'height', '210').css('display', 'block').css('margin', 'auto');

                        // $("#edit_profile_picture").attr('src', '/' + employee_info[0].picurl);


                        $("#edit_sss").val(employee_info[0]
                            .sssid);
                        $("#edit_pagibig").val(employee_info[0]
                            .pagibigid);
                        $("#edit_philhealth").val(employee_info[0]
                            .philhealtid);
                        $("#edit_tin").val(employee_info[0]
                            .tinid);


                        $("#edit_contactFirstname").val(employee_info[0]
                            .fname);
                        $("#edit_contactLastname").val(employee_info[0]
                            .lname);
                        $("#edit_contactMiddlename").val(employee_info[0]
                            .mname);
                        $("#edit_contactSuffix").val(employee_info[0]
                            .contact_suffix);
                        $("#edit_Relationship").val(employee_info[0]
                            .relationship);
                        $("#edit_Cellphone").val(employee_info[0]
                            .contactno);
                        $("#edit_Telephone").val(employee_info[0]
                            .telno);
                        $("#edit_Email").val(employee_info[0]
                            .contact_email);


                        var $highest_education_sections = $("#edithighest_education_section");
                        $highest_education_sections.empty(); // Clear existing content
                        // Header row for requirements
                        //     var highest_education_row = `
                    //            <div class="highest-education-rows-header row g-3">
                    //                 <div class="col-md-3">
                    //                     <label for="Company" class="form-label">School Name</label>

                    //                 </div>
                    //                 <div class="col-md-3">
                    //                     <label for="year_graduated" class="form-label">
                    //                         Year Graduated</label>

                    //                 </div>
                    //                 <div class="col-md-3">
                    //                     <label for="Datefrom" class="form-label">Course</label>

                    //                 </div>
                    //                 <div class="col-md-3">
                    //                     <label for="Dateto" class="form-label">Award</label>

                    //                 </div>


                    //             </div>
                    // `;
                        //     $highest_education_sections.append(highest_education_row);

                        // Iterate through each requirement
                        education_info.forEach(function(education_infos) {
                            // Generate unique ID for the new row
                            var rowCount = $highest_education_sections.children(
                                    '.row')
                                .length + 1;
                            var rowId = 'highest_education_rows_' + rowCount;

                            var highest_education_rowBody = `
                                <div class="highest-education-rows" id="' + rowId + '">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Company" class="form-label">School Name</label>
                                                <input type="text" class="form-control form-control-sm highest_education_schoolname" data-id="${education_infos.id}" name="highest_education[][school]" value="${education_infos.schoolname}" placeholder=""  onkeyup="this.value = this.value.toUpperCase();">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="year_graduated" class="form-label">Year Graduated</label>
                                                <input type="text" class="form-control form-control-sm highest_education_schoolyear" name="highest_education[][schoolyear]" value="${education_infos.schoolyear}" placeholder="" maxlength="11" pattern="[0-9]*" title="Please enter numbers only">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="Datefrom" class="form-label">Course</label>
                                                <input type="text" class="form-control form-control-sm highest_education_coursetaken" name="highest_education[][coursetaken]" value="${education_infos.coursetaken}" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <label for="Dateto" class="form-label">Award</label>
                                            <div class="form-group d-flex">
                                                
                                                <input type="text" class="form-control form-control-sm highest_education_awards" name="highest_education[][awards]" value="${education_infos.awards}" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                                <button type="button" class="btn btn-sm btn-danger remove_highest_education_fetch ml-1"  data-id="${education_infos.id}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            
                             `;
                            $highest_education_sections.append(
                                highest_education_rowBody);
                        });

                        // $(document).on("click", "#addhighest_education", function() {

                        //     var highest_education_section = $(
                        //         ".highest_education_section");
                        //     var highest_education_row = $(".highest-education-rows")
                        //         .last().clone();
                        //     highest_education_row.find("input").val("");
                        //     highest_education_section.append(highest_education_row);
                        // });



                        // }
                        $(document).on('click', '.remove_highest_education', function() {
                            $(this).closest('.highest-education-rows').remove();

                        });

                        $(document).on('click', '.remove_highest_education_fetch', function() {
                            var educationid = $(this).attr('data-id')

                            Swal.fire({
                                title: 'Delete Education',
                                text: 'Are you sure you want to delete selected education?',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '<i class="fa fa-trash"></i> Delete',
                                cancelButtonText: '<i class="fa fa-times"></i> Cancel'
                            }).then((result) => {
                                if (result.value) {
                                    delete_highest_education(educationid)
                                    $(this).closest('.highest-education-rows').remove();
                                }
                            })


                        })

                        function delete_highest_education(educationid) {


                            $.ajax({
                                type: 'GET',
                                url: '/empoloyee/education/delete',
                                data: {
                                    educationid: educationid,
                                },
                                success: function(data) {
                                    if (data[0].status == 1) {
                                        Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                        })

                                        $(this).closest('.highest-education-rows').remove();

                                    } else {
                                        Toast.fire({
                                            type: 'error',
                                            title: data[0].message
                                        })
                                    }
                                }
                            })
                        }






                        var $work_experience_sections = $("#editwork-experience-section");
                        $work_experience_sections.empty(); // Clear existing content
                        // Header row for requirements
                        //     var work_experience_row = `
                    //                <div class="row g-3">
                    //                     <div class="col-md-3">
                    //                         <label for="Company" class="form-label">Company Name</label>

                    //                      </div>
                    //                     <div class="col-md-3">
                    //                         <label for="Designation"
                    //                             class="form-label">Designation</label>

                    //                     </div>
                    //                     <div class="col-md-3">
                    //                         <label for="Datefrom" class="form-label">Date From</label>

                    //                     </div>
                    //                     <div class="col-md-3">
                    //                         <label for="Dateto" class="form-label">Date To</label>
                    //                         <div class="d-flex align-items-center">

                    //                         </div>

                    //                     </div>


                    //                 </div>
                    // `;
                        //     $work_experience_sections.append(work_experience_row);

                        // Iterate through each requirement
                        work_experiences.forEach(function(work_experience) {
                            // Generate unique ID for the new row
                            var rowCount = $work_experience_sections.children(
                                    '.row')
                                .length + 1;
                            var rowId = 'work_experience_rows_' + rowCount;

                            var work_experience_rowBody = `
                                <div class="work-experience-rows" id="' + rowId + '">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Company" class="form-label">Company Name</label>
                                                <input type="text" class="form-control form-control-sm companyname" name="work_experience[][companyname]" data-id="${work_experience.id}" value="${work_experience.companyname}" placeholder=""  onkeyup="this.value = this.value.toUpperCase();">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="Designation"
                                                class="form-label">Designation</label>
                                                <input type="text" class="form-control form-control-sm designation" name="work_experience[][designation]" value="${work_experience.designation}" placeholder="" maxlength="11" pattern="[0-9]*" title="Please enter numbers only">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label for="Datefrom" class="form-label">Date From</label>
                                                <input type="date" class="form-control form-control-sm datefrom" name="work_experience[][periodfrom]" value="${work_experience.periodfrom}" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <label for="Dateto" class="form-label">Date To</label>
                                            <div class="form-group d-flex">
                                                
                                                <input type="date" class="form-control form-control-sm dateto" name="work_experience[][periodto]" value="${work_experience.periodto}" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                                <button type="button" class="btn btn-sm btn-danger remove_work_experience_fetch ml-1"  data-id="${work_experience.id}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                             `;
                            $work_experience_sections.append(
                                work_experience_rowBody);
                        });

                        // }
                        $(document).on('click', '.remove_work_experience', function() {
                            $(this).closest('.work-experience-rows').remove();
                        });


                        $(document).on('click', '.remove_work_experience_fetch', function() {
                            var workexpid = $(this).attr('data-id')

                            Swal.fire({
                                title: 'Delete Work Experience',
                                text: 'Are you sure you want to delete selected work experience?',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '<i class="fa fa-trash"></i> Delete',
                                cancelButtonText: '<i class="fa fa-times"></i> Cancel'
                            }).then((result) => {
                                if (result.value) {
                                    delete_work_experience(workexpid)
                                    $(this).closest('.work-experience-rows').remove();
                                }
                            })


                        })

                        function delete_work_experience(workexpid) {


                            $.ajax({
                                type: 'GET',
                                url: '/empoloyee/work_experience/delete',
                                data: {
                                    workexpid: workexpid,
                                },
                                success: function(data) {
                                    if (data[0].status == 1) {
                                        Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                        })

                                        $(this).closest('.work-experience-rows').remove();

                                    } else {
                                        Toast.fire({
                                            type: 'error',
                                            title: data[0].message
                                        })
                                    }
                                }
                            })
                        }



                        var $bank_account_sections = $("#editaddbank_account_section");
                        $bank_account_sections.empty(); // Clear existing content
                        // Header row for requirements
                        //     var bank_account_row = `
                    //                <div class="row g-3">
                    //                     <div class="col-md-3">
                    //                         <label for="Company" class="form-label">Company Name</label>

                    //                      </div>
                    //                     <div class="col-md-3">
                    //                         <label for="Designation"
                    //                             class="form-label">Designation</label>

                    //                     </div>
                    //                     <div class="col-md-3">
                    //                         <label for="Datefrom" class="form-label">Date From</label>

                    //                     </div>
                    //                     <div class="col-md-3">
                    //                         <label for="Dateto" class="form-label">Date To</label>
                    //                         <div class="d-flex align-items-center">

                    //                         </div>

                    //                     </div>


                    //                 </div>
                    // `;
                        //     $bank_account_sections.append(bank_account_row);

                        // Iterate through each requirement
                        bankAccounts.forEach(function(bankAccount) {
                            // Generate unique ID for the new row
                            var rowCount = $bank_account_sections.children(
                                    '.row')
                                .length + 1;
                            var rowId = 'bank_account_rows_' + rowCount;

                            var bank_account_rowBody = `
                                  <div class="addbank-account-rows mb-3" id="' + rowId + '" style="margin-top: -13px;">
                                    <input type="text" class="form-control bank_name" id="edit_append_bank_name" 
                                    name="bankAccount[][bankname]"  data-id="${bankAccount.id}" value="${bankAccount.bankname}"
                                    style="background-color: transparent;" >
                                        <div class="d-flex align-items-center mt-1">
                                            <input type="text" class="form-control bank_number" id="edit_append_bank_number" 
                                              name="bankAccount[][banknumber]" value="${bankAccount.banknumber}"
                                                style="background-color: transparent;">
                                            <button type="button" class="btn btn-sm btn-danger remove_bank_account_fetch ml-1"  data-id="${bankAccount.id}">
                                                <i class="fas fa-times"></i></button>
                                        </div>
                                  </div>
                                </div>
                             `;
                            $bank_account_sections.append(
                                bank_account_rowBody);
                        });

                        // }
                        $(document).on('click', '.remove_bank_account_fetch', function() {
                            $(this).closest('.addbank-account-rows').remove();
                        });


                    }


                });
            }



            // })


            $(document).on("click", "#editaddhighest_education", function() {

                var highest_education_section = $(
                    ".edithighest_education_section");
                // var highest_education_row = $(".highest-education-rows")
                //     .last().clone();

                var highest_education_row = `<div class="highest-education-rows row g-3">
                                        <div class="col-md-3">
                                            <label for="Company" class="form-label">School Name</label>
                                            <input type="text" class="form-control highest_education_schoolname" name="highest_education[][school]" id="school_name"
                                                placeholder="St. Michael's college"  required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="year_graduated" class="form-label">Year Graduated</label>
                                            <input type="text" class="form-control highest_education_schoolyear" name="highest_education[][schoolyear]" id="year_graduated"
                                                placeholder="2018-2019" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Datefrom" class="form-label">Course</label>
                                            <input type="text" class="form-control highest_education_coursetaken" name="highest_education[][coursetaken]" id="course"
                                                placeholder="AB Secondary Education" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Dateto" class="form-label">Award</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control highest_education_awards" name="highest_education[][awards]" id="award"
                                                    placeholder="Cum Laude" required>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger remove_highest_education ml-1" ><i
                                                        class="fas fa-times"></i></button>
                                            </div>

                                        </div>


                                    </div>`;
                // highest_education_row.find("input").val("");
                highest_education_section.append(highest_education_row);
            });


            $(document).on("click", "#editaddwork_expereiences", function() {

                var work_experiences_section = $(
                    ".editwork-experience-section");
                // var highest_education_row = $(".highest-education-rows")
                //     .last().clone();

                var work_experiences_row = `<div class="work-experience-rows row g-3">
                              
                                        <div class="col-md-3">
                                            <label for="Company" class="form-label">Company Name</label>
                                            <input type="text" class="form-control form-control-sm companyname" name="work_experience[][company]" id="Company"
                                                placeholder="St. Michael's college" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Designation" class="form-label">Designation</label>
                                            <input type="text" class="form-control form-control-sm designation" name="work_experience[][designation]" id="Designation"
                                                placeholder="Teacher" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Datefrom" class="form-label">Date From</label>
                                            <input type="date" class="form-control form-control-sm datefrom" name="work_experience[][periodfrom]" id="Datefrom" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Dateto" class="form-label">Date To</label>
                                            <div class="form-group d-flex">
                                                <input type="date" class="form-control form-control-sm dateto" name="work_experience[][periodto]" id="Dateto" required>
                                                <button type="button" class="btn btn-sm btn-danger remove_work_experience ml-1" >
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                        </div>


                                    </div>`;
                // highest_education_row.find("input").val("");
                work_experiences_section.append(work_experiences_row);
            });


            $(document).on("click", "#editaddbank_account", function() {

                var bank_account_section = $(
                    ".editaddbank_account_section");
                // var highest_education_row = $(".highest-education-rows")
                //     .last().clone();

                var bank_account_row = `<div class="addbank-account-rows  g-3">
              
                    <div class="editaddbank_account_rows mb-3" style="margin-top: -13px;">
                                        <input type="text" class="form-control bank_name" name="bankAccount[][bankname]" id="append_bank_name"
                                            placeholder="Input Bank Name" style="background-color: transparent;">
                                        <div class="d-flex align-items-center mt-1">
                                            <input type="text" class="form-control bank_number" name="bankAccount[][banknumber]"
                                                id="append_bank_number" placeholder="203211214">
                                            <button type="button" class="btn btn-sm btn-danger remove_bank_account ml-1">
                                                <i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                    </div>`;
                // highest_education_row.find("input").val("");
                bank_account_section.append(bank_account_row);
            });

            $(document).on('click', '#update_employee_btn', function() {

                var id = $('#edit_employeeid').val();
                var employee_firstname = $('#edit_firstName').val();
                var employee_lastname = $('#edit_lastName').val();
                var employee_middlename = $('#edit_middleName').val();
                var employee_suffix = $('#edit_suffix').val();
                var employee_sex = $('#edit_sex').val();
                var employee_cellphone = $('#edit_cellphone').val();
                var employee_email = $('#edit_email').val();
                var employee_address = $('#edit_address').val();
                var employee_rfid = $('#edit_rfid').val();
                // var employee_image = $('#edit_profile_picture')[0].files[0];

                var employee_sss = $('#edit_sss').val();
                var employee_pagibig = $('#edit_pagibig').val();
                var employee_tin = $('#edit_tin').val();
                var employee_philhealth = $('#edit_philhealth').val();

                var employee_contactfname = $('#edit_contactFirstname').val();
                var employee_contactlname = $('#edit_contactLastname').val();
                var employee_contactmname = $('#edit_contactMiddlename').val();
                var employee_contactsuffix = $('#edit_contactSuffix').val();
                var employee_contactrelationship = $('#edit_Relationship').val();
                var employee_contactcellphone = $('#edit_Cellphone').val();
                var employee_contacttelephone = $('#edit_Telephone').val();
                var employee_contactemail = $('#edit_Email').val();


                highestEducations = [];

                $('.highest-education-rows').each(function(index) {
                    var educationid = $(this).find('input.highest_education_schoolname').attr(
                        'data-id');
                    var schoolname = $(this).find('.highest_education_schoolname').val();
                    var yearsgraduated = $(this).find('.highest_education_schoolyear').val();
                    var course = $(this).find('.highest_education_coursetaken').val();
                    var award = $(this).find('.highest_education_awards').val();


                    // Create an object for each emergency contact
                    var highestEducation = {
                        educationid: educationid,
                        schoolname: schoolname,
                        yearsgraduated: yearsgraduated,
                        course: course,
                        award: award
                    };

                    // Push emergency contact object to the array
                    highestEducations.push(highestEducation);

                });


                workExperiences = [];

                $('.work-experience-rows').each(function(index) {
                    var workexpid = $(this).find('input.companyname').attr(
                        'data-id');
                    var companyname = $(this).find('.companyname').val();
                    var designation = $(this).find('.designation').val();
                    var datefrom = $(this).find('.datefrom').val();
                    var dateto = $(this).find('.dateto').val();


                    // Create an object for each emergency contact
                    var workExperience = {
                        workexpid: workexpid,
                        companyname: companyname,
                        designation: designation,
                        datefrom: datefrom,
                        dateto: dateto
                    };

                    // Push emergency contact object to the array
                    workExperiences.push(workExperience);
                });

                bankAccounts = [];

                $('.addbank-account-rows').each(function(index) {
                    var bankid = $(this).find('input.bank_name').attr(
                        'data-id');
                    var bankname = $(this).find('.bank_name').val();
                    var banknumber = $(this).find('.bank_number').val();

                    // Create an object for each emergency contact
                    var bankAccount = {
                        bankid: bankid,
                        bankname: bankname,
                        banknumber: banknumber,
                    };

                    // Push emergency contact object to the array
                    bankAccounts.push(bankAccount);
                });


                var formData = new FormData();

                // formData.append('_method', '  ');
                formData.append('employee_picture', $('#edit_employee_picture')[0].files[0]);

                formData.append('id', id);
                formData.append('employee_firstname', employee_firstname);
                formData.append('employee_lastname', employee_lastname);
                formData.append('employee_middlename', employee_middlename);
                formData.append('employee_suffix', employee_suffix);
                formData.append('employee_sex', employee_sex);
                formData.append('employee_cellphone', employee_cellphone);
                formData.append('employee_email', employee_email);
                formData.append('employee_address', employee_address);
                formData.append('employee_rfid', employee_rfid);
                // formData.append('employee_image', employee_image);
                formData.append('employee_sss', employee_sss);
                formData.append('employee_pagibig', employee_pagibig);
                formData.append('employee_tin', employee_tin);
                formData.append('employee_philhealth', employee_philhealth);
                formData.append('employee_contactfname', employee_contactfname);
                formData.append('employee_contactlname', employee_contactlname);
                formData.append('employee_contactmname', employee_contactmname);
                formData.append('employee_contactsuffix', employee_contactsuffix);
                formData.append('employee_contactrelationship', employee_contactrelationship);
                formData.append('employee_contactcellphone', employee_contactcellphone);
                formData.append('employee_contacttelephone', employee_contacttelephone);
                formData.append('employee_contactemail', employee_contactemail);

                // highestEducations.forEach(function(highestEducation, index) {
                //     formData.append('highest_educations[' + index + '][schoolname]',
                //         highestEducation
                //         .schoolname);
                //     formData.append('highest_educations[' + index + '][yearsgraduated]',
                //         highestEducation.yearsgraduated);
                //     formData.append('highest_educations[' + index + '][course]',
                //         highestEducation.course);
                //     formData.append('highest_educations[' + index + '][award]',
                //         highestEducation.award);
                // });

                highestEducations.forEach(function(highestEducation, index) {
                    formData.append(`highestEducations[${index}][educationid]`, highestEducation
                        .educationid);
                    formData.append(`highestEducations[${index}][schoolname]`, highestEducation
                        .schoolname);
                    formData.append(`highestEducations[${index}][yearsgraduated]`, highestEducation
                        .yearsgraduated);
                    formData.append(`highestEducations[${index}][course]`, highestEducation.course);
                    formData.append(`highestEducations[${index}][award]`, highestEducation.award);
                });



                workExperiences.forEach(function(workExperience, index) {
                    formData.append('work_experiences[' + index + '][workexpid]',
                        workExperience
                        .workexpid);
                    formData.append('work_experiences[' + index + '][companyname]',
                        workExperience
                        .companyname);
                    formData.append('work_experiences[' + index + '][designation]',
                        workExperience.designation);
                    formData.append('work_experiences[' + index + '][datefrom]',
                        workExperience.datefrom);
                    formData.append('work_experiences[' + index + '][dateto]',
                        workExperience.dateto);
                });


                bankAccounts.forEach(function(bankAccount, index) {
                    formData.append('bank_accounts[' + index + '][bankid]',
                        bankAccount.bankid);
                    formData.append('bank_accounts[' + index + '][bankname]',
                        bankAccount.bankname);
                    formData.append('bank_accounts[' + index + '][banknumber]',
                        bankAccount.banknumber);

                });


                $.ajax({
                    type: 'POST',
                    url: '/empoloyee/basicinformation/update',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            });
                        } else if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Updated'
                            });
                            getemployees()

                        } else {
                            Toast.fire({
                                type: 'warning',
                                title: 'Please complete all fields or verify your input'
                            });
                        }
                    }
                });



            });

            $(document).on('click', '#update_employeebtn__employmentdetails', function() {

                var id = $('#edit_employeeid').val();
                var general_workdays_id = $('#edit-select-generalworkdays').val();
                var edit_select_designation = $('#edit_select_designation').val();
                var edit_select_department = $('#edit_select_department').val();
                var edit_select_office = $('#edit_select_office').val();
                var edit_date_hired = $('#edit_date_hired').val();
                var edit_accumulated_years_service = $('#edit_accumulated_years_service').val();
                var edit_select_jobstatus = $('#edit_select_jobstatus').val();
                var edit_probationary_start_date = $('#edit_probationary_start_date').val();
                var edit_probationary_end_date = $('#edit_probationary_end_date').val();

                var edit_scheduleData = {};

                var days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                days.forEach(day => {
                    edit_scheduleData[day] = {
                        dayType: $(`#${day}_select_fetch`).val(),
                        amin: $(`#${day}_amin_time_fetch`).val() || null,
                        amout: $(`#${day}_amout_time_fetch`).val() || null,
                        pmin: $(`#${day}_pmin_time_fetch`).val() || null,
                        pmout: $(`#${day}_pmout_time_fetch`).val() || null
                    };
                });

                var formData = new FormData();

                formData.append('employee_picture', $('#edit_employee_picture')[0].files[0]);

                formData.append('id', id);
                formData.append('general_workdays_id', general_workdays_id);
                formData.append('edit_select_designation', edit_select_designation);
                formData.append('edit_select_department', edit_select_department);
                formData.append('edit_select_office', edit_select_office);
                formData.append('edit_date_hired', edit_date_hired);
                formData.append('edit_accumulated_years_service', edit_accumulated_years_service);
                formData.append('edit_select_jobstatus', edit_select_jobstatus);
                formData.append('edit_probationary_start_date', edit_probationary_start_date);
                formData.append('edit_probationary_end_date', edit_probationary_end_date);


                Object.keys(edit_scheduleData).forEach((key, index) => {
                    formData.append(`edit_scheduleData[${index}][day]`, key);
                    formData.append(`edit_scheduleData[${index}][dayType]`, edit_scheduleData[key]
                        .dayType);
                    formData.append(`edit_scheduleData[${index}][amin]`, edit_scheduleData[key]
                        .amin);
                    formData.append(`edit_scheduleData[${index}][amout]`, edit_scheduleData[key]
                        .amout);
                    formData.append(`edit_scheduleData[${index}][pmin]`, edit_scheduleData[key]
                        .pmin);
                    formData.append(`edit_scheduleData[${index}][pmout]`, edit_scheduleData[key]
                        .pmout);
                });



                $.ajax({
                    type: 'POST',
                    url: '/empoloyee/employmenttdetails/update',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            });
                        } else if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Updated'
                            });
                            getemployees()

                        } else {
                            Toast.fire({
                                type: 'warning',
                                title: 'Please complete all fields or verify your input'
                            });
                        }
                    }
                });


            });

            $(document).on('change', '#edit_employee_picture', function() {
                var file = $(this)[0].files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_profile_picture').attr('src', e.target.result);
                }

                reader.readAsDataURL(file);
            });

            $(document).on("click", ".mployment_details_nav", function(e) {
                e.preventDefault();

                $('.employee_information').hide()
                $('.employment_details').show()

                $('.mployment_details_nav').addClass('active')
                $('.employee_information_nav').removeClass('active')

            });

            $(document).on("click", ".employee_information_nav", function(e) {
                e.preventDefault();

                $('.employment_details').hide()
                $('.employee_information').show()

                $('.employee_information_nav').addClass('active')
                $('.mployment_details_nav').removeClass('active')

            });

            // $(document).on('click', '.remove_highest_education_fetch', function() {
            //     var educationid = $(this).attr('data-id')

            //     Swal.fire({
            //         title: 'Delete Education',
            //         text: 'Are you sure you want to delete selected education?',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: '<i class="fa fa-trash"></i> Delete',
            //         cancelButtonText: '<i class="fa fa-times"></i> Cancel'
            //     }).then((result) => {
            //         if (result.value) {
            //             delete_highest_education(educationid)
            //         }
            //     })


            // })

            // function delete_highest_education(educationid) {


            //     $.ajax({
            //         type: 'GET',
            //         url: '/empoloyee/education/delete',
            //         data: {
            //             educationid: educationid,
            //         },
            //         success: function(data) {
            //             if (data[0].status == 1) {
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: data[0].message
            //                 })

            //                 $(this).closest('.highest-education-rows').remove();

            //             } else {
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: data[0].message
            //                 })
            //             }
            //         }
            //     })
            // }

            $(document).on('click', '#addnewemployee', function() {
                $('#newEmployeeModal').modal('show')
                $('.employee_information_nav').click();

            });

            $(document).on('click', '#btn_saveworkday', function() {
                const scheduleData = {};
                const description = $('#workday_description').val();
                const selected_employeeid = $('#selected_employee_id').val();

                // Gather schedule data
                const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                days.forEach(day => {
                    scheduleData[day.toLowerCase()] = {
                        dayType: $(`#${day.toLowerCase()}_select`).val(),
                        amin: $(`#${day.toLowerCase()}_amin_time`).val() || null,
                        amout: $(`#${day.toLowerCase()}_amout_time`).val() || null,
                        pmin: $(`#${day.toLowerCase()}_pmin_time`).val() || null,
                        pmout: $(`#${day.toLowerCase()}_pmout_time`).val() || null
                    };
                });

                $.ajax({
                    url: '/hr/setup/foremployee_store_workday',
                    method: 'GET',
                    data: {
                        schedule: scheduleData,
                        description: description,
                        employeeid: selected_employeeid
                    },
                    success: function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Workday Saved Successfully!'
                        });
                    },
                    error: function(error) {
                        Toast.fire({
                            type: 'error',
                            title: 'Failed to save workday!'
                        });
                    }
                });
            });

        });

        function formatDate(date) {
            var options = {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            }; // Short month format (e.g., Jan)
            return new Date(date).toLocaleDateString('en-US', options).replace(',', '');
        }

        function getDayName(date) {
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[new Date(date).getDay()];
        }

        function filterTimerecords(startDate, endDate) {
            $('#timerecord').empty(); // Clear existing table data

            var workdays = [];
            var currentDate = new Date(startDate);
            var lastDate = new Date(endDate);

            while (currentDate <= lastDate) {
                workdays.push({
                    date: formatDate(currentDate),
                    day: getDayName(currentDate)
                });
                currentDate.setDate(currentDate.getDate() + 1);
            }

            var employeeattendance = [];
            workdays.forEach(function(workday) {
                employeeattendance.push({
                    date: workday.date,
                    day: workday.day,
                    am_in: '',
                    am_out: '',
                    pm_in: '',
                    pm_out: ''
                });
            });

            // Render the filtered records in the table
            employeeattendance.forEach(function(record) {
                var row = '<tr>';
                row += '<td>';
                row += record.date + '<br>';
                if (record.day === 'Saturday' || record.day === 'Sunday') {
                    row += '<span class="right badge badge-secondary">' + record.day + '</span>';
                } else {
                    row += '<span class="right badge badge-default">' + record.day + '</span>';
                }
                row += '</td>';
                row += '<td class="text-center">' + record.am_in + '</td>';
                row += '<td class="text-center">' + record.am_out + '</td>';
                row += '<td class="text-center">' + record.pm_in + '</td>';
                row += '<td class="text-center">' + record.pm_out + '</td>';
                row += '<td class="text-center">' + record.pm_in + '</td>';
                row += '<td class="text-center">' + record.pm_out + '</td>';
                row += '</tr>';
                $('#timerecord').append(row);
            });
        }

        function calculateTotalMinutes() {
            // const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            // let totalMinutes = 0;

            // days.forEach(day => {
            //     const amin_time = $(`#${day.toLowerCase()}_amin_time`).val();
            //     const amout_time = $(`#${day.toLowerCase()}_amout_time`).val();
            //     const pmin_time = $(`#${day.toLowerCase()}_pmin_time`).val();
            //     const pmout_time = $(`#${day.toLowerCase()}_pmout_time`).val();

            //     if (amin_time && amout_time && pmin_time && pmout_time) {
            //         const [amin_hour, amin_minute] = amin_time.split(':').map(Number);
            //         const [amout_hour, amout_minute] = amout_time.split(':').map(Number);
            //         const [pmin_hour, pmin_minute] = pmin_time.split(':').map(Number);
            //         const [pmout_hour, pmout_minute] = pmout_time.split(':').map(Number);

            //         totalMinutes += (amout_hour * 60 + amout_minute) - (amin_hour * 60 + amin_minute) + (
            //             pmout_hour * 60 + pmout_minute) - (pmin_hour * 60 + pmin_minute);
            //     }
            // });

            // var Fullday_totalMinutes = totalMinutes;

            var Fullday_totalMinutes = 480;

            // Update the total minutes in the respective input fields
            $('#fullday_overtime').val(`${Fullday_totalMinutes}`);
            $('#fullday_undertime').val(`${Fullday_totalMinutes}`);

            var Halfday_totalMinutes = Fullday_totalMinutes / 2;

            $('#halfday_overtime').val(`${Halfday_totalMinutes}`);
            $('#halfday_undertime').val(`${Halfday_totalMinutes}`);


        }

        calculateTotalMinutes();


        // Apply the change event listener
        $(document).on('change', '.js-flatpickr', calculateTotalMinutes);

        $(document).on('input', '.js-flatpickr', function() {
            if ($(this).val()) {
                calculateTotalMinutes();

            }
        });

        // Initialize workday schedule
        $(document).ready(function() {
            workday_select();
            calculateTotalMinutes();
        });



        $('#tarday_status').change(function() {
            if ($(this).is(':checked')) {
                $('.tardiness_main_section').show();
            } else {
                $('.tardiness_main_section').hide();
            }
        });

        // Initialize the visibility based on the checkbox status on page load
        if ($('#tarday_status').is(':checked')) {
            $('.tardiness_main_section').show();
        } else {
            $('.tardiness_main_section').hide();
        }



        $(document).on("click", "#add_tardiness", function(e) {
            e.preventDefault();
            var tardiness_section = $(".tardiness_section");
            var tardiness_row = $(".tardiness_row").first().clone();
            tardiness_row.find("input").val("");
            tardiness_row.find(".remove_tardiness").prop('hidden', false);
            tardiness_section.append(tardiness_row);
        });

        $(document).on("click", ".remove_tardiness", function(e) {
            e.preventDefault();
            $(this).closest('.tardiness_row').remove();
        });


        var currentDate = moment().format('YYYY-MM-DD');
        $('#dtrdaterange').daterangepicker({
            startDate: currentDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('#dtrdaterange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            filterTimerecords(startDate, endDate);
        });

        function workday_select() {
            // Days of the week
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            // Reference to table body
            const scheduleTable = document.getElementById('scheduleTable');

            // Clear table content
            scheduleTable.innerHTML = ``;

            // Generate rows dynamically
            days.forEach(day => {
                scheduleTable.innerHTML += `
            <tr>
                <td>${day}</td>
                <td>
                    <select class="form-control days form-control-sm" id="${day.toLowerCase()}_select" name="${day.toLowerCase()}_select">
                        <option value="1" selected>Full Day</option>
                        <option value="2">Half AM</option>
                        <option value="3">Half PM</option>
                        <option value="0">Day Off</option>
                    </select>
                </td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amin_time" name="${day.toLowerCase()}_amin_time" value="08:00"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amout_time" name="${day.toLowerCase()}_amout_time" value="12:00"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmin_time" name="${day.toLowerCase()}_pmin_time" value="13:00"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmout_time" name="${day.toLowerCase()}_pmout_time" value="17:00"></td>
            </tr>
        `;
            });
        }

        // Function to populate the general workdays dropdown
        function select_generalworkdays() {
            $.ajax({
                type: "GET",
                url: "/newempoloyee/select_generalworkdays",
                success: function(data) {
                    // Clear and initialize the dropdown
                    $('#select-generalworkdays').empty();
                    $('#select-generalworkdays').append('<option value="">Select General Workdays</option>');
                    data.forEach(function(item) {
                        $('#select-generalworkdays').append(
                            `<option value="${item.id}">${item.text}</option>`);
                    });
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load workdays!'
                    });
                }
            });
        }

        // Call the function to populate workdays on page load
        select_generalworkdays();

        // Event listener for when the dropdown value changes
        $('#select-generalworkdays').on('change', function() {
            const dataId = $(this).val();
            console.log(dataId);

            if (!dataId) {
                // Clear the modal or table if no workday is selected
                clearWorkdayDetails();
                return;
            }

            // Fetch and populate workday details
            $.ajax({
                url: '/hr/setup/edit_workday',
                method: 'GET',
                data: {
                    id: dataId
                },
                success: function(data) {
                    if (data) {
                        // Populate the workday details
                        populateWorkdayDetails(data);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'No data found!'
                        });
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load data!'
                    });
                }
            });
        });

        // Function to populate workday details
        function populateWorkdayDetails(data) {
            // Clear and repopulate the table
            const scheduleTable = document.getElementById('generalworkdaysTable');
            scheduleTable.innerHTML = ''; // Clear existing rows

            // Days of the week
            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            // Generate rows dynamically
            days.forEach(day => {
                scheduleTable.innerHTML += `
            <tr style="font-size: 13px;">
                <td>${day.charAt(0).toUpperCase()}${day.charAt(1)}${day.charAt(2)}</td>
                <td>
                    <option id="${day.toLowerCase()}_select_general" name="${day.toLowerCase()}_select_general" value="${data[day]}">${data[day] == 1 ? 'Full Day' : data[day] == 2 ? 'Half AM' : data[day] == 3 ? 'Half PM' : 'Day Off'}</option>
                </td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_amin_time_general" name="${day}_amin_time_general" value="${data[`${day}_amin`]}"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_amout_time_general" name="${day}_amout_time_general" value="${data[`${day}_amout`]}"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_pmin_time_general" name="${day}_pmin_time_general" value="${data[`${day}_pmin`]}"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" style="font-size: 10.7px;" id="${day}_pmout_time_general" name="${day}_pmout_time_general" value="${data[`${day}_pmout`]}"></td>
            </tr>
        `;
                // <input type="text" class="form-control days form-control-sm" id="${day}_select" name="${day}_select" value="${data[day]}">
            });
        }

        // Function to clear workday details
        function clearWorkdayDetails() {
            $('#workday_description').val('');
            const scheduleTable = document.getElementById('scheduleTable');
            scheduleTable.innerHTML = ''; // Clear existing rows
        }


        $(document).on('click', '#btn_addworkday', function() {
            workday_select()
            $('#modal_desc').text('Cuztomized Workday')
            $('.modal_addworkday').modal('show')
        })

        $('.modal_addworkday').on('hidden.bs.modal', function() {
            $('#workday_description').val('')
            $('.modal_addworkday').find('input').val('')
            $('#scheduleTable').empty()
        });

        $(document).on('change', 'select[name="designationid"]', function() {
            $('#academicprogram').empty();
            if ($(this)[0].selectedOptions[0].text == 'TEACHER' || $(this)[0].selectedOptions[0].text ==
                'PRINCIPAL') {
                $('.submit-btn').attr('id', 'submitbutton');
                $('.submit-btn').prop('type', 'button');
                $.ajax({
                    url: '/hr/employees/getacademicprogram',
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#academicprogram').append(
                                '<div class="icheck-primary d-inline">' +
                                '<input type="checkbox" id="checkboxPrimary' + value.id +
                                '" name="academicprogram[]" value="' + value.id +
                                '" checked="">' +
                                '<label for="checkboxPrimary' + value.id + '">' +
                                value.progname +
                                '</label>' +
                                '</div>' +
                                '<br>'
                            );
                        })
                    }
                });
            }
        });
        $(document).ready(function() {

            // $('#sex').select2()

            // $('body').addClass('sidebar-collapse')
            $('input[name="contactno"]').inputmask({
                mask: "9999-999-9999"
            });
            $('input[name="emercontactno"]').inputmask({
                mask: "9999-999-9999"
            });

        });
        $(document).on('click', '#submitbutton', function() {
            if ($('input[name="academicprogram[]"]:checked').length == 0) {
                $('#academicprogram').prepend(
                    '<div class="row">' +
                    '<div class="text-danger">' +
                    'Please select an academic program!' +
                    '</div>' +
                    '</div>'
                )
            } else {
                $(this).prop('type', 'submit');
            }
        })
        $(document).on("input", 'input[name="noofchildren"]', function() {
            this.value = this.value.replace(/\D/g, '');
        });
        // $(document).on("input", 'input[name="licensenumber"]', function() {
        //     this.value = this.value.replace(/\D/g,'');
        // });
        $(document).on("click", "#addhighest_education", function(e) {
            e.preventDefault();
            var work_experience_section = $(".highest_education_section");
            var work_experience_row = $(".highest_education_row").first().clone();
            work_experience_row.find("input").val("");
            work_experience_row.find(".remove_highest_education").prop('hidden', false);
            work_experience_section.append(work_experience_row);
        });

        $(document).on("click", ".remove_highest_education", function(e) {
            e.preventDefault();
            $(this).closest('.highest_education_row').remove();
        });



        $(document).on("click", "#addwork_expereiences", function(e) {
            e.preventDefault();
            var work_experience_section = $(".work_experience_section");
            var work_experience_row = $(".work_experience_row").first().clone();
            work_experience_row.find("input").val("");
            work_experience_row.find(".remove_work_experience").prop('hidden', false);
            work_experience_section.append(work_experience_row);
        });

        $(document).on("click", ".remove_work_experience", function(e) {
            e.preventDefault();
            $(this).closest('.work_experience_row').remove();
        });

        $(document).on("click", "#addbank_account", function(e) {
            e.preventDefault();
            var addbank_account_section = $(".addbank_account_section");
            var addbank_account_row = $(".addbank_account_row").first().clone();
            addbank_account_row.find("input").val("");
            addbank_account_row.find(".bank_name").prop('hidden', false);
            addbank_account_row.find(".bank_number").prop('hidden', false);
            addbank_account_row.find(".remove_bank_account_static").prop('hidden', true);
            addbank_account_row.find(".bpi_name").prop('hidden', true);
            addbank_account_row.find(".bpi_number").prop('hidden', true);
            addbank_account_row.find(".remove_bank_account").prop('hidden', false);
            addbank_account_section.append(addbank_account_row);
        });


        $(document).on("click", ".remove_bank_account", function(e) {
            e.preventDefault();
            $(this).closest('.addbank_account_row').remove();
        });

        $(document).on("click", ".remove_bank_account_static", function(e) {
            e.preventDefault();
            $(this).closest('.addbank_account_row').remove();
        });

        $(document).on("click", "#save_employeebtn", function(e) {
            e.preventDefault();
            add_newemployee();
        });

        function add_newemployee() {

            var minGradeRequirement = $('#minGradeRequirement').val();
            var setActivePointEquivalency = $('#input_Active_recognitiontype').is(':checked') ? 1 : 0;
            var recognitionTypeDescription = $('#recognitionTypeDescription').val();

            $.ajax({
                type: 'GET',
                url: '/setup/academicrecognition/create',
                data: {
                    recognitionTypeDesc: recognitionTypeDescription,
                    minGradeRequirement: minGradeRequirement,
                    setActive: setActivePointEquivalency

                },
                success: function(data) {
                    if (data[0].status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        })


                    } else if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        })


                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('change', '#employee_picture', function() {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile_picture').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        $('#save_employeebtn__employeeInformation').on('click', function(event) {
            // event.preventDefault();
            add_newemployee()

        });


        function add_newemployee() {

            var rfid = $('#rfid').val();
            var firstName = $('#firstName').val();
            var middleName = $('#middleName').val();
            var lastName = $('#lastName').val();
            var suffix = $('#suffix').val();
            var sex = $('#sex').val();
            var civilStatus = $('#civilStatus').val();
            var birthdate = $('#birthdate').val();
            var cellphone = $('#cellphone').val();
            var email = $('#email').val();
            var address = $('#address').val();

            var contactFirstname = $('#contactFirstname').val();
            var contactMiddlename = $('#contactMiddlename').val();
            var contactLastname = $('#contactLastname').val();
            var contactSuffix = $('#contactSuffix').val();
            var Relationship = $('#Relationship').val();
            var Telephone = $('#Telephone').val();
            var Cellphone = $('#Cellphone').val();
            var Email = $('#Email').val();

            higher_educational_attainment = [];

            $('.highest_education_row').each(function() {
                var Schoolname = $(this).find('#school_name').val();
                var Year_graduated = $(this).find('#year_graduated').val();
                var Course = $(this).find('#course').val();
                var Award = $(this).find('#award').val();

                var higherEducational = {
                    Schoolname: Schoolname,
                    Year_graduated: Year_graduated,
                    Course: Course,
                    Award: Award
                };

                higher_educational_attainment.push(higherEducational);
            });

            work_experience = [];

            $('.work_experience_row').each(function() {
                var Company = $(this).find('#Company').val();
                var Designation = $(this).find('#Designation').val();
                var Datefrom = $(this).find('#Datefrom').val();
                var Dateto = $(this).find('#Dateto').val();

                var workExperience = {
                    Company: Company,
                    Designation: Designation,
                    Datefrom: Datefrom,
                    Dateto: Dateto
                };

                work_experience.push(workExperience);
            });

            bank_account = [];

            $('.addbank_account_row').each(function() {
                var append_bank_name = $(this).find('#append_bank_name').val();
                var append_bank_number = $(this).find('#append_bank_number').val();

                // Create an object for each bank account
                var bankAccount = {
                    append_bank_name: append_bank_name,
                    append_bank_number: append_bank_number
                };

                // Push bank account object to the array
                bank_account.push(bankAccount);
            });







            var formData = new FormData();

            higher_educational_attainment.forEach(function(higherEducational, index) {

                formData.append('higher_educational_attainments[' + index + '][Schoolname]', higherEducational
                    .Schoolname);
                formData.append('higher_educational_attainments[' + index + '][Year_graduated]', higherEducational
                    .Year_graduated);
                formData.append('higher_educational_attainments[' + index + '][Course]', higherEducational.Course);
                formData.append('higher_educational_attainments[' + index + '][Award]', higherEducational.Award);

            });

            work_experience.forEach(function(workExperience, index) {

                formData.append('work_experiences[' + index + '][Company]', workExperience.Company);
                formData.append('work_experiences[' + index + '][Designation]', workExperience
                    .Designation);
                formData.append('work_experiences[' + index + '][Datefrom]', workExperience.Datefrom);
                formData.append('work_experiences[' + index + '][Dateto]', workExperience.Dateto);

            });

            bank_account.forEach(function(bankAccount, index) {
                if (index > 0) {
                    formData.append('bank_accounts[' + index + '][append_bank_name]', bankAccount.append_bank_name);
                    formData.append('bank_accounts[' + index + '][append_bank_number]', bankAccount
                        .append_bank_number);
                }
            });

            var sss = $('#sss').val();
            var pagibig = $('#pagibig').val();
            var philhealth = $('#philhealth').val();
            var tin = $('#tin').val();

            formData.append('employee_picture', $('#employee_picture')[0].files[0]);

            formData.append('firstName', firstName);
            formData.append('middleName', middleName);
            formData.append('lastName', lastName);
            formData.append('suffix', suffix);
            formData.append('sex', sex);
            formData.append('civilStatus', civilStatus);
            formData.append('birthdate', birthdate);
            formData.append('cellphone', cellphone);
            formData.append('email', email);
            formData.append('address', address);
            formData.append('rfid', rfid);

            formData.append('contactFirstname', contactFirstname);
            formData.append('contactMiddlename', contactMiddlename);
            formData.append('contactLastname', contactLastname);
            formData.append('contactSuffix', contactSuffix);
            formData.append('Relationship', Relationship);
            formData.append('Telephone', Telephone);
            formData.append('Cellphone', Cellphone);
            formData.append('Email', Email);

            formData.append('SSS', sss);
            formData.append('PagIbig', pagibig);
            formData.append('Philhealth', philhealth);
            formData.append('TIN', tin);

            $.ajax({
                type: 'POST',
                url: '/newempoloyee/basicinformation/create',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data.message
                        });
                    } else if (data.status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        });
                        $('#selected_employee_id').val(data.employee_id);
                        $('#empid').val(data.tid);
                    } else {
                        Toast.fire({
                            type: 'warning',
                            title: 'Please complete all fields or verify your input'
                        });
                    }
                }
            });
        }


        $('#save_employeebtn__employmentdetails').on('click', function(event) {
            // event.preventDefault();
            add_newemployee_employmentDetails()

        });


        function add_newemployee_employmentDetails() {

            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();

            var selected_employeeid = $('#selected_employee_id').val();
            var designation = $('#select-designation').val();
            var department = $('#select-department').val();
            var office = $('#select-office').val();
            var date_hired = $('#date_hired').val();
            var yos = $('#accumulated_years_service').val();
            var job_status = $('#select-jobstatus').val();
            var prob_start_date = $('#probationary_start_date').val();
            var prob_end_date = $('#probationary_end_date').val();
            var general_workdays = $('#select-generalworkdays').val();

            var scheduleData = {};

            var days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            days.forEach(day => {
                scheduleData[day] = {
                    dayType: $(`#${day}_select_general`).val(),
                    amin: $(`#${day}_amin_time_general`).val() || null,
                    amout: $(`#${day}_amout_time_general`).val() || null,
                    pmin: $(`#${day}_pmin_time_general`).val() || null,
                    pmout: $(`#${day}_pmout_time_general`).val() || null
                };
            });



            var formData = new FormData();

            formData.append('firstname', firstName);
            formData.append('lastname', lastName);

            formData.append('selected_employeeid', selected_employeeid);
            formData.append('designation', designation);
            formData.append('department', department);
            formData.append('office', office);
            // formData.append('office', office);
            formData.append('date_hired', date_hired);
            formData.append('yos', yos);
            formData.append('job_status', job_status);
            formData.append('prob_start_date', prob_start_date);
            formData.append('prob_end_date', prob_end_date);
            formData.append('general_workdays', general_workdays);
            // formData.append('prob_start_date', prob_start_date);
            // formData.append('prob_end_date', prob_end_date);


            Object.keys(scheduleData).forEach((key, index) => {
                formData.append(`scheduleData[${index}][day]`, key);
                formData.append(`scheduleData[${index}][dayType]`, scheduleData[key].dayType);
                formData.append(`scheduleData[${index}][amin]`, scheduleData[key].amin);
                formData.append(`scheduleData[${index}][amout]`, scheduleData[key].amout);
                formData.append(`scheduleData[${index}][pmin]`, scheduleData[key].pmin);
                formData.append(`scheduleData[${index}][pmout]`, scheduleData[key].pmout);
            });

            $.ajax({
                type: 'POST',
                url: '/newempoloyee/employmentdetails/create',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data.message
                        });
                    } else if (data.status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        });

                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data.message
                        });
                    }
                }
            });
        }

        /******  3d9d03dd-eb3d-4965-90ed-c9e3dfd19a78  *******/
        $(document).on("click", "#addapproval", function(e) {
            e.preventDefault();
            var approval_section = $(".approval_section");
            var approval_row = $(".approval_row").first().clone();
            // approval_row.find("input").val("");
            approval_row.find(".remove_approval").prop('hidden', false);
            var current_count = approval_section.children(".approval_row").length + 1; // Count existing rows + 1
            var ordinal_suffix = getOrdinalSuffix(current_count); // Get the ordinal suffix (e.g., 1st, 2nd, 3rd)
            approval_row.find("label").text(`${current_count}${ordinal_suffix} Approval`);

            approval_section.append(approval_row);

        });

        // Function to remove an approval row
        $(document).on("click", ".remove_approval", function(e) {
            e.preventDefault();
            $(this).closest(".approval_row").remove();

            // Update labels after removing a row
            $(".approval_row").each(function(index) {
                var updated_count = index + 1; // Update index starting from 1
                var ordinal_suffix = getOrdinalSuffix(updated_count);
                $(this).find("label").text(`${updated_count}${ordinal_suffix} Approval`);
            });
        });

        // Function to determine the ordinal suffix (e.g., st, nd, rd, th)
        function getOrdinalSuffix(number) {
            const j = number % 10,
                k = number % 100;
            if (j == 1 && k != 11) return "st";
            if (j == 2 && k != 12) return "nd";
            if (j == 3 && k != 13) return "rd";
            return "th";
        }


        function select_departments() {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/select_departments",
                success: function(data) {
                    console.log(data);
                    $('#select-department').empty()
                    $('#select-department').append('<option value="">Select Department</option>')
                    $('#select-department').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Department'
                    });

                }
            });
        }


        select_departments()

        function select_designations() {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/select_designations",
                success: function(data) {
                    $('#select-designation').empty()
                    $('#select-designation').append('<option value="">Select Designation</option>')
                    $('#select-designation').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Designation',
                        dropdownCssClass: 'custom-dropdown'
                    });
                }
            });
        }

        select_designations()


        function select_offices() {
            $.ajax({
                type: "GET",
                url: "/newempoloyee/select_offices",
                success: function(data) {
                    $('#select-office').empty()
                    $('#select-office').append('<option value="">Select Office</option>')
                    $('#select-office').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Office',
                        dropdownCssClass: 'custom-dropdown'
                    });
                }
            });
        }

        select_offices()


        function select_jobstatus() {
            $.ajax({
                type: "GET",
                url: "/newempoloyee/select_jobstatus",
                success: function(data) {
                    $('#select-jobstatus').empty()
                    $('#select-jobstatus').append('<option value="">Select Job Status</option>')
                    $('#select-jobstatus').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Job Status',
                        dropdownCssClass: 'custom-dropdown'
                    });
                }
            });
        }

        select_jobstatus()


        $(document).on("click", ".mployment_details_nav", function(e) {
            e.preventDefault();

            $('.employee_information').hide()
            $('.employment_details').show()

            $('.mployment_details_nav').addClass('active')
            $('.employee_information_nav').removeClass('active')

        });

        $(document).on("click", ".employee_information_nav", function(e) {
            e.preventDefault();

            $('.employment_details').hide()
            $('.employee_information').show()

            $('.employee_information_nav').addClass('active')
            $('.mployment_details_nav').removeClass('active')

        });

        $('#employeecloseModalBtn').on('click', function() {
            var hasData =

                $("#rfid").val().trim() !== "" ||
                $("#firstName").val().trim() !== "" ||
                $("#middleName").val().trim() !== "" ||
                $("#lastName").val().trim() !== "" ||
                $("#suffix").val().trim() !== "" ||
                $("#civilStatus").val().trim() !== "" ||
                $("#birthdate").val().trim() !== "" ||
                $("#cellphone").val().trim() !== "" ||
                $("#email").val().trim() !== "" ||
                $("#address").val().trim() !== "" ||

                $("#contactFirstname").val().trim() !== "" ||
                $("#contactMiddlename").val().trim() !== "" ||
                $("#contactLastname").val().trim() !== "" ||
                $("#contactSuffix").val().trim() !== "" ||
                $("#Relationship").val().trim() !== "" ||
                $("#Telephone").val().trim() !== "" ||
                $("#Cellphone").val().trim() !== "" ||
                $("#Email").val().trim() !== "" ||

                $("#sss").val().trim() !== "" ||
                $("#pagibig").val().trim() !== "" ||
                $("#philhealth").val().trim() !== "" ||
                $("#tin").val().trim() !== "" ||

                //employment details
                $("#date_hired").val().trim() !== "" ||
                $("#accumulated_years_service").val().trim() !== "" ||
                $("#probationary_start_date").val().trim() !== "" ||
                $("#probationary_end_date").val().trim() !== ""


            ;

            $(".highest_education_section .highest_education_row:visible").each(function() {
                if (
                    $(this).find("#school_name").val().trim() != "" ||
                    $(this).find("#year_graduated").val().trim() != "" ||
                    $(this).find("#course").val().trim() != "" ||
                    $(this).find("#award").val().trim() != ""
                ) {
                    hasData = true;
                    return false;
                }
            })

            $(".work_experience_section .work_experience_row:visible").each(function() {
                if (
                    $(this).find("#Company").val().trim() != "" ||
                    $(this).find("#Designation").val().trim() != "" ||
                    $(this).find("#Datefrom").val().trim() != "" ||
                    $(this).find("#Dateto").val().trim() != ""
                ) {
                    hasData = true;
                    return false;
                }
            })

            $(".addbank_account_section .addbank_account_row:visible").each(function() {
                if (
                    $(this).find("#append_bank_name").val().trim() != "" ||
                    $(this).find("#append_bank_number").val().trim() != ""
                ) {
                    hasData = true;
                    return false;
                }
            })

            //employment details

            if ($('#select-designation').val() !== "") {
                hasData = true;
            }
            if ($('#select-department').val() !== "") {
                hasData = true;
            }
            if ($('#select-office').val() !== "") {
                hasData = true;
            }
            if ($('#select-jobstatus').val() !== "") {
                hasData = true;
            }


            //workday
            if ($('#select-generalworkdays').val() !== "") {
                hasData = true;
            }


            if (hasData) {
                // Confirm with the user before deleting all attendance data
                Swal.fire({
                    // title: 'Create Grade Point Equivalency Reset',
                    text: 'You have unsaved changes. Would you like to save your work before leaving?',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Save Changes',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Discard Changes',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                        $("#employee_picture").val("");

                        $("#rfid").val("");
                        $("#firstName").val("");
                        $("#middleName").val("");
                        $("#lastName").val("");
                        $("#suffix").val("");
                        $("#civilStatus").val("");
                        $("#birthdate").val("");
                        $("#cellphone").val("");
                        $("#email").val("");
                        $("#address").val("");

                        $("#contactFirstname").val("");
                        $("#contactMiddlename").val("");
                        $("#contactLastname").val("");
                        $("#contactSuffix").val("");
                        $("#Relationship").val("");
                        $("#Telephone").val("");
                        $("#Cellphone").val("");
                        $("#Email").val("");

                        $('#sss').val("");
                        $('#pagibig').val("");
                        $('#philhealth').val("");
                        $('#tin').val("");

                        //employment details
                        $('#select-designation').val("").trigger('change');
                        $('#select-department').val("").trigger('change');
                        $('#select-office').val("").trigger('change');
                        $('#select-jobstatus').val("").trigger('change');
                        $('#date_hired').val("");
                        $('#accumulated_years_service').val("");
                        $('#probationary_start_date').val("");
                        $('#probationary_end_date').val("");

                        //workday
                        $('#select-generalworkdays').val("").trigger('change');
                        $('#generalworkdaysTable').empty();



                        $(".highest_education_row").each(function() {
                            $(this).find('input').val("");
                            $(this).find('select').val("").trigger('change');
                        });

                        $(".highest_education_row:not(:first-of-type)").remove();

                        $(".work_experience_row").each(function() {
                            $(this).find('input').val("");
                            $(this).find('select').val("").trigger('change');
                        });
                        $(".work_experience_row:not(:first-of-type)").remove();


                        $(".addbank_account_row").each(function() {
                            $(this).find('input').val("");
                            $(this).find('select').val("").trigger('change');
                        });
                        $(".addbank_account_row:not(:first-of-type)").remove();


                    } else {
                        // Save employee
                        $('#save_employeebtn__employeeInformation').click();
                        $('#newEmployeeModal').modal('show');

                        $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                        $("#employee_picture").attr('src', "/avatar/S(F) 1.png");

                    }
                });
            } else {
                // Hide modal
                $('#newEmployeeModal').modal('hide');

                $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                $("#employee_picture").val("");
            }
        });




        // $('#employeecloseModalBtn').on('click', function() {

        //     var NewData = $("#select-designation").val() !== $("#select-designation").data('previous');
        //     if (NewData) {
        //         // Confirm with the user before deleting all attendance data
        //         Swal.fire({
        //             // title: 'Create Grade Point Equivalency Reset',
        //             text: 'You have unsaved changes from employment details. Would you like to save your work before leaving?',
        //             type: 'warning',
        //             showCancelButton: true,
        //             cancelButtonText: 'Save Changes',
        //             confirmButtonColor: '#d33',
        //             cancelButtonColor: '#3085d6',
        //             confirmButtonText: 'Discard Changes',
        //             reverseButtons: true
        //         }).then((result) => {
        //             if (result.value) {

        //                 $('#select-designation').val("").trigger('change');

        //             } else {
        //                 // Save employee
        //                 $('#save_employeebtn__employeeInformation').click();
        //                 $('#newEmployeeModal').modal('show');
        //             }
        //         });
        //     } else {
        //         // Hide modal
        //         $('#newEmployeeModal').modal('hide');
        //     }

        // });
    </script>
@endsection
