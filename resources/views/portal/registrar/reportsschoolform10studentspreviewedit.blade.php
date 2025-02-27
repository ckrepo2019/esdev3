
@extends('registrar.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td{
            border-bottom: hidden;
        }
        input[type=text], .input-group-text, .select{
            background-color: white !important;
            border: hidden;
            border-bottom: 2px solid #ddd;
            font-size: 12px !important;
        }
        .input-group-text{
            border-bottom: hidden;
        }
        .fontSize{
            font-size: 12px;
        }
        .container{
            overflow-x: scroll !important;
        }
        table{
            width: 100%;
        }
        .inputClass{
            width: 100%;
        }
        .tdInputClass{
            padding: 0px !important;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }
    </style>
    @php
        $male = 1;
        $female = 1;
    @endphp
    <form name="backRecord" action="/reports_schoolform10/students_preview/{{$url->syid}}/{{$url->sectionid}}/{{$url->glevelid}}/{{$student_id}}">
        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
    </form>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-10">
                <h4>Learner's Permanent Academic Record</h4>
                <em>(Formerly Form 137)</em>
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
                <li class="breadcrumb-item"><a href="/reports_schoolform10/students/{{$url->syid}}/{{$url->sectionid}}/{{$url->glevelid}}/{{$url->teacherid}}">{{$url->levelname}} - {{$url->sectionname}}</a></li>
                <li class="breadcrumb-item"><a id="backRecord">Learner's Permanent Academic Record</a></li>
                <li class="breadcrumb-item active">Edit this record</li>
            </ol>
        </div>
        <div class="col-md-12">
            @if((string)Session::get('newData') == true)
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i>{{ (string)Session::get('newData') }}</h5>
                    
                </div>
            @endif
        </div>
        &nbsp;
        <div class="col-md-12">
            <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
            <form action="/editForm10/edit/{{$student_id}}/{{$header_id}}" method="GET">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title col-md-12" >
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>
                                            </div>
                                            <select id="gradelevelid" name="gradelevelid" class="form-control form-control-sm text-uppercase select">
                                                @foreach ($gradelevels as $gradelevel)
                                                    <option value="{{$gradelevel->id}}" {{$header[0]->levelname==$gradelevel->levelname ? 'selected' : ''}}>{{$gradelevel->levelname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">School</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolname"  value="{{$header[0]->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            @php
                                                $schoolyear = explode("-",$header[0]->schoolyear)   ;
                                                $fromschoolyear = $schoolyear[0];
                                                $toschoolyear = $schoolyear[1];
                                            @endphp
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">School Year:</span>
                                            </div>
                                            <input type="text" name="schoolyear_from" class="yearpicker form-control" value="{{$fromschoolyear}}" />
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="inputGroupPrepend">to</span>
                                            </div>
                                            <input type="text" name="schoolyear_to" class="yearpicker form-control" value="{{$toschoolyear}}" />
                                            <input type="hidden" name="editHeaderId" class="" value="{{$header[0]->id}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <table class="table table-bordered fontSize">
                                <thead>
                                    <tr>
                                        <th width="30%">SUBJECT</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>FINAL RATING</th>
                                        <th width="15%">ACTION TAKEN</th>
                                        <th colspan="2">CREDITS EARNED</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $oldSubj = 0;   
                                    @endphp
                                    @foreach ($subjects as $subject)
                                        @if($subject->subj_desc != 'General Average')
                                            <tr>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->id}}"/>
                                                    <input type="text" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->subj_desc}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->quarter1}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->quarter2}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->quarter3}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->quarter4}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->finalrating}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="text" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->action}}"/>
                                                </td>
                                                <td class="tdInputClass" colspan="2">
                                                    <input type="number" class="form-control" name="old{{$oldSubj}}[]" value="{{$subject->credits}}"/>
                                                </td>
                                            </tr>
                                            
                                        @php
                                            $oldSubj+=1;   
                                        @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @foreach ($subjects as $subject)
                                        @if($subject->subj_desc == 'General Average')
                                            <tr>
                                                <td width="30%" class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->id}}"/>
                                                    <input type="text" class="form-control" name="genAve[]" value="{{$subject->subj_desc}}" disabled/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->quarter1}}" disabled/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->quarter2}}" disabled/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->quarter3}}" disabled/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->quarter4}}" disabled/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="genAve[]" value="{{$subject->finalrating}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->action}}" disabled/>
                                                </td>
                                                <td colspan="2" class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->credits}}" disabled/>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="8"></td>
                                        <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <table class="table table-bordered fontSize">
                                <thead>
                                    <tr>
                                        <th width="10%"></th>
                                        <th>Jun</th>
                                        <th>Jul</th>
                                        <th>Aug</th>
                                        <th>Sept</th>
                                        <th>Oct</th>
                                        <th>Nov</th>
                                        <th>Dec</th>
                                        <th>Jan</th>
                                        <th>Feb</th>
                                        <th>Mar</th>
                                        <th>Apr</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%">No. of School</td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jun')
                                                    <input type="hidden" class="form-control" name="days[]" value="{{$monthlyAttendance->id}}"/>
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jul')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Aug')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Sep')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Oct')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Nov')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Dec')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jan')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Feb')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Mar')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Apr')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'May')
                                                    <input type="number" class="form-control" name="days[]" value="{{$monthlyAttendance->numofschooldays}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">No. of Days present</td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jun')
                                                    <input type="hidden" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->id}}"/>
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jul')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Aug')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Sep')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Oct')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Nov')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Dec')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jan')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Feb')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Mar')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Apr')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'May')
                                                    <input type="number" class="form-control" name="dayspresent[]" value="{{$monthlyAttendance->numofdayspresent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">No. of Days absent</td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jun')
                                                    <input type="hidden" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->id}}"/>
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jul')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Aug')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Sep')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Oct')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Nov')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Dec')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Jan')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Feb')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Mar')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'Apr')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
                                                @else
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="tdInputClass">
                                            @foreach ($attendance as $monthlyAttendance)
                                                @if($monthlyAttendance->month == 'May')
                                                    <input type="number" class="form-control" name="daysabsent[]" value="{{$monthlyAttendance->numofdaysabsent}}"/>
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
                                        <input type="text" name="numUnits" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$header[0]->numUnits}}" aria-describedby="inputGroupPrepend" placeholder="" >
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
                                        <input type="text" name="numYears" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$header[0]->numYears}}" aria-describedby="inputGroupPrepend" placeholder="" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="academicprogram" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$academicprogram}}" aria-describedby="inputGroupPrepend" placeholder="" >
                        @if($header[0]->operation == 1)
                            <button type="submit" class="btn btn-block btn-warning">Update</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <script>
        $( document ).ready(function() {
            var newSubj = 0;
            $('#addrow').on('click', function(){
                var closestTable = $(this).closest("table");
                closestTable.append(
                    '<tr>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                    '</tr>'
                );
                newSubj+=1;
            });
            $(document).on('click', '.removebutton', function () {
                $(this).closest('tr').remove();
                return false;
            });
            $(".yearpicker").yearpicker({
                    endYear: 2030
                });
            $('#backRecord').on('click', function(){
                $('form[name=backRecord]').submit();
            })
        });
    </script>
@endsection
