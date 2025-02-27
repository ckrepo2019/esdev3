<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
<style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }
.logo{
    width: 100%;
    table-layout: fixed;
}
.header{
    width: 100%;
}
table tr {
    page-break-inside: auto !important;
}

</style>
</head>
<body>
    {{-- <table class="logo">
        <tr>
            <td width="15%"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></td>
            <td>
                    <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>
                    <br>
                    <span style="font-size: 11px;">{{DB::table('schoolinfo')->first()->address}}</span>
            </td>
            <td width="15%"></td>
        </tr>
    </table> --}}
        @if(count($schedules)>0)
            @foreach($schedules as $key => $eachschedule)
            {{-- <div style="width: 100%; text-align: center; font-size: 12px;">
                {{DB::table('schoolinfo')->first()->schoolname}}
                <br/>
                McArthur Highway, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
                <br/>
                <br/>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
                GRADING SHEET
                @else
                OFFICIAL CLASS LIST
                @endif
                <br/>
                @if($semester == 1) First Semester @elseif($semester == 2) Second Semester @else {{$semester}}@endif, S.Y. {{$sydesc}}
            </div> --}}
            <br>
            @php
            
            $numlimit = $eachschedule->numstudents/2;   
            if (strpos($numlimit,'.') !== false) {
                $numlimit+=0.5;
            }
            @endphp
                <div style="width: 100%;">
                    <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
                        <tr> 
                            
                            <th style="width: 15%;">Schedule Code:</th>     
                            <td>{{$eachschedule->subjcode}}</td>
                            <th style="width: 15%;">Time:</th>
                            <td>{{date('h:i A',strtotime($eachschedule->stime))}} - {{date('h:i A',strtotime($eachschedule->etime))}}</td>
                        </tr>
                        {{-- <tr> 
                            <th>Subject:</th>     
                            <td>{{$eachschedule->subjcode}}</td>
                            <th>Time:</th>
                            <td>{{date('h:i A',strtotime($eachschedule->stime))}} - {{date('h:i A',strtotime($eachschedule->etime))}}</td>
                        </tr> --}}
                        <tr> 
                            <th>Description:</th>     
                            <td>{{$eachschedule->subjdesc}}</td>
                            <th>Room:</th>
                            <td>{{$eachschedule->roomname}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <td></td>
                            <th>Teacher:</th>
                            <td>{{$eachschedule->teachername}}</td>
                        </tr>
                    </table>
                    <br>
                    @php
                        $num = 1;
                        $num2 = $numlimit+1;
                        $studarray = $eachschedule->students;
                    @endphp
                    @if(count($studarray) <= 40)
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px; page-break-inside: auto;">
                            <thead style="text-align: left;">
                                <tr>
                                    <th style="width: 10%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                                    <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">STUDENT'S NAME</th>
                                    {{-- <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">COURSE</th> --}}
                                </tr>
                            </thead>
                            @php
                                $num = 1;
                            @endphp
                            @foreach ($studarray as $key => $student)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                        {{-- <td>@if(isset($student->courseabrv)){{$student->courseabrv}}@endif</td> --}}
                                    </tr>
                                    @php
                                        if(($key+1) == 40)
                                        {
                                            $student->display = 1;
                                            $num += 1;
                                            break;
                                        }
                                    @endphp
                            @endforeach
                        </table>
                    @else
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px; page-break-inside: auto;">
                        <thead style="text-align: left;">
                            <tr>
                                <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                                <th style="width: 45%; border-top: 1px dashed black; border-bottom: 1px dashed black;">STUDENT'S NAME</th>
                                {{-- <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">COURSE</th> --}}
                                <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                                <th style="width: 45%; border-top: 1px dashed black; border-bottom: 1px dashed black;">STUDENT'S NAME</th>
                                {{-- <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">COURSE</th> --}}
                            </tr>
                        </thead>
                        @foreach ($studarray as $student)
                            @if($num<=$numlimit)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} @if($student->middlename !=null) {{$student->middlename[0]}}. @endif {{ucwords(strtolower($student->suffix))}}</td>
                                    {{-- <td>{{$student->courseabrv}}</td> --}}
                                    @php
                                        $student->display = 1;
                                        $num += 1;
                                    @endphp
                                    
                                    @if($num2<=count($studarray))
                                    <td>{{$num2}}</td>
                                    <td>
                                        <?php try { ?>
                                            {{ucwords(strtolower($studarray[$num2-1]->lastname))}}, {{ucwords(strtolower($studarray[$num2-1]->firstname))}} @if($studarray[$num2-1]->middlename !=null) {{$studarray[$num2-1]->middlename[0]}}. @endif {{ucwords(strtolower($studarray[$num2-1]->suffix))}}
                                        <?php }catch(\Exception $error) {} ?>
                                    </td>
                                    {{-- <td>
                                        {{$studarray[$num2-1]->courseabrv}}
                                    </td> --}}
                                    @php
                                        $studarray[$num2-1]->display = 1;
                                        $num2+= 1;
                                    @endphp
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </table>
                    @endif
                    <br/>
                    <br/>
                    <div style="width: 100%; font-size: 12px;">
                        To the Teacher Concerned:
                    </div>
                    <br/>
                    <div style="width: 100%; font-size: 12px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        If the Student Name is not listed above, please advise them to process their enrolment with the Records Section.
                    </div>
                    <div style="width: 100%; font-size: 12px; text-align: right;">
                        <br>
                        <br>
                        The Registrar
                    </div>
                    @if(isset($schedules[$key+1]))
                    <div style="page-break-before: always;"></div>
                    @endif
                </div>
            @endforeach
        @endif

</body>
</html>