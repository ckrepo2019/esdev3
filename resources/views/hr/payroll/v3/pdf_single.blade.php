
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
                    <td>
                        <div style="vertical-align: middle;">
                            <span>
                                <span style="font-size: 9px;">
                                    <strong>{{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->division}}</strong>
                                </span>
                                <br>
                                <span style="font-size: 9px;">
                                    {{DB::table('schoolinfo')->first()->address}}
                                </span>
                                <br>
                                <span style="font-size: 9px;">PAY SLIP</span>
                                <br>
                                <span style="font-size: 9px;">
                                    {{date('M d, Y', strtotime($payrollinfo->datefrom))}} - {{date('M d, Y', strtotime($payrollinfo->dateto))}}
                                </span>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="width: 100%;"></div>
            <table style="width:100%;  page-break-inside: avoid;">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px;"><strong style="text-transform: uppercase;">{{$header->lastname.', '.$header->firstname}}</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Employee ID :{{$header->tid}}</td>
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
                            Earning(s)
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
                            {{$header->basicsalaryamount}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '1')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '2')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- @if($payrolldetails->lateamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>
                                &nbsp;&nbsp;&nbsp;Tardiness</td>
                            <td></td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->lateamount,2,'.',',')}}
                            </td>
                        </tr>
                    @endif --}}
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '3')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '4')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @if(count($addedparticulars)>0)
                        @foreach($addedparticulars as $addedparticular)
                        <tr style=" text-transform: uppercase;">
                            <td>
                                &nbsp;&nbsp;&nbsp;{{$addedparticular->description}}
                            </td>
                            <td style="text-align: right;">
                                @if($addedparticular->type == 1)
                                {{number_format($addedparticular->amount,2,'.',',')}}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($addedparticular->type == 2)
                                {{number_format($addedparticular->amount,2,'.',',')}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    {{-- <tr style=" text-transform: uppercase;">
                        <td>
                            &nbsp;&nbsp;&nbsp;Leave(s)
                        </td>
                        <td style="text-align: right;">
                            {{number_format(collect($payrolldetails->otherdetails)->where('employeeleaveid','!=','0')->sum('amount'),2)}}
                        </td>
                        <td>
                            
                        </td>
                    </tr> --}}
                    {{-- @if($payrolldetails->overtimepay > 0)
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
                    @endif --}}
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format(collect($particulars)->whereIn('particulartype',[3,4])->sum('amountpaid')+$header->basicsalaryamount+collect($addedparticulars)->where('type',1)->sum('amount'),2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format(collect($particulars)->whereIn('particulartype',[1,2])->sum('amountpaid')+collect($addedparticulars)->where('type',2)->sum('amount'),2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: 
                            <strong>{{number_format($header->netsalary,2,'.',',')}}</strong>
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
                        {{auth()->user()->name}}
                    </td>
                    <td style="width: 10%; "></td>
                    <td style="width: 28%; border-bottom: 1px solid black;">{{$header->firstname}} {{$header->middlename}} {{$header->lastname}} {{$header->suffix}}</td>
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
                    <td>
                        <div style="vertical-align: middle;">
                            <span>
                                <span style="font-size: 9px;">
                                    <strong>{{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->division}}</strong>
                                </span>
                                <br>
                                <span style="font-size: 9px;">
                                    {{DB::table('schoolinfo')->first()->address}}
                                </span>
                                <br>
                                <span style="font-size: 9px;">PAY SLIP</span>
                                <br>
                                <span style="font-size: 9px;">
                                    {{date('M d, Y', strtotime($payrollinfo->datefrom))}} - {{date('M d, Y', strtotime($payrollinfo->dateto))}}
                                </span>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="width: 100%;"></div>
            <table style="width:100%;  page-break-inside: avoid;">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:none; font-size: 12px;"><strong style="text-transform: uppercase;">{{$header->lastname.', '.$header->firstname}}</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Employee ID :{{$header->tid}}</td>
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
                            Earning(s)
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
                            {{$header->basicsalaryamount}}
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <strong>Regular Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '1')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3">
                           <strong>Other Deduction(s)</strong>
                        </td>
                    </tr>
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '2')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- @if($payrolldetails->lateamount > 0)
                        <tr style=" text-transform: uppercase;">
                            <td>
                                &nbsp;&nbsp;&nbsp;Tardiness</td>
                            <td></td>
                            <td style="text-align: right;">
                                {{number_format($payrolldetails->lateamount,2,'.',',')}}
                            </td>
                        </tr>
                    @endif --}}
                    <tr>
                        <td colspan="3">
                           <strong>Non Taxable De minimis</strong>
                        </td>
                    </tr>
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '3')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($particulars as $particular)
                        @if($particular->particulartype == '4')
                            <tr style=" text-transform: uppercase;">
                                <td>
                                    &nbsp;&nbsp;&nbsp;{{$particular->description}}
                                </td>
                                <td style="text-align: right;">
                                    {{number_format($particular->amountpaid,2,'.',',')}}
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @if(count($addedparticulars)>0)
                        @foreach($addedparticulars as $addedparticular)
                        <tr style=" text-transform: uppercase;">
                            <td>
                                &nbsp;&nbsp;&nbsp;{{$addedparticular->description}}
                            </td>
                            <td style="text-align: right;">
                                @if($addedparticular->type == 1)
                                {{number_format($addedparticular->amount,2,'.',',')}}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($addedparticular->type == 2)
                                {{number_format($addedparticular->amount,2,'.',',')}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    {{-- <tr style=" text-transform: uppercase;">
                        <td>
                            &nbsp;&nbsp;&nbsp;Leave(s)
                        </td>
                        <td style="text-align: right;">
                            {{number_format(collect($payrolldetails->otherdetails)->where('employeeleaveid','!=','0')->sum('amount'),2)}}
                        </td>
                        <td>
                            
                        </td>
                    </tr> --}}
                    {{-- @if($payrolldetails->overtimepay > 0)
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
                    @endif --}}
                    <tr style=" text-transform: uppercase;">
                        <td style="width: 50%; border-top: 1px solid black; border-bottom: 1px solid black;">
                            Total:
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format(collect($particulars)->whereIn('particulartype',[3,4])->sum('amountpaid')+$header->basicsalaryamount+collect($addedparticulars)->where('type',1)->sum('amount'),2,'.',',')}}
                        </td>
                        <td style="width: 25%; border-top: 1px solid black; border-bottom: 1px solid black;text-align: right;">
                            {{number_format(collect($particulars)->whereIn('particulartype',[1,2])->sum('amountpaid')+collect($addedparticulars)->where('type',2)->sum('amount'),2,'.',',')}}
                        </td>
                    </tr>
                    <tr style=" text-transform: uppercase;">
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black;">
                            Net Pay: 
                            <strong>{{number_format($header->netsalary,2,'.',',')}}</strong>
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
                        {{auth()->user()->name}}
                    </td>
                    <td style="width: 10%; "></td>
                    <td style="width: 28%; border-bottom: 1px solid black;">{{$header->firstname}} {{$header->middlename}} {{$header->lastname}} {{$header->suffix}}</td>
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