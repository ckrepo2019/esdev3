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
    <input type="hidden" value="School Form 5B" name="selectedform"/>
</form>
<form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
    <input type="hidden" value="{{$sy[0]->id}}" name="syid"/>
    <input type="hidden" value="School Form 5B" name="selectedform"/>
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
 
@php
$countMale = 0;
$countFemale = 0;
$complete_twoyears_male = 0;
$complete_abovetwoyears_male = 0;
$complete_twoyears_female = 0;
$complete_abovetwoyears_female = 0;
@endphp

@if(isset($semester))
<form action="/reports_schoolform5/print/{{$selectedform}}/{{$sy[0]->id}}/{{$gradeAndLevel[0]->sectionid}}/{{$gradeAndLevel[0]->levelid}}" method="GET" target="_blank">
<div class="row">
    <div class="col-12">
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa fa-file"></i>
                    <strong>School Form 5B List of Learners with Complete SHS Requirements (SF5B-SHS) </strong>
                </h3>
                @php
                    $schoolinfo = Db::table('schoolinfo')
                        ->first();
                @endphp
                @if($schoolinfo->authorized == null)
                    <button type="button" class="btn btn-primary btn-sm text-white float-right authorizedinput" route=""  viewtarget="print" >
                        <i class="fa fa-upload"></i>
                        Print
                    </button>
                @else
                    <button type="submit" class="btn btn-primary btn-sm text-white float-right" >
                        <i class="fa fa-upload"></i>
                    Print
                    
                    </button>
                @endif
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
        <div class="main-cardcard p-0">
            <div class="card-body p-0">
                <table class="table table-bordered students">
                    <tr>
                        <th>No.</th>
                        <th>LRN</th>
                        <th>LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                        <th>Completed SHS in 2 SYs? (Y/N)</th>
                        <th>National<br>Certification Level Attained<br>(only if applicable)</th>
                    </tr>
                    <tr>
                        <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">MALE</th>
                    </tr>
                    @foreach ($filter as $student)
                        {{-- @foreach($student as $persem) --}}
                            @if($student->semid == 1)
                                @if(strtoupper($student->gender) == 'MALE')
                                    @php
                                        $countMale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countMale.'.'}}</td>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename[0]}}</td>
                                        <td>
                                            <center>
                                                @if ($student->status == 'COMPLETE')
                                                    @php
                                                        $complete_twoyears_male+=1;   
                                                    @endphp
                                                    Y
                                                @elseif ($student->status == 'INCOMPLETE')
                                                    {{-- y --}}
                                                @elseif ($student->status == 'OVERSTAYING')
                                                    @php
                                                        $complete_abovetwoyears_male+=1;   
                                                    @endphp
                                                    N
                                                @endif
                                            </center>
                                        </td>
                                        <td class="p-0">
                                            <textarea name="{{$student->lastname.'-'.$student->firstname}}" type="text" class="form-control form-control-sm m-0 p-0" ></textarea>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        {{-- @endforeach --}}
                    @endforeach
                    <tr>
                        <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">FEMALE</th>
                    </tr>
                    @foreach ($filter as $student)
                        {{-- @foreach($student as $persem) --}}
                            @if($student->semid == 1)
                                @if(strtoupper($student->gender) == 'FEMALE')
                                    @php
                                        $countFemale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countFemale.'.'}}</td>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename[0]}}</td>
                                        <td>
                                            <center>
                                                @if ($student->status == 'COMPLETE')
                                                    @php
                                                        $complete_twoyears_female+=1;   
                                                    @endphp
                                                    Y
                                                @elseif ($student->status == 'INCOMPLETE')
                                                
                                                @elseif ($student->status == 'OVERSTAYING')
                                                    @php
                                                        $complete_abovetwoyears_female+=1;   
                                                    @endphp
                                                    N
                                                @endif
                                            </center>
                                        </td>
                                        <td class="p-0">
                                            <textarea name="{{$student->lastname.'-'.$student->firstname}}" type="text" class="form-control form-control-sm m-0 p-0" ></textarea>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        {{-- @endforeach --}}
                    @endforeach
                </table>
                <div class="m-3">
                    <p>
                        <strong>
                            GUIDELINES:
                        </strong>
                        <ol>
                            <li>This form should be accomplished by the Class Adviser at End of School Year.</li>
                            <li>It should be compiled and checked by the School Head and passed to the Division Office before graduation.</li>
                        </ol>
                    </p>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="street" class="">Prepared by:</label>
                                <input name="teacher" id="street" type="text" class="form-control form-control-sm" value="{{strtoupper($teachername[0]->firstname.' '.$teachername[0]->middlename[0].'. '.$teachername[0]->lastname.' '.$teachername[0]->suffix)}}" readonly/>
                                <small>Signature of Class Adviser over Printed Name</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="barangay" class="">Certified Correct by:</label>
                                <input name="schoolhead" id="barangay" type="text" class="form-control form-control-sm" value="{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}"readonly/>
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="main-card card p-0">
            <div class="card-body p-0">
                <table class="table table-bordered m-0 summary" style="width:">
                    <tr>
                        <th colspan="4">SUMMARY TABLE A<br>&nbsp;</th>
                    </tr>
                    <tr>
                        <th>STATUS</th>
                        <th>MALE</th>
                        <th>FEMALE</th>
                        <th>TOTAL</th>
                    </tr>
                    <tr>
                        <th>Learners who completed SHS Program within 2 SYs or 4 semesters</th>
                        <th>{{$complete_twoyears_male}}</th>
                        <th>{{$complete_twoyears_female}}</th>
                        <th>{{$complete_twoyears_male + $complete_twoyears_female}}</th>
                    </tr>
                    <tr>
                        <th>Learners who completed SHS Program in more than 2 SYs or 4 semesters</th>
                        <th>{{$complete_abovetwoyears_male}}</th>
                        <th>{{$complete_abovetwoyears_female}}</th>
                        <th>{{$complete_abovetwoyears_male + $complete_abovetwoyears_female}}</th>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <th>{{$complete_twoyears_male + $complete_abovetwoyears_male}}</th>
                        <th>{{$complete_twoyears_female + $complete_abovetwoyears_female}}</th>
                        <th>{{($complete_twoyears_male + $complete_twoyears_female) + ($complete_abovetwoyears_male + $complete_abovetwoyears_female)}}</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-bordered m-0 summary" style="width:">
                    <tr>
                        <th colspan="4">SUMMARY TABLE B</th>
                    </tr>
                    <tr>
                        <th>STATUS</th>
                        <th>MALE</th>
                        <th>FEMALE</th>
                        <th>TOTAL</th>
                    </tr>
                    <tr>
                        <th>NCIII</th>
                        <th class="p-0">
                            <input type="number" name="nciiimale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="nciiifemale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="nciiitotal" class="form-control"/>
                        </th>
                    </tr>
                    <tr>
                        <th>NC II</th>
                        <th class="p-0">
                            <input type="number" name="nciimale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="nciifemale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="nciitotal" class="form-control"/>
                        </th>
                    </tr>
                    <tr>
                        <th>NC I</th>
                        <th class="p-0">
                            <input type="number" name="ncimale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="ncifemale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="ncitotal" class="form-control"/>
                        </th>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <th class="p-0">
                            <input type="number" name="nctotalmale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="nctotalfemale" class="form-control"/>
                        </th>
                        <th class="p-0">
                            <input type="number" name="nctotal" class="form-control"/>
                        </th>
                    </tr>
                </table>
                <small>Note: NC's are recorded here for documentation but is not a requirement for graduation.</small>
            </div>
        </div>
    </div>
</div>
</form>
@endif
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

@endsection