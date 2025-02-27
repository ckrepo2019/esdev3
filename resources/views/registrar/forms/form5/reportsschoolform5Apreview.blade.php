@extends('registrar.layouts.app')

@section('content')
<style>
    
    #header td                          { padding-left: 1px; }

    #header, #header th, #header td     { font-size: 12px; border: none !important; /* border:1px solid black !important; */ padding:2px; text-align: right; }

    th                                  { text-align: center; /* table-layout: fixed; */ }

    input[type=text]                    { text-align: center; width:100%; }

    .bottom                             { position: absolute; bottom: 0; }

    td                                  {text-transform: uppercase}

    .header                             { border: hidden; font-size: 13px;}

    .header td                          { border: hidden;}

    .summary, .students, .prepared      {font-size: 13px;}

</style>
@php
    $countMale = 0;
    $countFemale = 0;
    $firstsemcompletemale = 0;
    $firstsemcompletefemale = 0;
    $firstsemincompletemale = 0;   
    $firstsemincompletefemale = 0;   
    $secondsemcompletemale = 0;
    $secondsemcompletefemale = 0;
    $secondsemincompletemale = 0;  
    $secondsemincompletefemale = 0;  
@endphp
@if(isset($semester))
<form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
    <input type="hidden" value="{{$sy[0]->id}}" name="syid"/>
    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
    <input type="hidden" value="School Form 5A" name="selectedform"/>
</form>
<form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
    <input type="hidden" value="{{$sy[0]->id}}" name="syid"/>
    <input type="hidden" value="School Form 5A" name="selectedform"/>
    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
</form>
@endif
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
                <li class="breadcrumb-item active">School Form 5</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    {{-- <div class="row mb-2"> --}}
    @if(isset($message))
        <div class="col-sm-12">
            <div class="alert alert-warning alert-dismissible">
                {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                {{$message}}
            </div>
        </div>
    @endif
    @if(isset($semester))
    <form action="/export/form5a" method="GET" target="_blank">
        @csrf
        <input type="hidden" id="action" name="action" value="export"/>
        <input type="hidden" id="selectedform" name="selectedform" value="{{$selectedform}}"/>
        <input type="hidden" id="syid" name="syid" value="{{$sy[0]->id}}"/>
        <input type="hidden" id="sectionid" name="sectionid" value="{{$gradeAndLevel[0]->sectionid}}"/>
        <input type="hidden" id="levelid" name="levelid" value="{{$gradeAndLevel[0]->levelid}}"/>
        <input type="hidden" id="exporttype" name="exporttype" value=""/>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <strong>School Form 5A End of Semester and School Year Status of Learners for Senior High School (SF5A-SHS) </strong>
                        </div>
                        <div class="col-md-3 text-right">
                            {{-- <button type="button" class="btn btn-default btn-sm" id="btn-exportexcel">
                                <i class="fa fa-file-excel"></i>
                                Excel
                            </button> --}}
                            <button type="button" class="btn btn-default btn-sm" id="btn-exportpdf">
                                <i class="fa fa-upload"></i>
                                PDF
                            </button>
                            {{-- @php
                                $schoolinfo = Db::table('schoolinfo')
                                    ->first();
                            @endphp
                            @if($schoolinfo->authorized == null)
                                <button type="button" class="btn btn-default btn-sm"
                                 authorizedinput" route=""  viewtarget="print" 
                                 >
                                    <i class="fa fa-upload"></i>
                                    PDF
                                </button>
                            @else
                                <button type="button" class="btn btn-default btn-sm" id="btn-exportexcel">
                                    <i class="fa fa-upload"></i>
                                    PDF
                                </button>
                            @endif --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                    <table class="table header" style="border:none">
                        <thead>
                            {{-- <tr>
                                <th>School Name<br><input type="text" class="form-control" value="{{$school[0]->schoolname}}" readonly/></th>
                                <th>School ID<br><input type="text" class="form-control" value="{{$school[0]->schoolid}}" readonly/></th>
                                <th>District<br><input type="text" class="form-control" value="{{$school[0]->district}}" readonly/></th>
                                <th>Division<br><input type="text" class="form-control" value="{{$school[0]->division}}" readonly/></th>
                            </tr> --}}
                            <tr>
                                {{-- <th>Region<br><input type="text" class="form-control" value="{{$school[0]->region}}" readonly/></th> --}}
                                <th >School Year<br><input type="text" class="form-control form-control-sm" value="{{$sy[0]->sydesc}}" readonly/></th>
                                <th >Semester<br><input type="text" class="form-control form-control-sm" value="{{$semester[0]->semester}}" readonly/></th>
                                <th>Grade Level<br><input type="text" id="curriculum" class="form-control form-control-sm" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->levelname}}" readonly/></th>
                                <th>Section<br><input type="text" id="curriculum" class="form-control form-control-sm" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->sectionname}}" readonly/></th>
                            </tr>
                            <tr>
                                <th colspan="2">Track and Strand
                                    @if(isset($trackAndStrands))
                                    <br>
                                    @foreach ($trackAndStrands as $track)
                                        {{$track['track'].' - '.$track['strand']}}
                                        <br>
                                    @endforeach
                                    @else
                                    <input type="text" class="form-control form-control-sm" value="" />
                                    @endif
                                </th>
                                @if(isset($tvl))
                                <th colspan="2">Course/s (only for TVL)<br><input type="text" class="form-control form-control-sm" value="" /></th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="main-card card p-0">
                <div class="card-body p-0">
                    <table class="table table-bordered students">
                        <tr>
                            <th>No.</th>
                            <th>LRN</th>
                            <th>LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                            <th>BACK SUBJECT/S<br>List down subjects where learner obtain a rating below 75%</th>
                            <th>END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                            <th>END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">MALE</th>
                        </tr>
                        @if($semester[0]->id == 1)
                            @foreach ($grades['firstsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                    @php
                                        $countMale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countMale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->middlename[0].'. '.$grade['studentdata']->suffix}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif --}}
                                        </td>
                                    </tr>

                                @endif
                            @endforeach
                        @elseif($semester[0]->id == 2)
                            @foreach ($grades['secondsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                    @php
                                        $countMale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countMale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->middlename[0].'. '.$grade['studentdata']->suffix}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">FEMALE</th>
                        </tr>
                        @if($semester[0]->id == 1)
                            @foreach ($grades['firstsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                    @php
                                        $countFemale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countFemale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->middlename[0].'. '.$grade['studentdata']->suffix}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif --}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @elseif($semester[0]->id == 2)
                            @foreach ($grades['secondsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                    @php
                                        $countFemale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countFemale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->middlename[0].'. '.$grade['studentdata']->suffix}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </table>
                    <div class="m-3">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="street" class="">Prepared by:</label>
                                    <input name="street" id="street" type="text" class="form-control form-control-sm" value="{{strtoupper($teachername[0]->firstname.' '.$teachername[0]->middlename[0].'. '.$teachername[0]->lastname.' '.$teachername[0]->suffix)}}" readonly/>
                                    <small>Signature of Class Adviser over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="barangay" class="">Certified Correct by:</label>
                                    <input name="barangay" id="barangay" type="text" class="form-control form-control-sm" value="{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}" readonly/>
                                    <small>Signature of School Head over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="city" class="">Reviewed by:</label>
                                    <input name="divisionrep" id="city" type="text" class="form-control form-control-sm" required/>
                                    <small>Signature of Division Representative over Printed Name</small>
                                </div>
                            </div>
                        </div>
                        <p>
                            <strong>
                                GUIDELINES:
                            </strong>
                            <br>
                            <em>
                                This form shall be accomplished after each semester in a school year,  leaving the End of School Year Status Column and Summary Table for End of School Year Status blank/unfilled at the end of the 1st Semester.  These data elements shall be filled up only after the 2nd semester or at the end of the School Year. 
                            </em>
                        </p>
                        <br>
                        <p>
                            <strong>
                                INDICATORS:
                            </strong>
                            <br>
                            <em>
                                <strong>
                                    End of Semester Status
                                </strong>
                            </em>
                            <br>
                            <span class="ml-5"> 
                                <strong>Complete</strong> - number of learners who completed/satisfied the requirements in all subject areas (with grade of at least 75%)
                            </span>
                            <br>
                            <span class="ml-5"> 
                                <strong>Incomplete</strong> - number of learners who did not meet expectations in one or more subject areas, regardless of number of subjects failed (with grade less than 75%)
                            </span>
                            <br>
                            <span class="ml-5"> 
                                <em>
                                    <strong>Note:</strong> Do not include learners who are No Longer in School (<strong>NLS</strong>)
                                </em>
                            </span>
                            <br>
                            <em>
                                <strong>
                                    End of School Year Status
                                </strong>
                            </em>
                            <br>
                            <span class="ml-5"> 
                                <strong>Regular</strong> - number of learners who completed/satisfied requirements in all subject areas  both in the 1st and 2nd semester
                            </span>
                            <br>
                            <span class="ml-5"> 
                                <strong>Irregular</strong> - number of learners who were not able to satisfy/complete requirements in one or both semesters
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            {{-- <div class="col-md-12"> --}}
                <div class="main-card card p-0">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0 summary" style="width:">
                            <tr>
                                <th colspan="4">SUMMARY TABLE 1ST SEM</th>
                            </tr>
                            <tr>
                                <th>STATUS</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th>COMPLETE</th>
                                <th>
                                        @foreach ($grades['firstsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                                @if(count($grade['backsubjects']) == 0)
                                                    @php
                                                        $firstsemcompletemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$firstsemcompletemale}}
                                </th>
                                <th>
                                        @foreach ($grades['firstsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                                @if(count($grade['backsubjects']) == 0)
                                                    @php
                                                        $firstsemcompletefemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$firstsemcompletefemale}}
                                </th>
                                <th>{{$firstsemcompletemale + $firstsemcompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>INCOMPLETE</th>
                                <th>
                                        @foreach ($grades['firstsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                                @if(count($grade['backsubjects']) != 0)
                                                    @php
                                                        $firstsemincompletemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$firstsemincompletemale}}
                                </th>
                                <th>
                                        @foreach ($grades['firstsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                                @if(count($grade['backsubjects']) != 0)
                                                    @php
                                                        $firstsemincompletefemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$firstsemincompletefemale}}
                                </th>
                                <th>{{$firstsemincompletemale + $firstsemincompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th>{{$firstsemcompletemale + $firstsemincompletemale}}</th>
                                <th>{{$firstsemcompletefemale + $firstsemincompletefemale}}</th>
                                <th>{{($firstsemcompletemale + $firstsemcompletefemale) + ($firstsemincompletemale + $firstsemincompletefemale)}}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            {{-- </div>
            <div class="col-md-12"> --}}
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0 summary" style="width:">
                            <tr>
                                <th colspan="4">SUMMARY TABLE 2ND SEM</th>
                            </tr>
                            <tr>
                                <th>STATUS</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th>COMPLETE</th>
                                <th>
                                        @foreach ($grades['secondsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                                @if(count($grade['backsubjects']) == 0)
                                                    @php
                                                        $secondsemcompletemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemcompletemale}}
                                </th>
                                <th>
                                        @foreach ($grades['secondsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                                @if(count($grade['backsubjects']) == 0)
                                                    @php
                                                        $secondsemcompletefemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemcompletefemale}}
                                </th>
                                <th>{{$secondsemcompletemale + $secondsemcompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>INCOMPLETE</th>
                                <th>
                                        @foreach ($grades['secondsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                                @if(count($grade['backsubjects']) != 0)
                                                    @php
                                                        $secondsemincompletemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemincompletemale}}
                                </th>
                                <th>
                                        @foreach ($grades['secondsem'] as $grade)
                                            @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                                @if(count($grade['backsubjects']) != 0)
                                                    @php
                                                        $secondsemincompletefemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemincompletefemale}}
                                </th>
                                <th>{{$secondsemincompletemale + $secondsemincompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th>{{$secondsemcompletemale + $secondsemincompletemale}}</th>
                                <th>{{$secondsemcompletefemale + $secondsemincompletefemale}}</th>
                                <th>{{($secondsemcompletemale + $secondsemcompletefemale) + ($secondsemincompletemale + $secondsemincompletefemale)}}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            {{-- </div>
            <div class="col-md-12"> --}}
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0 summary" style="width:">
                            <tr>
                                <th colspan="4">SUMMARY TABLE (End of the School Year Only)</th>
                            </tr>
                            <tr>
                                <th>STATUS</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th>REGULAR</th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{$firstsemcompletemale + $secondsemcompletemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{$firstsemcompletefemale + $secondsemcompletefemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemcompletefemale + $secondsemcompletefemale)}}
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>IRREGULAR</th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{$firstsemincompletemale + $secondsemincompletemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{$firstsemincompletefemale + $secondsemincompletefemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{($firstsemincompletemale + $secondsemincompletemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester[0]->id == 2)
                                        {{(($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)) + (($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale))}}
                                    @endif
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
    </div>
</form>
@endif
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script>
    $('#btn-exportexcel').on('click', function(){
        $('#exporttype').val('excel')
        $(this).closest('form').submit();
    })
    $('#btn-exportpdf').on('click', function(){
        $('#exporttype').val('pdf')
        $(this).closest('form').submit();
    })
</script>
@endsection
