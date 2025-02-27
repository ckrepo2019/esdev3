<html>

<head>
    <title>Transcript of Records</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        @page {
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
            z-index: -2000;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 250px;
        }

        footer {
            position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 300px;
        }
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
    <div id="watermark1" style="width: 100%; padding-top: 250px; text-align: center;"><img
            src="{{ base_path() }}/public/{{ $schoolinfo->picurl }}" height="700px" width="700px" /></div>

    <footer style=" font-family: Arial, Helvetica, sans-serif !important;">
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <th style="vertical-align: top; width: 20%;">Official Grades:</th>
                <td>1.0-95 - 100%, 1.1-94, 1.2 - 93, 1.3-92, 1.4-91, 1.5-90, 1.6-89, 1.7-88, 1.8-87, 1.9-86 <br />
                    2.0-85, 2.1-84, 2.2-83, 2.3-82, 2.4-81, 2.5-80, 2.6-79, 2.7-78, 2.8-77, 2.9-76, 3.0-75,5.0 Failed,
                    <br />
                    W (Withdrawal with Permission), WF (Withdrawal while failing), DR (Dropped), <br />
                    INC (Incomplete), FA (Failure for excessive absences), NFE (No Final Exam).
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 15px !important;" colspan="2"><u>C E R T I F I C A T I O
                        N</u></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby
                    certify that the foregoing record of <u style="font-weight: bold;">{{ $studentinfo->firstname }}
                        {{ $studentinfo->middlename }} {{ $studentinfo->lastname }} {{ $studentinfo->suffix }}</u> have
                    been verified by me that the true copies of the official records substantiating the same are kept in
                    the files of the school. </td>
            </tr>
            <tr>
                <td style="" colspan="2">(NOT VALID WITHOUT<br />
                    SCHOOL SEAL)
                </td>
            </tr>
        </table>
        <br />
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="width: 10%;">Prepared by:</td>
                <td style="width: 30%; text-align: center; border-bottom: 1px solid black;">{{ $assistantreg }}</td>
                <td style="width: 5%;"></td>
                <td style="text-align: center; border-bottom: 1px solid black;">
                    @if ($dateissued != null)
                        <span style="font-weight: bold;">{{ date('m/d/Y', strtotime($dateissued)) }}</span>
                    @endif
                </td>
                <td style="width: 5%;"></td>
                <td style="width: 30%; text-align: center; border-bottom: 1px solid black;">{{ $registrar }}</td>
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
            <td style="width: 20%; vertical-align: top;">
                <img src="{{ base_path() }}/public/{{ $schoolinfo->picurl }}" alt="school" width="130px" />
            </td>
            <td style="font-weight: bold; font-size: 18px;">
                {{ DB::table('schoolinfo')->first()->schoolname }} <br><br>
                <span style="font-size: 12px;">{{ DB::table('schoolinfo')->first()->address }}</span><br>
                <span style="font-size: 14px; color: rgb(0, 140, 255);margin-top:10px;"> OFFICE OF THE REGISTRAR</span>
            </td>
            <td
                style="font-weight: bold; font-size: 18px;text-align: right; vertical-align: bottom;padding-right: 5px;">
                <span style="font-size: 14px; color: rgb(0, 140, 255);"> TRANSCRIPT<br>OF RECORD</span>
            </td>
            <td rowspan="2" style="width: 20%;">
                @if ($getphoto)
                    <img src="{{ $getphoto->picurl . '?random="' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') }}"
                        style="width: 100%; margin: 0px; position: absolute; border: 2px solid black;" alt="student photo" />
                @else
                    @php
                        if (strtoupper($studentinfo->gender) == 'FEMALE') {
                            $avatar = 'avatar/S(F) 1.png';
                        } else {
                            $avatar = 'avatar/S(M) 1.png';
                        }
                        $picExists = !empty($studentinfo->picurl) && file_exists(public_path($studentinfo->picurl));
                        // dd(URL::asset($studentinfo->picurl . '?random="' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')));
                    @endphp

                    @if ($picExists)

                        @php
                            $picurl =
                                str_replace('jpg', 'png', $studentinfo->picurl) .
                                '?random="' .
                                \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
                                '"';
                                // dd(asset($picurl));
                        @endphp
                        <img src="{{ $picurl }}"
                        onerror="this.onerror = null, this.src='{{ $avatar }}'" alt="student pic" 
                        style="width: 100%; margin: 0px; position: absolute;border: 1px solid black; height: 140px; width: 140px; background-size: cover !important; background-position: center; background-repeat: no-repeat;">

                        {{-- <img src="{{ asset($picurl) }}" alt="student pic" style="width: 100%; margin: 0px; position: absolute;border: 1px solid black; height: 146px;" onerror="this.onerror=null;this.src='{{ asset('avatar/unknown.png') }}'"> --}}
                    @else
                        <img src="{{$avatar }}" alt="student avatar"
                            style="width: 100%; margin: 0px; position: absolute;border: 2px solid black;">
                    @endif

                @endif
            </td>
        </tr>
        {{-- <tr>
            <td style="text-align: center; font-size: 10.5px;">
                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                    {{ DB::table('schoolinfo')->first()->address }}<br />Tel. no. 828-6058 Email Add: <u
                        style="color:blue;">saitvalencia1960@gmail.com</u> <br />Website: https://www.sait.edu.ph
                @else
                    {{ DB::table('schoolinfo')->first()->address }}
                @endif
            </td>
        </tr> --}}
        {{-- <tr>
            <td style="text-align: center; font-weight: bold; vertical-align: top;">COLLEGIATE ACADEMIC RECORD<br />
                @if (file_exists(base_path() . '/public/assets/images/sait/office-of-the-registrar.png'))
                    <img src="{{ base_path() }}/public/assets/images/sait/office-of-the-registrar.png" alt="office"
                        width="250px" />
                @else
                    
                @endif
            </td>
        </tr> --}}
        {{-- <tr>
            <td></td>
            <td style="text-align: center; font-weight: bold; ">OFFICIAL TRANSCRIPT OF
                RECORD</td>
        </tr> --}}
        {{-- <tr>
            <td style="border-bottom: 2px solid #4a7ebb;"></td>
            <td style="border-bottom: 2px solid #4a7ebb;"></td>
        </tr> --}}
    </table><br>

    <div style="border-bottom: 1px solid black;"></div>

    {{-- <table style="width: 100%; font-size: 12px; margin-top: 5px;">
        <tr>
            <th colspan="5" style="text-align: center; font-weight: bold; font-size: 18px;">
                {{ $records[0]->coursename }}</th>
        </tr>
        <tr>
            <td colspan="5">
                <div style="height: 15px; background-color: transparent;"></div>
            </td>
        </tr>
        <tr>
            <th rowspan="2" style="width: 10%; vertical-align: top;">Name</td>
            <td style="width: 28%; font-size: 15px; text-align: center; font-weight: bold;">
                {{ $studentinfo->lastname }}</td>
            <td style="width: 28%; font-size: 15px; text-align: center; font-weight: bold;">
                {{ $studentinfo->firstname }}</td>
            <td style="width: 25%; font-size: 15px; text-align: center; font-weight: bold;">
                {{ $studentinfo->middlename }}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center;">Last Name</td>
            <td style="text-align: center;">First Name</td>
            <td style="text-align: center;">Middle Name</td>
            <td></td>
        </tr>
    </table> --}}
    <br>

    <table
        style="width: 100%; font-size: 12px;border-collapse: collapse; table-layout: fixed; border: 1px solid black; margin: 0px">
        <tr>
            <td width="20%" style="background-color: black; color: white; text-align: center;padding:2px;">
                <b>PERSONAL DATA</b>
            </td>
            <td width="30%"> </td>
            <td width="5%"></td>
            <td width="15%"> </td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td width="20%" style="padding-left: 5px; padding-top: 10px"><b>Name:</b></td>
            <td width="30%"> {{ $studentinfo->firstname }} {{ $studentinfo->middlename }}.
                {{ $studentinfo->lastname }}, {{ $studentinfo->suffix }}</td>
            <td width="5%"></td>
            <td width="15%" style="text-align: left;"> <b>Student ID No. :</b> </td>
            <td width="30%"> {{ $studentinfo->sid }} </td>
        </tr>
        <tr>
            <td style="padding-left: 5px"><b>Date of Birth:</b></td>
            <td> {{ $studentinfo->dob != null ? date('j F Y', strtotime($studentinfo->dob)) : '' }} </td>
            <td></td>
            <td> <b>Special Order No.:</b> </td>
            <td> </td>
        </tr>
        <tr>
            <td style="padding-left: 5px"><b>Place of Birth:</b></td>
            <td> {{ $studentinfo->pob }} </td>
            <td></td>
            <td> <b>Course:</b> </td>
            <td> {{ $records[0]->coursename }} </td>
        </tr>
        <tr>
            <td style="padding-left: 5px"><b>Permanent Address:</b></td>
            <td colspan="4">{{ $studentinfo->street }}, {{ $studentinfo->barangay }},
                {{ $studentinfo->city }},
                {{ $studentinfo->province }}
            </td>
        </tr>
        <tr>
            <td style="padding-left: 5px; padding-bottom: 5px"><b>Nationality:</b></td>
            <td> {{ $studentinfo->nationality }} </td>
            <td></td>
            <td> <b>Date Graduated:</b> </td>
            <td> {{ $details->graduationdate }} </td>
        </tr>
    </table>

    <table
        style="width: 100%; font-size: 12px;border-collapse: collapse; table-layout: fixed; border: 1px solid black;margin: 0px; transform: translateY(-2px);">
        <tr>
            <td width="20%" style="background-color: black; color: white; text-align: center;padding:2px;">
                <b>ENTRANCE</b>
            </td>
            <td width="30%"> </td>
            <td width="5%"></td>
            <td width="15%"> </td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px; padding-top: 10px"><b>Date / Term and School Year Admitted:</b>
                @if ($enrollment_his && $enrollment_his->created_at != null)
                    {{ date('F j, Y, g:i A', strtotime($enrollment_his->created_at)) }}
            </td>
            @endif
        </tr>
        <tr>
            <td colspan="5">
                <table style="table-layout: fixed;" width="100%">
                    <tr>
                        <td width="15%" style="padding-left: 5px; "><b>Category: </b></td>
                        <td width="5%" style="text-align: right !important;">
                            <input type="checkbox" name="category" @if ($acadprog == 5) checked @endif>
                        </td>
                        <td width="10%"> SHS </td>
                        <td width="5%">
                            <input type="checkbox" name="category" @if ($acadprog == 7) checked @endif>
                        </td>
                        <td width="25%" style="white-space: nowrap;">Vocation Level/Graduate</td>
                        <td width="5%">
                            <input type="checkbox" name="category" @if ($acadprog == 6) checked @endif>
                        </td>
                        <td width="15%" style="white-space: nowrap;">College Level</td>
                        <td width="5%">
                            <input type="checkbox" name="category" @if ($iscollege_grad) checked @endif>
                        </td>
                        <td width="15%" style="white-space: nowrap;">College Graduate</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px;"><b>Last School Attended:</b>
                {{ $studentinfo->lastschoolatt }} </td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px;padding-bottom: 5px"><b>Date Graduated / Last Term and School
                    Year Attended:</b> {{ $lastterm }} </td>
        </tr>

    </table>

    {{-- <table style="width: 100%; font-size: 12px; margin-top: 20px; table-layout: fixed;">
        <tr>
            <td style="width: 15%;">Date of Birth</td>
            <td style="width: 20%; text-align: left;">:
                {{ $studentinfo->dob != null ? date('m/d/Y', strtotime($studentinfo->dob)) : '' }}</td>
            <td style="width: 15%;">Sex</td>
            <td style="width: 20%; text-align: left;">: {{ strtoupper($studentinfo->gender) }}</td>
            <td style="width: 15%;">Date of Entrance</td>
            <td style="width: 20%; text-align: left;">: {{ $details->entrancedate ?? '' }}</td>
        </tr>
        <tr>
            <td>Birth Place</td>
            <td style="width: 20%; text-align: left;">: {{ $studentinfo->pob }}</td>
            <td>Citizenship</td>
            <td style="width: 20%; text-align: left;">: {{ $details->citizenship ?? $studentinfo->nationality }}</td>
            <td>Entrance Data</td>
            <td style="width: 20%; text-align: left;">: {{ $details->entrancedata ?? '' }}</td>
        </tr>
        <tr>
            <td>Father's Name</td>
            <td style="width: 20%; text-align: left;">: {{ $studentinfo->fathername }}</td>
            <td>Civil Status</td>
            <td style="width: 20%; text-align: left;">: {{ $studentinfo->civilstatus ?? '' }}</td>
            <td>Religion</td>
            <td style="width: 20%; text-align: left;">: {{ $studentinfo->religionname }}</td>
        </tr>
        <tr>
            <td>Mother's Name</td>
            <td style="width: 20%; text-align: left;">:{{ $studentinfo->mothername }}</td>
            <td>Home Address</td>
            <td colspan="3" style="text-align: left;">: {{ $studentinfo->street }}, {{ $studentinfo->barangay }},
                {{ $studentinfo->city }},
                {{ $studentinfo->province }}</td>
        </tr>
    </table> --}}

    {{-- <div style="width: 100%; margin-top: 10px; font-size: 12.5px; text-align: center; font-weight: bold;">RECORDS OF
        PRELIMINARY EDUCATION</div>
    <table style="width: 100%; font-size: 12px;" border="1">
        <tr>
            <td style="width: 18%; text-align: left;">&nbsp;&nbsp;&nbsp;Completed Courses</td>
            <td style="text-align: center;">Name of School</td>
            <td style="text-align: center;">Address</td>
            <td style="width: 15%; text-align: center;">School Year</td>
        </tr>
        <tr>
            <td style="text-align: left;">&nbsp;&nbsp;&nbsp;Primary</td>
            <td>{{ $details->primaryschoolname ?? '' }}</td>
            <td>{{ $details->primaryschooladdress ?? '' }}</td>
            <td>{{ $details->primaryschoolyear ?? '' }}</td>
        </tr>
        <tr>
            <td style="text-align: left;">&nbsp;&nbsp;&nbsp;Junior High School</td>
            <td>{{ $details->juniorschoolname ?? '' }}</td>
            <td>{{ $details->juniorschooladdress ?? '' }}</td>
            <td>{{ $details->juniorschoolyear ?? '' }}</td>
        </tr>
        <tr>
            <td style="text-align: left;">&nbsp;&nbsp;&nbsp;Senior High School</td>
            <td>{{ $details->seniorschoolname ?? '' }}</td>
            <td>{{ $details->seniorschooladdress ?? '' }}</td>
            <td>{{ $details->seniorschoolyear ?? '' }}</td>
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
            <td style="width: 32%;">{{ $details->graduationdate }}</td>
            <td style="width: 16%;">NSTP Serial No.:</td>
            <td>{{ $details->nstpserialno ?? '' }}</td>
        </tr>
    </table> --}}
    {{-- <div style="width: 100%; font-weight: bold; text-align: center; margin-top: 5px;">C O L L E G I A T E
        &nbsp;&nbsp;&nbsp;&nbsp;R E C O R D</div> --}}
    <table
        style="width: 100%; font-size: 12px;margin-top: 20px; table-layout: fixed; border-collapse: collapse; border: 1px solid black">
        <thead style="border: 1px solid black">
            <tr>
                <th style="width: 13%; border: 1px solid black;">Course No.</th>
                <th style="border: 1px solid black;">Descriptive Title</th>
                <th style="width: 12%; border: 1px solid black;">Grades</th>
                <th style="width: 12%; border: 1px solid black;">Units</th>
            </tr>
        </thead>
        @if (count($records) > 0)
            @foreach ($records as $key => $record)
                @php
                    $record->subjdata = collect($record->subjdata)->unique('subjcode');
                    $subjnum = count($record->subjdata);
                    $break = 0;
                @endphp

                @if ($initialschool == strtolower($record->schoolname))
                @else
                    @php
                        $initialschool = strtolower($record->schoolname);
                        if ($initialcourse != strtolower($record->coursename)) {
                            $initialcourse = $record->coursename;
                        }
                        // $firstcountrows+=2;
                    @endphp
                @endif

                @if (count($record->subjdata) > 0)
                    <tr style="font-size: 12px;">
                        <td colspan="4" style="font-weight: bold; text-align: left; padding: 5px;">
                            @if ($initialschool != $record->coursename)
                                {{ strtoupper($initialschool) }} <br>
                            @endif

                            @if ($record->semid == 1)
                                1st Semester
                            @elseif($record->semid == 2)
                                2nd Semester
                            @else
                                Summer
                            @endif
                            {{ $record->sydesc }}
                            <!--- {{ strtoupper($initialschool) }}-->
                        </td>
                    </tr>
                    @php
                        $firstcountrows += 1;
                    @endphp
                    @foreach (collect($record->subjdata)->values()->all() as $key => $subj)
                        @php

                            $subj->display = 0;
                        @endphp
                        <tr style="font-size: 12px;">
                            <td style="padding-left: 5px; border-top: hidden; border-bottom: hidden;">
                                {{ $subj->subjcode }}</td>
                            <td style=" border-top: hidden; border-bottom: hidden;">{{ $subj->subjdesc }}</td>
                            <td style=" border-top: hidden; border-bottom: hidden; text-align: center;">
                                {{ $subj->subjgrade }}</td>
                            <td style=" border-top: hidden; border-bottom: hidden;text-align: center;">
                                {{ $subj->subjcredit > 0 ? $subj->subjcredit : '' }}</td>
                        </tr>
                        @php
                            $subj->display = 1;
                            if ($firstcountrows == $firstrowsperpage) {
                                $break += 1;
                                break;
                            } else {
                                $firstcountrows += 1;
                            }
                        @endphp
                    @endforeach
                @endif
                @php
                    if ($firstcountrows == $firstrowsperpage) {
                        // $firstcountrows+=1;
                        if (collect($record->subjdata)->count() == 0) {
                            $record->display = 1;
                            break;
                        } else {
                            if (collect($record->subjdata)->count() == $subjnum) {
                                $record->display = 1;
                                break;
                            }
                        }
                    } else {
                        if (collect($record->subjdata)->count() == 0) {
                            $record->display = 1;
                        } else {
                            if (collect($record->subjdata)->count() == $subjnum) {
                                $record->display = 1;
                            }
                            if ($break > 0) {
                                break;
                            }
                        }
                    }
                    // if(count($record->subjdata) == $subjnum)
                    // {
                    //     $record->display = 1;
                    // }
                    // if($break>0)
                    // {
                    //     break;
                    // }
                @endphp
            @endforeach
            <tr>
                <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                <td style="text-align: center; border-top: hidden;"></td>
                <td style="text-align: center; border-top: hidden;"></td>
                <td style="text-align: center; border-top: hidden;"></td>
            </tr>
            @if (collect($records)->where('display', '0')->count() > 0)
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
            {{-- @for ($x = $firstcountrows; $x <= $firstrowsperpage; $x++)
                    @if ($x > $firstrowsperpage)
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
    @if (collect($records)->where('display', '0')->count() > 0)
        <div style="width: 100%; page-break-before: always; text-align: center; font-weight: bold;">
            {{ DB::table('schoolinfo')->first()->schoolname }}
        </div>
        <table
            style="width: 100%; font-size: 12px; table-layout: fixed; border-collapse: collapse; border: 1px solid black">
            <thead style="border: 1px solid black">
                <tr>
                    <th style="width: 13%; border: 1px solid black;">Course No.</th>
                    <th style="border: 1px solid black;">Descriptive Title</th>
                    <th style="width: 12%; border: 1px solid black;">Grades</th>
                    <th style="width: 12%; border: 1px solid black;">Units</th>
                </tr>
            </thead>
            @if (collect($records)->where('display', '0')->count() > 0)
                @foreach (collect($records)->where('display', '0')->values() as $key => $record)
                    @php
                        $record->subjdata = collect($record->subjdata)->unique('subjcode');
                        $subjnum = count($record->subjdata);
                        $break = 0;
                    @endphp
                    @if ($initialschool == strtolower($record->schoolname))
                    @else
                        @php
                            $initialschool = strtolower($record->schoolname);
                            if ($initialcourse != strtolower($record->coursename)) {
                                $initialcourse = $record->coursename;
                            }
                            // $firstcountrows+=2;
                        @endphp
                    @endif
                    @if (count($record->subjdata) > 0)
                        <tr style="font-size: 12px;">
                            <td colspan="4" style="font-weight: bold; text-align: left; padding: 5px;">
                                @if ($initialschool != $record->coursename)
                                    {{ strtoupper($initialschool) }} <br>
                                @endif

                                @if ($record->semid == 1)
                                    1st Semester
                                @elseif($record->semid == 2)
                                    2nd Semester
                                @else
                                    Summer
                                @endif
                                {{ $record->sydesc }}
                                <!--- {{ strtoupper($initialschool) }}-->
                            </td>
                        </tr>
                        @php
                            $countrows += 1;
                        @endphp
                        @foreach (collect($record->subjdata)->values()->all() as $key => $subj)
                            @php

                                $subj->display = 0;
                            @endphp
                            <tr style="font-size: 12px;">
                                <td style="padding-left: 5px; border-top: hidden; border-bottom: hidden;">
                                    {{ $subj->subjcode }}</td>
                                <td style=" border-top: hidden; border-bottom: hidden;">{{ $subj->subjdesc }}</td>
                                <td style=" border-top: hidden; border-bottom: hidden; text-align: center;">
                                    {{ $subj->subjgrade }}</td>
                                <td style=" border-top: hidden; border-bottom: hidden;text-align: center;">
                                    {{ $subj->subjcredit > 0 ? $subj->subjcredit : '' }}</td>
                            </tr>
                            @php
                                $subj->display = 1;
                                if ($countrows == $rowsperpage) {
                                    $break += 1;
                                    break;
                                } else {
                                    $countrows += 1;
                                }
                            @endphp
                        @endforeach
                    @endif
                    @php
                        if ($countrows == $rowsperpage) {
                            // $firstcountrows+=1;
                            if (collect($record->subjdata)->count() == 0) {
                                $record->display = 1;
                                break;
                            } else {
                                if (collect($record->subjdata)->count() == $subjnum) {
                                    $record->display = 1;
                                    break;
                                }
                            }
                        } else {
                            if (collect($record->subjdata)->count() == 0) {
                                $record->display = 1;
                            } else {
                                if (collect($record->subjdata)->count() == $subjnum) {
                                    $record->display = 1;
                                }
                                if ($break > 0) {
                                    break;
                                }
                            }
                        }
                        // if(count($record->subjdata) == $subjnum)
                        // {
                        //     $record->display = 1;
                        // }
                        // if($break>0)
                        // {
                        //     break;
                        // }
                    @endphp
                @endforeach
                <tr>
                    <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                    <td style="text-align: center; border-top: hidden;"></td>
                    <td style="text-align: center; border-top: hidden;"></td>
                    <td style="text-align: center; border-top: hidden;"></td>
                </tr>
                @if (collect($records)->where('display', '0')->count() > 0)
                    <tr>
                        <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                        <td style="text-align: center;">x-x-x-x-x-x- NEXT PAGE PLEASE -x-x-x-x-x-x</td>
                        <td style="text-align: center; border-top: hidden;"></td>
                        <td style="text-align: center; border-top: hidden;"></td>
                    </tr>
                @else
                    <tr>
                        <td style="text-align: center; border-top: hidden;">&nbsp;</td>
                        <td style="text-align: center; border-top: hidden;">x-x-x-x-x-x- NOTHING FOLLOWS -x-x-x-x-x-x
                        </td>
                        <td style="text-align: center; border-top: hidden;"></td>
                        <td style="text-align: center; border-top: hidden;"></td>
                    </tr>
                @endif
            @endif
        </table>
    @endif
</body>

</html>
