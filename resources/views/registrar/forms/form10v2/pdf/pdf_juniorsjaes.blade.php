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
            <td style="text-align:center; font-size: 13px; width:70%;">
                Archdiocese of Cagayan de Oro<br/>
                Cagayan de Oro Network of Archdiocesan Schools (CONAS)
            </td>
            <td width="15%" style="text-align:left; vertical-align: top;"  rowspan="3"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 20px; font-weight: bold; color: green;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 13px; font-weight: bold;">Zone 2, poblacion, El Salvador City<br/>(088) 882-04-98</td>
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
    <tr style="font-size: 13px; font-weight: bold;">
        <td style="width: 15%;">LAST NAME:</td>
        <td style="width: 50%; text-align: center;" colspan="4">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}}</td>
        <td style="width: 5%;">Sex:</td>
        <td style="width: 25%;" colspan="2">{{$studinfo->gender}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 13px; font-weight: bold; margin-left: 30px;" id="table3">
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 15%; border: 1px solid black; text-align: center;">{{$studinfo->lrn}}</td>
        <td style="width: 35%;"></td>
        <td style="width: 15%;">Date of Birth:</td>
        <td style="width: 20%; text-align: left;" colspan="2">{{date('m/d/Y',strtotime($studinfo->dob))}}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px; margin-left: 30px; margin-right: 30px;">&nbsp;</div>
<table style="width: 100%; font-size: 13px; font-weight: bold; text-align: center;" id="table9">
    <tr>
        <td style="">
            SCHOLASTIC RECORD
        </td>
    </tr>
</table>
<div style="border-top: 2px solid black;">&nbsp;</div>
@php
    $tablescount = 2;
    $tablescount-= count($records);
@endphp
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
    @if(count($records)>0)
        @foreach($records as $record)      
        
            <table style="width: 100%; table-layout: fixed; font-size: 11px; page-break-inside: avoid; margin-left: 50px; margin-right: 50px;">
                <thead>
                    <tr>
                        <th colspan="18" style="border: hidden !important;">
                            <table style="width: 100%; table-layout: fixed; font-size: 11px; page-break-inside: avoid;" >
                                <tr>
                                    <td style="width: 15%; font-weight: bold;">CLASSIFIED AS</td>
                                    <td style="width: 15%; font-weight: bold; font-size: 18px; vertical-align: bottom;" rowspan="2">YEAR</td>
                                    <td rowspan="2" style="width: 6%; vertical-align: bottom;">School</td>
                                    <td rowspan="2" style="border-bottom: 1px solid black; vertical-align: bottom;">{{$record[0]->schoolname}}</td>
                                    <td rowspan="2" style="width: 10%; vertical-align: bottom;">School Year</td>
                                    <td rowspan="2" style="width: 15%; border-bottom: 1px solid black; text-align: center; vertical-align:bottom;">{{$record[0]->sydesc}}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid black; text-align: center;">{{preg_replace('/\D+/', '', $record[0]->levelname)}}</td>
                                </tr>
                            </table>
                            <br/>
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2" colspan="7" style="width: 40% !important; border: 1px solid black;">SUBJECTS</th>
                        <th colspan="7" style=" border: 1px solid black;">PERIODIC RATINGS</th>
                        <th rowspan="2" colspan="2" style="width: 10%; border: 1px solid black;">Action<br/>Taken</th>
                        <th rowspan="2" colspan="2" style="width: 10%; border: 1px solid black;">Credit<br/>Earned</th>
                    </tr>
                    <tr>
                        <th style=" border: 1px solid black;">1</th>
                        <th style=" border: 1px solid black;">2</th>
                        <th style=" border: 1px solid black;">3</th>
                        <th style=" border: 1px solid black;">4</th>
                        <th style=" border: 1px solid black;"></th>
                        <th style=" border: 1px solid black;"></th>
                        <th style=" border: 1px solid black;">Final</th>
                    </tr>
                </thead>
                {{-- @if(strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308') fmcma --}}
                @if(count($record[0]->grades)>0)
                    @foreach($record[0]->grades as $grade)
                        @if($record[0]->type == 2)
                            @if(strtolower($grade->subjtitle) != 'general average')
                                <tr>
                                    <td colspan="7" style=" border: 1px solid black;">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))&nbsp;&nbsp;&nbsp;@endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter1}}</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter2}}</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter3}}</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter4}}</td>
                                    <td style=" border: 1px solid black;"></td>
                                    <td style=" border: 1px solid black;"></td>
                                    <td style=" border: 1px solid black;" class="text-center">{{$grade->finalrating}}</td>
                                    <td colspan="2" style=" border: 1px solid black;"class="text-center">{{$grade->remarks}}</td>
                                    <td colspan="2" style=" border: 1px solid black;" class="text-center">{{isset($grade->credits) ? $grade->credits : ''}}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td colspan="7" style=" border: 1px solid black;">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter1}}</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter2}}</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter3}}</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter4}}</td>
                                <td style=" border: 1px solid black;"></td>
                                <td style=" border: 1px solid black;"></td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->finalrating}}</td>
                                <td colspan="2" class="text-center" style=" border: 1px solid black;">{{$grade->remarks}}</td>
                                <td colspan="2" style=" border: 1px solid black;"class="text-center">{{isset($grade->credits) ? $grade->credits : ''}}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @for($x=0; $x<$maxgradecount; $x++)
                        <tr>
                            <td colspan="7" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td colspan="2" style=" border: 1px solid black;">&nbsp;</td>
                            <td colspan="2" style=" border: 1px solid black;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
                <tr>
                    <td colspan="18" style="border: 1px solid black; line-height: 5px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;" colspan="6">Month</td>
                    @for($x = 0; $x < 12; $x++)
                        @php
                            $monthdesc = ($x+1).'th';
                            if(($x+1) == 1)
                            {   
                                $monthdesc = ($x+1).'st';
                            }
                            elseif(($x+1) == 2)
                            {   
                                $monthdesc = ($x+1).'nd';
                            }
                            elseif(($x+1) == 3)
                            {   
                                $monthdesc = ($x+1).'rd';
                            }
                            elseif(($x+1) == 12)
                            {   
                                $monthdesc = 'Total';
                            }
                        @endphp
                        @if(isset($record[0]->attendance[$x]))
                        <td style="border: 1px solid black; text-align: center;">{{ucwords(strtolower($record[0]->attendance[$x]->monthdesc))}}</td>
                        @else
                        <td style="border: 1px solid black; text-align: center;">{{$monthdesc}}</td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="border-left: 1px solid black;" colspan="5">Days of School</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    @for($x = 0; $x < 12; $x++)
                        @if(isset($record[0]->attendance[$x]))
                        <td style="border: 1px solid black; text-align: center;">{{ucwords(strtolower($record[0]->attendance[$x]->numdays))}}</td>
                        @else
                        <td style="border: 1px solid black; text-align: center;">&nbsp;</td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="border-left: 1px solid black; border-bottom: 1px solid black;" colspan="3">Days Present</td>
                    <td style="border-bottom: 1px solid black;" colspan="3"></td>
                    @for($x = 0; $x < 12; $x++)
                        @if(isset($record[0]->attendance[$x]))
                        <td style="border: 1px solid black; text-align: center;">{{ucwords(strtolower($record[0]->attendance[$x]->numdayspresent))}}</td>
                        @else
                        <td style="border: 1px solid black; text-align: center;">&nbsp;</td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td colspan="18" style=" border-bottom: 1px solid black;" >
                        <div style="width: 100%; line-height: 5px;">&nbsp;</div>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="width: 20%;">Has advance credit in</td>
                                <td style="border-bottom: 1px solid black;">{{$record[0]->credit_advance}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="width: 15%;">Lacks credits in</td>
                                <td style="border-bottom: 1px solid black;">{{$record[0]->credit_lack}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="width: 30%;">Total number of years in school to date</td>
                                <td style="border-bottom: 1px solid black;">{{$record[0]->noofyears}}</td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 10px;">&nbsp;</div>
                    </td>
                </tr>
            </table>
            {{-- <div style="width: 100%; line-height: 5px;">&nbsp;</div>
            <table style="width: 100%; font-size: 11px; margin-left: 50px; margin-right: 50px;">
                <tr>
                    <td style="width: 20%;">Has advance credit in</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px; margin-left: 50px; margin-right: 50px;">
                <tr>
                    <td style="width: 15%;">Lacks credits in</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px; margin-left: 50px; margin-right: 50px;">
                <tr>
                    <td style="width: 30%;">Total number of years in school to date</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 10px;">&nbsp;</div>
            <div style="border-top: 1px solid black; margin-left: 50px; margin-right: 50px;">&nbsp;</div>
             --}}
            <table style="width: 100%; table-layout: fixed; font-size: 11px; page-break-inside: avoid; margin-left: 50px; margin-right: 50px; margin-top: 20px;">
                <thead>
                    <tr>
                        <th colspan="18" style="border: hidden !important;">
                            <table style="width: 100%; table-layout: fixed; font-size: 11px; page-break-inside: avoid;" >
                                <tr>
                                    <td style="width: 15%; font-weight: bold;">CLASSIFIED AS</td>
                                    <td style="width: 15%; font-weight: bold; font-size: 18px; vertical-align: bottom;" rowspan="2">YEAR</td>
                                    <td rowspan="2" style="width: 6%; vertical-align: bottom;">School</td>
                                    <td rowspan="2" style="border-bottom: 1px solid black; vertical-align: bottom;">{{$record[1]->schoolname}}</td>
                                    <td rowspan="2" style="width: 10%; vertical-align: bottom;">School Year</td>
                                    <td rowspan="2" style="width: 15%; border-bottom: 1px solid black; text-align: center; vertical-align: bottom;">{{$record[1]->sydesc}}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid black; text-align: center;">{{preg_replace('/\D+/', '', $record[1]->levelname)}}</td>
                                </tr>
                            </table>
                            <br/>
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2" colspan="7" style="width: 40% !important; border: 1px solid black;">SUBJECTS</th>
                        <th colspan="7" style=" border: 1px solid black;">PERIODIC RATINGS</th>
                        <th rowspan="2" colspan="2" style="width: 10%; border: 1px solid black;">Action<br/>Taken</th>
                        <th rowspan="2" colspan="2" style="width: 10%; border: 1px solid black;">Credit<br/>Earned</th>
                    </tr>
                    <tr>
                        <th style=" border: 1px solid black;">1</th>
                        <th style=" border: 1px solid black;">2</th>
                        <th style=" border: 1px solid black;">3</th>
                        <th style=" border: 1px solid black;">4</th>
                        <th style=" border: 1px solid black;"></th>
                        <th style=" border: 1px solid black;"></th>
                        <th style=" border: 1px solid black;">Final</th>
                    </tr>
                </thead>
                {{-- @if(strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308') fmcma --}}
                @if(count($record[1]->grades)>0)
                    @foreach($record[1]->grades as $grade)
                        @if($record[1]->type == 2)
                            @if(strtolower($grade->subjtitle) != 'general average')
                                <tr>
                                    <td colspan="7" style=" border: 1px solid black;">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif  @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter1}}</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter2}}</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter3}}</td>
                                    <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter4}}</td>
                                    <td style=" border: 1px solid black;"></td>
                                    <td style=" border: 1px solid black;"></td>
                                    <td style=" border: 1px solid black;" class="text-center">{{$grade->finalrating}}</td>
                                    <td colspan="2" style=" border: 1px solid black;"class="text-center">{{$grade->remarks}}</td>
                                    <td colspan="2" style=" border: 1px solid black;"class="text-center">{{isset($grade->credits) ? $grade->credits : ''}}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td colspan="7" style=" border: 1px solid black;">@if($grade->inMAPEH ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @if(isset($grade->inTLE))@if($grade->inTLE ==1 ) &nbsp;&nbsp;&nbsp;&nbsp; @endif @endif @if(strtolower($grade->subjtitle) == 't.l.e' || strtolower($grade->subjtitle) == 'mapeh' ){{strtoupper($grade->subjtitle)}}@else {{$grade->subjtitle}}@endif</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter1}}</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter2}}</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter3}}</td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->quarter4}}</td>
                                <td style=" border: 1px solid black;"></td>
                                <td style=" border: 1px solid black;"></td>
                                <td class="text-center" style=" border: 1px solid black;">{{$grade->finalrating}}</td>
                                <td colspan="2" class="text-center" style=" border: 1px solid black;">{{$grade->remarks}}</td>
                                <td colspan="2" style=" border: 1px solid black;"class="text-center">{{isset($grade->credits) ? $grade->credits : ''}}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @for($x=0; $x<$maxgradecount; $x++)
                        <tr>
                            <td colspan="7" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">&nbsp;</td>
                            <td colspan="2" style=" border: 1px solid black;">&nbsp;</td>
                            <td colspan="2" style=" border: 1px solid black;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
                <tr>
                    <td colspan="18" style="border: 1px solid black; line-height: 5px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;" colspan="6">Month</td>
                    @for($x = 0; $x < 12; $x++)
                        @php
                            $monthdesc = ($x+1).'th';
                            if(($x+1) == 1)
                            {   
                                $monthdesc = ($x+1).'st';
                            }
                            elseif(($x+1) == 2)
                            {   
                                $monthdesc = ($x+1).'nd';
                            }
                            elseif(($x+1) == 3)
                            {   
                                $monthdesc = ($x+1).'rd';
                            }
                            elseif(($x+1) == 12)
                            {   
                                $monthdesc = 'Total';
                            }
                        @endphp
                        @if(isset($record[1]->attendance[$x]))
                        <td style="border: 1px solid black; text-align: center;">{{ucwords(strtolower($record[1]->attendance[$x]->monthdesc))}}</td>
                        @else
                        <td style="border: 1px solid black; text-align: center;">{{$monthdesc}}</td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="border-left: 1px solid black;" colspan="5">Days of School</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    @for($x = 0; $x < 12; $x++)
                        @if(isset($record[1]->attendance[$x]))
                        <td style="border: 1px solid black; text-align: center;">{{ucwords(strtolower($record[1]->attendance[$x]->numdays))}}</td>
                        @else
                        <td style="border: 1px solid black; text-align: center;">&nbsp;</td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="border-left: 1px solid black; border-bottom: 1px solid black;" colspan="3">Days Present</td>
                    <td style="border-bottom: 1px solid black;" colspan="3"></td>
                    @for($x = 0; $x < 12; $x++)
                        @if(isset($record[1]->attendance[$x]))
                        <td style="border: 1px solid black; text-align: center;">{{ucwords(strtolower($record[1]->attendance[$x]->numdayspresent))}}</td>
                        @else
                        <td style="border: 1px solid black; text-align: center;">&nbsp;</td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td colspan="18" style=" border-bottom: 1px solid black;" >
                        <div style="width: 100%; line-height: 5px;">&nbsp;</div>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="width: 20%;">Has advance credit in</td>
                                <td style="border-bottom: 1px solid black;">{{$record[1]->credit_advance}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="width: 15%;">Lacks credits in</td>
                                <td style="border-bottom: 1px solid black;">{{$record[1]->credit_lack}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="width: 30%;">Total number of years in school to date</td>
                                <td style="border-bottom: 1px solid black;">{{$record[1]->noofyears}}</td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 10px;">&nbsp;</div>
                    </td>
                </tr>
            </table>
        @endforeach
    @endif
    <table style="width: 100%; margin-left: 50px; margin-right: 50px; margin-top: 20px;">
        <tr>
            <th style="font-size: 20px;" colspan="2">TRANSFER</th>
        </tr>
        <tr>
            <td style="font-size: 12px; text-align: center; padding: 0px;" colspan="2">
               I certify that this is a true record of <u>&nbsp;{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} @if($studinfo->middlename != null){{$studinfo->middlename[0].'.'}}@endif &nbsp;</u>. This student is, on this <u>{{date('jS')}}</u> day of <u>{{date('F')}}</u>, 20<u>{{date('y')}}</u>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px; text-align: center; padding: 0px; padding-top: 5px;" colspan="2">                
                    eligible for admission to the <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> and has no money, nor property responsibility in this school.               
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td style="width: 30%; border-bottom: 1px solid black; text-align: center; font-size: 12px;">{{auth()->user()->name}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; font-size: 12px;">Registrar</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td>
                @php
                    $principalname = '';
                    $principal = DB::table('teacher')
                        ->where('id', DB::table('academicprogram')->where('id', $acadprogid)->first()->principalid)
                        ->first();

                    $principalname.=$principal->firstname.' ';
                    if($principal->middlename != null)
                    {
                        $principalname.=$principal->middlename[0].'. ';
                    }
                    $principalname.=$principal->lastname.' ';
                    $principalname.=$principal->suffix.' ';

                @endphp
            </td>
            <td style="width: 30%; border-bottom: 1px solid black; text-align: center; font-size: 12px;">{{$principalname}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; font-size: 12px;">Principal</td>
        </tr>
    </table>