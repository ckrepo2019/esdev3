<style>
    @page{
        size: 13in 8.5in;
    }
    *{
        
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
@php
    $countMale = 0;
    $countFemale = 0;
    $init = 21;
    $firstsemcompletemale = 0;
    $firstsemcompletefemale = 0;
    $firstsemincompletemale = 0;   
    $firstsemincompletefemale = 0;   
    $secondsemcompletemale = 0;
    $secondsemcompletefemale = 0;
    $secondsemincompletemale = 0;  
    $secondsemincompletefemale = 0;  
    
    $signatories = DB::table('signatory')
        ->where('form','form5a')
        ->where('syid', $sy->id)
        ->where('deleted','0')
        ->where('acadprogid',5)
        ->get();

    $signatory_name1 = '';
    $signatory_name2 = '';
    if(count($signatories) == 0)
    {
        $signatories = DB::table('signatory')
            ->where('form','form5a')
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
                    ->where('form','form5a')
                    ->where('syid', $sy->id)
                    ->where('deleted','0')
                    ->where('acadprogid',5)
                    ->get();

    if(count($signatoriesv2) == 0)
    {
        $signatoriesv2 = DB::table('signatory')
            ->where('form','form5a')
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
        <th colspan="10" style="text-align: center; font-weight: bold;">School Form 5A<br>End of Semester and School Year Status of Learners for Senior High School<br>(SF5A-SHS)
        </th>
    </tr>
</table>

@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
<div style="line-height: 25px;">&nbsp;</div>
@else
<div style="line-height: 15px;">&nbsp;</div>
@endif
<table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">
    <thead>
        <tr>
            <td style="text-align: right; width: 10%;">School Name</td>
            <td style="border: 1px solid black; width: 25%; text-align: center:">{{$schoolinfo->schoolname}}</td>
            <td style="text-align: right; width: 7%;">School ID</td>
            <td style="border: 1px solid black; width: 6%;; text-align: center:">{{$schoolinfo->schoolid}}</td>
            <td style="text-align: right; width: 6%;">District</td>
            <td style="width: 12%; border: 1px solid black;; text-align: center:">{{($schoolinfo->district == null) ? DB::table('schoolinfo')->first()->districttext : $schoolinfo->district}}</td>
            <td style="text-align: right; width: 6%;">Division</td>
            <td style="width: 15%; border: 1px solid black; text-align: center:">{{($schoolinfo->division == null) ? DB::table('schoolinfo')->first()->divisiontext : $schoolinfo->division}}</td>
            <td style="width: 6%; text-align: right;">Region</td>
            <td style="width: 7%; border: 1px solid black; text-align: center:">{{str_replace('REGION', '', ($schoolinfo->region == null) ? DB::table('schoolinfo')->first()->regiontext : $schoolinfo->region)}}</td>
        </tr>
    </thead>
</table>
<div style="line-height: 10px;">&nbsp;</div>
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
        <td style="width: 70%; padding: 0px;"><table style="width: 100%; @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif" border="1">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 17%">LRN</th>
                        <th style="width: 29%; text-align: center;">LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                        <th style="width: 19%; text-align: center;">BACK SUBJECTS<br>List down subjects where learner obtain a rating below 75%</th>
                        <th style="width: 16%; text-align: center;">END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                        <th style="width: 14%; text-align: center;">END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                    </tr>
                </thead>
                <tr>
                    <td colspan="6" style="text-align: none;background-color:lightgrey;padding-left:5px;">MALE</td>
                </tr>
                @if(count($students)>0)
                @foreach (collect($students)->where('gender','male')->where('semid',$sem->id)->unique('id')->values() as $student)
                            @php
                                $countMale+=1;   
                            @endphp
                            <tr style="@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px @else font-size: 9px @endif !important;" nobr="true">
                                <td class="p-0">{{$countMale}}</td>
                                <td>{{$student->lrn}}</td>
                                <td style="text-align: left;">{{ucwords(strtolower($student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix))}}</td>
                                <td style="text-align: left; margin: 0px;">
                                    @if(collect($student->backsubjects)->where('semid',$sem->id)->count()>0)
                                        @foreach (collect($student->backsubjects)->where('semid',$sem->id)->values() as $backsubjectskey => $backsubjects)
                                            {{$backsubjectskey+1}}. {{ucwords(strtolower($backsubjects->subjectcode))}}
                                            <div style="line-height: 5px;">&nbsp;</div>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($sem->id == 1)
                                        @if($student->sem1status == 1) COMPLETE @else INCOMPLETE @endif
                                    @else 
                                        @if($student->sem2status == 1) COMPLETE @else INCOMPLETE @endif
                                    @endif
                                </td>
                                <td>
                                    @if($sem->id == 2)
                                        @if($student->sem1status == 1 && $student->sem2status == 1) REGULAR @else IRREGULAR @endif
                                    @endif
                                </td>
                            </tr>
                    @endforeach
                @endif
                @for($x = $countMale; $x<$init; $x++)
                    <tr style="font-size: 9px !important;" nobr="true">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
                <tr>
                    <td colspan="6" style="text-align: none;background-color:lightgrey;padding-left:5px;">FEMALE</td>
                </tr>
                @if(count($students)>0)
                    @foreach (collect($students)->where('gender','female')->where('semid',$sem->id)->unique('id')->values() as $student)
                            @php
                                $countFemale+=1;   
                            @endphp
                            <tr style="@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px @else font-size: 9px @endif !important;" nobr="true">
                                <td class="p-0">{{$countFemale}}</td>
                                <td>{{$student->lrn}}</td>
                                <td style="text-align: left;">{{ucwords(strtolower($student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix))}}</td>
                                <td style="text-align: left; margin: 0px;">
                                    @if(collect($student->backsubjects)->where('semid',$sem->id)->count()>0)
                                        @foreach (collect($student->backsubjects)->where('semid',$sem->id)->values() as $backsubjectskey => $backsubjects)
                                        {{$backsubjectskey+1}}. {{ucwords(strtolower($backsubjects->subjectcode))}}
                                            <div style="line-height: 5px;">&nbsp;</div>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($sem->id == 1)
                                        @if($student->sem1status == 1) COMPLETE @else INCOMPLETE @endif
                                    @else 
                                        @if($student->sem2status == 1) COMPLETE @else INCOMPLETE @endif
                                    @endif
                                </td>
                                <td>
                                    @if($sem->id == 2)
                                        @if($student->sem1status == 1 && $student->sem2status == 1) REGULAR @else IRREGULAR @endif
                                    @endif
                                </td>
                            </tr>
                    @endforeach
                @endif
                @for($x = $countFemale; $x<$init; $x++)
                    <tr style="font-size: 9px !important;" nobr="true">
                        <td>&nbsp;</td>
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
            <div style="line-height: 50px;">&nbsp;</div><table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif" border="1">
                <thead style="text-align: center;">
                    <tr>
                        <th colspan="4" style="text-align: center;">SUMMARY TABLE 1ST SEM</th>
                    </tr>
                    <tr>
                        <th style="width: 35%;">STATUS</th>
                        <th style="width: 20%;">MALE</th>
                        <th style="width: 25%;">FEMALE</th>
                        <th style="width: 20%;">TOTAL</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: top;">
                    <tr>
                        <td>COMPLETE</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('gender','male')->where('sem1status','1')->count()}}</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('gender','female')->where('sem1status','1')->count()}}</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('sem1status','1')->count()}}</td>
                    </tr>
                    <tr>
                        <td>INCOMPLETE</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('gender','male')->where('sem1status','0')->count()}}</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('gender','female')->where('sem1status','0')->count()}}</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('sem1status','0')->count()}}</td>
                    </tr>
                    <tr>
                        <td>RETAINED</td>
                        <td style="text-align: center;">0</td>
                        <td style="text-align: center;">0</td>
                        <td style="text-align: center;">0</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('gender','male')->count()}}</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('gender','female')->count()}}</td>
                        <td style="text-align: center;">{{collect($students)->where('semid','1')->where('sem1status','0')->count()+collect($students)->where('semid','1')->where('sem1status','1')->count()}}</td>
                    </tr>
                    {{-- <tr>
                        <th>COMPLETE</th>
                        <th style="text-align: center;">
                            @php
                                $firstsemcompletemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='MALE')
                                    @if(collect($student->backsubjects)->where('semid',1)->count() == 0)
                                        @php
                                            $firstsemcompletemale+=1;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                            {{$firstsemcompletemale}}
                        </th>
                        <th style="text-align: center;">
                            @php
                                $firstsemcompletefemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='FEMALE')
                                    @if(collect($student->backsubjects)->where('semid',1)->count() == 0)
                                        @php
                                            $firstsemcompletefemale+=1;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                            {{$firstsemcompletefemale}}
                        </th>
                        <th style="text-align: center;">{{$firstsemcompletemale + $firstsemcompletefemale}}</th>
                    </tr>
                    <tr>
                        <th>INCOMPLETE</th>
                        <th style="text-align: center;">
                            @php
                                $firstsemincompletemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='MALE')
                                    @if(collect($student->backsubjects)->where('semid',1)->count() > 0)
                                        @php
                                            $firstsemincompletemale+=1;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                            {{$firstsemincompletemale}}
                        </th>
                        <th style="text-align: center;">
                            @php
                                $firstsemincompletefemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='FEMALE')
                                    @if(collect($student->backsubjects)->where('semid',1)->count() > 0)
                                        @php
                                            $firstsemincompletefemale+=1;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                            {{$firstsemincompletefemale}}
                        </th>
                        <th style="text-align: center;">{{$firstsemincompletemale + $firstsemincompletefemale}}</th>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <th style="text-align: center;">{{$firstsemcompletemale + $firstsemincompletemale}}</th>
                        <th style="text-align: center;">{{$firstsemcompletefemale + $firstsemincompletefemale}}</th>
                        <th style="text-align: center;">{{($firstsemcompletemale + $firstsemcompletefemale) + ($firstsemincompletemale + $firstsemincompletefemale)}}</th>
                    </tr> --}}
                </tbody>
            </table>
            <div style="line-height: 10px;">&nbsp;</div>
            <table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif" border="1">
                <thead style="text-align: center;">
                    <tr>
                        <th colspan="4" style="text-align: center;">SUMMARY TABLE 2ND SEM</th>
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
                        <td>COMPLETE</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('gender','male')->where('sem2status','1')->count()}}@endif</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('gender','female')->where('sem2status','1')->count()}}@endif</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','1')->count()}}@endif</td>
                    </tr>
                    <tr>
                        <td>INCOMPLETE</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('gender','male')->where('sem2status','0')->count()}}@endif</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('gender','female')->where('sem2status','0')->count()}}@endif</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->count()}}@endif</td>
                    </tr>
                    <tr>
                        <td>RETAINED</td>
                        <td style="text-align: center;">@if($sem->id == 2)0 @endif</td>
                        <td style="text-align: center;">@if($sem->id == 2)0 @endif</td>
                        <td style="text-align: center;">@if($sem->id == 2)0 @endif</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->where('gender','male')->count()+collect($students)->where('semid','2')->where('sem2status','1')->where('gender','male')->count()}}@endif</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->where('gender','female')->count()+collect($students)->where('semid','2')->where('sem2status','1')->where('gender','female')->count()}}@endif</td>
                        <td style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->count()+collect($students)->where('semid','2')->where('sem2status','1')->count()}}@endif</td>
                    </tr>
                    {{-- <tr>
                        <th>COMPLETE</th>
                        <th style="text-align: center;">
                            @php
                                $secondsemcompletemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='MALE')
                                    @if(collect($student->backsubjects)->where('semid',2)->count() == 0)
                                            @php
                                                $secondsemcompletemale+=1;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            {{$secondsemcompletemale}}
                        </th>
                        <th style="text-align: center;">
                            @php
                                $secondsemcompletefemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='FEMALE')
                                    @if(collect($student->backsubjects)->where('semid',2)->count() == 0)
                                            @php
                                                $secondsemcompletefemale+=1;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            {{$secondsemcompletefemale}}
                        </th>
                        <th style="text-align: center;">{{$secondsemcompletemale + $secondsemcompletefemale}}</th>
                    </tr>
                    <tr>
                        <th>INCOMPLETE</th>
                        <th style="text-align: center;">
                            @php
                                $secondsemincompletemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='MALE')
                                    @if(collect($student->backsubjects)->where('semid',2)->count() > 0)
                                            @php
                                                $secondsemincompletemale+=1;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            {{$secondsemincompletemale}}
                        </th>
                        <th style="text-align: center;">
                            @php
                                $secondsemincompletefemale = 0;
                            @endphp
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='FEMALE')
                                    @if(collect($student->backsubjects)->where('semid',2)->count() > 0)
                                            @php
                                                $secondsemincompletefemale+=1;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            {{$secondsemincompletefemale}}
                        </th>
                        <th style="text-align: center;">{{$secondsemincompletemale + $secondsemincompletefemale}}</th>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <th style="text-align: center;">{{$secondsemcompletemale + $secondsemincompletemale}}</th>
                        <th style="text-align: center;">{{$secondsemcompletefemale + $secondsemincompletefemale}}</th>
                        <th style="text-align: center;">{{($secondsemcompletemale + $secondsemcompletefemale) + ($secondsemincompletemale + $secondsemincompletefemale)}}</th>
                    </tr> --}}
                </tbody>
            </table>
            <div style="line-height: 10px;">&nbsp;</div>
            <table style="width: 100%;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif" border="1">
                <thead style="text-align: center;">
                    <tr>
                        <th colspan="4">SUMMARY TABLE (End of School Year Only)</th>
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
                        <th>REGULAR</th>
                        @if($sem->id == 2)
                        <th style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->where('gender','male')->count()+collect($students)->where('semid','2')->where('sem2status','1')->where('gender','male')->count()}}@endif</th>
                        <th style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->where('gender','female')->count()+collect($students)->where('semid','2')->where('sem2status','1')->where('gender','female')->count()}}@endif</th>
                        <th style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->count()+collect($students)->where('semid','2')->where('sem2status','1')->count()}}@endif</th>
                        
                        @else
                        <th style="text-align: center;">&nbsp;</th>
                        <th style="text-align: center;">&nbsp;</th>
                        <th style="text-align: center;">&nbsp;</th>
                        @endif
                        <!--<th style="text-align: center;">-->
                        <!--    @if($sem->id == 2)-->
                        <!--        {{$firstsemcompletemale + $secondsemcompletemale}}-->
                        <!--    @endif-->
                        <!--</th>-->
                        <!--<th style="text-align: center;">-->
                        <!--    @if($sem->id == 2)-->
                        <!--        {{$firstsemcompletefemale + $secondsemcompletefemale}}-->
                        <!--    @endif-->
                        <!--</th>-->
                        <!--<th style="text-align: center;">-->
                        <!--    @if($sem->id == 2)-->
                        <!--        {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemcompletefemale + $secondsemcompletefemale)}}-->
                        <!--    @endif-->
                        <!--</th>-->
                    </tr>
                    <tr>
                        <th>IRREGULAR</th>
                        <th style="text-align: center;">
                            @if($sem->id == 2)
                                {{$firstsemincompletemale + $secondsemincompletemale}}
                            @endif
                        </th>
                        <th style="text-align: center;">
                            @if($sem->id == 2)
                                {{$firstsemincompletefemale + $secondsemincompletefemale}}
                            @endif
                        </th>
                        <th style="text-align: center;">
                            @if($sem->id == 2)
                                {{($firstsemincompletemale + $secondsemincompletemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <td>RETAINED</td>
                        <td style="text-align: center;">
                            @if($sem->id == 2)0 @endif</td>
                        <td style="text-align: center;">@if($sem->id == 2)0 @endif</td>
                        <td style="text-align: center;">@if($sem->id == 2)0 @endif</td>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        @if($sem->id == 2)
                        <th style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->where('gender','male')->count()+collect($students)->where('semid','2')->where('sem2status','1')->where('gender','male')->count()}}@endif</th>
                        <th style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->where('gender','female')->count()+collect($students)->where('semid','2')->where('sem2status','1')->where('gender','female')->count()}}@endif</th>
                        <th style="text-align: center;">@if($sem->id == 2){{collect($students)->where('semid','2')->where('sem2status','0')->count()+collect($students)->where('semid','2')->where('sem2status','1')->count()}}@endif</th>
                        
                        @else
                        <th style="text-align: center;">&nbsp;</th>
                        <th style="text-align: center;">&nbsp;</th>
                        <th style="text-align: center;">&nbsp;</th>
                        @endif<!--<th style="text-align: center;">-->
                        <!--    @if($sem->id == 2)-->
                        <!--        {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)}}-->
                        <!--    @endif-->
                        <!--</th>-->
                        <!--<th style="text-align: center;">-->
                        <!--    @if($sem->id == 2)-->
                        <!--        {{($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}-->
                        <!--    @endif-->
                        <!--</th>-->
                        <!--<th style="text-align: center;">-->
                        <!--    @if($sem->id == 2)-->
                        <!--        {{(($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)) + (($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale))}}-->
                        <!--    @endif-->
                        <!--</th>-->
                    </tr>
                </tbody>
            </table>
            <div style="line-height: 30px;">&nbsp;</div>
            <div style="padding-top:5%;width: 100%; font-size: 10px;">
                
                <table style="width: 100%; page-break-inside: avoid;">
                    <tr>
                        <td>Prepared by:</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid black; text-align: center;">{{strtoupper($getTeacherName->firstname.' '.$getTeacherName->middlename.' '.$getTeacherName->lastname.' '.$getTeacherName->suffix)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <span style="text-align: center; font-size: 9px; line-height: 10px;">
                                Signature of Class Adviser over Printed Name
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                @if(count($signatoriesv2)>0)
                    <table style="width: 100%; page-break-inside: avoid;">
                        @foreach($signatoriesv2 as $signatory)
                            <tr>
                                <td style="@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') font-size: 11px;@else font-size: 9px;@endif">{{$signatory->title}}</td>
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
                    &nbsp;<br/>
                @else
                    
                    <table style="width: 100%; page-break-inside: avoid;">
                        <tr>
                            <td>Certified Correct by:</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black; text-align: center;">{{$signatory_name1}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <span style="text-align: center; font-size: 9px; line-height: 10px;">
                                    Signature of School Head over Printed Name
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        {{-- <tr>
                            <td>&nbsp;</td>
                        </tr> --}}
                    </table>
                    <br/>
                    <table style="width: 100%; page-break-inside: avoid;">
                        <tr>
                            <td>Reviewed by:</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black; text-align: center;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') MAJARANI MACARAEG JACINTO EDD, CESO IV @else  {{$signatory_name2}} @endif</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <span style="text-align: center; font-size: 9px; line-height: 10px;">
                                    Signature of Division Representative over Printed Name
                                </span>
                            </td>
                        </tr>
                    </table>
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 10px;">
            <div style="line-height: 10px;">&nbsp;</div><span id="guidelines"><strong>GUIDELINES:</strong>
                <br><em>This form shall be accomplished after each semester in a school year,  leaving the End of School Year Status Column and Summary Table for End of School Year Status blank/unfilled at the end of the 1st Semester.  These data elements shall be filled up only after the 2nd semester or at the end of the School Year. 
                </em>
            <br>
            <br><strong>INDICATORS:</strong>
                <br><em><strong>End of Semester Status</strong></em>
                <br>
                <span style="padding-left:5%"> 
                    <strong>Complete</strong> - number of learners who completed/satisfied the requirements in all subject areas (with grade of at least 75%)
                </span>
                <br>
                <span style="padding-left:5%"> 
                    <strong>Incomplete</strong> - number of learners who did not meet expectations in one or more subject areas, regardless of number of subjects failed (with grade less than 75%)
                </span>
                <br>
                <span style="padding-left:5%"> 
                    <em>
                        <strong>Note:</strong> Do not include learners who are No Longer in School (<strong>NLS</strong>)
                    </em>
                </span>
                <br>
                <br><em><strong>End of School Year Status</strong></em>
                <br>
                <span style="padding-left:5%"> 
                    <strong>Regular</strong> - number of learners who completed/satisfied requirements in all subject areas  both in the 1st and 2nd semester
                </span>
                <br>
                <span style="padding-left:5%"> 
                    <strong>Irregular</strong> - number of learners who were not able to satisfy/complete requirements in one or both semesters
                </span>
            </span>
        </td>
    </tr>
</table>