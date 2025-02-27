
<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<table style="width:100%; page-break-inside: avoid; font-size: 10px; ">
    <tr>
        <td style="width: 50%; padding-right: 3%;">
            <table style="width: 100%; font-size: 11px; text-align: center;">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 9px;"><strong>{{$schoolinfo->schoolname}}<br/>{{$schoolinfo->division}}</strong></span>
                        <br>
                        <span style="font-size: 9px;">{{$schoolinfo->address}}</span>
                        <br>
                        <span style="font-size: 9px;">PAY SLIP</span>
                        <br>
                        <span style="font-size: 9px;">{{$payrolldetails->payrolldatefrom}} - {{$payrolldetails->payrolldateto}}</span>
                    </td>
                </tr>
            </table>
            <div style="width: 100%;"></div>
            <table style="width:100%;  page-break-inside: avoid;">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px;"><strong style="text-transform: uppercase;">{{$employeeinfo->lastname.', '.$employeeinfo->firstname}}</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Employee ID :{{$employeeinfo->tid}}</td>
                        <td colspan="2"></td>
                    </tr>
                    
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Particulars
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Earnings
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Deduction(s)
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Taxable earnings - Basic</strong>
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td>
                            &nbsp;&nbsp;&nbsp;Basic Salary
                        </td>
                        <td style="text-align: right;">
                            {{-- {{number_format($payrolldetails->basicpay,2,'.',',')}} --}}
                            {{number_format($payrolldetails->dayspresentamount,2,'.',',')}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($payrolldetails->otherdetails as $standarddeduction)
                        @if($standarddeduction->type == 'standard' && $standarddeduction->deductionid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$standarddeduction->deductiondesc}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($standarddeduction->amount,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($payrolldetails->otherdetails as $otherdeduction)
                        @if($otherdeduction->type == 'other' && $otherdeduction->deductionid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$otherdeduction->deductiondesc}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($otherdeduction->amount,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if($payrolldetails->lateamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>
                                &nbsp;&nbsp;&nbsp;Tardiness</td>
                            <td></td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->lateamount,2,'.',',')}}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($payrolldetails->otherdetails as $standardallowance)
                        @if($standardallowance->type == 'standard' && $standardallowance->allowanceid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$standardallowance->allowancedesc}}
                                </td>
                                <td style="text-align: right;">{{number_format($standardallowance->amount,2,'.',',')}}</td>
                                <td>
                                    
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($payrolldetails->otherdetails as $otherallowance)
                        @if($otherallowance->type == 'other' && $otherallowance->allowanceid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$otherallowance->allowancedesc}}
                                </td>
                                <td style="text-align: right;">{{number_format($otherallowance->amount,2,'.',',')}}</td>
                                <td>
                                    
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr style=" text-transform: uppercase;">
                        <td>
                            &nbsp;&nbsp;&nbsp;Leave(s)
                        </td>
                        <td style="text-align: right;">
                            {{number_format(collect($payrolldetails->otherdetails)->where('employeeleaveid','!=','0')->sum('amount'),2)}}
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                    @if($payrolldetails->overtimepay > 0)
                        <tr>
                            <td>
                                &nbsp;&nbsp;&nbsp;Overtime
                            </td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->overtimepay,2,'.',',')}}
                            </td>
                            <td></td>
                        </tr>
                    @endif
                     @if($payrolldetails->holidaypay > 0)
                        <tr>
                            <td>Holiday(s) </td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->holidaypay,2,'.',',')}}
                            </td>
                            <td></td>
                        </tr>
                     @endif 
                    @if($payrolldetails->holidayovertimepay > 0)
                        <tr>
                            <td>Holiday(s) (Overtime)</td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->holidayovertimepay,2,'.',',')}}
                            </td>
                            <td></td>
                        </tr>
                    @endif
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format($payrolldetails->totalearnings,2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format($payrolldetails->totaldeductions+$payrolldetails->lateamount,2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: 
                            {{number_format($payrolldetails->netpay,2,'.',',')}}
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%; text-align: center;font-size: 9px;">
                <tr>
                    <td>&nbsp;
                    </td>
                    <td></td>
                    <td>
                        &nbsp;
                    </td>
                    <td></td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
            </table>
            <table style="width: 100%; text-align: center;font-size: 9px;">
                <tr>
                    <td style="width: 27%; border-bottom: 1px solid black; text-transform: uppercase; ">
                        {{-- {{$preparedby->firstname}} {{$preparedby->middlename[0]}}. {{$preparedby->lastname}} {{$preparedby->suffix}} --}}
                    </td>
                    <td style="width: 10%; "></td>
                    <td style="width: 28%; border-bottom: 1px solid black;"></td>
                    <td style="width: 10%; "></td>
                    <td style="width: 25%; border-bottom: 1px solid black;"></td>
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
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 9px;"><strong>{{$schoolinfo->schoolname}}<br/>{{$schoolinfo->division}}</strong></span>
                        <br>
                        <span style="font-size: 9px;">{{$schoolinfo->address}}</span>
                        <br>
                        <span style="font-size: 9px;">PAY SLIP</span>
                        <br>
                        <span style="font-size: 9px;">{{$payrolldetails->payrolldatefrom}} - {{$payrolldetails->payrolldateto}}</span>
                    </td>
                    {{-- <td style="font-size: 12px;">
                        <div style="text-align: right; vertical-align: middle;">Date Released: {{$getpayrollhistoryinfo[0]->historyinfo->datereleased}}</div>
                    </td> --}}
                </tr>
            </table>
            <div style="width: 100%;"></div>
            <table style="width:100%;  page-break-inside: avoid ">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px;"><br/><strong style="text-transform: uppercase;">{{$employeeinfo->lastname.', '.$employeeinfo->firstname}}</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Employee ID :{{$employeeinfo->tid}}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Particulars
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Earnings
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Deduction(s)
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Taxable earnings - Basic</strong>
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td>
                            &nbsp;&nbsp;&nbsp;Basic Salary
                        </td>
                        <td style="text-align: right;">
                            {{-- {{number_format($payrolldetails->basicpay,2,'.',',')}} --}}
                            {{number_format($payrolldetails->dayspresentamount,2,'.',',')}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($payrolldetails->otherdetails as $standarddeduction)
                        @if($standarddeduction->type == 'standard' && $standarddeduction->deductionid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$standarddeduction->deductiondesc}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($standarddeduction->amount,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($payrolldetails->otherdetails as $otherdeduction)
                        @if($otherdeduction->type == 'other' && $otherdeduction->deductionid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$otherdeduction->deductiondesc}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($otherdeduction->amount,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if($payrolldetails->lateamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>
                                &nbsp;&nbsp;&nbsp;Tardiness</td>
                            <td></td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->lateamount,2,'.',',')}}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($payrolldetails->otherdetails as $standardallowance)
                        @if($standardallowance->type == 'standard' && $standardallowance->allowanceid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$standardallowance->allowancedesc}}
                                </td>
                                <td style="text-align: right;">{{number_format($standardallowance->amount,2,'.',',')}}</td>
                                <td>
                                    
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($payrolldetails->otherdetails as $otherallowance)
                        @if($otherallowance->type == 'other' && $otherallowance->allowanceid > 0)
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$otherallowance->allowancedesc}}
                                </td>
                                <td style="text-align: right;">{{number_format($otherallowance->amount,2,'.',',')}}</td>
                                <td>
                                    
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr style=" text-transform: uppercase;">
                        <td>
                            &nbsp;&nbsp;&nbsp;Leave(s)
                        </td>
                        <td style="text-align: right;">
                            {{number_format(collect($payrolldetails->otherdetails)->where('employeeleaveid','!=','0')->sum('amount'),2)}}
                            {{-- @foreach($payrolldetails->otherdetails as $leave)
                                @if($leave->employeeleaveid > 0)
                                    {{number_format($leave->amount,2,'.',',')}}
                                @endif
                            @endforeach --}}
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                    @if($payrolldetails->overtimepay > 0)
                        <tr>
                            <td>
                                &nbsp;&nbsp;&nbsp;Overtime
                            </td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->overtimepay,2,'.',',')}}
                            </td>
                            <td></td>
                        </tr>
                    @endif
                     @if($payrolldetails->holidaypay > 0)
                        <tr>
                            <td>Holiday(s) </td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->holidaypay,2,'.',',')}}
                            </td>
                            <td></td>
                        </tr>
                     @endif 
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format($payrolldetails->totalearnings,2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format($payrolldetails->totaldeductions+$payrolldetails->lateamount,2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: 
                            {{number_format($payrolldetails->netpay,2,'.',',')}}
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%; text-align: center;font-size: 9px;">
                <tr>
                    <td>&nbsp;
                    </td>
                    <td></td>
                    <td>
                        &nbsp;
                    </td>
                    <td></td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
            </table>
            <table style="width: 100%; text-align: center;font-size: 9px;">
                <tr>
                    <td style="width: 27%; border-bottom: 1px solid black; text-transform: uppercase; ">
                        {{-- {{$preparedby->firstname}} {{$preparedby->middlename[0]}}. {{$preparedby->lastname}} {{$preparedby->suffix}} --}}
                    </td>
                    <td style="width: 10%; "></td>
                    <td style="width: 28%; border-bottom: 1px solid black;"></td>
                    <td style="width: 10%; "></td>
                    <td style="width: 25%; border-bottom: 1px solid black;"></td>
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