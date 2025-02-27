
@extends('registrar.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td                                          { border-bottom: hidden; }
        input[type=text], .input-group-text, .select{ background-color: white !important; border: hidden; border-bottom: 2px solid #ddd; font-size: 12px !important; }
        .input-group-text                           { border-bottom: hidden; }
        .fontSize                                   { font-size: 12px; }
        .fontSize td, .fontSize th{
            border: 1px solid black;
        }
        .container                                  { overflow-x: scroll !important; }
        table                                       { width: 100%; }
        .inputClass                                 { width: 100%; }
        .tdInputClass                               { padding: 0px !important; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button            { -webkit-appearance: none; margin: 0; }
        
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>Learner's Permanent Academic Record</strong>
                        {{-- {{$studentdata->id}} --}}
                    </h3>
                    <br>
                    <small><em>(Formerly Form 137)</em></small>
                    {{-- @if(isset($gradelevelid)) --}}
                    <form action="/seniorhigh/dashboard" target="_blank" method="get" class="m-0 p-0">
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                        <input type="hidden" value="print" name="action"/>
                        {{-- <input type="hidden" value="{{$schoolyear}}" name="schoolyear"/>
                        <input type="hidden" value="{{$sectionid}}" name="sectionid"/>
                        <input type="hidden" value="{{$gradelevelid}}" name="gradelevelid"/> --}}
                        <input type="hidden" value="{{$studentdata->id}}" name="studid"/>
                        <button type="submit" class="btn btn-primary btn-sm text-white float-right">
                            <i class="fa fa-upload"></i>
                        Print
                        </button>
                    </form>
                    {{-- @endif --}}
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
                </div>
                <div class="card-body">
                    <form action="/seniorhigh/eligibility" method="get">
                        @csrf
                        <div class="bg-gray"><center>ELIGIBILITY FOR SHS ENROLMENT</center></div>
                        
                        {{-- <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                        <input type="hidden" value="{{$studentdata->id}}" name="studentid"/>
                        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
                         --}}
                        <div style="border: 1px solid;">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <div class="icheck-primary d-inline">
                                                    @if(isset($eligibility))
                                                    @if(count($eligibility)!=0)
                                                        @if($eligibility[0]->completer == 'hs')
                                                            <input type="radio" id="radioPrimary1" name="completer" value="hs" checked>
                                                        @else
                                                            <input type="radio" id="radioPrimary1" name="completer" value="hs">
                                                        @endif
                                                    @else
                                                        <input type="radio" id="radioPrimary1" name="completer" value="hs" checked>
                                                    @endif
                                                    @endif
                                                <label for="radioPrimary1">
                                                </label>
                                                </div>
                                            </div>
                                            {{-- <span class="input-group-text" id="inputGroupPrepend"></span> --}}
                                            <input type="text" class="form-control text-uppercase" name="hs_completer" id="validationCustomUsername"  value="High School Completer" aria-describedby="inputGroupPrepend" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <div class="icheck-primary d-inline">
                                                    @if(isset($eligibility))
                                                    @if(count($eligibility)!=0)
                                                        @if($eligibility[0]->completer == 'jhs')
                                                            <input type="radio" id="radioPrimary2" name="completer" value="jhs" checked>
                                                        @else
                                                            <input type="radio" id="radioPrimary2" name="completer" value="jhs">
                                                        @endif
                                                    @else
                                                        <input type="radio" id="radioPrimary2" name="completer" value="jhs">
                                                    @endif
                                                    @endif
                                                <label for="radioPrimary2">
                                                </label>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" name="jhs_completer" id="validationCustomUsername"  value="Junior High School Completer" aria-describedby="inputGroupPrepend" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Gen. Ave:</span>
                                            </div>
                                                    @if(isset($eligibility))
                                                    @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase" name="gen_ave" id="validationCustomUsername"  value="{{$eligibility[0]->gen_ave}}" aria-describedby="inputGroupPrepend" required>
                                            @else
                                                <input type="text" class="form-control text-uppercase" name="gen_ave" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend" required>
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">&nbsp;</div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Date of Graduation/Competion (MM/DD/YYYY):</span>
                                            </div>
                                                    @if(isset($eligibility))
                                                    @if(count($eligibility)!=0)
                                                <input type="text" id="currentDate" class="currentDate" style="text-align:center;" name="graduation_date" width="100%" value="{{$eligibility[0]->completion_date}}"/>
                                            @else
                                                <input type="text" id="currentDate" class="currentDate" style="text-align:center;" name="graduation_date" width="100%" value=""/>
                                            @endif
                                            @endif
                                            {{-- <input type="number" class="form-control text-uppercase" name="jhs_graduationdate" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend"> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">NAME OF SCHOOL:</span>
                                            </div>
                                            @if(isset($eligibility))
                                            @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase" name="schoolname" id="validationCustomUsername"  value="{{$eligibility[0]->schoolname}}" aria-describedby="inputGroupPrepend">
                                            @else
                                                <input type="text" class="form-control text-uppercase" name="schoolname" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend">
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">ADDRESS OF SCHOOL:</span>
                                            </div>
                                            @if(isset($eligibility))
                                            @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase" name="schooladdress" id="validationCustomUsername"  value="{{$eligibility[0]->schooladdress}}" aria-describedby="inputGroupPrepend">
                                            @else
                                                <input type="text" class="form-control text-uppercase" name="schooladdress" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend">
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <small>Other Credential Presented</small>
                        <div class="pl-5" style="width:100%;">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <div class="icheck-primary d-inline">
                                                    @if(isset($eligibility))
                                                    @if(count($eligibility)!=0)
                                                        @if($eligibility[0]->passer == 'pept')
                                                            <input type="radio" id="passer1" name="passer" value="pept" checked>
                                                        @else
                                                            <input type="radio" id="passer1" name="passer" value="pept">
                                                        @endif
                                                    @else
                                                        <input type="radio" id="passer1" name="passer" value="pept">
                                                    @endif
                                                    @endif
                                                <label for="passer1">
                                                </label>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" name="pept_passer" id="validationCustomUsername"  value="PEPT Passer" aria-describedby="inputGroupPrepend" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <div class="icheck-primary d-inline">
                                                    @if(isset($eligibility))
                                                    @if(count($eligibility)!=0)
                                                        @if($eligibility[0]->passer == 'als') 
                                                            <input type="radio" id="passer2" name="passer" value="als" checked>
                                                        @else
                                                            <input type="radio" id="passer2" name="passer" value="als">
                                                        @endif
                                                    @else
                                                        <input type="radio" id="passer2" name="passer" value="als">
                                                    @endif
                                                    @endif
                                                <label for="passer2">
                                                </label>
                                                </div>
                                                {{-- <span class="input-group-text" id="inputGroupPrepend">ALS A & E Passer</span> --}}
                                            </div>
                                            <input type="text" class="form-control text-uppercase" name="als_passer" id="validationCustomUsername"  value="ALS A & E Passer" aria-describedby="inputGroupPrepend" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">RATING:</span>
                                            </div>
                                            @if(isset($eligibility))
                                            @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase" name="rating" id="validationCustomUsername"  value="{{$eligibility[0]->rating}}" aria-describedby="inputGroupPrepend">
                                            @else
                                                <input type="text" class="form-control text-uppercase" name="rating" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend">
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Date of Examination/Assessment (mm/dd/yyyy):</span>
                                            </div>
                                            @if(isset($eligibility))
                                            @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase currentDate" id="examDate" name="exam_date" id="validationCustomUsername"  value="{{$eligibility[0]->exam_date}}" aria-describedby="inputGroupPrepend">
                                            @else
                                                <input type="text" class="form-control text-uppercase currentDate" id="examDate" name="exam_date" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend">
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Name of Testing Center:</span>
                                            </div>
                                            @if(isset($eligibility))
                                            @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase" name="center_name" id="validationCustomUsername"  value="{{$eligibility[0]->learning_center_name}}" aria-describedby="inputGroupPrepend">
                                            @else
                                                <input type="text" class="form-control text-uppercase" name="center_name" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend">
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Address of Testing Center:</span>
                                            </div>
                                            @if(isset($eligibility))
                                            @if(count($eligibility)!=0)
                                                <input type="text" class="form-control text-uppercase" name="center_address" id="validationCustomUsername"  value="{{$eligibility[0]->learning_center_address}}" aria-describedby="inputGroupPrepend">
                                            @else
                                                <input type="text" class="form-control text-uppercase" name="center_address" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend">
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($eligibility))
                        <input type="hidden" name="studid" id="validationCustomUsername"  value="{{$studentdata->id}}" >
                        @if(count($eligibility)==0)
                            <button type="submit" class="btn btn-success btn-sm text-white float-right">
                                <i class="fa fa-upload"></i>
                            Submit
                            </button>
                        @else
                            <button type="submit" class="btn btn-warning btn-sm text-white float-right">
                                <i class="fa fa-upload"></i>
                            Update
                            </button>
                        @endif
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            {{-- <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="/reports_schoolform10/students/{{$schoolyear}}/{{$info->sectionid}}/{{$info->glevelid}}/{{$info->teacherid}}">{{$info->levelname}} - {{$info->sectionname}}</a></li>
                <li class="breadcrumb-item active">Learner's Permanent Academic Record</li>
            </ol> --}}
        </div>
        <div class="col-md-12">
            @if($completed != 1)
            <button id="addrecord" type="button" class="btn btn-warning btn-sm float-left"><i class="fa fa-plus"></i></button>
            @endif
            {{-- <button id="generateBtn" type="button" class="btn btn-primary btn-sm float-left ml-2"> <small><i class="fa fa-sync"></i>&nbsp;Generate</small></button> --}}
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
            @if(isset($records))
                @php
                    $uniqueId = 1;   
                @endphp
                @foreach ($records as $studentRecord)
                    <div id="accordion">
                        <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title col-md-12" >
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$uniqueId}}">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>
                                                        </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord->gradedetails->levelname}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">&nbsp;</div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">Schoolyear:</span>
                                                        </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord->gradedetails->sydesc}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
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
                                        @php
                                            $summer = 0;   
                                        @endphp
                                        <table class="table table-bordered fontSize" >
                                            <thead>
                                            <tr>
                                                <th colspan="6">1st Semester</th>
                                            </tr>
                                            <thead class="bg-gray">
                                                <tr>
                                                    <th rowspan="2">Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                    <th rowspan="2" width="30%">SUBJECT</th>
                                                    <th colspan="2">QUARTER</th>
                                                    <th rowspan="2">SEMI FINAL GRADE</th>
                                                    <th rowspan="2">ACTION TAKEN</th>
                                                </tr>
                                                <tr>
                                                    <th>1</th>
                                                    <th>2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($studentRecord->firstsem as $subjects)
                                                    @if((($subjects->quarter1 + $subjects->quarter2)/2)<75)
                                                        @php
                                                            $summer+=1;   
                                                        @endphp
                                                        <tr style="background-color:#ffb2b9">
                                                    @else
                                                        <tr>
                                                    @endif
                                                            <td>{{$subjects->subjcode}}</td>
                                                            <td>{{$subjects->subjtitle}}</td>
                                                            <td><center>{{($subjects->quarter1)}}</center></td>
                                                            <td><center>{{($subjects->quarter2)}}</center></td>
                                                            <td><center>{{($subjects->quarter1 + $subjects->quarter2)/2}}</center></td>
                                                            <td><center></center></td>
                                                        </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td width="30%">&nbsp;</td>
                                                    <td colspan="2"><center>GENERAL AVERAGE</center></td>
                                                    <td>
                                                        {{-- @foreach ($studentRecord['firstsem'][0]['grades'] as $subjects)
                                                            @if($subjects->subj_desc == 'General Average')
                                                            <center>{{$subjects->finalrating}}</center>
                                                            @endif
                                                        @endforeach --}}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <br>
                                        <br>
                                        @if(count($studentRecord->summer)!=0)
                                        <table class="table table-bordered fontSize" style="text-align: center;">
                                            <thead class="bg-gray">
                                                <tr>
                                                    <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                    <th width="30%">SUBJECTS</th>
                                                    <th>SEMI FINAL GRADE</th>
                                                    <th>REMEDIAL CLASS MARK</th>
                                                    <th>RECOMPUTED FINAL GRADE</th>
                                                    <th>ACTION TAKEN</th>
                                                </tr>
                                            </thead>
                                            <tbody style="background-color:#ffdd99">
                                                @foreach ($studentRecord->summer as $summersubjects)
                                                    @if($summersubjects->semid == '1')
                                                    <tr>
                                                        <td>{{$summersubjects->subjcode}}</td>
                                                        <td>{{$summersubjects->subjtitle}}</td>
                                                        <td>{{$summersubjects->qg}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                        @if(count($studentRecord->secondsem)!=0)
                                        <br>
                                        <br>
                                        <br>
                                        <table class="table table-bordered fontSize" >
                                            <thead >
                                                <tr>
                                                    <th colspan="6">2nd Semester</th>
                                                </tr>
                                            </thead>
                                            <thead class="bg-gray">
                                                <tr>
                                                    <th rowspan="2">Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                    <th rowspan="2" width="30%">SUBJECT</th>
                                                    <th colspan="2">QUARTER</th>
                                                    <th rowspan="2">SEMI FINAL GRADE</th>
                                                    <th rowspan="2">ACTION TAKEN</th>
                                                </tr>
                                                <tr>
                                                    <th>3</th>
                                                    <th>4</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($studentRecord->secondsem as $subjects)
                                                    @if((($subjects->quarter1 + $subjects->quarter2)/2)<75)
                                                        @php
                                                            $summer+=1;   
                                                        @endphp
                                                        <tr style="background-color:#ffb2b9">
                                                    @else
                                                        <tr>
                                                    @endif
                                                            <td>{{$subjects->subjcode}}</td>
                                                            <td>{{$subjects->subjtitle}}</td>
                                                            <td><center>{{($subjects->quarter1)}}</center></td>
                                                            <td><center>{{($subjects->quarter2)}}</center></td>
                                                            <td><center>{{($subjects->quarter1 + $subjects->quarter2)/2}}</center></td>
                                                            <td><center></center></td>
                                                        </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td width="30%">&nbsp;</td>
                                                    <td colspan="2"><center>GENERAL AVERAGE</center></td>
                                                    <td>
                                                        {{-- @foreach ($studentRecord['firstsem'][0]['grades'] as $subjects)
                                                            @if($subjects->subj_desc == 'General Average')
                                                            <center>{{$subjects->finalrating}}</center>
                                                            @endif
                                                        @endforeach --}}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @endif
                                        <br>
                                        <br>
                                        <br>
                                        @if(count($studentRecord->summer)!=0)
                                            <table class="table table-bordered fontSize" style="text-align: center;">
                                                <thead class="bg-gray">
                                                    <tr>
                                                        <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                        <th width="30%">SUBJECTS</th>
                                                        <th>SEMI FINAL GRADE</th>
                                                        <th>REMEDIAL CLASS MARK</th>
                                                        <th>RECOMPUTED FINAL GRADE</th>
                                                        <th>ACTION TAKEN</th>
                                                    </tr>
                                                </thead>
                                                <thead class="bg-gray">
                                                    <tr>
                                                        <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                        <th width="30%">SUBJECTS</th>
                                                        <th>SEMI FINAL GRADE</th>
                                                        <th>REMEDIAL CLASS MARK</th>
                                                        <th>RECOMPUTED FINAL GRADE</th>
                                                        <th>ACTION TAKEN</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="background-color:#ffdd99">
                                                    {{-- @if(count($studentRecord['firstsum'][0]['grades'])!=0) --}}
                                                    @foreach ($studentRecord->summer as $summersubjects)
                                                        @if($summersubjects->semid == '2')
                                                        <tr>
                                                            <td>{{$summersubjects->subjcode}}</td>
                                                            <td>{{$summersubjects->subjtitle}}</td>
                                                            <td>{{$summersubjects->qg}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                    {{-- @endif --}}
                                                </tbody>
                                            </table>
                                        @endif
                                        {{-- @if($summer>0 && count($studentRecord->summer)==0 && count($failingsubjects)!=0 && $studentRecord->gradedetails->levelid == $failedlevelid->levelid)
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#applySummer{{$studentRecord->gradedetails->levelid}}">Apply for special class</button>
                                            <div class="modal fade" id="applySummer{{$studentRecord->gradedetails->levelid}}" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form action="/seniorhigh/applysummer" method="get" >
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">{{$studentRecord->gradedetails->levelname}}</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-bordered fontSize" >
                                                                    <thead class="bg-gray">
                                                                        <tr>
                                                                            <th width="20%">Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                                            <th  >SUBJECT</th>
                                                                            <th >SEMI FINAL GRADE</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($failingsubjects as $failedsubjects)
                                                                                <tr style="background-color:#ffb2b9">
                                                                                    <td>
                                                                                        <input type="hidden" value="{{$failedsubjects->id}}" name="subjid[]" readonly/>
                                                                                        {{$failedsubjects->subjcode}}
                                                                                    </td>
                                                                                    <td>{{$failedsubjects->subjtitle}}</td>
                                                                                    <td><center>{{$failedsubjects->grade}}</center></td>
                                                                                </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <input type="hidden" value="{{$failedlevelid->levelid}}" name="levelid" readonly/>
                                                                <input type="hidden" value="{{$studentdata->id}}" name="studid" readonly/>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Apply for special class</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif --}}
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
            @php
                $uniqueId = 111;   
            @endphp
            @foreach ($torrecords as $torrecord)
                <div id="accordion">
                    <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title col-md-12" >
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$uniqueId}}">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>
                                                    </div>
                                                <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$torrecord->levelname->levelname}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">&nbsp;</div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">Schoolyear:</span>
                                                    </div>
                                                <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$torrecord->schoolyear->schoolyear}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
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
                                    <form action="/senior/editform10/edit" method="GET" class="m-0">
                                        <input type="hidden" name="student_id" value="{{$studentdata->id}}"/>
                                        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
                                        <input type="hidden" name="recordid" value="{{$torrecord->schoolyear->id}}"/>
                                        <input type="hidden" name="semester" value="1"/>
                                        <button type="submit" class="btn btn-warning btn-xs mb-2 editButton"><i class="fa fa-edit"></i>&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                    </form>
                                    <table class="table table-bordered fontSize" >
                                        <thead>
                                        <tr>
                                            <th colspan="6">1st Semester</th>
                                        </tr>
                                        <thead class="bg-gray">
                                            <tr>
                                                <th rowspan="2">Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                <th rowspan="2" width="30%">SUBJECT</th>
                                                <th colspan="2">QUARTER</th>
                                                <th rowspan="2">SEMI FINAL GRADE</th>
                                                <th rowspan="2">ACTION TAKEN</th>
                                            </tr>
                                            <tr>
                                                <th>1</th>
                                                <th>2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($torrecord->firstsem[0]->grades as $subjects)
                                                @if($subjects->semester == 1)
                                                    @if(($subjects->quarter1 + $subjects->quarter2)<75)
                                                        @php
                                                            $summer+=1;   
                                                        @endphp
                                                        <tr style="background-color:#ffb2b9">
                                                    @else
                                                        <tr>
                                                    @endif
                                                            <td>{{$subjects->core}}</td>
                                                            <td>{{$subjects->subj_desc}}</td>
                                                            <td><center>{{$subjects->quarter1}}</center></td>
                                                            <td><center>{{$subjects->quarter2}}</center></td>
                                                            <td><center>{{$subjects->finalrating}}</center></td>
                                                            <td><center>{{$subjects->action}}</center></td>
                                                        </tr>
                                                @endif
                                            @endforeach
                                            @if(count($torrecord->firstsem[0]->generalaverage)>0)
                                                <tr>
                                                    <td width="30%">&nbsp;</td>
                                                    <td colspan="3"><center>GENERAL AVERAGE</center></td>
                                                    <td>
                                                        {{$torrecord->firstsem[0]->generalaverage[0]->genave}}
                                                    </td>
                                                    <td>
                                                        {{-- @if($avggrade == 0)
                                                        @else
                                                        @if(($avggrade/count($studentRecord->grades))>75)
                                                        <center>PASSED</center>
                                                        @else
                                                        <center>FAILED</center>
                                                        @endif
                                                        @endif --}}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <br>
                                    <form action="/senior/editform10/edit" method="GET" class="m-0">
                                        <input type="hidden" name="student_id" value="{{$studentdata->id}}"/>
                                        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
                                        <input type="hidden" name="recordid" value="{{$torrecord->schoolyear->id}}"/>
                                        <input type="hidden" name="semester" value="2"/>
                                        <button type="submit" class="btn btn-warning btn-xs mb-2 editButton"><i class="fa fa-edit"></i>&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                    </form>
                                    <table class="table table-bordered fontSize" >
                                        <thead >
                                            <tr>
                                                <th colspan="6">2nd Semester</th>
                                            </tr>
                                        </thead>
                                        <thead class="bg-gray">
                                            <tr>
                                                <th rowspan="2">Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                                <th rowspan="2" width="30%">SUBJECT</th>
                                                <th colspan="2">QUARTER</th>
                                                <th rowspan="2">SEMI FINAL GRADE</th>
                                                <th rowspan="2">ACTION TAKEN</th>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($torrecord->secondsem)>0)
                                                @foreach ($torrecord->secondsem[0]->grades as $subjects)
                                                    @if($subjects->semester == 2)
                                                        @if(($subjects->quarter1 + $subjects->quarter2)<75)
                                                            <tr style="background-color:#ffb2b9">
                                                        @else
                                                            <tr>
                                                        @endif
                                                                <td>{{$subjects->core}}</td>
                                                                <td>{{$subjects->subj_desc}}</td>
                                                                <td><center>{{$subjects->quarter1}}</center></td>
                                                                <td><center>{{$subjects->quarter2}}</center></td>
                                                                <td><center>{{$subjects->finalrating}}</center></td>
                                                                <td><center>{{$subjects->action}}</center></td>
                                                            </tr>
                                                    @endif
                                                @endforeach
                                                @if(count($torrecord->secondsem[0]->generalaverage)>0)
                                                    <tr>
                                                        <td width="30%">&nbsp;</td>
                                                        <td colspan="3"><center>GENERAL AVERAGE</center></td>
                                                        <td>
                                                            {{$torrecord->secondsem[0]->generalaverage[0]->genave}}
                                                        </td>
                                                        <td>
                                                            {{-- @if($avggrade == 0)
                                                            @else
                                                            @if(($avggrade/count($studentRecord->grades))>75)
                                                            <center>PASSED</center>
                                                            @else
                                                            <center>FAILED</center>
                                                            @endif
                                                            @endif --}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $uniqueId+=1;   
                @endphp
            @endforeach
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
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        $(document).ready(function() {
            console.log($('input[name=completer]').val());
            $('#currentDate').datepicker({
            format: 'yyyy-mm-dd'
            });
            $('#examDate').datepicker({
            format: 'yyyy-mm-dd'
            });
            $('#addrecord').on('click',function(){
                var newRow = 1;
                $('#addcontainer').prepend(
                    '<div class="card" >'+
                        '<div class="ribbon-wrapper ribbon-sm">'+
                            '<div class="ribbon bg-warning text-sm">NEW</div>'+
                        '</div>'+
                        '<button id="removeCard'+newRow+'" class="btn btn-xs btn-outline-danger removeCard col-md-1"><i class="fa fa-times"></i></button>'+
                        '<form action="/senior/addform10" method="GET">'+
                            '@csrf'+
                        '<div class="card-header" id="header">'+
                            // '<div class="row">'+
                            //     '<div role="group" class="btn-group-sm btn-group float-left">'+
                            //         '<button name="semester" value="regular" class="active btn btn-success">Regular</button>'+
                            //         '<button name="semester" value="summer" class="btn btn-success">Summer</button>'+
                            //     '</div>'+
                            // '</div>'+
                            '<div id="headerContainer">'+
                            '<div class="form-row">'+
                                '<div class="col-md-6">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">SCHOOL</span>'+
                                            '</div>'+
                                            '<input id="school" name="school" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(SCHOOL)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-6">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">SCHOOL ID</span>'+
                                            '</div>'+
                                            '<input id="schoolid" name="schoolid" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(SCHOOL ID)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-row">'+
                                '<div class="col-md-4">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">DISTRICT</span>'+
                                            '</div>'+
                                            '<input id="district" name="district" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(DISTRICT)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-4">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">DIVISION</span>'+
                                            '</div>'+
                                            '<input id="division" name="division" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(DIVISION)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-4">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">REGION</span>'+
                                            '</div>'+
                                            '<input id="region" name="region" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(REGION)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-row">'+
                                '<div class="col-md-3">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>'+
                                            '</div>'+
                                            '<select id="gradelevelid" name="gradelevelid" class="form-control form-control-sm text-uppercase select" required>'+
                                                '<option></option>'+
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
                                                '<span class="input-group-text" id="inputGroupPrepend">SECTION</span>'+
                                            '</div>'+
                                            '<input id="section" name="section" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(Section)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">School Year:</span>'+
                                            '</div>'+
                                            '<input type="text" name="schoolyear_from" class="yearpicker form-control" value="" required/>'+
                                            '<div class="input-group-append">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">to</span>'+
                                            '</div>'+
                                            '<input type="text" name="schoolyear_to" class="yearpicker form-control" value="" required/>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-row">'+
                                '<div class="col-md-6">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">TRACK</span>'+
                                            '</div>'+
                                            '<input id="division" name="track" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(TRACK)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-6">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">STRAND</span>'+
                                            '</div>'+
                                            '<input id="region" name="strand" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(STRAND)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-row">'+
                                '<div class="col-md-12">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">Name of Adviser/Teacher</span>'+
                                            '</div>'+
                                            '<input id="teacher" name="teacher" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(Name of Adviser/Teacher)" />'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-row">'+
                                '<div class="col-md-3">'+
                                    '<div class="position-relative form-group ">'+
                                        '<div class="input-group input-group-sm">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="inputGroupPrepend">SEMESTER:</span>'+
                                            '</div>'+
                                            '<select id="semesterSummer" name="semester" class="form-control form-control-sm text-uppercase select" required>'+
                                                '<option value=""></option>'+
                                                '<option value="first">1st</option>'+
                                                '<option value="second">2nd</option>'+
                                            '</select>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '</div>'+
                        '<div class="card-body" id="semesterContainer">'+
                            '<div class="col-md-12" class="semesterContainer">'+
                                '<input type="hidden" name="levelid" value=""/>'+
                                '<input type="hidden" name="sectionid" value=""/>'+
                                '<input type="hidden" name="studentid" value="{{$studentdata->id}}"/>'+
                                '<input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>'+
                                // '<h3><center>First Semester</center></h3>'+
                                '<table class="table table-bordered uppercase fontSize normal" style="text-align:center;">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th rowspan="2"width="">Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>'+
                                            '<th rowspan="2"width="30%">SUBJECT</th>'+
                                            '<th colspan="2">QUARTER</th>'+
                                            '<th rowspan="2">SEMI FINAL GRADE</th>'+
                                            '<th rowspan="2">ACTION TAKEN</th>'+
                                            '<th></th>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<th class="semesterQa">1</th>'+
                                            '<th class="semesterQb">2</th>'+
                                            '<th></th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody id="tbody">'+
                                        '<tr>'+
                                            '<td class="tdInputClass"><input type="text" class="form-control" name="first'+newRow+'[]" required/></td>'+
                                            '<td class="tdInputClass"><input type="text" class="form-control" name="first'+newRow+'[]" required/></td>'+
                                            '<td class="tdInputClass"><input type="number grades" class="form-control" max="100" name="first'+newRow+'[]" required/></td>'+
                                            '<td class="tdInputClass"><input type="number grades" class="form-control" name="first'+newRow+'[]" required/></td>'+
                                            '<td class="tdInputClass"><input type="number grades" class="form-control" name="first'+newRow+'[]" required/></td>'+
                                            '<td class="tdInputClass"><input type="text" class="form-control" name="first'+newRow+'[]" required/></td>'+
                                            '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                                        '</tr>'+
                                    '</tbody>'+
                                    '<tfoot>'+
                                        '<tr>'+
                                            '<td></td>'+
                                            '<td class="tdInputClass"><input type="hidden" class="form-control" name="firstGen[]" value="first" disabled/><input type="text" class="form-control" name="firstGen[]" value="General Average" disabled/></td>'+
                                            '<td></td>'+
                                            '<td></td>'+
                                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="firstGen[]" required/></td>'+
                                            '<td></td>'+
                                            '<td></td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td colspan="6" style="border-bottom: hidden; border-left: hidden;"></td>'+
                                            '<td id="addrow1"><center><i class="fa fa-plus"></i></center></td>'+
                                        '</tr>'+
                                    '</tfoot>'+
                                '</table>'+
                                '<button type="submit" class="btn btn-block btn-warning">Submit Form</button>'+
                            '</div>'+
                        '</div>'+
                    '</form>'+
                '</div>'
                );
                newRow+=1;
                $('#addrow1').on('click', function(){
                    var closestTable = $(this).closest("table");
                    closestTable.append(
                        '<tr>'+
                            '<td class="tdInputClass"><input type="text" class="form-control" name="first'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="text" class="form-control" name="first'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="first'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="first'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="number" class="form-control grades" name="first'+newRow+'[]" required/></td>'+
                            '<td class="tdInputClass"><input type="text" class="form-control" name="first'+newRow+'[]" required/></td>'+
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
                $(".yearpicker").yearpicker();
                $(document).on('click', '.removebutton', function () {
                    $(this).closest('tr').remove();
                    return false;
                });
                $('.removeCard').on('click', function () {
                    $(this).closest('.card').remove();
                    return false;
                });
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
            // $(".deleteConfirm").on('click',function () {

            //     var student_id = $('input[name=student_id]').val();
            //     var header_id = $('input[name=editHeaderId]').val();
            //     $('form[name=deleteForm]').attr('action', '/editForm10/delete/'+student_id+'/'+header_id+'').submit();

            // });
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

                                        

                                        
                                        