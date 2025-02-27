

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
            table-layout: fixed;
            font-family: Arial, Helvetica, sans-serif;
            /* border: 1px solid black; */
        }
        .header td {
            font-size: 15px !important;
            /* border: 1px solid black; */
        }
        .studentstable{
            width: 100%;
            /* table-layout: fixed; */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            border: 1px solid black;
            border-collapse: collapse;
        }
        .studentstable td, .enrollees th{
            border: 1px solid black;
            padding: 5px;
            text-align: center;
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
        footer {
                    position: fixed; 
                    bottom: 0cm; 
                    left: 0cm; 
                    right: 0cm;
                    height: 2cm;
                }

    </style>
</head>
<body>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 34;
            $y = 810;
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
        <td>
            <strong>{{$schoolinfo->schoolname}}</strong>
            <br/>
            <small style="font-size: 10px !important;">{{$schoolinfo->address}}</small>
        </td>
        <td style="text-align:right;">
            <strong>Technical-Vocational-Livelihood</strong>
            <br>
            <small style="font-size: 11px !important;"><strong>{{count($data)}}</strong> ENROLLED STUDENTS</small>
        </td>
    </tr>
</table>
<br/>

@if($coursename != "")
<span style="font-size: 12px;text-transform: uppercase">
    <strong>
        Course: {{$coursename}}
        @if($batch != "")
            <br/>
        Batch: {{$batch}}
        @endif
        
        @endif
    </strong>
</span>
@php
    $count = 1;   
@endphp
<table class="studentstable">
    <thead>
        <tr>
            <td style="width: 3%;">No.</td>
            <td style="width: 10%;">SID</td>
            <td style="width: 25%;">NAME</td>
            <td style="width: 10%;">GENDER</td>
            <td style="width: 30%;">COURSE</td>
            <td style="width: 20%;">BATCH</td>
            <td>STATUS</td>
        </tr>
    </thead>
    <tbody>
        @if(count($data)>0) 
            @foreach($data as $student)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$student->sid}}</td>
                    <td style="text-align: left !important;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                    <td>{{$student->gender}}</td>
                    <td>{{$student->coursename}}</td>
                    <td>{{$student->startdate}} {{$student->enddate}}</td>
                    <td>
                        @if($student->status == 1)
                            ENROLLED
                        @endif
                    </td>
                </tr>
                @php
                    $count+=1;
                @endphp
            @endforeach
        @endif
    </tbody>
</table>
</body>
</html>