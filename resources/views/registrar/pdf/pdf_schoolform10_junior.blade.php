<style>
    * { font-family: Arial, Helvetica, sans-serif; }
    @page { margin: 20px;  size: {{$papersize != null ? $papersize : '8.5in 13in'}} ;} /*8.5 13*/

    #table1 td{
        padding: 0px;
    }
    table {
        border-collapse: collapse;
    }
    #table2{
        margin-top: 2px;
        font-size: 11px;
    }

    input[type="checkbox"] {
    /* position: relative; */
    top: 2px;
    box-sizing: content-box;
    width: 14px;
    height: 14px;
    margin: 0 5px 0 0;
    cursor: pointer;
    -webkit-appearance: none;
    border-radius: 2px;
    background-color: #fff;
    border: 1px solid #b7b7b7;
    }

    input[type="checkbox"]:before {
    content: '';
    display: block;
    }

    input[type="checkbox"]:checked:before {
    width: 4px;
    height: 9px;
    margin: 0px 4px;
    border-bottom: 2px solid ;
    border-right: 2px solid ;
    transform: rotate(45deg);
    }
    .text-center{
        text-align: center;
    }
</style>
@if(strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308')
    <table style="width: 100%" id="table1">
        <tr>
            <td width="6%" style="text-align: left;"><sup style="font-size: 9px;">SF10-JHS</sup></td>
            <td width="12%" rowspan="6" style="text-align: right;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="17%" style="text-align:left;" rowspan="6"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Region X-Northern Mindanao</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Division of Ozamiz City</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 12px; font-weight: bold;">Learner's Permanent Academic Record for Junior High School (SF10-JHS)</td>
        </tr>
        <tr style="line-height: 10px;font-size: 11px;">
            <td style="text-align:center; font-weight: bold;" colspan="4">(Formerly Form 137)</td>
        </tr>
    </table>
@else
    @if($format == 'school')
    <table style="width: 100%" >
        <tr>
            <td width="15%" rowspan="4" style="vertical-align:top;"><sup style="font-size: 9px;">SF10-JHS</sup></td>
            <td width="10%"rowspan="4" style="text-align: right;">
            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') --}}
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px">
            {{-- @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            @else
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
            @endif --}}
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="10%" style="text-align:right;"  rowspan="4"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
            <td width="15%" rowspan="7" style="border: 1px solid #ddd; vertical-align: middle; text-align: center; font-size: 10px;"><br/>Photo<br/>1x1</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 16px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</td>
        </tr>
        <tr>
            <td rowspan="3" style="border: 1px solid #ddd; vertical-align: top; font-size: 8px;"><sup>Forwarded to:</sup></td>
            <td colspan="3" style="line-height: 10px;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center; font-size: 14px; font-weight: bold;">Learner's Permanent Academic Record for Junior High School (SF10-JHS)</td>
        </tr>
        <tr style="line-height: 5px;font-size: 11px;">
            <td colspan="3" style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
        </tr>
    </table>
    @else
    <table style="width: 100%" id="table1">
        <tr>
            <td width="15%" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif><sup style="font-size: 9px;">SF10-JHS</sup><br/>
            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') --}}
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            {{-- @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            @else
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
            @endif --}}
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci') --}}
            <td width="15%" style="text-align:right;"  @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif>
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
        </td>
            {{-- @else
            <td width="15%" style="text-align:right;"  @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
            @endif --}}
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">Learner's Permanent Academic Record for Junior High School </td>
        </tr>
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
        <tr>
            <td style="text-align:center; font-size: 20px; font-weight: bold; color: green; ">Holy Cross of Babak, Inc.</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-JHS)</td>
        </tr>
        <tr style="font-size: 11px;">
            <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
        </tr>
        @else
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-JHS)</td>
        </tr>
        <tr style="line-height: 5px;font-size: 11px;">
            <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
        </tr>
        @endif
    </table>
    @endif
@endif
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%" id="table2">
    <tr>
        <td colspan="8" style="text-align: center; font-size: 13px; font-weight: bold; background-color: #aba9a9; border: 1px solid black;">LEARNER'S INFORMATION</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->lastname}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->firstname}}</td>
        <td style="width: 15%;">NAME EXTN. (Jr,I,II)</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->suffix}}</td>
        <td style="width: 10%;">MIDDLE NAME</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->middlename ?? null}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;" id="table3">
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 20%;">Learner Reference Number (LRN):</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
        <td style="width: 20%; text-align: right;">Birthdate (mm/dd/yyyy):</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{date('m/d/Y',strtotime($studinfo->dob))}}</td>
        <td style="width: 10%; text-align: right;">Sex:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table4">
    <tr>
        <td style="border: 1px solid black; background-color: #bababa">
            ELIGIBILITY FOR JHS ENROLMENT
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<div style="width: 100%; border: 1px solid black; padding-top: 4px;">
    <table style="width: 100%; font-size: 11px;" id="table5">
        <tr style="font-style: italic;">
            <td><input type="checkbox" name="check-1"@if($eligibility->completer == 1) checked @endif>Elementary School Completer</td>
            <td>General Average: {{$eligibility->genave}}</td>
            <td>Citation: (If Any)<u>{{$eligibility->citation}}</u></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px;" id="table6">
        <tr>
            <td style="width: 13%;">Name of School:</td>
            <td style="width: 25%; border-bottom: 1px solid black;">{{$eligibility->schoolname}}</td>
            <td style="width: 10%;">School ID:</td>
            <td style="border-bottom: 1px solid black;">{{$eligibility->schoolid}}</td>
            <td style="width: 15%;">Address of School:</td>
            <td style="width: 20%; border-bottom: 1px solid black;">{{$eligibility->schooladdress}}</td>
        </tr>
    </table>
    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
</div>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 11px;" id="table7" >
    <tr>
        <td colspan="4">Other Credential Presented</td>
    </tr>
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check-1"@if($eligibility->peptpasser == 1) checked @endif>PEPT Passer &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility->peptrating}}&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check-1"@if($eligibility->alspasser == 1) checked @endif>ALS A & E Passer &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility->alsrating}}&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td style="width: 18%;"><input type="checkbox" name="check-1">Others (Pls. Specify):</td>
        <td style="width: 16%; border-bottom: 1px solid black;">{{$eligibility->specifyothers}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;" id="table8" >
    <tr>
        <td style="width: 35%; text-align: right;">Date of Examination/Assessment (mm/dd/yyyy):</td>
        <td style="width: 10%; border-bottom: 1px solid black;">@if($eligibility->examdate != null) {{date('m/d/Y',strtotime($eligibility->examdate))}} @endif</td>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Name and Address of Testing Center:</td>
        <td style="border-bottom: 1px solid black;" colspan="2">{{$eligibility->centername}}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table9">
    <tr>
        <td style="border: 1px solid black; background-color: #bababa">
            SCHOLASTIC RECORD
        </td>
    </tr>
</table>
@php
    $tablescount = 2;
    $tablescount-= count($records);
@endphp
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
    @if(count($records)>0)
        @foreach($records as $record)      
        
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px; page-break-inside: avoid;" border="1">
                <thead>
                    <tr>
                        <td colspan="7">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{$record[0]->schoolname}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 15%; border-bottom: 1px solid black;">{{$record[0]->schoolid}}</td>
                                    <td style="width: 5%;">District:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{$record[0]->schooldistrict}}</td>
                                    <td style="width: 5%;">Division:</td>
                                    <td style="width: 15%; border-bottom: 1px solid black;">{{$record[0]->schooldivision}}</td>
                                    <td style="width: 5%;">Region:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">
                                        @if(isset($record[0]->schoolregion))
                                            @if (str_contains(strtolower($record[0]->schoolregion), 'region')) 
                                            {{str_replace('REGION', '', strtoupper($record[0]->schoolregion))}}
                                            @else
                                            {{isset($record[0]->schoolregion) ? $record[0]->schoolregion : null}}
                                            @endif
                                        @else
                                            {{isset($record[0]->schoolregion) ? $record[0]->schoolregion : null}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 15%;">Classified as Grade:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[0]->levelname)}}</td>
                                    <td style="width: 15%;">Section:</td>
                                    <td style="width: 30%; border-bottom: 1px solid black;">{{$record[0]->sectionname}}</td>
                                    <td style="width: 20%;">SchoolYear:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{$record[0]->sydesc}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 20%;">Name of Adviser/Teacher:</td>
                                    <td style="border-bottom: 1px solid black;">{{$record[0]->teachername}}</td>
                                    <td style="width: 10%;">Signature:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" style="width: 40%;">LEARNING AREAS</th>
                        <th colspan="4">Quarterly</th>
                        <th rowspan="2" style="width: 10%;">Final<br/>Rating</th>
                        <th rowspan="2" style="width: 10%;">Remarks</th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">1</th>
                        <th style="width: 8%;">2</th>
                        <th style="width: 8%;">3</th>
                        <th style="width: 8%;">4</th>
                    </tr>
                </thead>
                @if(count($record[0]->grades)>0)
                    @foreach($record[0]->grades as $grade)
                        @if($record[0]->type == 2)
                            @if(strtolower($grade->subjtitle) != 'general average')
                                <tr>
                                    <td>@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE ==1 )&nbsp;&nbsp;&nbsp;@endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                    <td class="text-center">{{$grade->quarter1 > 0 ? $grade->quarter1 : ''}}</td>
                                    <td class="text-center">{{$grade->quarter2 > 0 ? $grade->quarter2 : ''}}</td>
                                    <td class="text-center">{{$grade->quarter3 > 0 ? $grade->quarter3 : ''}}</td>
                                    <td class="text-center">{{$grade->quarter4 > 0 ? $grade->quarter4 : ''}}</td>
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' && $grade->inMAPEH ==1)
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    @else
                                    <td class="text-center">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center">{{$grade->remarks}}</td>
                                    @endif
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE ==1 )&nbsp;&nbsp;&nbsp;@endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                <td class="text-center">{{$grade->quarter1}}</td>
                                <td class="text-center">{{$grade->quarter2}}</td>
                                <td class="text-center">{{$grade->quarter3}}</td>
                                <td class="text-center">{{$grade->quarter4}}</td>
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' && $grade->inMAPEH ==1)
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                @else
                                <td class="text-center">{{$grade->finalrating}}</td>
                                <td class="text-center">{{$grade->remarks}}</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                    @if($record[0]->type == 1)
                        @if(count($record[0]->subjaddedforauto)>0)
                            @foreach($record[0]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td>{{$customsubjgrade->subjdesc}}</td>
                                    <td class="text-center">{{$customsubjgrade->q1}}</td>
                                    <td class="text-center">{{$customsubjgrade->q2}}</td>
                                    <td class="text-center">{{$customsubjgrade->q3}}</td>
                                    <td class="text-center">{{$customsubjgrade->q4}}</td>
                                    <td class="text-center">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(DB::table('schoolinfo')->first()->schoolid == '405308') {{--fmcma--}}
                            <tr style="font-weight: bold;">
                                <td></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{collect($record[0]->generalaverage)->first()->finalrating}}</td>
                                <td class="text-center">@if(collect($record[0]->generalaverage)->first()->finalrating > 0){{collect($record[0]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif</td>
                            </tr>
                        @else
                            <tr style="font-weight: bold;">
                                <td></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{collect($record[0]->generalaverage)->first()->finalrating}}</td>
                                <td class="text-center">
                                    @if(collect($record[0]->generalaverage)->first()->finalrating > 0){{ collect($record[0]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif
                                </td>
                                {{-- <td class="text-center">{{number_format(collect($record[0]->grades)->sum('finalrating')/count($record[0]->grades))}}</td>
                                <td class="text-center">{{ number_format(collect($record[0]->grades)->sum('finalrating')/count($record[0]->grades)) >= 75 ? 'PASSED' : 'FAILED'}}</td> --}}
                            </tr>
                        @endif
                    @elseif($record[0]->type == 2)
                        @if(count($record[0]->grades) > 1)
                            @foreach($record[0]->grades as $grade)
                                @if(strtolower($grade->subjtitle) == 'general average')
                                    <tr style="font-weight: bold;">
                                        <td></td>
                                        <td colspan="4">General Average</td>
                                        <td class="text-center">{{$grade->finalrating}}</td>
                                        @if(DB::table('schoolinfo')->first()->schoolid == '405308') {{--fmcma--}}
                                        <td class="text-center">{{$grade->remarks}}</td>
                                        @else
                                        <td class="text-center">@if($grade->finalrating > 0){{$grade->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif</td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @else
                    @for($x=0; $x<$maxgradecount; $x++)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            </table>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px;" border="1">
                @if(count($record[0]->remedials)>0)
                    @if(collect($record[0]->remedials)->contains('type','2'))
                        @foreach($record[0]->remedials as $remedial)
                            @if($remedial->type == 2)
                                <tr>
                                    <td style="width: 30%;">Remedial Classes</td>
                                    <td colspan="4">Conducted from:&nbsp;&nbsp;{{$remedial->datefrom}};&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to &nbsp;&nbsp;{{$remedial->dateto}};</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td style="width: 30%;">Remedial Classes</td>
                            <td colspan="4">Conducted from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to </td>
                        </tr>
                    @endif
                    <tr>
                        <th>Learning Ares</th>
                        <th>Final Rating</th>
                        <th>Remedial Class Mark</th>
                        <th>Recomputed Final</th>
                        <th style="width: 10%;">Remarks</th>
                    </tr>
                    @if(collect($record[0]->remedials)->contains('type','1'))
                        @foreach($record[0]->remedials as $remedial)
                            @if($remedial->type == 1)
                                <tr>
                                    <td>{{$remedial->subjectname}}</td>
                                    <td class="text-center">{{$remedial->finalrating}}</td>
                                    <td class="text-center">{{$remedial->remclassmark}}</td>
                                    <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                    <td class="text-center">{{$remedial->remarks}}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    @if($columnremedials>count(collect($record[0]->remedials)->where('type','1')))
                        @php
                            $columnremedialsdisplay = $columnremedials-count(collect($record[0]->remedials)->where('type','1'));
                        @endphp        
                        @for($x = 0; $columnremedialsdisplay>$x; $columnremedialsdisplay--)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    @endif   
                @else
                    <tr>
                        <td style="width: 30%;">Remedial Classes</td>
                        <td colspan="4">Conducted from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to </td>
                    </tr>
                    <tr>
                        <th>Learning Ares</th>
                        <th>Final Rating</th>
                        <th>Remedial Class Mark</th>
                        <th>Recomputed Final</th>
                        <th style="width: 10%;">Remarks</th>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            </table>
            @endif
            <div style="width: 100%; line-height: 4px;">&nbsp;</div>
            @if(count($record)==1)
                <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px; page-break-inside: avoid;" border="1">
                    <thead>
                        <tr>
                            <td colspan="7">
                                <table style="width: 100%; font-size: 10px;">
                                    <tr>
                                        <td style="width: 5%;">School:</td>
                                        <td style="width: 23%; border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 7%;">School ID:</td>
                                        <td style="width: 15%; border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 5%;">District:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 5%;">Division:</td>
                                        <td style="width: 15%; border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 5%;">Region:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; font-size: 10px;">
                                    <tr>
                                        <td style="width: 15%;">Classified as Grade:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 15%;">Section:</td>
                                        <td style="width: 30%; border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 20%;">SchoolYear:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; font-size: 10px;">
                                    <tr>
                                        <td style="width: 20%;">Name of Adviser/Teacher:</td>
                                        <td style="border-bottom: 1px solid black;">&nbsp;</td>
                                        <td style="width: 10%;">Signature:</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                    </tr>
                                </table>
                                <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <th rowspan="2" style="width: 40%;">LEARNING AREAS</th>
                            <th colspan="4">Quarterly</th>
                            <th rowspan="2" style="width: 10%;">Final<br/>Rating</th>
                            <th rowspan="2" style="width: 10%;">Remarks</th>
                        </tr>
                        <tr>
                            <th style="width: 8%;">1</th>
                            <th style="width: 8%;">2</th>
                            <th style="width: 8%;">3</th>
                            <th style="width: 8%;">4</th>
                        </tr>
                    </thead>
                    @for($x=0; $x<count($record[0]->grades); $x++)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                    <tr style="font-weight: bold;">
                        <td>&nbsp;</td>
                        <td colspan="4">General Average</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            @elseif(count($record)==2)
                <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px; page-break-inside: avoid;" border="1">
                    <thead>
                        <tr>
                            <td colspan="7">
                                <table style="width: 100%; font-size: 10px;">
                                    <tr>
                                        <td style="width: 5%;">School:</td>
                                        <td style="width: 23%; border-bottom: 1px solid black;">{{$record[1]->schoolname}}</td>
                                        <td style="width: 7%;">School ID:</td>
                                        <td style="width: 15%; border-bottom: 1px solid black;">{{$record[1]->schoolid}}</td>
                                        <td style="width: 5%;">District:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">{{$record[1]->schooldistrict}}</td>
                                        <td style="width: 5%;">Division:</td>
                                        <td style="width: 15%; border-bottom: 1px solid black;">{{$record[1]->schooldivision}}</td>
                                        <td style="width: 5%;">Region:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">{{$record[1]->schoolregion}}</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; font-size: 10px;">
                                    <tr>
                                        <td style="width: 15%;">Classified as Grade:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[1]->levelname)}}</td>
                                        <td style="width: 15%;">Section:</td>
                                        <td style="width: 30%; border-bottom: 1px solid black;">{{$record[1]->sectionname}}</td>
                                        <td style="width: 20%;">SchoolYear:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">{{$record[1]->sydesc}}</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; font-size: 10px;">
                                    <tr>
                                        <td style="width: 20%;">Name of Adviser/Teacher:</td>
                                        <td style="border-bottom: 1px solid black;">{{$record[1]->teachername}}</td>
                                        <td style="width: 10%;">Signature:</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                    </tr>
                                </table>
                                <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <th rowspan="2" style="width: 40%;">LEARNING AREAS</th>
                            <th colspan="4">Quarterly</th>
                            <th rowspan="2" style="width: 10%;">Final<br/>Rating</th>
                            <th rowspan="2" style="width: 10%;">Remarks</th>
                        </tr>
                        <tr>
                            <th style="width: 8%;">1</th>
                            <th style="width: 8%;">2</th>
                            <th style="width: 8%;">3</th>
                            <th style="width: 8%;">4</th>
                        </tr>
                    </thead>
                    @if(count($record[1]->grades)>0)
                        @foreach($record[1]->grades as $grade)
                            @if($record[1]->type == 2)
                                @if(strtolower($grade->subjtitle) != 'general average')
                                    <tr>
                                        <td>@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE == 1 )&nbsp;&nbsp;&nbsp;@endif @endif  @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                        <td class="text-center">{{$grade->quarter1 > 0 ? $grade->quarter1 : ''}}</td>
                                        <td class="text-center">{{$grade->quarter2 > 0 ? $grade->quarter2 : ''}}</td>
                                        <td class="text-center">{{$grade->quarter3 > 0 ? $grade->quarter3 : ''}}</td>
                                        <td class="text-center">{{$grade->quarter4 > 0 ? $grade->quarter4 : ''}}</td>
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' && $grade->inMAPEH ==1)
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        @else
                                        <td class="text-center">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                        <td class="text-center">{{$grade->remarks}}</td>
                                        @endif
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>@if(isset($grade->inMAPEH) ) @if($grade->inMAPEH ==1 )&nbsp;&nbsp;&nbsp;&nbsp;@endif @endif @if(isset($grade->inTLE))@if($grade->inTLE == 1 )&nbsp;&nbsp;&nbsp;@endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                    <td class="text-center">{{$grade->quarter1}}</td>
                                    <td class="text-center">{{$grade->quarter2}}</td>
                                    <td class="text-center">{{$grade->quarter3}}</td>
                                    <td class="text-center">{{$grade->quarter4}}</td>
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' && $grade->inMAPEH ==1)
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    @else
                                    <td class="text-center">{{$grade->finalrating}}</td>
                                    <td class="text-center">{{$grade->actiontaken}}</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                        @if($record[1]->type == 1)
                            @if(count($record[1]->subjaddedforauto)>0)
                                @foreach($record[1]->subjaddedforauto as $customsubjgrade)
                                    <tr>
                                        <td>{{$customsubjgrade->subjdesc}}</td>
                                        <td class="text-center">{{$customsubjgrade->q1}}</td>
                                        <td class="text-center">{{$customsubjgrade->q2}}</td>
                                        <td class="text-center">{{$customsubjgrade->q3}}</td>
                                        <td class="text-center">{{$customsubjgrade->q4}}</td>
                                        <td class="text-center">{{$customsubjgrade->finalrating}}</td>
                                        <td class="text-center">{{$customsubjgrade->actiontaken}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(DB::table('schoolinfo')->first()->schoolid == '405308') {{--fmcma--}}
                                <tr style="font-weight: bold;">
                                    <td></td>
                                    <td colspan="4">General Average</td>
                                    <td class="text-center">{{number_format(collect($record[1]->generalaverage)->first()->finalrating)}}</td>
                                    <td class="text-center">@if(collect($record[1]->generalaverage)->first()->finalrating > 0){{collect($record[1]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif</td>
                                </tr>
                            @else
                                <tr style="font-weight: bold;">
                                    <td></td>
                                    <td colspan="4">General Average</td>
                                    <td class="text-center">{{collect($record[1]->generalaverage)->first()->finalrating}}</td>
                                    <td class="text-center">
                                        @if(collect($record[1]->generalaverage)->first()->finalrating > 0){{ collect($record[1]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif
                                    </td>
                                    {{-- <td class="text-center" style="text-align: center;">{{number_format(collect($record[1]->grades)->where('subjtitle','!=','General Average')->sum('finalrating')/count($record[1]->grades))}}</td>
                                    <td style="text-align: center;">{{number_format(collect($record[1]->grades)->where('subjtitle','!=','General Average')->sum('finalrating')/count($record[1]->grades)) > 74 ? 'PASSED' : 'FAILED'}}</td> --}}
                                </tr>
                            @endif
                        @elseif($record[1]->type == 2)
                            @if(count($record[1]->grades) > 1)
                                @foreach($record[1]->grades as $grade)
                                    @if(strtolower($grade->subjtitle) == 'general average')
                                        <tr style="font-weight: bold;">
                                            <td></td>
                                            <td colspan="4">General Average</td>
                                            <td class="text-center">{{$grade->finalrating}}</td>
                                            @if(DB::table('schoolinfo')->first()->schoolid == '405308') {{--fmcma--}}
                                            <td class="text-center">{{$grade->remarks}}</td>
                                            @else
                                            <td class="text-center">@if($grade->finalrating > 0){{$grade->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @else
                        @for($x=0; $x<$maxgradecount; $x++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    @endif
                </table>
                
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px;" border="1">
                    @if(count($record[1]->remedials)>0)
                        @if(collect($record[1]->remedials)->contains('type','2'))
                            @foreach($record[1]->remedials as $remedial)
                                @if($remedial->type == 2)
                                    <tr>
                                        <td style="width: 30%;">Remedial Classes</td>
                                        <td colspan="4">Conducted from:&nbsp;&nbsp;{{$remedial->datefrom}};&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to &nbsp;&nbsp;{{$remedial->dateto}};</td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td style="width: 30%;">Remedial Classes</td>
                                <td colspan="4">Conducted from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Learning Ares</th>
                            <th>Final Rating</th>
                            <th>Remedial Class Mark</th>
                            <th>Recomputed Final</th>
                            <th style="width: 10%;">Remarks</th>
                        </tr>
                        @if(collect($record[1]->remedials)->contains('type','1'))
                            @foreach($record[1]->remedials as $remedial)
                                @if($remedial->type == 1)
                                    <tr>
                                        <td>{{$remedial->subjectname}}</td>
                                        <td class="text-center">{{$remedial->finalrating}}</td>
                                        <td class="text-center">{{$remedial->remclassmark}}</td>
                                        <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                        <td class="text-center">{{$remedial->remarks}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        @if($columnremedials>count(collect($record[1]->remedials)->where('type','1')))
                            @php
                                $columnremedialsdisplay = $columnremedials-count(collect($record[1]->remedials)->where('type','1'));
                            @endphp        
                            @for($x = 0; $columnremedialsdisplay>$x; $columnremedialsdisplay--)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                        @endif   
                    @else
                        <tr>
                            <td style="width: 30%;">Remedial Classes</td>
                            <td colspan="4">Conducted from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to </td>
                        </tr>
                        <tr>
                            <th>Learning Ares</th>
                            <th>Final Rating</th>
                            <th>Remedial Class Mark</th>
                            <th>Recomputed Final</th>
                            <th style="width: 10%;">Remarks</th>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endif
                </table>
                @endif
            @endif
            <div style="width: 100%; line-height: 4px;">&nbsp;</div>
        @endforeach
    @endif
    <div style="width: 100%; line-height: 4px;">&nbsp;</div>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
        <div style="width: 100%;page-break-inside: avoid;">
            <div style="width: 100%; font-size: 11px;">For Transfer Out /Elementary School Completer Only</div>
                <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                    <tr>
                        <td colspan="10" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                            CERTIFICATION
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 3%;"></td>
                        <td colspan="8" style="text-align: justify;">
                            I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                        </td>
                        <td style="width: 3%;"></td>
                    </tr>
                    <tr>
                        <td style="width: 3%;"></td>
                        <td style="width: 10%;">Name of School:</td>
                        <td style="width: 31%; border-bottom: 1px solid black;" colspan="3">{{strtoupper(DB::table('schoolinfo')->first()->schoolname)}}</td>
                        <td style="width: 7%;">School ID:</td>
                        <td style="width: 6%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->schoolid}}</td>
                        <td style="width: 16%;">Last School Year Attended:</td>
                        <td style="width: 9%; border-bottom: 1px solid black;"></td>
                        <td style="width: 3%;"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Copy for:</td>
                        <td colspan="8">{{$footer->purpose}}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td ></td>
                        <td style="border-bottom: 1px solid black; text-align: center;">{{date('M d, Y')}}</td>
                        <td>&nbsp;</td>
                        <td colspan="4" style="border-bottom: 1px solid black; text-align: center; font-weight: bold;">GERALD C. PANIAMOGAN, LPT, MBA</td>
                        <td></td>
                        <td>&nbsp;</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td ></td>
                        <td class="text-center">Date</td>
                        <td>&nbsp;</td>
                        <td colspan="4" class="text-center" style="font-size: 10px;">School Registrar</td>
                        <td colspan="3"style="text-align: center;">(Affix School Seal here)</td>
                    </tr>
                </table>
        </div>
    @else
        <div style="width: 100%;page-break-inside: avoid;">
            @if(strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                <table style="width: 100%; font-size: 11px; border: 1px solid black; border-bottom: none;">
                    <tr>
                        <td colspan="7" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                            CERTIFICATION
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 3%;">&nbsp;</td>
                        <td colspan="5" style="text-align: justify;">
                            <p style="text-align: justify;">I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}}</u> with LRN <u>{{$studinfo->lrn}}</u> and  that he/she is  eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.</p>
                        </td>
                        <td style="width: 3%;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 3%;"></td>
                        <td colspan="6">Name of School: <u>{{DB::table('schoolinfo')->first()->schoolname}}</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  School ID: <u>{{DB::table('schoolinfo')->first()->schoolid}}</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Last School Year Attended: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 11px; border: 1px solid black; border-top: none;">
                    <tr>
                        <td></td>
                        <td style="border-bottom: 1px solid black; text-align: center; width: 10%;">{{date('m/d/Y')}}</td>
                        <td></td>
                        <td style="border-bottom: 1px solid black; text-align: center; width: 40%;" colspan="2">{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}</td>
                        <td></td>
                        {{-- <td></td> --}}
                        <td style="width: 35%;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;">Date
                        </td>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;" colspan="2">Name of Principal/School Head over Printed Name</td>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;">(Affix School Seal Here)</td>
                    </tr>
                </table>
                {{-- <span style="font-size: 11px;">
                    May add Certification Box if needed
                </span> --}}
                <div style="font-size: 11px; float: right; text-align: right; font-style: italic;">
                    SFRT Revised 2017
                </div>
            @else
                {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                        <tr>
                            <td colspan="7" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                                CERTIFICATION
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 3%;"></td>
                            <td colspan="5" style="text-align: justify;">
                                I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->suffix}}</u> with LRN <u>{{$studinfo->lrn}}</u>
                            </td>
                            <td style="width: 3%;"></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>REMARKS</strong></td>
                            <td></td>
                            <td colspan="4">Copy for <u><strong>{{$footer->purpose}}</strong></u></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="border-bottom: 1px solid black; text-align: center;">{{date('M d, Y')}}</td>
                            <td></td>
                            <td style="border-bottom: 1px solid black; text-align: center;">{{$footer->recordsincharge}}</td>
                            <td></td>
                            <td style="text-align: center;"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; font-weight: bold;">Date</td>
                            <td></td>
                            <td style="text-align: center; font-weight: bold;">Records In-Charge</td>
                            <td></td>
                            <td style="text-align: center; font-weight: bold;"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="4" style="font-weight: bold;">SCHOOL SEAL</td>
                            <td style="border-bottom: 1px solid black; text-align: center; font-weight: bold;"></td>
                            <td></td>
                        </tr>
                    </table>
                    <span style="font-size: 11px; float: right; text-align: right; font-style: italic;">
                        SFRT Revised 2017
                    </span>
                @else --}}
                    <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                        <tr>
                            <td colspan="7" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                                CERTIFICATION
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 3%;"></td>
                            <td colspan="5" style="text-align: justify;">
                                I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;{{$footer->admissiontograde ?? ''}}&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                            </td>
                            <td style="width: 3%;"></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>REMARKS</strong></td>
                            <td></td>
                            <td colspan="4">Copy for <u><strong>{{$footer->purpose}}</strong></u></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{$footer->classadviser}}</td>
                            <td></td>
                            <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{$footer->recordsincharge}}</td>
                            <td></td>
                            <td style="width: 20%; border-bottom: 1px solid black; text-aligsn: center; width: 25%;">{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; font-weight: bold;">Class Adviser</td>
                            <td></td>
                            <td style="text-align: center; font-weight: bold;">Records In-Charge</td>
                            <td></td>
                            <td style="text-align: center; font-weight: bold;">School Head</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="4" style="font-weight: bold;">SCHOOL SEAL</td>
                            <td style="border-bottom: 1px solid black; text-align: center; font-weight: bold;">{{date('M d, Y')}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td style="text-align: center;">Date</td>
                            <td></td>
                        </tr>
                    </table>
                    <span style="font-size: 11px;">
                        May add Certification Box if needed
                    </span>
                    <span style="font-size: 11px; float: right; text-align: right; font-style: italic;">
                        SFRT Revised 2017
                    </span>
                {{-- @endif --}}
            @endif
        </div>
    @endif