<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    @page {
        margin: 20px;
        size: {{ $papersize != null ? $papersize : '8.5in 14in' }};
    }

    /*8.5 14*/

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
</style>
<!--@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
-->
<!--    <div style="line-height: 1.6in;">&nbsp;</div>-->
<!--
@endif-->
{{-- @if (strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308') --}}
@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
    <table style="width: 100%" id="table1">
        <tr>
            <td width="15%" rowspan="5"><sup style="font-size: 8px;">SF10-ES</sup><br />

                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' ||
                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                    <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="school"
                        width="70px">
                @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
                    <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="school"
                        width="70px">
                @else
                    <img src="{{ base_path() }}/public/assets/images/department_of_Education.png" alt="school"
                        width="70px">
                @endif
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="15%" style="text-align:right;" rowspan="5"><img
                    src="{{ base_path() }}/public/assets/images/deped_logo.png" alt="school" width="70px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">Learner's Permanent Academic Record for
                Elementary School </td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-ES)</td>
        </tr>
        <tr style="line-height: 5px;font-size: 11px;">
            <td style="text-align:center;">(Formerly Form 137)</td>
        </tr>
    </table>
@else
    <table style="width: 100%" id="table1">
        <tr>
            <td width="5%" style="text-align: left;"><sup style="font-size: 8px;">SF10-ES</sup></td>
            <td width="12%" rowspan="6" style="text-align: right;">
                <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="school"
                    width="80px">
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="17%" style="text-align:left;" rowspan="6"><img
                    src="{{ base_path() }}/public/assets/images/department_of_Education.png" alt="school"
                    width="80px"></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Region X-Northern Mindanao</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Division of
                {{ DB::table('schoolinfo')->first()->divisiontext }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px; font-weight: bold;">
                {{ DB::table('schoolinfo')->first()->schoolname }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 12px; font-weight: bold;">Learner's Permanent Academic Record for
                Elementary School (SF10-ES)</td>
        </tr>
        <tr style="line-height: 10px;font-size: 11px;">
            <td style="text-align:center; font-weight: bold;" colspan="4">(Formerly Form 137)</td>
        </tr>
    </table>
@endif
{{-- @else
    <table style="width: 100%" id="table1">
        <tr>
            <td width="15%" rowspan="5"><sup style="font-size: 8px;">SF10-ES</sup><br/>
            
            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
            @else
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
            @endif
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="15%" style="text-align:right;" rowspan="5"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">Learner's Permanent Academic Record for Elementary School </td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-ES)</td>
        </tr>
        <tr style="line-height: 5px;font-size: 11px;">
            <td style="text-align:center;">(Formerly Form 137)</td>
        </tr>
    </table>
@endif --}}
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%" id="table2">
    <tr>
        <td colspan="8"
            style="text-align: center; font-size: 13px; font-weight: bold; background-color: #aba9a9; border: 1px solid black;">
            LEARNER'S PERSONAL INFORMATION</td>
    </tr>
    {{-- <tr>
        <td colspan="8">&nbsp;</td>
    </tr> --}}
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{ $studinfo->lastname }}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{ $studinfo->firstname }}</td>
        <td style="width: 15%;">NAME EXTN. (Jr,I,II)</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{ $studinfo->suffix }}</td>
        <td style="width: 10%;">MIDDLE NAME</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{ $studinfo->middlename }}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;" id="table3">
    {{-- <tr>
        <td colspan="6">&nbsp;</td>
    </tr> --}}
    <tr>
        <td style="width: 20%;">Learner Reference Number (LRN):</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{ $studinfo->lrn }}</td>
        <td style="width: 20%; text-align: right;">Birthdate (mm/dd/yyyy):</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{ date('m/d/Y', strtotime($studinfo->dob)) }}</td>
        <td style="width: 10%; text-align: right;">Sex:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{ $studinfo->gender }}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table4">
    <tr>
        <td style="border: 1px solid black; background-color: #bababa">
            ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<div style="width: 100%; border: 1px solid black; padding-top: 4px;">
    <table style="width: 100%; font-size: 11px;" id="table5">
        <tr style="font-style: italic;">
            <td>Credential Presented for Grade 1:</td>
            <td><input type="checkbox" name="check-1"@if ($eligibility->kinderprogreport == 1) checked @endif>Kinder Progress
                Report</td>
            <td><input type="checkbox" name="check-1"@if ($eligibility->eccdchecklist == 1) checked @endif>ECCD Checklist
            </td>
            <td><input type="checkbox" name="check-1"@if ($eligibility->kindergartencert == 1) checked @endif>Kindergarten
                Certificate of Completion</td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px;" id="table6">
        <tr>
            <td style="width: 13%;">Name of School:</td>
            <td style="width: 25%; border-bottom: 1px solid black;">{{ $eligibility->schoolname }}</td>
            <td style="width: 10%;">School ID:</td>
            <td style="border-bottom: 1px solid black;">{{ $eligibility->schoolid }}</td>
            <td style="width: 15%;">Address of School:</td>
            <td style="width: 20%; border-bottom: 1px solid black;">{{ $eligibility->schooladdress }}</td>
        </tr>
    </table>
    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
</div>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 11px;" id="table7">
    <tr>
        <td colspan="5">Other Credential Presented</td>
    </tr>
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
                name="check-1"@if ($eligibility->pept == 1) checked @endif>PEPT Passer
            &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;{{ $eligibility->peptrating }}&nbsp;&nbsp;&nbsp;&nbsp;</u>
        </td>
        <td style="width: 32%;">Date of Examination/Assessment (mm/dd/yyyy):</td>
        <td style="width: 10%; border-bottom: 1px solid black;">
            @if ($eligibility->examdate != null)
                {{ date('m/d/Y', strtotime($eligibility->examdate)) }}
            @endif
        </td>
        <td style="width: 18%;"><input type="checkbox" name="check-1">Others (Pls. Specify):</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{ $eligibility->specifyothers }}</td>
    </tr>
</table>

<table style="width: 100%; font-size: 11px;" id="table8">
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Name and Address of Testing Center:
        </td>
        <td style="border-bottom: 1px solid black;" colspan="2">{{ $eligibility->centername }}</td>
        <td style="width: 5%;">Remark:</td>
        <td style="border-bottom: 1px solid black;">{{ $eligibility->remarks }}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table9">
    <tr>
        <td style="border: 1px solid black; background-color: #bababa">
            SCHOLASTIC RECORD
        </td>
    </tr>
</table>
@php
    $tablescount = 4;
    $tablescount -= count($records);
@endphp
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
@if (count($records) > 0)
    @foreach ($records as $record)
        @php
            $columngrades = $maxgradecount;
            $columngrades00 = $maxgradecount;
            $columngrades01 = $maxgradecount;
            $columngrades10 = $maxgradecount;
            $columngrades11 = $maxgradecount;
        @endphp

        <table style="width: 100%; font-size: 8px; table-layout: fixed;" id="table10">
            <tr>
                <td style="width: 50%; padding: 0px; vertical-align: top;">
                    <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                        <thead>
                            <tr>
                                <td colspan="7">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 10%;">School:</td>
                                            <td style="width: 50%; border-bottom: 1px solid black;">
                                                {{ $record[0]->schoolname }}</td>
                                            <td style="width: 15%;">School ID:</td>
                                            <td style="width: 15%; border-bottom: 1px solid black;">
                                                {{ $record[0]->schoolid }}</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 10%;">District:</td>
                                            <td style="width: 25%; border-bottom: 1px solid black;">
                                                {{ $record[0]->schooldistrict }}</td>
                                            <td style="width: 10%;">Division:</td>
                                            <td style="width: 25%; border-bottom: 1px solid black;">
                                                {{ $record[0]->schooldivision }}</td>
                                            <td style="width: 10%;">Region:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">
                                                {{ $record[0]->schoolregion }}</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 30%;">Classified as Grade:</td>
                                            <td style="width: 10%; border-bottom: 1px solid black;">
                                                @if (strpos(strtolower($record[0]->levelname), 'grade') !== false)
                                                    {{ preg_replace('/\D+/', '', $record[0]->levelname) }}
                                                @else
                                                    {{ $record[0]->levelname }}
                                                @endif
                                            </td>
                                            <td style="width: 10%;">Section:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">
                                                {{ $record[0]->sectionname }}</td>
                                            <td style="width: 10%;">SchoolYear:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">
                                                {{ $record[0]->sydesc }}</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                            <td style="border-bottom: 1px solid black;">{{ $record[0]->teachername }}
                                            </td>
                                            <td style="width: 10%;">Signature:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                        </tr>
                                    </table>
                                    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2" style="width: 35%;">LEARNING AREAS</th>
                                <th colspan="4">Quarterly</th>
                                <th rowspan="2" style="width: 10%;">Final<br />Rating</th>
                                <th rowspan="2" style="width: 15%;">Remarks</th>
                            </tr>
                            <tr>
                                <th style="width: 8%;">1</th>
                                <th style="width: 8%;">2</th>
                                <th style="width: 8%;">3</th>
                                <th style="width: 8%;">4</th>
                            </tr>
                        </thead>
                        @if (count($record[0]->grades) > 0)
                            @foreach ($record[0]->grades as $grade)
                                @if ($record[0]->type == 2)
                                    @if (strtolower($grade->subjtitle) != 'general average')
                                        <tr>
                                            <td>
                                                @if ($grade->inMAPEH == 1 || $grade->inTLE)
                                                    &nbsp;&nbsp;&nbsp;
                                                @endif&nbsp;&nbsp;{{ $grade->subjtitle }}
                                            </td>
                                            <td class="text-center">{{ $grade->quarter1 }}</td>
                                            <td class="text-center">{{ $grade->quarter2 }}</td>
                                            <td class="text-center">{{ $grade->quarter3 }}</td>
                                            <td class="text-center">{{ $grade->quarter4 }}</td>
                                            <td class="text-center">
                                                @if ($grade->finalrating > 0)
                                                    {{ $grade->finalrating }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($grade->finalrating > 0)
                                                    {{ $grade->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td>
                                            @if ($grade->inMAPEH == 1 || $grade->inTLE)
                                                &nbsp;&nbsp;&nbsp;
                                            @endif&nbsp;&nbsp;{{ $grade->subjtitle }}
                                        </td>
                                        <td class="text-center">{{ $grade->quarter1 > 0 ? $grade->quarter1 : '' }}
                                        </td>
                                        <td class="text-center">{{ $grade->quarter2 > 0 ? $grade->quarter2 : '' }}
                                        </td>
                                        <td class="text-center">{{ $grade->quarter3 > 0 ? $grade->quarter3 : '' }}
                                        </td>
                                        <td class="text-center">{{ $grade->quarter4 > 0 ? $grade->quarter4 : '' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($grade->finalrating > 0)
                                                {{ number_format($grade->finalrating) }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($grade->finalrating > 0)
                                                {{ $grade->remarks != null ? $grade->remarks : ($grade->finalrating > 74 ? 'PASSED' : 'FAILED') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @if (count($record[0]->subjaddedforauto) > 0)
                                @foreach ($record[0]->subjaddedforauto as $customsubjgrade)
                                    <tr>
                                        <td>&nbsp;&nbsp;{{ $customsubjgrade->subjdesc }}</td>
                                        <td class="text-center">{{ $customsubjgrade->q1 }}</td>
                                        <td class="text-center">{{ $customsubjgrade->q2 }}</td>
                                        <td class="text-center">{{ $customsubjgrade->q3 }}</td>
                                        <td class="text-center">{{ $customsubjgrade->q4 }}</td>
                                        <td class="text-center">{{ $customsubjgrade->finalrating }}</td>
                                        <td class="text-center">{{ $customsubjgrade->actiontaken }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if ($columngrades > $record[0]->noofgrades)
                                @php
                                    $columngradesdisplay = $columngrades - $record[0]->noofgrades;
                                @endphp
                                @for ($x = 0; $columngradesdisplay > $x; $columngradesdisplay--)
                                    <tr>
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
                            @if ($record[0]->type == 1)
                                @if (strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308')
                                    {{-- fmcma --}}
                                    <tr style="font-weight: bold;">
                                        <td>&nbsp;&nbsp;General Average</td>
                                        <td class="text-center">
                                            @if (collect($record[0]->generalaverage)->count() > 0)
                                                {{ collect($record[0]->generalaverage)->first()->quarter1 }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (collect($record[0]->generalaverage)->count() > 0)
                                                {{ collect($record[0]->generalaverage)->first()->quarter2 }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (collect($record[0]->generalaverage)->count() > 0)
                                                {{ collect($record[0]->generalaverage)->first()->quarter3 }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (collect($record[0]->generalaverage)->count() > 0)
                                                {{ collect($record[0]->generalaverage)->first()->quarter4 }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ collect($record[0]->generalaverage)->first()->finalrating }}</td>
                                        <td class="text-center">
                                            @if (collect($record[0]->generalaverage)->count() > 0)
                                                {{ collect($record[0]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr style="font-weight: bold;">
                                        <td colspan="5" style="text-align:right">&nbsp;&nbsp;General Average</td>
                                        <!--<td class="text-center">{{ number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('quarter1') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) }}</td>-->
                                        <!--<td class="text-center">{{ number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('quarter2') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) }}</td>-->
                                        <!--<td class="text-center">{{ number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('quarter3') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) }}</td>-->
                                        <!--<td class="text-center">{{ number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('quarter4') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) }}</td>-->
                                        {{-- <td class="text-center">{{number_format(collect($record[0]->grades)->where('inMAPEH','0')->sum('finalrating')/collect($record[0]->grades)->where('inMAPEH','0')->count())}}</td> --}}
                                        <td class="text-center">
                                            {{ number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('finalrating') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) > 0? number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('finalrating') /collect($record[0]->grades)->where('inMAPEH', '0')->count()): '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('finalrating') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) == 0? '': (number_format(collect($record[0]->grades)->where('inMAPEH', '0')->sum('finalrating') /collect($record[0]->grades)->where('inMAPEH', '0')->count()) >= 75? 'PASSED': 'FAILED') }}
                                        </td>
                                    </tr>
                                @endif
                            @elseif($record[0]->type == 2)
                                @if (collect($record[0]->grades)->where('subjtitle', 'General Average')->first())
                                    {{-- @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' || strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308') fmcma --}}
                                    {{-- <tr style="font-weight: bold;">
                                                <td colspan="5"  style="text-align: right;">General Average</td>
                                                <td class="text-center">{{collect($record[0]->grades)->where('subjtitle','General Average')->first()->finalrating}}</td>
                                                <td class="text-center">{{collect($record[0]->grades)->where('subjtitle','General Average')->first()->finalrating >= 75? 'PASSED':'FAILED'}}</td>
                                            </tr> --}}
                                    <tr style="font-weight: bold;">
                                        <td colspan="5" style="text-align: right;">&nbsp;&nbsp;General Average</td>
                                        {{-- <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter1'))}}</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter2'))}}</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter3'))}}</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter4'))}}</td> --}}
                                        <td class="text-center">{{ $grade->finalrating }}</td>
                                        <td class="text-center">{{ $grade->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                        </td>
                                    </tr>
                                    {{-- @else
                                            <tr style="font-weight: bold;">
                                                <td>General Average</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->sum('quarter1')/count(collect($record[0]->grades)->where('subjtitle','!=','General Average')))}}</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->sum('quarter2')/count(collect($record[0]->grades)->where('subjtitle','!=','General Average')))}}</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->sum('quarter3')/count(collect($record[0]->grades)->where('subjtitle','!=','General Average')))}}</td>
                                                <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->sum('quarter4')/count(collect($record[0]->grades)->where('subjtitle','!=','General Average')))}}</td>
                                                <td class="text-center">{{$grade->finalrating}}</td>
                                                <td class="text-center">{{$grade->remarks}}</td>
                                            </tr>
                                        @endif --}}
                                @else
                                    <tr style="font-weight: bold;">
                                        <td colspan="5" style="text-align: right;">&nbsp;&nbsp;General Average</td>
                                        {{-- <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter1')) ?? '}}</td>
                                        <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter2')) ?? '}}</td>
                                        <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter3')) ?? '}}</td>
                                        <td class="text-center">{{number_format(collect($record[0]->grades)->where('subjtitle','!=','General Average')->avg('quarter4')) ?? '}}</td> --}}
                                        <td class="text-center">{{ $grade->finalrating }}</td>
                                        <td class="text-center">{{ $grade->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @else
                            @for ($x = 0; $x < $maxgradecount; $x++)
                                <tr>
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
                    <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                        @if (collect($record[0]->remedials)->contains('type', '2'))
                            @foreach (collect($record[0]->remedials)->where('type', '2')->values() as $remedial)
                                @if ($remedial->type == 2)
                                    <tr>
                                        <td style="width: 30%;">Remedial Classes</td>
                                        <td colspan="4">Conducted
                                            from:&nbsp;&nbsp;{{ $remedial->datefrom != null ? date('m/d/Y', strtotime($remedial->datefrom)) : '' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to:&nbsp;&nbsp;{{ $remedial->dateto != null ? date('m/d/Y', strtotime($remedial->dateto)) : '' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td style="width: 30%;">Remedial Classes</td>
                                <td colspan="4">Conducted
                                    from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Learning Ares</th>
                            <th>Final Rating</th>
                            <th>Remedial Class Mark</th>
                            <th>Recomputed Final</th>
                            <th>Remarks</th>
                        </tr>
                        @if (collect($record[0]->remedials)->contains('type', '1'))
                            @foreach (collect($record[0]->remedials)->where('type', '1')->values() as $remedial)
                                @if ($remedial->type == 1)
                                    <tr>
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
                                </tr>
                            @endfor
                        @endif
                    </table>
                </td>
                {{-- <td>&nbsp;
                    </td> --}}
                <td style="width: 50%; padding: 0px; vertical-align: top;">
                    @if (count($record) == 2)
                        <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                            <thead>
                                <tr>
                                    <td colspan="7">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 10%;">School:</td>
                                                <td style="width: 50%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->schoolname }}</td>
                                                <td style="width: 15%;">School ID:</td>
                                                <td style="width: 15%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->schoolid }}</td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 10%;">District:</td>
                                                <td style="width: 25%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->schooldistrict }}</td>
                                                <td style="width: 10%;">Division:</td>
                                                <td style="width: 25%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->schooldivision }}</td>
                                                <td style="width: 10%;">Region:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->schoolregion }}</td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 30%;">Classified as Grade:</td>
                                                <td style="width: 10%; border-bottom: 1px solid black;">
                                                    {{ preg_replace('/\D+/', '', $record[1]->levelname) }}</td>
                                                <td style="width: 10%;">Section:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->sectionname }}</td>
                                                <td style="width: 10%;">SchoolYear:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">
                                                    {{ $record[1]->sydesc }}</td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                                <td style="border-bottom: 1px solid black;">
                                                    {{ $record[1]->teachername }}</td>
                                                <td style="width: 10%;">Signature:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                            </tr>
                                        </table>
                                        <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="width: 35%;">LEARNING AREAS</th>
                                    <th colspan="4">Quarterly</th>
                                    <th rowspan="2" style="width: 10%;">Final<br />Rating</th>
                                    <th rowspan="2" style="width: 15%;">Remarks</th>
                                </tr>
                                <tr>
                                    <th style="width: 8%;">1</th>
                                    <th style="width: 8%;">2</th>
                                    <th style="width: 8%;">3</th>
                                    <th style="width: 8%;">4</th>
                                </tr>
                            </thead>
                            @if (count($record[1]->grades) > 0)
                                @foreach ($record[1]->grades as $grade)
                                    @if ($record[1]->type == 2)
                                        @if (strtolower($grade->subjtitle) != 'general average')
                                            <tr>
                                                <td>
                                                    @if ($grade->inMAPEH == 1 || $grade->inTLE)
                                                        &nbsp;&nbsp;&nbsp;
                                                    @endif&nbsp;&nbsp;{{ $grade->subjtitle }}
                                                </td>
                                                <td class="text-center">{{ $grade->quarter1 }}</td>
                                                <td class="text-center">{{ $grade->quarter2 }}</td>
                                                <td class="text-center">{{ $grade->quarter3 }}</td>
                                                <td class="text-center">{{ $grade->quarter4 }}</td>
                                                <td class="text-center">
                                                    @if ($grade->finalrating > 0)
                                                        {{ $grade->finalrating }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($grade->finalrating > 0)
                                                        {{ $grade->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td>
                                                @if ($grade->inMAPEH == 1 || $grade->inTLE)
                                                    &nbsp;&nbsp;&nbsp;
                                                @endif&nbsp;&nbsp;{{ $grade->subjtitle }}
                                            </td>
                                            <td class="text-center">{{ $grade->quarter1 > 0 ? $grade->quarter1 : '' }}
                                            </td>
                                            <td class="text-center">{{ $grade->quarter2 > 0 ? $grade->quarter2 : '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $grade->quarter3 > 0 ? $grade->quarter3 : '' }}</td>
                                            <td class="text-center">
                                                {{ $grade->quarter4 > 0 ? $grade->quarter4 : '' }}</td>
                                            <td class="text-center">
                                                @if ($grade->finalrating > 0)
                                                    {{ number_format($grade->finalrating) }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($grade->finalrating > 0)
                                                    {{ $grade->remarks != null ? $grade->remarks : ($grade->finalrating > 74 ? 'PASSED' : 'FAILED') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if (count($record[1]->subjaddedforauto) > 0)
                                    @foreach ($record[1]->subjaddedforauto as $customsubjgrade)
                                        <tr>
                                            <td>&nbsp;&nbsp;{{ $customsubjgrade->subjdesc }}</td>
                                            <td class="text-center">{{ $customsubjgrade->q1 }}</td>
                                            <td class="text-center">{{ $customsubjgrade->q2 }}</td>
                                            <td class="text-center">{{ $customsubjgrade->q3 }}</td>
                                            <td class="text-center">{{ $customsubjgrade->q4 }}</td>
                                            <td class="text-center">{{ $customsubjgrade->finalrating }}</td>
                                            <td class="text-center">{{ $customsubjgrade->actiontaken }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($columngrades > $record[1]->noofgrades)
                                    @php
                                        $columngradesdisplay = $columngrades - $record[1]->noofgrades;
                                    @endphp
                                    @for ($x = 0; $columngradesdisplay > $x; $columngradesdisplay--)
                                        <tr>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    @endfor
                                @endif
                                @if ($record[1]->type == 1)
                                    @if (strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308')
                                        {{-- fmcma --}}
                                        <tr style="font-weight: bold;">
                                            <td>&nbsp;&nbsp;General Average</td>
                                            <td class="text-center">
                                                @if (collect($record[1]->generalaverage)->count() > 0)
                                                    {{ collect($record[1]->generalaverage)->first()->quarter1 }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (collect($record[1]->generalaverage)->count() > 0)
                                                    {{ collect($record[1]->generalaverage)->first()->quarter2 }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (collect($record[1]->generalaverage)->count() > 0)
                                                    {{ collect($record[1]->generalaverage)->first()->quarter3 }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (collect($record[1]->generalaverage)->count() > 0)
                                                    {{ collect($record[1]->generalaverage)->first()->quarter4 }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ collect($record[1]->generalaverage)->first()->finalrating }}</td>
                                            <td class="text-center">
                                                @if (collect($record[1]->generalaverage)->count() > 0)
                                                    {{ collect($record[1]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr style="font-weight: bold;">
                                            <td style="text-align:right;" colspan="5">&nbsp;&nbsp;General Average
                                            </td>
                                            <!--<td class="text-center">{{ number_format(collect($record[1]->grades)->sum('quarter1') / count($record[1]->grades)) }}</td>-->
                                            <!--<td class="text-center">{{ number_format(collect($record[1]->grades)->sum('quarter2') / count($record[1]->grades)) }}</td>-->
                                            <!--<td class="text-center">{{ number_format(collect($record[1]->grades)->sum('quarter3') / count($record[1]->grades)) }}</td>-->
                                            <!--<td class="text-center">{{ number_format(collect($record[1]->grades)->sum('quarter4') / count($record[1]->grades)) }}</td>-->
                                            <td class="text-center">
                                                {{ number_format(collect($record[1]->grades)->sum('finalrating') / count($record[1]->grades)) > 0 ? number_format(collect($record[1]->grades)->sum('finalrating') / count($record[1]->grades)) : '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format(collect($record[1]->grades)->where('inMAPEH', '0')->sum('finalrating') /collect($record[1]->grades)->where('inMAPEH', '0')->count()) == 0? '': (number_format(collect($record[1]->grades)->where('inMAPEH', '0')->sum('finalrating') /collect($record[1]->grades)->where('inMAPEH', '0')->count()) >= 75? 'PASSED': 'FAILED') }}
                                            </td>
                                        </tr>
                                    @endif
                                @elseif($record[1]->type == 2)
                                    @php
                                        $generalaverage = $record[1]->generalaverage;
                                    @endphp
                                    @if (collect($generalaverage)->count() > 0)
                                        <tr style="font-weight: bold;">
                                            <td colspan="5" style="text-align: right;">&nbsp;&nbsp;General Average
                                            </td>
                                            <td class="text-center">
                                                {{ collect($generalaverage)->first()->finalrating }}</td>
                                            <td class="text-center">
                                                {{ collect($generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED' }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr style="font-weight: bold;">
                                            <td colspan="5" style="text-align: right;">&nbsp;&nbsp;General Average
                                            </td>
                                            <td class="text-center"> </td>
                                            <td class="text-center"> </td>
                                        </tr>
                                    @endif
                                @endif
                            @else
                                @for ($x = 0; $x < $maxgradecount; $x++)
                                    <tr>
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
                        <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                        <table style="width: 100%; border: 2px solid black;" border="1">
                            @if (collect($record[1]->remedials)->contains('type', '2'))
                                @foreach ($record[1]->remedials as $remedial)
                                    @if ($remedial->type == 2)
                                        <tr>
                                            <td style="width: 30%;">Remedial Classes</td>
                                            <td colspan="4">Conducted
                                                from:&nbsp;&nbsp;{{ $remedial->datefrom != null ? date('m/d/Y', strtotime($remedial->datefrom)) : '' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to:&nbsp;&nbsp;{{ $remedial->dateto != null ? date('m/d/Y', strtotime($remedial->dateto)) : '' }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td style="width: 30%;">Remedial Classes</td>
                                    <td colspan="4">Conducted
                                        from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Learning Ares</th>
                                <th>Final Rating</th>
                                <th>Remedial Class Mark</th>
                                <th>Recomputed Final</th>
                                <th>Remarks</th>
                            </tr>
                            @if (collect($record[1]->remedials)->contains('type', '1'))
                                @foreach ($record[1]->remedials as $remedial)
                                    @if ($remedial->type == 1)
                                        <tr>
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
                                    </tr>
                                @endfor
                            @endif
                        </table>
                    @else
                        <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                            <thead>
                                <tr>
                                    <td colspan="7">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 10%;">School:</td>
                                                <td style="width: 50%; border-bottom: 1px solid black;">&nbsp;</td>
                                                <td style="width: 15%;">School ID:</td>
                                                <td style="width: 15%; border-bottom: 1px solid black;">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 10%;">District:</td>
                                                <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;</td>
                                                <td style="width: 10%;">Division:</td>
                                                <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;</td>
                                                <td style="width: 10%;">Region:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 30%;">Classified as Grade:</td>
                                                <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                                <td style="width: 10%;">Section:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                                <td style="width: 10%;">SchoolYear:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                                <td style="border-bottom: 1px solid black;">&nbsp;</td>
                                                <td style="width: 10%;">Signature:</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                            </tr>
                                        </table>
                                        <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="width: 35%;">LEARNING AREAS</th>
                                    <th colspan="4">Quarterly</th>
                                    <th rowspan="2" style="width: 10%;">Final<br />Rating</th>
                                    <th rowspan="2" style="width: 15%;">Remarks</th>
                                </tr>
                                <tr>
                                    <th style="width: 8%;">1</th>
                                    <th style="width: 8%;">2</th>
                                    <th style="width: 8%;">3</th>
                                    <th style="width: 8%;">4</th>
                                </tr>
                            </thead>
                            @for ($x = 0; $x < $maxgradecount; $x++)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                            <tr style="font-weight: bold;">
                                <td>&nbsp;&nbsp;General Average</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td></td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                        <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                            <tr>
                                <td style="width: 30%;">Remedial Classes</td>
                                <td colspan="4">Conducted
                                    from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to
                                </td>
                            </tr>
                            <tr>
                                <th>Learning Ares</th>
                                <th>Final Rating</th>
                                <th>Remedial Class Mark</th>
                                <th>Recomputed Final</th>
                                <th>Remarks</th>
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
                    @endif
                </td>
            </tr>
        </table>
        <div style="width: 100%; line-height: 4px;">&nbsp;</div>
    @endforeach
@endif
@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
    @for ($x = 0; $x < $tablescount; $x++)
        <table style="width: 100%; font-size: 8px; table-layout: fixed;" id="table10">
            <tr>
                <td style="width: 50%; padding: 0px; vertical-align: top;">

                    <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                        <thead>
                            <tr>
                                <td colspan="7">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 10%;">School:</td>
                                            <td style="width: 50%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 15%;">School ID:</td>
                                            <td style="width: 15%; border-bottom: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 10%;">District:</td>
                                            <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Division:</td>
                                            <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Region:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 30%;">Classified as Grade:</td>
                                            <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Section:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">SchoolYear:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                            <td style="border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Signature:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                        </tr>
                                    </table>
                                    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2" style="width: 40%;">LEARNING AREAS</th>
                                <th colspan="4">Quarterly</th>
                                <th rowspan="2" style="width: 10%;">Final<br />Rating</th>
                                <th rowspan="2" style="width: 10%;">Remarks</th>
                            </tr>
                            <tr>
                                <th style="width: 8%;">1</th>
                                <th style="width: 8%;">2</th>
                                <th style="width: 8%;">3</th>
                                <th style="width: 8%;">4</th>
                            </tr>
                        </thead>
                        @for ($a = 0; $a < 15; $a++)
                            <tr>
                                <td>&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                        @endfor
                        <tr style="font-weight: bold;">
                            <td>General Average</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td></td>
                        </tr>
                    </table>
                    <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                        <tr>
                            <td style="width: 30%;">Remedial Classes</td>
                            <td colspan="4">Conducted
                                from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to
                            </td>
                        </tr>
                        <tr>
                            <th>Learning Ares</th>
                            <th>Final Rating</th>
                            <th>Remedial Class Mark</th>
                            <th>Recomputed Final</th>
                            <th>Remarks</th>
                        </tr>
                        @for ($b = 0; $b < 2; $b++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </table>
                </td>
                <td style="width: 50%; padding: 0px; vertical-align: top;">

                    <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                        <thead>
                            <tr>
                                <td colspan="7">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 10%;">School:</td>
                                            <td style="width: 50%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 15%;">School ID:</td>
                                            <td style="width: 15%; border-bottom: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 10%;">District:</td>
                                            <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Division:</td>
                                            <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Region:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 30%;">Classified as Grade:</td>
                                            <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Section:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">SchoolYear:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                            <td style="border-bottom: 1px solid black;">&nbsp;</td>
                                            <td style="width: 10%;">Signature:</td>
                                            <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                        </tr>
                                    </table>
                                    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2" style="width: 40%;">LEARNING AREAS</th>
                                <th colspan="4">Quarterly</th>
                                <th rowspan="2" style="width: 10%;">Final<br />Rating</th>
                                <th rowspan="2" style="width: 10%;">Remarks</th>
                            </tr>
                            <tr>
                                <th style="width: 8%;">1</th>
                                <th style="width: 8%;">2</th>
                                <th style="width: 8%;">3</th>
                                <th style="width: 8%;">4</th>
                            </tr>
                        </thead>
                        @for ($c = 0; $c < 15; $c++)
                            <tr>
                                <td>&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                        @endfor
                        <tr style="font-weight: bold;">
                            <td>General Average</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td></td>
                        </tr>
                    </table>
                    <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed; border: 2px solid black;" border="1">
                        <tr>
                            <td style="width: 30%;">Remedial Classes</td>
                            <td colspan="4">Conducted
                                from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to
                            </td>
                        </tr>
                        <tr>
                            <th>Learning Ares</th>
                            <th>Final Rating</th>
                            <th>Remedial Class Mark</th>
                            <th>Recomputed Final</th>
                            <th>Remarks</th>
                        </tr>
                        @for ($d = 0; $d < 2; $d++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </table>
                </td>
            </tr>
        </table>
    @endfor
@endif
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
    <div style="width: 100%;page-break-inside: avoid;">
        <div style="width: 100%; font-size: 11px;">For Transfer Out /Elementary School Completer Only</div>
        <table style="width: 100%; font-size: 11px; border: 1px solid black;">
            <tr>
                <td colspan="10"
                    style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;"
                    class="text-center">
                    CERTIFICATION
                </td>
            </tr>
            <tr>
                <td style="width: 3%;"></td>
                <td colspan="8" style="text-align: justify;">
                    I CERTIFY that this is a true record of <u>{{ $studinfo->lastname }}, {{ $studinfo->firstname }}
                        {{ $studinfo->suffix }}
                        {{ isset($studinfo->middlename[0]) ? $studinfo->middlename[0] . '. ' : '' }}</u> with LRN
                    <u>{{ $studinfo->lrn }}</u> and that he/she is eligible for admission to Grade
                    <u>&nbsp;&nbsp;&nbsp;{{ $footer->admissiontograde }}&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                </td>
                <td style="width: 3%;"></td>
            </tr>
            <tr>
                <td style="width: 3%;"></td>
                <td style="width: 10%;">Name of School:</td>
                <td style="width: 31%; border-bottom: 1px solid black;" colspan="3">{{ $schoolinfo->schoolname }}
                </td>
                <td style="width: 7%;">School ID:</td>
                <td style="width: 6%; border-bottom: 1px solid black;">{{ $schoolinfo->schoolid }}</td>
                <td style="width: 16%;">Last School Year Attended:</td>
                <td style="width: 9%; border-bottom: 1px solid black;">{{ $footer->lastsy }}</td>
                <td style="width: 3%;"></td>
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
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Copy for:;</td>
                <td colspan="8">{{ $footer->purpose }}</td>
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
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{ date('M d, Y') }}</td>
                <td>&nbsp;</td>
                <td colspan="4" style="border-bottom: 1px solid black; text-align: center; font-weight: bold;">
                    GERALD C. PANIAMOGAN, LPT, MBA</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-center">Date</td>
                <td>&nbsp;</td>
                <td colspan="4" class="text-center" style="font-size: 10px;">School Registrar</td>
                <!--<td>&nbsp;</td>-->
                <td colspan="3"style="text-align: center;">(Affix School Seal here)</td>
            </tr>
            <!--<tr>-->
            <!--    <td colspan="2"></td>-->
            <!--    <td class="text-center">Date</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
-->
            <!--    <td colspan="3" class="text-center" style="font-size: 10px;">Name of School Registrar over Printed Name</td>-->
        <!--    @else-->
            <!--    <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School Head over Printed Name</td>-->
            <!--
@endif-->
            <!--    <td colspan="2"style="text-align: right;">(Affix School Seal here)</td>-->
            <!--    <td></td>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--    <td>&nbsp;</td>-->
            <!--</tr>-->
        </table>
    </div>
@else
    <div style="width: 100%;page-break-inside: avoid;">
        <div style="width: 100%; font-size: 11px;">For Transfer Out /Elementary School Completer Only</div>

        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
            <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                <tr>
                    <td colspan="7"
                        style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;"
                        class="text-center">
                        CERTIFICATION
                    </td>
                </tr>
                <tr>
                    {{-- <td style="width: 3%;"></td> --}}
                    <td colspan="7" style="text-align: justify; padding-left: 20px;">
                        I CERTIFY that this is a true record of <u>{{ $studinfo->lastname }},
                            {{ $studinfo->firstname }} {{ $studinfo->suffix }}
                            {{ isset($studinfo->middlename[0]) ? $studinfo->middlename[0] . '. ' : '' }}</u> with LRN
                        <u>{{ $studinfo->lrn }}</u> and that he/she is eligible for admission to Grade
                        <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                    </td>
                    {{-- <td style="width: 3%;"></td> --}}
                </tr>
                <tr>
                    <td style="width: 3%;"></td>
                    <td colspan="6">Name of School: <u>{{ DB::table('schoolinfo')->first()->schoolname }}</u>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; School ID:
                        <u>{{ DB::table('schoolinfo')->first()->schoolid }}</u>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Last School Year Attended:
                        <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border-bottom: 1px solid black; text-align: center; width: 10%;">{{ date('m/d/Y') }}
                    </td>
                    <td></td>
                    <td style="border-bottom: 1px solid black; text-align: center; width: 40%;" colspan="2">
                        {{ strtoupper(DB::table('schoolinfo')->first()->authorized) }}</td>
                    <td></td>
                    <td style="width: 35%;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;">Date
                    </td>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;" colspan="2">Name of Principal/School Head
                        over Printed Name</td>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;">(Affix School Seal Here)</td>
                </tr>
            </table>
            {{-- <span style="font-size: 11px;">
                May add Certification Box if needed
            </span> --}}
            <div style="font-size: 11px; float: right; text-align: right; font-style: italic;">
                SFRT Revised 2017
            </div>
        @else
            @for ($x = 0; $x < 3; $x++)
                <table style="width: 100%; font-size: 11px; border: 1px solid black;">
                    <tr>
                        <td colspan="10"
                            style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;"
                            class="text-center">
                            CERTIFICATION
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 3%;"></td>
                        <td colspan="8" style="text-align: justify;">
                            I CERTIFY that this is a true record of <u>{{ $studinfo->lastname }},
                                {{ $studinfo->firstname }} {{ $studinfo->suffix }}
                                {{ isset($studinfo->middlename[0]) ? $studinfo->middlename[0] . '. ' : '' }}</u> with
                            LRN <u>{{ $studinfo->lrn }}</u> and that he/she is eligible for admission to Grade
                            <u>&nbsp;&nbsp;&nbsp;{{ $footer->admissiontograde }}&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                        </td>
                        <td style="width: 3%;"></td>
                    </tr>
                    <tr>
                        <td style="width: 3%;"></td>
                        <td style="width: 10%;">School Name:</td>
                        <td style="width: 18%; border-bottom: 1px solid black;">{{ $schoolinfo->schoolname }}</td>
                        <td style="width: 7%;">School ID:</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{ $schoolinfo->schoolid }}</td>
                        <td style="width: 6%;">Division:</td>
                        <td style="width: 14%; border-bottom: 1px solid black;">{{ $schoolinfo->division }}</td>
                        <td style="width: 18%;">Last School Year Attended:</td>
                        <td style="width: 9%; border-bottom: 1px solid black;">{{ $footer->lastsy }}</td>
                        <td style="width: 3%;"></td>
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
                    </tr>
                    <tr>
                        @php
                            $principalname = DB::table('academicprogram')
                                ->join('teacher', 'academicprogram.principalid', '=', 'teacher.id')
                                ->first();
                        @endphp
                        <td colspan="2"></td>
                        <td style="border-bottom: 1px solid black; text-align: center;">{{ date('M d, Y') }}</td>
                        <td>&nbsp;</td>
                        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">
                                {{ $footer->recordsincharge }}</td>
                        @else
                            {{-- <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">
                                {{ strtoupper(DB::table('schoolinfo')->first()->authorized) }}</td> --}}
                            <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">
                                {{ $principalname->firstname ?? '' }}
                                {{ isset($principalname->middlename) ? $principalname->middlename[0] . '.' : '' }}
                                {{ $principalname->lastname ?? '' }}</td>
                        @endif
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-center">Date</td>
                        <td>&nbsp;</td>
                        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            <td colspan="3" class="text-center" style="font-size: 10px;">Name of School Registrar
                                over Printed Name</td>
                        @else
                            <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School
                                Head over Printed Name</td>
                        @endif
                        <td colspan="2"style="text-align: right;">(Affix School Seal here)</td>
                        <td></td>
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
                    </tr>
                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                        <tr style="font-weight: bold; font-size: 12px !important;">
                            <td>&nbsp;</td>
                            <td>Copy for:</td>
                            <td colspan="8">{{ $footer->purpose }}</td>
                        </tr>
                    @endif
                </table>
                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                    @if ($x == 3)
                    @else
                        <div style="width: 100%; line-height: 10px;">&nbsp;</div>
                    @endif
                @else
                    @php
                        break;
                    @endphp
                @endif
            @endfor
            <span style="font-size: 11px;">
                May add Certification Box if needed
            </span>
            <span style="font-size: 11px; float: right; text-align: right; font-style: italic;">
                SFRT Revised 2017
            </span>
    </div>
@endif
@endif
