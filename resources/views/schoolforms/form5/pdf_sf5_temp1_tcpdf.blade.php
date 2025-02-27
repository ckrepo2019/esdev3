
<style>
    @page{
        size: 13in 8.5in;
    }
    td{
        padding: 5px;
    }
    .students td{
        padding: 10px !important;
    }
</style>
@php
function capitalize($word)
    {
        return \App\Http\Controllers\SchoolFormsController::capitalize_word($word);
    }
@endphp
<table style="width: 100%; table-layout: fixed;">
    <tr style="line-height: 15px;">
        <th colspan="10" style="font-size: 20px; text-align: center; font-weight: bold;">School Form 5 (SF 5) Report on Promotion & Level of Proficiency															

        </th>
    </tr>
    <tr>
        <td rowspan="3">
            &nbsp;
            {{-- <img src='{{base_path()}}/public/{{$getSchoolInfo[0]->picurl}}' alt='school' width='65px'> --}}
        </td>
        <th colspan="8" style="font-size: 11px; text-align: center; font-weight: bold;">
            <em><center>(This replaces Forms 18-E1, 18-E2, 18A and List of Graduates)															
</center></em>
        </th>
        <td rowspan="3">
            &nbsp;
            {{-- <img src='{{base_path()}}/public/assets/images/department_of_Education.png' alt='school' width='65px'> --}}
        </td>
    </tr>
    <tr style="font-size: 11px;">
        <td>Region</td>
        <td><div style="border: 1px solid black; text-align: center;">{{str_replace('REGION', '', $getSchoolInfo[0]->region)}}</div></td>
        <td>Division</td>
        <td colspan="2"><div style="border: 1px solid black; text-align: center;">{{str_replace('CITY', '', $getSchoolInfo[0]->division)}}</div></td>
        <td>District</td>
        <td colspan="2"><div style="border: 1px solid black; text-align: center;">{{$getSchoolInfo[0]->district}}</div></td>
    </tr>
    <tr style="font-size: 11px;">
        <td>School ID</td>
        <td colspan="2"><div style="border: 1px solid black; text-align: center;">{{$getSchoolInfo[0]->schoolid}}</div></td>
        <td>School Year</td>
        <td><div style="border: 1px solid black; text-align: center;">{{$sy->sydesc}}</div></td>
        <td>Curriculum</td>
        <td colspan="2"><div style="border: 1px solid black; text-align: center;">K+12 Enhanced BEC	
</div></td>
    </tr>
</table>
<table style="width: 100%;">
    <tr style="font-size: 11px;">
        <td colspan="2" style="padding-left: 50px; text-align: right;">School Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4"><div style="border: 1px solid black; text-align: center;">{{$getSchoolInfo[0]->schoolname}}</div></td>
        <td>Grade Level</td>
        <td><div style="border: 1px solid black; text-align: center;">@if(count($getSectionAndLevel)>0){{$getSectionAndLevel[0]->levelname}}@endif</div></td>
        <td>Section</td>
        <td><div style="border: 1px solid black; text-align: center;">@if(count($getSectionAndLevel)>0){{$getSectionAndLevel[0]->sectionname}}@endif</div></td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td style="width: 75%; vertical-align: top;"><table class="students" style="width: 100%; page-break-inside: auto; font-size: 11px;" border="1">
                <thead>
                    <tr style="text-align: center;">
                        <th style="width: 16%; line-height: 10px;" rowspan="2">LRN</th>
                        <th style="width: 30%;" rowspan="2">LEARNER'S NAME<br><sup>(Last Name, First Name, Middle Name)</sup></th>
                        <th style="width: 12%;" rowspan="2">GENERAL<br>AVERAGE(Numerical Value in 2 decimal places and 3 decimal places for honor learners, and Descriptive Letter)</th>
                        <th style="width: 15%;" rowspan="2">ACTION TAKEN: PROMOTED, CONDITIONAL, or RETAINED</th>
                        <th style="width: 27%;" colspan="2">INCOMPLETE SUBJECT/S (This column is for K to 12 Curriculum and remaining RBEC in High School. Elementary grades level that are still implementing RBEC need not to fill up these columns)</th>
                    </tr>
                    <tr style="text-align: center;">
                        <th style="font-size: 9px;width: 13.5%;">From previous school years completed as of end of current School Year</th>
                        <th style="font-size: 9px;width: 13.5%;">As of end of current School Year</th>
                    </tr>
                </thead>
                @php
                    $countMale = 0;   
                    $malelineheight = 14;
                    $malecountlimit = 20;
                    $malecount = collect($students)->where('gender','male')->count();
                    // $malelinecount = 35-collect($students)->where('gender','male')->count();
                    // if($malelinecount > 0)
                    // {
                    //     for($x = $malelinecount; $x>0; $x--)
                    //     {
                    //         $malelineheight+=.50;
                    //     }
                    // }
                    
                @endphp
                <tr nobr="true">
                    <td style="width: 16%; text-align: center; font-weight: bold; @if(collect($students)->where('gender','male')->count() < 40) line-height: {{$malelineheight}}px; @endif">MALE</td>
                    <td colspan="5"></td>
                </tr>
                @foreach ($students as $student)
                    @if(strtolower($student->gender)=="male")
                        <tr nobr="true" >
                            <td style="width: 16%;">{{$student->lrn}}</td>
                            <td style="width: 30%; text-align: left;">{{capitalize($student->lastname)}}, {{capitalize($student->firstname)}} {{$student->suffix}}
                                @if($student->middlename != null)
                                {{$student->middlename[0]}}. 
                                @endif
                                
                            </td>
                            <td style="width: 12%; text-align: center;">
                                @if($student->generalaverage>0)
                                    @if($student->fraward != "")
                                    <center>{{$student->fcomp}}</center>
                                    @else
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                            <center>{{round($student->fcomp,2)}}</center>
                                        @else
                                            @if($student->generalaverage>=90)
                                            <center>{{$student->generalaverage}}</center>
                                            @else
                                            <center>{{$student->generalaverage}}</center>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td  style="width: 15%; text-align: center;">
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                {{strtoupper($student->promotionstat)}}
                                @else
                                {{strtoupper($student->promotionstat.' '.$student->fraward)}}
                                @endif
                            </td>
                            <td style="width: 13.5%;">
                                @if(collect($student->grades)->where('failed','1')->count()>0)
                                    
                                @endif
                            </td>
                            <td style="width: 13.5%;"></td>
                        </tr>
                        @php
                            $countMale+=1
                        @endphp
                    @endif
                @endforeach
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm')
                    @for($x = $malecount; $x < $malecountlimit; $x++)
                        <tr nobr="true" style=" line-height: {{$malelineheight}}px;">
                            <td style="width: 16%;">&nbsp;</td>
                            <td style="width: 30%; text-align: left;">
                            &nbsp;
                            </td>
                            <td style="width: 12%; text-align: center;">
                                &nbsp;
                            </td>
                            <td  style="width: 15%;">
                                &nbsp;
                            </td>
                            <td style="width: 13.5%;"></td>
                            <td style="width: 13.5%;"></td>
                        </tr>
                    @endfor
                @endif
                @php
                        $male=0;   
                @endphp
                @foreach ($students as $student)
                    @if(strtolower($student->gender)=="male")
                            @php
                            $male+=1
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td style="text-align: center;"> <span style="font-weight: bold;">{{$countMale}}</span>
                    </td>
                    <td style="text-align: left; font-weight: bold;">
                        ==== TOTAL MALE
                    </td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                </tr>
                @php
                    $countFemale = 0;   
                    $femalelineheight = 14;
                    $femalecountlimit = 20;
                    $femalecount = collect($students)->where('gender','female')->count();
                    // $femalelinecount = 35-collect($students)->where('gender','female')->count();
                    // if($femalelinecount > 0)
                    // {
                    //     for($x = $femalelinecount; $x>0; $x--)
                    //     {
                    //         $femalelineheight+=1;
                    //     }
                    // }
                    
                @endphp
                <tr nobr="true">
                    <td style="width: 16%; text-align: center; font-weight: bold; line-height: {{$femalelineheight}}px;">FEMALE</td>
                    <td colspan="5"></td>
                </tr>
                @foreach ($students as $student)
                    @if(strtolower($student->gender)=="female")
                        <tr nobr="true">
                            <td style="width: 16%;">{{$student->lrn}}</td>
                            <td style="width: 30%; text-align: left;">{{capitalize($student->lastname)}}, {{capitalize($student->firstname)}} {{$student->suffix}}
                                @if($student->middlename != null)
                                {{$student->middlename[0]}}. 
                                @endif
                               
                            </td>
                            <td style="width: 12%; text-align: center;">
                                @if($student->generalaverage>0)
                                    @if($student->fraward != "")
                                    <center>{{$student->fcomp}}</center>
                                    @else
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                            <center>{{round($student->fcomp,2)}}</center>
                                        @else
                                            @if($student->generalaverage>=90)
                                            <center>{{$student->generalaverage}}</center>
                                            @else
                                            <center>{{$student->generalaverage}}</center>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td  style="width: 15%; text-align: center;">
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                {{strtoupper($student->promotionstat)}}
                                @else
                                {{strtoupper($student->promotionstat.' '.$student->fraward)}}
                                @endif
                            </td>
                            <td style="width: 13.5%;">@if(collect($student->grades)->where('failed','1')->count()>0)
                                    
                                @endif</td>
                            <td style="width: 13.5%;"></td>
                        </tr>
                        @php
                            $countFemale+=1
                        @endphp
                    @endif
                @endforeach
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm')
                    @for($x = $femalecount; $x < $femalecountlimit; $x++)
                        <tr nobr="true">
                            <td style="width: 16%;">&nbsp;</td>
                            <td style="width: 30%; text-align: left;">
                            &nbsp;
                            </td>
                            <td style="width: 12%; text-align: center;">
                                &nbsp;
                            </td>
                            <td  style="width: 15%;">
                                &nbsp;
                            </td>
                            <td style="width: 13.5%;"></td>
                            <td style="width: 13.5%;"></td>
                        </tr>
                    @endfor
                @endif
                @php
                        $female=0;   
                @endphp
                @foreach ($students as $student)
                    @if(strtolower($student->gender)=="female")
                            @php
                            $female+=1
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td style="text-align: center;"> <span style="font-weight: bold;">{{$countFemale}}</span>
                    </td>
                    <td style="text-align: left; font-weight: bold;">
                        ==== TOTAL FEMALE
                    </td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                </tr>
                <tr>
                    <td style="text-align: center; @if(collect($students)->count() < 80) line-height: 20px; @endif"> <span style="font-weight: bold;">{{$countMale+$countFemale}}</span>
                    </td>
                    <td style="text-align: left; font-weight: bold;  @if(collect($students)->count() < 80)  @endif">
                        ==== TOTAL MALE & FEMALE

                    </td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                    <td style=" background-color: #ddd;"></td>
                </tr>
            </table>
        </td>
        <td style="width: 25%; padding-top: 5px;"><br/><br/><br/><br/><br/><br/><br/><table style="width: 100%; font-size: 11px; text-align: center; table-layout: fixed;" border="1">
                <thead>
                    <tr style="text-align: center;">
                        <th colspan="4">SUMMARY TABLE			
</th>
                    </tr>
                    <tr style="text-align: center;">
                        <th style="width: 40%;">STATUS</th>
                        <th style="width: 20%;">MALE</th>
                        <th style="width: 20%;">FEMALE</th>
                        <th style="width: 20%;">TOTAL</th>
                    </tr>
                </thead>
                <tr>
                    <th style="width: 40%;">PROMOTED</th>
                    <td style="width: 20%;">
                        @php
                            $promotedMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->promotionstat == "PROMOTED")
                                    @php
                                        $promotedMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$promotedMale}}</center>
                    </td>
                    <td style="width: 20%;">
                        @php
                            $promotedFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->promotionstat == "PROMOTED")
                                    @php
                                        $promotedFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$promotedFemale}}</center>
                    </td>
                    <td style="width: 20%;"><center>{{$promotedMale + $promotedFemale}}</center></td>
                </tr>
                <tr>
                    <th>*Conditional</th>
                    <td>
                        @php
                            $conditionalMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->promotionstat == "CONDITIONAL")
                                    @php
                                        $conditionalMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$conditionalMale}}</center>
                    </td>
                    <td>
                        @php
                            $conditionalFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->promotionstat == "CONDITIONAL")
                                    @php
                                        $conditionalFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$conditionalFemale}}</center>
                    </td>
                    <td><center>{{$conditionalFemale + $conditionalMale}}</center></td>
                </tr>
                <tr>
                    <th>RETAINED</th>
                    <td>
                        @php
                            $retainedMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->promotionstat == "RETAINED")
                                    @php
                                        $retainedMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$retainedMale}}</center>
                    </td>
                    <td>
                        @php
                            $retainedFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->promotionstat == "RETAINED")
                                    @php
                                        $retainedFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$retainedFemale}}</center>
                    </td>
                    <td><center>{{$retainedFemale + $retainedMale}}</center></td>
                </tr>
            </table>
            <div style="line-height: 5px;"></div>
            <table style="width: 100%;  text-align: center;" border="1">
                <thead>
                    <tr style="text-align: center; font-size: 11px;">
                        <th colspan="4">LEVEL OF PROFICIENCY			
</th>
                    </tr>
                    <tr style="text-align: center; font-size: 11px;">
                        <th style="width: 40%;">Descriptors & Grading Scale</th>
                        <th style="width: 20%;">MALE</th>
                        <th style="width: 20%;">FEMALE</th>
                        <th style="width: 20%;">TOTAL</th>
                    </tr>
                </thead>
                <tr style="font-size: 11px;">
                    <th>Did Not Meet Expectations<br>(74 and below)</th>
                    <td>
                        @php
                            $didNotMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->generalaverage <= 74 && $student->generalaverage!=0)
                                    @php
                                        $didNotMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$didNotMale}}</center>
                    </td>
                    <td>
                        @php
                            $didNotFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->generalaverage <= 74 && $student->generalaverage!=0)
                                    @php
                                        $didNotFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$didNotFemale}}</center>
                    </td>
                    <td><center>{{$didNotFemale + $didNotMale}}</center></td>
                </tr>
                <tr style="font-size: 11px;">
                    <th>Fairly Satisfactory<br>(75-79)</th>
                    <td>
                        @php
                            $fairlyMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->generalaverage >= 75 && $student->generalaverage <= 79.99 && $student->generalaverage!=0)
                                    @php
                                        $fairlyMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$fairlyMale}}</center>
                    </td>
                    <td>
                        @php
                            $fairlyFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                       
                            @if(strtolower($student->gender)=="female")
                                @if($student->generalaverage >= 75 && $student->generalaverage <= 79.99 && $student->generalaverage!=0)
                                    @php
                                        $fairlyFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$fairlyFemale}}</center>
                    </td>
                    <td><center>{{$fairlyFemale + $fairlyMale}}</center></td>
                </tr>
                <tr style="font-size: 11px;">
                    <th>Satisfactory<br>(80-84)</th>
                    <td>
                        @php
                            $satisfactoryMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->generalaverage >= 80 && $student->generalaverage <= 84.99 && $student->generalaverage!=0)
                                    @php
                                        $satisfactoryMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$satisfactoryMale}}</center>
                    </td>
                    <td>
                        @php
                            $satisfactoryFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->generalaverage >= 80 && $student->generalaverage <= 84.99 && $student->generalaverage!=0)
                                    @php
                                        $satisfactoryFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$satisfactoryFemale}}</center>
                    </td>
                    <td><center>{{$satisfactoryFemale + $satisfactoryMale}}</center></td>
                </tr>
                <tr style="font-size: 11px;">
                    <th>Very Satisfactory<br>(85-89)</th>
                    <td>
                        @php
                            $verySatisfactoryMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->generalaverage >= 85 && $student->generalaverage <= 89.99 && $student->generalaverage!=0)
                                    @php
                                        $verySatisfactoryMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$verySatisfactoryMale}}</center>
                    </td>
                    <td>
                        @php
                            $verySatisfactoryFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->generalaverage >= 85 && $student->generalaverage <= 89.99 && $student->generalaverage!=0)
                                    @php
                                        $verySatisfactoryFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$verySatisfactoryFemale}}</center>
                    </td>
                    <td><center>{{$verySatisfactoryFemale + $verySatisfactoryMale}}</center></td>
                </tr>
                <tr style="font-size: 11px;">
                    <th>Outstanding (90-100)</th>
                    <td>
                        @php
                            $outstandingMale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="male")
                                @if($student->generalaverage >= 90 && $student->generalaverage <= 100 && $student->generalaverage!=0)
                                    @php
                                        $outstandingMale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$outstandingMale}}</center>
                    </td>
                    <td>
                        @php
                            $outstandingFemale = 0;
                        @endphp
                        @foreach ($students as $student)
                            @if(strtolower($student->gender)=="female")
                                @if($student->generalaverage >= 90 && $student->generalaverage <= 100 && $student->generalaverage!=0)
                                    @php
                                        $outstandingFemale+=1;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        <center>{{$outstandingFemale}}</center>
                    </td>
                    <td><center>{{$outstandingFemale + $outstandingMale}}</center></td>
                </tr>
            </table>
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <table style="width: 100%; font-size: 11px; page-break-inside: avoid;">
                <tr>
                    <td style="border:hidden !important;">PREPARED BY:</td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid black; text-align: center;">
                        <div style="line-height: 5px;"></div>
                        <center>{{$getTeacherName->firstname}} {{$getTeacherName->middlename[0]}}. {{$getTeacherName->lastname}} {{$getTeacherName->suffix}}</center>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">            
                        <span>Class Adviser
                            <br>(Name and Signature) 
                        </span>
                    </td>
                </tr>
            </table>
            <br/>
            <div style="line-height: 30px;"></div>
            <table style="width: 100%; font-size: 11px; page-break-inside: avoid;">
                <tr>
                    <td style="border:hidden !important;">CERTIFIED CORRECT & SUBMITTED:</td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid black;  text-align: center; text-transform: uppercase;">
                        <div style="line-height: 5px;"></div>
                        {{$getSchoolInfo[0]->authorized}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">            
                        <span>School Head
                            <br>(Name and Signature) 
                        </span>
                    </td>
                </tr>
            </table>
            <br/>
            <div style="line-height: 30px;"></div>
            <table style="width: 100%; font-size: 11px; page-break-inside: avoid;">
                <tr>
                    <td style="border:hidden !important;">REVIEWED BY:</td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid black;">
                        <div style="line-height: 5px;"></div>
                        <div style="font-size: 11px !important; text-align: center;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') MAJARANI MACARAEG JACINTO ED.D,CESO VI @endif</div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">            
                        <span>Division Representative
                            <br>(Name and Signature) 
                        </span>
                    </td>
                </tr>
            </table>
            <br/>&nbsp;
            <br/>&nbsp;
            <div style="font-size: 10px; text-align: justify; line-height: 11px;">
                <p><strong>GUIDELINES:</strong></p>
                <p><span>1. For All Grade/Year Levels</span></p>
                <p><span>2. To be prepared by the Adviser. The Adviser should indicate the General Average based on the learner's Form 138.</span></p>
                <p><span>3. On the summary table, reflect the total number of learners PROMOTED (Final Grade of at least <strong>75 in ALL learning areas</strong>), RETAINED (Did not Meet Expectations in <strong>three (3) or more learning areas</strong>) and *CONDITIONAL (*Did Not Meet Expectations in <strong>not more than two (2) learning areas</strong>) and the Learning Progress and Achievements according to the individual General Average. All provisions on classroom assessment and the grading system in the said Order shall be in effect for all grade levels - Deped Order 29, s. 2015.</span></p>
                <p><span>4. Must tally with the total enrollment report as of End of School Year GESP /GSSP (EBEIS)</span></p>
                <p><span>5. Potocols of validation & submission is under the discretion of the Schools Division Superintendent.</span></p>
                <p><span>School Form 5: Page ____ of ________	</span>
</p>
            </div>
        </td>
    </tr>        
</table>