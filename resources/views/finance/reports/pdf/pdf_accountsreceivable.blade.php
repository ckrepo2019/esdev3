
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>

<style>
    *{
        
        font-family: Arial, Helvetica, sans-serif;
    }
    .header{
        width: 100%;
        /* table-layout: fixed; */
        font-family: Arial, Helvetica, sans-serif;
        /* font-size: 15px; */
        /* border: 1px solid black; */
    }
    .header td {
        font-size: 15px !important;
        /* border: 1px solid black; */
    }
    .studentstable{
        width: 100%;
        /* table-layout: fixed; */
        font-size: 11px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .studentstable td, .studentstable th{
        font-size: 9px !important;
        border: 1px solid black;
        /* border-bottom: none !important; */
        /* border-top: none !important; */
        padding: 5px;
    }
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
    #overalltotalassessment, #overalltotaldiscount,#overalltotalnetassessed,#overalltotalpayment,#overalltotalbalance{
        font-size: 9px !important;
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
    .filter{
        width: 100%;
        font-size: 11px !important;
        table-layout: fixed;
        font-weight: bold;
        
    }
</style>

</head>
<body>
<script type="text/php">
    if (isset($pdf)) {
        $x = 34;
        $y = 760;
        $text = "Page {PAGE_NUM} of {PAGE_COUNT} pages";
        $font = null;
        $size = 7;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color);
    }
</script>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo->schoolname}}</strong> <br/> S.Y {{$selectedschoolyear}}</td>
    </tr>
</table>
<table class="filter">
    <tr>
        <td>
            @if($selecteddaterange != null)
                AS OF : {{strtoupper($selecteddaterange)}}
                <br/>
            @endif
            @if($selecteddepartment != null)
                DEPARTMENT : {{$selecteddepartment}}
                <br/>
            @endif
            @if($selectedgradelevel != null)
                GRADE LEVEL : {{$selectedgradelevel}}
                <br/>
            @endif
            
        </td>
        <td>
            @if($selectedsemester != null)
                SEMESTER : {{strtoupper($selectedsemester)}}
                <br/>
            @endif
            @if($selectedgrantee != null)
                GRANTEE : {{$selectedgrantee}}
                <br/>
            @endif
            @if($selectedmode != null)
                MODE OF LEARNING : {{$selectedmode}}
                <br/>
            @endif
        </td>
    </tr>
</table>
@php
    $count = 1;
@endphp
<table class="studentstable">
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Student Name</th>
            <th>Department</th>
            <th>Level</th>
            <th>Units</th>
            <th>Total<br/>Assessment</th>
            <th>Discount</th>
            <th>Net<br/>Assessed</th>
            <th>Total<br/>Payment</th>
            <th>Balance</th>
        </tr>
    </thead>
    {{-- <body> --}}
        @if(count($students)>0)
            @foreach($students as $student)
                <tr >
                    <td>{{$count}}</td>
                    <td class="sid">{{$student->sid}}</td>
                    <td class="sname">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                    <td class="sacadprogcode">{{$student->acadprogcode}}</td>
                    <td class="slevelname">{{$student->levelname}}</td>
                    <td class="sunits">{{$student->units}}</td>
                    <td class="sta"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($student->totalassessment,2,'.',',')}}</td>
                    <td class="sd"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($student->discount,2,'.',',')}}</td>
                    <td class="sna"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($student->netassessed,2,'.',',')}}</td>
                    <td class="stp"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($student->totalpayment,2,'.',',')}}</td>
                    <td class="sb"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($student->balance,2,'.',',')}}</td>
                </tr>
                @php
                    $count+=1;
                @endphp
            @endforeach
        @endif
    {{-- </tbody> --}}
    {{-- <tfoot> --}}
        <tr>
            <th colspan="6" style="text-align:right">TOTAL</th>
            <th id="overalltotalassessment"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($overalltotalassessment,2,'.',',')}}</th>
            <th id="overalltotaldiscount"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($overalltotaldiscount,2,'.',',')}}</th>
            <th id="overalltotalnetassessed"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($overalltotalnetassessed,2,'.',',')}}</th>
            <th id="overalltotalpayment"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($overalltotalpayment,2,'.',',')}}</th>
            <th id="overalltotalbalance"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{number_format($overalltotalbalance,2,'.',',')}}</th>
        </tr>
    {{-- </tfoot> --}}

</table>
</body>
</html>

{{-- <footer>
    
</footer> --}}