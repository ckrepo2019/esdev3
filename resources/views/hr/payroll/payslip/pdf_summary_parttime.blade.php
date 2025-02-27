
<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
        text-transform: uppercase;
    }
</style>

<div style="width: 100%;">For the period: {{$payrolldates->datefrom}} - {{$payrolldates->dateto}}
    <br/>
    Salary type : <strong>{{$salarytype}}</strong>
</div>
<br/>
@php
    $totalperhour       = 0; 
    $totaldaysrendered  = 0; 
    $totalhoursrendered = 0; 
    $totalamount        = 0; 

    $totalsummary       = 0;  
    $totalbasicsalary   = 0; 
    $totalabsences      = 0; 
    $totalgross         = 0; 
    $totaldeductions    = 0; 

@endphp
<table border="1" cellpadding="2" style="font-size: 10px;text-transform: uppercase;">
    <thead >
        <tr nobr="true" style="font-size: 9px;">
            <th rowspan="2" style="width: 2%;text-align:center;">
                <div style="vertical-align: middle;">No</div>
            </th>
            <th rowspan="2" colspan="3" style="width: 25%;text-align:center">
                <div style="vertical-align: middle;">NAME</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">PER HOUR</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;"># OF DAYS RENDERED</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">TOTAL # OF HOURS</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">TOTAL AMOUNT</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">GROSS SALARY PAY</div>
            </th>
            <th colspan="{{count($standarddeductions)}}" style="text-align:center"> STANDARD DEDUCTIONS </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">TOTAL<br/>DEDUCTIONS</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">NET PAY</div>
            </th>
            <th rowspan="2" style="text-align:center">
                <div style="vertical-align: middle;">SIGNATURE</div>
            </th>
            <th rowspan="2" style="width: 2%;"></th>
        </tr>
        <tr nobr="true">            
            @if(count($standarddeductions)>0)
                @foreach($standarddeductions as $standarddeduction)
                    <th  style="text-align:center">
                        <div style="vertical-align: middle;">{{$standarddeduction->description}}</div>
                    </th>
                @endforeach
            @endif
        </tr>
    </thead>
    @if(count($payrolldetails)>0)
        @php
            $empno = 1;
        @endphp
        @foreach($payrolldetails as $payrolldetail)
        @php
            $totalperhour+=$payrolldetail->basicpay;
            $totaldaysrendered+=$payrolldetail->dayspresent;
            $totalhoursrendered+=$payrolldetail->hoursrendered;
            $totalamount+=($payrolldetail->dayspresentamount-$payrolldetail->lateamount);
        @endphp
            <tr nobr="true">
                <td style="width: 2%;vertical-align: middle;text-align:center;padding-top: 10px;">
                    {{$empno}}
                </td>
                <td style="width: 12%;">
                    {{$payrolldetail->lastname}}
                </td>
                <td style="width: 1%;">,</td>
                <td style="width: 12%;">
                    {{$payrolldetail->firstname}}
                </td>
                <td  style="text-align:right">
                    {{number_format($payrolldetail->basicpay,2,'.',',')}}
                </td>
                <td style="text-align:center">
                    {{$payrolldetail->dayspresent}}
                </td>
                <td style="text-align:center">
                    {{$payrolldetail->hoursrendered}}
                </td>
                <td style="text-align:right">
                    {{number_format($payrolldetail->dayspresentamount-$payrolldetail->lateamount,2,'.',',')}}
                </td>
                <td style="text-align:right">
                    {{-- {{$payrolldetail->dayspresentamount}} --}}
                    {{number_format($payrolldetail->dayspresentamount-$payrolldetail->lateamount,2,'.',',')}}
                </td>
                @if(count($standarddeductions)>0)
                    @foreach($standarddeductions as $standarddeduction)
                        
                        <td style="text-align:right">
                            @if(count($payrolldetail->historydetails)>0)
                                @foreach($payrolldetail->historydetails as $historydetail)
                                    @if($historydetail->type == 'standard' && $historydetail->deductionid == $standarddeduction->id)
                                        {{$historydetail->amount}}
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    @endforeach
                @endif
                <td style="text-align:right">
                    {{$payrolldetail->totaldeductions}}
                </td>
                <td style="text-align:right">
                    {{number_format($payrolldetail->netpay,2,'.',',')}}
                </td>
                <td>
                    
                </td>
                <td style="width: 2%;">
                    {{$empno}}
                </td>
            </tr>
            @php
                $empno+=1;   
                $totalsummary+=$payrolldetail->netpay;
                $totalbasicsalary+=$payrolldetail->basicsalary;
                $totalabsences+=($payrolldetail->daysabsentamount+$payrolldetail->lateamount);
                $totalgross+=($payrolldetail->dayspresentamount-$payrolldetail->lateamount);
                $totaldeductions+=$payrolldetail->totaldeductions;
            @endphp
        @endforeach
    @else
        <tr nobr="true">
            <td style="width: 2%;vertical-align: middle;text-align:center;padding-top: 10px;">
            </td>
            <td style="width: 12%;">
            </td>
            <td style="width: 1%;"></td>
            <td style="width: 12%;">
            </td>
            <td  style="text-align:right">
            </td>
            <td style="text-align:right">
            </td>
            <td style="text-align:right">
            </td>
            <td style="text-align:right">
                
            </td>
            <td style="text-align:right">
                
            </td>
            <td colspan="{{count($standarddeductions)}}" > </td>
            <td style="text-align:right">
                
            </td>
            <td style="text-align:right">
                
            </td>
            <td>
                
            </td>
            <td style="width: 2%;">
                
            </td>
        </tr>
    @endif
    <tr nobr="true">
        <td colspan="4" style="text-align: right;font-weight: bold; font-size: 11px;">TOTAL</td>
        <td style="text-align: right;font-weight: bold; font-size: 11px;">{{number_format($totalperhour,2,'.',',')}}</td>
        <td style="text-align: center;font-weight: bold; font-size: 11px;">{{$totaldaysrendered}}</td>
        <td style="text-align: center;font-weight: bold; font-size: 11px;">{{$totalhoursrendered}}</td>
        <td style="text-align: right;font-weight: bold; font-size: 11px;">{{number_format($totalamount,2,'.',',')}}</td>
        <td style="text-align: right;font-weight: bold; font-size: 11px;">{{number_format($totalgross,2,'.',',')}}</td>
        @foreach($standarddeductions as $standarddeduction)
            <td style="text-align: right;font-weight: bold; font-size: 11px;">
                {{-- {{number_format($standarddeduction->total,2,'.',',')}} --}}
            </td>
        @endforeach
        <td style="text-align: right;font-weight: bold; font-size: 11px;">{{number_format($totaldeductions,2,'.',',')}}</td>
        <td style="text-align: right;font-weight: bold; font-size: 11px;">{{number_format($totalsummary,2,'.',',')}}</td>
        <td></td>
        <td style="width: 2%;">
            
        </td>
    </tr>
</table>