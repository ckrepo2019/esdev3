<html>
    <header>
        <title>School Form 4</title>
        <style>
            @page{
                size: 13in 8.5in;
                margin: 0.5in
                
            }
            * {                
                font-family: Arial, Helvetica, sans-serif;
            }
            table{
                border-collapse: collapse;
            }
            #table-header td{
                padding: 3px;
            }
        </style>
    </header>
    @php
    $schoolinfo = DB::table('schoolinfo')
        ->select(
            'schoolinfo.schoolid',
            'schoolinfo.schoolname',
            'schoolinfo.authorized',
            'refcitymun.citymunDesc as division',
            'schoolinfo.district',
            'schoolinfo.address',
            'schoolinfo.picurl',
            'refregion.regDesc as region',
            'schoolinfo.regiontext',
            'schoolinfo.districttext',
            'schoolinfo.divisiontext'
        )
        ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
        ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
        ->first();
        
$signatoriesv2 = DB::table('signatory')
        ->where('form','form4')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->get();

$signatoriesv2 = array_chunk(collect($signatoriesv2)->toArray(), 2);
    @endphp
    <body>
        <div style="width: 100%; font-weight: bold; text-align: center; font-size: 25px;">School Form 4 (SF4) Monthly Learner's Movement and Attendance</div>
        <div style="width: 100%; text-align: center; font-size: 11px;"><em>(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</em></div>
        <br/>
        <table style="width: 100%; font-size: 12px;" id="table-header">
            <tr>
                <td style="width: 15%; text-align: right;">School ID</td>
                <td style="width: 12%; text-align: center; border: 1px solid black;">{{$schoolinfo->schoolid}}</td>
                <td style="width: 2%;"></td>
                <td style="width: 12%; text-align: center; border: 1px solid black;">{{$schoolinfo->regiontext ?? $schoolinfo->region}}</td>
                <td style="text-align: right;">Division</td>
                <td colspan="2" style="text-align: center; border: 1px solid black;">{{$schoolinfo->divisiontext ?? $schoolinfo->division}}</td>
                <td style="text-align: right;">District</td>
                <td style="text-align: center; border: 1px solid black;">{{$schoolinfo->districttext ?? $schoolinfo->district}}</td>
            </tr>
            <tr>
                <td style="text-align: right;">School Name</td>
                <td colspan="3" style="text-align: center; border: 1px solid black;">{{$schoolinfo->schoolname}}</td>
                <td style="text-align: right;">School Year</td>
                <td style="text-align: center; border: 1px solid black;">{{$sydesc}}</td>
                <td colspan="2" style="text-align: right;">Report for the Month of</td>
                <td style="text-align: center; border: 1px solid black;">{{$monthname}}</td>
            </tr>
        </table>
        <table style="width:100%; font-size: 10px; page-break-inside: always;" border="1" id="table-form">
            <thead style="text-align: center;">
                <tr>
                    <th rowspan="3" style="width: 6%;">Grade/<br/>Year Level</th>
                    <th rowspan="3" style="width: 8%;">Section</th>
                    <th rowspan="3" style="width: 10%;">Name of Adviser</th>
                    <th colspan="3" rowspan="2">REGISTERED<br/>LEARNERS<br/>(As of End of<br/>the Month)</th>
                    <th colspan="6">ATTENDANCE</th>
                    <th colspan="9">NLPA</th>
                    <th colspan="9">TRANSFERRED OUT</th>
                    <th colspan="9">TRANSFERRED IN</th>
                </tr>
                <tr>
                    <th colspan="3">Daily Average</th>
                    <th colspan="3">Percentage for<br/>the Month</th>
                    <th colspan="3">(A) Cumulative<br/>as of Previous<br/>Month</th>
                    <th colspan="3">(B) For the<br/>Month</th>
                    <th colspan="3">(A+B)<br/>Cumulative as of<br/>End of the Month</th>
                    <th colspan="3">(A) Cumulative<br/>as of Previous<br/>Month</th>
                    <th colspan="3">(B) For the<br/>Month</th>
                    <th colspan="3">(A+B)<br/>Cumulative as of<br/>End of the Month</th>
                    <th colspan="3">(A) Cumulative<br/>as of Previous<br/>Month</th>
                    <th colspan="3">(B) For the<br/>Month</th>
                    <th colspan="3">(A+B)<br/>Cumulative as of<br/>End of the Month</th>
                </tr>
                <tr>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                    <th style="width: 2.1%;">M</th>
                    <th style="width: 2.1%;">F</th>
                    <th style="width: 2.1%;">T</th>
                </tr>
            </thead>
                @if(count($gradelevels)>0)
                    @foreach($gradelevels as $gradelevel)
                        @if(count($gradelevel->sections)>0)
                            @foreach($gradelevel->sections as $eachsection)
                                <tr>
                                    <td style="text-align: center;">{{ucwords(strtolower($gradelevel->levelname))}}</td>
                                    <td>{{$eachsection->sectionname}}</td>
                                    <td>@if($eachsection->lastname != null) {{ucwords(strtolower($eachsection->lastname))}}, {{ucwords(strtolower($eachsection->firstname))}} @if($eachsection->middlename != null) {{$eachsection->middlename[0]}}.@endif {{$eachsection->suffix}}@endif</td>
                                    <td style="text-align: center;">{{$eachsection->registeredmale}}</td>
                                    <td style="text-align: center;">{{$eachsection->registeredfemale}}</td>
                                    <td style="text-align: center;">{{$eachsection->registeredmale + $eachsection->registeredfemale}}</td>                                            
                                    @if($eachsection->countdates == 0)
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    @else
                                    <td style="text-align: center;">{{number_format($eachsection->presentmale/$eachsection->countdates,2)}}</td>
                                    <td style="text-align: center;">{{number_format($eachsection->presentfemale/$eachsection->countdates,2)}}</td>
                                    <td style="text-align: center;">{{number_format(((($eachsection->presentmale/$eachsection->countdates)+($eachsection->presentfemale/$eachsection->countdates))/2),2)}}</td>
                                    <td style="text-align: center;">{{number_format((($eachsection->presentmale/$eachsection->countdates)/$eachsection->registeredmale)*100,2)}}</td>
                                    <td style="text-align: center;">{{number_format((($eachsection->presentfemale/$eachsection->countdates)/$eachsection->registeredfemale)*100,2)}}</td>
                                    <td style="text-align: center;">{{number_format(((((($eachsection->presentmale/$eachsection->countdates)/$eachsection->registeredmale)*100)+((($eachsection->presentfemale/$eachsection->countdates)/$eachsection->registeredfemale)*100))/2),2)}}</td>
                                    
                                    @php
                                        $eachsection->da_m = number_format($eachsection->presentmale/$eachsection->countdates,2);
                                        $eachsection->da_f = number_format($eachsection->presentfemale/$eachsection->countdates,2);
                                        $eachsection->da_t = number_format(((($eachsection->presentmale/$eachsection->countdates)+($eachsection->presentfemale/$eachsection->countdates))/2),2);

                                        $eachsection->pfm_m = number_format((($eachsection->presentmale/$eachsection->countdates)/$eachsection->registeredmale)*100,2);
                                        $eachsection->pfm_f = number_format((($eachsection->presentfemale/$eachsection->countdates)/$eachsection->registeredfemale)*100,2);
                                        $eachsection->pfm_t = number_format(((((($eachsection->presentmale/$eachsection->countdates)/$eachsection->registeredmale)*100)+((($eachsection->presentfemale/$eachsection->countdates)/$eachsection->registeredfemale)*100))/2),2);                                            
                                    @endphp
                                    @endif
                                    <td style="text-align: center;">{{$eachsection->nlpa_a_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_a_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_a_m + $eachsection->nlpa_a_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_b_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_b_m + $eachsection->nlpa_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_a_m + $eachsection->nlpa_b_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_a_f + $eachsection->nlpa_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->nlpa_a_f + $eachsection->nlpa_b_f + $eachsection->nlpa_a_m + $eachsection->nlpa_b_m}}</td>
                                    
                                    <td style="text-align: center;">{{$eachsection->to_a_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_a_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_a_m + $eachsection->to_a_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_b_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_b_m + $eachsection->to_b_f}}</td>                                            
                                    <td style="text-align: center;">{{$eachsection->to_a_m + $eachsection->to_b_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_a_f + $eachsection->to_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->to_a_m + $eachsection->to_b_m + $eachsection->to_a_f + $eachsection->to_b_f}}</td>
                                    
                                    <td style="text-align: center;">{{$eachsection->ti_a_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_a_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_a_m + $eachsection->ti_a_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_b_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_b_m + $eachsection->ti_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_a_m + $eachsection->ti_b_m}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_a_f + $eachsection->ti_b_f}}</td>
                                    <td style="text-align: center;">{{$eachsection->ti_a_m + $eachsection->ti_b_m + $eachsection->ti_a_f + $eachsection->ti_b_f}}</td>

                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    <tr>
                        <th colspan="3" style="text-align: left; padding: 3px;">{{$acadprogdesc}}:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach($gradelevels as $gradelevel)
                        <tr>
                            <th colspan="3">{{$gradelevel->levelname}}</th>

                            <th>{{collect($gradelevel->sections)->sum('registeredmale')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('registeredfemale')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('registeredmale')+collect($gradelevel->sections)->sum('registeredfemale')}}</th>
                            
                            <th>{{collect($gradelevel->sections)->sum('da_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('da_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('da_t')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('pfm_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('pfm_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('pfm_t')}}</th>
                            
                            <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_a_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')+collect($gradelevel->sections)->sum('nlpa_a_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_b_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_b_m')+collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')+collect($gradelevel->sections)->sum('nlpa_b_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_a_f')+collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')+collect($gradelevel->sections)->sum('nlpa_b_m')+collect($gradelevel->sections)->sum('nlpa_a_f')+collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                            
                            <th>{{collect($gradelevel->sections)->sum('to_a_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_a_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_a_m')+collect($gradelevel->sections)->sum('to_a_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_b_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_b_m')+collect($gradelevel->sections)->sum('to_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_a_m')+collect($gradelevel->sections)->sum('to_b_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_a_f')+collect($gradelevel->sections)->sum('to_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('to_a_m')+collect($gradelevel->sections)->sum('to_b_m')+collect($gradelevel->sections)->sum('to_a_f')+collect($gradelevel->sections)->sum('to_b_f')}}</th>
                            
                            <th>{{collect($gradelevel->sections)->sum('ti_a_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_a_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_a_m')+collect($gradelevel->sections)->sum('ti_a_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_b_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_b_m')+collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_a_m')+collect($gradelevel->sections)->sum('ti_b_m')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_a_f')+collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                            <th>{{collect($gradelevel->sections)->sum('ti_a_m')+collect($gradelevel->sections)->sum('ti_b_m')+collect($gradelevel->sections)->sum('ti_a_f')+collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                        </tr>
                        @php
                        @endphp
                    @endforeach
                    <tr>
                        <th colspan="3">TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                @endif       
        </table>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="width: 50%; font-size: 14px; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mortality (Death)</td>
                <td style="font-size: 11px; font-weight: bold; vertical-align: top;" rowspan="4" colspan="3">
                    <table style="width: 100%;" >
                    
                        @if(count($signatoriesv2) == 0)
                        {{-- <tr>
                            <td style="width: 3%;"></td>
                        </tr> --}}
                        <tr>
                            <td style="width: 3%;"></td>
                            <td style="text-align: center; font-size: 11px;">Prepared and Submitted by:</td>
                            <td style="width: 3%;"></td>
                            <td style="text-align: center; font-size: 11px;"></td>
                        </tr>
                        <tr>
                            <td style="width: 3%;">&nbsp;</td>
                            <td style="text-align: center; font-size: 11px;" rowspan="2">&nbsp;</td>
                            <td style="width: 3%;">&nbsp;</td>
                            <td style="text-align: center; font-size: 11px;" rowspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 3%;">&nbsp;</td>
                            <td style="width: 3%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 3%;"></td>
                            <td style="text-align: center; font-size: 11px;">(Signature of School Head over Printed Name)</td>
                            <td style="width: 3%;"></td>
                            <td style="text-align: center; font-size: 11px;">Generated thru LIS</td>
                        </tr>
                        @else
                            @foreach($signatoriesv2 as $eachsignatory)
                            <tr>
                                <td style="width: 3%;"></td>
                                @for($x = 0; $x < 2; $x++)
                                    <td style="text-align: center; font-size: 11px;">{{$eachsignatory[$x]->title ?? ''}}&nbsp;</td>
                                    @if($x < 1)
                                    <td style="width: 3%;">&nbsp;</td>
                                    @endif
                                @endfor
                            </tr>
                            <tr>
                                <td style="width: 3%;"></td>
                                @for($x = 0; $x < 2; $x++)
                                    <td style="text-align: center; font-size: 11px;">&nbsp;</td>
                                    @if($x < 1)
                                    <td style="width: 3%;"></td>
                                    @endif
                                @endfor
                            </tr>
                            <tr>
                                <td style="width: 3%;"></td>
                                @for($x = 0; $x < 2; $x++)
                                    <td style="text-align: center; font-size: 11px; {{isset($eachsignatory[$x]) ? 'border-bottom: 1px solid black' : ''}}">{{$eachsignatory[$x]->name ?? ''}}&nbsp;</td>
                                    @if($x < 1)
                                    <td style="width: 3%;"></td>
                                    @endif
                                @endfor
                            </tr>
                            <tr>
                                <td style="width: 3%;"></td>
                                @for($x = 0; $x < 2; $x++)
                                    <td style="text-align: center; font-size: 11px;">{{$eachsignatory[$x]->description ?? ''}}&nbsp;</td>
                                    @if($x < 1)
                                    <td style="width: 3%;"></td>
                                    @endif
                                @endfor
                            </tr>
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: middle; padding-left: 50px;">
                    <table style="width: 80%; table-layout: fixed; font-size: 11px;" border="1">
                        <tr>
                            <th>Previous<br/>Month/s</th>
                            <th></th>
                            <th>For the<br/>Month</th>
                            <th></th>
                            <th style="width: 30%;">Cummulative as of<br/>End of Month</th>
                            <th></th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="font-size: 12px;">
                <td>Generated on: {{date('l, F d, Y')}}</td>
            </tr>
        </table>
    </body>
</html>