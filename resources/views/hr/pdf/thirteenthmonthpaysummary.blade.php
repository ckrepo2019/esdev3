
<style>
    *       {
                font-family: Arial, Helvetica, sans-serif;
            }
            
    header  {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;

                /** Extra personal styles **/
                color: black;
                text-align: center;
                line-height: 35px;
            }

    #summarytable 
            {
                width: 100%;
                border: 1px solid black;
                border-collapse: collapse;
                font-size: 12px;
                text-transform: uppercase;
            }

    #summarytable td 
            {
                border: 1px solid black;
            }

    footer  {
                font-size: 10px;
                border-top: 2px solid #ddd;
                position: fixed; 
                bottom: -100px; 
                left: 0px; 
                right: 0px;
                height: 160px; 
                position:absolute;


                /** Extra personal styles **/
                /* background-color: #03a9f4; */
                color: black;
                /* text-align: center; */
                line-height: 10px;
            }
</style>
<table style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 10%;">
                <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="60px" style="display: inline;">
            </th>
            <th style="text-align: left;">
                {{$schoolinfo->schoolname}}
                <br>
                <span style="font-size: 10px;">
                    {{$schoolinfo->district}} | {{$schoolinfo->province}} | {{$schoolinfo->region}}
                </span>
            </th>
        </tr>
    </thead>
</table>
@php
    $overalltotal = 0;   
@endphp
<table id="summarytable">
    <thead>
        <tr>
            <th>Employeename</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employeesthirteentmonthpay as $employee)
            <tr>
                <td style="padding-left: 10px;">
                    {{$employee->employeeinfo->lastname}}, {{$employee->employeeinfo->firstname}} {{$employee->employeeinfo->middlename[0]}}. {{$employee->employeeinfo->suffix}}
                    <br>
                    <small style="color: grey">{{$employee->employeeinfo->designation}}</small>
                </td>
                <td style="padding-left: 10px;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($employee->pay,2,'.',',')}}
                </td>
            </tr>
            @php
                $overalltotal+=$employee->pay;
            @endphp
        @endforeach
    </tbody>
</table>
<footer>
    <table style="width: 100%; text-transform: uppercase; text-align: right !important; font-size: 15px;">
        <tr>
            <th>OVERALL TOTAL: <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($overalltotal,2,'.',',')}}</th>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <table style="width: 100%; text-transform: uppercase; text-align: center;">
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
                {{$currentdate}}
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
</footer>