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
    .col1               { width: 70%; }
    .col2               { width: 30%; }

    .left               { float:left; width:69%; }
    .right              { float: right; width: 30%; }

    .tableLeft1         { width: 100%; border: 1px solid; border-spacing: 0; border-left: hidden; border-top: hidden; font-size: 12px; text-align: center; }

    .tableLeft1 td,
    .tableLeft1 th      { border: 1px solid; border-right: hidden; border-bottom: hidden; }

    #pSpan span         { display: block; visibility: hidden; }

    #guidelines         { font-size: 12px; }
</style>
<div class="nobreak">
    <table id="logo">
        <tr>
            <td >
                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
            </td>
            <td style="width: 70%">
                <h2><center>School Form 5A<br>End of Semester and School Year Status of Learners for Senior High School<br>(SF5A-SHS)</center></h2>
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
            <td class="labels">Grade Level</td>
            <td id="cellCenter"><div class="box">{{$getSectionAndLevel[0]->levelname}}</div></td>
            <td class="labels">Section</td>
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
                        <th style="width: 30%">BACK SUBJECTS<br>List down subjects where learner obtain a rating below 75%</sup></th>
                        <th style="width: 15%">END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                        <th style="width: 15%">END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: none;background-color:lightgrey;padding-left:5px;">MALE</td>
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
                                    <td>
                                    </td>
                                </tr>
                                @php
                                    $countMale+=1
                                @endphp
                            @endif
                        @endforeach
                        @php
                            $male=0;
                            $female=0;
                            $init = 20;   
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>IRREGULAR</th>
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
                    <tbody><tr>
                        <td colspan="6" style="text-align: none;background-color:lightgrey;padding-left:5px;">FEMALE</td>
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
                                <td>
                                </td>
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
                <div style="width:100%;border-bottom: 1px solid;">&nbsp;</div>
                <small><center>Signature of Class Adviser over Printed Name</center></small>
                <br>
                <br>
                <small>Certified Correct by:</small>
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

<p id="pSpan"><span>hello</span><span>How are you</span></p>
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