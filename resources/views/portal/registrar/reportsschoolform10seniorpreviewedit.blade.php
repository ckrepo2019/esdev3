
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
    <form name="backStudents" action="/reports_schoolform10/students/{{$url->syid}}/{{$url->sectionid}}/{{$url->glevelid}}/{{$url->teacherid}}" method="GET">
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="{{$selectedsection}}" name="selectedsection"/>
        <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
        <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
    </form>
    <form name="backRecord" action="/reports_schoolform10/students_preview/{{$url->syid}}/{{$url->sectionid}}/{{$url->glevelid}}/{{$student_id}}" method="GET">
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="{{$selectedsection}}" name="selectedsection"/>
        <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
        <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
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
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item text-info"><a id="backStudents">{{$url->levelname}} - {{$url->sectionname}}</a></li>
                <li class="breadcrumb-item text-info"><a id="backRecord">Learner's Permanent Academic Record</a></li>
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
            {{-- {{$header_id}} --}}
            <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
            <form action="/editForm10/edit/{{$student_id}}/{{$header_id}}" method="GET">
                <div class="card card-primary">
                    <div class="card-body">
                        @if($semester == 'firstsemester')
                        <h3><strong>First Semester</strong></h3>
                        <h4 class="card-title col-md-12" >
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolname"  value="{{$header[0]->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL ID:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolid"  value="{{$header[0]->schoolid}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-row">
                            </div> --}}
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
                                                <span class="input-group-text" id="inputGroupPrepend">SECTION</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="section"  value="{{$header[0]->section}}" aria-describedby="inputGroupPrepend" placeholder="(Section)">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Track:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="track"  value="{{$header[0]->track}}" aria-describedby="inputGroupPrepend" placeholder="(Track)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Strand:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="strand"  value="{{$header[0]->strand}}" aria-describedby="inputGroupPrepend" placeholder="(Strand)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Name of Adviser/Teacher:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="teacher"  value="{{$header[0]->teacher}}" aria-describedby="inputGroupPrepend" placeholder="(Name of Adviser/Teacher)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="academicprogram"  value="{{$academicprogram}}" >
                            <input type="hidden" name="schooltableid"  value="{{$header[0]->parentid}}" >
                        </h4>
                        <div class="container">
                            <table class="table table-bordered fontSize">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                        <th width="30%">SUBJECT</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>FINAL RATING</th>
                                        <th width="15%">ACTION TAKEN</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $firstSemOldSubj = 0;   
                                    @endphp
                                    @foreach ($subjects as $subject)
                                        @if($subject->subj_desc != 'General Average' && $subject->semester == '1')
                                            <tr>
                                                <td class="tdInputClass">
                                                    {{-- {{$subject->subj_desc}} --}}
                                                    <input type="hidden" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->id}}"/>
                                                    <input type="text" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->core}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="text" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->subj_desc}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->quarter1}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->quarter2}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->finalrating}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="text" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->action}}"/>
                                                </td>
                                                <td></td>
                                            </tr>
                                            
                                        @php
                                            $firstSemOldSubj+=1;   
                                        @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                    </tr>
                                    @foreach ($subjects as $subject)
                                        @if($subject->subj_desc == 'General Average' && $subject->semester == '1')
                                            <tr> 
                                                <td colspan="3">{{$subject->subj_desc}}</td>
                                                <td class="tdInputClass">
                                                    {{-- <input type="hidden" class="form-control" name="hihihi" value="{{$subject->id}}" disabled/> --}}
                                                    {{-- <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->quarter2}}" disabled/> --}}
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="number" class="form-control" name="genAve[]" value="{{$subject->finalrating}}"/>
                                                </td>
                                                <td class="tdInputClass">
                                                    <input type="hidden" class="form-control" name="genAve3[]" value="{{$subject->action}}" disabled/>
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tfoot>
                            </table>
                        </div>
                        @elseif($semester == 'firstsummer')
                        
                        <h5><strong>Summer</strong></h5>
                        <h4 class="card-title col-md-12" >
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolname"  value="{{$subjects[0]->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL ID:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolid"  value="{{$subjects[0]->schoolid}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Name of Adviser/Teacher:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="teacher"  value="{{$subjects[0]->teacher}}" aria-describedby="inputGroupPrepend" placeholder="(Name of Adviser/Teacher)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Conducted from - to:</span>
                                            </div>
                                            <input type="text" name="conducted" class="form-control float-right" id="reservation" name="conducted" vale="{{$subjects[0]->conducted_from}} - {{$subjects[0]->conducted_to}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="academicprogram"  value="{{$academicprogram}}" >
                            <input type="hidden" name="schooltableid"  value="{{$schoolid}}" >
                        </h4>
                        <div class="container">
                            <table class="table table-bordered fontSize">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                        <th width="30%">SUBJECT</th>
                                        <th>SEM FINAL GRADE</th>
                                        <th>REMEDIAL CLASS MARK</th>
                                        <th>RECOMPUTED FINAL GRADE</th>
                                        <th width="15%">ACTION TAKEN</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $firstSummerOldSubj = 0;   
                                    @endphp
                                    @if(count($subjects)!=0)
                                        @foreach ($subjects as $subject)
                                            @if($subject->subjects != 'General Average' && $subject->semester == '1')
                                                <tr>
                                                    <td class="tdInputClass">
                                                        <input type="hidden" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->id}}"/>
                                                        <input type="text" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->core}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="text" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->subjects}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="number" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->semfinal}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="number" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->classmark}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="number" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->recomputedgrade}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="text" class="form-control" name="old{{$firstSummerOldSubj}}[]" value="{{$subject->action}}"/>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                
                                            @php
                                                $firstSummerOldSubj+=1;   
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($semester == 'secondsemester')
                        <h3><strong>Second Semester</strong></h3>
                        <h4 class="card-title col-md-12" >
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolname"  value="{{$header[0]->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL ID:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolid"  value="{{$header[0]->schoolid}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                <span class="input-group-text" id="inputGroupPrepend">SECTION</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="section"  value="{{$header[0]->section}}" aria-describedby="inputGroupPrepend" placeholder="(Section)">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Track:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="track"  value="{{$header[0]->track}}" aria-describedby="inputGroupPrepend" placeholder="(Track)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Strand:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="strand"  value="{{$header[0]->strand}}" aria-describedby="inputGroupPrepend" placeholder="(Strand)">
                                       </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Name of Adviser/Teacher:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="teacher"  value="{{$header[0]->teacher}}" aria-describedby="inputGroupPrepend" placeholder="(Name of Adviser/Teacher)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="academicprogram"  value="{{$academicprogram}}" >
                            <input type="hidden" name="schooltableid"  value="{{$schoolid}}" >
                        </h4>
                       <div class="container">
                        <table class="table table-bordered fontSize">
                            <thead class="bg-secondary">
                                <tr>
                                    <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                    <th width="30%">SUBJECT</th>
                                    <th>3</th>
                                    <th>4</th>
                                    <th>FINAL RATING</th>
                                    <th width="15%">ACTION TAKEN</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $firstSemOldSubj = 0;   
                                @endphp
                                @foreach ($subjects as $subject)
                                    @if($subject->subj_desc != 'General Average' && $subject->semester == '2')
                                        <tr>
                                            <td class="tdInputClass">
                                                {{-- {{$subject->id}} --}}
                                                <input type="hidden" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->id}}"/>
                                                <input type="text" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->core}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="text" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->subj_desc}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->quarter3}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->quarter4}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->finalrating}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="text" class="form-control" name="old{{$firstSemOldSubj}}[]" value="{{$subject->action}}"/>
                                            </td>
                                            <td></td>
                                        </tr>
                                        
                                    @php
                                        $firstSemOldSubj+=1;   
                                    @endphp
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6"></td>
                                    <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                </tr>
                                @foreach ($subjects as $subject)
                                    @if($subject->subj_desc == 'General Average' && $subject->semester == '2')
                                        <tr> 
                                            <td colspan="3">{{$subject->subj_desc}}</td>
                                            <td class="tdInputClass">
                                                {{-- <input type="hidden" class="form-control" name="hihihi" value="{{$subject->id}}" disabled/> --}}
                                                {{-- <input type="hidden" class="form-control" name="genAve[]" value="{{$subject->quarter2}}" disabled/> --}}
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="genAve[]" value="{{$subject->finalrating}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="hidden" class="form-control" name="genAve3[]" value="{{$subject->action}}" disabled/>
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tfoot>
                        </table>
                        </div> 
                        @elseif($semester == 'secondsummer')
                        <h5><strong>Second Semester</strong></h5>
                        <h4 class="card-title col-md-12" >
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolname"  value="{{$subjects[0]->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">SCHOOL ID:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="schoolid"  value="{{$subjects[0]->schoolid}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Name of Adviser/Teacher:</span>
                                            </div>
                                            <input type="text" class="form-control text-uppercase" id="validationCustomUsername" name="teacher"  value="{{$subjects[0]->teacher}}" aria-describedby="inputGroupPrepend" placeholder="(Name of Adviser/Teacher)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Conducted from - to:</span>
                                            </div>
                                            <input type="text" id="secondsummer" name="conducted" class="form-control float-right" id="" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="academicprogram"  value="{{$academicprogram}}" >
                            {{-- <input type="hidden" name="schooltableid"  value="{{$header[0]->parentid}}" > --}}
                            <input type="hidden" name="schooltableid"  value="{{$schoolid}}" >
                        </h4>
                        <div class="container">
                            <table class="table table-bordered fontSize">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>Indicate if Subject is<br>CORE, APPLIED, or<br>SPECIALIZED</th>
                                        <th width="30%">SUBJECT</th>
                                        <th>SEM FINAL GRADE</th>
                                        <th>REMEDIAL CLASS MARK</th>
                                        <th>RECOMPUTED FINAL GRADE</th>
                                        <th width="15%">ACTION TAKEN</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $secondSummerOldSubj = 0;   
                                    @endphp
                                    @if(count($subjects))
                                        @foreach ($subjects as $subject)
                                            @if($subject->subjects != 'General Average' && $subject->semester == '2')
                                                <tr>
                                                    <td class="tdInputClass">
                                                        <input type="hidden" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->id}}"/>
                                                        <input type="text" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->core}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        {{-- <input type="hidden" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->id}}"/> --}}
                                                        <input type="text" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->subjects}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="number" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->semfinal}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="number" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->classmark}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="number" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->recomputedgrade}}"/>
                                                    </td>
                                                    <td class="tdInputClass">
                                                        <input type="text" class="form-control" name="old{{$secondSummerOldSubj}}[]" value="{{$subject->action}}"/>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                
                                            @php
                                                $secondSummerOldSubj+=1;   
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endif
                        &nbsp;
                        @if($semester=='firstsemester' || $semester=='secondsemester')
                        @if($header[0]->operation == 1)
                            <input type="text" name="semester" value="{{$semester}}"/>
                            <button type="submit" class="btn btn-block btn-warning">Update</button>
                        @endif
                        @else
                        {{-- @if($header[0]->operation == 1) --}}
                            <input type="text" name="semester" value="{{$semester}}"/>
                            <button type="submit" class="btn btn-block btn-warning">Update</button>
                        {{-- @endif --}}
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>
        $( document ).ready(function() {
            
            $(function () {
                $('#reservation').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                })
                $('#secondsummer').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                })
            })
            var newSubj = 0;
            $('#addrow').on('click', function(){
                var closestTable = $(this).closest("table");
                closestTable.append(
                    '<tr>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
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
            $('#backStudents').on('click', function(){
                $('form[name=backStudents]').submit();
            })
        });
    </script>
@endsection
