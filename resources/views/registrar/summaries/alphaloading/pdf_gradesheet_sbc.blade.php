<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
<style>
    @page{
        margin: 30px 25px 20px 25px;
    }
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
    /* header { position: fixed; top: -60px; left: 0px; right: 0px; height: 300px; } */

</style>
</head>
<body>
    {{-- <header>
  </header> --}}
  <main>
    @if(count($schedules)>0)
        @foreach($schedules as $key => $eachschedule)
            @php
     
                $studarray = $eachschedule->studentlist ?? $eachschedule->students;

                $students_chunks = array_chunk(collect($studarray)->toArray(),45);
                $countstudent = 0;
            @endphp
            @if(count($students_chunks)>0)
                @foreach($students_chunks as $students_chunk)
                    <div style="width: 100%; text-align: center; font-size: 12px;">
                        {{DB::table('schoolinfo')->first()->schoolname}}
                        <br/>
                        {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
                        <br/>
                        <br/>
                        <div style="text-align: center; font-weight: bold; font-size: 15px; margin-bottom: 10px;">GRADE SHEET</div>
                    </div>
                    <div style="text-align: center; font-weight: bold; font-size: 15px;">{{$eachschedule->code ?? $eachschedule->subjcode}}</div>
                    <div style="text-align: center; font-weight: bold; font-size: 15px;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc') margin-bottom: 20px;@endif">{{$eachschedule->subjectname}}</div>
                    <div style="text-align: center;font-size: 14px; margin-bottom: 20px;">{{$eachschedule->sectionname}}</div>
                    {{-- {{collect($students_chunk)}} --}}
                    @if(count($students_chunk) > 0)
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                            <thead style="text-align: left !important;">
                                <tr>
                                    <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                                    <th style="width: 40%; border-top: 1px dashed black; border-bottom: 1px dashed black;">Student Name</th>
                                    <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Course</th>
                                    <th style="width: 10%; border-top: 1px dashed black; border-bottom: 1px dashed black;">Grade</th>
                                    <th style="width: 2%;border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                                    <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;">Units</th>
                                    <th style="width: 2%;border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                                    <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Remarks</th>
                                </tr>
                            </thead>
                            @php
                                $num = 1;
                            @endphp
                            @foreach ($students_chunk as $key => $student)
                                @php
                                    $finalgrade = collect($grades)->where('subjid', $eachschedule->subjectid)->where('studentprospectusstudid', $student->id)->first()->subjgrade ?? null;
                                    $countstudent+=1;
                                @endphp
                                    <tr>
                                        <td style="text-align: center;">{{$countstudent}}.</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->suffix}} {{$student->middlename}}</td>
                                        <td>@if(isset($student->courseabrv)){{$student->courseabrv}}@endif</td>
                                        <td style=" border-bottom: 1px solid black; text-align: center;">{{$finalgrade == 8 ? 'INC' : ($finalgrade == 9 ? 'DROPPED' : $finalgrade)}}</td>
                                        <td></td>
                                        <td style=" border-bottom: 1px solid black; text-align: center;">{{$eachschedule->units}}</td>
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
                    
                        @if(count($students_chunk) > 0)
                            @if(isset($schedules[$key+1]))
                            {{-- <div style="page-break-after: always;"></div> --}}
                            @else
                            <div style="page-break-after: always;"></div>
                            @endif
                        @endif
                    {{-- @endif --}}
                @endforeach
                {{-- @if(isset($schedules[$key+1]))
                <div style="page-break-before: always;"></div>
                @endif --}}
            @endif
            @endforeach
        @endif
  </main>
</body>
</html>