@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">


<style>
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    td, th{
        padding: 1px !important;
    }
    .customtd{
        padding-top: 7px !important;
        padding-bottom: 7px !important;
    }
    .info-box{
        min-height: unset;
    }
            
    .select2-container .select2-selection--single {
        height: 40px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -1px;
    }
    .hidden {
        display: none;
    }
    .show {
        display: flex;
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
        content:"Processing...";
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
        .loaderholder {
            padding-left: 42px;
        }
        /* HTML: <div class="loader"></div> */
        /* .loader1 {
        width: 100px;
        height: 60px;
        display: flex;
        animation: l12-0 2s infinite linear;
        }
        .loader1::before,
        .loader1::after  {
        content:"";
        flex:4;
        background: 
            radial-gradient(at 50% 20%,#0000,#000a) bottom left/20px 20px repeat-x,
            linear-gradient(red 0 0) bottom/100% 20px no-repeat
            #ddd;
        -webkit-mask:
            repeating-linear-gradient(90deg,#000 0 4px,#0000 0 20px) 8px 0,
            radial-gradient(farthest-side,#000 90%,#0000) left bottom/20px 20px repeat-x;
        }
        .loader1::after {
        flex: 1;
        transform-origin: top;
        animation: l12-1 1s cubic-bezier(0,20,1,20) infinite;
        }
        @keyframes l12-0 { 
        0%,49.9% {transform: scaleX(1)}
        50%,100% {transform: scaleX(-1)}
        }
        @keyframes l12-1 { 
        100% {transform: rotate(-2deg)}
        } */

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
/* [class*=icheck-]>input:first-child+input[type=hidden]+label::before,
[class*=icheck-]>input:first-child+label::before {
    width: 18px !important;
    height: 18px !important;
} */
</style>
@php
    $countreleased = 0; // Default value if $payrollperiod is null or not an object

    if ($payrollperiod) {
        $countreleased = DB::table('hr_payrollv2history')->where('payrollid', $payrollperiod->id)->where('released', '1')->where('deleted', '0')->count();
    }
@endphp
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <!-- <h1>Payroll</h1> -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
                PAYROLL</h4>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Payroll</li>
            </ol>
            </div>
        </div>
        </div><!-- /.container-fluid -->
    </section>
    {{-- MODAL VIEW EMPLOYEE STANDARD DEDUCTION HISTORY DEDUCTION --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_releasedsetup">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;">
                {{-- <h4 class="modal-title">Release Payroll</h4> --}}
                <div class="col-md-5">

                    <div class="input-group date" id="departments" data-target-input="nearest">
                        <select class="form-control form-control-sm select2" id="select-department"></select>
                        <div class="input-group-append" data-target="#departments" data-toggle="datetimepicker">
                            <div class="input-group-text" style="background-color: #fff;"><a href="javascript:void(0)" id="select_departments"><i class="fas fa-users-cog text-primary"></i></a></div>
                        </div>
                    </div>
                    <div class="form-group clearfix mt-2 mb-0">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="excludeparttimer">
                            <label for="excludeparttimer">
                                Exclude Part Timer
                            </label>
                        </div>
                    </div>
                    {{-- <div class="row" style="user-select: none;">
                        <div class="col-md-12">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="excludeparttimer">
                                    <label for="excludeparttimer">
                                        Exclude Part Timer
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="form-group" style="padding: 0; margin-bottom: 0px!important; display: flex">
                        <select class="form-control form-control-sm select2" id="select-department"></select>
                        <a href="javascript:void(0)" id="select_departments"><i class="fas fa-users-cog"></i></a>
                    </div> --}}
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <select class="form-control form-control-sm select2" id="select-department"></select>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-12">
                <table width="100%" class="table table-sm" style="table-layout: fixed; font-size: 16px;" id="releasedsetup">
                    <thead>
                        <tr>
                            <th width="75%" style="padding-top: 10px!important; padding-bottom: 10px!important">EMPLOYEE</th>
                            <th width="20%" class="text-left align-middle">STATUS</th>
                            <th width="5%" class="text-center">
                                {{-- <div class="form-group form-check" style="margin: 0px!important; padding-top: 3px!important;">
                                    <input type="checkbox" class="form-check-input"id="checkallsubjs" style="width: 18px; height: 18px; padding: 0px;margin: 0px; position: relative;"/>
                                </div> --}}
                                <input type="checkbox" class="form-check-input"id="checkallemployee" style="width: 18px; height: 18px; padding: 0px;margin: 0px; position: absolute; top: 20px; right: 20px;"/>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-primary btn-sm" id="batchreleased"><i class="far fa-paper-plane"></i> Release Payslip</button>
                <button type="button" class="btn btn-primary btn-sm" id="batchgenerate"><i class="far fa-paper-plane"></i> Generate Worksheet</button>
                <button type="button" class="btn btn-danger btn-sm ml-auto" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    {{-- MODAL VIEW SPECIFIC EMPLOYEE OTHER DEDUCTION --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_void">
        <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="text-info" id="otherdeductionname" style="font-weight: bold"></span> Void Transaction</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" class="form-control" id="voidremarks" aria-describedby="voidremarks" placeholder="Enter Remarks">
                </div>
            </div>
            <div class="row mt-3 p-0">
                <div class="col-md-12 justify-content-between" style="display: flex;">
                    <button type="button" class="btn btn-primary btn-sm" id="btn_void" style=""><i class="fas fa-plus"></i> Void</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

    {{-- MODAL VIEW SPECIFIC EMPLOYEE OTHER DEDUCTION --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_editpayslip">
        <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="text-info" id="otherdeductionname" style="font-weight: bold"></span> Edit Payslip</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" class="form-control" id="edipayslipremarks" aria-describedby="edipayslipremarks" placeholder="Purpose">
                </div>
            </div>
            <div class="row mt-3 p-0">
                <div class="col-md-12 justify-content-between" style="display: flex;">
                    <button type="button" class="btn btn-primary btn-sm" id="btn_edit" style="">Edit Payslip</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

    <div class="info-box shadow payrollperiod" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="info-box-content">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-12">
                    <div class="form-group" style="margin-bottom: 5px;">
                        <label>Select Salary Type</label>
                        <select class="form-control form-control-sm select2" id="select-salarytype"></select>
                        <span class="invalid-feedback" role="alert">
                        <strong>Salary Type is required</strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Released Dates</label>
                        <select class="form-control form-control-sm select2" id="select-releaseddates"></select>
                        <span class="invalid-feedback" role="alert">
                        </span>
                    </div>
                    <button type="button" class="btn btn-default" id="btn-releaseprocess" hidden>
                        Release Payroll
                    </button>   
                    {{-- <button type="button" class="btn btn-default" id="btn-generateprocess" hidden>
                        Generate Payroll
                    </button>              --}}
                </div>
                <div class="col-md-7">
                    <div id="ifcountzero" hidden>
                            <label>Payroll Period</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right input-payrolldates" id="reservationnew" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-success btn-payroll-dates-submit" id="btn-payroll-dates-submit" data-action="new">
                                        <i class="fa fa-share"></i> Activate period
                                    </button>
                                </div>
                            </div>
                            
                        </div>

                        <div id="ifpayrollv2history" hidden>
                        <label>Payroll Period</label>
                        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                        <small><a style="cursor: pointer;" href="#" id="a-close-payroll-period"><u>Close this Payroll Period</u></a></small>
                        @endif
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            @if ($countreleased > 0)
                                <input type="text" class="form-control float-right input-payrolldates" id="reservation" disabled readonly data-id="">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-warning btn-payroll-dates-submit" id="btn-payroll-dates-submit" data-action="update" disabled>
                                        <i class="fa fa-share"></i> Update period
                                    </button>
                                </div>
                            @else
                                <input type="text" class="form-control float-right input-payrolldates" id="reservation" readonly data-id="">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-warning btn-payroll-dates-submit" id="btn-payroll-dates-submit" data-action="update">
                                        <i class="fa fa-share"></i> Update period
                                    </button>
                                </div>
                            @endif
                            
                        </div>

                        <label><span class="text-bold" style="font-size: 13px;">Note: When a payslip is released, you can no longer update the payroll period.</span></label>
                      
                    </div>
                    @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                        <div class="row mt-2 forpayrollemployee" hidden>
                            <div class="col-md-8">                    
                                {{-- <select class="form-control select2" id="select-employee">
                                    <option value="0">Select employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{$employee->id}}" salary="{{$employee->salary}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                                    @endforeach
                                </select> --}}
                                <select class="form-control form-control-sm select2" id="select-employee"></select>
                            </div>
                            <div class="col-md-4 align-self-end text-right">
                                {{-- @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0) --}}
                                    {{-- <h5><span id="numofreleased">{{DB::table('hr_payrollv2history')->where('payrollid',DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id)->where('deleted','0')->where('released','1')->count()}}</span>/{{count($employees)}} Released</h5> --}}
                                    <h5><span id="numofreleased"></span> / <span id="countemployee"></span> Released</h5>
                                {{-- @endif --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 collegeloadtable" hidden>
                    
                            </div>
                        </div>    
                    @endif
                    <div class="row forpayrollemployeelist mt-2" hidden>
                        <div class="col-md-8">                    
                            {{-- <select class="form-control select2" id="select-employee">
                                <option value="0">Select employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}" salary="{{$employee->salary}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                                @endforeach
                            </select> --}}
                            <select class="form-control form-control-sm select2" id="select-employee-released"></select>
                        </div>
                        <div class="col-md-4 align-self-end text-right">
                            {{-- @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0) --}}
                                {{-- <h5><span id="numofreleased">{{DB::table('hr_payrollv2history')->where('payrollid',DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id)->where('deleted','0')->where('released','1')->count()}}</span>/{{count($employees)}} Released</h5> --}}
                                <h5><span id="numofreleasedemployees"></span> / <span id="countemployee"></span> Released</h5>
                            {{-- @endif --}}
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 collegeloadtable" hidden>
                
                        </div>
                    </div>    
                </div>
                <div class="col-md-2 text-right">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-default" id="btn-print-summary" hidden><i class="fa fa-file-pdf"></i> Export Summary</button><br>
                </div>
                
            </div>
            <div id="containerforpayrollperiod">
            </div>
            <div class="row forpayrollperiod">
                <div class="col-md-6">
                    @php
                        $ifcountzero = DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->get();

                        foreach ($ifcountzero as $ifcountz) {
                            $ifcountz->datevalue = date('m/d/Y', strtotime($ifcountz->datefrom)).' - '.date('m/d/Y', strtotime($ifcountz->dateto));
                        }
                        $ifpayrollv2history = DB::table('hr_payrollv2history')->where('deleted','0')->get();

                        $firstPayroll = DB::table('hr_payrollv2')
                            ->select('id')
                            ->where('deleted', '0')
                            ->where('status', '1')
                            ->first();

                        if ($firstPayroll) {
                            $ifrelease = DB::table('hr_payrollv2history')
                                ->where('payrollid', $firstPayroll->id)
                                ->where('deleted', '0')
                                ->where('released', '1')
                                ->get();
                        } else {
                            $ifrelease = [];
                        }
                    @endphp

                    <div id="ifcountzero" hidden>
                        <label>Payroll Period</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right input-payrolldates" id="reservationnew" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-success btn-payroll-dates-submit" id="btn-payroll-dates-submit" data-action="new">
                                    <i class="fa fa-share"></i> Activate period
                                </button>
                            </div>
                        </div>
                        
                    </div>

                    <div id="ifpayrollv2history" hidden>
                        <label>Payroll Period</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right input-payrolldates" id="reservation" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-warning btn-payroll-dates-submit" id="btn-payroll-dates-submit" data-action="update">
                                    <i class="fa fa-share"></i> Update period
                                </button>
                            </div>
                        </div>

                        <small><em class="text-bold">Note: When a payslip is released, you can no longer update the payroll period.</em></small>
                        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                        <small><a style="cursor: pointer;" href="#" id="a-close-payroll-period"><u>Close this Payroll Period</u></a></small>
                        @endif
                    </div>

                    
                     {{--  <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                       @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0)
                            <input type="text" class="form-control float-right input-payrolldates" id="reservation" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-success" id="btn-payroll-dates-submit" data-action="new">
                                    <i class="fa fa-share"></i> Activate period
                                </button>
                            </div>
                        @else
                            @if(DB::table('hr_payrollv2history')->where('payrollid', DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id)->where('deleted','0')->count() == 0)
                                <input type="text" class="form-control float-right input-payrolldates" id="reservation" readonly value="{{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->datefrom))}} - {{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->dateto))}}" data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-warning" id="btn-payroll-dates-submit" data-action="update">
                                        <i class="fa fa-share"></i> Update period
                                    </button>
                                </div>
                            @else
                                <input type="text" class="form-control float-right input-payrolldates" readonly  data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}" value="{{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->datefrom))}} - {{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->dateto))}}">
                            
                            @endif
                        @endif

                        <small><em class="text-bold">Note: When a payslip is released, you can no longer update the payroll period.</em></small>
                        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                        <small><a style="cursor: pointer;" href="#" id="a-close-payroll-period"><u>Close this Payroll Period</u></a></small>
                        @endif
                    </div>  --}}
                </div>
                {{-- <div class="col-md-6 text-right">
                    @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-default" id="btn-print-summary"><i class="fa fa-file-pdf"></i> Export Summary</button>
                    @endif
                </div> --}}
            </div>
            {{-- </div>
            <div class="card-body p-1"> --}}
            
        </div>
    </div>
    <div class="card card-success" style="border: none;" hidden>
        <div class="card-header">
            <h3 class="card-title">List of Employees</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
            </div>
            </div>
        <div class="card-body">
            @if(count($employees) == 0)

            @else
            
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="2%" class="p-1">&nbsp;&nbsp;No.</th>
                                    <th width="78%" class="p-1">Employee</th>
                                    <th class="text-center p-1" width="20%"></th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div id="div-container-salaryinfo"></div>
    {{-- <div class="card card-success" style="border: none;"@if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0) hidden @endif>
        <div class="card-header p-1 pl-2">
        <h3 class="card-title p-0"><input type="text" class="form-control m-0" id="input-filter-employee" placeholder="Search Employee"/></h3>

        <div class="card-tools p-2">
            <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
        </div>
        <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="max-height:500px; overflow:scroll;">
            @if(count($employees) == 0)
            @else
            <div class="row">
                @foreach ($employees as $employee)
                <div class="col-md-12 mb-2 div-each-employee" data-empid="{{$employee->id}}" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->designation}}<" style="border-radius: 5px; border: 1px solid green; cursor: pointer">
                    <label class="m-0">{{strtoupper($employee->lastname)}}</label>, {{ucwords(strtolower($employee->firstname))}}
                    <p class="text-bold text-muted p-0 m-0"><sup>{{$employee->designation}}</sup></p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <!-- /.card-body -->
    </div> --}}
    {{-- <div class="col-md-7" @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0) hidden @endif>
        <div class="card h-100" style="border: none;">
            <div class="card-header">
                <small>Note: Please select an employee by clicking their name cards.</small>
                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                </button>
                </div>
            </div>
            <div class="card-body p-2" id="div-container-salaryinfo"  style="height:570px; overflow: scroll;">
                
            </div>
            <div class="card-footer p-1 pr-3" id="card-footer-computation">                  
            <div class="row">
                <div class="col-md-8">
                    <h4>&nbsp;</h4>
                    <button type="button" class="btn btn-sm btn-primary btn-compute" data-id="0" hidden>Compute</button>
                    <button type="button" class="btn btn-sm btn-primary btn-compute" data-id="1" id="btn-save-computation">Save Computation</button>
                    <button type="button" class="btn btn-sm btn-warning" id="btn-release-payslip">Release Pay Slip</button>
                    <button type="button" class="btn btn-sm btn-info" data-id="1" id="btn-printslip">Print Payslip</button>
                </div>   
                <div class="col-md-4 text-right">
                    <h4><span id="netsalary"></span><span id="newsalary"></span></h4>
                    <h6>Net Salary</h6>
                </div>                     
            </div>
            </div>
        </div>
    </div> --}}
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- SweetAlert2 -->
  <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <!-- ChartJS -->
  <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
  <!-- DataTables -->
  <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js')}}"></script>
  <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
  <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <!-- date-range-picker -->
  <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script>
    var wifcountzero = @json($ifcountzero);
    var wifpayrollv2history = @json($ifpayrollv2history);
    var wifrelease = @json($ifrelease);
    var syid = @json($sy).id;

    const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
  $(document).ready(function(){
    var employeeallids = [];
    var employeeallidsperdep = [];
    var salaribasistype = [];
    var allemployeesdata = [];
    var listofemployees = [];
    // allemployees()
    salarybasistype()
    allemployeeslist()
    //modal Close
    $('#modal_releasedsetup').on('hidden.bs.modal', function (e) {
        employeeallids = [];
        $('#checkallemployee').prop('checked', false)
        allemployees()
    });
    
    $(document).on('change', '#select-salarytype', function(){
        $('.forpayrollemployeelist').prop('hidden', true);
        countreleaseemployee()
        var salid = $(this).val();
        loadreleasedpayslip(salid)
        $('#ifcountzero').prop('hidden', true)
        $('#ifpayrollv2history').prop('hidden', true)
        $('#btn-print-summary').prop('hidden', true)
        $('#btn-print-summary').prop('hidden', true)
        $('.forpayrollemployee').prop('hidden', true);
        // if (salid !== null && salid !== '') {
        //     $('.forpayrollperiod').prop('hidden', false);
        //     $('.forpayrollemployee').prop('hidden', false);

        // } else {
        //     $('.forpayrollperiod').prop('hidden', true);
        //     $('.forpayrollemployee').prop('hidden', true);
        // }
        // Check if salid is empty
        if (salid === null || salid === '') {
            // If salid is empty, hide the button and return
            $('#btn-releaseprocess').attr('hidden', true);
            $('#btn-generateprocess').attr('hidden', true);
            return;
        }
        $('#btn-releaseprocess').attr('hidden', false);
        $('#btn-generateprocess').attr('hidden', false);
        var withdata = wifcountzero.filter(x=>x.salarytypeid == salid)
        

        if (withdata.length == 0) {
            $('#div-container-salaryinfo').empty()
            $('#ifcountzero').prop('hidden', false)
        } else {
            
            $('#div-container-salaryinfo').empty()
            $('#ifpayrollv2history').prop('hidden', false)
            $('#ifpayrollv2history').prop('hidden', false)
            $('#btn-print-summary').prop('hidden', false)
            $('.forpayrollemployee').prop('hidden', false);

            $('.input-payrolldates').attr('data-id', withdata[0].id);
            $('#reservation').val(withdata[0].datevalue)
            allemployees()
        

            // employeespertype = allemployeesdata.filter(x=>x.salarybasistype == withdata[0].salarytypeid)
            // var countrelease = wifrelease.filter(x=>x.payrollid == employeespertype.id)
            // $('#countemployee').text(employeespertype.length)
            // load_employees(employeespertype)
        }

    });

    $('#modal_void').on('hide.bs.modal', function (e) {
        $('#voidremarks').val('');
    })

    $(document).on('click', '#btn_void', function(){
        var valid_data = true;
        var voidremarks = $('#voidremarks').val();
        var payrollid = $('#reservation').attr('data-id');
        var employeeid = $('#select-employee').val();
        
        if (voidremarks == null || voidremarks == '') {
            valid_data = false;
            toastr.warning('Please enter your remarks.','Remarks')

        } 

        if (valid_data) {
            Swal.fire({
            title: 'Are you sure?',
            html: "Once Void, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Void Payroll?',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/hr/payrollv3/voidpayslip",
                        data: {
                            voidremarks : voidremarks,
                            payrollid : payrollid,
                            employeeid : employeeid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                $('#modal_void').modal('hide');
                                $('#btn-refresh').click()
                            }else{
                                toastr.error('Something went wrong!','Payroll')
                            }
                        }
                    });
                }
            })
        }
        
    })

    $(document).on('click', '#btn_edit', function(){
        var valid_data = true;
        var editremarks = $('#edipayslipremarks').val();
        var releaseddates = $('#select-employee-released').val()

        if (releaseddates == null || releaseddates == '') {
            var employeeid = $('#select-employee').val();
            var payrollid = $('#reservation').attr('data-id');
        } else {
            var payrollid = $('#select-releaseddates').val();
            var employeeid = $('#select-employee-released').val();
        }
        
        if (voidremarks == null || voidremarks == '') {
            valid_data = false;
            toastr.warning('Please enter your remarks.','Remarks')

        } 

        if (valid_data) {
            Swal.fire({
            title: 'Are you sure?',
            html: "Once Edit, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Edit Payroll?',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/hr/payrollv3/editpayslip",
                        data: {
                            editremarks : editremarks,
                            payrollid : payrollid,
                            employeeid : employeeid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                $('#modal_editpayslip').modal('hide');
                                $('#btn-refresh').click()
                            }else{
                                toastr.error('Something went wrong!','Payroll')
                            }
                        }
                    });
                }
            })
        }
        
    })

    $(document).on('change', '#select-releaseddates', function(){
        $('#div-container-salaryinfo').empty();
        if ($('#select-employee').val() && $('#select-employee').val().length > 0) {
                // Clear or empty Select2 selections
                $("#select-employee").val([]).trigger("change");
            }
        var releasepdateid = $(this).val()
        if (releasepdateid == null || releasepdateid == 0) {
            $('.forpayrollemployee').removeClass('show');
            $('.forpayrollemployee').removeClass('hidden');
        } else {
            $('.forpayrollemployee').addClass('hidden');
            $('.forpayrollemployeelist').prop('hidden',false);
            $('.forpayrollemployeelist').removeClass('hidden');

        }
        loadreleasedemployees(releasepdateid)
    })

    
    $(document).on('change', '#select-department', function(){
        var valid_data = true;
        employeeallids = [];
        employeeallidsperdep = [];
        $('#checkallemployee').prop('checked', false)
        var payrollid = $('.input-payrolldates').attr('data-id');
        var salid = $('#select-salarytype').val();
        var deptid = $('#select-department').val()
        var setup = 'releasedsetup'
        
        if (deptid == '' || deptid == null) {
            employeeallids = [];
            valid_data = false
        }
        if (valid_data) {
            $.ajax({
                type: "GET",
                url: "/hr/payrollv3/allemployees",
                data: {
                    payrollid : payrollid,
                    setup : setup,
                    salid : salid,
                    deptid : deptid
                },
                success: function (data) {
                    var empdata = data
                    var employeeids = empdata.map(employee => employee.id);
                    employeeallids.push(...employeeids);
                    employeeallidsperdep.push(...employeeids);
                    console.log('select department');
                    console.log(employeeallids);
                    console.log('select department');
                    table_releasedsetup(empdata)
                }
            });
        }
        
    })
    // Event listener for select2:unselect
    $('#select-department').on('select2:unselect', function (e) {
        console.log('Select2 dropdown cleared');
        employeeallids = [];
        allemployees()
    });
    $('#select-releaseddates').on('select2:unselect', function (e) {
        $('#div-container-salaryinfo').empty();
        $('.forpayrollemployee').removeClass('hidden');
        $('.forpayrollemployee').addClass('show');

        $('.forpayrollemployeelist').addClass('hidden');

    });
    // check all subjects in Subject loads Modal
    
    $(document).on('change', '#checkallemployee',  function(){
            employeeallids = [];

            if ($(this).is(':checked') && $('#select-department').val() == '') {
                console.log($('#select-department').val() + 'dadadadadad');
                
                $('.employeecheck').prop('checked', true);


                if ($('#excludeparttimer').is(':checked')) {
                    // var configuredemployee = allemployeesdata.filter(x=>x.configured == 1 && x.parttimer != 1)
                    var configuredemployee = allemployeesdata.filter(x=>x.configured == 1  || x.released == 1 && x.parttimer != 1)

                } else {
                    var configuredemployee = allemployeesdata.filter(x=>x.configured == 1 || x.released == 1)
                }

                console.log(configuredemployee);
                var employeeids = configuredemployee.map(employee => employee.id);
				employeeallids.push(...employeeids);

                console.log('-----------------------------------');
                console.log(employeeallids);
                console.log('------------------------------------');

            } else if($(this).is(':checked') && $('#select-department').val() != ''){
                employeeallids = employeeallidsperdep
                console.log(employeeallids);
            } else {
                employeeallids = [];
                $('.employeecheck').prop('checked', false);
            }

        // Update "Check All" checkbox when individual subject checkboxes are clicked
        // $(document).on('change', '.employeecheck', function() {
        //     console.log('ssssssssssssssss');
        //     if (!$(this).is(':checked')) {
        //         let valueToRemove = parseInt($(this).attr('empid'));
        //         console.log(valueToRemove);
        //         let indexToRemove = employeeallids.indexOf(valueToRemove);

        //         if (indexToRemove !== -1) {
        //             // Remove the element at the specified index
        //             employeeallids.splice(indexToRemove, 1);

        //             // Now, '77' is removed from the array
        //             console.log("Removed:", valueToRemove);
        //             console.log("Updated Array:", employeeallids);
        //         } else {
        //             console.log("Value not found in the array.");
        //         }
        //     }
        //     var allSubjectsChecked = $('.employeecheck:checked').length === $('.employeecheck').length;
        //     $('#checkallemployee').prop('checked', allSubjectsChecked);
        //     console.log(employeeallids);
        // });
            $(document).on('change', '.employeecheck', function() {
                if (!$(this).is(':checked')) {
                    let valueToRemove = parseInt($(this).attr('empid'));
                    console.log(valueToRemove);
                    let indexToRemove = employeeallids.indexOf(valueToRemove);

                    if (indexToRemove !== -1) {
                        // Remove the element at the specified index
                        employeeallids.splice(indexToRemove, 1);

                        // Now, '77' is removed from the array
                        console.log("Removed:", valueToRemove);
                        console.log("Updated Array:", employeeallids);
                    } else {
                        console.log("Value not found in the array.");
                    }
                } 
                // else {
                //     var empid = $(this).closest('td').find('.employeecheck').attr('empid');

                //     employeeallids.push(empid);



                // }
                var allSubjectsChecked = $('.employeecheck:checked').length === $('.employeecheck').length;
                $('#checkallemployee').prop('checked', allSubjectsChecked);
                console.log(employeeallids);

            });

    });

    $(document).on('change', '.employeecheck', function() {
        if (!$(this).is(':checked')) {
            let valueToRemove = parseInt($(this).attr('empid'));
            console.log(valueToRemove);
            let indexToRemove = employeeallids.indexOf(valueToRemove);

            if (indexToRemove !== -1) {
                // Remove the element at the specified index
                employeeallids.splice(indexToRemove, 1);

                // Now, '77' is removed from the array
                console.log("Removed:", valueToRemove);
                console.log("Updated Array:", employeeallids);
            } else {
                console.log("Value not found in the array.");
            }
        } else {
            var empid = $(this).closest('td').find('.employeecheck').attr('empid');

            employeeallids.push(empid);



        }
        var allSubjectsChecked = $('.employeecheck:checked').length === $('.employeecheck').length;
        $('#checkallemployee').prop('checked', allSubjectsChecked);
        console.log(employeeallids);

    });

   
    

    // console.log(checkboxStates);
    
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
    });

    // batch released payslip
    $(document).on('click', '#batchreleased', function () {
        console.log('Selected Employee Ids:', employeeallids);

        // return false;
        // $('.employeecheck:checked').each(function () {
        //     var empid = $(this).attr('empid');
        //     if (empid) {
        //         checkedEmpIds.push(empid);
        //     }
        // });

        
        if (employeeallids.length > 0) {

            // The rest of your code (Swal.fire and Ajax call) goes here
            Swal.fire({
                text: 'Are you sure you want to release Payslip?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Released'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/hr/payrollv3/batchreleaseemployeepayroll",
                        data: {
                            checkedEmpIds: employeeallids
                        },
                        success: function (data) {
                            if(data[0].status == 0){
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }else{
                                $('#modal_releasedsetup').modal('hide')
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                
                            }
                        }
                    });
                }
            });
        } else {
            Toast.fire({
                type: 'warning',
                title: 'No Employee Selected'
            });
        }
    });

    $(document).on('click', '#batchgenerate', function () {

        
        var checkedEmpIds = [];
        var payrollid = $('.input-payrolldates').attr('data-id');

        $('.employeecheck:checked').each(function () {
            var empid = $(this).attr('empid');
            if (empid) {
                checkedEmpIds.push(empid);
            }
        });

        console.log('employeeallids'); 
        console.log(employeeallids); 
        console.log('employeeallids'); 

        if (checkedEmpIds.length > 0) {

            Swal.fire({
                text: 'Are you sure you want to Generate Payslip?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Generate'
            }).then((result) => {
                if (result.value) {
                    window.open('/hr/payrollv3/batchgenerateemployeepayroll?checkedEmpIds='+employeeallids+'&payrollid='+payrollid,'_blank')
                    // $.ajax({
                    //     type: "GET",
                    //     url: "/hr/payrollv3/batchgenerateemployeepayroll",
                    //     data: {
                    //         checkedEmpIds: checkedEmpIds,
                    //         payrollid : payrollid
                    //     },
                    //     success: function (data) {
                          
                    //     }
                    // });
                }
            });
        } else {
            Toast.fire({
                type: 'warning',
                title: 'No Employee Selected'
            });
        }
    });


    // Gian Additional
    

    $(document).on('click', '#btn-releaseprocess', function(){
        select_departments();
        allemployees()

        // var employeeids = employeespertype.map(employee => employee.id);
        // employeeallids.push(...employeeids);
        $('#modal_releasedsetup').modal('show')
    })


    $(document).on('click', '#excludeparttimer', function(){
        if ($(this).is(':checked')) {
            $('#checkallemployee').prop('checked', false)
            var parttimer = employeespertype.filter(x=>x.parttimer != 1)
            var allemployeesdata = parttimer;
        } else {
            var parttimer = employeespertype
            $('#checkallemployee').prop('checked', false)
        }
        table_releasedsetup(parttimer)
    })

    function table_releasedsetup(employeespertype){

        var checkboxStates = [];
        var table = $('#releasedsetup').DataTable({
            destroy: true,
            lengthChange: true,
            scrollX: false,
            autoWidth: false,
            searching: true,
            order: true,
            data: employeespertype,
            columns : [
                {"data" : 'text'},
                {"data" : null},
                {"data" : null}
            ], 
            columnDefs: [
                {
                'targets': 0,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    var text = '<span style="padding-top: 10px;text-transform: uppercase;">'+rowData.text+'</span>';
                    $(td)[0].innerHTML =  text
                    $(td).addClass('align-middle')
                    $(td).addClass('customtd')
                    $(td).addClass('text-left')
                }
                },
                {
                'targets': 1,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    if (rowData.released == 1) {
                        var text = '<span class="badge badge-success">released</span>';
                    } else if(rowData.configured == 1) {
                        var text = '<span class="badge badge-info">configured</span>';
                    } else {
                        var text = '<span class="badge badge-warning">Not Saved Computation</span>';
                    }
                    $(td)[0].innerHTML =  text
                    $(td).addClass('align-middle')
                    // $(td).addClass('text-center')
                    }
                },
                {
                'targets': 2,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    if (rowData.released == 1) {
                        // var text = '<i class="fas fa-check text-success"></i>';
                        var text = '<input type="checkbox" empid="'+rowData.id+'" class="employeecheck" style="width: 18px; height: 18px; margin-top: 5px;">';

                    } else if(rowData.configured == 1) {
                        var text = '<input type="checkbox" empid="'+rowData.id+'" class="employeecheck" style="width: 18px; height: 18px; margin-top: 5px;">';
                    } else {
                        var text = '<span><i class="fas fa-minus text-danger"></i></span>';
                    }
                    $(td)[0].innerHTML =  text
                    $(td).addClass('align-middle')
                    $(td).addClass('text-center')
                    }
                }
            ],
            "drawCallback": function(settings) {
                // Reapply checkbox states when DataTable page changes
                $('.employeecheck').each(function(i) {
                    $(this).prop('checked', checkboxStates[i]);
                });
            }
        })

        

        $(document).on('change', '#checkallemployee', function() {
            var isChecked = $(this).is(':checked');
            $('.employeecheck').prop('checked', isChecked);
            checkboxStates = Array($('.employeecheck').length).fill(isChecked);
        });
        table.draw();

        // var label_text = $($('#releasedsetup_wrapper')[0].children[0])[0].children[0]
        // $(label_text)[0].innerHTML = `<div class="row" style="user-select: none;">
        //                                 <div class="col-md-12">
        //                                     <div class="form-group clearfix">
        //                                         <div class="icheck-primary d-inline">
        //                                             <input type="checkbox" id="excludeparttimer">
        //                                             <label for="excludeparttimer">
        //                                                 Exclude Part Timer
        //                                             </label>
        //                                         </div>
        //                                     </div>
        //                                 </div>
        //                               </div>`

    }
    // console.log(checkboxStates);
    function loadreleasedpayslip(salid){
        $.ajax({
            type: "GET",
            url: "/hr/payrollv3/allreleasedpayrolldates",
            data: {
                salid : salid
            },
            success: function (data) {
                $('#select-releaseddates').empty()
                $('#select-releaseddates').append('<option value="">Select Payroll Date</option>')
                $('#select-releaseddates').select2({
                    data: data,
                    allowClear : true,
                    placeholder: 'Select Payroll Date'
                });
            }
        });
    }

    // Load Select 2 salary type (Ex. Monthly, Daily, Weekly)
    function load_salarytype(){
        console.log('load salaribasistype');
        console.log(salaribasistype);
        $('#select-salarytype').empty()
        $('#select-salarytype').append('<option value="">Select Salary Type</option>')
        $('#select-salarytype').select2({
            data: salaribasistype,
            allowClear : true,
            placeholder: 'Select Salary Type'
        });
        // Select the option with text "Monthly"
        var desiredText = 'Monthly';
        var salid = salaribasistype[0].id;
        loadreleasedpayslip(salid)
        for (var i = 0; i < salaribasistype.length; i++) {
            if (salaribasistype[i].text === desiredText) {
                $('#select-salarytype').val(salaribasistype[i].id).trigger('change');
            
                
                break; // Stop iterating once the desired option is found
            }
        }
    }

    function salarybasistype(){

        $.ajax({
            type: "GET",
            url: "/hr/payrollv3/salarybasistype",
            success: function (data) {
                console.log('DATA',data);
                
                salaribasistype = data
                load_salarytype()
            }
        });
    }

    // Load Select2 Employees
    function load_employees(employeespertype) {
        $('#select-employee').empty();
        $('#select-employee').append('<option value="">Select Employee</option>');

        // Iterate through the employeespertype and add the badge and text
        employeespertype.forEach(function(employee) {
            var text = employee.text;
            
            if (employee.released === 1) {
                // Add a green badge aligned to the right
                text += ' <p class="badge badge-dark" style="background-color: green!important;">&nbsp;&nbsp;&nbsp;&nbsp;( Released )</p>';
            }
            
            $('#select-employee').append('<option value="' + employee.id + '"><span style="background-color: green!important;">' + text + '</span></option>');
        });

        $('#select-employee').select2({
            allowClear: true,
            placeholder: 'Select Employee',
            escapeMarkup: function (markup) {
                return markup; // Allows HTML tags in the option text
            }
        });
    }   

    function loadreleasedemployees(releasepdateid){
        $.ajax({
            type: "GET",
            url: "/hr/payrollv3/allreleasedemployees",
            data: {
                releasepdateid : releasepdateid
            },
            success: function (data) {
                var numofreleased = data.length;
                $('#numofreleasedemployees').text(numofreleased)
                console.log(numofreleased);
                $('#select-employee-released').empty()
                $('#select-employee-released').append('<option value="">Select Employee</option>')
                $('#select-employee-released').select2({
                    data: data,
                    allowClear : true,
                    placeholder: 'Select Employee'
                });
                
            }
        });
    }

    function allemployees(){

        var payrollid = $('.input-payrolldates').attr('data-id');
        var salid = $('#select-salarytype').val();
        $.ajax({
            type: "GET",
            url: "/hr/payrollv3/allemployees",
            data: {
                payrollid : payrollid
            },
            success: function (data) {
                allemployeesdata = data
                var withdata = wifcountzero.filter(x=>x.salarytypeid == salid)
                employeespertype = allemployeesdata.filter(x=>x.salarybasistype == withdata[0].salarytypeid)
                var countrelease = wifrelease.filter(x=>x.payrollid == employeespertype.id)

                $('#countemployee').text(employeespertype.length)
                load_employees(employeespertype)
                table_releasedsetup(employeespertype)
                load_employeestable()
            }
        });
    }

    function countreleaseemployee(){
        var salid = $('#select-salarytype').val();

        $.ajax({
            type: "GET",
            url: "/hr/payrollv3/countemployeerelease",
            data: {
                salid : salid
            },
            success: function (data) {
                $('#numofreleased').text(data);
            }
        });
    }


    function select_departments() {
        var salid = $('#select-salarytype').val();
      
        var setup = 'releasedsetup';
        $.ajax({
        type: "GET",
        url: "/payrollclerk/employees/profile/select_departments",
        data: {
            salid : salid,
            setup : setup
        },
            success: function (data) {
                $('#select-department').empty();
                $('#select-department').append('<option value="">Select Department</option>');

                // Loop through departmentsload_employeestable()
                data.forEach(function (department) {
                    // Append a new option with a badge
                    var option = $('<option value="' + department.id + '">' + department.text +
                        ' <span class="badge badge-secondary"">(' + department.employee_count + ')</span>' +
                        '</option>');

                    $('#select-department').append(option);
                });

                // Initialize Select2
                $('#select-department').select2({
                    allowClear: true,
                    placeholder: {
                        id: '',
                        text: 'Select Department',
                        template: function (data) {
                            return '<span style="font-size: 9px; font-weight: normal;">' + data.text + '</span>';
                        }
                    }
                });
            }
        });
    }

    function allemployeeslist(){

        $.ajax({
            type: "GET",
            url: "/hr/payrollv3/listofemployees",
            success: function (data) {
                listofemployees = data

                load_employeestable()
            }
        });
    }

    function load_employeestable(){
        var salid = $('#select-salarytype').val();

        var listofemps = listofemployees.filter(x=>x.salarybasistype == salid);

        $('#employee_datatables').DataTable({
            destroy: true,
            lengthChange: true,
            scrollX: false,
            autoWidth: true,
            order: false,
            data: listofemps,
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
                        var index = row + 1; 
                        var text = '<span>&nbsp;&nbsp;' + index + '</span>';
                        $(td)[0].innerHTML = text;
                        $(td).addClass('align-middle text-left p-1');
                    }
                },
                {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.text+'</a>';
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle  text-left p-1')
                    }
                },
                {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var text = '<a class="mb-0" style="text-transform: uppercase;"></a>';
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle  text-left p-1')
                    }
                }
            ]
        })
    }
})
  </script>







  <script>
    
    $(function () {
        $('#example2').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });

    $(document).ready(function(){
        var activesem = [];
        var clsubjects = [];
        load_allpayrollreleased_select2()
        // getactivesem()
            // $('.select2').select2({
            // theme: 'bootstrap4'
            // })

        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() >0)
            $('#a-close-payroll-period').on('click', function(){
                var salid = $('#select-salarytype').val();
                // var payrollid = '{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}';
                var payrollid = wifcountzero.filter(x=>x.salarytypeid == salid && x.status == 1)[0].id;
                // console.log(payrollid);
                // return false;
                Swal.fire({
                title: 'Are you sure?',
                html: "Once closed, you won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Close Payroll Period',
                cancelButtonText: 'Cancel',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        payrollid
                        $.ajax({
                            url: '/hr/payrollv3/payrolldates',
                            type: 'get',
                            data: {
                                action: 'closepayroll',
                                payrollid   :   payrollid
                            },
                            success: function(data){
                                if(data == 1)
                                {
                                    window.location.reload();
                                }else{
                                    toastr.error('Something went wrong!','Payroll')
                                }
                            }
                        })
                    }
                })
            })
        @endif

        $('#card-footer-computation').hide()
        @if(DB::table('hr_payrollv2')->where('status','1')->where('deleted','0')->count() == 0)
            toastr.warning('Please select payroll period!','Payroll')
        @else

        @endif
        $('#reservationnew').daterangepicker({
            
            locale: {
            format: 'M/DD/YYYY'
            }
        })
        $('#reservation').daterangepicker({
            
            locale: {
            format: 'M/DD/YYYY'
            }
        })
        
        // $("#input-filter-employee").on("keyup", function() {
        //     var input = $(this).val().toUpperCase();
        //     var visibleCards = 0;
        //     var hiddenCards = 0;

        //     $(".container").append($("<div class='card-group card-group-filter'></div>"));


        //     $(".div-each-employee").each(function() {
        //         if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

        //         $(".card-group.card-group-filter:first-of-type").append($(this));
        //         $(this).hide();
        //         hiddenCards++;

        //         } else {

        //         $(".card-group.card-group-filter:last-of-type").prepend($(this));
        //         $(this).show();
        //         visibleCards++;

        //         if (((visibleCards % 4) == 0)) {
        //             $(".container").append($("<div class='card-group card-group-filter'></div>"));
        //         }
        //         }
        //     });

        // });
      



        $('.btn-payroll-dates-submit').on('click', function(){
            var dataaction = $(this).attr('data-action')
            var salid = $('#select-salarytype').val();
            
            if (dataaction == 'new') {
                var dates = $('#reservationnew').val()
            } else {
                var dates = $('#reservation').val()
            }
            // Swal.fire({
            //     title: 'Processing data...',
            //     onBeforeOpen: () => {
            //         Swal.showLoading()
            //     },
            //     allowOutsideClick: false
            // })
            Swal.fire({
                title: 'Please wait...',
                html: `
                    <div class="row" style="justify-content: center !important; display: grid !important;">
                        <div class="loader1holder">
                            <div class="loader1"></div>
                        </div>
                        <div class="loaderholder">
                            <div class="loader"></div>
                        </div>
                        
                        <div class="note text-danger"><small><strong>Note!!! do not refresh while the process is ongoing...</strong></small></div>
                    </div>`,
                onBeforeOpen: () => {
                    // You can perform additional actions before the modal is opened
                },
                onAfterClose: () => {
                    // You can perform additional actions after the modal is closed
                },
                allowOutsideClick: false,
                showConfirmButton: false,
            });
        

            $.ajax({
                url: '/hr/payrollv3/payrolldates',
                type: 'get',
                data: {
                    action: dataaction,
                    dates   :   dates,
                    salid   :   salid
                },
                success: function(data){
                    if(data == 1)
                    {
                        toastr.success('Payroll date range is set!','Payroll')
                        window.location.reload();
                    }else{
                        toastr.error('Something went wrong!','Payroll')
                    }
                }
            })
            
        })

        // $('.btn-payroll-dates-submit').on('click', function () {
        //     var dataaction = $(this).attr('data-action');
        //     var salid = $('#select-salarytype').val();

        //     // Choose the appropriate date field based on dataaction
        //     var datesField = dataaction === 'new' ? $('#reservationnew') : $('#reservation');
        //     var dates = datesField.val();

        //     $.ajax({
        //         url: '/hr/payrollv3/payrolldates',
        //         type: 'get',
        //         xhr: function () {
        //             var xhr = new window.XMLHttpRequest();

        //             // Upload progress
        //             xhr.upload.addEventListener("progress", function (evt) {
        //                 if (evt.lengthComputable) {
        //                     var percentComplete = (evt.loaded / evt.total) * 100;
        //                     $('#progress-bar').width(percentComplete + '%');
        //                 }
        //             }, false);

        //             return xhr;
        //         },
        //         data: {
        //             action: dataaction,
        //             dates: dates,
        //             salid: salid
        //         },
        //         success: function (data) {
        //             // Reset progress bar after success
        //             $('#progress-bar').width('0%');

        //             if (data == 1) {
        //                 toastr.success('Payroll date range is set!', 'Payroll');
        //                 window.location.reload();
        //             } else {
        //                 toastr.error('Something went wrong!', 'Payroll');
        //             }
        //         }
        //     });
        // });
        
        $('#select-employee').on('change', function(){
           
            var employeeid = $(this).val();
            var selectedOption = $(this).find("option:selected");
            var salary = selectedOption.attr("salary");
            var activesyid = syid
            var semesterid = activesem
            // loademployee_clloadsubjects()
            // loadteachersubjload(activesyid, semesterid)

            var pid = $('.input-payrolldates').attr('data-id');
            getallholiday(employeeid, pid)

            if(employeeid == 0 || employeeid == null)
            {
                console.log(employeeid);

                $('#div-container-salaryinfo').empty();

                return false;
            }else{
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                
                $.ajax({
                    url: '/hr/payrollv3/getsalaryinfo',
                    type: 'get',
                    data: {
                        payrollid    :   pid,
                        employeeid   :   employeeid
                    },
                    success: function(data){
                        $('#div-container-salaryinfo').empty()
                        $('#div-container-salaryinfo').append(data)

                        $('.btn-compute[data-id="1"]').attr('data-employeeid', employeeid)
                        $('input[type="radio"]:checked').each(function(){
                            $(this).click();
                        })
                        $('.btn-compute[data-id="0"]').click()
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')


                        // toastr.success('Payroll date range is set!','Payroll')
                        // location.reload();
                    }
                })
            }
            
        })

        $('#select-employee-released').on('change', function(){
            var employeeid = $(this).val();
            var selectedOption = $(this).find("option:selected");
            var salary = selectedOption.attr("salary");
            var activesyid = syid
            var semesterid = activesem

            // loademployee_clloadsubjects()

            // loadteachersubjload(activesyid, semesterid)

            var pid = $('#select-releaseddates').val();
            getallholiday(employeeid, pid)

            if(employeeid == 0 || employeeid == null)
            {
                console.log(employeeid);

                $('#div-container-salaryinfo').empty();

                return false;
            }else{
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/hr/payrollv3/getsalaryinfo',
                    type: 'get',
                    data: {
                        payrollid    :   pid,
                        employeeid   :   employeeid
                    },
                    success: function(data){
                        $('#div-container-salaryinfo').empty()
                        $('#div-container-salaryinfo').append(data)

                        $('.btn-compute[data-id="1"]').attr('data-employeeid', employeeid)
                        $('input[type="radio"]:checked').each(function(){
                            $(this).click();
                        })
                        $('.btn-compute[data-id="0"]').click()
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        // toastr.success('Payroll date range is set!','Payroll')
                        // location.reload();
                    }
                })
            }
            
        })

        $('.div-each-employee').on('click', function(){
            $('.div-each-employee').removeClass('bg-success')
            $('.div-each-employee').removeClass('text-white')
            $('.div-each-employee').find('sup').removeClass('text-white')
            $('.div-each-employee').find('sup').addClass('text-muted')
            $(this).addClass('bg-success')
            $(this).addClass('text-white')
            $(this).find('sup').removeClass('text-muted')
            $(this).find('sup').addClass('text-white')
            var employeeid = $(this).attr('data-empid');
            
            $.ajax({
                url: '/hr/payrollv3/getsalaryinfo',
                type: 'get',
                data: {
                    payrollid    :   $('.input-payrolldates').attr('data-id'),
                    employeeid   :   employeeid
                },
                success: function(data){
                    
                    $('#div-container-salaryinfo').empty()
                    $('#div-container-salaryinfo').append(data)
                    $('.btn-compute[data-id="1"]').attr('data-employeeid', employeeid)
                    $('input[type="radio"]:checked').each(function(){
                        $(this).click();
                    })
                    $('.btn-compute[data-id="0"]').click()
                    // toastr.success('Payroll date range is set!','Payroll')
                    // window.location.reload();
                }
            })
        })
        function ReplaceNumberWithCommas(yourNumber) {
            //Seperates the components of the number
            var components = yourNumber.toString().split(".");
            //Comma-fies the first part
            components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //Combines the two sections
            return components.join(".");
        }
        $(document).on('click','.btn-compute', function(){
            var releaseddates = $('#select-employee-released').val()

            if (releaseddates == null || releaseddates == '') {
                var employeeid = $('#select-employee').val();
                var payrollid = $('#reservation').attr('data-id');
            } else {
                var payrollid = $('#select-releaseddates').val();
                var employeeid = $('#select-employee-released').val();
            }
            
            if (voidremarks == null || voidremarks == '') {
                valid_data = false;
                toastr.warning('Please enter your remarks.','Remarks')

            } 
     
            var totalearnings = 0;
            var holidayadmount = 0;
            var tardinessamount = 0;
            var basicpay = parseFloat($('#td-basicpay-amount').attr('data-amount').replace(',', ''));
            
            var regularloadamountElement = $('#td-clregular-amount');
            var overloadloadamountElement = $('#td-cloverload-amount');
            var parttimeloadamountElement = $('#td-clparttime-amount');

            var regularloadamount = regularloadamountElement.length ? parseFloat(regularloadamountElement.attr('data-amount').replace(',', '')) || 0 : 0;
            var overloadloadamount = overloadloadamountElement.length ? parseFloat(overloadloadamountElement.attr('data-amount').replace(',', '')) || 0 : 0;
            var parttimeloadamount = parttimeloadamountElement.length ? parseFloat(parttimeloadamountElement.attr('data-amount').replace(',', '')) || 0 : 0;

            var netsalary = parseFloat($('#netsalary').text().replace(',', ''));
            var overtimepay = parseFloat($('#td-overtimepay').attr('data-amount')); 
            
            // var overtimepay = parseFloat($('#td-overtimepay').attr('data-amount').replace(',', '')); 
            var overtimeids = JSON.stringify($('#td-overtimepay').attr('data-ids')); 
            var tardinessamount = parseFloat($('#tardinessamount').text().replace(',', ''))    
            var lateminutes =      $('#countlateminutes').attr('data-value');          
            var undertimeminutes =      $('#undertimeminutes').attr('data-value');          
            var totalworkedhours =      $('#totalworkedhours').attr('data-value');          
            var amountperday =      $('#amountperday').attr('data-value');          
            var amountabsent =      parseFloat($('#amountabsent').attr('data-value').replace(',', ''));          
            var amountundertime =      parseFloat($('#amountundertime').attr('data-value').replace(',', ''));          
            var amounttardyregular =      parseFloat($('#amounttardyregular').attr('data-value').replace(',', ''));          
            var amounttardyoverload =      parseFloat($('#amounttardyoverload').attr('data-value').replace(',', ''));          
            var amounttardyemergencyload =      parseFloat($('#amounttardyemergencyload').attr('data-value').replace(',', ''));          
            var amountlate =      parseFloat($('#amountlate').attr('data-value').replace(',', ''));        
            holidayadmount =      parseFloat($('#holiday-amount').attr('data-value'));   
            if (isNaN(holidayadmount)) {
                holidayadmount = 0
            } 
            if (isNaN(tardinessamount)) {
                tardinessamount = 0;
            }
            var deductamount = 0;
            var allowanceamount = 0;
            var particulars = [];

            totalearnings = (basicpay+regularloadamount+overloadloadamount+parttimeloadamount);
            if(overtimepay>0)
            {
                var overtimepay = parseFloat($('#td-overtimepay').attr('data-amount').replace(',', '')); 
                totalearnings+=overtimepay;
            }
            var totaldeductions = (amountabsent+amountlate+amountundertime+amounttardyregular+amounttardyoverload+amounttardyemergencyload);
            console.log('amountundertime');
            console.log(totaldeductions);
            console.log('amountundertime');
            
            $('.standarddeduction').each(function(){
                if($(this).find('input[type="checkbox"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var deducttype = 0;
                    if($(this).attr('data-deducttype') == 2)
                    {
                        deducttype = 2;
                        if($(this).find('.standarddedductioncustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.standarddeddsuctioncustom').val());
                            deductamount+=parseFloat($(this).find('.standarddedductioncustom').val());
                        }

                    }else{
                        deducttype = $(this).attr('data-deducttype');
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        deductamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : deducttype,
                        particulartype       : 1,
                        paidstatus         : 1,

                    };
                    totaldeductions+=amountpaid;
                    particulars.push(obj)

                    console.log('standarddeduction');
                    console.log(particulars);
                    console.log('standarddeduction');
                }
            })
            $('.otherdeduction').each(function(){
                if($(this).find('input[type="checkbox"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var deducttype = 0;
                    // if($(this).attr('data-deducttype') == 1)
                    // {
                    //     deducttype = $(this).attr('data-deducttype');
                    //     // deducttype = 1;
                    //     // if($(this).find('.otherdedductioncustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                    //     // {
                    //     //     amountpaid=parseFloat($(this).find('.otherdedductioncustom').val());
                    //     //     deductamount+=parseFloat($(this).find('.otherdedductioncustom').val());
                    //     // }

                    // }else{
                    //     deducttype = $(this).attr('data-deducttype');
                    // }
                        amountpaid=parseFloat($(this).attr('data-amount').replace(/,/g, ''));
                        deductamount+=parseFloat($(this).attr('data-amount').replace(/,/g, ''));

                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        // odid      : $(this).attr('data-odid'),
                        dataid : $(this).attr('dataid'),
                        description      : $(this).attr('data-description'),
                        // totalamount       : $(this).attr('data-totalamount').replace(/,/g, ''),
                        totalamount       : $(this).attr('data-totalamount').replace(/,/g, ''),
                        amountpaid       : amountpaid,
                        paymenttype       : $(this).attr('data-deducttype'),
                        particulartype       : 2,
                        paidstatus         : 1,
                        totalamountpaid : parseFloat($('td[data-totalamountdeductpaid]').attr('data-totalamountdeductpaid')) + amountpaid
                        
                    };
                    totaldeductions+=amountpaid;
                    particulars.push(obj)

                    // console.log('otherdeduction');
                    // console.log(particulars);
                    // console.log('otherdeduction');

                }
                
            })

            $('.otherdeductionentryamountactive').each(function() {
                var amountpaid = parseFloat($(this).val().replace(/,/g, ''));
                var deductionid = $(this).data('deductionid');
                var deducttype = $(this).data('deducttype');
                
                obj = {
                    particularid      : $(this).attr('data-deductionid'),
                    // odid      : $(this).attr('data-odid'),
                    dataid : $(this).attr('dataid'),
                    description      : $(this).attr('data-description'),
                    totalamount       : amountpaid,
                    amountpaid       : amountpaid,
                    paymenttype       : $(this).attr('data-deducttype'),
                    particulartype       : 2,
                    paidstatus         : 1

                };

                totaldeductions += amountpaid;
                particulars.push(obj);
                // console.log('otherdeductionentryamountactive');
                // console.log(particulars);
                // console.log('otherdeductionentryamountactive');
            });
            // $('.amountholiday').each(function(){
            //     amountpaid=parseFloat($(this).attr('data-amount'));

            //     obj = {
            //             particularid      : $(this).attr('data-id'),
            //             description      : $(this).attr('data-typename'),
            //             totalamount       : amountpaid,
            //             amountpaid       : amountpaid,
            //             paymenttype       : 1,
            //             particulartype       : 8
            //         };
            //     // totalearnings += amountpaid;

            //     // console.log(totalearnings);
            //     particulars.push(obj)
            // })
            $('.standardallowance').each(function(){
                if($(this).find('input[type="checkbox"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var allowancetype = 0;
                    if($(this).attr('data-allowancetype') == 2)
                    {
                        if($(this).find('.standardallowancecustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.standardallowancecustom').val());
                            allowanceamount+=parseFloat($(this).find('.standardallowancecustom').val());
                        }

                    }else{
                        allowancetype = $(this).attr('data-allowancetype');
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        allowanceamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-allowanceid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        // odid      : $(this).attr('data-odid'),
                        amountpaid       : amountpaid,
                        paymenttype       : allowancetype,
                        particulartype       : 3,
                        paidstatus         : 1
                    };
                    totalearnings += amountpaid;

                    particulars.push(obj)

                    // console.log('standardallowance');
                    // console.log(particulars);
                    // console.log('standardallowance');
                }
            })
            $('.otherallowance').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var allowancetype = 0;
                    if($(this).attr('data-allowancetype') == 2)
                    {
                        if($(this).find('.otherallowancecustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.otherallowancecustom').val());
                            allowanceamount+=parseFloat($(this).find('.otherallowancecustom').val());
                        }

                    }else{
                        allowancetype = $(this).attr('data-allowancetype');
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        allowanceamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-allowanceid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        // odid      : $(this).attr('data-odid'),
                        amountpaid       : amountpaid,
                        paymenttype       : allowancetype,
                        particulartype       : 4
                    };
                }
            })
            
            // $('.td-leaves').each(function(){
            //         obj = {
            //             particularid      : 0,
            //             ldateid      : $(this).attr('data-ldateid'),
            //             description      : $(this).attr('data-description'),
            //             totalamount       : $(this).attr('data-amount'),
            //             amountpaid       : $(this).attr('data-amount'),
            //             paymenttype       : 0,
            //             employeeleaveid       : $(this).attr('data-empleaveid'),
            //             particulartype       : 0
            //         };
            //         totalearnings += parseFloat($(this).attr('data-amount'));
            //         particulars.push(obj)

            //         // console.log(obj);
            // })
            $('.filedovertimes').each(function(){
                    obj = {
                        particularid      : $(this).attr('data-id'),
                        description      : $(this).attr('data-totalhours')+' hr(s)',
                        totalamount       : $(this).attr('data-amount'),
                        amountpaid       : $(this).attr('data-amount'),
                        paymenttype       : 0,
                        particulartype       : 6
                    };
                    particulars.push(obj)
            })
            
            $('.span-description').each(function(){
                if($(this).attr('data-type') == 1)
                {
                    allowanceamount+=parseFloat($(this).attr('data-amount').replace(',', ''));
                    totalearnings+=parseFloat($(this).attr('data-amount').replace(',', ''));

                }else{
                    deductamount+=parseFloat($(this).attr('data-amount').replace(',', ''));
                    totaldeductions+=parseFloat($(this).attr('data-amount').replace(',', ''));
                }
                obj = {
                    particularid      : 0,
                    description      : $(this).text(),
                    totalamount       : $(this).attr('data-amount'),
                    amountpaid       : $(this).attr('data-amount'),
                    paymenttype       : 0,
                    dataid : $(this).attr('data-id'),
                    particulartype       : $(this).attr('data-type')
                };
                particulars.push(obj)
            })

            var additionalparticulars = [];

            $('.additional-paticular').each(function() {
                var obj = {
                    id: $(this).attr('id'),
                    type: $(this).attr('data-type'),
                    amount: $(this).attr('data-amount'),
                    description: $(this).text(),
                    dataid : $(this).attr('data-id')
                };

                additionalparticulars.push(obj);
                
                console.log(additionalparticulars);
            });
            
            netsalary += allowanceamount;
         
            $('#span-total-earn-display').text(ReplaceNumberWithCommas(totalearnings.toFixed(2)))
            $('#span-total-earn').text(totalearnings.toFixed(2))
            $('#span-total-deduct-display').text(ReplaceNumberWithCommas(totaldeductions.toFixed(2)))
            $('#span-total-deduct').text(totaldeductions.toFixed(2))
            
            // var newsalary = parseFloat(netsalary-deductamount).toFixed(2);
            // newsalary = ReplaceNumberWithCommas(newsalary);
            var netpay = (totalearnings-totaldeductions);
            var netpayDisplay = $('#span-netpay-display');
            if (netpay <= 0) {
                netpayDisplay.text(ReplaceNumberWithCommas(netpay.toFixed(2))).addClass('text-red');
            } else {
                netpayDisplay.text(ReplaceNumberWithCommas(netpay.toFixed(2))).addClass('text-green');
            }
            $('#span-netpay').text(netpay.toFixed(2))
            // $('#netsalary').hide();
            // $('#newsalary').text(newsalary)

            if($(this).attr('data-id') == '1')
            {
                // var additionalparticulars = [];
                // if($('.additional-paticular').length > 0)
                // {
                //     $('.additional-paticular').each(function(){
                //         obj = {
                //             id              : $(this).attr('id'),
                //             type            : $(this).attr('data-type'),
                //             amount          : $(this).attr('data-amount'),
                //             description     : $(this).text()
                //         }
                //         additionalparticulars.push(obj)
                //     })
                // }
                $.ajax({
                    url: '/hr/payrollv3/configuration',
                    type: 'get',
                    data: {
                        payrollid               : payrollid,
                        particulars             : JSON.stringify(particulars),
                        additionalparticulars   : JSON.stringify(additionalparticulars),
                        amountabsent            : amountabsent,
                        amountlate              : amountlate,
                        amountundertime         : amountundertime,
                        amounttardyregular      : amounttardyregular,
                        amounttardyoverload     : amounttardyoverload,
                        amounttardyemergencyload: amounttardyemergencyload,
                        tardinessamount         : tardinessamount,
                        lateminutes             : lateminutes,
                        undertimeminutes        : undertimeminutes,
                        totalworkedhours        : totalworkedhours,
                        amountperday            : amountperday,
                        netsalary               : netpay,
                        totalearnings           : totalearnings,
                        totaldeductions         : totaldeductions,
                        dayspresent             : $('#dayspresent').attr('dayspresentwithsat'),
                        daysabsent              : $('#daysabsent').text(),
                        basicsalary             : $('#td-basicpay-amount').attr('data-amount'),
                        monthlysalary           : $('#monthlysalary').text(),
                        salarytype              : $('#salarytype').text(),
                        employeeid              :   employeeid,
                        regularloadamount       :   regularloadamount,
                        overloadloadamount      :   overloadloadamount,
                        parttimeloadamount      :   parttimeloadamount
                    },
                    success: function(data){
                        if(data == 1)
                        {                            
                            $('#btn-release-payslip').show();
                            // $('.btn-compute[data-id="0"]').click()
                            toastr.success('Saved successfully!','Payroll Computation')
                        }
                        // toastr.success('Payroll date range is set!','Payroll')
                        // window.location.reload();
                    }
                })
            }
        })
        function numberofreleased(){
            var payrollid = $('.input-payrolldates').attr('data-id');
            $.ajax({
                url: '/hr/payrollv3/payrolldates',
                type: 'get',
                data: {
                    action                  : 'getnumberofreleased',
                    payrollid               : $('.input-payrolldates').attr('data-id')
                },
                success: function(data){
                    $('#numofreleased').text(data)
                }
            })
        }

        $(document).on('click','#btn-release-payslip', function(){

            var releaseddates = $('#select-employee-released').val()

            if (releaseddates == null || releaseddates == '') {
                var employeeid = $('#select-employee').val();
                var payrollid = $('#reservation').attr('data-id');
            } else {
                var payrollid = $('#select-releaseddates').val();
                var employeeid = $('#select-employee-released').val();
            }
            

            
            // var employeeid = $('#select-employee').val();
            Swal.fire({
            title: 'Are you sure?',
            html: "Once released, you will not be able to reconfigure this computation again!<br/>Please save all changes first!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Release',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    // $('.btn-compute[data-id="0"]').click()
                    // $('#btn-save-computation').hide();
                    // window.open('/hr/payrollv3/export?exporttype=1&payrollid='+$('.input-payrolldates').attr('data-id')+'&employeeid='+employeeid,'_blank')
                    // $('#select-employee').change()
                    // numberofreleased();


                    // $('.btn-compute[data-id="0"]').click()
                    // $('#btn-save-computation').hide();
                    // window.open('/hr/payrollv3/export?exporttype=1&payrollid='+$('.input-payrolldates').attr('data-id')+'&employeeid='+employeeid,'_blank')
                    // $('#select-employee').change()
                    // numberofreleased();
                   
                    $.ajax({
                        type: "GET",
                        url: "/hr/payrollv3/export",
                        data: {
                            payrollid : payrollid,
                            employeeid : employeeid,
                            exporttype : 1
                        },
                        success: function (data) {
                            $('#btn-refresh').click()
                            window.open('/hr/payrollv3/export?exporttype=1&payrollid='+payrollid+'&employeeid='+employeeid,'_blank')

                        }
                    });
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    // Swal.fire(
                    // 'Cancelled',
                    // 'Your imaginary file is safe :)',
                    // 'error'
                    // )
                }
            })
            numberofreleased();
        })
        $(document).on('click','#btn-export-payslip', function(){
            var employeeid = $('#select-employee').val();

            if (employeeid == null || employeeid == '') {
                employeeid = $('#select-employee-released').val();
            }
          
            var payrollid = $('.input-payrolldates').attr('data-id');
            
            $('#btn-save-computation').hide();
            window.open('/hr/payrollv3/export?exporttype=1&payrollid='+$('.input-payrolldates').attr('data-id')+'&employeeid='+employeeid,'_blank')
        })
        $(document).on('click', '#void_payslip', function(){
            $('#modal_void').modal('show')
        })
        $(document).on('click', '#edit_payslip', function(){
            $('#modal_editpayslip').modal('show')
        })

        $(document).on('click','.remove-particular', function(){
            // var particulartype = $(this).attr('data-id')
            // var particularamount = $(this).attr('data-amount')
            // var netsalary = parseFloat($('#newsalary').text().replace(',', ''))
            // if(particulartype == 1)
            // {
            // var newsalary = parseFloat(netsalary)-parseFloat(particularamount);
            // }
            // if(particulartype == 2)
            // {
            //     var newsalary = parseFloat(netsalary)+parseFloat(particularamount);
            // }
            // newsalary = ReplaceNumberWithCommas(newsalary);
            // $('#netsalary').hide();
            // $('#newsalary').text(parseFloat(newsalary).toFixed(2))
            $(this).closest('tr').remove();
            $('.btn-compute[data-id="0"]').click()
        })
        $(document).on('click', '.delete-particular', function(){
            var id = $(this).attr('data-id');
            var employeeid = $(this).attr('dataemployeeid');
            var thisrow = $(this).closest('.row');
            var additionalid = $(this).attr('additionalid');

            Swal.fire({
            title: 'Are you sure about deleting this added particular?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                $.ajax({
                    url: '/hr/payrollv3/addedparticular',
                    type: 'get',
                    data: {
                        action         : 'delete',
                        payrollid      : $('.input-payrolldates').attr('data-id'),
                        employeeid     : employeeid,
                        id             : id,
                        additionalid   : additionalid
                    },
                    success: function(data){
                        if(data == 1)
                        {                            
                            toastr.success('Deleted successfully!','Added Particulars')
                            $('#btn-refresh').click()
                        }
                        // toastr.success('Payroll date range is set!','Payroll')
                        // window.location.reload();
                    }
                })
                }

            })
        })
        $('#btn-print-summary').on('click', function(){
            window.open('/hr/payrollv3/export?exporttype=2&payrollid='+$('.input-payrolldates').attr('data-id'),'_blank')
        })
        function refreshContent() {
            // Call the logic responsible for refreshing content
            // For example, if it's the logic behind the #btn-refresh button, trigger its click event
            $('#btn-refresh').click();
        }

        
        function loadteachersubjload(activesyid, semesterid){
            var empid = $('#select-employee').val()
            var activesy = activesyid
            var employeesubjects = [];
            
            var schoolyearid = $('#profileselect-sy').val()

            // return false;
            $.ajax({
            type: "GET",
            url: "/payrollclerk/setup/parttime/loading/perteacher",
            data: {
                syid : activesyid,
                semid : semesterid,
                empid : empid
            },
            success: function (data) {
                console.log(data);

                var table = $('<table width="100%" class="table table-bordered">');
                table.append(`<thead>
                                <tr>
                                <th class="" width="40%">SUBJECTS</th>
                                <th class="text-center" width="13%"># OF HOURS <br> LECTURE</th>
                                <th class="text-center" width="13%"># OF HOURS <br> LAB</th>
                                <th class="text-center" width="10%">HOURLY <br> RATE</th>
                                <th class="text-center" width="14%">TYPE</th>
                                <th class="text-center" width="10%">TOTAL</th>
                                </tr>
                            </thead>`);
                var body = $('<tbody>');
                    $.each(data, function (index, item) {
                    var filtersubjtype = clsubjects.filter(x=>x.subjid == item.subjid && x.syid == item.syid && x.semid == item.semid)[0]
                    if (filtersubjtype) {
                    if (filtersubjtype.subjtype != null) {
                        var fsubjectype = filtersubjtype.subjtype;
                    } else {
                        var fsubjectype = 1;
                    }

                    var labhours = filtersubjtype.numberofhourslab
                    var lechours = filtersubjtype.numberofhourslec
                    // var persem = filtersubjtype.totalpemers

                    if (labhours == 0 && lechours != 0) {
                        var persem = lechours * filtersubjtype.hourlyrate
                    } else if(lechours == 0 && labhours != 0){
                        var persem = labhours * filtersubjtype.hourlyrate
                    } else if(labhours == 0 && lechours == 0) {
                        var persem = 0
                    } else {
                        totalhours = labhours + lechours
                        var persem = totalhours * filtersubjtype.hourlyrate
                    }
                    
                    if (item.intervals != null || item.intervals != 0) {
                        var per15 = persem / item.intervals
                    } else {
                        var per15 = 0
                    }
                    var per15 = persem / item.intervals
                    } else {
                    var fsubjectype = 1;
                    var labhours = 0
                    var lechours = item.totalhourss
                    var persem = item.amountinasem
                    if (item.intervals != null || item.intervals != 0) {
                        var per15 = item.amountinasem / item.intervals
                    } else {
                        var per15 = 0
                    }
                    }

                    var selectElement = $('<select>', {
                    class: 'select2 form-control form-control-sm text-uppercase subjecttypechange',
                    datasubjid: item.subjid,
                    datasyid: item.syid,
                    datasemid: item.semid,
                    id: 'subjecttype_' + item.subjid,
                    style: 'margin: auto!important;'

                    
                    
                    }).append(
                    $('<option>', {
                        value: '1',
                        text: 'Regular',
                        selected: fsubjectype == 1
                    }),
                    $('<option>', {
                        value: '2',
                        text: 'Overload',
                        selected: fsubjectype == 2
                    }),
                    $('<option>', {
                        value: '3',
                        text: 'Part Time',
                        selected: fsubjectype == 3
                    })
                    ).on('change', function() {
                    employeesubjects[index].subjtype = $(this).val();
                    });

                    var updateIcon = $('<a>', {
                    class: 'fas fa-edit text-primary text-center updaterowcomputation', // Assuming you are using Font Awesome
                    style: 'font-size: 18px; cursor: pointer;',
                    id: 'updaterowcomputation'+item.subjid,
                    datasubjid: item.subjid,
                    interval:item.intervals

                    });

                    body.append($('<tr>').append(
                        $('<td>').append($('<span>', {
                            style: 'font-size: 14px;',
                            text: item.subjdesc
                        })),
                        // $('<td>', {
                        //     class: 'text-center',
                        //     style: 'font-size: 14px;',
                        //     text: item.totalhourss
                        // }),
                        $('<td>').append($('<input>', {
                            type: 'number',
                            class: 'form-control form-control-sm text-center lechoursinput',
                            id: 'lechoursinput'+item.subjid,
                            datasubjid: item.subjid,
                            perhour: item.clsubjperhour,
                            totalpersem: item.amountinasem,
                            interval:item.intervals,
                            'data-subjid': item.subjid, // Add your custom data attributes if needed
                            'data-syid': item.syid,
                            'data-semid': item.semid,
                            value: lechours,
                            style: 'border: none!important'
                        })),
                        $('<td>').append($('<input>', {
                            type: 'number',
                            class: 'form-control form-control-sm text-center labhoursinput',
                            id: 'labhoursinput'+item.subjid,
                            datasubjid: item.subjid,
                            perhour: item.clsubjperhour,
                            totalpersem: item.amountinasem,
                            interval:item.intervals,
                            'data-subjid': item.subjid, // Add your custom data attributes if needed
                            'data-syid': item.syid,
                            'data-semid': item.semid,
                            value: labhours,
                            style: 'border: none!important'
                        })),
                        $('<td>', {
                            class: 'text-center',
                            style: 'font-size: 14px;',
                            text: item.clsubjperhour
                        }),
                        $('<td>').append(selectElement),
                        $('<td>', {
                            class: 'text-center',
                            id: 'totalpersem'+item.subjid,
                            style: 'font-size: 14px;',
                            text: number_format(persem,2)
                        }),
                        $('<td>').append(updateIcon)
                    ));

                    // Accumulate data in the array
                    employeesubjects.push({
                    empid: empid,
                    activesy: activesy,
                    semid: item.semid,
                    subjid: item.subjid,
                    subjdesc: item.subjdesc,
                    labhours: labhours,
                    lechours: lechours,
                    clsubjperhour: item.clsubjperhour,
                    amountpersem: persem,
                    subjtype: fsubjectype,
                    per15 : per15
                    }); 
                });

                table.append(body);
                $('.collegeloadtable').empty().append(table);
                saveAllData(employeesubjects);
            }
            });

            function saveAllData(employeesubjects) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "/payrollclerk/setup/parttime/saveempsubjects",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                data: JSON.stringify({
                    employeesubjects: employeesubjects
                }),
                contentType: "application/json",
                success: function (data) {
                },
                error: function (error) {
                    console.error('Error saving data:', error);
                }
            });
            }
        }
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };

            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }

            return s.join(dec);
        }

        function loademployee_clloadsubjects(){
            var empid = $('#select-employee').val()
            var activesyid = syid
            var semesterid = activesem

            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/loadclloadsubjects",
                data: {
                    syid : syid,
                    semid : semesterid,
                    empid : empid
                },
                success: function (data) {
                    clsubjects = data
                    console.log(clsubjects);
                }
            });
        }

        function getactivesem(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/getactivesem",
                data: {
                    syid : syid
                },
                success: function (data) {
                    activesem = data[0].semester
                }
            });
        }

        function getallholiday(employeeid, pid){

            $.ajax({
              type: "GET",
              url: "/hr/payrollv3/getallholiday",
              data: {
                employeeid : employeeid,
                pid : pid
              },
              success: function (data) {
              }
            });
        }

        var releasedpayslip = []
        // All Payroll Released
        $(document).on('click', '#all_releasedpayslip', function(){
            load_empallpayrollreleased()
            $('#modal_allreleasedpayroll').modal('show');
        })
        function load_empallpayrollreleased(){
            var employeeid = $('#select-employee').val();
            $.ajax({
            type: "GET",
            url: "/hr/payrollv3/emp_allreleasedpayrolldates",
            data: {
                employeeid : employeeid
            },
            success: function (data) {
                releasedpayslip = data
                console.log(releasedpayslip);
                load_allpayrollreleased_select2()
                empallpayrollreleased()
            }
            });
        }

        function load_allpayrollreleased_select2(){
            $('#select-payrolldatesreleased').empty()
            $('#select-payrolldatesreleased').append('<option value="">Select Payroll Date</option>')
            $('#select-payrolldatesreleased').select2({
                data: releasedpayslip,
                allowClear : true,
                placeholder: 'Select Payroll Date'
            });
        }

        function empallpayrollreleased(){
            $('#listreleasedpayroll_datatable').DataTable({
                lengthMenu: false,
                info: false,
                paging: true,
                searching: true,
                destroy: true,
                lengthChange: false,
                // scrollX: true,
                // autoWidth: true,
                order: false,
                data: releasedpayslip,
                columns : [
                    {"data" : 'text'},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span>' + rowData.text + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle p-1');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span>' + rowData.totalearning + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle p-1');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span>' + rowData.totaldeduction + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle p-1');
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span>' + rowData.netsalary + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle p-1');
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<a href="javascript:void(0)" type="button" data-payroll="'+rowData.payrollid+'" class="btn btn-tool text-secondary btn-getdetails" data-card-widget="collapse">View Details</a>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                        }
                    }
                ]
            })

            var label_text = $($('#listreleasedpayroll_datatable_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<div class="row" style="padding-bottom: 10px!important;"><div class="col-md-12 col-sm-12"><a href="javascript:void(0)" id="listview"><i class="fas fa-list"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" id="listview"><i class="fas fa-th-large"></i></a></div></div>'
        }

        $(document).on('click', '.btn-getdetails', function(){
            var pid = $(this).attr('data-payroll')

            
            var employeeid = $('#select-employee').val();
            var action = 'modaldetails';
            // return false;
            $.ajax({
                url: '/hr/payrollv3/getsalaryinfo',
                type: 'get',
                data: {
                    payrollid    :   pid,
                    employeeid   :   employeeid,
                    action       :   action
                },
                success: function(data){
                    console.log(data);
                    var attendance = data.attendance;
                    console.log(attendance);
                    modaltablepayrolldetails(attendance,data)
                    getadditionaldetails(pid, employeeid)
                }
            })
            $('#modal_payrolldetails').modal('show');
        })

        function modaltablepayrolldetails(attendance,data){
            $('#modal_payrolldetailsdatatable').DataTable({
                lengthMenu: false,
                info: false,
                paging: false,
                searching: false,
                destroy: true,
                lengthChange: false,
                // scrollX: true,
                autoWidth: false,
                order: false,
                data: attendance,
                columns : [
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
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
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span><b>'+rowData.date+'</b><br><small style="font-size: 11px !important">'+rowData.day+'</small></span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle p-1');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (rowData.amin == null) {
                                var amin = '';
                            } else {
                                var amin = rowData.amin;
                            }
                            var content = '<span>'+amin+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (rowData.amout == null) {
                                var amout = '';
                            } else {
                                var amout = rowData.amout;
                            }
                            var content = '<span>'+amout+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (rowData.pmin == null) {
                                var pmin = '';
                            } else {
                                var pmin = rowData.pmin;
                            }
                            var content = '<span>'+pmin+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (rowData.pmout == null) {
                                var pmout = '';
                            } else {
                                var pmout = rowData.pmout;
                            }
                            var content = '<span>'+pmout+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (data.basicsalaryinfo.flexitime == 1) {
                                var min = 0;
                            } else {
                                if (rowData.latehours > 0) {
                                    var number = rowData.latehours * 60;
                                    var formatter = new Intl.NumberFormat('en-US');
                                    var min = formatter.format(number);
                                } else {
                                    var min = 0;
                                }
                            }
                            if (min > 0) {
                                var unit = 'mins';
                            } else {
                                var unit = 'min';
                            }
                            var content = '<span>'+min+' '+unit+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (data.basicsalaryinfo.flexitime == 1) {
                                if (rowData.latehours > 0) {
                                    var number = rowData.flexihoursundertime * 60;
                                    var formatter = new Intl.NumberFormat('en-US');
                                    var min = formatter.format(number);
                                } else {
                                    var min = 0;
                                }
                            } else {
                                if (rowData.latehours > 0) {
                                    var number = rowData.undertimehours * 60;
                                    var formatter = new Intl.NumberFormat('en-US');
                                    var min = formatter.format(number);
                                } else {
                                    var min = 0;
                                }
                            }
                            if (min > 0) {
                                var unit = 'mins';
                            } else {
                                var unit = 'min';
                            }
                            var content = '<span>'+min+' '+unit+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 7,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var hours, minutes;
                            if (data.basicsalaryinfo.flexitime == 1) {
                                hours = Math.floor(rowData.flexihours);
                                minutes = Math.floor((rowData.flexihours - hours) * 60);
                            } else {
                                hours = Math.floor(rowData.totalworkinghoursrender);
                                minutes = Math.floor((rowData.totalworkinghoursrender - hours) * 60);
                            }

                            var content = '<span>' + hours + ' hours ' + minutes + ' minutes</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 8,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (data.basicsalaryinfo.flexitime == 1) {
                                var min = 0;
                            } else {
                                if(data.basicsalaryinfo.attendancebased == 1){
                                    if (data.tardinessbaseonsalary != null) {
                                    if (data.basicsalaryinfo.flexitime == 1) {
                                        var latetime = rowData.flexihoursundertime * 60;

                                        var amountperhour = data.basicsalaryinfo.amountperhour / 60;
                                        var min = (amountperhour - .005) * latetime;
                                    } else {
                                        var latetime = rowData.lateminutes;
                                        var amountperhour = data.basicsalaryinfo.amountperhour / 60;
                                        var min = (amountperhour - .005, 2) * latetime;
                                    }
                                    
                                    } else {
                                    if (data.basicsalaryinfo.flexitime == 1) {
                                        var min = rowData.latedeductionamount;
                                    } else {
                                        var min = rowData.latedeductionamount;
                                    }
                                    }
                                } else {
                                    var min = 0;
                                }
                            }
                            var content = '<span>'+min+'</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle p-1');
                        }
                    },
                    {
                        'targets': 9,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (data.basicsalaryinfo.flexitime == 1) {
                                if (data.basicsalaryinfo.attendancebased == 1) {
                                    if (data.tardinessbaseonsalary != null) {
                                        if (data.basicsalaryinfo.flexitime == 1) {
                                            var latetime = rowData.flexihoursundertime * 60;

                                            var amountperhour = data.basicsalaryinfo.amountperhour / 60;
                                            var number = (amountperhour - 0.005) * latetime;
                                            var formattedNumber = number.toFixed(2);  // Format to two decimal places
                                        } else {
                                            var latetime = rowData.lateminutes;
                                            var amountperhour = data.basicsalaryinfo.amountperhour / 60;
                                            var number = (amountperhour - 0.005) * latetime;
                                            var formattedNumber = number.toFixed(2);  // Format to two decimal places
                                        }
                                    } else {
                                        var formattedNumber = rowData.latedeductionamount.toFixed(2);  // Format to two decimal places
                                    }
                                } else {
                                    var formattedNumber = '0.00';  // Default value if no value to format
                                }
                            } else {
                                var undertime = (rowData.undertimehours * 60);
                                var amountperhour = data.basicsalaryinfo.amountperhour / 60;
                                var number = (amountperhour - 0.005) * undertime;
                                var formattedNumber = number.toFixed(2);  // Format to two decimal places
                            }
                            
                            var content = '<span>' + formattedNumber + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                        }
                    }
                ]
            })
    
        }

        function getadditionaldetails(pid, employeeid){
            $.ajax({
                url: '/hr/payrollv3/payrolldetails',
                type: 'get',
                data: {
                    action: 'getadditionaldetails',
                    historyid   :   pid,
                    employeeid : employeeid
                },
                success: function(data){
                    console.log(data);
                    $('#additionaldetailsdeductions').empty()
                    $('#additionaldetailsearnings').empty()

                    var basicdeduction = '<table width="100%" style="text-transform: uppercase; font-weight: bold; font-size: 11px;"><tr><td width="65%">Absent</td><td width="35%"><span>' + data.history.daysabsentamount + '</span></td></tr>' +
                                        '<tr><td width="65%" style="text-transform: uppercase; font-weight: bold; font-size: 11px;">Late</td><td width="35%"><span>' + data.history.lateamount + '</span></td></tr>' +
                                        '<tr><td width="65%" style="text-transform: uppercase; font-weight: bold; font-size: 11px;">Undertime</td><td width="35%"><span>' + data.history.undertimeamount + '</span></td></tr></table>';

                    var basicsalaryamount = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">Basic Pay</small></td><td class="" width="35%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + data.history.basicsalaryamount + '</small></td></tr></table>';

                    $('#additionaldetailsdeductions').append(basicdeduction);
                    $('#additionaldetailsearnings').append(basicsalaryamount);

                    if (data.particulars.length > 1) {
                        $.each(data.particulars, function (index, item) {
                            if (item.particulartype === 1) {
                                var deductionsContent = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.description + '</small></td><td class="" width="35%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.amountpaid + '</small></td></tr></table>';
                                $('#additionaldetailsdeductions').append(deductionsContent);
                            } else if(item.particulartype === 2) {
                                var deductionsContentother = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.description + '</small></td><td class="" width="35%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.amountpaid + '</small></td></tr></table>';
                                $('#additionaldetailsdeductions').append(deductionsContentother);
                            } else {
                                var earningsContent = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.description + '</small></td><td class="" width="35%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.amountpaid + '</small></td></tr></table>';
                                $('#additionaldetailsearnings').append(earningsContent);
                            }
                        });
                    }
                    // if (data.addedparticulars.length > 1) {
                    //     $.each(data.addedparticulars, function (index, item) {
                    //         if (item.type == 1) {
                    //             var deductionsContent = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.description + '</small></td><td class="" width="35%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.amountpaid + '</small></td></tr></table>';
                    //             $('#additionaldetailsdeductions').append(deductionsContent);
                    //         } else {
                    //             var earningsContent = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.description + '</small></td><td class="" width="35%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + item.amountpaid + '</small></td></tr></table>';
                    //             $('#additionaldetailsearnings').append(earningsContent);
                    //         }
                    //     });
                    // }

                    var totalearning = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">Total Earnings</small></td><td class="" width="35%"><small class="text-success"  style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + data.history.totalearning + '</small></td></tr></table>';
                    var totaldeduction = '<table width="100%"><tr><td width="65%"><small style="text-transform: uppercase; font-weight: bold; font-size: 11px;">Total Deduction</small></td><td class="" width="35%"><small class="text-danger" style="text-transform: uppercase; font-weight: bold; font-size: 11px;">' + data.history.totaldeduction + '</small></td></tr></table>';
                    $('#additionaldetailsdeductions').append(totaldeduction);
                    $('#additionaldetailsearnings').append(totalearning);
                }
            })
        }

        // // Append content to the 'additionaldetailsearnings' div
        // $('#additionaldetailsearnings').append(earningsContent);

        // // Append content to the 'additionaldetailsdeductions' div
        // $('#additionaldetailsdeductions').append(deductionsContent);
        // ============================================================================================

    })



  
  </script>
@endsection

