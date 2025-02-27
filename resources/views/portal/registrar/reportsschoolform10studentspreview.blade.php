
@extends('registrar.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td                                          { border-bottom: hidden; }
        input[type=text], .input-group-text, .select{ background-color: white !important; border: hidden; border-bottom: 2px solid #ddd; font-size: 12px !important; }
        .input-group-text                           { border-bottom: hidden; }
        .fontSize                                   { font-size: 12px; }
        .container                                  { overflow-x: scroll !important; }
        table                                       { width: 100%; }
        .inputClass                                 { width: 100%; }
        .tdInputClass                               { padding: 0px !important; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button            { -webkit-appearance: none; margin: 0; }
    </style>

    @php
        $male = 1;
        $female = 1;
    @endphp
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="School Form 10" name="selectedform"/>
    </form>
    <form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="School Form 10" name="selectedform"/>
    </form>
    <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'elementary')
                <h4>Elementary</h4>
            @elseif($academicprogram == 'juniorhighschool')
                <h4>Junior High School</h4>
            @elseif($academicprogram == 'seniorhighschool')
                <h4>Senior High School</h4>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/reports/{{$academicprogram}}">{{$selectedform}}</a></li>
                    <li class="breadcrumb-item"><a id="selectschoolyear" class="text-info">{{$schoolyeardesc}}</a></li>
                    <li class="breadcrumb-item"><a id="selectsection" class="text-info">{{$selectedsection}}</a></li>
                    <li class="breadcrumb-item"><a href="/reports_schoolform10/students/{{$schoolyear}}/{{$sectionid}}/{{$gradelevelid}}/{{$info->teacherid}}" >School Form 10 (Form 137)</a></li>
                    <li class="breadcrumb-item active">{{$studentdata->lastname}}, {{$studentdata->firstname}} {{$studentdata->middlename}} {{$studentdata->suffix}}.</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>Learner's Permanent Academic Record</strong>
                    </h3>
                    <br>
                    <small><em>(Formerly Form 137)</em></small>
                    @if(isset($gradelevelid))
                    <form action="/reports_schoolform10/print/{{$schoolyear}}/{{$sectionid}}/{{$gradelevelid}}/{{$studentdata->id}}" target="_blank" method="get" class="m-0 p-0">
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                        <button type="submit" class="btn btn-primary btn-sm text-white float-right">
                            <i class="fa fa-upload"></i>
                        Print
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">NAME:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->lastname}}, {{$studentdata->firstname}} {{$studentdata->middlename}} {{$studentdata->suffix}}." aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">SEX:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->gender}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">DATE OF BIRTH:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->dob}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">PLACE OF BIRTH:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->street}} / {{$studentdata->barangay}}" aria-describedby="inputGroupPrepend" placeholder="(Street/Barangay)" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">/</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->city}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">/</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->province}}" aria-describedby="inputGroupPrepend" placeholder="(Province)" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($studentdata->mothername != ',')
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">PARENT / GUARDIAN:</span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->mothername}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">OCCUPATION:</span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->moccupation}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($studentdata->fathername != ',')
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">PARENT / GUARDIAN:</span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->fathername}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">OCCUPATION:</span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->foccupation}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($studentdata->guardianname != ',')
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">PARENT / GUARDIAN:</span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->guardianname}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">OCCUPATION:</span>
                                        </div>
                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">ADDRESS:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->street}} / {{$studentdata->barangay}}" aria-describedby="inputGroupPrepend" placeholder="(Street/Barangay)" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">/</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->city}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">/</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->province}}" aria-describedby="inputGroupPrepend" placeholder="(Province)" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">INTERMEDIATE COURSE COMPLETED:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Year:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="/reports_schoolform10/students/{{$schoolyear}}/{{$info->sectionid}}/{{$info->glevelid}}/{{$info->teacherid}}">{{$info->levelname}} - {{$info->sectionname}}</a></li>
                <li class="breadcrumb-item active">Learner's Permanent Academic Record</li>
            </ol>
        </div>
        <div class="col-md-12">
            <button id="addrecord" type="button" class="btn btn-warning btn-sm float-left"><i class="fa fa-plus"></i></button>
            <button id="generateBtn" type="button" class="btn btn-primary btn-sm float-left ml-2"> <small><i class="fa fa-sync"></i>&nbsp;Generate</small></button>
            @if((string)Session::get('newData') == true)
                <br>
                <br>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> {{ (string)Session::get('newData') }}</h5>
                    
                </div>
            @endif
            @if((string)Session::get('message') == true)
                <br>
                <br>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> {{ (string)Session::get('message') }}</h5>
                
                </div>
            @endif
            @if((string)Session::get('deleteData') == true)
                <br>
                <br>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> {{ (string)Session::get('deleteData') }}</h5>
                    
                </div>
            @endif
        </div>
        &nbsp;
        <div class="col-md-12">
            <div id="addcontainer"></div>
        </div>
        <div class="col-md-12">
            @if(isset($studentRecords))
                @php
                    $uniqueId = 1;   
                @endphp
                @foreach ($studentRecords as $studentRecord)
                    <div id="accordion">
                        <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title col-md-12" >
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$uniqueId}}">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>
                                                        </div>
                                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord[0]['gradelevelname']}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">School</span>
                                                        </div>
                                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord[0]['schoolname']}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">School Year:</span>
                                                        </div>
                                                        <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord[0]['schoolyear']}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne{{$uniqueId}}" class="panel-collapse collapse in">
                                <div class="card-body">
                                    <div class="container">
                                        @if($studentRecord[0]['operation'] == 1)
                                            <form name="formSubmit" action="" method="GET" class="m-0">
                                                <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
                                                <input type="hidden" name="section_id" value="{{$info->sectionid}}"/>
                                                <input type="hidden" name="student_id" value="{{$studentdata->id}}"/>
                                                <input type="hidden" name="editHeaderId" value="{{$studentRecord[3]}}"/>
                                                <button type="btn" class="btn btn-warning btn-xs mb-2 editButton"><i class="fa fa-edit"></i>&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                            </form>
                                            <button type="btn" class="btn btn-danger btn-xs mb-2 deleteButton" data-toggle="modal" data-target="#deleteButton{{$studentRecord[3]}}"><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                        <!-- Modal -->
                                            <div class="modal fade" id="deleteButton{{$studentRecord[3]}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">
                                                                <span class="mr-5">Student:</span>
                                                                <span class="">
                                                                    <strong>{{$studentdata->lastname}}, {{$studentdata->firstname}} {{$studentdata->middlename}} {{$studentdata->suffix}}.</strong>
                                                                </span>
                                                            </h5>
                                                                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button> --}}
                                                        </div>
                                                        <form name="deleteForm" action="" method="GET" class="m-0">
                                                            <div class="modal-body">
                                                                {{-- <small> --}}
                                                                <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
                                                                <input type="hidden" name="headerid" value="{{$studentRecord[3]}}"/>
                                                                <span class="mr-5">Classified as:</span>
                                                                <span class="ml-3">
                                                                    <strong>{{$studentRecord[0]['gradelevelname']}}</strong>
                                                                </span>
                                                                {{-- </small> --}}
                                                                <br>
                                                                {{-- <small> --}}
                                                                <span class="mr-5">School:</span>
                                                                <span class="ml-5">
                                                                    {{-- <strong>{{$studentRecord[0]['schoolname']}}</strong> --}}
                                                                </span>
                                                                {{-- </small> --}}
                                                                <br>
                                                                {{-- <small> --}}
                                                                <span class="mr-5">School Year:</span>
                                                                <span class="ml-3">
                                                                    {{-- <strong>{{$studentRecord[0]['schoolyear']}}</strong> --}}
                                                                </span>
                                                                {{-- </small> --}}
                                                                <br>
                                                                <br>
                                                                <strong class="text-danger">Are you sure you want to delete this record?</strong>
                                                                <br>
                                                                <span class="text-danger">Once you confirm, it cannot be undone.</span>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger deleteConfirm">Confirm</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <table class="table table-bordered fontSize">
                                            <thead>
                                                <tr>
                                                    <th width="30%">SUBJECT</th>
                                                    <th>1</th>
                                                    <th>2</th>
                                                    <th>3</th>
                                                    <th>4</th>
                                                    <th>FINAL RATING</th>
                                                    <th>ACTION TAKEN</th>
                                                    <th>CREDITS EARNED</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($studentRecord[1] as $subjects)
                                                    @if($subjects->subj_desc != 'General Average')
                                                        <tr>
                                                            <td>{{$subjects->subj_desc}}</td>
                                                            <td><center>{{$subjects->quarter1}}</center></td>
                                                            <td><center>{{$subjects->quarter2}}</center></td>
                                                            <td><center>{{$subjects->quarter3}}</center></td>
                                                            <td><center>{{$subjects->quarter4}}</center></td>
                                                            <td><center>{{$subjects->finalrating}}</center></td>
                                                            <td><center>{{$subjects->action}}</center></td>
                                                            <td><center>{{$subjects->credits}}</center></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td width="30%">&nbsp;</td>
                                                    <td colspan="4"><center>GENERAL AVERAGE</center></td>
                                                    <td>
                                                        @foreach ($studentRecord[1] as $subjects)
                                                            @if($subjects->subj_desc == 'General Average')
                                                            <center>{{$subjects->finalrating}}</center>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        {{-- {{$studentRecord[2]}} --}}
                                        <table class="table table-bordered fontSize">
                                            <thead>
                                                <tr>
                                                    <th width="10%"></th>
                                                    <th><center>Jun</center></th>
                                                    <th><center>Jul</center></th>
                                                    <th><center>Aug</center></th>
                                                    <th><center>Sept</center></th>
                                                    <th><center>Oct</center></th>
                                                    <th><center>Nov</center></th>
                                                    <th><center>Dec</center></th>
                                                    <th><center>Jan</center></th>
                                                    <th><center>Feb</center></th>
                                                    <th><center>Mar</center></th>
                                                    <th><center>Apr</center></th>
                                                    <th><center>Total</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td width="20%">No. of School</td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jun')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jul')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Aug')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Sep')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Oct')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Nov')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Dec')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jan')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Feb')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Mar')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Apr')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Total')
                                                            <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">No. of Days present</td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jun')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jul')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Aug')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Sep')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Oct')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Nov')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Dec')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jan')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Feb')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Mar')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Apr')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Total')
                                                            <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">No. of Days absent</td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jun')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jul')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Aug')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Sep')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Oct')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Nov')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Dec')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Jan')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Feb')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Mar')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Apr')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($studentRecord[2] as $monthlyAttendance)
                                                            @if($monthlyAttendance->month == 'Total')
                                                            <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    &nbsp;
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">TOTAL NUMBER OF UNITS EARNED:</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord[0]['numUnits']}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">TOTAL NUMBER OF YEARS IN SCHOOL TO DATE:</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord[0]['numYears']}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $uniqueId+=1;   
                    @endphp
                @endforeach
            @endif
        </div>
    </div>
    {{-- </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#addrecord').on('click',function(){
                var newRow = 1;
                $('#addcontainer').prepend(
                    '<div class="card">'+
                        '<div class="ribbon-wrapper ribbon-sm">'+
                            '<div class="ribbon bg-warning text-sm">NEW</div>'+
                    '</div>'+
                            '<button id="removeCard'+newRow+'" class="btn btn-xs btn-outline-danger removeCard col-md-1"><i class="fa fa-times"></i></button>'+
                        '<form action="/addNewForm10" method="GET">'+
                            '@csrf'+
                        '<div class="card-header">'+
                            '<div class="form-row">'+
                                '<div class="col-md-3">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>'+
                                            '</div>'+
                                            '<select id="gradelevelid" name="gradelevelid" class="form-control form-control-sm text-uppercase select" required>'+
                                                '<option value=""></option>'+
                                                '@foreach($gradelevels as $level)'+
                                                    '<option value="{{$level->id}}">{{$level->levelname}}</option>'+
                                                '@endforeach'+
                                            '</select>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-6">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">School</span>'+
                                            '</div>'+
                                            '<input id="schoolname" name="schoolname" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">School Year:</span>'+
                                            '</div>'+
                                            '<input type="text" name="schoolyear_from" class="yearpicker form-control" value="" />'+
                                            '<div class="input-group-append">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">to</span>'+
                                            '</div>'+
                                            '<input type="text" name="schoolyear_to" class="yearpicker form-control" value="" />'+
                                            // '<input id="schoolyear" name="schoolyear" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="School Year" required>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                            '<div class="col-md-12">'+
                                    '<input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>'+
                                    '<input type="hidden" name="levelid" value="{{$gradelevelid}}"/>'+
                                    '<input type="hidden" name="sectionid" value="{{$sectionid}}"/>'+
                                    '<input type="hidden" name="studentid" value="{{$studentdata->id}}"/>'+
                                    '<table class="table table-bordered uppercase fontSize">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th width="30%">SUBJECT</th>'+
                                                '<th>1</th>'+
                                                '<th>2</th>'+
                                                '<th>3</th>'+
                                                '<th>4</th>'+
                                                '<th>FINAL RATING</th>'+
                                                '<th>ACTION TAKEN</th>'+
                                                '<th>CREDITS EARNED</th>'+
                                                '<th></th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody id="tbody">'+
                                            '<tr>'+
                                                '<td class="tdInputClass"><input type="text" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number grades" class="form-control" max="100" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number grades" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number grades" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number grades" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number grades" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="text" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                                                '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                                            '</tr>'+
                                        '</tbody>'+
                                        '<tfoot>'+
                                            '<tr>'+
                                                '<td class="tdInputClass"><input type="text" class="form-control" name="entryGen[]" value="General Average" disabled/></td>'+
                                                '<td></td>'+
                                                '<td></td>'+
                                                '<td></td>'+
                                                '<td></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control grades" name="entryentryGen[]" required/></td>'+
                                                '<td></td>'+
                                                '<td></td>'+
                                                '<td></td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td colspan="8" style="border-bottom: hidden; border-left: hidden;"></td>'+
                                                '<td id="addrow"><center><i class="fa fa-plus"></i></center></td>'+
                                            '</tr>'+
                                        '</tfoot>'+
                                    '</table>'+
                                    '<table class="table table-bordered fontSize">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th width="20%"></th>'+
                                                '<th>Jun</th>'+
                                                '<th>Jul</th>'+
                                                '<th>Aug</th>'+
                                                '<th>Sept</th>'+
                                                '<th>Oct</th>'+
                                                '<th>Nov</th>'+
                                                '<th>Dec</th>'+
                                                '<th>Jan</th>'+
                                                '<th>Feb</th>'+
                                                '<th>Mar</th>'+
                                                '<th>Apr</th>'+
                                                '<th>Total</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                            '<tr>'+
                                                '<th>No. of School</th>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<th>No. of Days present</th>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<th>No. of Days absent</th>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                                '<td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>'+
                                            '</tr>'+
                                        '</tbody>'+
                                    '</table>'+
                                    '&nbsp;'+
                                    '<div class="form-row">'+
                                        '<div class="col-md-4">'+
                                            '<div class="position-relative form-group ">'+
                                                '<div class="input-group input-group-sm">'+
                                                    '<div class="input-group-prepend">'+
                                                        '<span class="input-group-text" id="inputGroupPrepend">TOTAL NUMBER OF UNITS EARNED:</span>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-2">'+
                                            '<div class="position-relative form-group ">'+
                                                '<div class="input-group input-group-sm">'+
                                                    '<input type="number" name="numUnits" class="form-control " id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="">'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-row">'+
                                        '<div class="col-md-4">'+
                                            '<div class="position-relative form-group ">'+
                                                '<div class="input-group input-group-sm">'+
                                                    '<div class="input-group-prepend">'+
                                                        '<span class="input-group-text" id="inputGroupPrepend">TOTAL NUMBER OF YEARS IN SCHOOL TO DATE:</span>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-2">'+
                                            '<div class="position-relative form-group ">'+
                                                '<div class="input-group input-group-sm">'+
                                                    '<input type="number" class="form-control" id="validationCustomUsername"  name="numYears" value="" aria-describedby="inputGroupPrepend" placeholder="">'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<button type="submit" class="btn btn-block btn-warning">Submit Form</button>'+
                                '</div>'+
                            '</div>'+
                        '</form>'+
                    '</div>'
                );
                newRow+=1;
                $('#addrow').on('click', function(){
                    var closestTable = $(this).closest("table");
                    closestTable.append(
                        '<tr>'+
                            '<td class="tdInputClass"><input type="text" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="text" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control" name="entry'+newRow+'[]" required/></td>'+
                            '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                        '</tr>'
                    );
                    newRow+=1;
                    $('.grades').on('change', function () {
                        var input = parseInt(this.value);
                        if (input < 60 )
                            $(this).val('60')
                        else if (input > 100 )
                            $(this).val('100')
                        return;
                    });
                });
                $(document).on('click', '.removebutton', function () {
                    $(this).closest('tr').remove();
                    return false;
                });
                $('.removeCard').on('click', function () {
                    $(this).closest('.card').remove();
                    return false;
                });
                // $('input').on('input', function () {
                    
                //     var value = $(this).val();
                    
                //     if ((value !== '') && (value.indexOf('.') === -1)) {
                        
                //         $(this).val(Math.max(Math.min(value, 100), -100));
                //     }
                // });
                $('.grades').on('change', function () {
                    var input = parseInt(this.value);
                    if (input < 60 )
                        $(this).val('60')
                    else if (input > 100 )
                        $(this).val('100')
                    return;
                });
                $(".yearpicker").yearpicker();
            });
            $(".editButton").on('click',function () {
                var student_id = $(this).prev().prev().attr('value');
                var header_id = $(this).prev().attr('value');
                $('form[name=formSubmit]').attr('action', '/editForm10/preview/'+student_id+'/'+header_id+'').submit();

            });
            $(".deleteConfirm").on('click',function () {

                var student_id = $('input[name=student_id]').val();
                var header_id = $('input[name=editHeaderId]').val();
                $('form[name=deleteForm]').attr('action', '/editForm10/delete/'+student_id+'/'+header_id+'').submit();

            });
            $("#generateBtn").click(function(){
                    location.reload(true);
            });
        });
            
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
        $('#selectsection').on('click', function (){
            document.getElementById('submitSelectSection').submit();
        });
    </script>
@endsection