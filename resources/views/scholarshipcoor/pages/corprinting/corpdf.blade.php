<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>Certificate of Registration</title>
    <style type="text/css">
        @page {
            margin: 0;
        }

        * {
            padding: 0;
            margin: 0;
        }

        @font-face {
            font-family: "source_sans_proregular";
            src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: "source_sans_proregular", Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .page_break {
            page-break-before: always;
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 11px;
        }


        .p-1 {
            padding: .25rem !important;
        }

        table {
            border-collapse: collapse;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .p-0 {
            padding: 0 !important;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .text-right {
            text-align: right !important;
        }

        .mb-1,
        .my-1 {
            margin-bottom: .25rem !important;
        }

        .copy {
            height: 6.3in;
        }
    </style>


</head>

<body style="margin: 0.25in 0.25in 0.25in 0.25in;">
    <div class="copy" style="border-bottom: 1px black dashed">
        {{-- <table width="100%"> 
                        <tr style="">
                        <td width="50%" style="text-align: center; font-size:10px;"><img src="{{base_path()}}/public/{{$schoolInfo->picurl}}" alt="school" width="120px">{{$schoolInfo->schoolname}}</td>
                        <td width="50%" style="text-align: right; font-size:20px;" valign="top"><b>CERTIFICATE OF REGISTRATION</b></td>
                        </tr>
                        <tr >
                              <td style="text-align: center; font-size:9px">{{$schoolInfo->address}}</td>
                              <td style="text-align: center; font-size:11px"></td>
                        </tr>
				</table> --}}
        <table width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%">
                    <img src="{{ base_path() }}/public/{{ $schoolInfo->picurl }}" alt="school" width="60px">
                </td>
                <td style="width: 50%; text-align: center;">
                    <div style="width: 100%; font-weight: bold; font-size: 15px;">{{ $schoolInfo->schoolname }}</div>
                    <div style="width: 100%; font-size: 12px;">{{ $schoolInfo->address }}</div>
                </td>
                <td width="25%"></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%"></td>
                <td style="width: 50%; text-align: center; font-size: 12px;">
                    <b>CERTIFICATE OF REGISTRATION</b>
                </td>
                <td width="25%"></td>
            </tr>
        </table>

        <table width="100%" style="font-size:10px" class="mb-1">
            <tbody>
                <tr>
                    <td width="75%">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                @php
                                    $middle = '';
                                    if (isset($studentInfo->middlename)) {
                                        if ($studentInfo->middlename != '' && $studentInfo->middlename != null) {
                                            $middle = isset($studentInfo->middlename)
                                                ? ' ' . $studentInfo->middlename[0] . '.'
                                                : '';
                                        }
                                    }
                                @endphp
                                <tr style="vertical-align: top;">
                                    <td width="25%">ID no. {{ $studentInfo->sid }}</td>
                                    <td width="35%">NAME:
                                        {{ $studentInfo->lastname . ', ' . $studentInfo->firstname . $middle }}</td>
                                    <td width="30%">CONTACT NO. {{ $studentInfo->contactno }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Course & Year: {{ $studentInfo->courseDesc }}
                                        {{ $studentInfo->levelname }}</td>
                                </tr>
                                <tr>
                                    <td colspan="1">Date of Birth:
                                        {{ \Carbon\Carbon::create($studentInfo->dob)->isoFormat('MM/DD/YYYY') }}</td>
                                    <td colspan="2">Address:
                                        @if ($studentInfo->street != '')
                                            {{ $studentInfo->street . ', ' }}
                                        @endif
                                        @if ($studentInfo->barangay != '')
                                            {{ $studentInfo->barangay . ', ' }}
                                        @endif
                                        @if ($studentInfo->city != '')
                                            {{ $studentInfo->city . ', ' }}
                                        @endif
                                        @if ($studentInfo->province != '')
                                            {{ $studentInfo->province . ', ' }}
                                        @endif

                                </tr>
                            </tbody>
                        </table>

                    </td>
                    <td width="25%">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td with="50%" style="text-align: right; font-size:11px">
                                        {{ \Carbon\Carbon::create($studentInfo->date_enrolled)->isoFormat('DD/MM/YYYY') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-size:11px">{{ $activeSem->semester }} S.Y.:
                                        {{ $activeSy->sydesc }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-size:11px">
                                        Block Code: {{ $studentInfo->sectionDesc }}
                                        {{-- Block Code: 
                                                                  <u><b><span style="font-size:13px">
                                                                              {{$studentInfo->courseabrv}}
                                                                                    @if ($studentInfo->levelid == 17)
                                                                                          1
                                                                                    @elseif( $studentInfo->levelid == 18)
                                                                                          2
                                                                                    @elseif( $studentInfo->levelid == 19)
                                                                                          3
                                                                                    @elseif( $studentInfo->levelid == 20)
                                                                                          4
                                                                                    @endif
                                                                              {{$studentInfo->sectionDesc}}
                                                                        </span>
                                                                  </b></u> --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align:right; font-size:15px">
                                        @if ($studentInfo->studtype == null || $studentInfo->studtype == 'old')
                                            OLD STUDENT
                                        @else
                                            NEW STUDENT
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="table table-bordered mb-0" style="font-size:.5625rem !important">
            @php
                $totalUnits = 0.0;
            @endphp
            <tbody>
                <tr>
                    <th class="p-1 text-left">Code</th>
                    <th class="p-1 text-left">Description</th>
                    <th class="p-1 text-center">Units</th>
                    <th class="p-1 text-center">Time</th>
                    <th class="p-1 text-center">Day</th>
                    <th class="p-1 text-center">Room</th>
                    <th class="p-1 text-center">Instructor</th>
                </tr>
              
                @foreach ($schedules as $item)
                    @php
                        $count = 0;
                    @endphp

                    <tr>
                        <td class="p-1" width="8%">
                            {{ $item[0]->subjCode }}
                        </td>
                        <td class="p-1" width="30%">
                            {{ $item[0]->subjDesc }}
                        </td>
                        <td class="p-1  text-center" width="5%">
                            {{ number_format($item[0]->lecunits + $item[0]->labunits, 1) }}
                        </td>
                        <td class="p-1  text-center" width="20%">
                            @foreach ($item as $timeitem)
                                @php
                                    // $schedother = $timeitem->schedotherclass == 'Laboratory' ? 'Lab:. ' : 'Lec:. ';
                                    $schedother = substr($timeitem->schedotherclass, 0, 3) . ':';
                                @endphp
                                @if ($timeitem->stime != null && $timeitem->etime != null)
                                    <p class="mb-0">{{ $schedother }}
                                        {{ \Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A') }} -
                                        {{ \Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A') }}</p>
                                @else
                                    <p class="mb-0"></p>
                                @endif
                            @endforeach
                        </td>
                        <td class="p-1  text-center" width="8%">
                            @foreach ($item as $descriptiontem)
                                @if ($descriptiontem->description != null)
                                    <p class="mb-0">{{ $descriptiontem->description }}</p>
                                @else
                                    <p class="mb-0"></p>
                                @endif
                            @endforeach
                        </td>
                        <td class="p-1  text-center" width="14%">
                            @foreach ($item as $roomitem)
                                @if ($roomitem->roomname != null)
                                    <p class="mb-0">{{ $roomitem->roomname }}</p>
                                @else
                                    <p class="mb-0"></p>
                                @endif
                            @endforeach

                        </td>
                        <td class="p-1  text-center" width="15%" style="font-size:.6rem !important">
                            {{ $item[0]->teacher }}
                        </td>
                        @php
                            $totalUnits += number_format($item[0]->lecunits + $item[0]->labunits, 1);
                        @endphp
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table" width="100%" style="font-size:.5625rem !important; ">
            <tr>
                <td width="38%" class="text-right p-1">TOTAL UNITS</td>
                <td width="5%" class="text-center p-1"><b>{{ number_format($totalUnits, 1) }}<b></td>
                <td width="57%" class=" p-1"></td>
            </tr>
        </table>

        <table class="table" width="100%" style="font-size:.5625rem !important; transform: translateY(-1.5em);">
            <tr>
                <td width="30%" class="p-0">
                    <table class="table table-bordered" width="100%" style="font-size:.5625rem !important">
                        <tbody>
                            <tr>
                                <td colspan="2" class="p-1 text-center">BILLING ASSESSMENT</td>
                            </tr>
                            @foreach ($ledger as $item)
                                <tr>
                                    <td width="70%" class="p-1">
                                        {{ $item->particulars }}
                                    </td>
                                    <td width="30%" class="text-right p-1">
                                        {{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="p-1">
                                    TOTAL AMOUNT:
                                </td>
                                <td class="text-right p-1">
                                    <b>{{ number_format(collect($ledger)->sum('amount'), 2) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="10%" class="p-0">

                </td>
                <td width="60%" class="p-0">
                    <table class="table mb-1" width="100%">
                        <tr>
                            <td width="100%" class="p-1">
                                <span>This is your official Certificate of registration. Please check and verify
                                    thoroughly the correctness of these data. If you have question or verification on
                                    the data found in this report, you may visit the RECORDS AND ADMISSION OFFICE
                                    @if ($schoolInfo->abbreviation == 'HCCSI')
                                    or you may call us at +63 82 2330013 @else.
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                    <table class="table" width="100%" style="font-size: 9px;">
                        <tr>
                            <td width="45%" class="p-0 text-center">Generated By:</td>
                            <td width="10%" class="p-0 text-center"></td>
                            <td width="45%" class="p-0 text-center">Approved By;</td>
                        </tr>
                        <tr>
                            <td class="p-0 text-center" style="border-bottom:solid 1px black">
                                {{ $registrar_sig != null ? $registrar_sig : '&nbsp;' }}
                            </td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center" style="border-bottom:solid 1px black"> {{ $regname}} </td>
                        </tr>
                        <tr>
                            <td class="p-0 text-center">REGISTRAR STAFF</td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center">REGISTRAR</td>
                        </tr>
                    </table>
                    <table class="table mb-1" width="100%">
                        <tr>
                            <td width="100%" class="p-1">
                                <p style="font-size:10px"> * Note: Show this form in case of irregularities. <br>DO NOT
                                    LOSE.</p>
                            </td>
                        </tr>
                    </table>

                </td>

            </tr>
        </table>

        {{-- <table style="font-size:10px"  width="100%">
                        <tr >
                              <td width="10%" style="text-align: left">Generated by:</td>
                              <td width="25%" style="text-align: center"><u>{{$registrar_sig}}</u></td>
                              <td width="10%" style="text-align: left">Approved by:</td>
                              <td width="20%" style="text-align: center"> <u>{{$dean}}</u></td>
                              <td width="35%" rowspan="2"  style="text-align: center">
                                   
                              </td>
                        </tr>
                        <tr>
                              <td></td>
                              <td style="text-align: center">
                                    <b>College Registrar</b>
                              </td>
                              <td></td>
                              <td style="text-align: center">
                                    <b>College Dean</b>
                              </td>
                             
                        </tr>
                  </table>
                   --}}
    </div>
    <div class="copy" style="margin-top:20px">

        <table width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%">
                    <img src="{{ base_path() }}/public/{{ $schoolInfo->picurl }}" alt="school" width="60px">
                </td>
                <td style="width: 50%; text-align: center;">
                    <div style="width: 100%; font-weight: bold; font-size: 15px;">{{ $schoolInfo->schoolname }}</div>
                    <div style="width: 100%; font-size: 12px;">{{ $schoolInfo->address }}</div>
                </td>
                <td width="25%"></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%"></td>
                <td style="width: 50%; text-align: center; font-size: 12px;">
                    <b>CERTIFICATE OF REGISTRATION</b>
                </td>
                <td width="25%"></td>
            </tr>
        </table>

        <table width="100%" style="font-size:10px" class="mb-1">
            <tbody>
                <tr>
                    <td width="75%">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                @php
                                    $middle = '';
                                    if (isset($studentInfo->middlename)) {
                                        if ($studentInfo->middlename != '' && $studentInfo->middlename != null) {
                                            $middle = isset($studentInfo->middlename)
                                                ? ' ' . $studentInfo->middlename[0] . '.'
                                                : '';
                                        }
                                    }
                                @endphp
                                <tr style="vertical-align: top;">
                                    <td width="25%">ID no. {{ $studentInfo->sid }}</td>
                                    <td width="35%">NAME:
                                        {{ $studentInfo->lastname . ', ' . $studentInfo->firstname . $middle }}</td>
                                    <td width="30%">CONTACT NO. {{ $studentInfo->contactno }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Course & Year: {{ $studentInfo->courseDesc }}
                                        {{ $studentInfo->levelname }}</td>
                                </tr>
                                <tr>
                                    <td colspan="1">Date of Birth:
                                        {{ \Carbon\Carbon::create($studentInfo->dob)->isoFormat('MM/DD/YYYY') }}</td>
                                    <td colspan="2">Address:
                                        @if ($studentInfo->street != '')
                                            {{ $studentInfo->street . ', ' }}
                                        @endif
                                        @if ($studentInfo->barangay != '')
                                            {{ $studentInfo->barangay . ', ' }}
                                        @endif
                                        @if ($studentInfo->city != '')
                                            {{ $studentInfo->city . ', ' }}
                                        @endif
                                        @if ($studentInfo->province != '')
                                            {{ $studentInfo->province . ', ' }}
                                        @endif

                                </tr>
                            </tbody>
                        </table>

                    </td>
                    <td width="25%">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td with="50%" style="text-align: right; font-size:11px">
                                        {{ \Carbon\Carbon::create($studentInfo->date_enrolled)->isoFormat('DD/MM/YYYY') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-size:11px">{{ $activeSem->semester }} S.Y.:
                                        {{ $activeSy->sydesc }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-size:11px">
                                        Block Code: {{ $studentInfo->sectionDesc }}
                                        {{-- Block Code: 
                                                                  <u><b><span style="font-size:13px">
                                                                              {{$studentInfo->courseabrv}}
                                                                                    @if ($studentInfo->levelid == 17)
                                                                                          1
                                                                                    @elseif( $studentInfo->levelid == 18)
                                                                                          2
                                                                                    @elseif( $studentInfo->levelid == 19)
                                                                                          3
                                                                                    @elseif( $studentInfo->levelid == 20)
                                                                                          4
                                                                                    @endif
                                                                              {{$studentInfo->sectionDesc}}
                                                                        </span>
                                                                  </b></u> --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align:right; font-size:15px">
                                        @if ($studentInfo->studtype == null || $studentInfo->studtype == 'old')
                                            OLD STUDENT
                                        @else
                                            NEW STUDENT
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="table table-bordered mb-0" style="font-size:.5625rem !important">
            @php
                $totalUnits = 0.0;
            @endphp
            <tbody>
                <tr>
                    <th class="p-1 text-left">Code</th>
                    <th class="p-1 text-left">Description</th>
                    <th class="p-1 text-center">Units</th>
                    <th class="p-1 text-center">Time</th>
                    <th class="p-1 text-center">Day</th>
                    <th class="p-1 text-center">Room</th>
                    <th class="p-1 text-center">Instructor</th>
                </tr>
                @foreach ($schedules as $item)
                    @php
                        $count = 0;
                    @endphp

                    <tr>
                        <td class="p-1" width="8%">
                            {{ $item[0]->subjCode }}
                        </td>
                        <td class="p-1" width="30%">
                            {{ $item[0]->subjDesc }}
                        </td>
                        <td class="p-1  text-center" width="5%">
                            {{ number_format($item[0]->lecunits + $item[0]->labunits, 1) }}
                        </td>
                        <td class="p-1  text-center" width="20%">
                            @foreach ($item as $timeitem)
                                @php
                                    // $schedother = $timeitem->schedotherclass == 'Laboratory' ? 'Lab:. ' : 'Lec:. ';
                                    $schedother = substr($timeitem->schedotherclass, 0, 3) . ':';
                                @endphp
                                @if ($timeitem->stime != null && $timeitem->etime != null)
                                    <p class="mb-0">{{ $schedother }}
                                        {{ \Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A') }} -
                                        {{ \Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A') }}</p>
                                @else
                                    <p class="mb-0"></p>
                                @endif
                            @endforeach
                        </td>
                        <td class="p-1  text-center" width="8%">
                            @foreach ($item as $descriptiontem)
                                @if ($descriptiontem->description != null)
                                    <p class="mb-0">{{ $descriptiontem->description }}</p>
                                @else
                                    <p class="mb-0"></p>
                                @endif
                            @endforeach
                        </td>
                        <td class="p-1  text-center" width="14%">
                            @foreach ($item as $roomitem)
                                @if ($roomitem->roomname != null)
                                    <p class="mb-0">{{ $roomitem->roomname }}</p>
                                @else
                                    <p class="mb-0"></p>
                                @endif
                            @endforeach

                        </td>
                        <td class="p-1  text-center" width="15%" style="font-size:.6rem !important">
                            {{ $item[0]->teacher }}
                        </td>
                        @php
                            $totalUnits += number_format($item[0]->lecunits + $item[0]->labunits, 1);
                        @endphp
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table" width="100%" style="font-size:.5625rem !important">
            <tbody>
                <tr>
                    <td width="38%" class="text-right p-1">TOTAL UNITS</td>
                    <td width="5%" class="text-center p-1"><b>{{ number_format($totalUnits, 1) }}<b></td>
                    <td width="57%" class=" p-1"></td>
                </tr>
            </tbody>
        </table>
        <table class="table" width="100%" style="font-size:.5625rem !important; transform: translateY(-1.5em);">
            <tr>
                <td width="30%" class="p-0">
                    <table class="table table-bordered" width="100%" style="font-size:.6rem !important">
                        <tbody>
                            <tr>
                                <td colspan="2" class="p-1 text-center">BILLING ASSESSMENT</td>
                            </tr>
                            @foreach ($ledger as $item)
                                <tr>
                                    <td width="70%" class="p-1">
                                        {{ $item->particulars }}
                                    </td>
                                    <td width="30%" class="text-right p-1">
                                        {{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="p-1">
                                    TOTAL AMOUNT:
                                </td>
                                <td class="text-right p-1">
                                    <b>{{ number_format(collect($ledger)->sum('amount'), 2) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="10%" class="p-0">

                </td>
                <td width="60%" class="p-0">
                    <table class="table mb-1" width="100%">
                        <tr>
                            <td width="100%" class="p-1">
                                <span>This is your official Certificate of registration. Please check and verify
                                    thoroughly the correctness of these data. If you have question or verification on
                                    the data found in this report, you may visit the RECORDS AND ADMISSION OFFICE
                                    @if ($schoolInfo->abbreviation == 'HCCSI')
                                    or you may call us at +63 82 2330013 @else.
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>

                    <table class="table" width="100%">
                        <tr>
                            <td width="45%" class="p-0 text-center">Generated By:</td>
                            <td width="10%" class="p-0 text-center"></td>
                            <td width="45%" class="p-0 text-center">Approved By;</td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center" style="border-bottom:solid 1px black">
                                @if ($registrar_sig != null)
                                    {{ $registrar_sig }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td class="p-0 text-center"></td>
                            <td class="p-1 text-center" style="border-bottom:solid 1px black">
                                @if ($dean != null)
                                    {{ $dean }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="p-0 text-center">College Registrar</td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center">College Dean</td>
                        </tr>
                    </table>
                    <table class="table mb-1" width="100%">
                        <tr>
                            <td width="100%" class="p-1">
                                <p style="font-size:10px"> * Note: Show this form in case of irregularities. <br>DO NOT
                                    LOSE.</p>
                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>

        {{-- <table style="font-size:10px"  width="100%">
                        <tr >
                              <td width="10%" style="text-align: left">Generated by:</td>
                              <td width="25%" style="text-align: center"><u>{{$registrar_sig}}</u></td>
                              <td width="10%" style="text-align: left">Approved by:</td>
                              <td width="20%" style="text-align: center"> <u>{{$dean}}</u></td>
                              <td width="35%" rowspan="2"  style="text-align: center">
                                   
                              </td>
                        </tr>
                        <tr>
                              <td></td>
                              <td style="text-align: center">
                                    <b>College Registrar</b>
                              </td>
                              <td></td>
                              <td style="text-align: center">
                                    <b>College Dean</b>
                              </td>
                             
                        </tr>
                  </table>
                   --}}
    </div>

</body>

</html>
