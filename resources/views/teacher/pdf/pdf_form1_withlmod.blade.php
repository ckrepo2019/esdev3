<style>
    * {

        font-family: Arial, Helvetica, sans-serif;
    }

    @page {
        margin: 25px 20px;
        size: 14in 8.5in;
    }

    table {
        border-collapse: collapse;
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
        -moz-transform: rotate(-90.0deg);
        /* FF3.5+ */
        -o-transform: rotate(-90.0deg);
        /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);
        /* Saf3.1+, Chrome */
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);
        /* IE6,IE7 */
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
        /* IE8 */
        margin-left: -10em;
        margin-right: -10em;
    }

    .header th {
        padding-top: 2px;
        padding-bottom: 2px;
    }

    #table-schoolinfo td {
        padding: 2px 0;
    }
</style>
@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
    <style>
        .table-students tbody {
            text-transform: uppercase;
        }
    </style>
@endif
@php

    $signatories = DB::table('signatory')
        ->where('form', 'form1')
        ->where('syid', $forms[0]->syid)
        ->where('deleted', '0')
        ->whereIn('acadprogid', [$forms[0]->acadprogid, 0])
        ->get();

    $signatory_name = '';
    if (count($signatories) == 0) {
        $signatory_name = DB::table('schoolinfo')->first()->authorized;
    } else {
        $signatory_name = $signatories[0]->name;
    }

    $signatoriesv2 = DB::table('signatory')
        ->where('form', 'form1')
        ->where('syid', $forms[0]->syid)
        ->where('deleted', '0')
        ->where('acadprogid', $forms[0]->acadprogid)
        ->get();

    if (count($signatoriesv2) == 0) {
        $signatoriesv2 = DB::table('signatory')
            ->where('form', 'form1')
            ->where('syid', $forms[0]->syid)
            ->where('deleted', '0')
            ->where('acadprogid', 0)
            ->get();

        if (count($signatoriesv2) > 0) {
            if (
                collect($signatoriesv2)
                    ->where('levelid', $forms[0]->levelid)
                    ->count() == 0
            ) {
                $signatoriesv2 = collect($signatoriesv2)->where('levelid', 0)->values();
            } else {
                $signatoriesv2 = collect($signatoriesv2)
                    ->where('levelid', $forms[0]->levelid)
                    ->values();
            }
        }
    } else {
        if (
            collect($signatoriesv2)
                ->where('levelid', $forms[0]->levelid)
                ->count() == 0
        ) {
            $signatoriesv2 = collect($signatoriesv2)->where('levelid', 0)->values();
        } else {
            $signatoriesv2 = collect($signatoriesv2)
                ->where('levelid', $forms[0]->levelid)
                ->values();
        }
    }

    $first = collect($signatoriesv2)->first();

    if (count($signatoriesv2) > 0) {
        foreach ($signatoriesv2 as $signatory) {
            $signatory->display = 0;
        }
    }
    $odd = [];
    $even = [];
    foreach (collect($signatoriesv2)->toArray() as $k => $v) {
        if ($k > 0) {
            if ($k % 2 == 0) {
                $even[] = $v;
            } else {
                $odd[] = $v;
            }
        }
    }

    $schoolinfo = DB::table('schoolinfo')->first();
    function capitalize($word)
    {
        return \App\Http\Controllers\SchoolFormsController::capitalize_word($word);
    }
@endphp
<div style="page-break-inside: avoid;">
    @if ($forms[0]->acadprogid == 5)
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <tr>
                <td rowspan="2" style="width: 6%; vertical-align: top;">
                    <img src='{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}' alt='school'
                        width='75px'>
                </td>
                <th style="font-size: 25px;">School Form 1 School Register for Senior High School (SF1-SHS)
                </th>
                <td rowspan="2" style="text-align: right; width: 6%; vertical-align: top;">
                    <img style="padding:0px; margin:0px;"
                        src="{{ base_path() }}/public/assets/images/department_of_Education.png" alt="school"
                        width="75px">
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <div style="line-height: 15px;">&nbsp;</div>
                    <table style="width: 100%;" id="table-schoolinfo">
                        <tr style="font-size: 12px;">
                            <td style="width: 8%; text-align: right;">School Name&nbsp;&nbsp;</td>
                            <td style="width: 26%; border: 1px solid black; text-align: center;">
                                {{ $forms[0]->schoolinfo->schoolname }}</td>
                            <td style="width: 8%; text-align: right;">School ID&nbsp;&nbsp;</td>
                            <td colspan="2" style="width: 8%;border: 1px solid black; text-align: center;">
                                {{ $forms[0]->schoolinfo->schoolid }}</td>
                            <td style="width: 5%; text-align: right;">District&nbsp;&nbsp;</td>
                            <td colspan="2" style="border: 1px solid black; text-align: center;">
                                @if (isset($schoolinfo->districttext))
                                    {{ $schoolinfo->districttext }}@else{{ $forms[0]->schoolinfo->district }}
                                @endif
                            </td>
                            <td style="width: 6%; text-align: right;">Division&nbsp;&nbsp;</td>
                            <td colspan="2" style="border: 1px solid black; text-align: center;">
                                @if (isset($schoolinfo->divisiontext))
                                    {{ $schoolinfo->divisiontext }}@else{{ $forms[0]->schoolinfo->citymunDesc }}
                                @endif
                            </td>
                            <td style="width: 8%; text-align: right;">Region&nbsp;&nbsp;</td>
                            <td style="border: 1px solid black; text-align: center;">
                                @if (isset($schoolinfo->regiontext))
                                    {{ $schoolinfo->regiontext }}@else{{ $forms[0]->schoolinfo->regDesc }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="13" style="line-height: 1px;">&nbsp;</td>
                        </tr>
                        <tr style="font-size: 12px;">
                            <td style="text-align: right;">Semester&nbsp;&nbsp;</td>
                            <td style="border: 1px solid black; text-align: center;">{{ $forms[0]->semester }}</td>
                            <td style="text-align: right;">School Year&nbsp;&nbsp;</td>
                            <td colspan="2" style="border: 1px solid black; text-align: center;">
                                {{ $forms[0]->schoolyear }}</td>
                            <td colspan="2" style="text-align: right;">Grade Level&nbsp;&nbsp;</td>
                            <td style="border: 1px solid black; text-align: center;">{{ $forms[0]->gradelevel }}</td>
                            <td colspan="2" style="text-align: right;">Track and Strand&nbsp;&nbsp;</td>
                            <td colspan="3" style="border: 1px solid black; text-align: center;">
                                {{ $forms[0]->trackname }} - {{ $forms[0]->strandcode }}</td>
                        </tr>
                        <tr>
                            <td colspan="13" style="line-height: 2px;">&nbsp;</td>
                        </tr>
                        <tr style="font-size: 12px;">
                            <td style="text-align: right;">Section&nbsp;&nbsp;</td>
                            <td style="border: 1px solid black; text-align: center;">{{ $forms[0]->section }}</td>
                            <td colspan="2" style="text-align: right;">Course (for TVL only)&nbsp;&nbsp;</td>
                            <td colspan="8" style="border: 1px solid black; text-align: center;">
                                {{ $forms[0]->tvlcourse }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @else
        <table style="width: 100%; ">
            <tr>
                <td rowspan="3" style="padding-left: 10%;">
                    <img src='{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}'
                        alt='school' width='75px'>
                </td>
                <th style="font-size: 25px;" colspan="8">School Form (SF 1) School Register</th>
                <td rowspan="3" style="text-align: right; padding-right: 10%;">
                    <img style="padding:0px; margin:0px;"
                        src="{{ base_path() }}/public/assets/images/department_of_Education.png" alt="school"
                        width="75px">
                </td>
            </tr>
            <tr>
                <th style="font-size: 12px; vertical-align: top;" colspan="8"><sup>This replaced Form 1, Master List
                        & STS Form 2-Family Background and Profile</sup></th>
            </tr>
            <tr>
                <th colspan="8" style="line-height: 5px;">&nbsp;</th>
            </tr>
            <tr style="font-size: 11px;">
                <th style="text-align: right;">School ID&nbsp;</th>
                <th style="border: 1px solid black;">{{ $forms[0]->schoolinfo->schoolid }}</th>
                <th style="text-align: right;">Region&nbsp;</th>
                <th style="border: 1px solid black;">
                    @if (isset($schoolinfo->regiontext))
                        {{ $schoolinfo->regiontext }}@else{{ $forms[0]->schoolinfo->regDesc }}
                    @endif
                </th>
                <th style="text-align: right;">Division&nbsp;</th>
                <th style="border: 1px solid black;" colspan="3">
                    @if (isset($schoolinfo->divisiontext))
                        {{ $schoolinfo->divisiontext }}@else{{ $forms[0]->schoolinfo->citymunDesc }}
                    @endif
                </th>
                @if ($forms[0]->acadprogid == 4)
                    <th></th>
                    <th></th>
                @else
                    <th style="text-align: right;">District&nbsp;</th>
                    <th style="border: 1px solid black;">
                        @if (isset($schoolinfo->districttext))
                            {{ $schoolinfo->districttext }}@else{{ $forms[0]->schoolinfo->district }}
                        @endif
                    </th>
                @endif
            </tr>
            <tr>
                <th colspan="10" style="line-height: 5px;">&nbsp;</th>
            </tr>
            <tr style="font-size: 11px;">
                <th style="text-align: right;">School Name&nbsp;</th>
                <th style="border: 1px solid black;" colspan="3">{{ $forms[0]->schoolinfo->schoolname }}</th>
                <th style="text-align: right;">School Year&nbsp;</th>
                <th style="border: 1px solid black;">{{ $forms[0]->schoolyear }}</th>
                <th style="text-align: right;">Grade Level&nbsp;</th>
                <th style="border: 1px solid black;">{{ $forms[0]->gradelevel }}</th>
                <th style="text-align: right;">Section&nbsp;</th>
                <th style="border: 1px solid black;">{{ $forms[0]->section }}</th>
            </tr>
        </table>
    @endif
    {{-- <table style="width: 100%;" class="header">
    <thead>
        <tr style="font-size: 11px;">
            <th style="width: 20%; text-align: right;">School ID</th>
            <th style="width: 13%; border: 1px solid black;">{{$forms[0]->schoolinfo->schoolid}}</th>
            <th style="width: 5%; text-align: right;">Region</th>
            <th style="width: 13%; border: 1px solid black;">@if (isset($schoolinfo->regiontext)){{$schoolinfo->regiontext}}@else{{$forms[0]->schoolinfo->regDesc}}@endif</th>
            <th style="width: 8%; text-align: right;">Division</th>
            <th style="width: 21%; border: 1px solid black;" colspan="3">@if (isset($schoolinfo->divisiontext)){{$schoolinfo->divisiontext}}@else{{$forms[0]->schoolinfo->citymunDesc}}@endif</th>
            <th style="width: 10%; text-align: right;">District</th>
            <th style="width: 20%; border: 1px solid black;">@if (isset($schoolinfo->districttext)){{$schoolinfo->districttext}}@else{{$forms[0]->schoolinfo->district}}@endif</th>
        </tr>
    </thead>
    <tr style="font-size: 11px;">
        <th style="text-align: right;">School Name</th>
        <th colspan="3" style="border: 1px solid black;">{{$forms[0]->schoolinfo->schoolname}}</th>
        <th style="text-align: right;">School Year</th>
        <th style="border: 1px solid black;">{{$forms[0]->schoolyear}}</th>
        <th style="text-align: right;">Grade Level</th>
        <th style="border: 1px solid black;">{{$forms[0]->gradelevel}}</th>
        <th style="text-align: right;">Section</th>
        <th colspan="2" style="border: 1px solid black;">{{$forms[0]->section}}</th>
    </tr>
</table> --}}
    @php
        $countstudentmale = 0;
        $countstudentfemale = 0;
        $eosymale = 0;
        $eosyfemale = 0;
    @endphp
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; page-break-inside: always;"
        border="1" class="table-students">
        <thead style="font-size: 9px;">
            <tr>
                <th rowspan="2" style="width: 2%;">

                </th>
                <th rowspan="2" style="width: 6%;">
                    LRN
                </th>
                <th rowspan="2" style="width: 11%;">
                    NAME<br />
                    <span style="font-size: 7px;">(Last Name, First Name, Middle Name)</span>
                </th>
                <th rowspan="2" style="width: 2%;">
                    Sex
                    <br>
                    <span style="font-size: 7px;">(M/F)</span>
                </th>
                <th rowspan="2" style="width: 5%;">
                    BIRTH DATE
                    <span style="font-size: 7px;">(mm/dd/yyyy)</span>
                </th>
                <th rowspan="2" style="width: 3%;">
                    AGE as<br>
                    of 1st<br>
                    Friday
                    <br>
                    June
                </th>
                <th rowspan="2" style="width: 5%;">
                    MOTHER
                    TONGUE
                </th>
                <th rowspan="2" style="width: 5%;">
                    IP
                    (Ethnic Group)
                </th>
                <th rowspan="2" style="width: 5%;">
                    RELIGION
                </th>
                <th colspan="4">
                    ADDRESS
                </th>
                <th colspan="2" style="width: 11%;">
                    PARENTS
                </th>
                <th colspan="2" style="width: 10%;">
                    GUARDIAN
                    (If not Parent)
                </th>
                <th rowspan="2" style="width: 6%;">
                    Contact Number of Parent or Guardian
                </th>
                <th rowspan="2" style="width: 5%;">
                    Learning Modality
                </th>
                <th>
                    REMARKS
                </th>
            </tr>
            <tr>
                <th>
                    House #/<br />Street/<br />Sitio/<br />Purok
                </th>
                <th>
                    Barangay
                </th>
                <th>
                    Municipality/<br />City
                </th>
                <th>
                    Province
                </th>
                <th style="width: 5.5%;">
                    Father's Name (Last Name,
                    First Name, Middle Name)
                </th>
                <th style="width: 5.5%;">
                    Mother's Maiden Name (Last
                    Name, First Name, Middle
                    Name)
                </th>
                <th style="width: 6% ;">
                    Name
                </th>
                <th style="width: 4%;">
                    Relationship
                </th>
                <th>
                    (Please refer to the
                    legend on last page)
                </th>
            </tr>
        </thead>
        <tbody style="font-size: 10px;">
            @foreach ($forms[0]->students as $studentinfo)
                @if (strtolower($studentinfo->gender) == 'male')
                    @php
                        $countstudentmale += 1;
                        if (
                            $studentinfo->studstatus == 1 ||
                            $studentinfo->studstatus == 2 ||
                            $studentinfo->studstatus == 4
                        ) {
                            $eosymale += 1;
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $countstudentmale }}</td>
                        <td>
                            {{ $studentinfo->lrn }}
                        </td>
                        <td style="padding-left: 2px;">{{ capitalize($studentinfo->lastname) }},
                            {{ capitalize($studentinfo->firstname) }} {{ $studentinfo->suffix }}
                            {{ capitalize($studentinfo->middlename) }}</td>
                        <td style="text-align: center;">{{ $studentinfo->gender[0] }}</td>
                        <td style="text-align: center;">{{ $studentinfo->dob }}</td>
                        <td style="text-align: center;">{{ $studentinfo->age }}</td>
                        <td style="text-align: center;">
                            {{-- @if ($forms[0]->levelid == 1 || $forms[0]->levelid == 5 || $forms[0]->levelid == 6)
                                {{ ucwords(strtolower($studentinfo->mtname)) }}
                            @endif --}}
                            @if ($studentinfo->mtname)
                                {{ ucwords(strtolower($studentinfo->mtname)) }}
                            @endif
                        </td>
                        <td style="text-align: center;">{{ ucwords(strtolower($studentinfo->egname)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->religionname)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->street)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->barangay)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->city)) }}</td>
                        <td style=" word-wrap: break-word;">{{ ucwords(strtolower($studentinfo->province)) }}</td>
                        <td style="padding-left: 2px; word-wrap: break-word;">
                            @if ($studentinfo->fathername != ',')
                                {{ ucwords(strtolower($studentinfo->fathername)) }}
                            @endif
                        </td>
                        <td style="padding-left: 2px; word-wrap: break-word;">
                            @if ($studentinfo->mothername != ',')
                                {{ ucwords(strtolower($studentinfo->mothername)) }}
                            @endif
                        </td>
                        <td style="padding-left: 2px; word-wrap: break-word;">
                            @if ($studentinfo->guardianname != ',')
                                {{ ucwords(strtolower($studentinfo->guardianname)) }}
                            @endif
                        </td>
                        <td>{{ ucwords(strtolower($studentinfo->guardianrelation)) }}</td>
                        <td style="text-align: center;">
                            @php
                                $contactnumbermale = null;
                            @endphp
                            @if ($studentinfo->fcontactno != null && $contactnumbermale == null)
                                @php
                                    $contactnumbermale = $studentinfo->fcontactno;
                                @endphp
                            @endif
                            @if ($studentinfo->mcontactno != null && $contactnumbermale == null)
                                @php
                                    $contactnumbermale = $studentinfo->mcontactno;
                                @endphp
                            @endif
                            @if ($studentinfo->gcontactno != null && $contactnumbermale == null)
                                @php
                                    $contactnumbermale = $studentinfo->gcontactno;
                                @endphp
                            @endif
                            {{ $contactnumbermale }}
                        </td>
                        <td>
                            @if (count(DB::table('modeoflearning')->where('id', $studentinfo->mol)->get()) > 0)
                                {{ DB::table('modeoflearning')->where('id', $studentinfo->mol)->first()->description }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($studentinfo->studstatus == 2)
                                {{-- Late enrolled --}}
                                LE Date:
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @elseif($studentinfo->studstatus == 3)
                                {{-- Dropped --}}
                                DRP
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @elseif($studentinfo->studstatus == 4)
                                {{-- Transferred In --}}
                                T/I Date:
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @elseif($studentinfo->studstatus == 5)
                                {{-- Transferred Out --}}
                                T/O Date:
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr style="text-align: center;">
                <td></td>
                <td>{{ $countstudentmale }}</td>
                <td>==TOTAL MALE</td>
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
            @foreach ($forms[0]->students as $studentinfo)
                @if (strtolower($studentinfo->gender) == 'female')
                    @php
                        $countstudentfemale += 1;
                        if (
                            $studentinfo->studstatus == 1 ||
                            $studentinfo->studstatus == 2 ||
                            $studentinfo->studstatus == 4
                        ) {
                            $eosyfemale += 1;
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $countstudentfemale }}</td>
                        <td>
                            {{ $studentinfo->lrn }}
                        </td>
                        <td style="padding-left: 2px;">{{ capitalize($studentinfo->lastname) }},
                            {{ capitalize($studentinfo->firstname) }} {{ $studentinfo->suffix }}
                            {{ capitalize($studentinfo->middlename) }}</td>
                        <td style="text-align: center;">{{ $studentinfo->gender[0] }}</td>
                        <td style="text-align: center;">{{ $studentinfo->dob }}</td>
                        <td style="text-align: center;">{{ $studentinfo->age }}</td>
                        <td style="text-align: center;">
                            {{-- @if ($forms[0]->levelid == 1 || $forms[0]->levelid == 5 || $forms[0]->levelid == 6)
                                {{ ucwords(strtolower($studentinfo->mtname)) }}
                            @endif --}}
                            @if ($studentinfo->mtname)
                                {{ ucwords(strtolower($studentinfo->mtname)) }}
                            @endif
                        </td>
                        <td style="text-align: center;">{{ ucwords(strtolower($studentinfo->egname)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->religionname)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->street)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->barangay)) }}</td>
                        <td style="text-align: center; word-wrap: break-word;">
                            {{ ucwords(strtolower($studentinfo->city)) }}</td>
                        <td style=" word-wrap: break-word;">{{ ucwords(strtolower($studentinfo->province)) }}</td>
                        <td style="padding-left: 2px; word-wrap: break-word;">
                            @if ($studentinfo->fathername != ',')
                                {{ ucwords(strtolower($studentinfo->fathername)) }}
                            @endif
                        </td>
                        <td style="padding-left: 2px; word-wrap: break-word;">
                            @if ($studentinfo->mothername != ',')
                                {{ ucwords(strtolower($studentinfo->mothername)) }}
                            @endif
                        </td>
                        <td style="padding-left: 2px; word-wrap: break-word;">
                            @if ($studentinfo->guardianname != ',')
                                {{ ucwords(strtolower($studentinfo->guardianname)) }}
                            @endif
                        </td>
                        <td>{{ ucwords(strtolower($studentinfo->guardianrelation)) }}</td>
                        <td style="text-align: center;">
                            @php
                                $contactnumberfemale = null;
                            @endphp
                            @if ($studentinfo->fcontactno != null && $contactnumberfemale == null)
                                @php
                                    $contactnumberfemale = $studentinfo->fcontactno;
                                @endphp
                            @endif
                            @if ($studentinfo->mcontactno != null && $contactnumberfemale == null)
                                @php
                                    $contactnumberfemale = $studentinfo->mcontactno;
                                @endphp
                            @endif
                            @if ($studentinfo->gcontactno != null && $contactnumberfemale == null)
                                @php
                                    $contactnumberfemale = $studentinfo->gcontactno;
                                @endphp
                            @endif
                            {{ $contactnumberfemale }}
                        </td>
                        <td>
                            @if (count(DB::table('modeoflearning')->where('id', $studentinfo->mol)->get()) > 0)
                                {{ DB::table('modeoflearning')->where('id', $studentinfo->mol)->first()->description }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($studentinfo->studstatus == 2)
                                {{-- Late enrolled --}}
                                LE Date:
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @elseif($studentinfo->studstatus == 3)
                                {{-- Dropped --}}
                                DRP
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @elseif($studentinfo->studstatus == 4)
                                {{-- Transferred In --}}
                                T/I Date:
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @elseif($studentinfo->studstatus == 5)
                                {{-- Transferred Out --}}
                                T/O Date:
                                {{ $studentinfo->studstatdate != null ? date('m/d/Y', strtotime($studentinfo->studstatdate)) : '' }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr style="text-align: center;">
                <td></td>
                <td>{{ $countstudentfemale }}</td>
                <td>==TOTAL FEMALE</td>
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
            <tr style="text-align: center;">
                <td></td>
                <td>{{ $countstudentfemale + $countstudentmale }}</td>
                <td>==COMBINED</td>
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
        </tbody>
    </table>
</div>
<table style="width: 100%; ">
    <tr style="font-size: 10px;">
        <th>List and Code of Indicators under REMARKS column</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td style="width: 50%; vertical-align: top; padding: 0px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                <tr>
                    <td style="border: 1px solid black;">Indicator</td>
                    <td style="border: 1px solid black; text-align: center; ">Code</td>
                    <td style="border: 1px solid black;">Required Information</td>
                    <td style="border: 1px solid black;">Indicator</td>
                    <td style="border: 1px solid black; text-align: center; ">Code</td>
                    <td style="border: 1px solid black;">Required Information</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Transferred Out</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center; ">
                        T/O</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of Public (P)
                        Private (PR) School & Effectivity Date</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">CCT Recipient</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center; ">
                        CCT</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">CCT Control/reference
                        number & Effectivity Date</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;"></td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;"></td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;"></td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Balik Aral</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center;">B/A
                    </td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of school last
                        attended & Year</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Transferred In</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center;">T/I
                    </td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of Public (P)
                        Private (PR) School & Effectivity Date</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Learner With Disability
                    </td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center; ">
                        LWD</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of school last
                        attended & Year</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-top: none;  ">
                        @if ($forms[0]->acadprogid < 5)
                            Dropped
                        @endif
                    </td>
                    <td style="border: 1px solid black; border-top: none; text-align: center; ">
                        @if ($forms[0]->acadprogid < 5)
                            DRP
                        @endif
                    </td>
                    <td style="border: 1px solid black; border-top: none;  ">
                        @if ($forms[0]->acadprogid < 5)
                            Reason and Effectivity Date
                        @endif
                    </td>
                    <td style="border: 1px solid black; border-top: none;">Accelerated</td>
                    <td style="border: 1px solid black; border-top: none; text-align: center; ">ACL</td>
                    <td style="border: 1px solid black; border-top: none;  ">Specify Level & Effectivity Date</td>
                </tr>
            </table>
        </td>
        <td style="width: 1%;  padding: 0px;"></td>
        @if ($forms[0]->acadprogid == 5)
            <td style="width: 20%; vertical-align: top; padding: 0px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 9px;" border="1">
                    <tr>
                        <th style="padding: 2px;">REGISTERED</th>
                        <th style="padding: 2px;">Beginning of the Semester</th>
                        <th style="padding: 2px;">End of the Semester</th>
                    </tr>
                    <tr>
                        <th style="padding: 5px;">MALE</th>
                        <th style="padding: 5px;">{{ $forms[0]->bosy_male }}</th>
                        <th style="padding: 5px;">{{ $forms[0]->eosy_male }}</th>
                    </tr>
                    <tr>
                        <th style="padding: 5px;">FEMALE</th>
                        <th style="padding: 5px;">{{ $forms[0]->bosy_female }}</th>
                        <th style="padding: 5px;">{{ $forms[0]->eosy_female }}</th>
                    </tr>
                    <tr>
                        <th style="padding: 5px;">TOTAL</th>
                        <th style="padding: 5px;">{{ $forms[0]->bosy_male + $forms[0]->bosy_female }}</th>
                        <th style="padding: 5px;">{{ $forms[0]->eosy_male + $forms[0]->eosy_female }}</th>
                    </tr>
                </table>
            </td>
        @else
            <td style="width: 14%; vertical-align: top; padding: 0px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 9px;" border="1">
                    <tr>
                        <th style="padding: 2px;">REGISTERED</th>
                        <th style="padding: 2px;">BoSY</th>
                        <th style="padding: 2px;">EoSY</th>
                    </tr>
                    <tr>
                        <th style="padding: 5px;">MALE</th>
                        <th style="padding: 5px;">{{ $forms[0]->bosy_male }}</th>
                        <th style="padding: 5px;">{{ $forms[0]->eosy_male }}</th>
                    </tr>
                    <tr>
                        <th style="padding: 5px;">FEMALE</th>
                        <th style="padding: 5px;">{{ $forms[0]->bosy_female }}</th>
                        <th style="padding: 5px;">{{ $forms[0]->eosy_female }}</th>
                    </tr>
                    <tr>
                        <th style="padding: 5px;">TOTAL</th>
                        <th style="padding: 5px;">{{ $forms[0]->bosy_male + $forms[0]->bosy_female }}</th>
                        <th style="padding: 5px;">{{ $forms[0]->eosy_male + $forms[0]->eosy_female }}</th>
                    </tr>
                </table>
            </td>
        @endif
        <td style="width: 1%;  padding: 0px;"></td>
        @if ($forms[0]->acadprogid == 5)
            <td style="vertical-align: top; padding: 0px;" colspan="3">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 9px;" colspan="3">Prepared by:</td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            style="font-size: 9px; border-bottom: 1px solid black; text-align: center;">
                            <br />
                            {{ $forms[0]->teachername }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-size: 8px; text-align: center; padding-top:5px;">
                            <sup>(Signature of Adviser over Printed Name)</sup>
                        </td>
                    </tr>
                    @if (count($odd) > 0)
                        @foreach ($odd as $eachodd)
                            <tr>
                                <td colspan="3" style="font-size: 9px;">{{ $first->title }}:</td>
                            </tr>
                            <tr>
                                <td colspan="3"
                                    style="font-size: 9px; border-bottom: 1px solid black; text-align: center;">
                                    <br />
                                    {{ $first->name }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="font-size: 8px; text-align: center;">
                                    <sup>{{ $first->description }}</sup>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td style="font-size: 8px; font-weight: bold;">Beginning of the Semester</td>
                        <td></td>
                        <td style="font-size: 8px; font-weight: bold;">End of the Semester</td>
                    </tr>
                </table>
            </td>
        @else
            <td style="width: 14%; vertical-align: top; padding: 0px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 10px;">Prepared by:</td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px; border-bottom: 1px solid black; text-align: center;">
                            <br />
                            {{ $forms[0]->teachername }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px; text-align: center; padding-top:5px;">
                            <sup>(Signature of Adviser over Printed Name)</sup>
                        </td>
                    </tr>
                    {{-- @if (count($odd) > 0)
                        @foreach ($odd as $eachodd)
                            <tr>
                                <td style="font-size: 9px;">{{$first->title}}:</td>
                            </tr>
                            <tr>
                                <td style="font-size: 9px; border-bottom: 1px solid black; text-align: center;">
                                    <br/>
                                    {{$first->name}}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 8px; text-align: center;">
                                    <sup>{{$first->description}}</sup>
                                </td>
                            </tr>
                        @endforeach
                    @endif --}}
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid black; font-size: 7px; font-weight: bold;">
                            BoSY Date:
                            {{ date('F d, Y',strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->sdate)) }}&nbsp;&nbsp;&nbsp;&nbsp;EoSY
                            Date:
                            {{ date('F d, Y',strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->edate)) }}

                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 1%;  padding: 0px;"></td>
            <td style="width: 19% vertical-align: top !important;  padding: 0px;">
                <table style="width: 100%;">
                    {{-- <p> {{ $signatoriesv2 }} </p> --}}
                    @if (count($signatoriesv2) == 0)
                        <tr>
                            <td style="font-size: 10px; ">Certified Correct:</td>
                        </tr>
                        <tr>
                            <td style="font-size: 10px; border-bottom: 1px solid black; text-align: center; ">
                                <br />
                                @if ($signatory_name == '' || $signatory_name == null)
                                    &nbsp;<br />
                                @else
                                    {{ $signatory_name }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 10px; text-align: center; padding-top:5px;">
                                <sup>(Signature of School Head over Printed Name)</sup>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black; font-size: 8px; font-weight: bold;">
                                <br />
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 10px; text-align: center; padding-top:5px;">
                                <sup>Generated thru LIS</sup>
                            </td>
                        </tr>
                    @else
                        @foreach ($signatoriesv2 as $signatory)
                            <tr>
                                <td style="font-size: 10px; vertical-align: top;">
                                    {{ $signatory->title ?? 'Title Not Specified' }}</td>
                            </tr>
                            <tr>
                                <td style="font-size: 10px; border-bottom: 1px solid black; text-align: center;">
                                    <br />
                                    {{ $signatory->name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 10px; text-align: center; padding-top:5px;">
                                    <sup>{{ $signatory->description ?? 'Not Specified' }}</sup>
                                </td>
                            </tr>
                        @endforeach
                        @if (count($signatoriesv2) == 1)
                            <tr>
                                <td style="font-size: 9px; vertical-align: top;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-size: 9px; vertical-align: top; border-bottom: 1px solid black;"></td>
                            </tr>
                            <td style="font-size: 10px; text-align: center; padding-top:5px;">
                                <sup>Generated thru LIS</sup>
                            </td>
                        @endif
                    @endif
                </table>
            </td>
        @endif
    </tr>
</table>
<div style="width: 100%; font-size: 9px;">
    Generated on: {{ date('l, F d, Y') }}
</div>
{{-- <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
    <tr>
        <th colspan="5" style="text-align: center;">List and Code of Indicators under REMARKS column</th>
        <th colspan="4"></th>
        <th>&nbsp;&nbsp;&nbsp;</th>
        <th rowspan="2">Prepared by:</th>
        <th rowspan="6"> &nbsp;&nbsp;&nbsp;</th>
        <th rowspan="2">Certified Correct:</th>
    </tr>
    <tr>
        <td style="border: 1px solid black;">Indicator</td>
        <td style="text-align: center; border: 1px solid black;">Code</td>
        <td style="border: 1px solid black;">Required Information</td>
        <td style="text-align: center; border: 1px solid black;">Code</td>
        <td style="border: 1px solid black;">Required Information</td>
        <td rowspan="5" style="border-top: none; border-bottom: none; width: 1%;"></td>
        <td style="text-align: center; border: 1px solid black;">REGISTERED</td>
        <td style="text-align: center; border: 1px solid black;">BoSY</td>
        <td style="text-align: center; border: 1px solid black;">EoSy</td>
        <td rowspan="5" style="border: none;"></td>
    </tr>
    <tr>
        <td style="border-bottom: none; border: 1px solid black;">Transferred Out</td>
        <td style="border-bottom: none;text-align: center; border: 1px solid black;">T/O</td>
        <td style="border-bottom: none; border: 1px solid black;">Name of Public (P) Private (PR) School & Effectivity Date</td>
        <td style="border-bottom: none;text-align: center; border: 1px solid black;">CCT</td>
        <td style="border-bottom: none; border: 1px solid black;">CCT Control/reference number & Effectivity Date</td>
        <td style="text-align: center; border: 1px solid black;">MALE</td>
        <td style="text-align: center; border: 1px solid black;">{{$countstudentmale}}</td>
        <td style="border: 1px solid black;"></td>
        <td style="border: none; border-bottom: 1px solid black; text-transform: uppercase; text-align: center;">
            {{$forms[0]->preparedby->firstname}} {{$forms[0]->preparedby->middlename[0].'.'}} {{$forms[0]->preparedby->lastname}} {{$forms[0]->preparedby->suffix}}
        </td>
        <td style="border: none; border-bottom: 1px solid black; text-align: center;">
            {{$forms[0]->schoolinfo->authorized}}
        </td>
    </tr>
    <tr>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Transferred IN</td>
        <td style="border-top: none; border-bottom: none; text-align: center; border: 1px solid black;">T/I</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Name of Public (P) Private (PR) School & Effectivity Date</td>
        <td style="border-top: none; border-bottom: none;text-align: center; border: 1px solid black;">B/A</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Name of school last attended & Year</td>
        <td style="text-align: center; border: 1px solid black;">FEMALE</td>
        <td style="text-align: center; border: 1px solid black;">{{$countstudentfemale}}</td>
        <td style="border: 1px solid black;"></td>
        <td style="border: none; font-size: 8px; text-align: center; padding: 0px;">
            <sup>
                <em>
                    (Signature of Adviser over Printed Name)
                </em>
            </sup>
        </td>
        <td style="border: none; font-size: 8px; text-align: center;">
            <sup>
                <em>
                    (Signature of School Head over Printed Name)
                </em>
            </sup>
        </td>
    </tr>
    <tr>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">DROPPED</td>
        <td style="border-top: none; border-bottom: none;text-align: center; border: 1px solid black;">DRP</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Reason and Effectivity Date</td>
        <td style="border-top: none; border-bottom: none;text-align: center; border: 1px solid black;">LWD</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Specify</td>
        <td rowspan="2" style="text-align: center; border: 1px solid black;">TOTAL</td>
        <td rowspan="2" style="text-align: center; border: 1px solid black;">{{$countstudentmale+$countstudentfemale}}</td>
        <td rowspan="2" style="border: 1px solid black;"></td>
        <td rowspan="2" style="border: none; border-bottom: 1px solid;">
            BoSy Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EoSY Date:
        </td>
        <td rowspan="2" style="border: none; border-bottom: 1px solid;">
            BoSy Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EoSY Date:
        </td>
    </tr>
    <tr>
        <td style="border-top: none; border: 1px solid black;">Late Enrollment</td>
        <td style="border-top: none;text-align: center; border: 1px solid black;">LE</td>
        <td style="border-top: none; border: 1px solid black;">Reason (Enrollment beyond 1st Friday of June)</td>
        <td style="border-top: none;text-align: center; border: 1px solid black;">ACL</td>
        <td style="border-top: none; border: 1px solid black;">Specify </td>
    </tr>
</table> --}}
