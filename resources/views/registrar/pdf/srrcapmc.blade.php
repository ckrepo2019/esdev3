<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Certificate of Enrollment</title>
    <style>
        @page {
            size: 8.5in 13in;
            margin: .25in;
        }

        #watermark1 {
            position: fixed;
            text-align: center !important;
            /**
                    Set a position in the page for your image
                    This should center it vertically
                **/
            bottom: 30cm;
            /*left:     1cm;*/
            opacity: 0.1;

            /** Change image dimensions**/
            /* width:    8cm;
                height:   8cm; */

            /** Your watermark should be behind every content**/
            z-index: -1000;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
        }

        .table thead th {
            vertical-align: bottom;
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

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .p-1 {
            padding: .25rem !important;
        }





        .border-bottom {
            border-bottom: 1px solid black;
        }

        .mb-1,
        .my-1 {
            margin-bottom: .25rem !important;
        }

        body {
            font-family: Calibri, sans-serif;
        }

        .align-middle {
            vertical-align: middle !important;
        }


        .grades td {
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11px !important;
            font-family: "Lucida Console", "Courier New", monospace;
        }

        .studentinfo td {
            padding-top: .1rem;
            padding-bottom: .1rem;

        }

        .text-red {
            color: red;
            border: solid 1px black;
        }
    </style>
    <style>
        .double {
            border-style: double;
        }
    </style>
    <style>
        .detail-margin {
            margin-right: 50px;
            margin margin-left: 50px;
        }

        .detail-margin1 {
            margin-right: 70px;
            margin-left: 70px;
        }
    </style>
</head>

<head>

<body>
    <div id="watermark1">
        <img src="{{ base_path() }}/public/{{ $schoolinfo->picurl }}" height="400px" height="400px" />
    </div>
    <table class="table grades " width="100%">
        <tr>
            <td style="text-align: right !important; vertical-align: top;" width="25%">
                <img src="{{ base_path() }}/public/{{ $schoolinfo->picurl }}" alt="school" width="70px">
            </td>
            <td style="width: 50%; text-align: center;" class="align-middle">
                <div style="width: 100%; font-weight: bold; font-size: 19px !important;">{{ $schoolinfo->schoolname }}
                </div>
                <div style="width: 100%; font-size: 12px;">{{ $schoolinfo->address }}</div>
            </td>
            <td width="25%">
                {{-- <img src="{{base_path()}}/public/uccplogo.png" alt="school" width="70px"> --}}
            </td>
        </tr>
    </table>
    <br>
    <table class="table grades " width="100%">
        <tr>
            <td class="text-center" style="font-size:18px !important"><b>REQUEST FOR FINAL GRADE</b></td>
        </tr>
    </table>
    <br>
    <table class="table grades" width="100%">
        <tr>
            <td width="12%" class="text-center"><b>{{ $studinfo->sid }}</b></td>
            <td width="25%" class="text-center"><b>{{ $studinfo->firstname }}</b></td>
            <td width="25%" class="text-center"><b>{{ $studinfo->lastname }}</b></td>
            <td width="14%" class="text-center"><b>{{ $studinfo->mi }}</b></td>
            <td width="16%" class="text-center"><b>{{ $studinfo->gender }}</b></td>
        </tr>
        <tr>
            <td class="text-center">ID Number</td>
            <td class="text-center">First Name</td>
            <td class="text-center">Last Name</td>
            <td class="text-center">M.I.</td>
            <td class="text-center">Gender</td>
        </tr>
    </table>
    <table class="table grades " width="100%">
        <tr>
            <td width="30%" class="text-center"><b>{{ $enrollment->courseabrv }}</b></td>
            {{-- <td width="30%" class="text-center"><b>{{ $enrollment->leveltext }}</b></td> --}}
            <td width="30%" class="text-center"><b>{{ $enrollment->levelname }}</b></td>
            {{-- <td width="30%" class="text-center">
                <b>{{ $enrollment->yearLevel >= 21 ? $enrollment->leveltext : $enrollment->levelname }}</b></td> --}}
            <td width="20%" class="text-center"><b>{{ $sydesc->sydesc }}</b></td>
            <td width="20%" class="text-center"><b>{{ $semdesc->semester }}</b></td>
        </tr>
        <tr>
            <td class="text-center">Course</td>
            <td class="text-center">Year Level</td>
            <td class="text-center">School Year</td>
            <td class="text-center">Semester</td>
        </tr>
    </table>
    <hr>
    <table class="table grades" width="100%">
        <tr>
            <td width="5%"></td>
            <td width="90%">
                <table class="table grades table-bordered" width="100%">
                    <tr>
                        <td width="15%" class="text-center"><b>Subj Code</b></td>
                        <td width="40%" class="text-center"><b>Subject</b></td>
                        <td width="8%" class="text-center"><b>Units</b></td>
                        <td width="8%" class="text-center"><b>Grade</b></td>
                        <td width="27%" class="text-center"><b>Instructor</b></td>
                    </tr>
                    @php
                        $with_average = true;
                    @endphp

                    @foreach ($grades as $item)
                        <tr>
                            <td class="text-center align-middle">{{ $item->subjCode }}</td>
                            <td class=" align-middle">{{ $item->subjDesc }}</td>
                            <td class="text-center align-middle">{{ $item->units }}</td>
                            <td class="text-center align-middle">
                                @if (isset($item->fg))
                                    {{ $item->fg }}
                            </td>
                        @else
            </td>
            @endif
            <td class="text-center align-middle">{{ $item->teacher }}</td>
        </tr>
        @php
            if ($item->fg == null) {
                $with_average = false;
            }

        @endphp
        @endforeach
    </table>
    </td>
    <td width="5%"></td>
    </tr>
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="5%"></td>
            <td width="90%">
                <b>GPA:</b>{{ $with_average ? number_format(collect($grades)->average('fg'), 2) : null }}
            </td>
            <td width="5%"></td>
        </tr>
    </table>
    <br>
    <br>
    <table class="table grades" width="100%">
        <tr>
            <td width="70%"></td>
            <td width="25%" class="border-bottom text-center"><b>{{ $registrars }}</b></td>
            <td width="5%"></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">Registrar</td>
            <td></td>
        </tr>
    </table>
</body>
</head>

</html>
