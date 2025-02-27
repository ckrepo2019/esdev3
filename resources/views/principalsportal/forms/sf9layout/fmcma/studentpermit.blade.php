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
            font-family: 'Times New Roman', Times, serif;
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
                transform-origin: 92 21 ;
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
               font-family: 'Times New Roman', Times, serif;
            }
        @page { size: 11in 8.5in; margin: 20px 25px;}
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="padding-right: 10px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000;">
                <tr>
                    <td width="100%">
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="35%" class="text-right p-0" style="padding-top: 5px!important;">
                                    <img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                                </td>
                                <td width="30%" class="text-center p-0" style="font-size: 15px; vertical-align: middle;">
                                    <div><b>FMC MA SCHOOL</b></div>
                                    <div><b>Ozamiz City</b></div>
                                    <div><b>S.Y. {{$schoolyear->sydesc}}</b></div>
                                </td>
                                <td width="35%" class="p-0" style=""></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center" style="font-size: 15px;"><b>EXAMINATION PERMIT</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="12%" class="text-left p-0"><b>&nbsp;NAME:</b></td>
                                <td width="57%" class="text-left p-0"><b>{{$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'.'}}</b></td>
                                <td width="13%" class="text-left p-0"><b>LEVEL:</b></td>
                                <td width="18%" class="text-left p-0"><b>{{$student->levelname}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>SUBJECTS</b></td>
                                <td width="7%" class="text-left p-0"></td>
                                <td width="41%" class="text-right p-0"><b>TEACHER’S INITIALS </b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>English</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Science</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Mathematics</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Araling Panlipunan</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>MAPEH/TLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Others:</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 20px!important; padding-bottom: 8px!important;">
                            <tr>
                                <td width="19%" class="text-left p-0"><b>&nbsp;ISSUED BY:</b></td>
                                <td width="33%" class="text-left p-0"><b>&nbsp;BRENDA B. TUBLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="35%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="5%" class="text-left p-0"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="padding-left: 10px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000;">
                <tr>
                    <td width="100%">
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="35%" class="text-right p-0" style="padding-top: 5px!important;">
                                    <img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                                </td>
                                <td width="30%" class="text-center p-0" style="font-size: 15px; vertical-align: middle;">
                                    <div><b>FMC MA SCHOOL</b></div>
                                    <div><b>Ozamiz City</b></div>
                                    <div><b>S.Y. {{$schoolyear->sydesc}}</b></div>
                                </td>
                                <td width="35%" class="p-0" style=""></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center" style="font-size: 15px;"><b>EXAMINATION PERMIT</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="12%" class="text-left p-0"><b>&nbsp;NAME:</b></td>
                                <td width="57%" class="text-left p-0"><b>{{$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'.'}}</b></td>
                                <td width="13%" class="text-left p-0"><b>LEVEL:</b></td>
                                <td width="18%" class="text-left p-0"><b>{{$student->levelname}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>SUBJECTS</b></td>
                                <td width="7%" class="text-left p-0"></td>
                                <td width="41%" class="text-right p-0"><b>TEACHER’S INITIALS </b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>English</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Science</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Mathematics</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Araling Panlipunan</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>MAPEH/TLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Others:</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 20px!important; padding-bottom: 8px!important;">
                            <tr>
                                <td width="19%" class="text-left p-0"><b>&nbsp;ISSUED BY:</b></td>
                                <td width="33%" class="text-left p-0"><b>&nbsp;BRENDA B. TUBLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="35%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="5%" class="text-left p-0"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 10px;">
    <tr>
        <td width="50%" class="p-0" style="padding-right: 10px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000;">
                <tr>
                    <td width="100%">
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="35%" class="text-right p-0" style="padding-top: 5px!important;">
                                    <img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                                </td>
                                <td width="30%" class="text-center p-0" style="font-size: 15px; vertical-align: middle;">
                                    <div><b>FMC MA SCHOOL</b></div>
                                    <div><b>Ozamiz City</b></div>
                                    <div><b>S.Y. {{$schoolyear->sydesc}}</b></div>
                                </td>
                                <td width="35%" class="p-0" style=""></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center" style="font-size: 15px;"><b>EXAMINATION PERMIT</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="12%" class="text-left p-0"><b>&nbsp;NAME:</b></td>
                                <td width="57%" class="text-left p-0"><b>{{$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'.'}}</b></td>
                                <td width="13%" class="text-left p-0"><b>LEVEL:</b></td>
                                <td width="18%" class="text-left p-0"><b>{{$student->levelname}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>SUBJECTS</b></td>
                                <td width="7%" class="text-left p-0"></td>
                                <td width="41%" class="text-right p-0"><b>TEACHER’S INITIALS </b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>English</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Science</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Mathematics</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Araling Panlipunan</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>MAPEH/TLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Others:</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 20px!important; padding-bottom: 8px!important;">
                            <tr>
                                <td width="19%" class="text-left p-0"><b>&nbsp;ISSUED BY:</b></td>
                                <td width="33%" class="text-left p-0"><b>&nbsp;BRENDA B. TUBLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="35%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="5%" class="text-left p-0"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="padding-left: 10px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000;">
                <tr>
                    <td width="100%">
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="35%" class="text-right p-0" style="padding-top: 5px!important;">
                                    <img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                                </td>
                                <td width="30%" class="text-center p-0" style="font-size: 15px; vertical-align: middle;">
                                    <div><b>FMC MA SCHOOL</b></div>
                                    <div><b>Ozamiz City</b></div>
                                    <div><b>S.Y. {{$schoolyear->sydesc}}</b></div>
                                </td>
                                <td width="35%" class="p-0" style=""></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center" style="font-size: 15px;"><b>EXAMINATION PERMIT</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="12%" class="text-left p-0"><b>&nbsp;NAME:</b></td>
                                <td width="57%" class="text-left p-0"><b>{{$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'.'}}</b></td>
                                <td width="13%" class="text-left p-0"><b>LEVEL:</b></td>
                                <td width="18%" class="text-left p-0"><b>{{$student->levelname}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>SUBJECTS</b></td>
                                <td width="7%" class="text-left p-0"></td>
                                <td width="41%" class="text-right p-0"><b>TEACHER’S INITIALS </b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 10px!important;">
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>English</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Science</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Mathematics</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Araling Panlipunan</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>MAPEH/TLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0"><b>Others:</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="8%" class="text-left p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;font-size: 15px; padding-top: 20px!important; padding-bottom: 8px!important;">
                            <tr>
                                <td width="19%" class="text-left p-0"><b>&nbsp;ISSUED BY:</b></td>
                                <td width="33%" class="text-left p-0"><b>&nbsp;BRENDA B. TUBLE</b></td>
                                <td width="8%" class="text-left p-0"></td>
                                <td width="35%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="5%" class="text-left p-0"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>