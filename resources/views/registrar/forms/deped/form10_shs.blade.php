<!DOCTYPE html>
<html lang="en">

<head>
    <title>SHS - School Form 10</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 11px;
        }

        @page {
            margin: 40px 20px 5px 20px;
            size: {{ $papersize != null ? $papersize : '8.5in 13in' }};
        }

        /*8.5 13*/

        #table1 td {
            padding: 0px;
        }

        table {
            border-collapse: collapse;
        }

        #table2 {
            margin-top: 2px;
            font-size: 11px;
        }

        input[type="checkbox"] {
            /* position: relative; */
            top: 2px;
            box-sizing: content-box;
            width: 14px;
            height: 14px;
            margin: 0 5px 0 0;
            cursor: pointer;
            -webkit-appearance: none;
            border-radius: 2px;
            background-color: #fff;
            border: 1px solid #b7b7b7;
        }

        input[type="checkbox"]:before {
            content: '';
            display: block;
        }

        input[type="checkbox"]:checked:before {
            width: 4px;
            height: 9px;
            margin: 0px 4px;
            border-bottom: 2px solid;
            border-right: 2px solid;
            transform: rotate(45deg);
        }

        .text-center {
            text-align: center;
        }

        table {
            page-break-inside: avoid !important;
        }

        .watermark {
            position: absolute;
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif !important;
            /**
            Set a position in the page for your image
            This should center it vertically
        **/
            top: -0.4cm;
            left: 0cm;
            opacity: 1;

            /** Change image dimensions**/
            /* width:    8cm;
        height:   8cm; */

            /** Your watermark should be behind every content**/
            z-index: -1000;
        }
    </style>
</head>

<body>
    {{-- <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_text(500, 5, "Page {PAGE_NUM} of {PAGE_COUNT}", '', 9, array(0,0,0));

            }
        </script>  --}}


    @php
        $guardianinfo = DB::table('studinfo')
            ->where('id', $studinfo->id)
            ->first();
        $guardianname = '';
        if ($guardianinfo->fathername == null) {
            $guardianname .= $guardianinfo->guardianname;
        } else {
            $explodename = explode(',', $guardianinfo->fathername);
            if (count($explodename) > 1) {
                $guardianname .= 'MR. AND MRS. ';
                $explodelastname = $explodename[0];

                $firstname = explode(' ', $explodename[1]);
                if (count($firstname) < 3) {
                    $guardianname .= $firstname[0];
                } else {
                    $guardianname .= $firstname[0] . ' ' . $firstname[1] . ' ';
                }
                $guardianname .= $explodelastname;
            }
        }
        $address = '';
        if ($guardianinfo->street != null) {
            $address .= $guardianinfo->street . ', ';
        }
        if ($guardianinfo->barangay != null) {
            $address .= $guardianinfo->barangay . ', ';
        }
        if ($guardianinfo->city != null) {
            $address .= $guardianinfo->city . ', ';
        }
        if ($guardianinfo->province != null) {
            $address .= $guardianinfo->province;
        }
        $studstatdate = '';
        $sh_enrolledstud = DB::table('sh_enrolledstud')
            ->where('studid', $studinfo->id)
            ->where('levelid', $studinfo->levelid)
            ->where('semid', $studinfo->semid)
            ->where('deleted', '0')
            ->first();

        if ($sh_enrolledstud) {
            if ($sh_enrolledstud->dateenrolled == null) {
            } else {
                $studstatdate .= date('F d, Y', strtotime($sh_enrolledstud->dateenrolled));
            }
        } else {
        }

        function lower_case($word)
        {
            return \App\Http\Controllers\SchoolFormsController::lowercase_word($word);
        }
    @endphp
    {{-- @if ($format == 'school') --}}
    {{-- <table style="width: 100%" >
            <tr>
                <td width="15%" rowspan="2" style="vertical-align:top;"><sup style="font-size: 9px;">SF10-SHS</sup></td>
                <td width="10%"rowspan="2" style="text-align: right;">
                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
                </td>
                <td style="text-align:center; font-size: 16px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
                <td width="10%" style="text-align:right;"  rowspan="2"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="80px"></td>
                <td width="15%" rowspan="5" style="border: 1px solid #ddd; vertical-align: middle; text-align: center; font-size: 10px;"><br/>Photo<br/>1x1</td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 11px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td rowspan="2" style="border: 1px solid #ddd; vertical-align: top; font-size: 8px;"><sup>Forwarded to:</sup></td>
                <td colspan="3" style="text-align:center; font-size: 14px; font-weight: bold;">SENIOR HIGH SCHOOL STUDENT PERMANENT RECORD</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>
    @else --}}
    <table style="width: 100%; top: -0.4cm; position: absolute;" id="table1">
        <tr>
            <td width="10%" @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif><sup
                    style="font-size: 9px;">SF10-SHS</sup><br />
                <img src="{{ base_path() }}/public/assets/images/department_of_Education.png" alt="school"
                    width="70px">
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="10%" style="text-align:right;"
                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif><img
                    src="{{ base_path() }}/public/assets/images/deped_logo.png" alt="school" width="80px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold; text-transform: uppercase;">Learner's
                Permanent Academic Record for Senior High School </td>
        </tr>

        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
            <tr>
                <td style="text-align:center; font-size: 20px; font-weight: bold; color: green; padding: 5px 0px;">Holy
                    Cross of Babak, Inc.</td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-SHS)</td>
            </tr>
            <tr style="font-size: 11px;">
                <td style="text-align:center; font-style: italic; padding: 7px 0px 0px 0px;">(Formerly Form 137)</td>
            </tr>
        @else
            <tr>
                <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-SHS)</td>
            </tr>
            <tr style="line-height: 5px; font-size: 11px;">
                <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
            </tr>
        @endif
    </table>
    {{-- @endif --}}
    <div style="width: 100%; line-height: 1px; margin-top: 70px;">&nbsp;</div>
    <table class="table table-sm table-bordered" width="100%"
        style="font-size: 11px !important; margin-top:.5rem !important;">
        <tr>
            <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">
                LEARNER'S INFORMATION</td>
        </tr>
    </table>
    <table class="table table-sm" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
        <tr>
            <td style="width: 10%;">LAST NAME:</td>
            <td style="width: 25%; border-bottom: 1px solid black;">{{ $studinfo->lastname }}</td>
            <td style="width: 10%;">FIRST NAME:</td>
            <td style="width: 25%; border-bottom: 1px solid black;">{{ $studinfo->firstname }} {{ $studinfo->suffix }}
            </td>
            <td style="width: 11%;">MIDDLE NAME:</td>
            <td style="width: 19%; border-bottom: 1px solid black;">{{ $studinfo->middlename }}</td>
        </tr>
    </table>
    <table class="table table-sm" width="100%" style="font-size: 11px !important;">
        <tr>
            <td style="width: 5%;">LRN:</td>
            <td style="width: 12%; border-bottom: 1px solid black;">{{ $studinfo->lrn }}</td>
            <td style="width: 20%; text-align: right;">Date of Birth (MM/DD/YYYY):</td>
            <td style="width: 15%; border-bottom: 1px solid black;">
                {{ \Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY') }}</td>
            <td style="width: 5%; text-align: right;">Sex:</td>
            <td style="width: 7%; border-bottom: 1px solid black;">{{ $studinfo->gender }}</td>
            <td style="width: 26%;">Date of SHS Admission (MM/DD/YYYY):</td>
            <td style="width: 10%; border-bottom: 1px solid black;">
                @if ($eligibility->shsadmissiondate != null)
                    {{ date('m/d/Y', strtotime($eligibility->shsadmissiondate)) ?? null }}
                @endif
            </td>
        </tr>
    </table>
    <table class="table table-sm table-bordered" width="100%"
        style="font-size: 11px !important; margin-top:.5rem !important;">
        <tr>
            <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">
                ELIGIBILITY FOR SHS ENROLMENT</td>
        </tr>
    </table>
    <table class="table table-sm table-bordered" width="100%"
        style="font-size: 10px !important; margin-top:.5rem !important;">
        <tr>
            <td width="2%" style="border:solid 1px black" class="text-center">
                @if ($eligibility->completerhs == '1')
                    <b>/</b>
                @endif
            </td>
            <td width="16%">
                High School Completer*
            </td>
            <td width="7%">
                Gen. Ave:
            </td>
            <td width="7%" style="border-bottom: 1px solid;">
                @if ($eligibility->completerhs == '1')
                    {{ $eligibility->genavehs }}
                @endif
            </td>
            <td width="2%"></td>
            <td width="2%" style="border:solid 1px black" class="text-center">
                @if ($eligibility->completerjh == '1')
                    <b>/</b>
                @endif
            </td>
            <td width="21%">
                Junior High School Completer*
            </td>
            <td width="7%">
                Gen. Ave:
            </td>
            <td width="7%" style="border-bottom: 1px solid;">
                @if ($eligibility->completerjh == '1')
                    {{ $eligibility->genavejh }}
                @endif
            </td>
            <td width="30%"></td>
        </tr>
    </table>
    <table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important;">
        <tr>
            <td width="27%" style="vertical-align: top;">Date of Graduation/Completing (MM/DD/YYYY):<u>
                    @if ($eligibility->graduationdate == null)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@else{{ date('m/d/Y', strtotime($eligibility->graduationdate)) }}
                    @endif
                </u>
            </td>
            <td width="40%" style="vertical-align: top;">Name of School:<u>
                    @if ($eligibility->schoolname == null)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@else{{ $eligibility->schoolname }}
                    @endif
                </u>
            </td>
            <td style="vertical-align: top;" width="23%" colspan="2">School Address: <u>
                    @if ($eligibility->schooladdress == null)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@else{{ $eligibility->schooladdress }}
                    @endif
                </u></td>
        </tr>
    </table>
    <table class="table table-sm table-bordered" width="100%"
        style="font-size: 10px !important; margin-top:.2rem !important; table-layout: fixed;">
        <tr>
            <td width="2%" class="text-center" style="vertical-align: top;">
                <input type="checkbox"
                    style="border: 1px solid black;"@if ($eligibility->peptpasser == '1') checked @endif />
            </td>
            <td width="16%" style="vertical-align: top; padding-top: 3px;">
                PEPT Passer**
            </td>
            <td width="5%" style="vertical-align: top; padding-top: 3px;">
                Rating:
            </td>
            <td width="9%" style="vertical-align: top; padding-top: 3px;">
                @if ($eligibility->peptpasser == '1')
                    <u>&nbsp;&nbsp;&nbsp;&nbsp;{{ $eligibility->peptrating }}&nbsp;&nbsp;&nbsp;&nbsp;</u>
                @else
                    <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                @endif
            </td>
            <td width="2%"></td>
            <td width="2%" style="vertical-align: top;" class="text-center">
                <input type="checkbox"
                    style="border: 1px solid black;"@if ($eligibility->alspasser == '1') checked @endif />
            </td>
            <td width="12%" style="vertical-align: top; padding-top: 3px;">
                ALS A&E Passer**
            </td>
            <td width="5%" style="vertical-align: top; padding-top: 3px;">
                Rating:
            </td>
            <td width="9%" style="vertical-align: top; padding-top: 3px;">
                @if ($eligibility->peptpasser == '1')
                    <u>&nbsp;&nbsp;&nbsp;&nbsp;{{ $eligibility->alsrating }}&nbsp;&nbsp;&nbsp;&nbsp;</u>
                @else
                    <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                @endif
            </td>
            <td width="2%"></td>
            <td width="2%" style="vertical-align: top;" class="text-center">
                <input type="checkbox"
                    style="border: 1px solid black;"@if (strlen($eligibility->others) > 0) checked @endif />
            </td>
            <td width="23%" style="vertical-align: top; padding-top: 3px;">
                Others (Pls. Specify):
                @if (strlen($eligibility->others) == 0)
                    <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                @elseif(strlen($eligibility->others) < 15)
                    <u>{{ $eligibility->others }}</u>
                @else
                    <u>{{ $eligibility->others }}</u>
                @endif
            </td>
        </tr>
    </table>
    <table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; ">
        <tr>
            <td width="30%">Date of Examination/Assessment (MM/DD/YYYY):</td>
            <td width="15%" style="border-bottom: 1px solid;">
                @if ($eligibility->examdate != null)
                    {{ date('m/d/Y', strtotime($eligibility->examdate)) }}
                @endif
            </td>
            <td width="30%">Name and Address of Community Learning Center:</td>
            <td width="15%" style="border-bottom: 1px solid;">
                {{ $eligibility->centername }}
            </td>
            <td width="10%"></td>
        </tr>
    </table>
    <table class="table table-sm table-bordered" width="100%" style="font-size: 7px !important; ">
        <tr>
            <td colspan="2"><em>*High School Completers are students who graduated from secondary school under the
                    old curriculum</em></td>
            <td colspan="2"><em>***ALS A&E - Alternative Learning System Accreditation and Equivalency Test for
                    JHS</em></td>
        </tr>
        <tr>
            <td colspan="4"><em>**PEPT - Philippine Educational Placement Test for JHS</em></td>
        </tr>
    </table>

    <table class="table table-sm table-bordered" width="100%"
        style="font-size: 11px !important; margin-top:.5rem !important;">
        <tr>
            <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">
                SCHOLASTIC RECORD</td>
        </tr>
    </table>
    @php
        $record_count = 0;
    @endphp
    <div style="width: 100%; line-height: 4px;">&nbsp;</div>
    @foreach ($gradelevels as $eachlevelkey => $eachgradelevel)
        @foreach ($eachgradelevel->records as $eachrecordkey => $eachrecord)
            @php
                $record_count += 1;
            @endphp

            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <thead
                    style="border-left: 2px solid black; border-right: 2px solid black; border-top: 2px solid black">

                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 38%; border-bottom: 1px solid black;">
                                        {{ $eachrecord->schoolname }}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 7%; border-bottom: 1px solid black;">{{ $eachrecord->schoolid }}
                                    </td>
                                    <td style="width: 10%;">GRADE LEVEL:</td>
                                    <td style="width: 8%; border-bottom: 1px solid black;">
                                        {{ preg_replace('/\D+/', '', $eachrecord->levelname) }}</td>
                                    <td style="width: 2%;">SY:</td>
                                    <td style="width: 13%; border-bottom: 1px solid black;">{{ $eachrecord->sydesc }}
                                    </td>
                                    <td style="width: 4%;">SEM:</td>
                                    <td style="width: 6%; border-bottom: 1px solid black;">
                                        @if ($eachrecord->semid == 1)
                                            1st
                                        @elseif($eachrecord->semid == 2)
                                            2nd
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 10%;">TRACK/STRAND:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">
                                        {{ $eachrecord->trackname }} / {{ $eachrecord->strandname }}</td>
                                    <td style="width: 7%;">SECTION:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">
                                        {{ $eachrecord->sectionname }}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr style="font-size: 9px !important;">
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE,
                            APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                        <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                        <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
                                SEM
                            @endif FINAL
                            <br />GRADE
                        </th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br />TAKEN</th>
                    </tr>
                    <tr>
                        <th style="width: 8%; border: solid 1px black;">
                            @if ($eachrecord->semid == 1)
                                1
                            @elseif($eachrecord->semid == 2)
                                3
                            @endif
                        </th>
                        <th style="width: 8%; border: solid 1px black;">
                            @if ($eachrecord->semid == 1)
                                2
                            @elseif($eachrecord->semid == 2)
                                4
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody
                    style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black">
                    @if (collect($eachrecord->grades)->where('semid', $eachrecord->semid)->unique('subjdesc')->count() > 1)
                        @php
                            $gen_ave_for_sem = 0;
                            $with_final_rating = true;
                        @endphp
                        @foreach (collect($eachrecord->grades)->where('semid', $eachrecord->semid)->unique('subjdesc') as $grade)
                            @php
                                $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                                $average = $with_final_rating ? ($grade->q1 + $grade->q2) / 2 : '';
                                $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                            @endphp
                            @if ($eachrecord->type == 2)
                                @if (strtolower($grade->subjdesc) != 'general average')
                                    <tr>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->subjcode }}</td>
                                        <td style="border: solid 1px black;">{{ lower_case($grade->subjdesc) }}</td>
                                        <td class="text-center" style="border: solid 1px black;">{{ $grade->q1 }}
                                        </td>
                                        <td class="text-center" style="border: solid 1px black;">{{ $grade->q2 }}
                                        </td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->finalrating > 0 ? $grade->finalrating : '' }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->remarks }}</td>
                                    </tr>
                                @endif
                            @else
                                @if (strtolower($grade->subjdesc) != 'general average')
                                    <tr>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->subjcode }}</td>
                                        <td style="border: solid 1px black;">{{ lower_case($grade->subjdesc) }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->q1 > 0 ? number_format($grade->q1) : '' }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->q2 > 0 ? number_format($grade->q2) : '' }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->finalrating > 0 ? $grade->finalrating : '' }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $grade->finalrating > 0 ? $grade->remarks : '' }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        @if ($eachrecord->type == 1)
                            @if (count($eachrecord->subjaddedforauto) > 0)
                                @foreach ($eachrecord->subjaddedforauto as $customsubjgrade)
                                    <tr>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $customsubjgrade->subjcode }}</td>
                                        <td style="border: solid 1px black;">{{ $customsubjgrade->subjdesc }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ number_format($customsubjgrade->q1) }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ number_format($customsubjgrade->q2) }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $customsubjgrade->finalrating }}</td>
                                        <td class="text-center" style="border: solid 1px black;">
                                            {{ $customsubjgrade->actiontaken }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr style="font-weight: bold;">
                                @php
                                    $genave = $eachrecord->generalaverage;
                                @endphp
                                <td colspan="4" style="text-align: right; border: solid 1px black;">General Average
                                </td>
                                <td class="text-center" style="border: solid 1px black;">
                                    @if (count($genave) > 0)
                                        @if ($genave[0]->finalrating > 0)
                                            {{ $genave[0]->finalrating }}
                                        @endif
                                    @endif
                                </td>
                                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                    <td class="text-center" style="border: solid 1px black;">
                                        @if (count($genave) > 0)
                                            @if ($genave[0]->finalrating > 0)
                                                {{ $genave[0]->finalrating >= 75 ? 'COMPLETER' : 'FAILED' }}
                                            @endif
                                        @endif
                                    </td>
                                @else
                                    <td class="text-center" style="border: solid 1px black;">
                                        @if (count($genave) > 0)
                                            @if ($genave[0]->finalrating > 0)
                                                {{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                            @endif
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @elseif($eachrecord->type == 2)
                            @php
                                $genave = $eachrecord->generalaverage[0]->finalrating ?? 0;
                                if ($genave == 0) {
                                    if (count($eachrecord->grades) > 0) {
                                        foreach ($eachrecord->grades as $grade) {
                                            if (strtolower($grade->subjdesc) == 'general average') {
                                                $genave = $grade->finalrating;
                                            }
                                        }
                                    }
                                }
                                if ($genave == 0) {
                                    $genave = collect($eachrecord->grades)->avg('finalrating');
                                }
                            @endphp
                            <tr style="font-weight: bold;">
                                <td colspan="4" style="text-align: right;">General Average</td>
                                <td class="text-center">{{ $genave > 0 ? $genave : '' }}</td>
                                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                    <td class="text-center">{{ $genave >= 75 ? 'COMPLETER' : 'FAILED' }}</td>
                                @else
                                    <td class="text-center">
                                        {{ $genave >= 75 ? 'PASSED' : ($genave == 0 ? '' : 'FAILED') }}</td>
                                @endif
                            </tr>
                        @endif
                    @else
                        @php
                            // $levelsubjects = collect($gradelevels)->where('id', 14)->first()->subjects;
                            $defaultsubjects = collect($eachgradelevel->subjects)
                                ->where('semid', $eachrecord->semid)
                                ->unique('subjcode');
                        @endphp
                        @for ($x = 0; $x < 10; $x++)
                            <tr>
                                <td style="border: solid 1px black;">&nbsp;</td>
                                <td style="border: solid 1px black;">&nbsp;</td>
                                <td style="border: solid 1px black;">&nbsp;</td>
                                <td style="border: solid 1px black;">&nbsp;</td>
                                <td style="border: solid 1px black;">&nbsp;</td>
                                <td style="border: solid 1px black;">&nbsp;</td>
                            </tr>
                        @endfor
                        <tr style="font-weight: bold;">
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average
                            </td>
                            <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                            <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @php
                $eachsemremarks = '';
                $certncorrectname = '';
                $certncorrectdesc = '';
                $datechecked = '';
                if (
                    collect($eachsemsignatories)
                        ->where('levelid', $eachrecord->levelid)
                        ->where('semid', $eachrecord->semid)
                        ->first()
                ) {
                    $signinfo = collect($eachsemsignatories)
                        ->where('levelid', $eachrecord->levelid)
                        ->where('semid', $eachrecord->semid)
                        ->first();
                    $certncorrectname = $signinfo->certncorrectname;
                    $certncorrectdesc = $signinfo->certncorrectdesc;
                    $eachsemremarks = $signinfo->remarks;
                    $datechecked =
                        $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                } else {
                    $certncorrectname = $eachrecord->recordincharge;
                    $certncorrectdesc = "SHS-School Record's In-Charge";
                    $datechecked =
                        $eachrecord->datechecked != null ? date('M d, Y', strtotime($eachrecord->datechecked)) : '';
                }
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4">{{ $eachsemremarks }}</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">
                        &nbsp;{{ $eachrecord->teachername }}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">
                        &nbsp;{{ $certncorrectname }}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">
                        &nbsp;{{ $datechecked }}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">&nbsp;{{ $certncorrectdesc }}</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            @if ($eachlevelkey == 0)
                @if (count($eachgradelevel->records) > 2 && $eachrecordkey > 1)
                    <table class="watermark" width="100%" style=" font-size: 10px;">
                        <tr>
                            <td width="10%">Page {{ $eachlevelkey + 2 }}</td>
                            <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                            <td width="10%" style="text-align: right;">SF10-SHS</td>
                        </tr>
                    </table>
                @endif
            @else
                @if (count($eachgradelevel->records) > 2)
                    @if (count($records) == 3)
                        @if ($papersize == '8.3in 11.7in' || $papersize == '8.5in 11in')
                            <table class="watermark" width="100%" style=" font-size: 10px;">
                                <tr>
                                    <td width="10%">Page {{ $eachlevelkey + 1 }}</td>
                                    <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                                    <td width="10%" style="text-align: right;">SF10-SHS</td>
                                </tr>
                            </table>
                        @endif
                    @else
                        <table class="watermark" width="100%" style=" font-size: 10px;">
                            <tr>
                                <td width="10%">Page {{ $eachlevelkey + 2 }}</td>
                                <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                                <td width="10%" style="text-align: right;">SF10-SHS</td>
                            </tr>
                        </table>
                    @endif
                @else
                    @if (count($records) == 2)
                        @if ($papersize == '8.3in 11.7in' || $papersize == '8.5in 11in')
                            <table class="watermark" width="100%" style=" font-size: 10px;">
                                <tr>
                                    <td width="10%">Page 2</td>
                                    <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                                    <td width="10%" style="text-align: right;">SF10-SHS</td>
                                </tr>
                            </table>
                        @endif
                    @endif
                @endif
            @endif
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            @php
                $eachsemremedial_teachername = '';
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 9px; page-break-inside: avoid;">
                @if (collect($eachrecord->remedials)->contains('type', '2'))
                    @php
                        $eachsemremedial_header = collect($eachrecord->remedials)
                            ->where('type', '2')
                            ->values();
                        $eachsemremedial_teachername = $eachsemremedial_header[0]->teachername;
                    @endphp
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">
                            {{ $eachsemremedial_header[0]->datefrom != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->datefrom)) : '' }}
                        </td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">
                            {{ $eachsemremedial_header[0]->dateto != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->dateto)) : '' }}
                        </td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;">{{ $eachsemremedial_header[0]->schoolname }}</td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;">{{ $eachsemremedial_header[0]->schoolid }}</td>
                    </tr>
                @else
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;"></td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;"></td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="9"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table
                style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px; text-transform: uppercase;"
                border="1">

                <tr style="font-size: 9px !important;">
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">
                        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
                            SEM
                        @endif FINAL
                        <br />GRADE
                    </th>
                    <th style="width: 10%;">REMEDIAL<br />CLASS<br />MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br />FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                @if (collect($eachrecord->remedials)->contains('type', '1'))
                    @foreach ($eachrecord->remedials as $remedial)
                        @if ($remedial->type == 1)
                            <tr>
                                <td class="text-center">{{ $remedial->subjectcode }}</td>
                                <td>{{ $remedial->subjectname }}</td>
                                <td class="text-center">{{ $remedial->finalrating }}</td>
                                <td class="text-center">{{ $remedial->remclassmark }}</td>
                                <td class="text-center">{{ $remedial->recomputedfinal }}</td>
                                <td class="text-center">{{ $remedial->remarks }}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @for ($x = 0; $x < 3; $x++)
                        <tr>
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
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2">
                        {{ $eachsemremedial_teachername }}</td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
        @endforeach
    @endforeach
    {{-- @if (count($records) > 0)
    @php
        $record_count = 1;
    @endphp
    @foreach ($records as $recordkey => $record)
        @if (count($record) == 1)
        
            
            
        @elseif(count($record) == 2)
        
            
            
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                    @php
                        $record_count += 1;
                    @endphp
                <thead style="border-left: 2px solid black; border-right: 2px solid black; border-top: 2px solid black">
                    
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 38%; border-bottom: 1px solid black;">{{$record[0]->schoolname}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 7%; border-bottom: 1px solid black;">{{$record[0]->schoolid}}</td>
                                    <td style="width: 10%;">GRADE LEVEL:</td>
                                    <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[0]->levelname)}}</td>
                                    <td style="width: 2%;">SY:</td>
                                    <td style="width: 13%; border-bottom: 1px solid black;">{{$record[0]->sydesc}}</td>
                                    <td style="width: 4%;">SEM:</td>
                                    <td style="width: 6%; border-bottom: 1px solid black;">@if ($record[0]->semid == 1) 1st @elseif($record[0]->semid == 2) 2nd @endif</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 10%;">TRACK/STRAND:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[0]->trackname}} / {{$record[0]->strandname}}</td>
                                    <td style="width: 7%;">SECTION:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{$record[0]->sectionname}}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr style="font-size: 9px !important;">
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                        <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                        <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr >
                        <th style="width: 8%; border: solid 1px black;">1</th>
                        <th style="width: 8%; border: solid 1px black;">2</th>
                    </tr>
                </thead>
                <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black">
                @if (collect($record[0]->grades)->where('semid', $record[0]->semid)->unique('subjdesc')->count() > 1)
                    @php
                        $gen_ave_for_sem = 0;
                        $with_final_rating = true;
                    @endphp
                    @foreach (collect($record[0]->grades)->where('semid', $record[0]->semid)->unique('subjdesc') as $grade)
                        @php
                            $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                            $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                            $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                        @endphp
                        @if ($record[0]->type == 2)
                            @if (strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if (strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1 > 0 ? number_format($grade->q1) : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2 > 0 ? number_format($grade->q2) : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating > 0 ? $grade->remarks : ''}}</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach                    
                    @if ($record[0]->type == 1)
                        @if (count($record[0]->subjaddedforauto) > 0)
                            @foreach ($record[0]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{$customsubjgrade->subjdesc}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q1)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q2)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr style="font-weight: bold;">
                            @php
                                $genave = $record[0]->generalaverage;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">@if (count($genave) > 0)@if ($genave[0]->finalrating > 0){{$genave[0]->finalrating}}@endif @endif</td>
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                            <td class="text-center" style="border: solid 1px black;">@if (count($genave) > 0)@if ($genave[0]->finalrating > 0){{ $genave[0]->finalrating >= 75 ? 'COMPLETER' : 'FAILED'  }}@endif @endif</td>
                            @else
                            <td class="text-center" style="border: solid 1px black;">@if (count($genave) > 0)@if ($genave[0]->finalrating > 0){{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED'  }}@endif @endif</td>
                            @endif
                        </tr>
                    @elseif($record[0]->type == 2)
                    @php
                        $genave = $record[0]->generalaverage[0]->finalrating ?? 0;
                        if($genave == 0)
                        {
                            if(count($record[0]->grades) > 0)
                            {
                                foreach($record[0]->grades as $grade)
                                {
                                    if(strtolower($grade->subjdesc) == 'general average')
                                    {
                                        $genave = $grade->finalrating;
                                    }
                                }
                            }
                        }
                        if($genave == 0)
                        {
                            $genave = collect($record[0]->grades)->avg('finalrating');
                        }
                    @endphp
                    <tr style="font-weight: bold;">
                        <td colspan="4" style="text-align: right;">General Average</td>
                        <td class="text-center">{{$genave > 0 ? $genave : ''}}</td>
                        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                        <td class="text-center">{{$genave >= 75 ? 'COMPLETER' : 'FAILED'}}</td>
                        @else
                        <td class="text-center">{{$genave >= 75 ? 'PASSED' : ($genave == 0 ? '' : 'FAILED')}}</td>
                        @endif
                    </tr>
                    @endif
                    
                @else
                    @php
                    if($recordkey == 0)
                    {
                    $levelsubjects = collect($gradelevels)->where('id', 14)->first()->subjects;
                    }else{
                    $levelsubjects = collect($gradelevels)->where('id', 15)->first()->subjects;
                    }
                    $defaultsubjects = collect($levelsubjects)->where('semid', $record[0]->semid)->unique('subjcode');
                    @endphp
                    @for ($x = 0; $x < 10; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                    <tr style="font-weight: bold;">
                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                    </tr>
                @endif
                </tbody>
            </table>
            @php
                $eachsemremarks = '';
                $certncorrectname = '';
                $certncorrectdesc = '';
                $datechecked = '';
                if(collect($eachsemsignatories)->where('levelid', $record[0]->levelid)->where('semid',$record[0]->semid)->first())
                {
                    $signinfo = collect($eachsemsignatories)->where('levelid', $record[0]->levelid)->where('semid',$record[0]->semid)->first();
                    $certncorrectname = $signinfo->certncorrectname;
                    $certncorrectdesc = $signinfo->certncorrectdesc;
                    $eachsemremarks = $signinfo->remarks;
                    $datechecked = $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                }else{
                    $certncorrectname = $record[0]->recordincharge;
                    $certncorrectdesc = "SHS-School Record's In-Charge";
                    $datechecked = $record[0]->datechecked != null ?  date('M d, Y',strtotime($record[0]->datechecked)) : '';
                }
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4">{{$eachsemremarks}}</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$record[0]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$certncorrectname}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$datechecked}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">&nbsp;{{$certncorrectdesc}}</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            @php
                $eachsemremedial_teachername = '';
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                @if (collect($record[0]->remedials)->contains('type', '2'))
                    @php
                        $eachsemremedial_header = collect($record[0]->remedials)->where('type','2')->values();
                        $eachsemremedial_teachername = $eachsemremedial_header[0]->teachername;
                    @endphp
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->datefrom != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->datefrom)) : ''}}</td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->dateto != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->dateto)) : ''}}</td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolname}}</td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolid}}</td>
                    </tr>
                @else
                <tr>
                    <td style="width: 15%;">REMEDIAL CLASSES</td>
                    <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td style="width: 13%;">to (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td>SCHOOL:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td>SCHOOL ID:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                @endif
                <tr>
                    <td colspan="9"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px; text-transform: uppercase;" border="1">
                
                <tr style="font-size: 9px !important;">
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                    <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                    @if (collect($record[0]->remedials)->contains('type', '1'))
                        @foreach ($record[0]->remedials as $remedial)
                            @if ($remedial->type == 1)
                                <tr>
                                    <td class="text-center">{{$remedial->subjectcode}}</td>
                                    <td>{{$remedial->subjectname}}</td>
                                    <td class="text-center">{{$remedial->finalrating}}</td>
                                    <td class="text-center">{{$remedial->remclassmark}}</td>
                                    <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                    <td class="text-center">{{$remedial->remarks}}</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        @for ($x = 0; $x < 3; $x++)
                            <tr>
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
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2">{{$eachsemremedial_teachername}}</td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px; " border="1">
                <thead>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 38%; border-bottom: 1px solid black;">{{$record[1]->schoolname}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 7%; border-bottom: 1px solid black;">{{$record[1]->schoolid}}</td>
                                    <td style="width: 10%;">GRADE LEVEL:</td>
                                    <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[1]->levelname)}}</td>
                                    <td style="width: 2%;">SY:</td>
                                    <td style="width: 13%; border-bottom: 1px solid black;">{{$record[1]->sydesc}}</td>
                                    <td style="width: 4%;">SEM:</td>
                                    <td style="width: 6%; border-bottom: 1px solid black;">@if ($record[1]->semid == 1) 1st @elseif($record[1]->semid == 2) 2nd @endif</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 10%;">TRACK/STRAND:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[1]->trackname}} / {{$record[1]->strandname}}</td>
                                    <td style="width: 7%;">SECTION:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{$record[1]->sectionname}}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr style="font-size: 9px !important;">
                        <th rowspan="2" style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 2nd sem
                        <th rowspan="2" style="width: 40%;">SUBJECTS</th>
                        <th colspan="2">Quarter</th>
                        <th rowspan="2" style="width: 10%;">@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">3</th>
                        <th style="width: 8%;">4</th>
                    </tr>
                </thead>
                @php
                    $gen_ave_for_sem = 0;
                    $with_final_rating = true;
                @endphp
                @if (collect($record[1]->grades)->where('semid', $record[1]->semid)->unique('subjdesc')->count() > 0)
                    @foreach (collect($record[1]->grades)->where('semid', $record[1]->semid)->unique('subjdesc') as $grade)
                        @php
                            $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                            $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                            $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                        @endphp
                        @if ($record[1]->type == 2)
                            @if (strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center">{{$grade->subjcode}}</td>
                                    <td>{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center">{{$grade->q1}}</td>
                                    <td class="text-center">{{$grade->q2}}</td>
                                    <td class="text-center">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if (strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center">{{$grade->subjcode}}</td>
                                    <td>{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center">{{$grade->q1 != null || $grade->q2 != 0 ? number_format($grade->q1) : ''}}</td>
                                    <td class="text-center">{{$grade->q2 != null || $grade->q2 != 0 ? number_format($grade->q2) : ''}}</td>
                                    <td class="text-center">
                                        {{$grade->finalrating}}
                                        </td>
                                    <td class="text-center">
                                        {{$grade->remarks}}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    @if ($record[1]->type == 1)
                        @if (count($record[1]->subjaddedforauto) > 0)
                            @foreach ($record[1]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{$customsubjgrade->subjdesc}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q1)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q2)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr style="font-weight: bold;">
                            @php
                                $genave = $record[1]->generalaverage;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">@if (count($genave) > 0)@if ($genave[0]->finalrating > 0){{$genave[0]->finalrating}}@endif @endif</td>
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                            <td class="text-center" style="border: solid 1px black;">@if (count($genave) > 0)@if ($genave[0]->finalrating > 0){{ $genave[0]->finalrating >= 75 ? 'COMPLETER' : 'FAILED'  }}@endif @endif</td>
                            @else
                            <td class="text-center" style="border: solid 1px black;">@if (count($genave) > 0)@if ($genave[0]->finalrating > 0){{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED'  }}@endif @endif</td>
                            @endif
                        </tr>
                    @elseif($record[1]->type == 2)
                        @php
                            $genave = $record[1]->generalaverage[0]->finalrating ?? 0;
                            if($genave == 0)
                            {
                                if(count($record[1]->grades) > 0)
                                {
                                    foreach($record[1]->grades as $grade)
                                    {
                                        if(strtolower($grade->subjdesc) == 'general average')
                                        {
                                            $genave = $grade->finalrating;
                                        }
                                    }
                                }
                            }
                            if($genave == 0)
                            {
                                $genave = collect($record[1]->grades)->avg('finalrating');
                            }
                        @endphp
                        <tr style="font-weight: bold;">
                            <td colspan="4" style="text-align: right;">General Average</td>
                        <td class="text-center">{{$genave > 0 ? $genave : ''}}</td>
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                            <td class="text-center">{{$genave >= 75 ? 'COMPLETER' : 'FAILED'}}</td>
                            @else
                        <td class="text-center">{{$genave >= 75 ? 'PASSED' : ($genave == 0 ? '' : 'FAILED')}}</td>
                            @endif
                        </tr>
                    @endif
                @else
                    @php
                    if($recordkey == 0)
                    {
                    $levelsubjects = collect($gradelevels)->where('id', 14)->first()->subjects;
                    }else{
                    $levelsubjects = collect($gradelevels)->where('id', 15)->first()->subjects;
                    }
                    $defaultsubjects = collect($levelsubjects)->where('semid', 2)->unique('subjcode');
                    @endphp
                    @for ($x = 0; $x < 10; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                    <tr style="font-weight: bold;">
                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                    </tr>
                @endif
            </table>
            
            @php
                $certncorrectname = '';
                $certncorrectdesc = '';
                $datechecked = '';                
                $eachsemremarks = '';
                if(collect($eachsemsignatories)->where('levelid', $record[1]->levelid)->where('semid',$record[1]->semid)->first())
                {
                    $signinfo = collect($eachsemsignatories)->where('levelid', $record[1]->levelid)->where('semid',$record[1]->semid)->first();
                    $certncorrectname = $signinfo->certncorrectname;
                    $certncorrectdesc = $signinfo->certncorrectdesc;
                    $eachsemremarks = $signinfo->remarks;
                    $datechecked = $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                }else{
                    $certncorrectname = $record[1]->recordincharge;
                    $certncorrectdesc = "SHS-School Record's In-Charge";
                    $datechecked = $record[1]->datechecked != null ?  date('M d, Y',strtotime($record[1]->datechecked)) : '';
                }
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4">{{$eachsemremarks}}</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$record[1]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$certncorrectname}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$datechecked}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">&nbsp;{{$certncorrectdesc}}</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            @php
                $eachsemremedial_teachername = '';
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                @if (collect($record[1]->remedials)->contains('type', '2'))
                    @php
                        $eachsemremedial_header = collect($record[1]->remedials)->where('type','2')->values();
                        $eachsemremedial_teachername = $eachsemremedial_header[0]->teachername;
                    @endphp
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->datefrom != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->datefrom)) : ''}}</td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->dateto != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->dateto)) : ''}}</td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolname}}</td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolid}}</td>
                    </tr>
                @else
                <tr>
                    <td style="width: 15%;">REMEDIAL CLASSES</td>
                    <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td style="width: 13%;">to (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td>SCHOOL:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td>SCHOOL ID:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                @endif
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; text-transform: uppercase;" border="1">
                
                <tr style="font-size: 9px !important;">
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                    <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                @if (collect($record[1]->remedials)->contains('type', '1'))
                    @foreach ($record[1]->remedials as $remedial)
                        @if ($remedial->type == 1)
                            <tr>
                                <td class="text-center">{{$remedial->subjectcode}}</td>
                                <td>{{$remedial->subjectname}}</td>
                                <td class="text-center">{{$remedial->finalrating}}</td>
                                <td class="text-center">{{$remedial->remclassmark}}</td>
                                <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                <td class="text-center">{{$remedial->remarks}}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @for ($x = 0; $x < 3; $x++)
                        <tr>
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
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2">{{$eachsemremedial_teachername}}</td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
        @endif
        @if ($record_count == 2)
            @if (count($records) > 2)
            <table class="{{$papersize == '8.3in 11.7in' ? 'watermark' : 'table'}}" width="100%" style=" font-size: 10px;">
                <tr>
                    <td width="10%">Page {{$record_count}}</td>
                    <td width="80%">{{$studinfo->lastname}}, {{$studinfo->firstname}}</td>
                    <td width="10%" style="text-align: right;">SF10-SHS</td>
                </tr>
            </table>
            @else
            <div style="page-break-before: always;"></div>
            @endif
        @endif
    @endforeach
    @if (count($records) == 1)
    @endif
@endif --}}
    <table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
        <tr>
            <td style="width:20%"><strong>Track/Strand Accomplished:</strong></td>
            <td style="border-bottom: 1px solid;width:45%; text-align: center;">
                {{ $footer->strandaccomplished }}
            </td>
            <td style="width:20%"><strong>SHS General Average:</strong></td>
            <td style="border-bottom: 1px solid; text-align: center;">{{ $footer->shsgenave }}</td>
        </tr>
    </table>
    <table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
        <tr>
            <td style="width:20%"><strong>Awards/Honors Received:</strong></td>
            <td style="border-bottom: 1px solid;width:40%">
                {{ $footer->honorsreceived }}
            </td>
            <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
            <td style="border-bottom: 1px solid; text-align: center;">{{ $footer->shsgraduationdate }}</td>
        </tr>
    </table>
    <table style="font-size: 10px; width: 100%;">
        <tr>
            <td style="width: 60%;"><strong>Certified by:</strong></td>
            <td style="width: 40%;"><strong>Place School Seal Here:</strong></td>
        </tr>
        <tr>
            <td style="border-right: 1px solid;">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 60%;">
                            <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                    PANIAMOGAN, GERALD C.
                                @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                                    {{ strtoupper(DB::table('schoolinfo')->first()->schoolrecordsincharge) }}
                                @else
                                    {{ $footer->registrar != null ? $footer->registrar : strtoupper(DB::table('schoolinfo')->first()->authorized) }}
                                @endif
                            </div>
                        </td>
                        <td style="width: 40%; ">
                            <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                                <strong>{{ $footer->datecertified }}</strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width: 90%;">
                                <center>Signature of School Head over Printed Name</center>
                            </div>
                        </td>
                        <td>
                            <div style="width: 90%;">
                                <center>Date</center>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <div style="width: 95%; border: 1px solid;padding: 5px;">
                    <strong>NOTE:</strong>
                    <br>
                    <small>
                        <em>
                            This permanent record or a photocopy of this permanent record that bears the seal of the
                            school and the original signature in ink of the School Head shall be considered valid for
                            all legal purposes. Any erasure or alteration made on this copy should be validated by the
                            School Head.
                            <br>
                            If the student transfers to another school, the originating school should produce one (1)
                            certified true copy of this permanent record for safekeeping. The receiving school shall
                            continue filling up the original form.
                            <br>
                            Upon graduation, the school form which the student graduated should keep the original form
                            and produce one (1) certified true copy for the Division Office.
                        </em>
                    </small>
                </div>
            </td>
            <td></td>
        </tr>
        {{-- <tr>
        <td colspan="2">
            <strong>Copy for: </strong> 
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br>
            <strong>Date Issued (MM/DD/YYYY): <u>{{$footer->datecertified}}</u></strong>
        </td>
    </tr> --}}
    </table>
    <br />
    <table style="font-size: 11px; width: 60%;">
        <tr>
            <td style="width: 20%;">
                <strong>REMARKS:</strong>
            </td>
            <td>
                @if ($footer->copyforupper == null || $footer->copyforupper == '')
                    <span style="font-size: 11px;"><em>(Please indicate the purpose for which this permanent record
                            will be used)</em></span>
                @else
                    <strong style="font-size: 12px;"><u>{{ $footer->copyforupper }}</u></strong>
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size: 11px;">
                {{ $footer->copyforlower }}
            </td>
        </tr>
    </table>
    @if (count($records) > 2)
        <table class="watermark" width="100%" style=" font-size: 10px;">
            <tr>
                <td width="10%">Page {{ count($records) }}</td>
                <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                <td width="10%" style="text-align: right;">SF10-SHS</td>
            </tr>
        </table>
    @else
        @if ($papersize == '8.3in 11.7in' || $papersize == '8.5in 11in')
            <table class="watermark" width="100%" style=" font-size: 10px;">
                <tr>
                    <td width="10%">Page 3</td>
                    <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                    <td width="10%" style="text-align: right;">SF10-SHS</td>
                </tr>
            </table>
        @else
            <table class="watermark" width="100%" style=" font-size: 10px;">
                <tr>
                    <td width="10%">Page {{ count($records) }}</td>
                    <td width="80%">{{ $studinfo->lastname }}, {{ $studinfo->firstname }}</td>
                    <td width="10%" style="text-align: right;">SF10-SHS</td>
                </tr>
            </table>
        @endif
    @endif
</body>

</html>
