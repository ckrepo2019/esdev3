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
    
    .studentsTable      { border: 1px solid black; width: 100%; text-align: center; border-collapse: collapse; font-size: 12px; }

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
            <td colspan="2" id="cellCenter"><div class="box">&nbsp;</div></td>
            <td class="labels">School Year</td>
            <td id="cellCenter"><div class="box">{{$getSchoolYear[0]->sydesc}}</div></td>
            {{-- <td class="labels">Grade Level</td>
            <td id="cellCenter"><div class="box">{{$getSectionAndLevel[0]->levelname}}</div></td> --}}
            <td class="labels" colspan="2">Section</td>
            <td id="cellCenter" colspan="2"><div class="box">{{$getSectionAndLevel[0]->sectionname}}</div></th>
        </tr>
        <tr>
            <td class="labels" colspan="2">Track and  Strand</td>
            <td colspan="3">
                <div class="box">&nbsp;</div>
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
                        @php
                            $countMale = 0;   
                        @endphp
                        @foreach ($finalGrades as $student)
                            @if(($student[1]->gender)=="MALE")
                                <tr>
                                    <td></td>
                                    <td>{{$student[1]->lrn}}</td>
                                    <td>{{$student[1]->lastname}}, {{$student[1]->firstname}} {{$student[1]->middlename[0]}}.</td>
                                    <td>
                                    </td>
                                    <td></td>
                                </tr>
                                @php
                                    $countMale+=1
                                @endphp
                            @endif
                        @endforeach
                        @php
                            $male=0;
                            $female=0;
                            $init = 23;   
                        @endphp
                        @foreach ($finalGrades as $student)
                            @if(($student[1]->gender)=="MALE")
                                @php
                                    $male+=1
                                @endphp
                            @endif
                        @endforeach
                        @for($x = $male; $x<$init; $x++)
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Learners who<br>completed SHS<br>Program in more<br>than 2 SYs or 4<br>semesters</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>NC II</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>NC I</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
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
                    @foreach ($finalGrades as $student)
                        @if( ($student[1]->gender)=="FEMALE")
                            <tr>
                                <td></td>
                                <td>{{$student[1]->lrn}}</td>
                                <td>{{$student[1]->lastname}}, {{$student[1]->firstname}} {{$student[1]->middlename[0]}}.</td>
                                <td>
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($finalGrades as $student)
                        @if(($student[1]->gender)=="FEMALE")
                            @php
                                $female+=1
                            @endphp
                        @endif
                    @endforeach
                    @for($x = $female; $x<$init; $x++)
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
                <div style="width:100%;border-bottom: 1px solid;">&nbsp;</div>
                <small><center>Signature of Class Adviser over Printed Name</center></small>
                <br>
                <br>
                <small>Certified Correct & Submitted by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">&nbsp;</div>
                <small><center>Signature of School Head over Printed Name</center></small>
                <br>
                <br>
                <small>Reviewed by:</small>
                <br>
                &nbsp;
                <div style="width:100%;border-bottom: 1px solid;">&nbsp;</div>
                <small><center>Signature of Division Representative over Printed Name</center></small>
            </div>
        </div>
    </div>
</div>