
<style>
    * {
        text-transform: uppercase;
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
@page { margin-top: 2%; }
/* body { margin: 0px; } */
</style>
<table style="width: 100%;">
    <tr>
        <td >
            <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" width="60px" style="display: inline;">
        </td>
        <td>
            <div style="vertical-align: middle;">
                <span style="font-size: 12px;">
                    <strong>
                        {{$schoolinfo[0]->schoolname}}
                    </strong>
                </span>
                <br>
                <span style="font-size: 11px;">
                    <strong>
                        {{$schoolinfo[0]->district}} | {{$schoolinfo[0]->division}} | {{$schoolinfo[0]->region}}
                    </strong>
                </span>
                <br>
                <span style="font-size: 11px;">
                    <strong>
                        {{$schoolinfo[0]->address}}
                    </strong>
                </span>
            </div>
        </td>
        <td style="font-size: 10px;">
            <div style="text-align: right; vertical-align: middle;">Date Released: {{$employeesalaryinfo[0]->payrollinfo->datereleased}}</div>
        </td>
    </tr>
</table>
<div style="border: 1px solid; ">
    <table style="width:100%; page-break-inside: avoid; ">
        <thead>
            <tr>
                <th colspan="2" style="text-align:none">{{$employeesalaryinfo[0]->employeeinfo->lastname.', '.$employeesalaryinfo[0]->employeeinfo->firstname.' '.$employeesalaryinfo[0]->employeeinfo->middlename[0].' '.$employeesalaryinfo[0]->employeeinfo->suffix}}.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 10px;width:50%;">
                    <table style="width:100%; table-layout: fixed">
                        <tr>
                            <td>Salary Period</td>
                            <td>{{$getdaterange[0]->datefrom}} - {{$getdaterange[0]->dateto}}</td>
                        </tr>
                        <tr>
                            <td>Basic Pay</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->basicpay,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td>Rate Type</td>
                            <td>{{$employeesalaryinfo[0]->payrollinfo->ratetype}}</td>
                        </tr>
                        {{-- @if($employeesalaryinfo[0]->leavesearn > 0.00)
                            <tr>
                                <td>Leaves</td>
                                <td> <strong>+</strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$employeesalaryinfo[0]->payrollinfo->leavesearn,2,'.',',')}}</td>
                            </tr>
                        @endif
                        @if($employeesalaryinfo[0]->leavesdeduct > 0.00)
                            <tr>
                                <td>Leaves</td>
                                <td> <strong>-</strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$employeesalaryinfo[0]->payrollinfo->leavesdeduct,2,'.',',')}}</td>
                            </tr>
                        @endif --}}
                    </table>
                </td>
                <td style="font-size: 10px;width:50%;">
                    <table style="width: 100%; table-layout: fixed;">
                        <tr>
                            <td style="width: 70%;">Attendance (Present)</td>
                            <td style="width: 30%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->attendancesalary,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td>Attendance (Absent {{$employeesalaryinfo[0]->payrollinfo->numofdaysabsent}} day/s)</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->absenttotalamount,2,'.',',')}}</td>
                        </tr>
                        @if($employeesalaryinfo[0]->payrollinfo->overtimepay > 0)
                            <tr>
                                <td>Overtime</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->overtimepay,2,'.',',')}}</td>
                            </tr>
                        @endif
                        @if($employeesalaryinfo[0]->payrollinfo->holidaypay > 0)
                            <tr>
                                <td>Holiday/s </td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->holidaypay,2,'.',',')}}</td>
                            </tr>
                        @endif
                        @if($employeesalaryinfo[0]->payrollinfo->holidayovertimepay > 0)
                            <tr>
                                <td>Holiday/s (Overtime)</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->holidayovertimepay,2,'.',',')}}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;">
                        <table style="width:100%; table-layout: fixed; border: 1px solid;">
                            <tr>
                                <th colspan="2">Allowances</th>
                            </tr>
                            @foreach($employeesalaryinfo[0]->standardallowances as $standardallowance)
                            <tr>
                                <td style="width: 80%;">{{$standardallowance->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($standardallowance->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @foreach($employeesalaryinfo[0]->otherallowances as $otherallowance)
                            <tr>
                                <td>{{$otherallowance->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($otherallowance->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @if(count($employeesalaryinfo[0]->leavesearned)>0)
                                @foreach($employeesalaryinfo[0]->leavesearned as $leavesearned)
                                    <tr>
                                        <td>{{$leavesearned->description}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($leavesearned->amount,2,'.',',')}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        {{-- <table style="width:100%; table-layout: fixed;border: 1px solid;">
                            <tr>
                                <th colspan="2">Other Allowances</th>
                            </tr>
                        </table> --}}
                </td>
                <td style="font-size: 10px;">
                        <table style="width:100%; table-layout: fixed; border: 1px solid;">
                            <tr>
                                <th colspan="2">Deductions</th>
                            </tr>
                            @foreach($employeesalaryinfo[0]->standarddeductions as $standarddeduction)
                            <tr>
                                <td style="width: 80%;">{{$standarddeduction->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($standarddeduction->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @foreach($employeesalaryinfo[0]->otherdeductions as $otherdeduction)
                            <tr>
                                <td>{{$otherdeduction->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($otherdeduction->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @if($employeesalaryinfo[0]->payrollinfo->tardinessamount > 0)
                                <tr>
                                    <td>Tardiness</td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->tardinessamount,2,'.',',')}}</td>
                                </tr>
                            @endif
                            @if(count($employeesalaryinfo[0]->leavesdeducted)>0)
                                @foreach($employeesalaryinfo[0]->leavesdeducted as $leavesdeducted)
                                    <tr>
                                        <td>{{$leavesdeducted->description}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($leavesdeducted->amount,2,'.',',')}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        {{-- <table style="width:100%; table-layout: fixed;border: 1px solid;">
                            <tr>
                                <th colspan="2">Other Deductions</th>
                            </tr>
                            @if($employeesalaryinfo[0]->latedeductions > 0.00)
                                <tr>
                                    <td>Tardiness</td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$employeesalaryinfo[0]->latedeductions}}</td>
                                </tr>
                            @endif
                        </table> --}}
                </td>
            </tr>
            <tr style="font-size: 10px">
                <td>Total Earnings: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->totalearnings,2,'.',',')}}</td>
                <td>Total Deductions: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->totaldeductions,2,'.',',')}}</td>
            </tr>
        </tbody>
    
    </table>
    <table style="width: 100%;">
        <tr style="font-size: 10px">
            <td><p><strong>Net Salary: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span class="salary">{{number_format($employeesalaryinfo[0]->payrollinfo->netpay,2,'.',',')}}</span></strong> ({{$employeesalaryinfo[0]->payrollinfo->netpaystring}})</p></td>
        </tr>
    </table>
</div>
<br>
<table style="width: 100%; text-transform: uppercase; text-align: center;font-size: 10px;">
    <tr style="border: none !important;">
        <td style="border: none !important; width: 15%">PREPARED BY :</td>
        <td style="border: none !important; width: 25% !important;border-bottom: 1px solid black;">
            {{$preparedby->firstname}} {{$preparedby->middlename[0]}}. {{$preparedby->lastname}} {{$preparedby->suffix}}
        </td>
        <td style="border: none !important; width: 5%">&nbsp;</td>
        <td style="border: none !important; width: 15%">APPROVED BY :</td>
        <td style="border: none !important; width: 25%;border-bottom: 1px solid black;">
            @if(count($finance) > 0)
                {{$finance[0]->firstname}} {{$finance[0]->middlename[0]}}. {{$finance[0]->lastname}} {{$finance[0]->suffix}}
            @endif
        </td>
        <td style="border: none !important; width: 5%">&nbsp;</td>
        <td style="border: none !important;border-bottom: 1px solid black; width: 20%">
            {{-- {{$currentdate}} --}}
        </td>
    </tr>
    <tr style="text-align: center;">
        <td></td>
        <td>
            <sup>HR</sup>
        </td>
        <td colspan="2"></td>
        <td>
            <sup>FINANCE</sup>
        </td>
        <td></td>
        <td>
            <sup>DATE</sup>
        </td>
    </tr>
</table>
<br>
<br>
<hr style="border: 1px dashed black;"/>
<br>
<br>
<table style="width: 100%;">
    <tr>
        <td >
            <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" width="60px" style="display: inline;">
        </td>
        <td>
            <div style="vertical-align: middle;">
                <span style="font-size: 12px;">
                    <strong>
                        {{$schoolinfo[0]->schoolname}}
                    </strong>
                </span>
                <br>
                <span style="font-size: 11px;">
                    <strong>
                        {{$schoolinfo[0]->district}} | {{$schoolinfo[0]->division}} | {{$schoolinfo[0]->region}}
                    </strong>
                </span>
                <br>
                <span style="font-size: 11px;">
                    <strong>
                        {{$schoolinfo[0]->address}}
                    </strong>
                </span>
            </div>
        </td>
        <td style="font-size: 10px;">
            <div style="text-align: right; vertical-align: middle;">Date Released: {{$employeesalaryinfo[0]->payrollinfo->datereleased}}</div>
        </td>
    </tr>
</table>
<div style="border: 1px solid; ">
    <table style="width:100%; page-break-inside: avoid; ">
        <thead>
            <tr>
                <th colspan="2" style="text-align:none">{{$employeesalaryinfo[0]->employeeinfo->lastname.', '.$employeesalaryinfo[0]->employeeinfo->firstname.' '.$employeesalaryinfo[0]->employeeinfo->middlename[0].' '.$employeesalaryinfo[0]->employeeinfo->suffix}}.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 10px;width:50%;">
                    <table style="width:100%; table-layout: fixed">
                        <tr>
                            <td>Salary Period</td>
                            <td>{{$getdaterange[0]->datefrom}} - {{$getdaterange[0]->dateto}}</td>
                        </tr>
                        <tr>
                            <td>Basic Pay</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->basicpay,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td>Rate Type</td>
                            <td>{{$employeesalaryinfo[0]->payrollinfo->ratetype}}</td>
                        </tr>
                        {{-- @if($employeesalaryinfo[0]->leavesearn > 0.00)
                            <tr>
                                <td>Leaves</td>
                                <td> <strong>+</strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$employeesalaryinfo[0]->payrollinfo->leavesearn,2,'.',',')}}</td>
                            </tr>
                        @endif
                        @if($employeesalaryinfo[0]->leavesdeduct > 0.00)
                            <tr>
                                <td>Leaves</td>
                                <td> <strong>-</strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$employeesalaryinfo[0]->payrollinfo->leavesdeduct,2,'.',',')}}</td>
                            </tr>
                        @endif --}}
                    </table>
                </td>
                <td style="font-size: 10px;width:50%;">
                    <table style="width: 100%; table-layout: fixed;">
                        <tr>
                            <td style="width: 70%;">Attendance (Present)</td>
                            <td style="width: 30%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->attendancesalary,2,'.',',')}}</td>
                        </tr>
                        <tr>
                            <td>Attendance (Absent {{$employeesalaryinfo[0]->payrollinfo->numofdaysabsent}} day/s)</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->absenttotalamount,2,'.',',')}}</td>
                        </tr>
                        @if($employeesalaryinfo[0]->payrollinfo->overtimepay > 0)
                            <tr>
                                <td>Overtime</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->overtimepay,2,'.',',')}}</td>
                            </tr>
                        @endif
                        @if($employeesalaryinfo[0]->payrollinfo->holidaypay > 0)
                            <tr>
                                <td>Holiday/s </td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->holidaypay,2,'.',',')}}</td>
                            </tr>
                        @endif
                        @if($employeesalaryinfo[0]->payrollinfo->holidayovertimepay > 0)
                            <tr>
                                <td>Holiday/s (Overtime)</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->holidayovertimepay,2,'.',',')}}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;">
                        <table style="width:100%; table-layout: fixed; border: 1px solid;">
                            <tr>
                                <th colspan="2">Allowances</th>
                            </tr>
                            @foreach($employeesalaryinfo[0]->standardallowances as $standardallowance)
                            <tr>
                                <td style="width: 80%;">{{$standardallowance->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($standardallowance->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @foreach($employeesalaryinfo[0]->otherallowances as $otherallowance)
                            <tr>
                                <td>{{$otherallowance->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($otherallowance->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @if(count($employeesalaryinfo[0]->leavesearned)>0)
                                @foreach($employeesalaryinfo[0]->leavesearned as $leavesearned)
                                    <tr>
                                        <td>{{$leavesearned->description}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($leavesearned->amount,2,'.',',')}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        {{-- <table style="width:100%; table-layout: fixed;border: 1px solid;">
                            <tr>
                                <th colspan="2">Other Allowances</th>
                            </tr>
                        </table> --}}
                </td>
                <td style="font-size: 10px;">
                        <table style="width:100%; table-layout: fixed; border: 1px solid;">
                            <tr>
                                <th colspan="2">Deductions</th>
                            </tr>
                            @foreach($employeesalaryinfo[0]->standarddeductions as $standarddeduction)
                            <tr>
                                <td style="width: 80%;">{{$standarddeduction->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($standarddeduction->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @foreach($employeesalaryinfo[0]->otherdeductions as $otherdeduction)
                            <tr>
                                <td>{{$otherdeduction->description}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($otherdeduction->amount,2,'.',',')}}</td>
                            </tr>
                            @endforeach
                            @if($employeesalaryinfo[0]->payrollinfo->tardinessamount > 0)
                                <tr>
                                    <td>Tardiness</td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->tardinessamount,2,'.',',')}}</td>
                                </tr>
                            @endif
                            @if(count($employeesalaryinfo[0]->leavesdeducted)>0)
                                @foreach($employeesalaryinfo[0]->leavesdeducted as $leavesdeducted)
                                    <tr>
                                        <td>{{$leavesdeducted->description}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($leavesdeducted->amount,2,'.',',')}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        {{-- <table style="width:100%; table-layout: fixed;border: 1px solid;">
                            <tr>
                                <th colspan="2">Other Deductions</th>
                            </tr>
                            @if($employeesalaryinfo[0]->latedeductions > 0.00)
                                <tr>
                                    <td>Tardiness</td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$employeesalaryinfo[0]->latedeductions}}</td>
                                </tr>
                            @endif
                        </table> --}}
                </td>
            </tr>
            <tr style="font-size: 10px">
                <td>Total Earnings: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->totalearnings,2,'.',',')}}</td>
                <td>Total Deductions: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employeesalaryinfo[0]->payrollinfo->totaldeductions,2,'.',',')}}</td>
            </tr>
        </tbody>
    
    </table>
    <table style="width: 100%;">
        <tr style="font-size: 10px">
            <td><p><strong>Net Salary: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span class="salary">{{number_format($employeesalaryinfo[0]->payrollinfo->netpay,2,'.',',')}}</span></strong> ({{$employeesalaryinfo[0]->payrollinfo->netpaystring}})</p></td>
        </tr>
    </table>
</div>
<br>
<table style="width: 100%; text-transform: uppercase; text-align: center;font-size: 10px;">
    <tr style="border: none !important;">
        <td style="border: none !important; width: 15%">PREPARED BY :</td>
        <td style="border: none !important; width: 25% !important;border-bottom: 1px solid black;">
            {{$preparedby->firstname}} {{$preparedby->middlename[0]}}. {{$preparedby->lastname}} {{$preparedby->suffix}}
        </td>
        <td style="border: none !important; width: 5%">&nbsp;</td>
        <td style="border: none !important; width: 15%">APPROVED BY :</td>
        <td style="border: none !important; width: 25%;border-bottom: 1px solid black;">
            @if(count($finance) > 0)
                {{$finance[0]->firstname}} {{$finance[0]->middlename[0]}}. {{$finance[0]->lastname}} {{$finance[0]->suffix}}
            @endif
        </td>
        <td style="border: none !important; width: 5%">&nbsp;</td>
        <td style="border: none !important;border-bottom: 1px solid black; width: 20%">
            {{-- {{$currentdate}} --}}
        </td>
    </tr>
    <tr style="text-align: center;">
        <td></td>
        <td>
            <sup>HR</sup>
        </td>
        <td colspan="2"></td>
        <td>
            <sup>FINANCE</sup>
        </td>
        <td></td>
        <td>
            <sup>DATE</sup>
        </td>
    </tr>
</table>