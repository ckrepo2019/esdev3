
<style>
    .header{
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
        font-size: 10px !important;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .ledgerstable td{
        font-size: 10px !important;
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
        <td style="text-align:right;"><strong>Balance Forwarding Report</strong><br><small>S.Y {{$sy[0]->sydesc}}</small></td>
    </tr>
</table>
<br>

@if(count($balforwardlist) > 0)
<table style="width: 100%" class="header">
    <thead>
        <tr>
            <th style="text-align: left;">Name of Students</th> 
            <th></th> 
        </tr>
    </thead>
    <tbody class="studentscontainer text-uppercase">
            @foreach($balforwardlist as $list)
                <tr>
                    <td style="font-size: 11px !important;">
                        <b>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</b><br>{{$list->levelname}}
                    </td>
                    <td>
                        <table style="width: 100%" class="ledgerstable">    
                            <thead>
                                <tr>
                                    <th>PARTICULARS</th>
                                    <th>AMOUNT</th>
                                    <th>PAYMENT</th>
                                    <th>BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$list->particulars}}</td>
                                    <td>{{number_format($list->amount, 2)}}</td>
                                    <td>{{number_format($list->amountpay, 2)}}</td>
                                    <td>{{number_format($list->balance, 2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
    </tbody>
</table>
@endif
<br>
<footer>
<table style="width: 50%">
    <tr style="border: none !important;">
        <td style="border: none !important; width: 20px;">PRINTED BY :</td>
        <td style="border: none !important; width: 20px;border-bottom: 1px solid black;">
            <center>{{auth()->user()->name}}</center>
        </td>
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