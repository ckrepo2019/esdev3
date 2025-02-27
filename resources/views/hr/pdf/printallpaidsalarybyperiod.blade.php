
<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
       }
    tr{
        padding: 0px;
        font-size: 12px;
        
    }
    td{
        padding: 2px;
        border: 1px solid black !important;
    }
    .header, .header td{
        width: 100%;
        table-layout: fixed;
        border: hidden !important;
        font-size: 15px !important;
        /* border: 1px solid black; */
    }
    table{
        border-collapse: collapse
    }
    header {
        position: fixed;
        top: -60px;
        left: 0px;
        right: 0px;
        height: 50px;

        /** Extra personal styles **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 35px;
    }

    footer {
        border-top: 2px solid #ddd;
        position: fixed; 
        bottom: -60px; 
        left: 0px; 
        right: 0px;
        height: 120px; 
        display: block;

        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        color: black;
        /* text-align: center; */
        line-height: 10px;
    }
</style>
<div style="width: 100%;border-top: 2px solid #ddd; border-bottom: 2px solid #ddd">
    <table class="header">
        <tr>
            <td style="width: 30%;" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
            <td>
                <strong>
                    {{$schoolinfo->schoolname}}<br>
                    {{-- {{$schoolinfo->district}} | {{$schoolinfo->division}} --}}
                </strong>
            </td>
            <td style="text-align:right;"><strong>Payroll Summary</strong><br></td>
        </tr>
    </table>
{{-- {{$schoolinfo->schoolname}} --}}
{{-- <br>
{{$schoolinfo->address}}
<br> --}}
<small>Payroll Period :  <strong>{{$getdaterange[0]->datefrom}} to {{$getdaterange[0]->dateto}}</strong></small>
{{-- <p>Payroll Period: <strong>{{$getdaterange[0]->datefrom}} to {{$getdaterange[0]->dateto}}</strong></p> --}}
</div>
<br>
@php 
    $countnum = 1;
    $totalpayroll = 0;
@endphp
{{-- @if(strtolower($salarytype) != 'all')
Salary type: <strong>{{$salarytype}}</strong>
<br>
<br>
@endif --}}
<table style="width:100%; border: 1px solid; page-break-inside: avoid ">
    <thead style="text-align: none !important; text-align: center;">
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Designation</th>
            <th>Basic Pay</th>
            <th>Total Earnings</th>
            <th>Total Deductins</th>
            <th>Net Salary</th>
        </tr>
    </thead>
    <tbody style="text-transform: uppercase;">
        @foreach($allhistory as $employee)
            <tr>
                <td>{{$countnum}}</td>
                <td>{{$employee->historyinfo->lastname.', '.$employee->historyinfo->firstname.' '.$employee->historyinfo->middlename[0].' '.$employee->historyinfo->suffix}}.</td>
                <td style="text-align: center;">{{$employee->historyinfo->utype}}</td>
                <td style="text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employee->historyinfo->basicpay,2,'.',',')}}</td>
                <td style="text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employee->historyinfo->totalearnings,2,'.',',')}}</td>
                <td style="text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employee->historyinfo->totaldeductions,2,'.',',')}}</td>
                <td style="text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span class="salary">{{number_format($employee->historyinfo->netpay,2,'.',',')}}</span></td>
            </tr>
            @php
                $countnum+=1;
                if($employee->historyinfo->netpay>=0)
                {
                $totalpayroll+=$employee->historyinfo->netpay;
                }
            @endphp
        @endforeach
    </tbody>
</table>
<footer>
    <table style="width: 100%; float: left; text-transform: uppercase; font-size: 11px !important;">
        <tr style="border: none !important;">
            <td style="border: none !important;">PREPARED BY :</td>
            <td style="border: none !important; width: 5%;"></td>
            <td style="border: none !important;">APPROVED BY :</td>
            <td style="border: none !important; width: 5%;"></td>
            <td style="border: none !important; text-align: right;">OVERALL TOTAL : </td>
            <td style="border: none !important;">
                <center>
                    <strong>
                        <h2>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($totalpayroll, 2, '.', ',')}}</span> 
                        </h2>
                    </strong>
                </center>
            </td>
        </tr>
        <tr style="border: none !important;">
            <td style="border: none !important;border-bottom: 1px solid black;">
                <center>
                    {{$preparedby->firstname}} {{$preparedby->middlename[0].'.'}} {{$preparedby->lastname}} {{$preparedby->suffix}}
                </center>
            </td>
            <td style="border: none !important;"></td>
            <td style="border: none !important;border-bottom: 1px solid black;">&nbsp;</td>
        </tr>
        {{-- <tr style="border: none !important;">
            <td style="border: none !important;">APPROVED BY :</td>
            <td style="border: none !important;border-bottom: 1px solid black;"></td>
        </tr> --}}
        {{-- <tr style="border: none !important;">
            <td style="border: none !important;"></td>
            <td style="border: none !important;"><center>FINANCE</center></td>
        </tr> --}}
    </table>
    {{-- <table style="width: 45%; table-layout: fixed; float: right; text-align: right;">
        <tr style="border: none !important;">
            <td style="border: none !important; width: 50%;">OVERALL TOTAL:</td>
            <td style="border: none !important;" >
                <center>
                    <strong>
                        <h2>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($totalpayroll, 2, '.', ',')}}</span> 
                        </h2>
                    </strong>
                </center>
            </td>
        </tr>
        <tr style="border: none !important;">
            <td style="border: none !important;">DATE & TIME :</td>
            <td style="border: none !important;"><center>{{$currentdateandtime}}</center></td>
        </tr>
    </table> --}}
</footer>