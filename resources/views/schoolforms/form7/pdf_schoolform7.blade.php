<html>
    <head>
        <title>School Form 7</title>
        <style>
            html{                
            font-family: Arial, Helvetica, sans-serif;
            }
            
            @page{
                margin: 20px;
            }
            table{
                border-collapse: collapse;
            }
            td{
                padding: 0px;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%; table-layout: fixed; margin-bottom: 0px;">
            <tr>
                <th rowspan="5" style="vertical-align: top; width: 10%; text-align: left;">                        
                    <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="100px"/>
                </th>
                <th colspan="7" style="font-size: 20px;">School Form 7 (SF7) School Personnel Assignment List and Basic Profile</th>
                <th></th>
            </tr>
            <tr>
                <td colspan="7" style="text-align: center; font-size: 13px; padding-bottom: 7px;">
                    <em>This replaces Form 12-Monthly Status Report for Teacher, Form 19-Assignment List,</em><br/>
                    Form 29-Teacher Program and Form 31-Summary Impormation of Teacher
                </td>
                <th></th>
            </tr>
            <tr style="font-size: 13px;">
                <td style="text-align: right; width: 8%;">School ID&nbsp;</td>
                <td style=" width: 10%;">
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$schoolinfo->schoolid}}</div>
                </td>
                <td style="text-align: right; width: 8%;">Region&nbsp;</td>
                <td style=" width: 10%;">
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$schoolinfo->regiontext == null ? $schoolinfo->region : $schoolinfo->regiontext}}</div>
                </td>
                <td style="text-align: right; width: 8%;">Division&nbsp;</td>
                <td>
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$schoolinfo->divisiontext == null ? $schoolinfo->division : $schoolinfo->divisiontext}}</div>
                </td>
                <td style="text-align: right; width: 8%;">Month&nbsp;</td>
                <td style=" width: 10%;">
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$monthname}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="8"></td>
            </tr>
            <tr style="font-size: 13px;">
                <td style="text-align: right;">School Name&nbsp;</td>
                <td colspan="3">
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$schoolinfo->schoolname}}</div>
                </td>
                <td style="text-align: right;">District&nbsp;</td>
                <td>
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$schoolinfo->districttext == null ? $schoolinfo->district : $schoolinfo->districttext}}</div>
                </td>
                <td style="text-align: right;">School Year&nbsp;</td>
                <td>
                    <div style="width: 100%; border: 1px solid black; text-align: center;">{{$sydesc}}</div>
                </td>
            </tr>
        </table>
        <table style="width: 100%; table-layout: fixed; font-size: 9px; margin-top: 0px;">
            <tr>
                <td style="width: 27%;; vertical-align: top;">
                    <table style="width: 100%" border="1">
                        <tr>
                            <th colspan="2">(A) Nationally-Funded Teaching Related Items</th>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding: 10px; width: 75%;">Title of Plantilla Position<br/>(as it appears in the appointment<br/>document/PSIPOP)</td>
                            <td style="text-align: center;">Number of<br/>Incumbent</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 1%;"></td>
                <td style="width: 27%; vertical-align: top;">
                    <table style="width: 100%" border="1">
                        <tr>
                            <th colspan="2">(B) National-Funded Non Teaching Items</th>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding: 15.5px 0px; width: 75%;">Title of Plantilla Position<br/>(as it appears in the appointment document/PSIPOP)</td>
                            <td style="text-align: center;">Number of<br/>Incumbent</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 1%;"></td>
                <td style=" vertical-align: top;">
                    <table style="width: 100%; vertical-align: top;" border="1">
                        <tr>
                            <th colspan="5">(C) Other Appointments and Funding Sources</th>
                        </tr>
                        <tr>
                            <td rowspan="2" style="text-align: center; width: 35%;">Title of Designation (as it appears in the contract/document: Teacher, Clerk, Security Guard, Driver etc.)</td>
                            <td rowspan="2" style="text-align: center; width: 20%;">Appointment: (Contractual, Substitute, Volunteer, Others Specify)</td>
                            <td rowspan="2" style="text-align: center;">Fund Source (SEF, PTA, NGO's etc.)</td>
                            <td style="text-align: center; width: 20%; padding: 2px;" colspan="2">Number of<br/>Incumbent</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;  padding: 2px;">Teaching</td>
                            <td style="text-align: center;  padding: 2px;">Non-<br/>Teaching</td>
                        </tr>
                        <tr>
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
                        </tr>
                        <tr>
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
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width: 100%; table-layout: fixed; font-size: 9px; margin-top: 5px;" border="1">
            <thead>
                <tr>
                    <td rowspan="2" style="width: 5%; text-align: center;">Employee No.<br/>(or Tax<br/>Identification<br/>Number -<br/>T.I.N.)</td>
                    <td rowspan="2" style="width: 15%; text-align: center;">Name of School Personnel<br/>(Arrange by Position,<br/>Descending)</td>
                    <td rowspan="2" style="width: 3%; text-align: center;">Sex</td>
                    <td rowspan="2" style="width: 5%; text-align: center;">Fund<br/>Source</td>
                    <td rowspan="2" style="width: 7%; text-align: center;">Position/<br/>Designation</td>
                    <td rowspan="2" style="width: 7%; text-align: center;">Nature of Appointment/ Employment <br/></td>
                    <th colspan="3" style="width: 17%;">EDUCATIONAL QUALIFICATION</th>
                    <td rowspan="2" style="width: 12%; text-align: center;"><strong>Subject Taught</strong> (including Grade<br/>& Section), Advisory Class &<br/><strong>Other Ancilliary Assignment</strong></td>
                    <td colspan="4" style=" text-align: center;">Daily Program (time duration)</td>
                    <td rowspan="2" style="width: 8%; text-align: center;">Remarks (Fo<br/> Detailed Items,<br/>Indeicate name of<br/>school/office, for IP's<br/>Ethnicity)</td>
                </tr>
                <tr>
                    <td style="text-align: center;">Degree/Post<br/>Graduate</td>
                    <td style="text-align: center;">Major/<br/>Specialization</td>
                    <td style="text-align: center;">Minor</td>
                    <td style="text-align: center;">DAY (M/T/W/TH/F)</th>
                    <td style="text-align: center;">From (00:00)</td>
                    <td style="text-align: center;">To (00:00)</td>
                    <td style="text-align: center;">Total Actual Teaching Minutes per Week</td>
                </tr>
            </thead>
            @if(count($employees) > 0)
                @foreach($employees as $employee)
                    @php
                        if(count($employee->schedules)>0)
                        {
                            $rowspan = count($employee->schedules);
                        }else{
                        $rowspan  = 1;
                        }
                    @endphp
                    <tr nobr="true">
                        <td rowspan="{{$rowspan}}">
                            @if(collect($employee->accounts)->where('accountdescription','T.I.N')->count()>0)
                                {{collect($employee->accounts)->where('accountdescription','T.I.N')->first()->accountnum}}
                            @endif
                        </td>
                        <td rowspan="{{$rowspan}}" style="padding: 0px 2px;">{{$employee->firstname}} {{isset($employee->middlename[0]) ? $employee->middlename[0].'.' : ''}} {{$employee->lastname}} {{$employee->suffix}}</td>
                        <td rowspan="{{$rowspan}}" style="text-align: center;">{{isset($employee->gender[0]) ? strtoupper($employee->gender[0]) : ''}}</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}" style="padding: 0px 2px; font-size: 8px !important;text-align: center;">{{$employee->designation}}</td>
                        <td rowspan="{{$rowspan}}" style="text-align: center;">{{$employee->employmentstatus}}</td>
                        @if(count($employee->educationalbackground)>0)
                        <td rowspan="{{$rowspan}}">{{$employee->educationalbackground[0]->coursetaken}}</td>
                        <td rowspan="{{$rowspan}}">{{$employee->educationalbackground[0]->major}}</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        @else
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        @endif
                        @if($rowspan == 1)
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                        @else
                        <td>{{$employee->schedules[0]->levelname}}{{$employee->schedules[0]->sectionname}} {{$employee->schedules[0]->subjdesc}}</td>
                        <td>{{$employee->schedules[0]->schedule[0]->day}}</td>
                        <td>{{$employee->schedules[0]->schedule[0]->start}}</td>
                        <td>{{$employee->schedules[0]->schedule[0]->end}}</td>
                        <td>&nbsp;</td>
                        @endif
                        <td rowspan="{{$rowspan}}">&nbsp;</td>
                    </tr>
                    @if($rowspan > 1)
                        @for($x = 1; $x < $rowspan; $x++)
                            <tr>
                                <td>@if(isset($employee->schedules[$x])){{$employee->schedules[$x]->levelname}}{{$employee->schedules[$x]->sectionname}} {{$employee->schedules[$x]->subjdesc}}@endif</td>
                                <td>@if(isset($employee->schedules[$x])){{$employee->schedules[$x]->schedule[0]->day}}@endif</td>
                                <td>@if(isset($employee->schedules[$x])){{$employee->schedules[$x]->schedule[0]->start}}@endif</td>
                                <td>@if(isset($employee->schedules[$x])){{$employee->schedules[$x]->schedule[0]->end}}@endif</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    @endif

                @endforeach
            @else
                @for($x = 0; $x < 5; $x++)
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
                    </tr>
                @endfor
            @endif
        </table>
        <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-top: 10px;">
            <tr>
                <td style="width: 70%; vertical-align: top;">
                    <div style="width: 100%;">GUIDANCE:</div>
                    <div style="width: 100%;">1. This form shall be accomplished at the beginning of the school year by the school head. In case movement of teachers and<br/>other personnel during the school year, an updated Form 19 must be submitted to the Division Office.</div>
                    <div style="width: 100%;">2. All school personnel, regardless of position/nature of appointment should be included in this form and should be listed<br/>from highest rank down to the lowest.</div>
                    <div style="width: 100%;">3. Please reflect subjects being taught and if teacher handling advisory class or Ancillary Assignment. Other administrative<br/>duties amust also reported.</div>
                    <div style="width: 100%;">4. Daily Program Column is for teaching personnel only</div>
                </td>
                <td style=" vertical-align: top;">
                    <div>Submitted by:</div>
                    <br/>
                    <br/>
                    <br/>
                    <div style="font-weight: bold; width: 100%; text-align: center; border-bottom: 1px solid black">&nbsp;</div>
                    <div style="width: 100%; text-align: center;"><em>(Signature of School Head over Printed Name)</em></div>
                    <br/>
                    <br/>
                    <table style="width: 100%; table-layout: fixed;">
                        <tr>
                            <td>Updated as of :</td>
                            <td style="border-bottom: 1px solid black; text-align: center; width: 70%;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>School Form 7, Page _______of________</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>