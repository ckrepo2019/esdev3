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
    @php
        $registrarname = '';
        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
        {
        $registrarname = 'MERLIE S. SABUELO';
        }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
        {
        $registrarname = 'SGD. ElPIDIA G. SALAZAR, LPT, MAEd
';
        }else{
            $userinfo = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first();
            if($userinfo)
            {
                $registrarname.=$userinfo->firstname.' ';
                $registrarname.=$userinfo->middlename != null ? $userinfo->middlename[0].'. ' : '';
                $registrarname.=$userinfo->lastname.' ';
                $registrarname.=$userinfo->suffix;
            }
        }

    @endphp
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
    @php
        $schedules = collect($schedules)->groupBy('groupby');
    @endphp
    @foreach($schedules as $schedule)
        @php
        $students = collect();
        foreach($schedule as $eachsched)
        {
            
            $students = $students->merge($eachsched->students);
        }
        $students = $students->unique();
        $numlimit = count($students)/2;   
        if (strpos($numlimit,'.') !== false) {
            $numlimit+=0.5;
        }
        @endphp
        <div style="width: 100%; text-align: center; font-size: 12px;">
            {{DB::table('schoolinfo')->first()->schoolname}}
            <br/>
            {{DB::table('schoolinfo')->first()->address}}
            <br/>
            <br/>
            OFFICIAL CLASS LIST
            <br/>
            {{$semester}} S.Y. {{$sydesc}}
        </div>
        <table style="width: 100%; font-size: 11px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th style="width: 15%;">Subjects Code:</th>     
                <td><u>{{$eachsched->subjcode ?? $eachsched->code ?? $eachsched->schedcode ?? ''}}</u></td>
                <th style="width: 15%;">Credit Units:</th>
                <td>{{$eachsched->units}}</td>
            </tr>
            <tr> 
                <th>Descriptive Title:</th>     
                <td><u>{{$eachsched->subjectname}}</u></td>
                <th>Time:</th>
                <td><u>{{date('h:i A',strtotime($eachsched->stime))}} - {{date('h:i A',strtotime($eachsched->etime))}}</u></td>
            </tr>
            <tr> 
                <th>Term:</th>     
                <td><u>{{$selectedterm}}</u></td>
                <th>Instructor:</th>
                <td>@if($eachsched->lastname != null)
                    {{$eachsched->lastname}}, {{$eachsched->firstname}}
                    @endif
                </td>
            </tr>
        </table>
        <br>
        <table  style="width:100%; font-size: 11px; border-collapse: collapse; page-break-inside: always;" >
            <thead style="text-align: left !important;">
                <tr>
                    <th>NO</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th style="width: 35%;">Name</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th>Program</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th>Year Level</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th>Midterm</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th>Final</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th>Sem Grade</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 1;
                @endphp
                    @foreach ($students as $key => $student)
                            <tr>
                                <td style="text-align: left;">{{$num}}</td>
                                <td></td>
                                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                <td></td>
                                <td>{{$student->coursename}}</td>
                                <td></td>
                                <td style="text-align: center;">{{$student->yearlevel}}</td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                            </tr>
                            @php
                                $num += 1;
                            @endphp
                    @endforeach
        
            </tbody>
        </table>
        <br/>
        <br/>
        <br/>
        
    <table style="width: 100%; font-size: 12px;">
        <tr>
            <td style="border-bottom: 1px solid black;"></td>
            <td style="width: 10%;"></td>
            <td style="border-bottom: 1px solid black;"></td>
            <td style="width: 10%;"></td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
        <tr>
            <td style="text-align: center;">Instructor</td>
            <td></td>
            <td style="text-align: center;">Dean</td>
            <td></td>
            <td style="text-align: center;">Registrar</td>
        </tr>
        <tr>
            <td>Date Signed:</td>
            <td></td>
            <td>Date Signed:</td>
            <td></td>
            <td>Date Signed:</td>
        </tr>
    </table>
        <div style="page-break-after: always;"></div>
    @endforeach
    @else
    @foreach($schedules as $schedule)
        @php
        
        $numlimit = count($schedule->studentlist)/2;   
        if (strpos($numlimit,'.') !== false) {
            $numlimit+=0.5;
        }
        @endphp
        <div style="width: 100%; text-align: center; font-size: 12px;">
            <div style="width: 100%; text-align: center; font-size: 12px; padding: 0px;">
                <table style="width: 100%; margin: 0px;">
                    <tr>
                        <td width="30%" style="text-align: right;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                        <td style="width: 40%; text-align: center; vertical-align: top;">
                                <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>
                                <br>
                                <span style="font-size: 12px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</span>
                                <br/>
                                <br/>
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
                                GRADING SHEET
                                @else
                                OFFICIAL CLASS LIST
                                @endif
                                <br/>
                                @if($semester == 1) First Semester @elseif($semester == 2) Second Semester @else {{$semester}}@endif, S.Y. {{$sydesc}}
                        </td>
                        <td width="30%"></td>
                    </tr>
                </table>
            </div>
            <br>
        </div>
        <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                <th style="width: 15%;">Course:</th>     
                <td>{{$schedule->courseabrv}}</td>
                <th style="width: 15%;">Days:</th>
                <td>{{$schedule->description}}</td>
                @else
                <th style="width: 15%;">Schedule Code:</th>     
                <td>{{$schedule->code ?? $schedule->subjcode}}</td>
                <th style="width: 15%;">Term:</th>
                <td>{{$schedule->term ?? ''}}</td>
                @endif
            </tr>
            @if(isset($schedule->sectionname))
            <tr> 
                <th>Section:</th>     
                <td>{{$schedule->sectionname ?? ''}}</td>
                <th>Units:</th>
                <td>{{$schedule->units ?? ''}}</td>
            </tr>
            @endif
            <tr> 
                <th>Subject:</th>     
                <td>{{$schedule->subjcode}}</td>
                <th>Schedule:</th>
                <td>{{$schedule->description ?? ''}} {{date('h:i A',strtotime($schedule->stime))}} - {{date('h:i A',strtotime($schedule->etime))}}</td>
            </tr>
            <tr> 
                <th>Description:</th>     
                <td>{{$schedule->subjectname}}</td>
                <th>Room:</th>
                <td>
                    {{collect($schedules)->first()->roomname ?? ''}}
                </td>
            </tr>
            <tr>
                <th>Instructor:</th>
                <?php try{ ?> 
        <td>@if($schedule->lastname !=null){{$schedule->lastname}}, {{$schedule->firstname}}@endif</td>
        
                <?php }catch(\Exception $e){ ?>
        <td>@if(isset($schedule->teachername)){{$schedule->teachername}} @endif</td>
                <?php } ?>
            </tr>
        </table>
        <br/>
        @php
            $num = 1;
            $num2 = $numlimit+1;
            // $studarray = collect($schedules)->first()->studentlist;
        @endphp
        @if(count($schedule->studentlist) <= 40)
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead style="text-align: left;">
                    <tr>
                        <th style="width: 10%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">STUDENT'S NAME</th>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Mode of learning</th>
                        @endif
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">COURSE</th>
                    </tr>
                </thead>
                @php
                    $num = 1;
                @endphp
                @foreach ($schedule->studentlist as $key => $student)
                        <tr>
                            <td>{{$num}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                            <td>{{$student->mol}}</td>
                            @endif
                            <td>@if(isset($student->courseabrv)){{$student->courseabrv}}@endif</td>
                        </tr>
                        @php
                            $num += 1;
                        @endphp
                @endforeach
            </table>
        @else
            <table style="width: 100%; border-collapse: collapse; font-size: 11px; page-break-inside: auto;">
                <thead style="text-align: left;">
                    <tr>
                        <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                        <th style="width: 25%; border-top: 1px dashed black; border-bottom: 1px dashed black;">STUDENT'S NAME</th>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Mode of learning</th>
                        @endif
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">COURSE</th>
                        <th style="width: 5%; border-top: 1px dashed black; border-bottom: 1px dashed black;"></th>
                        <th style="width: 25%; border-top: 1px dashed black; border-bottom: 1px dashed black;">STUDENT'S NAME</th>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">Mode of learning</th>
                        @endif
                        <th style="border-top: 1px dashed black; border-bottom: 1px dashed black;">COURSE</th>
                    </tr>
                </thead>
                @foreach ($schedule->studentlist as $student)
                    @if($num<=$numlimit)
                        <tr>
                            <td>{{$num}}</td>
                            <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} @if($student->middlename !=null) {{$student->middlename[0]}}. @endif {{ucwords(strtolower($student->suffix))}}</td>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                <td>{{$student->mol}}</td>
                            @endif
                            <td>{{$student->courseabrv}}</td>
                            @php
                                $student->done = 1;
                                $num += 1;
                            @endphp
                            
                            @if($num2<=count($schedule->studentlist))
                                <td>{{$num2}}</td>
                                <td>
                                    {{ucwords(strtolower($schedule->studentlist[$num2-1]->lastname))}}, {{ucwords(strtolower($schedule->studentlist[$num2-1]->firstname))}} @if($schedule->studentlist[$num2-1]->middlename !=null) {{$schedule->studentlist[$num2-1]->middlename[0]}}. @endif {{ucwords(strtolower($schedule->studentlist[$num2-1]->suffix))}}
                                </td>
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                <td> {{$schedule->studentlist[$num2-1]->mol}}</td>
                                @endif
                                <td>
                                    {{$schedule->studentlist[$num2-1]->courseabrv}}
                                </td>
                                @php
                                    $schedule->studentlist[$num2-1]->done = 1;
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
        <table style="width: 100%; font-size: 12px; table-layout: fixed; margin: 0px 40px;">
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center; border-bottom: 1px solid black;">&nbsp;{{$registrarname}}&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">School Registrar</td>
            </tr>
        </table>
        <div style="page-break-after: always;"></div>
    @endforeach
    @endif
</body>
</html>