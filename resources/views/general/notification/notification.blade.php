@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <style>
        body {
            /* font-family: "Roboto", sans-serif; */
            font-family: "Poppins", sans-serif;
        }

        img {
            border-radius: 0px !important;
        }

        .alert {
            width: 80px;
            border-radius: 20px;
            text-align: center;
        }

        .button-edit {
            width: 80px;
            border-radius: 20px;
            text-align: center;
        }

        .button-create {
            width: 35%;
            border-radius: 10px;
            text-align: center;
            background-color: #003687;
        }

        .sig-button {
            width: 50%;
            border-radius: 20px;
            text-align: center;
            background-color: #003687;
            position: flex;
        }

        .input {
            border-color: black
        }

        .modal-header-sm {
            padding: 0.5rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: calc(0.3rem - 1px);
            border-top-right-radius: calc(0.3rem - 1px);
        }

        .modal-header-sm .modal-title {
            font-size: 0.875rem;
            /* Adjust the font size as needed */
        }

        .activeimage {
            border: 2px solid skyblue;
        }

        .align-middle td {
            vertical-align: middle;
        }

        .rounded-badge {
            border-radius: 20px;
            text-align: center;
        }

        .badge-light {
            background-color: #CFCFCF;
        }

        .custom-bg-lightgray {
            background-color: #d3d3d3;
            color: black;
        }

        .border-blue {
            border-radius: 20px;
            background-color: #CFCFCF;
        }

        .rounded-left {
            border-radius: 20px 0px 0px 20px !important;
        }

        .rounded-right {
            border-radius: 0px 20px 20px 0px !important;
        }

        .outlined-row {
            /* outline: 2px solid #101b92 !important; */
            border-radius: 15px !important;
            border-bottom: 3px solid #003687;
            /* outline: 2px solid #003687 !important; */
        }

        .timeline::before {
            border-radius: .25rem;
            background-color: #003687;
            bottom: 0;
            content: "";
            left: 31px;
            margin: 0;
            position: absolute;
            top: 0;
            width: 4px;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        .card {
            /* box-shadow: 1px 1px 4px #272727c9 !important; */
            border: none !important;
        }
    </style>
@endsection



@section('content')



    <div class="card">
        <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#notification" data-toggle="tab">Notifications <span class="badge badge-primary">2</span></a> </li>
                <li class="nav-item"><a class="nav-link" href="#request" data-toggle="tab">Request  <span class="badge badge-warning">3</span></a></li>
                <li class="nav-item"><a class="nav-link" href="#complaints" data-toggle="tab">Complaints  <span class="badge badge-danger">3</span></a></li>
            </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="notification">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SUBJECT</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>DUE DATE</th>
                                <th>STATUS</th>
                                <th>DEPARTMENT APPROVAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                
                                
                                    <a href="#"> Requirement Submission</a>
                                
                                </td>
                                <td>2024-01-01</td>
                                <td>2024-01-15</td>
                                <td>2024-01-31</td>
                                <td>On Process</td>
                                <td>HR</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">Insurance Effectivity</a>
                                    
                                
                                </td>
                                <td>2024-11-01</td>
                                <td>2024-11-15</td>
                                <td>2024-11-31</td>
                                <td>Approved Finance</td>
                                <td>FINANCE</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="request">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SUBJECT</th>
                                <th>DATE FILED</th>
                                <th>REMARKS</th>
                                <th>DEPARTMENT APPROVAL</th>
                                <th>FILE UPLOADED</th>
                                <th>STATUS</th>
                                <th>OR NO</th>
                                <th>DATE PAID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="#">OFFICE SUPPLIES</a>
                                </td>
                                <td>2024-01-01</td>
                                <td> NEED ASAP</td>
                                <td>HR</td>
                                <td> REQUEST PDF </td>
                                <td>On Process</td>
                                <td>52355</td>
                                <td>2023-12-24</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">LEAVE</a>
                                </td>
                                <td>2024-01-15</td>
                                <td> MATERNITY </td>
                                <td>FINANCE</td>
                                <td> LEAVE FORM </td>
                                <td>Approved</td>
                                <td>43243</td>
                                <td>2023-12-25</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="complaints">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SUBJECT</th>
                                <th>DEPARTMENT</th>
                                <th>DATE FILED </th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>RICE ALLOWANCE DECREPANCY</td>
                                <td>HR</td>
                                <td>2023-12-25</td>
                                <td>On Process</td>
                            </tr>
                            <tr>
                                <td>UNPAID OVERTIME</td>
                                <td>HR</td>
                                <td>2023-12-24</td>
                                <td>Approved</td>
                            </tr>
                            <tr>
                                <td>UNPAID LEAVE</td>
                                <td>HR</td>
                                <td>2023-12-23</td>
                                <td>Declined</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.card-body -->
    </div>






@endsection


@section('footerjavascript')






@endsection