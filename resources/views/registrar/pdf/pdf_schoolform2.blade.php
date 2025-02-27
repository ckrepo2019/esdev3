
<style>
    #header, #header td{
        font-size: 13px;
        width: 100%;
        table-layout: fixed;
    }
    .contentTable{
        font-size: 12px;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    .contentTable th{
        text-align: center;
        border:1px solid black !important;
        border-collapse: collapse;
    }
    .contentTable td{
        border:1px solid black !important;
        border-collapse: collapse;
    }
    h2{
        margin:5px;
    }
    div.box{
        border: 1px solid black;
        padding: 5px;
        text-align: center;
    }
    .cellRight{
        text-align: right;
    }
    .rotate {
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
        padding: 10px !important;
        font-size: 11px;
    }
    .rotate div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
    }
    .num{
        width:30px;
        border-right: 1px solid black;
        text-align: center;
        padding: 4px;
    }
    .small, small {
        font-size: 80%;
        font-weight: 400;
    }
    ol {
        display: block;
        list-style-type: decimal;
        margin-block-start: 1em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 40px;
    }
    li {
        display: list-item;
        text-align: -webkit-match-parent;
    }
    .container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr; /* fraction*/
    }
    .guidelines{
        table-layout: fixed;
        vertical-align: top !important;
        padding: 0px !important;
    }
    .guidelines th{
        border: 1px solid black;
        vertical-align: top left !important;
    }
    .summary{
        width: 100%;
        border-collapse: collapse;
    }
    .summary th{
        font-size: 12px;
        text-align: center;
        vertical-align: middle !important;
    }
    .summary td{
        font-size: 12px;
        text-align: center;
        vertical-align: middle !important;
        border: 1px solid black;
        padding: 5px;
    }
    .late{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 20px 20px 0 0;
        border-color: gray transparent transparent transparent;
    }
    .cc{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 20px 20px;
        border-color: transparent transparent gray transparent;

    }
</style>
<table id="header">
    <tr>
        <th rowspan="2" width="7%">
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
        </th>
        <th colspan="7" width="75%" style="padding-left:15%">
            <h2><center>School Form 2 (SF2) Daily Attendance Report of Learners</center></h2>
            <small><em><center>This replaced Form 1, Fom 2 & STS Fom 4 - Absenteeism and Dropout Profile</center></em></small>
        </th>
        <th rowspan="2">
            <center>
                <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="150px">
            </center>
        </th>
    </tr>
    <tr>
        <th class="cellRight" width="10%">School ID</th>
        <th><div class="box">{{$getSchoolInfo[0]->schoolid}}</div></th>
        <th class="cellRight">School Year</th>
        <th><div class="box">{{$getSchoolYear[0]->sydesc}}</div></th>
        <th width="13%" class="cellRight">Report for the Month of </th>
        <th colspan="2"><div class="box">&nbsp;</div></th>
    </tr>
    <tr>
        <th colspan="2" class="cellRight">Name of School</th>
        <th colspan="3" ><div class="box">{{$getSchoolInfo[0]->schoolname}}</div></th>
        <th class="cellRight">Grade Level</th>
        <th><div class="box">{{$gradeAndSection[0]->levelname}}</div></th>
        <th class="cellRight">Section</th>
        <th><div class="box">{{$gradeAndSection[0]->sectionname}}</div></th>
    </tr>
</table>
<table class="contentTable">
    <thead>
        <tr>
            <th rowspan="3" style="width:20%">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
            <th colspan="25" style="width:40%">(1st row fo date, 2nd row for Day: M, T, W, TH, F)</th>
            <th colspan="2" rowspan="2" style="width:10%">Total for the Month</th>
            <th rowspan="3" style="width:20%">REMARKS/S (If DROPPED OUT, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.) </th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th class='rotate'><div>MON</div></th>
            <th class='rotate'><div>TUE</div></th>
            <th class='rotate'><div>WED</div></th>
            <th class='rotate'><div>THU</div></th>
            <th class='rotate'><div>FRI</div></th>
            <th class='rotate'><div>MON</div></th>
            <th class='rotate'><div>TUE</div></th>
            <th class='rotate'><div>WED</div></th>
            <th class='rotate'><div>THU</div></th>
            <th class='rotate'><div>FRI</div></th>
            <th class='rotate'><div>MON</div></th>
            <th class='rotate'><div>TUE</div></th>
            <th class='rotate'><div>WED</div></th>
            <th class='rotate'><div>THU</div></th>
            <th class='rotate'><div>FRI</div></th>
            <th class='rotate'><div>MON</div></th>
            <th class='rotate'><div>TUE</div></th>
            <th class='rotate'><div>WED</div></th>
            <th class='rotate'><div>THU</div></th>
            <th class='rotate'><div>FRI</div></th>
            <th class='rotate'><div>MON</div></th>
            <th class='rotate'><div>TUE</div></th>
            <th class='rotate'><div>WED</div></th>
            <th class='rotate'><div>THU</div></th>
            <th class='rotate'><div>FRI</div></th>
            <th>ABSENT</th>
            <th>TARDY</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:0px;"><div class="num">1.</div></td>
            <td></td>
            <td></td>
            <td ><div class="cc"></div></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>
                <i class="fa fa-arrow-left pr-3"></i> MALE | TOTAL Per Day <i class="fa fa-arrow-right pl-3"></i>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="padding:0px;"><div class="num">1.</div></td>
            <td><div class="late">
                </div></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>
                <i class="fa fa-arrow-left pr-3"></i> FEMALE | TOTAL Per Day <i class="fa fa-arrow-right pl-3"></i>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>
                <i class="fa fa-arrow-left pr-3"></i> COMBINED TOTAL PER DAY <i class="fa fa-arrow-right pl-3"></i>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tbody>
</table>
<table class="guidelines" style="width:100%;">
    <tr>
        <td style="width:50%" valign="top">
            <div>
                <small><strong>GUIDELINES:</strong></small>
                <small>
                    <ol style="font-size: 11px; padding-left: 15px;">
                        <li> The attendance shall be accomplished daily. Refer to the codes for checking learners' attendance.</li>
                        <li> Dates shall be witten in the preceding columns beside Leaner's Name.</li>
                        <li>
                            To compute the following:
                            <br>
                            <table>
                                <tr>
                                    <td>
                                        <div>
                                            a. Percentage of Enrolment = 
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="row" style="border-bottom: 1px solid black;width:100%;">Registered Learner as of End of the Month</div> 
                                        <div class="row">Enrolment as of 1st Fiday of June</div>
                                    </td>
                                    <td>
                                        <span>x100</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            b. Average Daily Attendance = 
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="row" style="border-bottom: 1px solid black;width:100%;">Total Daily Attendance</div> 
                                        <div class="row">Number of School Days in reporting month</div>
                                    </td>
                                    <td>
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            c. Pecentage of Attendance for the month =
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="row" style="border-bottom: 1px solid black;width:100%;">Average daily attendance</div> 
                                        <div class="row">Registered Learner as of End of the month</div>
                                    </td>
                                    <td>
                                        <span>x100</span>
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </li>
                        <li> Every End of the month, the class adviser will submit this form to the office of the pincipal fo recording of summary table into the School Form 4. Once signed by the principal, this form should be returned to the adviser.</li>
                        <li> The adviser will extend neccessary intervention including but not limited to home visitation to learner/s that committed 5 consecutive days of absences or those with potentials of dropping out.</li>
                        <li> Attendance peformance of leaner is expected to reflect in Form 137 and Form 138 every grading period<br> * Beginning of School Year cut-off report is every 1st Fiday of School Calenda Days</li>
                    </ol>
                </small>
            </div>
        </td>
        <td style="width:20%" valign="top">
            <div style="border: 1px solid black;padding:0px;">
                <div style="border-bottom: 1px solid black;padding:2px; ">
                    <small style="font-size: 11px;">
                        <strong>1. CODES FOR CHECKING ATTENDANCE</strong>
                    </small>
                </div>
                <div style="font-size: 11px;padding:3px;">
                        <strong>blank</strong> - Pesent; (x)- Absent; Tardy (half shaded = Upper for Late Commer, Lowe for Cutting Classes)
                </div>
                <div style="padding:2px;">
                    <span>
                        <small style="font-size: 11px;">
                            <strong>2. REASONS/CAUSES OF DROP-OUTS</strong>
                        </small>
                    </span>
                    <br>
                    <div style="padding-bottom:3px;padding:2px;">
                        <span style="font-size: 11px;">
                            <strong>a. Domestic-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.1. Had to take care of siblings
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.2. Early marriage/pregnancy
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.3. Parents' attitude towad schooling
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.4. Family problems
                        </span>
                    </div>
                    <div style="padding-bottom:3px;padding:2px;">
                        <span style="font-size: 11px;">
                            <strong>b. Individual-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.1. Illness
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.2. Overage
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.3. Death
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.4. Drug Abuse
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.5. Poor academic performance
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.6. Lack of interest/Dsitractions
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.7. Hunger/Malnutrition
                        </span>
                    </div>
                    <div style="padding-bottom:3px;padding:2px;">
                        <span style="font-size: 11px;">
                            <strong>c. School-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.1. Teacher Factor
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.2. Physical condition of classroom
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.3. Peer influence
                        </span>
                    </div>
                    <div style="padding-bottom:3px;padding:2px;">
                        <span style="font-size: 11px;">
                            <strong>d. Geographic/Environmental</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.1. Distance between home and school
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.2. Armed conflict (incl. Tribal wars & clanfeuds)
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.3. Calamities/Disasters
                        </span>
                    </div>
                    <div style="padding-bottom:3px;padding:2px;">
                        <span style="font-size: 11px;">
                            <strong>e. Financial-Related</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            e.1. Child labor, work
                        </span>
                    </div>
                    <div style="padding-bottom:3px;padding:2px;">
                        <span style="font-size: 11px;">
                            <strong>f. Others</strong>
                        </span>
                    </div>
                </div>
            </div>                           
        </td>
        <td style="width:30%;padding-left:20px;" valign="top">
            <table class="summary">
                <tr>
                    <th rowspan="2" width="30%">Month: JULY</th>
                    <th rowspan="2" width="40%">No. of Days of Classes: 1</th>
                    <th colspan="3" width="30%">Summary for the Month</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                    <th>TOTAL</th>
                </tr>
                <tr>
                    <td colspan="2"><em>* Enrolment as of (1st Friday of June)</em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Late Enrollment <strong>during the month</strong><br>(beyond cut-off)</em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Registered Learner as of <strong>end of the month</strong></em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Percentage of Enollment as of <strong>end of the month</strong></em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Average Daily Attendance</em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Percentage of Attendance for the month</em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Number of students with 5 consecutive days of absences:</em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em><strong>Drop out</strong></em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em><strong>Tansferred out</strong></em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><em><strong>Tansferred in</strong></em></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br>
            <div style="font-size: 11px;">
                <span>
                    <em>I certify that this is a true and correct report.</em>
                </span>
            </div>
            <div style="font-size: 11px; text-align:center;">
                <center>
                    <span>
                        &nbsp;
                    </span>
                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                    <em class="p-0">(Signature of Teacher over Printed Name)</em>
                </center>
            </div>
            <br>
            <div style="font-size: 11px;">
                <span>
                    <em>Attested by:</em>
                </span>
            </div>
            <div style="font-size: 11px; text-align:center;">
                <center>
                    <span>
                        &nbsp;
                    </span>
                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                    <em class="p-0">(Signature of School Head over Printed Name)</em>
                </center>
            </div>
        </td>
    </tr>
</table>
        

      