<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
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
        .p-0 td{
            padding-top: 0!important;
            padding-bottom: 0!important;
            padding-right: 0!important;
            padding-left: 0!important;
        }
        .p-5{
            padding-top: 13px!important;
            padding-bottom: 13px!important;
            padding-right: 2px!important;
            padding-left: 2px!important;
        }
       .pb-0{
            padding-bottom: 0!important;
       }
       .pb-5{
            padding-bottom: 25px!important;
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
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 8pt;
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
            font-size: 9pt!important;
        }
        .smaller-font{
            font-size: 8pt!important;
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
            border: 2px solid rgb(71, 237, 246);
            border-radius: 10px!important;
        }
        .rounded-bordered{
            border: 2px solid black;
            border-radius: 30px!important;
        }
        .title{
            font-size: 12pt!important;
        }
        .subtitle{
            font-size: 11pt!important;
        }
        .subspace{
            margin-top: 7px!important
        }
        .space{
            margin-top: 13px!important
        }
        .spacing{
            margin-top: 25px!important;
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
            margin-left: .15in!important;
        }
        .smallcompressed{
            margin-right: .2in!important;
            margin-left: .2in!important;
        }
        .compressed{
            margin-left: .15in!important;
            margin-right: .15in!important;
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
            /* text-align: center!important; */
        }
        .border-bot{
            border-bottom: 3px solid black;
        }
        .border-top{
            border-top: 3px solid black;
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
            padding-bottom: 20px !important;
            padding-top: 20px !important;
        }
        .m-tb{
            margin-top: 25px!important;
            margin-bottom: 25px!important;
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
            word-spacing: 8px;
        }
        .mr-0{
            margin-right: 0!important;
        }
        .ml-0{
            margin-left: 0!important;
        }
        .yellow-back{
            background-color: yellow;
        }
        .light-green{
            background-color: #92D050;
        }
        .white{
            color: rgb(255, 255, 255);
        }
        .blue-border{
            border-bottom: 2px solid rgb(170, 215, 250);
            border-top: 2px solid rgb(150, 204, 246);
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
        .crvl{
            background-color: rgb(235,241,222);
        }
        .bs{
            background-color: rgb(242,220,219);
        }
        .qrtr{
            background-color: rgb(255,255,204);
        }
        .two{
            background-color: rgb(253,233,217);
        }
        .three{
            background-color: rgb(228,223,236);
        }
        .four{
            background-color: rgb(218,238,243);
        }
        .la{
            background-color: rgb(238,236,225);
        }
        .red{
            background-color: rgb(230,184,183);
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 13in 8.5in; margin: .15in .1in;}
    </style>
</head>
<body>
    @php
    $headers = collect($employees)->pluck('header')->toArray();
    $overallgrosspay = 0;
    @endphp
    <table width="100%" class="table bold text-left">
        <tr>
            <td class="p-0 text-left">{{$schoolinfo[0]->schoolname}}</td>
        </tr>
        <tr>
            <td class="p-0 text-left">{{$schoolinfo[0]->address}}</td>
        </tr>
        <tr>
            <td class="p-0 text-left"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    {{-- <table width="100%" class="table table-bordered bold text-center">
        <tr>
            <td width="1%">S.<br>N<br>o.</td>
            <td width="24%">NAME OF EMPLOYEE</td>
            <td width="6%">Basic Pay</td>
            <td width="6%">Undertime</td>
            <td width="6%">Absent/Late</td>
            <td width="6%">ALLO<br>WANCE</td>
            <td width="6%">COLLEGE<br>EXTRA<br>LOAD</td>
            <td width="8%">FUNCTION<br>RATE/POSITION</td>
            <td width="6%">HONO<br>RARIUM</td>
            <td width="6%">SUMMER LOAD</td>
            <td width="5%">UNDER<br>PAYMENT</td>
            <td width="6%">GROSS</td>
            <td width="5%">SSS<br>Contr'n</td>
            <td width="4%">PHIC<br>Contr'n</td>
            <td width="5%">PAG-IBIG<br>Contr'n</td>
        </tr>

        @if(count($employees)>0)
            @foreach($employees as $key => $employee)
            <tr>
                <td style="text-align: center; padding-top: 6px!important; padding-bottom: 6px!important" >{{$key+1}}</td>
                <td class="text-left">&nbsp;{{$employee->lastname}}, {{$employee->firstname}} @if($employee->middlename != null){{$employee->middlename[0]}}.@endif {{$employee->suffix}}</td>
                <td style="text-align: right;">
                    @if ($employee->salarybasistype == 7)
                        {{$employee->header->dailyrate}}

                    @else
                        {{$employee->amount}}
                    @endif
                </td>
                <td>{{number_format($employee->header->undertimeamount,2,'.',',')}}</td>
                <td>{{number_format($employee->header->lateamount+$employee->header->daysabsentamount,2,'.',',')}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    @if(count($employee->addedparticulars) > 0)
                    @foreach($employee->addedparticulars as $addedParticular)
                        @if($addedParticular->description == 'HONORARIUM')
                            {{ number_format($addedParticular->amount, 2, '.', ',') }}
                        @endif
                    @endforeach
                @endif
                </td>
                <td></td>
                <td></td>
                <td style="text-align: right;">{{number_format($employee->header->totalearning,2,'.',',')}} @php $overallgrosspay +=$employee->header->totalearning; @endphp</td>
                <td style="text-align: right;">
                    @if(collect($employee->particulars)->where('particulartype','1')->where('particularid', 7)->count() >0)
                        {{number_format(collect($employee->particulars)->where('particulartype','1')->where('particularid', 7)->first()->amountpaid,2,'.',',')}}
                    @endif
                </td>
                <td style="text-align: right;">
                    @if(collect($employee->particulars)->where('particulartype','1')->where('particularid', 6)->count() >0)
                        {{number_format(collect($employee->particulars)->where('particulartype','1')->where('particularid', 6)->first()->amountpaid,2,'.',',')}}
                    @endif
                </td>
                <td style="text-align: right;">
                    @if(collect($employee->particulars)->where('particulartype','1')->where('particularid', 5)->count() >0)
                        {{number_format(collect($employee->particulars)->where('particulartype','1')->where('particularid', 5)->first()->amountpaid,2,'.',',')}}
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    <table class="new-page">
        <tr>
            <td></td>
        </tr>
    </table>
    <table width="92%" class="table table-bordered bold text-center">
        <tr>
            <td width="5%">HDMF-Salary Loan</td>
            <td width="5%">SSS LOAN</td>
            <td width="5%">SSS-CALAMITY Loan</td>
            <td width="5%">PAG-IBIG Loan</td>
            <td width="5%">ABSENCES</td>
            <td width="8%">DCC EMPLOYEE LOAN FUND</td>
            <td width="5%">SILANGAN</td>
            <td width="5%">TUITION</td>
            <td width="5%">NETBOOK AND LAPTOP</td>
            <td width="7%">WITHHOLDING TAX</td>
            <td width="5%">CAR LOAN</td>
            <td width="5%">DELP</td>
            <td width="5%">IOU</td>
            <td width="5%">GADGET</td>
            <td width="5%">PERAA LOAN</td>
            <td width="5%">Total<br>Deductions</td>
            <td width="7%">Net pay</td>
        </tr>
        @if(count($employees)>0)
            @foreach($employees as $key => $employee)
            <tr>
                <td></td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'SSS LOAN' || $particular->deductionid == 4)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'SSS CALAMITY LOAN' || $particular->deductionid == 8)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'PAG-IBIG LOAN' || $particular->deductionid == 3)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'PAG-IBIG LOAN' || $particular->deductionid == 3)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td></td>
                <td></td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'TUITION' || $particular->deductionid == 6)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td></td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'WITHOLDING TAX' || $particular->deductionid == 9)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'CAR LOAN' || $particular->deductionid == 10)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'DELP' || $particular->deductionid == 2)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'IOU' || $particular->deductionid == 11)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'GADGET' || $particular->deductionid == 7)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(count($employee->particulars) > 0)
                        @foreach($employee->particulars as $particular)
                            @if($particular->description == 'PERAA' || $particular->deductionid == 5)
                                {{ number_format($particular->amountpaid, 2, '.', ',') }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td style="text-align: right;">{{number_format(collect($employee->particulars)->whereIn('particulartype',[1,2])->sum('amountpaid')+collect($employee->addedparticulars)->where('type',2)->sum('amount'),2,'.',',')}}</td>
                <td style="text-align: right;">{{number_format($employee->header->netsalary,2,'.',',')}}</td>
            </tr>
            @endforeach
        @endif
    </table> --}}
    <table width="100%" class="table table-sm table-bordered bold text-center" style="table-layout: fixed; page-break-after: always;" border="1">
        <tr style="word-wrap: break-word; height: 60px!important">
            @foreach($firstTwentyDescriptions as $key => $filterhead1)
                <td 
                    @if ($key == 0) 
                        style="width: 2% !important;" 
                    @elseif($key == 1)
                        style="width: 20% !important;" 
                    @elseif($filterhead1 == "GROSS")
                        style="width: 10% !important;" 
                    @endif>
                    {{$filterhead1}}
                </td>
            @endforeach
        </tr>

        @if(count($employees)>0)
            @foreach($employees as $key => $employee)
            <tr>
                <td style="text-align: center; padding-top: 6px!important; padding-bottom: 6px!important" >{{$key+1}}</td>
                <td class="text-left" style="text-transform: uppercase">&nbsp;{{$employee->lastname}}, {{$employee->firstname}} @if($employee->middlename != null){{$employee->middlename[0]}}.@endif {{$employee->suffix}}</td>
                
                @php
                // Find the index of "Basic Pay" in the array
                    $startIndex = array_search("Basic Pay", $firstTwentyDescriptions);
                    $empdatas = collect($finaldetails)->where('employeeid', $employee->employeeid)->values();
                    $history = collect($generateddatahistorys)->where('employeeid', $employee->employeeid)->first();
                @endphp
                {{-- @dd($empdatas); --}}
                @foreach(array_slice($firstTwentyDescriptions, $startIndex) as $key => $filterhead1)
                    <td>
                        @foreach($empdatas as $key => $data)
                            @if ($filterhead1 == $data->description)
                                {{$data->amountpaid}}
                            @endif
                        @endforeach

                        @if ($history)
                            @if ($filterhead1 == 'Basic Pay')
                                {{$history->basicsalaryamount}}

                            @elseif ($filterhead1 == 'GROSS')
                                {{$history->totalearning}}

                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        @endif
    </table>
    <table width="100%" class="table bold text-left">
        <tr>
            <td class="p-0 text-left">{{$schoolinfo[0]->schoolname}}</td>
        </tr>
        <tr>
            <td class="p-0 text-left">{{$schoolinfo[0]->address}}</td>
        </tr>
        <tr>
            <td class="p-0 text-left"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table width="" class="table table-sm table-bordered bold text-center" style="word-wrap: break-word;" border="1">
        <tr style="word-wrap: break-word; height: 60px!important">
            @foreach($remainingDescriptions as $key => $filterhead2)
                <td style="width: 100px!important;">
                    {{$filterhead2}}
                </td>
            @endforeach
        </tr>
        @if(count($employees)>0)
            @foreach($employees as $key => $employee)
            <tr>
                <td style="text-align: center; padding-top: 6px!important; padding-bottom: 6px!important" >{{$key+1}}</td>
                @php
                // Find the index of "Basic Pay" in the array
                    $empdatas = collect($finaldetails)->where('employeeid', $employee->employeeid)->values();
                    $history = collect($generateddatahistorys)->where('employeeid', $employee->employeeid)->first();
                @endphp
                @foreach($remainingDescriptions as $key => $filterhead2)
                <td>
                    @foreach($empdatas as $key => $data)
                        @if ($filterhead2 == $data->description)
                            {{$data->amountpaid}}
                        @endif
                    @endforeach

                    @if ($history)
                        @if ($filterhead2 == 'Total Deductions')
                            {{$history->totaldeduction}}

                        @elseif ($filterhead2 == 'Undertime')
                            {{$history->undertimeamount}}

                        @elseif ($filterhead2 == 'Absent/Late')
                            {{$history->lateamount + $history->daysabsentamount}}

                        @elseif ($filterhead2 == 'Net Pay')
                            {{$history->netsalary}}

                        @endif
                    @endif
                </td>
            @endforeach
            </tr>
        @endforeach
    @endif
    </table>
</body>
</html>