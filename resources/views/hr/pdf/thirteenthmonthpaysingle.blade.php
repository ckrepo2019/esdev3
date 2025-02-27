
<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
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
        font-size: 10px;
        border-top: 2px solid #ddd;
        position: fixed; 
        bottom: -120px; 
        left: 0px; 
        right: 0px;
        height: 150px; 

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
<table style="width: 100%; font-size: 12px;">
    <thead>
        <tr>
            <td style="width: 20%;">Employee</td>
            <td>: <strong>
                {{$employeeinfo->lastname ?? ''}}, 
                {{$employeeinfo->firstname ?? ''}} 
                {{isset($employeeinfo->middlename[0]) ? $employeeinfo->middlename[0] . '.' : ''}} 
                {{$employeeinfo->suffix ?? ''}}
            </strong></td>
        </tr>
        <tr>
            <td>Designation</td>
            <td>: {{$employeeinfo->designation}}</td>
        </tr>
        <tr>
            <td>Department</td>
            <td>: {{$employeeinfo->department}}</td>
        </tr>
    </thead>
</table>
<br>
<table style="width: 100%; font-size: 12px;">
    <thead style="border: 1px solid black; border-bottom: hidden;">
        <tr>
            <th style="border-right: 1px solid black">Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody style="border: 1px solid black;">
        <tr>
            <td style="border-right: 1px solid black; padding-left: 10px;"> Thirteenth Month Pay</td>
            <td> <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($amountpay, 2, '.', ',')}}</td>
        </tr>
    </tbody>
</table>
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
<br>
<hr style="border: 1px dashed gray"/>
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
<table style="width: 100%; font-size: 12px;">
    <thead>
        <tr>
            <td style="width: 20%;">Employee</td>
            <td>: <strong>{{$employeeinfo->lastname}}, {{$employeeinfo->firstname}} {{$employeeinfo->middlename[0]}}. {{$employeeinfo->suffix}}</strong></td>
        </tr>
        <tr>
            <td>Designation</td>
            <td>: {{$employeeinfo->designation}}</td>
        </tr>
        <tr>
            <td>Department</td>
            <td>: {{$employeeinfo->department}}</td>
        </tr>
    </thead>
</table>
<br>
<table style="width: 100%; font-size: 12px;">
    <thead style="border: 1px solid black; border-bottom: hidden;">
        <tr>
            <th style="border-right: 1px solid black">Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody style="border: 1px solid black;">
        <tr>
            <td style="border-right: 1px solid black; padding-left: 10px;"> Thirteenth Month Pay</td>
            <td> <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($amountpay, 2, '.', ',')}}</td>
        </tr>
    </tbody>
</table>
<br>
<footer>
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