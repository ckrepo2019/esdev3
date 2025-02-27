
<html>
    <header>
        <style>
            
            *{
                
                font-family: Arial, Helvetica, sans-serif;
            }
            @page{
                margin: 20px;
            }
        </style>
    </header>
    <body>
        @for($x = 1; $x <= 2; $x++)
            {{-- <div style="width: 100%; line-height: 5px; width: 100%;
            height: 100px;"> --}}
                <img src="{{base_path()}}/public/assets/images/apmc/apmc_coe_header.png" style="width: 100%;">
            {{-- </div> --}}
            <div style="width: 100%; font-size: 15px; text-align: center; font-weight: bold;">STATEMENT OF ACCOUNT</div>
            <div style="width: 100%; font-size: 11px; text-align: center;"><em>{{strtoupper($selectedsemester)}}, School Year {{$selectedschoolyear}}</em></div>
            <table style="font-size: 11px; font-weight: bold; width: 100%; line-height: 2px;">
                {{-- <tr>
                    <td>S.Y {{$selectedschoolyear}}
                    
                        @if($selectedmonth != null)
                        <br/>AS OF : {{strtoupper($selectedmonth)}};
                        @endif
                    </td>
                    <td>
                        @if($selectedsemester != null)
                            SEMESTER : {{strtoupper($selectedsemester)}}
                        @endif
                    </td>
                </tr> --}}
                <tr>
                    <td >STUDENT: {{$studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix}}</td>
                    <td style="text-align: right;">
                        @if($selectedmonth != null)
                        <br/>MONTH OF : {{strtoupper($monthname)}}
                        @endif
                    </td>
                </tr>
            </table>
            <table  cellspacing="0" cellpadding="1" border="1" style="font-size: 9px;" width="100%">
                <thead >
                    <tr>
                        <th colspan="5" style="font-weight: bold; width: 100%; text-align: left;">LEDGER</th>
                    </tr>
                    <tr style="text-align:center;">
                        <th style="font-weight: bold;" width="10%;">Date</th>
                        <th style="font-weight: bold;" width="30%">Description</th>
                        <th style="font-weight: bold;" width="20%">Billing</th>
                        <th style="font-weight: bold;" width="20%">Payment</th>
                        <th style="font-weight: bold;" width="20%">Balance</th>
                    </tr>
                </thead>
                    
                @php
                    $bal = 0;
                    $debit = 0;
                    $credit = 0;
                @endphp
                @foreach($ledger as $led)
                    @php
                        $debit += $led->amount;
                    @endphp
            
                    @if($led->void == 0)
                        @php
                            $credit += $led->payment;
                        @endphp
                    @endif
                    
                    @php
                        $lDate = date_create($led->createddatetime);
                        $lDate = date_format($lDate, 'm-d-Y');
                    @endphp
            
                    @if($led->amount > 0)
                        @php
                            $amount = number_format($led->amount,2);
                        @endphp
                    @else
                        @php
                            $amount = '';
                        @endphp
                    @endif
            
                    @if($led->payment > 0)
                        @php
                            $payment = number_format($led->payment,2);
                        @endphp
                    @else
                        @php
                            $payment = '';
                        @endphp
                    @endif
                    @if($led->void == 0)
                        @php
                            $bal += $led->amount - $led->payment;
                        @endphp
                        <tr>
                            <td width="10%">{{$lDate}}</td>
                            <td width="30%">{{$led->particulars}}</td>
                            <td width="20%" style="text-align:right;">{{$amount}}</td>
                            <td width="20%" style="text-align:right;">{{$payment}}</td>
                            <td width="20%" style="text-align:right;">{{number_format($bal, 2)}}</td>
                        </tr>
                    @else
                        <tr>
                            <td width="10%"><del>{{$lDate}} </del></td>
                            <td width="30%"><del>{{$led->particulars}}</del></td>
                            <td width="20%" style="text-align:right;"><del>{{$amount}}</del></td>
                            <td width="20%" style="text-align:right;"><del>{{$payment}}</del></td>
                            <td width="20%" style="text-align:right;"><del>{{number_format($bal, 2)}}</del></td>
                        </tr>
                    @endif
                @endforeach
                <tr style="background-color:#59bdf0">
                    <th width="10%"></th>
                    <th width="30%" style="text-align:right">
                        TOTAL:
                    </th>
                    <th width="20%" style="text-align:right;">
                        {{number_format($debit, 2)}}
                    </th>
                    <th width="20%" style="text-align:right;">
                        {{number_format($credit, 2)}}
                    </th>
                    <th width="20%" style="text-align:right;">
                        {{number_format($bal, 2)}}
                    </th>
                </tr>
                <tr>
                    <th colspan="5"  style="font-weight: bold; text-align: left;">ASSESSMENT</th>
                </tr>
                @php
            
                    $assessbilling = 0;
                    $assesspayment = 0;
                    $assessbalance = 0;
                    $totalBal = collect($getPaySched)->sum('balance');;
                @endphp
                @if(count($getPaySched) > 0)
                    
                    @foreach($getPaySched as $psched)
                        @php
                            // $totalBal += $psched->balance;
                            $assessbilling += $psched->amountdue;
                            $assesspayment += $psched->amountpay;
                            $assessbalance += $psched->balance;
                            
                            $m = date_create($psched->duedate);
                            $f = date_format($m, 'F');
                            $m = date_format($m, 'm');
                            
                            if($psched->duedate != '')
                            {
                            $particulars = 'TUITION/BOOKS/OTH FEE - ' . $f;  
                            }
                            else
                            {
                            $particulars = 'TUITION/BOOKS/OTH FEE';
                            $m = 0;
                            }
                        @endphp
            
                        
                        @if($month == null || $month == "")
                            @if($m != $month)
                                @if($psched->balance > 0)
                                    <tr>
                                    <td width="10%"></td>
                                    <td width="30%">{{$particulars}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                    </tr>
                                @endif
                            @else
                                @if($psched->balance > 0)
                                    <tr>
                                    <td width="10%"></td>
                                    <td width="30%">{{$particulars}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                    </tr>
                                @else
                                    <tr>
                                    <td width="10%"></td>
                                    <td width="30%">{{$particulars}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                    </tr>
                                @endif
                            @break
                            @endif
                        
                        @else
                            @if($m != $month)
                                <tr>
                                    <td width="10%"></td>
                                    <td width="30%">{{$particulars}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                </tr>
                            @else
                                <tr>
                                    <td width="10%"></td>
                                    <td width="30%">{{$particulars}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                                    <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                </tr>
                            
                            @break; 
                            @endif
                        @endif
                    @endforeach
            
                    <tr style="background-color:#59bdf0">
                        <th width="10%"></th>
                        <th width="30%" style="text-align:right">
                            TOTAL:
                        </th>
                        <th width="20%" style="text-align:right;">
                            {{number_format($assessbilling, 2)}}
                        </th>
                        <th width="20%" style="text-align:right;">
                            {{number_format($assesspayment, 2)}}
                        </th>
                        <th width="20%" style="text-align:right;">
                            {{number_format($assessbalance, 2)}}
                        </th>
                    </tr>
                    <tr style="background-color: #f5e069">
                        <th width="10%"></th>
                        <th width="30%" style="text-align:right">
                            TOTAL BALANCE:
                        </th>
                        <th width="20%"style="text-align:right">
                            {{number_format($assessbilling, 2)}}
                        </th>
                        <th width="20%"style="text-align:right">
                            {{number_format($assesspayment, 2)}}
                        </th>
                        <th width="20%"style="text-align:right">
                            {{number_format(($totalBal-$assesspayment), 2)}}
                        </th>
                    </tr>
                    <tr style="background-color: #f5e069">
                        <th width="10%"></th>
                        <th width="30%" style="text-align:right">
                            TOTAL AMOUNT DUE:
                        </th>
                        <th width="20%"style="text-align:right">
                            
                        
                        </th>
                        <th width="20%"style="text-align:right">
                            
                        </th>
                        <th width="20%" style="font-size:13px;text-align:right">
                            {{number_format(($totalBal-$assesspayment), 2)}}
                        </th>
                    </tr>
            
                @else
            
                    <tr style="background-color: yellow">
                        <th width="10%"></th>
                        <th width="30%" style="text-align:right">
                            TOTAL BALANCE:
                        </th>
                        <th width="20%"style="text-align:right">
                            {{number_format($debit, 2)}}
                        </th>
                        <th width="20%"style="text-align:right">
                            {{number_format($credit, 2)}}
                        </th>
                        <th width="20%"style="text-align:right">
                            {{number_format($bal, 2)}}
                        </th>
                    </tr>
                    <tr style="background-color: yellow">
                        <th width="10%"></th>
                        <th width="30%" style="text-align:right">
                            TOTAL AMOUNT DUE:
                        </th>
                        <th width="20%">
                            
                        
                        </th>
                        <th width="20%">
                            
                        </th>
                        <th width="20%" style="font-size:13px;text-align:right">
                            {{number_format($bal, 2)}}
                        </th>
                    </tr>
                @endif
            </table>
            @if($notestatus>0)
            {
                <span style="font-size: 9px;font-weight: bold">NOTES:</span><br/>
                @foreach($notes as $note)
                    <p style="line-height: 8px; margin-bottom: 0px;font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$note->description}}</p>
                @endforeach
                <br/>&nbsp;
            @endif
            <table  cellspacing="0" cellpadding="1" style="font-size: 9px;" width="100%">
                <thead>
                    <tr>
                        <th style="font-weight: bold; text-align: left;">Prepared By:</th>
                        <th style="font-weight: bold; text-align: left;">Received By:</th>
                    </tr>
                </thead>
                <tr>
                    <td>
                        <table style="width: 80%"  >
                            <tr>
                                <td style="border-bottom: 1px solid black;height: 20px;">
                                
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;text-transform: uppercase; font-weight: bold;">
                                {{$preparedby->firstname.' '.$preparedby->middlename.' '.$preparedby->lastname.' '.$preparedby->suffix}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="width: 80%" >
                            <tr>
                                <td style="border-bottom: 1px solid black;height: 20px;">
                                
                                </td>
                            </tr>
                            <tr>
                                <td style="text-transform: uppercase; font-weight: bold; border-bottom: 1px solid black;">
                                    Date: 
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @if($x == 1)
                <div style="width: 100%; line-height: 10px; border-top: 1px dashed black;">
                    &nbsp;
                </div>
            @endif
        @endfor
    </body>
</html>
{{-- <table style="width: 100%" style="border-bottom: 1px solid black;">
    <tr>
        <td width="20%"><img src="{{$schoolinfo->picurl}}" style="width: 80px; float: right;"></td>
        <td width="60%" style="text-align: center;vertical-align: top;padding: 0px;">
            <div style="width: 100%;">{{$schoolinfo->schoolname}}</div>
            <sup style="width: 100%;">{{$schoolinfo->address}}</sup>
            <div style="width: 100%;font-weight: bold; font-style: italic;">STATEMENT OF ACCOUNT</div>
            {{$selectedsemester}}, School Year {{$selectedschoolyear}}
            <br/>
        </td>
        <td width="20%"></td>
    </tr>
</table>
&nbsp;
<table style="width: 100%; font-size: 11px; border-bottom: 1px solid black;">
    <tr style="line-height: 3px;; ">
        <td colspan="6" width="100%"></td>
    </tr>
    <tr>
        <td width="10%">Name:</td>
        <td style="border-bottom: 1px solid black;" width="40%">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->suffix}}</td>
        <td width="10%">Sex:</td>
        <td width="10%" style="border-bottom: 1px solid black; text-align: center;">{{substr($studinfo->gender, 0, 1)}}</td>
        <td width="15%">Bdate:</td>
        <td width="15%" style="border-bottom: 1px solid black;">{{$studinfo->dob}}</td>
    </tr>
    <tr>
        <td width="10%">Address:</td>
        <td style="border-bottom: 1px solid black;" width="60%" colspan="3">{{$studinfo->address}}</td>
        <td width="15%">Course/Year:</td>
        <td width="15%" style="border-bottom: 1px solid black;"></td>
    </tr>
    <tr style="line-height: 4px;; ">
        <td colspan="6" width="100%"></td>
    </tr>
    <tr style="line-height: 3px;; ">
        <td colspan="6" width="100%" style="border-bottom: 1px solid black;"></td>
    </tr>
    <tr style="line-height: 3px;; ">
        <td colspan="6" width="100%"></td>
    </tr>
</table>

&nbsp;
<br/>

<table style="width: 100%; font-size: 11px;">
    <tr>
        <td width="15%">Remarks:</td>
        <td width="15%" style="text-align: center;">{{$studinfo->studtype}}</td>
        <td width="15%">NO. OF UNITS:</td>
        <td width="15%" style="text-align: center;">{{$units}}</td>
        <td width="40%"></td>
    </tr>
    <tr>
        <td width="15%">Assessment:</td>
        <td colspan="4" width="90%"></td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;table-layout: fixed;">
    
    <tr>
        <td width="40%">
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="60%">&nbsp;&nbsp;&nbsp;&nbsp;Tuition Fee ({{$unitprice}}/unit)</td>
                    <td width="40%"></td>
                </tr>
            </table>
        </td>
        <td width="10%">
            
        </td>
        <td width="40%">
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="70%">Payment:</td>
                    <td width="30%"></td>
                </tr>
            </table>
        </td>
        <td width="10%">

        </td>
    </tr>
</table>
<br/>
<div style="width: 100%; border-bottom: 1px dashed black;"></div>
<br/>
<table style="width: 100%" style="border-bottom: 1px solid black;">
    <tr>
        <td width="20%"><img src="{{$schoolinfo->picurl}}" style="width: 80px; float: right;"></td>
        <td width="60%" style="text-align: center;vertical-align: top;padding: 0px;">
            <div style="width: 100%;">{{$schoolinfo->schoolname}}</div>
            <sup style="width: 100%;">{{$schoolinfo->address}}</sup>
            <div style="width: 100%;font-weight: bold; font-style: italic;">STATEMENT OF ACCOUNT</div>
            {{$selectedsemester}}, School Year {{$selectedschoolyear}}
            <br/>
        </td>
        <td width="20%"></td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px; border-bottom: 1px solid black;">
    <tr style="line-height: 3px;; ">
        <td colspan="6" width="100%"></td>
    </tr>
    <tr>
        <td width="10%">Name:</td>
        <td style="border-bottom: 1px solid black;" width="40%">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->suffix}}</td>
        <td width="10%">Sex:</td>
        <td width="10%" style="border-bottom: 1px solid black; text-align: center;">{{substr($studinfo->gender, 0, 1)}}</td>
        <td width="15%">Bdate:</td>
        <td width="15%" style="border-bottom: 1px solid black;">{{$studinfo->dob}}</td>
    </tr>
    <tr>
        <td width="10%">Address:</td>
        <td style="border-bottom: 1px solid black;" width="60%" colspan="3">{{$studinfo->address}}</td>
        <td width="15%">Course/Year:</td>
        <td width="15%" style="border-bottom: 1px solid black;"></td>
    </tr>
    <tr style="line-height: 4px;; ">
        <td colspan="6" width="100%"></td>
    </tr>
    <tr style="line-height: 3px;; ">
        <td colspan="6" width="100%" style="border-bottom: 1px solid black;"></td>
    </tr>
    <tr style="line-height: 3px;; ">
        <td colspan="6" width="100%"></td>
    </tr>
</table>

&nbsp;
<br/>

<table style="width: 100%; font-size: 11px;">
    <tr>
        <td width="15%">Remarks:</td>
        <td width="15%" style="text-align: center;">{{$studinfo->studtype}}</td>
        <td width="15%">NO. OF UNITS:</td>
        <td width="15%" style="text-align: center;">{{$units}}</td>
        <td width="40%"></td>
    </tr>
    <tr>
        <td width="15%">Assessment:</td>
        <td colspan="4" width="90%"></td>
    </tr>
</table> --}}
