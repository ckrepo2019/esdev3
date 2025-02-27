
<style>
        html{
            font-family: Arial, Helvetica, sans-serif;
    
        }
            #header{
                font-size: 13px;
                /* border: none !important; */
                border-spacing: 0;
                /* border:1px solid black !important; */
                /* padding:2px; */
                /* border-collapse: collapse; */
                text-align: right;
                width: 100%;
                /* table-layout: fixed; */
            }
            #header th{
                /* padding-top: 0px; */
                padding: 3px;
                /* border:1px solid black !important; */
            }
        
            h2{
                margin:5px;
            }
            /* input[type=text]{
                text-align: center;
            } */
            div.box{
                border: 1px solid black;
                padding: 5px;
                text-align: center;
            }
            .leftImg{
                text-align: left !important;
                /* padding-top: 0px !important;
                margin-top: 0px !important; */
                text-align: left;
                vertical-align: top;
                
            }
            .labels{
                width: 60% !important;
            }
          
            .page_break { 
                    page-break-before: always; 
            }
            h3{
                margin: 0px;
            }
            
            .summaryTable th, .summaryTable td{
                font-size: 11px;
                border:1px solid black !important;
                text-align: center;
                /* table-layout: fixed; */
                padding: 3px;
            }
            .summaryTable{
                padding-top:0px;
                border-collapse: collapse;
                
            }
            .guidelines{
                font-size: 11px;
                width: 100%;
            }
        </style>
        <table id="header">
            <tr>
                <th rowspan="2" style="width:10%;" class="leftImg">
                    <center><img style="padding:0px; margin:0px;" src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></center>
                </th>
                <td colspan="12" style="padding-top:0px;"><div style="padding-right:7%;margin-top: 0px;padding-top:0px;"><center><h3 style="font-size:20px !important;">School Form 4 (SF4) Monthly Learner's Movement and Attendance</h3><sup><em>(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</em></sup></center><br></div></td>
            </tr>
            <tr>
                <th width="8%">School ID</th>
                <th><div class="box">{{$getSchoolInfo[0]->schoolid}}</div></th>
                <th width="5%">Region</th>
                <th><div class="box">{{$getSchoolInfo[0]->region}}</div></th>
                <th width="5%">Division</th>
                <th colspan="2"><div class="box">{{$getSchoolInfo[0]->division}}</div></th>
                <th width="5%">District</th>
                <th colspan="2"><div class="box">{{$getSchoolInfo[0]->district}}</div></th>
                <th colspan="2">&nbsp;</th>
            </tr>
            <tr>
                <th>School Name</th>
                <th colspan="6"><div class="box">{{$getSchoolInfo[0]->schoolname}}</div></th>
                <th colspan="2">School Year</th>
                <th><div class="box">{{$getSchoolYear[0]->sydesc}}</div></th>
                <th colspan="2">Report fo the Month of</th>
                <th><div class="box">&nbsp;</div></th>
            </tr>
        </table>
        <table class="summaryTable">
            <tr>
                <th rowspan="3" style="width:4%;">GRADE/YEAR LEVEL</th>
                <th rowspan="3" style="width:8%;">SECTION</th>
                <th rowspan="3" style="width:15%;">NAME OF ADVISER</th>
                <th rowspan="2" colspan="3">REGISTERED LEANES<br>(As of End of the Month)</th>
                <th colspan="6">ATTENDANCE</th>
                <th colspan="9">DROPPED OUT</th>
                <th colspan="9">TRANSFERRED OUT</th>
                <th colspan="9">TRANSFERRED IN</th>
            </tr>
            <tr>
                <th colspan="3">Daily Average</th>
                <th colspan="3">Percentage fo the Month</th>
                <th colspan="3">(A) Cumulative as of Previous Month</th>
                <th colspan="3">(B) For the Month</th>
                <th colspan="3">(A + B) Cumulative as of End of the Month</th>
                <th colspan="3">(A) Cumulative as of Previous Month</th>
                <th colspan="3">(B) For the Month</th>
                <th colspan="3">(A + B) Cumulative as of End of the Month</th>
                <th colspan="3">(A) Cumulative as of Previous Month</th>
                <th colspan="3">(B) For the Month</th>
                <th colspan="3">(A + B) Cumulative as of End of the Month</th>
            </tr>
            <tr>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
                <th>M</th>
                <th>F</th>
                <th>T</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th colspan="3">ELEMENTARY/SECONDARY</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th colspan="3">TOTAL</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <br>
        <table class="guidelines">
            <tr>
                <td style="width:65%;">
                    <div style="margin-right: 20px;">
                        <span>GUIDELINES:</span>
                        <ol style="padding-left: 20px;">
                            <li>This form shall be accomplish every end of the month using the summary box of SF2 submitted by the teachers/advisers to update figures for the month.</li>
                            <li>Furnish the Division Office with a copy a week after June 30, October 30 & March 31</li>
                        </ol>
                    </div>
                </td>
                <td>
                        <strong>Prepared and Submitted by:</strong>
                        <div style="width: 100%">
                           
                               <div style="width: 80%;border-bottom: 1px solid black;margin: auto;padding: 10px;">&nbsp;</div>
                               <center><em>(Signature of School Head over Printed Name)</em></center>
                        </div>
                </td>
            </tr>
        </table>