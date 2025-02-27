<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>
        .full-width-image {
            width: 100%;
            height: auto;
            display: block; /* Ensures proper alignment */
            }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table td, .table th {
            padding: .75rem;
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
        
        .p-0{
            padding: 0 !important;
        }
       
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 12pt !important;
        }

        .grades_2 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11pt !important;
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
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 14 26;
            transform: rotate(-90deg);
        }
        .asidetotal {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .asidetotal span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 24 16;
            transform: rotate(-90deg);
        }
        .asideno {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .asideno span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 25 16;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
	
        @page { size: 8.5in 14in; margin: .5in .5in;}
        
    </style>
</head>
<body>
    <table width="100%" class="text-center">
        <tr>
            <td width="100%"><img src="{{base_path()}}/public/assets/images/pac/header2.png" alt="school" width="700px"></td>
        </tr>
    </table>
    <table width="100%" class="grades">
        <tr>
            <td width="100%" style="padding-left:.5in; padding-right:.5in">
                <table width="100%" class="text-center" style="margin-top: 30px">
                    <tr>
                        <td style="font-size: 20pt !important; ">CERTIFICATE OF REGISTRATION</td>
                    </tr>
                </table>
                <br>
                <table class="grades_2" width="100%" style="font-size:10pt">
                    <tr>
                        <td width="70%" class="p-0">ID Number: {{$studentInfo->sid}}</td>
                        <td width="30%" class="p-0">Semester: {{$activeSem->semester}}</td>
                    </tr>
                    <tr>
                        <td class="p-0">Student Name: <span style="font-size: 10pt">{{$studentInfo->student}}</td>
                        <td class="p-0">School Year: {{$activeSy->sydesc}}</td>
                    </tr>
                </table>
                <table class="grades_2" width="100%" >
                    <tr>
                        <td width="100%" class="p-0 mt-0">Course: {{$studentInfo->courseDesc}}</td>
                    </tr>
                    <tr>
                        <td class="p-0">Year: {{$studentInfo->levelname}}</td>
                    </tr>
                    <tr>
                        <td class="p-0">Sex: {{$studentInfo->gender}}</td>
                    </tr>
                </table>
                <table class="grades" width="100%">
                    <tr>
                        <td width="100%">
                            <p style="text-indent: .6in; font-size: 12pt; margin-bottom:0 !important" >
                                He/She enrolled in the subjects listed below with the corresponding grades, to wit:
                            </p>
                        </td>
                    </tr>
                </table>
                <br>
                <table class="grades" width="100%">
                    <tr>
                        <td width="30%"><b>COURSE NO.</td>
                        <td width="60%"><b>DESCRIPTION</td>
                        <td width="20%" class="text-center"><b>UNITS</td>
                    </tr>
					<tr>
                        <td >&nbsp;</td>
                        <td >&nbsp;</td>
                        <td >&nbsp;</td>
                    </tr>
                    @foreach ($schedules as $item)
                        <tr>
                            <td>{{$item[0]->subjCode}}</td>
                            <td>{{$item[0]->subjDesc}}</td>
                            <td  class="text-center">{{number_format($item[0]->lecunits + $item[0]->labunits,1)}}</td>
                        </tr>
                    @endforeach
                </table>
                <table class="grades" width="100%">
                    <tr>
                        <td width="100%">
                            <p style="text-indent: .5in;  margin-bottom:0 !important;">
                                This certification is issued upon request of the above named student for whatever legal purpose/s this may serve.
                            </p>
                        </td>
                    </tr>
                </table>
				@php
				
					$day = \Carbon\Carbon::now('Asia/Manila')->isoFormat('Do');
        
					preg_match_all('/([0-9]+|[a-zA-Z]+)/',$day,$matches);
					
					$day = $matches[0][0];
					$sc = $matches[0][1];
					$month = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM');
					$year = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY');
				@endphp
                <table class="grades" width="100%">
                    <tr>
                        <td width="100%">
                            <p style="text-indent: .5in;  margin-bottom:0 !important;">
                                Issued this {{$day}}<sup>{{$sc}}</sup> day of {{$month}} {{$year}} at <b><i>Philippine Advent College</i></b>, Sindangan, Zamboanga del Norte.
                            </p>
                        </td>
                    </tr>
                </table>
                <table class="grades" width="100%" class="text-center" style="margin-top: 40px">
                    <tr>
                        <td width="40%"></td>
                        <td width="60%" class="text-center"><b>{{$regname}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center"><i>College Registrar</td>
                    </tr>
                </table>
                <table class="grades" width="100%" class="text-center" style="margin-top: 20px">
                    <tr>
                        <td width="40%" class="text-center"><b>NOT VALID WITHOUT</td>
                        <td width="60%"><b></td>
                    </tr>
                    <tr>
                        <td  class="text-center"><b>COLLEGE SEAL</td>
                        <td class="text-center">Note: Erasure/alteration is not valid</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    
</body>
</html>