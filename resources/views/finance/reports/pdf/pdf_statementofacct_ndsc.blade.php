
<html>
    <header>
        <style>
            
            *{
                
                font-family: Arial, Helvetica, sans-serif;
            }
            @page{
                margin: 45px 50px;
            }
            table{
                border-collapse: collapse;
            }
        </style>
    </header>
    <body>
        <table style="width: 100%;">
            <tr style="font-size: 16px; font-weight: bold; text-align: left !important;">
                <th colspan="2">{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</th>
            </tr>
            <tr>
                <td style="width: 70%; font-size: 14px; font-weight: bold; text-align: left !important; vertical-align: top;" rowspan="2">Archdiocesan Notre Dame Schools of Cotabato</td>
                <td style="font-size: 13px; font-weight: bold;">Student Section</td>
            </tr>
            <tr>
                <td style="font-size: 13px; font-weight: bold;">{{$studinfo->sectionname}}</td>
            </tr>
        </table>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="width: 70%; font-weight: bold; text-align: left !important; vertical-align: top;">Student ID#: {{$studinfo->sid}}</td>
                <td style="font-weight: bold;">Statement of Account</td>
            </tr>
            <tr>
                <td style="width: 70%; font-weight: bold; text-align: left !important; vertical-align: top;" rowspan="2">{{strtoupper($studinfo->lastname)}}, {{strtoupper($studinfo->firstname)}} @if($studinfo->suffix != null){{strtoupper($studinfo->middlename)[0]}}.@endif - {{$studinfo->levelname}}</td>
                <td style="font-weight: bold;">As of {{$monthname}}</td>
            </tr>
            <tr>
                <td>Page 1 of 1</td>
            </tr>
        </table>
        <br/>
        <br/>
        <table style="width: 100%; table-layout: fixed;">
            <tr style="font-size: 11px;">
                <th style="width: 10%; border-top: 1px solid black; border-bottom: 1px solid black;">DATE</th>
                <th style="width: 15%; border-top: 1px solid black; border-bottom: 1px solid black;">REF. #</th>
                <th style="border-top: 1px solid black; border-bottom: 1px solid black;">Description</th>
                <th style="width: 12%; border-top: 1px solid black; border-bottom: 1px solid black;">Prev. Balance</td>
                <th style="width: 12%;border-top: 1px solid black; border-bottom: 1px solid black;">Billing</th>
                <th style="width: 12%;border-top: 1px solid black; border-bottom: 1px solid black;">Payments</th>
                <th style="width: 12%;border-top: 1px solid black; border-bottom: 1px solid black;">BALANCE</td>
            </tr>
            @if(count($previousbalance)>0)
            @endif
            
            @php

                $assessbilling = 0;
                $assesspayment = 0;
                $assessbalance = 0;
                $overallbilling = 0;
                $overallpayment = 0;
                $overallbalance = 0;
                $totalBal = 0;
            @endphp
            @if(count($getPaySched) > 0)                    
                <tr style="font-size: 10px;">
                    <td></td>
                    <td style="text-align: right; font-weight: bold;">Amount Due:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($getPaySched as $psched)
                    @if($monthsetupid == 0)
                        @php
                            $overallbilling += $psched->amountdue;
                            $overallpayment += $psched->amountpay;
                            $overallbalance += $psched->balance;

                            $assessbilling += $psched->amountdue;
                            $assesspayment += $psched->amountpay;
                            $assessbalance += $psched->balance;
                            $totalBal += $psched->balance;
                            $m = date_create($psched->duedate);
                            $f = date_format($m, 'F');
                            $m = date_format($m, 'm');
                            
                            if($psched->duedate != null && $psched->duedate != '')
                            {
                            $particulars = 'TUITION/BOOKS/OTH FEE - ' . $f;  
                            }
                            else
                            {
                            $particulars = 'TUITION/BOOKS/OTH FEE';
                            $m = 0;
                            }
                        @endphp           
                        <tr style="font-size: 10px;">
                            <td></td>
                            <td style="text-align: right;">@if($psched->duedate != null) {{date('d/m/Y', strtotime($psched->duedate))}} @endif</td>
                            <td style="padding-left: 10px;">{{$particulars}}</td>
                            <td></td>
                            <td style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                            <td style="text-align:right;">@if($psched->amountpay > 0){{number_format($psched->amountpay, 2)}}@endif</td>
                            <td style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                        </tr>
                    @else
                        @php
                            
                            $m = date_create($psched->duedate);
                            $f = date_format($m, 'F');
                            $m = date_format($m, 'm');
                            
                            if($psched->duedate != null && $psched->duedate != '')
                            {
                            $particulars = 'TUITION/BOOKS/OTH FEE - ' . $f;  
                            }
                            else
                            {
                            $particulars = 'TUITION/BOOKS/OTH FEE';
                            $m = 0;
                            }
                            $arraymonthsetups = collect($monthsetup)->where('id','<=', $monthsetupid)->values();
                        @endphp
                        @if(count($arraymonthsetups)>0)
                            @if($psched->monthid == 0)
                                @php          
                                    $overallbilling += $psched->amountdue;
                                    $overallpayment += $psched->amountpay;
                                    $overallbalance += $psched->balance;              
                                    $assessbilling += $psched->amountdue;
                                    $assesspayment += $psched->amountpay;
                                    $assessbalance += $psched->balance;
                                @endphp           
                                <tr style="font-size: 10px;">
                                    <td></td>
                                    <td style="text-align: right;">@if($psched->duedate != null) {{date('d/m/Y', strtotime($psched->duedate))}} @endif</td>
                                    <td style="padding-left: 10px;">{{$particulars}}</td>
                                    <td></td>
                                    <td style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                    <td style="text-align:right;">@if($psched->amountpay > 0){{number_format($psched->amountpay, 2)}}@endif</td>
                                    <td style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                </tr>
                            @else
                                @if(collect($arraymonthsetups)->where('id', $psched->monthid)->count()>0)
                                @php       
                                    $overallbilling += $psched->amountdue;
                                    $overallpayment += $psched->amountpay;
                                    $overallbalance += $psched->balance;                   
                                    $assessbilling += $psched->amountdue;
                                    $assesspayment += $psched->amountpay;
                                    $assessbalance += $psched->balance;
                                @endphp           
                                    <tr style="font-size: 10px;">
                                        <td></td>
                                        <td style="text-align: right;">@if($psched->duedate != null) {{date('d/m/Y', strtotime($psched->duedate))}} @endif</td>
                                        <td style="padding-left: 10px;">{{$particulars}}</td>
                                        <td></td>
                                        <td style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                                        <td style="text-align:right;">@if($psched->amountpay > 0){{number_format($psched->amountpay, 2)}}@endif</td>
                                        <td style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                                    </tr>
                                @endif
                            @endif
                        @endif
                    @endif
                @endforeach
                <tr style="font-size: 10px; font-weight: bold;">
                    <td style="border-top: 1px solid black;"></td>
                    <td style="border-top: 1px solid black; text-align: right;">Total Balance:</td>
                    <td style="border-top: 1px solid black;"></td>
                    <td style="border-top: 1px solid black;"></td>
                    <td style="border-top: 1px solid black;"></td>
                    <td style="border-top: 1px solid black; text-align: right;">{{number_format($overallpayment, 2)}}</td>
                    <td style="border-top: 1px solid black; text-align: right;">{{number_format($overallbalance, 2)}}</td>
                </tr>
                <tr style="font-size: 10px; font-weight: bold;">
                    <td></td>
                    <td style="text-align: right;">Total Amount Due:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">{{number_format($overallbalance, 2)}}</td>
                </tr>
            @endif
        </table>
        <div style="width: 100%; border-top: 1px solid black; padding-top: 5px;">
            <h6 style="margin: 0px; padding: 0px;">NOTES:</h6>
            <p style="font-size: 11px; margin: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please disregard notice if statement received after payment. Thank you.</p>
        </div>
        <br/>
        <table style="width: 100%; font-size: 11px; table-layout: fixed;">
            <tr>
                <th style="width: 30%; text-align: left;">Prepared By:</td>
                <td></td>
                <th style="width: 35%; text-align: left;">Received By:</th>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid black;">&nbsp;</td>
                <td></td>
                <td style="border-bottom: 1px solid black;">&nbsp;</td>
            </tr>
            <tr>
                <td>{{DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->firstname}} {{DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->lastname}}</td>
                <td></td>
                <td style="border-bottom: 1px solid black;">Date:</td>
            </tr>
        </table>
    </body>
</html>

{{-- <table style="font-size: 9px; font-weight: bold; padding-top: 5px;">
    <tr>
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
    </tr>
    <tr>
        <td>STUDENT: {{$studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix}}</td>
        <td>GRADE LEVEL: {{$studinfo->levelname}} - {{$studinfo->sectionname}}<br/>COURSE: {{$studinfo->courseabrv}}
        </td>
    </tr>
</table>
<br/>
<table  cellspacing="0" cellpadding="1" border="1" style="font-size: 9px;">
    <thead >
        <tr>
            <th colspan="5" style="font-weight: bold;">LEDGER</th>
        </tr>
        <tr style="text-align:center;">
            <th style="font-weight: bold;" width="70px">Date</th>
            <th style="font-weight: bold;" width="200px">Description</th>
            <th style="font-weight: bold;" width="120px">Billing</th>
            <th style="font-weight: bold;" width="120px">Payment</th>
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
                <td width="70px">{{$lDate}}</td>
                <td width="200px">{{$led->particulars}}</td>
                <td width="120px" style="text-align:right;">{{$amount}}</td>
                <td width="120px" style="text-align:right;">{{$payment}}</td>
                <td width="20%" style="text-align:right;">{{number_format($bal, 2)}}</td>
            </tr>
        @else
            <tr>
                <td width="70px"><del>{{$lDate}} </del></td>
                <td width="200px"><del>{{$led->particulars}}</del></td>
                <td width="120px" style="text-align:right;"><del>{{$amount}}</del></td>
                <td width="120px" style="text-align:right;"><del>{{$payment}}</del></td>
                <td width="20%" style="text-align:right;"><del>{{number_format($bal, 2)}}</del></td>
            </tr>
        @endif
    @endforeach
    <tr style="background-color:#59bdf0">
        <th width="70px"></th>
        <th width="200px" style="text-align:right">
            TOTAL:
        </th>
        <th width="120px" style="text-align:right;">
            {{number_format($debit, 2)}}
        </th>
        <th width="120px" style="text-align:right;">
            {{number_format($credit, 2)}}
        </th>
        <th width="20%" style="text-align:right;">
            {{number_format($bal, 2)}}
        </th>
    </tr>
    <tr>
        <th colspan="5"  style="font-weight: bold;">ASSESSMENT</th>
    </tr>
    @php

        $assessbilling = 0;
        $assesspayment = 0;
        $assessbalance = 0;
        $overallbilling = 0;
        $overallpayment = 0;
        $overallbalance = 0;
        $totalBal = 0;
    @endphp
    @if(count($getPaySched) > 0)
        
        @foreach($getPaySched as $psched)
            @if($monthsetupid == 0)
                @php
                    $overallbilling += $psched->amountdue;
                    $overallpayment += $psched->amountpay;
                    $overallbalance += $psched->balance;

                    $assessbilling += $psched->amountdue;
                    $assesspayment += $psched->amountpay;
                    $assessbalance += $psched->balance;
                    $totalBal += $psched->balance;
                    $m = date_create($psched->duedate);
                    $f = date_format($m, 'F');
                    $m = date_format($m, 'm');
                    
                    if($psched->duedate != null && $psched->duedate != '')
                    {
                    $particulars = 'TUITION/BOOKS/OTH FEE - ' . $f;  
                    }
                    else
                    {
                    $particulars = 'TUITION/BOOKS/OTH FEE';
                    $m = 0;
                    }
                @endphp
                <tr>
                <td width="70px"></td>
                <td width="200px">{{$particulars}}</td>
                <td width="120px" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                <td width="120px" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                </tr>
            @else
                @php
                    $overallbilling += $psched->amountdue;
                    $overallpayment += $psched->amountpay;
                    $overallbalance += $psched->balance;
                    
                    $m = date_create($psched->duedate);
                    $f = date_format($m, 'F');
                    $m = date_format($m, 'm');
                    
                    if($psched->duedate != null && $psched->duedate != '')
                    {
                    $particulars = 'TUITION/BOOKS/OTH FEE - ' . $f;  
                    }
                    else
                    {
                    $particulars = 'TUITION/BOOKS/OTH FEE';
                    $m = 0;
                    }
                    $arraymonthsetups = collect($monthsetup)->where('id','<=', $monthsetupid)->values();
                @endphp
                @if(count($arraymonthsetups)>0)
                    @if($psched->monthid == 0)
                        @php                        
                            $assessbilling += $psched->amountdue;
                            $assesspayment += $psched->amountpay;
                            $assessbalance += $psched->balance;
                        @endphp
                        <tr>
                        <td width="70px"></td>
                        <td width="200px">{{$particulars}}</td>
                        <td width="120px" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                        <td width="120px" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                        <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                        </tr>
                    @else
                        @if(collect($arraymonthsetups)->where('id', $psched->monthid)->count()>0)
                        @php                        
                            $assessbilling += $psched->amountdue;
                            $assesspayment += $psched->amountpay;
                            $assessbalance += $psched->balance;
                        @endphp
                        <tr>
                        <td width="70px"></td>
                        <td width="200px">{{$particulars}}</td>
                        <td width="120px" style="text-align:right;">{{number_format($psched->amountdue, 2)}}</td>
                        <td width="120px" style="text-align:right;">{{number_format($psched->amountpay, 2)}}</td>
                        <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
                        </tr>
                        @endif
                    @endif
                @endif
            @endif
  
        @endforeach
  
        <tr style="background-color:#59bdf0">
            <th width="70px"></th>
            <th width="200px" style="text-align:right">
                TOTAL:
            </th>
            <th width="120px" style="text-align:right;">
                {{number_format($assessbilling, 2)}}
            </th>
            <th width="120px" style="text-align:right;">
                {{number_format($assesspayment, 2)}}
            </th>
            <th width="20%" style="text-align:right;">
                {{number_format($assessbalance, 2)}}
            </th>
        </tr>
        <tr style="background-color: #f5e069">
            <th width="70px"></th>
            <th width="200px" style="text-align:right">
                TOTAL BALANCE:
            </th>
            <th width="120px"style="text-align:right">
                {{number_format($overallbilling, 2)}}
            </th>
            <th width="120px"style="text-align:right">
                {{number_format($overallpayment, 2)}}
            </th>
            <th width="20%"style="text-align:right">
                {{number_format($overallbalance, 2)}}
            </th>
        </tr>
        <tr style="background-color: #f5e069">
            <th width="70px"></th>
            <th width="200px" style="text-align:right">
                TOTAL AMOUNT DUE:
            </th>
            <th width="120px"style="text-align:right">
                
            
            </th>
            <th width="120px"style="text-align:right">
                
            </th>
            <th width="20%" style="font-size:13px;text-align:right">
                {{number_format($overallbalance, 2)}}
            </th>
        </tr>
  
    @else
  
        <tr style="background-color: yellow">
            <th width="70px"></th>
            <th width="200px" style="text-align:right">
                TOTAL BALANCE:
            </th>
            <th width="120px"style="text-align:right">
                {{number_format($debit, 2)}}
            </th>
            <th width="120px"style="text-align:right">
                {{number_format($credit, 2)}}
            </th>
            <th width="20%"style="text-align:right">
                {{number_format($bal, 2)}}
            </th>
        </tr>
        <tr style="background-color: yellow">
            <th width="70px"></th>
            <th width="200px" style="text-align:right">
                TOTAL AMOUNT DUE:
            </th>
            <th width="120px">
                
            
            </th>
            <th width="120px">
                
            </th>
            <th width="20%" style="font-size:13px;text-align:right">
                {{number_format($bal, 2)}}
            </th>
        </tr>
    @endif
</table>
<br/>

@if($notestatus>0)
{
    <span style="font-size: 9px;font-weight: bold">NOTES:</span><br/>
    @foreach($notes as $note)
        <p style="line-height: 8px; margin-bottom: 0px;font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$note->description}}</p>
    @endforeach
    <br/>&nbsp;
@endif
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
<table  cellspacing="0" cellpadding="1" style="font-size: 9px;" width="50%">
    <thead>
        <tr>
            <th style="font-weight: bold;">Assessed By:</th>
        </tr>
    </thead>
    <tr>
        <td>
            <table style="width: 80%"  cellpadding="5" >
                <tr>
                    <td style="border-bottom: 1px solid black;height: 25px;">
                    
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;text-transform: uppercase; font-weight: bold;">
                        @if($preparedby)
                        {{$preparedby->firstname.' '.$preparedby->lastname.' '.$preparedby->suffix}}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@else
<table  cellspacing="0" cellpadding="1" style="font-size: 9px;" width="100%">
    <thead>
        <tr>
            <th style="font-weight: bold;">Prepared By:</th>
            <th style="font-weight: bold;">Received By:</th>
        </tr>
    </thead>
    <tr>
        <td>
            <table style="width: 80%"  cellpadding="5" >
                <tr>
                    <td style="border-bottom: 1px solid black;height: 25px;">
                    
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;text-transform: uppercase; font-weight: bold;">
                        @if($preparedby)
                        {{$preparedby->firstname.' '.$preparedby->lastname.' '.$preparedby->suffix}}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <table style="width: 80%"  cellpadding="5" >
                <tr>
                    <td style="border-bottom: 1px solid black;height: 25px;">
                    
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
@endif --}}