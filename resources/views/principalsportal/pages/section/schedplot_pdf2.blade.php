<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>

.table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            table-layout: fixed
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            vertical-align: top;
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
        }

        .text-center{
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0 td{
            padding: 0 !important;
        }
       
        .pl-3{
            padding-left: 1rem !important;
        }

        .m-0{
            margin: 0!important;
        }

        .mb-0{
            margin-bottom: 0;
        }
        .mt-0{
            margin-top: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7pt;
        }
        p{
            margin: 0;
        }
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 8pt !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        td{
            padding-left: 5px;
            padding-right: 5px;
        }
        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 10px;
            height: 30px;
            border: 1px solid #000!important;
            white-space: nowrap;
            text-align: center;
        }

        .asider {
            color: #000;
            border: 1px solid #000!important;
            white-space: nowrap;
            text-align: center;
            background-color: #afafaf;
            font-size: 10px;
            vertical-align: middle; /* Vertically center the content */
            padding: 0;
        }

        .asider span {
            display: inline-block;
            transform: rotate(-90deg) translateY(-7px); 
            font-size: 10px; 
            white-space: nowrap;
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 10;
            left: 0;

            /* Border is the new background */
            background: none;
            white-space: nowrap;
            /* Rotate from top left corner (not default) */
            transform-origin: 12 10;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }
        .subtitle{
            font-size: 9pt!important;
        }
        .font{
            font-size: 8pt!important;
        }
        .small-font{
            font-size: 7pt!important;
        }
        .smaller-font{
            font-size: 6.5pt!important;
        }
        .underline{
            border-bottom: 1px solid black;
        }
        h4{
            margin-bottom: 10px;
        }
        .spacing{
            padding: 5px;
        }
        .title{
            font-size: 11pt!important;
            
        }
        .first-page{
            font-size: 10pt!important;
        }
        .second-page{
            font-size: 10pt!important;
        }
        .red{
            color: red;
        }
        .compress{
            margin-right: .1in;
            margin-left: .1in;
        }
        .compressed{
            margin-right: .3in;
            margin-left: .3in;
        }
        .very-compressed{
            margin: 0in 1.5in;
        }
        .times-new{
            font-family: 'Times New Roman', Times, serif!important;
        }
        .td-space td{
            margin-top: 2px!important;
        }

        .space{
            margin-top: 5px!important
        }
        .spacer{
            margin-top: 10px!important
        }

        .bold{
            font-weight:bold;
        }
        .uppercase{
            text-transform: uppercase;
        }

        .spacing{
            margin-top: 19px!important;
        }

        .p-1 td{
            padding: 0.1rem!important;
        }
        .border{
            border: 1px solid black;
        }
        .indent{
            margin-left: 10px!important
        }
        .italic{
            font-style: italic;
        }
        .capital{
            text-transform: capitalize
        }
        @page {  
            margin:20px 20px;
            
        }
        
/* 		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            } */

       
        
        @page { size: 8.5in 11in; margin: .25in .25in;  }
        
    </style>
</head>
      <body>
            <table width="100%" class="table">
                <tr>
                    <td width="25%" class="text-right"><img  src="{{public_path($schoolinfo->picurl)}}" style="width:50px;"></td>
                    <td width="50%" class="text-center" style="font-size: 12pt"> 
                        <div><b>{{$schoolinfo->schoolname}}<b></div>
                        <div style="font-size: 8pt">{{$schoolinfo->address}}</div>
                        </td>
                    <td width="25%"></td>

                </tr>
            </table>
            <div class="title">School Year: {{$sy->sydesc}}</div>
            <div class="title">Section:</div>
            <div class="title">Adviser: {{$adviser->firstname}} {{substr($adviser->middlename, 0, 1)}}. {{$adviser->lastname}}</div>

            <table class="table table-bordered subtitle spacing">
                <thead>
                    <tr style="background-color:CornflowerBlue">
                        <th width="40%">Subject</th>
                        <th width="10%" class="text-center">Day</th>
                        <th width="25%" class="text-center">Time</th>
                        <th width="10%" class="text-center">Room</th>
                        <th width="15%" class="text-center">Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lastSubjDesc = null; // Track the last subjdesc processed
                        $rowspanCount = 0; // Counter for rowspan
                    @endphp

                    @foreach ($temp_sched as $item)
                        @php
                            $lastDay = null; // Track the last day processed
                            $dayGroups = []; // Group schedules by consecutive days
                            foreach ($item->schedule as $sched) {
                                if ($sched->day === $lastDay) {
                                    $dayGroups[array_key_last($dayGroups)][] = $sched; // Add to the current group
                                } else {
                                    $dayGroups[] = [$sched]; // Start a new group
                                    $lastDay = $sched->day; // Update the last day processed
                                }
                            }
                        @endphp

                        @foreach($dayGroups as $index => $group)
                            @foreach($group as $i => $sched)
                            <tr>
                                {{-- Only display subjdesc if it hasn't been processed --}}
                                @if ($index === 0 && $i === 0)
                                    @php
                                        $rowspanCount = array_sum(array_map('count', $dayGroups)); // Calculate total rowspan
                                        $lastSubjDesc = $item->subjdesc; // Update the last subjdesc processed
                                    @endphp
                                    <td rowspan="{{ $rowspanCount }}">
                                        <div>{{ $item->subjdesc }}</div>
                                        <div class="smaller-font red capital italic">{{ $item->subjcode }}</div>
                                    </td>
                                @endif

                                {{-- Display day cell only for the first schedule in the group --}}
                                @if ($i === 0)
                                    <td class="text-center" rowspan="{{ count($group) }}">
                                        {{ $sched->day }}
                                    </td>
                                @endif

                                {{-- Display time, room, and teacher --}}
                                <td class="text-center">{{ $sched->time }}</td>
                                <td class="text-center">{{ $sched->roomname }}</td>
                                <td class="text-center">{{ $sched->teacher }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </body>
</html>