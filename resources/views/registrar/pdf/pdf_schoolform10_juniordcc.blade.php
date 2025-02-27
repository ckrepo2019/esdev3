<style>
    * { font-family: Arial, Helvetica, sans-serif; }
    @page { margin: 20px;  size: 8.5in 13in ;}

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

<table style="width: 100%" id="table1">
    <tr>
        <td width="15%" ><sup style="font-size: 9px;">SF10-JHS</sup></td>
        <td width="10%"></td>
        <td></td>
        <td width="25%" style="font-size: 9px; text-align: right; padding-bottom: 10px;">page 1 of 2 pages&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px">
        </td>
        <td style="text-align: left; vertical-align: top;" rowspan="5"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px"></td>
        <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
        <td style="text-align:right; vertical-align: middle;" rowspan="5"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td style="text-align:center; font-size: 11px;">Department of Education</td>
    </tr>
    <tr>
        <td style="text-align:center; font-size: 20px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
    </tr>
    <tr>
        <td style="text-align:center; font-size: 8px;">ACSCU-ACI Accredited</td>
        <td style="text-align:center; font-size: 11px; padding-bottom: 3px;">Juan dela Cruz St., Toril, Davao City</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center; font-size: 11px; padding-bottom: 5px;">Telephone Number: (082) 291-1882 / (082) 295-662</td>
    </tr>
    <tr>
        <td style="text-align:center; font-size: 13px; font-weight: bold;" colspan="4">Learner’s Permanent Academic Record for Junior High School</td>
    </tr>
    <tr style="line-height: 10px;font-size: 11px;">
        <td style="text-align:center;" colspan="4"><em>(Formerly Form 137)</em></td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%" id="table2">
    <tr>
        <td colspan="8" style="text-align: center; font-size: 13px; font-weight: bold; background-color: #ddd9c3; border: 1px solid black;">LEARNER'S INFORMATION</td>
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
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->middlename}}</td>
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
        <td style="border: 1px solid black; background-color: #ddd9c3">
            ELIGIBILITY FOR JHS ENROLMENT
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<div style="width: 100%; border: 1px solid black; padding-top: 4px;">
    <table style="width: 100%; font-size: 11px;" id="table5">
        <tr style="font-style: italic;">
            <td><input type="checkbox" name="check-1"@if($eligibility->completer == 1) checked @endif>Elementary School Completer</td>
            <td>General Average: {{$eligibility->genave > 0 ? $eligibility->genave : null}}</td>
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
<table style="width: 100%; font-size: 10px;" id="table7" >
    <tr>
        <td colspan="6">Other Credential Presented</td>
    </tr>
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check-1"@if($eligibility->peptpasser == 1) checked @endif>PEPT Passer &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility->peptrating}}&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check-1"@if($eligibility->alspasser == 1) checked @endif>ALS A & E Passer &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility->alsrating}}&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td style="width: 18%;"><input type="checkbox" name="check-1">Others (Pls. Specify):</td>
        <td style="width: 8%; border-bottom: 1px solid black;">{{$eligibility->specifyothers}}</td>
        <td style="width: 8%;">Rating:</td>
        <td style="width: 4%; border-bottom: 1px solid black;"></td>
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
        <td style="border: 1px solid black; background-color: #ddd9c3">
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
        @foreach($records as $key=> $record)      
        
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px; page-break-inside: avoid;" border="1">
                <thead>
                    <tr>
                        <td colspan="8">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{$record->schoolname}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 15%; border-bottom: 1px solid black;">{{$record->schoolid}}</td>
                                    <td style="width: 5%;">District:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{$record->schooldistrict}}</td>
                                    <td style="width: 5%;">Division:</td>
                                    <td style="width: 15%; border-bottom: 1px solid black;">{{$record->schooldivision}}</td>
                                    <td style="width: 5%;">Region:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{$record->schoolregion}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 15%;">Classified as Grade:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record->levelname)}}</td>
                                    <td style="width: 15%;">Section:</td>
                                    <td style="width: 30%; border-bottom: 1px solid black;">{{$record->sectionname}}</td>
                                    <td style="width: 20%;">SchoolYear:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{$record->sydesc}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 20%;">Name of Adviser/Teacher:</td>
                                    <td style="border-bottom: 1px solid black;">{{$record->teachername}}</td>
                                    <td style="width: 10%;">Signature:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" colspan="2" style="width: 40%;">LEARNING AREAS</th>
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
                @if(count($record->grades)>0)
                    @foreach($record->grades as $grade)
                        @if($record->type == 2)
                            @if(strtolower($grade->subjtitle) != 'general average')
                                <tr>
                                    <td colspan="2">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE ==1 )&nbsp;&nbsp;&nbsp;@endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
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
                        @else
                            <tr>
                                <td colspan="2">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE ==1 )&nbsp;&nbsp;&nbsp;@endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
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
                    @if($record->type == 1)
                        @if(count($record->subjaddedforauto)>0)
                            @foreach($record->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td colspan="2">{{$customsubjgrade->subjdesc}}</td>
                                    <td class="text-center">{{$customsubjgrade->q1}}</td>
                                    <td class="text-center">{{$customsubjgrade->q2}}</td>
                                    <td class="text-center">{{$customsubjgrade->q3}}</td>
                                    <td class="text-center">{{$customsubjgrade->q4}}</td>
                                    <td class="text-center">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="4"  class="text-center"><em>General Average</em></td>
                                <td class="text-center">{{(number_format(collect($record->grades)->sum('finalrating')/count($record->grades))) > 0 ? number_format(collect($record->grades)->sum('finalrating')/count($record->grades)) : null}}</td>
                                <td class="text-center">@if((number_format(collect($record->grades)->sum('finalrating')/count($record->grades))) > 0){{ number_format(collect($record->grades)->sum('finalrating')/count($record->grades)) >= 75 ? 'PASSED' : 'FAILED'}}@endif</td>
                            </tr>
                    @elseif($record->type == 2)
                        @if(count($record->grades) > 1)
                            @foreach($record->grades as $grade)
                                @if(strtolower($grade->subjtitle) == 'general average')
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="4"  class="text-center"><em>General Average</em></td>
                                        <td class="text-center">{{$grade->finalrating}}</td>
                                        <td class="text-center">{{$grade->finalrating >= 75 ? 'PASSED' : 'FAILED'}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @else
                    @php
                        if($key == 0)
                        {
                            $currentlevel = 10;
                        }
                        if($key == 1)
                        {
                            $currentlevel = 11;
                        }
                        if($key == 2)
                        {
                            $currentlevel = 12;
                        }
                        if($key == 3)
                        {
                            $currentlevel = 13;
                        }
                    @endphp
                    @if(collect($subjects)->where('levelid',$currentlevel)->count()>0)
                        @foreach(collect($subjects)->where('levelid',$currentlevel)->values()->all() as $eachsubj)
                        <tr>
                            <td colspan="2">@if($eachsubj->inMAPEH == 1)&nbsp;&nbsp;&nbsp;&nbsp;@endif{{$eachsubj->subjdesc}}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="4"  class="text-center"><em>General Average</em></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                    @endif
                    {{-- @for($x=0; $x<$maxgradecount; $x++)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor --}}
                @endif
                <tr>
                    <td class="text-center" style="width: 25%;">Remedial Classes</td>
                    <td colspan="6">Conducted from (mm/dd/yy): @for($x = 0; $x < 10; $x++)	&nbsp;&nbsp;&nbsp;&nbsp; @endfor	to (mm/dd/yy):	</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center">Learning Areas</td>
                    <td colspan="2" class="text-center">Final Rating</td>
                    <td colspan="2" class="text-center">Remedial Class Mark</td>
                    <td colspan="2" class="text-center">Recomputed Final Grade</td>
                    <td class="text-center">Remarks</td>
                </tr>
                @for($x = 0; $x < 2; $x++)
                <tr>
                    <td class="text-center">&nbsp;</td>
                    <td colspan="2" class="text-center">&nbsp;</td>
                    <td colspan="2" class="text-center">&nbsp;</td>
                    <td colspan="2" class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                </tr>
                @endfor
            </table>
            @if($key == 1)
            <div style="width: 100%; line-height: 4px;">&nbsp;</div>
            <div style="width: 100%;page-break-inside: avoid;">
                {{-- <div style="width: 100%; font-size: 11px;">For Transfer Out /Elementary School Completer Only</div> --}}
                    <table style="width: 100%; font-size: 11px; border: 2px solid black;">
                        <tr>
                            <td colspan="10" style="border: 1px solid black; border-bottom: hidden; font-weight: bold;" class="text-center">
                                CERTIFICATION
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 3%;"></td>
                            <td colspan="8" style="text-align: justify;">
                                I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->suffix}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;{{$footer->admissiontograde ?? null}}&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                            </td>
                            <td style="width: 3%;"></td>
                        </tr>
                        <tr>
                            <td style="width: 3%;"></td>
                            <td style="width: 10%;">Name of School:</td>
                            <td style="width: 31%; border-bottom: 1px solid black;" colspan="3">{{$schoolinfo->schoolname}}</td>
                            <td style="width: 7%;">School ID:</td>
                            <td style="width: 6%; border-bottom: 1px solid black;">{{$schoolinfo->schoolid}}</td>
                            <td style="width: 16%;">Last School Year Attended:</td>
                            <td style="width: 9%; border-bottom: 1px solid black;">{{$footer->lastsy ?? null}}</td>
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
                        {{-- <tr>
                            <td>&nbsp;</td>
                            <td>Copy for:;</td>
                            <td colspan="8">{{$footer->purpose}}</td>
                        </tr> --}}
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
                            <td colspan="4" style="/**border-bottom: 1px solid black;**/ text-align: center; font-weight: bold;"></td>
                            <td></td>
                            <td>&nbsp;</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td ></td>
                            <td class="text-center">Date</td>
                            <td>&nbsp;</td>
                            <td colspan="4" class="text-center" style="font-size: 10px;"></td>
                            <!--<td>&nbsp;</td>-->
                            <td colspan="3"style="text-align: center;">(Affix School Seal here)</td>
                        </tr>
                        <!--<tr>-->
                        <!--    <td colspan="2"></td>-->
                        <!--    <td class="text-center">Date</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')-->
                        <!--    <td colspan="3" class="text-center" style="font-size: 10px;">Name of School Registrar over Printed Name</td>-->
                        <!--    @else-->
                        <!--    <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School Head over Printed Name</td>-->
                        <!--    @endif-->
                        <!--    <td colspan="2"style="text-align: right;">(Affix School Seal here)</td>-->
                        <!--    <td></td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--    <td>&nbsp;</td>-->
                        <!--</tr>-->
                    </table>
            </div>
            @endif
            <div style="width: 100%; line-height: 4px;">&nbsp;</div>
        @endforeach
        <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 11px; page-break-inside: avoid;" border="1">
            <thead>
                <tr>
                    <td colspan="8">
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 5%;">School:</td>
                                <td style="width: 23%; border-bottom: 1px solid black;">{{$record->schoolname}}</td>
                                <td style="width: 7%;">School ID:</td>
                                <td style="width: 15%; border-bottom: 1px solid black;">{{$record->schoolid}}</td>
                                <td style="width: 5%;">District:</td>
                                <td style="width: 10%; border-bottom: 1px solid black;">{{$record->schooldistrict}}</td>
                                <td style="width: 5%;">Division:</td>
                                <td style="width: 15%; border-bottom: 1px solid black;">{{$record->schooldivision}}</td>
                                <td style="width: 5%;">Region:</td>
                                <td style="width: 10%; border-bottom: 1px solid black;">{{$record->schoolregion}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 15%;">Classified as Grade:</td>
                                <td style="width: 10%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record->levelname)}}</td>
                                <td style="width: 15%;">Section:</td>
                                <td style="width: 30%; border-bottom: 1px solid black;">{{$record->sectionname}}</td>
                                <td style="width: 20%;">SchoolYear:</td>
                                <td style="width: 10%; border-bottom: 1px solid black;">{{$record->sydesc}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 20%;">Name of Adviser/Teacher:</td>
                                <td style="border-bottom: 1px solid black;">{{$record->teachername}}</td>
                                <td style="width: 10%;">Signature:</td>
                                <td style="width: 20%; border-bottom: 1px solid black;"></td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2" colspan="2" style="width: 40%;">LEARNING AREAS</th>
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
                @php
                    if($key == 0)
                    {
                        $currentlevel = 10;
                    }
                    if($key == 1)
                    {
                        $currentlevel = 11;
                    }
                    if($key == 2)
                    {
                        $currentlevel = 12;
                    }
                    if($key == 3)
                    {
                        $currentlevel = 13;
                    }
                @endphp
                @if(collect($subjects)->where('levelid',$currentlevel)->count()>0)
                    @foreach(collect($subjects)->where('levelid',$currentlevel)->values()->all() as $eachsubj)
                    <tr>
                        <td colspan="2">@if($eachsubj->inMAPEH == 1)&nbsp;&nbsp;&nbsp;&nbsp;@endif{{$eachsubj->subjdesc}}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="4"  class="text-center"><em>General Average</em></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                @endif
                {{-- @for($x=0; $x<$maxgradecount; $x++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor --}}
            <tr>
                <td class="text-center" style="width: 25%;">Remedial Classes</td>
                <td colspan="6">Conducted from (mm/dd/yy): @for($x = 0; $x < 10; $x++)	&nbsp;&nbsp;&nbsp;&nbsp; @endfor	to (mm/dd/yy):	</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center">Learning Areas</td>
                <td colspan="2" class="text-center">Final Rating</td>
                <td colspan="2" class="text-center">Remedial Class Mark</td>
                <td colspan="2" class="text-center">Recomputed Final Grade</td>
                <td class="text-center">Remarks</td>
            </tr>
            @for($x = 0; $x < 2; $x++)
            <tr>
                <td class="text-center">&nbsp;</td>
                <td colspan="2" class="text-center">&nbsp;</td>
                <td colspan="2" class="text-center">&nbsp;</td>
                <td colspan="2" class="text-center">&nbsp;</td>
                <td class="text-center">&nbsp;</td>
            </tr>
            @endfor
        </table>
    @endif
    <div style="width: 100%; line-height: 4px;">&nbsp;</div>
    <div style="width: 100%;page-break-inside: avoid;">
        {{-- <div style="width: 100%; font-size: 11px;">For Transfer Out /Elementary School Completer Only</div> --}}
            <table style="width: 100%; font-size: 11px; border: 2px solid black;">
                <tr>
                    <td colspan="10" style="border: 1px solid black; border-bottom: hidden; font-weight: bold;" class="text-center">
                        CERTIFICATION
                    </td>
                </tr>
                <tr>
                    <td style="width: 3%;"></td>
                    <td colspan="8" style="text-align: justify;">
                        I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->suffix}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;{{$footer->admissiontograde ?? null}}&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                    </td>
                    <td style="width: 3%;"></td>
                </tr>
                <tr>
                    <td style="width: 3%;"></td>
                    <td style="width: 10%;">Name of School:</td>
                    <td style="width: 31%; border-bottom: 1px solid black;" colspan="3">{{$schoolinfo->schoolname}}</td>
                    <td style="width: 7%;">School ID:</td>
                    <td style="width: 6%; border-bottom: 1px solid black;">{{$schoolinfo->schoolid}}</td>
                    <td style="width: 16%;">Last School Year Attended:</td>
                    <td style="width: 9%; border-bottom: 1px solid black;">{{$footer->lastsy ?? null}}</td>
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
                {{-- <tr>
                    <td>&nbsp;</td>
                    <td>Copy for:;</td>
                    <td colspan="8">{{$footer->purpose}}</td>
                </tr> --}}
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
                    <td colspan="4" style="/**border-bottom: 1px solid black;**/ text-align: center; font-weight: bold;"></td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td ></td>
                    <td class="text-center">Date</td>
                    <td>&nbsp;</td>
                    <td colspan="4" class="text-center" style="font-size: 10px;"></td>
                    <!--<td>&nbsp;</td>-->
                    <td colspan="3"style="text-align: center;">(Affix School Seal here)</td>
                </tr>
                <!--<tr>-->
                <!--    <td colspan="2"></td>-->
                <!--    <td class="text-center">Date</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')-->
                <!--    <td colspan="3" class="text-center" style="font-size: 10px;">Name of School Registrar over Printed Name</td>-->
                <!--    @else-->
                <!--    <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School Head over Printed Name</td>-->
                <!--    @endif-->
                <!--    <td colspan="2"style="text-align: right;">(Affix School Seal here)</td>-->
                <!--    <td></td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--    <td>&nbsp;</td>-->
                <!--</tr>-->
            </table>
    </div>