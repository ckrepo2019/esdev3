@php
    $totalamountpaid = 0;
@endphp
<style>
	.watermark {
		opacity: .1;
		position: absolute;
		left: 30%;
		bottom: 68.2%;
	}
	.watermark2 {
		opacity: .1;
		position: absolute;
		left: 30%;
		top: 62%;
	}
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
    @page{ size: 8.5in 13in; margin: 0px 20px;}
    
</style>
{{-- <div>
	<div class="row watermark">
		<div class="col-md-12">
			<img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="400px">
		</div>
	</div>
</div>
<table width="100%" class="table table-sm mb-0" style="padding-top: 10px;">
    <tr>
        <td colspan="2" class="p-0 text-left" style=""><span style="font-size: 20px!important;"><b>{{$schoolinfo[0]->schoolname}}</b></span></td>
        <td rowspan="2" class="p-0" style="vertical-align: top;">
            <table width="100%" class="table table-sm p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" style="font-size: 20px; letter-spacing: 4px;"><b>PAY SLIP</b></td>
                </tr>
                <tr>
                    <td class="p-0" style="font-size: 11px;"><b>&nbsp;&nbsp;&nbsp;({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="10%" class="p-0" style="">
        <div class="table-avatar">
              @php
                    $number = rand(1,3);
                    if(strtoupper($employeeinfo->gender) == 'FEMALE'){
                        $avatar = 'avatar/T(F) '.$number.'.png';
                    }
                    else{
                        $avatar = 'avatar/T(M) '.$number.'.png';
                    }
                  @endphp
                <a href="#" class="avatar" style="">
                    <img src="{{ asset($employeeinfo->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 50px;height: 50px;border: 2px double #000; position: absolute"/>
                </a>
             
          </div>
        </td>
        <td width="65%" class="p-0" style="vertical-align: top; font-size: 12px; padding-left: 10px!important;">
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
            <table width="100%" class="p-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0" width="28%"><b>Rate</b></td>
                    <td class="p-0" width="23%"><b><span>:</span> {{number_format($header->basicsalaryamount,2,'.',',')}}</b></td>
                    <td class="p-0" width="49%"><b>Position :&nbsp;&nbsp;{{$header->utype}}</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" class="table table-sm" style="size: 30px;padding-top: 13px;">
    <tr>
        <td class="p-0" width="77.2%"  style="vertical-align: top; padding-right: 2px!important;" >
            <table width="100%" class="p-0 mb-0" style="table-layout: fixed; margin-bottom: 0px !important;" border="1" >
               <tr>
                    <td width="33.3333333333%" class="text-center p-0" style="border: 1px solid #000;  height: 310px!important; ">
                        <span style="font-size: 12px;"><b>Earnings</b></span>
                        <table width="100%" class="" style="border-top: 1px solid #000; border-bottom: 1px solid #000;table-layout: fixed;padding-top: 5px;font-size: 9px;">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b>HRS</b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed;font-size: 9px;">
                            <tr>
                                <td class="text-left p-0" width="60%">&nbsp;<span><b>Basic Pay</b></span></td>
                                <td class="p-0"></td>
                                <td class="text-right p-0" width="40%"><b>{{number_format($header->basicsalaryamount,2,'.',',')}}</b>&nbsp;</td>
                            </tr>
                            @if(count($holidays)>0)
                                @foreach($holidays as $holiday)
                                    @if($holiday->particulartype == '8')
                                        <tr>
                                            <td class="text-left p-0" style="padding-left: 3px!important; font-size: 7.5px!important"><span><b>{{ucwords($holiday->description)}}</b></span></td>
                                            <td class="p-0"></td>
                                            <td class="text-right p-0" style="vertical-align: middle!important;">
                                                <b>{{number_format($holiday->amountpaid,2,'.',',')}}</b>&nbsp;
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
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

                            <tr>
                                <td class="p-0" colspan="3">
                                    <table class="table" style="padding-top: 20px;">
                                        @foreach($addedparticulars as $addedparticular)
                                            @if($addedparticular->type == '1')
                                                <tr>
                                                    <td class="text-left p-0"><span>&nbsp;<b>{{$addedparticular->description}}</b></span></td>
                                                    <td class="p-0"></td>
                                                    <td class="text-right p-0">
                                                        <b>{{number_format($addedparticular->amount,2,'.',',')}}</b>&nbsp;
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                    <td width="33.3333333333%" class="text-center p-0"  style="border: 1px solid #000;">
                        <span style="font-size: 12px;"><b>Deductions</b></span>
                        <table width="100%" class="" style="table-layout: fixed; border-top: 1px solid #000; border-bottom: 1px solid #000;padding-top: 5px; font-size: 9px">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b></b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="width: 100%; font-size: 9px;">
                            @if($payrolldetail->daysabsentamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Absent</b></td>
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

                            <tr>
                                <td class="p-0" colspan="3">
                                    <table class="table" style="padding-top: 20px;">
                                        @foreach($addedparticulars as $addedparticular)
                                            @if($addedparticular->type == '2')
                                                <tr>
                                                    <td class="text-left p-0"><span>&nbsp;<b>{{$addedparticular->description}}</b></span></td>
                                                    <td class="p-0"></td>
                                                    <td class="text-right p-0">
                                                        <b>{{number_format($addedparticular->amount,2,'.',',')}}</b>&nbsp;
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.3333333333%" class="text-center p-0">
                        <span style="font-size: 12px;"><b>Balances</b></span>
                        <table width="100%" class="" style="table-layout: fixed; border-top: 1px solid #000; border-bottom: 1px solid #000;padding-top: 5px;font-size: 9px">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b></b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="width: 100%; font-size: 9px;">
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
                    </td>
               </tr>
            </table>

            <table width="100%" class="table table-sm p-0" style="margin-top: -3px!important; table-layout: fixed!important; font-size: 10px;" border="1">
                <tr>
                    <td width="33.3333333333%" class="p-0" style="">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>Gross pay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($payrolldetail->totalearning,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.3333333333%" class="p-0">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>Deductions</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->totaldeduction,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.3333333333%" class="p-0">
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
                    <td style="border: 1px dotted #000; height: 310px!important;"></td>
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
                            <tr>
                                <td class="text-center p-0" style="font-size: 9px;"><b>({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
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
                            <tr>
                                <td class="text-left p-0" style="padding-top: 5px!important"><b>Prepared by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 8px!important; font-size: 8px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 8px!important"><b>Checked by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 8px!important; font-size: 8px;">&nbsp;</td>
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
<table style="position: absolute; top: 53%; left: 0;">
    <tr>
        <td colspan="3" class="p-0 text-left"><span class="" style="font-size: 7.5px;"><i>This statement constitutes a record and deductions, Please report any discrepancies. Actual payout of salaries is still based on this schedule or release, and does not coincide with issuance of payslip.</i></span></td>
    </tr>
</table> --}}
<div>
	<div class="row watermark">
		<div class="col-md-12">
			<img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="300px">
		</div>
	</div>
</div>
<table width="100%" class="table table-sm mb-0" style="padding-top: 10px;">
    <tr>
        <td colspan="2" class="p-0 text-left" style=""><span style="font-size: 20px!important;"><b>{{$schoolinfo[0]->schoolname}}</b></span></td>
        <td rowspan="2" class="p-0" style="vertical-align: top;">
            <table width="100%" class="table table-sm p-0" style="table-layout: fixed; padding-left: 110px!important;">
                <tr>
                    <td class="p-0" style="font-size: 20px; letter-spacing: 4px; padding-left: 10px!important;" ><b>PAY SLIP</b></td>
                </tr>
                <tr>
                    <td class="p-0" style="font-size: 11px;"><b>&nbsp;&nbsp;&nbsp;({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="10%" class="p-0" style="">
        <div class="table-avatar">
			@php
                      $number = rand(1,3);
                      if(strtoupper($employeeinfo->gender) == 'FEMALE'){
                          $avatar = 'avatar/T(F) '.$number.'.png';
                      }
                      else{
                          $avatar = 'avatar/T(M) '.$number.'.png';
                      }
                  @endphp
                <a href="#" class="avatar">
                      <img src="{{ asset($employeeinfo->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 50px;height: 50px;border: 2px double #000; position: absolute" />
              </a>
             
             
          </div>
        </td>
        <td width="65%" colspan="2" class="p-0" style="vertical-align: top; font-size: 12px; padding-left: 10px!important;">
            {{-- <table width="100%" class="p-0" style="table-layout: fixed; font-size: 14px!important">
                <tr>
                    <td class="p-0 text-left"><span style="font-size: 20px!important;"><b>{{$schoolinfo[0]->schoolname}}</b></span></td>
                </tr>
            </table> --}}
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
    </tr>
</table>
<table width="100%" class="table table-sm" style="size: 30px;padding-top: 13px;">
    <tr>
        <td class="p-0" width="77.2%"  style="vertical-align: top; padding-right: 2px!important;" >
            <table width="100%" class="p-0 mb-0" style="table-layout: fixed; margin-bottom: 0px !important;" border="1" >
            <tr>
                    <td width="33.3333333333%" class="text-center p-0" style="border: 1px solid #000;  height: 270px!important; ">
                        <span style="font-size: 12px;"><b>Earnings</b></span>
                        <table width="100%" class="" style="border-top: 1px solid #000; border-bottom: 1px solid #000;table-layout: fixed;padding-top: 5px;font-size: 9px;">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b>HRS</b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed;font-size: 9px;">
                            <tr>
                                <td class="text-left p-0" width="60%">&nbsp;<span><b>Basic Pay</b></span></td>
                                <td class="p-0"></td>
                                <td class="text-right p-0" width="40%"><b>{{number_format($header->basicsalaryamount,2,'.',',')}}</b>&nbsp;</td>
                            </tr>
                            @if ($header->clregularloadamount > 0)
                                <tr>
                                    <td class="text-left p-0" width="60%">&nbsp;<span><b>Regular Load</b></span></td>
                                    <td class="p-0"></td>
                                    <td class="text-right p-0" width="40%"><b>{{number_format($header->clregularloadamount,2,'.',',')}}</b>&nbsp;</td>
                                </tr>
                            @endif
                            @if ($header->cloverloadloadamount > 0)
                                <tr>
                                    <td class="text-left p-0" width="60%">&nbsp;<span><b>Overload</b></span></td>
                                    <td class="p-0"></td>
                                    <td class="text-right p-0" width="40%"><b>{{number_format($header->cloverloadloadamount,2,'.',',')}}</b>&nbsp;</td>
                                </tr>
                            @endif
                            @if ($header->clparttimeloadamount > 0)
                                <tr>
                                    <td class="text-left p-0" width="60%">&nbsp;<span><b>Part Time</b></span></td>
                                    <td class="p-0"></td>
                                    <td class="text-right p-0" width="40%"><b>{{number_format($header->clparttimeloadamount,2,'.',',')}}</b>&nbsp;</td>
                                </tr>
                            @endif
                            @if(count($holidays)>0)
                                @foreach($holidays as $holiday)
                                    @if ($holiday->employeeid == $header->employeeid)
                                        @if($holiday->particulartype == '8')
                                            <tr>
                                                <td class="text-left p-0" style="padding-left: 3px!important; font-size: 7.5px!important"><span><b>{{ucwords($holiday->description)}}</b></span></td>
                                                <td class="p-0"></td>
                                                <td class="text-right p-0" style="vertical-align: middle!important;">
                                                    <b>{{number_format($holiday->amountpaid,2,'.',',')}}</b>&nbsp;
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                            @if(count($leavedetails)>0)
                                @foreach($leavedetails as $leavedetail)
                                    @if ($leavedetail->employeeid == $header->employeeid)
                                        <tr>
                                            <td class="text-left p-0"><span>&nbsp;<b>{{$leavedetail->leave_type}} {{date('m/d/Y',strtotime($leavedetail->ldate))}}</b></span></td>
                                            <td class="p-0"></td>
                                            <td class="text-right p-0"><b>{{number_format($leavedetail->amount,2,'.',',')}}</b>&nbsp;</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            @foreach($particulars as $particular)
                                @if ($particular->employeeid == $header->employeeid)
                                    @if($particular->particulartype == '3')
                                    
                                        <tr>
                                            <td class="text-left p-0"><span>&nbsp;<b>{{ucwords($particular->description)}}</b></span></td>
                                            <td class="p-0"></td>
                                            <td class="text-right p-0">
                                                <b>{{number_format($particular->amountpaid,2,'.',',')}}</b>&nbsp;
                                            </td>
                                        </tr>
                                    @endif
                                    @endif
                            @endforeach
                            @foreach($particulars as $particular)
                                @if ($particular->employeeid == $header->employeeid)
                                    @if($particular->particulartype == '4')
                                        <tr>
                                            <td class="text-left p-0"><span>&nbsp;<b>{{$particular->description}}</b></span></td>
                                            <td class="p-0"></td>
                                            <td class="text-right p-0">
                                                <b>{{number_format($particular->amountpaid,2,'.',',')}}</b>&nbsp;
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach

                            <tr>
                                <td class="p-0" colspan="3">
                                    <table class="table" style="padding-top: 20px;">
                                        @foreach($addedparticulars as $addedparticular)
                                            @if ($addedparticular->employeeid == $header->employeeid)
                                                @if($addedparticular->type == '1')
                                                    <tr>
                                                        <td class="text-left p-0"><span>&nbsp;<b>{{$addedparticular->description}}</b></span></td>
                                                        <td class="p-0"></td>
                                                        <td class="text-right p-0">
                                                            <b>{{number_format($addedparticular->amount,2,'.',',')}}</b>&nbsp;
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                    <td width="33.3333333333%" class="text-center p-0"  style="border: 1px solid #000;">
                        <span style="font-size: 12px;"><b>Deductions</b></span>
                        <table width="100%" class="" style="table-layout: fixed; border-top: 1px solid #000; border-bottom: 1px solid #000;padding-top: 5px; font-size: 9px">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b></b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="width: 100%; font-size: 9px;">
                            @if($header->daysabsentamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Absent</b></td>
                                <td class="p-0"><b>{{$header->absentdays > 0 ? $header->absentdays.'days' : ''}}</b></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($header->daysabsentamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @if($header->lateamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Late</b></td>
                                <td class="p-0"><b>{{$header->lateminutes > 0 ? $header->lateminutes.' min(s)' : ''}}</b></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($header->lateamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @if($header->undertimeamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Undertime</b></td>
                                <td class="p-0"><b>{{$header->undertimeminutes > 0 ? $header->undertimeminutes.' min(s)' : ''}}</b></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($header->undertimeamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @if($header->regulartardyamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Rregular Tardy</b></td>
                                <td class="p-0"></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($header->regulartardyamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @if($header->overloadtardyamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Overload Tardy</b></td>
                                <td class="p-0"></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($header->overloadtardyamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @if($header->emergencyloadtardyamount > 0)
                            <tr>
                                <td class="text-left p-0">&nbsp;<b>Emergency Tardy</b></td>
                                <td class="p-0"></td>
                                <td class="text-right p-0">
                                    <b>{{number_format($header->emergencyloadtardyamount,2,'.',',')}}&nbsp;</b>
                                </td>
                            </tr>
                            @endif
                            @foreach($particulars as $particular)
                                @if ($particular->employeeid == $header->employeeid)
                                    @if($particular->particulartype == '1' && $particular->amountpaid > 0)
                                        <tr>
                                            <td class="text-left p-0" colspan="2">&nbsp;<b>{{$particular->description}}</b>
                                            </td>
                                            <td class="text-right p-0">
                                                <b>{{number_format($particular->amountpaid,2,'.',',')}}&nbsp;</b>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                    
                            @foreach($particulars as $particular)
                                @if ($particular->employeeid == $header->employeeid)
                                    @if($particular->particulartype == '2' && $particular->amountpaid > 0 && $particular->paidstatus == 1)
                                        <tr>
                                            <td class="text-left p-0" colspan="2">&nbsp;<b>{{$particular->description}}</b>
                                            </td>
                                            <td class="text-right p-0">
                                                <b>{{number_format($particular->amountpaid,2,'.',',')}}&nbsp;</b>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach

                            <tr>
                                <td class="p-0" colspan="3">
                                    <table class="table" style="padding-top: 20px;">
                                        @foreach($addedparticulars as $addedparticular)
                                            @if ($addedparticular->employeeid == $header->employeeid)
                                                @if($addedparticular->type == '2')
                                                    <tr>
                                                        <td class="text-left p-0"><span>&nbsp;<b>{{$addedparticular->description}}</b></span></td>
                                                        <td class="p-0"></td>
                                                        <td class="text-right p-0">
                                                            <b>{{number_format($addedparticular->amount,2,'.',',')}}</b>&nbsp;
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.3333333333%" class="text-center p-0">
                        <span style="font-size: 12px;"><b>Balances</b></span>
                        <table width="100%" class="" style="table-layout: fixed; border-top: 1px solid #000; border-bottom: 1px solid #000;padding-top: 5px;font-size: 9px">
                            <tr>
                                <td><span><b>Description</b></span></td>
                                <td><span><b></b></span></td>
                                <td><span><b>Amount</b></span></td>
                            </tr>
                        </table>
                        {{-- @dd($otherdeductions); --}}
                        {{-- <table class="table table-sm" style="width: 100%; font-size: 9px;">
                            @foreach($otherdeductions as $otherdeduction)
                                @if ($otherdeduction->employeeid == $header->employeeid)
                                    <tr>
                                        <td class="text-left p-0" colspan="2">&nbsp;<b>{{$otherdeduction->description}}</b>
                                        </td>
                                        <td class="text-right p-0">
                                            @if ($otherdeduction->term == 0)
                                                @php
                                                    if ($otherdeduction->totalamountpaid == null) {
                                                        $totalamountpaid = 0;
                                                    } else {
                                                        $totalamountpaid = $otherdeduction->totalamountpaid;
                                                    }
                                                @endphp
                                                @if ($otherdeduction->term == 0)
                                                    <b>{{ number_format($otherdeduction->amount, 2, '.', ',')}}&nbsp;</b>
                                                @else
                                                    <b>{{ number_format($otherdeduction->amount - $totalamountpaid, 2, '.', ',')}}&nbsp;</b>
                                                @endif
                                            @else
                                                @php
                                                    if ($otherdeduction->totalamountpaid == null) {
                                                        $totalamountpaid = 0;
                                                    } else {
                                                        $totalamountpaid = $otherdeduction->totalamountpaid;
                                                    }
                                                @endphp

                                                @if ($otherdeduction->term == 0)
                                                    <b>{{ number_format($otherdeduction->amount, 2, '.', ',')}}&nbsp;</b>
                                                @else
                                                    <b>{{ number_format($otherdeduction->amount * $otherdeduction->term - $totalamountpaid, 2, '.', ',')}}&nbsp;</b>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table> --}}
                        <table class="table table-sm" style="width: 100%; font-size: 9px;">
                            @foreach($empotherdeductions as $otherdeduction)
                                @if ($otherdeduction->totalamountpaid != $otherdeduction->fullamount)
                                    @if ($otherdeduction->employeeid == $header->employeeid)
                                        <tr>
                                            <td class="text-left p-0" colspan="2">&nbsp;<b>{{$otherdeduction->description}}</b>
                                            </td>
                                            <td class="text-right p-0">
                                                @if ($otherdeduction->term == 0)
                                                    @php
                                                        if ($otherdeduction->totalamountpaid == null) {
                                                            $totalamountpaid = 0;
                                                        } else {
                                                            $totalamountpaid = $otherdeduction->totalamountpaid;
                                                        }
                                                    @endphp
                                                    @if ($otherdeduction->term == 0)
                                                        <b>{{ number_format($otherdeduction->amount, 2, '.', ',')}}&nbsp;</b>
                                                    @else
                                                        <b>{{ number_format($otherdeduction->amount - $totalamountpaid, 2, '.', ',')}}&nbsp;</b>
                                                    @endif
                                                @else
                                                    @php
                                                        if ($otherdeduction->totalamountpaid == null) {
                                                            $totalamountpaid = 0;
                                                        } else {
                                                            $totalamountpaid = $otherdeduction->totalamountpaid;
                                                        }
                                                    @endphp

                                                    @if ($otherdeduction->term == 0)
                                                        <b>{{ number_format($otherdeduction->amount, 2, '.', ',')}}&nbsp;</b>
                                                    @else
                                                        <b>{{ number_format($otherdeduction->amount * $otherdeduction->term - $totalamountpaid, 2, '.', ',')}}&nbsp;</b>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        </table>
                    </td>
            </tr>
            </table>

            <table width="100%" class="table table-sm p-0 mb-0" style="margin-top: -3px!important; table-layout: fixed!important; font-size: 10px;" border="1">
                <tr>
                    <td width="33.3333333333%" class="p-0" style="">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>Gross pay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->totalearning,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.3333333333%" class="p-0">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>Deductions</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->totaldeduction,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.3333333333%" class="p-0">
                        <table width="100%" style="">
                            <tr>
                                <td class="text-left" style=""><span><b>NetPay</b></span></td>
                                <td class="text-right" style="padding-top: 7px!important;"><span><b>{{number_format($header->netsalary,2,'.',',')}}</b></span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-sm p-0" style="table-layout: fixed!important; font-size: 10px;">
                <tr>
                    <td class="text-left p-0"><span class="" style="font-size: 7.5px;"><i>This statement constitutes a record and deductions, Please report any discrepancies. Actual payout of salaries is still based on this schedule or release, and does not coincide with issuance of payslip.</i></span></td>
                </tr>
            </table>
        </td>
        <td class="p-0" width=".8%">
            <table width="100%" class="table table-sm" style="table-layout: fixed; padding-left: 5px; padding-right: 5px;">
                <tr>
                    <td style="border: 1px dotted #000; height: 285px!important;"></td>
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
                            <tr>
                                <td class="p-0 text-center" style="font-size: 10px;"><b>{{ date('mdY', strtotime($payrollinfo->dateto)) }}</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 9px;"><b>({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
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
                            <tr>
                                <td class="text-left p-0" style="padding-top: 5px!important"><b>Prepared by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 2px!important; font-size: 8px;">{{$preparedby->firstname}} {{$preparedby->lastname}}</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 7px!important"><b>Checked by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 7px!important; font-size: 8px;">PUNU, NOEL JR., A.</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 2px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 7px!important"><b>Approved by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 2px!important; font-size: 8px;">ADVINCULA, ALEXANDER C.</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 5px!important; border-bottom: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td class="text-left p-0" style="padding-top: 5px!important"><b>Recieved by</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="padding-top: 8px!important; font-size: 8px;">{{$header->lastname}}@if ($header->lastname != null), @endif {{$header->firstname}} {{$header->middlename}}  {{$header->suffix}}</td>
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

