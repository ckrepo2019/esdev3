<html>
    <head>
        <title>Transcript of Records</title>
        <style>
            * {
                font-family: Arial, Helvetica, sans-serif;
            }
            table{
                border-collapse: collapse;
            }
            @page{
                margin: 0.25in 0.5in !important;
                size: 8.5in 13in
            }
    #watermark1 {
        opacity: 0.1;
                position: absolute;
                /* bottom:   0px; */
                /* left:     -70px; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                /* width:    21.5cm; */
                /* height:   28cm; */

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 250px; }
        footer { position: fixed; bottom: -10px; left: 0px; right: 0px; height: 300px; }
        </style>
    </head>
    <body>
        @php
            $initialschool = null;
            $initialcourse = null;
    
            $firstcountrows = 0;
            $firstrowsperpage = 20;
            $countrows = 0;
            $rowsperpage = 60;
            
            $avatar = 'assets/images/avatars/unknown.png';
        @endphp
        <div id="watermark1" style="width: 100%; padding-top: 250px; text-align: center;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" height="700px" width="700px" /></div>
        
        <footer style=" font-family: Arial, Helvetica, sans-serif !important;">
            <table style="width: 100%; font-size: 12px;">
                <tr>
                    <th style="vertical-align: top; width: 20%;">Official Grades:</th>
                    <td>1.0-95 - 100%, 1.1-94, 1.2 - 93, 1.3-92, 1.4-91, 1.5-90, 1.6-89, 1.7-88, 1.8-87, 1.9-86 <br/>
                        2.0-85, 2.1-84, 2.2-83, 2.3-82, 2.4-81, 2.5-80, 2.6-79, 2.7-78, 2.8-77, 2.9-76, 3.0-75,5.0 Failed, <br/>
                        W (Withdrawal with Permission), WF (Withdrawal while failing), DR (Dropped), <br/>
                        INC (Incomplete), FA (Failure for excessive absences), NFE (No Final Exam).
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: 15px !important;" colspan="2"><u>C E R T I F I C A T I O N</u></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that the foregoing record of <u style="font-weight: bold;">{{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->lastname}} {{$studentinfo->suffix}}</u> have been verified by me that the true copies of the official records substantiating the same are kept in the files of the school.  </td>
                </tr>
                <tr>
                    <td style="" colspan="2">(NOT VALID WITHOUT<br/>
                        SCHOOL SEAL)
                        </td>
                </tr>
            </table>
            <br/>
            <table style="width: 100%; font-size: 12px;">
                <tr>
                    <td style="width: 10%;">Prepared by:</td>
                    <td style="width: 30%; text-align: center; border-bottom: 1px solid black;">{{$assistantreg}}</td>
                    <td style="width: 5%;"></td>
                    <td style="text-align: center; border-bottom: 1px solid black;">@if($dateissued != null) <span style="font-weight: bold;">{{date('m/d/Y', strtotime($dateissued))}}</span> @endif</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; text-align: center; border-bottom: 1px solid black;">{{$registrar}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Registrar’s Clerk</td>
                    <td style="width: 5%;"></td>
                    <td style="text-align: center;">Date of Issuance</td>
                    <td style="width: 5%;"></td>
                    <td style="text-align: center;">College Registrar</td>
                </tr>
            </table>
        </footer>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td rowspan="3" style="width: 20%; vertical-align: top;">                    
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="130px"/>
                </td>
                <td style="text-align: center; font-weight: bold; vertical-align: bottom; font-size: 18px;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
                <td rowspan="5" style="width: 20%;">@if($getphoto)<img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}"  style="width: 100%; margin: 0px; position: absolute;" />@else
                    
        @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
        <img src="{{base_path()}}/public/{{$studentinfo->picurl}}" style="width: 100%; margin: 0px; position: absolute;" /> 
    @else
        @php
        
            if(strtoupper($studentinfo->gender) == 'FEMALE'){
                $avatar = 'avatar/S(F) 1.png';
            }
            else{
                $avatar = 'avatar/S(M) 1.png';
            }
        @endphp
        <img src="{{base_path()}}/public/{{$avatar}}" alt="student" style="width: 100%; margin: 0px; position: absolute;" >
    @endif @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 10.5px;">
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                    {{DB::table('schoolinfo')->first()->address}}<br/>Tel. no. 828-6058 Email Add: <u style="color:blue;">saitvalencia1960@gmail.com</u> <br/>Website: https://www.sait.edu.ph
                    @else
                    {{DB::table('schoolinfo')->first()->address}}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold; vertical-align: top;">COLLEGIATE ACADEMIC RECORD<br/>
                    <img src="{{base_path()}}/public/assets/images/sait/office-of-the-registrar.png" alt="school" width="250px"/></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #4a7ebb;"></td>
                <td style="border-bottom: 1px solid #4a7ebb; text-align: center; font-weight: bold; ">OFFICIAL TRANSCRIPT OF RECORD</td>
            </tr>
            <tr>
                <td style="border-bottom: 2px solid #4a7ebb;"></td>
                <td style="border-bottom: 2px solid #4a7ebb;"></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; margin-top: 5px;">
            <tr>
                <th colspan="5" style="text-align: center; font-weight: bold; font-size: 18px;">{{$records[0]->coursename}}</th>
            </tr>
            <tr>
                <th rowspan="2" style="width: 10%; vertical-align: top;">Name</td>
                <td style="width: 28%; font-size: 15px; text-align: center; font-weight: bold;">{{$studentinfo->lastname}}</td>
                <td style="width: 28%; font-size: 15px; text-align: center; font-weight: bold;">{{$studentinfo->firstname}}</td>
                <td style="width: 25%; font-size: 15px; text-align: center; font-weight: bold;">{{$studentinfo->middlename}}</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">Last Name</td>
                <td style="text-align: center;">First Name</td>
                <td style="text-align: center;">Middle Name</td>
                <td></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; margin-top: 5px; table-layout: fixed;">
            <tr>
                <td>Date of Birth</td>
                <td style="width: 30%;">: {{$studentinfo->dob != null ? date('m/d/Y', strtotime($studentinfo->dob)) : ''}}</td>
                <td>Sex</td>
                <td>: {{strtoupper($studentinfo->gender)}}</td>
                <td>Date of Entrance</td>
                <td>: {{$details->entrancedate ?? ''}}</td>
            </tr>
            <tr>
                <td>Birth Place</td>
                <td>: {{$studentinfo->pob}}</td>
                <td>Citizenship</td>
                <td>: {{$details->citizenship ?? ''}}</td>
                <td>Entrance Data</td>
                <td>: {{$details->entrancedata ?? ''}}</td>
            </tr>
            <tr>
                <td>Father's Name</td>
                <td>: {{$studentinfo->fathername}}</td>
                <td>Civil Status</td>
                <td>: {{$details->civilstatus ?? ''}}</td>
                <td>Religion</td>
                <td>: {{$studentinfo->religionname}}</td>
            </tr>
            <tr>
                <td>Mother's Name</td>
                <td>:{{$studentinfo->mothername}}</td>
                <td>Home Address</td>
                <td colspan="3">: {{$studentinfo->street}}, {{$studentinfo->barangay}}, {{$studentinfo->city}}, {{$studentinfo->province}}</td>
            </tr>
        </table>
        <div style="width: 100%; margin-top: 2px; font-size: 12.5px; text-align: center; font-weight: bold;">RECORDS OF PRELIMINARY EDUCATION</div>
        <table style="width: 100%; font-size: 12px;" border="1">
            <tr>
                <td style="width: 18%; text-align: left;">&nbsp;&nbsp;&nbsp;Completed Courses</td>
                <td style="text-align: center;">Name of School</td>
                <td style="text-align: center;">Address</td>
                <td style="width: 15%; text-align: center;">School Year</td>
            </tr>
            <tr>
                <td style="text-align: left;">&nbsp;&nbsp;&nbsp;Primary</td>
                <td>{{$details->primaryschoolname ?? ''}}</td>
                <td>{{$details->primaryschooladdress ?? ''}}</td>
                <td>{{$details->primaryschoolyear ?? ''}}</td>
            </tr>
            <tr>
                <td style="text-align: left;">&nbsp;&nbsp;&nbsp;Junior High School</td>
                <td>{{$details->juniorschoolname ?? ''}}</td>
                <td>{{$details->juniorschooladdress ?? ''}}</td>
                <td>{{$details->juniorschoolyear ?? ''}}</td>
            </tr>
            <tr>
                <td style="text-align: left;">&nbsp;&nbsp;&nbsp;Senior High School</td>
                <td>{{$details->seniorschoolname ?? ''}}</td>
                <td>{{$details->seniorschooladdress ?? ''}}</td>
                <td>{{$details->seniorschoolyear ?? ''}}</td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; margin-top: 8px;">
            <tr>
                <td style="width: 13%;"></td>
                <td style="width: 16%;">Candidate for</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td></td>
                <td>Date of Graduation</td>
                <td style="width: 32%;">{{$details->graduationdate}}</td>
                <td style="width: 16%;">NSTP Serial No.:</td>
                <td>{{$details->nstpserialno ?? ''}}</td>
            </tr>
        </table>
        <div style="width: 100%; font-weight: bold; text-align: center; margin-top: 5px;">C O L L E G I A T E &nbsp;&nbsp;&nbsp;&nbsp;R E C O R D</div>
        <table style="width: 100%; font-size: 12px;" border="1">
            <thead>
                <tr>
                    <th style="width: 13%;">SUBJECTS<br/>& NUMBERS</th>
                    <th>DESCRIPTIVE TITLE</th>
                    <th style="width: 12%;">FINAL<br/>GRADE</th>
                    <th style="width: 12%;">CREDITS</th>
                </tr>
            </thead>
            @if(count($records)>0)
                @foreach($records as $key => $record)
                    @php
                        $record->subjdata = collect($record->subjdata)->unique('subjcode');
                        $subjnum = count($record->subjdata);
                        $break = 0;
                    @endphp
                    @if($initialschool == strtolower($record->schoolname))
                    @else
                        @php
                            $initialschool = strtolower($record->schoolname);
                            if($initialcourse != strtolower($record->coursename))
                            {
                                $initialcourse = $record->coursename;
                            }
                            // $firstcountrows+=2;
                        @endphp
                    @endif
                    @if(count($record->subjdata)>0)
                        <tr style="font-size: 12px;">
                            <td></td>
                            <td style="background-color: #ddd; font-weight: bold; text-align: center;">AY {{$record->sydesc}} @if($record->semid == 1)1st Semester @elseif($record->semid == 2)2nd Semester @else Summer @endif
                            <!--- {{strtoupper($initialschool)}}-->
                            </td>
							<td style="text-align: center; border-bottom: hidden;"></td>
                            <td style="text-align: center; border-bottom: hidden;"></td>
						</tr>
                        @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                            @php
                            
                            $subj->display = 0;
                            @endphp
                            <tr style="font-size: 12px;">
                                <td style="padding-left: 5px; border-top: hidden; border-bottom: hidden;">{{$subj->subjcode}}</td>
                                <td style=" border-top: hidden; border-bottom: hidden;">{{$subj->subjdesc}}</td>
                                <td style=" border-top: hidden; border-bottom: hidden; text-align: center;">{{$subj->subjgrade}}</td>
                                <td style=" border-top: hidden; border-bottom: hidden;text-align: center;">{{$subj->subjcredit > 0 ? $subj->subjcredit : ''}}</td>
                            </tr>
                            @php
                            $subj->display = 1;
                            if($firstcountrows == $firstrowsperpage)
                            {
                                $break+=1;
                                break;
                            }else{
    
                                $firstcountrows+=1;
                            }
                            @endphp
                        @endforeach
                    @endif
                    @php
                    if(count($record->subjdata) == $subjnum)
                    {
                        $record->display = 1;
                    }
                    if($break>0)
                    {
                        break;
                    }
                    @endphp
                @endforeach
                <tr>
                    <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                    <td style="text-align: center; border-top: hidden;"></td>
                    <td style="text-align: center; border-top: hidden;"></td>
                    <td style="text-align: center; border-top: hidden;"></td>
                </tr>
                @if($firstcountrows > $firstrowsperpage)
                <tr>
                    <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                    <td style="text-align: center;">x-x-x-x-x-x- NEXT PAGE PLEASE -x-x-x-x-x-x</td>
                    <td style="text-align: center; border-top: hidden;"></td>
                    <td style="text-align: center; border-top: hidden;"></td>
                </tr>
                @else
                <tr>
                    <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                    <td style="text-align: center; border-top: hidden;">x-x-x-x-x-x- NOTHING FOLLOWS -x-x-x-x-x-x</td>
                    <td style="text-align: center; border-top: hidden;"></td>
                    <td style="text-align: center; border-top: hidden;"></td>
                </tr>
                @endif
                {{-- @for($x=$firstcountrows;$x <= $firstrowsperpage; $x++)
                    @if($x > $firstrowsperpage)
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align: center;">x-x-x-x-x-x- NEXT PAGE PLEASE -x-x-x-x-x-x</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                @endfor --}}
            @endif
        </table>
        @if(collect($records)->where('display','0')->count()>0)
            <div style="width: 100%; page-break-before: always; text-align: center; font-weight: bold;">
                {{DB::table('schoolinfo')->first()->schoolname}}
            </div>
            {{-- <table style="width: 100%; page-break">

            </table> --}}
        @endif
    </body>
</html>