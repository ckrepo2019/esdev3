
<style>
    #header, #header td{
        font-size: 13px;
        text-align: right;
        width: 100%;
        table-layout: fixed;
    }
    h2{
        margin:5px;
    }
    #cellCenter{
        text-align: center !important;
    }
    div.box{
        border: 1px solid black;
        padding: 5px;
    }
    .leftImg{
        text-align: left !important;
    }
    .labels{
        width: 60% !important;
    }
    .left{
        float:left;
        width:70%;
    }
    .right{
        float: right;
        width: 30%;
    }
    .studentsTable{
        border: 1px solid black;
        width: 100%;
        text-align: center;
        border-collapse: collapse;
        font-size: 13px;
    }
    .studentsTable th{
        padding-top: 15px;
        padding-bottom: 15px;
        border: 1px solid black;
    }
    .studentsTable td{
        border: 1px solid black;
        text-transform: uppercase;
    }
    .page_break { 
            page-break-before: always; 
    }
    .summaryTable{
        padding-left: 10px;
        padding-top: 95px;
        width: 100%;
        text-align: center;
        border-spacing: 0;
        font-size: 13px;
    }
    .summaryTable th, .summaryTable td{
        border: 1px solid black;
        border-bottom: hidden;
        border-right: hidden;
    }
    .learningProgress{
        padding-left: 10px;
        padding-top: 20px;
        width: 100%;
        text-align: center;
        border-spacing: 0;
        font-size: 13px;
    }
    .learningProgress th, .learningProgress td{
        border: 1px solid black;
        border-bottom: hidden;
        border-right: hidden;
    }
    .classAdviser{
        width: 100%;
        padding-top: 95px;
        padding-left: 10px;
        font-size: 13px;
    }
    .schoolHead{
        width: 100%;
        padding-top: 25px;
        padding-left: 10px;
        font-size: 13px;
    }
    .divRepresentative{
        width: 100%;
        padding-top: 25px;
        padding-left: 10px;
        font-size: 13px;
    }
    .guidelines{
        width: 100%;
        padding-top: 10px;
        padding-left: 10px;
        font-size: 13px;
    }
    .rightBorder{
        border-right: 1px solid black !important;
    }
    .bottomBorder{
        border-bottom: 1px solid black !important;
    }
    .bottom {
        position: absolute;
        bottom: 0;
    }
</style>
<h2><center>School Form 5 (SF5) Report on Promotion and Learning Progress & Achievement</center></h2>
<small><em><center>Revised to confom with the instructions of Deped Order 8, s. 2015</center></em></small>
<table id="header">
    <tr>
        <td rowspan="2" class="leftImg labels">
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
        </td>
        <td class="labels">Region</td>
        <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->region}}</div></td>
        <td class="labels">Division</td>
        <td colspan="2" id="cellCenter"><div class="box">{{$getSchoolInfo[0]->division}}</div></td>
        <td class="labels">District</td>
        <td colspan="2" id="cellCenter"><div class="box">{{$getSchoolInfo[0]->district}}</div></td>
        <td class="labels"></td>
    </tr>
    <tr>
        <td class="labels">School ID</td>
        <td colspan="2" id="cellCenter"><div class="box">{{$getSchoolInfo[0]->schoolid}}</div></td>
        <td class="labels">School Year</td>
        <td id="cellCenter"><div class="box">{{$getSchoolYear[0]->sydesc}}</div></td>
        <td class="labels">Curiculum</td>
        <td colspan="2" id="cellCenter"><div class="box">&nbsp;</div></td>
        <td class="labels"></td>
    </tr>
    <tr>
        <td colspan="2" class="labels">School Name</td>
        <td colspan="4" id="cellCenter"><div class="box">{{$getSchoolInfo[0]->schoolname}}</div></td>
        <td class="labels">Grade Level</td>
        <td id="cellCenter"><div class="box">{{$getSectionAndLevel[0]->levelname}}</div></td>
        <td class="labels">Section</td>
        <td id="cellCenter"><div class="box">{{$getSectionAndLevel[0]->sectionname}}</div></th>
    </tr>
</table>
<br>
<div>
    <div class="left">
        <table class="studentsTable">
            <tr>
                <th>LRN</th>
                <th>LEARNER'S NAME<br><sup>(Last Name, First Name, Middle Name)</sup></th>
                <th>GENERAL<br>AVERAGE<br><sup>(Whole numbers for non-honor)</sup></th>
                <th>ACTION TAKEN:<br>PROMOTED,<br>CONDITIONAL, or<br>RETAINED</th>
                <th>Did Not Meet Expectations of the<br>ff. Learning Area/s as of end of<br>current School Year</th>
            </tr>
            @php
                $countMale = 0;   
            @endphp
            @foreach ($finalGrades as $student)
                @if(strtoupper($student[1]->gender)=="MALE")
                    <tr>
                        <td>{{$student[1]->lrn}}</td>
                        <td>{{$student[1]->lastname}}, {{$student[1]->firstname}} {{$student[1]->middlename[0]}}.</td>
                        <td>
                            @if($student[0]->Final>=90)
                            <center>{{round($student[0]->Final)}}</center>
                            @else
                            <center>{{round($student[0]->Final, 2)}}</center>
                            @endif
                        </td>
                        <td><center>{{$student[2]}}</center></td>
                        <td>
                            @if(isset($student[3]))
                            @foreach($student[3] as $failedSubject)
                            {{$failedSubject[0]}}<br>
                            @endforeach
                            @endif
                        </td>
                    </tr>
                    @php
                        $countMale+=1
                    @endphp
                @endif
            @endforeach
            @php
                    $male=0;   
            @endphp
            @foreach ($finalGrades as $student)
                @if(strtoupper($student[1]->gender)=="MALE")
                        @php
                        $male+=1
                    @endphp
                @endif
            @endforeach
            @while ($male <= 17)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @php
                    $male+=1;   
                @endphp
            @endwhile
            <tr>
                <td></td>
                <td>TOTAL MALE</td>
                <td>
                    <center>{{$countMale}}</center>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="right">
        <table class="summaryTable">
            <tr>
                <th colspan="4" class="rightBorder">SUMMARY</th>
            </tr>
            <tr>
                <th>STATUS</th>
                <th>MALE</th>
                <th>FEMALE</th>
                <th class="rightBorder">TOTAL</th>
            </tr>
            <tr>
                <th>PROMOTED</th>
                <td>
                    @php
                        $promotedMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[2] == "PROMOTED")
                                @php
                                    $promotedMale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$promotedMale}}</center>
                </td>
                <td>
                    @php
                        $promotedFemale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[2] == "PROMOTED")
                                @php
                                    $promotedFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$promotedFemale}}</center>
                </td>
                <td class="rightBorder"><center>{{$promotedMale + $promotedFemale}}</center></td>
            </tr>
            <tr>
                <th>*Conditional</th>
                <td>
                    @php
                        $conditionalMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[2] == "CONDITIONAL")
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
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[2] == "CONDITIONAL")
                                @php
                                    $conditionalFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$conditionalFemale}}</center>
                </td>
                <td class="rightBorder"><center>{{$conditionalFemale + $conditionalMale}}</center></td>
            </tr>
            <tr>
                <th class="bottomBorder">RETAINED</th>
                <td class="bottomBorder">
                    @php
                        $retainedMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[2] == "RETAINED")
                                @php
                                    $retainedMale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$retainedMale}}</center>
                </td>
                <td class="bottomBorder">
                    @php
                        $retainedFemale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[2] == "RETAINED")
                                @php
                                    $retainedFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$retainedFemale}}</center>
                </td>
                <td class="bottomBorder rightBorder"><center>{{$retainedFemale + $retainedMale}}</center></td>
            </tr>
        </table>
        <table class="learningProgress">
            <tr>
                <th colspan="4" class="rightBorder">LEARNING PROCESS AND ACHIEVEMENT<br>(Based on Learner's General Average)</th>
            </tr>
            <tr>
                <th>Descriptors & Grading Scale</th>
                <th>MALE</th>
                <th>FEMALE</th>
                <th class="rightBorder">TOTAL</th>
            </tr>
            <tr>
                <th>Did Not Meet Expectations<br>(74 and below)</th>
                <td>
                    @php
                        $didNotMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[0]->Final <= 74 )
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
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[0]->Final <= 74 )
                                @php
                                    $didNotFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$didNotFemale}}</center>
                </td>
                <td class="rightBorder"><center>{{$didNotFemale + $didNotMale}}</center></td>
            </tr>
            <tr>
                <th>Fairly Satisfactory<br>(75-79)</th>
                <td>
                    @php
                        $fairlyMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[0]->Final >= 75 && $student[0]->Final <= 79.99)
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
                    @foreach ($finalGrades as $student)
                   
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[0]->Final >= 75 && $student[0]->Final <= 79.99)
                                @php
                                    $fairlyFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$fairlyFemale}}</center>
                </td>
                <td class="rightBorder"><center>{{$fairlyFemale + $fairlyMale}}</center></td>
            </tr>
            <tr>
                <th>Satisfactory<br>(80-84)</th>
                <td>
                    @php
                        $satisfactoryMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[0]->Final >= 80 && $student[0]->Final <= 84.99)
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
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[0]->Final >= 80 && $student[0]->Final <= 84.99)
                                @php
                                    $satisfactoryFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$satisfactoryFemale}}</center>
                </td>
                <td class="rightBorder"><center>{{$satisfactoryFemale + $satisfactoryMale}}</center></td>
            </tr>
            <tr>
                <th>Very Satisfactory<br>(85-89)</th>
                <td>
                    @php
                        $verySatisfactoryMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[0]->Final >= 85 && $student[0]->Final <= 89.99)
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
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[0]->Final >= 85 && $student[0]->Final <= 89.99)
                                @php
                                    $verySatisfactoryFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$verySatisfactoryFemale}}</center>
                </td>
                <td class="rightBorder"><center>{{$verySatisfactoryFemale + $verySatisfactoryMale}}</center></td>
            </tr>
            <tr>
                <th class="bottomBorder">Outstanding (90-100)</th>
                <td class="bottomBorder">
                    @php
                        $outstandingMale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="MALE")
                            @if($student[0]->Final >= 90 && $student[0]->Final <= 100)
                                @php
                                    $outstandingMale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$outstandingMale}}</center>
                </td>
                <td class="bottomBorder">
                    @php
                        $outstandingFemale = 0;
                    @endphp
                    @foreach ($finalGrades as $student)
                        @if(strtoupper($student[1]->gender)=="FEMALE")
                            @if($student[0]->Final >= 90 && $student[0]->Final <= 100)
                                @php
                                    $outstandingFemale+=1;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <center>{{$outstandingFemale}}</center>
                </td>
                <td class="bottomBorder rightBorder"><center>{{$outstandingFemale + $outstandingMale}}</center></td>
            </tr>
        </table>
    </div>
</div>
<div class="page_break"></div>
<div>
    <div class="left">
        <table class="studentsTable">
            <tr>
                <th>LRN</th>
                <th>LEARNER'S NAME<br><sup>(Last Name, First Name, Middle Name)</sup></th>
                <th>GENERAL<br>AVERAGE<br><sup>(Whole numbers for non-honor)</sup></th>
                <th>ACTION TAKEN:<br>PROMOTED,<br>CONDITIONAL, or<br>RETAINED</th>
                <th>Did Not Meet Expectations of the<br>ff. Learning Area/s as of end of<br>current School Year</th>
            </tr>
            @php
                $countFemale = 0;   
            @endphp
            @foreach ($finalGrades as $student)
                @if(strtoupper($student[1]->gender)=="FEMALE")
                    <tr>
                        <td>{{$student[1]->lrn}}</td>
                        <td>{{$student[1]->lastname}}, {{$student[1]->firstname}} {{$student[1]->middlename[0]}}.</td>
                        <td>
                            @if($student[0]->Final>=90)
                            <center>{{round($student[0]->Final)}}</center>
                            @else
                            <center>{{round($student[0]->Final, 2)}}</center>
                            @endif
                        </td>
                        <td><center>{{$student[2]}}</center></td>
                        <td>
                            @if(isset($student[3]))
                            @foreach($student[3] as $failedSubject)
                            {{$failedSubject[0]}}<br>
                            @endforeach
                            @endif
                        </td>
                    </tr>
                    @php
                        $countFemale+=1
                    @endphp
                @endif
            @endforeach
            @php
                    $female=0;   
            @endphp
            @foreach ($finalGrades as $student)
                @if(strtoupper($student[1]->gender)=="FEMALE")
                        @php
                        $female+=1
                    @endphp
                @endif
            @endforeach
            @while ($female <= 31)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @php
                    $female+=1;   
                @endphp
            @endwhile
            <tr>
                <td></td>
                <td>TOTAL FEMALE</td>
                <td>
                    <center>{{$countFemale}}</center>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="right">
            <table class="classAdviser">
            <tr>
                <td style="border:hidden !important;">PREPARED BY:</td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid black;">
                    <br>
                    <center>{{$getTeacherName[0]->firstname}} {{$getTeacherName[0]->middlename[0]}}. {{$getTeacherName[0]->lastname}} {{$getTeacherName[0]->suffix}}</center>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">            
                    <sup>Class Adviser<br>(Name and Signature)</sup>
                </td>
            </tr>
        </table>
        <table class="schoolHead">
            <tr>
                <td style="border:hidden !important;">CERTIFIED CORRECT & SUBMITTED:</td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid black;">
                    <br>
                    <center>{{$getPrincipal[0]->firstname}} {{$getPrincipal[0]->middlename[0]}}. {{$getPrincipal[0]->lastname}} {{$getPrincipal[0]->suffix}}</center>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">            
                    <sup>School Head<br>(Name and Signature)</sup>
                </td>
            </tr>
        </table>
        <table class="divRepresentative">
            <tr>
                <td style="border:hidden !important;">REVIEWED BY:</td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid black;">
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">            
                    <sup>Division Representative<br>(Name and Signature)</sup>
                </td>
            </tr>
        </table>
        <div class="guidelines bottom">
            <p><strong>GUIDELINES:</strong></p>
            <p><small><em><strong>1.Do not include Dropouts and Transfered Out (D.O.4, 2014)</strong></em></small></p>
            
            <p><small>2. To be prepared by the Adviser. The Adviser should indicate the General Average based on the learner's Form 138.</small></p>

            <p><small>3. On the summary table, reflect the total number of learners PROMOTED (Final Grade of at least <strong>75 in ALL learning areas</strong>), RETAINED (Did not Meet Expectations in <strong>three (3) or more learning areas</strong>) and *CONDITIONAL (*Did Not Meet Expectations in <strong>not more than two (2) learning areas</strong>) and the Learning Progress and Achievements accoding to the individual General Average. All provisions on classroom assessment and the grading system in the said Order shall be in effect for all grade levels - Deped Order 29, s. 2015.</small></p>
            
            <p><small>4. Did Not Meet Expectations of the Learning Areas. This refers to learning area/s that the learner had failed as of end of curent SY. The learner may be for remediation or retention.</small></p>
            
            <p><small>5. Potocols of validation & submission is under the discretion of the Schools Division Superintendent..</small></p>
        </div>
    </div>
</div>
    