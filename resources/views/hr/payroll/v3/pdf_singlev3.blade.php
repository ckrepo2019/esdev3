
<style>

    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        font-size:11px ;
    }

    table {
        border-collapse: collapse;
    }
    
    .table thead th {
        vertical-align: bottom;
    }
    
    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
    }
    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
    }
    
    .table-bordered {
        border: 1px solid #00000;
    }

    .table-bordered td, .table-bordered th {
        border: 1px solid #00000;
    }

    .table-sm td, .table-sm th {
        padding: .3rem;
    }

    .text-center{
        text-align: center !important;
    }
    
    .text-right{
        text-align: right !important;
    }
    
    .text-left{
        text-align: left !important;
    }
    
    .p-0{
        padding: 0 !important;
    }
   .p-1 {
        padding-top: 3px!important;
        padding-bottom: 3px!important;
   }
   .p-2 {
        padding-top: 8px!important;
        padding-bottom: 8px!important;
   }
    .pl-3{
        padding-left: 1rem !important;
    }

    .mb-0{
        margin-bottom: 0;
    }

    .border-bottom{
        border-bottom:1px solid black;
    }

    .mb-1, .my-1 {
        margin-bottom: .25rem!important;
    }

    body{
        font-family: Calibri, sans-serif;
    }
    
    .align-middle{
        vertical-align: middle !important;    
    }

     
    .grades td{
        padding-top: .1rem;
        padding-bottom: .1rem;
        font-size: .7rem !important;
    }

    .studentinfo td{
        padding-top: .1rem;
        padding-bottom: .1rem;
      
    }

    .bg-red{
        color: red;
        border: solid 1px black !important;
    }

    td{
        padding-left: 5px;
        padding-right: 5px;
    }
    .aside {
        /* background: #48b4e0; */
        color: #000;
        line-height: 15px;
        height: 35px;
        border: 1px solid #000!important;
        
    }
    .aside span {
        /* Abs positioning makes it not take up vert space */
        /* position: absolute; */
        top: 0;
        left: 0;

        /* Border is the new background */
        background: none;

        /* Rotate from top left corner (not default) */
        transform-origin: 10 10;
        transform: rotate(-90deg);
    }
    .trhead {
        background-color: rgb(167, 223, 167); 
        color: #000; font-size
    }
    .trhead td {
        border: 3px solid #000;
    }

    body { 
        margin: 0px 0px;
        padding: 0px 0px;
    }
    
     .check_mark {
           font-family: ZapfDingbats, sans-serif;
        }
    @page { size: 8.5in 6.5in; margin: 0px 20px;}
    
</style>

@if ($schoolinfo)
<table style="width: 100%; table-layout: fixed;">
	<tr>
		<td width="15%" style="text-align: center;">
			<img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
		</td>
		<td width="70%" class="text-center" style="text-align: center!important">
			<div style="width: 100%; font-weight: bold; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
			<div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
		</td>
		<td width="15%" style="text-align: center;">
		</td>
	</tr>
</table>                 
@endif
<table style="width: 100%; table-layout: fixed; page-break-after: always" border="1">
    <tr>
        <td colspan="2">
            <div style="font-size: 11px; ">Payroll Period : {{date('M d, Y', strtotime($payrollinfo->datefrom))}} - {{date('M d, Y', strtotime($payrollinfo->dateto))}}</div>
            <h5 style="text-transform: uppercase;">{{$header->title}}. {{$header->firstname}} {{$header->middlename}} {{$header->lastname}} {{$header->suffix}}</h5>
            <div style="font-size: 11px;">{{$header->utype}}</div>
            <div style="font-size: 13px; font-weight: bold;">{{$header->tid}}</div>
            <table style="width: 100%; font-size: 11px; ">
                <tr>
                    <td>Daily Rate: {{number_format($header->dailyrate,2,'.',',')}}</td>
                    <td>No. of Present Days: {{$header->presentdays}}</td>
                </tr>
            </table>
            {{-- <div style="font-size: 11px; width: 45%; float: left; padding-top: 15px;">Daily Rate: {{number_format($header->dailyrate,2,'.',',')}}</div>
            <div style="font-size: 11px; width: 45%; float: right; padding-top: 15px;">No. of Present Days: {{$header->presentdays}}</div> --}}
        </td>
        <td colspan="2" rowspan="2" style="vertical-align: top !important; border-bottom: none;">
            <h5 style="text-align: center;">DEDUCTIONS</h5>
            <table style="width: 100%; font-size: 11px; ">
                @if($payrolldetail->daysabsentamount > 0)
                <tr style=" text-transform: uppercase;">
                    <td style="width: 5%;">&nbsp;</td>
                    <td>Absent
                    </td>
                    <td>{{$payrolldetail->absentdays > 0 ? $payrolldetail->absentdays.' day(s)' : ''}} </td>
                    <td style="width: 20%; text-align: right;">
                        {{number_format($payrolldetail->daysabsentamount,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                @endif
                @if($payrolldetail->lateamount > 0)
                <tr style=" text-transform: uppercase;">
                    <td style="width: 5%;">&nbsp;</td>
                    <td>Late
                    </td>
                    <td>{{$payrolldetail->lateminutes > 0 ? $payrolldetail->lateminutes.' min(s)' : ''}}</td>
                    <td style="width: 20%; text-align: right;">
                        {{number_format($payrolldetail->lateamount,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                @endif
                @foreach($particulars as $particular)
                    @if($particular->particulartype == '1' && $particular->amountpaid > 0)
                        <tr style=" text-transform: uppercase;">
                            <td style="width: 5%;">&nbsp;</td>
                            <td colspan="2">{{$particular->description}}
                            </td>
                            <td style="width: 20%; text-align: right;">
                                {{number_format($particular->amountpaid,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    @endif
                @endforeach
                @foreach($particulars as $particular)
                    @if($particular->particulartype == '2' && $particular->amountpaid > 0)
                        <tr style=" text-transform: uppercase;">
                            <td style="width: 5%;">&nbsp;</td>
                            <td colspan="2">{{$particular->description}}
                            </td>
                            <td style="width: 20%; text-align: right;">
                                {{number_format($particular->amountpaid,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="vertical-align: top; border-bottom: none;">
            <h5 style="text-align: center;">EARNINGS</h5>
            <table style="width: 100%; font-size: 11px; ">
                <tr>
                    <td style="width: 5%;"></td>
                    <td>BASIC PAY</td>
                    <td style="width: 25%; text-align: right;">{{number_format($header->basicsalaryamount,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                @if(count($leavedetails)>0)
                    @foreach($leavedetails as $leavedetail)
                        <tr>
                            <td style="width: 5%;"></td>
                            <td>{{$leavedetail->leave_type}} {{date('m/d/Y',strtotime($leavedetail->ldate))}}</td>
                            <td style="width: 25%; text-align: right;">{{number_format($leavedetail->amount,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                    @endforeach
                @endif
                @foreach($particulars as $particular)
                    @if($particular->particulartype == '3')
                        <tr style=" text-transform: uppercase;">
                            <td></td>
                            <td>{{$particular->description}}
                            </td>
                            <td style="text-align: right;">
                                {{number_format($particular->amountpaid,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    @endif
                @endforeach
                @foreach($particulars as $particular)
                    @if($particular->particulartype == '4')
                        <tr style=" text-transform: uppercase;">
                            <td></td>
                            <td>{{$particular->description}}
                            </td>
                            <td style="text-align: right;">
                                {{number_format($particular->amountpaid,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-right: none; border-top: none; border-bottom: none;">&nbsp;</td>
        <td style="border-left: none; border-top: none; border-bottom: none;">&nbsp;</td>
        <td style="border-right: none; border-top: none; border-bottom: none;">&nbsp;</td>
        <td style="border-left: none; border-top: none; border-bottom: none;">&nbsp;</td>
    </tr>
    <tr style="font-size: 11px;">
        <td style="border-right: none; border-top: none;"><label>&nbsp;&nbsp;&nbsp;&nbsp;TOTAL EARNINGS</label></td>
        <td style="border-left: none; text-align: right; font-weight: bold; border-top: none;">{{number_format($payrolldetail->totalearning,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td style="border-right: none; border-top: none;"><label>&nbsp;&nbsp;&nbsp;&nbsp;TOTAL DEDUCTIONS</label></td>
        <td style="border-left: none; text-align: right; font-weight: bold; border-top: none;">{{number_format($header->totaldeduction,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="border-right: none;">&nbsp;</td>
        <td style="vertical-align: top; text-align: center; border-right: none; border-left: none;">NET PAY</td>
        <td style="vertical-align: top; text-align: right; font-weight: bold; border-left: none;">{{number_format($header->netsalary,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    {{-- <tr>
        <td colspan="2" style="font-size: 12px; text-align: center; border-bottom: none;"><h5>EMPLOYEES CONTRIBUTION</h5></td>
        <td rowspan="2" style="vertical-align: top; text-align: center; border-right: none; border-bottom: none;">NET PAY</td>
        <td rowspan="2" style="vertical-align: top; text-align: right; font-weight: bold; border-left: none; border-bottom: none;">{{number_format($payrolldetail->netsalary,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="vertical-align: top; border-top: none;">
            @if(collect($particulars)->where('particulartype','1')->count()>0)
            <table style="width: 100%; font-size: 11px; ">
                <tr>
                    <th style=" text-align: left; padding-left: 15px;">Active</th>
                </tr>
                @foreach($particulars as $particular)
                    @if($particular->particulartype == '1')
                        <tr style=" text-transform: uppercase; text-align: left ;">
                            <td style=" padding-left: 15px;">{{$particular->description}}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            @endif
        </td>
    </tr> --}}
</table>


<table width="100%" class="table table-sm mb-0" style="padding-top: 10px;">
    <tr>
        <td width="60%" class="p-0" style="vertical-align: top; font-size: 12px">
            <table width="100%" class="p-0" style="table-layout: fixed; font-size: 14px!important">
                <tr>
                    <td class="p-0 text-left"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="16px">&nbsp;<span style="font-size: 20px!important;"><b>{{$schoolinfo[0]->schoolname}}</b></span></td>
                </tr>
            </table>
            <table width="100%" class="p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" width="28%"><b>Employee No.</b></td>
                    <td class="p-0" width="72%"><b><span>:</span> {{$header->tid}}</b></td>
                </tr>
            </table>
            <table width="100%" class="p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" width="28%"><b>Employee Name</b></td>
                    <td class="p-0" width="72%"><b><span>:</span> {{$header->lastname}}@if ($header->lastname != null), @endif {{$header->firstname}} {{$header->middlename}}  {{$header->suffix}}</b></td>
                </tr>
            </table>
            {{-- <table width="100%" class="p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" width="28%"><b>Division</b></td>
                    <td class="p-0" width="72%"><b><span>:</span> ADMIN101</b></td>
                </tr>
            </table> --}}
            <table width="100%" class="p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" width="28%"><b>Rate</b></td>
                    <td class="p-0" width="23%"><b><span>:</span> {{number_format($header->basicsalaryamount,2,'.',',')}}</b></td>
                    <td class="p-0" width="49%"><b>Position :&nbsp;&nbsp;{{$header->utype}}</b></td>
                </tr>
            </table>
            {{-- <table width="100%" class="p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" width="28%"><b>Tax Code</b></td>
                    <td class="p-0" width="72%"><b><span>:</span> S</b></td>
                </tr>
            </table> --}}
        </td>
        <td width="40%" class="p-0" style="vertical-align: top;">
            <table width="100%" class="table table-sm" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" style="font-size: 25px; letter-spacing: 4px;"><b>PAY SLIP</b></td>
                </tr>
                <tr>
                    <td class="p-0" style="font-size: 11px;"><b>&nbsp;&nbsp;&nbsp;({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" class="table table-sm" style="size: 30px;padding-top: 5px;">
    <tr>
        <td class="p-0" width="77.2%"  style="vertical-align: top; padding-right: 2px!important;" >
            <table width="100%" class="p-0 mb-0" style="table-layout: fixed; margin-bottom: 0px !important;" border="1" >
               <tr>
                    <td width="35%" class="text-center p-0" style="border: 1px solid #000;  height: 332px!important; ">
                        <span style="font-size: 13px;"><b>Earnings</b></span>
                        <table width="100%" class="" style="border-top: 1px solid #000;table-layout: fixed;padding-top: 5px;">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b>HRS</b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="border-top: 1px solid #000;table-layout: fixed;font-size: 10px;">
                            {{-- <tr>
                                <td class="text-left"><span><b>Basic Pay</b></span></td>
                                <td></td>
                                <td class="text-right"><span><b>{{number_format($header->basicsalaryamount,2,'.',',')}}</b></span></td>
                            </tr> --}}
                            <tr>
                                <td class="text-left p-0" width="60%">&nbsp;<span><b>Basic Pay</b></span></td>
                                <td class="p-0"></td>
                                <td class="text-right p-0" width="40%"><b>{{number_format($header->basicsalaryamount,2,'.',',')}}</b>&nbsp;</td>
                            </tr>
                            @if(count($leavedetails)>0)
                                @foreach($leavedetails as $leavedetail)
                                    <tr>
                                        <td class="text-left p-0"><span>&nbsp;<b>{{$leavedetail->leave_type}} {{date('m/d/Y',strtotime($leavedetail->ldate))}}</b></span></td>
                                        <td class="p-0"></td>
                                        <td class="text-right p-0"><b>{{number_format($leavedetail->amount,2,'.',',')}}</b>&nbsp;</td>
                                    </tr>
                                @endforeach
                            @endif
                            @foreach($particulars as $particular)
                                @if($particular->particulartype == '3')
                                    <tr>
                                        <td class="text-left p-0"><span>&nbsp;<b>{{ucwords($particular->description)}}</b></span></td>
                                        <td class="p-0"></td>
                                        <td class="text-right p-0">
                                            <b>{{number_format($particular->amountpaid,2,'.',',')}}</b>&nbsp;
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($particulars as $particular)
                                @if($particular->particulartype == '4')
                                    <tr>
                                        <td class="text-left p-0"><span>&nbsp;<b>{{$particular->description}}</b></span></td>
                                        <td class="p-0"></td>
                                        <td class="text-right p-0">
                                            <b>{{number_format($particular->amountpaid,2,'.',',')}}</b>&nbsp;
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                        {{-- <table width="100%" class="p-0" style="table-layout: fixed; border-top: 1px solid #000;padding-top: 5px; position: absolute; bottom: -5; left: .5;">
                            <tr>
                                <td class="text-left" style="padding-top: 7px!important;"><span><b>Gross pay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($payrolldetail->totalearning,2,'.',',')}}</b></span></td>
                            </tr>
                        </table> --}}
                    </td>
                    <td width="30%" class="text-center p-0"  style="border: 1px solid #000;">
                        <span style="font-size: 13px;"><b>Deductions</b></span>
                        <table width="100%" class="" style="table-layout: fixed; border-top: 1px solid #000;padding-top: 5px;">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b></b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="width: 100%; font-size: 10px; border-top: 1px solid #000;">
                            @if($payrolldetail->daysabsentamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Absent</b></td>
                                {{-- <td>{{$payrolldetail->absentdays > 0 ? $payrolldetail->absentdays.' day(s)' : ''}} </td> --}}
                                <td class="p-0"><b>{{$payrolldetail->absentdays > 0 ? $payrolldetail->absentdays.'days' : ''}}</b></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($payrolldetail->daysabsentamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @if($payrolldetail->lateamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Late</b></td>
                                <td class="p-0"><b>{{$payrolldetail->lateminutes > 0 ? $payrolldetail->lateminutes.' min(s)' : ''}}</b></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($payrolldetail->lateamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @foreach($particulars as $particular)
                                @if($particular->particulartype == '1' && $particular->amountpaid > 0)
                                    <tr>
                                        <td class="text-left p-0" colspan="2">&nbsp;<b>{{$particular->description}}</b>
                                        </td>
                                        <td class="text-right p-0">
                                            <b>{{number_format($particular->amountpaid,2,'.',',')}}&nbsp;</b>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($particulars as $particular)
                                @if($particular->particulartype == '2' && $particular->amountpaid > 0)
                                    <tr>
                                        <td class="text-left p-0" colspan="2">&nbsp;<b>{{$particular->description}}</b>
                                        </td>
                                        <td class="text-right p-0">
                                            <b>{{number_format($particular->amountpaid,2,'.',',')}}&nbsp;</b>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                        {{-- <table width="100%" class="p-0" style="table-layout: fixed; border-top: 1px solid #000;padding-top: 5px; position: absolute; bottom: -5; left: 156;">
                            <tr>
                                <td class="text-left" style="padding-top: 7px!important;"><span><b>Deductions</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->totaldeduction,2,'.',',')}}</b></span></td>
                            </tr>
                        </table> --}}
                    </td>
                    <td width="35%" class="text-center p-0">
                        <span style="font-size: 13px;"><b>Balances</b></span>
                        <table width="100%" class="" style="table-layout: fixed; border-top: 1px solid #000;padding-top: 5px;">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b></b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="width: 100%; font-size: 10px; border-top: 1px solid #000;">
                            {{-- @foreach($particulars as $particular)
                                @if($particular->particulartype == '1' && $particular->amountpaid > 0)
                                    <tr>
                                        <td class="text-left p-0" colspan="2">&nbsp;<b>{{$particular->description}}</b>
                                        </td>
                                        <td class="text-right p-0">
                                            <b>{{$particular->balance}}&nbsp;</b>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach --}}
                            {{-- @foreach($particulars as $particular)
                                @if($particular->particulartype == '2' && $particular->amountpaid > 0)
                                    <tr>
                                        <td class="text-left p-0" colspan="2">&nbsp;<b>{{$particular->description}}</b>
                                        </td>
                                        <td class="text-right p-0">
                                            <b>{{$particular->balance}}&nbsp;</b>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach --}}
                            @foreach($otherdeductions as $otherdeduction)
                                <tr>
                                    <td class="text-left p-0" colspan="2">&nbsp;<b>{{$otherdeduction->description}}</b>
                                    </td>
                                    <td class="text-right p-0">
                                        <b>{{number_format($otherdeduction->interestamount - $otherdeduction->totalotherdeductionpaid,2,'.',',')}}&nbsp;</b>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        {{-- <table width="100%" class="p-0" style="table-layout: fixed; border-top: 1px solid #000;padding-top: 5px; position: absolute; bottom: -5; left: 289;">
                            <tr>
                                <td class="text-left" style="padding-top: 7px!important;"><span><b>NetPay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->netsalary,2,'.',',')}}</b></span></td>
                            </tr>
                        </table> --}}
                    </td>
               </tr>
            </table>

            <table width="100%" class="table table-sm p-0" style="margin-top: -3px!important; table-layout: fixed!important; font-size: 10px;" border="1">
                <tr>
                    <td width="35%" class="p-0" style="">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>Gross pay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($payrolldetail->totalearning,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="30%" class="p-0">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>Deductions</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->totaldeduction,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="35%" class="p-0">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>NetPay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->netsalary,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td class="p-0" width=".8%">
            <table width="100%" class="table table-sm" style="table-layout: fixed; padding-left: 5px; padding-right: 5px;">
                <tr>
                    <td style="border: 1px dotted #000; height: 348px!important;"></td>
                </tr>
            </table>
        </td>
        <td class="p-0" width="22%" style="vertical-align: top;">
            <table width="100%" class="table table-sm" style="table-layout: fixed; padding-left: 2px; padding-right: 5px;">
                <tr>
                    <td style="  border: 1px solid #000;">
                        <table>
                            <tr>
                                <td class="text-center p-0"><b>Payrol Cutoff</b></td>
                            </tr>
                            {{-- <tr>
                                <td class="p-0 text-center"><b>01120311</b></td>
                            </tr> --}}
                            <tr>
                                <td class="text-center p-0"><b>({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 5px!important"><b>{{$header->tid}}</b></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="font-size: 8px;">{{$header->lastname}}@if ($header->lastname != null), @endif {{$header->firstname}} {{$header->middlename}}  {{$header->suffix}}</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 12px; padding-top: 10px!important;"><b>== NETPAY == </b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 13px; padding-top: 5px!important;"><b>{{number_format($header->netsalary,2,'.',',')}}</b></td>
                            </tr>
                            {{-- <tr>
                                <td class="text-center p-0" style="font-size: 12px; padding-top: 10px!important;"><b>== Coin Savings == </b></td>
                            </tr> --}}
                            <tr>
                                <td class="text-left p-0" style="padding-top: 15px!important"><b>Prepared by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 10px!important; font-size: 8px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 15px!important"><b>Checked by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 10px!important; font-size: 8px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 15px!important"><b>Approved by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 10px!important; font-size: 8px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 15px!important"><b>Recieved by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 10px!important; font-size: 8px;">{{$header->lastname}}@if ($header->lastname != null), @endif {{$header->firstname}} {{$header->middlename}}  {{$header->suffix}}</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important"><b>{{$schoolinfo[0]->schoolname}}</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
             </table>
        </td>
    </tr>
    
</table>
<table style="position: absolute; top: 70.9%; left: 0;">
    <tr>
        <td colspan="3" class="p-0 text-left"><span class="" style="font-size: 7.5px;"><i>This statement constitutes a record and deductions, Please report any discrepancies. Actual payout of salaries is still based on this schedule or release, and does not coincide with issuance of payslip.</i></span></td>
    </tr>
</table>
