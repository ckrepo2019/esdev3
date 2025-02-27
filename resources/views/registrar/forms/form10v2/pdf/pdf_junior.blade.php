
<html>
    <head>
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
    .watermark {
                position: absolute;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                /* bottom:   22cm; */
                top:   0;
                left:     1cm;
                opacity: 0.1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
</style>
    </head>
    <body>
    <script type="text/php">
        if ( isset($pdf) ) {
            if ($PAGE_NUM > 1) {
                $pdf->page_text(100, 100, "Page {PAGE_NUM} of {PAGE_COUNT}", '', 9, array(0,0,0));
            }

        }
    </script> 
@php
    $coloredlines = '#aba9a9';
    $borderlines = 'border: 1px solid black;';
    if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
    {
        $coloredlines = '#ddd9c4';
        $borderlines = '';
    }
@endphp
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
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px">
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
            <td width="15%" rowspan="5"><sup style="font-size: 9px;">SF10-JHS</sup><br/>
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="15%" style="text-align:right;"  rowspan="5"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">Learner's Permanent Academic Record for Junior High School </td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-JHS)</td>
        </tr>
        <tr style="line-height: 5px;font-size: 11px;">
            <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
        </tr>
    </table>
    @endif
@endif
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%" id="table2">
    <tr>
        <td colspan="8" style="text-align: center; font-size: 13px; font-weight: bold; background-color: {{$coloredlines}}; {{$borderlines}}">LEARNER'S INFORMATION</td>
    </tr>
    {{-- <tr>
        <td colspan="8">&nbsp;</td>
    </tr> --}}
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black; text-align: center;">{{strtoupper($studinfo->lastname)}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black; text-align: center;">{{strtoupper($studinfo->firstname)}}</td>
        <td style="width: 15%;">NAME EXTN. (Jr,I,II)</td>
        <td style="width: 10%; border-bottom: 1px solid black; text-align: center;">{{strtoupper($studinfo->suffix)}}</td>
        <td style="width: 10%;">MIDDLE NAME</td>
        <td style="width: 10%; border-bottom: 1px solid black; text-align: center;">{{strtoupper($studinfo->middlename)}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;" id="table3">
    {{-- <tr>
        <td colspan="6">&nbsp;</td>
    </tr> --}}
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
        <td style="{{$borderlines}} background-color: {{$coloredlines}}">
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
        <td style="{{$borderlines}} background-color: {{$coloredlines}}">
            SCHOLASTIC RECORD
        </td>
    </tr>
</table>
@php
    $tablescount = 2;
    $tablescount-= count($gradelevels);
    $page = 0;
    $firstpage_cert_level = 0;
    $secondpage_cert_level = 0;
@endphp
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
    @if(count($gradelevels)>0)
        @foreach($gradelevels as $gradelevelkey => $gradelevel)      
        
        @if($gradelevelkey > 0 && ($gradelevelkey % 2 == 0))
            @php
            $page+=1;
                if($page == 1)
                {
                $page+=1;
                }
            @endphp
            @if($page>0)
            {{-- <div class="watermark"> --}}
                <table style="width: 100%; page-break-before: always;">
                    <tr>
                        <td colspan="6">
                             <table class="table" width="100%" style=" font-size: 10px;">
                                 <tr>
                                     <td width="10%">Page {{$page}}</td>
                                     <td width="80%">{{$studinfo->lastname}}, {{$studinfo->firstname}}</td>
                                     <td width="10%" style="text-align: right;">SF10-JHS</td>
                                 </tr>
                             </table>
                         </td>
                     </tr>
                </table>
            {{-- </div> --}}
            @endif
        @endif
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px !important; page-break-inside: avoid; " border="1">
                <thead>
                    <tr>
                        <td colspan="8">
                            <table style="width: 100%; font-size: 11px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->schoolname) ? $gradelevel->headerinfo[0]->schoolname : null}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 15%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->schoolid) ? $gradelevel->headerinfo[0]->schoolid : null}}</td>
                                    <td style="width: 5%;">District:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->schooldistrict) ? $gradelevel->headerinfo[0]->schooldistrict : null}}</td>
                                    <td style="width: 5%;">Division:</td>
                                    <td style="width: 15%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->schooldivision) ? $gradelevel->headerinfo[0]->schooldivision : null}}</td>
                                    <td style="width: 5%;">Region:</td>
                                    <td style="width: 10%; border-bottom: 1px solid black;">
                                        @if(isset($gradelevel->headerinfo[0]->schoolregion))
                                            @if (str_contains(strtolower($gradelevel->headerinfo[0]->schoolregion), 'region')) 
                                            {{str_replace('REGION', '', strtoupper($gradelevel->headerinfo[0]->schoolregion))}}
                                            @else
                                            {{isset($gradelevel->headerinfo[0]->schoolregion) ? $gradelevel->headerinfo[0]->schoolregion : null}}
                                            @endif
                                        @else
                                            {{isset($gradelevel->headerinfo[0]->schoolregion) ? $gradelevel->headerinfo[0]->schoolregion : null}}
                                        @endif
                                        </td>
                                </tr>
                            </table>
                            @if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                <table style="width: 100%; font-size: 11px;">
                                    <tr>
                                        <td style="width: 14%;">Classified as Grade:</td>
                                        <td style="width: 3%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $gradelevel->levelname)}}</td>
                                        <td style="width: 6%;">Section:</td>
                                        <td style=" border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->sectionname) ? $gradelevel->headerinfo[0]->sectionname : null}}</td>
                                        <td style="width: 8%;">SchoolYear:</td>
                                        <td style="width: 8%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->sydesc) ? $gradelevel->headerinfo[0]->sydesc : null}}</td>
                                        <td style="">Name of Adviser/Teacher:</td>
                                        <td style="border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->teachername) ? $gradelevel->headerinfo[0]->teachername : null}}</td>
                                        <td style="width: 8%;">Signature:</td>
                                        <td style="width: 5%; border-bottom: 1px solid black;"></td>
                                    </tr>
                                </table>
                            @else
                                <table style="width: 100%; font-size: 11px;">
                                    <tr>
                                        <td style="width: 15%;">Classified as Grade:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $gradelevel->levelname)}}</td>
                                        <td style="width: 15%;">Section:</td>
                                        <td style="width: 30%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->sectionname) ? $gradelevel->headerinfo[0]->sectionname : null}}</td>
                                        <td style="width: 20%;">SchoolYear:</td>
                                        <td style="width: 10%; border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->sydesc) ? $gradelevel->headerinfo[0]->sydesc : null}}</td>
                                    </tr>
                                </table>
                                    <table style="width: 100%; font-size: 11px;">
                                    <tr>
                                        <td style="width: 20%;">Name of Adviser/Teacher:</td>
                                        <td style="border-bottom: 1px solid black;">{{isset($gradelevel->headerinfo[0]->teachername) ? $gradelevel->headerinfo[0]->teachername : null}}</td>
                                        <td style="width: 10%;">Signature:</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                    </tr>
                                </table>
                            @endif
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" rowspan="2" style="width: 40%;">LEARNING AREAS</th>
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
                        if($gradelevelkey == 0 || $gradelevelkey == 1)
                        {
                            $firstpage_cert_level = ($gradelevel->id) + 1;
                            if($gradelevel->promotionstatus == 2 || $gradelevel->promotionstatus == 3 || $gradelevel->promotionstatus == null)
                            {
                                $firstpage_cert_level = $gradelevel->id;
                            }
                        }elseif($gradelevelkey == 2 || $gradelevelkey == 3 || $gradelevelkey == 4)
                        {
                            $secondpage_cert_level = ($gradelevel->id) + 1;
                            if($gradelevel->promotionstatus == 2 || $gradelevel->promotionstatus == 3 || $gradelevel->promotionstatus == null)
                            {
                                $secondpage_cert_level = $gradelevel->id;
                                if((int)filter_var(DB::table('gradelevel')->where('id', $firstpage_cert_level)->first()->levelname, FILTER_SANITIZE_NUMBER_INT) < 9)
                                {
                                    $secondpage_cert_level = null;
                                }
                            }
                        }
                    @endphp
                @if(count($gradelevel->grades)>0)
                    @if($gradelevel->recordinputype == 0)
                        @foreach($gradelevel->grades as $grade)
                            <tr>
                                <td colspan="2">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE) == 1)&nbsp;@endif {{strtoupper($grade->subjdesc)}}</td>
                                <td class="text-center">{{$grade->quarter1 > 0 ? $grade->quarter1 : null}}</td>
                                <td class="text-center">{{$grade->quarter2 > 0 ? $grade->quarter2 : null}}</td>
                                <td class="text-center">{{$grade->quarter3 > 0 ? $grade->quarter3 : null}}</td>
                                <td class="text-center">{{$grade->quarter4 > 0 ? $grade->quarter4 : null}}</td>
                                <td class="text-center">@if($grade->inMAPEH ==0 ){{$grade->finalrating > 0 ? $grade->finalrating : null}}@endif</td>
                                <td class="text-center">@if($grade->inMAPEH ==0 ){{$grade->remarks}}@endif</td>
                            </tr>
                        @endforeach
                        @if(count($gradelevel->subjaddedforauto)>0)
                            @foreach($gradelevel->subjaddedforauto as $customsubjgrade)
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
                        @if(DB::table('schoolinfo')->first()->schoolid == '405308') 
                            <tr style="font-weight: bold;">
                                <td colspan="2"></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->finalrating}}</td>
                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->finalrating >= 75 ? 'PROMOTED' : 'FAILED'}}</td>
                            </tr>
                        @else
                            @if(count($gradelevel->generalaverage) == 0)
                            <tr style="font-weight: bold;">
                                <td colspan="2"></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{number_format(collect($gradelevel->grades)->sum('finalrating')/count($gradelevel->grades))}}</td>
                                <td class="text-center">@if(number_format(collect($gradelevel->grades)->sum('finalrating')/count($gradelevel->grades)) > 60){{ number_format(collect($gradelevel->grades)->sum('finalrating')/count($gradelevel->grades)) >= 75 ? 'PROMOTED' : 'FAILED'}}@endif</td>
                            </tr>
                            @else
                            <tr style="font-weight: bold;">
                                <td colspan="2"></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->finalrating}}</td>
                                <td class="text-center">@if(collect($gradelevel->generalaverage)->first()->finalrating > 60){{collect($gradelevel->generalaverage)->first()->finalrating >= 75 ? 'PROMOTED' : 'FAILED'}}@endif</td>
                            </tr>
                            @endif
                        @endif
                    @elseif($gradelevel->recordinputype == 1)
                        @php
                            $totalsumfinalrating = 0;
                            $divisor = 0;
                        @endphp
                        @foreach($gradelevel->grades as $grade)
                            <tr>
                                <td colspan="2">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE) == 1)&nbsp;@endif {{strtoupper($grade->subjdesc)}}</td>
                                <td class="text-center">{{$grade->quarter1 > 0 ? $grade->quarter1 : null}}</td>
                                <td class="text-center">{{$grade->quarter2 > 0 ? $grade->quarter2 : null}}</td>
                                <td class="text-center">{{$grade->quarter3 > 0 ? $grade->quarter3 : null}}</td>
                                <td class="text-center">{{$grade->quarter4 > 0 ? $grade->quarter4 : null}}</td>
                                <td class="text-center">@if($grade->inMAPEH ==0 ){{$grade->finalrating > 0 ? $grade->finalrating : null}}@endif</td>
                                <td class="text-center">@if($grade->inMAPEH ==0 ){{$grade->remarks}}@endif</td>
                            </tr>
                            @php
                            
                            if($grade->inMAPEH == 0)
                            {
                                    try{
                                $totalsumfinalrating += $grade->finalrating;
                                $divisor+=1;
                                    }catch(\Exception $error){}
                            }
                            @endphp
                        @endforeach
                        @if(count($gradelevel->subjaddedforauto)>0)
                            @foreach($gradelevel->subjaddedforauto as $customsubjgrade)
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
                        @if(DB::table('schoolinfo')->first()->schoolid == '405308') 
                            <tr style="font-weight: bold;">
                                <td colspan="2"></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->finalrating}}</td>
                                <td class="text-center">@if(collect($gradelevel->generalaverage)->first()->finalrating > 60){{collect($gradelevel->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif</td>
                            </tr>
                        @else
                            <tr style="font-weight: bold;">
                                <td colspan="2"></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">{{number_format($totalsumfinalrating/$divisor)}}</td>
                                <td class="text-center">@if($totalsumfinalrating > 60){{number_format($totalsumfinalrating/$divisor) >= 75 ? 'PROMOTED' : 'FAILED'}}@endif</td>
                            </tr>
                        @endif
                    @elseif($gradelevel->recordinputype == 2 || $gradelevel->recordinputype == 3)
                        @if(count($gradelevel->grades) > 0)
                            @foreach($gradelevel->grades as $grade)
                                @if(strtolower($grade->subjtitle) != 'general average')
                                <?php try{ ?> 
                                    <tr>
                                        <td colspan="2">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE) == 1)&nbsp;@endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                        <td class="text-center">{{$grade->quarter1 > 0 ? $grade->quarter1 : null}}</td>
                                        <td class="text-center">{{$grade->quarter2 > 0 ? $grade->quarter2 : null}}</td>
                                        <td class="text-center">{{$grade->quarter3 > 0 ? $grade->quarter3 : null}}</td>
                                        <td class="text-center">{{$grade->quarter4 > 0 ? $grade->quarter4 : null}}</td>
                                        <td class="text-center">@if($grade->inMAPEH ==0 ){{$grade->finalrating > 0 ? $grade->finalrating : null}} @endif</td>
                                        <td class="text-center">@if($grade->inMAPEH ==0 ) {{$grade->remarks}} @endif</td>
                                    </tr>
                                <?php }catch(\Exception $e){ ?>
    
                                <?php } ?>
                                @endif
                            @endforeach
                        @endif
                        @if(count($gradelevel->generalaverage) == 0)
                            <tr style="font-weight: bold;">
                                <td colspan="2"></td>
                                <td colspan="4">General Average</td>
                                <td class="text-center">
                                    <?php try{ ?> 
                                    {{number_format(collect($gradelevel->grades)->where('inMAPEH','0')->where('finalrating','>','0')->avg('finalrating')) }}
                                    <?php }catch(\Exception $e){ ?>
                                        {{-- {{collect($gradelevel->grades)->where('inMAPEH','0')->where('finalrating','>','0')->count()}} --}}
                                        {{-- {{number_format(collect($gradelevel->grades)->where('inMAPEH','0')->where('finalrating','>','0')->avg('finalrating')) }} --}}
                                    <?php } ?>
                                </td>
                                <td class="text-center">{{number_format(collect($gradelevel->grades)->where('inMAPEH','0')->where('finalrating','>','0')->avg('finalrating')) >= 75 ? 'PROMOTED' : 'FAILED'}}</td>
                            </tr>
                        @else
                            @if(count($gradelevel->grades) > 1)
                                @foreach($gradelevel->grades as $grade)
                                    @if(strtolower($grade->subjtitle) == 'general average')
                                        <tr style="font-weight: bold;">
                                            <td colspan="2"></td>
                                            <td colspan="4">General Average</td>
                                            <td class="text-center">{{$grade->finalrating}}</td>
                                            <td class="text-center">@if($grade->finalrating > 60){{$grade->finalrating >= 75 ? 'PROMOTED' : 'FAILED'}} @endif</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @endif
                @else
                        @for($x=0; $x<14; $x++)
                            <tr>
                                <td colspan="2">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                @endif
                @if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                    <tr>
                        <td colspan="8" style="background-color: {{$coloredlines}}; line-height: 5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Remedial Classes</th>
                        <th colspan="7">Conducte from (mm/dd/yyyy) _________________ to (mm/dd/yyyy) ___________________</th>
                    </tr>
                    <tr>
                        <th>Learning Areas</th>
                        <th>Final Rating</th>
                        <th colspan="3">Remedial Class Mark</th>
                        <th colspan="2">Recomputed Final Grade</th>
                        <th>Remarks</th>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="3">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="3">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            </table>
            <div style="width: 100%; line-height: 4px;">&nbsp;</div>
            @if($gradelevelkey == 1 || $gradelevelkey == 3 || $gradelevelkey == 4)
                @if(strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                    <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                        <tr>
                            <td colspan="7" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                                CERTIFICATION
                            </td>
                        </tr>
                        <tr>
                            {{-- <td style="width: 2%;"></td> --}}
                            <td colspan="7" style="text-align: justify; padding-left: 10px;">
                                I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is  eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;
                                @if($gradelevelkey == 1 && $firstpage_cert_level > 0)
                                {{(int)filter_var(DB::table('gradelevel')->where('id', $firstpage_cert_level)->first()->levelname, FILTER_SANITIZE_NUMBER_INT)}}
                                @elseif(($gradelevelkey == 3 || $gradelevelkey == 4) && $secondpage_cert_level > 0)
                                {{(int)filter_var(DB::table('gradelevel')->where('id', $secondpage_cert_level)->first()->levelname, FILTER_SANITIZE_NUMBER_INT)}}
                                @else &nbsp;&nbsp;&nbsp;&nbsp; @endif</u>.
                            </td>
                            {{-- <td style="width: 2%;"></td> --}}
                        </tr>
                        <tr>
                            {{-- <td style="width: 2%;"></td> --}}
                            <td colspan="7" style=" padding-left: 10px;">Name of School: <u>{{isset($gradelevels[0]->headerinfo[0]->schoolname) ? $gradelevels[0]->headerinfo[0]->schoolname : (isset($gradelevels[1]->headerinfo[0]->schoolname) ? $gradelevels[1]->headerinfo[0]->schoolname : '')}}</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  School ID: <u>{{isset($gradelevels[0]->headerinfo[0]->schoolid) ? $gradelevels[0]->headerinfo[0]->schoolid : (isset($gradelevels[1]->headerinfo[0]->schoolid) ? $gradelevels[1]->headerinfo[0]->schoolid : '')}}</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Last School Year Attended: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{isset($gradelevels[0]->headerinfo[0]->sydesc) ? $gradelevels[0]->headerinfo[0]->sydesc : (isset($gradelevels[1]->headerinfo[0]->sydesc) ? $gradelevels[1]->headerinfo[0]->sydesc : '')}}<&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="border-bottom: 1px solid black; text-align: center; width: 10%;">{{date('m/d/Y')}}</td>
                            <td></td>
                            <td style="border-bottom: 1px solid black; text-align: center; width: 40%;" colspan="2">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') MERLIE S. SABUELO, SCHOOL REGISTRAR @else {{strtoupper(DB::table('schoolinfo')->first()->authorized)}}@endif</td>
                            <td></td>
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
                    @if($gradelevelkey > 3)
                    <div style="font-size: 11px; float: right; text-align: right; font-style: italic;">
                        SFRT Revised 2017
                    </div>
                    @endif
                @else
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                        <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                            <tr>
                                <td colspan="7" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                                    CERTIFICATION
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 3%;"></td>
                                <td colspan="5" style="text-align: justify;">
                                    I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is  eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
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
                    @else
                        <table style="width: 100%; font-size: 10.5px; border: 1px solid black;">
                            <tr>
                                <td colspan="7" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                                    CERTIFICATION
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 3%;"></td>
                                <td colspan="5" style="text-align: justify;">
                                    I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}}</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is  eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                                </td>
                                <td style="width: 3%;"></td>
                            </tr>
                            <!--<tr>-->
                            <!--    <td colspan="7">&nbsp;</td>-->
                            <!--</tr>-->
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
                                <td style="border-bottom: 1px solid black; text-align: center;">{{$footer->classadviser}}</td>
                                <td></td>
                                <td style="border-bottom: 1px solid black; text-align: center;">{{$footer->recordsincharge}}</td>
                                <td></td>
                                <td style="border-bottom: 1px solid black; text-align: center;">{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}</td>
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
                    @endif
                @endif
            @endif
        @endforeach
    @endif
    {{-- <div style="width: 100%; line-height: 4px;">&nbsp;</div>
    <div style="width: 100%;page-break-inside: avoid;">
    </div> --}}
    </body>
</html>