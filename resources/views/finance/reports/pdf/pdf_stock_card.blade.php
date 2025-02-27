
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
        <td width="15%" rowspan="2"><img src="/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo[0]->schoolname}}</strong> </td>
        <td style="text-align:right;"><strong>Stock Card Report</strong><br><small>S.Y {{$sy[0]->sydesc}}</small></td>
    </tr>
</table>
<br>

<br>
<br>
    <div class="paymentstable">
        <table style="width: 100%" class="ledgerstable">    
            <thead>
                <tr>
                    <th width="5%"> </th>
                    <th width="20%" class="text-center">Item</th>
                    <th width="5%"> Stock In</th>
                    <th width="5%">Stock Out</th>
                    <th width="5%">Initial Onhand</th>
                    <th width="5%">Onhand</th>
                    <th width="20%">Department</th>
                    <th width="20%" class="text-center">Remarks</th>
                    <th width="20%" class="text-center">Transacted by</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->stock_in }}</td>
                        <td>{{ $item->stock_out }}</td>
                        <td>{{ $item->initial_onhand }}</td>
                        <td>{{ $item->onhand }}</td>
                        <td>{{ $item->deparment_name }}</td>
                        <td>{{ $item->remarks }}</td>
                        <td>{{ $item->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<br>
<div style="bottom: -38px !important;">
<table style="width: 50%;" >
    <tr style="border: none !important;">
        <td style="border: none !important; width: 20px;">PRINTED BY :</td>
        <td style="border: none !important; width: 20px;border-bottom: 1px solid black;"><center>{{$printedby->firstname}}
            @if(isset($printedby->middlename))

                {{$printedby->middlename[0].'.'}} 

            @endif
            {{$printedby->lastname}} 
            {{$printedby->suffix}}</center></td>
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
</div>

<script>
    function triggerPrint() {
        window.print();
    }

    triggerPrint();

     // Function to close the page
    function closePage() {
        window.close();
    }

    // Event listener for close button or any element indicating close action
    window.addEventListener('afterprint', function() {
        console.log('After print dialog is closed');
        closePage(); // Close the window when the print dialog is closed (e.g., if the user cancels printing)
    });


</script>