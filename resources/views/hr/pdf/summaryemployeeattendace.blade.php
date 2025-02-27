
<style>
* {
    font-family: Arial, Helvetica, sans-serif;
   }
    .header, .header td{
        width: 100%;
        table-layout: fixed;
        border: hidden !important;
        /* border: 1px solid black; */
    }
    tr{
        padding: 0px;
        font-size: 12px;
        
    }
    td{
        padding: 2px;
        border: 1px solid black !important;
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
        height: 100px; 
        font-size: 11px !important;
        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        color: black;
        /* text-align: center; */
        line-height: 20px;
    }
</style>

<table class="header">
    <tr>
        <td style="width: 30%;" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo->schoolname}}</strong> </td>
        <td style="text-align:right;"><strong>Attendance Report</strong><br><small>As of :  <strong>{{$selecteddate}}</strong></small></td>
    </tr>
</table>
<br>
@if(count($present) > 0)
    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
        <thead style="text-align: center;">
            <tr>
                <th colspan="2">PRESENT</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Designation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($present as $presentemployee)
                <tr>
                    <td>{{$presentemployee->firstname}} {{$presentemployee->middlename[0].'.'}} {{$presentemployee->lastname}} {{$presentemployee->suffix}}</td>
                    <td style="text-align: center;">{{$presentemployee->designation}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
@endif
@if(count($tardy) > 0)
    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
        <thead style="text-align: center;">
            <tr>
                <th colspan="2">TARDY</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Designation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tardy as $tardyemployee)
                <tr>
                    <td>{{$tardyemployee->firstname}} {{$tardyemployee->middlename[0].'.'}} {{$tardyemployee->lastname}} {{$tardyemployee->suffix}}</td>
                    <td style="text-align: center;">{{$tardyemployee->designation}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
@endif
@if(count($absent) > 0)
    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
        <thead style="text-align: center;">
            <tr>
                <th colspan="2">ABSENT</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Designation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absent as $absentemployee)
                <tr>
                    <td>{{$absentemployee->firstname}} {{$absentemployee->middlename[0].'.'}} {{$absentemployee->lastname}} {{$absentemployee->suffix}}</td>
                    <td style="text-align: center;">{{$absentemployee->designation}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
<footer>
<table style="width: 50%">
    <tr style="border: none !important;">
        <td style="border: none !important; width: 20px;">PREPARED BY :</td>
        <td style="border: none !important; width: 50px;border-bottom: 1px solid black;"><center>{{$preparedby->firstname}} {{$preparedby->middlename[0].'.'}} {{$preparedby->lastname}} {{$preparedby->suffix}}</center></td>
    </tr>
    <tr style="border: none !important;">
        <td style="border: none !important;"></td>
        <td style="border: none !important;"><center>HR</center></td>
    </tr>
    <tr style="border: none !important;">
        <td style="border: none !important;">DATE & TIME :</td>
        <td style="border: none !important;"><center> {{$dateprepared}}</center></td>
    </tr>
</table>
</footer>