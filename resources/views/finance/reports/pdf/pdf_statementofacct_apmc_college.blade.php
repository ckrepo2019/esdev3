
<html>
    <header>
        <style>
            
            *{
                
                font-family: Arial, Helvetica, sans-serif;
            }
            @page{
                margin: 18px 18px 5px 18px;
            }
        </style>
    </header>
    <body>
        @php
            $nstp = 0;
            $comlabfee = 0;
            $totalassessment = (collect($itemized)->where('classid','!=',7)->sum('itemamount')+($unitprice*$units));
            
            if(collect($ledger)->where('classid',20)->count()>0)
            {
                $totalassessment+=collect($ledger)->where('classid',20)->sum('amount');
                $checknstp = collect($ledger)->where('classid',20)->filter(function ($item){
                    return false !== stristr($item->particulars, 'nstp');
                })->values();

                if(count($checknstp)>0)
                {
                    $nstp += collect($checknstp)->sum('amount');
                }
                $checkcomlabfee = collect($ledger)->where('classid',20)->filter(function ($item){
                    return true !== stristr($item->particulars, 'cs');
                })->values();

                if(count($checkcomlabfee)>0)
                {
                    $comlabfee += collect($checkcomlabfee)->sum('amount');
                }
            }

        @endphp
        <img src="{{base_path()}}/public/assets/images/apmc/apmc_coe_header.png" style="width: 100%;">
        <div style="width: 100%;font-weight: bold; font-style: italic; text-align: center;">STATEMENT OF ACCOUNT</div>
        <div style="width: 100%; text-align: center; border-bottom: 1px solid black; font-size: 13px;">{{$selectedsemester}}, School Year {{$selectedschoolyear}}</div>
        
        <table style="width: 100%; font-size: 11px; border-bottom: 1px solid black; margin: 0px 50px;">
            {{-- <tr style="line-height: 3px;; ">
                <td colspan="6" width="100%"></td>
            </tr> --}}
            <tr>
                <td width="10%">Name:</td>
                <td style="border-bottom: 1px solid black;" width="40%">{{$studinfo->lastname}}, {{$studinfo->firstname}} @if($studinfo->middlename != null) {{$studinfo->middlename[0]}}. @endif {{$studinfo->suffix}}</td>
                <td width="5%">Sex:</td>
                <td width="5%" style="border-bottom: 1px solid black; text-align: center;">{{substr($studinfo->gender, 0, 1)}}</td>
                <td width="10%">Bdate:</td>
                <td width="30%" style="border-bottom: 1px solid black;">{{$studinfo->dob}}</td>
            </tr>
            <tr>
                <td width="10%">Address:</td>
                <td style="border-bottom: 1px solid black;" width="50%" colspan="3">{{$studinfo->address}}</td>
                <td width="10%">Course/Year:</td>
                <td width="30%" style="border-bottom: 1px solid black;">{{$courseandyear}}</td>
            </tr>
            {{-- <tr style="line-height: 2px;; ">
                <td colspan="6" width="100%">&nbsp;</td>
            </tr> --}}
            <tr style="line-height: 1px;">
                <td colspan="6" width="100%" style="border-bottom: 1px solid black;">&nbsp;</td>
            </tr>
            {{-- <tr style="line-height: 2px;; ">
                <td colspan="6" width="100%"></td>
            </tr> --}}
        </table>   
        <table style="width: 100%; font-size: 11px; margin: 0px 50px;">
            <tr>
                <td width="15%">Remarks:</td>
                <td width="15%" style="text-align: center;">{{strtoupper($studinfo->studtype)}}</td>
                <td width="15%">NO. OF UNITS:</td>
                <td width="15%" style="text-align: center;">{{$units}}</td>
                <td width="40%"></td>
            </tr>
            <tr>
                <td width="15%">Assessment:</td>
                <td colspan="4" width="90%"></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 11px;table-layout: fixed; margin: 0px 60px;">            
            <tr>
                <td width="45%" style="vertical-align: center;" rowspan="2">
                    <table style="width: 100%; font-size: 11px;">
                        <tr>
                            <td width="60%">&nbsp;&nbsp;&nbsp;&nbsp;Tuition Fee ({{$unitprice}}/unit)</td>
                            <td width="40%" style="text-align: right; padding-right: 20px;">{{number_format($unitprice*$units,2)}}</td>
                        </tr>
                        <tr>
                            <td  colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;Other Fees:</td>
                        </tr>
                        @if(count($itemized)>0) 
                            @foreach($itemized as $item)
                                <tr style="font-size: 10px !important;">
                                    <td style=" padding-left: 40px;">
                                        @if(ctype_upper($item->description))
                                        {{$item->description}}
                                        @else
                                        {{ucwords(strtolower($item->description))}}
                                        @endif
                                    </td>
                                    <td style="text-align: right; padding-right: 20px;">{{$item->itemamount}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(collect($ledger)->where('classid','20')->count()>0)
                            @foreach(collect($ledger)->where('classid','20')->values() as $eachpart)
                                <tr>
                                    <td width="60%">&nbsp;&nbsp;&nbsp;&nbsp; {{$eachpart->particulars}}</td>
                                    <td width="40%" style="text-align: right; padding-right: 20px;">{{$eachpart->amount}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td></td>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style=" text-align: right;">{{number_format(($totalassessment),2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>

                    </table>
                    <table style="width: 100%; font-size: 12px;">
                        <tr>
                            <td>Less:</td>
                            <td width="20%">of Tuition Fee</td>
                            <td style="border-bottom: 1px solid black; text-align: right;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style=" text-align: right;">{{number_format($totalassessment,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Previous BA:</td>
                            <td style="border-bottom: 1px solid black; text-align: right;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style=" text-align: right;">{{number_format($totalassessment,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Downpayment:</td>
                            <td style="border-bottom: 1px solid black; text-align: right;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                    </table>

                </td>
                <td width="10%" rowspan="2">
                    
                </td>
                <td width="45%" style="vertical-align: center;">
                    <table style="width: 100%; font-size: 12px;">
                        <tr>
                            <td colspan="2">Payment:</td>
                            <td width="35%"></td>
                        </tr>
                        <tr>
                            <td width="20%"></td>
                            <td width="45%">Prelim Exam</td>
                            <td>{{number_format($totalassessment/4,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td width="20%"></td>
                            <td width="45%">Mid-term Exam</td>
                            <td>{{number_format($totalassessment/4,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td width="20%"></td>
                            <td width="45%">Semi-Final Exam</td>
                            <td>{{number_format($totalassessment/4,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td width="20%"></td>
                            <td width="45%">Final Exam</td>
                            <td>{{number_format($totalassessment/4,2,'.',',')}}</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <table style="width: 100%; font-size: 12px;">
                        <tr>
                            <td style="width: 25%;">Total</td>
                            <td rowspan="2" style="width: 45%; height: 30px; border: 1px solid black; font-size: 20px; text-align: right;">{{number_format($totalassessment,2,'.',',')}} &nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: bottom; padding: 0px;">
                    <table style="width: 100%; border-collapse; collapse;"  cellpadding="0">
                        <tr>
                            <td colspan="4">Assessed by:</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 25%;"></td>
                            <td colspan="2" style="border-bottom: 1px solid black; text-align: center;">EMILIA C. ZANORIA {{--uppercase--}}
                            </td>
                            <td style="width: 25%;"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center;">Cash Disbursing Officer</td>
                        </tr>
                        <tr>
                            <td colspan="2">Date assessed:</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="4"><em>Note: Please report immediately any data entry error.</em></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="width: 100%; border-bottom: 1px dashed black; margin: 5px;"></div>
        <img src="{{base_path()}}/public/assets/images/apmc/apmc_coe_header.png" style="width: 100%; margin-top: 5px;">
        <div style="width: 100%;font-weight: bold; font-style: italic; text-align: center;">STATEMENT OF ACCOUNT</div>
        <div style="width: 100%; text-align: center; border-bottom: 1px solid black; font-size: 13px;">{{$selectedsemester}}, School Year {{$selectedschoolyear}}</div>
        
        <table style="width: 100%; font-size: 11px; border-bottom: 1px solid black; margin: 0px 50px;">
            {{-- <tr style="line-height: 3px;; ">
                <td colspan="6" width="100%"></td>
            </tr> --}}
            <tr>
                <td width="10%">Name:</td>
                <td style="border-bottom: 1px solid black;" width="40%">{{$studinfo->lastname}}, {{$studinfo->firstname}} @if($studinfo->middlename != null) {{$studinfo->middlename[0]}}. @endif  {{$studinfo->suffix}}</td>
                <td width="5%">Sex:</td>
                <td width="5%" style="border-bottom: 1px solid black; text-align: center;">{{substr($studinfo->gender, 0, 1)}}</td>
                <td width="10%">Bdate:</td>
                <td width="30%" style="border-bottom: 1px solid black;">{{$studinfo->dob}}</td>
            </tr>
            <tr>
                <td width="10%">Address:</td>
                <td style="border-bottom: 1px solid black;" width="50%" colspan="3">{{$studinfo->address}}</td>
                <td width="10%">Course/Year:</td>
                <td width="30%" style="border-bottom: 1px solid black;">{{$courseandyear}}</td>
            </tr>
            {{-- <tr style="line-height: 2px;; ">
                <td colspan="6" width="100%">&nbsp;</td>
            </tr> --}}
            <tr style="line-height: 1px;">
                <td colspan="6" width="100%" style="border-bottom: 1px solid black;">&nbsp;</td>
            </tr>
            {{-- <tr style="line-height: 2px;; ">
                <td colspan="6" width="100%"></td>
            </tr> --}}
        </table>   
        <table style="width: 100%; font-size: 11px; margin: 0px 50px;">
            <tr>
                <td width="15%">Remarks:</td>
                <td width="15%" style="text-align: center;">{{strtoupper($studinfo->studtype)}}</td>
                <td width="15%">NO. OF UNITS:</td>
                <td width="15%" style="text-align: center;">{{$units}}</td>
                <td width="40%"></td>
            </tr>
            <tr>
                <td width="15%">Assessment:</td>
                <td colspan="4" width="90%"></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 11px;table-layout: fixed; margin: 0px 60px;">            
            <tr>
                <td width="45%" style="vertical-align: center;">
                    <table style="width: 100%; font-size: 11px;">
                        <tr>
                            <td width="60%">&nbsp;&nbsp;&nbsp;&nbsp;Tuition Fee ({{$unitprice}}/unit)</td>
                            <td width="40%" style="text-align: right; padding-right: 20px;">{{number_format($unitprice*$units,2)}}</td>
                        </tr>
                        <tr>
                            <td >&nbsp;&nbsp;&nbsp;&nbsp;Other Fees:</td>
                            <td style=" text-align: right;">{{number_format(collect($itemized)->where('classid','!=',7)->sum('itemamount'),2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        @if($nstp>0)
                        <tr>
                            <td width="60%">&nbsp;&nbsp;&nbsp;&nbsp;NSTP</td>
                            <td width="40%" style="text-align: right; padding-right: 20px;">{{$nstp}}</td>
                        </tr>
                        @endif
                        @if($comlabfee>0)
                        <tr>
                            <td width="60%">&nbsp;&nbsp;&nbsp;&nbsp;COMLAB FEE</td>
                            <td width="40%" style="text-align: right; padding-right: 20px;">{{$comlabfee}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td></td>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style=" text-align: right;">{{number_format($totalassessment,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>

                    </table>
                    <table style="width: 100%; font-size: 12px;">
                        <tr>
                            <td>Less:</td>
                            <td width="20%">of Tuition Fee</td>
                            <td style="border-bottom: 1px solid black; text-align: right;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style=" text-align: right;">{{number_format($totalassessment,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Previous BA:</td>
                            <td style="border-bottom: 1px solid black; text-align: right;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style=" text-align: right;">{{number_format($totalassessment,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Downpayment:</td>
                            <td style="border-bottom: 1px solid black; text-align: right;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                    </table>
                    
                    <table style="width: 100%; font-size: 20px; margin-top: 5px;">
                        <tr>
                            <td width="15%">TOTAL:</td>
                            <td colspan="4" width="90%" style="text-align: right;">{{number_format($totalassessment,2,'.',',')}}</td>
                        </tr>
                    </table>
                </td>
                <td width="10%" rowspan="2">
                    
                </td>
                <td width="45%" style="vertical-align: bottom; padding: 0px;">
                    <table style="width: 100%; border-collapse; collapse;"  cellpadding="0">
                        <tr>
                            <td colspan="4">Assessed by:</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 25%;"></td>
                            <td colspan="2" style="border-bottom: 1px solid black; text-align: center;">EMILIA C. ZANORIA {{--uppercase--}}
                            </td>
                            <td style="width: 25%;"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center;">Cash Disbursing Officer</td>
                        </tr>
                        <tr>
                            <td colspan="2">Date assessed:</td>
                            <td colspan="2"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
    </body>
</html>