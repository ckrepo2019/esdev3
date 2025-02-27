
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
<style>
    * { font-family: Arial, Helvetica, sans-serif; }
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
</style>

</head>
<body>
    
    @php
        $selectedacadprog = DB::table('academicprogram')
            ->where('id', $acadprogid)
            ->first();
            
        if($selectedacadprog)
        {
            $selectedacadprog = $selectedacadprog->progname;
        }else{
            $selectedacadprog = 'All Academic Program';
        }
    @endphp
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></td>
        <td>
            <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>
            <br/>
            <small style="font-size: 10px !important;">{{DB::table('schoolinfo')->first()->address}}</small>
        </td>
        <td style="text-align:right;">
            <strong>Not Enrolled Students</strong>
            <br>
            <small style="font-size: 11px !important;"> {{DB::table('semester')->where('isactive','1')->first()->semester}}</small> <small style="font-size: 11px !important;">S.Y {{DB::table('sy')->where('isactive','1')->first()->sydesc}}</small>
            <br>
            <small style="font-size: 11px !important;"><strong>{{$selectedacadprog}}</strong></small>
            
        </td>
    </tr>
</table>

<table style="width: 100%; border-collapse: collapse; font-size: 11px; border-bottom: 1px solid black;">
    <thead style="text-align: left !important; border-top: 1px solid black; border-bottom: 1px solid black;">
        <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 15%;">Student ID</th>
            <th style="width: 15%;">LRN</th>
            <th style="width: 35%;">Student Name</th>
            {{-- <th style="width: 12%;">Gender</th> --}}
            <th>Grade Level</th>
            {{-- <th>Section</th> --}}
        </tr>
    </thead>
    @foreach($students as $key => $student)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$student->sid}}</td>
            <td>{{$student->lrn}}</td>
            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
            {{-- <td>{{strtoupper($student->gender)}}</td> --}}
            <td>{{$student->levelname}}</td>
            {{-- <td>{{$student->sectionname}}</td> --}}
        </tr>
    @endforeach
</table>
<table style="width: 100%; border-bottom: 1px solid black; font-size: 11px; text-align: right;">
    <tr>
        <td style="width: 92%;">TOTAL MALE:</td>
        <td>{{collect($students)->where('gender','male')->count()}}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid black;" >TOTAL FEMALE:</td>
        <td style="border-bottom: 1px solid black;" >{{collect($students)->where('gender','female')->count()}}</td>
    </tr>
    <tr>
        <td>OVERALL TOTAL:</td>
        <td>{{collect($students)->count()}}</td>
    </tr>
</table>
</body>
  
    
 
</html>
        