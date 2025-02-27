<style>
    @page{
        size: 13in 8.5in;
        font-family: Arial, Helvetica, sans-serif;
    }
    
    .rotate div {
        font-size: 10px;
            -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -10em;
                margin-right: -10em;
        }
</style>
@php
    $init = 17;
    $countMale = 0;
    $countFemale = 0;
    $complete_twoyears_male = 0;
    $complete_abovetwoyears_male = 0;
    $complete_twoyears_female = 0;
    $complete_abovetwoyears_female = 0;
    $signatories = DB::table('signatory')
        ->where('form','form5b')
        ->where('syid', $sy->id)
        ->where('deleted','0')
        ->where('acadprogid',5)
        ->get();

    $signatory_name1 = '';
    $signatory_name2 = '';
    if(count($signatories) == 0)
    {
        $signatories = DB::table('signatory')
            ->where('form','form5b')
            ->where('syid', $sy->id)
            ->where('deleted','0')
            ->where('acadprogid',0)
            ->get();

        if(count($signatories) == 0)
        {
                
            $signatory_name1 = DB::table('schoolinfo')->first()->authorized;
        }
        elseif(count($signatories) == 1)
        {
            $signatory_name1 = $signatories[0]->name;
        }else{
            $signatory_name1 = collect($signatories)->first()->name;
            $signatory_name2 = collect($signatories)->last()->name;
        }

    }
    elseif(count($signatories) == 1)
    {
        $signatory_name1 = $signatories[0]->name;
    }else{
        if(collect($signatories)->where('levelid',$getSectionAndLevel[0]->levelid)->count() == 1)
        {
            $signatory_name1 = collect($signatories)->where('levelid',$getSectionAndLevel[0]->levelid)->first()->name;
        }
        if(collect($signatories)->where('levelid',$getSectionAndLevel[0]->levelid)->count() >=2)
        {
            $signatory_name1 = collect($signatories)->where('levelid',$getSectionAndLevel[0]->levelid)->first()->name;
            $signatory_name2 = collect($signatories)->where('levelid',$getSectionAndLevel[0]->levelid)->last()->name;
        }

    }
    $signatoriesv2 = DB::table('signatory')
                    ->where('form','form5b')
                    ->where('syid', $sy->id)
                    ->where('deleted','0')
                    ->where('acadprogid',5)
                    ->get();

    if(count($signatoriesv2) == 0)
    {
        $signatoriesv2 = DB::table('signatory')
            ->where('form','form5b')
            ->where('syid', $sy->id)
            ->where('deleted','0')
            ->where('acadprogid',0)
            ->get();

        if(count($signatoriesv2)>0)
        {
            if(collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->count() == 0)
            {
                $signatoriesv2 = collect($signatoriesv2)->where('levelid',0)->values();
            }else{
                $signatoriesv2 = collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->values();
            }
        }

        
    }else{
        if(collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->count() == 0)
        {
            $signatoriesv2 = collect($signatoriesv2)->where('levelid',0)->values();
        }else{
            $signatoriesv2 = collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->values();
        }
    }
@endphp
<table style="width: 100%; table-layout: fixed;">
    <tr style="@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')font-size: 15px;@else font-size: 12px;@endif">
        <th colspan="10" style="text-align: center; font-weight: bold;">School Form 5B List of Learners  with  Complete  SHS Requirements<br/>(SF5B-SHS) 
        </th>
    </tr>
</table>
<div style="line-height: 40px;">&nbsp;</div>
<table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">
    <thead>
        <tr>
            <td style="text-align: right; width: 10%;">School Name</td>
            <td style="border: 1px solid black; width: 24%; text-align: center:">{{$getSchoolInfo->schoolname}}</td>
            <td style="text-align: right; width: 7%;">School ID</td>
            <td style="border: 1px solid black; width: 8%;; text-align: center:">{{$getSchoolInfo->schoolid}}</td>
            <td style="text-align: right; width: 6%;">District</td>
            <td style="width: 12%; border: 1px solid black;; text-align: center:">{{($getSchoolInfo->district == null) ? DB::table('schoolinfo')->first()->districttext : $getSchoolInfo->district}}</td>
            <td style="text-align: right; width: 6%;">Division</td>
            <td style="width: 15%; border: 1px solid black; text-align: center:">{{($getSchoolInfo->division == null) ? DB::table('schoolinfo')->first()->divisiontext : $getSchoolInfo->division}}</td>
            <td style="width: 6%; text-align: right;">Region</td>
            <td style="width: 7%; border: 1px solid black; text-align: center:">{{str_replace('REGION', '', ($getSchoolInfo->region == null) ? DB::table('schoolinfo')->first()->regiontext : $getSchoolInfo->region)}}</td>
        </tr>
    </thead>
</table>
<div style="line-height: 15px;">&nbsp;</div>
<table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">
    <thead>
        <tr>
            <td style="text-align: right; width: 10%;">Semester</td>
            <td style="text-align: center; border: 1px solid black; width: 13%;">{{$sem->semester}}</td>
            <td style="text-align: right; width: 10%;">School Year</td>
            <td style="text-align: center; border: 1px solid black; width: 13%;">{{$sy->sydesc}}</td>
            <td style="text-align: right; width: 10%;">Grade Level</td>
            <td style="text-align: center; border: 1px solid black; width: 13%;">{{$getSectionAndLevel[0]->levelname}}</td>
            <td style="text-align: right; width: 10%;">Section</td>
            <td style="text-align: center; border: 1px solid black; width: 21%;">{{$getSectionAndLevel[0]->sectionname}}</td>
        </tr>
    </thead>
</table>
<div style="line-height: 10px;">&nbsp;</div>
<table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">
    <thead>
        <tr>
            <td style="text-align: right;">Track and  Strand</td>
            <td style="border: 1px solid black; text-align: center;">
                @if(count($strandinfo)>0)
                    {{$strandinfo[0]->trackname}} - {{$strandinfo[0]->strand}}
                @endif
            </td>
            <td style="text-align: right;">Course/s (only for TVL)</td>
            <td style="border: 1px solid black;">
                {{$courses}}
            </td>
        </tr>
    </thead>
</table>
<div style="line-height: 10px;">&nbsp;</div>
<table style="width: 100%;">
    <tr>
        <td style="width: 70%; padding: 0px;"><table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 10px;@endif" border="1">
                <thead>
                    <tr style="text-align: center;">
                        <th style="width: 5%">No</th>
                        <th style="width: 20%">LRN</th>
                        <th style="width: 35%">LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                        <th style="width: 20%;" class="rotate"><div>Completed <br/>SHS<br>in 2 SYs?<br/>(Y/N)</div></th>
                        <th style="width: 20%;">National<br>Certification Level<br>Attained<br>(only if applicable)</th>
                    </tr>
                </thead>
                <tr>
                    <td colspan="5" style="text-align: none;background-color:lightgrey;padding-left:5px;">MALE</td>
                </tr>
                @foreach ($getStudents as $student)
                    @if(strtolower($student->gender) == 'male')
                        @php
                            $countMale+=1;   
                        @endphp
                        <tr>
                            <td>{{$countMale}}</td>
                            <td>{{$student->lrn}}</td>
                            <td style="text-align: left;">&nbsp;{{ucwords(strtolower($student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename))}}</td>
                            <td style="text-align: center;">
                                @if($student->completed == 1) Y @elseif($student->completed == 0) N @endif
                            </td>
                            <td style="text-align: center;">
                                {{$student->certificationlevel}}
                            </td>
                        </tr>
                    @endif
                @endforeach
                @for($x = $countMale; $x<$init; $x++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
                <tr>
                    <td colspan="5" style="text-align: none;background-color:lightgrey;padding-left:5px;">FEMALE</td>
                </tr>
                @foreach ($getStudents as $student)
                    @if(strtolower($student->gender) == 'female')
                        @php
                            $countFemale+=1;   
                        @endphp
                        <tr>
                            <td>{{$countFemale}}</td>
                            <td>{{$student->lrn}}</td>
                            <td style="text-align: left;">&nbsp;{{ucwords(strtolower($student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename))}}</td>
                            <td style="text-align: center;">
                                @if($student->completed == 1) Y @elseif($student->completed == 0) N @endif
                            </td>
                            <td style="text-align: center;">
                                {{$student->certificationlevel}}
                            </td>
                        </tr>
                    @endif
                @endforeach
                @for($x = $countFemale; $x<$init; $x++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </table>
        </td>
        <td style="width: 30%;">
            <div style="line-height: 20px;">&nbsp;</div><table border="1" style="width:100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">
                <thead>
                    <tr style="text-align: center;">
                        <th colspan="4">SUMMARY TABLE A</th>
                    </tr>
                    <tr>
                        <th style="width: 35%;">STATUS</th>
                        <th style="width: 20%;">MALE</th>
                        <th style="width: 25%;">FEMALE</th>
                        <th style="width: 20%;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="text-align: justify;">Learners who completed SHS Program within 2 SYs or 4 semesters</th>
                        <td style="text-align: center;">{{collect($getStudents)->where('gender','male')->where('completed','1')->count()}}</td>
                        <td style="text-align: center;">{{collect($getStudents)->where('gender','female')->where('completed','1')->count()}}</td>
                        <td style="text-align: center;">{{collect($getStudents)->where('completed','1')->count()}}</td>
                        {{-- <th style="text-align: center;">{{$complete_twoyears_male}}</th>
                        <th style="text-align: center;">{{$complete_twoyears_female}}</th>
                        <th style="text-align: center;">{{$complete_twoyears_male + $complete_twoyears_female}}</th> --}}
                    </tr>
                    <tr>
                        <th style="text-align: justify;">Learners who completed SHS Program in more than 2 SYs or 4 semesters</th>
                        <td style="text-align: center;">{{collect($getStudents)->where('gender','male')->where('status','OVERSTAYING')->count()}}</td>
                        <td style="text-align: center;">{{collect($getStudents)->where('gender','female')->where('status','OVERSTAYING')->count()}}</td>
                        <td style="text-align: center;">{{collect($getStudents)->where('status','OVERSTAYING')->count()}}</td>
                        {{-- <th style="text-align: center;">{{$complete_abovetwoyears_male}}</th>
                        <th style="text-align: center;">{{$complete_abovetwoyears_female}}</th>
                        <th style="text-align: center;">{{$complete_abovetwoyears_male + $complete_abovetwoyears_female}}</th> --}}
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <td style="text-align: center;">{{collect($getStudents)->where('gender','male')->where('completed','1')->count()+collect($getStudents)->where('gender','male')->where('status','OVERSTAYING')->count()}}</td>
                        <td style="text-align: center;">{{collect($getStudents)->where('gender','female')->where('completed','1')->count()+collect($getStudents)->where('gender','female')->where('status','OVERSTAYING')->count()}}</td>
                        <td style="text-align: center;">{{collect($getStudents)->where('completed','1')->count()+collect($getStudents)->where('status','OVERSTAYING')->count()}}</td>
                        {{-- <th style="text-align: center;">{{$complete_twoyears_male + $complete_abovetwoyears_male}}</th>
                        <th style="text-align: center;">{{$complete_twoyears_female + $complete_abovetwoyears_female}}</th>
                        <th style="text-align: center;">{{($complete_twoyears_male + $complete_twoyears_female) + ($complete_abovetwoyears_male + $complete_abovetwoyears_female)}}</th> --}}
                    </tr>
                </tbody>
            </table>
            <div style="line-height: 10px;">&nbsp;</div><table border="1" style="width:100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">
                <thead>
                    <tr style="text-align: center;">
                        <th colspan="4">SUMMARY TABLE B</th>
                    </tr>
                    <tr style="text-align: center;">
                        <th>STATUS</th>
                        <th>MALE</th>
                        <th>FEMALE</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>NC III</th>
                        <th style="text-align: center;">{{$ncArray[0]->nciiimale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->nciiifemale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->nciiitotal}}</th>
                    </tr>
                    <tr>
                        <th>NC II</th>
                        <th style="text-align: center;">{{$ncArray[0]->nciimale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->nciifemale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->nciitotal}}</th>
                    </tr>
                    <tr>
                        <th>NC I</th>
                        <th style="text-align: center;">{{$ncArray[0]->ncimale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->ncifemale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->ncitotal}}</th>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <th style="text-align: center;">{{$ncArray[0]->nctotalmale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->nctotalfemale}}</th>
                        <th style="text-align: center;">{{$ncArray[0]->nctotal}}</th>
                    </tr>
                </tbody>
            </table>
            <div style="font-size: 10px; text-align: justify;">Note: NCS are recorded here for documentation but is not a requirement for graduation.</div>
            <div style="line-height: 5px;">&nbsp;</div>
            <div style="font-size: 10px;"><strong>GUIDELINES:</strong></div>
            <div style="font-size: 10px; text-align: justify;">1. This for should be accomplished by the Class Adviser at End of School Year.</div>
            <div style="font-size: 10px; text-align: justify;">2. It should be compiled and checked by the School Head and passed to the Division Office before graduation.</div>
            <div style="line-height: 5px;">&nbsp;</div>
            {{-- <div style="font-size: 10px;">Prepared by:</div>
            <div style="line-height: 5px;">&nbsp;</div>
            &nbsp;
            <div style="width:100%;border-bottom: 1px solid black; font-size: 10px; text-align: center;">{{strtoupper($getTeacherName->firstname.' '.$getTeacherName->middlename.' '.$getTeacherName->lastname.' '.$getTeacherName->suffix)}} 
            </div>
            <div style="text-align: ceter"> <sup style="font-size: 8px; text-align: center !important;">Signature of Class Adviser over Printed Name</sup></div>
            <div style="line-height: 20px;">&nbsp;</div> --}}
            
            <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 9px;">Prepared by:</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; border-bottom: 1px solid black; text-align: center;">&nbsp;<br/>{{strtoupper($getTeacherName->firstname.' '.$getTeacherName->middlename.' '.$getTeacherName->lastname.' '.$getTeacherName->suffix)}}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px; text-align: center;">Signature of Class Adviser over Printed Name</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
            </table>
            
            @if(count($signatoriesv2)>0)
            <table style="width: 100%;">
                @foreach($signatoriesv2 as $signatory)
                    <tr>
                        <td style="font-size: 9px;">{{$signatory->title}}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; border-bottom: 1px solid black; text-align: center;">&nbsp;<br/>{{$signatory->name}}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px; text-align: center;">{{$signatory->description}}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                @endforeach
            </table>
            @else
            <div style=" font-size: 10px;">Certified Correct & Submitted by:</div>
            <div style="line-height: 10px;">&nbsp;</div>
            <div style="width:100%;border-bottom: 1px solid black; font-size: 10px; text-align: center;">{{$signatory_name1}}</div>
            <div style="line-height: 5px;">&nbsp;</div>
            <sup style="font-size: 8px; text-align: center;">Signature of School Head over Printed Name</sup>
            <div style="line-height: 10px;">&nbsp;</div>
            <div style=" font-size: 10px;">Reviewed by:</div>
            <div style="line-height: 5px;">&nbsp;</div>
            <div style="width:100%;border-bottom: 1px solid black; font-size: 10px; text-align: center;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') MAJARANI MACARAEG JACINTO EDD, CESO IV @else  {{$signatory_name2}} @endif
            </div>
            <div style="line-height: 5px;">&nbsp;</div>
            <sup style="font-size: 8px; text-align: center;">Signature of Division Representative over Printed Name</sup>
            @endif
            {{-- <div style=" font-size: 10px;">Certified Correct & Submitted by:</div>
            <div style="line-height: 10px;">&nbsp;</div>
            <div style="width:100%;border-bottom: 1px solid black; font-size: 10px; text-align: center;">{{$signatory_name1}}</div>
            <div style="line-height: 5px;">&nbsp;</div>
            <sup style="font-size: 8px; text-align: center;">Signature of School Head over Printed Name</sup>
            <div style="line-height: 10px;">&nbsp;</div>
            <div style=" font-size: 10px;">Reviewed by:</div>
            <div style="line-height: 5px;">&nbsp;</div>
            <div style="width:100%;border-bottom: 1px solid black; font-size: 10px; text-align: center;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') MAJARANI MACARAEG JACINTO ED.D,CESO VI @else {{strtoupper($divisionrep)}} @endif
            </div>
            <div style="line-height: 5px;">&nbsp;</div>
            <sup style="font-size: 8px; text-align: center;">Signature of Division Representative over Printed Name</sup> --}}
        </td>
    </tr>
</table>