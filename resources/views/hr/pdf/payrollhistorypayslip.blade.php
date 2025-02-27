
<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
    }
    .payslip-title {
        margin-bottom: 20px;
        text-align: center;
        text-decoration: underline;
        text-transform: uppercase;
    }
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}.m-b-20 {
    margin-bottom: 20px !important;
}

.col-sm-6 {
    width: 50%;
}

.list-unstyled {
    padding-left: 0;
    list-style: none;
}
table{
    border-collapse: collapse;
}
dl, ol, ul {
    margin-top: 0;
    margin-bottom: 1rem;
}.invoice-details, .invoice-payment-details > li span {
    float: right;
    text-align: right;
}
tr{
    padding: 0px;
}
@page { 
    margin-top: 2%; 
    }
</style>

<table style="width:100%; page-break-inside: avoid; font-size: 10px; ">
    <tr>
        <td style="width: 50%; padding-right: 3%;">
            <table style="width: 100%; font-size: 11px; text-align: center;">
                <tr>
                    <td>
                        <div style="vertical-align: middle;">
                            <span>
                                {{$schoolinfo->schoolname}}, {{$schoolinfo->division}}
                                <br>
                                {{$schoolinfo->address}}
                                <br>
                                PAY SLIP
                                <br>
                                {{$getpayrollhistoryinfo[0]->historyinfo->payrolldatefrom}} - {{$getpayrollhistoryinfo[0]->historyinfo->payrolldateto}}
                            </span>
                        </div>
                    </td>
                    {{-- <td style="font-size: 12px;">
                        <div style="text-align: right; vertical-align: middle;">Date Released: {{$getpayrollhistoryinfo[0]->historyinfo->datereleased}}</div>
                    </td> --}}
                </tr>
            </table>
            <table style="width:100%;  page-break-inside: avoid;text-transform: uppercase; ">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px; text-align: center;">
                            <strong>
                                {{$getpayrollhistoryinfo[0]->historyinfo->lastname.', '.$getpayrollhistoryinfo[0]->historyinfo->firstname.' '.$getpayrollhistoryinfo[0]->historyinfo->middlename[0].' '.$getpayrollhistoryinfo[0]->historyinfo->suffix}}.
                            </strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Particulars
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Earnings
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Deductions
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Taxable earnings - Basic</strong>
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td>
                           Basic Salary
                        </td>
                        <td>
                            {{number_format($getpayrollhistoryinfo[0]->historyinfo->attendancesalary,2,'.',',')}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deductions</strong>
                        </td>
                    </tr>
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $standarddeduction)
                        @if($standarddeduction->type == 'standarddeduction')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standarddeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($standarddeduction->amount,2,'.',',')}}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deductions</strong>
                        </td>
                    </tr>
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $otherdeduction)
                        @if($otherdeduction->type == 'otherdeduction')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherdeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($otherdeduction->amount,2,'.',',')}}</td>
                            </tr>
                        @endif
                        {{-- @if($otherdeduction->type == 'deductedleave')
                            <tr>
                                <td>Leave/s</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$otherdeduction->amount}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$otherdeduction->amount}}</td>
                            </tr>
                        @endif --}}
                    @endforeach
                    @if($getpayrollhistoryinfo[0]->historyinfo->tardinessamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>Tardiness</td>
                            <td></td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->tardinessamount,2,'.',',')}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $standardallowance)
                        @if($standardallowance->type == 'standardallowance')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standardallowance->description}}</td>
                                <td>{{number_format($standardallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $otherallowance)
                        @if($otherallowance->type == 'otherallowance')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherallowance->description}}</td>
                                <td>{{number_format((int)$otherallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        @endif
                        @if($otherallowance->type == 'earnedleave')
                            <tr style=" text-transform: uppercase;">
                                <td>Leave/s</td>
                                <td>{{number_format($otherallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @if($getpayrollhistoryinfo[0]->historyinfo->overtimepay > 0)
                        <tr>
                            <td>Overtime</td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->overtimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($getpayrollhistoryinfo[0]->historyinfo->holidaypay > 0)
                        <tr>
                            <td>Holiday/s </td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->holidaypay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($getpayrollhistoryinfo[0]->historyinfo->holidayovertimepay > 0)
                        <tr>
                            <td>Holiday/s (Overtime)</td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->holidayovertimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($getpayrollhistoryinfo[0]->historyinfo->totalearnings,2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($getpayrollhistoryinfo[0]->historyinfo->totaldeductions,2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: {{number_format($getpayrollhistoryinfo[0]->historyinfo->netpay,2,'.',',')}}
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table style="width: 100%; text-align: center;font-size: 9px;">
                <tr>
                    <td style="width: 35%; border-bottom: 1px solid black; text-transform: uppercase; ">
                        {{$preparedby->firstname}} {{$preparedby->middlename[0]}}. {{$preparedby->lastname}} {{$preparedby->suffix}}
                    </td>
                    <td></td>
                    <td style="width: 35%; border-bottom: 1px solid black;"></td>
                    <td></td>
                    <td style="width: 20%; border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td>
                        Prepared By:
                    </td>
                    <td></td>
                    <td>
                        Recieved By:
                    </td>
                    <td></td>
                    <td>
                        Date
                    </td>
                </tr>
            </table>
        </td>
        <td style=" padding-left: 3%;">

            <table style="width: 100%; font-size: 11px; text-align: center;">
                <tr>
                    <td>
                        <div style="vertical-align: middle;">
                            <span>
                                {{$schoolinfo->schoolname}}, {{$schoolinfo->division}}
                                <br>
                                {{$schoolinfo->address}}
                                <br>
                                PAY SLIP
                                <br>
                                {{$getpayrollhistoryinfo[0]->historyinfo->payrolldatefrom}} - {{$getpayrollhistoryinfo[0]->historyinfo->payrolldateto}}
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
            <table style="width:100%;  page-break-inside: avoid ">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px; text-align: center;">
                            <strong>
                                {{$getpayrollhistoryinfo[0]->historyinfo->lastname.', '.$getpayrollhistoryinfo[0]->historyinfo->firstname.' '.$getpayrollhistoryinfo[0]->historyinfo->middlename[0].' '.$getpayrollhistoryinfo[0]->historyinfo->suffix}}.
                            </strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Particulars
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Earnings
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Deductions
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Taxable earnings - Basic</strong>
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td>
                           Basic Salary
                        </td>
                        <td>
                            {{number_format($getpayrollhistoryinfo[0]->historyinfo->attendancesalary,2,'.',',')}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deductions</strong>
                        </td>
                    </tr>
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $standarddeduction)
                        @if($standarddeduction->type == 'standarddeduction')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standarddeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($standarddeduction->amount,2,'.',',')}}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deductions</strong>
                        </td>
                    </tr>
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $otherdeduction)
                        @if($otherdeduction->type == 'otherdeduction')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherdeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($otherdeduction->amount,2,'.',',')}}</td>
                            </tr>
                        @endif
                        {{-- @if($otherdeduction->type == 'deductedleave')
                            <tr>
                                <td>Leave/s</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$otherdeduction->amount}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$otherdeduction->amount}}</td>
                            </tr>
                        @endif --}}
                    @endforeach
                    @if($getpayrollhistoryinfo[0]->historyinfo->tardinessamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>Tardiness</td>
                            <td></td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->tardinessamount,2,'.',',')}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $standardallowance)
                        @if($standardallowance->type == 'standardallowance')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standardallowance->description}}</td>
                                <td>{{number_format($standardallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($getpayrollhistoryinfo[0]->historydetail as $otherallowance)
                        @if($otherallowance->type == 'otherallowance')
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherallowance->description}}</td>
                                <td>{{number_format((int)$otherallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        @endif
                        @if($otherallowance->type == 'earnedleave')
                            <tr style=" text-transform: uppercase;">
                                <td>Leave/s</td>
                                <td>{{number_format($otherallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @if($getpayrollhistoryinfo[0]->historyinfo->overtimepay > 0)
                        <tr>
                            <td>Overtime</td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->overtimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($getpayrollhistoryinfo[0]->historyinfo->holidaypay > 0)
                        <tr>
                            <td>Holiday/s </td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->holidaypay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($getpayrollhistoryinfo[0]->historyinfo->holidayovertimepay > 0)
                        <tr>
                            <td>Holiday/s (Overtime)</td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->holidayovertimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($getpayrollhistoryinfo[0]->historyinfo->totalearnings,2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($getpayrollhistoryinfo[0]->historyinfo->totaldeductions,2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: {{number_format($getpayrollhistoryinfo[0]->historyinfo->netpay,2,'.',',')}}
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table style="width: 100%; text-align: center;font-size: 9px;">
                <tr>
                    <td style="width: 35%; border-bottom: 1px solid black; text-transform: uppercase; ">
                        {{$preparedby->firstname}} {{$preparedby->middlename[0]}}. {{$preparedby->lastname}} {{$preparedby->suffix}}
                    </td>
                    <td></td>
                    <td style="width: 35%; border-bottom: 1px solid black;"></td>
                    <td></td>
                    <td style="width: 20%; border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td>
                        Prepared By:
                    </td>
                    <td></td>
                    <td>
                        Recieved By:
                    </td>
                    <td></td>
                    <td>
                        Date
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>