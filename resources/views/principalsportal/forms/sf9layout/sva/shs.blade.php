<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            width: 100%;
            margin-bottom: 0px;
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

        .td-bordered {
            border-right: 1px solid #00000;
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
        .p-1{
            padding-top: 10px!important;
            padding-bottom: 10px!important;
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

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
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
            line-height: 12px;
            height: 50px;
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
            transform-origin: 0 50%;
            transform: rotate(-90deg);
        }
        .finalratingside {
            vertical-align: middle;
        }
        .finalratingside div {
           -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -5em;
                margin-right: -5em;

                
                transform-origin: 85 20 ;
        }
        .remarksside {
            
        }
        .remarksside div {
            -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -5em;
                margin-right: -5em;
                transform-origin: 83 14 ;
        }
        .hypens {
            -webkit-hyphens: manual;
            -moz-hyphens: manual;
            -ms-hyphens: manual;
            hyphens: manual;
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:20px 20px;
            
        }
        
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin-top: .8cm; margin-bottom: 0; margin-left: 1cm; margin-right: .8cm;}
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="padding-right: .4cm!important;">
            <div width="100%" style="height: 96%; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="100%" class="p-0" style="padding-left: .7cm!important; padding-right: .6cm!important;">
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                                <tr>
                                    <td width="100%" class="text-center p-0">
                                        <div style="font-size: 12px; padding-top: 10px;"><b>REPORT ON ATTENDANCE</b></div>
                                    </td>
                                </tr>
                            </table>
                            @php
                            $width = count($attendance_setup) != 0? 59 / count($attendance_setup) : 0;
                            @endphp
                            <table class="table table-bordered table-sm grades mb-0" width="100%">
                                <tr>
                                    <td class="p-0" style="border: 1px solid #000; text-align: center;">&nbsp;</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle; p-0" width="{{$width}}%"><span style="font-size: 11px!important;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                    @endforeach
                                    <td class="text-center p-0" width="17%" style="font-size: 11px!important;"><span style=""><b>Total</b></span></td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-left" width="24%"  style="font-size: 10px!important;">No. School Days</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                                    @endforeach
                                    <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-left" style="font-size: 10px!important;">No. of Days Present</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                                    @endforeach
                                    <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-left" style="font-size: 10px!important;">No. of Days Absent</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                                    @endforeach
                                    <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-left" style="font-size: 10px!important;">No. of Days Tardy</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle p-0" style="font-size: 10px!important;"></td>
                                    @endforeach
                                    <td class="text-center align-middle p-0" style="font-size: 10px!important;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                                <tr>
                                    <td width="100%" class="text-center p-0">
                                        <div style="font-size: 12px; padding-top: 10px;"><b>PARENT'S COMMENT/SIGNATURE</b></div>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px;">First Grading:</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px; padding-left: 75px!important; padding-right: 75px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px;">Second Grading:</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px; padding-left: 75px!important; padding-right: 75px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px;">Third Grading:</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px; padding-left: 75px!important; padding-right: 75px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px;">Fourth Grading:</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px; padding-left: 75px!important; padding-right: 75px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; padding-left: 40px!important; padding-right: 40px!important;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; border-bottom: 1.5px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td width="50%" class="p-0" style="padding-left: .8cm!important;">
            <div width="100%" style="height: 96%; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="100%" class="p-0" style="padding-left: .8cm!important; padding-right: .65cm!important;">
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                                <tr>
                                    <td width="20%" class="p-0"><img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                                    <td width="60%" class="text-center p-0">
                                        <div style="font-size: 16px;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
                                        <div style="font-size: 10.2px;"><b><i>Kauswagan, Lanao del Norte 9202</i></b></div>
                                        <div style="font-size: 8px;">Tel. No.: (063) 227-1007</div>
                                        <div style="font-size: 8.5px;">Email: svacademyschool@yahoo.com</div>
                                        <div style="font-size: 8.5px;">Website: svapeace.webs.com</div>
                                        <div style="font-size: 12.5px; padding-top: 10px;"><b>SENIOR HIGH SCHOOL GRADE {{str_replace('GRADE', '', $student->levelname)}}</b></div>
                                        <div style="font-size: 11.5px; padding-top: 5px;">School Year 20 <u>&nbsp;&nbsp;&nbsp;{{substr($schoolyear->sydesc, 2,2)}}&nbsp;&nbsp;&nbsp;</u> 20 <u>&nbsp;&nbsp;&nbsp;{{substr($schoolyear->sydesc, 7,7)}}&nbsp;&nbsp;&nbsp;</u></div>
                                    </td>
                                    <td width="20%" class="text-center p-0" style=""></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed;">
                                <tr>
                                    <td width="100%" class="text-center p-0">
                                        <div style="font-size: 12px; padding-top: 10px;"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></div>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 20px;">
                                <tr>
                                    <td width="10%" class="text-left p-0" style="font-size: 12px;">Name:</td>
                                    <td width="90%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="10%" class="text-left p-0" style="font-size: 12px;">Age:</td>
                                    <td width="40%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{$student->age}}</td>
                                    <td width="14%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;Sex:</td>
                                    <td width="36%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{$student->gender}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="10%" class="text-left p-0" style="font-size: 12px;">Grade:</td>
                                    <td width="40%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{str_replace('GRADE', '', $student->levelname)}}</td>
                                    <td width="14%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;Section:</td>
                                    <td width="36%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{$student->sectionname}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="18%" class="text-left p-0" style="font-size: 12px;">School Year:</td>
                                    <td width="32%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{$schoolyear->sydesc}}</td>
                                    <td width="14%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;LRN:</td>
                                    <td width="36%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">{{$student->lrn}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 12px;">Dear Parent:</td>
                                </tr>
                                <tr>
                                    <td width="100%" class="p-0" style="font-size: 12px; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.</td>
                                </tr>
                                <tr>
                                    <td width="100%" class="p-0" style="font-size: 12px; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school welcomes you should you desire to know more about your child's progress.</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                                <tr>
                                    <td width="57%" class="text-left p-0" style="font-size: 12px;"></td>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;"><b>{{$adviser}}</b></td>
                                </tr>
                                <tr>
                                    <td width="57%" class="text-left p-0" style="font-size: 12px;"></td>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; padding-top: 4px!important;">Adviser</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                                <tr>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;"><b>EVELYN L. YEE</b></td>
                                    <td width="57%" class="text-left p-0" style="font-size: 12px;"></td>
                                </tr>
                                <tr>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; padding-top: 4px!important;">Principal</td>
                                    <td width="57%" class="text-left p-0" style="font-size: 12px;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                                <tr>
                                    <td width="100%" class="text-center p-0" style="font-size: 12px;">Certificate of Transfer</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="25%" class="text-left p-0" style="font-size: 12px;">Admitted to Grade:</td>
                                    <td width="17.5%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">&nbsp;</td>
                                    <td width="12.5%" class="text-left p-0" style="font-size: 12px;">&nbsp;Section:</td>
                                    <td width="45%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="45%" class="text-left p-0" style="font-size: 12px;">Eligibility for Admission to Grade:</td>
                                    <td width="55%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;">&nbsp;</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 12px;">Approved:</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px;">
                                <tr>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;"><b>EVELYN L. YEE</b></td>
                                    <td width="14%" class="text-left p-0" style="font-size: 12px;"></td>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;"><b>{{$adviser}}</b></td>
                                </tr>
                                <tr>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; padding-top: 4px!important;">Principal</td>
                                    <td width="14%" class="text-left p-0" style="font-size: 12px;"></td>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; padding-top: 4px!important;">Adviser</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                                <tr>
                                    <td width="100%" class="text-center p-0" style="font-size: 12px;">Cancellation of Eligibility to Transfer</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed;">
                                <tr>
                                    <td width="18%" class="text-left p-0" style="font-size: 12px;">Admitted in:</td>
                                    <td width="51%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;">&nbsp;</td>
                                    <td width="31%" class="text-left p-0" style="font-size: 12px;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                                <tr>
                                    <td width="8%" class="text-left p-0" style="font-size: 12px;">Date:</td>
                                    <td width="61%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;">&nbsp;</td>
                                    <td width="31%" class="text-left p-0" style="font-size: 12px;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 45px;">
                                <tr>
                                    <td width="57%" class="text-left p-0" style="font-size: 12px;"></td>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; border-bottom: 1px solid #000;"><b>EVELYN L. YEE</b></td>
                                </tr>
                                <tr>
                                    <td width="57%" class="text-left p-0" style="font-size: 12px;"></td>
                                    <td width="43%" class="text-center p-0" style="font-size: 12px; padding-top: 4px!important;">Principal</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
{{-- =============================================================================== --}}
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="padding-right: .4cm!important;">
            <div width="100%" style="height: 96%; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="100%" class="p-0" style="padding-left: .7cm!important; padding-right: .6cm!important;">
                            @php
                                $x = 1;
                            @endphp
                            <table width="100%" class="table table-bordered table-sm grades mb-0" style="">
                                <tr>
                                    <td colspan="6" class="text-center" style="font-size: 12px!important;"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                                    <td width="11%" rowspan="4" class="text-center remarksside" style="font-size: 11px!important;"><div><b>REMARKS</b></div></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-left p-0" style="font-size: 11px!important; padding: 2px 0px!important;">&nbsp;Grade {{str_replace('GRADE', '', $student->levelname)}} First Semester</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center" style="font-size: 9px!important; vertical-align: middle;"><b>Subjects</b></td>
                                    <td width="15%" colspan="2"  class="text-center align-middle" style="font-size: 9px!important;"><b>Quarter</b></td>
                                    <td width="15%" class="text-center align-middle" style="font-size: 9px!important;"><b>Semester</b></td>
                                </tr>
                                <tr>
                                    @if($x == 1)
                                        <td colspan="3" class="text-left" style="font-size: 9px!important; vertical-align: middle;"><b>Strand: <span style="font-size: 8px!important;">{{$strandInfo->strandname}}</span></b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>1</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>2</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>Final Grade</b></td>
                                    @elseif($x == 2)
                                        <td colspan="3" class="text-left" style="font-size: 9px!important; vertical-align: middle;"><b>Strand: <span style="font-size: 8px!important;">{{$strandInfo->strandname}}</span></b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>3</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>4</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>Final Grade</b></td>
                                    @endif
                                </tr>
                                <tr style="background-color: gray;">
                                    <td class="text-left p-0" style="font-size: 9px!important; padding: 2px 0px!important;">&nbsp;SubjectCode</td>
                                    <td class="text-left p-0" style="font-size: 9px!important; padding: 2px 0px!important;">&nbsp;LearningAreas</td>
                                    <td class="text-left p-0" style="font-size: 9px!important; padding: 2px 0px!important;">&nbsp;Subject Description</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                {{-- <tr>
                                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Core</b></td>
                                </tr> --}}
                                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                    <tr>
                                        <td width="14%" style="font-size: 8px!important;">Core</td>
                                        <td width="16%" style="font-size: 8px!important;">{{$item->subjcode!=null ? $item->subjcode : null}}</td>
                                        <td width="30%" style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        @endif
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Applied</b></td>
                                </tr> --}}
                                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                    <tr>
                                        <td style="font-size: 8px!important;">Applied</td>
                                        <td style="font-size: 8x!important;">{{$item->subjcode!=null ? $item->subjcode : null}}
                                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        @endif
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Specialized</b></td>
                                </tr> --}}
                                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                    <tr>
                                        <td style="font-size: 8px!important;">Specialized</td>
                                        <td style="font-size: 8px!important;">{{$item->subjcode!=null ? $item->subjcode : null}}
                                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        @endif
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                            
                                <tr>
                                    @php
                                        $genave = collect($finalgrade)->where('semid',$x)->first();
                                    @endphp
                                    <td class="text-right" colspan="5" style="font-size: 10px!important;"><b>General Average for the Semester</b></td>
                                    <td class="text-center" style="font-weight: bold; font-size: 10px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                    <td class="text-center" style="font-weight: bold; font-size: 10px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                                </tr>
                            </table>
                            <br>
                            @php
                                $x = 2;
                            @endphp
                            <table width="100%" class="table table-sm grades mb-0" style="">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11px!important; padding: 2px 0px!important;">&nbsp;Grade {{str_replace('GRADE', '', $student->levelname)}} Second Semester</td>
                                </tr>
                            </table>
                            <table width="100%" class="table table-bordered table-sm grades mb-0" style="">
                                <tr>
                                    <td colspan="3" class="text-center" style="font-size: 9px!important; vertical-align: middle;"><b>Subjects</b></td>
                                    <td width="15%" colspan="2"  class="text-center align-middle" style="font-size: 9px!important;"><b>Quarter</b></td>
                                    <td width="15%" class="text-center align-middle" style="font-size: 9px!important;"><b>Semester</b></td>
                                    <td width="11%" rowspan="2" class="text-center remarksside" style="font-size: 11px!important;"><div><b>&nbsp;</b></div></td>
                                </tr>
                                <tr>
                                    @if($x == 1)
                                        <td colspan="3" class="text-left" style="font-size: 9px!important; vertical-align: middle;"><b>Strand: <span style="font-size: 8px!important;">{{$strandInfo->strandname}}</span></b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>1</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>2</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>Final Grade</b></td>
                                    @elseif($x == 2)
                                        <td colspan="3" class="text-left" style="font-size: 9px!important; vertical-align: middle;"><b>Strand: <span style="font-size: 8px!important;">{{$strandInfo->strandname}}</span></b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>3</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>4</b></td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;"><b>Final Grade</b></td>
                                    @endif
                                </tr>
                                <tr style="background-color: gray;">
                                    <td class="text-left p-0" style="font-size: 9px!important; padding: 2px 0px!important;">&nbsp;SubjectCode</td>
                                    <td class="text-left p-0" style="font-size: 9px!important; padding: 2px 0px!important;">&nbsp;LearningAreas</td>
                                    <td class="text-left p-0" style="font-size: 9px!important; padding: 2px 0px!important;">&nbsp;Subject Description</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                {{-- <tr>
                                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Core</b></td>
                                </tr> --}}
                                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                    <tr>
                                        <td width="14%" style="font-size: 8px!important;">Core</td>
                                        <td width="16%" class="hypens" style="font-size: 8px!important;">{{$item->subjcode!=null ? $item->subjcode : null}}
                                        <td width="30%" style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        @endif
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Applied</b></td>
                                </tr> --}}
                                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                    <tr>
                                        <td style="font-size: 8px!important;">Applied</td>
                                        <td style="font-size: 8px!important;">{{$item->subjcode!=null ? $item->subjcode : null}}
                                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        @endif
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Specialized</b></td>
                                </tr> --}}
                                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                    <tr>
                                        <td style="font-size: 8px!important;">Specialized</td>
                                        <td style="font-size: 8px!important;">{{$item->subjcode!=null ? $item->subjcode : null}}
                                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        @endif
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                            
                                <tr>
                                    @php
                                        $genave = collect($finalgrade)->where('semid',$x)->first();
                                    @endphp
                                    <td class="text-right" colspan="5" style="font-size: 10px!important;"><b>General Average for the Semester</b></td>
                                    <td class="text-center" style="font-weight: bold; font-size: 10px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                    <td class="text-center" style="font-weight: bold; font-size: 10px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 12px;">Legend: Learner's Progress and Achievement</td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed;">
                                <tr>
                                    <td width="33%" class="text-left p-0" style="font-size: 9px;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Descriptors</b></td>
                                    <td width="20%" class="text-left p-0" style="font-size: 9px;"><b>Grading Scale</b></td>
                                    <td width="25%" class="text-left p-0" style="font-size: 9px;"><b>Remarks</b></td>
                                    <td width="22%" class="text-center"></td>
                                </tr>
                                <tr>
                                    <td width="33%" class="text-left p-0" style="font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Outstanding</td>
                                    <td width="20%" class="text-left p-0" style="font-size: 9px;">90-100</td>
                                    <td width="25%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                                    <td width="22%" class="text-center"></td>
                                </tr>
                                <tr>
                                    <td width="33%" class="text-left p-0" style="font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Very Satisfactory</td>
                                    <td width="20%" class="text-left p-0" style="font-size: 9px;">85-89</td>
                                    <td width="25%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                                    <td width="22%" class="text-center"></td>
                                </tr>
                                <tr>
                                    <td width="33%" class="text-left p-0" style="font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Satisfactory</td>
                                    <td width="20%" class="text-left p-0" style="font-size: 9px;">80-84</td>
                                    <td width="25%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                                    <td width="22%" class="text-center"></td>
                                </tr>
                                <tr>
                                    <td width="33%" class="text-left p-0" style="font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fairly Satisfactory</td>
                                    <td width="20%" class="text-left p-0" style="font-size: 9px;">75-79</td>
                                    <td width="25%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                                    <td width="22%" class="text-center"></td>
                                </tr>
                                <tr>
                                    <td width="33%" class="text-left p-0" style="font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Did Not Meet Expectations</td>
                                    <td width="20%" class="text-left p-0" style="font-size: 9px;">Below 75</td>
                                    <td width="25%" class="text-left p-0" style="font-size: 9px;">Failed</td>
                                    <td width="22%" class="text-center"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td width="50%" class="p-0" style="padding-left: .8cm!important;">
            <div width="100%" style="height: 96%; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="100%" class="p-0" style="padding-left: .7cm!important; padding-right: .6cm!important;">
                            <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed;">
                                <tr>
                                    <td class="text-center" colspan="5" style="font-size: 12.5px;"><b>REPORTS ON LEARNER'S OBSERVED VALUES</b></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-size: 12px;"></td>
                                    <td class="text-center" colspan="4" style="font-size: 12px;"><b>QUARTER</b></td>
                                </tr>
                                <tr>
                                    <td width="76%" class="text-center p-0" style=""></td>
                                    <td width="6%" class="text-center p-0" style=""><b>1</b></td>
                                    <td width="6%" class="text-center p-0" style=""><b>2</b></td>
                                    <td width="6%" class="text-center p-0" style=""><b>3</b></td>
                                    <td width="6%" class="text-center p-0" style=""><b>4</b></td>
                                </tr>
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if($item->value == 0)
                                        @else
                                            
                                            <tr>
                                                {{-- @if($count == 0)
                                                        <td width="21%" class="align-middle" style="border-right: 1px solid #fff;" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                                        @php
                                                            $count = 1;
                                                        @endphp
                                                @endif --}}
                                                <td class="align-middle" style="font-size: 10px;"><b>{{$item->group}} -</b> {{$item->description}}</td>
                                                <td class="text-center align-middle" style="font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle" style="font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle" style="font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle" style="font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed;">
                                <tr>
                                    <td width="100%" class="p-0" style="padding-right: .6cm!important;">
                                        <table class="table table-sm mb-0" style="table-layout: fixed;">
                                            <tr>
                                                <td width="100%" class="text-left p-0" style="font-size: 12px;">Legend: Observed Value</td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm mb-0" style="table-layout: fixed;">
                                            <tr>
                                                <td width="33%" class="text-left p-0" style="font-size: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Marking</b></td>
                                                <td width="35%" class="text-left p-0" style="font-size: 10px;"><b>Non-Numerical Rating</b></td>
                                                <td width="32%" class="text-left p-0" style="font-size: 10px;"></td>
                                            </tr>
                                            <tr>
                                                <td width="33%" class="text-left p-0" style="font-size: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AO</td>
                                                <td width="35%" class="text-left p-0" style="font-size: 10px;">Always Observed</td>
                                                <td width="32%" class="text-left p-0" style="font-size: 10px;"></td>
                                            </tr>
                                            <tr>
                                                <td width="33%" class="text-left p-0" style="font-size: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SO</td>
                                                <td width="35%" class="text-left p-0" style="font-size: 10px;">Sometimes Observed</td>
                                                <td width="32%" class="text-left p-0" style="font-size: 10px;"></td>
                                            </tr>
                                            <tr>
                                                <td width="33%" class="text-left p-0" style="font-size: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RO</td>
                                                <td width="35%" class="text-left p-0" style="font-size: 10px;">Rarely Observed</td>
                                                <td width="32%" class="text-left p-0" style="font-size: 10px;"></td>
                                            </tr>
                                            <tr>
                                                <td width="33%" class="text-left p-0" style="font-size: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO</td>
                                                <td width="35%" class="text-left p-0" style="font-size: 10px;">Not Observed</td>
                                                <td width="32%" class="text-left p-0" style="font-size: 10px;"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</body>
</html>