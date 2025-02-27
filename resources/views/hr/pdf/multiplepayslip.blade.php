
<style>
    * {
        text-transform: uppercase;
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
@page { 
    margin-top: 2%; 
    }
table{
    border-collapse: collapse;
}
</style>

{{-- <h4 class="payslip-title">Payslip for the !month of Feb 2019!</h4> --}}
@foreach($employeesalaryinfo as $employee)
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
                                {{$getdaterange[0]->datefrom}} - {{$getdaterange[0]->dateto}}
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
            <table style="width:100%;  page-break-inside: avoid;text-transform: uppercase; ">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px; text-align: center;">
                            <strong>
                                {{$employee->historyinfo->lastname.', '.$employee->historyinfo->firstname.' '.$employee->historyinfo->middlename[0].' '.$employee->historyinfo->suffix}}.
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
                            {{number_format($employee->historyinfo->attendancesalary,2,'.',',')}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deductions</strong>
                        </td>
                    </tr>
                    @foreach($employee->standarddeductions as $standarddeduction)
                        {{-- @if($standarddeduction->type == 'standarddeduction') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standarddeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($standarddeduction->amount,2,'.',',')}}</td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deductions</strong>
                        </td>
                    </tr>
                    @foreach($employee->otherdeductions as $otherdeduction)
                        {{-- @if($otherdeduction->type == 'otherdeduction') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherdeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($otherdeduction->amount,2,'.',',')}}</td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    {{-- @if($getpayrollhistoryinfo[0]->historyinfo->tardinessamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>Tardiness</td>
                            <td></td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->tardinessamount,2,'.',',')}}</td>
                        </tr>
                    @endif --}}
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($employee->standardallowances as $standardallowance)
                        {{-- @if($standardallowance->type == 'standardallowance') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standardallowance->description}}</td>
                                <td>{{number_format($standardallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    @foreach($employee->getearnedleaves as $getearnedleaves)
                        {{-- @if($otherallowance->type == 'otherallowance') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$getearnedleaves->description}}</td>
                                <td>{{number_format((int)$getearnedleaves->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    @foreach($employee->otherallowances as $otherallowance)
                        {{-- @if($otherallowance->type == 'otherallowance') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherallowance->description}}</td>
                                <td>{{number_format((int)$otherallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    @if($employee->historyinfo->overtimepay > 0)
                        <tr>
                            <td>Overtime</td>
                            <td>{{number_format($employee->historyinfo->overtimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($employee->historyinfo->holidaypay > 0)
                        <tr>
                            <td>Holiday/s </td>
                            <td>{{number_format($employee->historyinfo->holidaypay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($employee->historyinfo->holidayovertimepay > 0)
                        <tr>
                            <td>Holiday/s (Overtime)</td>
                            <td>{{number_format($employee->historyinfo->holidayovertimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($employee->historyinfo->totalearnings,2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($employee->historyinfo->totaldeductions,2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: {{number_format($employee->historyinfo->netpay,2,'.',',')}}
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
                                {{$getdaterange[0]->datefrom}} - {{$getdaterange[0]->dateto}}
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
            <table style="width:100%;  page-break-inside: avoid;text-transform: uppercase; ">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px; text-align: center;">
                            <strong>
                                {{$employee->historyinfo->lastname.', '.$employee->historyinfo->firstname.' '.$employee->historyinfo->middlename[0].' '.$employee->historyinfo->suffix}}.
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
                            {{number_format($employee->historyinfo->attendancesalary,2,'.',',')}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deductions</strong>
                        </td>
                    </tr>
                    @foreach($employee->standarddeductions as $standarddeduction)
                        {{-- @if($standarddeduction->type == 'standarddeduction') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standarddeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($standarddeduction->amount,2,'.',',')}}</td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deductions</strong>
                        </td>
                    </tr>
                    @foreach($employee->otherdeductions as $otherdeduction)
                        {{-- @if($otherdeduction->type == 'otherdeduction') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherdeduction->description}}</td>
                                <td></td>
                                <td>{{number_format($otherdeduction->amount,2,'.',',')}}</td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    {{-- @if($getpayrollhistoryinfo[0]->historyinfo->tardinessamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>Tardiness</td>
                            <td></td>
                            <td>{{number_format($getpayrollhistoryinfo[0]->historyinfo->tardinessamount,2,'.',',')}}</td>
                        </tr>
                    @endif --}}
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($employee->standardallowances as $standardallowance)
                        {{-- @if($standardallowance->type == 'standardallowance') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$standardallowance->description}}</td>
                                <td>{{number_format($standardallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    @foreach($employee->getearnedleaves as $getearnedleaves)
                        {{-- @if($otherallowance->type == 'otherallowance') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$getearnedleaves->description}}</td>
                                <td>{{number_format((int)$getearnedleaves->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    @foreach($employee->otherallowances as $otherallowance)
                        {{-- @if($otherallowance->type == 'otherallowance') --}}
                            <tr style=" text-transform: uppercase;">
                                <td>{{$otherallowance->description}}</td>
                                <td>{{number_format((int)$otherallowance->amount,2,'.',',')}}</td>
                                <td></td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                    @if($employee->historyinfo->overtimepay > 0)
                        <tr>
                            <td>Overtime</td>
                            <td>{{number_format($employee->historyinfo->overtimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($employee->historyinfo->holidaypay > 0)
                        <tr>
                            <td>Holiday/s </td>
                            <td>{{number_format($employee->historyinfo->holidaypay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    @if($employee->historyinfo->holidayovertimepay > 0)
                        <tr>
                            <td>Holiday/s (Overtime)</td>
                            <td>{{number_format($employee->historyinfo->holidayovertimepay,2,'.',',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($employee->historyinfo->totalearnings,2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            {{number_format($employee->historyinfo->totaldeductions,2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: {{number_format($employee->historyinfo->netpay,2,'.',',')}}
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
<br>
<hr style="border: 1px dashed;"/>
<br>
@endforeach