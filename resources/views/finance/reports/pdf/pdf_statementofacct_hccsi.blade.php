<html>
    <style>
        *{
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif;
        }
        table, td, th {
            border-collapse: collapse;
        }
    </style>
<body>
    @if(count($students)>0)
        @foreach($students as $eachstudent)
        <table style="width: 100%; page-break-inside: avoid;" border="1">
                <tr>
                    <td style="padding: 0px; width: 50%;"><table style="width: 100%; margin: 0px;">
                            <tr>
                                <td style="width: 80%; padding: 0px; font-weight: bold;">{{$eachstudent[0]->lastname.', '.$eachstudent[0]->firstname.' '.$eachstudent[0]->middlename.' '.$eachstudent[0]->suffix}}</td>
                                <td style="width: 20%;text-align: right;">{{date('d M, Y')}}</td>
                            </tr>
                            <tr>
                                <td>{{$eachstudent[0]->levelname}} - {{$eachstudent[0]->sectionname}}</td>
                                <td style="text-align: right;">{{$selectedschoolyear}}</td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 5px;">&nbsp;</div>
                        <table style="width: 100%; text-align: center;">
                            <tr>
                                <th style="width: 35%; font-weight: bold;">Particulars</th>
                                <th style="width: 20%; font-weight: bold;">Amount</th>
                                <th style="width: 15%; font-weight: bold;">PAYT</th>
                                <th style="width: 15%; font-weight: bold;">Balance</th>
                                <th style="width: 15%;">D U E</th>
                            </tr>
                        </table>
                    </td>
                    @if(count($eachstudent) == 2)
                    <td style="padding: 0px; width: 50%;"><table style="width: 100%; margin: 0px;">
                            <tr>
                                <td style="width: 80%; padding: 0px; font-weight: bold;">{{$eachstudent[1]->lastname.', '.$eachstudent[1]->firstname.' '.$eachstudent[1]->middlename.' '.$eachstudent[1]->suffix}}</td>
                                <td style="width: 20%; text-align: right;">{{date('d M, Y')}}</td>
                            </tr>
                            <tr>
                                <td>{{$eachstudent[1]->levelname}} - {{$eachstudent[1]->sectionname}}</td>
                                <td style="text-align: right;">{{$selectedschoolyear}}</td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 5px;">&nbsp;</div>
                        <table style="width: 100%; text-align: center;">
                            <tr>
                                <th style="width: 40%; font-weight: bold;">Particulars</th>
                                <th style="width: 15%; font-weight: bold;">Amount</th>
                                <th style="width: 15%; font-weight: bold;">PAYT</th>
                                <th style="width: 15%; font-weight: bold;">Balance</th>
                                <th style="width: 15%;">D U E</th>
                            </tr>
                        </table>
                    </td>
                    @else
                    <td>&nbsp;</td>
                    @endif
                </tr>
            <tr>
                <td>
                    @php
                        $trs1 = count($eachstudent[0]->miscs)+count($eachstudent[0]->tuis);
                    @endphp
                    <table style="width: 100%;">
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        @if(count($eachstudent[0]->miscs) > 0)
                            @foreach($eachstudent[0]->miscs as $misc)                            
                                @if(count($misc->items) > 0)
                                    @foreach($misc->items as $item)
                                        <tr>
                                            <td style="width: 35%; font-size: 10px;">{{ucwords(strtolower($item->description))}}</td>
                                            <td style="width: 20%; text-align: right; font-size: 11px;">{{number_format($item->itemamount,2,'.',',')}}</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($item->totalamount,2,'.',',')}}</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">@if($item->balance>0){{number_format($item->balance,2,'.',',')}}@endif</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">@if($item->balance>0){{number_format($item->balance,2,'.',',')}}@endif</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        @if(count($eachstudent[0]->tuis) > 0)
                            @foreach($eachstudent[0]->tuis as $tui)                            
                                <tr>
                                    <td style="width: 40%; font-size: 10px;">{{ucwords(strtolower($tui->particulars))}}</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($tui->amount,2,'.',',')}}</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($tui->amountpay,2,'.',',')}}</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">@if($tui->balance>0 || $tui->balance != '0.00'){{number_format($tui->balance,2,'.',',')}}@endif</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">@if($tui->balance>0 || $tui->balance != '0.00'){{number_format($tui->balance,2,'.',',')}}@endif</td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                                </tr>
                            @endforeach
                        @endif
                        @for($x = $trs1; $x<30; $x++)
                            <tr>
                                <td colspan="5">&nbsp;</td>
                            </tr>
                        @endfor
                    </table>
                </td>
                
                @if(count($eachstudent) == 2)
                <td>
                    @php
                        $trs2 = count($eachstudent[1]->miscs)+count($eachstudent[1]->tuis);
                    @endphp
                    <table style="width: 100%;">
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        @if(count($eachstudent[1]->miscs) > 0)
                            @foreach($eachstudent[1]->miscs as $misc)
                            
                                @if(count($misc->items) > 0)
                                    @foreach($misc->items as $item)
                                        <tr>
                                            <td style="width: 40%; font-size: 10px;">{{ucwords(strtolower($item->description))}}</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($item->itemamount,2,'.',',')}}</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($item->totalamount,2,'.',',')}}</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">@if($item->balance>0){{number_format($item->balance,2,'.',',')}}@endif</td>
                                            <td style="width: 15%; text-align: right; font-size: 11px;">@if($item->balance>0){{number_format($item->balance,2,'.',',')}}@endif</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        @if(count($eachstudent[1]->tuis) > 0)
                            @foreach($eachstudent[1]->tuis as $tui)                            
                                <tr>
                                    <td style="width: 40%; font-size: 10px;">{{ucwords(strtolower($tui->particulars))}}</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($tui->amount,2,'.',',')}}</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">{{number_format($tui->amountpay,2,'.',',')}}</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">@if($tui->balance>0 || $tui->balance != '0.00'){{number_format($tui->balance,2,'.',',')}}@endif</td>
                                    <td style="width: 15%; text-align: right; font-size: 11px;">@if($tui->balance>0 || $tui->balance != '0.00'){{number_format($tui->balance,2,'.',',')}}@endif</td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                                </tr>
                            @endforeach
                        @endif
                        @for($x = $trs2; $x<30; $x++)
                            <tr>
                                <td colspan="5">&nbsp;</td>
                            </tr>
                        @endfor
                    </table>
                </td>
                @else
                <td></td>
                @endif
            </tr>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 65%; text-align: right;"><i>Outstanding Balance</i></td>
                            <td style="width: 20%; text-align: right;">{{number_format(collect($eachstudent[0]->miscs)->sum('balance')+collect($eachstudent[0]->tuis)->sum('balance'),2,'.',',')}}</td>
                            <td style="width: 15%; text-align: right;"></td>
                        </tr>
                        <tr>
                            <td style="width: 63%; text-align: right;"><i>Total due for this month</i></td>
                            <td style="width: 20%; text-align: right;"></td>
                            <td style="width: 17%; text-align: right; font-weight: bold;">{{number_format(collect($eachstudent[0]->miscs)->sum('balance')+collect($eachstudent[0]->tuis)->sum('balance'),2,'.',',')}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 65%; text-align: right;"><i>Outstanding Balance</i></td>
                            <td style="width: 20%; text-align: right;">@if(count($eachstudent) == 2){{number_format(collect($eachstudent[1]->miscs)->sum('balance')+collect($eachstudent[1]->tuis)->sum('balance'),2,'.',',')}}@endif</td>
                            <td style="width: 15%; text-align: right;"></td>
                        </tr>
                        <tr>
                            <td style="width: 63%; text-align: right;"><i>Total due for this month</i></td>
                            <td style="width: 20%; text-align: right;"></td>
                            <td style="width: 17%; text-align: right; font-weight: bold;">@if(count($eachstudent) == 2){{number_format(collect($eachstudent[1]->miscs)->sum('balance')+collect($eachstudent[1]->tuis)->sum('balance'),2,'.',',')}}@endif</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; line-height: 5px;"></td>
                <td style="width: 50%; line-height: 5px;"></td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td colspan="3" style="line-height: 5px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 45%;">&nbsp;&nbsp;Please pay on or before</td>
                            <td style="border-bottom: 1px solid black; width: 30%;"></td>
                            <td style="width: 25%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Disregard if payment has been made</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td style="text-align: right;">Thank You</td>
                        </tr>
                    </table>
                    <br/>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td colspan="3" style="line-height: 5px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 45%;">&nbsp;&nbsp;Please pay on or before</td>
                            <td style="border-bottom: 1px solid black; width: 30%;"></td>
                            <td style="width: 25%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Disregard if payment has been made</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td style="text-align: right;">Thank You</td>
                        </tr>
                    </table>
                    <br/>
                </td>
            </tr>
        </table>
        {{-- <div style="page-break-after: always;"></div> --}}
        @endforeach
    @endif
</body>
    
</html>