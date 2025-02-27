@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    #employee_datatables .form-control {
        height: calc(2rem + 1px)!important;
        padding: 0.2rem 1.5rem!important;
    }
    #loademployee2_datatables > thead > tr > th {
        padding-right: 0px;
        padding-left: 0px;
    }

    @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");

    .wrapper .icon {
        background-color: none!important;
        position: relative;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .tooltip {
        text-align: center;
        width: 150px;
        position: absolute;
        top: 0;
        font-size: 14px;
        padding: 5px 8px;
        border-radius: 5px;
        box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .tooltip::before {
        position: absolute;
        content: "";
        height: 8px;
        width: 8px;
        bottom: -3px;
        left: 50%;
        transform: translate(-50%) rotate(45deg);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .icon:hover .tooltip {
        top: -33px;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        z-index: 1000;

    }

    .wrapper .icon:hover span,
    .wrapper .icon:hover .tooltip {
        text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
        z-index: 1000;

    }

    .wrapper .pdfs:hover .tooltip,
    .wrapper .pdfs:hover .tooltip::before {
        background: #0275d8;
        color: #fff;
        z-index: 1000;
    }
    .select2-container .select2-selection--single {
        height: 37px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #007bff;
        border-radius: 4px;
        cursor: default;
        float: left;
        margin-right: 5px;
        margin-top: 5px;
        padding: 0 5px;
    }

    .modal.lower-right .modal-dialog {
        position: fixed;
        bottom: 150px;
        right: 20px;
        margin: 0;
        width: 700px;
        max-width: 700px;
        pointer-events: auto;
    }
    .modal-backdrop {
        display: none; /* Hide the backdrop */
    }

    .zoom-in {
        transition: transform 0.3s, font-size 0.3s;
        transform: scale(1.1);
        font-size: 1.2em;
    }

    .zoom-out {
        transition: transform 0.3s, font-size 0.3s;
        transform: scale(1);
        font-size: 1em;
    }

    .rotate-icon {
        display: inline-block;
        transform: rotate(-45deg); /* Change the degrees as needed */
        transition: transform 0.3s ease-in-out; /* Optional: Add smooth transition */
    }

    .file-container-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .file-container-item .file-name {
        margin-left: 10px;
    }
    .file-container-item .remove-file {
        margin-left: auto;
        color: red;
        cursor: pointer;
    }

    .loader {
        /* text-align: center; */
        --w:10ch;
        font-weight: bold;
        font-family: monospace;
        font-size: 50px;
        letter-spacing: var(--w);
        width:var(--w);
        overflow: hidden;
        white-space: nowrap;
        text-shadow: 
            calc(-1*var(--w)) 0, 
            calc(-2*var(--w)) 0, 
            calc(-3*var(--w)) 0, 
            calc(-4*var(--w)) 0,
            calc(-5*var(--w)) 0, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0, 
            calc(-8*var(--w)) 0, 
            calc(-9*var(--w)) 0;
            animation: l16 2s infinite, dots 2s linear infinite;

        }
        .loader:before {
        content:"Sending...";
        }
        @keyframes l16 {
        20% {text-shadow: 
            calc(-1*var(--w)) 0, 
            calc(-2*var(--w)) 0 red, 
            calc(-3*var(--w)) 0, 
            calc(-4*var(--w)) 0 #ffa516,
            calc(-5*var(--w)) 0 #63fff4, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0, 
            calc(-8*var(--w)) 0 green, 
            calc(-9*var(--w)) 0;}
        40% {text-shadow: 
            calc(-1*var(--w)) 0, 
            calc(-2*var(--w)) 0 red, 
            calc(-3*var(--w)) 0 #e945e9, 
            calc(-4*var(--w)) 0,
            calc(-5*var(--w)) 0 green, 
            calc(-6*var(--w)) 0 orange, 
            calc(-7*var(--w)) 0, 
            calc(-8*var(--w)) 0 green, 
            calc(-9*var(--w)) 0;}
        60% {text-shadow: 
            calc(-1*var(--w)) 0 lightblue, 
            calc(-2*var(--w)) 0, 
            calc(-3*var(--w)) 0 #e945e9, 
            calc(-4*var(--w)) 0,
            calc(-5*var(--w)) 0 green, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0 yellow, 
            calc(-8*var(--w)) 0 #ffa516, 
            calc(-9*var(--w)) 0 red;}
        80% {text-shadow: 
            calc(-1*var(--w)) 0 lightblue, 
            calc(-2*var(--w)) 0 yellow, 
            calc(-3*var(--w)) 0 #63fff4, 
            calc(-4*var(--w)) 0 #ffa516,
            calc(-5*var(--w)) 0 red, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0 grey, 
            calc(-8*var(--w)) 0 #63fff4, 
            calc(-9*var(--w)) 0 ;}
        }

        .loader1holder {
            padding-top: 59px;
            padding-bottom: 44px;
            padding-left: 176px;
        }
        .loader1holder2 {
            padding-top: 25px;
            padding-bottom: 0px;
        }
        
        .loaderholder {
            padding-left: 42px;
        }

        .profile {
            font-size: 14px;
        }

        /* HTML: <div class="loader"></div> */
        .loader1 {
        --d:28px;
        width: 3px;
        height: 3px;
        border-radius: 50%;
        color: #25b09b;
        box-shadow: 
            calc(1*var(--d))      calc(0*var(--d))     0 0,
            calc(0.707*var(--d))  calc(0.707*var(--d)) 0 1px,
            calc(0*var(--d))      calc(1*var(--d))     0 2px,
            calc(-0.707*var(--d)) calc(0.707*var(--d)) 0 3px,
            calc(-1*var(--d))     calc(0*var(--d))     0 4px,
            calc(-0.707*var(--d)) calc(-0.707*var(--d))0 5px,
            calc(0*var(--d))      calc(-1*var(--d))    0 6px;
        animation: l27 1s infinite steps(8);
        }
        @keyframes l27 {
        100% {transform: rotate(1turn)}
        }

        .card {
            box-shadow: 1px 1px 3px #e7e7e7c9 !important;
            border: .3px solid #f1f1f1;
        }
</style>
{{-- <section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Notifications</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
                </div>
        </div>
    </div>
</section> --}}

@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp

{{-- MODAL --}}

<div class="modal fade" id="assigningrecipient_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="assigningrecipient_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assigningrecipient_modalLabel">SETTINGS</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success btn-sm">Add Notifications</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3 d-flex">
                    <div class="col-md-4 bg-primary">
                        <h4><center>MESSAGES</center></h4>
                    </div>
                    <div class="col-md-4 bg-warning">
                        <h4><center>REQUESTS</center></h4>
                    </div>
                    <div class="col-md-4 bg-dark">
                        <h4><center>CONCERNS</center></h4>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

{{-- MODAL END --}}

<section class="content">
    <div class="row">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" id="settings_btn"><u>SETTINGS</u></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-4">
            <h1><center>RECIPIENTS</center></h1>

            <table width="100%" class="table table-sm table-bordered table-head-fixed" id="valid_employees_datatable" style="table-layout: fixed">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">&nbsp;&nbsp;No.</th>
                        <th class="text-center" width="85%">Employee</th>
                        <th class="text-center" width="10%"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-3 p-2">
            <div class="card mb-2" style="width: 18rem;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 bg-primary" style="height: 80px">
                            {{-- <img class="elevation-2" src="{{ asset('dist/img/download.png') }}" id="profilepic" style="width: 200px; height: 200px;" alt="Profile Picture"> --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img class="elevation-2" src="{{ asset('dist/img/download.png') }}" id="profilepic" style="width: 80px; height: 80px; border-radius: 50%; position: absolute; top: -30px; left: 10px" alt="Profile Picture" >
                        </div>
                        <div class="col-md-8 pt-2 pl-3">
                            <h5>Clyde Shabu</h5>
                            <small style="line-height: 2px;"><p>CK Pusher/User</p></small>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12 mt-3">
                            <table width="100%" style="table-layout: fixed">
                                <tr>
                                    <td class="text-center">
                                        <div style="display: grid">
                                            <a class="profile" href="#"><b>12</b></a>
                                            <a class="profile" href="#">Request</a>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: grid">
                                            <a class="profile" href="#"><b>8</b></a>
                                            <a class="profile" href="#">Concerns</a>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: grid">
                                            <a class="profile" href="#"><b>23</b></a>
                                            <a class="profile" href="#">Messages</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-success btn-sm">Add Notifications</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="width: 18rem;">
                <div class="card-header text-left pl-2 pb-2 pt-2">
                    <span style="font-weight: 500">User Status</span>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
        <div class="col-6 p-2 bg-secondary">
            <h3><center>MESSAGE</center></h3>
        </div>
        <div class="col-3 p-2 bg-dark">
            <h3><center>SCHEDULE</center></h3>
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

        // variable declaration
        var valid_employees = []

        // function call
        load_valid_employee()

        // select2 decalaration
      
        // modal close
      
        // functions

        function load_valid_employee() {
            $.ajax({
                type: "GET",
                url: "/hr/settings/notification/valid_employees",
                success: function (data) {
                    valid_employees = data
                    valid_employees_table()
                    
                }
            });
        }
      

        // select2 function call
      
        // Click Events
        $(document).on('click', '#settings_btn', function () {
            $('#assigningrecipient_modal').modal('show');
        })
      
        // Datatables or tables
        function valid_employees_table(){

            $('#valid_employees_datatable').DataTable({
                destroy: true,
                lengthChange: false,
                // scrollX: true,
                autoWidth: true,
                order: false,
                data: valid_employees,
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

                            var text = '<a href="javascript:void(0)" style="font-family: Calibri">'+rowData.full_name+'</a';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = `<a href="javascript:void(0)" data-toggle="tooltip" title="Remove"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>`;
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]
            })

        }
    })
</script>
@endsection


{{-- // variable declaration
      
// function call

// select2 decalaration

// modal close

// functions

// select2 function call

// Click Events

// Datatables or tables --}}

