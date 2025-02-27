<style>
    * { 
        font-family: Arial, Helvetica, sans-serif; 
    }
    @page { margin: 20px; }
    
    #table1 td{
        padding: 0px;
    }
    table {
        border-collapse: collapse;
    }
    #table2{
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
    <table style="width: 100%; margin-left: 30px; margin-right: 30px;" id="table1">
        <tr>
            <td width="15%" rowspan="3" style="text-align: right; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            </td>
            <td style="text-align:center; font-size: 12px; width:70%;">
                Archdiocese of Cagayan de Oro<br/>
                Cagayan de Oro Network of Archdiocesan Schools (CONAS)
            </td>
            <td width="15%" style="text-align:left; vertical-align: top;"  rowspan="3"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 20px; font-weight: bold; color: green;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 12px; font-weight: bold;">Zone 2, poblacion, El Salvador City<br/>(088) 882-04-98</td>
        </tr>
    </table>
    <table style="width: 100%; margin-left: 30px; margin-right: 30px;" id="table2">
    <tr>
        <td colspan="8" style="text-align: center; font-size: 15px; font-weight: bold;">STUDENT'S PERMANENT RECORD</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align: left; font-size: 15px; font-weight: bold;">LEARNER'S INFORMATION</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr style="font-size: 12px; font-weight: bold;">
        <td style="width: 15%;">LAST NAME:</td>
        <td style="width: 50%; text-align: center;" colspan="4">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}}</td>
        <td style="width: 5%;">Sex:</td>
        <td style="width: 25%;" colspan="2">{{$studinfo->gender}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 12px; font-weight: bold; margin-left: 30px;" id="table3">
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 15%; border: 1px solid black; text-align: center;">{{$studinfo->lrn}}</td>
        <td style="width: 35%;"></td>
        <td style="width: 15%;">Date of Birth:</td>
        <td style="width: 20%; text-align: left;" colspan="2">{{date('m/d/Y',strtotime($studinfo->dob))}}</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 12px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; "> ELIGIBILITY FOR SHS ENROLMENT</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 12px !important; margin-left: 30px; margin-right: 30px;">
    <tr>
        <td  width="10%">Name of School:</td>
        <td style="border-bottom: 1px solid"  width="30%">
            {{$eligibility->schoolname}}
        </td>
    </tr>
    <tr>
        <td style=""  width="10%">School Address:</td>
        <td style="border-bottom: 1px solid"  width="13%">
            {{$eligibility->schooladdress}}
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px; margin-left: 30px; margin-right: 30px;">&nbsp;</div>
<table class="table table-sm" width="100%" style="font-size: 12px !important; margin-top:.5rem !important; margin-left: 30px; margin-right: 30px;">
    <tr>
        <td class="text-center" style="font-weight: bold;">SCHOLASTIC RECORD</td>
    </tr>
</table>
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
@if(count($records)>0)
    @php
        $record_count = 1;
    @endphp
    @foreach($records as $record)
        @if(count($record)==1)
        
            
            
        @elseif(count($record) == 2)
        
            
            
            <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-left: 30px; margin-right: 30px; page-break-inside: avoid;">
                   @if( $record_count == 2)
                       <tr>
                           <td colspan="6">
                                <table class="table" width="100%" style=" font-size: 10px;">
                                    <tr>
                                        <td width="10%">Page 2</td>
                                        <td width="80%">{{$studinfo->lastname}}, {{$studinfo->firstname}}</td>
                                        <td width="10%" style="text-align: right;">SF10-SHS</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                    @php
                        $record_count += 1;
                    @endphp
               <thead>
                    
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 11px;">
                                <tr>
                                    <td style="width: 10%;">School:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[0]->schoolname}}</td>
                                    <td style="width: 10%;">School ID:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;">{{$record[0]->schoolid}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 10%;">Grade Level:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[0]->levelname)}} - {{$record[0]->sectionname}}</td>
                                    <td style="width: 10%;">Semester:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;">@if($record[0]->semid == 1) FIRST @elseif($record[0]->semid == 2) SECOND @endif</td>
                                </tr>
                                <tr>
                                    <td style="width: 10%;">Strand/Track:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[0]->strandcode}}</td>
                                    <td style="width: 10%;">School Year:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;">{{$record[0]->sydesc}}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                        <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                        <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">SEM FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr >
                        <th style="width: 8%; border: solid 1px black;">1</th>
                        <th style="width: 8%; border: solid 1px black;">2</th>
                    </tr>
                </thead>
                <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black">
                @if(collect($record[0]->grades)->unique('subjdesc')->count()>0)
                    @php
                        $gen_ave_for_sem = 0;
                        $with_final_rating = true;
                            $countsubjs = 0;
                            $withpe = 0;
                    @endphp
                    @foreach(collect($record[0]->grades)->unique('subjdesc') as $grade)
                        @php
                                if($grade->inSF9 == 1)
                                {
                            $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                            $average = $with_final_rating ? $grade->subjectsjaesfinalrating : '';
                            $gen_ave_for_sem += $with_final_rating ? $average : 0;
                                }
                        @endphp
                        @if($record[0]->type == 2)
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{strtoupper($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{strtoupper($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q1) > 0 ? number_format($grade->q1) : null}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q2) > 0 ? number_format($grade->q2) : null}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}} </td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                </tr>
                                @php
                                if($grade->inSF9 == 1)
                                {
                                    if(strpos(strtolower($grade->subjdesc),'physical edu') !== false)
                                    {
                                        $withpe += 1;
                                    }
                                $countsubjs+=1;
                                }
                                @endphp
                            @endif
                        @endif
                    @endforeach                    
                    @if($record[0]->type == 1)
                        @if(count($record[0]->subjaddedforauto)>0)
                            @foreach($record[0]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{strtoupper($customsubjgrade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q1)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q2)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr style="font-weight: bold;">
                            @php
                                $with_final = collect($record[0]->grades)->where('q1','!=,',null)->where('inSF9',1)->count() == 0 && collect($record[0]->grades)->where('q2','!=,',null)->count() == 0 ? true:false;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">
                                @if($withpe == 0)
                                {{ $with_final ? number_format($gen_ave_for_sem / $countsubjs) : '' }}
                                @else
                                {{ $with_final ? number_format(number_format($gen_ave_for_sem / ($countsubjs-0.75),3),2) : '' }}
                                @endif
                                <!--{{ number_format($gen_ave_for_sem / $countsubjs)  }} , {{$gen_ave_for_sem}} {{$countsubjs-0.75}}-->
                                </td>
                            <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / $countsubjs) >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                        </tr>
                    @elseif($record[0]->type == 2)
                        @if(count($record[0]->grades) > 1)
                            @foreach($record[0]->grades as $grade)
                                @if(strtolower($grade->subjdesc) == 'general average')
                                    <tr style="font-weight: bold;">
                                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                        <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                        <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    
                @else
                
                    @for($x=0; $x<$maxgradecount; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            <tbody>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-left: 30px; margin-right: 30px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->principalname}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">School Principal</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </table>
            
            <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-left: 30px; margin-right: 30px; ">
                <thead>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 11px;">
                                <tr>
                                    <td style="width: 10%;">School:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[1]->schoolname}}</td>
                                    <td style="width: 10%;">School ID:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;">{{$record[1]->schoolid}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 10%;">Grade Level:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[1]->levelname)}} - {{$record[1]->sectionname}}</td>
                                    <td style="width: 10%;">Semester:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;">@if($record[1]->semid == 1) FIRST @elseif($record[1]->semid == 2) SECOND @endif</td>
                                </tr>
                                <tr>
                                    <td style="width: 10%;">Strand/Track:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[1]->strandcode}}</td>
                                    <td style="width: 10%;">School Year:</td>
                                    <td style="width: 20%; border-bottom: 1px solid black;">{{$record[1]->sydesc}}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                        <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                        <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">SEM FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr >
                        <th style="width: 8%; border: solid 1px black;">1</th>
                        <th style="width: 8%; border: solid 1px black;">2</th>
                    </tr>
                </thead>
                @php
                    $gen_ave_for_sem = 0;
                    $with_final_rating = true;
                            $countsubjs = 0;
                            $withpe = 0;
                @endphp
                @if(collect($record[1]->grades)->where('semid','2')->where('subjcode','!=',null)->unique('subjdesc')->count() >0)
                    @php
                        $gen_ave_for_sem = 0;
                        $with_final_rating = true;
                    @endphp
                    @foreach(collect($record[1]->grades)->where('semid','2')->unique('subjdesc') as $grade)
                        @php
                        
                                if($grade->inSF9 == 1)
                                {
                            $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                            $average = $with_final_rating ? $grade->subjectsjaesfinalrating : '';
                            $gen_ave_for_sem += $with_final_rating ? $average : 0;
                                }
                        @endphp
                        @if($record[1]->type == 2)
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{strtoupper($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}} </td>
                                    <td style="border: solid 1px black;">{{strtoupper($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q1) > 0 ? number_format($grade->q1) : null}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q2) > 0 ? number_format($grade->q2) : null}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">@if(isset($grade->remarks)){{$grade->remarks}}@else {{$grade->actiontaken}}@endif</td>
                                </tr>
                                @php
                                if($grade->inSF9 == 1)
                                {
                                    if(strpos(strtolower($grade->subjdesc),'physical edu') !== false)
                                    {
                                        $withpe += 1;
                                    }
                                $countsubjs+=1;
                                }
                                @endphp
                            @endif
                        @endif
                    @endforeach                    
                    @if($record[1]->type == 1)
                        @if(count($record[1]->subjaddedforauto)>0)
                            @foreach($record[1]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{strtoupper($customsubjgrade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q1)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q2)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr style="font-weight: bold;">
                            @php
                                $with_final = collect($record[1]->grades)->where('semid','2')->where('inSF9',1)->where('q1','!=,',null)->count() == 0 && collect($record[1]->grades)->where('semid','2')->where('q2','!=,',null)->count() == 0 ? true:false;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">
                                @if($withpe == 0)
                                {{ $with_final ? number_format($gen_ave_for_sem / $countsubjs) : '' }}
                                @else
                                {{ $with_final ? number_format(number_format($gen_ave_for_sem / ($countsubjs-0.75),3),2) : '' }}
                                @endif</td>
                            <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / $countsubjs) >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                        </tr>
                    @elseif($record[1]->type == 2)
                        @if(count($record[1]->grades) > 1)
                            @foreach($record[1]->grades as $grade)
                                @if(strtolower($grade->subjdesc) == 'general average')
                                    <tr style="font-weight: bold;">
                                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                        <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                        <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    
                @else
                
                    @for($x=0; $x<$maxgradecount; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            </table>
            
            <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-left: 30px; margin-right: 30px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[1]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[1]->principalname}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">School Principal</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </table>
        {{-- <div style="page-break-before: always;"></div> --}}
        @endif
    @endforeach
    @if(count($records) == 1)
    @endif
@endif
