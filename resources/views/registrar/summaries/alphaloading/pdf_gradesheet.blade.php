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
    <div style="width: 100%; text-align: center; font-size: 12px;">
        {{DB::table('schoolinfo')->first()->schoolname}}
        <br/>
        McArthur Highway, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
        <br/>
        <br/>
        <div style="text-align: center; font-weight: bold; font-size: 15px; margin-bottom: 10px;">GRADE SHEET</div>
    </div>
        @if(count($schedules)>0)
        @foreach($schedules as $key => $eachschedule)
        @php
         
                    $studarray = $eachschedule->studentlist ?? $eachschedule->students;
                @endphp
                <div style="text-align: center; font-weight: bold; font-size: 15px;">{{$eachschedule->code ?? $eachschedule->subjcode}}</div>
                <div style="text-align: center; font-weight: bold; font-size: 15px;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc') margin-bottom: 20px;@endif">{{$eachschedule->subjectname}}</div>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                <div style="text-align: center;font-size: 14px; margin-bottom: 20px;">{{$eachschedule->sectionname}}</div>
                @endif
            {{-- <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
                <tr> 
                    <th style="width: 15%;">Schedule Code:</th>     
                    <td>{{$eachschedule->code ?? $eachschedule->subjcode}}</td>
                    <th style="width: 15%;">Term:</th>
                    <td>0</td>
                </tr>
                <tr> 
                    <th>Subject:</th>     
                    <td>{{$eachschedule->subjcode}}</td>
                    <th>Time:</th>
                    <td>{{date('h:i A',strtotime($eachschedule->stime))}} - {{date('h:i A',strtotime($eachschedule->etime))}}</td>
                </tr>
                <tr> 
                    <th>Description:</th>     
                    <td>{{$eachschedule->subjectname}}</td>
                    <th>Room:</th>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td></td>
                    <th>Teacher:</th>
                    <td>@if($eachschedule->lastname !=null){{$eachschedule->lastname}}, {{$eachschedule->firstname}}@endif</td>
                </tr>
            </table> --}}
            <br>
            @php
                $studarray = $eachschedule->studentlist ?? $eachschedule->students;
            @endphp
            @if(count($studarray) > 0)
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead style="text-align: left !important;">
                        <tr>
                            <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                            <th style="width: 40%; border-top: 1px dashed black; border-bottom: 1px dashed black;">Student Name</th>
                            <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Course</th>
                            <th style="width: 10%; border-top: 1px dashed black; border-bottom: 1px dashed black;">Grade</th>
                            <th style="width: 2%;border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                            <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Remarks</th>
                        </tr>
                    </thead>
                    @php
                        $num = 1;
                    @endphp
                    @foreach ($studarray as $key => $student)
                        @php
                            $finalgrade = collect($grades)->where('subjid', $eachschedule->subjectid)->where('studentprospectusstudid', $student->id)->first()->subjgrade ?? null;
                        @endphp
                            <tr>
                                <td style="text-align: center;">{{$num}}.</td>
                                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                <td>@if(isset($student->courseabrv)){{$student->courseabrv}}@endif</td>
                                <td style=" border-bottom: 1px solid black; text-align: center;">{{$finalgrade == 8 ? 'INC' : ($finalgrade == 9 ? 'DROPPED' : $finalgrade)}}</td>
                                <td></td>
                                <td style=" border-bottom: 1px solid black;"></td>
                            </tr>
                            @php
                                $num += 1;
                            @endphp
                    @endforeach
                </table>
            @endif
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; table-layout: fixed; font-size: 12px;" >
                <tr>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr style="text-align: center;">
                    <td>Date Submitted</td>
                    <td></td>
                    <td>Department Head</td>
                    <td></td>
                    <td>Instructor Signature</td>
                </tr>
            </table>
            {{-- <div style="width: 100%; font-size: 12px;">
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
            </div> --}}
            @if(isset($schedules[$key+1]))
            <div style="page-break-before: always;"></div>
            @endif
        @endforeach
    @endif
</body>
</html>