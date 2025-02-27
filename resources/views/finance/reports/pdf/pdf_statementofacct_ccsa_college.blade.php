<table style="font-size: 9px; font-weight: bold; padding-top: 5px;">
    <tr>
        <td>S.Y {{$selectedschoolyear}}
        
            @if($selectedmonth != null)
            <br/>AS OF : {{strtoupper($selectedmonth)}};
            @endif
        </td>
        <td style="text-align: right;">
            @if($selectedsemester != null)
                SEMESTER : {{strtoupper($selectedsemester)}}
            @endif
        </td>
    </tr>
    <tr>
        <td width="50%"  style="text-align: left;">STUDENT: {{$studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix}}</td>
        {{-- <td>{{$studinfo->levelid}}</td> --}}
        @if ($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
            <td width="50%" style="text-align: right;">YEAR LEVEL: {{$studinfo->levelname}} - {{$studinfo->courseabrv}}</td>
        @else
            <td width="50%" style="text-align: right;">GRADE LEVEL: {{$studinfo->levelname}} - {{$studinfo->sectionname}}</td>
        @endif
        
        {{-- <td>YEAR LEVEL: {{$studinfo->levelname}} - {{$studinfo->sectionname}}<br/>COURSE: {{$studinfo->courseabrv}}
        </td> --}}
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
        $totalBal = collect($getPaySched)->sum('balance');;
    @endphp
    @if(count($getPaySched) > 0)
        
        @foreach($getPaySched as $psched)
            @php
                // $totalBal += $psched->balance;
                $assessbilling += $psched->amount;
                $assesspayment += $psched->payment;
                $assessbalance += $psched->balance;
                
                $m = date_create($psched->duedate);
                $f = date_format($m, 'F');
                $m = date_format($m, 'm');
                
                if($psched->duedate != '')
                {
                    $particulars = 'PAYABLES FOR ' . strtoupper($f);
                }
                else
                {
                    $particulars = 'ONE-TIME PAYMENT';
                    $m = 0;
                }
            @endphp
  
            <tr>
                <td width="70px"></td>
                <td width="200px">{{$particulars}}</td>
                <td width="120px" style="text-align:right;">{{number_format($psched->amount, 2)}}</td>
                <td width="120px" style="text-align:right;">{{number_format($psched->payment, 2)}}</td>
                <td width="20%" style="text-align:right;">{{number_format($psched->balance, 2)}}</td>
            </tr>
            
                
                    
            
            
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
                {{number_format($assessbilling, 2)}}
            </th>
            <th width="120px"style="text-align:right">
                {{number_format($assesspayment, 2)}}
            </th>
            <th width="20%"style="text-align:right">
                {{number_format($assessbalance, 2)}}
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
                {{number_format($assessbalance, 2)}}
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
@endif