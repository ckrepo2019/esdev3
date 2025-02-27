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
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
        @php
        
        $numlimit = count($students)/2;   
        if (strpos($numlimit,'.') !== false) {
            $numlimit+=0.5;
        }
        @endphp
        <div style="width: 100%; text-align: center; font-size: 12px;">
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
        </div>
        <br>
        <table style="width: 100%; font-size: 11px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th style="width: 15%;">Subjects Code:</th>     
                <td><u>{{collect($schedules)->first()->subjcode}}</u></td>
                <th style="width: 15%;">Credit Units:</th>
                <td>{{collect($schedules)->first()->units}}</td>
            </tr>
            <tr> 
                <th>Descriptive Title:</th>     
                <td><u>{{collect($schedules)->first()->subjectname}}</u></td>
                <th>Time:</th>
                <td><u>{{date('h:i A',strtotime(collect($schedules)->first()->stime))}} - {{date('h:i A',strtotime(collect($schedules)->first()->etime))}}</u></td>
            </tr>
            <tr> 
                <th>Term:</th>     
                <td><u>{{$selectedterm}}</u></td>
                <th>Instructor:</th>
                <td>@if($schedules[0]->lastname != null)
                    {{$schedules[0]->lastname}}, {{$schedules[0]->firstname}}
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
    
    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
        
        @if(count($schedules)>0)
            @foreach($schedules as $key => $eachschedule)
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
                @php
                
                $numlimit = count($eachschedule->studentlist)/2;   
                if (strpos($numlimit,'.') !== false) {
                    $numlimit+=0.5;
                }
                @endphp
                <div style="width: 100%;">
                    <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
                        <tr> 
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                            <th style="width: 15%;">Course:</th>     
                            <td>{{$eachschedule->courseabrv}}</td>
                            <th style="width: 15%;">Days:</th>
                            <td>{{$eachschedule->description}}</td>
                            @else
                            <th style="width: 15%;">Schedule Code:</th>     
                            <td>{{$eachschedule->code ?? $eachschedule->subjcode}}</td>
                            <th style="width: 15%;">Term:</th>
                            <td>{{$eachschedule->term ?? ''}}</td>
                            @endif
                        </tr>
                        @if(isset($eachschedule->sectionname))
                        <tr> 
                            <th>Section:</th>     
                            <td>{{$eachschedule->sectionname ?? ''}}</td>
                            <th>Units:</th>
                            <td>{{$eachschedule->units ?? ''}}</td>
                        </tr>
                        @endif
                        <tr> 
                            <th>Subject:</th>     
                            <td>{{$eachschedule->subjcode}}</td>
                            <th>Schedule:</th>
                            <td>{{$eachschedule->description ?? ''}} {{date('h:i A',strtotime($eachschedule->stime))}} - {{date('h:i A',strtotime($eachschedule->etime))}}</td>
                        </tr>
                        <tr> 
                            <th>Description:</th>     
                            <td>{{$eachschedule->subjectname}}</td>
                            <th>Room:</th>
                            <td>
                                {{collect($schedules)->first()->roomname ?? ''}}
                            </td>
                        </tr>
                        <tr>
                            <th>Instructor:</th>
                            <?php try{ ?> 
                    <td>@if($eachschedule->lastname !=null){{$eachschedule->lastname}}, {{$eachschedule->firstname}}@endif</td>
                    
                            <?php }catch(\Exception $e){ ?>
                    <td>@if(isset($eachschedule->teachername)){{$eachschedule->teachername}} @endif</td>
                            <?php } ?>
                        </tr>
                    </table>
                    <br>
                    @php
                        $num = 1;
                        $num2 = $numlimit+1;
                        $studarray = $eachschedule->studentlist ?? $eachschedule->students;
                    @endphp
                    @if(count($studarray) <= 40)
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px; page-break-inside: auto;">
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
                            @foreach ($studarray as $key => $student)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                        <td>{{$student->mol}}</td>
                                        @endif
                                        <td>@if(isset($student->courseabrv)){{$student->courseabrv}}@endif</td>
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
                        @foreach ($studarray as $student)
                            @if($num<=$numlimit)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} @if($student->middlename !=null) {{$student->middlename[0]}}. @endif {{ucwords(strtolower($student->suffix))}}</td>
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                    <td>{{$student->mol}}</td>
                                    @endif
                                    <td>{{$student->courseabrv}}</td>
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
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                    <td> {{$studarray[$num2-1]->mol}}</td>
                                    @endif
                                    <td>
                                        {{$studarray[$num2-1]->courseabrv}}
                                    </td>
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
                    {{-- <div style="width: 100%; font-size: 12px; text-align: right;">
                        <br>
                        <br>
                        The Registrar
                    </div> --}}
                    @if(isset($schedules[$key+1]))
                    <div style="page-break-before: always;"></div>
                    @endif
                </div>
            @endforeach
        @endif
    @else
         @if(count($schedules)>0)
            @foreach($schedules as $key => $eachschedule)
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
            @php
            
            $numlimit = count($eachschedule->studentlist)/2;   
            if (strpos($numlimit,'.') !== false) {
                $numlimit+=0.5;
            }
            @endphp
                <div style="width: 100%;">
                    <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
                        <tr> 
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                            <th style="width: 15%;">Course:</th>     
                            <td>{{$eachschedule->courseabrv}}</td>
                            <th style="width: 15%;">Days:</th>
                            <td>{{$eachschedule->description}}</td>
                            @else
                            <th style="width: 15%;">Schedule Code:</th>     
                            <td>{{$eachschedule->code ?? $eachschedule->subjcode}}</td>
                            <th style="width: 15%;">Term:</th>
                            <td>{{$eachschedule->term ?? ''}}</td>
                            @endif
                        </tr>
                        @if(isset($eachschedule->sectionname))
                        <tr> 
                            <th>Section:</th>     
                            <td>{{$eachschedule->sectionname ?? ''}}</td>
                            <th>Units:</th>
                            <td>{{$eachschedule->units ?? ''}}</td>
                        </tr>
                        @endif
                        <tr> 
                            <th>Subject:</th>     
                            <td>{{$eachschedule->subjcode}}</td>
                            <th>Schedule:</th>
                            <td>{{$eachschedule->description ?? ''}} {{date('h:i A',strtotime($eachschedule->stime))}} - {{date('h:i A',strtotime($eachschedule->etime))}}</td>
                        </tr>
                        <tr> 
                            <th>Description:</th>     
                            <td>{{$eachschedule->subjectname}}</td>
                            <th>Room:</th>
                            <td>
                                {{collect($schedules)->first()->roomname ?? ''}}
                            </td>
                        </tr>
                        <tr>
                            <th>Instructor:</th>
                            <?php try{ ?> 
                    <td>@if($eachschedule->lastname !=null){{$eachschedule->lastname}}, {{$eachschedule->firstname}}@endif</td>
                    
                            <?php }catch(\Exception $e){ ?>
                    <td>@if(isset($eachschedule->teachername)){{$eachschedule->teachername}} @endif</td>
                            <?php } ?>
                        </tr>
                    </table>
                    <br>
                    @php
                        $num = 1;
                        $num2 = $numlimit+1;
                        $studarray = $eachschedule->studentlist ?? $eachschedule->students;
                    @endphp
                    @if(count($studarray) <= 40)
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px; page-break-inside: auto;">
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
                            @foreach ($studarray as $key => $student)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                        <td>{{$student->mol}}</td>
                                        @endif
                                        <td>@if(isset($student->courseabrv)){{$student->courseabrv}}@endif</td>
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
                        @foreach ($studarray as $student)
                            @if($num<=$numlimit)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} @if($student->middlename !=null) {{$student->middlename[0]}}. @endif {{ucwords(strtolower($student->suffix))}}</td>
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                    <td>{{$student->mol}}</td>
                                    @endif
                                    <td>{{$student->courseabrv}}</td>
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
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                    <td> {{$studarray[$num2-1]->mol}}</td>
                                    @endif
                                    <td>
                                        {{$studarray[$num2-1]->courseabrv}}
                                    </td>
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
                    {{-- <div style="width: 100%; font-size: 12px; text-align: right;">
                        <br>
                        <br>
                        The Registrar
                    </div> --}}
                    @if(isset($schedules[$key+1]))
                    <div style="page-break-before: always;"></div>
                    @endif
                </div>
            @endforeach
        @endif
    @endif

</body>
</html>