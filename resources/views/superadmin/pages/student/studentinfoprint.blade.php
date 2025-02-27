<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: 8.5in 13in; /* Adjust the size as needed */
            margin: 20px; /* Adjust margins */
            background: url('{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}') no-repeat center center;
            background-size: cover;
        }

        .watermark {
            opacity: .1;
            position: fixed; /* Use fixed to keep watermark on every page */
            left: 17%;
            top: 20%;
            width: 300px; /* Adjust width as needed */
            z-index: -1; /* Ensure watermark is behind content */
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .table thead th {
            vertical-align: bottom;
            background-color: rgb(167, 223, 167); /* Header background color */
            color: #000;
        }

        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .table-bordered {
            border: 1px solid #000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #000;
        }

        .table-sm td, .table-sm th {
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
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }

        .p-2 {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }

        .pl-3 {
            padding-left: 1rem !important;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem !important;
        }

        body {
            font-family: Calibri, sans-serif;
            margin: 0;
            padding: 0;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .grades td {
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
        }

        .studentinfo td {
            padding-top: .1rem;
            padding-bottom: .1rem;
        }

        .bg-red {
            color: red;
            border: solid 1px black !important;
        }

        td {
            padding-left: 5px;
            padding-right: 5px;
        }

        .aside {
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000 !important;
        }

        .aside span {
            top: 0;
            left: 0;
            background: none;
            transform-origin: 5 5;
            transform: rotate(-90deg);
        }
    </style>
</head>
<body>
    <div class="watermark">
        <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="watermark">
    </div>

    <table width="100%" class="table table-sm mb-0">
        <tr>
            <td>
                @php
                    date_default_timezone_set('Asia/Manila');
                    $currentDateTime = date('F j, Y g:i:s A');
                    echo $currentDateTime;
                @endphp
            </td>
        </tr>
        <tr>
            <td class="p-0 text-center" style="font-size: 14px;"><b>{{DB::table('schoolinfo')->first()->schoolname}}</b></td>
        </tr>
        <tr>
            <td class="p-0" style="font-size: 12px; text-align: center;">{{DB::table('schoolinfo')->first()->address}}</td>
        </tr>
    </table>

    <table width="100%" class="table table-sm mb-0 table-bordered mt-4" style="font-size:12px !important; font-family: Calibri, sans-serif">
        <thead>
            <tr>
                <td width="5%"></td>
                <td width="10%" class="text-left"  style="vertical-align: middle;"><b>ID</b></td>
                <td width="40%" class="text-left"  style="vertical-align: middle;"><b>NAME</b></td>
                <td width="15%" class="text-center" style="vertical-align: middle;"><b>GRADE LEVEL TO ENROLL</b></td>
                <td width="30%" class="text-left" style="vertical-align: middle;"><b>SECTION</b></td>
            </tr>
        </thead>
        <tbody>
            
            @foreach ($all_students as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->sid }}</td>
                    <td>{{ $item->student }}</td>
                    <td class="text-center">
                        @if($item->studstatus == 0)
                            {{ $item->levelname }}
                        @else
                            {{ $item->levelname }}
                        @endif
                    </td>
                    <td>
                        @if ($item->sectionname != 'Not Found')
                            {{$item->sectionname}}
                            @if ($item->strandcode != '' ||  $item->strandcode != null)
                                : {{$item->strandcode}} 
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
