
<style>
    .header{
        width: 100%;
        /* table-layout: fixed; */
        font-family: Arial, Helvetica, sans-serif;
        /* font-size: 15px; */
        /* border: 1px solid black; */
    }
    .paymentstable{
        width: 100%;
        /* table-layout: fixed; */
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        /* border: 1px solid black; */
    }
    .header td {
        font-size: 15px !important;
        /* border: 1px solid black; */
    }
    .ledgerstable{
        width: 100%;
        /* table-layout: fixed; */
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .ledgerstable td, .enrollees th{
        border: 1px solid black;
        padding: 5px;
    }
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
    tbody td {
        font-size: 11px !important;
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
        border-top: 2px solid #007bffa8;
        position: fixed; 
        bottom: -60px; 
        left: 0px; 
        right: 0px;
        height: 100px; 

        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        color: black;
        /* text-align: center; */
        line-height: 20px;
    }
</style>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo[0]->schoolname}}</strong> </td>
        <td style="text-align:right;"><strong>Online Payments Report</strong><br><small>S.Y {{$sy[0]->sydesc}}</small></td>
    </tr>
</table>
<br>
@if($status == 'all')
<span class="header">{{strtoupper($status)}} PAYMENTS</span>
@elseif($status == '0')
<span class="header">PENDING PAYMENTS</span>
@elseif($status == '1')
<span class="header">APPROVED PAYMENTS</span>
@elseif($status == '2')
<span class="header">DISAPPROVED PAYMENTS</span>
@elseif($status == '5')
<span class="header">PAID PAYMENTS</span>
@endif
<br>
<br>
@if(count($studentonlinepayment) > 0)
    <div class="paymentstable">
        @foreach($studentonlinepayment as $onlinepayment)
            <span style="font-size: 12px">
                <strong>{{$onlinepayment->studinfo->lastname}}, {{$onlinepayment->studinfo->firstname}} {{$onlinepayment->studinfo->middlename}} {{$onlinepayment->studinfo->suffix}}</strong>
            </span>
            <br>
    
            <table style="width: 100%" class="ledgerstable">    
                <thead>
                    <tr>
                        <th>AMOUNT</th>
                        <th>BANKNAME</th>
                        <th>TRANSACTION DATE</th>
                        <th>PAYMENT DATE</th>
                        <th>REMARKS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($onlinepayment->paymentinfo as $paymentinfo)
                        <tr>
                            <td>{{$paymentinfo->amount}}</td>
                            <td>{{$paymentinfo->bankName}}</td>
                            <td>{{$paymentinfo->TransDate}}</td>
                            <td>{{$paymentinfo->paymentDate}}</td>
                            <td>{{$paymentinfo->remarks}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        @endforeach
        
    </div>
@endif
<br>
<footer>
<table style="width: 50%">
    <tr style="border: none !important;">
        <td style="border: none !important; width: 20px;">PRINTED BY :</td>
        <td style="border: none !important; width: 20px;border-bottom: 1px solid black;"><center>{{$printedby->firstname}} {{$printedby->middlename}} {{$printedby->lastname}} {{$printedby->suffix}}</center></td>
    </tr>
    <tr style="border: none !important;">
        <td style="border: none !important;"></td>
        <td style="border: none !important;"><center>FINANCE</center></td>
    </tr>
    <tr style="border: none !important;">
        <td style="border: none !important;">DATE & TIME :</td>
        <td style="border: none !important;"><center>{{$printeddatetime}}</center></td>
    </tr>
</table>
</footer>