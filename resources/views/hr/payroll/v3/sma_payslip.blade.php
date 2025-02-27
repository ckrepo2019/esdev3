<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            background-color: transparent;
            vertical-align: middle;
            table-layout: fixed;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            /* padding-top: 5px; */
            padding-bottom: 2px;
            /* vertical-align: top; */
        }
        
        .table-bordered {
            border: 1px solid #FFFFFF;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #FFFFFF;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .table-sm td, .table-sm th {
            padding: 5px!important;
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
            padding-top: 0!important;
            padding-bottom: 0!important;
            padding-right: 0!important;
            padding-left: 0!important;
        }
        .p-5{
            padding-top: 5px!important;
            padding-bottom: 5px!important;
            padding-right: 2px!important;
            padding-left: 2px!important;
        }
       .pb-0{
            padding-bottom: 0!important;
       }
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }
        .mt-0{
            margin-top: 0!important;
        }
        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif
        }
        p{
            margin: 0;
        }
        .align-top{
            vertical-align: top!important;
        }
        .align-middle{
            vertical-align: middle !important;    
        }
        .align-bottom{
            vertical-align: bottom!important;
        }
         
        .grades td{
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 9pt;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }
        .bold{
            font-weight: bold!important;
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
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
        }
        .small-font{
            font-size: 10pt!important;
        }
        .smaller-font{
            font-size: 9pt!important;
        }
        .smallest-font{
            font-size: 7pt!important;
        }
        h4{
            margin-bottom: 10px;
        }
        .suptitle{
            font-size: 15pt;
        }
        .round-bordered{
            border: 2px solid black;
            border-radius: 10px!important;
        }
        .rounded-bordered{
            border: 2px solid black;
            border-radius: 30px!important;
        }
        .title{
            font-size: 13pt!important;
        }
        .subtitle{
            font-size: 11pt!important;
        }
        .space{
            margin-top: 10px!important
        }
        .spacing{
            margin-top: 30px!important;
        }
        .superspace{
            margin-top: .8in!important;
        }
        .underline{
            border-bottom: 1px solid black;
        }
        .timesnew{
            font-family: 'Times New Roman', Times, serif;
        }
        #space{
            margin-top: .2in!important
        }
        .right-margin{
            margin-right: .5in!important;
        }
        .left-margin{
            margin-left: .3in!important;
        }
        .smallcompressed{
            margin-right: .2in!important;
            margin-left: .2in!important;
        }
        .compressed{
            margin-right: .5in!important;
            margin-left: .3in!important;
        }
        .supcompressed{
            margin-right: 1.3in!important;
            margin-left: 1.3in!important;
        }
        .border-top-bottom{
            border-top: 1px solid black;
            border-bottom: 1px solid black;

        }
        .border{
            border: 1px solid black;
        }
        .indent{
            text-indent: .5in;
        }
        .small-indent{
            text-indent: .4in
        }
        .new-page{
            page-break-after: always;
        }
        .padding{
            padding-bottom: 12px !important;
            padding-top: 12px !important;
        }
        .no-border{
            border-top: 1px solid white!important;
            border-bottom: 1px solid white!important;
        }
        .no-right{
            border-right: 1px solid white!important;
        }
        .no-left{
            border-left: 1px solid white !important;
        }
        .no-top{
            border-top: 1px solid white!important;
        }
        .no-bottom{
            border-bottom: 1px solid white!important;
        }
        .relative{
            position: relative;
        }
        .absolute{
            position: absolute;
            top: 11.5in;
        }
        .word-space{
            word-spacing: 11px;
            letter-spacing: 1px;
        }
        .mr-0{
            margin-right: 0!important;
        }
        .ml-0{
            margin-left: 0!important;
        }
        .orange-back{
            background-color: rgb(255, 200, 105);
        }
        .oranger-back{
            background-color: rgb(255, 165, 10);
        }
        .blue{
            color: rgb(7, 56, 93);
        }
        .blue-border{
            border-bottom: 2px solid rgb(10, 79, 131);
        }
        .uppercase{
            text-transform: uppercase!important;
        }
        .justify{
            text-align: justify!important;
        }
        .gigaspace{
            margin-top: 1.7in;
        }
        .paragraph{
            font-size: 8.5pt;
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 11in 8.5in; margin: .1in .1in;}
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td width="50%">
                <table width="100%" class="text-center smaller-font space">
                    <tr>
                        <td width="100%" class="bold">STELLA MATUTINA ACADEMY OF BUKIDNON, INC.</td>
                    </tr>
                    <tr>
                        <td>Rizal St. West Kibawe, Kibawe, Bukidnon.</td>
                    </tr>
                </table>
                <table width="100%" class="oranger-back text-center smaller-font spacing">
                    <tr>
                        <td width="100%" class="bold">PAYSLIP</td>
                    </tr>
                </table>
                <table width="100%" class="bold smaller-font">
                    <tr>
                        <td>EMPLOYEE NAME : &nbsp;&nbsp;<b><span style="text-transform: uppercase;">{{$header->lastname}}@if ($header->lastname != null), @endif {{$header->firstname}} {{$header->middlename}}  {{$header->suffix}}</b></span> </td>
                    </tr>
                    <tr>
                        <td>DATE :<b>&nbsp;&nbsp;({{date('m/d/Y', strtotime($payrollinfo->datefrom))}} - {{date('m/d/Y', strtotime($payrollinfo->dateto))}})</b></td>
                    </tr>
                    <tr>
                        <td>SIGNATURE :</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="50%" class="align-top">
                            <table width="100%" class="smaller-font orange-back table-bordered" style="font-size: 11.5px!important;">
                                <tr class="oranger-back">
                                    <td width="60%" class="bold">SALARY</td>
                                    <td width="40%" class="bold">AMOUNT</td>
                                </tr>
								<tr>
									<td class="text-left p-0" width="60%">&nbsp;<span><b>Basic Pay</b></span></td>
									
									<td class="text-left" width="40%">{{number_format($header->basicsalaryamount,2,'.',',')}}</td>
								</tr>
								@if(count($holidays)>0)
									@foreach($holidays as $holiday)
										@if($holiday->particulartype == '8')
											<tr>
												<td class="text-left p-0" style="padding-left: 3px!important;"><span><b>{{ucwords($holiday->description)}}</b></span></td>
										
												<td class="text-left" style="vertical-align: middle!important;">
													{{number_format($holiday->amountpaid,2,'.',',')}}
												</td>
											</tr>
										@endif
									@endforeach
								@endif
								@if(count($leavedetails)>0)
									@foreach($leavedetails as $leavedetail)
										<tr>
											<td class="text-left p-0"><span>&nbsp;<b>{{$leavedetail->leave_type}} {{date('m/d/Y',strtotime($leavedetail->ldate))}}</b></span></td>
											
											<td class="text-left">{{number_format($leavedetail->amount,2,'.',',')}}</td>
										</tr>
									@endforeach
								@endif
								@foreach($particulars as $particular)
									@if($particular->particulartype == '3')
										<tr>
											<td class="text-left p-0"><span>&nbsp;<b>{{ucwords($particular->description)}}</b></span></td>
											
											<td class="text-left">
												{{number_format($particular->amountpaid,2,'.',',')}};
											</td>
										</tr>
									@endif
								@endforeach
								
                                @foreach($addedparticulars as $addedparticular)
                                    @if($addedparticular->type == '1')
                                        <tr>
                                            <td class="text-left p-0"><span>&nbsp;<b>{{$addedparticular->description}}</b></span></td>
                                        
                                            <td class="text-left">
                                                {{number_format($addedparticular->amount,2,'.',',')}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
									
                                {{--<tr>
                                    <td class="smallest-font">BASIC SALARY</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">ADVISORY/<br>POSITION PAY</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">EXTRA LOAD</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font bold">TOTAL PAY</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">LESS DEDUCTION</td>
                                    <td></td>
                                </tr>--}}
                                <tr class="oranger-back">
                                    <td class="bold">TOTAL NET PAY</td>
                                    <td>{{number_format($header->netsalary,2,'.',',')}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%">
                            <table width="100%" class="smaller-font orange-back table-bordered" style="font-size: 11.5px!important;">
                                <tr class="oranger-back">
                                    <td width="60%" class="bold">DEDUCTIONS</td>
                                    <td width="40%" class="bold">AMOUNT</td>
                                </tr>
                                {{--<tr>
                                    <td class="smallest-font">SSS PREM</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">SSSLOAN</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">PAGIBIG PREM</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font bold">PAGIBIG LOAN</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">PHILHEALTH</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">CASH ADVANCE</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">BDAY</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">CANTEEN</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">MORTUARY</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">UNIFORM</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="smallest-font">ABSENT</td>
                                    <td></td>
                                </tr>--}}
								 @if($payrolldetail->daysabsentamount > 0)
								<tr>
									<td class="text-left p-0">&nbsp;<b>Absent</b></td>
									{{--<td class="p-0"><b>{{$payrolldetail->absentdays > 0 ? $payrolldetail->absentdays.'days' : ''}}</b></td>--}}
									<td class="text-left">
										{{number_format($payrolldetail->daysabsentamount,2,'.',',')}}
									</td>
								</tr>
								@endif
								@if($payrolldetail->lateamount > 0)
								<tr>
									<td class="text-left p-0">&nbsp;<b>Late</b></td>
									{{--<td class="p-0"><b>{{$payrolldetail->lateminutes > 0 ? $payrolldetail->lateminutes.' min(s)' : ''}}</b></td>--}}
									<td class="text-left">
										{{number_format($payrolldetail->lateamount,2,'.',',')}}
									</td>
								</tr>
								@endif
								@if($payrolldetail->undertimeamount > 0)
								<tr>
									<td class="text-left p-0">&nbsp;<b>Undertime</b></td>
									{{--<td class="p-0"><b>{{$payrolldetail->undertimeminutes > 0 ? $payrolldetail->undertimeminutes.' min(s)' : ''}}</b></td>--}}
									<td class="text-left">
										{{number_format($payrolldetail->undertimeamount,2,'.',',')}}
									</td>
								</tr>
								@endif
								@foreach($particulars as $particular)
									@if($particular->particulartype == '1' && $particular->amountpaid > 0)
										<tr>
											<td class="text-left p-0">&nbsp;<b>{{$particular->description}}</b>
											</td>
											<td class="text-left">
												{{number_format($particular->amountpaid,2,'.',',')}}
											</td>
										</tr>
									@endif
								@endforeach
								@foreach($particulars as $particular)
									@if($particular->particulartype == '2' && $particular->amountpaid > 0)
										<tr>
											<td class="text-left p-0">&nbsp;<b>{{$particular->description}}</b>
											</td>
											<td class="text-left">
												{{number_format($particular->amountpaid,2,'.',',')}}
											</td>
										</tr>
									@endif
								@endforeach

								
                                @foreach($addedparticulars as $addedparticular)
                                    @if($addedparticular->type == '2')
                                        <tr>
                                            <td class="text-left p-0"><span>&nbsp;<b>{{$addedparticular->description}}</b></span></td>
                                        
                                            <td class="text-left">
                                                {{number_format($addedparticular->amount,2,'.',',')}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
								
                                <tr class="oranger-back">
                                    <td class="bold">TOTAL DEDUCTIONS</td>
                                    <td>{{number_format($header->totaldeduction,2,'.',',')}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="smaller-font spacing">
                    <tr>
                        <td width="50%" class="text-right bold">PREPARED BY:</td>
                        <td width="50%" class="bold text-center"><u>SR. VIRGINIA A. LOQUIAS, MQM</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">FINANCE OFFICER</td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
    </table>
</body>
</html>