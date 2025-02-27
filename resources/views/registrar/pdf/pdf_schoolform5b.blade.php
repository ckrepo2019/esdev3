<style>
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
/* 
    .summaryTable th,
    .summaryTable td    { border: 1px solid black; border-bottom: hidden; border-right: hidden; }

    .learningProgress   { padding-left: 10px; padding-top: 20px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px;
    }

    .learningProgress th,
    .learningProgress td{ border: 1px solid black; border-bottom: hidden; border-right: hidden;
    } */

    .summaryTable       {  padding-left: 10px; padding-top: 95px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px;  table-layout: fixed;}

    .summaryTable th,
    .summaryTable td    { border: 1px solid black; border-bottom: hidden; border-right: hidden; }

    .learningProgress   { padding-left: 10px; padding-top: 20px; width: 100%; text-align: center; border-spacing: 0; font-size: 13px; }

    .learningProgress th,
    .learningProgress td{ border: 1px solid black; border-bottom: hidden; border-right: hidden; }

    .nobreak            { page-break-inside: avoid; }

    .col-container      { display: table; width: 100%; }
    .col                { display: table-cell; }
    .left               { float:left; width:69%; }
    .right              { float: right; width: 30%; }

    .tableLeft1         { width: 100%; border: 1px solid; border-spacing: 0; border-left: hidden; border-top: hidden; font-size: 12px; text-align: center; }

    .tableLeft1 td,
    .tableLeft1 th      { border: 1px solid; border-right: hidden; border-bottom: hidden; }

    #pSpan span         { display: block; visibility: hidden; }

    #guidelines         { font-size: 12px; }

    
    .rotate div {
            -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -10em;
                margin-right: -10em;
        }
</style>
@php
    $init = 17;
    $countMale = 0;
    $countFemale = 0;
    $complete_twoyears_male = 0;
    $complete_abovetwoyears_male = 0;
    $complete_twoyears_female = 0;
    $complete_abovetwoyears_female = 0;
@endphp
<div class="nobreak">
    <table id="logo">
        <tr>
            <td >
                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
            </td>
            <td style="width: 70%">
                <h2><center>School Form 5B List of Learners with Complete SHS Requirements (SF5B-SHS)</center></h2>
                {{-- <h2><center>School Form 5B<br>List of Learners with Complete SHS Requirements<br>(SF5A-SHS)</center></h2> --}}
            </td>
            <td style="text-align:right">
                <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="150px">
            </td>
        </tr>
    </table>
    <table id="header" style="table-layout: fixed;">
        <tr>
            <td class=""  style="width:60%">School Name</td>
            <td id="cellCenter"  ><div class="box">{{$getSchoolInfo[0]->schoolname}}</div></td>
            <td class="" style="width:50%">School ID</td>
            <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->schoolid}}</div></td>
            <td class="" style="width:50%">District</td>
            <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->district}}</div></td>
            <td class="" style="width:50%">Division</td>
            <td id="cellCenter" ><div class="box">{{$getSchoolInfo[0]->division}}</div></td>
            <td class=""style="width:50%">Region</td>
            <td id="cellCenter"><div class="box">{{$getSchoolInfo[0]->region}}</div></td>
        </tr>
        <tr>
            <td class="labels">Semester</td>
            <td colspan="2" id="cellCenter"><div class="box">{{$getSemester[0]->semester}}</div></td>
            <td class="labels">School Year</td>
            <td id="cellCenter"><div class="box">{{$getSchoolYear[0]->sydesc}}</div></td>
            {{-- <td class="labels">Grade Level</td>
            <td id="cellCenter"><div class="box">{{$getSectionAndLevel[0]->levelname}}</div></td> --}}
            <td class="labels" colspan="2">Section</td>
            <td id="cellCenter" colspan="2"><div class="box">{{$getSectionAndLevel[0]->sectionname}}</div></th>
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
            <td colspan="4">
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
                            <th style="width: 5%;" class="rotate"><div>Completed SHS<br>in2 SYs? (Y/N)</div></th>
                            <th style="width: 15%;">National<br>Certification Level<br>Attained<br>(only if applicable)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" style="text-align: none;background-color:lightgrey;padding-left:5px;">MALE</td>
                        </tr>
                        @foreach ($filterArray as $student)
                            @if(strtoupper($student->gender) == 'MALE')
                                @php
                                    $countMale+=1;   
                                @endphp
                                <tr>
                                    <td>{{$countMale.'.'}}</td>
                                    <td>{{$student->lrn}}</td>
                                    <td>{{$student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename[0]}}</td>
                                    <td>
                                        <center>
                                            @if ($student->status == 'COMPLETE')
                                                @php
                                                    $complete_twoyears_male+=1;   
                                                @endphp
                                                Y
                                            @elseif ($student->status == 'INCOMPLETE')
                                                {{-- y --}}
                                            @elseif ($student->status == 'OVERSTAYING')
                                                @php
                                                    $complete_abovetwoyears_male+=1;   
                                                @endphp
                                                N
                                            @endif
                                        </center>
                                    </td>
                                    <td class="p-0">
                                        @foreach ($certificationattained as $certificate)
                                            @if(($student->lastname.'-'.$student->firstname) == $certificate->name)
                                                {{$certificate->certificate}}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @for($x = $countMale; $x<$init; $x++)
                            <tr>
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
                            <th colspan="4">SUMMARY TABLE A</th>
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
                            <th>Learners who<br>completed SHS<br>Program within 2<br>SYs or 4<br>semesters</th>
                            <th>{{$complete_twoyears_male}}</th>
                            <th>{{$complete_twoyears_female}}</th>
                            <th>{{$complete_twoyears_male + $complete_twoyears_female}}</th>
                        </tr>
                        <tr>
                            <th>Learners who<br>completed SHS<br>Program in more<br>than 2 SYs or 4<br>semesters</th>
                            <th>{{$complete_abovetwoyears_male}}</th>
                            <th>{{$complete_abovetwoyears_female}}</th>
                            <th>{{$complete_abovetwoyears_male + $complete_abovetwoyears_female}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>{{$complete_twoyears_male + $complete_abovetwoyears_male}}</th>
                            <th>{{$complete_twoyears_female + $complete_abovetwoyears_female}}</th>
                            <th>{{($complete_twoyears_male + $complete_twoyears_female) + ($complete_abovetwoyears_male + $complete_abovetwoyears_female)}}</th>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="tableLeft1" style="vertical-align: top;">
                    <thead>
                        <tr>
                            <th colspan="4">SUMMARY TABLE B</th>
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
                            <th>NC III</th>
                            <th>{{$ncArray[0]->nciiimale}}</th>
                            <th>{{$ncArray[0]->nciiifemale}}</th>
                            <th>{{$ncArray[0]->nciiitotal}}</th>
                        </tr>
                        <tr>
                            <th>NC II</th>
                            <th>{{$ncArray[0]->nciimale}}</th>
                            <th>{{$ncArray[0]->nciifemale}}</th>
                            <th>{{$ncArray[0]->nciitotal}}</th>
                        </tr>
                        <tr>
                            <th>NC I</th>
                            <th>{{$ncArray[0]->ncimale}}</th>
                            <th>{{$ncArray[0]->ncifemale}}</th>
                            <th>{{$ncArray[0]->ncitotal}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>{{$ncArray[0]->nctotalmale}}</th>
                            <th>{{$ncArray[0]->nctotalfemale}}</th>
                            <th>{{$ncArray[0]->nctotal}}</th>
                        </tr>
                    </tbody>
                </table>
                <small>Note: NCs are recorded here for documentation but is not a requirement for graduation.</small>
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
                        <th style="width: 5%;" class="rotate"><div>Completed SHS<br>in2 SYs? (Y/N)</div></th>
                        <th style="width: 15%;">National<br>Certification Level<br>Attained<br>(only if applicable)</th>
                    </tr>
                    </thead>
                    <tbody><tr>
                        <td colspan="5" style="text-align: none;background-color:lightgrey;padding-left:5px;">FEMALE</td>
                    </tr>
                    @foreach ($filterArray as $student)
                        @if(strtoupper($student->gender) == 'FEMALE')
                            @php
                                $countFemale+=1;   
                            @endphp
                            <tr>
                                <td>{{$countFemale.'.'}}</td>
                                <td>{{$student->lrn}}</td>
                                <td>{{$student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename[0]}}</td>
                                <td>
                                    <center>
                                        @if ($student->status == 'COMPLETE')
                                            @php
                                                $complete_twoyears_female+=1;   
                                            @endphp
                                            Y
                                        @elseif ($student->status == 'INCOMPLETE')
                                            {{-- y --}}
                                        @elseif ($student->status == 'OVERSTAYING')
                                            @php
                                                $complete_abovetwoyears_female+=1;   
                                            @endphp
                                            N
                                        @endif
                                    </center>
                                </td>
                                <td class="p-0">
                                    @foreach ($certificationattained as $certificate)
                                        @if(($student->lastname.'-'.$student->firstname) == $certificate->name)
                                            {{$certificate->certificate}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @for($x = $countFemale; $x<$init; $x++)
                        <tr>
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
                <br>
                <small><strong>GUIDELINES:</strong></small>
                <br>
                <ol>
                    <li> <small>This for should be accomplished by the Class Adviser at End of School Year.</small></li>
                    <li> <small>It should be compiled and checked by the School Head and passed to the Division Office before graduation.</small></li>
                </ol>
                <br>
                <br>
                <small>Prepared by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">
                    <center>
                        {{strtoupper($getTeacherName[0]->firstname.' '.$getTeacherName[0]->middlename[0].'. '.$getTeacherName[0]->lastname.' '.$getTeacherName[0]->suffix)}}
                    </center>
                </div>
                <small><center>Signature of Class Adviser over Printed Name</center></small>
                <br>
                <br>
                <small>Certified Correct & Submitted by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">
                    <center>
                        {{strtoupper($getSchoolInfo[0]->authorized)}}
                    </center>
                </div>
                <small><center>Signature of School Head over Printed Name</center></small>
                <br>
                <br>
                <small>Reviewed by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">
                    <center>{{strtoupper($divisionrep)}}</center>
                </div>
                <small><center>Signature of Division Representative over Printed Name</center></small>
            </div>
        </div>
    </div>
</div>