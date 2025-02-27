<style>
    *{
        
        font-family: Arial, Helvetica, sans-serif;
    }
    #header,
    #header td          { font-size: 11px; text-align: right; width: 100%;text-transform: uppercase; border-collapse: collapse}

    h2                  { margin:5px; }

    #logo               { width: 100%; table-layout: fixed; font-size: 13px;}


    #cellCenter         { text-align: center !important; }

    div.box             { border: 1px solid black; padding: 5px; }

    .leftImg            { text-align: left !important; }

    .labels             { width: 50% !important; }

    .page_break         {  page-break-before: always;   }
    
    .studentsTable      { border: 1px solid black; width: 100%; text-align: center; border-collapse: collapse; font-size: 12px; table-layout: fixed;}

    .studentsTable th   { padding-top: 15px; padding-bottom: 15px; border: 1px solid black; }

    .studentsTable td   { border: 1px solid black; text-transform: uppercase; }

    .summaryTable       { padding-left: 10px; padding-top: 95px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px;
    }

    .summaryTable th,
    .summaryTable td    { border: 1px solid black; border-bottom: hidden; border-right: hidden; }

    .learningProgress   { padding-left: 10px; padding-top: 20px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px;
    }

    .learningProgress th,
    .learningProgress td{ border: 1px solid black; border-bottom: hidden; border-right: hidden;
    }

    .summaryTable       {  padding-left: 10px; padding-top: 95px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px; }

    .summaryTable th,
    .summaryTable td    { border: 1px solid black; border-bottom: hidden; border-right: hidden; }

    .learningProgress   { padding-left: 10px; padding-top: 20px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px; }

    .learningProgress th,
    .learningProgress td{ border: 1px solid black; border-bottom: hidden; border-right: hidden; }

    .nobreak            { page-break-inside: avoid; }

    .col-container      { display: table; width: 100%; }
    .col                { display: table-cell; }
    .col1               { width: 70%; }
    .col2               { width: 30%; }

    .left               { float:left; width:69%; }
    .right              { float: right; width: 30%; }

    .tableLeft1         { width: 100%; border: 1px solid; border-spacing: 0; border-left: hidden; border-top: hidden; font-size: 12px; text-align: center; table-layout: fixed;}

    .tableLeft1 td,
    .tableLeft1 th      { border: 1px solid; border-right: hidden; border-bottom: hidden; }

    #pSpan span         { display: block; visibility: hidden; }

    #guidelines         { font-size: 12px; }
</style>
@php
    $countMale = 0;
    $countFemale = 0;
    $init = 17;
    $firstsemcompletemale = 0;
    $firstsemcompletefemale = 0;
    $firstsemincompletemale = 0;   
    $firstsemincompletefemale = 0;   
    $secondsemcompletemale = 0;
    $secondsemcompletefemale = 0;
    $secondsemincompletemale = 0;  
    $secondsemincompletefemale = 0;  
@endphp
<div class="nobreak">
    <table id="logo">
        <tr>
            <td >
                <img src="{{base_path()}}/public/{{$getSchoolInfo[0]->picurl}}" alt="school" width="80px">
            </td>
            <td style="width: 70%">
                <h2><center>School Form 5A<br>End of Semester and School Year Status of Learners for Senior High School<br>(SF5A-SHS)</center></h2>
            </td>
            <td style="text-align:right">
                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
            </td>
        </tr>
    </table>
    <table id="header" style="table-layout: fixed;">
        <tr>
            <td class=""  style="">School Name</td>
            <td id="cellCenter" colspan="3"><div class="box">{{$getSchoolInfo[0]->schoolname}}</div></td>
            <td class="" style="">School ID</td>
            <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->schoolid}}</div></td>
            <td class="" style="">District</td>
            <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->district}}</div></td>
            <td class="" style="">Division</td>
            <td id="cellCenter" ><div class="box">{{$getSchoolInfo[0]->division}}</div></td>
            <td class=""style="">Region</td>
            <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->region}}</div></td>
        </tr>
        <tr>
            <td class="labels" colspan="2">Semester</td>
            <td colspan="2" id="cellCenter"><div class="box">{{$getSemester[0]->semester}}</div></td>
            <td class="labels">School Year</td>
            <td id="cellCenter"><div class="box">{{$getSchoolYear[0]->sydesc}}</div></td>
            <td class="labels">Grade Level</td>
            <td id="cellCenter"><div class="box">{{$getSectionAndLevel[0]->levelname}}</div></td>
            <td class="labels">Section</td>
            <td id="cellCenter" colspan="3"><div class="box">{{$getSectionAndLevel[0]->sectionname}}</div></th>
        </tr>
        <tr>
            <td class="labels" colspan="2">Track and  Strand</td>
            <td colspan="3" id="cellCenter">
                @if(isset($trackAndStrands))
                    <div class="box">
                        @foreach ($trackAndStrands as $track)
                            {{$track['track'].' - '.$track['strand']}}
                            <br>
                        @endforeach
                    </div>
                @else
                    <div class="box">
                        &nbsp;
                    </div>
                @endif
            </td>
            <td class="labels">Course/s (only for TVL)</td>
            <td colspan="6">
                <div class="box">&nbsp;</div>
            </td>
        </tr>
    </table>
    <br>
    <div style="width: 100%;">
        <div>
            <div class="left">
                <table class="studentsTable">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 15%">LRN</th>
                            <th style="width: 30%">LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</sup></th>
                            <th style="width: 30%">BACK SUBJECTS<br>List down subjects where learner obtain a rating below 75%</sup></th>
                            <th style="width: 15%">END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                            <th style="width: 15%">END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: none;background-color:lightgrey;padding-left:5px;">MALE</td>
                        </tr>
                        @if($getSemester[0]->id == 1)
                            @foreach ($gradesArray[0]['firstsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                    @php
                                        $countMale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countMale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->suffix.' '.$grade['studentdata']->middlename[0]}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif --}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @elseif($getSemester[0]->id == 2)
                            @foreach ($gradesArray[0]['secondsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                    @php
                                        $countMale+=1;   
                                    @endphp
                                    <tr>
                                        <td>{{$countMale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->suffix.' '.$grade['studentdata']->middlename[0]}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        @for($x = $countMale; $x<$init; $x++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
  
            <div class="right" style="padding-top:15%;position: relative">
                <table class="tableLeft1" >
                    <thead>
                        <tr>
                            <th colspan="4">SUMMARY TABLE 1ST SEM</th>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>COMPLETE</th>
                            <th>
                                    @foreach ($gradesArray[0]['firstsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                            @if(count($grade['backsubjects']) == 0)
                                                @php
                                                    $firstsemcompletemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$firstsemcompletemale}}
                            </th>
                            <th>
                                    @foreach ($gradesArray[0]['firstsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                            @if(count($grade['backsubjects']) == 0)
                                                @php
                                                    $firstsemcompletefemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$firstsemcompletefemale}}
                            </th>
                            <th>{{$firstsemcompletemale + $firstsemcompletefemale}}</th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
                            <th>
                                    @foreach ($gradesArray[0]['firstsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                            @if(count($grade['backsubjects']) != 0)
                                                @php
                                                    $firstsemincompletemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$firstsemincompletemale}}
                            </th>
                            <th>
                                    @foreach ($gradesArray[0]['firstsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                            @if(count($grade['backsubjects']) != 0)
                                                @php
                                                    $firstsemincompletefemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$firstsemincompletefemale}}
                            </th>
                            <th>{{$firstsemincompletemale + $firstsemincompletefemale}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>{{$firstsemcompletemale + $firstsemincompletemale}}</th>
                            <th>{{$firstsemcompletefemale + $firstsemincompletefemale}}</th>
                            <th>{{($firstsemcompletemale + $firstsemcompletefemale) + ($firstsemincompletemale + $firstsemincompletefemale)}}</th>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="tableLeft1" >
                    <thead>
                        <tr>
                            <th colspan="4">SUMMARY TABLE 2ND SEM</th>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>COMPLETE</th>
                            <th>
                                    @foreach ($gradesArray[0]['secondsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                            @if(count($grade['backsubjects']) == 0)
                                                @php
                                                    $secondsemcompletemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$secondsemcompletemale}}
                            </th>
                            <th>
                                    @foreach ($gradesArray[0]['secondsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                            @if(count($grade['backsubjects']) == 0)
                                                @php
                                                    $secondsemcompletefemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$secondsemcompletefemale}}
                            </th>
                            <th>{{$secondsemcompletemale + $secondsemcompletefemale}}</th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
                            <th>
                                    @foreach ($gradesArray[0]['secondsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='MALE')
                                            @if(count($grade['backsubjects']) != 0)
                                                @php
                                                    $secondsemincompletemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$secondsemincompletemale}}
                            </th>
                            <th>
                                    @foreach ($gradesArray[0]['secondsem'] as $grade)
                                        @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                            @if(count($grade['backsubjects']) != 0)
                                                @php
                                                    $secondsemincompletefemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                {{$secondsemincompletefemale}}
                            </th>
                            <th>{{$secondsemincompletemale + $secondsemincompletefemale}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>{{$secondsemcompletemale + $secondsemincompletemale}}</th>
                            <th>{{$secondsemcompletefemale + $secondsemincompletefemale}}</th>
                            <th>{{($secondsemcompletemale + $secondsemcompletefemale) + ($secondsemincompletemale + $secondsemincompletefemale)}}</th>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="tableLeft1" >
                    <thead>
                        <tr>
                            <th colspan="4">SUMMARY TABLE (End of School Year Only)</th>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>REGULAR</th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{$firstsemcompletemale + $secondsemcompletemale}}
                                @endif
                            </th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{$firstsemcompletefemale + $secondsemcompletefemale}}</th>
                                @endif
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemcompletefemale + $secondsemcompletefemale)}}
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th>IRREGULAR</th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{$firstsemincompletemale + $secondsemincompletemale}}
                                @endif
                            </th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{$firstsemincompletefemale + $secondsemincompletefemale}}</th>
                                @endif
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{($firstsemincompletemale + $secondsemincompletemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)}}
                                @endif
                            </th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                                @endif
                            </th>
                            <th>
                                @if($getSemester[0]->id == 2)
                                    {{(($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)) + (($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale))}}
                                @endif
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page_break"></div>
        <div >
            <div class="left">
                
                <table class="studentsTable">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 15%">LRN</th>
                            <th style="width: 30%">LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</sup></th>
                            <th style="width: 30%">BACK SUBJECTS<br>List down subjects where learner obtain a rating below 75%</sup></th>
                            <th style="width: 15%">END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                            <th style="width: 15%">END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: none;background-color:lightgrey;padding-left:5px;">FEMALE</td>
                        </tr>
                        @if($getSemester[0]->id == 1)
                            @foreach ($gradesArray[0]['firstsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                        @php
                                            $countFemale+=1;   
                                        @endphp
                                    <tr>
                                        <td>{{$countFemale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->suffix.' '.$grade['studentdata']->middlename[0]}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif --}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @elseif($getSemester[0]->id == 2)
                            @foreach ($gradesArray[0]['secondsem'] as $grade)
                                @if (strtoupper($grade['studentdata']->gender)=='FEMALE')
                                        @php
                                            $countFemale+=1;   
                                        @endphp
                                    <tr>
                                        <td>{{$countFemale}}</td>
                                        <td>{{$grade['studentdata']->lrn}}</td>
                                        <td>{{$grade['studentdata']->lastname.', '.$grade['studentdata']->firstname.' '.$grade['studentdata']->suffix.' '.$grade['studentdata']->middlename[0]}}</td>
                                        <td>
                                            @foreach ($grade['backsubjects'] as $backsubjects)
                                                {{$backsubjects->subjtitle}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                                {{-- {{$firstsemincomplete}} --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if (count($grade['backsubjects'])==0)
                                                <center>REGULAR</center>
                                            @else
                                                <center>IRREGULAR</center>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        @for($x = $countFemale; $x<$init; $x++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
  
            <div class="right" style="padding-top:15%;">
                <small>Prepared by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">
                    <center>{{strtoupper($getTeacherName[0]->firstname.' '.$getTeacherName[0]->middlename[0].'. '.$getTeacherName[0]->lastname.' '.$getTeacherName[0]->suffix)}}</center>
                </div>
                <small><center>Signature of Class Adviser over Printed Name</center></small>
                <br>
                <br>
                <small>Certified Correct by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">
                    <center>{{strtoupper($getSchoolInfo[0]->authorized)}}</center>
                </div>
                <small><center>Signature of School Head over Printed Name</center></small>
                <br>
                <br>
                <small>Reviewed by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;"><center>{{strtoupper($divisionrep)}}</center></div>
                <small><center>Signature of Division Representative over Printed Name</center></small>
            </div>
        </div>
        
<p id="pSpan"><span>hello</span><span>How are you</span></p>
    </div>
    
<br>
</div>
<br>

<p id="pSpan"><span>hello</span><span>How are you</span></p>
<br>
<br>
<br>
<span id="guidelines">
    <strong>
        GUIDELINES:
    </strong>
    <br>
    <em>
        This form shall be accomplished after each semester in a school year,  leaving the End of School Year Status Column and Summary Table for End of School Year Status blank/unfilled at the end of the 1st Semester.  These data elements shall be filled up only after the 2nd semester or at the end of the School Year. 
    </em>
<br>
<br>
    <strong>
        INDICATORS:
    </strong>
    <br>
    <em>
        <strong>
            End of Semester Status
        </strong>
    </em>
    <br>
    <span style="padding-left:5%"> 
        <strong>Complete</strong> - number of learners who completed/satisfied the requirements in all subject areas (with grade of at least 75%)
    </span>
    <br>
    <span style="padding-left:5%"> 
        <strong>Incomplete</strong> - number of learners who did not meet expectations in one or more subject areas, regardless of number of subjects failed (with grade less than 75%)
    </span>
    <br>
    <span style="padding-left:5%"> 
        <em>
            <strong>Note:</strong> Do not include learners who are No Longer in School (<strong>NLS</strong>)
        </em>
    </span>
    <br>
    <br>
    <em>
        <strong>
            End of School Year Status
        </strong>
    </em>
    <br>
    <span style="padding-left:5%"> 
        <strong>Regular</strong> - number of learners who completed/satisfied requirements in all subject areas  both in the 1st and 2nd semester
    </span>
    <br>
    <span style="padding-left:5%"> 
        <strong>Irregular</strong> - number of learners who were not able to satisfy/complete requirements in one or both semesters
    </span>
</span>