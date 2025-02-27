
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
        font-size: 14px !important;
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
<div style="width: 4.0in !important;">
    <table class="header">
        <tr>
            {{-- <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td> --}}
            <td style="text-align: center;">
                <strong>{{$schoolinfo->schoolname}}</strong> <br>
                <strong>{{$schoolinfo->address}}</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">
                Reminder Slip
            </td>
        </tr>
    </table>

    <p>Sir/Madam:</p>
    <p style="text-indent: 50px;"> 
        This is to remind you that the accoutns of {{$name}} is now due. The account's outstanding balance as of {{date_format(date_create($duedate), 'F, d, Y')}}:
    </p>

    <br>
    <table>
        @foreach($assessment as $a)
            <tr>
                <td style="width: 250px">{{$a->particulars}}</td>
                <td style="text-align: right;">{{$a->amount}}</td>
            </tr>
        @endforeach

        <tr>
            <td style="font-weight: bold;">Total Assessment</td>
            <td style="font-weight: bold; text-align: right;">{{$totalassessment}}</td>
        </tr>
    </table>

    <br>

    <table>
        @foreach($oth as $a)
            <tr>
                <td style="width: 250px">{{$a->particulars}}</td>
                <td style="text-align: right;">{{$a->amount}}</td>
            </tr>
        @endforeach

        <tr>
            <td style="font-weight: bold;">Total Assessment</td>
            <td style="font-weight: bold; text-align: right;">{{$totaloth}}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: right;">TOTAL AMOUNT DUE: {{$totaldue}}</td>
        </tr>
    </table>

    <br>
    <br>
    <br>

    <table>
        <tr>
            <td>Prepared by:</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; border-bottom: solid; width: 200px;">
                {{auth()->user()->name}}
            </td>

        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;">
                Cashier
            </td>
            
        </tr>
    </table>
</div>


{{-- <footer>
<table style="width: 50%">
    <tr style="border: none !important;">
        <td style="border: none !important; width: 20px;">PRINTED BY :</td>
        <td style="border: none !important; width: 20px;border-bottom: 1px solid black;"><center>{{$printedby->firstname}} {{$printedby->middlename[0].'.'}} {{$printedby->lastname}} {{$printedby->suffix}}</center></td>
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
</footer> --}}